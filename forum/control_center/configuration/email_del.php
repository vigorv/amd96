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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|<a href=\"".$redirect_url."?do=configuration&op=email\">������� E-mail �����������</a>|�������� �������";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������� E-mail ����������� &raquo; �������� �������";

$control_center->errors = array ();

if ($id)
{
	$del = $DB->one_select ("*", "templates_email", "id = '{$id}'");
	if ($del['id'] AND !$del['protect'])
	{
		$DB->delete("id = '{$id}'", "templates_email");
        $cache->clear("template", "email_template");
        
		$info = "<font color=red>��������</font> ������� E-mail �����������: ".$DB->addslashes($$del['title']);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=configuration&op=email" );
        exit();
	}
	else
	{
		$control_center->errors[] = "��������� ������ E-mail ����������� �� ������ � �� ��� �� �������.";
		$control_center->errors_title = "������!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "�� �� ������� ������ E-mail ����������� ��� ��������.";
	$control_center->errors_title = "������!";
	$control_center->message();
}

?>