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

$lang_m_b_posts_out = language_forum ("board/modules/board/posts_out");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$errors = array();
$where = array();

$link_speddbar = speedbar_forum (0, true);

if ($member_name)
{
    $onl_location = str_replace("{user}", $member_name, $lang_m_b_posts_out['location_online']);
    
    $DB->prefix = DLE_USER_PREFIX;
    $user_out = $DB->one_select( "user_id, name", "users", "name='{$member_name}'" );
    
    if (!$user_out['user_id'])
    {
        $errors[] = $lang_m_b_posts_out['not_found_user'];
        $link_speddbar = "";
    }
    else
    {
        $where[] = "p.post_member_id = '{$user_out['user_id']}'";
        
        $location_profile = str_replace("{link}", profile_link($user_out['name'], $user_out['user_id']), $lang_m_b_posts_out['location_profile']);
        $location_profile = str_replace("{user}", $user_out['name'], $location_profile);
        $link_speddbar .= "|".$location_profile."|".$lang_m_b_posts_out['location1'];
    }    
    $title_module = str_replace("{user}", $member_name, $lang_m_b_posts_out['title_module1']);
    $link_nav = navigation_post_out_link("posts", urlencode($user_out['name']));
    $link_on_post = $link_nav;
    $meta_info_other = str_replace("{user}", $member_name, $lang_m_b_posts_out['meta_info1']);
}
elseif ($do == "board" AND $op == "last_posts")
{
    $onl_location = $lang_m_b_posts_out['location_online'];
    $link_speddbar .= "|".$lang_m_b_posts_out['location2'];
  
    $title_module = $lang_m_b_posts_out['title_module2'];
    $link_nav = navigation_post_out_link("last_posts");
    $link_on_post = $link_nav;
    $meta_info_other = $lang_m_b_posts_out['meta_info2'];
}
else
    $errors[] = $lang_message['no_act'];

if( ! $errors[0] )
{
    $_SESSION['Get_Next_Post_Buttom'] = 0;
    
    if (isset ( $_REQUEST['page'] ))
        $page = intval ( $_GET['page'] );
    else
        $page = 0;

    $link_on_post = str_replace("{i_page}", $page, $link_on_post);

    if ($page < 0)
        $page = 0;

    if ($page)
    {
        $page = $page - 1;
        $page = $page * $cache_config['topic_post_page']['conf_value'];
    }

    $i = $page;
    
    $access_forums = array();
    foreach ($cache_forums as $cf)
    {
        if(forum_permission($cf['id'], "read_forum"))
        {
            if (forum_all_password($cf['id']))
                $access_forums[] = $cf['id'];
            elseif (!forum_permission($cf['id'], "read_theme"))
                $access_forums[] = $cf['id'];
        }
        else
            $access_forums[] = $cf['id'];
    }    
    
    if (!forum_options_topics(0, "allpermission"))
        $where[] = "t.hiden = '0'";
    
    $access_forums = implode (",", $access_forums);
    
    if ($access_forums)
    {
        $where[] = "t.forum_id NOT IN (".$access_forums.")";
    }
            
    $where[] = "t.basket = '0'";

    if ($where[0] != "")
        $where_db = implode(" AND ", $where);
    else
        $where_db = "";
        
    $nav = $DB->one_join_select( "COUNT(*) as count", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db);
    $nav_all = $nav['count'];
    $DB->free($nav);
    
    $number_pages = ceil( $nav_all / $cache_config['topic_post_page']['conf_value'] );
                
    include LB_CLASS.'/posts_out.php';
    $LB_posts = new LB_posts;
    
    $DB->prefix = array ( 2 => DLE_USER_PREFIX );
    $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num, t.forum_id, t.hiden, t.title, t.basket, t.status, t.member_id_open", "LEFT", "posts p||topics t||users u||members_online mo", "p.topic_id=t.id||p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", $where_db, "ORDER by post_date DESC LIMIT ".$page.", ".$cache_config['topic_post_page']['conf_value'] );
    $LB_posts->Data_out("board/topic_posts.tpl", "posts", "", true, true, false, true);
    
    unset($LB_posts);
  
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
    
    if (!$nav_all OR intval($_GET['page']) > $number_pages)
        message ($lang_message['information'], $lang_m_b_posts_out['not_found_posts']);
    else
    {          
        $tpl->load_template ( 'board/topic_posts_global.tpl' );   
        
        require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
         
        $tpl->tags('{topic_title}', $title_module);
        $tpl->tags_templ('{posts}', $bbcode_script.$tpl->result['posts']);
        $tpl->tags('{posts_fixed}', "");
        $tpl->tags('{fast_forum}', "");
        $tpl->tags('{poll}', "");
    
        if(forum_options_topics_mas(0, 0, "check"))
        {
            $tpl->tags_blocks("moder");
            $tpl->tags('{moder_comm}', forum_options_topics_mas(0, 0, "posts"));
            $tpl->tags('{moder_topic}', "");
            $tpl->tags_blocks("author_topic", false);
        }
        else
        {
            $tpl->tags_blocks("moder", false);
            $tpl->tags_blocks("author_topic", false);
        }
    
        $tpl->tags_blocks("posts_out", false);
        $tpl->tags_blocks("share_links", false);
    
        $tpl->tags('{form}', "");
        $tpl->tags_templ('{pages}', $tpl->result['navigation']);
        $tpl->compile('content');
        $tpl->clear();
                
        $script_update = "";
        
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
                
                var top_s = $("span#newpost-out").offset().top;
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
                    
                    top_s = $("span#newpost-out").offset().top;
                    loaded_content_hieght = $(window).scrollTop() + $(window).height() + 120;
                    
                    if ( !loaded_content && loaded_content_hieght > top_s )
                    {
                        show_loading_message();
                        $.get(LB_root + "components/scripts/ajax/get_next_page_posts_all.php", {member_name:"'.$member_name.'", page:loaded_content_page, last_post_i:loaded_content_post, go:go_show, content_end:loaded_content_end}, function(data){
                            $("span#newpost-out:last").before(data);
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
    
        $tpl->result['content'] .= $script_update;
    }
}
else
    message ($lang_message['error'], $errors, 1);
?>