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

// ���� info.php

$lang = array(

'header'            => '�������',
'cache_speedbar'    => '<a href="{link}">�������</a>|������ ������ ����',
'cache_online'      => '������� &raquo; ������ ������ ����',
'rebuild_speedbar'  => '<a href="{link}">�������</a>|�������� ������',
'rebuild_online'    => '������� &raquo; �������� ������'

);

?>