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

if(control_center_admins($member_cca['logs']['logs']))
{
    switch ($op)
    {
	   case "login":
            if(control_center_admins($member_cca['logs']['login']))
                require LB_CONTROL_CENTER . '/logs/login.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Логи авторизации";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; Логи авторизации";
                control_center_admins_error();
            }
	   break;

	   case "files":
            if(control_center_admins($member_cca['logs']['files']))
                require LB_CONTROL_CENTER . '/logs/files.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Прямые обращения к файлам";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; Прямые обращения к файлам";
                control_center_admins_error();
            }
	   break;

	   case "actions":
            if(control_center_admins($member_cca['logs']['action']))
                require LB_CONTROL_CENTER . '/logs/actions.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Действия в центре управления";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; Действия в центре управления";
                control_center_admins_error();
            }
	   break;

	   case "mysql_errors":
            if(control_center_admins($member_cca['logs']['mysql']))
                require LB_CONTROL_CENTER . '/logs/mysql_errors.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|MySQL ошибки";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; MySQL ошибки";
                control_center_admins_error();
            }
	   break;
    
       case "blocking":
            if(control_center_admins($member_cca['logs']['ban']))
                require LB_CONTROL_CENTER . '/logs/blocking.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Блокировка и ограничения пользователей";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; Блокировка и ограничения пользователей";
                control_center_admins_error();
            }
	   break;
              
       case "topics":
            if(control_center_admins($member_cca['logs']['topics']))
                require LB_CONTROL_CENTER . '/logs/topics.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Действия с темами";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; Действия с темами";
                control_center_admins_error();
            }
	   break;
       
       case "posts":
            if(control_center_admins($member_cca['logs']['posts']))
                require LB_CONTROL_CENTER . '/logs/posts.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Действия с сообщениями";
                $control_center->header("Журнал логов", $link_speddbar);
                $onl_location = "Журнал логов &raquo; Действия с сообщениями";
                control_center_admins_error();
            }
	   break;

	   default :
		  include_once LB_CONTROL_CENTER . '/logs/main.php';
	   break;
    }
}
else
{
    $control_center->header("Журнал логов", "Журнал логов");
    $onl_location = "Журнал логов";
    control_center_admins_error();
}

$control_center->footer(4);

?>