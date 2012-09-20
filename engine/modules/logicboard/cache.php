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

if ($cron == 2)
{
    @unlink( ENGINE_DIR . '/cache/system/logicboard_user_birthday.php' );
}

$cache_lb_config = get_vars( "logicboard_config" );
    
if( !$cache_lb_config )
{        
    $cache_lb_config = array();
    $LB_config_out = "'warning_on', 'warning_show', 'online_time', 'general_rewrite_url', 'basket_on', 'basket_fid'";
    $LB_config = $db->query( "SELECT conf_id, conf_key, conf_value FROM " . LB_DB_PREFIX . "_configuration WHERE conf_key IN ({$LB_config_out})" );
            
    while ( $row_lb = $db->get_row($LB_config) )
    {               
        $cache_lb_config[$row_lb['conf_key']] = array ();
        foreach ($row_lb as $key => $value)
            $cache_lb_config[$row_lb['conf_key']][$key] = $value;
    }
    $db->free($LB_config);
    set_vars( "logicboard_config", $cache_lb_config );
}

$cache_forums = get_vars( "logicboard_forums" );
    
if( !$cache_forums )
{        
    $cache_forums = array();
    $LB_forums = $db->query( "SELECT id, parent_id, group_permission, password_notuse, password, title, alt_name, flink, postcount, last_post_member_id FROM " . LB_DB_PREFIX . "_forums ORDER by posi, title ASC" );
        
    while ( $row_lb = $db->get_row($LB_forums) )
    {               
        $cache_forums[$row_lb['id']] = array ();
        foreach ($row_lb as $key => $value)
            $cache_forums[$row_lb['id']][$key] = $value;
    }
    $db->free($LB_forums);
    set_vars( "logicboard_forums", $cache_forums );
}

$cache_lb_group = get_vars( "logicboard_group" );

if( !$cache_lb_group )
{       
    $cache_lb_group = array();
    $LB_group = $db->query( "SELECT * FROM " . LB_DB_PREFIX . "_groups" );
            
    while ( $row_lb = $db->get_row($LB_group) )
    {               
        $cache_lb_group[$row_lb['g_id']] = array ();
        foreach ($row_lb as $key => $value)
            $cache_lb_group[$row_lb['g_id']][$key] = $value;
    }
    $db->free($LB_group);
    set_vars( "logicboard_group", $cache_lb_group );
}
    
?>