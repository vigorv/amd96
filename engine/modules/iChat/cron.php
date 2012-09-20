<?php 

/*====================================================
 Author: RooTM
------------------------------------------------------
 Web-site: http://weboss.net/
=====================================================*/

if( !defined( 'E_DEPRECATED' ) ) {

	@error_reporting ( E_ALL ^ E_NOTICE );
	@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );

} else {

	@error_reporting ( E_ALL ^ E_DEPRECATED ^ E_NOTICE );
	@ini_set ( 'error_reporting', E_ALL ^ E_DEPRECATED ^ E_NOTICE );

}

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
��������: � ����� ������������ �� ����������� ������������� ����
cron.php � ����� ������ �������� � ����������� PHP

��� ������ ������� �������� �� ���������� ���������� ��������� �����
��������� ������� ���������� � �������������� Cron ����� ���������
���������� � ��� ��� ������������ ������ ������� �� ������
�������� � ������ ������� ����������.
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
��� ��������� ��������� ������� �������� �� ����� �� ������ 
��������� �������� 1 ��� ���������� $allow_cron
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

$allow_cron = 0;
$chat_cfg['cron_clean'] = 500;

if( $allow_cron ) {

define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -20 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );
	
$iChat_db = sqlite_open(ENGINE_DIR . '/modules/iChat/data/iChat.db');

$query = sqlite_query($iChat_db, "SELECT id FROM iChat ORDER BY date DESC" );
		
   while($row = sqlite_fetch_array($query)) $ids[] = $row['id']; 

$i = 1;

foreach ($ids as $id){
if( $i > $chat_cfg['cron_clean'] ) sqlite_query($iChat_db, "DELETE FROM iChat where id='{$id}'"); 
$i++;
}
	
	//-------------------------------------------------
	//	������� ���
	//-------------------------------------------------

$fdir = opendir( ENGINE_DIR . '/modules/iChat/data/cache' );
	
while ( $file = readdir( $fdir ) ) {
if( $file != '.' and $file != '..' and $file != '.htaccess' ) @unlink( ENGINE_DIR . '/modules/iChat/data/cache/' . $file );	
}
 
die ("Done");

}

die ("Cron not allowed");

?>
