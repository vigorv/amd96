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

// Файл /components/global/functions.php

$lang = array(

// 2.2

'attachment_download_link'              => 'Скачать',
'hide_in_post_limit_posts'              => '<blockquote class="blockhide"><p><span class="titlehide">У Вас должно быть не менее <b>{num}</b> ответов для просмотра скрытого текста.</span></p></blockquote>',
'hide_in_post_limit_group'              => '<blockquote class="blockhide"><p><span class="titlehide">Скрытый текст предназначен только для определённх групп пользователей.</span></p></blockquote>',
'hide_in_post_limit_reg'                => '<blockquote class="blockhide"><p><span class="titlehide">С момента регитсрации Вашего аккаунта должно пройти не менее {days} дней для просмотра скрытого текста.</span></p></blockquote>',
'hide_in_post_limit_user'               => '<blockquote class="blockhide"><p><span class="titlehide">Скрытый текст предназначен только для определённых пользователей.</span></p></blockquote>',
'hide_in_post_limit_user_max'           => '<blockquote class="blockhide"><p><span class="titlehide">Неверно указан тег скрытого текста, кол-во пользователей не может быть больше 100.</span></p></blockquote>',

// 2.1

'search_tag_preg'                       => 'Поиск.',
'formatdate_today'                      => 'Сегодня, ',
'formatdate_yesterday'                  => 'Вчера, ',
'message_info'                          => '<li>{text}</li>',
'message_back'                          => '<li><a href="javascript:history.go(-1)">Вернуться назад</a></li>',
'speedbar'                              => '<i>Не определено</i>',
'forum_options_hide_topics'             => '<li><a href="{link}">Показать скрытые темы</a></li>',
'forum_options_hide_posts'              => '<li><a href="{link}">Темы со скрытыми сообщениями</a></li>',
'forum_options_topics_open'             => 'Открыть',
'forum_options_topics_close'            => 'Закрыть',
'forum_options_topics_hide'             => 'Скрыть',
'forum_options_topics_publ'             => 'Опубликовать',
'forum_options_topics_up'               => 'Поднять',
'forum_options_topics_down'             => 'Опустить',
'forum_options_topics_move'             => 'Переместить',
'forum_options_topics_union'            => 'Объединить',
'forum_options_topics_subscribe'        => 'Отписать всех от выбранных тем',
'forum_options_topics_del'              => 'Удалить',
'forum_options_topics_mas_p_hide'       => 'Скрыть сообщения',
'forum_options_topics_mas_p_publ'       => 'Опубликовать сообщения',
'forum_options_topics_mas_p_edit'       => 'Редактировать сообщения',
'forum_options_topics_mas_p_fix'        => 'Закрепить сообщения',
'forum_options_topics_mas_p_unfix'      => 'Открепить сообщения',
'forum_options_topics_mas_p_union'      => 'Объединить сообщения',
'forum_options_topics_mas_p_move'       => 'Переместить сообщения',
'forum_options_topics_mas_p_del'        => 'Удалить сообщения',
'forum_options_topics_mas_t_unsubsc'    => 'Отписать всех от темы',
'forum_options_topics_mas_t_hide'       => 'Скрыть тему',
'forum_options_topics_mas_t_pub'        => 'Опубликовать тему',
'forum_options_topics_mas_t_edit'       => 'Редактировать тему',
'forum_options_topics_mas_t_up'         => 'Поднять тему',
'forum_options_topics_mas_t_down'       => 'Опустить тему',
'forum_options_topics_mas_t_open'       => 'Открыть тему',
'forum_options_topics_mas_t_close'      => 'Закрыть тему',
'forum_options_topics_mas_t_move'       => 'Переместить тему',
'forum_options_topics_mas_t_del'        => 'Удалить тему',
'member_publ_info1'                     => 'Блокировка до: {date}',
'member_publ_info2'                     => 'Блокировка до: Навсегда',
'forum_options_topics_author_edit'      => 'Редактировать тему',
'forum_options_topics_author_open'      => 'Открыть тему',
'forum_options_topics_author_close'     => 'Закрыть тему',
'forum_options_topics_author_hide'      => 'Удалить (скрыть) тему',
'send_new_pm_by'                        => 'Сообщение от <b>{name}</b><br /><br />',
'send_new_pm_title'                     => 'Новое личное сообщение.',
'topic_poll_logs'                       => '<li>{spisok} <span>({vote_num}/{num}% голосов)</span><div><i style="width:{num}%;"></i></div></li>',
'share_links'                           => 'Поделиться ссылкой через {title}',
'show_attach_off'                       => '<span class="attachment">Администрация отключила возможность скачивания файлов.</span>',
'show_attach_permission'                => '<span class="attachment">У Вас недостаточно прав для скачивания файлов.</span>',
'show_attach_count'                     => ' Загрузок: {num}',
'hide_in_post_show_1'                   => '<blockquote class="blockhide"><p><span class="titlehide">Скрытый текст:</span><span class="texthide">',
'hide_in_post_show_2'                   => '</span></p></blockquote>',
'hide_in_post_access_denied_group'      => '<blockquote class="blockhide"><p><span class="titlehide">Вашей группе <b>{group}</b> запрещён просмотр скрытого текста.</span></p></blockquote>',
'hide_in_post_limit'                    => '<blockquote class="blockhide"><p><span class="texthide">Для просмотра скрытого текста нужно {num} сообщений.</span></p></blockquote>',
'topic_do_subscribe_answers'            => 'Новые ответы в теме: ',
'topic_do_subscribe_topic'              => 'Тема: {link}',
'topic_do_subscribe_name'               => '<br />Автор: {name}',
'topic_do_subscribe_date'               => '<br />Время: {date}',
'topic_do_subscribe_answers2'           => 'Новые ответы в теме.',
'online_members_first'                  => '<li>{info}</li>',
'online_members_next'                   => '<li>, {info}</li>',
'online_members_hide_loc'               => 'Просматривает: Доступ закрыт'

);

?>