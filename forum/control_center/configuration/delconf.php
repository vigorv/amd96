<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|�������� ���������";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; �������� ���������";

$control_center->errors = array ();

if ($id)
{
	$confdel = $DB->one_select ("*", "configuration", "conf_id = '{$id}'");
	if ($confdel['conf_id'] AND !$confdel['conf_protect'])
	{
		$DB->delete("conf_id = '{$id}'", "configuration");
		$cache->clear("", "config");
		$info = "<font color=red>��������</font> ���������: ".$DB->addslashes($confdel['conf_name']);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$_SESSION['back_link_conf']}" );
        exit();
	}
    elseif ($confdel['conf_id'] AND $confdel['conf_protect'])
    {
        $control_center->errors[] = "��������� ��������� ��������, � ������ �������� ��� �������.";
		$control_center->errors_title = "������ ������.";
		$control_center->message();  
    }
	else
	{
		$control_center->errors[] = "��������� ��������� �� ������� � ���� ������. �������� ��� ���� ������� ��� �� ��������.";
		$control_center->errors_title = "������!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "�� �� ������� ��������� ��� ��������.";
	$control_center->errors_title = "������!";
	$control_center->message();
}

?>