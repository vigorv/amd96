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

$lang_m_b_moderation = language_forum ("board/modules/board/moderation");

if (!isset($_SESSION['back_link_board']) OR $_SESSION['back_link_board'] == "")
    $_SESSION['back_link_board'] = $redirect_url;

$errors = array();

$selected = $_POST['topics'];
$topic_mass = array();
foreach	($selected as $id)
{
    $topic_mass[] = intval( $id );
}
$topics = implode("|", $topic_mass);

if(!$selected)
{
    message ($lang_message['access_denied'], $lang_m_b_moderation['no_topics'], 1);
}
elseif (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
    message ($lang_message['access_denied'], $lang_message['secret_key'], 1);
}
elseif (!isset($_POST['act']))
{
    message ($lang_message['access_denied'], $lang_message['no_act'], 1);
}
elseif(count($topic_mass) < 2 AND $_POST['act'] == "8")
{
   message ($lang_message['error'], $lang_m_b_moderation['union_min'], 1);
}
elseif (!member_publ_access(2))
{
    $link_speddbar = speedbar_forum(0, true)."|".$lang_m_b_moderation['location'];
    $onl_location = $lang_m_b_moderation['location_online'];
    
    $message_arr = array();
    $message_arr[] = $lang_m_b_moderation['access_denied_group_edit'];
    $message_arr[] = member_publ_info();
    
    message ($lang_message['access_denied'], $message_arr, 1);   
}
else
{  
    $meta_info_other = $lang_m_b_moderation['meta_info'];
    
    function check_access_mass($t_id = 0, $f_id = 0, $hide = 0)
    {
        global $lang_m_b_moderation, $member_id, $cache_group;
        
        $message = "";
        
        if ($t_id AND !forum_permission($f_id, "read_forum"))
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_moderation['access_denied_group_f']);
        }
        elseif ($t_id AND !forum_permission($f_id, "read_theme"))
        {
            $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_moderation['access_denied_group_t']);
        }
        elseif ($t_id AND forum_all_password($f_id))
        {
             $message = $lang_m_b_moderation['forum_pass'];
        }
        elseif ($t_id AND $hide AND !forum_options_topics($f_id, "hideshow"))
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_moderation['hide_topic']);
        }
        
        return $message;        
    }
    
    $link_speddbar = speedbar_forum (0, true)."|".$lang_m_b_moderation['location'];
    $onl_location = $lang_m_b_moderation['location_online'];
    
    if($_POST['act'] == "1")
    {
        $topics_db = $DB->select( "forum_id, id, hiden, status", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        while ( $row = $DB->get_row($topics_db) )
        {   
            $i ++;
            if (!forum_options_topics($row['forum_id'], "opentopic"))
            {
                $errors[] = $lang_m_b_moderation['rights_open'];
                break;
            }
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
            {
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);
                break;
            }
            
            if( ! $errors[0] AND $row['status'] != "open" )
            {   
                $DB->update("status = 'open'", "topics", "id = '{$row['id']}'");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 3;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
            
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            exit ();
        }
    }
    elseif($_POST['act'] == "2")
    {
        $topics_db = $DB->select( "forum_id, id, hiden, status", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        while ( $row = $DB->get_row($topics_db) )
        {     
            $i ++;
            if (!forum_options_topics($row['forum_id'], "closetopic"))
            {
                $errors[] = $lang_m_b_moderation['rights_close'];
                break;
            }
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
            {
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);
                break;
            }
            
            if( ! $errors[0] AND $row['status'] != "closed" )
            {   
                $DB->update("status = 'closed'", "topics", "id = '{$row['id']}'");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 2;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
            
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            exit ();
        }
    }
    elseif($_POST['act'] == "3")
    {
        $topics_db = $DB->join_select( "t.forum_id, t.id, t.hiden, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        while ( $row = $DB->get_row($topics_db) )
        {     
            $i ++;
            
            if (!forum_options_topics($row['forum_id'], "hidetopic"))
                $errors[] = $lang_m_b_moderation['rights_hide'];
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);
        
            if( ! $errors[0] )
            {   
                if ($row['hiden'])
                    continue;
                
                $DB->update("hiden = '1'", "topics", "id = '{$row['id']}'");
                $DB->update("hide = '1'", "posts", "topic_id = '{$row['id']}' AND new_topic = '1'");
                
                $where = array();
                $where[] = "topics_hiden = topics_hiden+1, topics = topics-1";
                
                $cache_forums[$row['forum_id']]['topics_hiden'] += 1;
                $cache_forums[$row['forum_id']]['topics'] -= 1;
                
                $topic_last = $DB->one_join_select( "t.id, t.title, t.member_name_last, t.last_post_member, t.date_last, t.last_post_id, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$row['forum_id']}' AND t.hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                {
                    if (!$topic_last['id'])
                    {
                        $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
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
                        $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                        $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                        $cache_forums[$row['forum_id']]['last_topic_id'] = $topic_last['id'];
                        $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                        $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                        $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                        $cache_forums[$row['forum_id']]['last_title'] = $topic_last['title'];
                    }
                } 
                $DB->free($topic_last);
                
                $where_db = implode(", ", $where);
                $DB->update($where_db, "forums", "id = '{$row['forum_id']}'");
                forum_last_avatar ($row['forum_id']);
                
                unset($where);
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 7;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            else
                break;
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
            
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            $cache->update("forums", $cache_forums);
            exit ();
        }
    }
    elseif($_POST['act'] == "4")
    {
        $topics_db = $DB->join_select( "t.forum_id, t.id, t.hiden, t.date_last, t.title, t.member_name_last, t.last_post_member, t.last_post_id, f.last_post_id as forum_last_post_id, f.last_post_date", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        while ( $row = $DB->get_row($topics_db) )
        {       
            $i ++;
            if (!forum_options_topics($row['forum_id'], "hidetopic"))
                $errors[] = $lang_m_b_moderation['rights_publ'];
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);
        
            if( ! $errors[0] )
            {   
                if (!$row['hiden'])
                    continue;
                
                $DB->update("hiden = '0'", "topics", "id = '{$row['id']}'");
                $DB->update("hide = '0'", "posts", "topic_id = '{$row['id']}' AND new_topic = '1'");
                
                $where = array();
                $where[] = "topics_hiden = topics_hiden-1, topics = topics+1";
                
                $cache_forums[$row['forum_id']]['topics_hiden'] -= 1;
                $cache_forums[$row['forum_id']]['topics'] += 1;
                
                $topic_last = $DB->one_select( "last_post_date", "forums", "id = '{$row['forum_id']}'" );
                if ($topic_last['last_post_date'] < $row['date_last'])
                {
                    $row['title'] = $DB->addslashes($row['title']);
                    $where[] = "last_post_member = '{$row['member_name_last']}', last_post_member_id = '{$row['last_post_member']}', last_post_date = '{$row['date_last']}', last_title = '{$row['title']}', last_topic_id = '{$row['id']}', last_post_id = '{$row['last_post_id']}'";
                    $cache_forums[$row['forum_id']]['last_post_id'] = $row['last_post_id'];
                    $cache_forums[$row['forum_id']]['last_topic_id'] = $row['id'];
                    $cache_forums[$row['forum_id']]['last_post_member'] = $row['member_name_last'];
                    $cache_forums[$row['forum_id']]['last_post_member_id'] = $row['last_post_member'];
                    $cache_forums[$row['forum_id']]['last_post_date'] = $row['date_last'];
                    $cache_forums[$row['forum_id']]['last_title'] = $row['title'];
                } 
                $DB->free($topic_last); 
                
                $where_db = implode(", ", $where);
                $DB->update($where_db, "forums", "id = '{$row['forum_id']}'");
                forum_last_avatar ($row['forum_id']);
                
                unset($where);
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 8;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            else
                break;
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
            
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            $cache->update("forums", $cache_forums);
            exit ();
        }
    }
    elseif($_POST['act'] == "5")
    {
        $topics_db = $DB->select( "forum_id, id, hiden, fixed", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        while ( $row = $DB->get_row($topics_db) )
        {   
            $i ++;
            if (!forum_options_topics($row['forum_id'], "fixtopic"))
                $errors[] = $lang_m_b_moderation['rights_up'];
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);
        
            if( ! $errors[0] AND !$row['fixed'] )
            {   
                $DB->update("fixed = '1'", "topics", "id = '{$row['id']}'");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 4;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            else
                break;
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
            
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            exit ();
        }
    }
    elseif($_POST['act'] == "6")
    {
        $topics_db = $DB->select( "forum_id, id, hiden, fixed", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        while ( $row = $DB->get_row($topics_db) )
        {      
            $i ++;
            if (!forum_options_topics($row['forum_id'], "unfixtopic"))
                $errors[] = $lang_m_b_moderation['rights_down'];
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);
        
            if( ! $errors[0] AND $row['fixed'] )
            {   
                $DB->update("fixed = '0'", "topics", "id = '{$row['id']}'");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 5;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            else
                break;
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
            
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: ".$_SESSION['back_link_board'] );
            exit();
        }
    }
    elseif($_POST['act'] == "7") // переместить
    {
        if (isset($_POST['movetopic']))
        {
            $move_id = intval($_POST['move_id']);
                        
            $topics_db = $DB->join_select( "t.id, t.hiden, t.forum_id, t.post_hiden, t.post_num, t.date_last, f.last_topic_id, f.last_post_date", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.id regexp '[[:<:]](".$topics.")[[:>:]]'" );
            $i = 0;
            $topics_box = "";
            $topic_mass_bd = array();
            while ( $row = $DB->get_row($topics_db) )
            {
                $i ++;
                if (!forum_options_topics($row['forum_id'], "movetopic"))
                    $errors[] = $lang_m_b_moderation['rights_move'];
                elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                    $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);    
                elseif ($row['forum_id'] == $move_id)
                    $errors[] = $lang_m_b_moderation['move_there is'];
                elseif (!$cache_forums[$move_id]['id'] OR !$move_id)
                    $errors[] = $lang_m_b_moderation['move_no_forum'];
                        
                if( ! $errors[0] )
                {   
                    $topic_mass_bd[$row['id']] = array ();
                    foreach ($row as $key => $value)
                        $topic_mass_bd[$row['id']][$key] = $value;
                }
                else
                    break;
            }     
            $DB->free($topics_db);
        
            if (!$i)
                $errors[] = $lang_m_b_moderation['topics_not_found'];
                
            if ($cache_forums[$move_id]['parent_id'] == 0)
                $errors[] = $lang_m_b_moderation['move_to_category'];
               
            if( $errors[0] )
                message ($lang_message['error'], $errors, 1);
            else
            {      
                foreach ($topic_mass_bd as $row)
                {
                    if ($row['basket'])
                        $DB->update("forum_id = '{$move_id}', basket = '0', basket_fid = '0'", "topics", "id = '{$row['id']}'");
                    else
                        $DB->update("forum_id = '{$move_id}'", "topics", "id = '{$row['id']}'");
                        
                    $DB->update("file_fid = '{$move_id}'", "topics_files", "file_tid = '{$row['id']}'");
                    
                    $where = array();
                    $where2 = array();
                                
                    if ($row['hiden'])
                    {
                        $cache_forums[$row['forum_id']]['topics_hiden'] -= 1;
                        $cache_forums[$move_id]['topics_hiden'] += 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                        $where2[] = "topics_hiden = topics_hiden+1";
                    }
                    else
                    {
                        $cache_forums[$row['forum_id']]['topics'] -= 1;
                        $cache_forums[$move_id]['topics'] += 1;
                        $where[] = "topics = topics-1";
                        $where2[] = "topics = topics+1";
                    }
                
                    if ($row['post_hiden'])
                    {
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= $row['post_hiden'];
                        $cache_forums[$move_id]['posts_hiden'] += $row['post_hiden'];
                        $where[] = "posts_hiden = posts_hiden-{$row['post_hiden']}";
                        $where2[] = "posts_hiden = posts_hiden+{$row['post_hiden']}";        
                    }
                
                    if ($row['post_num'])
                    {
                        $cache_forums[$row['forum_id']]['posts'] -= $row['post_num'];
                        $cache_forums[$move_id]['posts'] += $row['post_num'];
                        $where[] = "posts = posts-{$row['post_num']}";  
                        $where2[] = "posts = posts+{$row['post_num']}"; 
                    }
                    
                    $topic_last = $DB->one_join_select( "t.id, t.title, t.member_name_last, t.last_post_member, t.date_last, t.last_post_id, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$row['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
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
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$row['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$row['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last);
                    
                    if ($cache_forums[$move_id]['last_post_date'] < $row['date_last'])
                    {
                        $topic_last = $DB->one_select( "title, member_name_last, last_post_member, date_last, id, last_post_id", "topics", "forum_id = '{$move_id}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
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
                    $DB->update($where_db, "forums", "id = '{$row['forum_id']}'");
                    forum_last_avatar ($row['forum_id']);
                    
                    $DB->update($where_db2, "forums", "id = '{$move_id}'");
                    forum_last_avatar ($move_id);
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $move_id;
                    $logs_record_mas['tid'] = $row['id'];
                    $logs_record_mas['act_st'] = 9;
                    
                    $logs_record_mas['info'] = $lang_m_b_moderation['move_logs'];
                    $logs_record_mas['info'] = str_replace ("{title}", $cache_forums[$move_id]['title'], $logs_record_mas['info']);
                    $logs_record_mas['info'] = str_replace ("{id}", $move_id, $logs_record_mas['info']);

                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
                
                header( "Location: ".$_SESSION['back_link_board'] );
                $cache->update("forums", $cache_forums);
                exit ();
            }
        }
        else
        {
            $topics_db = $DB->select( "id, forum_id, hiden, title", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
            $i = 0;
            $topics_box = "";
            $topic_fid = array();
            while ( $row = $DB->get_row($topics_db) )
            {
                $i ++;
                if (!forum_options_topics($row['forum_id'], "movetopic"))
                    $errors[] = $lang_m_b_moderation['rights_move'];
                elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                    $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);    
                        
                if( ! $errors[0] )
                {   
                    $topic_fid[] = $row['forum_id'];
                    $topics_box .= "<input type=\"checkbox\" name=\"topics[]\" value=\"".$row['id']."\" checked /> ".$row['title']."<br />";
                }
                else
                    break;
            }     
            $DB->free($topics_db);
        
            if (!$i)
                $errors[] = $lang_m_b_moderation['topics_not_found'];
               
            if( $errors[0] )
                message ($lang_message['access_denied'], $errors, 1);
            else
            {    
                $forum_id_one = 0;
                $forum_mass = false;
                foreach ($topic_fid as $fid)
                {
                    if ($forum_id_one != $fid AND $forum_id_one != 0)
                    {
                        $forum_mass = true;
                        break;
                    }
                    $forum_id_one = $fid;
                }
                
                $tpl->load_template ( 'board/topic_edit_move.tpl' );    
                $tpl->tags('{forums}', ForumsList(0, 0, "", "", true));
                $tpl->tags('{topics}', $topics_box);
                
                if ($forum_mass)
                    $tpl->tags('{title}', $lang_m_b_moderation['move_others_forums']);
                else
                    $tpl->tags('{title}', $cache_forums[$forum_id_one]['title']);
                
                $tpl->tags_blocks("one_topic", false);
                $tpl->tags_blocks("mass_topic");
                
                $tpl->compile('content');
                $tpl->clear();
            }
        } 
    } 
    elseif($_POST['act'] == "8") // Объеденить
    {
        if (isset($_POST['uniontopic']))
        {
            $title_id = intval($_POST['title_id']);
            $title_topic = "";
            
            $topic_move = $DB->one_select( "id, forum_id", "topics", "id = '{$title_id}'" );
            
            if (!$topic_move['id'])
                $errors[] = $lang_m_b_moderation['union_topic_not_found'];
            else
            {
                $topic = array();
                
                $topics_db = $DB->join_select( "t.id, t.forum_id, t.hiden, t.views, t.post_num, t.post_hiden, f.last_topic_id, f.last_post_date", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.id regexp '[[:<:]](".$topics.")[[:>:]]'" );
                $i = 0;
                $topics_box = "";
                $topic_mass_bd = array();
                while ( $row = $DB->get_row($topics_db) )
                {
                    $i ++;
                    if (!forum_options_topics($row['forum_id'], "uniontopic"))
                        $errors[] = $lang_m_b_moderation['rights_union'];
                    elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                        $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);    
                            
                    if( ! $errors[0] )
                    {   
                        if ($row['id'] != $title_id)
                        {
                            $topic_mass_bd[$row['id']] = array ();
                            foreach ($row as $key => $value)
                                $topic_mass_bd[$row['id']][$key] = $value;
                        }
                        else
                        {
                            foreach ($row as $key => $value)
                                $topic[$key] = $value;
                        }
                    }
                    else
                        break;
                }     
                $DB->free($topics_db);
            
                if (!$i)
                    $errors[] = $lang_m_b_moderation['topics_not_found'];
                    
                if ($i < 2)
                    $errors[] = $lang_m_b_moderation['union_min'];
                    
                if (!$topic['id'])
                    $errors[] = $lang_m_b_moderation['union_no_title'];
            }
             
            if( $errors[0] )
                message ($lang_message['access_denied'], $errors, 1);
            else
            {
                $post_num = 0;
                $post_hiden = 0;
                $views = 0;
                foreach ($topic_mass_bd as $row)
                {
                    $DB->update("topic_id = '{$title_id}', new_topic = '0'", "posts", "topic_id = '{$row['id']}'");
                    $DB->delete("id = '{$row['id']}'", "topics");
                    
                    $DB->update("file_tid = '{$title_id}', file_fid = '{$topic_move['forum_id']}'", "topics_files", "file_tid = '{$row['id']}'");
                    
                    $where = array(); // старый форум
                    $where2 = array(); // новый форум
                                
                    $views += $row['views'];
                    $post_num_forum_hide = 0;
                    
                    if ($row['hiden'])
                    {
                        $cache_forums[$row['forum_id']]['topics_hiden'] -= 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                        $post_num_forum = $row['post_num'];
                        $post_num_forum_hide += 1;
                    }
                    else
                    {
                        $cache_forums[$row['forum_id']]['topics'] -= 1;
                        $where[] = "topics = topics-1";
                        $post_num += 1;
                        $cache_forums[$topic['forum_id']]['posts'] += 1;
                        $post_num_forum = $row['post_num'] + 1;
                    }
                
                    if ($row['hiden'] OR $row['post_hiden'])
                    {
                        $post_num_forum_hide += $row['post_hiden'];
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= $row['post_hiden'];
                        $cache_forums[$topic['forum_id']]['posts_hiden'] += $post_num_forum_hide;
                        $where[] = "posts_hiden = posts_hiden-{$row['post_hiden']}";
                        $where2[] = "posts_hiden = posts_hiden+{$post_num_forum_hide}";
                        $post_hiden += $post_num_forum_hide;
                    }
                
                    if ($row['post_num'])
                    {
                        $cache_forums[$row['forum_id']]['posts'] -= $row['post_num'];
                        $cache_forums[$topic['forum_id']]['posts'] += $row['post_num'];
                        $where[] = "posts = posts-{$row['post_num']}";  
                        $where2[] = "posts = posts+{$post_num_forum}";
                        
                        $post_num = $post_num + $row['post_num'];
                    }
                
                    $topic_last = $DB->one_join_select( "t.id, t.title, t.member_name_last, t.last_post_member, t.date_last, t.last_post_id, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$row['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
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
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$row['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$row['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last);
                    
                    $where_db = implode(", ", $where);
                    
                    $DB->update($where_db, "forums", "id = '{$row['forum_id']}'");
                    forum_last_avatar ($row['forum_id']);
                    
                    if ($where2[0] != "")
                    {
                        $where_db2 = implode(", ", $where2);
                        $DB->update($where_db2, "forums", "id = '{$topic['forum_id']}'");
                        forum_last_avatar ($topic['forum_id']);
                    }
                    unset($where);
                    unset($where2);                    
                }
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $topic_move['forum_id'];
                $logs_record_mas['tid'] = $title_id;
                $logs_record_mas['act_st'] = 10;
                $logs_record_mas['info'] = str_replace ("{id}", str_replace("|", ", ", $topics), $logs_record_mas['union_logs']);
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
                
                $cache->update("forums", $cache_forums);
                
                $post_last = $DB->one_select( "post_date, pid, post_member_id, post_member_name", "posts", "topic_id = '{$topic['id']}' AND hide = '0'", "ORDER BY post_date DESC LIMIT 1" );
                $where3 = "";
                if ($post_last['post_date'] > $topic['date_last'])
                {
                    $where3 = ", last_post_id = '{$post_last['pid']}', last_post_member = '{$post_last['post_member_id']}', date_last = '{$post_last['post_date']}', member_name_last = '{$post_last['post_member_name']}'";
                    if ($topic['last_topic_id'] == $topic['id'])
                    {                    
                        $cache_forums[$topic['forum_id']]['last_post_id'] = $post_last['pid'];
                        $cache_forums[$topic['forum_id']]['last_post_member_id'] = $post_last['post_member_id'];
                        $cache_forums[$topic['forum_id']]['last_post_date'] = $post_last['post_date'];
                        $cache_forums[$topic['forum_id']]['last_post_member'] = $post_last['post_member_name'];  
                        $DB->update("last_post_id = '{$post_last['pid']}', last_post_member_id = '{$post_last['post_member_id']}', last_post_date = '{$post_last['post_date']}', last_post_member = '{$post_last['post_member_name']}'", "forums", "id = '{$topic['forum_id']}'");
                        forum_last_avatar ($topic['forum_id']);
                        
                        $cache->update("forums", $cache_forums);  
                    }
                }
                $DB->free($post_last);
                
                $DB->update("post_num = post_num+{$post_num}, post_hiden = post_hiden+{$post_hiden}, views = views+{$views} {$where3}", "topics", "id = '{$topic['id']}'");     
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
            $topics_db = $DB->select( "id, forum_id, hiden, title, views, post_num", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
            $i = 0;
            $topics_title = "";
            $topics_box = "";
            $topics_selected = "";
            while ( $row = $DB->get_row($topics_db) )
            {
                $i ++;
                if (!forum_options_topics($row['forum_id'], "uniontopic"))
                    $errors[] = $lang_m_b_moderation['rights_union'];
                elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                    $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);    
                        
                if( ! $errors[0] )
                {   
                    if ($i == 0)
                        $topics_title .= "<option value=\"".$row['id']."\" selected>".$row['title']."</option>";
                    else
                        $topics_title .= "<option value=\"".$row['id']."\">".$row['title']."</option>";
                        
                    $topics_box .= "<input type=\"hidden\" name=\"topics[]\" value=\"".$row['id']."\" />\r";
                    $topics_selected .= $row['title']." (".str_replace("{num}", $row['post_num'], $lang_m_b_moderation['union_answers'])." ".str_replace("{num}", $row['views'], $lang_m_b_moderation['union_views']).")<br />";
                }
                else
                    break;
            }     
            $DB->free($topics_db);
        
            if (!$i)
                $errors[] = $lang_m_b_moderation['topics_not_found'];
               
            if( $errors[0] )
                message ($lang_message['access_denied'], $errors, 1);
            else
            {                   
                $tpl->load_template ( 'board/topic_edit_union.tpl' );    

                $tpl->tags('{topics_selected}', $topics_selected);
                $tpl->tags('{topics_title}', $topics_title);
                $tpl->tags('{topics}', $topics_box);
                
                $tpl->compile('content');
                $tpl->clear();
            }
        } 
    }
    elseif($_POST['act'] == "9") // Удалить
    {
        if ($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] AND $cache_forums[$cache_config['basket_fid']['conf_value']]['parent_id'] != 0)         
            $move_id = intval($cache_config['basket_fid']['conf_value']);
        else
            $move_id = 0;
        
        $topics_db = $DB->join_select( "t.id, t.forum_id, t.hiden, t.title, t.post_hiden, t.post_num, t.date_open, t.date_last, t.basket, t.basket_fid, t.member_id_open, t.member_name_open, f.last_topic_id, f.last_post_date", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.id regexp '[[:<:]](".$topics.")[[:>:]]'" );
        $i = 0;
        $topic_mass_bd_move = array();
        $topic_mass_bd_del = array();
        while ( $row = $DB->get_row($topics_db) )
        {
            $i ++;
                
            if (!forum_options_topics($row['forum_id'], "deltopic"))
                $errors[] = $lang_m_b_moderation['rights_del'];
            elseif(check_access_mass($row['id'], $row['forum_id'], $row['hiden']))
                $errors[] = check_access_mass($row['id'], $row['forum_id'], $row['hiden']);    
                        
            if( ! $errors[0] )
            {   
                if ($move_id AND !$row['basket'])
                {
                    $topic_mass_bd_move[$row['id']] = array ();
                    foreach ($row as $key => $value)
                        $topic_mass_bd_move[$row['id']][$key] = $value;
                }
                else
                {
                    $topic_mass_bd_del[$row['id']] = array ();
                    foreach ($row as $key => $value)
                        $topic_mass_bd_del[$row['id']][$key] = $value;
                }
            }
            else
            break;
        }     
        $DB->free($topics_db);
        
        if (!$i)
            $errors[] = $lang_m_b_moderation['topics_not_found'];
               
        if( $errors[0] )
            message ($lang_message['access_denied'], $errors, 1);
        else
        {     
            $m_posts_count = array();
            
            if (count($topic_mass_bd_move))
            {                
                foreach ($topic_mass_bd_move as $row)
                {
                    $DB->update("forum_id = '{$move_id}', basket = '1', basket_fid = '{$row['forum_id']}'", "topics", "id = '{$row['id']}'");
                    
                    $DB->update("file_fid = '{$move_id}'", "topics_files", "file_tid = '{$row['id']}'");
                    
                    $where = array();
                    $where2 = array();
                                
                    if ($row['hiden'])
                    {
                        $cache_forums[$row['forum_id']]['topics_hiden'] -= 1;
                        $cache_forums[$move_id]['topics_hiden'] += 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                        $where2[] = "topics_hiden = topics_hiden+1";
                    }
                    else
                    {
                        $cache_forums[$row['forum_id']]['topics'] -= 1;
                        $cache_forums[$move_id]['topics'] += 1;
                        $where[] = "topics = topics-1";
                        $where2[] = "topics = topics+1";
                    }
                
                    if ($row['post_hiden'])
                    {
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= $row['post_hiden'];
                        $cache_forums[$move_id]['posts_hiden'] += $row['post_hiden'];
                        $where[] = "posts_hiden = posts_hiden-{$row['post_hiden']}";
                        $where2[] = "posts_hiden = posts_hiden+{$row['post_hiden']}";        
                    }
                
                    if ($row['post_num'])
                    {
                        $cache_forums[$row['forum_id']]['posts'] -= $row['post_num'];
                        $cache_forums[$move_id]['posts'] += $row['post_num'];
                        $where[] = "posts = posts-{$row['post_num']}";  
                        $where2[] = "posts = posts+{$row['post_num']}"; 
                    }
                    
                    $topic_last = $DB->one_join_select( "t.id, t.title, t.member_name_last, t.last_post_member, t.date_last, t.last_post_id, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$row['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
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
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$row['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$row['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last);
                    
                    if ($cache_forums[$move_id]['last_post_date'] < $row['date_last'])
                    {
                        $topic_last = $DB->one_select( "id, title, member_name_last, last_post_member, date_last, last_post_id", "topics", "forum_id = '{$move_id}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
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
                    $DB->update($where_db, "forums", "id = '{$row['forum_id']}'");
                    forum_last_avatar ($row['forum_id']);
                    
                    $DB->update($where_db2, "forums", "id = '{$move_id}'");
                    forum_last_avatar ($move_id);
                    
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $move_id;
                    $logs_record_mas['tid'] = $row['id'];
                    $logs_record_mas['act_st'] = 9;
                    
                    $logs_record_mas['info'] = $lang_m_b_moderation['move_logs'];
                    $logs_record_mas['info'] = str_replace ("{title}", $cache_forums[$move_id]['title'], $logs_record_mas['info']);
                    $logs_record_mas['info'] = str_replace ("{id}", $move_id, $logs_record_mas['info']);

                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
            }    

            if (count($topic_mass_bd_del))
            {
                foreach ($topic_mass_bd_del as $row)
                {
                    if (!$row['basket'] OR ($row['basket'] AND $cache_forums[$row['basket_fid']]['postcount']))
                    {
                        $m_posts = $DB->select( "post_member_id", "posts", "topic_id = '{$row['id']}' AND hide = '0' AND new_topic = '0'" );
                        while ( $row2 = $DB->get_row($m_posts) )
                        {
                            if (!isset($m_posts_count[$row2['post_member_id']]))
                                $m_posts_count[$row2['post_member_id']] = 1;
                            else
                                $m_posts_count[$row2['post_member_id']] += 1;
                        }
                        
                        $DB->free($m_posts);
                        
                        $DB->prefix = DLE_USER_PREFIX;
                        $DB->update("topics_num=topics_num-1", "users", "user_id = '{$row['member_id_open']}'");
                    }
                    
                    $DB->delete("id = '{$row['id']}'", "topics");
                    $DB->delete("topic_id = '{$row['id']}'", "posts");
                    
                    $t_files = $DB->select( "file_id", "topics_files", "file_tid = '{$row['id']}'" );
                    include_once LB_CLASS. "/upload_files.php";
                    $LB_upload = new LB_Upload();
                    
                    while ( $row3 = $DB->get_row($t_files) )
                    {
                        $LB_upload->Del_Record($row3['file_id'], $secret_key);
                    }

                    $DB->free($t_files);
                    unset($LB_upload);
                    
                    $DB->delete("file_tid = '{$row['id']}'", "topics_files");
                         
                    $where = array();
                                
                    if ($row['hiden'])
                    {
                        $cache_forums[$row['forum_id']]['topics_hiden'] -= 1;
                        $where[] = "topics_hiden = topics_hiden-1";
                    }
                    else
                    {
                        $cache_forums[$row['forum_id']]['topics'] -= 1;
                        $where[] = "topics = topics-1";   
                    }
                
                    if ($row['post_hiden'])
                    {
                        $cache_forums[$row['forum_id']]['posts_hiden'] -= $row['post_hiden'];
                        $where[] = "posts_hiden = posts_hiden-{$row['post_hiden']}";         
                    }
                
                    if ($row['post_num'])
                    {
                        $cache_forums[$row['forum_id']]['posts'] -= $row['post_num'];
                        $where[] = "posts = posts-{$row['post_num']}";   
                    }
                
                    $topic_last = $DB->one_join_select( "t.id, t.title, t.last_post_id, t.member_name_last, t.last_post_member, t.date_last, f.last_topic_id", "LEFT", "topics t||forums f", "t.forum_id=f.id", "t.forum_id = '{$row['forum_id']}' AND hiden = '0'", "ORDER BY date_last DESC LIMIT 1" );
                    if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
                    {
                        if (!$topic_last['id'])
                        {
                            $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
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
                            $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                            $cache_forums[$row['forum_id']]['last_post_id'] = $topic_last['last_post_id'];
                            $cache_forums[$row['forum_id']]['last_topic_id'] = $topic_last['id'];
                            $cache_forums[$row['forum_id']]['last_post_member'] = $topic_last['member_name_last'];
                            $cache_forums[$row['forum_id']]['last_post_member_id'] = $topic_last['last_post_member'];
                            $cache_forums[$row['forum_id']]['last_post_date'] = $topic_last['date_last'];
                            $cache_forums[$row['forum_id']]['last_title'] = $topic_last['title'];
                        }
                    } 
                    $DB->free($topic_last);

                    $where_db = implode(", ", $where);
                    $DB->update($where_db, "forums", "id = '{$row['forum_id']}'");
                    forum_last_avatar ($row['forum_id']);
                    unset($where);
                                        
                    $logs_record_mas = array();
                    $logs_record_mas['table'] = "logs_topics";
                    $logs_record_mas['fid'] = $row['forum_id'];
                    $logs_record_mas['tid'] = $row['id'];
                    $logs_record_mas['act_st'] = 0;
                    $logs_record_mas['info'] = array();
                    $logs_record_mas['info']['member'] = $row['member_name_open'];
                    $logs_record_mas['info']['date_open'] = $row['date_open'];
                    $logs_record_mas['info']['date_last'] = $row['date_last'];
                    $logs_record_mas['info']['title'] = $row['title'];
                    $logs_record_mas['info'] = serialize($logs_record_mas['info']);
                    
                    logs_record ($logs_record_mas);
                    unset($logs_record_mas);
                }
            } 
            
            if (count($m_posts_count))
            {
                foreach ($m_posts_count as $key => $value)
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("posts_num=posts_num-{$value}", "users", "user_id = '{$key}'");
                }
            }
              
            $cache->update("forums", $cache_forums);
            header( "Location: ".$_SESSION['back_link_board'] );   
            exit ();
        }
    } 
    elseif($_POST['act'] == "10") // отписать
    {
        if (!$cache_group[$member_id['user_group']]['g_supermoders'] OR $member_id['user_group'] != 1)
            $errors[] = $lang_m_b_moderation['rights_subsc'];
        else
        {
            $topics_db = $DB->select( "id, forum_id", "topics", "id regexp '[[:<:]](".$topics.")[[:>:]]'" );
            while ( $row = $DB->get_row($topics_db) )
            {
                $DB->prefix = array( 1 => DLE_USER_PREFIX );
                $topics_subs = $DB->join_select( "ts.id, ts.subs_member, u.lb_subscribe, u.user_id", "LEFT", "topics_subscribe ts||users u", "ts.subs_member=u.user_id", "ts.topic = '{$row['id']}'" );
                while ( $row2 = $DB->get_row($topics_subs) )
                {
                    $subs = "";
                    $subscribe = explode (",", $row2['lb_subscribe']);
                    if (in_array($row['id'], $subscribe))
                    {
                        $key_fav = array_search($row['id'], $subscribe);
                        unset($subscribe[$key_fav]);
                    }
                    $subs = implode (",", $subscribe);
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("lb_subscribe = '{$subs}'", "users", "user_id = '{$row2['user_id']}'");
                }
                $DB->free($topics_subs);
                
                $DB->delete("topic = '{$row['id']}'", "topics_subscribe");
                
                $logs_record_mas = array();
                $logs_record_mas['table'] = "logs_topics";
                $logs_record_mas['fid'] = $row['forum_id'];
                $logs_record_mas['tid'] = $row['id'];
                $logs_record_mas['act_st'] = 6;
                logs_record ($logs_record_mas);
                unset($logs_record_mas);
            }
            $DB->free($topics_db);
        }
             
        if( $errors[0] )
            message ($lang_message['error'], $errors, 1);
        else
        {
            header( "Location: {$_SESSION['back_link_board']}" );
            exit ();
        }
    }
    else
    {
        header( "Location: {$_SESSION['back_link_board']}" );
        exit ();
    }
}

?>