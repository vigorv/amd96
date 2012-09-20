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

$lang_m_rules = language_forum ("board/modules/rules");

$row = $DB->one_select( "*", "rules" );
if ($row['id'])
{
    $link_speddbar = speedbar_forum (0, true)."|".$lang_m_rules['location'];
    $onl_location = $lang_m_rules['location'];
    $meta_info_other = $lang_m_rules['location'];

    $tpl->load_template( 'rules.tpl' );

    $tpl->tags( '{text}', $row['text'] );
    
    if($cache_config['regist_rules_date']['conf_value'])
        $tpl->tags( '{last_update}', formatdate($row['lastdate']) );
    else
        $tpl->tags( '{last_update}', "" );

    $tpl->compile( 'content' );
    $tpl->clear();
}
else
    message ($lang_message['error'], $lang_m_rules['page_id'], 1);

$DB->free($row);


?>