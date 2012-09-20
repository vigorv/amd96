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

// Файл configuration.php

$lang = array(

'header'                => 'Настройки',
'addgroup_speedbar'     => '<a href="{link}">Настройки</a>|Добавление группы настроек',
'addgroup_online'       => 'Настройки &raquo; Добавление группы настроек',
'editgroup_speedbar'    => '<a href="{link}">Настройки</a>|<a href="{link_2}">Группа: {title}</a>|Редактирование',
'editgroup_online'      => 'Настройки &raquo; Группа: {title} &raquo; Редактирование',
'delgroup_speedbar'     => '<a href="{link}">Настройки</a>|Удаление группы',
'delgroup_online'       => 'Настройки &raquo; Удаление группы',
'addconf_speedbar'      => '<a href="{link}">Настройки</a>|<a href="{link_2}">Группа: {title}</a>|Добавление настройки',
'addconf_online'        => 'Настройки &raquo; Группа: {title} &raquo; Добавление настройки',
'editconf_speedbar'     => '<a href="{link}">Настройки</a>|<a href="{link_2}">Группа: {title}</a>|Редактирвоание настройки: <i>Доступ закрыт</i>',
'editconf_online'       => 'Настройки &raquo; Группа: {title} &raquo; Редактирвоание настройки: <i>Доступ закрыт</i>',
'delconf_speedbar'      => '<a href="{link}">Настройки</a>|Удаление настройки',
'delconf_online'        => 'Настройки &raquo;Удаление настройки',
'email_speedbar'        => '<a href="{link}">Настройки</a>|Шаблоны E-mail уведомлений',
'email_online'          => 'Настройки &raquo; Шаблоны E-mail уведомлений',
'email_edit_speedbar'   => '<a href="{link}">Настройки</a>|<a href="{link_2}">Шаблоны E-mail уведомлений</a>|Редактирвоание настройки: <i>Доступ закрыт</i>',
'email_edit_online'     => 'Настройки &raquo; Шаблоны E-mail уведомлений &raquo; Редактирвоание настройки: <i>Доступ закрыт</i>',
'email_del_speedbar'    => '<a href="{link}">Настройки</a>|<a href="{link_2}">Шаблоны E-mail уведомлений</a>|Удаление шаблона',
'email_del_online'      => 'Настройки &raquo; Шаблоны E-mail уведомлений &raquo; Удаление шаблона',
'email_add_speedbar'    => '<a href="{link}">Настройки</a>|<a href="{link_2}">Шаблоны E-mail уведомлений</a>|Новый шаблон',
'email_add_online'      => 'Настройки &raquo; Шаблоны E-mail уведомлений &raquo; Новый шаблон',
'template_speedbar'     => '<a href="{link}">Настройки</a>|Шаблоны форума',
'template_online'       => 'Настройки &raquo; Шаблоны форума',
'lang_speedbar'         => '<a href="{link}">Настройки</a>|Язык форума',
'lang_online'           => 'Настройки &raquo; Язык форума',
'user_agent_speedbar'   => '<a href="{link}">Настройки</a>|Список User Agent',
'user_agent_online'     => 'Настройки &raquo; Список User Agent'

);

?>