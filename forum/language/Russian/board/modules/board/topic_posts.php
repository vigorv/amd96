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

// ���� /components/modules/board/topic_posts.php

$lang = array(

// 2.2

'no_posts_title'                => '��������� �� �������.',
'no_posts_descr'                => '�� ������ ������� �� ���� ������� ����������. <a href="{link}">��������� � ��������� ���� ��� �������</a>.',
'link_utility_1'                => '������ �������� ���������',
'link_utility_0'                => '��� ��������� ����',

// 2.1

'location_access_denied'        => '<i>������</i> (������ ������)',
'location_online_access_denied' => '������������� ����: <i>������</i> (������ ������)',
'access_denied_group_v'         => '����� ������ <b>{group}</b> ��������� ������������� ������ �����.',
'access_denied_group_r'         => '����� ������ <b>{group}</b> ��������� ������ ���� � ������ ������.',
'access_denied_group_h'         => '��� ��������� ������ ������� ����.',
'forum_wrong_pass'              => '�� ����� �������� ������ ��� ������� ������. ���������� ��� ���.',
'forum_pass'                    => '��� ����� ������ ������ ��� ��������� ������� ������ � ��� ���.',
'location_pass'                 => '������ ������',
'location_online_pass'          => '������������� ����: ������ ������',
'access_denied_close'           => '��� ��������� �������� � �������� �����.',
'access_denied_answer'          => '��� ��������� �������� � �����.',
'forum_is_basket'               => '������ ����� �������� �������� � � ����� ������ ��������.',
'post_text_min'                 => '����� ��������� ������ {min} ��������.',
'post_text_max'                 => '����� ��������� ������ {max} ��������.',
'post_text_max_2'               => '���� ��������� ��������� ����������� �� ������ ������. ��� ����� ���� ������� � ���, ��� � ��������� ������������ BB�ode, ������� ����������� �������� ������ ��������� � ����.',
'no_name'                       => '�� �� ����� ��� ��� ��� ����� ������� ������� (������ 40 ��������).',
'access_denied_forum_answer'    => '��� ��������� �������� � ����� ������� ������.',
'location'                      => '{title}',
'location_online'               => '������������� ����: <a href="{link}">{title}</a>',
'topic_title_close'             => '���� �������.',
'topic_info_close'              => '<li>������ ���� �������. �� �� ������ �������� � ���.</li>',
'topic_info_answer'             => '<li>��� ��������� �������� � ����� ������� ������.</li>',
'topic_info_publ'               => '<li>��� ��������� �������� � �����.</li><li>{info}</li>',
'topic_info_basket'             => '<li>������ ����� �������� �������� � � ����� ������ ��������.</li>',
'topic_info_answer_other'       => '<li>����� ������ <b>{group}</b> �������� ����� � ����� �����.</li>',
'topic_info_close_answer'       => '<li>������ ���� �������. �� �� ������ � ��� ��������.</li>',
'do_fav_add'                    => '�������� � ���������',
'do_fav_del'                    => '������� �� ����������',
'do_subs_add'                   => '����������� �� ����',
'do_subs_del'                   => '�������� ��������',
'not_found_title'               => '���� �� �������.',
'not_found_info'                => '��������� ���� ���� �� ������� � ���� ������.'

);

?>