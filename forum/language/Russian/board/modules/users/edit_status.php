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

// Файл /components/modules/users/edit_status.php

$lang = array(

'location'              => '<a href="{link}">Профиль: {name}</a>|Редактирование статуса',
'location_online'       => 'Редактирование статуса: <a href="{link}">{name}</a>',
'meta_info'             => 'Редактирование статуса » Профиль: {name}',
'access_denied_group'   => 'Вашей группе <b>{group}</b> запрещено использование личных статусов.',
'status_max'            => 'Длина статуса превышает {max} символов.',
'location_error'        => '<i>Ошибка</i>',
'location_online_error' => 'Редактирование профиля: <i>Ошибка</i>',
'not_found'             => 'Пользователь не найден в базе данных или у Вас недостаточно прав для редактирования данного профиля.'

);

?>