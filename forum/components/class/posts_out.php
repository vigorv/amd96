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

class LB_posts
{
	PUBLIC $query = "";

	function Data_out($template, $template_save, $topic = "", $attachment = true, $adt = true, $other_counter = false, $topic_nav = false, $ajax_post = false, $numbering = true)
	{
        global $tpl, $DB, $cache_config, $member_id, $cache_group, $cache_forums, $i, $j, $onl_limit, $link_on_post, $lang_message, $cache, $logged, $time;
                
        if ($attachment)
            $attachment_post = array();
            
        $adt_i = 0;
        
        if (is_array($topic))
        {
            $topic_fid = $topic['forum_id'];
            $topic_status = $topic['status'];
            $topic_mido = $topic['member_id_open'];
        }
        else
        {
            $topic_fid = 0;
            $topic_status = "";
            $topic_mido = 0;
        }
        
        $tpl->load_template ( $template );
        
        while ( $row = $DB->get_row($this->query) )
        {
            if (!$other_counter)
                $i ++;
            else
                $j ++;
                        
            if ($attachment)
            {
                if ($row['attachments'])
                    $attachment_post[] = $row['pid'];
            }
            
            if ($adt)
            {
                $tpl->copy_template .= topics_adtblock( $adt_i, $cache_config['topic_post_page']['conf_value'] );
                $adt_i ++;
            }
            
            if (isset($row['forum_id'])) $topic_fid = $row['forum_id'];
            if (isset($row['status'])) $topic_status = $row['status'];
            if (isset($row['member_id_open'])) $topic_mido = $row['member_id_open'];
                
    		$tpl->copy_template .= "<div id=\"post-id_jq-".$row['pid']."\"></div>";
            
            $tpl->tags_blocks("moder_mass", forum_options_topics_mas($topic_fid, $row['topic_id'], "check"));
            
            if (!$logged AND !$cache_config['posts_utility']['conf_value'])
                $tpl->tags_blocks("utility", false);
            elseif ($row['post_member_id'] == $member_id['user_id'])
                $tpl->tags_blocks("utility", false);
            else
                $tpl->tags_blocks("utility");  
                          
            $tpl->tags('{utility}', "<span id=\"utility_".$row['pid']."\">".$row['utility']."</span>");
            
            $tpl->tags('{pid}', $row['pid']);
            $tpl->tags('{link_on_post}', $link_on_post."#post".$row['pid']);
            
            $tpl->tags_blocks("full_link", $topic_nav);
            if ($topic_nav)
            {
                $tpl->tags('{forum}', topic_allforum($topic_fid, intval($cache_config['topic_allforums']['conf_value'])));
                $tpl->tags('{topic}', $row['title']);
                $tpl->tags('{topic_link}', topic_link($row['topic_id'], $topic_fid));
            }
            
            if ($row['post_member_id'] == 0 OR !$row['user_id'])
            {
                $tpl->tags_blocks("guest_post");
                $tpl->tags_blocks("member_post", false);
                $tpl->tags('{member_name}', $row['post_member_name']);
                $tpl->tags('{member_group}', member_group(5));
                $tpl->tags('{member_avatar}', member_avatar());
                $tpl->tags('{member_id}', 0);
                $tpl->tags_blocks("online", false);
                $tpl->tags_blocks("offline", false);
            }
            else
            {
                $tpl->tags_blocks("member_post");
                $tpl->tags_blocks("guest_post", false);
                $tpl->tags('{member_name}', $row['name']);
                $tpl->tags('{member_group}', member_group($row['user_group'], $row['banned']));
                $tpl->tags_blocks("online", member_online($row['mo_id'], $row['mo_date'], $onl_limit));
                $tpl->tags_blocks("offline", member_online($row['mo_id'], $row['mo_date'], $onl_limit), true);
                $tpl->tags('{member_avatar}', member_avatar($row['foto']));
                $tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
                $tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
                $tpl->tags('{topics_link}', member_topics_link($row['name'], $row['user_id']));
                $tpl->tags('{posts_link}', member_posts_link($row['name'], $row['user_id']));
                
                if (intval($cache_config['forums_unitetp']['conf_value']))
                    $tpl->tags('{member_posts}', $row['posts_num'] + $row['topics_num']);
                else
                    $tpl->tags('{member_posts}', $row['posts_num']);
                    
                $tpl->tags('{member_id}', $row['user_id']);
            }
        
            $tpl->tags('{member_group_icon}', member_group_icon($row['user_group']));
            
            if (!$cache_group[$row['user_group']]['g_signature'] OR !$row['post_member_id'] OR ($cache_group[$row['user_group']]['g_signature'] AND !$row['signature']))
            {
                $tpl->tags('{signature}', "");
                $tpl->tags_blocks("signature", false);
            }
            else
            {
                $tpl->tags('{signature}', $row['signature']);
                $tpl->tags_blocks("signature");
            }
        
            if (member_rank($row['posts_num'], $row['user_id']))
            {
                $rank = member_rank($row['posts_num'], $row['user_id']);
                $tpl->tags('{ranks_starts}', $rank[0]);
                $tpl->tags('{ranks_title}', $rank[1]);
                $tpl->tags_blocks("ranks");
            }
            else
                $tpl->tags_blocks("ranks", false);
            
            if (!$row['edit_member_id'])
                $tpl->tags_blocks("last_edit_post", false);
            else
            {
                if ($row['edit_reason'])
                {
                    $tpl->tags('{edit_reason}', $row['edit_reason']);
                    $tpl->tags_blocks("edit_reason");
                }
                else
                    $tpl->tags_blocks("edit_reason", false);
                    
                $tpl->tags('{edit_date}', formatdate($row['edit_date']));
                $tpl->tags('{edit_member_link}', profile_link($row['edit_member_name'], $row['edit_member_id']));
                $tpl->tags('{edit_member}', $row['edit_member_name']);
                $tpl->tags_blocks("last_edit_post");
            }
               
            $tpl->tags_blocks("hide_post_moder", forum_options_topics($topic_fid, "hidetopic"));
            if(forum_options_topics($topic_fid, "hidetopic"))
            {                
                $tpl->tags('{post_showhide_link}', post_edit_link($row['pid'], "showhide", intval($_GET['page'])));
                
                if ($row['hide'])
                {
                    $tpl->tags_blocks("hide_post");
                    $tpl->tags_blocks("un_hide_post", false);
                }
                else
                {
                    $tpl->tags_blocks("hide_post", false);
                    $tpl->tags_blocks("un_hide_post");
                }
            }
            else
            {                
                if (forum_options_topics($topic_fid, "hideshow") AND $row['hide'])
                {
                    $tpl->tags_blocks("hide_post");
                    $tpl->tags_blocks("un_hide_post", false);
                }
                else
                {
                    $tpl->tags_blocks("hide_post", false);
                    $tpl->tags_blocks("un_hide_post");
                }
            }
            
            $tpl->tags_blocks("fixed_post_moder", forum_options_topics($topic_fid, "fixedpost"));
            if(forum_options_topics($topic_fid, "fixedpost"))
            {                
                $tpl->tags('{post_unfixed_link}', post_edit_link($row['pid'], "unfixed", intval($_GET['page'])));
                $tpl->tags('{post_fixed_link}', post_edit_link($row['pid'], "fixed", intval($_GET['page'])));
                
                $tpl->tags_blocks("unfixed_post", $row['fixed']);
                $tpl->tags_blocks("fixed_post", $row['fixed'], true);
            }
                
            if(!$row['new_topic'] AND forum_options_topics($topic_fid, "delpost"))
            {
                $tpl->tags_blocks("del_post");
                $tpl->tags('{post_del_link}', post_edit_link($row['pid'], "delete", intval($_GET['page'])));
            }
            elseif(!$row['new_topic'] AND $member_id['user_id'] == $row['post_member_id'] AND group_permission("local_delpost"))
            {
                $access_edit_post = true;
                if (intval($cache_group[$member_id['user_group']]['g_pc_time']))
                {
                    $pc_time = intval($cache_group[$member_id['user_group']]['g_pc_time']) * 60 * 60;
                    if (($row['post_date'] + $pc_time) < $time) $access_edit_post = false;
                }
                
                if ($access_edit_post)
                {
                    $tpl->tags_blocks("del_post");
                    $tpl->tags('{post_del_link}', post_edit_link($row['pid'], "delete", intval($_GET['page'])));
                }
                else
                    $tpl->tags_blocks("del_post", false);
            }
            else
                $tpl->tags_blocks("del_post", false);
               
            $double_click = "";
            if(forum_options_topics($topic_fid, "changepost"))
            {
                $tpl->tags_blocks("edit_post");
                $tpl->tags('{post_edit_link}', post_edit_link($row['pid'], "edit", intval($_GET['page'])));
                $double_click = "ondblClick=\"EditPost('".$row['pid']."');return false;\"";
            }
            elseif ($member_id['user_id'] == $row['post_member_id'] AND group_permission("local_changepost"))
            {
                $access_edit_post = true;
                if (intval($cache_group[$member_id['user_group']]['g_pc_time']))
                {
                    $pc_time = intval($cache_group[$member_id['user_group']]['g_pc_time']) * 60 * 60;
                    if (($row['post_date'] + $pc_time) < $time) $access_edit_post = false;
                }
                
                if ($access_edit_post)
                {
                    $tpl->tags_blocks("edit_post");
                    $tpl->tags('{post_edit_link}', post_edit_link($row['pid'], "edit", intval($_GET['page'])));
                    $double_click = "ondblClick=\"EditPost('".$row['pid']."');return false;\"";
                }
                else
                    $tpl->tags_blocks("edit_post", false);
            }
            else
                $tpl->tags_blocks("edit_post", false); 
                
            $tpl->tags_blocks("moder_warning", $row['moder_reason']);
            if($row['moder_reason'])
            {
                $tpl->tags('{moder_reason}', $row['moder_reason']);
                $tpl->tags('{moder_member_name}', $row['moder_member_name']);
                $tpl->tags('{moder_member_link}', profile_link($row['moder_member_name'], $row['moder_member_id']));
                $tpl->tags('{moder_date}', formatdate($row['moder_date']));
            }
            
            if (!$numbering)
                $tpl->tags('{post_id}', "--");
            elseif ($row['fixed'] AND $other_counter)
                $tpl->tags('{post_id}', $j);
            else
                $tpl->tags('{post_id}', $i);
                
            $tpl->tags('{post_date}', formatdate($row['post_date']));
            $tpl->tags('{post_date2}', date("d.m.Y, H:i", $row['post_date']));
                                
            $tpl->tags('{reply_link}', reply_link($row['topic_id'], $row['pid'], $topic_fid));
            
            if (forum_moderation() AND ($cache_group[$member_id['user_group']]['g_show_ip'] OR $member_id['user_group'] == 1))
            {
                $tpl->tags_blocks("ip", true);
                if ($cache_config['security_adminip']['conf_value'] AND $row['user_group'] == "1")
                    $tpl->tags('{ip}', $lang_message['ip_hide']);
                else
                    $tpl->tags('{ip}', "<a href=\"".$cache_config['general_site']['conf_value']."control_center/?do=users&op=tools&ip=".$row['ip']."\" target=\"blank\">".$row['ip']."</a>");
            }
            else
            {
                $tpl->tags_blocks("ip", false);
                $tpl->tags('{ip}', "");
            }
                            
            $tpl->tags_blocks("post_log", forum_options_topics($topic_fid, "post_log"));
            $tpl->tags_blocks("complaint", $logged);
            $tpl->tags("{complaint_data}", "'post', '".$row['pid']."'");
                
            if ($topic_status == "closed" AND !forum_options_topics("0", "reply_close") AND !$cache_group[$member_id['user_group']]['g_reply_close'])
                $tpl->tags_blocks("answer_but", false);
            elseif(!forum_permission($topic_fid, "answer_theme"))
                $tpl->tags_blocks("answer_but", false);
            elseif (!member_publ_access(1))
           	    $tpl->tags_blocks("answer_but", false);
            elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic_fid)
                $tpl->tags_blocks("answer_but", false);
            elseif (!$cache_group[$member_id['user_group']]['g_reply_topic'] AND $topic_mido != $member_id['user_id'] AND $logged)
           	    $tpl->tags_blocks("answer_but", false);
            else
                $tpl->tags_blocks("answer_but");
                
            $row['text'] = hide_in_post($row['text'], $row['post_member_id']);
                
            $tpl->tags('{post_text}', "<div id=\"post-id-".$row['pid']."\" ".$double_click.">".$row['text']."</div>");

            $tpl->compile($template_save);
        
        }

        if ($attachment)
        {        
            if (strpos($tpl->result[$template_save], "[attachment=") !== false)
            {                
                $tpl->result[$template_save] = show_attach ($tpl->result[$template_save], $attachment_post);
            }
        } 
           
        $DB->free($LB_posts_query);    
        $tpl->clear();
        
        $this->clear();
        
        if (!$ajax_post)
        {
$small_img = <<<SCRIPT

    <script type="text/javascript">
    $(window).load(function(){
        Resize_img();
    });
    </script>
SCRIPT;
        }
        else
        {
$small_img = <<<SCRIPT

    <script type="text/javascript">Resize_img();</script>
SCRIPT;
        }
        $tpl->result[$template_save] .= $small_img;

	}

	function clear()
	{
        unset($this->query);
		$this->query = "";
	}
    
    function add_post ($lang_post, $topic, $bb_allowed_out, $type, $check = true)
    {
        global $cache_config, $DB, $LB_flood, $lang_message, $cache_group, $cache_forums, $member_id, $logged, $time, $_IP, $cache, $secret_key;
                
        if ($check)
        {
            if ($LB_flood->isBlock())
            {
                message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
                return;
            }
            elseif ($topic['status'] == "closed" AND !forum_options_topics("0", "reply_close") AND !$cache_group[$member_id['user_group']]['g_reply_close'])
            {
                message ($lang_message['access_denied'], $lang_post['access_denied_close']);
                return; 
            }
            elseif (!member_publ_access(1))
            {  
                message ($lang_message['access_denied'], $lang_post['access_denied_answer']);
                return;    
            }
            elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic['forum_id'])
            {
                message ($lang_message['access_denied'], $lang_post['forum_is_basket']);
                return;
            }
            elseif (!forum_permission($topic['forum_id'], "answer_theme"))
            {
                message ($lang_message['access_denied'], $lang_post['access_denied_forum_answer']);
                return;
            }
        }
        
        $errors = array();
        
        if ($type == "reply")
        {
            include_once LB_CLASS. "/upload_files.php";
            $LB_upload = new LB_Upload();
    
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
        }
                                               
        $_POST['text'] = htmlspecialchars($_POST['text']);
        filters_input ('post');
                        
        if (utf8_strlen($_POST['text']) < intval($cache_config['posts_text_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['posts_text_min']['conf_value']), $lang_post['post_text_min']);
        if (utf8_strlen($_POST['text']) > intval($cache_config['posts_text_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_post['post_text_max']);
                
        $_POST['text'] = parse_word(html_entity_decode($_POST['text']), $cache_forums[$topic['forum_id']]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));        
        
        $text = $DB->addslashes($_POST['text']);
            
        if (utf8_strlen($_POST['text']) > 65000) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_post['post_text_max_2']);
                
        if (!$logged)
        {
            $member_id['user_id'] = 0;
            $member_id['name'] = $DB->addslashes(parse_word($_POST['guest_name'], false, false));
            if (!$member_id['name'] OR utf8_strlen($member_id['name']) > 40)
                $errors[] = $lang_post['no_name'];
                    
            if ($cache_config['security_captcha_posts']['conf_value'])
            {
                if(!isset($_SESSION['captcha_keystring']) OR $_SESSION['captcha_keystring'] != $_POST['keystring'])
                    $errors[] = $lang_message['captcha'];
            }
        
            if (captcha_dop_check("guest"))
            {
                $_SESSION['captcha_keystring_a'] = trim($_POST['keystring_dop']);
                if (!captcha_dop_check_answer())
                    $errors[] = $lang_message['keystring'];
            }         
        }

        if(!count($errors))
        {     
            if ($type == "reply")
            {
                $attachment_mas = $LB_upload->Add_attachments($topic['id'], 0, $member_id['user_id'], $text);
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
            }
            else
                $a_id = "";
            
            unset($_SESSION['LB_reply_text_'.$topic['id']]);
                
            unset($_SESSION['captcha_keystring']);
            unset($_SESSION['captcha_keystring_a']);
            unset($_SESSION['captcha_keystring_q_num']);
            unset($_SESSION['captcha_keystring_q']);
            
            $cache_forums[$topic['forum_id']]['last_title'] = $topic['title'];
            $cache_forums[$topic['forum_id']]['last_post_member'] = $member_id['name'];
            $cache_forums[$topic['forum_id']]['last_post_member_id'] = $member_id['user_id'];
            $cache_forums[$topic['forum_id']]['avatar'] = $member_id['foto'];
            $cache_forums[$topic['forum_id']]['last_post_date'] = $time;
            $cache_forums[$topic['forum_id']]['last_topic_id'] = $topic['id'];
                
            $topic['title'] = $DB->addslashes($topic['title']);
            
            if ($cache_config['posts_paste']['conf_value'] AND $topic['last_post_member'] == $member_id['user_id'] AND ($time - (intval($cache_config['posts_pastemin']['conf_value']) * 60)) <= $topic['date_last'])
            {
                $post_last = $DB->one_select( "text", "posts", "pid = '{$topic['last_post_id']}'" );
                $post_id = $topic['last_post_id'];
                    
                $text = $DB->addslashes($post_last['text'])."<br /><br />".$text;
                    
                if ($type == "reply")
                {
                    $DB->update("text = '{$text}', attachments = '{$a_id}', post_date = '{$time}'", "posts", "pid = '{$topic['last_post_id']}'");
                    
                    if (forum_permission($topic['forum_id'], "upload_files") AND $logged AND $a_id != "")
                        $DB->update("file_pid = '{$topic['last_post_id']}'", "topics_files", "file_tid = '{$topic['id']}' AND file_pid = '0' AND file_mid = '{$member_id['user_id']}'");
                }
                else
                    $DB->update("text = '{$text}', post_date = '{$time}'", "posts", "pid = '{$topic['last_post_id']}'");
                    
                $DB->update("date_last = '{$time}'", "topics", "id = '{$topic['id']}'");
                $DB->update("last_title = '{$topic['title']}', last_post_member = '{$member_id['name']}', last_post_member_id = '{$member_id['user_id']}', last_post_date = '{$time}', last_topic_id = '{$topic['id']}', last_post_id = '{$post_id}'", "forums", "id = '{$topic['forum_id']}'");            
            }
            else
            {
                $DB->insert("topic_id = '{$topic['id']}', new_topic = '0', text = '{$text}', attachments = '{$a_id}', post_date = '{$time}', post_member_id = '{$member_id['user_id']}', post_member_name = '{$member_id['name']}', ip = '{$_IP}'", "posts");
                $post_id = $DB->insert_id();
                $DB->update("last_post_member = '{$member_id['user_id']}', member_name_last = '{$member_id['name']}', last_post_id = '{$post_id}', date_last = '{$time}', post_num = post_num+1", "topics", "id = '{$topic['id']}'");
                $DB->update("last_title = '{$topic['title']}', last_post_member = '{$member_id['name']}', last_post_member_id = '{$member_id['user_id']}', last_post_date = '{$time}', last_topic_id = '{$topic['id']}', posts = posts+1, last_post_id = '{$post_id}'", "forums", "id = '{$topic['forum_id']}'");

                $cache_forums[$topic['forum_id']]['posts'] += 1;
                
                if ($cache_forums[$topic['forum_id']]['postcount'])
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("posts_num = posts_num+1", "users", "user_id = '{$member_id['user_id']}'");
                }
                    
                if (forum_permission($topic['forum_id'], "upload_files") AND $logged AND $a_id != "")   
                    $DB->update("file_pid = '{$post_id}'", "topics_files", "file_tid = '{$topic['id']}' AND file_pid = '0' AND file_mid = '{$member_id['user_id']}'");
            }
                
            $cache_forums[$topic['forum_id']]['last_post_id'] = $post_id; 
                
            topic_do_subscribe ($topic['id'], $topic['title'], $topic['forum_id']);
                
            if ($type == "fast" AND intval($_POST['subscribe']) AND $logged)
            {
                include LB_CLASS.'/topics_out.php';
                $LB_topics = new LB_topics;
                $LB_topics->subscribe ($topic['id'], $topic['forum_id']);
                unset($LB_topics);
            }
                
            cookie_forums_read_update ($topic['forum_id'], $time);
            member_topic_read_update ($topic['id'], $time);
        
            $cache->update("forums", $cache_forums);
            
            if ($type != "ajax")
            {
                header( "Location: ".topic_link($topic['id'], $topic['forum_id'], true) );
                exit();
            }
        }
        else
        {
            if ($type != "ajax")
                message ($lang_message['error'], $errors, 1);
            else
            {
                $mes = "";
                foreach ($errors as $mes_data)
                {
                    $mes .= "- ".$mes_data."<br />";
                }
                echo show_jq_message("3", $lang_s_a_newpost['error'], $mes);
                stop_script();
            }
        }
    }
}
?>