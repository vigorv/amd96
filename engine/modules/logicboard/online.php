<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if ($logicboard_conf['online_status'])
{                        
    $onl_location = $db->safesql("На сайте");           
    $onl_browser = $db->safesql($_SERVER['HTTP_USER_AGENT']);
    $onl_session = md5( uniqid( microtime(), 1 ).$_IP );
            
    if ($member_id['user_group'] != 5)
    {
        $member_options = unserialize($member_id['mf_options']);
        $member_options = LB_member_options_default($member_options);
        
        if ($member_options['online']) $mo_hide = 1;
        else $mo_hide = 0;
        
        unset($member_options);
                
        $db->query( "DELETE FROM " . LB_DB_PREFIX . "_members_online WHERE mo_member_id = '{$member_id['user_id']}'" );
        $db->query( "INSERT INTO " . LB_DB_PREFIX . "_members_online SET mo_id = '{$onl_session}', mo_member_id = '{$member_id['user_id']}', mo_member_name = '{$member_id['name']}', mo_member_group = '{$member_id['user_group']}', mo_ip = '{$_IP}', mo_date = '{$_TIME}', mo_browser = '{$onl_browser}', mo_location = '{$onl_location}', mo_loc_do = '', mo_loc_op = '', mo_loc_id = '', mo_hide = '{$mo_hide}'" );
    }
    else
    {
        $db->query( "DELETE FROM " . LB_DB_PREFIX . "_members_online WHERE mo_ip = '{$_IP}'" );
        $db->query( "INSERT INTO " . LB_DB_PREFIX . "_members_online SET mo_id = '{$onl_session}', mo_member_name = '', mo_member_group = '5', mo_ip = '{$_IP}', mo_date = '{$_TIME}', mo_browser = '{$onl_browser}', mo_location = '{$onl_location}', mo_loc_do = '', mo_loc_op = '', mo_loc_id = '', mo_hide = '0' ON DUPLICATE KEY UPDATE mo_id = '{$onl_session}', mo_member_name = '', mo_member_group = '5', mo_ip = '{$_IP}', mo_date = '{$_TIME}', mo_browser = '{$onl_browser}', mo_location = '{$onl_location}', mo_loc_do = '', mo_loc_op = '', mo_loc_id = '', mo_hide = '0'" );
    }
}

?>