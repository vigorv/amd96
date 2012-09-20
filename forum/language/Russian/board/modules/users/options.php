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

// Файл /components/modules/users/options.php

$lang = array(

'location'              => '<a href="{link}">Профиль: {name}</a>|Настройки форума',
'location_online'       => 'Настройки форума: <a href="{link}">{name}</a>',
'meta_info'             => 'Настройки форума » Профиль: {name}',
'email_error'           => 'Вы неверно заполнили поле E-Mail.',
'email_max'             => 'Поле E-Mail превышает максимальное количество символов.',
'ip_error'              => 'Неверно заполнено поле блокировки по IP.',
'pmtoemail_op_no'       => 'Нет',
'pmtoemail_op_yes'      => 'Да',
'subscribe_op_pm'       => 'ЛС',
'subscribe_op_email'    => 'E-Mail',
'online_op_show'        => 'Видимый',
'online_op_hide'        => 'Скрытый',
'commprofile_op_off'    => 'Отключить',
'commprofile_op_on'     => 'Включить',
'commprofile_op_mess'   => '<font class="smalltext">Данная опция отключена администратором.</font>',
'location_error'        => '<i>Ошибка</i>',
'location_online_error' => 'Настройки форума: <i>Ошибка</i>',
'not_found'             => 'Пользователь не найден в базе данных или у Вас недостаточно прав для редактирования данного профиля.',
'posts_ajax_no'         => 'Выключить',
'posts_ajax_yes'        => 'Включить'

);

?>