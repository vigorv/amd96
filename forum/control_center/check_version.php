<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

@error_reporting ( E_ALL ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );

function clean_url($url)
{
	if( $url == "" )
		return;
	
	$url = strtolower($url);
	$url = str_replace('http://', '',$url);
	$url = str_replace("https://", "", $url);
	$url = str_replace("www.",    "", $url);
	$url = explode('/', $url);
	$url = $url[0];
	
	return $url;
}

$data = @file_get_contents("http://logicboard.ru/check_version.php?ver=".$_GET['ver']."&edition=dle&s=".clean_url($_SERVER['HTTP_HOST']));

if (!$data)
    exit("Error.");
else
{
    exit ($data);
}
?>