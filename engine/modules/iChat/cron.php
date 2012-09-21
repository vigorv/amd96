<?php 

/*====================================================
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
Внимание: В целях безопасности мы рекомендуем переименовать файл
cron.php в любое другое название с расширением PHP

Для работы запуска операций по расписанию необходима поддержка вашим
хостингом запуска приложений с использованием Cron более подробную
информацию о том как использовать данную функцию вы можете
получить у вашего хостинг провайдера.
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Для включения поддержки запуска операций по крону вы должны 
поставить значение 1 для переменной $allow_cron
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

$allow_cron = 0;

if( $allow_cron ) {

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../../..' );
define( 'ENGINE_DIR', '../..' );

include ENGINE_DIR . '/data/config.php';
include ENGINE_DIR . '/modules/iChat/data/config.php';

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
	
$db->query( "SELECT id FROM " . PREFIX . "_iChat ORDER BY date DESC" );
		
   while($row = $db->get_row()) $ids[] = $row['id']; 

$i = 1;

foreach ($ids as $id){
if( $i > $chat_cfg['cron_clean'] ) $db->query("DELETE FROM " .PREFIX.  "_iChat where id='{$id}'"); 
$i++;
}
	
clear_cache( 'iChat_' );
 
echo "Done";

}

die ("Cron not allowed");

?>
