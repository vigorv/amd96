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

$lang_m_b_notice = language_forum ("board/modules/board/notice");

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];
    
$link_speddbar = speedbar_forum (0, 1)."|".str_replace("{title}", $cache_forums_notice[$id]['title'], $lang_m_b_notice['location']);

$lang_location = str_replace("{link}", notice_link($id), $lang_m_b_notice['location_online']);
$lang_location = str_replace("{title}", $cache_forums_notice[$id]['title'], $lang_location);
$onl_location = $lang_location;

$meta_info_other = str_replace("{title}", $cache_forums_notice[$id]['title'], $lang_m_b_notice['meta_info']);

$DB->prefix = array ( 0 => DLE_USER_PREFIX );
$row = $DB->one_join_select( "mo.mo_id, mo.mo_date, u.name, user_id, user_group, foto, signature, posts_num, topics_num", "LEFT", "users u||members_online mo", "u.user_id=mo.mo_member_id", "u.user_id = '{$cache_forums_notice[$id]['author_id']}'", "LIMIT 1" );
    
$tpl->load_template ( 'board/forums_notice.tpl' );
$tpl->tags('{title}', $cache_forums_notice[$id]['title']);
$tpl->tags('{notice_id}', $id);
$tpl->tags('{member_name}', $row['name']);
$tpl->tags('{member_group}', member_group($row['user_group']));
$tpl->tags_blocks("online", member_online($row['mo_id'], $row['mo_date'], $onl_limit));
$tpl->tags_blocks("offline", member_online($row['mo_id'], $row['mo_date'], $onl_limit), true);
$tpl->tags('{member_avatar}', member_avatar($row['foto']));
$tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
$tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
$tpl->tags('{topics_link}', member_topics_link($row['name'], $row['user_id']));
$tpl->tags('{posts_link}', member_posts_link($row['name'], $row['user_id']));

if (intval($cache_config['forums_unitetp']['conf_value']))
    $tpl->tags('{member_posts}', $row['posts_num'] + $row['topics_num']);
else
    $tpl->tags('{member_posts}', $row['posts_num']);
    
$tpl->tags('{post_date}', formatdate($cache_forums_notice[$id]['start_date']));

$tpl->tags('{member_id}', $row['user_id']);
$tpl->tags('{member_group_icon}', member_group_icon($row['user_group']));

if (member_rank($row['posts_num'], $row['user_id']))
{
    $rank = member_rank($row['posts_num'], $row['user_id']);
    $tpl->tags('{ranks_starts}', $rank[0]);
    $tpl->tags('{ranks_title}', $rank[1]);
    $tpl->tags_blocks("ranks");
}
else
    $tpl->tags_blocks("ranks", false);

$cache_forums_notice[$id]['text'] = hide_in_post($cache_forums_notice[$id]['text'], $row['user_id']);

$tpl->tags('{post_text}', $cache_forums_notice[$id]['text']);

if($logged AND ($member_id['user_group'] == 1 OR $cache_group[$member_id['user_group']]['g_access_cc']))
{
    $tpl->tags_blocks("edit_post");
    $tpl->tags( '{notice_edit_link}', $redirect_url."control_center/?do=board&op=notice_edit&id=".$id);
}
else
    $tpl->tags_blocks("edit_post", false);

$tpl->tags('{fast_forum}', ForumsList(0, 0, "", "", true));
$tpl->compile('content');

$DB->free($row);

?>