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

// Список частых сообщений на форуме, переменная $lang_message

$lang = array(

'access_denied'             => 'Доступ закрыт.',
'access_denied_to_page'     => 'К данной странице доступ закрыт.',
'error'                     => 'Ошибка!',
'unknow_error'              => 'Неизвестная ошибка.',
'flood_control'             => 'Флуд контроль.',
'flood_control_stop'        => 'Вы были временно заблокированы на {time} секунд.',
'access_denied_speedbar'    => ' (доступ закрыт)',
'access_denied_speedbar2'   => '<i>Скрыто</i> (доступ закрыт)',
'no_act'                    => 'Не выбрано действие.',
'no_secret_key'             => 'Не указан секретный ключ.',
'secret_key'                => 'Указанный секретный ключ не совпадает с вашим ключом.',
'information'               => 'Информация.',
'page_not_found'            => 'Страница не найдена.',
'access_denied_function'    => 'Доступ к данной функции закрыт.',


);

?>