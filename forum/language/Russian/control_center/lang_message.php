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

// ������ ������ ��������� �� ������, ���������� $lang_message

$lang = array(

'access_denied'             => '������ ������.',
'access_denied_to_page'     => '� ������ �������� ������ ������.',
'error'                     => '������!',
'unknow_error'              => '����������� ������.',
'flood_control'             => '���� ��������.',
'flood_control_stop'        => '�� ���� �������� ������������� �� {time} ������.',
'access_denied_speedbar'    => ' (������ ������)',
'access_denied_speedbar2'   => '<i>������</i> (������ ������)',
'no_act'                    => '�� ������� ��������.',
'no_secret_key'             => '�� ������ ��������� ����.',
'secret_key'                => '��������� ��������� ���� �� ��������� � ����� ������.',
'information'               => '����������.',
'page_not_found'            => '�������� �� �������.',
'access_denied_function'    => '������ � ������ ������� ������.',


);

?>