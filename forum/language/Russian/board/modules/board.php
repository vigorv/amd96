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

// Файл /components/modules/board.php

$lang = array(

'no_forum_id'                               => 'Вы не выбрали категорию или форум.',
'access_denied_group_forumcat'              => 'Вашей группе <b>{group}</b> запрещён просмотр данного форума или категории.',
'wrong_password_forum'                      => 'Вы ввели неверный пароль для данного форума. Попробуйте ещё раз.',
'write_password_forum'                      => 'Вам нужно ввести пароль, для просмотра данного форума.',
'access_denied_group_newtopic'              => 'Вашей группе <b>{group}</b> запрещено создание новых тем.',
'access_denied_group_newtopic_in_forum'     => 'Вашей группе <b>{group}</b> запрещено создание темы в данном форуме.',
'location_forum'                            => 'Просматривает форум: {forum}',
'location_newtopic'                         => 'Создаёт новую тему в: ',
'access_denied_user_newtopic'               => 'Вам запрещено создавать темы.{info}',
'basket_forum'                              => 'Данный форум является корзиной и в нём нельзя создавать новые темы',
'no_topic_id'                               => 'Вы не выбрали тему.',
'no_post_id'                                => 'Вы не выбрали сообщение.',
'no_moder'                                  => 'Вы не являетесь модератором.',
'no_notice_id'                              => 'Вы не выбрали объявление.',
'no_active_notice_id'                       => 'Данное объявление не активно.',
'access_denied_notice'                      => 'Вам запрещено просматривать это объявление.'

);

?>