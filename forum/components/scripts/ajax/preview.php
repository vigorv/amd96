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

if ($cache_config['general_coding']['conf_value'] != "utf-8")
{
    require_once LB_CLASS . '/ajax_data.php';
    $ajax_unicode = new ajax_unicode;
    $ajax_unicode->input('post');
    unset($ajax_unicode);
}

$redirect_url = $cache_config['general_site']['conf_value'];
require_once LB_GLOBAL . '/functions.php';

require_once LB_GLOBAL . '/login.php';

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
    stop_script("Offline.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    stop_script("Banned.");
}

require_once LB_MAIN . '/components/scripts/bbcode/function.php';

if (isset($_POST['template']) AND $cache_config['general_template']['conf_value'])
{
	$_POST['template'] = trim(totranslit($_POST['template'], false));

	if ($_POST['template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_POST['template']))
    {
		$cache_config['template_name']['conf_value'] = $_POST['template'];
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

header( "Content-type: text/html; charset=".$LB_charset );

$_POST['text'] = htmlspecialchars($_POST['text']);
filters_input ('post');
$text = parse_word(html_entity_decode($_POST['text']));

$tpl->load_template ( 'preview.tpl' );
                                                        
if (strpos($text, "[attachment=") !== false)
{                            
    $text = show_attach ($text, "0");
}

$text = hide_in_post($text, $member['member_id']);

$small_img = "<script type=\"text/javascript\">Resize_img();</script>";

$tpl->tags('{text}', $text.$small_img);
$tpl->compile('preview');
$tpl->clear();
    
$tpl->global_tags ('preview');
echo $tpl->result['preview'];

stop_script();

?>