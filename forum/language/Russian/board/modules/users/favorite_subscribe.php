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

// Файл /components/modules/users/favorite_subscribe.php

$lang = array(

'fav_location'          => '<a href="{link}">Профиль: {name}</a>|Избранное',
'fav_location_online'   => 'Просматривает избранное.',
'fav_meta_info'         => 'Избранное » Профиль: {name}',
'fav_title'             => 'Избранные темы',
'subs_location'         => '<a href="{link}">Профиль: {name}</a>|Подписки на темы',
'subs_location_online'  => 'Просматривает подписки на темы.',
'subs_meta_info'        => 'Подписки на темы » Профиль: {name}',
'subs_title'            => 'Подписки на темы',
'fav_empty'             => 'Вы ничего не добавляли в избранное.',
'subs_empty'            => 'Вы не подписывались на темы.',
'not_found'             => 'Темы не найдены.'

);

?>