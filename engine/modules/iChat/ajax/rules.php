<?php 

/*====================================================
=====================================================*/

@session_start();
@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -25 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );

include ENGINE_DIR . '/data/config.php';
include_once ENGINE_DIR.'/modules/iChat/data/language.lng';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/modules/iChat/ajax/rules.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/templates.class.php';

$tpl = new dle_template ( );
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'] . '/iChat/';
define ( 'TEMPLATE_DIR', $tpl->dir );	

$tpl->load_template ( 'rules.tpl' );

$tpl->compile ( 'rules' );

$rules = $tpl->result['rules'];

$tpl->global_clear ();

@header( "Content-type: text/html; charset=" . $config['charset'] );

echo "<div id='rules' title='{$chat_lang['rules']}' style='display:none'>{$rules}</div>";

?>
