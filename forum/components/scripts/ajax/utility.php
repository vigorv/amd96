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

if (!$logged AND !$cache_config['posts_utility']['conf_value'])
{
    stop_script ("Not logged in.");
}

$banned_ip = LB_banned("ip", $_IP);

if ($logged)
    $banned_name = LB_banned("user_id", $member_id['user_id']);
else
    $banned_name = false;
    
if ($banned_ip OR $banned_name)
{
    stop_script ("Banned.");
}

if ($logged AND (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key))
{
    stop_script ("Wrong secret key.");
}
else
{
    $lang_s_a_utility = language_forum ("board/scripts/ajax/utility");
    header( "Content-type: text/html; charset=".$LB_charset );
    
    function check_access_fav($f_id = 0, $hide = 0)
    {
        global $lang_s_a_utility;
        
        $message = "";
        
        if(!forum_permission($f_id, "read_forum"))
            $message = $lang_s_a_utility['access_denied_forum'];
        elseif (!forum_permission($f_id, "read_theme"))
            $message = $lang_s_a_utility['access_denied_read'];
        elseif(forum_all_password($f_id))
            $message = $lang_s_a_utility['access_denied_pass'];
        elseif(!forum_options_topics($f_id, "hideshow") AND $hide)
            $message = $lang_s_a_utility['access_denied_hide'];
        return $message;        
    }    
    
    $id = intval($_GET['pid']);
    
    $topic = $DB->one_join_select( "p.pid, p.post_member_id, p.utility, p.ip, t.id, t.forum_id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid = '{$id}'");
    if (!$topic['id'])
        echo "0".show_jq_message ("3", $lang_s_a_utility['error_title'], $lang_s_a_utility['error_info']);
    elseif(check_access_fav($topic['forum_id'], $topic['hiden']))
        echo $topic['utility'].show_jq_message ("3", $lang_s_a_utility['error_title'], check_access_fav ($topic['forum_id'], $topic['hiden']));
    elseif(($logged AND $topic['post_member_id'] == $member_id['user_id']) OR (!$logged AND $topic['ip'] == $_IP))
        echo $topic['utility'].show_jq_message ("3", $lang_s_a_utility['error_title'], $lang_s_a_utility['error_post_member_id']);
    else
    {
        $error = "";
        
        if ($logged)
            $log_utility = $DB->one_select( "pid", "posts_utility", "pid = '{$id}' AND mid = '{$member_id['user_id']}'");
        else
        {
            $log_utility = $DB->one_select( "pid", "posts_utility", "pid = '{$id}' AND ip = '{$_IP}'");
            $member_id['user_id'] = 0;
        }
        
        if ($log_utility['pid'])
            $error = $lang_s_a_utility['log_checked'];
        
        if ($error)
            echo $topic['utility'].show_jq_message ("3", $lang_s_a_utility['error_title'], $error);
        else
        {
            $DB->update("utility = utility+1", "posts", "pid = '{$id}'");
            $DB->insert("pid = '{$id}', mid = '{$member_id['user_id']}', ip = '{$_IP}'", "posts_utility");
            
            $topic['utility'] += 1;
             
            echo $topic['utility'].show_jq_message ("1", $lang_s_a_utility['done_title'], $lang_s_a_utility['done_info']);
        }
    }
    
    stop_script ();
}   

?>