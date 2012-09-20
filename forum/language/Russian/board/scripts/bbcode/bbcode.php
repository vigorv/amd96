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

// Файл /components/scripts/bbcode/bbcode.php

$lang = array(

// 2.2

'search'            => 'Поиск',

// 2.1

'b'                 => 'жирный',
'i'                 => 'курсив',
's'                 => 'перечёркнутый',
'u'                 => 'подчёркнутый',
'size_window'       => 'Введите размер шрифта',
'size'              => 'размер шрифта',
'color'             => 'цвет текста',
'quote'             => 'цитата',
'smiles'            => 'смайлы',
'spoiler_window'    => 'Введите название спойлера (не обязательно)',
'spoiler'           => 'Спойлер',
'youtube_window'    => 'Введите адрес ролика',
'youtube'           => 'youtube',
'translite'         => 'translit',
'php'               => 'php',
'html'              => 'html',
'js'                => 'js',
'hide'              => 'скрытый',
'url_window'        => 'Введите адрес ссылки',
'url'               => 'ссылка',
'img_alt'           => 'картинка',
'img'               => 'Введите адрес картинки',
'img_align'         => 'Выравнивание',
'email_window'      => 'Введите E-Mail адрес',
'email'             => 'письмо',
'info_window'       => 'Системная информация',
'info'              => 'информация',
'text_align_alt'    => 'Выравнивание текста',
'text_align'        => 'Выравнить по'

);

?>