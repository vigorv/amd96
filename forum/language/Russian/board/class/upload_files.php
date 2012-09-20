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

// Файл /components/class/upload_files.php

$lang = array(

// 2.2

'mini_file'                 => 'Сжатый файл',
'open_file'                 => 'Открыть файл',
'files_table_name'          => 'Файл',
'files_table_size'          => 'Размер',
'files_table_addinpost'     => 'В сообщение',
'add_in_post_title'         => 'Добавить файл в сообщение.<br />Вы можете присвоить файлу своё название, например: [attachment=XX|Моё название]',

// 2.1

'not_logged'                => 'Вы не авторизованы.',
'no_forum_id'               => 'Не указан форум.',
'upload_off'                => 'Загрузка файлов запрещена.',
'max_size'                  => 'Размер файла больше {size} кб.',
'access_denied_group'       => 'Вашей группе <b>{group}</b> запрещено загружать файлы в данном форуме.',
'no_file_extension'         => 'Не удалось определить расширение файла.',
'denied_file_extension'     => 'Данный тип расширения не поддерживается: {type}',
'create_folder'             => 'Не удалось создать папку: /attachment/{folder}',
'denied_folder'             => 'Нет прав на запись в папке: /attachment/{folder}',
'download_error'            => 'Неудалось загрузить файл.',
'add_in_post'               => 'Добавить',
'del_file'                  => '<font color=red>Удалить</font>',
'no_file_id'                => 'Не указан файл.',
'secret_key'                => 'Указанный секретный ключ не совпадает с вашим ключом.',
'not_enough_rights'         => 'У вас недостаточно прав для удаления файлов.',
'file_is_not_found'         => 'Указаный файл не найден.'

);

?>