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

$lang_m_b_reply = language_forum ("board/modules/board/reply");

$topic = $DB->one_select( "*", "topics", "id = '{$id}'" );

function here_loaction ()
{
    global $link_speddbar, $onl_location, $lang_m_b_reply;
    
    $link_speddbar = speedbar_forum (0)."|".$lang_m_b_reply['location_hide'];
    $onl_location = $lang_m_b_reply['location_online_hide'];
}

function here_loaction_2 ()
{
    global $link_speddbar, $onl_location, $lang_m_b_reply, $topic;
        
    $location_access_denied = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_reply['location_access_denied']);
    $location_access_denied = str_replace("{title}", $topic['title'], $location_access_denied);
    $link_speddbar = speedbar_forum ($topic['forum_id'])."|".$location_access_denied;

    $location_access_denied = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_reply['location_online_access_denied']);
    $location_access_denied = str_replace("{title}", $topic['title'], $location_access_denied);
    $onl_location = $location_access_denied;
}

if ($topic['id'] AND !forum_permission($topic['forum_id'], "read_forum"))
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_reply['access_denied_group_f']), 1);
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "read_theme"))
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_reply['access_denied_group_r']), 1);
}
elseif (!$cache_group[$member_id['user_group']]['g_reply_topic'] AND $topic['member_id_open'] != $member_id['user_id'] AND $logged)
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_reply['access_denied_group_a_other']), 1);
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "answer_theme"))
{
    here_loaction_2 ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_reply['access_denied_group_a_all']), 1);
}
elseif ($topic['status'] == "closed" AND !forum_options_topics("0", "reply_close") AND !$cache_group[$member_id['user_group']]['g_reply_close'])
{
    here_loaction_2 ();
    message ($lang_m_b_reply['topic_close'], $lang_m_b_reply['topic_close_info'], 1);
}
elseif ($topic['id'] AND $topic['hiden'] AND !forum_options_topics($topic['forum_id'], "hideshow"))
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_reply['access_denied_group_h']), 1);
}
elseif ($topic['id'] AND forum_all_password($topic['forum_id']))
{
    $mo_loc_fid = $topic['forum_id'];
    
    if(isset($_POST['check_pass']))
    {
        $check_f_pass_id = intval($_POST['f_id']);
        $check_f_pass = $_POST['f_pass'];
        if ($LB_flood->isBlock())
            message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
        elseif ($check_f_pass == $cache_forums[$topic['forum_id']]['password'] AND $cache_forums[$topic['forum_id']]['password'] != "")
        {
            if($member_id['user_group'] != 5)
                $who = $member_id['name'];
            else
                $who = $_IP;
            
            $check_f_pass = md5($who.$cache_forums[$topic['forum_id']]['password']);
            update_cookie( "LB_password_forum_".$topic['forum_id'], $check_f_pass, 365 );
            header( "Location: {$_SERVER['REQUEST_URI']}" );
            exit();
        }
        elseif ($check_f_pass != $cache_forums[$topic['forum_id']]['password'] AND $cache_forums[$topic['forum_id']]['password'] != "")
            message ($lang_message['access_denied'], $lang_m_b_reply['wrong_password_forum']);
    }
    
    here_loaction_2 ();
    message ($lang_message['access_denied'], $lang_m_b_reply['write_password_forum']);
            
    $tpl->load_template ( 'board/forum_password.tpl' );
    $tpl->tags('{forum_title}', $cache_forums[forum_all_password($topic['forum_id'])]['title']);
    $tpl->tags('{forum_id}', forum_all_password($topic['forum_id']));
    $tpl->compile('content');
    $tpl->clear();
}
elseif (!member_publ_access(1))
{
    here_loaction_2 ();
    
    $message_arr = array();
    $message_arr[] = $lang_m_b_reply['access_denied_publ_access'];
    $message_arr[] = member_publ_info();

    message ($lang_message['access_denied'], $message_arr, 1);   
}
elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic['forum_id'])
{
    here_loaction_2 ();
    message ($lang_message['access_denied'], $lang_m_b_reply['forum_basket'], 1);
}
elseif($cache_forums[$topic['forum_id']]['flink'])    
{
    header("Location: ".forum_link($topic['forum_id']));
    exit ("Redirect.");
}
elseif($topic['id'])
{
    $rss_link = "t=".$topic['id'];
    $mo_loc_fid = $topic['forum_id'];
    
    cookie_forums_read_update ($topic['forum_id'], $topic['date_last']);
    member_topic_read_update ($topic['id'], $topic['date_last']);
    
    $bb_allowed_out = array();
    if ($cache_forums[$topic['forum_id']]['allow_bbcode'])
    {
        if ($cache_forums[$topic['forum_id']]['allow_bbcode_list'] AND $cache_forums[$topic['forum_id']]['allow_bbcode_list'] != "0")
        {
            include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
            $allow_bbcode_list = explode(",", $cache_forums[$topic['forum_id']]['allow_bbcode_list']);
            foreach($allow_bbcode_list as $value)
            {
                $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
            }
        }
    }
    
    $preview = 0;
    $text = "";
    $guest_id = 0;
    $guest_name = "";
    
    // Формирование мет-данных
    if ($topic['metatitle']) $meta_info_text = $topic['metatitle'];
    else $meta_info_other = $topic['title'];
    
    if ($topic['metadescr']) $meta_info_forum_desc = $topic['metadescr'];
    else $meta_info_forum_desc = $cache_forums[$topic['forum_id']]['meta_desc'];
    
    if ($topic['metakeys']) $meta_info_forum_keys = $topic['metakeys'];
    else $meta_info_forum_keys = $cache_forums[$topic['forum_id']]['meta_key']; 
    
    $meta_info_forum = $topic['forum_id'];

    include_once LB_CLASS.'/posts_out.php';
    $LB_posts = new LB_posts;

    include_once LB_CLASS. "/upload_files.php";
    $LB_upload = new LB_Upload();
    
    $small_img = "";

    if (isset($_POST['preview']))
    {
        $preview = 1;
    
        $_POST['text'] = htmlspecialchars($_POST['text']);
        filters_input ('post');
   
        $text = parse_word(html_entity_decode($_POST['text']), $cache_forums[$topic['forum_id']]['allow_bbcode'], true, true, $bb_allowed_out);
                  
        $_SESSION['LB_reply_text_'.$topic['id']] = parse_back_word($text);
                 
        if (strpos($text, "[attachment=") !== false)
        {                            
            $text = show_attach ($text, "0");
        }
        
        $text = hide_in_post($text, $member_id['user_id']);
        
$small_img = <<<SCRIPT

    <script type="text/javascript">
    $(window).load(function(){
        Resize_img();
    });
    </script>

SCRIPT;
    
        if (!$logged)
        {
            $guest_name = words_wilter(trim(htmlspecialchars($_POST['guest_name'])));
        }
    }
    elseif (isset($_POST['add_file']))
    {      
        $preview = 2;
        $upload_status = $LB_upload->Uploading($topic['forum_id'], $topic['id']);
        if ($upload_status)
            message ($lang_message['error'], $upload_status);
            
        if (!$logged)
        {
            $guest_name = words_wilter(trim(htmlspecialchars($_POST['guest_name'])));
        }
            
        $_SESSION['LB_reply_text_'.$topic['id']] = $_POST['text'];
        
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
    elseif ($LB_flood->isBlock() AND isset($_POST['addpost']))
        message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
    elseif (isset($_POST['addpost']))
    {
        $LB_posts->add_post($lang_m_b_reply, $topic, $bb_allowed_out, "reply", false);
    }
        
    $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_reply['location']);
    $lang_location = str_replace("{title}", $topic['title'], $lang_location);
    $link_speddbar = speedbar_forum ($topic['forum_id'])."|".$lang_location;
    
    $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_reply['location_online']);
    $lang_location = str_replace("{title}", $topic['title'], $lang_location);
    $onl_location = $lang_location;
    
    if(!forum_options_topics($topic['forum_id'], "hideshow")) $where = "AND hide = '0'";
    else $where = "";
        
    $DB->prefix = array( 1 => DLE_USER_PREFIX );
    $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num", "LEFT", "posts p||users u||members_online mo", "p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", "topic_id = '{$id}' {$where}", "ORDER by post_date DESC LIMIT 10" );
    $LB_posts->Data_out("board/reply_last_posts.tpl", "posts", $topic);
    
    unset($LB_posts);

    if ($topic['status'] == "closed" AND (forum_options_topics("0", "reply_close") OR $cache_group[$member_id['user_group']]['g_reply_close']))
         message ($lang_message['warning'], $lang_m_b_reply['close_open']);
  
    $tpl->load_template ( 'board/reply_global.tpl' );
        
    if ($cache_forums[$topic['forum_id']]['allow_bbcode'])
    {
        require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
        $tpl->tags('{bbcode}', $bbcode_script.$bbcode); 
    }
    else
        $tpl->tags('{bbcode}', "");
        
    if ($preview == 1) $tpl->tags_blocks("preview");
    else $tpl->tags_blocks("preview", false);
    
    $tpl->tags('{text_pr}', $text.$small_img);     
     
    if (isset($_REQUEST['pid']) AND intval($_REQUEST['pid']) AND $preview != 1)
    {   
        $post_q = $DB->one_join_select( "p.pid, p.text, p.post_date, p.post_member_name, p.post_member_id, t.forum_id, t.hiden", "LEFT", "posts p||topics t", "p.topic_id=t.id", "p.pid = '".intval($_REQUEST['pid'])."'" );
        if ($post_q['pid'])
        {            
            $post_q['text'] = hide_in_post($post_q['text'], $post_q['post_member_id'], true);
            
            if (!forum_permission($post_q['forum_id'], "read_theme") OR !forum_permission($post_q['forum_id'], "read_forum") OR forum_all_password($post_q['forum_id']))
                $_SESSION['LB_reply_text_'.$topic['id']] = "";
            elseif ($post_q['hiden'] AND !forum_options_topics($post_q['forum_id'], "hideshow"))
                $_SESSION['LB_reply_text_'.$topic['id']] = "";
            else
                $_SESSION['LB_reply_text_'.$topic['id']] = "[quote=".$post_q['post_member_name']."|".date("d.m.Y, H:i", $post_q['post_date'])."]".trim(parse_back_word($post_q['text']))."[/quote]";
        }
    }
        
    if ($cache_config['security_captcha_posts']['conf_value'] AND !$logged)
    {
        $tpl->tags_blocks("captcha");
        $tpl->tags( '{captcha}', "<img id=\"recaptcha_img\" src=\"".$redirect_url."components/class/kcaptcha/kcaptcha.php\"><br /><a href=\"#\" id=\"recaptcha\">".$lang_message['change_captcha']."</a>" );
    }
    else
        $tpl->tags_blocks("captcha", false);
        
    if (captcha_dop_check("guest") AND !$logged)
    {
        $tpl->tags_blocks("captcha_dop");
        $tpl->tags( '{captcha_dop}', captcha_dop());
    }
    else
        $tpl->tags_blocks("captcha_dop", false);
    
    if (isset($_SESSION['LB_reply_text_'.$topic['id']]) AND $_SESSION['LB_reply_text_'.$topic['id']] != "")
        $tpl->tags('{quote}', $_SESSION['LB_reply_text_'.$topic['id']]);
    else
        $tpl->tags('{quote}', "");
        
    $tpl->tags('{guest_name}', $guest_name);
    $tpl->tags('{tid}', $topic['id']);
        
    if (forum_permission($topic['forum_id'], "upload_files") AND $logged)
    {
        $tpl->tags_blocks("attachment");
        $tpl->tags('{attachments}', $LB_upload->Out_link($topic['id'], 0, $member_id['user_id'], 1));
    }
    else
        $tpl->tags_blocks("attachment", false);
    
$small_img = <<<SCRIPT

    <script type="text/javascript">
    $(window).load(function(){
        Resize_img();
    });
    </script>
SCRIPT;
    
    $tpl->tags_templ('{posts}', $tpl->result['posts'].$small_img);
    $tpl->tags('{topic_title}', $topic['title']);
    
    $tpl->compile('content');
    $tpl->clear();
    
    unset ($LB_upload);
}
else
    message ($lang_m_b_reply['not_found'], $lang_m_b_reply['not_found_info'], 1);

?>