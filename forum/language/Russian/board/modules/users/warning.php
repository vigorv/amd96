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

// ���� /components/modules/users/warning.php

$lang = array(

'location'              => '<a href="{link}">�������: {name}</a>|������� ��������������',
'location_online'       => '������� ��������������: <a href="{link}">{name}</a>',
'meta_info'             => '������� �������������� � �������: {name}',
'not_found'             => '������������ �� ������.',
'access_denied_history' => '�� �� ������ ������������� ������� �������������� ������������, ������������ � ������ <b>{group}</b>',
'level'                 => '��� ������� �������������� ��� ������. ������� �������: {num}',
'pm_title'              => '�������� ��������������.',
'status_on'             => '<font color=red>�������</font>',
'status_off'            => '<font color=green>�������</font>',
'option_1'              => '����� ��������������',
'option_2'              => '������� ��������������',
'no_warnings'           => '� ������������ <b><a href="{link}">{name}</a></b> ��� �������� ��������������.'

);

?>