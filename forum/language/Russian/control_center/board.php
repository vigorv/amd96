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

// Файл board.php

$lang = array(

'header'                    => 'Форум',
'addcategory_speedbar'      => '<a href="{link}">Форум</a>|Добавление категории',
'addcategory_online'        => 'Форум &raquo; Добавление категории',
'addforum_speedbar'         => '<a href="{link}">Форум</a>|Добавление форума',
'addforum_online'           => 'Форум &raquo; Добавление форума',
'editforum_speedbar'        => '<a href="{link}">Форум</a>|Редактирование категории: {title}',
'editforum_online'          => 'Форум &raquo; Редактирование категории: {title}',
'editforum_speedbar_2'      => '<a href="{link}">Форум</a>|Редактирование форума: {title}',
'editforum_online_2'        => 'Форум &raquo; Редактирование форума: {title}',
'delforum_speedbar'         => '<a href="{link}">Форум</a>|Удаление категории/форума',
'delforum_online'           => 'Форум &raquo; Удаление категории/форума',
'moderators_speedbar'       => '<a href="{link}">Форум</a>|Модераторы',
'moderators_online'         => 'Форум &raquo; Модераторы',
'moder_add_speedbar'        => '<a href="{link}">Форум</a>|<a href="{link_2}">Модераторы</a>|Добавление модератора',
'moder_add_online'          => 'Форум &raquo; Модераторы &raquo; Добавление модератора',
'moder_edit_speedbar'       => '<a href="{link}">Форум</a>|<a href="{link_2}">Модераторы</a>|Редактирование модератора',
'moder_edit_online'         => 'Форум &raquo; Модераторы &raquo; Редактирование модератора',
'words_filter_speedbar'     => '<a href="{link}">Форум</a>|Фильтр слов',
'words_filter_online'       => 'Форум &raquo; Фильтр слов',
'notice_speedbar'           => '<a href="{link}">Форум</a>|Объявления',
'notice_online'             => 'Форум &raquo; Объявления',
'notice_add_speedbar'       => '<a href="{link}">Форум</a>|<a href="{link_2}">Объявления</a>|Добавление',
'notice_add_online'         => 'Форум &raquo; Объявления &raquo; Добавление',
'notice_edit_speedbar'      => '<a href="{link}">Форум</a>|<a href="{link_2}">Объявления</a>|Редактирование',
'notice_edit_online'        => 'Форум &raquo; Объявления &raquo; Редактирование',
'sharelink_speedbar'        => '<a href="{link}">Форум</a>|Сервисы публикации',
'sharelink_online'          => 'Форум &raquo; Сервисы публикации',
'sharelink_add_speedbar'    => '<a href="{link}">Форум</a>|<a href="{link_2}">Сервисы публикации</a>|Добавление',
'sharelink_add_online'      => 'Форум &raquo; Сервисы публикации &raquo; Добавление',
'sharelink_edit_speedbar'   => '<a href="{link}">Форум</a>|<a href="{link_2}">Сервисы публикации</a>|Редактирование',
'sharelink_edit_online'     => 'Форум &raquo; Сервисы публикации &raquo; Редактирование'

);

?>