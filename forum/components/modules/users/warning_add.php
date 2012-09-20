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

function LB_check_filter($type, $word, $user)
{
	$word =	utf8_strtolower($word);
	$user =	utf8_strtolower($user);
	if ($type == "name")
	{
		$word = preg_quote( $word );
		if (! preg_match( "#\*#", $word ))
		{
			if(preg_match( "#^{$word}$#i".regular_coding(), $user ) )
				return true;
		}

	}
	if ($type == "ip")
	{
		$word = preg_quote( $word );
		if (preg_match( "#\*#", $word ))
		{
			$word = str_replace( "\*", "([0-9]|[0-9][0-9]|[0-9][0-9][0-9])*", $word );
			if(preg_match( "#{$word}#i".regular_coding(), $user ) )
				return true;
		}
		else
		{
			if(preg_match( "#^{$word}$#i".regular_coding(), $user ) )
				return true;
		}
	}
	return false;
}

$lang_m_u_warning_add = language_forum ("board/modules/users/warning_add");

$DB->prefix = DLE_USER_PREFIX;
$row = $DB->one_select( "user_id, name, email, user_group, mf_options, logged_ip, count_warning", "users", "name='{$member_name}'" );

if (!$row['user_id'])
    message ($lang_message['error'], $lang_m_u_warning_add['not_found']);
elseif ($cache_group[$row['user_group']]['g_warning'] AND $row['user_group'] == 1)
    message ($lang_message['error'],str_replace("{group}", $cache_group[$row['user_group']]['g_title'], $lang_m_u_warning_add['access_denied']));
elseif ($row['count_warning'] >= intval($cache_config['warning_levels']['conf_value']))
    message ($lang_message['access_denied'], $lang_m_u_warning_add['level']);
else
{
    $send_ok = false;
    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_warning_add['location']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $link_speddbar = speedbar_forum (0, true)."|".$lang_location;
    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_warning_add['location_online']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $onl_location = $lang_location;
    
    $meta_info_other = str_replace("{name}", $row['name'], $lang_m_u_warning_add['meta_info']);
    
    $bb_allowed_out = array('b', 'i', 's', 'u', 'text_align', 'quote', 'spoiler', 'color', 'url', 'email', 'translite', 'smile', 'font', 'size', 'hide');
    
    if (isset($_POST['add_warn']))
    {
        $errors = array();
    
        $_POST['text'] = htmlspecialchars($_POST['text']);
            
        filters_input ('post');
    
        if (utf8_strlen($_POST['text']) < 5)
            $errors[] = str_replace("{min}", 5, $lang_m_u_warning_add['post_min']);
        
        if (utf8_strlen($_POST['text']) > 1000)
            $errors[] = str_replace("{max}", 1000, $lang_m_u_warning_add['post_max']);
        
        $_POST['text'] = parse_word(html_entity_decode($_POST['text']), true, true, true, $bb_allowed_out);
        $text = $DB->addslashes($_POST['text']);
            
        $add_ban = false; 
        if (($row['count_warning'] + 1) >= intval($cache_config['warning_levels']['conf_value']))
        {
            if (LB_check_filter("name", $row['name'], $member_id['name']))
                $errors[] = $lang_m_u_warning_add['block_self'];
            elseif (LB_check_filter("ip", $row['logged_ip'], $_IP) AND $cache_config['warning_ip']['conf_value'])
                $errors[] = $lang_m_u_warning_add['block_self_ip'];
            else
                $add_ban = true;
        }    
            
   	    if( ! $errors[0] )
        {          
            $send_ok = true;
            $DB->insert("moder_id = '{$member_id['user_id']}', moder_name = '{$member_id['name']}', mid = '{$row['user_id']}', date = '{$time}', description = '{$text}', st_w = '1'", "members_warning");
            
            if ($add_ban)
            {
                $banned_member_days = intval($cache_config['warning_days']['conf_value']);
                if (!$banned_member_days)
                    $banned_member_days = 0;
                    
                if ($banned_member_days)
                    $date_end = $time + (60 * 60 * 24 * $banned_member_days);
                else
                    $date_end = 0;
                    
                if ($cache_config['warning_text']['conf_value'])
                    $ban_text = $DB->addslashes($cache_config['warning_text']['conf_value']);
                else
                    $ban_text = $lang_m_u_warning_add['max_level'];
                    
                $DB->prefix = DLE_USER_PREFIX;
                $check = $DB->one_select( "users_id", "banned", "users_id = '{$row['user_id']}'" );
        
                if (!$check['users_id'])
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->insert("users_id = '{$row['user_id']}', date = '{$date_end}', descr = '{$ban_text}', days = '{$banned_member_days}'", "banned");
                }
                else 
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $DB->update("date = '{$date_end}', descr = '{$ban_text}', days = '{$banned_member_days}'", "banned", "user_id = '{$row['user_id']}'");
                }
                
                $info_ban = str_replace("{days}", $banned_member_days, $lang_m_u_warning_add['info_ban']);
                $info_ban = str_replace("{text}", $ban_text, $info_ban);
                $info_ban = $DB->addslashes($info_ban);
                $DB->insert("member_id = '{$row['user_id']}', moder_id = '{$member_id['user_id']}', moder_name = '{$member_id['name']}', date = '{$time}', info = '{$info_ban}', ip = '{$_IP}'", "logs_blocking");
                
                $cache->clear("", "banfilters");
                
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("count_warning = count_warning+1, banned = 'yes'", "users", "user_id = '{$row['user_id']}'");
                message ($lang_message['information'], str_replace("{name}", $row['name'], $lang_m_u_warning_add['message_level_up_block']));
            }
            else
            {
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("count_warning = count_warning+1", "users", "user_id = '{$row['user_id']}'");
                if ($cache_config['warning_lcchange']['conf_value'])
                {
                    $text = $DB->addslashes(str_replace("{name}", $member_id['name'], $lang_m_u_warning_add['pm_text'])).$text;
                    send_new_pm($lang_m_u_warning_add['pm_title'], $row['user_id'], $text, $row['email'], $row['name'], $row['mf_options'], 1);
                }  
                message ($lang_message['information'], str_replace("{name}", $row['name'], $lang_m_u_warning_add['message_level_up']));
            }
        }
        else
            message ($lang_message['error'], $errors);
    }

    if (!$send_ok)
    {  
        $tpl->load_template ( 'users/warning_add.tpl' );
        require LB_MAIN . '/components/scripts/bbcode/bbcode.php';
        $tpl->tags('{bbcode}', $bbcode_script.$bbcode); 
        $tpl->tags('{title}', str_replace("{name}", $member_name, $lang_m_u_warning_add['title']));
        $tpl->compile('content');
        $tpl->clear();
    }
}

?>