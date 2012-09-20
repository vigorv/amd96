<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads' );

if(!@file_exists(LB_CONFIG . '/board_db.php')) exit ("LogicBoard not installed. Please run install.");

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
$rss_link = "";

$banned_ip = false;
$banned_name = false;

require_once LB_GLOBAL . '/functions.php';
require LB_GLOBAL . '/cron.php';

$lang_message = language_forum ("board/lang_message");

require_once LB_MAIN . '/components/scripts/bbcode/function.php';
require_once LB_GLOBAL . '/gzip.php';
microTimer_start();

if (isset($_POST['change_template']) AND $cache_config['general_template']['conf_value'])
{
	$_POST['skin_name'] = trim(totranslit($_POST['skin_name'], false));

	if ($_POST['skin_name'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_POST['skin_name']))
    {
		$cache_config['template_name']['conf_value'] = $_POST['skin_name'];
        update_cookie( "LB_template", $_POST['skin_name'], 365 );
  	}
}
elseif (isset($_GET['LB_template']) AND $cache_config['general_template']['conf_value'])
{
	$_GET['LB_template'] = trim(totranslit($_GET['LB_template'], false));

	if ($_GET['LB_template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_GET['LB_template']))
    {
		$cache_config['template_name']['conf_value'] = $_GET['LB_template'];
        update_cookie( "LB_template", $_GET['skin_name'], 365 );
  	}
}
elseif (isset($_COOKIE['LB_template']) AND $cache_config['general_template']['conf_value'])
{
	$_COOKIE['LB_template'] = trim(totranslit($_COOKIE['LB_template'], false));

	if ($_COOKIE['LB_template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_COOKIE['LB_template']))
		$cache_config['template_name']['conf_value'] = $_COOKIE['LB_template'];
}

if (!isset($_COOKIE['LB_forums_read']))
{
    $_COOKIE['LB_forums_read'] = "";
}

require_once LB_CLASS . '/templates.php';
$tpl = new LB_Template ( );
$tpl->dir = LB_MAIN . '/templates/'.$cache_config['template_name']['conf_value'];

require_once LB_GLOBAL . '/login.php';
require_once LB_GLOBAL . '/login_template.php';

if ($cache_config['antiflood_parse']['conf_value'])
{
    if ($LB_flood->isBlock("1"))
        exit ("Anti-flood system. Banned for ".$LB_flood->block_time." seconds.");
}

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
	require LB_MODULES . '/offline.php';
	exit ();
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
	require LB_MODULES . '/banned.php';
	exit ();
}

if (isset($_GET['clear_cookie']))
{
    if (!$logged OR ($logged AND $_GET['sk'] == $secret_key))
        clear_cookie();
        
    header( "Location: {$redirect_url}" );
	exit();    
}

if (isset($_GET['last_news_close']))
{
    if (!$logged OR ($logged AND $_GET['sk'] == $secret_key))
        update_cookie( "LB_last_news", $time, 20 );
                
    header( "Location: {$redirect_url}" );
	exit();    
}

if (isset($_GET['all_tf_read']) AND $logged)
{
    if ($_GET['sk'] == $secret_key)
    {
        $view_topic = array();
        $view_topic['all'] = $time;
        $view_topic = $DB->addslashes(serialize($view_topic));
        
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("view_topic = '{$view_topic}'", "users", "user_id = '{$member_id['user_id']}'");
        update_cookie( "LB_forums_read_all", $time, 365 );
        update_cookie( "LB_forums_read", "", 365 );
    }
        
    header( "Location: {$redirect_url}" );
	exit();    
}

$onl_limit = $time - (intval($cache_config['online_time']['conf_value']) * 60);

$meta_info_text = "";
$meta_info_other = "";
$meta_info_forum = false;

if (isset ( $_REQUEST['do'] ))
	$do = totranslit ( strip_tags( $_REQUEST['do'] ), false );
else
	$do = "";
    
if (isset ( $_REQUEST['op'] ))
	$op = totranslit ( strip_tags( $_REQUEST['op'] ), false );
else
	$op = "";

if (isset ( $_REQUEST['id'] ))
	$id = intval($_REQUEST['id']);
else
	$id = 0;
    
if (isset ( $_REQUEST['member_name'] ))
{
    $_REQUEST['member_name'] = filters_input_one( urldecode($_REQUEST['member_name']) );  
	$member_name = $DB->addslashes(strip_tags($_REQUEST['member_name']));
}
else
	$member_name = "";  
    
if (isset($_REQUEST['chpu_message']))
{
    message ($lang_message['information'], $lang_message['chpu_error']);
}
    
switch ($do)
{   
	case "staticpage":
		include_once LB_MODULES . '/staticpage.php';
	break;
    
   	case "users":
		include_once LB_MODULES . '/users.php';
	break;
    
   	case "rules":
		include_once LB_MODULES . '/rules.php';
	break;
    
    case "feedback":
		include_once LB_MODULES . '/feedback.php';
	break;
    
    case "search":
		include_once LB_MODULES . '/search.php';
	break;
    
    case "system_info":
		include_once LB_MODULES . '/system_info.php';
	break;
       
	default :
        $do = "board";
		include_once LB_MODULES . '/board.php';
	break;
}

if ($cache_config['online_status']['conf_value'])
{
    $onl_location = $DB->addslashes($onl_location);
    $onl_browser = $DB->addslashes($_SERVER['HTTP_USER_AGENT']);
    $onl_session = md5( uniqid( microtime(), 1 ).$_IP );
    if (!isset($mo_loc_fid)) $mo_loc_fid = 0;
        
    if ($logged)
    {
        if ($member_options['online']) $mo_hide = 1;
        else $mo_hide = 0;
        
        $DB->delete("mo_member_id = '{$member_id['user_id']}'", "members_online");
        $DB->insert("mo_id = '{$onl_session}', mo_member_id = '{$member_id['user_id']}', mo_member_name = '{$member_id['name']}', mo_member_group = '{$member_id['user_group']}', mo_ip = '{$_IP}', mo_date = '{$time}', mo_browser = '{$onl_browser}', mo_location = '{$onl_location}', mo_loc_do = '{$do}', mo_loc_op = '{$op}', mo_loc_id = '{$id}', mo_hide = '{$mo_hide}', mo_loc_fid = '{$mo_loc_fid}'", "members_online");
    }
    else
    {
        $DB->delete("mo_ip = '{$_IP}'", "members_online");  
        $DB->insert("mo_id = '{$onl_session}', mo_member_name = '', mo_member_group = '5', mo_ip = '{$_IP}', mo_date = '{$time}', mo_browser = '{$onl_browser}', mo_location = '{$onl_location}', mo_loc_do = '{$do}', mo_loc_op = '{$op}', mo_loc_id = '{$id}', mo_hide = '0', mo_loc_fid = '{$mo_loc_fid}' ON DUPLICATE KEY UPDATE mo_id = '{$onl_session}', mo_member_name = '', mo_member_group = '5', mo_ip = '{$_IP}', mo_date = '{$time}', mo_browser = '{$onl_browser}', mo_location = '{$onl_location}', mo_loc_do = '{$do}', mo_loc_op = '{$op}', mo_loc_id = '{$id}', mo_hide = '0', mo_loc_fid = '{$mo_loc_fid}'", "members_online");
    }
}
?>