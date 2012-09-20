<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 Copyright (c) 2004,2012 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: index.php
-----------------------------------------------------
 Назначение: Главная страница
=====================================================
*/

@session_start ();
@ob_start ();
@ob_implicit_flush ( 0 );

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define ( 'DATALIFEENGINE', true );

$member_id = FALSE;
$is_logged = FALSE;

define ( 'ROOT_DIR', dirname ( __FILE__ ) );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

require_once ROOT_DIR . '/engine/init.php';

if (clean_url ( $_SERVER['HTTP_HOST'] ) != clean_url ( $config['http_home_url'] )) {
	
	$replace_url = array ();
	$replace_url[0] = clean_url ( $config['http_home_url'] );
	$replace_url[1] = clean_url ( $_SERVER['HTTP_HOST'] );

} else
	$replace_url = false;

//forum
include ENGINE_DIR . '/modules/logicboard/last_topics.php';
include ENGINE_DIR . '/modules/logicboard/online_block.php';
///forum


$tpl->load_template ( 'main.tpl' );

//tizer
require_once ENGINE_DIR.'/modules/rumedia/tizer.php';
///tizer

//forum
$tpl->set ( '{last_topics}', $logicboard_topics );
$tpl->set ( '{online_block}', $logicboard_online_block );
///forum

$tpl->set ( '{calendar}', $tpl->result['calendar'] );
$tpl->set ( '{archives}', $tpl->result['archive'] );
$tpl->set ( '{tags}', $tpl->result['tags_cloud'] );
$tpl->set ( '{vote}', $tpl->result['vote'] );
$tpl->set ( '{topnews}', $tpl->result['topnews'] );
$tpl->set ( '{login}', $tpl->result['login_panel'] );
$tpl->set ( '{info}',  $tpl->result['info'] );
$tpl->set ( '{speedbar}', $tpl->result['speedbar'] );

if ($config['allow_skin_change'] == "yes") $tpl->set ( '{changeskin}', ChangeSkin ( ROOT_DIR . '/templates', $config['skin'] ) );

if (count ( $banners ) and $config['allow_banner']) {
	
	foreach ( $banners as $name => $value ) {
		$tpl->copy_template = str_replace ( "{banner_" . $name . "}", $value, $tpl->copy_template );
	}

}

$tpl->set_block ( "'{banner_(.*?)}'si", "" );

if (count ( $informers ) and $config['rss_informer']) {
	foreach ( $informers as $name => $value ) {
		$tpl->copy_template = str_replace ( "{inform_" . $name . "}", $value, $tpl->copy_template );
	}
}

if ($allow_active_news AND $config['allow_change_sort'] AND $do != "userinfo") {
	
	$tpl->set ( '[sort]', "" );
	$tpl->set ( '{sort}', news_sort ( $do ) );
	$tpl->set ( '[/sort]', "" );

} else {
	
	$tpl->set_block ( "'\\[sort\\](.*?)\\[/sort\\]'si", "" );

}

if ($dle_module == "showfull" ) {

	if (is_array($cat_list) AND count($cat_list) > 1 ) $category_id = implode(",", $cat_list);

}

if (stripos ( $tpl->copy_template, "[category=" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\[category=(.+?)\\](.*?)\\[/category\\]#ies", "check_category('\\1', '\\2', '{$category_id}')", $tpl->copy_template );
}

if (stripos ( $tpl->copy_template, "[not-category=" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\[not-category=(.+?)\\](.*?)\\[/not-category\\]#ies", "check_category('\\1', '\\2', '{$category_id}', false)", $tpl->copy_template );
}


if (stripos ( $tpl->copy_template, "[static=" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\[static=(.+?)\\](.*?)\\[/static\\]#ies", "check_static('\\1', '\\2')", $tpl->copy_template );
}

if (stripos ( $tpl->copy_template, "[not-static=" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\[not-static=(.+?)\\](.*?)\\[/not-static\\]#ies", "check_static('\\1', '\\2', false)", $tpl->copy_template );
}

if (stripos ( $tpl->copy_template, "{custom" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\{custom category=['\"](.+?)['\"] template=['\"](.+?)['\"] aviable=['\"](.+?)['\"] from=['\"](.+?)['\"] limit=['\"](.+?)['\"] cache=['\"](.+?)['\"]\\}#ies", "custom_print('\\1', '\\2', '\\3', '\\4', '\\5', '\\6', '{$dle_module}')", $tpl->copy_template );
}

$config['http_home_url'] = explode ( "index.php", strtolower ( $_SERVER['PHP_SELF'] ) );
$config['http_home_url'] = reset ( $config['http_home_url'] );

if (! $user_group[$member_id['user_group']]['allow_admin']) $config['admin_path'] = "";

$ajax .= <<<HTML
<div id="loading-layer" style="display:none"><div id="loading-layer-text">{$lang['ajax_info']}</div></div>{$pm_alert}
<script type="text/javascript">
<!--
var dle_root       = '{$config['http_home_url']}';
var dle_admin      = '{$config['admin_path']}';
var dle_login_hash = '{$dle_login_hash}';
var dle_group      = {$member_id['user_group']};
var dle_skin       = '{$config['skin']}';
var dle_wysiwyg    = '{$config['allow_comments_wysiwyg']}';
var quick_wysiwyg  = '{$config['allow_quick_wysiwyg']}';
var dle_act_lang   = ["{$lang['p_yes']}", "{$lang['p_no']}", "{$lang['p_enter']}", "{$lang['p_cancel']}", "{$lang['p_save']}"];
var menu_short     = '{$lang['menu_short']}';
var menu_full      = '{$lang['menu_full']}';
var menu_profile   = '{$lang['menu_profile']}';
var menu_send      = '{$lang['menu_send']}';
var menu_uedit     = '{$lang['menu_uedit']}';
var dle_info       = '{$lang['p_info']}';
var dle_confirm    = '{$lang['p_confirm']}';
var dle_prompt     = '{$lang['p_prompt']}';
var dle_req_field  = '{$lang['comm_req_f']}';
var dle_del_agree  = '{$lang['news_delcom']}';
var dle_complaint  = '{$lang['add_to_complaint']}';
var dle_big_text   = '{$lang['big_text']}';
var dle_orfo_title = '{$lang['orfo_title']}';
var dle_p_send     = '{$lang['p_send']}';
var dle_p_send_ok  = '{$lang['p_send_ok']}';
var dle_save_ok    = '{$lang['n_save_ok']}';
var dle_del_news   = '{$lang['news_delnews']}';\n
HTML;

if ($user_group[$member_id['user_group']]['allow_all_edit']) {
	
	$ajax .= <<<HTML
var dle_notice     = '{$lang['btn_notice']}';
var dle_p_text     = '{$lang['p_text']}';
var dle_del_msg    = '{$lang['p_message']}';
var allow_dle_delete_news   = true;\n
HTML;

} else {
	
	$ajax .= <<<HTML
var allow_dle_delete_news   = false;\n
HTML;

}

if ($config['fast_search'] AND $user_group[$member_id['user_group']]['allow_search']) {

	$ajax .= <<<HTML
var dle_search_delay   = false;
var dle_search_value   = '';
$(function(){
	FastSearch();
});
HTML;

}

if (strpos ( $tpl->result['content'], "<pre><code>" ) !== false) {

	$js_array[] = "engine/classes/highlight/highlight.code.js";

	$ajax .= <<<HTML

$(function(){
	$('pre code').each(function(i, e) {hljs.highlightBlock(e, null)});
});
HTML;

}

$ajax .= <<<HTML
//-->
</script>
HTML;

if (strpos ( $tpl->result['content'], "hs.expand" ) !== false or strpos ( $tpl->copy_template, "hs.expand" ) !== false) {
	
	if ($config['thumb_dimming']) $dimming = "hs.dimmingOpacity = 0.60;"; else $dimming = "";

	if ($config['thumb_gallery'] AND ($dle_module == "showfull" OR $dle_module == "static") ) {

	$gallery = "
	hs.align = 'center';
	hs.transitions = ['expand', 'crossfade'];
	hs.addSlideshow({
		interval: 4000,
		repeat: false,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			opacity: .75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
	});";

	} else {

		$gallery = "";

	}

	$js_array[] = "engine/classes/highslide/highslide.js";

	switch ( $config['outlinetype'] ) {

		case 1 :
			$type = "hs.wrapperClassName = 'wide-border';";
			break;

		case 2 :
			$type = "hs.wrapperClassName = 'borderless';";
			break;

		case 3 :
			$type = "hs.wrapperClassName = 'less';\nhs.outlineType = null;";
			break;
	
		default :
			$type = "hs.outlineType = 'rounded-white';";
			break;


	}
	
	$ajax .= <<<HTML
<script type="text/javascript">  
<!--  
	hs.graphicsDir = '{$config['http_home_url']}engine/classes/highslide/graphics/';
	{$type}
	hs.numberOfImagesToPreload = 0;
	hs.showCredits = false;
	{$dimming}
	hs.lang = {
		loadingText :     '{$lang['loading']}',
		playTitle :       '{$lang['thumb_playtitle']}',
		pauseTitle:       '{$lang['thumb_pausetitle']}',
		previousTitle :   '{$lang['thumb_previoustitle']}',
		nextTitle :       '{$lang['thumb_nexttitle']}',
		moveTitle :       '{$lang['thumb_movetitle']}',
		closeTitle :      '{$lang['thumb_closetitle']}',
		fullExpandTitle : '{$lang['thumb_expandtitle']}',
		restoreTitle :    '{$lang['thumb_restore']}',
		focusTitle :      '{$lang['thumb_focustitle']}',
		loadingTitle :    '{$lang['thumb_cancel']}'
	};
	{$gallery}
//-->
</script>
HTML;

}

$js_array = build_js($js_array, $config);

if ($allow_comments_ajax AND ($config['allow_comments_wysiwyg'] == "yes" OR $config['allow_quick_wysiwyg'])) {
	$lang['wysiwyg_language'] = totranslit( $lang['wysiwyg_language'], false, false );
	$js_array .="\n<script type=\"text/javascript\" src=\"{$config['http_home_url']}engine/editor/scripts/language/{$lang['wysiwyg_language']}/editor_lang.js\"></script>";
	$js_array .="\n<script type=\"text/javascript\" src=\"{$config['http_home_url']}engine/editor/scripts/innovaeditor.js\"></script>";
}

if ($config['allow_admin_wysiwyg'] == "yes" OR $config['allow_site_wysiwyg'] == "yes" OR $config['allow_static_wysiwyg'] == "yes") {
	$js_array .="\n<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js\"></script>";
	$js_array .="\n<script type=\"text/javascript\" src=\"{$config['http_home_url']}engine/editor/scripts/webfont.js\"></script>";
	$js_array .="\n<link media=\"screen\" href=\"{$config['http_home_url']}engine/editor/css/default.css\" type=\"text/css\" rel=\"stylesheet\" />";
}

//Right menu
if(file_exists(ENGINE_DIR.'/cache/cat_menu_right.tmp'))
	{
	$cat_menu=file_get_contents(ENGINE_DIR.'/cache/cat_menu_right.tmp');
	}
else
	{
	$sql_result = $db->query("SELECT id,alt_name,name,parentid FROM " . PREFIX . "_category WHERE parentid=0 ORDER BY posi");
	$cat_menu ='<div>';
	$cat_menu.='<ul id="theMenu">';
	$cat_menu.='<li><a href="http://flux.anka.ws/#rum">Главная</a></li>';
	$arrow = '';
	while($row = $db->get_row($sql_result))
		{  
		$res = $db->query("SELECT id,alt_name,name,parentid FROM " . PREFIX . "_category WHERE parentid='".$row['id']."' ORDER BY posi");
		if ($db->num_rows($res)>=1)
			{
				$arrow = '';
				$row['name'] = str_replace ("&","and",$row['name']);
				$cat_menu.='<li><a href="#" id="rm'.$row['id'].'">'.$row['name'].' [+]</a>';
			} 
		else 
			{
				$row['name'] = str_replace ("&","and",$row['name']);
				//Без подкатегорий
				$cat_menu.='<li><a href="http://flux.anka.ws/'.$row['alt_name'].'/#rum">'.$row['name'].'</a>';
			}
		#$cat_menu.='<!--[if lte IE 6]><table><tr><td><![endif]-->';
		$cat_menu.='<ul>';
					if ($row['parentid'] == 0 AND $db->num_rows($res) <> 0) 
						{
							$cat_menu.='<li> <a href="http://flux.anka.ws/'.$row['alt_name'].'/#rum">Все записи из категории</a></li>';
						}
		while($row2 = $db->get_row($res))
			{
			//Строение подкатегорий
			$row2['name'] = str_replace ("&","and",$row2['name']);
			$cat_menu.='<li> <a href="http://flux.anka.ws/'.$row2['alt_name'].'/#rum">'.$row2['name'].'</a></li>';
			}
		$cat_menu.='</ul>';
		#$cat_menu.='<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
		$cat_menu.='</li>';    
		}
#	$cat_menu.='<li><a href="'.$config['http_home_url'].'feedback.html" >'."Обратная связь"."</a></li>";
#	$cat_menu.='<li><a href="'.$config['http_home_url'].'statistics.html" >'."Статистика"."</a></li>";
#	$cat_menu.='<li><a href="'.$config['http_home_url'].'rules.html" >'."Правила"."</a></li>";
#	$cat_menu.='<li><a href="'.$config['http_home_url'].'yasitemap" >'."Карта сайта"."</a></li>";
	$cat_menu.='</ul>';
	$cat_menu.='</div>';
	$cat_menu = str_replace ("<ul></ul>","",$cat_menu);
	file_put_contents(ENGINE_DIR.'/cache/cat_menu_right.tmp',$cat_menu);
	}
///Right menu

$tpl->set('{cat_menu}', $cat_menu);

$tpl->set ( '{AJAX}', $ajax );
$tpl->set ( '{headers}', $metatags."\n".$js_array );

$tpl->set ( '{content}', "<div id='dle-content'>" . $tpl->result['content'] . "</div>" );

$tpl->compile ( 'main' );
$tpl->result['main'] = str_ireplace( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $tpl->result['main'] );
if ($replace_url) $tpl->result['main'] = str_replace ( $replace_url[0]."/", $replace_url[1]."/", $tpl->result['main'] );
$tpl->result['main'] = str_replace ('#<img src=\"/uploads\/posts\/(.*?)#i','$1', $tpl->result['main'] );

#$tpl->result['main'] = preg_replace('<img src="/uploads/posts/(.*?)', '<img src="http://data2.rumedia.ws/uploads/posts/$1', $tpl->result['main']);

#echo $tpl->result['main'];
eval (' ?' . '>' . $tpl->result['main'] . '<' . '?php ');
$tpl->global_clear ();
$db->close ();

GzipOut();
?>