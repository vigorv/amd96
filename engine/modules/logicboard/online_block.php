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

if ($logicboard_conf['online_block'])
{    
    $lang_online_block = array(
        'online_members_first'                  => '{info}',
        'online_members_next'                   => ', {info}',
        'online_members_hide_loc'               => 'Просматривает: Доступ закрыт'
    );
    
    $cache_user_agent = get_vars( "logicboard_user_agents" );
    
    if( !$cache_user_agent )
    {        
        $cache_user_agent = array();
        $LB_user_agents = $db->query( "SELECT * FROM " . LB_DB_PREFIX . "_user_agent ORDER BY name ASC" );
        
        while ( $row_lb = $db->get_row($LB_user_agents) )
        {               
            $cache_user_agent[$row_lb['id']] = array ();
            foreach ($row_lb as $key => $value)
                $cache_user_agent[$row_lb['id']][$key] = $value;
        }
        $db->free($LB_user_agents);
        set_vars( "logicboard_user_agents", $cache_user_agent );
    }
        
    function online_bots($user_agent = "")
    {
        global $cache_user_agent;
        
        if (!$user_agent)
            return false;
    	
    	$found_bot = false;
    	foreach($cache_user_agent as $bot)
        {
    		if(stristr($user_agent, $bot['search_ua']))
            {
    			$found_bot = $bot['name'];
    			break;
    		}
    	}
    	
    	return $found_bot;
    }
        
    function online_members ($limit = 0, $users = "all", $onl_do = "", $onl_op = "", $onl_id = 0)
    {
        global $db, $cache_lb_group, $cache_lb_config, $_TIME, $lang_online_block, $member_id, $config;
        
        $onl_limit = $_TIME - (intval($cache_lb_config['online_time']['conf_value']) * 60);
        
        $list = "";
        $onl_g = 0;
        $onl_u = 0;
        $onl_a = 0;
        $onl_h = 0;
        
        $where = array();
        $where[] = "mo_date > '$onl_limit'";
        
        if ($onl_do != "")
        {
            $where[] = "mo_loc_do = '{$onl_do}'";
            
            if ($onl_op != "") $where[] = "mo_loc_op = '{$onl_op}'";
            if ($onl_id) $where[] = "mo_loc_id = '{$onl_id}'";  
        }
    
        $where = implode(" AND ", $where);
        $bots_online = array();
        
        if ($users == "all")
        {
            $db->query("SELECT mo.*, u.banned FROM ".LB_DB_PREFIX."_members_online mo LEFT JOIN ".USERPREFIX."_users u ON mo.mo_member_id=u.user_id WHERE {$where} ORDER by mo_date DESC");
            while ( $row = $db->get_row() )
            {
                $onl_a ++;
                $name_bot = online_bots($row['mo_browser']);
                if ($name_bot AND !in_array($name_bot, $bots_online) AND !$row['mo_member_name'])
                {
                    $bots_online[] = $name_bot;
                    
                    if (!$list)
                        $list .= str_replace("{info}", $name_bot, $lang_online_block['online_members_first']);
                    else
                        $list .= str_replace("{info}", $name_bot, $lang_online_block['online_members_next']);
                    $onl_g ++;
                }
                elseif ($row['mo_member_name'] AND !$row['mo_hide'])
                {
                    if (!$row['mo_hide'])
                    {
                        $onl_u ++;
                        $class_hide = "";
                    }
                    else
                    {
                        $onl_h ++;
                        $class_hide = "class=\"hiden\"";
                    }
                    if (!$limit OR $limit >= $onl_u)
                    {
                        if (!$row['mo_hide'] OR ($row['mo_hide'] AND $member_id['user_group'] == 1))
                        {
                        
                            $row['mo_location'] = htmlspecialchars(strip_tags(str_replace("|", " ", $row['mo_location'])));
                                            
                            if ($row['banned'])
                                $member_name = "<font color=gray>".$row['mo_member_name']."</font>";
                            else
                                $member_name = $cache_lb_group[$row['mo_member_group']]['g_prefix_st'].$row['mo_member_name'].$cache_lb_group[$row['mo_member_group']]['g_prefix_end'];
                            
                            if ($row['mo_loc_fid'])
                            {
                                if (!LB_forum_permission($row['mo_loc_fid'], "read_forum")) // не видны форум и темы
                                    $row['mo_location'] = $lang_online_block['online_members_hide_loc'];
                                elseif (LB_forum_all_password($row['mo_loc_fid']) AND ($row['mo_loc_op'] == "topic" OR $row['mo_loc_op'] == "reply")) // не видны форум и темы
                                    $row['mo_location'] = $lang_online_block['online_members_hide_loc'];
                            }
                            
                            $info = "<a href=\"".LB_profile_link($row['mo_member_name'], $row['mo_member_id'], true)."\" class=\"a_b_s\" title=\"".$row['mo_location']."; ".LB_formatdate($row['mo_date'])."\" ".$class_hide.">".$member_name."</a>";
                            
                            if (!$list)
                                $list = str_replace("{info}", $info, $lang_online_block['online_members_first']);
                            else
                                $list .= str_replace("{info}", $info, $lang_online_block['online_members_next']);
                        }
                    }
                }
                elseif ($row['mo_hide'])
                    $onl_h ++;
                else
                    $onl_g ++;
                    
                $name_bot = "";          
            }
            $db->free();
        }
        $online = $onl_g."|".$onl_u."|".$onl_a."|".$onl_h."|".$list;
        $online = explode("|", $online);
        
        return $online;
    }
  
    $online = online_members(); // intval($cache_config['online_limitblock']['conf_value'])
    list($onl_g, $onl_u, $onl_a, $onl_h, $list) = $online; 
                    
    $tpl->load_template( 'logicboard_online.tpl' );
    
    $tpl->set( '{online_members}', $list );
    $tpl->set( '{online_i_u}', $onl_u );
    $tpl->set( '{online_i_g}', $onl_g );
    $tpl->set( '{online_i_a}', $onl_a );
    $tpl->set( '{online_i_h}', $onl_h );
    $tpl->set( '{online_limit}', intval($cache_lb_config['online_time']['conf_value']) );
    
    $tpl->set('{online_link}', LB_online_link_list("action"));
    
    $tpl->compile( 'logicboard_online_block' );
    $tpl->clear();
    
    $logicboard_online_block = $tpl->result['logicboard_online_block'];
}
else
    $logicboard_online_block = "";

?>