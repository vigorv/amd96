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

$lang_m_last_status = language_forum ("board/modules/last_status");

$last_status = "";

if ($cache_config['blockmod_status']['conf_value'])
{   
    $DB->prefix = array( 1 => DLE_USER_PREFIX );
    $DB->join_select( "s.*, u.name, u.mstatus, u.foto", "LEFT", "members_status s||users u", "s.member_id=u.user_id", "", "ORDER BY s.date DESC LIMIT ".intval($cache_config['blockmod_statusnum']['conf_value']) );
    $tpl->load_template ( 'block_last_status.tpl' );
    $status_i = 0;
    while ( $row = $DB->get_row() )
    {       
        if (!$row['name'])
        {
            $DB->delete("id = '{$row['id']}'", "members_status");
            continue;
        }
        
        $status_i ++;
        
        $tpl->tags('{text}', sub_title($row['text'], 60));
        $tpl->tags('{date}', formatdate($row['date']));
        $tpl->tags('{avatar}', member_avatar($row['foto']));
        $tpl->tags('{author}', $row['name']);
        $tpl->tags('{author_link}', profile_link($row['name'], $row['member_id']));

        $tpl->compile('last_status');
    }
    $tpl->clear();
    $DB->free();
    
    if (!$tpl->result['last_status']) $tpl->result['last_status'] = $lang_m_last_status['info'];
    
    if ($status_i == intval($cache_config['blockmod_statusnum']['conf_value']))
    {
        $tpl->result['last_status'] .= str_replace("{link}", all_status_link(), $lang_m_last_status['all']);
    }
    
    $last_status = $tpl->result['last_status'];
}

?>