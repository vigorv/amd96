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

// ���� /components/global/functions.php

$lang = array(

// 2.2

'attachment_download_link'              => '�������',
'hide_in_post_limit_posts'              => '<blockquote class="blockhide"><p><span class="titlehide">� ��� ������ ���� �� ����� <b>{num}</b> ������� ��� ��������� �������� ������.</span></p></blockquote>',
'hide_in_post_limit_group'              => '<blockquote class="blockhide"><p><span class="titlehide">������� ����� ������������ ������ ��� ���������� ����� �������������.</span></p></blockquote>',
'hide_in_post_limit_reg'                => '<blockquote class="blockhide"><p><span class="titlehide">� ������� ����������� ������ �������� ������ ������ �� ����� {days} ���� ��� ��������� �������� ������.</span></p></blockquote>',
'hide_in_post_limit_user'               => '<blockquote class="blockhide"><p><span class="titlehide">������� ����� ������������ ������ ��� ����������� �������������.</span></p></blockquote>',
'hide_in_post_limit_user_max'           => '<blockquote class="blockhide"><p><span class="titlehide">������� ������ ��� �������� ������, ���-�� ������������� �� ����� ���� ������ 100.</span></p></blockquote>',

// 2.1

'search_tag_preg'                       => '�����.',
'formatdate_today'                      => '�������, ',
'formatdate_yesterday'                  => '�����, ',
'message_info'                          => '<li>{text}</li>',
'message_back'                          => '<li><a href="javascript:history.go(-1)">��������� �����</a></li>',
'speedbar'                              => '<i>�� ����������</i>',
'forum_options_hide_topics'             => '<li><a href="{link}">�������� ������� ����</a></li>',
'forum_options_hide_posts'              => '<li><a href="{link}">���� �� �������� �����������</a></li>',
'forum_options_topics_open'             => '�������',
'forum_options_topics_close'            => '�������',
'forum_options_topics_hide'             => '������',
'forum_options_topics_publ'             => '������������',
'forum_options_topics_up'               => '�������',
'forum_options_topics_down'             => '��������',
'forum_options_topics_move'             => '�����������',
'forum_options_topics_union'            => '����������',
'forum_options_topics_subscribe'        => '�������� ���� �� ��������� ���',
'forum_options_topics_del'              => '�������',
'forum_options_topics_mas_p_hide'       => '������ ���������',
'forum_options_topics_mas_p_publ'       => '������������ ���������',
'forum_options_topics_mas_p_edit'       => '������������� ���������',
'forum_options_topics_mas_p_fix'        => '��������� ���������',
'forum_options_topics_mas_p_unfix'      => '��������� ���������',
'forum_options_topics_mas_p_union'      => '���������� ���������',
'forum_options_topics_mas_p_move'       => '����������� ���������',
'forum_options_topics_mas_p_del'        => '������� ���������',
'forum_options_topics_mas_t_unsubsc'    => '�������� ���� �� ����',
'forum_options_topics_mas_t_hide'       => '������ ����',
'forum_options_topics_mas_t_pub'        => '������������ ����',
'forum_options_topics_mas_t_edit'       => '������������� ����',
'forum_options_topics_mas_t_up'         => '������� ����',
'forum_options_topics_mas_t_down'       => '�������� ����',
'forum_options_topics_mas_t_open'       => '������� ����',
'forum_options_topics_mas_t_close'      => '������� ����',
'forum_options_topics_mas_t_move'       => '����������� ����',
'forum_options_topics_mas_t_del'        => '������� ����',
'member_publ_info1'                     => '���������� ��: {date}',
'member_publ_info2'                     => '���������� ��: ��������',
'forum_options_topics_author_edit'      => '������������� ����',
'forum_options_topics_author_open'      => '������� ����',
'forum_options_topics_author_close'     => '������� ����',
'forum_options_topics_author_hide'      => '������� (������) ����',
'send_new_pm_by'                        => '��������� �� <b>{name}</b><br /><br />',
'send_new_pm_title'                     => '����� ������ ���������.',
'topic_poll_logs'                       => '<li>{spisok} <span>({vote_num}/{num}% �������)</span><div><i style="width:{num}%;"></i></div></li>',
'share_links'                           => '���������� ������� ����� {title}',
'show_attach_off'                       => '<span class="attachment">������������� ��������� ����������� ���������� ������.</span>',
'show_attach_permission'                => '<span class="attachment">� ��� ������������ ���� ��� ���������� ������.</span>',
'show_attach_count'                     => ' ��������: {num}',
'hide_in_post_show_1'                   => '<blockquote class="blockhide"><p><span class="titlehide">������� �����:</span><span class="texthide">',
'hide_in_post_show_2'                   => '</span></p></blockquote>',
'hide_in_post_access_denied_group'      => '<blockquote class="blockhide"><p><span class="titlehide">����� ������ <b>{group}</b> �������� �������� �������� ������.</span></p></blockquote>',
'hide_in_post_limit'                    => '<blockquote class="blockhide"><p><span class="texthide">��� ��������� �������� ������ ����� {num} ���������.</span></p></blockquote>',
'topic_do_subscribe_answers'            => '����� ������ � ����: ',
'topic_do_subscribe_topic'              => '����: {link}',
'topic_do_subscribe_name'               => '<br />�����: {name}',
'topic_do_subscribe_date'               => '<br />�����: {date}',
'topic_do_subscribe_answers2'           => '����� ������ � ����.',
'online_members_first'                  => '<li>{info}</li>',
'online_members_next'                   => '<li>, {info}</li>',
'online_members_hide_loc'               => '�������������: ������ ������'

);

?>