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

// ���� /components/modules/board.php

$lang = array(

'no_forum_id'                               => '�� �� ������� ��������� ��� �����.',
'access_denied_group_forumcat'              => '����� ������ <b>{group}</b> �������� �������� ������� ������ ��� ���������.',
'wrong_password_forum'                      => '�� ����� �������� ������ ��� ������� ������. ���������� ��� ���.',
'write_password_forum'                      => '��� ����� ������ ������, ��� ��������� ������� ������.',
'access_denied_group_newtopic'              => '����� ������ <b>{group}</b> ��������� �������� ����� ���.',
'access_denied_group_newtopic_in_forum'     => '����� ������ <b>{group}</b> ��������� �������� ���� � ������ ������.',
'location_forum'                            => '������������� �����: {forum}',
'location_newtopic'                         => '������ ����� ���� �: ',
'access_denied_user_newtopic'               => '��� ��������� ��������� ����.{info}',
'basket_forum'                              => '������ ����� �������� �������� � � �� ������ ��������� ����� ����',
'no_topic_id'                               => '�� �� ������� ����.',
'no_post_id'                                => '�� �� ������� ���������.',
'no_moder'                                  => '�� �� ��������� �����������.',
'no_notice_id'                              => '�� �� ������� ����������.',
'no_active_notice_id'                       => '������ ���������� �� �������.',
'access_denied_notice'                      => '��� ��������� ������������� ��� ����������.'

);

?>