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

// ���� /components/modules/board/forum.php

$lang = array(

'closed_forum'      => '<i>�������� �����</i>',
'last_title_none'   => '-----',
'last_member_none'  => '-----',
'location_online'   => '������������� �����: {forum}',
'no_topics'         => '� ������ ������ ���� �� �������. ��� ����� ���� ������� � ���, ��� ���� ��� ����� �� �������� ��� ���� � ��� ���������� ������� ��� ������ ���.',
'forum_read'        => '����� ��������',
'forum_unread'      => '����� �� ��������'

);

?>