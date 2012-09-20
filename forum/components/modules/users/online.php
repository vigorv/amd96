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

$lang_m_u_online = language_forum ("board/modules/users/online");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$link_speddbar = speedbar_forum (0, true)."|".$lang_m_u_online['location'];
$onl_location = $lang_m_u_online['location_online'];
$meta_info_other = $lang_m_u_online['meta_info'];
    
if(isset($_GET['order']) AND $_GET['order'] == "action") $order = "mo_location";
elseif (isset($_GET['order']) AND $_GET['order'] == "name") $order = "mo_member_name";
else $order = "mo_date";
    
if (isset ( $_REQUEST['page'] ))
    $page = intval ( $_GET['page'] );
else
    $page = 0;

if ($page < 0)
    $page = 0;

if ($page)
{
    $page = $page - 1;
    $page = $page * $cache_config['member_page']['conf_value'];
}

$i = $page;

if ($member_id['user_group'] == 1)
    $where = "";
else
    $where = "AND mo_hide = '0'";

$DB->prefix = array( 1 => DLE_USER_PREFIX );
$DB->join_select( "name, banned, mo_member_group, mo_hide, user_id, foto, posts_num, reg_date, mo_member_id, lastdate, personal_title, mo_id, mo_date, mo_location, mo_browser, mo_loc_fid", "LEFT", "members_online mo||users u", "u.name=mo.mo_member_name", "mo_date > '{$onl_limit}' {$where}", "ORDER by {$order} DESC LIMIT ".$page.", ".$cache_config['member_page']['conf_value'] );
       
$tpl->load_template ( 'users/users_online.tpl' );

while ( $row = $DB->get_row() )
{
    $i ++;
    
    if ($row['name'] != "")
    {
        $tpl->tags_blocks("member_online");
        
        if ($row['mo_hide'])
            $tpl->tags('{member_name}', "<i>".$row['name']."</i>");
        else
            $tpl->tags('{member_name}', $row['name']);
            
        $tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
        $tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
        $tpl->tags('{personal_title}', $row['personal_title']);
        $tpl->tags('{member_group}', member_group($row['mo_member_group'], $row['banned']));
        $tpl->tags('{mid}', $row['user_id']);
    }
    else
    {
        $tpl->tags_blocks("member_online", false);
        $tpl->tags('{profile_link}', "#");
        
         if (online_bots($row['mo_browser']))
            $tpl->tags('{member_name}', online_bots($row['mo_browser']));
        else
            $tpl->tags('{member_name}', $lang_m_u_online['guest']);
            
        $tpl->tags('{personal_title}', "");
        $tpl->tags('{pm_link}', "#");
        $tpl->tags('{member_group}', member_group(5));
        $tpl->tags('{mid}', "0");
    }
        
    $tpl->tags('{member_avatar}', member_avatar($row['foto']));
    $tpl->tags('{online_time}', formatdate($row['mo_date']));
    
    if ($row['mo_loc_fid'])
    {
        if (!forum_permission($row['mo_loc_fid'], "read_forum")) // не видны форум и темы
            $row['mo_location'] = $lang_g_function['online_members_hide_loc'];
        elseif (forum_all_password($row['mo_loc_fid']) AND ($row['mo_loc_op'] == "topic" OR $row['mo_loc_op'] == "reply")) // не видны форум и темы
            $row['mo_location'] = $lang_g_function['online_members_hide_loc'];
    }
    
    $tpl->tags('{online_location}', $row['mo_location']);
 
    $tpl->compile('all');
}
$DB->free();
$tpl->clear();

$nav = $DB->one_select( "COUNT(*) as count", "members_online", "mo_date > '{$onl_limit}' {$where}");
$nav_all = $nav['count'];
$DB->free($nav);

$order_nav = "";
if (isset($_GET['order'])) $order_nav = $_GET['order'];

$link_nav = online_link_list($order_nav, true);

if ($nav_all > $cache_config['member_page']['conf_value'])
{
    include LB_CLASS.'/navigation_board.php';
    $navigation = new navigation;
    $navigation->create($page, $nav_all, $cache_config['member_page']['conf_value'], $link_nav, "5");
    $navigation->template();    
    unset($navigation);
}
else
    $tpl->result['navigation'] = "";

if ($nav_all)
{
    $tpl->load_template ( 'users/users_online_global.tpl' );
    $tpl->tags_templ('{users}', $tpl->result['all']);
    $tpl->tags_templ('{pages}', $tpl->result['navigation']);
    $tpl->compile('content');
    $tpl->clear();
}
else
    message ($lang_message['information'], $lang_m_u_online['no_online_members']);

?>