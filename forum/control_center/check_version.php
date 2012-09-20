<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

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