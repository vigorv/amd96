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

// Файл /components/modules/board/posts_out.php

$lang = array(

'location_online'   => 'Просматривает все сообщения: {user}',
'not_found_user'    => 'Выбранный пользователь не найден в базе данных.',
'location_profile'  => '<a href="{link}">Профиль: {user}</a>',
'location1'         => 'Все сообщения',
'title_module1'     => 'Все сообщения: {user}',
'meta_info1'        => 'Все сообщения » Профиль: {user}',
'location_online'   => 'Просматривает последние ответы',
'not_found_posts'   => 'Сообщения не найдены.',
'location2'         => 'Последние ответы',
'title_module2'     => 'Последние ответы',
'meta_info2'        => 'Последние ответы'

);

?>