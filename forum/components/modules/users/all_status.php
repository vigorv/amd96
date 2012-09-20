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

$lang_m_u_all_status = language_forum ("board/modules/users/all_status");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$link_speddbar = speedbar_forum (0, true)."|".$lang_m_u_all_status['location'];
$onl_location = $lang_m_u_all_status['location_online'];
$meta_info_other = $lang_m_u_all_status['meta_info'];

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
    $page = $page * $cache_config['status_page']['conf_value'];
}

$i = $page;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "s.*, u.name, u.mstatus, u.foto", "LEFT", "members_status s||users u", "s.member_id=u.user_id", "", "ORDER BY s.date DESC LIMIT ".$page.", ".$cache_config['status_page']['conf_value'] );
       
$tpl->load_template ( 'users/status_all.tpl' );

while ( $row = $DB->get_row() )
{
    $i ++;
        
    $tpl->tags('{text}', sub_title($row['text'], 60));
    $tpl->tags('{date}', formatdate($row['date']));
    $tpl->tags('{avatar}', member_avatar($row['foto']));
    $tpl->tags('{author}', $row['name']);
    $tpl->tags('{author_link}', profile_link($row['name'], $row['member_id']));
    $tpl->tags('{member_id}', $row['member_id']);
 
    $tpl->compile('all');
}
$DB->free();
$tpl->clear();
    
$nav = $DB->one_select( "COUNT(*) as count", "members_status");
$nav_all = $nav['count'];
$DB->free($nav);
$link_nav = all_status_link(true);
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
    
$tpl->load_template ( 'users/status_all_global.tpl' );
$tpl->tags_templ('{status}', $tpl->result['all']);
$tpl->tags_templ('{pages}', $tpl->result['navigation']);
$tpl->compile('content');
$tpl->clear();

?>