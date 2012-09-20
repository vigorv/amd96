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

// Файл /components/modules/board/main.php

$lang = array(

'closed_forum'      => '<i>Закрытый форум</i>',
'last_title_none'   => '-----',
'last_member_none'  => '-----',
'location_online'   => 'Просматривает главную страницу форума',
'no_forums'         => 'Категории и форум ещё не созданы.',
'forum_read'        => 'Форум прочитан',
'forum_unread'      => 'Форум не прочитан'

);

?>