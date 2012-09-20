<?php 

/*====================================================
 Author: RooTM
------------------------------------------------------
 Web-site: http://weboss.net/
=====================================================*/

if( ! defined( 'DATALIFEENGINE' ) ) die( "Hacking attempt!" );

switch ( $_POST['place'] ) {
	
	case "site" :
		$compiled_messages = dle_cache( "../modules/iChat/data/cache/site", $config['skin'] );
		break;

	case "window" :
		$compiled_messages = dle_cache( "../modules/iChat/data/cache/window", $config['skin'] );
		break;
	
	case "history" :
           $_POST['page'] = ( $_POST['page'] >= 1 ) ? $_POST['page'] : 1;
		$compiled_messages = dle_cache( "../modules/iChat/data/cache/history_".$_POST['page'], $config['skin'] );
		break;

      default :
           die("Incorrect cache name!");

}

if( $compiled_messages === false ) {

function ShowDate($format, $time_add) {
global $langdate, $config, $chat_lang;
$today = strtotime(date("Y-m-d.", time()+ ($config['date_adjust']*60)));
if ($time_add > $today) return $chat_lang['today'] . date ("H:i:s", $time_add);
elseif ($time_add > ($today - 86400)) return $chat_lang['yesterday'] . date ("H:i:s", $time_add);
else return @strtr(@date($format, $time_add), $langdate);
}

	//-------------------------------------------------
	//	Загружаем шаблон сообщений
	//-------------------------------------------------

switch ( $_POST['place'] ) {
	
	case "site" :
          $message_tpl = file_get_contents( ROOT_DIR . '/templates/' . $config['skin'] . '/iChat/message.tpl' );
		break;

	case "window" :
          $message_tpl = file_get_contents( ROOT_DIR . '/templates/' . $config['skin'] . '/iChat/window_message.tpl' );
		break;
	
	case "history" :
          $message_tpl = file_get_contents( ROOT_DIR . '/templates/' . $config['skin'] . '/iChat/history_message.tpl' );
		break;

}

if( ! $iChat_db ) $iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

$start_from = ($_POST['place'] != 'history') ? 0 : ($chat_cfg['sum_msg_history']*$_POST['page'])-$chat_cfg['sum_msg_history'];
$sum = ($_POST['place'] != 'history') ? $chat_cfg['sum_msg'] : $chat_cfg['sum_msg_history']+1;

$query = sqlite_query($iChat_db, "SELECT * FROM iChat ORDER BY date DESC LIMIT {$start_from},{$sum}");

$i = 0;

while ( $row = sqlite_fetch_array($query) ) {
 
if( $_POST['place'] == 'history' AND $i == $chat_cfg['sum_msg_history'] ) break;
	
preg_match( '/group_'.$row['user_group'].':(.*?),/is' , $chat_cfg['groups_color'].',' , $color );

$color = trim($color[1]);

if( $row['user_group'] == '5' ){

$author = "<a href=\"mailto:".$row['email']."\"><span style=\"color:".$color."\">".$row['author']."</span></a>";

}else{

if( $config['allow_alt_url'] == "yes" ) $go_page = $config['http_home_url'] . "user/" . urlencode( $row['author'] ) . "/";
	 else $go_page = "$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['author'] );
			
$author = "<a onclick=\"ShowProfile('" . urlencode( $row['author'] ) . "', '" . $go_page . "'); return false;\" href=\"" . $go_page . "\"><span style=\"color:".$color."\">" . $row['author'] . "</span> </a>";

}

$row['foto']  = ($row['foto'] == '') ? 'templates/' . $config['skin'] . '/images/noavatar.png' : 'uploads/fotos/'.$row['foto'];

$find = array();
$replace = array();

$find[] = '{id}';
$replace[] = $row[id];

$find[] = '{date}';
$replace[] = ShowDate($chat_cfg['format_date'],strtotime($row['date']));

$find[] = '{foto}';
$replace[] = $config['http_home_url'] . $row['foto'];

$find[] = '{author}';
$replace[] = $author;

$find[] = '{name}';
$replace[] = $row['author'];

$find[] = '{THEME}';
$replace[] = $config['http_home_url'] . 'templates/' . $config['skin'] . '/iChat';

$find[] = '{message}';
$replace[] = $row[message];

$compiled_messages .= str_replace( $find, $replace, $message_tpl );

$i++;

    }
	
if( ! $compiled_messages ) $compiled_messages = $chat_lang['no_messages'];

if($_POST['place'] == 'history'){

$new_record = '<center><input class="button" style="font-size: 11px;" title="'.$chat_lang['new_record'].'" onclick="iChatHistory('.($_POST['page']-1).'); return false;" type="button" value="'.$chat_lang['new_record'].'" /></center><br/>';
$previous_record = '<br/><center><input class="button" style="font-size: 11px;" title="'.$chat_lang['previous_record'].'" onclick="iChatHistory('.($_POST['page']+1).'); return false;" type="button" value="'.$chat_lang['previous_record'].'" /></center>';

if($_POST['page'] > 1) $compiled_messages = $new_record.$compiled_messages;
if($i == $chat_cfg['sum_msg_history']) $compiled_messages = $compiled_messages.$previous_record;

}
  
switch ( $_POST['place'] ) {
	
	case "site" :
		create_cache( "../modules/iChat/data/cache/site", $compiled_messages, $config['skin'] );
		break;

	case "window" :
		create_cache( "../modules/iChat/data/cache/window", $compiled_messages, $config['skin'] );
		break;
	
	case "history" :
		create_cache( "../modules/iChat/data/cache/history_".$_POST['page'], $compiled_messages, $config['skin'] );
		break;

}

}

if( $member_id['user_group'] != 5 OR $chat_cfg['allow_guest'] == 'yes' ) $compiled_messages = preg_replace( "'\[allow_reply\](.*?)\[/allow_reply\]'si", "\\1", $compiled_messages );
      else $compiled_messages = preg_replace ( "'\[allow_reply\](.*?)\[/allow_reply\]'si", "", $compiled_messages );

if( $user_group[$member_id['user_group']]['edit_allc'] ) $compiled_messages = preg_replace( "'\[allow_edit\](.*?)\[/allow_edit\]'si", "\\1", $compiled_messages );
      else $compiled_messages = preg_replace ( "'\[allow_edit\](.*?)\[/allow_edit\]'si", "", $compiled_messages );

if( $user_group[$member_id['user_group']]['del_allc'] ) $compiled_messages = preg_replace( "'\[allow_delete\](.*?)\[/allow_delete\]'si", "\\1", $compiled_messages );
      else $compiled_messages = preg_replace ( "'\[allow_delete\](.*?)\[/allow_delete\]'si", "", $compiled_messages );

?>
