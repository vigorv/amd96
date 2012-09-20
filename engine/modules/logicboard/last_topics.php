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

if ($logicboard_conf['last_topic'])
{                                   
    $access_forums = array();
    foreach ($cache_forums as $cf)
    {
        if(LB_forum_permission($cf['id'], "read_forum"))
        {
            if (LB_forum_password($cf['id']))
                $access_forums[] = $cf['id'];
            elseif(!LB_forum_permission($cf['id'], "read_theme"))
                $access_forums[] = $cf['id'];
        }
        else
            $access_forums[] = $cf['id'];
    }    
            
    $where = array();
            
    $where[] = "hiden = '0'";
            
    $access_forums = implode (",", $access_forums);
            
    if ($access_forums)
    {
        $where[] = "forum_id NOT IN (".$access_forums.")";
    }
            
    $where = implode (" AND ", $where);
    
    $LB_topics = $db->query( "SELECT title, id, date_last, member_name_last, last_post_member, hiden, forum_id, post_num, views FROM " . LB_DB_PREFIX . "_topics WHERE ".$where." ORDER BY date_last DESC LIMIT ".$logicboard_conf['last_topic_num'] );
        
    $tpl->load_template( 'logicboard_topics.tpl' );
    while ( $row_lb = $db->get_row($LB_topics) )
    {                
        if( LB_utf8_strlen( $row_lb['title'] ) > 40 )
            $row_lb['title'] = LB_utf8_substr( $row_lb['title'], 0, 40 ) . "...";
          
        $tpl->set('{title}', $row_lb['title']);
        $tpl->set('{topic_link}', LB_topic_link($row_lb['id'], $row_lb['forum_id'], true));
        $tpl->set('{date}', LB_formatdate($row_lb['date_last']));
        $tpl->set('{author}', $row_lb['member_name_last']);
        $tpl->set('{author_link}', LB_profile_link($row_lb['member_name_last'], $row_lb['last_post_member']));
        $tpl->set('{post_num}', $row_lb['post_num']);
        $tpl->set('{views}', $row_lb['views']);
        
        if (!$logicboard_conf['show_last_forum'])
            $speedbar_lb = LB_speedbar_forum($row_lb['forum_id']);
        else
            $speedbar_lb = "<a href=\"".LB_forum_link($row_lb['forum_id'])."\">".$cache_forums[$row_lb['forum_id']]['title']."</a>";
                    
        $tpl->set('{speedbar_lb}', $speedbar_lb);
                
        $tpl->compile( 'logicboard_topics' );
    }
    $db->free($LB_topics);
    $tpl->clear();
            
    $tpl->load_template( 'logicboard_topics_global.tpl' );
    $tpl->set('{topics}', $tpl->result['logicboard_topics']);
    $tpl->compile( 'logicboard' );
    $tpl->clear();
    
    $logicboard_topics = $tpl->result['logicboard'];
}
else
    $logicboard_topics = "";

?>