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

// ���� /components/modules/users/options.php

$lang = array(

'location'              => '<a href="{link}">�������: {name}</a>|��������� ������',
'location_online'       => '��������� ������: <a href="{link}">{name}</a>',
'meta_info'             => '��������� ������ � �������: {name}',
'email_error'           => '�� ������� ��������� ���� E-Mail.',
'email_max'             => '���� E-Mail ��������� ������������ ���������� ��������.',
'ip_error'              => '������� ��������� ���� ���������� �� IP.',
'pmtoemail_op_no'       => '���',
'pmtoemail_op_yes'      => '��',
'subscribe_op_pm'       => '��',
'subscribe_op_email'    => 'E-Mail',
'online_op_show'        => '�������',
'online_op_hide'        => '�������',
'commprofile_op_off'    => '���������',
'commprofile_op_on'     => '��������',
'commprofile_op_mess'   => '<font class="smalltext">������ ����� ��������� ���������������.</font>',
'location_error'        => '<i>������</i>',
'location_online_error' => '��������� ������: <i>������</i>',
'not_found'             => '������������ �� ������ � ���� ������ ��� � ��� ������������ ���� ��� �������������� ������� �������.',
'posts_ajax_no'         => '���������',
'posts_ajax_yes'        => '��������'

);

?>