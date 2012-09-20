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
@ob_start ();
@ob_implicit_flush ( 0 );

@error_reporting ( E_ERROR ); // E_ALL ^ E_NOTICE или E_ERROR

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LogicBoard_ADMIN', false );
define ( 'LB_MAIN', dirname ( __FILE__ ) );

require_once LB_MAIN.'/components/global/system.php';
require_once LB_GLOBAL.'/statistic.php';
require_once LB_MODULES.'/last_topcs.php';
require_once LB_MODULES.'/last_status.php';
require_once LB_MODULES.'/dle_news.php';

$topmenuch = file_get_contents("/var/www/html/rumedia.new/engine/cache/cat_menu_c0.tmp");

$lang_index = language_forum ("board/lang_index");

$tpl->load_template ( 'global.tpl' );

$tpl->tags( '{last_topics}', $last_topics );
$tpl->tags( '{last_status}', $last_status );
$tpl->tags( '{dle_news}', $block_news );
$tpl->tags( '{time_now}', date("d.m.Y, H:i", $time) );
$tpl->tags( '{link_topic_active}', link_on_module("board", "topic_active") );
$tpl->tags( '{link_moderators}', link_on_module("users", "moderators") );
$tpl->tags( '{link_last_posts}', link_on_module("board", "last_posts") );
$tpl->tags( '{link_last_topics}', link_on_module("board", "last_topics") );
$tpl->tags( '{link_feedback}', link_on_module("feedback") );
$tpl->tags( '{link_users}', link_on_module("users") );
$tpl->tags( '{link_rss}', rss_link($rss_link)  );
$tpl->tags( '{time_close}', $time );
$tpl->tags( '{charset}', $LB_charset );
$tpl->tags( '{topmenuch}', $topmenuch );

require_once LB_MODULES.'/forum_news.php';
//require_once LB_GLOBAL.'/mysql_stat.php'; // вывод подробной информации о запросах, загрузке кеша и шаблонов. Доступ только юзеру с ID = 1. Для вывода в global.tpl используется тег {mysql_stat}
$tpl->tags( '{mysql_stat}', ""); // закомментриуйте эту строку, если включён вывод информации о запросах

if (!$secret_key) $secret_key = 0;
$tpl->tags( '{clear_cookie}', $redirect_url."?clear_cookie=1&sk=".$secret_key  );
$tpl->tags( '{all_tf_read}', $redirect_url."?all_tf_read=1&sk=".$secret_key  );

if ($do == "users")
{
    $tpl->tags_blocks("module_users");
    $tpl->tags_blocks("module_board", false);   
}
else
{
    $tpl->tags_blocks("module_users", false);
    $tpl->tags_blocks("module_board");   
}

if ($cache_config['general_template']['conf_value']) $tpl->tags( '{templates}', change_template() );
else $tpl->tags( '{templates}', "" );

if (!$meta_info_forum_desc) $meta_info_forum_desc = $meta_info_text;
if (!$meta_info_forum_keys) $meta_info_forum_keys = $meta_info_text;

$tpl->tags( '{meta_title}', meta_info ($meta_info_text, "title", $meta_info_forum, $meta_info_other) );
$tpl->tags( '{meta_description}', meta_info ($meta_info_forum_desc, "description", $meta_info_forum) );
$tpl->tags( '{meta_keyword}', meta_info ($meta_info_forum_keys, "keyword", $meta_info_forum) );
        
$LB_root = reset(explode("index.php", strtolower($_SERVER['PHP_SELF'])));
    
$pm_new = "";

if ($logged AND $member_id['pm_unread'])
{
    $lang_index['pm_new_text'] = str_replace ("{num}", $member_id['pm_unread'], $lang_index['pm_new_text']);
    $lang_index['pm_new_text'] = str_replace ("{link}", pm_member(), $lang_index['pm_new_text']);
    $pm_new = show_jq_message("2", $lang_index['pm_new_title'], $lang_index['pm_new_text']);
}    

if (!isset($cache_config['language_name']['conf_value']) OR $cache_config['language_name']['conf_value'] == "") $dir_scr = "Russian";
else $dir_scr = $cache_config['language_name']['conf_value'];

$img_lb_width = intval($cache_config['pic_autosize']['conf_value']);

$host = clean_url($_SERVER['HTTP_HOST']);
$parts = explode('.', $host);
if(count($parts)>1)
{
    $tld = array_pop($parts);
    $domain_js = array_pop($parts).'.'.$tld;
}
else
    $domain_js = array_pop($parts);
    
$LB_base_url = $_SERVER['QUERY_STRING'];
$LB_base_url = explode ("&", $LB_base_url);
foreach ($LB_base_url as $key => $value)
{
    if (strpos($value, "page=") !== false)
        unset($LB_base_url[$key]);
}
$LB_base_url = implode ("&", $LB_base_url);

$scripts = <<<HTML
<script language="javascript" type="text/javascript">
var LB_root         = '{$LB_root}';
var LB_skin         = '{$cache_config['template_name']['conf_value']}';
var secret_key      = '{$secret_key}';
var domain_js       = '{$domain_js}';
var img_lb_width    = '{$img_lb_width}';
var LB_base_url     = '{HOME_LINK}?{$LB_base_url}';
</script>
<span id="mini_window"></span>
<div class="confirm_window"></div>
{$pm_new}
HTML;

$minify_files = array();
$minify_files[] = "language/{$dir_scr}/board/scripts.js";
$minify_files[] = "templates/{$cache_config['template_name']['conf_value']}/js/template.js";
$minify_files[] = "components/scripts/highslide/highslide.css";

$tpl->tags( '{SCRIPTS_FILE}', minify_compression($minify_files) );
$tpl->tags( '{SCRIPTS}', $scripts );

if (count($cache_adtblock))
{
	foreach ( $cache_adtblock as $value )
    {
        $show_adt = false;
        
        if (!$value['forum_id'] AND !$value['in_posts'])
        {
            $check_group = explode (",", $value['group_access']);
            if (in_array(0, $check_group) OR in_array($member_id['user_group'], $check_group))
                $show_adt = true;
        }
        
        if ($value['active_status'] AND $show_adt)
            $tpl->copy_template = str_replace ( "{adt_" . $value['id'] . "}", $value['text'], $tpl->copy_template );
        else
            $tpl->copy_template = str_replace ( "{adt_" . $value['id'] . "}", "", $tpl->copy_template );
	}
}

$tpl->tags( '{speedbar}', speedbar($link_speddbar) );
$tpl->tags_templ( '{statistic}', $tpl->result['statistic'] );
$tpl->tags_templ( '{login}', $tpl->result['login'] );
$tpl->tags_templ( '{message}', $tpl->result['message'] );
$tpl->tags_templ( '{content}', $tpl->result['content'] );

$tpl->compile ('global_template');
$tpl->global_tags ('global_template');

echo $tpl->result['global_template'];
$tpl->global_clear ();

GzipOut();
?>