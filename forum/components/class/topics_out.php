<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

class LB_topics
{
	PUBLIC $query = "";
    PRIVATE $lang = "";

	function Data_out($template, $template_save, $topic_nav = false)
	{
        global $tpl, $DB, $cache_config, $member_id, $cache_group, $cache_forums, $i, $j, $onl_limit, $link_on_post;

        $this->open_lang_file ();
        
        include_once LB_CLASS.'/navigation_board.php';
        $navigation = new navigation;

        $tpl->load_template ( $template );
        
        while ( $row = $DB->get_row($this->query) )
        {
            $i ++;
            $j ++;
        
            $tpl->tags('{author}', $row['member_name_open']);
        
            if ($row['member_id_open'] == 0)
            {
                $tpl->tags_blocks("guest_topic");
                $tpl->tags_blocks("member_topic", false);
            }
            else
            {
                $tpl->tags_blocks("guest_topic", false);
                $tpl->tags_blocks("member_topic");
                $tpl->tags("{author_link}", profile_link($row['member_name_open'], $row['member_id_open']));
            }
            
            $tpl->tags('{title}', $row['title']);
            
            if ($row['description'])
                $tpl->tags('{description}', htmlspecialchars($row['description']));
            else
                $tpl->tags('{description}', htmlspecialchars($row['title']));
                
            if ($topic_nav)
            {
                $tpl->tags('{forum}', topic_allforum($row['forum_id'], intval($cache_config['topic_allforums']['conf_value'])));
                $tpl->tags_blocks("full_link");
            }
            else
                $tpl->tags_blocks("full_link", false);
            
            $tpl->tags('{views}', $row['views']);
            
            if ($row['status'] == "closed")
            {
                $tpl->tags('{ico}', "ico-fold-closed");
                $tpl->tags('{alt_topic}', $this->lang['close']);
            }
            else
            {
                if (!$row['poll_id'])
                {
                    if ($row['post_num'] >= intval($cache_config['topic_hot']['conf_value']))
                    {
                        if (member_topic_read($row['id'], $row['date_last']))
                            $tpl->tags('{ico}', "ico-fold-hot-empty");
                        else   
                            $tpl->tags('{ico}', "ico-fold-hot");
                        $tpl->tags('{alt_topic}', $this->lang['hot']);
                    }
                    else
                    {
                        if (member_topic_read($row['id'], $row['date_last']))
                            $tpl->tags('{ico}', "ico-fold-open-empty");
                        else
                            $tpl->tags('{ico}', "ico-fold-open");
                        $tpl->tags('{alt_topic}', $this->lang['simple']);    
                    }
                }
                else
                {
                    if (member_topic_read($row['id'], $row['date_last']))
                        $tpl->tags('{ico}', "ico-fold-vote-empty");
                    else
                        $tpl->tags('{ico}', "ico-fold-vote");
                    $tpl->tags('{alt_topic}', $this->lang['poll']); 
                }
            }
            
            if($row['post_hiden'] AND forum_options_topics($row['forum_id'], "hideshow"))
            {
                $tpl->tags('{post_hiden}', $row['post_hiden']);
                $tpl->tags_blocks("un_hide_post", false);
                $tpl->tags_blocks("hide_post");
            }
            else
            {
                $tpl->tags_blocks("un_hide_post");
                $tpl->tags_blocks("hide_post", false);
            }
            
            if($row['fixed'])
                $tpl->tags_blocks("fixed");
            else
                $tpl->tags_blocks("fixed", false);
            
            if (forum_options_topics($row['forum_id']))
                $tpl->tags_blocks("moder_line");
            else
                $tpl->tags_blocks("moder_line", false);
                
            if ($row['hiden'])
                $tpl->tags_blocks("hiden");
            else
                $tpl->tags_blocks("hiden", false);
            
            $tpl->tags('{answers}', $row['post_num']);
            $tpl->tags('{date}', formatdate($row['date_last']));
            $tpl->tags('{topic_id}', $row['id']);
            $tpl->tags('{last_author}', $row['member_name_last']);
            
            if ($row['last_post_member'] == 0)
            {
                $tpl->tags_blocks("guest_post");
                $tpl->tags_blocks("member_post", false);  
            }
            else
            {
                $tpl->tags_blocks("guest_post", false);
                $tpl->tags_blocks("member_post");
                $tpl->tags('{last_author_link}', profile_link($row['member_name_last'], $row['last_post_member']));
            }
            
            $tpl->tags('{link}', topic_link($row['id'], $row['forum_id']));
            $tpl->tags('{link_last}', topic_link($row['id'], $row['forum_id'], true));
            $tpl->tags('{link_hide}', topic_link($row['id'], $row['forum_id'], "", true));
            
            $nav_all = 1 + $row['post_num'] - $row['post_fixed']; 
            if($row['post_hiden'] AND forum_options_topics($row['forum_id'], "hideshow")) $nav_all += $row['post_hiden'];
            
            $tpl->tags_blocks("topic_log", forum_options_topics($row['forum_id'], "topic_log"));
                        
            if ($nav_all > $cache_config['topic_post_page']['conf_value'])
            {
                $link_nav = navigation_topic_link($row['id'], $row['forum_id']);
                $navigation->create_topic($nav_all, $cache_config['topic_post_page']['conf_value'], $link_nav, "3");
                $tpl->tags('{topic_nav}', $navigation->result);
                $tpl->tags_blocks("topic_nav");  
            }
            else
            {
                $tpl->tags('{topic_nav}', "");
                $tpl->tags_blocks("topic_nav", false);  
            }
                
            $tpl->compile($template_save);
        }
           
        $DB->free();    
        $tpl->clear();
                
        $this->clear();

        unset($navigation);
	}
    
	function clear()
	{
        unset($this->query);
		$this->query = "";
	}
    
    function open_lang_file ()
    {
        if (!is_array($this->lang)) $this->lang = language_forum ("board/class/topics_out");
    }
    
    function check_access ($f_id = 0, $hide = 0, $status = "open")
    {
        global $member_id, $cache_group;
        
        $this->open_lang_file ();
        
        $message = "";
        
        if(!forum_permission($f_id, "read_forum"))
        {
            $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $this->lang['access_denied_group_f']);
        }
        elseif (!forum_permission($f_id, "read_theme"))
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $this->lang['access_denied_group_t']);
        }
        elseif(forum_all_password($f_id))
        {
             $message = $this->lang['forum_pass'];
        }
        elseif(!forum_options_topics($f_id, "hideshow") AND $hide)
        {
             $message = str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $this->lang['hide_topic']);
        }
        elseif($status == "closed")
        {
             $message = $this->lang['close_topic'];
        }

        return $message;        
    }  
    
    function subscribe ($tid, $fid, $check = false, $status = "", $hiden = 0, $ajax = false)
    {
        global $DB, $member_id, $time, $lang_message;
                
        $this->open_lang_file ();
          
        $find_error = false;
        $error = "";
        $act = $this->lang['subs_act_1'];
        
        $topic_subs = $DB->one_select( "id", "topics_subscribe", "topic = '{$tid}' AND subs_member = '{$member_id['user_id']}'");
            
        if (!$topic_subs['id'])
        {
            if (!$check OR ($check AND !$this->check_access($fid, $hiden, $status)))
                $DB->insert("subs_member = '{$member_id['user_id']}', topic = '{$tid}', date = '{$time}'", "topics_subscribe");
            else
            {
                if (!$ajax) message ($lang_message['error'], $this->check_access ($fid, $hiden, $status), 1);
                else $error = $this->check_access ($fid, $hiden, $status);
                
                $find_error = true;
            }
        }
        else
        {
            $DB->delete("id = '{$topic_subs['id']}'", "topics_subscribe");
        }
            
        if (!$find_error)  
        {
            if ($member_id['lb_subscribe'] == "")
            {
                if (!$check OR ($check AND !$this->check_access($fid, $hiden, $status)))
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("lb_subscribe = '{$tid}'", "users", "user_id = '{$member_id['user_id']}'");
                }
                else
                {
                    if (!$ajax) message ($lang_message['error'], $this->check_access ($fid, $hiden, $status), 1);
                    else $error = $this->check_access ($fid, $hiden, $status);
                    
                    $find_error = true;
                }
            }
            else
            {
                $subscribe = explode (",", $member_id['lb_subscribe']);
                if (in_array($tid, $subscribe))
                {
                    $key_fav = array_search($tid, $subscribe);
                    unset($subscribe[$key_fav]);
                    $act = $this->lang['subs_act_2'];
                }
                else
                {
                    if (!$check OR ($check AND !$this->check_access($fid, $hiden, $status)))
                        array_push ($subscribe, $tid);
                    else
                    {
                        if (!$ajax) message ($lang_message['error'], $this->check_access ($fid, $hiden, $status), 1);
                        else $error = $this->check_access ($fid, $hiden, $status);
                        
                        $find_error = true;
                    }
                }
                
                $subs = implode (",", $subscribe);
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("lb_subscribe = '{$subs}'", "users", "user_id = '{$member_id['user_id']}'");
            }
        }
            
        if (!$find_error)
        {
            if (!$ajax) header( "Location: ".topic_link($tid, $fid, true) );
            else echo show_jq_message ("1", $this->lang['done_title'], str_replace("{act}", $act, $this->lang['subs_done_info']));
        }
        elseif ($find_error AND $ajax)
        {
            echo show_jq_message ("3", $this->lang['error'], $error);
        }
    }
    
    function favorite ($tid, $fid, $check = false, $hiden = 0, $ajax = false)
    {
        global $DB, $member_id, $time, $lang_message;
                
        $this->open_lang_file ();
        
        $find_error = false;
        $error = "";
        $act = $this->lang['fav_act_1'];
        
        if ($member_id['lb_favorite'] == "")
        {
            if (!$check OR ($check AND !$this->check_access($fid, $hiden)))
            {
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("lb_favorite = '{$tid}'", "users", "user_id = '{$member_id['user_id']}'");
            }
            else
            {
                if (!$ajax) message ($lang_message['error'], $this->check_access ($fid, $hiden), 1);
                else $error = $this->check_access ($fid, $hiden);
                
                $find_error = true;
            }
        }
        else
        {
            $favorites = explode (",", $member_id['lb_favorite']);
            if (in_array($tid, $favorites))
            {
                $key_fav = array_search($tid, $favorites);
                unset($favorites[$key_fav]);
                $act = $this->lang['fav_act_2'];
            }
            else
            {
                if (!$check OR ($check AND !$this->check_access($fid, $hiden)))
                    array_push ($favorites, $tid);
                else
                {
                    if (!$ajax) message ($lang_message['error'], $this->check_access ($fid, $hiden), 1);
                    else $error = $this->check_access ($fid, $hiden);
                    
                    $find_error = true;
                }
            }
            
            $fav = implode (",", $favorites);
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("lb_favorite = '{$fav}'", "users", "user_id = '{$member_id['user_id']}'");
        }
        
        if (!$find_error)
        {
            if (!$ajax) header( "Location: ".topic_link($tid, $fid, true) );
            else echo show_jq_message ("1", $this->lang['done_title'], str_replace("{act}", $act, $this->lang['fav_done_info']));
        }
        elseif ($find_error AND $ajax)
        {
            echo show_jq_message ("3", $this->lang['error'], $error);
        }
    }
    
    function do_vote ($topic, $ajax = false)
    {
        global $DB, $LB_flood, $lang_message, $_IP, $member_id, $logged, $time;
        
        $this->open_lang_file ();

        if ($LB_flood->isBlock())
        {
            if (!$ajax)
                message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
            else
            {
                echo show_jq_message("3", $lang_s_a_topic_vote['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_s_a_topic_vote['flood_control_stop']));
                echo echo_poll();
                stop_script();
            }
        }
        else
        {
            if ($ajax)
            {
                $text = urldecode($_POST['text']);  // получаем данные по AJAX в виде строки
                $text = explode ("&", $text);
                $vote_variant = array();
                foreach($text as $post)
                {
                    $post_mass = explode ("=", $post);
                    if (substr($post_mass[0], 0, 2) == "tp")
                    {
                        $vid = intval($post_mass[1]);
                        $vote_variant[] = $vid;
                    }    
                    unset($post_mass);
                }
            }
            
            $errors = array();   
            $variants = explode ("\n", $topic['variants']);
            $tp_checked = array();
        
            if($logged)
                $poll_log = $DB->one_select( "id", "topics_poll_logs", "(member_id = '{$member_id['user_id']}' OR ip = '{$_IP}') AND poll_id = '{$topic['poll_id']}'", "LIMIT 1" );
            else
                $poll_log = $DB->one_select( "id", "topics_poll_logs", "ip = '{$_IP}' AND poll_id = '{$topic['poll_id']}'", "LIMIT 1" );
        
            if ($poll_log['id']) $errors[] = $this->lang['poll_used'];
        
            if ($topic['multiple'])
            {  
                if ($ajax)
                {
                    $_POST['tp'] = array();
                    foreach ($vote_variant as $vid)
                    {
                        $_POST['tp'][] = $vid; 
                    }
                }
                
                $tp = $_POST['tp'];
                if (!is_array($tp))
                    $errors[] = $this->lang['poll_no_answer'];
                else
                {
                    foreach ($tp as $tp_check)
                    {
                        $tp_check = intval($tp_check);
                        $tp_checked[$tp_check] = $tp_check;
                
                        if (!$variants[$tp_check]) $errors[] = $this->lang['poll_answer_not_found'];
                    }
                
                    $answer = implode("|", $tp_checked);
                }    
            } 
            else
            {
                if ($ajax)
                {
                    if (count($vote_variant) > 1)
                        $errors[] = $this->lang['poll_no_answer'];
                    else
                        $_POST['tp_1'] = $vote_variant[0];
                }
                
                if (!isset($_POST['tp_1'])) $errors[] = $this->lang['poll_no_answer'];
                
                $tp_1 = intval($_POST['tp_1']);
                if (!$variants[$tp_1]) $errors[] = $this->lang['poll_answer_not_found'];
                
                $answer = $tp_1;
                $tp_checked[$tp_1] = $tp_1;
            }
        
            if( ! $errors[0] )
            { 
                $answers = array();
            
                if ($topic['answers'])
                {
                    $answers_old = explode ("|", $topic['answers']);
                    foreach ($answers_old as $vote)
                    {
                        $vote = explode (":", $vote);
                        list($sp, $num) = $vote;
                    
                        if (isset($tp_checked[$sp]))
                        {
                            unset($tp_checked[$sp]);
                            $num += 1;
                        }
                        
                        $result[$sp] = $num;
                    }
                
                    foreach ($result as $key => $old)
                    {
                        $answers[] = $key.":".$old;
                    }
                } 
                        
                if (count($tp_checked))
                {
                    foreach ($tp_checked as $new)
                    {
                        $answers[] = $new.":1";
                    }
                }

                if (count($answers))
                    $answers = implode ("|", $answers);
                else
                    $answers = "";
            
                if (!$logged)
                {
                    $member_id['user_id'] = 0;
                    $member_id['name'] = "";
                }
                                        
                $DB->update("vote_num = vote_num+1, answers = '{$answers}'", "topics_poll", "id = '{$topic['poll_id']}'");
                $DB->insert("poll_id = '{$topic['poll_id']}', ip = '{$_IP}', member_id = '{$member_id['user_id']}', log_date = '{$time}', answer = '{$answer}', member_name = '{$member_id['name']}'", "topics_poll_logs");
                
                if (!$ajax)
                {
                    header( "Location: ".topic_link($topic['id'], $topic['forum_id'], false, false, intval ($_GET['page'])) );
                    exit();
                }
            }
            else
            {
                if (!$ajax)
                    message ($lang_message['error'], $errors);
                else
                {
                    $mes = "";
                    foreach ($errors as $mes_data)
                    {
                        $mes .= "- ".$mes_data."<br />";
                    }
                    echo show_jq_message("3", $lang_message['error'], $mes);        
                    echo echo_poll();
                    stop_script();
                }
            }
        }
    }
}
?>