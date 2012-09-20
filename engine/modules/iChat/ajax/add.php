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

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest") die('Only for AJAX requests!');

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

if( ! $is_logged ) {

$member_id['user_group'] = 5;

$_POST['name'] = convert_unicode( $_POST['name'], $config['charset']  );
$_POST['mail'] = convert_unicode( $_POST['mail'], $config['charset'] );

$not_allow_symbol = array ("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", "/", "¬", "#", ";", ":", "~", "[", "]", "{", "}", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', "'" );
$mail = trim( str_replace( $not_allow_symbol, '', strip_tags( stripslashes( $_POST['mail'] ) ) ) );

if( strlen( $mail ) > 50 ) $error = $lang['news_err_2'];

if( $mail != "" ) {
if( !preg_match( "/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $mail ) ) $error = $lang['news_err_10'];
}

$name =  trim( $_POST['name'] );

if( dle_strlen( $name, $config['charset'] ) > 20 ) $error = $lang['news_err_1'];

if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $name ) ) $error = $lang['reg_err_4'];

if( empty( $name ) and $error != TRUE ) $error = $lang['news_err_9'];

// Проверка является ли имя зарегистрированным
if( $error != TRUE ) {
	$db->query( "SELECT name from " . USERPREFIX . "_users where LOWER(name) = '" . strtolower( $name ) . "'" );
	
	if( $db->num_rows() > 0 ) $error = $lang['news_err_7'];
	$db->free();
}

$member_id['foto'] = '';
$member_id['user_group'] = 5;
$member_id['name'] = sqlite_escape_string(htmlspecialchars($name));
$member_id['email'] = sqlite_escape_string(htmlspecialchars($mail));

if( ! $error ){
set_cookie( "iChat_name", $member_id['name'], 365 );
set_cookie( "iChat_mail", $member_id['email'], 365 );
}

}

$message = convert_unicode( trim($_POST['message']), $config['charset'] );
	
preg_match_all( '/:(.*?):/is' , $message , $smilies_in_msg );

if( count($smilies_in_msg[0]) > $chat_cfg['max_smilies'] )$error = $chat_lang['max_smilies'];
	
if ($is_logged AND in_array($member_id['name'], explode(",",  $chat_cfg['no_access'] ))) $error = $chat_lang['no_access'];

if( dle_strlen( stripslashes($message), $config['charset'] ) > $chat_cfg['max_text'] ) $error = $chat_lang['max'];

if( $message == '' OR $member_id['banned'] == "yes" ) $error = $chat_lang['null'];

if(md5($message) == $_SESSION['last_message_hash']) $error = $chat_lang['copy'];

if( ! $iChat_db ) $iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

// Проверка защиты от флуда
if( $member_id['user_group'] > 2 and intval( $chat_cfg['stop_flood'] ) and ! $error) {
		$this_time = time() + ($config['date_adjust'] * 60) - $chat_cfg['stop_flood'];
		sqlite_query($iChat_db, "DELETE FROM flood where date < '$this_time'" );
		
		$row = sqlite_fetch_array(sqlite_query($iChat_db, "SELECT COUNT(*) as count FROM flood WHERE ip = '$_IP'"));
		
		if( $row['count'] ) $error =  $chat_lang['flood'];
}

if( ! $error ){
 
$_SESSION['last_message_hash'] = md5($message);

$_TIME = time() + ($config['date_adjust'] * 60);
$_IP = sqlite_escape_string( $_SERVER['REMOTE_ADDR'] );
$time = date( "Y-m-d H:i:s", $_TIME );

	//-------------------------------------------------
	//	Convert from BBCode to HTML
	//-------------------------------------------------

if( function_exists( "get_magic_quotes_gpc" ) && get_magic_quotes_gpc() ) $message = stripslashes( $message ); 

$message = htmlspecialchars( $message, ENT_QUOTES );

           $count_start = substr_count ($message, "[quote");
		$count_end = substr_count ($message, "[/quote]");

		if ($count_start AND $count_start == $count_end) {

			$message = preg_replace( "#\[quote\]#i", "<!--QuoteBegin--><div class=\"quote\"><!--QuoteEBegin-->", $message );
			$message = preg_replace( "#\[quote=(.+?)\]#i", "<!--QuoteBegin \\1 --><div class=\"title_quote\">{$lang['i_quote']} \\1</div><div class=\"quote\"><!--QuoteEBegin-->", $message );
			$message = preg_replace( "#\[/quote\]#i", "<!--QuoteEnd--></div><!--QuoteEEnd-->", $message );


		}

 if( $user_group[$member_id['user_group']]['allow_url'] ){

	function iChat_clear_url($url) {
		
		$url = strip_tags( trim( stripslashes( $url ) ) );
		
		$url = str_replace( '\"', '"', $url );
		$url = str_replace( "'", "", $url );
		$url = str_replace( '"', "", $url );
		
		$url = str_ireplace( "document.cookie", "d&#111;cument.cookie", $url );
		$url = str_replace( " ", "%20", $url );
		$url = str_replace( "<", "&#60;", $url );
		$url = str_replace( ">", "&#62;", $url );
		$url = preg_replace( "/javascript:/i", "j&#097;vascript:", $url );
		$url = preg_replace( "/data:/i", "d&#097;ta:", $url );
		
		return $url;
	
	}

	function iChat_check_home($url) {
		global $config;
		
		$value = str_replace( "http://", "", $config['http_home_url'] );
		$value = str_replace( "www.", "", $value );
		$value = explode( '/', $value );
		$value = reset( $value );
		if( $value == "" ) return false;
		
		if( strpos( $url, $value ) === false ) return false;
		else return true;
	}

	function iChat_build_url($url = array()) {
		global $config, $member_id, $user_group;
		
		if( preg_match( "/([\.,\?]|&#33;)$/", $url['show'], $match ) ) {
			$url['end'] .= $match[1];
			$url['show'] = preg_replace( "/([\.,\?]|&#33;)$/", "", $url['show'] );
		}
		
		$url['html'] = iChat_clear_url( $url['html'] );
		$url['show'] = stripslashes( $url['show'] );

			$url['show'] = str_replace( "&nbsp;", " ", $url['show'] );
	
			if (strlen(trim($url['show'])) < 3 )
				return "[url=" . $url['html'] . "]" . $url['show'] . "[/url]";
		
		if( strpos( $url['html'], $config['http_home_url'] ) !== false AND strpos( $url['html'], $config['admin_path'] ) !== false ) {
			
			return "[url=" . $url['html'] . "]" . $url['show'] . "[/url]";
		
		}
		
		if( ! preg_match( "#^(http|news|https|ed2k|ftp|aim|mms)://|(magnet:?)#", $url['html'] ) AND $url['html'][0] != "/" AND $url['html'][0] != "#") {
			$url['html'] = 'http://' . $url['html'];
		}

		if ($url['html'] == 'http://' )
			return "[url=" . $url['html'] . "]" . $url['show'] . "[/url]";
		
		$url['show'] = str_replace( "&amp;amp;", "&amp;", $url['show'] );
		$url['show'] = preg_replace( "/javascript:/i", "javascript&#58; ", $url['show'] );
		
		if( iChat_check_home( $url['html'] ) OR $url['html'][0] == "/" OR $url['html'][0] == "#") $target = "";
		else $target = "target=\"_blank\"";
			
			$url['html'] = $config['http_home_url'] . "engine/go.php?url=" . rawurlencode( base64_encode( $url['html'] ) );
			
			return "<!--dle_leech_begin--><a href=\"" . $url['html'] . "\" " . $target . ">" . $url['show'] . "</a><!--dle_leech_end-->" . $url['end'];
			
	}
	
			$message = preg_replace( "#\[leech\](\S.+?)\[/leech\]#ie", "\iChat_build_url(array('html' => '\\1', 'show' => '\\1'))", $message );
			$message = preg_replace( "#\[leech\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/leech\]#ie", "\iChat_build_url(array('html' => '\\1', 'show' => '\\2'))", $message );
			$message = preg_replace( "#\[leech\s*=\s*(\S.+?)\s*\](.*?)\[\/leech\]#ie", "\iChat_build_url(array('html' => '\\1', 'show' => '\\2'))", $message );

}

		$message = preg_replace( "#\[color=(.+?)\]#i", "<!--colorstart:\\1--><span style=\"color:\\1\"><!--/colorstart-->", $message );
		$message = str_replace("[/color]", "<!--colorend--></span><!--/colorend-->", $message);

		$message = str_ireplace( "[b]", "<b>", str_ireplace( "[/b]", "</b>", $message ) );
		$message = str_ireplace( "[i]", "<i>", str_ireplace( "[/i]", "</i>", $message ) );
		$message = str_ireplace( "[u]", "<u>", str_ireplace( "[/u]", "</u>", $message ) );
		$message = str_ireplace( "[s]", "<s>", str_ireplace( "[/s]", "</s>", $message ) );		

                $find = array("'{'", "'}'", "'\['", "']'");
                $replace = array("{<!-- stop -->", "<!-- stop -->}", "[<!-- stop -->", "<!-- stop -->]");

			$find[] = "'\r'";
			$replace[] = "";
			$find[] = "'\n'";
			$replace[] = "<br />";

		$smilies_arr = explode( ",", $chat_cfg['smiles'] );
		foreach ( $smilies_arr as $smile ) {
			$smile = trim( $smile );
			$find[] = "':$smile:'";
			$replace[] = "<!--smile:{$smile}--><img style=\"vertical-align: middle;border: none;\" alt=\"$smile\" src=\"" . $config['http_home_url'] . $chat_cfg['path_smiles']."/{$smile}.gif\" /><!--/smile-->";
		}
		
		$message = preg_replace( $find, $replace, $message );

$message = sqlite_escape_string( $message );

	//-------------------------------------------------
	//	Convert from BBCode to HTML
	//-------------------------------------------------

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

   
if( $member_id['user_group'] > 2 and intval( $chat_cfg['stop_flood'] ) ) sqlite_query($iChat_db, "INSERT INTO flood (date, ip) values ('$_TIME', '$_IP')" );

if( ! $iChat_db ) $iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

sqlite_query($iChat_db, "INSERT INTO iChat (date, foto, author, email, message, ip, user_group) values ('$time', '{$member_id['foto']}', '{$member_id['name']}', '{$member_id['email']}', '$message', '$_IP', '{$member_id['user_group']}' )" );  
 
	//-------------------------------------------------
	//	Очищаем кэш
	//-------------------------------------------------

$fdir = opendir( ENGINE_DIR . '/modules/iChat/data/cache' );
	
while ( $file = readdir( $fdir ) ) {
if( $file != '.' and $file != '..' and $file != '.htaccess' ) @unlink( ENGINE_DIR . '/modules/iChat/data/cache/' . $file );	
}

}

$config['allow_cache'] = "yes";

include ENGINE_DIR . '/modules/iChat/build.php';

$_SESSION['hash_messages_'.$_POST['place']] = md5($compiled_messages);

@header( "Content-type: text/html; charset=" . $config['charset'] );

echo $compiled_messages;

if(!$error) $js_c = "document.getElementById('iChat_form').message.value = '';";
       else $js_c = "DLEalert('".$error."', '".$chat_lang['title']."')";

echo '<script language="JavaScript" type="text/javascript">'.$js_c.'</script>';

?>
