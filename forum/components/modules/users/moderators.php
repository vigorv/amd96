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

$lang_m_u_moderators = language_forum ("board/modules/users/moderators");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$link_speddbar = speedbar_forum (0, true)."|".$lang_m_u_moderators['location'];
$onl_location = $lang_m_u_moderators['location_online'];
$meta_info_other = $lang_m_u_moderators['meta_info'];

function users_info ()
{
    global $row, $tpl, $onl_limit, $lang_m_u_moderators;
            
    $tpl->tags('{member_name}', $row['name']);
    $tpl->tags('{member_group}', member_group($row['user_group']));
    $tpl->tags_blocks("online", member_online($row['mo_id'], $row['mo_date'], $onl_limit));
    $tpl->tags_blocks("offline", member_online($row['mo_id'], $row['mo_date'], $onl_limit), true);
    $tpl->tags('{member_avatar}', member_avatar($row['foto']));
    
    $tpl->tags('{member_id}', $row['user_id']);
    $tpl->tags('{reg_date}', formatdate($row['reg_date']));
    $tpl->tags('{lastdate}', formatdate($row['lastdate']));
    $tpl->tags('{personal_title}', $row['personal_title']);
    
    $tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
    $tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
    $tpl->tags('{topics_link}', member_topics_link($row['name'], $row['user_id']));
    $tpl->tags('{posts_link}', member_posts_link($row['name'], $row['user_id']));
    $tpl->tags('{moder_forum}', $lang_m_u_moderators['moder_forum']);
}

$DB->prefix = array( 0 => DLE_USER_PREFIX );
$DB->join_select( "u.name, u.user_group, u.foto, u.user_id, u.reg_date, u.lastdate, u.personal_title, mo.mo_id, mo.mo_date", "LEFT", "users u||members_online mo||groups g", "u.user_id=mo.mo_member_id||u.user_group=g.g_id", "g.g_access_cc = '1'", "ORDER by reg_date DESC" );
       
$tpl->load_template ( 'users/users_moderators.tpl' );
$i = 0;

while ( $row = $DB->get_row() )
{
    $i ++;
    users_info();
    $tpl->compile('admin');
}
$DB->free();
$tpl->clear();

$tpl->load_template ( 'users/users_moderators_global.tpl' );
$tpl->tags('{title}', $lang_m_u_moderators['admin']);
$tpl->tags_templ('{users}', $tpl->result['admin']);
$tpl->compile('content');
$tpl->clear();
  
$DB->prefix = array( 0 => DLE_USER_PREFIX );      
$DB->join_select( "u.name, u.user_group, u.foto, u.user_id, u.reg_date, u.lastdate, u.personal_title, mo.mo_id, mo.mo_date", "LEFT", "users u||members_online mo||groups g", "u.user_id=mo.mo_member_id||u.user_group=g.g_id", "g.g_supermoders = '1' AND g.g_access_cc = '0'", "ORDER by reg_date DESC" );

$tpl->load_template ( 'users/users_moderators.tpl' );
$i = 0;

while ( $row = $DB->get_row() )
{
    $i ++;
    users_info();
    $tpl->compile('super');
}
$DB->free();
$tpl->clear();

if ($i)
{
    $tpl->load_template ( 'users/users_moderators_global.tpl' );
    $tpl->tags('{title}', $lang_m_u_moderators['super-moder']);
    $tpl->tags_templ('{users}', $tpl->result['super']);
    $tpl->compile('content');
    $tpl->clear();
}


$moders_list = array();
$moders_list['group'] = array();
$moders_list['moders'] = array();

if (count($cache_forums_moder))
{
    foreach ($cache_forums_moder as $moder)
    {
        if ($moder['fm_group_id'] AND $moder['fm_is_group'])
            $moders_list['group'][] = $moder['fm_group_id'];
    }
    
    foreach ($cache_forums_moder as $moder)
    {
        if ($moder['fm_member_id'] AND !in_array($moder['fm_group_id'], $moders_list['group']))
            $moders_list['moders'][] = $moder['fm_member_id'];            
    }
}

$where = array();

if (count($moders_list['group']))
{
    $moders_group = implode("', '", $moders_list['group']);
    $where[] = "u.user_group IN ('".$moders_group."')";
}

if (count($moders_list['moders']))
{
    $moders_members = implode("', '", $moders_list['moders']);
    $where[] = "u.user_id IN ('".$moders_members."')";
}

if($where[0] != "")
{
    $where_bd = implode(" OR ", $where);

    $DB->prefix = array( 0 => DLE_USER_PREFIX ); 
    $DB->join_select( "u.name, u.user_group, u.foto, u.user_id, u.reg_date, u.lastdate, u.personal_title, mo.mo_id, mo.mo_date", "LEFT", "users u||members_online mo", "u.user_id=mo.mo_member_id", $where_bd, "ORDER by reg_date DESC" );

    $tpl->load_template ( 'users/users_moderators.tpl' );
    $i = 0;

    while ( $row = $DB->get_row() )
    {
        $i ++;
        users_info();
        $tpl->compile('moder');
    }
    $DB->free();
    $tpl->clear();

    if ($i)
    {
        $tpl->load_template ( 'users/users_moderators_global.tpl' );
        $tpl->tags('{title}', $lang_m_u_moderators['moder']);
        $tpl->tags_templ('{users}', $tpl->result['moder']);
        $tpl->compile('content');
        $tpl->clear();
    }
}
?>