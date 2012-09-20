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

// Список частых сообщений на форуме

$lang = array(

'access_denied'             => 'Доступ закрыт.',
'access_denied_to_page'     => 'К данной странице доступ закрыт.',
'error'                     => 'Ошибка!',
'unknow_error'              => 'Неизвестная ошибка.',
'request_is_accepted'       => 'Запрос принят.',
'flood_control'             => 'Флуд контроль.',
'flood_control_stop'        => 'Вы были временно заблокированы на {time} секунд.',
'access_denied_speedbar'    => ' (доступ закрыт)',
'access_denied_speedbar2'   => '<i>Скрыто</i> (доступ закрыт)',
'no_act'                    => 'Не выбрано действие.',
'no_secret_key'             => 'Не указан секретный ключ.',
'secret_key'                => 'Указанный секретный ключ не совпадает с вашим ключом.',
'not_logged'                => 'Вы не авторизованы на форуме.',
'captcha'                   => 'Вы не ввели код или ввели его неверно.',
'keystring'                 => 'Вы неверно ответили на вопрос.',
'change_captcha'            => 'Сменить картинку',
'information'               => 'Информация.',
'text_is_hide'              => '<blockquote class="blockhide"><p><span class="titlehide">Скрытый текст.</span></p></blockquote>',
'topic_alt_close'           => 'Тема закрыта.',
'topic_alt_hot'             => 'Горячая тема.',
'topic_alt'                 => 'Обычная тема.',
'access_denied_function'    => 'Доступ к данной функции закрыт.',
'control_center'            => 'Центр управления',
'warning'                   => 'Предупреждение.',
'ip_hide'                   => 'Скрыт',
'spoiler_title'             => 'Спойлер [+]',
'quote_title'               => 'Цитата:',
'quote_title2'              => 'писал:',
'member_sex_male'           => 'Мужской',
'member_sex_female'         => 'Женский',
'chpu_error'                => 'По данному адресу никаких материалов не найдено.'

);

?>