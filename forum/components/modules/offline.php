<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$tpl->load_template ( 'offline.tpl' );

$msg_offline = str_replace ("\r", "<br />", $cache_config['general_close_msg']['conf_value']);
$tpl->tags( '{msg}', $msg_offline );
$tpl->tags( '{TITLE_BOARD}', $cache_config['general_name']['conf_value'] );
$tpl->tags( '{charset}', $LB_charset );

$tpl->compile ( 'global_template' );
$tpl->global_tags ('global_template');

echo $tpl->result['global_template'];
$tpl->global_clear ();

GzipOut();
?>