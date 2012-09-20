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
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$tpl->load_template( 'statistic.tpl' );

$tpl->tags( '{global_do}', $do );
$tpl->tags( '{global_op}', $op );
$tpl->tags( '{global_id}', $id );

if (!$cache_group[$member_id['user_group']]['g_show_online']) $tpl->tags_blocks("group_online", false);
elseif (!$cache_config['online_status']['conf_value']) $tpl->tags_blocks("group_online", false);
else
{
    $tpl->tags_blocks("group_online");
    $tpl->tags( '{online_list_action}', online_link_list("action") );
    $tpl->tags( '{online_list_name}', online_link_list("name") );
}

if($do == "board" AND $op == "forum")
{
    $tpl->tags_blocks("topics");
    $tpl->tags_blocks("birthday", false);
    $tpl->tags_blocks("stats", false);
    $tpl->tags_blocks("posts", false);
}
elseif($do == "board" AND ($op == "topic" OR $op == "reply"))
{   
    $tpl->tags_blocks("topics", false);
    $tpl->tags_blocks("birthday", false);
    $tpl->tags_blocks("stats", false);
    $tpl->tags_blocks("posts");
}
else
{
    $online_max = $cache->take("online_max", "", "statistics");
    $online_max = explode("|", $online_max);
    list($online_max, $online_max_date) = $online_max;
    $tpl->tags( '{online_max}', intval($online_max) );
    $tpl->tags( '{online_max_date}', formatdate($online_max_date) );

    if (count($cache_birthday))
        $tpl->tags_blocks("birthday");
    else
        $tpl->tags_blocks("birthday", false);
        
    $tpl->tags_blocks("stats");
    
    $stat_post = 0;
    foreach ($cache_forums as $num_posts)
    {
        $stat_post += $num_posts['posts'];
        $stat_post += $num_posts['topics'];
    }
    
    $tpl->tags( '{stat_post}', $stat_post );
    $tpl->tags( '{stat_users}', intval($cache_stats_users['users']) );
    $tpl->tags( '{stat_new_member}', $cache_stats_users['last_name'] );
    $tpl->tags( '{stat_new_member_link}', profile_link($cache_stats_users['last_name'], $cache_stats_users['last_id']) );
    
    $tpl->tags_blocks("topics", false);
    $tpl->tags_blocks("posts", false);
}
     
$tpl->compile( 'statistic' );
$tpl->clear();

?>