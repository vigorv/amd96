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
    
if (isset($_GET['content_end']) AND intval($_GET['content_end']))
    exit ();

@session_start ();

if ($_SESSION['Get_Next_Post_Buttom'] == 1)
    exit();

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

include_once LB_CLASS. "/flood_recorder.php";
$LB_flood = new LB_Flood();

$LB_flood->loadpage = intval($cache_config['antiflood_loadpage']['conf_value']);
$LB_flood->load_interval = intval($cache_config['antiflood_load_interval']['conf_value']);

$LB_flood->buttom = intval($cache_config['antiflood_buttom']['conf_value']);
$LB_flood->interval = intval($cache_config['antiflood_interval']['conf_value']);
$LB_flood->block_time = intval($cache_config['antiflood_blocktime']['conf_value']);

$redirect_url = $cache_config['general_site']['conf_value'];
$onl_limit = $time - (intval($cache_config['online_time']['conf_value']) * 60);

require_once LB_GLOBAL . '/functions.php';
require_once LB_MAIN . '/components/scripts/bbcode/function.php';
require_once LB_GLOBAL . '/login.php';

if ($cache_config['antiflood_parse']['conf_value'])
{
    if ($LB_flood->isBlock("1"))
        stop_script ("Anti-flood system. Banned for ".$LB_flood->block_time." seconds.");
}

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
    stop_script ("Offline.");
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

$tid = intval($_GET['tid']);
    
if (!$tid)
{
    stop_script ("Topic ID is 0.");
}

$date_now = intval($_GET['date_now']);

if ($date_now > $time)
{
    stop_script ("Wrong time.".$date_now." >= ".$time);
}

if (!intval($cache_config['posts_check_new_auto']['conf_value']))
{
    stop_script ("Function is disabled.");
}

$check_date = $date_now + 1200;
if ($check_date < $time)
{
    stop_script ("Wrong time.");
}

$lang_s_a_cn_posts = language_forum ("board/scripts/ajax/check_new_posts");
$lang_message = language_forum ("board/lang_message");
header( "Content-type: text/html; charset=".$LB_charset );

$topic = $DB->one_select( "id, forum_id, hiden, status, member_id_open", "topics", "id = {$tid}" );

if ($topic['id'] AND !forum_permission($topic['forum_id'], "read_theme"))
{
    echo show_jq_message("3", $lang_s_a_cn_posts['access_denied'], $lang_s_a_cn_posts['access_denied_read']);
    stop_script();
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "read_forum"))
{
    echo show_jq_message("3", $lang_s_a_cn_posts['access_denied'], $lang_s_a_cn_posts['access_denied_forum']);
    stop_script();
}
elseif ($topic['id'] AND $topic['hiden'] AND !forum_options_topics($topic['forum_id'], "hideshow"))
{
    echo show_jq_message("3", $lang_s_a_cn_posts['access_denied'], $lang_s_a_cn_posts['access_denied_hide']);
    stop_script();
}
elseif ($topic['id'] AND forum_all_password($topic['forum_id']))
{
    echo show_jq_message("3", $lang_s_a_cn_posts['access_denied'], $lang_s_a_cn_posts['access_denied_pass']);
    stop_script();
}
elseif ($topic['status'] == "closed")
{
    stop_script ();
}
elseif($topic['id'])
{
    $where = "";
    $go_show = "";
    
    if(!forum_options_topics($topic['forum_id'], "hideshow"))
        $where = "AND hide = '0'";
    
    $go_end = 1;
    
    include LB_CLASS.'/posts_out.php';
    $LB_posts = new LB_posts;
    
    $DB->prefix = array ( 1 => DLE_USER_PREFIX );
    $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num", "LEFT", "posts p||users u||members_online mo", "p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", "topic_id = '{$tid}' AND post_date >= '{$date_now}' {$where}", "ORDER by post_date ASC" );
    $LB_posts->Data_out("board/topic_posts.tpl", "posts", $topic, true, true, false, false, true);
    
    unset($LB_posts);
    
    if (utf8_strlen($tpl->result['posts']) > 100)
        $go_end = 0;
    
    if (!$go_end)
    { 
        $tpl->global_tags ('posts');
        
        echo $tpl->result['posts'];
        echo show_jq_message ("1", $lang_s_a_cn_posts['new_posts_title'], $lang_s_a_cn_posts['new_posts_info'], 1800);     
    }
    
    $time_out_new_post = intval($cache_config['posts_check_new_auto']['conf_value']) * 1000;
    
    echo '
    <script type="text/javascript">
    date_now = "'.$time.'";
    $("div[id^=newpost-out-jq]").remove();
    </script>';     
}
else
{
    echo show_jq_message("3", $lang_s_a_cn_posts['not_found_title'], $lang_s_a_cn_posts['not_found_info']);
    stop_script();
}
        
stop_script ();

?>