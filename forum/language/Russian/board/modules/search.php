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

// ���� /components/modules/search.php

$lang = array(

'location'                  => '�����',
'location_result'           => '����������',
'access_denied_group'       => '����� ������ <b>{group}</b> ��������� ������������ �������.',
'allforums'                 => '��� ������',
'type_topics_posts'         => '��������� ��� � ����������',
'type_topics'               => '��������� ���',
'type_posts'                => '����������',
'preview_topics'            => '��� ����',
'preview_posts'             => '��� ���������',
'sort_result_last_answer'   => '���������� ������',
'sort_result_title'         => '���������',
'sort_result_num_aswers'    => '���-�� �������',
'sort_result_num_views'     => '���-�� ����������',
'sort_order_DESC'           => '��������',
'sort_order_ASC'            => '�����������',
'mod_forum'                 => '����������',
'mod_members'               => '������������',
'members_allgroups'         => '��� ������',
'sort_result_m_name'        => '����',
'sort_result_m_reg'         => '���� �����������',
'sort_result_m_last'        => '���� ���������� ���������',
'sort_result_m_posts'       => '���-�� �������',
'word_len'                  => '�������� ����� ������ {num} ��������.',
'no_forum_id'               => '�� �� ������� ����� ��� ������� ��������������.',
'access_denied_forum'       => '��� ��������� ������ � ������ ������.',
'no_group_id'               => '��������� ������ �� ����������.',
'result'                    => '���������� ������: ',
'no_data_members'           => '�� �� ������� �� ������ ��������(��) ������������ ����� �� �������������.',
'empty'                     => '����� �� ��� �����������. ���������� �������� ��������� ������.'

);

?>