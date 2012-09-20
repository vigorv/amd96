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

$cache = new lb_cache;

$cache_config = $cache->take("config");

if (!$cache_config)
{
	$DB->select( "conf_key, conf_value", "configuration" );
	$cache_config = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_config[$row['conf_key']] = array ();
		foreach ($row as $key => $value)
			$cache_config[$row['conf_key']][$key] = $value;
	}
	$DB->free();
	$cache_config = $cache->take("config", $cache_config);
}

if (!count($cache_config))
    exit ("The settings of the forum were not found.");
    
$cache_config['general_time']['conf_value']         = intval($cache_config['general_time']['conf_value']);
$cache_config['online_time']['conf_value']          = intval($cache_config['online_time']['conf_value']);
$cache_config['topic_page']['conf_value']           = intval($cache_config['topic_page']['conf_value']);
$cache_config['topic_page']['conf_value']           = intval($cache_config['topic_page']['conf_value']);
$cache_config['topic_lasttopics_num']['conf_value'] = intval($cache_config['topic_lasttopics_num']['conf_value']);
$cache_config['member_page']['conf_value']          = intval($cache_config['member_page']['conf_value']);
$cache_config['blockmod_statusnum']['conf_value']   = intval($cache_config['blockmod_statusnum']['conf_value']);
$cache_config['rss_topics']['conf_value']           = intval($cache_config['rss_topics']['conf_value']);
$cache_config['rss_posts']['conf_value']            = intval($cache_config['rss_posts']['conf_value']);
$cache_config['posts_pastemin']['conf_value']       = intval($cache_config['posts_pastemin']['conf_value']);
$cache_config['pic_autosize']['conf_value']         = intval($cache_config['pic_autosize']['conf_value']);
$cache_config['upload_maxsize']['conf_value']       = intval($cache_config['upload_maxsize']['conf_value']);
$cache_config['forums_subforums']['conf_value']     = intval($cache_config['forums_subforums']['conf_value']);
$cache_config['forums_news_fid']['conf_value']      = intval($cache_config['forums_news_fid']['conf_value']);

$time = time() + ($cache_config['general_time']['conf_value'] * 60);

if ($cache_config['general_coding']['conf_value'] == "utf-8")
    $LB_charset = "utf-8";
else
    $LB_charset = "windows-1251";

$cache_dle_update = $cache->take("cache_update", "", "dle_modules");
$cache_dle_update_now = false;
if (($cache_dle_update != "no_cache" AND !$cache_dle_update) OR !$cache_dle_update)
{
	$DB->select( "*", "cache_update" );
	$cache_dle_update = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_dle_update[$row['name']] = array ();
		foreach ($row as $key => $value)
			$cache_dle_update[$row['name']][$key] = $value;
	}
	$DB->free();
    if (count($cache_dle_update))
    {
        $cache_dle_update_now = true;
        $cache_dle_update = $cache->take("cache_update", $cache_dle_update, "dle_modules");
    }
    else
        $cache->update("cache_update", "no_cache", "dle_modules");
        
}
elseif ($cache_dle_update == "no_cache")
{
    unset($cache_dle_update);
    $cache_dle_update = array();
}

$DB->select( "*", "cache_update" );
$cache_dle_update_check = false;
while ( $row = $DB->get_row() )
{
    if ($cache_dle_update_now OR !isset($cache_dle_update[$row['name']]) OR (isset($cache_dle_update[$row['name']]) AND $cache_dle_update[$row['name']]['lastdate'] < $row['lastdate']))
    {
        $cache_dle_update_check = true;
        $cache_dle_update[$row['name']]['lastdate'] = $row['lastdate'];
        $cache->update("cache_update", $cache_dle_update, "dle_modules");
        
        if ($row['name'] == "groups") $cache->clear("", "group");
        if ($row['name'] == "forums") $cache->clear("", "forums");
        if ($row['name'] == "banned") $cache->clear("", "banfilters");
        if ($row['name'] == "reputation") $cache->clear("dle_modules", "reputation");
        if ($row['name'] == "statistic") $cache->clear("statistics", "stats_users");
    }
}
$DB->free();

if ($cache_dle_update_check)
    $cache->update("cache_update", $cache_dle_update, "dle_modules");

$cache_group = $cache->take("group");

if (!$cache_group)
{
	$DB->select( "*", "groups" );
	$cache_group = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_group[$row['g_id']] = array ();
		foreach ($row as $key => $value)
			$cache_group[$row['g_id']][$key] = $value;
	}
	$DB->free();
	$cache_group = $cache->take("group", $cache_group);
}

$cache_cron = $cache->take("cron", "", "", "no");

if (!$cache_cron)
{
	$cache_cron = $time;
	$cache_cron = $cache->take("cron", $cache_cron, "", "no");
}

$cache_ranks = $cache->take("ranks");

if (($cache_ranks != "no_cache" AND !$cache_ranks) OR !$cache_ranks)
{
	$DB->select( "*", "members_ranks", "", "ORDER BY post_num ASC" );
	$cache_ranks = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_ranks[$row['id']] = array ();
		foreach ($row as $key => $value)
			$cache_ranks[$row['id']][$key] = $value;
	}
	$DB->free();
    if (count($cache_ranks))
    {
        function cmp($a, $b) 
        {
            if ($a['post_num'] == $b['post_num'])
                return 0;
            return ($a['post_num'] < $b['post_num']) ? -1 : 1;
        }
        usort($cache_ranks, "cmp");   
        $cache_ranks = $cache->take("ranks", $cache_ranks);
    }
    else
        $cache->update("ranks", "no_cache");
}
elseif ($cache_ranks == "no_cache")
{
    unset($cache_ranks);
    $cache_ranks = array();
}

$cache_banfilters = $cache->take("banfilters");

if (($cache_banfilters != "no_cache" AND !$cache_banfilters) OR !$cache_banfilters)
{
    $DB->prefix = DLE_PREFIX;
	$DB->select( "*", "banned" );
	$cache_banfilters = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_banfilters[$row['id']] = array ();
		foreach ($row as $key => $value)
			$cache_banfilters[$row['id']][$key] = $value;
	}
	$DB->free();
    if(count($cache_banfilters))
	    $cache_banfilters = $cache->take("banfilters", $cache_banfilters);
    else
        $cache->update("banfilters", "no_cache");
}
elseif ($cache_banfilters == "no_cache")
{
    unset($cache_banfilters);
    $cache_banfilters = array();
}

$cache_forums = $cache->take("forums");

if (!$cache_forums)
{
    $DB->prefix = array ( 1 => DLE_USER_PREFIX );
    $DB->join_select( "f.*, u.foto AS avatar", "LEFT", "forums f||users u", "f.last_post_member_id =u.user_id", "", "ORDER by posi, title ASC" );
    unset($cache_forums);
	$cache_forums = array ();
    
    $flink_keys = array(
        "id", "ficon", "parent_id", "posi", 
        "title", "description", "alt_name", 
        "group_permission", "posts", "flink", 
        "flink_npage"
    );
    
    $fcat_keys = array(
        "id", "parent_id", "posi", "title",
        "alt_name", "group_permission", 
        "meta_desc", "meta_key"
    );
    
	while ( $row = $DB->get_row() )
	{
		$cache_forums[$row['id']] = array ();
		foreach ($row as $key => $value)
        {
            if (!$row['parent_id']) // Если форум является категорией
            {
                if (in_array($key, $fcat_keys)) $cache_forums[$row['id']][$key] = $value;
            }
            elseif ($row['flink'] != "")    // Если форум является ссылкой
            {
                if (in_array($key, $flink_keys)) $cache_forums[$row['id']][$key] = $value;
            }
            else
                $cache_forums[$row['id']][$key] = $value;
        }
	}
	$DB->free();
    if (count($cache_forums))
    {
        $cache->update("forums", $cache_forums);
	    $cache_forums = $cache->take("forums");
    }
}

$cache_forums_moder = $cache->take("forums_moder");

if (($cache_forums_moder != "no_cache" AND !$cache_forums_moder) OR !$cache_forums_moder)
{
	$DB->select( "*", "forums_moderator");
    unset($cache_forums_moder);
	$cache_forums_moder = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_forums_moder[$row['fm_id']] = array ();
		foreach ($row as $key => $value)
			$cache_forums_moder[$row['fm_id']][$key] = $value;
	}
	$DB->free();
    if (count($cache_forums_moder))
    {
        $cache->update("forums_moder", $cache_forums_moder);
	    $cache_forums_moder = $cache->take("forums_moder");
    }
    else
        $cache->update("forums_moder", "no_cache");
}
elseif ($cache_forums_moder == "no_cache")
{
    unset($cache_forums_moder);
    $cache_forums_moder = array();
}

$cache_email = $cache->take("email_template", "", "template");

if (!$cache_email)
{
	$DB->select( "id, body_text", "templates_email", "", "ORDER by id ASC" );
	$cache_email = array ();
	while ( $row = $DB->get_row() )
	{
		$cache_email[$row['id']] = $row['body_text'];
	}
	$DB->free();
	$cache_email = $cache->take("email_template", $cache_email, "template");
}

$cache_forums_filter = $cache->take("forums_filter");

if (($cache_forums_filter != "no_cache" AND !$cache_forums_filter) OR !$cache_forums_filter)
{  
    $DB->select( "*", "forums_filter", "", "ORDER BY id ASC" );
    $cache_forums_filter = array ();
    while ( $row = $DB->get_row() )
    {
        $cache_forums_filter[$row['id']] = array ();
        foreach ($row as $key => $value)
            $cache_forums_filter[$row['id']][$key] = $value;
    }
    $DB->free();
    if(count($cache_forums_filter))
        $cache_forums_filter = $cache->take("forums_filter", $cache_forums_filter);
    else
        $cache->update("forums_filter", "no_cache");
}
elseif ($cache_forums_filter == "no_cache")
{
    unset($cache_forums_filter);
    $cache_forums_filter = array();
}

$cache_forums_notice = $cache->take("forums_notice");

if (($cache_forums_notice != "no_cache" AND !$cache_forums_notice) OR !$cache_forums_notice)
{  
    $DB->select( "*", "forums_notice", "", "ORDER BY id ASC" );
    $cache_forums_notice = array ();
    while ( $row = $DB->get_row() )
    {
        $cache_forums_notice[$row['id']] = array ();
        foreach ($row as $key => $value)
            $cache_forums_notice[$row['id']][$key] = $value;
    }
    $DB->free();
    if(count($cache_forums_notice))
        $cache_forums_notice = $cache->take("forums_notice", $cache_forums_notice);
    else
        $cache->update("forums_notice", "no_cache");
}
elseif ($cache_forums_notice == "no_cache")
{
    unset($cache_forums_notice);
    $cache_forums_notice = array();
}

$cache_stats_users = $cache->take("stats_users", "", "statistics");

if (!$cache_stats_users)
{  
    $cache_stats_users = array ();
    $DB->prefix = DLE_USER_PREFIX;
    $stats_users_all = $DB->one_select( "COUNT(*) as count", "users" );
    $cache_stats_users['users'] = $stats_users_all['count'];
    $DB->free($stats_users_all);
    
    $DB->prefix = DLE_USER_PREFIX;
    $stats_users = $DB->one_select( "name, user_id, reg_date", "users", "", "ORDER BY reg_date DESC LIMIT 1" );
    $cache_stats_users['last_name'] = $stats_users['name'];
    $cache_stats_users['last_id'] = $stats_users['user_id'];
    $DB->free($stats_users);
    
    $cache_stats_users = $cache->take("stats_users", $cache_stats_users, "statistics");
}

$cache_user_agent = $cache->take("user_agent");

if (($cache_user_agent != "no_cache" AND !$cache_user_agent) OR !$cache_user_agent)
{  
    $DB->select( "*", "user_agent", "", "ORDER BY name ASC" );
    $cache_user_agent = array ();
    while ( $row = $DB->get_row() )
    {
        $cache_user_agent[$row['id']] = array ();
        foreach ($row as $key => $value)
            $cache_user_agent[$row['id']][$key] = $value;
    }
    $DB->free();
    if(count($cache_user_agent))
        $cache_user_agent = $cache->take("user_agent", $cache_user_agent);
    else
        $cache->update("user_agent", "no_cache");
}
elseif ($cache_user_agent == "no_cache")
{
    unset($cache_user_agent);
    $cache_user_agent = array();
}

$cache_adtblock = $cache->take("adtblock", "", "template");

if (($cache_adtblock != "no_cache" AND !$cache_adtblock) OR !$cache_adtblock)
{  
    $DB->select( "*", "adtblock", "", "ORDER BY id ASC" );
    $cache_adtblock = array ();
    while ( $row = $DB->get_row() )
    {
        $cache_adtblock[$row['id']] = array ();
        foreach ($row as $key => $value)
            $cache_adtblock[$row['id']][$key] = $value;
    }
    $DB->free();
    if(count($cache_adtblock))
        $cache_adtblock = $cache->take("adtblock", $cache_adtblock, "template");
    else
        $cache->update("adtblock", "no_cache", "template");
}
elseif ($cache_adtblock == "no_cache")
{
    unset($cache_adtblock);
    $cache_adtblock = array();
}

if ($cache_config['topic_sharelink']['conf_value'])
{
    $cache_sharelink = $cache->take("topics_sharelink");
    
    if (($cache_sharelink != "no_cache" AND !$cache_sharelink) OR !$cache_sharelink)
    {  
        $DB->select( "*", "topics_sharelink", "", "ORDER BY id ASC" );
        $cache_sharelink = array ();
        while ( $row = $DB->get_row() )
        {
            $cache_sharelink[$row['id']] = array ();
            foreach ($row as $key => $value)
                $cache_sharelink[$row['id']][$key] = $value;
        }
        $DB->free();
        if(count($cache_sharelink))
            $cache_sharelink = $cache->take("topics_sharelink", $cache_sharelink);
        else
            $cache->update("topics_sharelink", "no_cache");
    }
    elseif ($cache_sharelink == "no_cache")
    {
        unset($cache_sharelink);
        $cache_sharelink = array();
    }
}
else
    $cache_sharelink = array();
?>