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

if (isset ( $_REQUEST['op'] ))
	$op = totranslit ( $_REQUEST['op'] );
else
	$op = "";

switch ($op)
{
	case "add":
		require LB_CONTROL_CENTER . '/adt/addadt.php';
	break;

	case "edit":
		require LB_CONTROL_CENTER . '/adt/editadt.php';
	break;

	default :
		include_once LB_CONTROL_CENTER . '/adt/main.php';
	break;
}

$control_center->footer(7);

?>