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

// Файл /components/modules/users/warning.php

$lang = array(

'location'              => '<a href="{link}">Профиль: {name}</a>|История предупреждений',
'location_online'       => 'История предупреждений: <a href="{link}">{name}</a>',
'meta_info'             => 'История предупреждений » Профиль: {name}',
'not_found'             => 'Пользователь не найден.',
'access_denied_history' => 'Вы не можете просматривать историю предупреждений пользователя, находящегося в группе <b>{group}</b>',
'level'                 => 'Ваш уровень предупреждений был снижен. Текущий уровень: {num}',
'pm_title'              => 'Удаление предупреждения.',
'status_on'             => '<font color=red>Активно</font>',
'status_off'            => '<font color=green>Удалено</font>',
'option_1'              => 'Снять предупреждение',
'option_2'              => 'Удалить предупреждение',
'no_warnings'           => 'У пользователя <b><a href="{link}">{name}</a></b> нет активных предупреждений.'

);

?>