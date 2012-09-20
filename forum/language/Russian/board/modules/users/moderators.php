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

// Файл /components/modules/users/moderators.php

$lang = array(

'location'          => 'Администрация',
'location_online'   => 'Просматривает список администраторов форума',
'meta_info'         => 'Администрация',
'moder_forum'       => 'Все форумы',
'admin'             => 'Администраторы',
'super-moder'       => 'Супер-модераторы',
'moder'             => 'Модераторы'

);

?>