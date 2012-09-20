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
    
if(control_center_admins($member_cca['users']['users']))
{
    switch ($op)
    {
	   case "group":
            if(control_center_admins($member_cca['users']['group']))
                require LB_CONTROL_CENTER . '/users/group.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Группы";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Группы";
                control_center_admins_error();
            }
	   break;

	   case "editgroup":
            if(control_center_admins($member_cca['users']['group']) AND control_center_admins($member_cca['users']['group_edit']))
                require LB_CONTROL_CENTER . '/users/editgroup.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=group\">Группы</a>|Редактирование: <i>Доступ закрыт</i>";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Группы &raquo; Редактирование: <i>Доступ закрыт</i>";
                control_center_admins_error();
            }                
	   break;

        case "adduser":
            if(control_center_admins($member_cca['users']['add']))
                require LB_CONTROL_CENTER . '/users/adduser.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Добавленние пользователя";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Добавленние пользователя";
                control_center_admins_error();
            }
	   break;

	   case "ranks":
            if(control_center_admins($member_cca['users']['ranks']))
                require LB_CONTROL_CENTER . '/users/ranks.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Звания";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Звания";
                control_center_admins_error();
            }
	   break;

	   case "edit_ranks":
            if(control_center_admins($member_cca['users']['ranks']))
                require LB_CONTROL_CENTER . '/users/edit_ranks.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=ranks\">Звания</a>|Редактирование: <i>Доступ закрыт</i>";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Звания &raquo; Редактирование: <i>Доступ закрыт</i>";
                control_center_admins_error();
            }
	   break;
    
        case "cca":
            if(control_center_admins($member_cca['users']['cca']))
                require LB_CONTROL_CENTER . '/users/cca.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Ограничение прав к центру управления";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Ограничение прав к центру управления";
                control_center_admins_error();
            }
	   break;
    
        case "cca_add":
            if(control_center_admins($member_cca['users']['cca']))
                require LB_CONTROL_CENTER . '/users/cca_add.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=cca\">Ограничение прав к центру управления</a>|Добавление ограничения";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Ограничение прав к центру управления &raquo; Добавление ограничения";
                control_center_admins_error();
            }
	   break;
    
        case "cca_edit":
            if(control_center_admins($member_cca['users']['cca']))
                require LB_CONTROL_CENTER . '/users/cca_edit.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=cca\">Ограничение прав к центру управления</a>|Редактирование ограничения";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Ограничение прав к центру управления &raquo; Редактирование ограничения";
                control_center_admins_error();
            }
	   break;
       
       case "tools":
            if(control_center_admins($member_cca['users']['tools']))
                require LB_CONTROL_CENTER . '/users/tools.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Инструменты";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Инструменты";
                control_center_admins_error();
            }
	   break;
              
       case "delivery":
            if(control_center_admins($member_cca['users']['delivery']))
                require LB_CONTROL_CENTER . '/users/delivery.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Рассылка";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Рассылка";
                control_center_admins_error();
            }
	   break;

        case "delivery_new":
            if(control_center_admins($member_cca['users']['delivery_new']))
                require LB_CONTROL_CENTER . '/users/delivery_new.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=delivery\">Рассылка</a>|Новая";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Рассылка &raquo; Новая";
                control_center_admins_error();
            }
	   break;
       
       case "warning":
            if(control_center_admins($member_cca['users']['warning']))
                require LB_CONTROL_CENTER . '/users/warning.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Предупреждения";
                $control_center->header("Пользователи", $link_speddbar);
                $onl_location = "Пользователи &raquo; Предупреждения &raquo; Новая";
                control_center_admins_error();
            }
	   break;

	   default :
       
            if(control_center_admins($member_cca['users']['main']))
                require LB_CONTROL_CENTER . '/users/main.php';
            else
            {
                $control_center->header("Пользователи", "Пользователи");
                $onl_location = "Пользователи";
                control_center_admins_error();
            }
	   break;
    }
}
else
{
    $control_center->header("Пользователи", "Пользователи");
    $onl_location = "Пользователи";
    control_center_admins_error();
}

$control_center->footer(2);

?>