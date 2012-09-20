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

$link_speddbar = "<a href=\"".$redirect_url."?do=staticpage\">����������� ��������</a>|�������� ��������";
$control_center->header("����������� ��������", $link_speddbar);
$onl_location = "����������� �������� &raquo; �������� ��������";

$control_center->errors = array ();

if ($id)
{
	$del = $DB->one_select ("*", "staticpage", "id = '{$id}'");
	if ($del['id'])
	{
		$DB->delete("id = '{$id}'", "staticpage");
		$cache->clear("", "config");
		$info = "<font color=red>��������</font> ����������� ��������: ".$del['name']." (".$del['title'].")";
        $info = $DB->addslashes($info);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$redirect_url}?do=staticpage" );
        exit();
	}
	else
	{
		$control_center->errors[] = "��������� ����������� �������� �� ������� � ���� ������. �������� ��� ���� ������� ��� �� ��������.";
		$control_center->errors_title = "������!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "�� �� ������� ����������� �������� ��� ��������.";
	$control_center->errors_title = "������!";
	$control_center->message();
}

?>