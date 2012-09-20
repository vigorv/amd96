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
    stop_script("Banned.");
}

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

$id = intval($_POST['id']);
    
if (!$id)
{
    stop_script("Post ID is 0.");
}
elseif ($_POST['act'] == "")
{
    stop_script("No action.");
}
elseif (!$logged)
{
    stop_script("Not logged.");
}

function return_text()
{
    global $post;
    
    if (!isset($post['text']))
        $post['text'] = "Error.";
    
    return $post['text'];
}

$lang_s_a_post_edit = language_forum ("board/scripts/ajax/post_edit");
header( "Content-type: text/html; charset=".$LB_charset );

$post = $DB->one_join_select( "p.*, t.forum_id, t.title, t.id, t.date_last, t.description, t.member_id_open, t.last_post_id as topic_last_post_id, t.poll_id, t.post_fixed, poll.vote_num, poll.title as p_title, poll.question, poll.variants, poll.multiple, poll.answers, f.last_post_date, f.last_post_id as forum_last_post_id, f.last_topic_id", "LEFT", "posts p||topics t||topics_poll poll||forums f", "p.topic_id=t.id||t.poll_id=poll.id||t.forum_id=f.id", "p.pid = '{$id}'", "LIMIT 1" );

$pc_time = intval($cache_group[$member_id['user_group']]['g_pc_time']) * 60 * 60;

if ($post['topic_id'] AND !forum_permission($post['forum_id'], "read_theme"))
{
    echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_read_theme']);
    echo return_text();
    stop_script();
}
elseif ($post['topic_id'] AND forum_all_password($post['forum_id']))
{
    echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_pass']);
    echo return_text();
    stop_script();
}
elseif (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
{
    echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['secret_key']);
    echo return_text();
    stop_script();
}
elseif (!member_publ_access(1))
{   
    echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], str_replace("{info}", member_publ_info(), $lang_s_a_post_edit['access_denied_publ']));
    echo return_text();
    stop_script();  
}
elseif($post['id'])
{    
    if(forum_options_topics($post['forum_id'], "changepost")) $pc_time = 0;
    
    if($_POST['act'] == "edit")
    {
        $bb_allowed_out = array();
        if ($cache_forums[$post['forum_id']]['allow_bbcode'])
        {
            if ($cache_forums[$post['forum_id']]['allow_bbcode_list'] AND $cache_forums[$post['forum_id']]['allow_bbcode_list'] != "0")
            {
                include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
                $allow_bbcode_list = explode(",", $cache_forums[$post['forum_id']]['allow_bbcode_list']);
                foreach($allow_bbcode_list as $value)
                {
                    $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
                }
            }
        }
        
        $access_edit_post = false;
    
        if(forum_options_topics($post['forum_id'], "changepost"))
            $access_edit_post = true;
        elseif(group_permission("local_changepost") AND $post['post_member_id'] == $member_id['user_id'])
        {
            $pc_time = intval($cache_group[$member_id['user_group']]['g_pc_time']) * 60 * 60;
            if ($pc_time AND ($post['post_date'] + $pc_time) < $time)
            {   
                echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], str_replace("{info}", intval($cache_group[$member_id['user_group']]['g_pc_time']), $lang_s_a_post_edit['access_denied_time']), 4000);
                echo return_text();
                stop_script();
            }
            else
                $access_edit_post = true;
        }
        else
        {
            echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_edit']);
            echo return_text();
            stop_script();
        }
        
        if($access_edit_post)
        {
            if ($LB_flood->isBlock() AND isset($_POST['editpost']))
            {
                echo show_jq_message("3", $lang_s_a_post_edit['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_s_a_post_edit['flood_control_stop']));
                echo return_text();
                stop_script();
            }
            elseif (isset($_POST['editpost']))
            {
                if (forum_permission($post['forum_id'], "answer_theme"))
                {
                    $errors = array();
                                        
                    $_POST['text'] = htmlspecialchars($_POST['text']);
                    
                    filters_input ('post');
                    
                    if (utf8_strlen($_POST['text']) < intval($cache_config['posts_text_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['posts_text_min']['conf_value']), $lang_s_a_post_edit['post_min']);
                    if (utf8_strlen($_POST['text']) > intval($cache_config['posts_text_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_s_a_post_edit['post_max']);
                        
                    $_POST['text'] = parse_word(html_entity_decode($_POST['text']), $cache_forums[$post['forum_id']]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));
                    $text = $DB->addslashes($_POST['text']);
                    $text_out = $_POST['text'];
                    
                    if (utf8_strlen($_POST['text']) > 65000) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_s_a_post_edit['post_max_2']);
                                         
                    $where = array();
                    $moder_reason = "";
                        
                    if(forum_options_topics($post['forum_id'], "changepost"))
                    {
                         $moder_reason = $DB->addslashes(trim(strip_tags($_POST['moder_reason'])));
                         $where[] = "moder_reason = '{$moder_reason}'";
                         
                         if ($moder_reason != $post['moder_reason'])
                            $where[] = "moder_date = '{$time}'";
                         
                         if(intval($_POST['change_moder']) OR !$post['moder_member_name'])
                         {
                            $moder_member_name = $member_id['name'];
                            $moder_member_id = $member_id['user_id'];
                            $where[] = "moder_member_id = '{$member_id['user_id']}', moder_member_name = '{$member_id['name']}'";
                         }
                         
                         if ($moder_reason == "")
                         {
                            unset($where);
                            $where = array();
                            $where[] = "moder_member_id = '0', moder_member_name = '', moder_reason = ''";
                         }
                    }
                    
                    if ($where[0] != "")
                        $where_bd = ", ".implode(", ", $where);
                    else
                        $where_bd = "";
                        
                    $edit_reason = $DB->addslashes(words_wilter(trim(strip_tags($_POST['edit_reason']))));
        
   	                if( ! $errors[0] )
                    {                          
                        $DB->update("text = '{$text}', edit_date = '{$time}', edit_member_id = '{$member_id['user_id']}', edit_member_name = '{$member_id['name']}', edit_reason = '{$edit_reason}' {$where_bd}", "posts", "pid = '{$id}'");
                           
                        $small_img = "<script type=\"text/javascript\">Resize_img();</script>";
                        
                        $logs_record_mas = array();
                        $logs_record_mas['table'] = "logs_posts";
                        $logs_record_mas['fid'] = $post['forum_id'];
                        $logs_record_mas['tid'] = $post['topic_id'];
                        $logs_record_mas['pid'] = $post['pid'];
                        $logs_record_mas['act_st'] = 1;
                        
                        if ($post['text'] != stripslashes($text))
                        {
                            $logs_record_mas['info'] = str_replace("{old_post}", $post['text'], $lang_s_a_post_edit['log_edit_text']);
                            $logs_record_mas['info'] = str_replace("{new_post}", stripslashes($text), $logs_record_mas['info']);
                            logs_record ($logs_record_mas);
                        }
                                                
                        $logs_record_mas_info = array();
                        
                        if ($moder_reason == "" AND $post['moder_reason'] != "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", $post['moder_reason'], $lang_s_a_post_edit['log_del_warning']);
                        }
                        elseif (stripslashes($moder_reason) != "" AND $post['moder_reason'] == "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", stripslashes($moder_reason), $lang_s_a_post_edit['log_add_warning']);
                        }
                        elseif (stripslashes($moder_reason) != $post['moder_reason'] AND $post['moder_reason'] != "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", $post['moder_reason']." -> ".stripslashes($moder_reason), $lang_s_a_post_edit['log_edit_warning']);
                        }
                        
                        if((intval($_POST['change_moder']) AND $post['moder_member_name']) AND $moder_reason != "")
                        {
                            if ($post['moder_member_id'] != $member_id['user_id'])
                            {
                                $logs_record_mas_info[] = str_replace("{info}", $post['moder_member_name']." -> ".$member_id['name'], $lang_s_a_post_edit['log_change_name_warning']);
                            }
                        }
                                                
                        if ($logs_record_mas_info[0] != "")
                        {
                            $logs_record_mas['info'] = implode ("<br />", $logs_record_mas_info);
                            logs_record ($logs_record_mas);
                        }
                        
                        $act = $lang_s_a_post_edit['act_edit'];
                        
                        $text_out = str_replace ( '{TEMPLATE}', $cache_config['general_site']['conf_value'] . 'templates/'.$cache_config['template_name']['conf_value'], $text_out );
                        $text_out = str_replace ( '{TEMPLATE_NAME}', $cache_config['template_name']['conf_value'], $text_out );
                        $text_out = str_replace ( '{HOME_LINK}', $cache_config['general_site']['conf_value'], $text_out );
                        
                        $attachment_post = array();
                        if ($post['attachments'])
                            $attachment_post[] = $id;
                            
                        if (strpos($text_out, "[attachment=") !== false)
                        {                            
                            $text_out = show_attach ($text_out, $attachment_post);
                        }
                        
                        echo hide_in_post($text_out, $member_id['user_id']).$small_img.show_jq_message("1", $lang_s_a_post_edit['successful'], str_replace("{act}", $act, $lang_s_a_post_edit['successful_info']));
                        stop_script();
                    }
                    else
                    {
                        $mes = "";
                        foreach ($errors as $mes_data)
                        {
                            $mes .= "- ".$mes_data."<br />";
                        }
                    
                        echo show_jq_message("3", $lang_s_a_post_edit['error'], $mes);
                        echo return_text();
                        stop_script();
                    }
                }
                else
                {
                    echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_answer']);
                    echo return_text();
                    stop_script();
                } 
            }
                   
            $tpl->load_template ( 'board/post_edit_ajax.tpl' );
               
            if ($cache_forums[$post['forum_id']]['allow_bbcode'])
            {
                require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
                $tpl->tags('{bbcode}', $bbcode_script.$bbcode);
            }
            else
                $tpl->tags('{bbcode}', "");
                
            $tpl->tags('{id}', $id);
            $tpl->tags('{text}', parse_back_word($post['text'], true, intval($cache_group[$member_id['user_group']]['g_html_allowed'])));
            $tpl->tags('{edit_reason}', $post['edit_reason']);
            
            $tpl->tags_blocks("moder_warning", forum_options_topics($post['forum_id'], "changepost"));
            
            if(forum_options_topics($post['forum_id'], "changepost"))
            {
                $tpl->tags('{moder_reason}', $post['moder_reason']);
                if ($post['moder_member_name'])
                {
                    $tpl->tags('{moder_member_name}', $post['moder_member_name']);
                    $tpl->tags('{moder_member_link}', profile_link($post['moder_member_name'], $post['moder_member_id']));
                }
                else
                {
                    $tpl->tags('{moder_member_name}', $lang_s_a_post_edit['moder_member_name']);
                    $tpl->tags('{moder_member_link}', "#");
                }
            }
                
            $tpl->compile('message');
            $tpl->clear();   
            
            $tpl->global_tags ('message');
            
            $new_textarea = "
            <script language=\"Javascript\" type=\"text/javascript\">	
            var aid = 'tf-".$id."';
	        </script>";  
            
            echo $tpl->result['message'].$new_textarea;
            stop_script();         
        }
    }
    elseif($_POST['act'] == "showhide")
    {       
        if(forum_options_topics($post['forum_id'], "hidetopic"))
        {
            $where = array(); // обновление данных в теме
            $where2 = array(); // обновление данных в форуме
            
            $logs_record_mas = array();
            $logs_record_mas['table'] = "logs_posts";
            $logs_record_mas['fid'] = $post['forum_id'];
            $logs_record_mas['tid'] = $post['topic_id'];
            $logs_record_mas['pid'] = $post['pid'];
            
            if ($post['hide']) // если пост уже скрыт - опубликовать его
            {
                $act = $lang_s_a_post_edit['act_show'];
                $DB->update("hide = '0'", "posts", "pid = '{$post['pid']}'");
                
                if ($cache_forums[$post['forum_id']]['postcount'])
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("posts_num = posts_num+1", "users", "user_id = '{$post['post_member_id']}'");
                }
                
                if ($post['new_topic'])
                {
                    $where[] = "hiden = '0'";
                    $cache_forums[$post['forum_id']]['topics'] += 1;
                    $cache_forums[$post['forum_id']]['topics_hiden'] -= 1;
                    $where2[] = "topics = topics+1, topics_hiden = topics_hiden-1";
                }
                else
                {
                    $where[] = "post_hiden = post_hiden-1, post_num = post_num+1";
                    $where2[] = "posts_hiden = posts_hiden-1, posts = posts+1";
                    $cache_forums[$post['forum_id']]['posts_hiden'] -= 1;
                    $cache_forums[$post['forum_id']]['posts'] += 1;
                }
                
                if ($post['date_last'] < $post['post_date'])
                {
                    $where[] = "last_post_id = '{$post['pid']}', last_post_member = '{$post['post_member_id']}', date_last = '{$post['post_date']}', member_name_last = '{$post['post_member_name']}'";
                    
                    if ($post['last_topic_id'] == $post['topic_id'])
                    {
                        $where2[] = "last_post_id = '{$post['pid']}', last_post_member_id = '{$post['post_member_id']}', last_post_date = '{$post['post_date']}', last_post_member = '{$post['post_member_name']}'";
                        $cache_forums[$post['forum_id']]['last_post_id'] = $post['pid'];
                        $cache_forums[$post['forum_id']]['last_post_member_id'] = $post['post_member_id'];
                        $cache_forums[$post['forum_id']]['last_post_date'] = $post['post_date'];
                        $cache_forums[$post['forum_id']]['last_post_member'] = $post['post_member_name'];
                    }  
                }
                
                if ($post['last_post_date'] < $post['post_date'] AND $post['last_topic_id'] != $post['topic_id'])
                {
                    $post['title'] = $DB->addslashes($post['title']);
                    $where2[] = "last_post_member = '{$post['post_member_name']}', last_post_member_id = '{$post['post_member_id']}', last_post_date = '{$post['post_date']}', last_title = '{$post['title']}', last_topic_id = '{$post['topic_id']}', last_post_id = '{$post['pid']}'";
                    $cache_forums[$post['forum_id']]['last_post_id'] = $post['pid'];
                    $cache_forums[$post['forum_id']]['last_topic_id'] = $post['topic_id'];
                    $cache_forums[$post['forum_id']]['last_post_member'] = $post['post_member_name'];
                    $cache_forums[$post['forum_id']]['last_post_member_id'] = $post['post_member_id'];
                    $cache_forums[$post['forum_id']]['last_post_date'] = $post['post_date'];
                    $cache_forums[$post['forum_id']]['last_title'] = $post['title'];  
                }
                
                $logs_record_mas['act_st'] = 5;
            }
            else
            {
                $act = $lang_s_a_post_edit['act_hide'];
                $DB->update("hide = '1'", "posts", "pid = '{$post['pid']}'");
                
                if ($cache_forums[$post['forum_id']]['postcount'])
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("posts_num = posts_num-1", "users", "user_id = '{$post['post_member_id']}'");
                }
                
                if ($post['new_topic'])
                {
                    $where[] = "hiden = '1'";
                    $cache_forums[$post['forum_id']]['topics'] -= 1;
                    $cache_forums[$post['forum_id']]['topics_hiden'] += 1;
                    $where2[] = "topics = topics-1, topics_hiden = topics_hiden+1";
                }
                else
                {
                    $where[] = "post_hiden = post_hiden+1, post_num = post_num-1";
                    $where2[] = "posts_hiden = posts_hiden+1, posts = posts-1";
                    $cache_forums[$post['forum_id']]['posts_hiden'] += 1;
                    $cache_forums[$post['forum_id']]['posts'] -= 1;
                }
                
                if ($post['topic_last_post_id'] == $post['pid'])
                {
                    $post_last = $DB->one_select( "*", "posts", "topic_id = '{$post['topic_id']}' AND hide = '0'", "ORDER BY post_date DESC LIMIT 1" );
                    if (!$post_last['pid'])
                    {
                        $DB->free($post_last);
                        $post_last = $DB->one_select( "*", "posts", "topic_id = '{$post['topic_id']}' AND new_topic = '1'", "LIMIT 1");
                    }
                    
                    $where[] = "last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";
                    
                    if ($post['last_topic_id'] == $post['topic_id'])
                    {
                        $where2[] = "last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                        $cache_forums[$post['forum_id']]['last_post_id'] = $post_last['pid'];
                        $cache_forums[$post['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                        $cache_forums[$post['forum_id']]['last_post_date'] = $post_last['post_date'];
                        $cache_forums[$post['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                    }  
                    $DB->free($post_last);
                }
                
                if ($post['last_topic_id'] == $post['topic_id'] AND $post['new_topic'])
                {
                    $topic_last = $DB->one_select( "*", "topics", "forum_id = '{$post['forum_id']}' AND id <> '{$post['topic_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if (!$topic_last['id'])
                    {
                        $where2[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                        $cache_forums[$post['forum_id']]['last_post_id'] = 0;
                        $cache_forums[$post['forum_id']]['last_topic_id'] = 0;
                        $cache_forums[$post['forum_id']]['last_post_member'] = "";
                        $cache_forums[$post['forum_id']]['last_post_member_id'] = 0;
                        $cache_forums[$post['forum_id']]['last_post_date'] = "";
                        $cache_forums[$post['forum_id']]['last_title'] = "";
                    }
                    else
                    {
                        $topic_last['title'] = $DB->addslashes($topic_last['title']);
                        $where2[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                        $cache_forums[$post['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                        $cache_forums[$post['forum_id']]['last_topic_id'] = $topic_last['id'];
                        $cache_forums[$post['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                        $cache_forums[$post['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                        $cache_forums[$post['forum_id']]['last_post_date'] = $topic_last['date_last'];
                        $cache_forums[$post['forum_id']]['last_title'] = $topic_last['title'];
                    }
                    $DB->free($post_last);  
                } 
                
                $logs_record_mas['act_st'] = 4;
            }
                        
            logs_record ($logs_record_mas);
                    
            $where_db = implode(", ", $where);
            $DB->update($where_db, "topics", "id = '{$post['topic_id']}'");
                        
            $where_db2 = implode(", ", $where2);
            $DB->update($where_db2, "forums", "id = '{$post['forum_id']}'");
            forum_last_avatar ($post['forum_id']);
            
            $cache->update("forums", $cache_forums);
                    
            echo show_jq_message("1", $lang_s_a_post_edit['successful'], str_replace("{act}", $act, $lang_s_a_post_edit['successful_info']));
            stop_script();
        }
        else
        {
            echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_showhide']);
            echo return_text();
            stop_script();
        }
    }
    elseif($_POST['act'] == "delete")
    {
        $access_edit_post = false;
    
        if(forum_options_topics($post['forum_id'], "delpost"))
            $access_edit_post = true;
        elseif(group_permission("local_delpost") AND $post['post_member_id'] == $member_id['user_id'])
        {
            $pc_time = intval($cache_group[$member_id['user_group']]['g_pc_time']) * 60 * 60;
            if ($pc_time AND ($post['post_date'] + $pc_time) < $time)
            {   
                echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], str_replace("{info}", intval($cache_group[$member_id['user_group']]['g_pc_time']), $lang_s_a_post_edit['access_denied_time']), 4000);
                echo return_text();
                stop_script();
            }
            else
                $access_edit_post = true;
        }
        else
        {
            echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_del']);
            echo return_text();
            stop_script();
        }

        if ($access_edit_post)
        {
            if ($post['new_topic'])
            {
                echo show_jq_message("3", $lang_s_a_post_edit['error'], $lang_s_a_post_edit['del_new_topic']);
                echo return_text();
                stop_script(); 
            }
            else
            {
                $act = $lang_s_a_post_edit['act_del'];
                
                $DB->delete("pid = '{$id}'", "posts");
                
                if ($post['attachments'])
                {
                    include_once LB_CLASS. "/upload_files.php";
                    $LB_upload = new LB_Upload();
                
                    $attachments = explode (",", $post['attachments']);
                    foreach ($attachments as $value)
                    {
                        $LB_upload->Del_Record($value, $secret_key);
                    }
                    
                    unset($LB_upload);
                }
                
                $where = "";
                $where2 = "";
                if ($post['hide'])
                {
                    $where = "post_hiden = post_hiden-1";
                    $where2 = "posts_hiden = posts_hiden-1";
                    $cache_forums[$post['forum_id']]['posts_hiden'] -= 1;
                }
                else
                {
                    $where = "post_num = post_num-1";
                    $where2 = "posts = posts-1";
                    $cache_forums[$post['forum_id']]['posts'] -= 1;
                }
                
                if ($post['forum_last_post_id'] == $id OR $post['topic_last_post_id'] == $id)
                {
                    $post_last = $DB->one_select( "*", "posts", "topic_id = '{$post['topic_id']}'", "ORDER by post_date DESC LIMIT 1");
                    $where .= ", last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";
                    $where2 .= ", last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                    
                    $cache_forums[$post['forum_id']]['last_post_id'] = $post_last['pid'];
                    $cache_forums[$post['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                    $cache_forums[$post['forum_id']]['last_post_date'] = $post_last['post_date'];
                    $cache_forums[$post['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                }
                    
                $DB->update($where, "topics", "id = '{$post['topic_id']}'");
                $DB->update($where2, "forums", "id = '{$post['forum_id']}'");
                forum_last_avatar ($post['forum_id']);
                
                if ($cache_forums[$post['forum_id']]['postcount'])
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("posts_num = posts_num-1", "users", "user_id = '{$post['post_member_id']}'");
                }
                
                $cache->update("forums", $cache_forums);
                
                echo show_jq_message("1", $lang_s_a_post_edit['successful'], str_replace("{act}", $act, $lang_s_a_post_edit['successful_info']));
                
                echo '
                <script type="text/javascript">
                $(document).ready(function()
                { 
                    $("#post-delete-'.$id.'").parents(".rl_item").eq(0).slideToggle(1100);
                    setTimeout(function(){ $("#post-delete-'.$id.'").parents(".rl_item").eq(0).remove(); }, 1100);
                });
                </script>';
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_posts";
                $logs_record_mas['fid'] = $post['forum_id'];
                $logs_record_mas['tid'] = $post['topic_id'];
                $logs_record_mas['pid'] = $post['pid'];
                $logs_record_mas['act_st'] = 0;
                $logs_record_mas['info'] = array();
                $logs_record_mas['info']['member'] = $post['post_member_name'];
                $logs_record_mas['info']['post_date'] = $post['post_date'];
                $logs_record_mas['info']['text'] = $post['text'];
                $logs_record_mas['info'] = serialize($logs_record_mas['info']);
                logs_record ($logs_record_mas);
                
                stop_script();
            }
        }
    }
    elseif($_POST['act'] == "fixed" OR $_POST['act'] == "unfixed")
    {
        if (forum_options_topics($post['forum_id'], "fixedpost"))
        {
            $logs_record_mas = array();
            $logs_record_mas['table'] = "logs_posts";
            $logs_record_mas['fid'] = $post['forum_id'];
            $logs_record_mas['tid'] = $post['topic_id'];
            $logs_record_mas['pid'] = $post['pid'];                        
            
            if ($_POST['act'] == "fixed")
            {
                $DB->update("fixed = '1'", "posts", "pid = '{$id}'");
                $DB->update("post_fixed = post_fixed+1", "topics", "id = '{$post['id']}'");
                    
                $act = $lang_s_a_post_edit['act_fix'];
                $logs_record_mas['act_st'] = 2;
            }
            else
            {
                $DB->update("fixed = '0'", "posts", "pid = '{$id}'");
                if ($post['post_fixed'])
                {
                    $DB->update("post_fixed = post_fixed-1", "topics", "id = '{$post['id']}'");
                }
                $act = $lang_s_a_post_edit['act_unfix'];
                $logs_record_mas['act_st'] = 3;
            }
            
            logs_record ($logs_record_mas);
                
            echo show_jq_message("1", $lang_s_a_post_edit['successful'], str_replace("{act}", $act, $lang_s_a_post_edit['successful_info']));
            stop_script();
        }
        else
        {
            echo show_jq_message("3", $lang_s_a_post_edit['access_denied'], $lang_s_a_post_edit['access_denied_fix']);
            echo return_text();
            stop_script();
        }    
    }
}
else
{
    echo show_jq_message("3", $lang_s_a_post_edit['not_found'], $lang_s_a_post_edit['not_found_info']);
    echo return_text();
    stop_script();
}
    
stop_script();

?>