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

// ���� /components/modules/board/topic_new.php

$lang = array(

// 2.2

'metatitle_min'     => '����� ��� ����-���� title ������ {min} ��������.',
'metatitle_max'     => '����� ��� ����-���� title ������ {max} ��������.',
'metadescr_min'     => '����� ��� ����-���� description ������ {min} ��������.',
'metadescr_max'     => '����� ��� ����-���� description ������ {max} ��������.',
'metakeys_min'      => '����� ��� ����-���� keywords ������ {min} ��������.',
'metakeys_max'      => '����� ��� ����-���� keywords ������ {max} ��������.',

// 2.1

'title_min'         => '�������� ���� ������ {min} ��������.',
'title_max'         => '�������� ���� ������ {max} ��������.',
'desc_max'          => '�������� ���� ������ 200 ��������.',
'post_min'          => '����� ���� ������ {min} ��������.',
'post_max'          => '����� ���� ������ {max} ��������.',
'post_max_2'        => '���� ��������� ��������� ����������� �� ������ ������. ��� ����� ���� ������� � ���, ��� � ��������� ������������ BB�ode, ������� ����������� �������� ������ ��������� � ����.',
'no_name'           => '�� �� ����� ��� ��� ��� ����� ������� ������� (������ 40 ��������).',
'title_limit'       => '��������� ������ ������ 3-� �������� ��� ������ 200.',
'question_limit'    => '������ ������ ������ 3-� �������� ��� ������ 200.',
'answers_min'       => '������ ���� ��� ������� 2 �������� ������.',
'location'          => '�������� ����� ����',
'location_online'   => '������ ����� ���� �: <a href="{link}">{title}</a>',
'meta_info'         => '�������� ����� ����'

);

?>