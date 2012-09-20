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
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$lang_m_board = language_forum ("board/modules/board");

switch ($op)
{
	case "forum":
        
        if (isset($_GET['name']) AND $_GET['name'] != "")
        {
            filters_input ('get');            
            $id = forum_find_alt_name($_GET['name']);
        }
        
        $mo_loc_fid = $id;
        
        if($cache_forums[$id]['id'] AND !forum_permission($id, "read_forum"))
        {
            $link_speddbar = speedbar_forum ($id).$lang_message['access_denied_speedbar'];
            $onl_location = str_replace("{forum}", $cache_forums[$id]['title'], $lang_m_board['location_forum']).$lang_message['access_denied_speedbar'];
            message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_board['access_denied_group_forumcat']), 1);
        }
        elseif(forum_all_password($id))
        {
            if(isset($_POST['check_pass']))
            {
                $check_f_pass_id = intval($_POST['f_id']);
                $check_f_pass = $_POST['f_pass'];
                if ($LB_flood->isBlock())
                    message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
                elseif ($check_f_pass == $cache_forums[$id]['password'] AND $cache_forums[$id]['password'] != "")
                {
                    if($member_id['user_group'] != 5)
                        $who = $member_id['name'];
                    else
                        $who = $_IP;
            
                    $check_f_pass = md5($who.$cache_forums[$id]['password']);
                    update_cookie( "LB_password_forum_".$id, $check_f_pass, 365 );
                    header( "Location: {$_SERVER['REQUEST_URI']}" );
                    exit();
                }
                elseif ($check_f_pass != $cache_forums[$id]['password'] AND $cache_forums[$id]['password'] != "")
                    message ($lang_message['access_denied'], $lang_m_board['wrong_password_forum']);
            }

            $link_speddbar = speedbar_forum ($id).$lang_message['access_denied_speedbar'];
            $onl_location = str_replace("{forum}", $cache_forums[$id]['title'], $lang_m_board['location_forum']).$lang_message['access_denied_speedbar'];
            message ($lang_message['access_denied'], $lang_m_board['write_password_forum']);
            
            $tpl->load_template ( 'board/forum_password.tpl' );
            $tpl->tags('{forum_title}', $cache_forums[forum_all_password($id)]['title']);
            $tpl->tags('{forum_id}', forum_all_password($id));
            $tpl->compile('content');
            $tpl->clear();
                        
        }
        elseif ($id AND $cache_forums[$id]['id'] == $id)
        {
            if ($cache_forums[$id]['flink'])
            {
                // подсчёт переходов
                $cache_forums[$id]['posts'] += 1;
                $cache->update("forums", $cache_forums);
                
                $DB->update("posts = posts+1", "forums", "id = '{$id}'");
                
                header("Location: ".$cache_forums[$id]['flink']);
                exit ("Redirect.");
            }
            else
                include_once LB_MODULES . '/board/forum.php';
        }
        else
            message ($lang_message['error'], $lang_m_board['no_forum_id'], 1);
	break;

	case "newtopic":
    
        if (isset($_GET['name']) AND $_GET['name'] != "")
        {
            filters_input ('get');            
            $id = forum_find_alt_name($_GET['name']);
        }
        
        $mo_loc_fid = $id;
    
        if(!$cache_group[$member_id['user_group']]['g_new_topic'])
        {
            $link_speddbar = speedbar_forum ($id).$lang_message['access_denied_speedbar'];
            $onl_location = $lang_m_board['location_newtopic']." <a href=\"".forum_link($id)."\">".$cache_forums[$id]['title']."</a>".$lang_message['access_denied_speedbar'];
            message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_board['access_denied_group_newtopic']), 1);
        }
        elseif($cache_forums[$id]['id'] AND !forum_permission($id, "read_forum"))
        {
            $link_speddbar = speedbar_forum (0)."|".$lang_message['access_denied_speedbar2'];
            $onl_location = $lang_m_board['location_newtopic']." ".$lang_message['access_denied_speedbar2'];
            message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_board['access_denied_group_forumcat']), 1);
        }
        elseif($cache_forums[$id]['id'] AND !forum_permission($id, "creat_theme"))
        {
            $link_speddbar = speedbar_forum ($id).$lang_message['access_denied_speedbar'];
            $onl_location = $lang_m_board['location_newtopic']." <a href=\"".forum_link($id)."\">".$cache_forums[$id]['title']."</a>".$lang_message['access_denied_speedbar'];
            message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_board['access_denied_group_newtopic_in_forum']), 1);
        }
        elseif(forum_all_password($id))
        {
            if(isset($_POST['check_pass']))
            {
                $check_f_pass_id = intval($_POST['f_id']);
                $check_f_pass = $_POST['f_pass'];
                if ($LB_flood->isBlock())
                    message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control_stop']));
                elseif ($check_f_pass == $cache_forums[$id]['password'] AND $cache_forums[$id]['password'] != "")
                {
                    if($member_id['user_group'] != 5)
                        $who = $member_id['name'];
                    else
                        $who = $_IP;
            
                    $check_f_pass = md5($who.$cache_forums[$id]['password']);
                    update_cookie( "LB_password_forum_".$id, $check_f_pass, 365 );
                    header( "Location: {$_SERVER['REQUEST_URI']}" );
                    exit();
                }
                elseif ($check_f_pass != $cache_forums[$id]['password'] AND $cache_forums[$id]['password'] != "")
                    message ($lang_message['access_denied'], $lang_m_board['wrong_password_forum']);
            }

            $link_speddbar = speedbar_forum ($id).$lang_message['access_denied_speedbar'];
            $onl_location = $lang_m_board['location_newtopic']." <a href=\"".forum_link($id)."\">".$cache_forums[$id]['title']."</a> ".$lang_message['access_denied_speedbar'];
            message ($lang_message['access_denied'], $lang_m_board['write_password_forum']);
            
            $tpl->load_template ( 'board/forum_password.tpl' );
            $tpl->tags('{forum_title}', $cache_forums[forum_all_password($id)]['title']);
            $tpl->tags('{forum_id}', forum_all_password($id));
            $tpl->compile('content');
            $tpl->clear();
                        
        }
        elseif (!member_publ_access(2))
        {
            $link_speddbar = speedbar_forum ($id).$lang_message['access_denied_speedbar'];
            $onl_location = $lang_m_board['location_newtopic']." <a href=\"".forum_link($id)."\">".$cache_forums[$id]['title']."</a> ".$lang_message['access_denied_speedbar2'];
    
            message ($lang_message['access_denied'], str_replace("{info}", member_publ_info(), $lang_m_board['access_denied_user_newtopic']), 1);   
        }
        elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $id)
            message ($lang_message['access_denied'], $lang_m_board['basket_forum'], 1);
        elseif($cache_forums[$id]['flink'])    
        {
            header("Location: ".forum_link($id));
            exit ("Redirect.");
        }
        elseif ($id AND $cache_forums[$id]['id'] == $id)
            include_once LB_MODULES . '/board/topic_new.php';
        else
            message ($lang_message['error'], $lang_m_board['no_forum_id'], 1);
	break;
    
   	case "topic":
        if ($id)
            include_once LB_MODULES . '/board/topic_posts.php';
        else
            message ($lang_message['error'], $lang_m_board['no_topic_id'], 1);
	break;
    
  	case "reply":
        if ($id)
            include_once LB_MODULES . '/board/reply.php';
        else
            message ($lang_message['error'], $lang_m_board['no_topic_id'], 1);
	break;
    
   	case "post_edit":
        if ($_GET['act'] == "")
            message ($lang_message['error'], $lang_message['no_act'], 1);
        elseif ($_GET['secret_key'] == "")
            message ($lang_message['error'], $lang_message['no_secret_key'], 1);
        elseif (!$logged)
            message ($lang_message['error'], $lang_message['not_logged'], 1);
        elseif ($id)
            include_once LB_MODULES . '/board/post_edit.php';
        else
            message ($lang_message['error'], $lang_m_board['no_post_id'], 1);
	break;
    
   	case "post_edit_mass":
        if (!$logged)
            message ($lang_message['error'], $lang_message['not_logged'], 1);
        elseif (!forum_moderation())
            message ($lang_message['access_denied'], $lang_m_board['no_moder'], 1);
        else
            include_once LB_MODULES . '/board/post_edit_mass.php';
	break;
        
   	case "moderation":
        if (!$logged)
            message ($lang_message['error'], $lang_message['not_logged'], 1);
        elseif (!forum_moderation())
            message ($lang_message['access_denied'], $lang_m_board['no_moder'], 1);
        else
            include_once LB_MODULES . '/board/moderation.php';
	break;
    
   	case "topic_edit":
        if (!$logged)
            message ($lang_message['error'], $lang_message['not_logged'], 1);
        else
            include_once LB_MODULES . '/board/topic_edit.php';
	break;

    case "last_topics":
        include_once LB_MODULES . '/board/topics_out.php';
	break;
    
    case "last_posts":
        include_once LB_MODULES . '/board/posts_out.php';
	break;
    
    case "topic_active":
        include_once LB_MODULES . '/board/topics_out.php';
	break;
    
    case "favorite":
        if (!$logged)
            message ($lang_message['error'], $lang_message['not_logged'], 1);
        elseif (!$id)
            message ($lang_message['error'],$lang_m_board['no_topic_id'], 1);
        elseif (!isset($_GET['secret_key']) OR $_GET['secret_key'] == "")
            message ($lang_message['error'], $lang_message['no_secret_key'], 1);
        else
            include_once LB_MODULES . '/board/favorite.php';
	break;
    
    case "subscribe":
        if (!$logged)
            message ($lang_message['error'], $lang_message['not_logged'], 1);
        elseif (!$id)
            message ($lang_message['error'], $lang_m_board['no_topic_id'], 1);
        elseif (!isset($_GET['secret_key']) OR $_GET['secret_key'] == "")
            message ($lang_message['error'], $lang_message['no_secret_key'], 1);
        else
            include_once LB_MODULES . '/board/subscribe.php';
	break;
    
    case "notice":
        $notice_group = explode (",", $cache_forums_notice[$id]['group_access']);
        
        if (!$id OR !$cache_forums_notice[$id]['id'])
            message ($lang_message['error'], $lang_m_board['no_notice_id'], 1);
        elseif (!$cache_forums_notice[$id]['active_status'])
            message ($lang_message['error'], $lang_m_board['no_active_notice_id'], 1);
        elseif (!in_array($member_id['user_group'], $notice_group ) AND !in_array("0", $notice_group ))
            message ($lang_message['access_denied'], $lang_m_board['access_denied_notice'], 1);
        else
            include_once LB_MODULES . '/board/notice.php';
	break;

	default :
		include_once LB_MODULES . '/board/main.php';
	break;
}

?>