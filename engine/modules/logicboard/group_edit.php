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

if ($action_logicboard['group_edit'] == 1)
{
    $action_logicboard['group_edit'] = 0;
    
    $group_mask = intval($_POST['group_mask']);
    if ($group_mask <= 0 OR !$user_group[$group_mask]['id']) $group_mask = 4;
    $LB_group = $db->super_query( "SELECT * FROM " . LB_DB_PREFIX . "_groups WHERE g_id = '{$group_mask}'" );
    $where = array ();
    foreach ($LB_group as $key => $value)
    {
        if ($key == "g_title") $value = stripslashes($group_name);
        if ($key == "g_id") $value = $new_group;
        $where[] = $key." = '".$db->safesql($value)."'";             
    }
    $where = implode (", ", $where);
    $db->query( "INSERT INTO " . LB_DB_PREFIX . "_groups SET ".$where );
        
    $db_result = $db->query( "SELECT id, group_permission, password_notuse FROM " . LB_DB_PREFIX . "_forums" );
    while ( $row = $db->get_row($db_result) )
    {
        $access_f = unserialize($row['group_permission']);
                
        $access_f[$new_group] = array();
  		$access_f[$new_group]['read_forum'] = $access_f[$group_mask]['read_forum'];
  		$access_f[$new_group]['read_theme'] = $access_f[$group_mask]['read_theme'];
  		$access_f[$new_group]['creat_theme'] = $access_f[$group_mask]['creat_theme'];
  		$access_f[$new_group]['answer_theme'] = $access_f[$group_mask]['answer_theme'];
       	$access_f[$new_group]['upload_files'] = $access_f[$group_mask]['upload_files'];
  		$access_f[$new_group]['download_files'] = $access_f[$group_mask]['download_files'];
                
        $group_permission = $db->safesql( serialize($access_f) );
                
        $password_notuse = $row['password_notuse'];
        if ($password_notuse)
        {
            $notuse = explode(",", $password_notuse);
            if (in_array($group_mask, $notuse))
                $notuse[] = $new_group;
                        
            $password_notuse = implode (",", $notuse);
        }
             
        $db->query( "UPDATE " . LB_DB_PREFIX . "_forums SET group_permission = '{$group_permission}', password_notuse = '{$password_notuse}' WHERE id = '{$row['id']}'" );  
    }
    $db->free($db_result);
    
    $this_time = time() + ($config['date_adjust'] * 60);
    $db->query( "INSERT INTO " . LB_DB_PREFIX . "_cache_update SET name = 'groups', lastdate = '{$this_time}' ON DUPLICATE KEY UPDATE name = 'groups', lastdate = '{$this_time}'" );
    $db->query( "INSERT INTO " . LB_DB_PREFIX . "_cache_update SET name = 'forums', lastdate = '{$this_time}' ON DUPLICATE KEY UPDATE name = 'forums', lastdate = '{$this_time}'" );
}
          
if ($action_logicboard['group_edit'] == 2)
{
    $action_logicboard['group_edit'] = 0;
    
    $db->query( "DELETE FROM " . LB_DB_PREFIX . "_groups WHERE g_id = '$id'" );
    $db_result = $db->query( "SELECT id, group_permission, password_notuse FROM " . LB_DB_PREFIX . "_forums" );
    while ( $row = $db->get_row($db_result) )
    {
        $access_f = unserialize($row['group_permission']);
        unset($access_f[$id]);                        
        $group_permission = $db->safesql( serialize($access_f) );
                        
        $password_notuse = $row['password_notuse'];
        if ($password_notuse)
        {
            $notuse = explode(",", $password_notuse);
            $notuse_new = array();
            foreach ($notuse as $value)
            {
                if ($value != $id) $notuse_new[] = $value;
            }
                                
            $password_notuse = implode (",", $notuse_new);
        }
                     
        $db->query( "UPDATE " . LB_DB_PREFIX . "_forums SET group_permission = '{$group_permission}', password_notuse = '{$password_notuse}' WHERE id = '{$row['id']}'" );  
    }
    $db->free($db_result);
    $this_time = time() + ($config['date_adjust'] * 60);
    $db->query( "INSERT INTO " . LB_DB_PREFIX . "_cache_update SET name = 'groups', lastdate = '{$this_time}' ON DUPLICATE KEY UPDATE name = 'groups', lastdate = '{$this_time}'" );
    $db->query( "INSERT INTO " . LB_DB_PREFIX . "_cache_update SET name = 'forums', lastdate = '{$this_time}' ON DUPLICATE KEY UPDATE name = 'forums', lastdate = '{$this_time}'" );
}

?>