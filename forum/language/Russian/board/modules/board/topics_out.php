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

// Файл /components/modules/board/topics_out.php

$lang = array(

'member_not_found'          => 'Выбранный пользователь не найден в базе данных.',
'member_location_online_no' => 'Просматривает все темы: <i>Пользователь не найден</i>',
'member_location'           => '<a href="{link}">Профиль: {name}</a>|Все темы',
'member_location_online'    => 'Просматривает все темы: <a href="{link}">{name}</a>',
'member_title_module'       => 'Все темы: {name}',
'member_meta_info'          => 'Все темы » Профиль: {name}',
'last_location_online'      => 'Просматривает последние темы',
'last_location'             => 'Последние темы',
'last_title_module'         => 'Последние темы',
'last_meta_info'            => 'Последние темы',
'active_location_online'    => 'Просматривает активные темы',
'active_location'           => 'Активные темы за последние 24 часа',
'active_title_module'       => 'Активные темы за последние 24 часа',
'active_meta_info'          => 'Активные темы за последние 24 часа',
'topics_not_found'          => 'Темы не найдены.'

);

?>