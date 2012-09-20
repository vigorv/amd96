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

// Файл /components/modules/users/check_new_pm.php

$lang = array(

'list'              => '<br />{date}; Автор: {name}; <a href="{link}">{title}</a>',
'mess_title_no'     => 'Информация.',
'mess_info_no'      => 'Новых сообщений нет.',
'mess_title_yes'    => 'Есть новые сообщения.',
'mess_info_yes'     => 'Всего новых сообщений: {num}'

);

?>