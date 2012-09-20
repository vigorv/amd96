<?php

/*====================================================
 Author: RooTM
------------------------------------------------------
 Web-site: http://weboss.net/
=====================================================*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

include ENGINE_DIR . '/modules/iChat/data/config.php';
include_once ENGINE_DIR.'/modules/iChat/data/language.lng';

$is_change = false;

if ($config['allow_cache'] != "yes") { $config['allow_cache'] = "yes"; $is_change = true;}

if($_POST['place'] != 'window') $_POST['place'] = 'site';

include ENGINE_DIR . '/modules/iChat/build.php';

$_SESSION['user_group'] = $member_id['user_group'];

$_SESSION['hash_messages_'.$_POST['place']] = md5($compiled_messages);

echo "\n\n<!-- iChat v.7.0.1-->\n\n";

if( ! $is_logged ) $chat_cfg['refresh'] = $chat_cfg['guest_refresh'];

echo <<<HTML
<script type="text/javascript">
<!--
var iChat_cfg = ["{$chat_cfg['max_text']}", "{$chat_cfg['refresh']}"];

var iChat_lang = ["{$chat_lang['edit_msg']}", "{$chat_lang['new_text_msg']}", "{$chat_lang['title']}", "{$chat_lang['null']}", "{$chat_lang['max']}", "{$chat_lang['rules_accept']}", "{$chat_lang['save']}", "{$chat_lang['clear']}", "{$chat_lang['updates']}"];

function reFreshiChat()
{
iChatRefresh('{$_POST['place']}');
return false;
};

setInterval(reFreshiChat , iChat_cfg[1]*1000);
//-->
</script>
HTML;

require_once ENGINE_DIR . '/classes/templates.class.php';

$tpl = new dle_template ( );
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'] . '/iChat';
define ( 'TEMPLATE_DIR', $tpl->dir );

if($_POST['place'] != 'window') $tpl->load_template ( 'skin.tpl' );
else $tpl->load_template ( 'window_skin.tpl' );

if( $user_group[$member_id['user_group']]['allow_url'] ) $tpl->copy_template = preg_replace( "'\[allow_url\](.*?)\[/allow_url\]'si", "\\1", $tpl->copy_template );
         else $tpl->copy_template = preg_replace ( "'\[allow_url\](.*?)\[/allow_url\]'si", "", $tpl->copy_template );

if( !$is_logged AND $chat_cfg['allow_guest'] == 'no' ) $tpl->copy_template = preg_replace( "'\[no_access\](.*?)\[/no_access\]'si", "\\1", $tpl->copy_template );
         else $tpl->copy_template = preg_replace ( "'\[no_access\](.*?)\[/no_access\]'si", "", $tpl->copy_template );

if( $is_logged OR $chat_cfg['allow_guest'] == 'yes' ) $tpl->copy_template = preg_replace( "'\[editor_form\](.*?)\[/editor_form\]'si", "\\1", $tpl->copy_template );
         else $tpl->copy_template = preg_replace ( "'\[editor_form\](.*?)\[/editor_form\]'si", "", $tpl->copy_template );

if( !$is_logged AND $chat_cfg['allow_guest'] == 'yes' ){
$tpl->set ( '{name}', ( isset($_COOKIE['iChat_name']) ) ? $_COOKIE['iChat_name'] : $chat_lang['name'] );
$tpl->set ( '{mail}', ( isset($_COOKIE['iChat_mail']) ) ? $_COOKIE['iChat_mail'] : $chat_lang['mail'] );
$tpl->set ( '{def_name}', $chat_lang['name']);
$tpl->set ( '{def_mail}', $chat_lang['mail'] );
}

$tpl->set ( '{messages}', $compiled_messages );
$tpl->set ( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'] . '/iChat' );

$tpl->compile ( 'skin' );

echo $tpl->result['skin'];

$tpl->global_clear ();

echo "\n\n<!-- iChat v.7.0.1 -->\n\n";

if ($is_change) $config['allow_cache'] = false;

?>