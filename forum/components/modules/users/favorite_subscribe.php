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

$lang_m_u_favorite_subscribe = language_forum ("board/modules/users/favorite_subscribe");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$errors = array();
$where = array();

$link_speddbar = speedbar_forum (0, true);

if ($op == "favorite")
{
    $info = "lb_favorite";
    
    $lang_location = str_replace("{link}", profile_link($member_id['name'], $member_id['user_id']), $lang_m_u_favorite_subscribe['fav_location']);
    $lang_location = str_replace("{name}", $member_id['name'], $lang_location);
    $link_speddbar .= "|".$lang_location;
    
    $lang_location = str_replace("{link}", profile_link($member_id['name'], $member_id['user_id']), $lang_m_u_favorite_subscribe['fav_location_online']);
    $lang_location = str_replace("{name}", $member_id['name'], $lang_location);
    $onl_location = $lang_location;
    
    $meta_info_other = str_replace("{name}", $member_id['name'], $lang_m_u_favorite_subscribe['fav_meta_info']);
    
    $link_nav = navigation_link_favsubs("favorite");
    $title = $lang_m_u_favorite_subscribe['fav_title'];
    

    $favorite_member = explode (",", $member_id['lb_favorite']);
    $favorite_member_new = array();
    $update_info = false;

    foreach($favorite_member as $tsm)
    {
        if (!intval($tsm))
            $update_info = true;
        else
            $favorite_member_new[] = intval($tsm);
    }
            
    if ($update_info)
    {
        $member_id['lb_favorite'] = implode (",", $favorite_member_new);
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("lb_favorite = '{$member_id['lb_favorite']}'", "users", "user_id = '{$member_id['user_id']}'");
    }
}
else
{
    $info = "lb_subscribe";
    
    $lang_location = str_replace("{link}", profile_link($member_id['name'], $member_id['user_id']), $lang_m_u_favorite_subscribe['subs_location']);
    $lang_location = str_replace("{name}", $member_id['name'], $lang_location);
    $link_speddbar .= "|".$lang_location;
    
    $lang_location = str_replace("{link}", profile_link($member_id['name'], $member_id['user_id']), $lang_m_u_favorite_subscribe['subs_location_online']);
    $lang_location = str_replace("{name}", $member_id['name'], $lang_location);
    $onl_location = $lang_location;
    
    $meta_info_other = str_replace("{name}", $member_id['name'], $lang_m_u_favorite_subscribe['subs_meta_info']);
        
    $link_nav = navigation_link_favsubs("subscribe");
    $title = $lang_m_u_favorite_subscribe['subs_title'];
    
    $topic_subs_db = $DB->select( "*", "topics_subscribe", "subs_member = '{$member_id['user_id']}'" );
    $topic_subs_mas = array();
    while ( $row = $DB->get_row($topic_subs_db) )
    {
        $topic_subs_mas[] = intval($row['topic']);    
    }    
    $DB->free($topic_subs_db);
    
    if (count($topic_subs_mas) AND $member_id['lb_subscribe'] != "")
    {
        $subscribe_member = explode (",", $member_id['lb_subscribe']);
        $update_info = false;
        foreach($topic_subs_mas as $tsm)
        {
            if (!in_array($tsm, $subscribe_member))
            {
                $update_info = true;
                break;
            }
        }
        
        if (!$update_info)
        {
            foreach($subscribe_member as $tsm)
            {
                if (!intval($tsm))
                {
                    $update_info = true;
                    break;
                }
            }
        }
        
        if ($update_info)
        {
            $member_id['lb_subscribe'] = implode (",", $topic_subs_mas);
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("lb_subscribe = '{$member_id['lb_subscribe']}'", "users", "user_id = '{$member_id['user_id']}'");
        }
        
    }
    elseif (!count($topic_subs_mas) AND $member_id['lb_subscribe'] != "")
    {
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("lb_subscribe = ''", "users", "user_id = '{$member_id['user_id']}'");
        $member_id['lb_subscribe'] = "";
    }
    elseif (count($topic_subs_mas) AND $member_id['lb_subscribe'] == "")
    {
        $member_id['lb_subscribe'] = implode (",", $topic_subs_mas);
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("lb_subscribe = '{$member_id['lb_subscribe']}'", "users", "user_id = '{$member_id['user_id']}'");
    }
}

if ($op == "favorite" AND $member_id['lb_favorite'] == "")
     message ($lang_message['error'], $lang_m_u_favorite_subscribe['fav_empty']);
elseif ($op == "subscribe" AND $member_id['lb_subscribe'] == "")
     message ($lang_message['error'], $lang_m_u_favorite_subscribe['subs_empty']);
else
{
    $topic_mass = array();
    if (isset($_POST['topics']))
    {
        $selected = $_POST['topics'];
        foreach	($selected as $id)
        {
            $topic_mass[] = intval( $id );
        }
    }
    
    if ((count($topic_mass) OR $_POST['act'] == 2) AND (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key))
    {
        message ($lang_message['access_denied'],$lang_message['secret_key'], 1);
    }
    elseif ($_POST['act'] == 2 AND $_POST['secret_key'] == $secret_key)
    {
        if ($op == "favorite")
        {
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("lb_favorite = ''", "users", "user_id = '{$member_id['user_id']}'");
            header( "Location: ".member_favorite() );
        }
        else
        {
            $DB->delete("subs_member = '{$member_id['user_id']}'", "topics_subscribe");
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("lb_subscribe = ''", "users", "user_id = '{$member_id['user_id']}'");
            header( "Location: ".member_subscribe() );
        }
        exit();
    } 
    elseif (count($topic_mass) AND $_POST['secret_key'] == $secret_key)
    {
        $favorites = explode (",", $member_id[$info]);
        foreach ($topic_mass as $tid)
        {
            if (in_array($tid, $favorites))
            {
                $key_fav = array_search($tid, $favorites);
                unset($favorites[$key_fav]);
            }
            $DB->delete("topic = '{$tid}' AND subs_member = '{$member_id['user_id']}'", "topics_subscribe");
        }
        $fav = implode (",", $favorites);
        
        if ($op == "favorite")
        {
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("lb_favorite = '{$fav}'", "users", "user_id = '{$member_id['user_id']}'");
            header( "Location: ".member_favorite() );
        }
        else
        {
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("lb_subscribe = '{$fav}'", "users", "user_id = '{$member_id['user_id']}'");
            header( "Location: ".member_subscribe() );
        }
        exit();
    } 
    
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
    
    $where = array();
    
    if (!forum_options_topics(0, "allpermission"))
        $where[] = "hiden = '0'";
        
    $access_forums = implode (",", $access_forums);
    
    if ($access_forums)
    {
        $where[] = "forum_id NOT IN (".$access_forums.")";
    }
    
    $where[] = "id IN (".$member_id[$info].")";

    $where_db = implode(" AND ", $where);

    $j = 0;

    include LB_CLASS.'/topics_out.php';
    $LB_topics = new LB_topics;
            
    $LB_topics->query = $DB->select( "*", "topics", $where_db, "ORDER by date_last DESC LIMIT ".$page.", ".$cache_config['topic_page']['conf_value'] );
    $LB_topics->Data_out("board/topic_all.tpl", "topics", true);
    
    unset($LB_topics);
    
    $nav = $DB->one_select( "COUNT(*) as count", "topics", $where_db);
    $nav_all = $nav['count'];
    $DB->free($nav);
    
    if ($nav_all > $cache_config['topic_page']['conf_value'])
    {
        include_once LB_CLASS.'/navigation_board.php';
        $navigation = new navigation;
        $navigation->create($page, $nav_all, $cache_config['topic_page']['conf_value'], $link_nav, "5");
        $navigation->template();
        unset($navigation);
    }
    else
        $tpl->result['navigation'] = "";
    
    if (!$nav_all)
        message ($lang_message['information'], $lang_m_u_favorite_subscribe['not_found']);
    else
    {    
        $tpl->load_template ( 'board/topic_favorite_global.tpl' );   
  
        $tpl->tags('{topics}', $tpl->result['topics']);
        $tpl->tags('{title}', $title);
        $tpl->tags_templ('{pages}', $tpl->result['navigation']);

        $tpl->compile('content');
        $tpl->clear();
    }
}
?>