<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

// ���� /components/global/login.php

$lang = array(

// 2.2

'ulogin_name'       => '����� ����� ��� ���� � ���� ������. ���������� �������� ��� ������� � ���. ���� � ��������� ������� �����������.',
'ulogin_email'      => '����� E-Mail {mail} ��� ���� � ���� ������. ���� �� �� ������� ������/������ �� ����� ������� ������ - �������������� �������� �������������� ������.',
'ulogin_name_min'   => '����� ������ ������ {num} ��������.',
'ulogin_name_max'   => '����� ������ ������ {num} ��������.',

// 2.1

'no_pass'       => '�� �� ����� ������.',
'no_name'       => '�� �� ����� �����.',
'name_symbols'  => '����� ��������� ����������� �������.',
'name_min'      => '����� ������ ������ {num} ��������.',
'block'         => '�� ������� ������������� ��-�� �������� ���������� ������� �����������.',
'block2'        => '���������� �� {min} �����, ��������� ������� ����: {date}',
'no_member_id'  => '������������ �� ������. �������� �� ����� ������� ����� ��� ������.',
'no_member_id_2'=> '������������ �� ������. �������� �� ����� ������� ����� ��� ������.',
'block_ip'      => '��� IP ����� �� ��������� � ��������� ������� IP ������� � ���������� ������. �� ��������� E-Mail ���� ���������� ��������� � ����������� ������������� ������� IP.'

);

?>