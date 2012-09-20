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

$lang_m_staticpage = language_forum ("board/modules/staticpage");

filters_input ('get');

$name_page = $DB->addslashes($_GET['name']);

if ($name_page)
{
	$row = $DB->one_select( "*", "staticpage", "title = '{$name_page}'" );
	if ($row['id'])
	{
        $link_speddbar = speedbar_forum (0, true)."|".$lang_m_staticpage['location'].$row['name'];
        $onl_location = $lang_m_staticpage['location'].$row['name'];
        $meta_info_other = $lang_m_staticpage['location'].$row['name'];
        $meta_info_forum_desc = $row['metadescr'];
        $meta_info_forum_keys = $row['metakeys'];
        
		$tpl->load_template( 'staticpage.tpl' );

		$tpl->tags( '{name}', $row['name'] );
		$tpl->tags( '{description}', $row['description'] );

		$tpl->compile( 'content' );
		$tpl->clear();
        
        if ($_SESSION['LB_read_stpage'] != $row['id'])
        {                
            $_SESSION['LB_read_stpage'] = $row['id'];       
            $DB->update("views = views + 1", "staticpage", "id='{$row['id']}'");
        }
	}
	else
		message ($lang_message['error'], $lang_m_staticpage['not_found'], 1);

	$DB->free($row);
}
else
	message ($lang_message['error'], $lang_m_staticpage['no_page_id'], 1);

?>