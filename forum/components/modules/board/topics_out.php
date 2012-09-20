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

$lang_m_b_topics_out = language_forum ("board/modules/board/topics_out");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$errors = array();
$where = array();

$link_speddbar = speedbar_forum (0, true);

$dopnav = "";

if (!forum_options_topics(0, "allpermission"))
    $where[] = "hiden = '0'";
else
{
    if (isset($_GET['hide']) AND $_GET['hide'] == "topics")
    {
        $where[] = "hiden = '1'";
        $dopnav = "topics";
    }
    elseif (isset($_GET['hide']) AND $_GET['hide'] == "posts")
    {
        $where[] = "post_hiden <> '0'";
        $dopnav = "posts";
    }            
}

if ($member_name)
{
    $DB->prefix = DLE_USER_PREFIX;
    $user_out = $DB->one_select( "user_id, name", "users", "name='{$member_name}'" );
    
    if (!$user_out['user_id'])
    {
        $errors[] = $lang_m_b_topics_out['member_not_found'];
        $link_speddbar = "";
        $onl_location = $lang_m_b_topics_out['member_location_online_no'];
    }
    else
    {
        $where[] = "member_id_open = '{$user_out['user_id']}'";
        
        $lang_location = str_replace ("{link}", profile_link($user_out['name'], $user_out['user_id']), $lang_m_b_topics_out['member_location']);
        $lang_location = str_replace ("{name}", $user_out['name'], $lang_location);
        $link_speddbar .= "|".$lang_location;
        
        $lang_location = str_replace ("{link}", profile_link($user_out['name'], $user_out['user_id']), $lang_m_b_topics_out['member_location_online']);
        $lang_location = str_replace ("{name}", $user_out['name'], $lang_location);
        $onl_location = $lang_location;
        
        $title_module = str_replace ("{name}", $user_out['name'], $lang_m_b_topics_out['member_title_module']);
        $meta_info_other = str_replace ("{name}", $user_out['name'], $lang_m_b_topics_out['member_meta_info']);
    }    
        
    $link_nav = navigation_topic_out_link("topics", urlencode($user_out['name']), $dopnav);
    $link_on_module = array();
    $link_on_module['module'] = "user_topics";
    $link_on_module['dop'] = urlencode($user_out['name']);
}
elseif ($do == "board" AND $op == "last_topics")
{
    $onl_location = $lang_m_b_topics_out['last_location_online'];
    $link_speddbar .= "|".$lang_m_b_topics_out['last_location'];
  
    $title_module = $lang_m_b_topics_out['last_title_module'];
    $meta_info_other = $lang_m_b_topics_out['last_meta_info'];
    
    $link_nav = navigation_topic_out_link("last_topics", "", $dopnav);
    $link_on_module = "last_topics";
}
elseif ($do == "board" AND $op == "topic_active")
{
    $onl_location = $lang_m_b_topics_out['active_location_online'];
    $link_speddbar .= "|".$lang_m_b_topics_out['active_location'];
  
    $title_module = $lang_m_b_topics_out['active_title_module'];
    $meta_info_other = $lang_m_b_topics_out['active_meta_info'];
    
    $link_nav = navigation_topic_out_link("topic_active", "", $dopnav);
    $time_day = $time - 86400;
    $where[0] = "date_last >= '".$time_day."'";
    $link_on_module = "topic_active";
}
else
    $errors[] = $lang_message['no_act'];

if( ! $errors[0] )
{
    if (isset ( $_REQUEST['page'] ))
        $page = intval ( $_GET['page'] );
    else
        $page = 0;

    if ($page < 0)
        $page = 0;

    if ($page)
    {
        $page = $page - 1;
        $page = $page * $cache_config['topic_page']['conf_value'];
    }

    $i = $page;
        
    $access_forums = array();
    foreach ($cache_forums as $cf)
    {
        if(forum_permission($cf['id'], "read_forum"))
        {
            if (forum_all_password($cf['id']))
                $access_forums[] = $cf['id'];
        }
        else
            $access_forums[] = $cf['id'];
    } 
    
    $access_forums = implode (",", $access_forums);
    
    if ($access_forums)
    {
        $where[] = "forum_id NOT IN (".$access_forums.")";
    }

    $where[] = "basket = '0'";
        
    $where_db = implode(" AND ", $where);
    
    $nav = $DB->one_select( "COUNT(*) as count", "topics", $where_db);
    $nav_all = $nav['count'];
    $DB->free($nav);
    
    $number_pages = ceil( $nav_all / $cache_config['topic_page']['conf_value'] );
    
    include LB_CLASS.'/topics_out.php';
    $LB_topics = new LB_topics;
            
    $LB_topics->query = $DB->select( "*", "topics", $where_db, "ORDER by date_last DESC LIMIT ".$page.", ".$cache_config['topic_page']['conf_value'] );
    $LB_topics->Data_out("board/topic_all.tpl", "topics", true);
    
    unset($LB_topics);

    if ($nav_all > $cache_config['topic_page']['conf_value'])
    {
        include_once LB_CLASS.'/navigation_board.php';
        $navigation = new navigation;
        $navigation->create($page, $nav_all, $cache_config['topic_page']['conf_value'], $link_nav, "7");
        $navigation->template();
        unset($navigation);
    }
    else
        $tpl->result['navigation'] = ""; 
     
    if (!$nav_all OR intval($_GET['page']) > $number_pages)
        message ($lang_message['information'], $lang_m_b_topics_out['topics_not_found']);
    else
    {    
        $tpl->load_template ( 'board/topic_global.tpl' );

        $tpl->tags('{category_name}', $title_module);

        if (forum_options_topics(0))
        {
            $tpl->tags('{forum_options}', forum_options(0, $link_on_module));
            $tpl->tags('{forum_options_topics}', forum_options_topics(0));
            $tpl->tags_blocks("moder_line");
        }
        else
            $tpl->tags_blocks("moder_line", false);
        
        $tpl->tags_blocks("topics_out", false);
  
        $tpl->tags_templ('{topics}', $tpl->result['topics']);
        $tpl->tags_templ('{pages}', $tpl->result['navigation']);

        $tpl->compile('content');
        $tpl->clear();
    }
}
else
    message ($lang_message['error'], $errors, 1);
?>