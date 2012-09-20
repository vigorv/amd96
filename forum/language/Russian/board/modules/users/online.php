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

// Файл /components/modules/users/online.php

$lang = array(

'location'                  => 'Онлайн пользователи',
'location_online'           => 'Просматривает список онлайн пользователей',
'meta_info'                 => 'Онлайн пользователи',
'guest'                     => 'Гость',
'no_online_members'         => 'Видимых пользователей или гостей нет в данный момент.',
'online_members_hide_loc'   => 'Просматривает: Доступ закрыт'

);

?>