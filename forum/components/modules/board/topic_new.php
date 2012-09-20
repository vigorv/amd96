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

$lang_m_b_topic_new = language_forum ("board/modules/board/topic_new");

$preview = 0;
$poll_title = "";
$poll_question = "";
$poll_mult = "";
$variants = "";
$desc = "";
$title = "";
$text = "";
$guest_id = 0;
$guest_name = "";
$small_img = "";

$meta_topic = array(
        "title" => "",
        "description" => "",
        "keywors" => ""
    );

$bb_allowed_out = array();
if ($cache_forums[$id]['allow_bbcode'])
{
    if ($cache_forums[$id]['allow_bbcode_list'] AND $cache_forums[$id]['allow_bbcode_list'] != "0")
    {
        include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
        $allow_bbcode_list = explode(",", $cache_forums[$id]['allow_bbcode_list']);
        foreach($allow_bbcode_list as $value)
        {
            $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
        }
    }
}

include_once LB_CLASS. "/upload_files.php";
$LB_upload = new LB_Upload();

if (isset($_POST['preview']) OR isset($_POST['add_file']))
{
    if (isset($_POST['preview']))
        $preview = 1;
    else
        $preview = 2;
        
    if (isset($_POST['add_file']))
    {
        $upload_status = $LB_upload->Uploading($id, 0, 0);
        if ($upload_status)
            message ($lang_message['error'], $upload_status);
                        
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
    
    if($cache_forums[$id]['allow_poll'] AND isset($_POST['poll_title']) AND $_POST['poll_title'] != "")
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
    
    $text = parse_word(html_entity_decode($_POST['text']), $cache_forums[$id]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));
    
    if (!$logged)
    {
        $guest_name = words_wilter(trim($_POST['guest_name']));
    }
    
    $meta_topic = create_metatags();
}
elseif ($LB_flood->isBlock() AND isset($_POST['newtopic']))
    message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
elseif (isset($_POST['newtopic']))
{
    $errors = array();
    
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
            $errors[] = $del_status;
    }
    
    $_POST['text'] = htmlspecialchars($_POST['text']);
    
    filters_input ('post');
    
    $title = $DB->addslashes(htmlspecialchars(words_wilter($_POST['title'], false)));
    if (utf8_strlen($title) < intval($cache_config['topic_title_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['topic_title_min']['conf_value']), $lang_m_b_topic_new['title_min']);
    if (utf8_strlen($title) > intval($cache_config['topic_title_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['topic_title_max']['conf_value']), $lang_m_b_topic_new['title_max']);
      
    $desc = $DB->addslashes(htmlspecialchars(words_wilter($_POST['desc'])));  
        
    if (utf8_strlen($desc) > 200) $errors[] = $lang_m_b_topic_new['desc_max'];
    
    $desc = wrap_word($desc);
    
    if (utf8_strlen($_POST['text']) < intval($cache_config['posts_text_min']['conf_value'])) $errors[] = str_replace("{min}", intval($cache_config['posts_text_min']['conf_value']), $lang_m_b_topic_new['post_min']); 
    if (utf8_strlen($_POST['text']) > intval($cache_config['posts_text_max']['conf_value'])) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_topic_new['post_max']);
    
    $_POST['text'] = parse_word(html_entity_decode($_POST['text']), $cache_forums[$id]['allow_bbcode'], true, true, $bb_allowed_out, intval($cache_group[$member_id['user_group']]['g_html_allowed']));
    $text = $DB->addslashes($_POST['text']);
    
    if (utf8_strlen($_POST['text']) > 65000) $errors[] = str_replace("{max}", intval($cache_config['posts_text_max']['conf_value']), $lang_m_b_topic_new['post_max_2']);
        
    if (!$logged)
    {
        $member_id['user_id'] = 0;
        $member_id['name'] = $DB->addslashes(words_wilter(trim($_POST['guest_name'])));
        if (!$member_id['name'] OR utf8_strlen($member_id['name']) > 40)
            $errors[] = $lang_m_b_topic_new['no_name'];
            
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
    
    if($cache_forums[$id]['allow_poll'] AND isset($_POST['poll_title']) AND $_POST['poll_title'] != "")
    {
        $poll_title = $DB->addslashes(htmlspecialchars($_POST['poll_title']));
        $poll_question = $DB->addslashes(htmlspecialchars($_POST['poll_question']));
        
        if (utf8_strlen($poll_title) < 3 OR utf8_strlen($poll_title) > 200) $errors[] = $lang_m_b_topic_new['title_limit'];
        if (utf8_strlen($poll_question) < 3 OR utf8_strlen($poll_question) > 200) $errors[] = $lang_m_b_topic_new['question_limit'];
        
        if (intval($_POST['poll_mult']))
            $poll_mult = 1;
        else
            $poll_mult = 0;
            
        $variants = htmlspecialchars($_POST['variants']);
        $variants_mas = explode ("\r\n", $variants);
        
        if (count($variants_mas))
        {
            $variants_mas2 = array();
            foreach ($variants_mas as $value)
            {
                if (utf8_strlen($value) > 0)
                    $variants_mas2[] = $value;
            }
        }
        
        if (count($variants_mas2) < 2 OR !$variants_mas2[0])
            $errors[] = $lang_m_b_topic_new['answers_min'];
            
        $variants = $DB->addslashes( implode("\r\n", $variants_mas2) );
    }
    
    // Создание мет-тегов темы
    $meta_topic = create_metatags();
    
    if ($meta_topic['title'] != "")
    {
        if (utf8_strlen($meta_topic['title']) < 3) $errors[] = str_replace("{min}", "3", $lang_m_b_topic_new['metatitle_min']);
        if (utf8_strlen($meta_topic['title']) > 255) $errors[] = str_replace("{max}", "255", $lang_m_b_topic_new['metatitle_max']);
    }
    if ($meta_topic['description'] != "")
    {
        if (utf8_strlen($meta_topic['description']) < 3) $errors[] = str_replace("{min}", "3", $lang_m_b_topic_new['metadescr_min']);
        if (utf8_strlen($meta_topic['description']) > 200) $errors[] = str_replace("{max}", "200", $lang_m_b_topic_new['metadescr_max']);
    }
    if ($meta_topic['keywords'] != "")
    {
        if (utf8_strlen($meta_topic['keywords']) < 3) $errors[] = str_replace("{min}", "3", $lang_m_b_topic_new['metakeys_min']);
        if (utf8_strlen($meta_topic['keywords']) > 1000) $errors[] = str_replace("{max}", "1000", $lang_m_b_topic_new['metakeys_max']);
    }
        
   	if( ! $errors[0] )
	{   
        unset($_SESSION['captcha_keystring']);	   
        unset($_SESSION['captcha_keystring_a']);
        unset($_SESSION['captcha_keystring_q_num']);
        unset($_SESSION['captcha_keystring_q']);
        
        $attachment_mas = $LB_upload->Add_attachments(0, 0, $member_id['user_id'], $text);
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
    
        $DB->insert("topic_id = '0', new_topic = '1', text = '{$text}', attachments = '{$a_id}', post_date = '{$time}', post_member_id = '{$member_id['user_id']}', post_member_name = '{$member_id['name']}', ip = '{$_IP}', utility = '0'", "posts");
        $post_id = $DB->insert_id();
        
        $where = array();
        foreach ($meta_topic as $key => $value)
        {
            if ($key == "title") $where[] = "metatitle = '".$DB->addslashes($value)."'";
            if ($key == "description") $where[] = "metadescr = '".$DB->addslashes($value)."'";
            if ($key == "keywords") $where[] = "metakeys = '".$DB->addslashes($value)."'";
        }
        $where = implode (", ", $where);
        
        $DB->insert("forum_id = '{$id}', title = '{$title}', description = '{$desc}', post_id = '{$post_id}', date_open = '{$time}', date_last = '{$time}', status = 'open', last_post_id = '{$post_id}', last_post_member = '{$member_id['user_id']}', member_name_last = '{$member_id['name']}', member_name_open = '{$member_id['name']}', member_id_open = '{$member_id['user_id']}', ".$where, "topics");
        $topic_id = $DB->insert_id();
        
        if (forum_permission($id, "upload_files") AND $logged AND $a_id != "")
            $DB->update("file_tid = '{$topic_id}', file_pid = '{$post_id}'", "topics_files", "file_fid = '{$id}' AND file_tid = '0' AND file_pid = '0' AND file_mid = '{$member_id['user_id']}'");
        
        if($cache_forums[$id]['allow_poll'] AND isset($_POST['poll_title']) AND $_POST['poll_title'] != "")
        {
            $DB->insert("tid = '{$topic_id}', title = '{$poll_title}', question = '{$poll_question}', variants = '{$variants}', multiple = '{$poll_mult}', open_date = '{$time}'", "topics_poll");
            $poll_id = $DB->insert_id();
            $DB->update("poll_id = '{$poll_id}'", "topics", "id = '{$topic_id}'");
        }
        
        $DB->update("topic_id = '{$topic_id}'", "posts", "topic_id = '0' AND new_topic='1' AND post_member_id = '{$member_id['user_id']}'");
        $DB->update("last_title = '{$title}', last_post_member = '{$member_id['name']}', last_post_member_id = '{$member_id['user_id']}', last_post_date = '{$time}', last_topic_id = '{$topic_id}', topics = topics+1", "forums", "id = '{$id}'");

        if ($cache_forums[$id]['postcount'])
        {
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("topics_num = topics_num+1", "users", "user_id = '{$member_id['user_id']}'");
        }
        
        $cache_forums[$id]['last_title'] = stripslashes($title);    
        $cache_forums[$id]['last_post_member'] = $member_id['name'];
        $cache_forums[$id]['last_post_member_id'] = $member_id['user_id'];
        $cache_forums[$id]['avatar'] = $member['foto'];
        $cache_forums[$id]['last_post_date'] = $time;
        $cache_forums[$id]['last_topic_id'] = $topic_id; 
        $cache_forums[$id]['topics'] += 1;
        
        $cache->update("forums", $cache_forums);
        
        if (intval($_POST['subscribe']) AND $logged)
        {
            include LB_CLASS.'/topics_out.php';
            $LB_topics = new LB_topics;
            $LB_topics->subscribe ($topic_id, $id);
            unset($LB_topics);
        }
        
        cookie_forums_read_update ($id, $time);
        member_topic_read_update ($topic_id, $time);
        
        header( "Location: ".topic_link($topic_id, $id) );
        exit();
    }
    else
		message ($lang_message['error'], $errors);
        
    $text = stripslashes($text);
}

$link_speddbar = speedbar_forum ($id)."|".$lang_m_b_topic_new['location'];

$lang_location = str_replace("{link}", forum_link($id), $lang_m_b_topic_new['location']);
$lang_location = str_replace("{title}", $cache_forums[$id]['title'], $lang_location);
$onl_location = $lang_location;
  
$meta_info_forum = $id;
$meta_info_other = $lang_m_b_topic_new['meta_info'];
  
$tpl->load_template ( 'board/topic_new.tpl' );

if ($cache_forums[$id]['allow_bbcode'])
{
    require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
    $tpl->tags('{bbcode}', $bbcode_script.$bbcode); 
}
else
    $tpl->tags('{bbcode}', "");
    
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
    
$tpl->tags('{text}', parse_back_word($text, true, intval($cache_group[$member_id['user_group']]['g_html_allowed'])));
    
if ($preview == 1)
{
    $tpl->tags_blocks("preview");
    
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

}
else
    $tpl->tags_blocks("preview", false);
    
$tpl->tags('{text_pr}', $text.$small_img);
$tpl->tags('{title}', $title);
$tpl->tags('{desc}', $desc);
    
$tpl->tags('{guest_id}', $guest_id);
$tpl->tags('{guest_name}', $guest_name);
                
$tpl->tags('{poll_title}', $poll_title);
$tpl->tags('{poll_question}', $poll_question);
$tpl->tags('{poll_mult}', $poll_mult);
$tpl->tags('{variants}', str_replace("\\r\\n", "\r\n", $variants));
    
$tpl->tags_blocks("poll", $cache_forums[$id]['allow_poll']);
    
if (forum_permission($id, "upload_files") AND $logged)
{
    $tpl->tags_blocks("attachment");
    $tpl->tags('{attachments}', $LB_upload->Out_link(0, 0, $member_id['user_id'], 1));
}
else
    $tpl->tags_blocks("attachment", false);
    
$tpl->tags_blocks("metadata", intval($cache_group[$member_id['user_group']]['g_metatopic']));
$tpl->tags('{meta_title}', $meta_topic['title']);
$tpl->tags('{meta_description}', $meta_topic['description']);
$tpl->tags('{meta_keywords}', $meta_topic['keywords']);
    
$tpl->tags('{forum_title}', $cache_forums[$id]['title']);
$tpl->compile('content');
$tpl->clear();

unset ($LB_upload);

?>