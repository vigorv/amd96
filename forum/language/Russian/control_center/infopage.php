<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

// Файл infopage.php

$lang = array(

'header'            => 'Система',
'cache_speedbar'    => '<a href="{link}">Система</a>|Список файлов кеша',
'cache_online'      => 'Система &raquo; Список файлов кеша',
'header_infopage'   => 'Страница информации',
'error_infopage'    => 'Вы не выбрали какую страницу с информации хотите просмотреть.'

);

?>