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

$lang_m_users = language_forum ("board/modules/users");

switch ($op)
{   
    case "edit_status":
        if ($logged)
        {
            if ($member_name != "")
                include_once LB_MODULES . '/users/edit_status.php';
            else
                message ($lang_message['error'], $lang_m_users['no_member_id'], 1);
        }
        else
            message ($lang_message['error'], $lang_message['not_logged'], 1);
	break;

   	case "online":
        if ($cache_group[$member_id['user_group']]['g_show_online'])
            include_once LB_MODULES . '/users/online.php';
        else
            message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_users['access_denied_group_online']), 1);
	break;
    
    case "topics":
        if (!$member_name)
            message ($lang_message['error'], $lang_m_users['no_member_id'], 1);
        else
            include_once LB_MODULES . '/board/topics_out.php';
	break;
    
    case "posts":
        if (!$member_name)
            message ($lang_message['error'], $lang_m_users['no_member_id'], 1);
        else
            include_once LB_MODULES . '/board/posts_out.php';
	break;
    
    case "moderators":
        include_once LB_MODULES . '/users/moderators.php';
	break;
        
    case "favorite":
        if (!$logged)
            message ($lang_message['access_denied'], $lang_message['not_logged'], 1);
        else
            include_once LB_MODULES . '/users/favorite_subscribe.php';
	break;
    
    case "subscribe":
        if (!$logged)
            message ($lang_message['access_denied'], $lang_message['not_logged'], 1);
        else
            include_once LB_MODULES . '/users/favorite_subscribe.php';
	break;
    
    case "options":
        if (!$logged)
            message ($lang_message['access_denied'], $lang_message['not_logged'], 1);
        else
            include_once LB_MODULES . '/users/options.php';
	break;
         
    case "warning":
        if (!$cache_config['warning_show']['conf_value'] OR !$cache_config['warning_on']['conf_value'])
            message ($lang_message['access_denied'], $lang_message['access_denied_function']);
        elseif (!$member_name)
            message ($lang_message['error'], $lang_m_users['no_member_id']);
        else
            include_once LB_MODULES . '/users/warning.php';
	break;
    
    case "warning_add":
        if (!$logged)
            message ($lang_message['error'], $lang_message['not_logged']);
        elseif (!$cache_config['warning_on']['conf_value'] OR !$cache_group[$member_id['user_group']]['g_warning'])
            message ($lang_message['access_denied'], $lang_message['access_denied_function']);
        elseif (!$member_name)
            message ($lang_message['error'], $lang_m_users['no_member_id']);
        else
            include_once LB_MODULES . '/users/warning_add.php';
	break;
    
    case "all_status":
        include_once LB_MODULES . '/users/all_status.php';
	break;
    
	default:
		include_once LB_MODULES . '/users/all.php';
	break;
}

?>