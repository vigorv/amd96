<?php
/*
=====================================================
 Menu
 Файл: cat_menu.php
=====================================================
*/
if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}


/* ===== ИНИЦИАЛИЗАЦИЯ ===== */

// Определение параметров конфигурации через шаблон
if ( $copy ) $copy = intval( $copy );
else $copy = 0;
if ( $cache_time ) $cache_time = intval( $cache_time );
else $cache_time = 14400;
if ( $new_days ) $new_days = intval( $new_days );
else $new_days = 0;
if ( $com_days ) $com_days = intval( $com_days );
else $com_days = 0;
if ( $iconimg ) $iconimg = intval( $iconimg );
else $iconimg = 0;
if ( $cute ) $cute = intval( $cute );
else $cute = 0;
if ( $hidden ) $hidden = preg_replace( "/[^0-9,]/", "", urldecode($hidden) );
else $hidden = 0;

// Подключаем функции построения меню
if( $copy == 0 ) include_once ( ENGINE_DIR.'/modules/cat_menu.functions.php' );



/* ===== ВЫВОД МЕНЮ ===== */
// получаем из кеша или формируем данные для вывода
$cat_menu = CatMenuLoad( 'cat_menu_c'.$copy, $cache_time, 'text' );
if ( !$cat_menu ) {
	// собираем сведения обо всех одобренных публикациях, если необходимо
	if ($new_days > 0) {
		$sql = $db->query( "SELECT id, date, category FROM " . PREFIX . "_post WHERE approve = '1' ORDER BY id ASC" );
		while( $row = $db->get_row( $sql ) ) {$post_list[] = $row;}
	}
	else $post_list = false;
	// собираем сведения обо всех одобренных комментариях, если необходимо
	if ($com_days > 0) {
		$sql = $db->query( "SELECT id, date, post_id FROM " . PREFIX . "_comments WHERE approve = '1' ORDER BY id ASC" );
		while( $row = $db->get_row( $sql ) ) {$comm_list[] = $row;}
	}
	else $comm_list = false;
	// получаем рабочий массив со всеми данными
	$all_info = CatMenuInit($post_list, $new_days, $comm_list, $com_days);
	// очищаем память
	unset($post_list);
	unset($comm_list);
	// Формируем массивы скрываемых категорий
	if ( $hidden != 0 ) $hiddens = explode( ',', $hidden );
	else $hiddens = array();
	// получаем готовое дерево категорий
	$cat_menu = CatMenu( 0, $all_info, $new_days, $com_days, $iconimg, $cute, $hiddens );
	if ( $mode == 1 ) unset($all_info);
	// сохраняем в кеш
	CatMenuSave( 'cat_menu_c'.$copy, $cat_menu );
	unset($all_info);
}

// вывод меню
echo "<ul class=\"cat_menu\" id=\"cat_menu_c".$copy."\">".$cat_menu."</ul><div style=\"clear:both\"></div>";

?>
