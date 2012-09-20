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
	
	$config['http_home_url'] = explode( "engine/modules/iChat/ajax/edit.php", $_SERVER['PHP_SELF'] );
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

if( ! $is_logged ) die( "error" );

if( !$user_group[$member_id['user_group']]['edit_allc'] ) { die ("error"); }

$id = trim($_POST['id']);

@header( "Content-type: text/html; charset=" . $config['charset'] );

if($_POST['action'] == "show" AND is_numeric($id)){

$iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

$message = sqlite_fetch_array(sqlite_query( $iChat_db, "SELECT message FROM iChat WHERE id = '$id'" ));

	//-------------------------------------------------
	//	Convert from HTML to BBCode
	//-------------------------------------------------

		$message = stripslashes( $message[0] );

		$message = preg_replace( "#<!--QuoteBegin-->(.+?)<!--QuoteEBegin-->#", '[quote]', $message );
		$message = preg_replace( "#<!--QuoteBegin ([^>]+?) -->(.+?)<!--QuoteEBegin-->#", "[quote=\\1]", $message );
		$message = preg_replace( "#<!--QuoteEnd-->(.+?)<!--QuoteEEnd-->#", '[/quote]', $message );

			$message = preg_replace( "#<!--colorstart:(.+?)-->(.+?)<!--/colorstart-->#", "[color=\\1]", $message );
			$message = str_replace( "<!--colorend--></span><!--/colorend-->", "[/color]", $message );

			$message = str_replace( "<b>", "[b]", str_replace( "</b>", "[/b]", $message ) );
			$message = str_replace( "<i>", "[i]", str_replace( "</i>", "[/i]", $message ) );
			$message = str_replace( "<u>", "[u]", str_replace( "</u>", "[/u]", $message ) );
			$message = str_replace( "<s>", "[s]", str_replace( "</s>", "[/s]", $message ) );		

	function iChat_decode_leech($url = "", $show = "") {
		
		$show = stripslashes( $show );
	
		$url = explode( "url=", $url );
		$url = end( $url );
		$url = rawurldecode( $url );
		$url = base64_decode( $url );
		$url = str_replace("&amp;","&", $url );
		
		return "[leech=" . $url . "]" . $show . "[/leech]";
	}

	$message = preg_replace( "#<!--dle_leech_begin--><a href=[\"'](http://|https://|ftp://|ed2k://|news://|magnet:)?(\S.+?)['\"].*?" . ">(.+?)</a><!--dle_leech_end-->#ie", "\iChat_decode_leech('\\1\\2', '\\3')", $message );

			$message = str_ireplace( "<br>", "\n", $message );
			$message = str_ireplace( "<br />", "\n", $message );

		$message = preg_replace( "#<!--smile:(.+?)-->(.+?)<!--/smile-->#is", ':\\1:', $message );

		$smilies_arr = explode( ",", $chat_cfg['smiles'] );
                $find[] = "'<!-- stop -->'"; $replace[] = "";
		foreach ( $smilies_arr as $smile ) {
			$smile = trim( $smile );
			$replace[] = ":$smile:";
			$find[] = "#<img style=['\"]border: none;['\"] alt=['\"]" . $smile . "['\"] align=['\"]absmiddle['\"] src=['\"](.+?)" . $smile . ".gif['\"] />#is";
		}

		$message = preg_replace( $find, $replace, $message );

	//-------------------------------------------------
	//	Convert from HTML to BBCode
	//-------------------------------------------------

echo $message;

}

if($_POST['action'] == "save" AND is_numeric($id)){

$new_message = convert_unicode( $_POST['new_message'], $config['charset'] );

if( dle_strlen( stripslashes($new_message), $config['charset'] ) > $chat_cfg['max_text'] ) $error = $chat_lang['max'];

if( trim($_POST['new_message']) == '' ) $error = $chat_lang['null'];

if( !$error ){
	//-------------------------------------------------
	//	Convert from BBCode to HTML
	//-------------------------------------------------

if( function_exists( "get_magic_quotes_gpc" ) && get_magic_quotes_gpc() ) $new_message = stripslashes( $new_message ); 

$new_message = htmlspecialchars( $new_message, ENT_QUOTES );

           $count_start = substr_count ($new_message, "[quote");
		$count_end = substr_count ($new_message, "[/quote]");

		if ($count_start AND $count_start == $count_end) {

			$new_message = preg_replace( "#\[quote\]#i", "<!--QuoteBegin--><div class=\"quote\"><!--QuoteEBegin-->", $new_message );
			$new_message = preg_replace( "#\[quote=(.+?)\]#i", "<!--QuoteBegin \\1 --><div class=\"title_quote\">{$lang['i_quote']} \\1</div><div class=\"quote\"><!--QuoteEBegin-->", $new_message );
			$new_message = preg_replace( "#\[/quote\]#i", "<!--QuoteEnd--></div><!--QuoteEEnd-->", $new_message );


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
			$new_message = preg_replace( "#\[leech\](\S.+?)\[/leech\]#ie", "\iChat_build_url(array('html' => '\\1', 'show' => '\\1'))", $new_message );
			$new_message = preg_replace( "#\[leech\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/leech\]#ie", "\iChat_build_url(array('html' => '\\1', 'show' => '\\2'))", $new_message );
			$new_message = preg_replace( "#\[leech\s*=\s*(\S.+?)\s*\](.*?)\[\/leech\]#ie", "\iChat_build_url(array('html' => '\\1', 'show' => '\\2'))", $new_message );

}

		$new_message = preg_replace( "#\[color=(.+?)\]#i", "<!--colorstart:\\1--><span style=\"color:\\1\"><!--/colorstart-->", $new_message );
		$new_message = str_replace("[/color]", "<!--colorend--></span><!--/colorend-->", $new_message);

		$new_message = str_ireplace( "[b]", "<b>", str_ireplace( "[/b]", "</b>", $new_message ) );
		$new_message = str_ireplace( "[i]", "<i>", str_ireplace( "[/i]", "</i>", $new_message ) );
		$new_message = str_ireplace( "[u]", "<u>", str_ireplace( "[/u]", "</u>", $new_message ) );
		$new_message = str_ireplace( "[s]", "<s>", str_ireplace( "[/s]", "</s>", $new_message ) );		

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
		
		$new_message = preg_replace( $find, $replace, $new_message );

$new_message = sqlite_escape_string( $new_message );

	//-------------------------------------------------
	//	Convert from BBCode to HTML
	//-------------------------------------------------

	//* Автоперенос длинных слов
		if( intval( $chat_cfg['max_word'] ) ) {
			
			$new_message = preg_split( '((>)|(<))', $new_message, - 1, PREG_SPLIT_DELIM_CAPTURE );
			$n = count( $new_message );
			
			for($i = 0; $i < $n; $i ++) {
				if( $new_message[$i] == "<" ) {
					$i ++;
					continue;
				}
				
				$new_message[$i] = preg_replace( "#([^\s\n\r]{" . intval( $chat_cfg['max_word'] ) . "})#i", "\\1<br />", $new_message[$i] );
			}
			
			$new_message = join( "", $new_message );
		
		}

$iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

sqlite_query( $iChat_db, "UPDATE iChat SET message='$new_message' WHERE id = '$id'" );

	//-------------------------------------------------
	//	Очищаем кэш
	//-------------------------------------------------

$fdir = opendir( ENGINE_DIR . '/modules/iChat/data/cache' );
	
while ( $file = readdir( $fdir ) ) {
if( $file != '.' and $file != '..' and $file != '.htaccess' ) @unlink( ENGINE_DIR . '/modules/iChat/data/cache/' . $file );	
}

}

$config['allow_cache'] = "yes";

$_POST['page'] = ( $_SESSION['page'] >= 1 ) ? $_SESSION['page'] : 1;

include ENGINE_DIR . '/modules/iChat/build.php';

if($_POST['place'] != "history") $_SESSION['hash_messages_'.$_POST['place']] = md5($compiled_messages);

echo $compiled_messages;

}

?>