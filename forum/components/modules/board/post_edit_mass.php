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

$lang_m_b_post_edit_mass = language_forum ("board/modules/board/post_edit_mass");

if (!isset($_SESSION['back_link_board']) OR $_SESSION['back_link_board'] == "")
    $_SESSION['back_link_board'] = $redirect_url;

$errors = array();

$selected = $_POST['posts'];
$post_mass = array();
foreach	($selected as $id)
{
    $post_mass[] = intval( $id );
}
$posts = implode("|", $post_mass);

function here_loaction ()
{
    global $link_speddbar, $onl_location, $lang_m_b_post_edit_mass;
    
    $link_speddbar = speedbar_forum(0, true)."|".$lang_m_b_post_edit_mass['location_access_denied'];
    $onl_location = $lang_m_b_post_edit_mass['location_access_denied'];
}

if(!$selected AND !isset($_POST['editpost']))
{
    here_loaction ();
    message ($lang_message['access_denied'], $lang_m_b_post_edit_mass['no_posts'], 1);
}
elseif(!$selected AND isset($_POST['editpost']))
{
    here_loaction ();
    message ($lang_message['error'], $lang_m_b_post_edit_mass['no_posts_save'], 1);
}
elseif(count($post_mass) < 2 AND $_POST['moder_posts'] == "4")
{
    here_loaction ();
    message ($lang_message['error'], $lang_m_b_post_edit_mass['union_min'], 1);
}
elseif (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
    here_loaction ();
    message ($lang_message['access_denied'], $lang_message['secret_key'], 1);
}
elseif (!isset($_POST['moder_posts']))
{
    here_loaction ();
    message ($lang_message['access_denied'], $lang_message['no_act'], 1);
}
elseif (!member_publ_access(1))
{
    here_loaction ();
    
    $message_arr = array();
    $message_arr[] = $lang_m_b_post_edit_mass['access_denied_publ_access'];
    $message_arr[] = member_publ_info();
    
    message ($lang_message['access_denied'], $message_arr, 1);   
}
else
{
    $meta_info_other = $lang_m_b_post_edit_mass['location'];
    
    function check_access_mass($t_id = 0, $f_id = 0, $hide = 0)
    {
        global $lang_m_b_post_edit_mass, $member_id, $cache_group;
        
        $message = "";
        
        if($t_id AND !forum_permission($f_id, "read_forum"))
        {
            $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_post_edit_mass['access_denied_group_f']);
        }
        elseif ($t_id AND !forum_permission($f_id, "read_theme"))
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_post_edit_mass['access_denied_group_t']);
        }
        elseif($t_id AND forum_all_password($f_id))
        {
             $message = $lang_m_b_post_edit_mass['forum_pass'];
        }
        elseif($t_id AND !forum_options_topics($f_id, "hideshow") AND $hide)
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_post_edit_mass['hide_topic']);
        }
        
        return $message;        
    }
    
    
    if($_POST['moder_posts'] == "1" OR $_POST['moder_posts'] == "2")
    {
        $posts_db = $DB->join_select( "p.topic_id, p.hide, p.pid, p.post_member_id, p.new_topic, p.post_date, t.forum_id, t.title, t.id, t.date_last, t.hiden, t.last_post_id as topic_last_post_id, f.last_post_date, f.last_post_id as forum_last_post_id, f.last_topic_id", "LEFT", "posts p||topics t||forums f", "p.topic_id=t.id||t.forum_id=f.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'" );
                
        while ( $row = $DB->get_row($posts_db) )
        {            
            if (!forum_options_topics_mas($row['forum_id'], $row['id'], "showhide"))
            {
                $errors[] = $lang_m_b_post_edit_mass['access_denied_hideshow'];
                break;
            }
            elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
            {
                $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                break; 
            }
            else
            {
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_posts";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['topic_id'];
                $logs_record_mas['pid'] = $row['pid'];
                
                $where = array(); // обновление данных в теме
                $where2 = array(); // обновление данных в форуме
            
                if ($_POST['moder_posts'] == "2") // опубликование поста
                {
                    if (!$row['hide'])
                        continue;
                    
                    $DB->update("hide = '0'", "posts", "pid = '{$row['pid']}'");
                    
                    if ($cache_forums[$row['forum_id']]['postcount'])
                    {
                        $DB->prefix = DLE_USER_PREFIX;
                        $DB->update("posts_num = posts_num+1", "users", "user_id = '{$row['post_member_id']}'");
                    }
                    
                    if ($row['new_topic'])
                    {
                        $where[] = "hiden = '0'";
                        $cache_forums[$row['forum_id']]['topics'] += 1;
                        $cache_forums[$row['forum_id']]['topics_hiden'] -= 1;
                        $where2[] = "topics = topics+1, topics_hiden = topics_hiden-1";
                    }
                    else
                    {
                        $where[] = "post_hiden = post_hiden-1, post_num = post_num+1";
                        $where2[] = "posts_hiden = posts_hiden-1, posts = posts+1";
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= 1;
                        $cache_forums[$row['forum_id']]['posts'] += 1;
                    }
                
                    $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$row['topic_id']}' AND hide = '0'", "ORDER by post_date DESC LIMIT 1");
                    if ($post_last['pid'] == $row['pid'])
                    {
                        $where[] = "last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";
                    
                        if ($row['last_topic_id'] == $row['topic_id'])
                        {
                            $where2[] = "last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $post_last['pid'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $post_last['post_date'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                        }    
                    }
                
                    if ($row['last_post_date'] < $row['post_date'] AND $row['last_topic_id'] != $row['topic_id'])
                    {
                        $row['title'] = $DB->addslashes($row['title']);
                        $where2[] = "last_post_member = '{$post_last['post_member_name']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_title = '{$row['title']}', last_topic_id = '{$row['topic_id']}', last_post_id = '{$post_last['pid']}'";
                        $cache_forums[$row['forum_id']]['last_post_id'] = $post_last['pid'];
                        $cache_forums[$row['forum_id']]['last_topic_id'] = $row['topic_id'];
                        $cache_forums[$row['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                        $cache_forums[$row['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                        $cache_forums[$row['forum_id']]['last_post_date'] = $post_last['post_date'];
                        $cache_forums[$row['forum_id']]['last_title'] = $row['title'];  
                    }
                    $DB->free($post_last);
                    
                    $logs_record_mas['act_st'] = 5;
                }
                else
                {
                    if ($row['hide'])
                        continue;
                    
                    $DB->update("hide = '1'", "posts", "pid = '{$row['pid']}'");
                    
                    if ($cache_forums[$row['forum_id']]['postcount'])
                    {
                        $DB->prefix = DLE_USER_PREFIX;
                        $DB->update("posts_num = posts_num-1", "users", "user_id = '{$row['post_member_id']}'");
                    }
                    
                    if ($row['new_topic'])
                    {
                        $where[] = "hiden = '1'";
                        $cache_forums[$row['forum_id']]['topics'] -= 1;
                        $cache_forums[$row['forum_id']]['topics_hiden'] += 1;
                        $where2[] = "topics = topics-1, topics_hiden = topics_hiden+1";
                    }
                    else
                    {
                        $where[] = "post_hiden = post_hiden+1, post_num = post_num-1";
                        $where2[] = "posts_hiden = posts_hiden+1, posts = posts-1";
                        $cache_forums[$row['forum_id']]['posts_hiden'] += 1;
                        $cache_forums[$row['forum_id']]['posts'] -= 1;
                    }
                                        
                    $post_last = $DB->one_select( "pid, post_date, post_member_id, post_member_name", "posts", "topic_id = '{$row['topic_id']}' AND hide = '0'", "ORDER by post_date DESC LIMIT 1");
                    if (!$post_last['pid'])
                    {
                        $DB->free($post_last);
                        $post_last = $DB->one_select( "pid, post_date, post_member_id, post_member_name", "posts", "topic_id = '{$row['topic_id']}' AND new_topic = '1'", "LIMIT 1");
                    }
                        
                    if ($post_last['post_date'] <= $row['post_date'])
                    {                    
                        $where[] = "last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";
                    
                        if ($row['last_topic_id'] == $row['topic_id'])
                        {
                            $where2[] = "last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $post_last['pid'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $post_last['post_date'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                        }  
                    }
                    $DB->free($post_last);
                
                    if ($row['last_topic_id'] == $row['topic_id'] AND $row['new_topic'])
                    {
                        $topic_last = $DB->one_select( "id, title, member_name_last, last_post_member, date_last, last_post_id", "topics", "forum_id = '{$row['forum_id']}' AND id <> '{$row['topic_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                        if (!$topic_last['id'])
                        {
                            $where2[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = 0;
                            $cache_forums[$row['forum_id']]['last_topic_id'] = 0;
                            $cache_forums[$row['forum_id']]['last_post_member'] = "";
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = 0;
                            $cache_forums[$row['forum_id']]['last_post_date'] = "";
                            $cache_forums[$row['forum_id']]['last_title'] = "";
                        }
                        else
                        {
                            $topic_last['title'] = $DB->addslashes($topic_last['title']);
                            $where2[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$row['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$row['forum_id']]['last_title'] = $topic_last['title'];
                        }
                        $DB->free($topic_last);  
                    } 
                    
                    $logs_record_mas['act_st'] = 4;
                }
                
                logs_record ($logs_record_mas);
                unset ($logs_record_mas);
        
                $where_db = implode(", ", $where);
                $DB->update($where_db, "topics", "id = '{$row['topic_id']}'");
            
                $where_db2 = implode(", ", $where2);
                $DB->update($where_db2, "forums", "id = '{$row['forum_id']}'");
                forum_last_avatar ($row['forum_id']);
            
                unset($where);
                unset($where2);
            }
        }        
        $DB->free($posts_db);
                        
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            $cache->update("forums", $cache_forums);
            header( "Location: ".$_SESSION['back_link_board'] );
            exit();
        }
    }
    elseif($_POST['moder_posts'] == "3") // Редактирование
    {
        include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
        
        if (isset($_POST['editpost']))
        {            
            $posts_mass = explode("|", $posts);
            foreach ($posts_mass as $pid)
            {
                $post = $DB->one_join_select( "p.topic_id, p.hide, p.pid, p.moder_reason, p.moder_member_name, p.moder_member_id, t.forum_id, t.id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid = '{$pid}'" );
                
                if (!forum_options_topics_mas($post['forum_id'], $post['id'], "changepost"))
                {
                    $errors[] = $lang_m_b_post_edit_mass['access_denied_edit'];
                    break;
                }
                elseif(check_access_mass($post['topic_id'], $post['forum_id'], $post['hiden']))
                {
                    $errors[] = check_access_mass($post['topic_id'], $post['forum_id'], $post['hiden']);
                    break; 
                }
                else
                {
                    $bb_allowed_out = array();
                    if ($cache_forums[$post['forum_id']]['allow_bbcode'])
                    {
                        if ($cache_forums[$post['forum_id']]['allow_bbcode_list'] AND $cache_forums[$post['forum_id']]['allow_bbcode_list'] != "0")
                        {
                            $allow_bbcode_list = explode(",", $cache_forums[$post['forum_id']]['allow_bbcode_list']);
                            foreach($allow_bbcode_list as $value)
                            {
                                $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
                            }
                        }
                    }
                    
                    $_POST['text_'.$pid] = htmlspecialchars($_POST['text_'.$pid]);
                    filters_input ('post');
                    
                    if (utf8_strlen($_POST['text_'.$pid]) < intval($cache_config['posts_text_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['posts_text_min']['conf_value']), $lang_m_b_post_edit_mass['post_text_min']);
                    if (utf8_strlen($_POST['text_'.$pid]) > intval($cache_config['posts_text_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_post_edit_mass['post_text_max']);
                        
                    $_POST['text_'.$pid] = parse_word(html_entity_decode($_POST['text_'.$pid]), $cache_forums[$post['forum_id']]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));
                    $text = $DB->addslashes($_POST['text_'.$pid]);
                    
                    if (utf8_strlen($_POST['text_'.$pid]) > 65000) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_post_edit_mass['post_text_max_2']);
                       
                    unset($bb_allowed_out);
                       
                    $where = array();
                    $moder_reason = "";
                        
                    if(forum_options_topics($post['forum_id'], "changepost"))
                    {
                         $moder_reason = $DB->addslashes(trim(strip_tags($_POST['moder_reason_'.$pid])));
                         $where[] = "moder_reason = '{$moder_reason}'";
                         
                         if ($moder_reason != $post['moder_reason'])
                            $where[] = "moder_date = '{$time}'";
                         
                         if(intval($_POST['change_moder_'.$pid]) OR !$post['moder_member_name'])
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
                        
                    unset($where);
                    $edit_reason = $DB->addslashes(words_wilter(trim(strip_tags($_POST['edit_reason_'.$pid]))));
                        
                    if( ! $errors[0] )
                    {  
                        $DB->update("text = '{$text}', edit_date = '{$time}', edit_member_id = '{$member_id['user_id']}', edit_member_name = '{$member_id['name']}', edit_reason = '{$edit_reason}' {$where_bd}", "posts", "pid = '{$pid}'");
                        
                        $logs_record_mas = array();
                        $logs_record_mas['table'] = "logs_posts";
                        $logs_record_mas['fid'] = $post['forum_id'];
                        $logs_record_mas['tid'] = $post['topic_id'];
                        $logs_record_mas['pid'] = $post['pid'];
                        $logs_record_mas['act_st'] = 1;
                        
                        if ($post['text'] != stripslashes($text))
                        {
                            $logs_record_mas['info'] = str_replace("{old_post}", $post['text'], $lang_m_b_post_edit_mass['log_edit_text']);
                            $logs_record_mas['info'] = str_replace("{new_post}", stripslashes($text), $logs_record_mas['info']);
                            logs_record ($logs_record_mas);
                        }
                        
                        $logs_record_mas_info = array();
                        
                        if ($moder_reason == "" AND $post['moder_reason'] != "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", $post['moder_reason'], $lang_m_b_post_edit_mass['log_del_warning']);
                        }
                        elseif (stripslashes($moder_reason) != "" AND $post['moder_reason'] == "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", stripslashes($moder_reason), $lang_m_b_post_edit_mass['log_add_warning']);;
                        }
                        elseif (stripslashes($moder_reason) != $post['moder_reason'] AND $post['moder_reason'] != "")
                        {
                            $logs_record_mas_info[] = str_replace("{info}", $post['moder_reason']." -> ".stripslashes($moder_reason), $lang_m_b_post_edit_mass['log_edit_warning']);
                        }
                        
                        if((intval($_POST['change_moder']) AND $post['moder_member_name']) AND $moder_reason != "")
                        {
                            if ($post['moder_member_id'] != $member_id['user_id'])
                            {
                                $logs_record_mas_info[] = str_replace("{info}", $post['moder_member_name']." -> ".$member_id['name'], $lang_m_b_post_edit_mass['log_change_name_warning']);
                            }
                        }
                        
                        if ($logs_record_mas_info[0] != "")
                        {
                            $logs_record_mas['info'] = implode ("<br />", $logs_record_mas_info);
                            logs_record ($logs_record_mas);
                        }
                        
                        unset ($logs_record_mas);
                        unset ($logs_record_mas_info);
                    }
                }
            }
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".$_SESSION['back_link_board'] );
                exit ();
            }
        }
        else
        {
            $posts_db = $DB->join_select( "p.pid, p.topic_id, p.post_member_name, p.edit_reason, p.moder_reason, p.moder_member_name, p.moder_member_id, p.text, t.forum_id, t.title, t.id, t.hiden, t.last_post_id as topic_last_post_id, f.last_post_id as forum_last_post_id", "LEFT", "posts p||topics t||forums f", "p.topic_id=t.id||t.forum_id=f.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'", "ORDER BY post_date ASC" );

            $link_speddbar = speedbar_forum(0, true)."|".$lang_m_b_post_edit_mass['location'];
            $onl_location = $lang_m_b_post_edit_mass['location'];
        
            $i = 0;
            $script_bb = false;
            $tpl->load_template ( 'board/post_edit_mass.tpl' );
            while ( $row = $DB->get_row($posts_db) )
            { 
                if (!forum_options_topics_mas($row['forum_id'], $row['id'], "changepost"))
                {
                    $errors[] = $lang_m_b_post_edit_mass['access_denied_edit'];
                    break;
                }
                elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
                {
                    $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                    break; 
                }
                
                $i ++;
                
                if ($cache_forums[$row['forum_id']]['allow_bbcode'])
                {
                    $bb_allowed_out = array();
                    if ($cache_forums[$row['forum_id']]['allow_bbcode'])
                    {
                        if ($cache_forums[$row['forum_id']]['allow_bbcode_list'] AND $cache_forums[$row['forum_id']]['allow_bbcode_list'] != "0")
                        {
                            $allow_bbcode_list = explode(",", $cache_forums[$row['forum_id']]['allow_bbcode_list']);
                            foreach($allow_bbcode_list as $value)
                            {
                                $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
                            }
                        }
                    }
                    
                    require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
                    if ($script_bb)
                        $tpl->tags('{bbcode}', $bbcode); 
                    else
                        $tpl->tags('{bbcode}', $bbcode_script.$bbcode);
                        
                    $script_bb = true;
                    
                    unset($bb_allowed_out);
                }
                else
                    $tpl->tags('{bbcode}', "");
                
                $tpl->tags('{author}', $row['post_member_name']);
                $tpl->tags('{topic}', $row['title']);
                $tpl->tags('{pid}', $row['pid']);
                $tpl->tags('{text}', parse_back_word($row['text'], true, intval($cache_group[$member_id['user_group']]['g_html_allowed'])));
                $tpl->tags('{edit_reason}', $row['edit_reason']);
                
                if(forum_options_topics($row['forum_id'], "changepost"))
                {
                    $tpl->tags_blocks("moder_warning");
                    $tpl->tags('{moder_reason}', $row['moder_reason']);
                    if ($row['moder_member_name'])
                    {
                        $tpl->tags('{moder_member_name}', $row['moder_member_name']);
                        $tpl->tags('{moder_member_link}', profile_link($row['moder_member_name'], $row['moder_member_id']));
                    }
                    else
                    {
                        $tpl->tags('{moder_member_name}', $lang_m_b_post_edit_mass['moder_member_name']);
                        $tpl->tags('{moder_member_link}', "#");
                    }
                }
                else
                    $tpl->tags_blocks("moder_warning", false);
                
                $tpl->compile('posts');
            }        
            $DB->free($posts_db);
            $tpl->clear();
            
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else 
            {
                if($i)
                {
                    $tpl->load_template ( 'board/post_edit_mass_global.tpl' );    
                    $tpl->tags('{post_edit_form}', $tpl->result['posts']);
                    $tpl->tags_blocks("mass_union", false);
                    $tpl->tags('{moder_posts}', "3");
                    $tpl->tags('{title}', $lang_m_b_post_edit_mass['location']);
                    $tpl->compile('content');
                    $tpl->clear();
                }
                else
                    message ($lang_message['access_denied'], $lang_m_b_post_edit_mass['access_denied_edit'], 1);
            }
        }
    }
    elseif($_POST['moder_posts'] == "4") // Объединение
    {              
        if (isset($_POST['editpost']))
        {
            $topic_id = intval($_POST['topic_id']);
            $author = intval($_POST['author']);
            $datepost = intval($_POST['datepost']);
            
            $post_mass_bd_check = array();
            $new_topic = false;
            $post_m = 0;
            $post_h = 0;
            
            $posts_db = $DB->join_select( "p.pid, p.topic_id, p.hide, p.new_topic, p.attachments, p.post_member_name, p.post_member_id, p.post_date, t.forum_id, t.title, t.id, t.hiden, t.last_post_id as topic_last_post_id, f.last_post_id as forum_last_post_id", "LEFT", "posts p||topics t||forums f", "p.topic_id=t.id||t.forum_id=f.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'", "ORDER BY post_date ASC" );
            while ( $row = $DB->get_row($posts_db) )
            { 
                $topic_sp_title = $row['title'];
                $topic_sp_id = $row['topic_id'];
                $topic_sp_forum = $row['forum_id'];
                    
                if (!forum_options_topics_mas($row['forum_id'], $row['id'], "unionpost"))
                {
                    $errors[] = $lang_m_b_post_edit_mass['access_denied_union'];
                    break;
                }
                elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
                {
                    $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                    break; 
                }
                elseif ($row['topic_id'] != $topic_id)
                {
                    $errors[] = $lang_m_b_post_edit_mass['access_denied_others_topics'];
                    break;
                }
                else
                {
                    if ($row['new_topic'])
                        $new_topic = true;
                        
                    if (!$row['hide'] AND !$row['new_topic'])
                    {
                        $cache_forums[$row['forum_id']]['posts'] -= 1;
                        $post_m ++;
                    }
                    elseif ($row['hide'] AND !$row['new_topic'])
                    {
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= 1;
                        $post_h ++;
                    }
                        
                    $forum_id = $row['forum_id'];
                        
                    $post_mass_bd_check['author'][] = $row['post_member_name'];
                    $post_mass_bd_check['author_id'][] = $row['post_member_id'];
                    $post_mass_bd_check['post_date'][] = $row['post_date'];
                    $post_mass_bd_check['topic_id'][] = $row['topic_id'];
                    $post_mass_bd_check['hide'][] = $row['hide'];
                    
                    if ($row['attachments'])
                        $post_mass_bd_check['attachments'][] = $row['attachments'];
                        
                    $post_mass_bd_check['pid'][] = $row['pid'];
                }
            }        
            $DB->free($posts_db);
            
            $link_speddbar = speedbar_forum ($topic_sp_forum);
            $link_speddbar .= "|<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>|".$lang_m_b_post_edit_mass['location_union'];
            $onl_location = str_replace("{title}", "<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>", $lang_m_b_post_edit_mass['location_online_union']);
            
            $post_mass_bd_check['author'] = array_unique($post_mass_bd_check['author']);
            $post_mass_bd_check['author_id'] = array_unique($post_mass_bd_check['author_id']);
            $find_author = false;
            $author_name = "";
            $i = 0;
            foreach ($post_mass_bd_check['author_id'] as $author_id)
            {
                if ($author_id == $author)
                {
                    $author_name = $post_mass_bd_check['author'][$i];
                    $find_author = true;
                    break;
                }
                $i ++;
            }
                
            $find_date = false;
            foreach ($post_mass_bd_check['post_date'] as $p_date)
            {
                if ($p_date == $datepost)
                {
                    $find_date = true;
                    break;
                }
            }

            if (!$find_author OR !$author_name)
                $errors[] = $lang_m_b_post_edit_mass['union_author'];
                
            if (!$find_date)
                $errors[] = $lang_m_b_post_edit_mass['union_date'];
            
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else 
            {
                unset($errors);
                $errors = array();
                
                $bb_allowed_out = array();
                if ($cache_forums[$forum_id]['allow_bbcode'])
                {
                    if ($cache_forums[$forum_id]['allow_bbcode_list'] AND $cache_forums[$forum_id]['allow_bbcode_list'] != "0")
                    {
                        $allow_bbcode_list = explode(",", $cache_forums[$forum_id]['allow_bbcode_list']);
                        foreach($allow_bbcode_list as $value)
                        {
                            $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
                        }
                    }
                }
                
                $_POST['text'] = htmlspecialchars($_POST['text']);
                
                filters_input ('post');
                
                if (utf8_strlen($_POST['text']) < intval($cache_config['posts_text_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['posts_text_min']['conf_value']), $lang_m_b_post_edit_mass['post_text_min']);
                if (utf8_strlen($_POST['text']) > intval($cache_config['posts_text_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_post_edit_mass['post_text_max']);
                        
                $_POST['text'] = parse_word(html_entity_decode($_POST['text']), $cache_forums[$forum_id]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));
                $text = $DB->addslashes($_POST['text']);
                
                if (utf8_strlen($_POST['text']) > 65000) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_post_edit_mass['post_text_max_2']);
                        
                $edit_reason = $DB->addslashes(words_wilter(trim(strip_tags($_POST['edit_reason']))));
                        
                if( ! $errors[0] )
                {  
                    $all_hide = true;
                    $hide_post = 1;
                    foreach($post_mass_bd_check['hide'] as $hide_p)
                    {
                        if (!$hide_p)
                        {
                            $all_hide = false;
                            $hide_post = 0;
                            break;
                        }
                    }
                    
                    $attachments = implode(",", $post_mass_bd_check['attachments']);
                    
                    $DB->insert("topic_id = '{$topic_id}', new_topic = '0', text = '{$text}', attachments = '{$attachments}', hide = '{$hide_post}', post_date = '{$datepost}', post_member_id = '{$author}', post_member_name = '{$author_name}', ip = '{$_IP}', edit_date = '{$time}', edit_member_id = '{$member_id['user_id']}', edit_member_name = '{$member_id['name']}', edit_reason = '{$edit_reason}'", "posts");
                    $new_post_id = $DB->insert_id();
                    
                    if ($attachments)
                    {
                        foreach($post_mass_bd_check['pid'] as $value)
                        {
                            $DB->update("file_pid = '{$new_post_id}'", "topics_files", "file_pid = '{$value}'");
                        }
                    }
                    
                    if ($cache_forums[$forum_id]['postcount'])
                    {
                        foreach ($post_mass_bd_check['author_id'] as $author_id)
                        {
                            if ($author_id != $author)
                            {
                                $DB->prefix = DLE_USER_PREFIX;
                                $DB->update("posts_num = posts_num-1", "users", "user_id = '{$author_id}'");
                            }
                        }
                    }
                    
                    if (!$all_hide)
                    {
                        $cache_forums[$forum_id]['posts'] += 1;
                    }
                    else
                    {
                        $post_m = 0;
                        if (!$new_topic)
                        {
                            $post_h -= 1;
                            $cache_forums[$forum_id]['posts_hiden'] += 1;
                        }
                    }
                    
                    $posts_mass = explode("|", $posts);
                    foreach ($posts_mass as $pid)
                    {
                        $DB->delete("pid = '{$pid}'", "posts");
                    }
                    
                    $where_hide = array();
                    $where_hide2 = array();
                    
                    if ($post_h)
                    {
                        $where_hide[] = "post_hiden = post_hiden-{$post_h}";
                        $where_hide2[] = "posts_hiden = posts_hiden-{$post_h}";
                    }
                    
                    if ($new_topic)
                    {                        
                        $post_first_new_t = $DB->one_join_select( "p.pid, post_member_name, post_member_id, hide, t.forum_id, t.id, t.last_post_id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "topic_id='{$topic_id}'", "ORDER BY post_date ASC LIMIT 1" );
                        $DB->update("new_topic = '1'", "posts", "pid = '{$post_first_new_t['pid']}'");
                        
                        $where_hide[] = "post_id = '{$post_first_new_t['pid']}', member_name_open = '{$post_first_new_t['post_member_name']}', member_id_open = '{$post_first_new_t['post_member_id']}', post_num = post_num-{$post_m}";
                        $where_hide2[] = "posts = posts-{$post_m}";
                        
                        if ($post_first_new_t['hide'] AND !$post_first_new_t['hiden'])
                        {
                            $where_hide[] = "hiden = '1'";
                            $where_hide2[] = "posts_hiden = posts_hiden-1, topics_hiden = topics_hiden+1, topics = topics-1";
                            $cache_forums[$post_first_new_t['forum_id']]['topics'] -= 1;
                            $cache_forums[$post_first_new_t['forum_id']]['topics_hiden'] += 1;
                            $cache_forums[$post_first_new_t['forum_id']]['posts_hiden'] -= 1;
                        }
                        elseif (!$post_first_new_t['hide'] AND $post_first_new_t['hiden'])
                        {
                            $where_hide[] = "hiden = '0'";
                            $where_hide2[] = "topics_hiden = topics_hiden-1, topics = topics+1";
                            $cache_forums[$post_first_new_t['forum_id']]['topics'] += 1;
                            $cache_forums[$post_first_new_t['forum_id']]['topics_hiden'] -= 1;
                        }
                        
                        $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$topic_id}' AND hide = '0'", "ORDER by post_date DESC LIMIT 1");
                        if (!$post_last['pid'])
                        {
                            $DB->free($post_last);
                            $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$topic_id}' AND new_topic = '1'", "LIMIT 1");
                        }
                        
                        if ($post_last['pid'] != $post_first_new_t['last_post_id'])
                        {
                            $where_hide[] = "last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";                            
                            $where_hide2[] = "last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                    
                            $cache_forums[$post_first_new_t['forum_id']]['last_post_id'] = $post_last['pid'];
                            $cache_forums[$post_first_new_t['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                            $cache_forums[$post_first_new_t['forum_id']]['last_post_date'] = $post_last['post_date'];
                            $cache_forums[$post_first_new_t['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                            
                        }
                        $DB->free($post_last);

                        $where_hide_db = implode(", ", $where_hide);
                        $where_hide_db2 = implode(", ", $where_hide2);
                         
                        $DB->update($where_hide_db, "topics", "id = '{$topic_id}'");
                        $DB->update($where_hide_db2, "forums", "id = '{$post_first_new_t['forum_id']}'");
                        forum_last_avatar ($post_first_new_t['forum_id']);
                    }
                    else
                    {
                        if (!$all_hide)
                            $post_m -= 1;
                            
                        $post_last = $DB->one_join_select( "p.pid, p.post_member_id, p.post_date, p.post_member_name, t.forum_id, t.id, t.last_post_id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "topic_id='{$topic_id}' AND hide = '0'", "ORDER BY post_date DESC LIMIT 1" );
                               
                        $where_hide[] = "post_num = post_num-{$post_m}";                  
                        $where_hide2[] = "posts = posts-{$post_m}";
                                        
                        if ($post_last['pid'] != $new_post_id)
                        {
                            $where_hide[] = "last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";                           
                            $where_hide2[] = "last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                            
                            $cache_forums[$post_last['forum_id']]['last_post_id'] = $post_last['pid'];
                            $cache_forums[$post_last['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                            $cache_forums[$post_last['forum_id']]['last_post_date'] = $post_last['post_date'];
                            $cache_forums[$post_last['forum_id']]['last_post_member'] = $post_last['post_member_name'];  
                        }
                        $DB->free($post_last);
                        
                        $where_hide_db = implode(", ", $where_hide);
                        $where_hide_db2 = implode(", ", $where_hide2);
                    
                        $DB->update($where_hide_db, "topics", "id = '{$topic_id}'");
                        $DB->update($where_hide_db2, "forums", "id = '{$post_last['forum_id']}'");
                        forum_last_avatar ($post_last['forum_id']);
                    }              
                    $cache->update("forums", $cache_forums);
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_posts";
                    $logs_record_mas['fid'] = $forum_id;
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['pid'] = $new_post_id;
                    $logs_record_mas['act_st'] = 7;
                    $logs_record_mas['info'] = str_replace("{id}", implode(", ", $post_mass_bd_check['pid']), $lang_m_b_post_edit_mass['log_union']);
                    logs_record ($logs_record_mas);
                }
                
                if( $errors[0] )
                    message ($lang_message['error'], $errors, 1);
                else
                {
                    header( "Location: ".$_SESSION['back_link_board'] );
                    exit ();
                }
            }
        }
        else
        {
            $posts_db = $DB->join_select( "p.topic_id, p.pid, p.text, p.post_member_name, p.post_member_id, p.post_date, p.hide, t.forum_id, t.title, t.id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'", "ORDER BY post_date ASC" );
       
            $post_mass_bd = array();
            $i = 0;
            while ( $row = $DB->get_row($posts_db) )
            { 
                $i ++;
                $topic_sp_title = $row['title'];
                $topic_sp_id = $row['topic_id'];
                $topic_sp_forum = $row['forum_id'];
                    
                if (!forum_options_topics_mas($row['forum_id'], $row['id'], "unionpost"))
                {
                    $errors[] = $lang_m_b_post_edit_mass['access_denied_union'];
                    break;
                }
                elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
                {
                    $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                    break; 
                }
                else
                {    
                    $post_mass_bd['text'][] = $row['text'];
                    $post_mass_bd['title'] = $row['title'];
                    $post_mass_bd['author'][] = $row['post_member_name'];
                    $post_mass_bd['author_id'][] = $row['post_member_id'];
                    $post_mass_bd['post_date'][] = $row['post_date'];
                    $post_mass_bd['topic_id'][] = $row['topic_id'];
                    $post_mass_bd['pid'][] = $row['pid'];
                }
            }        
            $DB->free($posts_db);
            $tpl->clear();
        
            $link_speddbar = speedbar_forum ($topic_sp_forum);
            $link_speddbar .= "|<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>|".$lang_m_b_post_edit_mass['location_union'];
            $onl_location = str_replace("{title}", "<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>", $lang_m_b_post_edit_mass['location_online_union']);
            
            if (!$i)
                $errors[] = $lang_m_b_post_edit_mass['not_found_posts'];
        
            if( $errors[0] )
                message ($lang_message['access_denied'], $errors, 1);
            else
            {
                $topic_id = 0;
                $topic_one = true;
                foreach($post_mass_bd['topic_id'] as $tid)
                {
                    if ($topic_id != 0 AND $topic_id != $tid)
                    {
                        $topic_one = false;
                        break;
                    }
                    else
                        $topic_id = $tid;
                }
            
                if ($topic_one)
                {
                    $tpl->load_template ( 'board/post_edit_mass_union.tpl' );
                    $tpl->tags('{author}', $row['post_member_name']);
                    
                    if ($cache_forums[$topic_sp_forum]['allow_bbcode'])
                    {
                        require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
                        $tpl->tags('{bbcode}', $bbcode_script.$bbcode); 
                    }
                    else
                        $tpl->tags('{bbcode}', "");
                
                    $author_op = "";
                    $i = 0;
                    $post_mass_bd['author'] = array_unique($post_mass_bd['author']);
                    $post_mass_bd['author_id'] = array_unique($post_mass_bd['author_id']);
                    foreach ($post_mass_bd['author'] as $author)
                    {
                        $author_op .= "<option value=\"".$post_mass_bd['author_id'][$i]."\">".$author."</option>";
                        $i ++;
                    }
                
                    $date_op = "";
                    foreach ($post_mass_bd['post_date'] as $p_date)
                    {
                        $date_op .= "<option value=\"".$p_date."\">".formatdate($p_date)."</option>";
                    }
                
                    $pid_input = "";
                    foreach ($post_mass_bd['pid'] as $pid_id)
                    {
                        $pid_input .= "<input type=\"hidden\" name=\"posts[]\" value=\"".$pid_id."\" />\r";
                    }
                
                    $tpl->tags('{topic}', $post_mass_bd['title']);
                
                    $tpl->tags('{date_op}', $date_op);
                    $tpl->tags('{author_op}', $author_op);
            
                    $tpl->tags('{text}', parse_back_word(implode("\n\r\n\r", $post_mass_bd['text']), true, intval($cache_group[$member_id['user_group']]['g_html_allowed'])));
                    $tpl->compile('posts');
                    $tpl->clear();
                
                    $tpl->load_template ( 'board/post_edit_mass_global.tpl' );    
                    $tpl->tags('{post_edit_form}', $tpl->result['posts']);
                    $tpl->tags('{moder_posts}', "4");
                    $tpl->tags('{pid}', $pid_input);
                    $tpl->tags('{topic_id}', $topic_id);
                    $tpl->tags('{title}', $lang_m_b_post_edit_mass['location_union']);
                
                    $tpl->tags_blocks("mass_union");
                
                    $tpl->compile('content');
                    $tpl->clear();
                }
                else
                    message ($lang_message['access_denied'], $lang_m_b_post_edit_mass['access_denied_others_topics'], 1);
            }
        }
    }
    elseif($_POST['moder_posts'] == "5") // Перемещение
    {
        if (isset($_POST['editpost']))
        {
            $move_id = intval($_POST['move_id']);
            $new_date = intval($_POST['new_date']);
             
            $topic_check = $DB->one_join_select( "t.id, t.date_last, t.title, t.forum_id, f.last_topic_id, f.last_post_id as f_post_id, f.last_post_date, f.last_title, f.last_post_member_id, f.last_post_member as f_member", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.id='{$move_id}'" );
            if (!$topic_check['id'])
                $errors[] = $lang_m_b_post_edit_mass['not_found_topic'];
            else
            {
                $i = 0;
                $posts_db = $DB->join_select( "p.pid, p.topic_id, p.hide, p.new_topic, p.attachments, p.post_date, p.post_member_id, p.post_member_name, t.forum_id, t.title, t.id, t.hiden, t.last_post_id as topic_last_post_id, f.last_post_id as forum_last_post_id", "LEFT", "posts p||topics t||forums f", "p.topic_id=t.id||t.forum_id=f.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'", "ORDER BY post_date ASC" );
                while ( $row = $DB->get_row($posts_db) )
                { 
                    $i ++;
                    $topic_sp_title = $row['title'];
                    $topic_sp_id = $row['topic_id'];
                    $topic_sp_forum = $row['forum_id'];
                    
                    if (!forum_options_topics_mas($row['forum_id'], $row['id'], "movepost"))
                    {
                        $errors[] = $lang_m_b_post_edit_mass['access_denied_move'];
                        break;
                    }
                    elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
                    {
                        $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                        break; 
                    }
                    elseif ($new_date AND !forum_options_topics_mas($row['forum_id'], $row['id'], "movepost_date"))
                    {
                        $errors[] = $lang_m_b_post_edit_mass['access_denied_move_date'];
                        break; 
                    }
                    
                    if ($row['new_topic'])
                    {
                        $errors[] = $lang_m_b_post_edit_mass['access_denied_move_first'];
                        break;
                    }
                    else
                    {
                        $post_mass_bd[$row['pid']] = array ();
                        foreach ($row as $key => $value)
                        {
                            if ($new_date AND $key == "post_date")
                            {
                                $value = $time+$i;
                            }
                            
                            $post_mass_bd[$row['pid']][$key] = $value;
                        }
                    
                        $post_mass_bd_c['topic_id'][] = $row['topic_id'];
                        $post_mass_bd_c['pid'][] = $row['pid'];
                    }
                }
                $DB->free($posts_db);
                
                $link_speddbar = speedbar_forum ($topic_sp_forum);            
                $link_speddbar .= "|<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>|".$lang_m_b_post_edit_mass['location_move'];
                $onl_location = str_replace("{title}", "<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>", $lang_m_b_post_edit_mass['location_online_move']);
                
                if (!$i) $errors[] = $lang_m_b_post_edit_mass['not_found_posts'];
               
                if( $errors[0] )
                    message ($lang_message['access_denied'], $errors, 1);
                else
                {      
                    $topic_id = 0;
                    $topic_one = true;
                    foreach($post_mass_bd_c['topic_id'] as $tid)
                    {
                        if ($topic_id != 0 AND $topic_id != $tid)
                        {
                            $topic_one = false;
                            break;
                        }
                        else
                            $topic_id = $tid;
                    }
            
                    if ($topic_one)
                    {
                        foreach ($post_mass_bd as $row)
                        {
                            $where = array(); // обновление данных о последнем посте в новой теме
                            $where2 = array(); // обновление данные о пследнем посте в новом форуме
                            $where3 = array(); // обновление данных о последнем посте в старом форуме
                            $where4 = array(); // обновление данных о последнем посте в старой теме
                        
                            if ($topic_check['date_last'] < $row['post_date'] AND $row['hide'] == 0)
                            {
                                $where[] = "date_last = '{$row['post_date']}', last_post_id = '{$row['pid']}', last_post_member = '{$row['post_member_id']}', member_name_last = '{$row['post_member_name']}'";
                                if($topic_check['last_post_date'] < $row['post_date'])
                                {
                                    $cache_forums[$topic_check['forum_id']]['last_post_id'] = $row['pid'];
                                    $cache_forums[$topic_check['forum_id']]['last_post_member_id'] = $row['post_member_id'];
                                    $cache_forums[$topic_check['forum_id']]['last_post_date'] = $row['post_date'];
                                    $cache_forums[$topic_check['forum_id']]['last_post_member'] = $row['post_member_name'];
                                    $where2[] = "last_post_id = '{$row['pid']}', last_post_member_id = '{$row['post_member_id']}', last_post_date = '{$row['post_date']}', last_post_member = '{$row['post_member_name']}'";
                                }
                                
                            }    
                            if ($row['hide'])
                            {
                                $where[] = "post_hiden = post_hiden+1";
                                $cache_forums[$topic_check['forum_id']]['posts_hiden'] += 1;
                                $cache_forums[$row['forum_id']]['posts_hiden'] -= 1;
                                $where2[] = "posts_hiden = posts_hiden+1";
                                $where3[] = "posts_hiden = posts_hiden-1";
                                $where4[] = "post_hiden = post_hiden-1";
                            }
                            else
                            {
                                $where[] = "post_num = post_num+1";
                                $cache_forums[$topic_check['forum_id']]['posts'] += 1;
                                $cache_forums[$row['forum_id']]['posts'] -= 1;
                                $where2[] = "posts = posts+1";
                                $where3[] = "posts = posts-1";
                                $where4[] = "post_num = post_num-1";
                            }
                            
                            $post_last = $DB->one_select( "pid, post_member_id, post_date, post_member_name", "posts", "topic_id = '{$row['topic_id']}' AND hide = '0' AND pid <> '{$row['pid']}'", "ORDER by post_date DESC LIMIT 1");
                            if ($post_last['post_date'] < $row['post_date'] AND $row['hide'] == 0)
                            {
                                $where4[] = "date_last = '{$post_last['post_date']}', last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', member_name_last = '{$post_last['post_member_name']}'";
                                                            
                                if ($row['pid'] == $cache_forums[$row['forum_id']]['last_post_id'])
                                {
                                    $cache_forums[$row['forum_id']]['last_post_id'] = $post_last['pid'];
                                    $cache_forums[$row['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                                    $cache_forums[$row['forum_id']]['last_post_date'] = $post_last['post_date'];
                                    $cache_forums[$row['forum_id']]['last_post_member'] = $post_last['post_member_name'];  
                                    $where3[] = "last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                                }
                                
                            }
                            elseif ($row['pid'] == $cache_forums[$row['forum_id']]['last_post_id'])
                            {          
                                $topic_last = $DB->one_select( "id, forum_id, last_post_id, last_post_member, date_last, member_name_last", "topics", "forum_id='{$row['forum_id']}'", "", "ORDER BY date_last DESC LIMIT 1" );
                                $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                                $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                                $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                                $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];  
                                $where3[] = "last_post_id = '{$topic_last['last_post_id']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_post_member = '{$topic_last['member_name_last']}'";
                                $DB->free($topic_last);
                            }
                            $DB->free($post_last);
                            
                            $where_bd = implode(", ", $where);
                            $where_bd4 = implode(", ", $where4);
                               
                            $DB->update($where_bd, "topics", "id = '{$move_id}'");
                            $DB->update($where_bd4, "topics", "id = '{$row['topic_id']}'");
                                                        
                            $where_bd2 = implode(", ", $where2);
                            $where_bd3 = implode(", ", $where3);
                            
                            $DB->update($where_bd2, "forums", "id = '{$topic_check['forum_id']}'");
                            forum_last_avatar ($topic_check['forum_id']);
                            
                            $DB->update($where_bd3, "forums", "id = '{$row['forum_id']}'");
                            forum_last_avatar ($row['forum_id']);
                            
                            $DB->update("topic_id = '{$move_id}', post_date = '{$row['post_date']}'", "posts", "pid = '{$row['pid']}'");
                            
                            if ($row['attachments'])
                                $DB->update("file_tid = '{$move_id}', file_fid = '{$topic_check['forum_id']}'", "topics_files", "file_pid = '{$row['pid']}'");
                            
                            if ($cache_forums[$row['forum_id']]['postcount'] != $cache_forums[$topic_check['forum_id']]['postcount'])
                            {
                                if ($cache_forums[$row['forum_id']]['postcount'] == 0 AND $cache_forums[$topic_check['forum_id']]['postcount'] == 1)
                                {
                                    $DB->prefix = DLE_USER_PREFIX;
                                    $DB->update("posts_num = posts_num+1", "users", "user_id = '{$author_id}'");
                                }
                                elseif ($cache_forums[$row['forum_id']]['postcount'] == 1 AND $cache_forums[$topic_check['forum_id']]['postcount'] == 0)
                                {
                                    $DB->prefix = DLE_USER_PREFIX;
                                    $DB->update("posts_num = posts_num-1", "users", "user_id = '{$author_id}'");
                                }
                            }
                                
                            unset($where);
                            unset($where2);
                            unset($where3);
                            unset($where4);
                            
                            $logs_record_mas = array();
                            $logs_record_mas['table'] = "logs_posts";
                            $logs_record_mas['fid'] = $topic_check['forum_id'];
                            $logs_record_mas['tid'] = $move_id;
                            $logs_record_mas['pid'] = $row['pid'];
                            $logs_record_mas['act_st'] = 6;
                            $logs_record_mas['info'] = str_replace("{title}", $topic_check['title'], $lang_m_b_post_edit['log_move']);
                            $logs_record_mas['info'] = str_replace("{id}", $topic_check['id'], $logs_record_mas['info']);
                            logs_record ($logs_record_mas);
                            unset($logs_record_mas);
                        }
                        $cache->update("forums", $cache_forums);
                    }
                }
            }
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".$_SESSION['back_link_board'] );
                exit ();
            }
        }
        else
        {
            $posts_db = $DB->join_select( "p.topic_id, p.pid, p.new_topic, t.forum_id, t.title, t.id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'", "ORDER BY p.post_date ASC" );
       
            $post_mass_bd = array();
            $post_mass_bd_c = array();
        
            $i = 0;
        
            while ( $row = $DB->get_row($posts_db) )
            { 
                $i ++;
                $topic_sp_title = $row['title'];
                $topic_sp_id = $row['topic_id'];
                $topic_sp_forum = $row['forum_id'];
                
                if (!forum_options_topics_mas($row['forum_id'], $row['id'], "movepost"))
                {
                    $errors[] = $lang_m_b_post_edit['access_denied_move'];
                    break;
                }
                elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
                {
                    $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                    break; 
                }
                    
                if ($row['new_topic'])
                {
                    $errors[] = $lang_m_b_post_edit['access_denied_move_first'];
                    break;
                }
                else
                {                        
                    $post_mass_bd_c['topic_id'][] = $row['topic_id'];
                    $post_mass_bd_c['pid'][] = $row['pid'];
                }
            }        
            $DB->free($posts_db);
            $tpl->clear();
            
            $link_speddbar = speedbar_forum ($topic_sp_forum);
            $link_speddbar .= "|<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>|".$lang_m_b_post_edit_mass['location_move'];
            $onl_location = str_replace("{title}", "<a href=".topic_link($topic_sp_id, $topic_sp_forum).">".$topic_sp_title."</a>", $lang_m_b_post_edit_mass['location_online_move']);
            
            if (!$i)
                $errors[] = $lang_m_b_post_edit_mass['not_found_posts'];
               
            if( $errors[0] )
                message ($lang_message['access_denied'], $errors, 1);
            else
            {
                $topic_id = 0;
                $topic_one = true;
                foreach($post_mass_bd_c['topic_id'] as $tid)
                {
                    if ($topic_id != 0 AND $topic_id != $tid)
                    {
                        $topic_one = false;
                        break;
                    }
                    else
                        $topic_id = $tid;
                }
            
                if ($topic_one)
                {
                    $i = 0;
                    
                    include LB_CLASS.'/posts_out.php';
                    $LB_posts = new LB_posts;
    
                    $posts_out = implode ("|", $post_mass_bd_c['pid']);
                    
                    $DB->prefix = array( 2 => DLE_USER_PREFIX );
                    $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, u.user_id, u.banned, u.user_group, u.foto, u.signature, u.posts_num, t.forum_id, t.status, t.member_id_open", "LEFT", "posts p||topics t||users u||members_online mo", "p.topic_id=t.id||p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", "p.pid regexp '[[:<:]](".$posts_out.")[[:>:]]'", "ORDER by p.post_date DESC LIMIT 10" );
                    $LB_posts->Data_out("board/reply_last_posts.tpl", "posts");
                    
                    unset($LB_posts);
                
                    $tpl->load_template ( 'board/post_edit_mass_move.tpl' );    
                    $tpl->tags('{posts}', $tpl->result['posts']);
                    $tpl->tags('{moder_posts}', "5");
                    
                    $pid_input = "";
                    foreach ($post_mass_bd_c['pid'] as $pid_id)
                    {
                        $pid_input .= "<input type=\"hidden\" name=\"posts[]\" value=\"".$pid_id."\" />\r";
                    }
                    
                    $tpl->tags_blocks("new_date", forum_options_topics_mas($topic_sp_forum, 0, "movepost_date"));
                    
                    $tpl->tags('{pid}', $pid_input);
                    $tpl->tags('{topic_title}', $topic_sp_title);
                
                    $tpl->compile('content');
                    $tpl->clear();
                }
                else
                    message ($lang_message['access_denied'], $lang_m_b_post_edit_mass['access_denied_others_topics'], 1);
            }
        } 
    }
    elseif($_POST['moder_posts'] == "6") // Удаление
    {
        $posts_db = $DB->join_select( "p.pid, p.topic_id, p.hide, p.text, p.attachments, p.post_member_id, p.post_member_name, p.post_date, t.forum_id, t.id, t.hiden, t.last_post_id as topic_last_post_id, f.last_post_id as forum_last_post_id", "LEFT", "posts p||topics t||forums f", "p.topic_id=t.id||t.forum_id=f.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'" );
        while ( $row = $DB->get_row($posts_db) )
        {
            if (!forum_options_topics_mas($row['forum_id'], $row['id'], "delpost"))
            {
                $errors[] = $lang_m_b_post_edit_mass['access_denied_del'];
                break;
            }
            elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
            {
                $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                break; 
            }
            else
            {
                if ($row['new_topic'])
                {
                    $errors[] = $lang_m_b_post_edit_mass['access_denied_del_first'];
                    break; 
                }
                else
                {
                    $DB->delete("pid = '{$row['pid']}'", "posts");
                    
                    if ($row['attachments'])
                    {
                        include_once LB_CLASS. "/upload_files.php";
                        $LB_upload = new LB_Upload();
                    
                        $attachments = explode (",", $row['attachments']);
                        foreach ($attachments as $value)
                        {
                            $LB_upload->Del_Record($value, $secret_key);
                        }
                        
                        unset($LB_upload);
                    }
                
                    $where = "";
                    $where2 = "";
                    if ($row['hide'])
                    {
                        $where = "post_hiden = post_hiden-1";
                        $where2 = "posts_hiden = posts_hiden-1";
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= 1;
                    }
                    else
                    {
                        $where = "post_num = post_num-1";
                        $where2 = "posts = posts-1";
                        $cache_forums[$row['forum_id']]['posts'] -= 1;
                    }
                    
                    if ($row['forum_last_post_id'] == $row['pid'] OR $row['topic_last_post_id'] == $row['pid'])
                    {
                        $post_last = $DB->one_select( "*", "posts", "topic_id = '{$row['topic_id']}'", "ORDER by post_date DESC LIMIT 1");
                        $where .= ", last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";
                        $where2 .= ", last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'";
                    
                        $cache_forums[$row['forum_id']]['last_post_id'] = $post_last['pid'];
                        $cache_forums[$row['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                        $cache_forums[$row['forum_id']]['last_post_date'] = $post_last['post_date'];
                        $cache_forums[$row['forum_id']]['last_post_member'] = $post_last['post_member_name'];
                        $DB->free($post_last);
                    }
                    
                    $DB->update($where, "topics", "id = '{$row['topic_id']}'");
                    $DB->update($where2, "forums", "id = '{$row['forum_id']}'");
                    forum_last_avatar ($row['forum_id']);
                    
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("posts_num = posts_num-1", "users", "user_id = '{$row['post_member_id']}'");
                                                
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_posts";
                    $logs_record_mas['fid'] = $row['forum_id'];
                    $logs_record_mas['tid'] = $row['topic_id'];
                    $logs_record_mas['pid'] = $row['pid'];
                    $logs_record_mas['act_st'] = 0;
                    $logs_record_mas['info'] = array();
                    $logs_record_mas['info']['member'] = $row['post_member_name'];
                    $logs_record_mas['info']['post_date'] = $row['post_date'];
                    $logs_record_mas['info']['text'] = $row['text'];
                    $logs_record_mas['info'] = serialize($logs_record_mas['info']);
                            
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
            }
        }
        $DB->free($posts_db);
        
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            $cache->update("forums", $cache_forums);
            header( "Location: ".$_SESSION['back_link_board'] );
            exit();
        }
    }
    elseif($_POST['moder_posts'] == "7" OR $_POST['moder_posts'] == "8") // Закрепление или открепление постов
    {
        $posts_db = $DB->join_select( "p.pid, p.topic_id, p.hide, t.forum_id, t.id, t.hiden, t.post_fixed", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid regexp '[[:<:]](".$posts.")[[:>:]]'" );
        while ( $row = $DB->get_row($posts_db) )
        {
            if (!forum_options_topics_mas($row['forum_id'], $row['id'], "fixedpost"))
            {
                $errors[] = $lang_m_b_post_edit_mass['access_denied_fix'];
                break;
            }
            elseif(check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']))
            {
                $errors[] = check_access_mass($row['topic_id'], $row['forum_id'], $row['hiden']);
                break; 
            }
            else
            {     
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_posts";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['topic_id'];
                $logs_record_mas['pid'] = $row['pid'];
                                            
                if ($_POST['moder_posts'] == "7")
                {
                    $DB->update("fixed = '1'", "posts", "pid = '{$row['pid']}'");
                    $DB->update("post_fixed = post_fixed+1", "topics", "id = '{$row['id']}'");
                        
                    $logs_record_mas['act_st'] = 2;
                }
                else
                {
                    $DB->update("fixed = '0'", "posts", "pid = '{$row['pid']}'");
                    if ($post['post_fixed'])
                    {
                        $DB->update("post_fixed = post_fixed-1", "topics", "id = '{$row['id']}'");
                    }
                    $logs_record_mas['act_st'] = 3;
                }
                
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
        }
        $DB->free($posts_db);
        
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            exit ();
        }
    }
    else
        header( "Location: {$_SESSION['back_link_board']}" );
}

?>