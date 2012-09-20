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

$lang_complaint = language_forum ("control_center/complaint");

if(control_center_admins($member_cca['complaint']['complaint']))
{
    switch ($op)
    {
	   default :
		  include_once LB_CONTROL_CENTER . '/complaint/main.php';
	   break;
    }
}
else
{
    $control_center->header($lang_complaint['header'], $lang_complaint['header']);
    $onl_location = $lang_complaint['header'];
    control_center_admins_error();
}

$control_center->footer(8);

?>