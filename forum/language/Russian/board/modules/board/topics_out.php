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

// ���� /components/modules/board/topics_out.php

$lang = array(

'member_not_found'          => '��������� ������������ �� ������ � ���� ������.',
'member_location_online_no' => '������������� ��� ����: <i>������������ �� ������</i>',
'member_location'           => '<a href="{link}">�������: {name}</a>|��� ����',
'member_location_online'    => '������������� ��� ����: <a href="{link}">{name}</a>',
'member_title_module'       => '��� ����: {name}',
'member_meta_info'          => '��� ���� � �������: {name}',
'last_location_online'      => '������������� ��������� ����',
'last_location'             => '��������� ����',
'last_title_module'         => '��������� ����',
'last_meta_info'            => '��������� ����',
'active_location_online'    => '������������� �������� ����',
'active_location'           => '�������� ���� �� ��������� 24 ����',
'active_title_module'       => '�������� ���� �� ��������� 24 ����',
'active_meta_info'          => '�������� ���� �� ��������� 24 ����',
'topics_not_found'          => '���� �� �������.'

);

?>