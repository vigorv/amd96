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

$lang_m_u_all = language_forum ("board/modules/users/all");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$link_speddbar = speedbar_forum (0, true)."|".$lang_m_u_all['location'];
$onl_location = $lang_m_u_all['location_online'];
$meta_info_other = $lang_m_u_all['meta_info'];

$title = $lang_m_u_all['module_title'];

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

$DB->prefix = array ( 0 => DLE_USER_PREFIX );
$DB->join_select( "u.name, u.user_group, u.foto, u.posts_num, u.banned, u.topics_num, u.reg_date, u.user_id, u.lastdate, u.personal_title, mo.mo_id, mo.mo_date", "LEFT", "users u||members_online mo", "u.user_id=mo.mo_member_id", "", "ORDER by reg_date DESC LIMIT ".$page.", ".$cache_config['member_page']['conf_value'] );
       
$tpl->load_template ( 'users/users_all.tpl' );

while ( $row = $DB->get_row() )
{
    $i ++;
        
    $tpl->tags('{member_name}', $row['name']);
    $tpl->tags('{member_group}', member_group($row['user_group'], $row['banned']));
    $tpl->tags_blocks("online", member_online($row['mo_id'], $row['mo_date'], $onl_limit));
    $tpl->tags_blocks("offline", member_online($row['mo_id'], $row['mo_date'], $onl_limit), true);
    $tpl->tags('{member_avatar}', member_avatar($row['foto']));
    
    $tpl->tags('{member_posts}', $row['posts_num']);
    $tpl->tags('{member_id}', $row['user_id']);
    $tpl->tags('{reg_date}', formatdate($row['reg_date']));
    $tpl->tags('{lastdate}', formatdate($row['lastdate']));
    $tpl->tags('{personal_title}', $row['personal_title']);
    
    $tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
    $tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
    $tpl->tags('{topics_link}', member_topics_link($row['name'], $row['user_id']));
    $tpl->tags('{posts_link}', member_posts_link($row['name'], $row['user_id']));
    
    $tpl->tags('{topics_num}', $row['topics_num']);
    $tpl->tags('{posts_num}', $row['posts_num']);
 
    $tpl->compile('all');
}
$DB->free();
$tpl->clear();
    
$DB->prefix = DLE_USER_PREFIX;
$nav = $DB->one_select( "COUNT(*) as count", "users");
$nav_all = $nav['count'];
$DB->free($nav);
$link_nav = users_link_list();
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
    
$tpl->load_template ( 'users/users_all_global.tpl' );
$tpl->tags('{title}', $title);
$tpl->tags_templ('{users}', $tpl->result['all']);
$tpl->tags_templ('{pages}', $tpl->result['navigation']);
$tpl->compile('content');
$tpl->clear();

?>