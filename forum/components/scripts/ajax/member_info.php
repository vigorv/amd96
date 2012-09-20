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

$redirect_url = $cache_config['general_site']['conf_value'];
$onl_limit = $time - (intval($cache_config['online_time']['conf_value']) * 60);

require_once LB_GLOBAL . '/functions.php';
require_once LB_GLOBAL . '/login.php';

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

$mid = intval($_GET['mid']);
    
if (!$mid)
{
    stop_script ("User not found.");
}
    
$lang_message = language_forum ("board/lang_message");
header( "Content-type: text/html; charset=".$LB_charset );
    
$DB->prefix = array( 0 => DLE_USER_PREFIX );
$row = $DB->one_join_select( "u.name, u.user_group, u.user_id, u.banned, u.logged_ip, u.posts_num, u.count_warning, u.topics_num, u.reg_date, u.lastdate, u.personal_title, mo.*, s.text, s.date", "LEFT", "users u||members_status s||members_online mo", "u.mstatus=s.id||u.user_id=mo.mo_member_id", "u.user_id='{$mid}'", "LIMIT 1" );
    
if (!$row['user_id'])
{
    stop_script ("User not found.");
}

$tpl->load_template ( 'member_info.tpl' );
    
$tpl->tags('{member_name}', $row['name']);
$tpl->tags('{member_group}', member_group($row['user_group'], $row['banned']));
$tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
$tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
$tpl->tags('{topics_link}', member_topics_link($row['name'], $row['user_id']));
$tpl->tags('{posts_link}', member_posts_link($row['name'], $row['user_id']));
$tpl->tags('{member_posts}', $row['posts_num']);
$tpl->tags('{reg_date}', formatdate($row['reg_date']));
$tpl->tags('{lastdate}', formatdate($row['lastdate']));
$tpl->tags('{personal_title}', $row['personal_title']);
$tpl->tags('{topics_num}', $row['topics_num']);

if (intval($cache_config['forums_unitetp']['conf_value']))
    $tpl->tags('{posts_num}', $row['posts_num'] + $row['topics_num']);
else
    $tpl->tags('{posts_num}', $row['posts_num']);

$tpl->tags_blocks("online", member_online($row['mo_id'], $row['mo_date'], $onl_limit));
$tpl->tags_blocks("offline", member_online($row['mo_id'], $row['mo_date'], $onl_limit), true);

if ($cache_config['warning_on']['conf_value'] AND !$cache_group[$row['user_group']]['g_warning'])
{
    $tpl->tags_blocks("warning");
    if ($cache_config['warning_show']['conf_value'])
        $tpl->tags('{count_warning}', "<a href=\"".warning_link($row['name'], $row['user_id'])."\">".$row['count_warning']."</a>");
    else
        $tpl->tags('{count_warning}', $row['count_warning']);
}
else
    $tpl->tags_blocks("warning", false);

if ($cache_config['warning_on']['conf_value'] AND $cache_group[$member_id['user_group']]['g_warning'] AND !$cache_group[$row['user_group']]['g_warning'] AND $row['user_group'] != 1)
{
    $tpl->tags_blocks("moder_warning");
    $tpl->tags('{link_warning}', warning_link($row['name'], $row['user_id'], 1));
}
else
    $tpl->tags_blocks("moder_warning", false);
    
if (forum_moderation())
{
    $tpl->tags_blocks("ip");
    if ($cache_config['security_adminip']['conf_value'] AND $row['user_group'] == "1")
        $tpl->tags('{ip}', $lang_message['ip_hide']);
    else
        $tpl->tags('{ip}', "<a href=\"".$cache_config['general_site']['conf_value']."control_center/?do=users&op=tools&ip=".$row['logged_ip']."\" target=\"blank\">".$row['logged_ip']."</a>");
}
else
    $tpl->tags_blocks("ip", false);

$tpl->compile('member_info');
$tpl->clear();

$tpl->global_tags ('member_info');   
echo $tpl->result['member_info'];

stop_script ();

?>