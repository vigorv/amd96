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

// Файл /components/modules/board/favorite.php

$lang = array(

'topic_not_found'       => 'Выбранная тема не найдена.',
'location'              => '<a href="{link}">{title}</a>|Избранное',
'location_online'       => 'Просматривает тему: <a href="{link}">{title}</a>'

);

?>