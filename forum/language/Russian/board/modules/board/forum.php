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

// Файл /components/modules/board/forum.php

$lang = array(

'closed_forum'      => '<i>Закрытый форум</i>',
'last_title_none'   => '-----',
'last_member_none'  => '-----',
'location_online'   => 'Просматривает форум: {forum}',
'no_topics'         => 'В данном форуме темы не найдены. Это может быть связано с тем, что темы ещё никто не создавал или если у Вас выставлены фильтры при выводе тем.',
'forum_read'        => 'Форум прочитан',
'forum_unread'      => 'Форум не прочитан'

);

?>