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

// ���� /components/modules/users/edit_status.php

$lang = array(

'location'              => '<a href="{link}">�������: {name}</a>|�������������� �������',
'location_online'       => '�������������� �������: <a href="{link}">{name}</a>',
'meta_info'             => '�������������� ������� � �������: {name}',
'access_denied_group'   => '����� ������ <b>{group}</b> ��������� ������������� ������ ��������.',
'status_max'            => '����� ������� ��������� {max} ��������.',
'location_error'        => '<i>������</i>',
'location_online_error' => '�������������� �������: <i>������</i>',
'not_found'             => '������������ �� ������ � ���� ������ ��� � ��� ������������ ���� ��� �������������� ������� �������.'

);

?>