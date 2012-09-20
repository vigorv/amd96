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

if (!$logged)
{
    stop_script ("Not logged in.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    stop_script ("Banned.");
}

if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
    stop_script ("Wrong secret key.");
}
else
{
    $lang_s_a_check_new_pm = language_forum ("board/scripts/ajax/check_new_pm");
    header( "Content-type: text/html; charset=".$LB_charset );
    
    $DB->prefix = DLE_USER_PREFIX;
    $DB->select( "*", "pm", "user = '{$member_id['user_id']}' AND pm_read = 'no'", "ORDER BY date DESC");
    $i = 0;
    $pm_list = "";
    while ( $row = $DB->get_row() )
    {
        $i ++;
        
        if( utf8_strlen( $row['subj'] ) > 30 )
            $row['subj'] = utf8_substr( $row['subj'], 0, 30 ) . "...";
        
        $lang_text = str_replace("{date}", formatdate($row['date']), $lang_s_a_check_new_pm['list']);
        $lang_text = str_replace("{name}", $row['user_from'], $lang_text);
        $lang_text = str_replace("{link}", pm_topics_link($row['id']), $lang_text);
        $lang_text = str_replace("{title}", $row['subj'], $lang_text);
        
        $pm_list .= $lang_text;
    }
    
    if (!$i)
        echo show_jq_message ("2", $lang_s_a_check_new_pm['mess_title_no'], $lang_s_a_check_new_pm['mess_info_no']);
    else
    {
        $time_open = 2000;
        if ($i > 2 AND $i < 5)
            $time_open = 3000;
        elseif ($i > 5 AND $i < 10)
            $time_open = 5000;
        elseif ($i > 10)
            $time_open = 10000;
            
        echo show_jq_message ("1", $lang_s_a_check_new_pm['mess_title_yes'], str_replace("{num}", $i, $lang_s_a_check_new_pm['mess_info_yes']).$pm_list, $time_open);
    }
    
    stop_script ();
}   

?>