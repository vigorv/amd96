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

// ���� /components/modules/board/favorite.php

$lang = array(

'topic_not_found'       => '��������� ���� �� �������.',
'location'              => '<a href="{link}">{title}</a>|���������',
'location_online'       => '������������� ����: <a href="{link}">{title}</a>'

);

?>