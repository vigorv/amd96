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

$lang_m_u_warning = language_forum ("board/modules/users/warning");

$DB->prefix = DLE_USER_PREFIX;
$row = $DB->one_select( "user_id, name, user_group, email, mf_options, count_warning", "users", "name='{$member_name}'" );

if (!$row['user_id'])
    message ($lang_message['error'], $lang_m_u_warning['not_found']);
elseif ($cache_group[$row['user_group']]['g_warning'])
    message ($lang_message['error'], str_replace("{group}", $cache_group[$row['user_group']]['g_title'], $lang_m_u_warning['access_denied_history']));
else
{
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_warning['location']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $link_speddbar = speedbar_forum (0, true)."|".$lang_location;
    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_warning['location_online']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $onl_location = $lang_location;
    
    $meta_info_other = str_replace("{name}", $row['name'], $lang_m_u_warning['meta_info']);

    if ($cache_group[$member_id['user_group']]['g_warning'] AND isset($_POST['del_warning']))
    {
        if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
            message ($lang_message['error'], $lang_message['secret_key']);
        elseif(intval($_POST['w_select']) < 1 OR intval($_POST['w_select']) > 2)
            message ($lang_message['error'], $lang_message['no_act']);
        else
        {
            $wid = $_POST['wid'];
            if (is_array($wid) AND $wid[0] != "")
            {                
                foreach($wid as $value)
                {
                    $value = intval($value);
                    if (intval($_POST['w_select']) == 2)
                        $DB->delete("mid = '{$row['user_id']}}' AND id = '{$value}'", "members_warning");
                    else
                        $DB->update("st_w = '0'", "members_warning", "mid = '{$row['user_id']}' AND id = '{$value}'");
                }
                
                $count_war = $DB->one_select( "COUNT(*) as count", "members_warning", "mid = '{$row['user_id']}}' AND st_w = '1'" );
                
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("count_warning = '{$count_war['count']}'", "users", "user_id = '{$row['user_id']}'");
                
                if ($cache_config['warning_lcchange']['conf_value'])
                {
                    $text = str_replace("{num}", $count_war['count'], $lang_m_u_warning['level']);
                    send_new_pm($lang_m_u_warning['pm_title'], $row['user_id'], $text, $row['email'], $row['name'], $row['mf_options'], 1);
                }
            }
        }
    }

    $i = 0;

    $DB->select( "*", "members_warning", "mid = '{$row['user_id']}'", "ORDER by date DESC" );
       
    $tpl->load_template ( 'users/warning.tpl' );

    while ( $row2 = $DB->get_row() )
    {
        $i ++;
        $tpl->tags('{num}', $i);
        
        $tpl->tags('{moder_name}', $row2['moder_name']);
        $tpl->tags('{moder_id}', $row2['moder_id']);
        $tpl->tags('{moder_link}', profile_link($row2['moder_name'], $row2['moder_id']));
        
        $check_mid = $row2['mid'];
        
        if ($member_id['user_id'] == $row2['moder_id'])
            $check_mid = $row2['moder_id'];

        $row2['description'] = hide_in_post($row2['description'], $check_mid);
        
        if ($row2['st_w'])
            $tpl->tags('{st_w}', $lang_m_u_warning['status_on']);
        else
            $tpl->tags('{st_w}', $lang_m_u_warning['status_off']);
        
        $tpl->tags('{text}', $row2['description']);
        $tpl->tags('{wid}', $row2['id']);    
        $tpl->tags('{date}', formatdate($row2['date']));
        
        $tpl->tags_blocks("moder_warning", $cache_group[$member_id['user_group']]['g_warning']);
        $tpl->compile('history');
    }
    $DB->free();
    $tpl->clear();

    if ($i)
    {
        $tpl->load_template ( 'users/warning_global.tpl' );
        
        $tpl->tags_blocks("moder_warning", $cache_group[$member_id['user_group']]['g_warning']);
        
        $w_select = "<option value=\"1\">".$lang_m_u_warning['option_1']."</option>";
        $w_select .= "<option value=\"2\">".$lang_m_u_warning['option_2']."</option>";
        
        $tpl->tags('{w_select}', $w_select);
        
        $tpl->tags('{member_name}', $row['name']);
        $tpl->tags_templ('{history}', $tpl->result['history']);
            
        $tpl->compile('content');
        $tpl->clear();
    }
    else
    {
        $lang_text = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_warning['no_warnings']);
        $lang_text = str_replace("{name}", $row['name'], $lang_text);
        message ($lang_message['information'], $lang_text);
    }
}
?>