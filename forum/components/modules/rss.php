<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

@session_start ();

@error_reporting ( E_ERROR );

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LB_MAIN', realpath("../../") );

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads/' );

require_once LB_CLASS . '/database.php';
include_once LB_CONFIG . '/board_db.php';

$_IP = $_SERVER['REMOTE_ADDR'];

require_once LB_CLASS . '/cache.php';
require_once LB_GLOBAL . '/creat_cache.php';

if (!intval($cache_config['rss_on']['conf_value']))
    exit ("Disabled.");

$redirect_url = $cache_config['general_site']['conf_value'];

require_once LB_GLOBAL . '/functions.php';
require_once LB_MAIN . '/components/scripts/bbcode/function.php';

$lang_m_rss = language_forum ("board/modules/rss");

$banned_ip = false;
$banned_name = false;
$logged = false;

if( intval( $_SESSION['dle_user_id'] ) > 0 OR intval( $_COOKIE['dle_user_id'] ) > 0)
{
    filters_input('coockie|session');
    
    if (intval( $_SESSION['dle_user_id'] ) > 0)
	{
		$id_login = intval( $_SESSION['dle_user_id'] );
		$pass_login = $DB->addslashes($_SESSION['dle_password']);
        $member_sk = $DB->addslashes($_SESSION['LB_member_sc']);
	}
	else
	{
		$id_login = intval( $_COOKIE['dle_user_id'] );
		$pass_login = $DB->addslashes($_COOKIE['dle_password']);
        $member_sk = $DB->addslashes($_COOKIE['LB_member_sc']);
	}

    $DB->prefix = DLE_USER_PREFIX;
	$member_id = $DB->one_select( "*", "users", "user_id='{$id_login}'" );
    $logged = false;
    
	if( $member_id['password'] == md5($pass_login) AND $member_id['secret_key'] == $member_sk AND $member_sk != "")
	{
        $member_options = unserialize($member_id['mf_options']);
        $member_options = member_options_default($member_options);
        
        if (LB_member_ip($member_id['allowed_ip']))
        {
            $logged = true;
            $secret_key = md5( strtolower( $_SERVER['HTTP_HOST'] . $member_id['name'] . sha1($pass_login) . date( "Ymd" ) ) );
		
            $_SESSION['dle_user_id'] = $member_id['user_id'];
            $_SESSION['dle_password'] = $pass_login;
            $_SESSION['LB_member_sc'] = $member_id['secret_key'];
        }
	}
}

if( !$logged)
{
	$member_id = array ();
    $member_id['user_group'] = 5;
	update_cookie( "LB_secret_key", "", 0 );
    update_cookie( "LB_member_sc", "", 0 );
    update_cookie( "dle_user_id", "", 0 );
	update_cookie( "dle_password", "", 0 );
	update_cookie( "dle_hash", "", 0 );
	$_SESSION['dle_user_id'] = 0;
	$_SESSION['dle_password'] = "";
	$_SESSION['LB_secret_key'] = "";
    $_SESSION['LB_member_sc'] = "";
}

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
	exit ("Offline.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
	exit ("Banned.");
}

require_once LB_CLASS . '/rss.php';

$rss_channel = new rssGenerator_channel();
$rss_channel->atomLinkHref = '';
$rss_channel->language = '';
$rss_channel->generator = 'LogicBoard';
$rss_channel->managingEditor = '';
$rss_channel->webMaster = '';

$attachment_post = array();

if (isset($_GET['forumid']))
{
    $id = intval ($_GET['forumid']);
    
    if (!$cache_forums[$id]['id'])
        exit ("Wrong address to RSS channel.");
    elseif (!forum_permission($id, "read_forum") OR !forum_permission($id, "read_theme") OR forum_all_password($id))
        exit ("No access.");
        
    $where = "";
    if(!forum_options_topics($id, "hideshow"))
        $where = "AND t.hiden = '0' AND p.hide = '0'";
        
    $rss_channel->title = $cache_config['general_name']['conf_value'];
    $rss_channel->link = forum_link($id);
    $rss_channel->description = $lang_m_rss['last_forum'].$cache_forums[$id]['title'];
                    
    $rss_db = $DB->join_select( "t.id, t.forum_id, t.title, t.last_post_id, t.date_last, p.text, t.member_name_last, p.attachments, p.pid, p.post_member_id", "LEFT", "topics t||posts p", "t.last_post_id=p.pid", "t.forum_id = '{$id}' {$where}", "ORDER by t.date_last DESC LIMIT ".intval($cache_config['rss_topics']['conf_value']) );
    while ( $row = $DB->get_row($rss_db) )
    {
        $item = new rssGenerator_item();
        $item->title = $row['title'];
        
        if ($row['attachments']) $attachment_post[] = $row['pid'];
        $row['text'] = hide_in_post($row['text'], $row['post_member_id']);
        $item->description = $row['text'];
        if (strpos($item->description, "[attachment=") !== false)
        {                
            $item->description = show_attach ($item->description, $attachment_post);
        }
        $item->link = topic_link($row['id'], $row['forum_id'], true);
        $item->guid = topic_link($row['id'], $row['forum_id'], true);
        $item->pubDate = date("r", $row['date_last']);
        $rss_channel->items[] = $item; 
    }
    $DB->free($rss_db);
}
elseif (isset($_GET['topicid']))
{
    $id = intval ($_GET['topicid']);
    $topic = $DB->one_select( "id, forum_id, hiden, title", "topics", "id = '{$id}'" );
    
    if (!$cache_forums[$topic['forum_id']]['id'] OR !$topic['id'])
        exit ("Wrong address to RSS channel.");
    elseif (!forum_permission($topic['forum_id'], "read_forum") OR !forum_permission($topic['forum_id'], "read_theme") OR forum_all_password($topic['forum_id']) OR ($topic['hiden'] AND !forum_options_topics($topic['forum_id'], "hideshow")))
        exit ("No access.");
        
    $where = "";
    if(!forum_options_topics($topic['forum_id'], "hideshow"))
        $where = "AND hide = '0'";
        
    $rss_channel->title = $cache_config['general_name']['conf_value'];
    $rss_channel->link = topic_link($id, $topic['forum_id']);
    $rss_channel->description = $lang_m_rss['last_topic'].$topic['title'];
                
    $rss_db = $DB->select( "text, post_date, topic_id, attachments, pid, post_member_id", "posts", "topic_id = '{$id}' {$where}", "ORDER by post_date DESC LIMIT ".intval($cache_config['rss_posts']['conf_value']) );
    while ( $row = $DB->get_row($rss_db) )
    {
        $item = new rssGenerator_item();
        $item->title = $topic['title'];
        
        if ($row['attachments']) $attachment_post[] = $row['pid'];
        $row['text'] = hide_in_post($row['text'], $row['post_member_id']);
        $item->description = $row['text'];
        if (strpos($item->description, "[attachment=") !== false)
        {                
            $item->description = show_attach ($item->description, $attachment_post);
        }
        $item->link = topic_link($topic['id'], $topic['forum_id'], true);
        $item->guid = topic_link($topic['id'], $topic['forum_id'], true);
        $item->pubDate = date("r", $row['post_date']);
        $rss_channel->items[] = $item; 
    }
    $DB->free($rss_db);
}
else
{
    $rss_channel->title = $cache_config['general_name']['conf_value'];
    $rss_channel->link = $redirect_url;
    $rss_channel->description = $lang_m_rss['description'];
    
    $where = array ();
    
    $access_forums = array();
    foreach ($cache_forums as $cf)
    {
        if(forum_permission($cf['id'], "read_forum"))
        {
            if (forum_all_password($cf['id']))
                $access_forums[] = $cf['id'];
            elseif (!forum_permission($cf['id'], "read_theme"))
                $access_forums[] = $cf['id'];
        }
        else
            $access_forums[] = $cf['id'];
    }    
    
    if (!forum_options_topics(0, "allpermission"))
        $where[] = "t.hiden = '0'";
        
    $access_forums = implode (",", $access_forums);
    
    if ($access_forums)
    {
        $where[] = "t.forum_id NOT IN (".$access_forums.")";
    }
    
    $where_db = implode(" AND ", $where);
                        
    $rss_db = $DB->join_select( "t.id, t.forum_id, t.title, t.last_post_id, t.date_last, p.text, t.member_name_last, p.attachments, p.pid, p.post_member_id", "LEFT", "topics t||posts p", "t.last_post_id=p.pid", $where_db, "ORDER by t.date_last DESC LIMIT ".intval($cache_config['rss_topics']['conf_value']) );
    while ( $row = $DB->get_row($rss_db) )
    {
        $item = new rssGenerator_item();
        $item->title = $row['title'];
        
        if ($row['attachments']) $attachment_post[] = $row['pid'];
        $row['text'] = hide_in_post($row['text'], $row['post_member_id']);
        $item->description = $row['text'];
        if (strpos($item->description, "[attachment=") !== false)
        {                
            $item->description = show_attach ($item->description, $attachment_post);
        }
        $item->link = topic_link($row['id'], $row['forum_id'], true);
        $item->guid = topic_link($row['id'], $row['forum_id'], true);
        $item->pubDate = date("r", $row['date_last']);
        $rss_channel->items[] = $item; 
    }
    $DB->free($rss_db);
}

$DB->close ();

$rss_feed = new rssGenerator_rss();

if ($cache_config['general_coding']['conf_value'] == "utf-8")
    $rss_feed->encoding = 'UTF-8';
else
    $rss_feed->encoding = 'windows-1251';
    
$rss_feed->stylesheet = '<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>';
    
$rss_feed->version = '2.0';
header('Content-Type: text/xml');

echo $rss_feed->createFeed($rss_channel);

?>