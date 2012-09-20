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

$lang_m_b_topic_edit = language_forum ("board/modules/board/topic_edit");

if (!isset($_SESSION['back_link_board']) OR $_SESSION['back_link_board'] == "")
    $_SESSION['back_link_board'] = $redirect_url;

$errors = array();

$topic_id = intval($_POST['topic_id']);
if(!$topic_id)
{
    message ($lang_message['access_denied'], $lang_m_b_topic_edit['no_topic'], 1);
}
elseif (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
    message ($lang_message['access_denied'], $lang_message['secret_key'], 1);
}
elseif (!isset($_POST['moder_topic']))
{
    message ($lang_message['access_denied'], $lang_message['no_act'], 1);
}
elseif (!member_publ_access(2))
{
    $link_speddbar = speedbar_forum(0, true)."|".$lang_m_b_topic_edit['location_access_denied'];
    $onl_location = $lang_m_b_topic_edit['location_online_access_denied'];
    
    $message_arr = array();
    $message_arr[] = $lang_m_b_topic_edit['access_denied_group_edit'];
    $message_arr[] = member_publ_info();
    
    message ($lang_message['access_denied'], $message_arr, 1);   
}
else
{  
    function check_access_mass($t_id = 0, $f_id = 0, $hide = 0)
    {
        global $lang_m_b_topic_edit, $member_id, $cache_group;
        
        $message = "";
        
        if ($t_id AND !forum_permission($f_id, "read_forum"))
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_topic_edit['access_denied_group_f']);
        }
        elseif ($t_id AND !forum_permission($f_id, "read_theme"))
        {
            $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_topic_edit['access_denied_group_t']);
        }
        elseif ($t_id AND forum_all_password($f_id))
        {
             $message = $lang_m_b_topic_edit['forum_pass'];
        }
        elseif ($t_id AND $hide AND !forum_options_topics($f_id, "hideshow"))
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_topic_edit['hide_topic']);
        }
        
        return $message;        
    }
    
    $topic = $DB->one_join_select( "p.text, p.edit_reason, p.moder_member_id, p.moder_member_name, p.moder_reason, t.*, f.last_topic_id, f.last_post_date", "LEFT", "topics t||posts p||forums f", "t.post_id=p.pid||t.forum_id=f.id", "t.id = '{$topic_id}'" );
    
    if (!$topic['id'])
         $errors[] = $lang_m_b_topic_edit['not_found'];
              
    if( $errors[0] )
        message ($lang_message['error'], $errors, 1);
    else
    {  
        $meta_info_forum = $topic['forum_id'];
        $meta_info_other = str_replace("{title}", $topic['title'], $lang_m_b_topic_edit['meta_info']);
        
        $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_topic_edit['location']);
        $lang_location = str_replace("{title}", $topic['title'], $lang_location);
        $link_speddbar = speedbar_forum ($topic['forum_id'])."|".$lang_location;
        
        $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_topic_edit['location_online']);
        $lang_location = str_replace("{title}", $topic['title'], $lang_location);
        $onl_location = $lang_location;

        if($_POST['moder_topic'] == "1") // скрыть тему
        {     
            if(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                if (forum_options_topics($topic['forum_id'], "hidetopic") OR ($topic['member_id_open'] == $member_id['user_id'] AND forum_options_topics_author("check", "delete")))
                {
                    $DB->update("hiden = '1'", "topics", "id = '{$topic_id}'");
                    $DB->update("hide = '1'", "posts", "topic_id = '{$topic_id}' AND new_topic = '1'");
                
                    $where = array();
                
                    if (!$topic['hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['topics_hiden'] += 1;
                        $cache_forums[$topic['forum_id']]['topics'] -= 1;
                        $where[] = "topics_hiden = topics_hiden+1, topics = topics-1";
                    }
                
                    $topic_last = $DB->one_join_select( "t.*, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$topic['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_member'] = "";
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_date'] = "";
                            $cache_forums[$topic['forum_id']]['last_title'] = "";
                        }
                        else
                        {
                            $topic_last['title'] = $DB->addslashes($topic_last['title']);
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$topic['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$topic['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$topic['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last);       
                
                    if ($where[0] != "")
                    {
                        $where_db = implode(", ", $where);
                        $DB->update($where_db, "forums", "id = '{$topic['forum_id']}'");
                        forum_last_avatar ($topic['forum_id']);
                        
                        $cache->update("forums", $cache_forums);
                    } 
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $topic['forum_id'];
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['act_st'] = 7;
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);  
                }
                else
                    $errors[] = $lang_m_b_topic_edit['rights_hide'];
            }

            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        elseif($_POST['moder_topic'] == "2") // опубликовать тему
        {
            if (!forum_options_topics($topic['forum_id'], "hidetopic"))
                $errors[] = $lang_m_b_topic_edit['rights_publ'];
            elseif(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                $DB->update("hiden = '0'", "topics", "id = '{$topic_id}'");
                $DB->update("hide = '0'", "posts", "topic_id = '{$topic_id}' AND new_topic = '1'");
                
                $where = array();
                
                if ($topic['hiden'])
                {
                    $cache_forums[$topic['forum_id']]['topics_hiden'] -= 1;
                    $cache_forums[$topic['forum_id']]['topics'] += 1;
                    $where[] = "topics_hiden = topics_hiden-1, topics = topics+1";
                }

                if ($topic['last_post_date'] < $topic['date_last'])
                {
                    $topic['title'] = $DB->addslashes($topic['title']);
                    $where[] = "last_post_member = '{$topic['member_name_last']}', last_post_member_id = '{$topic['last_post_member']}', last_post_date = '{$topic['date_last']}', last_title = '{$topic['title']}', last_topic_id = '{$topic['id']}', last_post_id = '{$topic['last_post_id']}'";
                    $cache_forums[$topic['forum_id']]['last_post_id'] = $topic['last_post_id'];
                    $cache_forums[$topic['forum_id']]['last_topic_id'] = $topic['id'];
                    $cache_forums[$topic['forum_id']]['last_post_member'] = $topic['member_name_last'];
                    $cache_forums[$topic['forum_id']]['last_post_member_id'] = $topic['last_post_member'];
                    $cache_forums[$topic['forum_id']]['last_post_date'] = $topic['date_last'];
                    $cache_forums[$topic['forum_id']]['last_title'] = $topic['title'];
                }
                
                if ($where[0] != "")
                {
                    $where_db = implode(", ", $where);
                    $DB->update($where_db, "forums", "id = '{$topic['forum_id']}'");
                    forum_last_avatar ($topic['forum_id']);
                    
                    $cache->update("forums", $cache_forums);
                }
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $topic['forum_id'];
                $logs_record_mas['tid'] = $topic_id;
                $logs_record_mas['act_st'] = 8;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }

            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        elseif($_POST['moder_topic'] == "3") // редактировать тему
        {
            if(((group_permission("local_titletopic") AND $topic['member_id_open'] == $member_id['user_id']) OR forum_options_topics($topic['forum_id'], "titletopic")))
            {
                header( "Location: ".post_edit_link($topic['post_id']) );
                exit();
            }
            else
                message ($lang_message['access_denied'], $lang_m_b_topic_edit['access_denied_edit'], 1);
        }
        elseif($_POST['moder_topic'] == "4") // поднять тему
        {
            if (!forum_options_topics($topic['forum_id'], "fixtopic"))
                $errors[] = $lang_m_b_topic_edit['rights_fix'];
            elseif(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                $DB->update("fixed = '1'", "topics", "id = '{$topic_id}'");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $topic['forum_id'];
                $logs_record_mas['tid'] = $topic_id;
                $logs_record_mas['act_st'] = 4;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        elseif($_POST['moder_topic'] == "5") // опустить тему
        {
            if (!forum_options_topics($topic['forum_id'], "unfixtopic"))
                $errors[] = $lang_m_b_topic_edit['rights_unfix'];
            elseif(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                $DB->update("fixed = '0'", "topics", "id = '{$topic_id}'");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $topic['forum_id'];
                $logs_record_mas['tid'] = $topic_id;
                $logs_record_mas['act_st'] = 5;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        elseif($_POST['moder_topic'] == "6") // открыть тему
        {
            $tc_time = intval($cache_group[$member['member_group']]['g_tc_time']) * 60 * 60;
            
            if(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                $access_open_tedit = false;
                if (forum_options_topics($topic['forum_id'], "opentopic"))
                     $access_open_tedit = true;
                elseif ($topic['member_id_open'] == $member_id['user_id'] AND forum_options_topics_author("check", "open"))
                {
                    if ($tc_time AND ($topic['date_open'] + $tc_time) < $time)
                    {   
                        $errors[] = str_replace("{info}", intval($cache_group[$member_id['user_group']]['g_tc_time']), $lang_m_b_topic_edit['access_denied_time']); 
                    }
                    else
                        $access_open_tedit = true;
                }
                else
                    $errors[] = $lang_m_b_topic_edit['rights_open'];
                    
                if ($access_open_tedit)
                {
                    $DB->update("status = 'open'", "topics", "id = '{$topic_id}'");
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $topic['forum_id'];
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['act_st'] = 3;
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
            }
            
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        elseif($_POST['moder_topic'] == "7") // закрыть тему
        {
            $tc_time = intval($cache_group[$member['member_group']]['g_tc_time']) * 60 * 60;
            
            if(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                $access_close_tedit = false;
                if (forum_options_topics($topic['forum_id'], "closetopic"))
                     $access_close_tedit = true;
                elseif ($topic['member_id_open'] == $member_id['user_id'] AND forum_options_topics_author("check", "close"))
                {
                    if ($tc_time AND ($topic['date_open'] + $tc_time) < $time)
                    {   
                        $errors[] = str_replace("{info}", intval($cache_group[$member_id['user_group']]['g_tc_time']), $lang_m_b_topic_edit['access_denied_time']); 
                    }
                    else
                        $access_close_tedit = true;
                }
                else
                    $errors[] = $lang_m_b_topic_edit['rights_close'];
                    
                if ($access_close_tedit)
                {
                    $DB->update("status = 'closed'", "topics", "id = '{$topic_id}'");
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $topic['forum_id'];
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['act_st'] = 2;
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
            }
            
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        elseif($_POST['moder_topic'] == "8") // переместить тему
        {
            if (isset($_POST['movetopic']))
            {
                $move_id = intval($_POST['move_id']);
             
                if (!forum_options_topics($topic['forum_id'], "movetopic"))
                    $errors[] = $lang_m_b_topic_edit['rights_move'];
                elseif(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                    $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
                elseif ($topic['forum_id'] == $move_id)
                    $errors[] = $lang_m_b_topic_edit['move_same_forum'];
                elseif (!$cache_forums[$move_id]['id'] OR !$move_id)
                    $errors[] = $lang_m_b_topic_edit['move_no_forum'];
                elseif ($cache_forums[$move_id]['parent_id'] == 0)
                    $errors[] = $lang_m_b_topic_edit['move_category'];
               
                if( $errors[0] )
                    message ($lang_message['access_denied'], $errors, 1);
                else
                {    
                    $DB->update("forum_id = '{$move_id}'", "topics", "id = '{$topic_id}'");
                    $DB->update("file_fid = '{$move_id}'", "topics_files", "file_tid = '{$topic_id}'");
                    
                    $where = array();
                    $where2 = array();
                                
                    if ($topic['hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['topics_hiden'] -= 1;
                        $cache_forums[$move_id]['topics_hiden'] += 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                        $where2[] = "topics_hiden = topics_hiden+1";
                    }
                    else
                    {
                        $cache_forums[$topic['forum_id']]['topics'] -= 1;
                        $cache_forums[$move_id]['topics'] += 1;
                        $where[] = "topics = topics-1";
                        $where2[] = "topics = topics+1";
                    }
                
                    if ($topic['post_hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['posts_hiden'] -= $topic['post_hiden'];
                        $cache_forums[$move_id]['posts_hiden'] += $topic['post_hiden'];
                        $where[] = "posts_hiden = posts_hiden-{$topic['post_hiden']}";
                        $where2[] = "posts_hiden = posts_hiden+{$topic['post_hiden']}";        
                    }
                
                    if ($topic['post_num'])
                    {
                        $cache_forums[$topic['forum_id']]['posts'] -= $topic['post_num'];
                        $cache_forums[$move_id]['posts'] += $topic['post_num'];
                        $where[] = "posts = posts-{$topic['post_num']}";  
                        $where2[] = "posts = posts+{$topic['post_num']}"; 
                    }
                    
                    $topic_last = $DB->one_join_select( "t.*, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$topic['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_member'] = "";
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_date'] = "";
                            $cache_forums[$topic['forum_id']]['last_title'] = "";
                        }
                        else
                        {
                            $topic_last['title'] = $DB->addslashes($topic_last['title']);
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$topic['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$topic['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$topic['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last); 
                    
                    if ($cache_forums[$move_id]['last_post_date'] < $topic['date_last'])
                    {
                        $topic_last = $DB->one_select( "*", "topics", "forum_id = '{$move_id}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                        $topic_last['title'] = $DB->addslashes($topic_last['title']);
                        $where2[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                        $cache_forums[$move_id]['last_post_id'] = $topic_last['last_post_id'];
                        $cache_forums[$move_id]['last_topic_id'] = $topic_last['id'];
                        $cache_forums[$move_id]['last_post_member'] = $topic_last['member_name_last'];
                        $cache_forums[$move_id]['last_post_member_id'] = $topic_last['last_post_member'];
                        $cache_forums[$move_id]['last_post_date'] = $topic_last['date_last'];
                        $cache_forums[$move_id]['last_title'] = $topic_last['title'];
                        $DB->free($topic_last);   
                    }
                    
                    $where_db = implode(", ", $where);
                    $where_db2 = implode(", ", $where2);
                    $DB->update($where_db, "forums", "id = '{$topic['forum_id']}'");
                    forum_last_avatar ($topic['forum_id']);
                    
                    $DB->update($where_db2, "forums", "id = '{$move_id}'");
                    forum_last_avatar ($move_id);
                    
                    $cache->update("forums", $cache_forums);
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $topic['forum_id'];
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['act_st'] = 9;
                    
                    $logs_record_mas['info'] = $lang_m_b_topic_edit['move_logs'];
                    $logs_record_mas['info'] = str_replace ("{title}", $cache_forums[$move_id]['title'], $logs_record_mas['info']);
                    $logs_record_mas['info'] = str_replace ("{id}", $move_id, $logs_record_mas['info']);
                    
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                    
                    header( "Location: ".topic_link($topic['id'], $move_id) );
                    exit ();
                }
            }
            else
            {
                if (!forum_options_topics($topic['forum_id'], "movetopic"))
                    $errors[] = $logs_record_mas['rights_move'];
                elseif(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                    $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
               
                if( $errors[0] )
                    message ($lang_message['access_denied'], $errors, 1);
                else
                {               
                    $tpl->load_template ( 'board/topic_edit_move.tpl' );    
                    $tpl->tags('{forums}', ForumsList(0, 0, "", "", true));
                    $tpl->tags('{moder_topic}', "8");
                    $tpl->tags('{tid}', $topic['id']);
                    $tpl->tags('{title}', $topic['title']);
                
                    $tpl->tags_blocks("one_topic");
                    $tpl->tags_blocks("mass_topic", false);
                
                    $tpl->compile('content');
                    $tpl->clear();
                }
            } 
        } 
        elseif($_POST['moder_topic'] == "9") // удалить тему
        {         
            if ($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] AND $cache_forums[$cache_config['basket_fid']['conf_value']]['parent_id'] != 0)         
                $move_id = intval($cache_config['basket_fid']['conf_value']);
            else
                $move_id = 0;
        
            if (!forum_options_topics($topic['forum_id'], "deltopic"))
                $errors[] = $lang_m_b_topic_edit['rights_del'];
            elseif(check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']))
                $errors[] = check_access_mass($topic['id'], $topic['forum_id'], $topic['hiden']);
            else
            {
                if ($move_id AND !$topic['basket'])
                {
                    $DB->update("forum_id = '{$move_id}', basket = '1', basket_fid = '{$topic['forum_id']}'", "topics", "id = '{$topic_id}'");
                    
                    $DB->update("file_fid = '{$move_id}'", "topics_files", "file_tid = '{$topic_id}'");
                    
                    $where = array();
                    $where2 = array();
                                
                    if ($topic['hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['topics_hiden'] -= 1;
                        $cache_forums[$move_id]['topics_hiden'] += 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                        $where2[] = "topics_hiden = topics_hiden+1";
                    }
                    else
                    {
                        $cache_forums[$topic['forum_id']]['topics'] -= 1;
                        $cache_forums[$move_id]['topics'] += 1;
                        $where[] = "topics = topics-1";
                        $where2[] = "topics = topics+1";
                    }
                
                    if ($topic['post_hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['posts_hiden'] -= $topic['post_hiden'];
                        $cache_forums[$move_id]['posts_hiden'] += $topic['post_hiden'];
                        $where[] = "posts_hiden = posts_hiden-{$topic['post_hiden']}";
                        $where2[] = "posts_hiden = posts_hiden+{$topic['post_hiden']}";        
                    }
                
                    if ($topic['post_num'])
                    {
                        $cache_forums[$topic['forum_id']]['posts'] -= $topic['post_num'];
                        $cache_forums[$move_id]['posts'] += $topic['post_num'];
                        $where[] = "posts = posts-{$topic['post_num']}";  
                        $where2[] = "posts = posts+{$topic['post_num']}"; 
                    }
                    
                    $topic_last = $DB->one_join_select( "t.*, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$topic['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_member'] = "";
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_date'] = "";
                            $cache_forums[$topic['forum_id']]['last_title'] = "";
                        }
                        else
                        {
                            $topic_last['title'] = $DB->addslashes($topic_last['title']);
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$topic['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$topic['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$topic['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last); 
                    
                    if ($cache_forums[$move_id]['last_post_date'] < $topic['date_last'])
                    {
                        $topic_last = $DB->one_select( "*", "topics", "forum_id = '{$move_id}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                        $topic_last['title'] = $DB->addslashes($topic_last['title']);
                        $where2[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                        $cache_forums[$move_id]['last_post_id'] = $topic_last['last_post_id'];
                        $cache_forums[$move_id]['last_topic_id'] = $topic_last['id'];
                        $cache_forums[$move_id]['last_post_member'] = $topic_last['member_name_last'];
                        $cache_forums[$move_id]['last_post_member_id'] = $topic_last['last_post_member'];
                        $cache_forums[$move_id]['last_post_date'] = $topic_last['date_last'];
                        $cache_forums[$move_id]['last_title'] = $topic_last['title'];
                        $DB->free($topic_last);   
                    }
                    
                    $where_db = implode(", ", $where);
                    $where_db2 = implode(", ", $where2);
                    $DB->update($where_db, "forums", "id = '{$topic['forum_id']}'");
                    forum_last_avatar ($topic['forum_id']);
                    
                    $DB->update($where_db2, "forums", "id = '{$move_id}'");
                    forum_last_avatar ($move_id);
                    
                    $cache->update("forums", $cache_forums);   
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $topic['forum_id'];
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['act_st'] = 9;
                    
                    $logs_record_mas['info'] = $lang_m_b_topic_edit['move_logs'];
                    $logs_record_mas['info'] = str_replace ("{title}", $cache_forums[$move_id]['title'], $logs_record_mas['info']);
                    $logs_record_mas['info'] = str_replace ("{id}", $move_id, $logs_record_mas['info']);
                    
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
                elseif (!$move_id OR $topic['basket'])
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("topics_num = topics_num-1", "users", "user_id = '{$topic['member_id_open']}'");
                    $all_posts = $DB->select( "COUNT(*) as count, post_member_id", "posts", "topic_id = '{$topic['id']}' AND hide = '0' AND new_topic = '0'", "GROUP BY post_member_id" );
                    while ( $row = $DB->get_row($all_posts) )
                    {
                        $DB->prefix = DLE_USER_PREFIX;
                        $DB->update("posts_num = posts_num-{$row['count']}", "users", "user_id = '{$row['post_member_id']}'");
                    }
                    
                    $DB->delete("id = '{$topic_id}'", "topics");
                    $DB->delete("topic_id = '{$topic_id}'", "posts");
                    
                    $t_files = $DB->select( "file_id", "topics_files", "file_tid = '{$topic_id}'" );
                    include_once LB_CLASS. "/upload_files.php";
                    $LB_upload = new LB_Upload();
                    
                    while ( $row3 = $DB->get_row($t_files) )
                    {
                        $LB_upload->Del_Record($row3['file_id'], $secret_key);
                    }

                    $DB->free($t_files);
                    unset($LB_upload);
                    
                    $DB->delete("file_tid = '{$topic_id}'", "topics_files");
                         
                    $where = array();
                                
                    if ($topic['hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['topics_hiden'] -= 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                    }
                    else
                    {
                        $cache_forums[$topic['forum_id']]['topics'] -= 1;
                        $where[] = "topics = topics-1";   
                    }
                
                    if ($topic['post_hiden'])
                    {
                        $cache_forums[$topic['forum_id']]['posts_hiden'] -= $topic['post_hiden'];
                        $where[] = "posts_hiden = posts_hiden-{$topic['post_hiden']}";         
                    }
                
                    if ($topic['post_num'])
                    {
                        $cache_forums[$topic['forum_id']]['posts'] -= $topic['post_num'];
                        $where[] = "posts = posts-{$topic['post_num']}";   
                    }
                
                    $topic_last = $DB->one_join_select( "t.*, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$topic['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_member'] = "";
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = 0;
                            $cache_forums[$topic['forum_id']]['last_post_date'] = "";
                            $cache_forums[$topic['forum_id']]['last_title'] = "";
                        }
                        else
                        {
                            $topic_last['title'] = $DB->addslashes($topic_last['title']);
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$topic['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$topic['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$topic['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$topic['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$topic['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$topic['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last); 
                
                    $where_db = implode(", ", $where);
                    $DB->update($where_db, "forums", "id = '{$topic['forum_id']}'");
                    forum_last_avatar ($topic['forum_id']);
                    
                    $cache->update("forums", $cache_forums);
            
                    $DB->free($all_posts);
                                        
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $topic['forum_id'];
                    $logs_record_mas['tid'] = $topic_id;
                    $logs_record_mas['act_st'] = 0;
                    $logs_record_mas['info'] = array();
                    $logs_record_mas['info']['member'] = $topic['member_name_open'];
                    $logs_record_mas['info']['date_open'] = $topic['date_open'];
                    $logs_record_mas['info']['date_last'] = $topic['date_last'];
                    $logs_record_mas['info']['title'] = $topic['title'];
                    $logs_record_mas['info'] = serialize($logs_record_mas['info']);
                    
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }               
            }

            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        } 
        elseif($_POST['moder_topic'] == "10") // отписать
        {
            if (!$cache_group[$member_id['user_group']]['g_supermoders'] OR $member_id['user_group'] != 1)
                $errors[] = $lang_m_b_topic_edit['rights_subscribe'];
            else
            {
                $DB->prefix = array( 1 => DLE_USER_PREFIX );
                $DB->join_select( "ts.*, u.lb_subscribe, u.user_id", "LEFT", "topics_subscribe ts||users u", "ts.subs_member=u.user_id", "ts.topic = '{$topic['id']}'" );
                while ( $row = $DB->get_row() )
                {
                    $subs = "";
                    $subscribe = explode (",", $row['lb_subscribe']);
                    if (in_array($topic['id'], $subscribe))
                    {
                        $key_fav = array_search($topic['id'], $subscribe);
                        unset($subscribe[$key_fav]);
                    }
                    $subs = implode (",", $subscribe);
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("lb_subscribe = '{$subs}'", "users", "user_id = '{$row['user_id']}'");
                }
                $DB->delete("topic = '{$topic['id']}'", "topics_subscribe");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $topic['forum_id'];
                $logs_record_mas['tid'] = $topic_id;
                $logs_record_mas['act_st'] = 6;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
             
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id']) );
                exit ();
            }
        }
        else
        {
            header( "Location: {$_SESSION['back_link_board']}" );
            exit ();
        } 
    }
}

?>