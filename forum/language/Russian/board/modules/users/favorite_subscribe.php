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

// ���� /components/modules/users/favorite_subscribe.php

$lang = array(

'fav_location'          => '<a href="{link}">�������: {name}</a>|���������',
'fav_location_online'   => '������������� ���������.',
'fav_meta_info'         => '��������� � �������: {name}',
'fav_title'             => '��������� ����',
'subs_location'         => '<a href="{link}">�������: {name}</a>|�������� �� ����',
'subs_location_online'  => '������������� �������� �� ����.',
'subs_meta_info'        => '�������� �� ���� � �������: {name}',
'subs_title'            => '�������� �� ����',
'fav_empty'             => '�� ������ �� ��������� � ���������.',
'subs_empty'            => '�� �� ������������� �� ����.',
'not_found'             => '���� �� �������.'

);

?>