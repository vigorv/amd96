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

// ���� /components/modules/users/check_new_pm.php

$lang = array(

'list'              => '<br />{date}; �����: {name}; <a href="{link}">{title}</a>',
'mess_title_no'     => '����������.',
'mess_info_no'      => '����� ��������� ���.',
'mess_title_yes'    => '���� ����� ���������.',
'mess_info_yes'     => '����� ����� ���������: {num}'

);

?>