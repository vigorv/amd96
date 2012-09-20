<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_CONTROL_CENTER', LB_MAIN . '/control_center' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads' );

require LB_CLASS . '/database.php';
include LB_CONFIG . '/board_db.php';

if (get_magic_quotes_gpc())
{
    include_once LB_CLASS. "/magic_quotes_gpc.php";
    $mq_gpc = new mq_gpc();
    $mq_gpc->del_slashes();
    unset($mq_gpc);  
}

require LB_CLASS . '/cache.php';
require LB_GLOBAL . '/creat_cache.php';

$_IP = $DB->addslashes( $_SERVER['REMOTE_ADDR'] );

include_once LB_CLASS. "/flood_recorder.php";
$LB_flood = new LB_Flood();

$LB_flood->loadpage = intval($cache_config['antiflood_loadpage']['conf_value']);
$LB_flood->load_interval = intval($cache_config['antiflood_load_interval']['conf_value']);

$LB_flood->buttom = intval($cache_config['antiflood_buttom']['conf_value']);
$LB_flood->interval = intval($cache_config['antiflood_interval']['conf_value']);
$LB_flood->block_time = intval($cache_config['antiflood_blocktime']['conf_value']);

require LB_CONTROL_CENTER . '/template/template.class.php';
$control_center = new Сontrol_Сenter ( );

$time_session = $cache_config['security_session']['conf_value'] * 3600;

$redirect_url = $cache_config['general_site']['conf_value']."control_center/";
$redirect_url_board = $cache_config['general_site']['conf_value'];

$banned_ip = false;
$banned_name = false;

require LB_GLOBAL . '/functions.php';
require LB_GLOBAL . '/cron.php';

$lang_message = language_forum ("control_center/lang_message");

unset ($LB_flood);

require_once LB_MAIN . '/components/scripts/bbcode/function.php';
require_once LB_GLOBAL . '/gzip.php';
microTimer_start();
require LB_CONTROL_CENTER . '/login.php';

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    require LB_CLASS . '/templates.php';
	$tpl = new LB_Template ( );
	$tpl->dir = LB_MAIN . '/templates/'.$cache_config['template_name']['conf_value'];
    
	require LB_MODULES . '/banned.php';
	exit ();
}

if ($logged AND $cache_group[$member_id['user_group']]['g_access_cc'] == "1")
	$logicboard_admin = true;
else
	$logicboard_admin = false;

if (isset ( $_REQUEST['do'] ))
	$do = totranslit ( strip_tags( $_REQUEST['do'] ), false );
else
	$do = "";

if (isset ( $_REQUEST['id'] ))
	$id = intval($_REQUEST['id']);
else
	$id = 0;
    
if (isset ( $_REQUEST['op'] ))
	$op = totranslit ( strip_tags( $_REQUEST['op'] ), false );
else
	$op = "";
    
function control_center_admins($option = 0)
{
    global $member_cca;
    
    if(!$member_cca OR $option == 1)
        return true;
    else
        return false;
}

function control_center_admins_error($text = "Вам запрещён доступ к данному разделу центра управления.")
{
    global $control_center;
    $control_center->errors = array ();
    $control_center->errors[] = $text;
    $control_center->errors_title = "Доступ закрыт.";
    $control_center->message();
}

$onl_location = "Не определено";

if ($logicboard_admin)
{    
	switch ($do)
	{
		case "configuration":
			$onl_location = "Настройки";
			require LB_CONTROL_CENTER . '/configuration.php';
		break;

		case "system":
			$onl_location = "Система";
			require LB_CONTROL_CENTER . '/info.php';
		break;

		case "logs":
			$onl_location = "Журнал логов";
			require LB_CONTROL_CENTER . '/logs.php';
		break;

		case "infopage":
			require LB_CONTROL_CENTER . '/infopage.php';
		break;

		case "users":
			$onl_location = "Пользователи";
			require LB_CONTROL_CENTER . '/users.php';
		break;

		case "board":
			require LB_CONTROL_CENTER . '/board.php';
		break;
        
        case "complaint":
			require LB_CONTROL_CENTER . '/complaint.php';
		break;

		case "staticpage":
            if(control_center_admins($member_cca['modules']['staticpage']))
                 require LB_CONTROL_CENTER . '/staticpage.php';
            else
            {
                $control_center->header("Статические страницы", "Статические страницы");
                $onl_location = "Статические страницы";
                control_center_admins_error();
                $control_center->footer(7);
            }
		break;
        
        case "rules":
            if(control_center_admins($member_cca['modules']['rules']))
                require LB_CONTROL_CENTER . '/rules.php';
            else
            {
                $control_center->header("Правила форума", "Правила форума");
                $onl_location = "Правила форума";
                control_center_admins_error();
                $control_center->footer(7);
            }
		break;
        
        case "adt":
            if(control_center_admins($member_cca['modules']['adt']))
                 require LB_CONTROL_CENTER . '/adt.php';
            else
            {
                $control_center->header("Блоки и реклама", "Блоки и реклама");
                $onl_location = "Блоки и реклама";
                control_center_admins_error();
                $control_center->footer(7);
            }
		break;

		default :
			$onl_location = "Главная";
			require LB_CONTROL_CENTER . '/main.php';
		break;
	}

	$onl_ip = $DB->addslashes( $_SERVER['REMOTE_ADDR'] );	
	$onl_location = $DB->addslashes($onl_location);

    $lastdate_online = $time - 300;
    $DB->delete("lastdate_online < '{$lastdate_online}'", "session_cc");
    $DB->insert("lastdate_online ='{$time}', location_online = '{$onl_location}', ip_online = '{$onl_ip}', member_id_online = '{$member_id['user_id']}' ON DUPLICATE KEY UPDATE lastdate_online ='{$time}', location_online = '{$onl_location}', ip_online = '{$onl_ip}', member_id_online = '{$member_id['user_id']}'", "session_cc");
}

?>