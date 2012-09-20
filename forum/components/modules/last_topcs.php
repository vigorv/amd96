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

$last_topics = "";

if ($cache_config['topic_lasttopics']['conf_value'])
{
    $access_forums = array();
    foreach ($cache_forums as $cf)
    {
        if(forum_permission($cf['id'], "read_forum"))
        {
            if (forum_all_password($cf['id']))
                $access_forums[] = $cf['id'];
            elseif(!forum_permission($cf['id'], "read_theme"))
                $access_forums[] = $cf['id'];
        }
        else
            $access_forums[] = $cf['id'];
    }    
    
    $where = array();
    
    if (!forum_options_topics(0, "allpermission"))
        $where[] = "hiden = '0'";
    
    $access_forums = implode (",", $access_forums);
    
    if ($access_forums)
    {
        $where[] = "forum_id NOT IN (".$access_forums.")";
    }
    
    $where = implode (" AND ", $where);
        
    $DB->select( "title, id, date_last, forum_id, member_name_last, last_post_member, hiden", "topics", $where, "ORDER BY date_last DESC LIMIT ".intval($cache_config['topic_lasttopics_num']['conf_value']) );
    $tpl->load_template ( 'block_last_topics.tpl' );
    $find = false;
    while ( $row = $DB->get_row() )
    {
        $find = true;
        
        $tpl->tags('{forum_speedbar}', htmlspecialchars($cache_forums[$row['forum_id']]['title']));
        $tpl->tags('{title}', sub_title($row['title'], 40));
        $tpl->tags('{topic_link}', topic_link($row['id'], $row['forum_id'], true));
        $tpl->tags('{date}', formatdate($row['date_last']));
        $tpl->tags('{author}', $row['member_name_last']);
        $tpl->tags('{author_link}', profile_link($row['member_name_last'], $row['last_post_member']));
    
        $tpl->compile('last_topics');
    }
    $tpl->clear();
    $DB->free();
    
    if ($find)
        $last_topics = $tpl->result['last_topics'];
    else
        $last_topics = "";
}
?>