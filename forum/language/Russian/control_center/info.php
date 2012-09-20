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

// Файл info.php

$lang = array(

'header'            => 'Система',
'cache_speedbar'    => '<a href="{link}">Система</a>|Список файлов кеша',
'cache_online'      => 'Система &raquo; Список файлов кеша',
'rebuild_speedbar'  => '<a href="{link}">Система</a>|Пересчёт данных',
'rebuild_online'    => 'Система &raquo; Пересчёт данных'

);

?>