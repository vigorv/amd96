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

// ���� board.php

$lang = array(

'header'                    => '�����',
'addcategory_speedbar'      => '<a href="{link}">�����</a>|���������� ���������',
'addcategory_online'        => '����� &raquo; ���������� ���������',
'addforum_speedbar'         => '<a href="{link}">�����</a>|���������� ������',
'addforum_online'           => '����� &raquo; ���������� ������',
'editforum_speedbar'        => '<a href="{link}">�����</a>|�������������� ���������: {title}',
'editforum_online'          => '����� &raquo; �������������� ���������: {title}',
'editforum_speedbar_2'      => '<a href="{link}">�����</a>|�������������� ������: {title}',
'editforum_online_2'        => '����� &raquo; �������������� ������: {title}',
'delforum_speedbar'         => '<a href="{link}">�����</a>|�������� ���������/������',
'delforum_online'           => '����� &raquo; �������� ���������/������',
'moderators_speedbar'       => '<a href="{link}">�����</a>|����������',
'moderators_online'         => '����� &raquo; ����������',
'moder_add_speedbar'        => '<a href="{link}">�����</a>|<a href="{link_2}">����������</a>|���������� ����������',
'moder_add_online'          => '����� &raquo; ���������� &raquo; ���������� ����������',
'moder_edit_speedbar'       => '<a href="{link}">�����</a>|<a href="{link_2}">����������</a>|�������������� ����������',
'moder_edit_online'         => '����� &raquo; ���������� &raquo; �������������� ����������',
'words_filter_speedbar'     => '<a href="{link}">�����</a>|������ ����',
'words_filter_online'       => '����� &raquo; ������ ����',
'notice_speedbar'           => '<a href="{link}">�����</a>|����������',
'notice_online'             => '����� &raquo; ����������',
'notice_add_speedbar'       => '<a href="{link}">�����</a>|<a href="{link_2}">����������</a>|����������',
'notice_add_online'         => '����� &raquo; ���������� &raquo; ����������',
'notice_edit_speedbar'      => '<a href="{link}">�����</a>|<a href="{link_2}">����������</a>|��������������',
'notice_edit_online'        => '����� &raquo; ���������� &raquo; ��������������',
'sharelink_speedbar'        => '<a href="{link}">�����</a>|������� ����������',
'sharelink_online'          => '����� &raquo; ������� ����������',
'sharelink_add_speedbar'    => '<a href="{link}">�����</a>|<a href="{link_2}">������� ����������</a>|����������',
'sharelink_add_online'      => '����� &raquo; ������� ���������� &raquo; ����������',
'sharelink_edit_speedbar'   => '<a href="{link}">�����</a>|<a href="{link_2}">������� ����������</a>|��������������',
'sharelink_edit_online'     => '����� &raquo; ������� ���������� &raquo; ��������������'

);

?>