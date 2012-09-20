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

$lang_m_b_favorite = language_forum ("board/modules/board/favorite");

if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
    message ($lang_message['access_denied'], $lang_message['secret_key'], 1);
else
{  
    $topic = $DB->one_select( "id, forum_id, title, hiden", "topics", "id = '{$id}'");
    if (!$topic['id'])
        message ($lang_message['error'], $lang_m_b_favorite['topic_not_found'], 1);
    else
    {  
        $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_favorite['location']);
        $lang_location = str_replace("{title}", $topic['title'], $lang_location);
        $link_speddbar = speedbar_forum ($topic['forum_id'])."|".$lang_location;

        $lang_location = str_replace("{link}", topic_link($topic['id'], $topic['forum_id']), $lang_m_b_favorite['location_online']);
        $lang_location = str_replace("{title}", $topic['title'], $lang_location);
        $onl_location = $lang_location;
        
        include LB_CLASS.'/topics_out.php';
        $LB_topics = new LB_topics;
        $LB_topics->favorite ($topic['id'], $topic['forum_id'], true, $topic['hiden'] );
        unset($LB_topics);
    }
}    
?>