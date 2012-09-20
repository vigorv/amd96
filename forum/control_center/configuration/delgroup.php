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

$control_center->errors = array ();

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|Удаление группы";
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Удаление группы";

if ($id)
{
	$confdel = $DB->one_select ("*", "configuration_group", "conf_gr_id = '{$id}'");
	if ($confdel['conf_gr_id'])
	{
		$confcount = $DB->one_select ("COUNT(*) as count", "configuration", "conf_group = '{$confdel['conf_gr_id']}'");
		if ($confcount['count'] > 0)
		{
			$control_center->errors[] = "В выбранной группе присутствуют настройки. Для удаления группы вам сначала нужно удалить или перенести все действующие настройки.";
			$control_center->errors_title = "Ошибка!";
			$control_center->message();
		}
		else
		{
			$DB->delete("conf_gr_id = '{$id}'", "configuration_group");
			$info = "<font color=red>Удаление</font> группы настроек: ".$DB->addslashes($confdel['conf_gr_name']);
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: {$_SESSION['back_link_conf']}" );
            exit();
		}
	}
	else
	{
		$control_center->errors[] = "Выбранная группа настроек не найдена в базе данных. Возможна она была удалена или вы ошиблись.";
		$control_center->errors_title = "Ошибка!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "Вы не выбрали группу настроек для удаления.";
	$control_center->errors_title = "Ошибка!";
	$control_center->message();
}

?>