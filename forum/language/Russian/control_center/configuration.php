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

// ���� configuration.php

$lang = array(

'header'                => '���������',
'addgroup_speedbar'     => '<a href="{link}">���������</a>|���������� ������ ��������',
'addgroup_online'       => '��������� &raquo; ���������� ������ ��������',
'editgroup_speedbar'    => '<a href="{link}">���������</a>|<a href="{link_2}">������: {title}</a>|��������������',
'editgroup_online'      => '��������� &raquo; ������: {title} &raquo; ��������������',
'delgroup_speedbar'     => '<a href="{link}">���������</a>|�������� ������',
'delgroup_online'       => '��������� &raquo; �������� ������',
'addconf_speedbar'      => '<a href="{link}">���������</a>|<a href="{link_2}">������: {title}</a>|���������� ���������',
'addconf_online'        => '��������� &raquo; ������: {title} &raquo; ���������� ���������',
'editconf_speedbar'     => '<a href="{link}">���������</a>|<a href="{link_2}">������: {title}</a>|�������������� ���������: <i>������ ������</i>',
'editconf_online'       => '��������� &raquo; ������: {title} &raquo; �������������� ���������: <i>������ ������</i>',
'delconf_speedbar'      => '<a href="{link}">���������</a>|�������� ���������',
'delconf_online'        => '��������� &raquo;�������� ���������',
'email_speedbar'        => '<a href="{link}">���������</a>|������� E-mail �����������',
'email_online'          => '��������� &raquo; ������� E-mail �����������',
'email_edit_speedbar'   => '<a href="{link}">���������</a>|<a href="{link_2}">������� E-mail �����������</a>|�������������� ���������: <i>������ ������</i>',
'email_edit_online'     => '��������� &raquo; ������� E-mail ����������� &raquo; �������������� ���������: <i>������ ������</i>',
'email_del_speedbar'    => '<a href="{link}">���������</a>|<a href="{link_2}">������� E-mail �����������</a>|�������� �������',
'email_del_online'      => '��������� &raquo; ������� E-mail ����������� &raquo; �������� �������',
'email_add_speedbar'    => '<a href="{link}">���������</a>|<a href="{link_2}">������� E-mail �����������</a>|����� ������',
'email_add_online'      => '��������� &raquo; ������� E-mail ����������� &raquo; ����� ������',
'template_speedbar'     => '<a href="{link}">���������</a>|������� ������',
'template_online'       => '��������� &raquo; ������� ������',
'lang_speedbar'         => '<a href="{link}">���������</a>|���� ������',
'lang_online'           => '��������� &raquo; ���� ������',
'user_agent_speedbar'   => '<a href="{link}">���������</a>|������ User Agent',
'user_agent_online'     => '��������� &raquo; ������ User Agent'

);

?>