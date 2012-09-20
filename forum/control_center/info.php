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

$lang_info = language_forum ("control_center/info");

if(control_center_admins($member_cca['system']['system']))
{
    switch ($op)
    {
	   case "cache":
            if(control_center_admins($member_cca['system']['cache']))
                include_once LB_CONTROL_CENTER . '/info/cache.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=system", $lang_info['cache_speedbar']);
                $control_center->header($lang_info['header'], $link_speddbar);
                $onl_location = $lang_info['cache_online'];
                control_center_admins_error();
            }
	   break;
    
       case "rebuild":
            if(control_center_admins($member_cca['system']['rebuild']))
                include_once LB_CONTROL_CENTER . '/info/rebuild.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=system", $lang_info['rebuild_speedbar']);
                $control_center->header($lang_info['header'], $link_speddbar);
                $onl_location = $lang_info['rebuild_online'];
                control_center_admins_error();
            }
	   break;      

	   default :
            if(control_center_admins($member_cca['system']['info']))
                include_once LB_CONTROL_CENTER . '/info/main.php';
            else
            {
                $control_center->header($lang_info['header'], $lang_info['header']);
                $onl_location = $lang_info['header'];
                control_center_admins_error();
            }
	   break;
    }
}
else
{
    $control_center->header($lang_info['header'], $lang_info['header']);
    $onl_location = $lang_info['header'];
    control_center_admins_error();
}

if (!isset($_SESSION['back_link_info']))
    $_SESSION['back_link_info'] = $redirect_url."?do=system";

$control_center->footer(6);

?>