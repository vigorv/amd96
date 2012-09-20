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

$lang_infopage = language_forum ("control_center/infopage");

switch ($op)
{
	case "logs":
		include_once LB_CONTROL_CENTER . '/infopage/logs.php';
	break;

	case "cache":
        if(control_center_admins($member_cca['system']['cache']))
		      include_once LB_CONTROL_CENTER . '/infopage/cache.php';
        else
        {
            $link_speddbar = str_replace("{link}", $redirect_url."?do=system", $lang_infopage['cache_speedbar']);
            $control_center->header($lang_infopage['header'], $link_speddbar);
            $onl_location = $lang_infopage['cache_online'];
            control_center_admins_error();
        }
	break;

	default :
		$control_center->header($lang_infopage['header_infopage']);
		$control_center->errors = array ();
		$control_center->errors[] = $lang_infopage['error_infopage'];
		$control_center->errors_title = $lang_message['page_not_found'];
		$control_center->message();
		$control_center->footer();
	break;
}

?>