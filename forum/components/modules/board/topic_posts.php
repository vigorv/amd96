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

$lang_m_b_topic_posts = language_forum ("board/modules/board/topic_posts");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$topic = $DB->one_join_select( "t.*, p.vote_num, p.title as p_title, p.question, p.variants, p.multiple, p.answers", "LEFT", "topics t||topics_poll p", "t.poll_id=p.id", "t.id = '{$id}'" );

if (isset($_GET['name']) AND $cache_config['general_rewrite_url']['conf_value'])
{
    filters_input ('get');
    $fid = forum_find_alt_name($_GET['name']);
}

function here_loaction ()
{
    global $link_speddbar, $onl_location, $lang_m_b_topic_posts;
    
    $link_speddbar = speedbar_forum (0)."|".$lang_m_b_topic_posts['location_access_denied'];
    $onl_location = $lang_m_b_topic_posts['location_online_access_denied'];
}

if ($topic['id'] AND !forum_permission($topic['forum_id'], "read_forum"))
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_topic_posts['access_denied_group_v']), 1);
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "read_theme"))
{
    here_loaction ();
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_topic_posts['access_denied_group_r']), 1);
}
elseif ($topic['id'] AND $topic['hiden'] AND !forum_options_topics($topic['forum_id'], "hideshow"))
{
    here_loaction ();
    message ($lang_message['access_denied'], $lang_m_b_topic_posts['access_denied_group_h'], 1);
}
elseif ($topic['id'] AND forum_all_password($topic['forum_id']))
{   
    if(isset($_POST['check_pass']))
    {
        $check_f_pass_id = intval($_POST['f_id']);
        $check_f_pass = $_POST['f_pass'];
        if ($LB_flood->isBlock())
        {
            message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
        }
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
            message ($lang_message['access_denied'], $lang_m_b_topic_posts['forum_wrong_pass']);
    }
    
    $lang_location = str_replace("{title}", $topic['title'], $lang_m_b_topic_posts['location_pass']);
    $link_speddbar = speedbar_forum ($topic['forum_id'])."|".$lang_location;

    $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_topic_posts['location_online_pass']);
    $lang_location = str_replace("{title}", $topic['title'], $lang_location);
    $onl_location = $lang_location;
    
    message ($lang_message['access_denied'], $lang_m_b_topic_posts['forum_pass']);
            
    $tpl->load_template ( 'board/forum_password.tpl' );
    $tpl->tags('{forum_title}', $cache_forums[forum_all_password($topic['forum_id'])]['title']);
    $tpl->tags('{forum_id}', forum_all_password($topic['forum_id']));
    $tpl->compile('content');
    $tpl->clear();
}
elseif ($topic['id'] AND isset($fid) AND $fid != $topic['forum_id'])
{
    header("HTTP/1.0 301 Moved Permanently");
    header("Location: ".topic_link($topic['id'], $topic['forum_id'], false, false, intval ($_GET['page'])));
    exit ("Redirect.");
}
elseif($cache_forums[$topic['forum_id']]['flink'])    
{
    header("Location: ".forum_link($topic['forum_id']));
    exit ("Redirect.");
}
elseif($topic['id'])
{  
    // Если тема была в корзине, но её не осстановили и корзины больше нет, либо ею стал другой форум - удаляем запись о том, что тема в корзине
    if ($topic['basket'] AND (!$cache_config['basket_on']['conf_value'] OR ($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] != $topic['forum_id']))) 
    {
        $DB->update("basket = '0', basket_fid = '0'", "topics", "id = '{$topic['id']}'");
        $topic['basket'] = 0;
        $topic['basket_fid'] = 0;
    }
    // Если тема не была в корзине, но данный форум стал корзиной - обновляем данные темы о том, что она в корзине ("удалена")
    elseif (!$topic['basket'] AND $cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic['forum_id']) 
    {
        $DB->update("basket = '1', basket_fid = '{$topic['forum_id']}'", "topics", "id = '{$topic['id']}'");
        $topic['basket'] = 1;
        $topic['basket_fid'] = $topic['forum_id'];
    }
    
    $mo_loc_fid = $topic['forum_id'];
    
    cookie_forums_read_update ($topic['forum_id'], $topic['date_last']);    
    member_topic_read_update ($topic['id'], $topic['date_last']);
    
    if ($member_id['lb_subscribe'])
    {
        $subscribe_member = explode (",", $member_id['lb_subscribe']);
        if (in_array($topic['id'], $subscribe_member))
        {
            $topic_subs = $DB->one_select( "send_status", "topics_subscribe", "subs_member = '{$member_id['user_id']}'" );
            if ($topic_subs['send_status'])
            {
                $DB->update("send_status = '0'", "topics_subscribe", "subs_member = '{$member_id['user_id']}' AND topic = '{$topic['id']}'");
            }
        }
    }
    
    // Формирование мет-данных
    if ($topic['metatitle']) $meta_info_text = $topic['metatitle'];
    else $meta_info_other = $topic['title'];
    
    if ($topic['metadescr']) $meta_info_forum_desc = $topic['metadescr'];
    else $meta_info_forum_desc = $cache_forums[$topic['forum_id']]['meta_desc'];
    
    if ($topic['metakeys']) $meta_info_forum_keys = $topic['metakeys'];
    else $meta_info_forum_keys = $cache_forums[$topic['forum_id']]['meta_key']; 
    
    $meta_info_forum = $topic['forum_id'];
    
    $rss_link = "t=".$id;   // Ссылка для RSS
        
    if (isset($_POST['polltopic']) AND ($cache_forums[$topic['forum_id']]['allow_poll'] == 2 OR ($cache_forums[$topic['forum_id']]['allow_poll'] == 1 AND $logged)) AND $topic['poll_id'])
    {
        include LB_CLASS.'/topics_out.php';
        $LB_topics = new LB_topics;
        $topic_poll_arr = array(
            "id"        => $topic['id'],
            "forum_id"  => $topic['forum_id'],
            "variants"  => $topic['variants'],
            "poll_id"   => $topic['poll_id'],
            "multiple"  => $topic['multiple'],
            "answers"   => $topic['answers']
        );
        
        $LB_topics->do_vote ($topic_poll_arr);
        unset($LB_topics);
    }
    
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
    
    include LB_CLASS.'/posts_out.php';
    $LB_posts = new LB_posts;
        
    if (isset($_POST['addpost']))
    {
        $LB_posts->add_post($lang_m_b_topic_posts, $topic, $bb_allowed_out, "fast");
    }
    
    $link_speddbar = speedbar_forum ($topic['forum_id'])."|".str_replace("{title}", $topic['title'], $lang_m_b_topic_posts['location']);
    
    $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_topic_posts['location_online']);
    $lang_location = str_replace("{title}", $topic['title'], $lang_location);
    $onl_location = $lang_location;
    
    $where = array();
    
    if (isset ( $_REQUEST['page'] ))
	   $page = intval ( $_GET['page'] );
    else
	   $page = 0;

    $link_on_post = str_replace("{i_page}", $page, navigation_topic_link($id, $topic['forum_id']));

    if ($page < 0)
	   $page = 0;

    if ($page)
    {
	   $page = $page - 1;
	   $page = $page * $cache_config['topic_post_page']['conf_value'];
    }

    $i = $page;
    
    $dopnav = array();
    $go_show = "";
    
    if(!forum_options_topics($topic['forum_id'], "hideshow"))
        $where[] = "hide = '0'";
    else
    {
        if (isset($_GET['go']) AND $_GET['go'] == "hide")
        {
            $where[] = "hide = '1'";
            $dopnav['hide'] = "topics";
            $go_show = "hide";
        }   
    }
    
    if (isset($_GET['utility']) AND intval($_GET['utility']) != 0) $where[] = "utility > '0'";

    if (count($where))
        $where = "AND ".implode (" AND ", $where);
    else
    {
        unset($where);
        $where = "";
    }
        
    $nav = $DB->one_select( "COUNT(*) as count", "posts", "topic_id = '{$id}' AND fixed = '0' {$where}");
    $nav_all = $nav['count'];
    $DB->free($nav);
    
    if (isset($_GET['s']))
    {
        $_GET['s'] = urldecode($_GET['s']);
        filters_input ('get');
        
        $find_words = $DB->addslashes(htmlspecialchars(strip_data($_GET['s'])));
        if ($find_words) $dopnav['s'] = urlencode($find_words);
    }
    
    if (isset($_GET['utility']) AND intval($_GET['utility']) > 0)
        $link_nav = topic_link_utility($id, $topic['forum_id'], 0, true);
    else
        $link_nav = navigation_topic_link($id, $topic['forum_id'], $dopnav);
    
    if ($nav_all > $cache_config['topic_post_page']['conf_value'])
    {
        include LB_CLASS.'/navigation_board.php';
        $navigation = new navigation;
        $navigation->create($page, $nav_all, $cache_config['topic_post_page']['conf_value'], $link_nav, "5");
        $navigation->template();
        unset($navigation);
    }
    else
        $tpl->result['navigation'] = "";
        
    $number_pages = ceil( $nav_all / $cache_config['topic_post_page']['conf_value'] );
    
    if (intval($_GET['page']) > $number_pages) $_GET['go'] = "last";
        
    if (isset($_GET['go']) AND $_GET['go'] == "last")
    {
        header( "Location: ".topic_link_last($topic['id'], $topic['forum_id'], $number_pages, $topic['last_post_id']) );
        exit();
    }
    
    $_SESSION['Get_Next_Post_Buttom'] = 0;
        
    if ($topic['post_fixed'])
    {
        $DB->prefix = array ( 1=> DLE_USER_PREFIX );
        $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num", "LEFT", "posts p||users u||members_online mo", "p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", "topic_id = '{$id}' AND fixed = '1' {$where}", "ORDER by fixed DESC, post_date ASC" );
        $j = 0;
        $LB_posts->Data_out("board/topic_posts.tpl", "posts_fixed", $topic, true, false, true);
    }
    
    $DB->prefix = array ( 1=> DLE_USER_PREFIX );
    $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num", "LEFT", "posts p||users u||members_online mo", "p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", "topic_id = '{$id}' AND fixed = '0' {$where}", "ORDER by fixed DESC, post_date ASC LIMIT ".$page.", ".$cache_config['topic_post_page']['conf_value'] );
    $LB_posts->Data_out("board/topic_posts.tpl", "posts", $topic);
    
    unset($LB_posts);
    
    // Определяем у пользователя темы, на которые он полписан
    
    $subscribe_add = true;
    if ($member_id['lb_subscribe'] != "")
    {
        $subscribe = explode (",", $member_id['lb_subscribe']);
        if (in_array($topic['id'], $subscribe))
        {
            $subscribe_add = false;
        }
    }
    
    // Определяем у пользователя темы, которые у него в избранном
    
    $favorite_add = true;
    if ($member_id['lb_favorite'] != "")
    {
        $favorites = explode (",", $member_id['lb_favorite']);
        if (in_array($topic['id'], $favorites))
        {
            $favorite_add = false;
        }
    }
    
    if ($nav_all OR $j > 0)   // Если не было найдено ниодного сообщения - то вывести ошибку
    {   
        if ($topic['status'] == "closed" AND !forum_options_topics("0", "reply_close") AND !$cache_group[$member_id['user_group']]['g_reply_close'])
        {
       	    $tpl->load_template( 'message.tpl' );
            $tpl->tags( '{title}', $lang_m_b_topic_posts['topic_title_close'] );
            $tpl->tags( '{message}', $lang_m_b_topic_posts['topic_info_close'] );
            $tpl->compile( 'form' );
            $tpl->clear();
        }
        elseif(!forum_permission($topic['forum_id'], "answer_theme"))
        {
            $tpl->load_template( 'message.tpl' );
            $tpl->tags( '{title}', $lang_message['access_denied'] );        
            $tpl->tags( '{message}', $lang_m_b_topic_posts['topic_info_answer'] );
            $tpl->compile( 'form' );
            $tpl->clear(); 
        }
        elseif (!member_publ_access(1))
        {
       	    $tpl->load_template( 'message.tpl' );
            $tpl->tags( '{title}', $lang_message['access_denied'] );                
            $tpl->tags( '{message}', str_replace("{info}", member_publ_info(), $lang_m_b_topic_posts['topic_info_publ']) );
            $tpl->compile( 'form' );
            $tpl->clear();
        }
        elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic['forum_id'])
        {
            $tpl->load_template( 'message.tpl' );
            $tpl->tags( '{title}', $lang_message['access_denied'] );        
            $tpl->tags( '{message}', $lang_m_b_topic_posts['topic_info_basket'] );
            $tpl->compile( 'form' );
            $tpl->clear();
        }
        elseif (!$cache_group[$member_id['user_group']]['g_reply_topic'] AND $topic['member_id_open'] != $member_id['user_id'] AND $logged)
        {
       	    $tpl->load_template( 'message.tpl' );
            $tpl->tags( '{title}', $lang_message['access_denied'] );
            $tpl->tags( '{message}', str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_b_topic_posts['topic_info_answer_other']) );
            $tpl->compile( 'form' );
            $tpl->clear();
        }
        else
        {
            if ($topic['status'] == "closed" AND (forum_options_topics("0", "reply_close") OR $cache_group[$member_id['user_group']]['g_reply_close']))
            {
       	        $tpl->load_template( 'message.tpl' );
                $tpl->tags( '{title}', $lang_message['warning'] );
                $tpl->tags( '{message}', $lang_m_b_topic_posts['topic_info_close_answer'] );
                $tpl->compile( 'form' );
                $tpl->clear();
            }
            
            $tpl->load_template ( 'board/reply_fast.tpl' );
            
            if ($cache_forums[$topic['forum_id']]['allow_bbcode'])
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
                
            if ($subscribe_add AND $logged)
                $tpl->tags_blocks("subscribe_in_reply");
            else
                $tpl->tags_blocks("subscribe_in_reply", false);
                
            $tpl->tags('{reply_link}', reply_link($topic['id'], 0, $topic['forum_id']));
            $tpl->tags('{tid}', $topic['id']);
            $tpl->compile('form');
            $tpl->clear();
        }        
            
        if($cache_forums[$topic['forum_id']]['allow_poll'] AND $topic['poll_id'])
        {
            $poll_check = false;
            if ($cache_config['topic_polllog']['conf_value'])
            {
                if ($logged)
                    $poll_log_where = "(ip = '{$_IP}' OR member_id = '{$member_id['user_id']}')";
                else
                    $poll_log_where = "ip = '{$_IP}'";
                    
                $poll_log = $DB->one_select( "*", "topics_poll_logs", "poll_id = '{$topic['poll_id']}' AND {$poll_log_where}" );
                
                if ($poll_log['id'])
                    $poll_check = true;
                    
                $DB->free($poll_log);
            }
            
            $tpl->load_template ( 'board/topic_poll.tpl' );
            $tpl->tags('{title}', $topic['p_title']); 
            $tpl->tags('{question}', $topic['question']); 
            $tpl->tags('{tid}', $topic['id']);
            
            if ($poll_check OR ($cache_forums[$topic['forum_id']]['allow_poll'] == 1 AND !$logged))
            {
                $tpl->tags('{variants}', topic_poll_logs($topic['variants'], $topic['answers'], $topic['vote_num']));  
                $tpl->tags_blocks("result", false);
                $tpl->tags_blocks("vote", false);
            }
            elseif (isset($_GET['poll']) AND $_GET['poll'] == "yes")
            {
                $tpl->tags('{variants}', topic_poll_logs($topic['variants'], $topic['answers'], $topic['vote_num']));
                $tpl->tags_blocks("result");
                $tpl->tags_blocks("vote", false);
                $tpl->tags('{vote_link}', $link_nav);
                $tpl->tags('{poll_link}', $link_nav);
            }
            else
            {
                $tpl->tags_blocks("vote");
                $tpl->tags_blocks("result", false);
                $tpl->tags('{poll_link}', $link_nav."&poll=yes");
                $tpl->tags('{variants}', topic_poll_variants($topic['variants'], $topic['multiple']));
                $tpl->tags('{poll_link}', $_SERVER['REQUEST_URI']."&poll=yes");
            }
            $tpl->compile('poll');  
            
            $tpl->result['poll'] = "<div id=\"topic_vote_jq\">".$tpl->result['poll']."</div>"; 
        }
        else
            $tpl->result['poll'] = "";
        
        $tpl->load_template ( 'board/topic_posts_global.tpl' );    
        $tpl->tags('{topic_title}', $topic['title']);
        
        if (isset($tpl->result['posts_fixed']))
            $tpl->tags_templ('{posts_fixed}', $tpl->result['posts_fixed']);
        else
            $tpl->tags_templ('{posts_fixed}', "");
        
        $tpl->tags_templ('{posts}', $tpl->result['posts']);
        $tpl->tags_templ('{poll}', $tpl->result['poll']);
        $tpl->tags('{fast_forum}', ForumsList($topic['forum_id'], 0, "", "", true));
        
        $tpl->tags_blocks("moder", forum_options_topics_mas($topic['forum_id'], $topic['id'], "check"));
        if(forum_options_topics_mas($topic['forum_id'], $topic['id'], "check"))
        {
            $tpl->tags('{moder_comm}', forum_options_topics_mas($topic['forum_id'], $topic['id'], "posts"));
            $tpl->tags('{moder_topic}', forum_options_topics_mas($topic['forum_id'], $topic['id'], "topic"));
            $tpl->block( "'\\[author_topic\\](.*?)\\[/author_topic\\]'si", "" );
        }
        else
        { 
            if($logged AND $topic['member_id_open'] == $member_id['user_id'] AND forum_options_topics_author("check"))
            {
                $tpl->tags_blocks("author_topic");
                $tpl->tags('{moder_topic}', forum_options_topics_author());
            }
            else
                $tpl->tags_blocks("author_topic", false);
        }
         
        $tpl->tags('{id}', $topic['id']);
        $tpl->tags_blocks("posts_out");
        
        $tpl->tags_templ('{form}', $tpl->result['form']);
        $tpl->tags_templ('{pages}', $tpl->result['navigation']);
        
        $tpl->tags('[link_favorite]', "<a href=\"".topic_favorite($topic['id'])."\" onclick=\"AddDelFavorite('".$topic['id']."');return false;\">" );
        $tpl->tags('[/link_favorite]', "</a>" );
        
        if ($favorite_add)
            $tpl->tags('{favorite_title}', $lang_m_b_topic_posts['do_fav_add']);
        else
            $tpl->tags('{favorite_title}', $lang_m_b_topic_posts['do_fav_del']);
        
        $tpl->tags('[link_subscribe]', "<a href=\"".topic_subscribe($topic['id'])."\" onclick=\"AddDelSubscribe('".$topic['id']."');return false;\">" );
        $tpl->tags('[/link_subscribe]', "</a>" );
        
        if ($subscribe_add)
            $tpl->tags('{subscribe_title}', $lang_m_b_topic_posts['do_subs_add']);
        else
            $tpl->tags('{subscribe_title}', $lang_m_b_topic_posts['do_subs_del']);
            
        if ($cache_config['topic_sharelink']['conf_value'] AND count($cache_sharelink) >= 1)
        {
            $tpl->tags_blocks("share_links");
            $tpl->tags('{share_links}', share_links($topic['id'], $topic['title'], $topic['forum_id']));
        }
        else
            $tpl->tags_blocks("share_links", false);
            
        if (isset($_GET['utility']) AND intval($_GET['utility']) > 0)
            $tpl->tags('{link_utility}', "<a href=\"".topic_link($topic['id'], $topic['forum_id'])."\">".$lang_m_b_topic_posts['link_utility_0']."</a>");
        else
            $tpl->tags('{link_utility}', "<a href=\"".topic_link_utility($topic['id'], $topic['forum_id'])."\">".$lang_m_b_topic_posts['link_utility_1']."</a>");
            
        $tpl->compile('content');
        $tpl->clear();
    }
    else
    {
        message ($lang_m_b_topic_posts['no_posts_title'], str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_topic_posts['no_posts_descr']));
    }
    
    $script_update = "";
    $script_new_posts = "";
    
    $time_out_new_post = intval($cache_config['posts_check_new_auto']['conf_value']) * 1000;
    $number_pages = ceil( $nav_all / $cache_config['topic_post_page']['conf_value'] );
    $script_new_posts_active = false;
    
    if ($number_pages == intval($_GET['page']))
        $script_new_posts_active = true;
    
    if (intval($cache_config['posts_get_next_page']['conf_value']) AND (!$logged OR ($logged AND !$member_options['posts_ajax'])))
    {
        $script_update = '
    <span id="script_update">
    <script type="text/javascript">
        $(document).ready(function(){
                    
            if ($("span#newpost-out").length == "0")
            {
                show_message("3", LB_lang[\'error\'], LB_lang[\'no_object\'] + "span#newpost-out");
            }
            
            var top_s = 0;
            var loaded_content = false;
            var loaded_content_hieght = $(window).scrollTop() + $(window).height();
            var loaded_content_page = "'.intval($_GET['page']).'";
            var loaded_content_post = "'.$i.'";
            var loaded_content_end = "0";
            var go_show = "'.$go_show.'";
                    
            function check(){
                
                if ($("span#newpost-out").length == "0")
                {
                    show_message("3", LB_lang[\'error\'], LB_lang[\'no_object\'] + "span#newpost-out");
                    return false;
                }
                
                top_s = $("#newpost-out").offset().top;
                loaded_content_hieght = $(window).scrollTop() + $(window).height() + 120;
                
                if ( !loaded_content && loaded_content_hieght > top_s ) {
                    show_loading_message();
                    
                    $.get(LB_root + "components/scripts/ajax/get_next_page_posts.php", {tid:"'.$topic['id'].'", page:loaded_content_page, last_post_i:loaded_content_post, go:go_show, content_end:loaded_content_end}, function(data){
                        $("span#newpost-out").before(data);
                        remove_loading_message();
                    });
                    
                    Error_AJAX_jQ ("span#newpost-out");
                    loaded_content = true;
                }
            }
            $(window).scroll(check);
            check();  
        });    
    </script>
    </span>';
    } 
    
    if (intval($cache_config['posts_check_new_auto']['conf_value']) AND $topic['status'] != "closed" AND $script_new_posts_active AND (!intval($cache_config['posts_get_next_page']['conf_value']) OR ($logged AND $member_options['posts_ajax'])))
    {
        $script_new_posts = '
            <script type="text/javascript">
            var date_now = "'.$time.'";

            function check_new_posts()
            {
                show_loading_message(LB_lang[\'search_new_answers\']);
                        
                $.get(LB_root + "components/scripts/ajax/check_new_posts.php", {tid:"'.$topic['id'].'", date_now:date_now}, function(data){
                    $("span#newpost-out").before(data);
                    remove_loading_message();
                });
                   
                Error_AJAX_jQ ("span#newpost-out");     
            }
                
            setInterval(check_new_posts, '.$time_out_new_post.');
            
            </script>';
    }
    
    $script_word = "
    <script type=\"text/javascript\">
    $(window).load(function(){
        $(\"[id^=post-id-]\").highlight('".stripslashes($find_words)."');
    });
    </script>";
    
    if ($find_words) $tpl->result['content'] .= $script_word.$script_update.$script_new_posts;
    else $tpl->result['content'] .= $script_img_resize.$script_update.$script_new_posts;
    
    if ($_SESSION['LB_read_topic'] != $topic['id'])
    {            
        $_SESSION['LB_read_topic'] = $topic['id'];      
        $DB->update("views = views + 1", "topics", "id='{$topic['id']}'");
    }
}
else
    message ($lang_m_b_topic_posts['not_found_title'], $lang_m_b_topic_posts['not_found_info'], 1);

?>