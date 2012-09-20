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
	@include '../../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$tpl->load_template ( 'info/bbcode.tpl' );

$tpl->tags( '{TITLE_BOARD}', $cache_config['general_name']['conf_value'] );
$tpl->tags( '{charset}', $LB_charset );

$group_list = array();

foreach ($cache_group as $value)
{
    $group_list[] = "ID: ".$value['g_id']." ".$value['g_prefix_st'].$value['g_title'].$value['g_prefix_end'];
}
$tpl->tags( '{group_list}', implode("<br />", $group_list) );

$tpl->compile ( 'system_info' );
$tpl->global_tags ('system_info');

echo $tpl->result['system_info'];
$tpl->global_clear ();

GzipOut();
?>