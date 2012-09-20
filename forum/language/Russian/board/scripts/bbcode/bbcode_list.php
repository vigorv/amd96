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

// Файл /components/scripts/bbcode/bbcode_list.php

$lang = array(

// 2.2

'search'    => 'Поиск',

// 2.1

'b'         => 'Жирный',
'i'         => 'Курсив',
's'         => 'Перечёркнутый',
'u'         => 'Подчёркнутый',
'size'      => 'Размер шрифта',
'color'     => 'Цвет шрифта',
'quote'     => 'Цитата',
'smiles'    => 'Смайлы',
'font'      => 'Шрифт',
'spoiler'   => 'Спойлер',
'youtube'   => 'YouTube',
'translite' => 'Транслит',
'php'       => 'PHP',
'html'      => 'HTML',
'js'        => 'JavaScript',
'hide'      => 'Скрытый текст',
'url'       => 'Cсылка',
'img'       => 'Rартинка',
'email'     => 'E-Mail',
'text_align'     => 'Выравнивание текста: [left] (слева) [right] (справа) [center] (по центру)'

);

?>