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

// ���� /components/modules/board/notice.php

$lang = array(

'location'          => '����������: {title}',
'location_online'   => '������������� ���������� <a href="{link}">{title}</a>',
'meta_info'         => '����������: {title}'

);

?>