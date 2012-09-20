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

// Файл /components/modules/users.php

$lang = array(

'access_denied_group'               => 'Вашей группе <b>{group}</b> запрещён просмотр профилей.',
'no_member_id'                      => 'Вы не выбрали пользователя.',
'access_denied_group_online'        => 'Вашей группе <b>{group}</b> запрещён просмотр онлайн списка.',
'no_comm_id'                        => 'Не выбран комментарий.'


);

?>