<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

switch ($op)
{
	case "add":
		require LB_CONTROL_CENTER . '/staticpage/addpage.php';
	break;

	case "edit":
		require LB_CONTROL_CENTER . '/staticpage/editpage.php';
	break;

	case "del":
		require LB_CONTROL_CENTER . '/staticpage/delpage.php';
	break;

	default :
		include_once LB_CONTROL_CENTER . '/staticpage/main.php';
	break;
}

$control_center->footer(7);

?>