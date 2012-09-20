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

// ���� /components/class/upload_files.php

$lang = array(

// 2.2

'mini_file'                 => '������ ����',
'open_file'                 => '������� ����',
'files_table_name'          => '����',
'files_table_size'          => '������',
'files_table_addinpost'     => '� ���������',
'add_in_post_title'         => '�������� ���� � ���������.<br />�� ������ ��������� ����� ��� ��������, ��������: [attachment=XX|�� ��������]',

// 2.1

'not_logged'                => '�� �� ������������.',
'no_forum_id'               => '�� ������ �����.',
'upload_off'                => '�������� ������ ���������.',
'max_size'                  => '������ ����� ������ {size} ��.',
'access_denied_group'       => '����� ������ <b>{group}</b> ��������� ��������� ����� � ������ ������.',
'no_file_extension'         => '�� ������� ���������� ���������� �����.',
'denied_file_extension'     => '������ ��� ���������� �� ��������������: {type}',
'create_folder'             => '�� ������� ������� �����: /attachment/{folder}',
'denied_folder'             => '��� ���� �� ������ � �����: /attachment/{folder}',
'download_error'            => '��������� ��������� ����.',
'add_in_post'               => '��������',
'del_file'                  => '<font color=red>�������</font>',
'no_file_id'                => '�� ������ ����.',
'secret_key'                => '��������� ��������� ���� �� ��������� � ����� ������.',
'not_enough_rights'         => '� ��� ������������ ���� ��� �������� ������.',
'file_is_not_found'         => '�������� ���� �� ������.'

);

?>