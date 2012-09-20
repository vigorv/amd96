<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

$type_mod = $_GET['type_mod'];

if ($type_mod != "online" AND $type_mod != "birthday")
    exit ("Error.");

@session_start ();

@error_reporting ( E_ERROR );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LB_MAIN', realpath("../../../") );

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads/' );

require_once LB_CLASS . '/database.php';
include_once LB_CONFIG . '/board_db.php';

if (get_magic_quotes_gpc())
{
    include_once LB_CLASS. "/magic_quotes_gpc.php";
    $mq_gpc = new mq_gpc();
    $mq_gpc->del_slashes();
    unset($mq_gpc);  
}

$_IP = $_SERVER['REMOTE_ADDR'];

require_once LB_CLASS . '/cache.php';
require_once LB_GLOBAL . '/creat_cache.php';

if ($cache_config['general_coding']['conf_value'] != "utf-8")
{
    require_once LB_CLASS . '/ajax_data.php';
    $ajax_unicode = new ajax_unicode;
    $ajax_unicode->input('get');
    unset($ajax_unicode);
}

include_once LB_CLASS. "/flood_recorder.php";
$LB_flood = new LB_Flood();

$LB_flood->loadpage = intval($cache_config['antiflood_loadpage']['conf_value']);
$LB_flood->load_interval = intval($cache_config['antiflood_load_interval']['conf_value']);

$LB_flood->buttom = intval($cache_config['antiflood_buttom']['conf_value']);
$LB_flood->interval = intval($cache_config['antiflood_interval']['conf_value']);
$LB_flood->block_time = intval($cache_config['antiflood_blocktime']['conf_value']);

$redirect_url = $cache_config['general_site']['conf_value'];
$onl_limit = $time - (intval($cache_config['online_time']['conf_value']) * 60);

if (!$cache_config['online_status']['conf_value'])
{
    stop_script("Access denied.");
}

require_once LB_GLOBAL . '/functions.php';
require_once LB_MAIN . '/components/scripts/bbcode/function.php';
require_once LB_GLOBAL . '/login.php';

if (!$cache_group[$member_id['user_group']]['g_show_online'])
{
    stop_script("Access denied.");
}

if ($cache_config['antiflood_parse']['conf_value'])
{
    if ($LB_flood->isBlock("1"))
        stop_script("Anti-flood system. Banned for ".$LB_flood->block_time." seconds.");
}

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
    stop_script("Offline.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    stop_script ("Banned.");
}

if (isset($_GET['template']) AND $cache_config['general_template']['conf_value'])
{
	$_GET['template'] = trim(totranslit($_GET['template'], false));

	if ($_GET['template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_GET['template']))
    {
		$cache_config['template_name']['conf_value'] = $_GET['template'];
  	}
}
elseif (isset($_COOKIE['LB_template']) AND $cache_config['general_template']['conf_value'])
{
	$_COOKIE['LB_template'] = trim(totranslit($_COOKIE['LB_template'], false));

	if ($_COOKIE['LB_template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_COOKIE['LB_template']))
		$cache_config['template_name']['conf_value'] = $_COOKIE['LB_template'];
}

require_once LB_CLASS . '/templates.php';
$tpl = new LB_Template ( );
$tpl->dir = LB_MAIN . '/templates/'.$cache_config['template_name']['conf_value'];
    
$lang_s_a_block_stats = language_forum ("board/scripts/ajax/block_stats");
header( "Content-type: text/html; charset=".$LB_charset );

if ($type_mod == "online")
{
    $do = totranslit ( strip_tags( $_GET['g_do'] ), false );
    $op = totranslit ( strip_tags( $_GET['g_op'] ), false );
    $id = intval($_GET['g_id']);
    
    if($do == "board" AND $op == "forum")
    {
        $online = online_members(intval($cache_config['online_limitblock']['conf_value']), "all", "board", "forum", $id);
    }
    elseif($do == "board" AND ($op == "topic" OR $op == "reply"))
    {
        $online = online_members(intval($cache_config['online_limitblock']['conf_value']), "all", "board", $op, $id);
    }
    else
    {
        $online = online_members(intval($cache_config['online_limitblock']['conf_value']));
    }
    
    $tpl->load_template( 'statistic_ajax.tpl' );
    
    $tpl->tags_blocks("group_online");
    $tpl->tags( '{online_list_action}', online_link_list("action") );
    $tpl->tags( '{online_list_name}', online_link_list("name") );
    $tpl->tags_blocks("birthday", false);
    
    if($do == "board" AND $op == "forum")
    { 
        $tpl->tags( '{online_title}', $lang_s_a_block_stats['this_forum'] );
    }
    elseif($do == "board" AND ($op == "topic" OR $op == "reply"))
    {
        $tpl->tags( '{online_title}', $lang_s_a_block_stats['this_topic'] );
    }
    else
    {
        $tpl->tags( '{online_title}', $lang_s_a_block_stats['forum'] );
    }
    
    list($onl_g, $onl_u, $onl_a, $onl_h, $list) = $online;  
    $tpl->tags( '{online_members}', $list );
    $tpl->tags( '{online_i_u}', $onl_u );
    $tpl->tags( '{online_i_g}', $onl_g );
    $tpl->tags( '{online_i_a}', $onl_a );
    $tpl->tags( '{online_i_h}', $onl_h );
    $tpl->tags( '{online_limit}', intval($cache_config['online_time']['conf_value']) );
    
    $tpl->compile( 'statistic' );
    $tpl->global_tags ('statistic');
    
    echo "<div style=\"display:none;\" id=\"statsblock_online_ajax\">
    <script type=\"text/javascript\">
    $(document).ready(function() {
        $('a.a_b_s').tooltip({
            track: true,
            delay: 0,
            showURL: false,
            fade: 200
        });    
    });
    </script>
    ".$tpl->result['statistic']."</div>";
}
elseif ($type_mod == "birthday")
{
    $cache_birthday = $cache->take("birthday");
    if (!$cache_birthday AND $cache_birthday != "no_cache")
    {
        $b_day = date ("d", $time);
        $b_month = date ("m", $time);
        
        $DB->prefix = DLE_USER_PREFIX;
        $today = $DB->select( "lb_b_day, lb_b_month, lb_b_year, name, user_id, user_group, banned", "users", "lb_b_day='{$b_day}' AND lb_b_month = '{$b_month}'", "ORDER BY lb_b_year ASC" );
        $cache_birthday = array ();
        while ( $row = $DB->get_row($today) )
        {
            $cache_birthday[$row['user_id']] = array ();
            foreach ($row as $key => $value)
                $cache_birthday[$row['user_id']][$key] = $value;
        }
        $DB->free($today);
        if (count($cache_birthday))
        {
            $cache->update("birthday", $cache_birthday);
            $cache_birthday = $cache->take("birthday");
        }
        else
            $cache->update("birthday", "no_cache");
    }
    elseif ($cache_birthday == "no_cache")
    {
        unset($cache_birthday);
        $cache_birthday = array();
    }
    
    $tpl->load_template( 'statistic_ajax.tpl' );
    $tpl->tags_blocks("group_online", false);

    if (count($cache_birthday))
    {
        $tpl->tags_blocks("birthday");
        $tpl->tags( '{birthday_all}', count($cache_birthday) );
        $birthday_list = "";
        foreach($cache_birthday as $today)
        {
            $date_now_year = date("Y", $time);
            $date_now_day = date("d", $time); 
            $date_now_month = date("m", $time);

            $age_user_day = $today['lb_b_day'] - $date_now_day;
            $age_user_month = $today['lb_b_month'] - $date_now_month;

            if (($age_user_month < 0 AND $age_user_day < 0) OR ($age_user_month < 0 AND $age_user_day >= 0))
                $age_user = $date_now_year - $today['lb_b_year'];
            elseif (($age_user_month == 0 AND $age_user_day < 0) OR ($age_user_day == 0 AND $age_user_month == 0))
                $age_user = $date_now_year - $today['lb_b_year'];
            else
                $age_user = $date_now_year - 1 - $today['lb_b_year'];
            
            if ($today['banned'])
                $member_name = "<font color=gray>".$today['name']." (".$age_user.")</font>";
            else
                $member_name = $cache_group[$today['user_group']]['g_prefix_st'].$today['name']." <font color=black><b>(".$age_user.")</b></font>".$cache_group[$today['user_group']]['g_prefix_end'];
            
            $min_profile_icon = "<a href=\"#\" onclick=\"ProfileInfo(this, '".$today['user_id']."');return false;\"><img src=\"{TEMPLATE}/images/profile_window_icon.png\" alt=\"mini-profile\" /></a>";
            
            
            if (!$birthday_list)
                $birthday_list = "<li><a href=\"".profile_link($today['name'], $today['user_id'])."\">".$member_name."</a> ".$min_profile_icon."</li>";
            else
                $birthday_list .= "<li>, <a href=\"".profile_link($today['name'], $today['user_id'])."\">".$member_name."</a> ".$min_profile_icon."</li>";
        }
        $tpl->tags( '{birthday_list}', $birthday_list );
    }
    else
        $tpl->tags_blocks("birthday", false);
    
    $tpl->compile( 'statistic' );
    $tpl->global_tags ('statistic');
    
    echo "<div style=\"display:none;\" id=\"statsblock_birthday_ajax\">".$tpl->result['statistic']."</div>";
}

stop_script();

?>