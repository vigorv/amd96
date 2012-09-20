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
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
	exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
}

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|<a href=\"".$redirect_url."?do=configuration&op=email\">Шаблоны E-mail уведомлений</a>|Удаление шаблона";
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Шаблоны E-mail уведомлений &raquo; Удаление шаблона";

$control_center->errors = array ();

if ($id)
{
	$del = $DB->one_select ("*", "templates_email", "id = '{$id}'");
	if ($del['id'] AND !$del['protect'])
	{
		$DB->delete("id = '{$id}'", "templates_email");
        $cache->clear("template", "email_template");
        
		$info = "<font color=red>Удаление</font> шаблона E-mail уведомления: ".$DB->addslashes($$del['title']);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=configuration&op=email" );
        exit();
	}
	else
	{
		$control_center->errors[] = "Выбранный шаблон E-mail уведомлений не найден в БД или он защищён.";
		$control_center->errors_title = "Ошибка!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "Вы не выбрали шаблон E-mail уведомления для удаления.";
	$control_center->errors_title = "Ошибка!";
	$control_center->message();
}

?>