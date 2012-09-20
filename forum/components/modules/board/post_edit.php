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
	@include '../../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$lang_m_b_post_edit = language_forum ("board/modules/board/post_edit");

if (!isset($_SESSION['back_link_board']) OR $_SESSION['back_link_board'] == "")
    $_SESSION['back_link_board'] = $redirect_url;

$post = $DB->one_join_select( "p.*, t.forum_id, t.title, t.id, t.date_last, t.description, t.member_id_open, t.last_post_id as topic_last_post_id, t.poll_id, t.post_fixed, t.metatitle, t.metadescr, t.metakeys, poll.vote_num, poll.title as p_title, poll.question, poll.variants, poll.multiple, poll.answers, f.last_post_date, f.last_post_id as forum_last_post_id, f.last_topic_id", "LEFT", "posts p||topics t||topics_poll poll||forums f", "p.topic_id=t.id||t.poll_id=poll.id||t.forum_id=f.id", "p.pid = '{$id}'", "LIMIT 1" );

function here_loaction ()
{
    global $link_speddbar, $onl_location, $lang_m_b_post_edit;
    
    $link_speddbar = speedbar_forum(0, true)."|".$lang_m_b_post_edit['location_access_denied'];
    $onl_location = $lang_m_b_post_edit['location_online_access_denied'];
}

if ($post['topic_id'] AND !forum_permission($post['forum_id'], "read_theme"))
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_post_edit['access_denied_group_read']), 1);
}
elseif ($post['topic_id'] AND forum_all_password($post['forum_id']))
{
    if(isset($_POST['check_pass']))
    {
        $check_f_pass_id = intval($_POST['f_id']);
        $check_f_pass = $_POST['f_pass'];
        if ($LB_flood->isBlock())
            message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
        elseif ($check_f_pass == $cache_forums[$post['forum_id']]['password'] AND $cache_forums[$post['forum_id']]['password'] != "")
        {
            if($member_id['user_group'] != 5)
                $who = $member_id['name'];
            else
                $who = $_IP;
            
            $check_f_pass = md5($who.$cache_forums[$post['forum_id']]['password']);
            update_cookie( "LB_password_forum_".$post['forum_id'], $check_f_pass, 365 );
            header( "Location: {$_SERVER['REQUEST_URI']}" );
            exit();
        }
        elseif ($check_f_pass != $cache_forums[$post['forum_id']]['password'] AND $cache_forums[$post['forum_id']]['password'] != "")
            message ($lang_message['access_denied'], $lang_m_b_post_edit['wrong_password_forum']);
    }
    
    here_loaction ();
    message ($lang_message['access_denied'], $lang_m_b_post_edit['write_password_forum']);
            
    $tpl->load_template ( 'board/forum_password.tpl' );
    $tpl->tags('{forum_title}', $cache_forums[forum_all_password($post['forum_id'])]['title']);
    $tpl->tags('{forum_id}', forum_all_password($post['forum_id']));
    $tpl->compile('content');
    $tpl->clear();
}
elseif (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
    here_loaction ();
    message ($lang_message['access_denied'], $lang_message['secret_key'], 1);
}
elseif (!member_publ_access(1))
{
    here_loaction ();
    
    $message_arr = array();
    $message_arr[] = $lang_m_b_post_edit['access_denied_publ_access'];
    $message_arr[] = member_publ_info();
    
    message ($lang_message['access_denied'], $message_arr, 1);   
}
elseif($post['id'])
{
    $mo_loc_fid = $post['forum_id'];
    
    $meta_info_forum = $post['forum_id'];
    $meta_info_other = str_replace("{title}", $post['title'], $lang_m_b_post_edit['meta_info_other']);
    
    include_once LB_CLASS. "/upload_files.php";
    $LB_upload = new LB_Upload();
    
    if($_GET['act'] == "edit")
    {
        $preview = 0;
        $quote = "";
        $text = "";
        $poll_title = "";
        $poll_question = "";
        $poll_mult = "";
        $variants = "";
        $desc = "";
        $title = "";
        
        $meta_topic = array(
            "title" => $post['metatitle'],
            "description" => $post['metadescr'],
            "keywords" => $post['metakeys']
        );
        
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
                message ($lang_message['access_denied'], str_replace("{info}", intval($cache_group[$member_id['user_group']]['g_pc_time']), $lang_m_b_post_edit['access_denied_time'])); 
            }
            else
                $access_edit_post = true;
        }
        else
            message ($lang_message['access_denied'], $lang_m_b_post_edit['access_denied_edit']);
            
        if($access_edit_post)
        {
            $tc_time = intval($cache_group[$member_id['user_group']]['g_tc_time']) * 60 * 60;
            
            // Определяем права на изменение голосования
            $access_title_tedit = false;
                    
            if ($post['new_topic'])
            {                        
                if (forum_options_topics($post['forum_id'], "titletopic"))
                    $access_title_tedit = true;
                elseif(group_permission("local_titletopic") AND $post['member_id_open'] == $member_id['user_id'])
                {
                    if (!$tc_time)
                        $access_title_tedit = true;
                    if (($post['post_date'] + $tc_time) >= $time)
                        $access_title_tedit = true;
                }
            }
            
            // Определяем права на изменение заголовка темы
            $access_poll_tedit = false;
                     
            if($post['new_topic'] AND $cache_forums[$post['forum_id']]['allow_poll'])
            {
                if (forum_options_topics($post['forum_id'], "polltopic"))
                    $access_poll_tedit = true;
                elseif (group_permission("local_polltopic") AND $post['member_id_open'] == $member_id['user_id'])
                {
                    if (!$tc_time)
                        $access_poll_tedit = true;
                    if (($post['post_date'] + $tc_time) >= $time)
                        $access_poll_tedit = true;
                }
            }
            
            if (isset($_POST['preview']) OR isset($_POST['add_file']))
            {
                if (isset($_POST['preview']))
                    $preview = 1;
                else
                    $preview = 2;
                    
                if (isset($_POST['add_file']))
                {
                    $upload_status = $LB_upload->Uploading($post['forum_id'], $post['id'], $id);
                    if ($upload_status) message ($lang_message['error'], $upload_status);
                        
                    $del_file = $_POST['del_file'];
                    if (is_array($del_file) AND $del_file[0] != "")
                    {
                        foreach ($del_file as $value)
                        {
                            $value = intval($value);
                            if ($value)
                                $del_status = $LB_upload->Del_Record($value, $secret_key); 
                        }
                        if ($del_status)
                            message ($lang_message['error'], $del_status);
                    }
                }
                                                  
                $_POST['text'] = htmlspecialchars($_POST['text']);
                filters_input ('post');
           
                $text = parse_word(html_entity_decode($_POST['text']), $cache_forums[$post['forum_id']]['allow_bbcode'], true, true, true, $bb_allowed_out);
                $quote = parse_back_word($text);
            
                if($cache_forums[$post['forum_id']]['allow_poll'] AND isset($_POST['poll_title']) AND $_POST['poll_title'] != "")
                {
                    $poll_title = htmlspecialchars($_POST['poll_title']);
                    $poll_question = htmlspecialchars($_POST['poll_question']);
                            
                    if (intval($_POST['poll_mult']))
                        $poll_mult = "checked";
                        
                    $variants = htmlspecialchars($_POST['variants']);
                }
                
                $title = words_wilter($_POST['title'], false);
                $desc = htmlspecialchars(words_wilter($_POST['desc']));  
                $desc = wrap_word($desc);
                
                $edit_reason = words_wilter(trim(strip_tags($_POST['edit_reason'])));
                $moder_reason = trim(strip_tags($_POST['moder_reason']));   
                
                if (intval($cache_group[$member_id['user_group']]['g_metatopic']))
                    $meta_topic = create_metatags();        
            }
            elseif ($LB_flood->isBlock() AND isset($_POST['editpost']))
                message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
            elseif (isset($_POST['editpost']))
            {
                if (forum_permission($post['forum_id'], "answer_theme"))
                {
                    $errors = array();
                    
                    $del_file = $_POST['del_file'];
                    if (is_array($del_file) AND $del_file[0] != "")
                    {
                        foreach ($del_file as $value)
                        {
                            $value = intval($value);
                            if ($value) $del_status = $LB_upload->Del_Record($value, $secret_key); 
                        }
                        if ($del_status) $errors[] = $del_status;
                    }
                    
                    $_POST['text'] = htmlspecialchars($_POST['text']);
                    
                    filters_input ('post');
                    
                    if (utf8_strlen($_POST['text']) < intval($cache_config['posts_text_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['posts_text_min']['conf_value']), $lang_m_b_post_edit['post_text_min']);
                    if (utf8_strlen($_POST['text']) > intval($cache_config['posts_text_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_post_edit['post_text_max']);
                        
                    $_POST['text'] = parse_word(html_entity_decode($_POST['text']), $cache_forums[$post['forum_id']]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));
                    $text = $DB->addslashes($_POST['text']);
                    
                    if (utf8_strlen($_POST['text']) > 65000) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_post_edit['post_text_max_2']);
                        
                    // Редактирование заголовка темы
                    
                    if($access_title_tedit)
                    {
                        $title = $DB->addslashes(htmlspecialchars(words_wilter($_POST['title'])));
                        if (utf8_strlen($title) < intval($cache_config['topic_title_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['topic_title_min']['conf_value']), $lang_m_b_post_edit['topic_title_min']);
                        if (utf8_strlen($title) > intval($cache_config['topic_title_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['topic_title_max']['conf_value']), $lang_m_b_post_edit['topic_title_max']);
        
                        $desc = $DB->addslashes(htmlspecialchars(words_wilter($_POST['desc'])));
                        
                        if (utf8_strlen($desc) > 200) $errors[] = $lang_m_b_post_edit['topic_desc'];
                            
                        $desc = wrap_word($desc);
                    }
                     
                    // Редактирование опроса темы
                                         
                    if($access_poll_tedit AND isset($_POST['poll_title']) AND $_POST['poll_title'] != "")
                    {
                        $poll_title = $DB->addslashes(htmlspecialchars($_POST['poll_title']));
                        $poll_question = $DB->addslashes(htmlspecialchars($_POST['poll_question']));
        
                        if (utf8_strlen($poll_title) < 3 OR utf8_strlen($poll_title) > 200) $errors[] = $lang_m_b_post_edit['poll_title'];
                        if (utf8_strlen($poll_question) < 3 OR utf8_strlen($poll_question) > 200) $errors[] = $lang_m_b_post_edit['poll_question'];
        
                        if (intval($_POST['poll_mult']))
                            $poll_mult = 1;
                        else
                            $poll_mult = 0;
            
                        $variants = htmlspecialchars($_POST['variants']);
                        $variants_mas = explode ("\r\n", $variants);
                        
                        $old_variants = explode ("\r\n", $post['variants']);
                        $update_log_poll = false;
                        
                        if (count($variants_mas))
                        {
                            $variants_mas2 = array();
                            foreach ($variants_mas as $value)
                            {
                                if (utf8_strlen($value) > 0)
                                    $variants_mas2[] = $value;
                                    
                                if (!in_array($value, $old_variants))
                                    $update_log_poll = true;
                            }
                        }
        
                        if (count($variants_mas2) < 2 OR !$variants_mas2[0])
                            $errors[] = $lang_m_b_post_edit['poll_question_min'];
            
                        $variants = $DB->addslashes( implode("\r\n", $variants_mas2) );
                    }
                     
                    $where = array();
                    $moder_reason = "";
                        
                    if(forum_options_topics($post['forum_id'], "changepost"))
                    {
                         $moder_reason = $DB->addslashes(trim(strip_tags($_POST['moder_reason'])));
                         $where[] = "moder_reason = '{$moder_reason}'";
                         
                         if (stripslashes($moder_reason) != $post['moder_reason'])
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
        
                    // Создание мет-тегов темы
                    if (intval($cache_group[$member_id['user_group']]['g_metatopic']))
                    {
                        $meta_topic = create_metatags();
                    
                        if ($meta_topic['title'] != "")
                        {
                            if (utf8_strlen($meta_topic['title']) < 3) $errors[] = str_replace("{min}", "3", $lang_m_b_post_edit['metatitle_min']);
                            if (utf8_strlen($meta_topic['title']) > 255) $errors[] = str_replace("{max}", "255", $lang_m_b_post_edit['metatitle_max']);
                        }
                        if ($meta_topic['description'] != "")
                        {
                            if (utf8_strlen($meta_topic['description']) < 3) $errors[] = str_replace("{min}", "3", $lang_m_b_post_edit['metadescr_min']);
                            if (utf8_strlen($meta_topic['description']) > 200) $errors[] = str_replace("{max}", "200", $lang_m_b_post_edit['metadescr_max']);
                        }
                        if ($meta_topic['keywords'] != "")
                        {
                            if (utf8_strlen($meta_topic['keywords']) < 3) $errors[] = str_replace("{min}", "3", $lang_m_b_post_edit['metakeys_min']);
                            if (utf8_strlen($meta_topic['keywords']) > 1000) $errors[] = str_replace("{max}", "1000", $lang_m_b_post_edit['metakeys_max']);
                        }
                    }
        
   	                if( ! $errors[0] )
                    {   
                        $attachment_mas = $LB_upload->Add_attachments($post['id'], $id, $post['post_member_id'], $text);
                        $text = $attachment_mas[0];
                        
                        if (is_array($del_file) AND $del_file[0] != "")
                        {
                            foreach ($del_file as $value)
                            {
                                $value = intval($value);
                                if (in_array($value, $attachment_mas[1]))
                                    unset ($attachment_mas[1][$value]);
                            }
                        }
                        
                        $a_id = implode(",", $attachment_mas[1]);
                        
                        $logs_record_mas = array();
                        $logs_record_mas['table'] = "logs_topics";
                        $logs_record_mas['fid'] = $post['forum_id'];
                        $logs_record_mas['tid'] = $post['topic_id'];
                        
                        $logs_record_mas_info = array();
                        
                        if($access_poll_tedit)
                        {
                            if ($post['poll_id'])
                            {                 
                                if (isset($_POST['poll_del']) AND intval($_POST['poll_del']))
                                {
                                    $DB->delete("poll_id = '{$post['poll_id']}'", "topics_poll_logs");
                                    $DB->delete("id = '{$post['poll_id']}'", "topics_poll");
                                    $DB->update("poll_id = '0'", "topics", "id = '{$post['topic_id']}'");
                                    
                                    $logs_record_mas_info[] = str_replace("{info}", $post['p_title'], $lang_m_b_post_edit['log_del_poll']);
                                }
                                else
                                {
                                    if (stripslashes($poll_title) != $post['p_title'])
                                        $logs_record_mas_info[] = str_replace("{info}", $post['p_title']." -> ".stripslashes($poll_title), $lang_m_b_post_edit['log_edit_title_poll']);
                                        
                                    if (stripslashes($poll_question) != $post['question'])
                                        $logs_record_mas_info[] = str_replace("{info}", $post['question']." -> ".stripslashes($poll_question), $lang_m_b_post_edit['log_edit_question_poll']);
                                        
                                    if ($poll_mult != $post['multiple'] AND $poll_mult)
                                        $logs_record_mas_info[] = $lang_m_b_post_edit['log_multi_on'];
                                    elseif ($poll_mult != $post['multiple'] AND !$poll_mult)
                                        $logs_record_mas_info[] = $lang_m_b_post_edit['log_multi_off'];
                                        
                                    if ($update_log_poll)
                                        $logs_record_mas_info[] = $lang_m_b_post_edit['log_edit_answers'];
                                    
                                    if ((isset($_POST['poll_log']) AND intval($_POST['poll_log'])) OR (isset($_POST['poll_again']) AND intval($_POST['poll_again'])))
                                    {
                                        $DB->delete("poll_id = '{$post['poll_id']}'", "topics_poll_logs");
                                        
                                        if (isset($_POST['poll_log']) AND intval($_POST['poll_log']))
                                            $logs_record_mas_info[] = str_replace("{info}", stripslashes($poll_title), $lang_m_b_post_edit['log_del_logs']);
                                    }
                                        
                                    if (isset($_POST['poll_again']) AND intval($_POST['poll_again']))
                                    {
                                        $answers = "";
                                        $vote_num = 0;
                                        $logs_record_mas_info[] = str_replace("{info}", stripslashes($poll_title), $lang_m_b_post_edit['log_clear_logs']);
                                    }
                                    else
                                    {
                                        $answers = $post['answers'];
                                        $vote_num = $post['vote_num'];
                                    }
                                    
                                    $DB->update("title = '{$poll_title}', question = '{$poll_question}', variants = '{$variants}', multiple = '{$poll_mult}', answers = '{$answers}', vote_num = '{$vote_num}'", "topics_poll", "id = '{$post['poll_id']}'");
                                }
                                
                                if ($logs_record_mas_info[0] != "")
                                {
                                    $logs_record_mas['info'] = implode ("<br />", $logs_record_mas_info);
                                    $logs_record_mas['act_st'] = 12;
                                    logs_record ($logs_record_mas);
                                }
                            }
                            else
                            {
                                if (isset($_POST['poll_title']) AND $_POST['poll_title'] != "")
                                {
                                    $DB->insert("tid = '{$post['topic_id']}', title = '{$poll_title}', question = '{$poll_question}', variants = '{$variants}', multiple = '{$poll_mult}', open_date = '{$time}'", "topics_poll");
                                    $poll_id = $DB->insert_id();
                                    $DB->update("poll_id = '{$poll_id}'", "topics", "id = '{$post['topic_id']}'");
                                    
                                    $logs_record_mas['act_st'] = 11;
                                    logs_record ($logs_record_mas);
                                }
                            }
                        }
                        
                        unset($logs_record_mas);
                        unset($logs_record_mas_info);
                        
                        $DB->update("text = '{$text}', attachments = '{$a_id}', edit_date = '{$time}', edit_member_id = '{$member_id['user_id']}', edit_member_name = '{$member_id['name']}', edit_reason = '{$edit_reason}' {$where_bd}", "posts", "pid = '{$id}'");
                        
                        $where_meta = "";                    
                        if (intval($cache_group[$member_id['user_group']]['g_metatopic']))
                        {
                            $where_meta = array();
                            foreach ($meta_topic as $key => $value)
                            {
                                if ($key == "title") $where_meta[] = "metatitle = '".$DB->addslashes($value)."'";
                                if ($key == "description") $where_meta[] = "metadescr = '".$DB->addslashes($value)."'";
                                if ($key == "keywords") $where_meta[] = "metakeys = '".$DB->addslashes($value)."'";
                            }
                            $where_meta = implode (", ", $where_meta);
                            
                            if (!$access_title_tedit)
                                $DB->update($where_meta, "topics", "id = '{$post['topic_id']}'");
                            else
                                $where_meta = ", ".$where_meta;
                        }
                        
                        if($access_title_tedit)
                        {
                            $DB->update("title = '{$title}', description = '{$desc}'".$where_meta, "topics", "id = '{$post['topic_id']}'");
                            if ($cache_forums[$post['forum_id']]['last_topic_id'] == $post['topic_id'])
                            {
                                $cache_forums[$post['forum_id']]['last_title'] = $title; 
                                $cache->update("forums", $cache_forums);
                            }
                            
                            $logs_record_mas = array();
                            $logs_record_mas['table'] = "logs_topics";
                            $logs_record_mas['fid'] = $post['forum_id'];
                            $logs_record_mas['tid'] = $post['topic_id'];
                        
                            $logs_record_mas_info = array();
                            
                            if (stripslashes($title) != $post['title'])
                            {
                                $logs_record_mas_info[] = str_replace("{info}", $post['title']." -> ".stripslashes($title), $lang_m_b_post_edit['log_edit_title_topic']);
                            }
                            
                            if (stripslashes($desc) != $post['description'])
                            {
                                $logs_record_mas_info[] = str_replace("{info}", $post['description']." -> ".stripslashes($desc), $lang_m_b_post_edit['log_edit_desc_topic']);
                            }
                            
                            if ($logs_record_mas_info[0] != "")
                            {
                                $logs_record_mas['info'] = implode ("<br />", $logs_record_mas_info);
                                $logs_record_mas['act_st'] = 1;
                                logs_record ($logs_record_mas);
                            }
                        }
                        
                        unset($logs_record_mas);
                        unset($logs_record_mas_info);
                        
                        $logs_record_mas = array();
                        $logs_record_mas['table'] = "logs_posts";
                        $logs_record_mas['fid'] = $post['forum_id'];
                        $logs_record_mas['tid'] = $post['topic_id'];
                        $logs_record_mas['pid'] = $post['pid'];
                        $logs_record_mas['act_st'] = 1;
                        
                        if ($post['text'] != stripslashes($text))
                        {
                            $logs_record_mas['info'] = str_replace("{old_post}", $post['text'], $lang_m_b_post_edit['log_edit_text']);
                            $logs_record_mas['info'] = str_replace("{new_post}", stripslashes($text), $logs_record_mas['info']);
                            logs_record ($logs_record_mas);
                        }
                        
                        $logs_record_mas_info = array();
                        
                        if ($moder_reason == "" AND $post['moder_reason'] != "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", $post['moder_reason'], $lang_m_b_post_edit['log_del_warning']);
                        }
                        elseif (stripslashes($moder_reason) != "" AND $post['moder_reason'] == "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", stripslashes($moder_reason), $lang_m_b_post_edit['log_add_warning']);
                        }
                        elseif (stripslashes($moder_reason) != $post['moder_reason'] AND $post['moder_reason'] != "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", $post['moder_reason']." -> ".stripslashes($moder_reason), $lang_m_b_post_edit['log_edit_warning']);
                        }
                        
                        if((intval($_POST['change_moder']) AND $post['moder_member_name']) AND $moder_reason != "")
                        {
                            if ($post['moder_member_id'] != $member_id['user_id'])
                            {
                                $logs_record_mas_info[] = str_replace("{info}", $post['moder_member_name']." -> ".$member_id['name'], $lang_m_b_post_edit['log_change_name_warning']);
                            }
                        }
                        
                        if ($logs_record_mas_info[0] != "")
                        {
                            $logs_record_mas['info'] = implode ("<br />", $logs_record_mas_info);
                            logs_record ($logs_record_mas);
                        }
                        
                        $back_link = str_replace("{i_page}", intval($_GET['page']), navigation_topic_link($post['id'], $post['forum_id']))."#post".$id;
                           
                        header( "Location: ".$back_link );
                        exit();
                    }
                    else
                        message ($lang_message['error'], $errors);
                }
                else
                    message ($lang_message['access_denied'], $lang_m_b_post_edit['access_denied_edit']); 
            }
    
            $lang_loaction = str_replace("{link}", topic_link($post['id'], $post['forum_id']), $lang_m_b_post_edit['location']);        
            $lang_loaction = str_replace("{title}", $post['title'], $lang_loaction);
            $link_speddbar = speedbar_forum ($post['forum_id'])."|".$lang_loaction;

            $lang_loaction = str_replace("{link}", topic_link($post['id'], $post['forum_id']), $lang_m_b_post_edit['location_online']);
            $lang_loaction = str_replace("{title}", $post['title'], $lang_loaction);
            $onl_location = $lang_loaction;
      
            $tpl->load_template ( 'board/post_edit.tpl' );
            
            if ($cache_forums[$post['forum_id']]['allow_bbcode'])
            {
                require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
                $tpl->tags('{bbcode}', $bbcode_script.$bbcode);
            }
            else
                $tpl->tags('{bbcode}', "");
            
            $tpl->tags('{topic_title}', $post['title']);
            
            if ($preview != 0)
                $tpl->tags('{text}', $quote);
            else
                $tpl->tags('{text}', parse_back_word($post['text'], true, intval($cache_group[$member_id['user_group']]['g_html_allowed']))); 
            
            if (!$preview)
                $tpl->tags('{edit_reason}', $post['edit_reason']);
            else
                $tpl->tags('{edit_reason}', $edit_reason);
            
            if(forum_options_topics($post['forum_id'], "changepost"))
            {
                $tpl->tags_blocks("moder_warning");

                if (!$preview)
                    $tpl->tags('{moder_reason}', $post['moder_reason']);
                else
                    $tpl->tags('{moder_reason}', $moder_reason);
                    
                if ($post['moder_member_name'])
                {
                    $tpl->tags('{moder_member_name}', $post['moder_member_name']);
                    $tpl->tags('{moder_member_link}', profile_link($post['moder_member_name'], $post['moder_member_id']));
                }
                else
                {
                    $tpl->tags('{moder_member_name}', $lang_m_b_post_edit['moder_member_name']);
                    $tpl->tags('{moder_member_link}', "#");
                }
            }
            else
                $tpl->tags_blocks("moder_warning", false);

                
            if($access_title_tedit)
            {
                $tpl->tags_blocks("topic_edit");
                if (!$preview)
                {
                    $tpl->tags('{title}', $post['title']);
                    $tpl->tags('{desc}', $post['description']);
                }
                else
                {
                    $tpl->tags('{title}', $title);
                    $tpl->tags('{desc}', $desc);
                }
            }
            else
                $tpl->tags_blocks("topic_edit", false);
                
            if($access_poll_tedit)
            {
                $tpl->tags_blocks("poll");
                
                if (!$preview)
                {                
                    if ($post['poll_id'])
                    {
                        $tpl->tags('{poll_title}', $post['p_title']);
                        $tpl->tags('{poll_question}', $post['question']);
                        if ($post['multiple'])
                            $tpl->tags('{poll_mult}', "checked");
                        else
                            $tpl->tags('{poll_mult}', "");
                        $tpl->tags('{variants}', $post['variants']);
                    }
                    else
                    {
                        $tpl->tags('{poll_title}', "");
                        $tpl->tags('{poll_question}', "");
                        $tpl->tags('{poll_mult}', "");
                        $tpl->tags('{variants}', "");
                    }
                }
                else
                {
                    $tpl->tags('{poll_title}', $poll_title);
                    $tpl->tags('{poll_question}', $poll_question);                    
                    $tpl->tags('{poll_mult}', $poll_mult);
                    $tpl->tags('{variants}', $variants);
                }
            }
            else
                $tpl->tags_blocks("poll", false);
                
            if ($preview == 1)
                $tpl->tags_blocks("preview");
            else
                $tpl->tags_blocks("preview", false);
            
            $tpl->tags('{text_pr}', $text);
        
            if (forum_permission($post['forum_id'], "upload_files") AND $logged)
            {
                $tpl->tags_blocks("attachment");
                $tpl->tags('{attachments}', $LB_upload->Out_link($post['id'], $id, $post['post_member_id'], 1));
            }
            else
                $tpl->tags_blocks("attachment", false);
                
            $tpl->tags_blocks("metadata", intval($cache_group[$member_id['user_group']]['g_metatopic']));
            $tpl->tags('{meta_title}', $meta_topic['title']);
            $tpl->tags('{meta_description}', $meta_topic['description']);
            $tpl->tags('{meta_keywords}', $meta_topic['keywords']);
            
            $tpl->compile('content');
            $tpl->clear();
        }
    }
    elseif($_GET['act'] == "showhide")
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
                    $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$post['topic_id']}' AND hide = '0'", "ORDER BY post_date DESC LIMIT 1" );
                    if (!$post_last['pid'])
                    {
                        $DB->free($post_last);
                        $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$post['topic_id']}' AND new_topic = '1'", "LIMIT 1");
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
                    $topic_last = $DB->one_select( "id, title, member_name_last, last_post_member, date_last, last_post_id", "topics", "forum_id = '{$post['forum_id']}' AND id <> '{$post['topic_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
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
            
            $back_link = str_replace("{i_page}", intval($_GET['page']), navigation_topic_link($post['id'], $post['forum_id']))."#post".$id;
            
            header( "Location: ".$back_link );
            exit();
        }
        else
            message ($lang_message['access_denied'], $lang_m_b_post_edit['access_denied_hideshow'], 1);
    }
    elseif($_GET['act'] == "delete")
    {
        $access_edit_post = false;
    
        if(forum_options_topics($post['forum_id'], "delpost"))
            $access_edit_post = true;
        elseif(group_permission("local_delpost") AND $post['post_member_id'] == $member_id['user_id'])
        {
            $pc_time = intval($cache_group[$member_id['user_group']]['g_pc_time']) * 60 * 60;
            if ($pc_time AND ($post['post_date'] + $pc_time) < $time)
            {   
                message ($lang_message['access_denied'], str_replace("{info}", intval($cache_group[$member_id['user_group']]['g_pc_time']), $lang_m_b_post_edit['access_denied_time'])); 
            }
            else
                $access_edit_post = true;
        }
        else
            message ($lang_message['access_denied'], $lang_m_b_post_edit['access_denied_del']);
            
        if ($access_edit_post)
        {
            if ($post['new_topic'])
                message ($lang_message['error'], $lang_m_b_post_edit['can_not_del']); 
            else
            {
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
                    $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$post['topic_id']}'", "ORDER by post_date DESC LIMIT 1");
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
                
                $back_link = str_replace("{i_page}", intval($_GET['page']), navigation_topic_link($post['id'], $post['forum_id']));
                
                header( "Location: ".$back_link );
                exit();
            }
        }  
    }
    elseif($_GET['act'] == "fixed" OR $_GET['act'] == "unfixed")
    {
        if (forum_options_topics($post['forum_id'], "fixedpost"))
        {
            $logs_record_mas = array();
            $logs_record_mas['table'] = "logs_posts";
            $logs_record_mas['fid'] = $post['forum_id'];
            $logs_record_mas['tid'] = $post['topic_id'];
            $logs_record_mas['pid'] = $post['pid'];
            
            if ($_GET['act'] == "fixed")
            {
                $DB->update("fixed = '1'", "posts", "pid = '{$id}'");
                $DB->update("post_fixed = post_fixed+1", "topics", "id = '{$post['id']}'");
                    
                $logs_record_mas['act_st'] = 2;
            }
            else
            {
                $DB->update("fixed = '0'", "posts", "pid = '{$id}'");
                if ($post['post_fixed'])
                {
                    $DB->update("post_fixed = post_fixed-1", "topics", "id = '{$post['id']}'");
                }
                
                $logs_record_mas['act_st'] = 3;
            }
                
            logs_record ($logs_record_mas);
            
            $back_link = str_replace("{i_page}", intval($_GET['page']), navigation_topic_link($post['id'], $post['forum_id']))."#post".$id; 
            
            header( "Location: ".$back_link );
            exit();
        }
        else
            message ($lang_message['access_denied'], $lang_m_b_post_edit['access_denied_fix']);    
    }
    else
        header( "Location: {$_SESSION['back_link_board']}" );
        
    unset ($LB_upload);
}
else
    message ($lang_m_b_post_edit['not_found_post'], $lang_m_b_post_edit['not_found_post2'], 1);

?>