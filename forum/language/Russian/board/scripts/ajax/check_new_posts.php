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

// ���� /components/modules/users/check_new_posts.php

$lang = array(

'access_denied'         => '������ ������.',
'access_denied_read'    => '��� ��������� ������ ���� � ������ ������.',
'access_denied_forum'   => '��� ��������� ������������� ������ �����.',
'access_denied_hide'    => '��� ��������� ������ ������� ����.',
'access_denied_pass'    => '��� ����� ������ ������, ��� ��������� ������� ������ � ��� ���.',
'new_posts_title'       => '�������� ���������.',
'new_posts_info'        => '��������� ����� ��������� �� ��������.',
'not_found_title'       => '���� �� �������.',
'not_found_info'        => '��������� ���� ���� �� ������� � ���� ������.'

);

?>