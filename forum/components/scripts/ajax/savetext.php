<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

@session_start ();

//$LB_charset = "utf-8";
$LB_charset = "windows-1251";

if (get_magic_quotes_gpc())
{
    include_once LB_CLASS. "/magic_quotes_gpc.php";
    $mq_gpc = new mq_gpc();
    $mq_gpc->del_slashes();
    unset($mq_gpc);  
}

$tid = intval($_GET['tid']);

if ($LB_charset == "windows-1251")
    $_SESSION['LB_reply_text_'.$tid] = mb_convert_encoding($_GET['text'], "windows-1251", "UTF-8");
else
    $_SESSION['LB_reply_text_'.$tid] = $_GET['text'];

?>