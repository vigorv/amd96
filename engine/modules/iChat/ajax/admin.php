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

include ENGINE_DIR.'/data/config.php';
include_once ENGINE_DIR.'/modules/iChat/data/language.lng';

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/modules/iChat/ajax/admin.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

require_once ENGINE_DIR.'/modules/sitelogin.php';

if(($member_id['user_group'] != 1)) {die ("error");}

if( $config["lang_" . $config['skin']] ) {
	
	if ( file_exists( ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/adminpanel.lng' ) ) {
		@include_once (ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/adminpanel.lng');
	} else die("Language file not found");

} else {
	
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/adminpanel.lng';

}

@header( "Content-type: text/html; charset=" . $config['charset'] );

include ENGINE_DIR . '/modules/iChat/data/config.php';

if($_POST['action'] == "save"){

$save_cfg = $_POST['save_cfg'];

$save_cfg['version'] = "7.0";
	
if(!is_numeric(trim($save_cfg['sum_msg'])) OR trim($save_cfg['sum_msg']) <= 0) { $save_cfg['sum_msg'] = 10;}
if(!is_numeric(trim($save_cfg['sum_msg_history'])) OR trim($save_cfg['sum_msg_history']) <= 0) { $save_cfg['sum_msg_history'] = 25;}
if(!is_numeric(trim($save_cfg['max_text'])) OR trim($save_cfg['max_text']) <= 0) { $save_cfg['max_text'] = 300;}
if(!is_numeric(trim($save_cfg['refresh'])) OR trim($save_cfg['refresh']) <= 0) { $save_cfg['refresh'] = 15;}
if(!is_numeric(trim($save_cfg['guest_refresh'])) OR trim($save_cfg['guest_refresh']) <= 0) { $save_cfg['guest_refresh'] = 60;}
if(!is_numeric(trim($save_cfg['max_word'])) OR trim($save_cfg['max_word']) <= 0) { $save_cfg['max_word'] = 33;}
if(!is_numeric(trim($save_cfg['stop_flood'])) OR trim($save_cfg['stop_flood']) < 0) { $save_cfg['stop_flood'] = 30;}
if(!is_numeric(trim($save_cfg['max_smilies'])) OR trim($save_cfg['max_smilies']) < 0) { $save_cfg['max_smilies'] = 7;}

if( is_dir( ROOT_DIR . '/' .$save_cfg['path_smiles'] ) ){

$handle = opendir( ROOT_DIR . '/' .$save_cfg['path_smiles'] );

while (false !== ($file = readdir($handle))) {
                
if ( end(explode(".", strtolower($file))) == "gif" ) $slilies_list[] = str_ireplace( ".gif", "", $file );

}
   
if(is_array($slilies_list)){
$save_cfg['smiles'] = implode(",", $slilies_list);
}else{
$save_cfg['path_smiles'] = "engine/data/emoticons";
$save_cfg['smiles'] = "wink,winked,smile,am,belay,feel,fellow,laughing,lol,love,no,recourse,request,sad,tongue,wassat,crying,what,bully,angry";
}
         
closedir($handle);

}else{
$save_cfg['path_smiles'] = "engine/data/emoticons";
$save_cfg['smiles'] = "wink,winked,smile,am,belay,feel,fellow,laughing,lol,love,no,recourse,request,sad,tongue,wassat,crying,what,bully,angry";
}

$save_cfg['no_access'] = convert_unicode($save_cfg['no_access']);

$save_cfg = $save_cfg + $chat_cfg;

	$handler = fopen( ENGINE_DIR . '/modules/iChat/data/config.php', "w" );
	
	fwrite( $handler, "<?PHP \n\n//iChat Configurations\n\n\$chat_cfg = array (\n\n" );
	foreach ( $save_cfg as $name => $value ) {
		
          $value = str_replace( "%3D", "=", $value );

	     $value = str_replace( "$", "&#036;", $value );
		$value = str_replace( "{", "&#123;", $value );
		$value = str_replace( "}", "&#125;", $value );
		
		$name = str_replace( "$", "&#036;", $name );
		$name = str_replace( "{", "&#123;", $name );
		$name = str_replace( "}", "&#125;", $name );

		fwrite( $handler, "'{$name}' => \"{$value}\",\n\n" );
	
	}
	fwrite( $handler, ");\n\n?>" );
	fclose( $handler );

include ENGINE_DIR . '/modules/iChat/data/config.php';

}

$js_data = @file_get_contents(ROOT_DIR . '/templates/' . $config['skin'] . '/iChat/js/admin.js');

	function makeDropDown($options, $name, $selected) {
		$output = "<select id=\"$name\" name=\"$name\">\r\n";
		foreach ( $options as $value => $description ) {
			$output .= "<option value=\"$value\"";
			if( $selected == $value ) {
				$output .= " selected ";
			}
			$output .= ">$description</option>\n";
		}
		$output .= "</select>";
		return $output;
	}

$allow_guest = makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "cfg14", "{$chat_cfg['allow_guest']}" );

if( ! $iChat_db ) $iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

		$row = sqlite_fetch_array(sqlite_query($iChat_db, "SELECT COUNT(*) as count FROM iChat"));

$content = <<<HTML
<script language="javascript" type="text/javascript">
var iChat_lang_loading = '{$chat_lang['loading']}';
$js_data
</script>

<div style="font-size: 10px;">

<b>{$chat_lang['admin1']}</b> {$allow_guest}
     <hr />
<b>{$chat_lang['admin2']}</b>
<input id="cfg01" type=text style="text-align: center;"  value="{$chat_cfg['sum_msg']}" size=10><br />
     <hr />
<b>{$chat_lang['admin3']}</b>
<input id="cfg15" type=text style="text-align: center;"  value="{$chat_cfg['sum_msg_history']}" size=10><br />
     <hr />
<b>{$chat_lang['admin4']}</b>
<input id="cfg12" type=text style="text-align: center;"  value="{$chat_cfg['max_text']}" size=10><br />
     <hr />
<b>{$chat_lang['admin5']}</b>
<input id="cfg13" type=text style="text-align: center;"  value="{$chat_cfg['format_date']}" size=10><br />
     <hr />
<b>{$chat_lang['admin6']}</b>
<input id="cfg02" type=text style="text-align: center;" value="{$chat_cfg['refresh']}" size=10><br />
     <hr />
<b>{$chat_lang['admin11']}</b>
<input id="cfg07" type=text style="text-align: center;"  value="{$chat_cfg['guest_refresh']}" size=10><br />
     <hr />
<b>{$chat_lang['admin7']}</b>
<input id="cfg03" type=text style="text-align: center;"  value="{$chat_cfg['stop_flood']}" size=10><br />
     <hr />
<b>{$chat_lang['admin8']}</b>
<input id="cfg04" type=text style="text-align: center;"  value="{$chat_cfg['max_word']}" size=10><br />
     <hr />
<b>{$chat_lang['admin9']}</b>
<input id="cfg05" type=text style="text-align: center;"  value="{$chat_cfg['max_smilies']}" size=10><br />
     <hr />
<b>{$chat_lang['admin10']}</b>
<input id="cfg16" type=text style="text-align: center;"  value="{$chat_cfg['no_access']}" size=28><br />
     <hr />
<b>{$chat_lang['admin12']}</b>
<input id="cfg06" type=text style="text-align: center;"  value="{$chat_cfg['path_smiles']}" size=30><br />
     <hr />
<b>{$chat_lang['admin13']}</b>
<input id="cfg08" type=text style="text-align: center;"  value="{$chat_cfg['groups_color']}" size=30><br />
 <hr />
- � ���� ������ <b>{$row['count']}</b> ���������.

<div id="progres"></div>

</div>
HTML;

if($_POST['check'] == "updates") {
$data = @file_get_contents("http://weboss.net/iChat/updates.php?version=".$chat_cfg['version']);

if ( !$data ) $data = "�� ������� ����������� � ���������� �������, �������� ��� ������ �� ������������ ��������� ����������, ���� ��������� ���� � ������ ����� ��������. ��������� ������� ������� ������� ..."; else {

	if ($config['charset'] == "utf-8") $data = iconv("windows-1251", "utf-8", $data);

}
echo <<<HTML
<script language="JavaScript" type="text/javascript">
DLEalert('$data', 'iChat updates')
</script>
HTML;
echo $content;
die();
}

if($_POST['action'] == "save" OR $_POST['action'] == "clear"){

if($_POST['action'] == "clear"){
if( ! $iChat_db ) $iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');
sqlite_query($iChat_db, "DELETE FROM iChat WHERE 1");
}

echo $content;

	//-------------------------------------------------
	//	������� ���
	//-------------------------------------------------

$fdir = opendir( ENGINE_DIR . '/modules/iChat/data/cache' );
	
while ( $file = readdir( $fdir ) ) {
if( $file != '.' and $file != '..' and $file != '.htaccess' ) @unlink( ENGINE_DIR . '/modules/iChat/data/cache/' . $file );	
}

}

echo "<div id='ECPU' title='{$chat_lang['admin_title']} &nbsp;|||&nbsp; Copyright &copy; <a href=\"http://weboss.net/\" target=\"_blank\" style=\"text-decoration: none; font-size: 9px;\">WEBoss.Net</a>' style='display:none'>{$content}</div>";

?>