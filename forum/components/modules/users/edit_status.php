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

$lang_m_u_edit_status = language_forum ("board/modules/users/edit_status");

$DB->prefix = array ( 0 => DLE_USER_PREFIX );
$row = $DB->one_join_select( "u.name, u.user_id, u.user_group, u.mstatus, s.text, s.id", "LEFT", "users u||members_status s", "u.mstatus=s.id", "u.name='{$member_name}'" );

if($row['user_id'] AND ($member_id['user_id'] == $row['user_id'] OR $member_id['user_group'] == 1))
{    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_edit_status['location']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $link_speddbar = speedbar_forum (0, true)."|".$lang_location;
    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_edit_status['location_online']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $onl_location = $lang_location;
    
    $meta_info_other = str_replace("{name}", $row['name'], $lang_m_u_edit_status['meta_info']);

    if (!intval($cache_group[$row['user_group']]['g_status']))
    {
        message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_u_edit_status['access_denied_group']), 1);
    }
    elseif (isset($_POST['editprofile']))
    {
        $_SESSION['LB_action_pass'] = 0;
            
        if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
        {
            exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
        }
            
        $errors = array ();
                  
        $mstatus = $DB->addslashes(trim(wrap_word(words_wilter(htmlspecialchars(strip_tags($_POST['mstatus']))))));
                                    
        if (utf8_strlen($mstatus) > intval($cache_group[$row['user_group']]['g_status']))
            $errors[] = str_replace("{max}", intval($cache_group[$row['user_group']]['g_status']), $lang_m_u_edit_status['status_max']);
                         
        if (! $errors[0])
        {
            if (!$row['id'] AND $mstatus)
            {
                $DB->insert("member_id = '{$row['user_id']}', date = '{$time}', text = '{$mstatus}'", "members_status");
                $row['id'] = $DB->insert_id();
            }
            else
            {
                if ($mstatus)
                    $DB->update ("text = '{$mstatus}', date = '{$time}'", "members_status", "id='{$row['id']}'");
                else
                {
                    $DB->delete ("id='{$row['id']}'", "members_status");
                    $row['id'] = 0;
                }
            }
                
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update ("mstatus = '{$row['id']}'", "users", "user_id='{$row['user_id']}'");
            header( "Location: ".profile_edit_link($row['name'], $row['user_id'], "status") );
            exit();
        }
        else
            message ($lang_message['error'], $errors);
    }
    else
    {
        $tpl->load_template ( 'users/users_profile_edit_status.tpl' );
        $tpl->tags('{member_name}', $row['name']);
            
        if($row['user_id'] == $member_id['user_id'])
        {
            $tpl->tags_blocks("subscribe");        
            $tpl->tags('{subscribe}', member_subscribe());
        }
        else
            $tpl->tags_blocks("subscribe", false); 
            
        if(intval($cache_group[$row['user_group']]['g_status']))
        {
            $tpl->tags_blocks("edit_status");
            $tpl->tags('{profile_edit_status}', profile_edit_link($row['name'], $row['user_id'], "status"));
        }
        else
            $tpl->tags_blocks("edit_status", false);
                    
        $tpl->tags('{status}', $row['text']);
               
        $tpl->tags('{profile_edit_options}', profile_edit_link($row['name'], $row['member_id'], "options"));
    
        $tpl->compile('content');
        $tpl->clear();
    }
}
else
{
    $link_speddbar = speedbar_forum (0, true)."|".$lang_m_u_edit_status['location_error'];
    $onl_location = $lang_m_u_edit_status['location_online_error'];
    message ($lang_message['error'], $lang_m_u_edit_status['not_found'], 1);
}

$DB->free();

?>