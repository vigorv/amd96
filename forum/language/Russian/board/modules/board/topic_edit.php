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

// ���� /components/modules/board/topic_edit.php

$lang = array(

// 2.2

'access_denied_time'            => '��� ��������� ��������� ��� ��������� ����, ���� � ������� � ���������� ������ ����� {info} �.',

// 2.1

'access_denied_group_f'         => '����� ������ <b>{group}</b> ��������� ������������� ������ �����.',
'access_denied_group_t'         => '����� ������ <b>{group}</b> ��������� ������ ���� � ������ ������.',
'forum_pass'                    => '����� ������ �������. ������� ������� ������ � ������, ����� �� ������� ����������� �� ����.',
'hide_topic'                    => '����� ������ <b>{group}</b> ��������� ������ ������� ���� �� ������� ������.',
'no_topic'                      => '�� ������� ����.',
'location_access_denied'        => '�������������� ����',
'location_online_access_denied' => '�������������� ����',
'access_denied_group_edit'      => '��� ��������� ������������� ����.',
'not_found'                     => '��������� ���� �� �������.',
'meta_info'                     => '��������� ���� � {title}',
'location'                      => '<a href="{link}">{title}</a>|��������� ����',
'location_online'               => '��������� ����: <a href="{link}">{title}</a>',
'rights_hide'                   => '� ��� ������������ ����, ����� ������ ���� � ������ ������.',
'rights_publ'                   => '� ��� ������������ ����, ����� ������������ ���� � ������ ������.',
'access_denied_edit'            => '��� ��������� ������������� ������ ����.',
'rights_fix'                    => '� ��� ������������ ����, ����� ���������� ���� � ������ ������.',
'rights_unfix'                  => '� ��� ������������ ����, ����� ���������� ���� � ������ ������.',
'rights_open'                   => '� ��� ������������ ����, ����� ��������� ���� � ������ ������.',
'rights_close'                  => '� ��� ������������ ����, ����� ������� ���� � ������ ������.',
'rights_move'                   => '� ��� ������������ ����, ����� ���������� ���� � ������ ������.',
'move_same_forum'               => '�� �� ������ ��������� ���� � ��� �� �����.',
'move_no_forum'                 => '�� �� ������� �����.',
'move_category'                 => '�� �� ������ ����������� ���� � ���������. �������� �����.',
'move_logs'                     => '���� ���������� � ����� "{title}" (ID: {id}).',
'rights_del'                    => '� ��� ������������ ����, ����� ������� ���� � ������ ������.',
'rights_subscribe'              => '� ��� ������������ ����, ����� �������� ���� �� ��������� ����.'

);

?>