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
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if ( extension_loaded('mysqli') AND version_compare("5.0.5", phpversion(), "!=") )
{
	include_once( LB_CLASS."/database.sqli.php" );
}
else
{
	include_once( LB_CLASS."/database.sql.php" );
}

?>