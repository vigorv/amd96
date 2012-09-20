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

// Файл /components/modules/search.php

$lang = array(

'location'                  => 'Поиск',
'location_result'           => 'Результаты',
'access_denied_group'       => 'Вашей группе <b>{group}</b> запрещено пользоваться поиском.',
'allforums'                 => 'Все форумы',
'type_topics_posts'         => 'Названиях тем и сообщениях',
'type_topics'               => 'Названиях тем',
'type_posts'                => 'Сообщениях',
'preview_topics'            => 'как темы',
'preview_posts'             => 'как сообщения',
'sort_result_last_answer'   => 'последнему ответу',
'sort_result_title'         => 'заголовку',
'sort_result_num_aswers'    => 'кол-ву ответов',
'sort_result_num_views'     => 'кол-ву просмотров',
'sort_order_DESC'           => 'убыванию',
'sort_order_ASC'            => 'возрастанию',
'mod_forum'                 => 'Обсуждения',
'mod_members'               => 'Пользователи',
'members_allgroups'         => 'Все группы',
'sort_result_m_name'        => 'нику',
'sort_result_m_reg'         => 'дате регистрации',
'sort_result_m_last'        => 'дате последнего посещения',
'sort_result_m_posts'       => 'кол-ву ответов',
'word_len'                  => 'Ключевое слово меньше {num} символов.',
'no_forum_id'               => 'Вы не выбрали форум или выбрали несуществующий.',
'access_denied_forum'       => 'Вам запрещено искать в данном форуме.',
'no_group_id'               => 'Выбранной группы не существует.',
'result'                    => 'Результаты поиска: ',
'no_data_members'           => 'Вы не выбрали по какому критерию(ям) осуществлять поиск по пользователям.',
'empty'                     => 'Поиск не дал результатов. Попробуйте изменить настройки поиска.'

);

?>