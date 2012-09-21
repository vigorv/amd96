<?php 

/*====================================================
 Author: RooTM
------------------------------------------------------
 Web-site: http://weboss.net/
=====================================================*/

@session_start();
@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -25 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );

include ENGINE_DIR . '/modules/iChat/data/config.php';
include_once ENGINE_DIR.'/modules/iChat/data/language.lng';

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/modules/iChat/ajax/add.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

$_COOKIE['dle_skin'] = trim(totranslit( $_COOKIE['dle_skin'], false, false ));

if( $_COOKIE['dle_skin'] ) {
	if( @is_dir( ROOT_DIR . '/templates/' . $_COOKIE['dle_skin'] ) ) {
		$config['skin'] = $_COOKIE['dle_skin'];
	}
}

if( $config["lang_" . $config['skin']] ) {
	
	if ( file_exists( ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng' ) ) {
		@include_once (ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng');
	} else die("Language file not found");

} else {
	
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

require_once ENGINE_DIR . '/modules/sitelogin.php';

//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

if( ! $is_logged AND $chat_cfg['allow_guest'] == 'no' ) die( "error" );

if( ! $is_logged ) $member_id['user_group'] = 5;

require_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;
$parse->allow_url = $user_group[$member_id['user_group']]['allow_url'];
$parse->allow_image = $user_group[$member_id['user_group']]['allow_image'];

$_POST['message'] = trim($_POST['message']);
$_POST['name'] = convert_unicode( $_POST['name'], $config['charset']  );
$_POST['mail'] = convert_unicode( $_POST['mail'], $config['charset'] );

$name = $db->safesql( $parse->process( trim( $_POST['name'] ) ) );

$not_allow_symbol = array ("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", "/", "¬", "#", ";", ":", "~", "[", "]", "{", "}", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', "'" );
$mail = $db->safesql(trim( str_replace( $not_allow_symbol, '', strip_tags( stripslashes( $_POST['mail'] ) ) ) ) );

$message = convert_unicode( $_POST['message'], $config['charset'] );

if(($member_id['user_group'] != 1)) {$chat_cfg['stop_bbcode'] = $chat_cfg['stop_bbcode'] . ",[admin],[/admin]";}

if( !$user_group[$member_id['user_group']]['allow_url'] ) {$chat_cfg['stop_bbcode'] = $chat_cfg['stop_bbcode'] . ",[url=,[/url],[leech=,[/leech]";}

if( ! $is_logged ) {

if( dle_strlen( $name, $config['charset'] ) > 20 ) $error = $lang['news_err_1'];

if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $name ) ) $error = $lang['reg_err_4'];

if( strlen( $mail ) > 50 ) $error = $lang['news_err_2'];

}

// Проверка является ли имя зарегистрированным
if( ! $is_logged and $error != TRUE ) {
	$db->query( "SELECT name from " . USERPREFIX . "_users where LOWER(name) = '" . strtolower( $name ) . "'" );
	
	if( $db->num_rows() > 0 ) $error = $lang['news_err_7'];
	$db->free();
}

if( empty( $name ) and $error != TRUE  and ! $is_logged ) $error = $lang['news_err_9'];

if( $mail != "" and ! $is_logged ) {
if( !preg_match( "/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $mail ) ) $error = $lang['news_err_10'];
}
		
$i = 0;
$ban_codes = explode(",",  $chat_cfg['stop_bbcode'] );
foreach($ban_codes as $ban_code){
$i++; $ban_code = trim($ban_code);
if(stristr(strtolower(stripslashes($message)), $ban_code)){
$error = $chat_lang['bad'];
break;
}}

$ban_names = explode(",",  $chat_cfg['no_access'] );
foreach($ban_names as $ban_name){
$ban_name = trim(strtolower($ban_name));
if(strtolower($name) == $ban_name OR strtolower($member_id['name']) == $ban_name){
$error = $chat_lang['no_access'];
break;
}}

if( dle_strlen( stripslashes($message), $config['charset'] ) > $chat_cfg['max_text'] ) {
	$error = $chat_lang['max'];
}

if( $_POST['message'] == '' OR $member_id['banned'] == "yes" ) {
	$error = $chat_lang['null'];
}

// Проверка защиты от флуда
if( $member_id['user_group'] > 2 and intval( $chat_cfg['stop_flood'] ) and ! $error) {
	if( flooder( $_IP, $chat_cfg['stop_flood'] ) == TRUE ) {
		$error =  $chat_lang['flood'];	
	}
}

if(md5($_POST['message']) == md5($_SESSION['last_message'])){
$error = $chat_lang['copy'];
}

if(($member_id['user_group'] == 1)) {
if (@file_exists(ROOT_DIR.'/iChat_install.php')) {
$error = $chat_lang['install_in'];
}}

if( ! $error ){
 
if( ! $is_logged ) {
set_cookie( "iChat_name", $name, 365 );
set_cookie( "iChat_mail", $mail, 365 );
}

$_SESSION['last_message'] = $_POST['message'];

$_TIME = time() + ($config['date_adjust'] * 60);
$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
$time = date( "Y-m-d H:i:s", $_TIME );

$message = $db->safesql( $parse->BB_Parse( $parse->process( $message ), false ) );

	$smilies_arr = explode( ",", $chat_cfg['smiles'] );
		foreach ( $smilies_arr as $smile ) {
			$smile = trim( $smile );
			$find[] = "':$smile:'";
			$replace[] = "<!--smile:{$smile}--><img style=\"vertical-align: middle;border: none;\" alt=\"$smile\" src=\"" . $config['http_home_url'] . "engine/data/emoticons/{$smile}.gif\" /><!--/smile-->";
		}

$message = preg_replace( $find, $replace, $message );

	//* Автоперенос длинных слов
		if( intval( $chat_cfg['max_word'] ) AND !$error ) {
			
			$message = preg_split( '((>)|(<))', $message, - 1, PREG_SPLIT_DELIM_CAPTURE );
			$n = count( $message );
			
			for($i = 0; $i < $n; $i ++) {
				if( $message[$i] == "<" ) {
					$i ++;
					continue;
				}
				
				$message[$i] = preg_replace( "#([^\s\n\r]{" . intval( $chat_cfg['max_word'] ) . "})#i", "\\1<br />", $message[$i] );
			}
			
			$message = join( "", $message );
		
		}

   
  	if( $chat_cfg['stop_flood'] ) {
			$db->query( "INSERT INTO " . PREFIX . "_flood (id, ip, flag) values ('$_TIME', '$_IP', '1')" );
		}  

  if( $is_logged ) $db->query( "INSERT INTO " . PREFIX . "_iChat (date, author, email, message, ip, user_group) values ('$time', '{$member_id['name']}', '{$member_id['email']}', '$message', '$_IP', '{$member_id['user_group']}' )" );  
              else $db->query( "INSERT INTO " . PREFIX . "_iChat (date, author, email, message, ip, user_group) values ('$time', '{$name}', '{$mail}', '$message', '$_IP', '5' )" );    

clear_cache( 'iChat_' );

}

$config['allow_cache'] = "yes";

if($_POST['place'] == 'site') $Messages = dle_cache( "iChat", $config['skin'] );
if($_POST['place'] == 'window') $Messages = dle_cache( "iChat_window", $config['skin'] );

include ENGINE_DIR . '/modules/iChat/build.php';

$_SESSION['hash_messages_'.$_POST['place']] = md5($Messages);

@header( "Content-type: text/html; charset=" . $config['charset'] );

echo $Messages;

if(!$error) $js_c = "document.getElementById('iChat_form').message.value = '';";
       else $js_c = "DLEalert('".$error."', '".$chat_lang['title']."')";

echo '<script language="JavaScript" type="text/javascript">'.$js_c.'</script>';

?>
