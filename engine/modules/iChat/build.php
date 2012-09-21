<?php 

/*====================================================
=====================================================*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

require_once ENGINE_DIR . '/classes/templates.class.php';

$tpl = new dle_template ( );
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'] . '/iChat';
define ( 'TEMPLATE_DIR', $tpl->dir );

if( $Messages === false ) {

function ShowDate($format, $time_add) {
global $langdate, $config, $chat_lang;
$today = strtotime(date("Y-m-d.", time()+ ($config['date_adjust']*60)));
if ($time_add > $today) return $chat_lang['today'] . date ("H:i:s", $time_add);
elseif ($time_add > ($today - 86400)) return $chat_lang['yesterday'] . date ("H:i:s", $time_add);
else return @strtr(@date($format, $time_add), $langdate);
}

$_POST['page'] = ( $_POST['page'] >= 1 ) ? $_POST['page'] : 1;

if($_POST['place'] == 'history') $limit = ($_POST['page']*$chat_cfg['sum_msg_history'])-$chat_cfg['sum_msg_history'].','.($chat_cfg['sum_msg_history']+1);

if($_POST['place'] == 'site' OR $_POST['place'] == 'window') $limit = '0,'.$chat_cfg['sum_msg'];

 $db->query( "SELECT * FROM " . PREFIX . "_iChat ORDER BY date DESC LIMIT {$limit}" );

switch ( $_POST['place'] ) {
	
	case "site" :
		$tpl->load_template ( 'message.tpl' );
		break;

	case "window" :
		$tpl->load_template ( 'window_message.tpl' );
		break;
	
	case "history" :
		$tpl->load_template ( 'history_message.tpl' );
		break;

}

$i = 1;

while ( $row = $db->get_row() ) {
 	
$color = stristr($chat_cfg['groups_color'], 'group_'.$row['user_group'].':' );
$color = reset(explode(',',$color));
$color = trim(str_replace('group_'.$row['user_group'].':','',$color));

if( $row['user_group'] == '5' ){

$author = "<a href=\"mailto:".$row['email']."\"><span style=\"color:".$color."\">".$row['author']."</span></a>";

}else{

if( $config['allow_alt_url'] == "yes" ) $go_page = $config['http_home_url'] . "user/" . urlencode( $row['author'] ) . "/";
	 else $go_page = "$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['author'] );
			
$author = "<a onclick=\"ShowProfile('" . urlencode( $row['author'] ) . "', '" . $go_page . "'); return false;\" href=\"" . $go_page . "\"><span style=\"color:".$color."\">" . $row['author'] . "</span> </a>";

}
		
$tpl->set ( '{id}', $row['id'] );
$tpl->set ( '{date}', ShowDate($chat_cfg['format_date'],strtotime($row['date'])) );
$tpl->set ( '{author}', $author );
$tpl->set ( '{name}', $row['author']);
$tpl->set ( '{message}', $row['message'] );
$tpl->set ( '{THEME}',  $config['http_home_url'] . 'templates/' . $config['skin'] . '/iChat'  );

if($_POST['place'] == 'site' OR $_POST['place'] == 'window' OR $_POST['place'] == 'history' AND $i<=$chat_cfg['sum_msg_history']) $tpl->compile ( 'message' );

$i++;

    }
	
	$db->free();
     $tpl->clear();

$Messages = $tpl->result['message'];

if(!$Messages) $Messages = $chat_lang['no_messages'];

if($_POST['place'] == 'history'){

$new_record = '<center><input class="bbcodes" style="font-size: 11px;" title="'.$chat_lang['new_record'].'" onclick="iChatHistory('.($_POST['page']-1).'); return false;" type="button" value="'.$chat_lang['new_record'].'" /></center><br/>';
$previous_record = '<br/><center><input class="bbcodes" style="font-size: 11px;" title="'.$chat_lang['previous_record'].'" onclick="iChatHistory('.($_POST['page']+1).'); return false;" type="button" value="'.$chat_lang['previous_record'].'" /></center>';

if($_POST['page'] > 1) $Messages = $new_record.$Messages;
if($i > $chat_cfg['sum_msg_history']) $Messages = $Messages.$previous_record;

}
  
switch ( $_POST['place'] ) {
	
	case "site" :
		create_cache( "iChat", $Messages, $config['skin'] );
		break;

	case "window" :
		create_cache( "iChat_window", $Messages, $config['skin'] );
		break;
	
	case "history" :
		create_cache( "iChat_history_".$_POST['page'], $Messages, $config['skin'] );
		break;

}

}

if($_POST['place'] == 'history') $_SESSION['page'] = $_POST['page'];

if( $is_logged OR !$is_logged AND $chat_cfg['allow_guest'] == 'yes' ) $Messages = preg_replace( "'\[allow_reply\](.*?)\[/allow_reply\]'si", "\\1", $Messages );
else $Messages = preg_replace ( "'\[allow_reply\](.*?)\[/allow_reply\]'si", "", $Messages );

if( $user_group[$member_id['user_group']]['edit_allc'] ) $Messages = preg_replace( "'\[allow_edit\](.*?)\[/allow_edit\]'si", "\\1", $Messages );
      else $Messages = preg_replace ( "'\[allow_edit\](.*?)\[/allow_edit\]'si", "", $Messages );

if( $user_group[$member_id['user_group']]['del_allc'] ) $Messages = preg_replace( "'\[allow_delete\](.*?)\[/allow_delete\]'si", "\\1", $Messages );
      else $Messages = preg_replace ( "'\[allow_delete\](.*?)\[/allow_delete\]'si", "", $Messages );

if( $user_group[$member_id['user_group']]['allow_hide'] ) $Messages = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $Messages );
else $Messages = preg_replace ( "'\[hide\](.*?)\[/hide\]'si", "<div class=\"quote\">" . $chat_lang['hide'] . "</div>", $Messages );

if( $member_id['user_group'] == 1 ) $Messages = preg_replace( "'\[admin\](.*?)\[/admin\]'si", "\\1", $Messages );
else $Messages = preg_replace ( "'\[admin\](.*?)\[/admin\]'si", "<div class=\"quote\">" . $chat_lang['admin_hide'] . "</div>", $Messages );

?>
