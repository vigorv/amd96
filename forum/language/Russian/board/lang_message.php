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

// ������ ������ ��������� �� ������

$lang = array(

'access_denied'             => '������ ������.',
'access_denied_to_page'     => '� ������ �������� ������ ������.',
'error'                     => '������!',
'unknow_error'              => '����������� ������.',
'request_is_accepted'       => '������ ������.',
'flood_control'             => '���� ��������.',
'flood_control_stop'        => '�� ���� �������� ������������� �� {time} ������.',
'access_denied_speedbar'    => ' (������ ������)',
'access_denied_speedbar2'   => '<i>������</i> (������ ������)',
'no_act'                    => '�� ������� ��������.',
'no_secret_key'             => '�� ������ ��������� ����.',
'secret_key'                => '��������� ��������� ���� �� ��������� � ����� ������.',
'not_logged'                => '�� �� ������������ �� ������.',
'captcha'                   => '�� �� ����� ��� ��� ����� ��� �������.',
'keystring'                 => '�� ������� �������� �� ������.',
'change_captcha'            => '������� ��������',
'information'               => '����������.',
'text_is_hide'              => '<blockquote class="blockhide"><p><span class="titlehide">������� �����.</span></p></blockquote>',
'topic_alt_close'           => '���� �������.',
'topic_alt_hot'             => '������� ����.',
'topic_alt'                 => '������� ����.',
'access_denied_function'    => '������ � ������ ������� ������.',
'control_center'            => '����� ����������',
'warning'                   => '��������������.',
'ip_hide'                   => '�����',
'spoiler_title'             => '������� [+]',
'quote_title'               => '������:',
'quote_title2'              => '�����:',
'member_sex_male'           => '�������',
'member_sex_female'         => '�������',
'chpu_error'                => '�� ������� ������ ������� ���������� �� �������.'

);

?>