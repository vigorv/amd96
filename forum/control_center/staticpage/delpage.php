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

$link_speddbar = "<a href=\"".$redirect_url."?do=staticpage\">Статические страницы</a>|Удаление страницы";
$control_center->header("Статические страницы", $link_speddbar);
$onl_location = "Статические страницы &raquo; Удаление страницы";

$control_center->errors = array ();

if ($id)
{
	$del = $DB->one_select ("*", "staticpage", "id = '{$id}'");
	if ($del['id'])
	{
		$DB->delete("id = '{$id}'", "staticpage");
		$cache->clear("", "config");
		$info = "<font color=red>Удаление</font> статической страницы: ".$del['name']." (".$del['title'].")";
        $info = $DB->addslashes($info);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$redirect_url}?do=staticpage" );
        exit();
	}
	else
	{
		$control_center->errors[] = "Выбранная статическая страница не найдена в базе данных. Возможна она была удалена или вы ошиблись.";
		$control_center->errors_title = "Ошибка!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "Вы не выбрали статическую страницу для удаления.";
	$control_center->errors_title = "Ошибка!";
	$control_center->message();
}

?>