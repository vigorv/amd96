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

// ���� /components/modules/board/posts_out.php

$lang = array(

'location_online'   => '������������� ��� ���������: {user}',
'not_found_user'    => '��������� ������������ �� ������ � ���� ������.',
'location_profile'  => '<a href="{link}">�������: {user}</a>',
'location1'         => '��� ���������',
'title_module1'     => '��� ���������: {user}',
'meta_info1'        => '��� ��������� � �������: {user}',
'location_online'   => '������������� ��������� ������',
'not_found_posts'   => '��������� �� �������.',
'location2'         => '��������� ������',
'title_module2'     => '��������� ������',
'meta_info2'        => '��������� ������'

);

?>