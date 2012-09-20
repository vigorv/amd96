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

// ���� /components/scripts/ajax/complaint.php

$lang = array(

'access_denied_forum'   => '��� ��������� ������������� ������ �����.',
'access_denied_read'    => '��� ��������� ������ ���� � ������ ������.',
'access_denied_pass'    => '����� ������ �������. ������� ������� ������ � ������, ����� �� ������� �������� ���� � ���������.',
'access_denied_hide'    => '��� ��������� ������ ������� ���� �� ������� ������.',
'error_title'           => '������!',
'error_info'            => '��������� ���� �� �������.',
'error_post_member_id'  => '�� �� ������ ������������ �� ��� �� ���������.',
'text_max'              => '������� ������� ���������, ������ 2 ���. ��������.',
'time_limit'            => '����� ���� ����� �� ��� ���������� �� ������ ���������.',
'done_title'            => '�������� ��������.',
'done_info'             => '���� ������ ����������.',
'pm_text'               => '������������ <b>{name}</b> ����������� �� ��������� ID <b>{id}</b> (�����: <b>{post_name}</b>; ����: {post_date}) � ���� "<a href="{topic_link}">{topic_title}</a>"<br /><br />����� ������:<br /><br />{text}',
'pm_title'              => '������ � ����: {title}'

);

?>