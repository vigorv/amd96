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
    $ajax_unicode->input('get');
    unset($ajax_unicode);
}

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


filters_input ('get');

$id = intval($_GET['id']);
$module = strtolower($_GET['module']); // нужно для CMS Edition, будет проверка других значений
$text = parse_word($_GET['text'], false);

if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
    stop_script ("Wrong secret key.");
}
elseif (!$id)
{
    stop_script ("No ID.");
}
elseif ($module != "post")
{
    stop_script ("Wrong module name.");
}
elseif (!$text)
{
    stop_script ("No text.");
}
else
{
    $lang_s_a_complaint = language_forum ("board/scripts/ajax/complaint");
    header( "Content-type: text/html; charset=".$LB_charset );
    
    function check_access_fav($f_id = 0, $hide = 0)
    {
        global $lang_s_a_complaint;
        
        $message = "";
        
        if(!forum_permission($f_id, "read_forum"))
            $message = $lang_s_a_complaint['access_denied_forum'];
        elseif (!forum_permission($f_id, "read_theme"))
            $message = $lang_s_a_complaint['access_denied_read'];
        elseif(forum_all_password($f_id))
            $message = $lang_s_a_complaint['access_denied_pass'];
        elseif(!forum_options_topics($f_id, "hideshow") AND $hide)
            $message = $lang_s_a_complaint['access_denied_hide'];
        return $message;        
    }    
    
    $topic = $DB->one_join_select( "p.pid, p.post_member_id, p.post_member_name, p.post_date, t.id, t.forum_id, t.hiden, t.title", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid = '{$id}'");
    if (!$topic['id'])
        echo show_jq_message ("3", $lang_s_a_complaint['error_title'], $lang_s_a_complaint['error_info']);
    elseif(check_access_fav($topic['forum_id'], $topic['hiden']))
        echo show_jq_message ("3", $lang_s_a_complaint['error_title'], check_access_fav ($topic['forum_id'], $topic['hiden']));
    elseif($topic['post_member_id'] == $member_id['user_id'])
        echo show_jq_message ("3", $lang_s_a_complaint['error_title'], $lang_s_a_complaint['error_post_member_id']);
    elseif (utf8_strlen($text) > 2000)
        echo show_jq_message ("3", $lang_s_a_complaint['error_title'], $lang_s_a_complaint['text_max']);
    else
    {
        $error = "";
        
        $date_log = $time - (60*60);
        $log_compl = $DB->one_select( "id", "complaint", "cid = '{$id}' AND mid = '{$member_id['user_id']}' AND date >= '{$date_log}'");
        
        if ($log_compl['id']) $error = $lang_s_a_complaint['time_limit'];
        
        if ($error)
            echo show_jq_message ("3", $lang_s_a_complaint['error_title'], $error);
        else
        {
            $text = $DB->addslashes($text);
            $module = $DB->addslashes($module);
            $DB->insert("module = '{$module}', cid = '{$id}', mid = '{$member_id['user_id']}', ip = '{$_IP}', date = '{$time}', info = '{$text}'", "complaint");
             
            $forum_moders_u = array();
            $forum_moders_g = array();
            if (count($cache_forums_moder))
            {
                foreach ($cache_forums_moder as $moder_list)
                {
                    if ($moder_list['fm_forum_id'] == $topic['forum_id'])
                    {
                        if ($moder_list['fm_member_id'])
                            $forum_moders_u[] = $moder_list['fm_member_id'];
                        else
                            $forum_moders_g[] = $moder_list['fm_group_id'];
                    }
                }
            }
            
            $send_moder = false;
            $send_super_moder = false;
            
            $text_pm = str_replace("{name}", $member_id['name'], $lang_s_a_complaint['pm_text']);
            $text_pm = str_replace("{id}", $id, $text_pm);
            $text_pm = str_replace("{post_name}", $topic['post_member_name'], $text_pm);
            $text_pm = str_replace("{post_date}", formatdate($topic['post_date']), $text_pm);
            $text_pm = str_replace("{topic_link}", topic_link($topic['id'], $topic['forum_id']), $text_pm);
            $text_pm = str_replace("{topic_title}", $topic['title'], $text_pm);
            $text_pm = str_replace("{text}", $text, $text_pm);
            
            $text_pm = $DB->addslashes($text_pm);
            $titile_pm = $DB->addslashes(str_replace("{title}", $topic['title'], $lang_s_a_complaint['pm_title']));
            
            function complaint_send($where = "")
            {
                global $DB, $titile_pm, $text_pm;
                
                if (!$where) return false;
                
                $status = false;
                $DB->prefix = DLE_USER_PREFIX;
                $DB->select( "user_id, email, name, mf_options", "users", $where);
                while ( $row = $DB->get_row() )
                {
                    $status = true;
                    send_new_pm ($titile_pm, $row['user_id'], $text_pm, $row['email'], $row['name'], $row['mf_options'], true);
                }
                $DB->free();
                
                return $status;
            }
            
            if (count($forum_moders_u))
            {
                $forum_moders_u = implode (",", $forum_moders_u);
                $send_moder = complaint_send ("user_id IN (".$forum_moders_u.")");
            }
            
            if (count($forum_moders_g))
            {
                $forum_moders_g = implode (",", $forum_moders_g);
                $send_moder = complaint_send ("user_group IN (".$forum_moders_g.")");
            }
            
            if (!$send_moder OR $cache_config['complaint_moders']['conf_value'])
            {
                unset ($forum_moders_g);
                $forum_moders_g = array();
                foreach ($cache_group as $value)
                {
                    if ($value['g_supermoders'] AND $value['g_id'] != 1) $forum_moders_g[] = $value['g_id'];
                }
                
                if (count($forum_moders_g))
                {
                    $forum_moders_g = implode (",", $forum_moders_g);
                    $send_super_moder = complaint_send ("user_group IN (".$forum_moders_g.")");
                }
            }
            
            if ((!$send_moder AND !$send_super_moder) OR $cache_config['complaint_admins']['conf_value'])
            {
                complaint_send ("user_group = '1'");
            }

            echo show_jq_message ("1", $lang_s_a_complaint['done_title'], $lang_s_a_complaint['done_info']);
        }
    }
    
    stop_script ();
}   

?>