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

// Файл /components/modules/users/check_new_posts.php

$lang = array(

'access_denied'         => 'Доступ закрыт.',
'access_denied_read'    => 'Вам запрещено читать темы в данном форуме.',
'access_denied_forum'   => 'Вам запрещено просматривать данный форум.',
'access_denied_hide'    => 'Вам запрещено читать скрытые темы.',
'access_denied_pass'    => 'Вам нужно ввести пароль, для просмотра данного форума и его тем.',
'new_posts_title'       => 'Загрузка выполнена.',
'new_posts_info'        => 'Загружены новые сообщения на странице.',
'not_found_title'       => 'Тема не найдена.',
'not_found_info'        => 'Выбранная Вами тема не найдена в базе данных.'

);

?>