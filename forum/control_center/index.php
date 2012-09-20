<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

@session_start ();
@ob_start ();
@ob_implicit_flush ( 0 );

@error_reporting ( E_ERROR );

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LogicBoard_ADMIN', true );
define ( 'LB_MAIN', realpath("../") );

require_once LB_MAIN.'/control_center/system.php';

if(!$logged)
{
	$lang_index = language_forum ("control_center/lang_index");
	$control_center->login_panel($lang_index['login']);
}

$tpl->template_parse_time = 0;
GzipOut (1);
?>