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

if ($cache_config['forums_news']['conf_value'] AND $cache_forums[$cache_config['forums_news_fid']['conf_value']]['id'] AND $cache_forums[$cache_config['forums_news_fid']['conf_value']]['last_title'] != "")
{     
    if (!isset($_COOKIE['LB_last_news']) OR $_COOKIE['LB_last_news'] < $cache_forums[$cache_config['forums_news_fid']['conf_value']]['last_post_date'])
    {
        $tpl->tags( '[last_forum_news]', "" );
        $tpl->tags( '[/last_forum_news]', "" );
        $tpl->tags('{last_forum_news_title}', $cache_forums[$cache_config['forums_news_fid']['conf_value']]['last_title']);
        $tpl->tags('{last_forum_news_topic_link}', topic_link($cache_forums[$cache_config['forums_news_fid']['conf_value']]['last_topic_id'], $cache_config['forums_news_fid']['conf_value'], true));
        $tpl->tags('{last_forum_news_close}', $redirect_url."?last_news_close=1&sk=".$secret_key);
    }
    else
    {
        $tpl->block( "'\\[last_forum_news\\].*?\\[/last_forum_news\\]'si", "" );
        $tpl->tags('{last_forum_news_title}', "");
        $tpl->tags('{last_forum_news_topic_link}', "");
        $tpl->tags('{last_forum_news_close}', "");
    }
}
else
{
    $tpl->block( "'\\[last_forum_news\\].*?\\[/last_forum_news\\]'si", "" );
    $tpl->tags('{last_forum_news_title}', "");
    $tpl->tags('{last_forum_news_topic_link}', "");
    $tpl->tags('{last_forum_news_close}', "");
}
?>