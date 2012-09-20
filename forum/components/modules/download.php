<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

@session_start ();

@error_reporting ( E_ERROR );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LB_MAIN', realpath("../../") );

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads' );

require_once LB_CLASS . '/database.php';
include_once LB_CONFIG . '/board_db.php';

$_IP = $_SERVER['REMOTE_ADDR'];

require_once LB_CLASS . '/cache.php';
require_once LB_GLOBAL . '/creat_cache.php';

$redirect_url = $cache_config['general_site']['conf_value'];

require_once LB_GLOBAL . '/functions.php';
require_once LB_GLOBAL . '/login.php';
require_once LB_CLASS . '/download.php';

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
    $DB->close();
	exit ("Offline.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    $DB->close();
	exit ("Banned.");
}

if (!$cache_config['upload_download']['conf_value'])
{
    $DB->close();
	die ( "Access denied." );
}
    
if ($cache_config['upload_antileech']['conf_value'])
{
	$_SERVER['HTTP_REFERER'] = clean_url($_SERVER['HTTP_REFERER']);
	$_SERVER['HTTP_HOST'] = clean_url($_SERVER['HTTP_HOST']);
	if ($_SERVER['HTTP_HOST'] != $_SERVER['HTTP_REFERER'])
    {
		header ( "Location: ".$redirect_url );
        $DB->close();
		die ( "Antileech." );
	}

}

$id = intval($_GET['id']);

if (!$id)
{
    $DB->close();
    die ( "File not checked." );   
}

$file_db = $DB->one_select( "*", "topics_files", "file_id = '{$id}'" );

if (!$file_db['file_id'])
{
    $DB->close();
    die ( "File not found." );   
}

if (!forum_permission($file_db['file_fid'], "download_files"))
{
    $DB->close();
    die ( "Access denied." );   
}

$dir_name = date( "Y-m", $file_db['file_date'] );

$real_name = $file_db['file_name'];

if ($cache_config['upload_realname']['conf_value'])
{
    $quotes = array ("/", "\\", ":", "*", "?", "<", ">", "|", '"');
    $real_name = str_replace( $quotes, '', $file_db['file_title'] );
}

if ($file_db['file_convert'] == "1" AND $cache_config['upload_convert']['conf_value'])
    header ("location: ".$cache_config['upload_convert']['conf_value'].$file_db['file_name']);
else
    $file = new download ( LB_UPLOADS."/attachment/".$dir_name."/".$file_db['file_name'], $real_name, $cache_config['upload_force']['conf_value'], $cache_config['upload_speed']['conf_value'] );

if ($cache_config['upload_count']['conf_value'] == 1)
    $DB->update("file_count = file_count + 1", "topics_files", "file_id='{$id}'");

$file->download_file();

$db->close ();

?>