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

$lang_m_s_log_topic = language_forum ("board/modules/system_info/log_topic");

$topic = $DB->one_select( "id, title, forum_id", "topics", "id = '{$id}'" );
if (!$topic['id'])
{
    message ($lang_m_s_log_topic['not_found_title'], $lang_m_s_log_topic['not_found_info'], 1);
}
elseif (!forum_options_topics($topic['forum_id'], "topic_log"))
{
    message ($lang_message['access_denied'], $lang_m_s_log_topic['access_denied_log'], 1);
}
else
{
    $info_data = "";
            
    $DB->prefix = array ( 1 => DLE_USER_PREFIX );
    $DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_topics log||users u", "log.mid=u.user_id", "tid = '".$id."'", "ORDER BY log.date DESC" );
    while ( $row = $DB->get_row() )
    {
    	$i++;
    
    	if ($i%2) $class = "appLine";
    	else $class = "appLine dark";
    
    	$row['date'] = formatdate( $row['date'] );
        
        $act_st = "Не известно";
        
        if ($row['act_st'] == 0) $act_st = $lang_m_s_log_topic['act_st_0'];
        elseif ($row['act_st'] == 1) $act_st = $lang_m_s_log_topic['act_st_1'];
        elseif ($row['act_st'] == 2) $act_st = $lang_m_s_log_topic['act_st_2'];
        elseif ($row['act_st'] == 3) $act_st = $lang_m_s_log_topic['act_st_3'];
        elseif ($row['act_st'] == 4) $act_st = $lang_m_s_log_topic['act_st_4'];
        elseif ($row['act_st'] == 5) $act_st = $lang_m_s_log_topic['act_st_5'];
        elseif ($row['act_st'] == 6) $act_st = $lang_m_s_log_topic['act_st_6'];
        elseif ($row['act_st'] == 7) $act_st = $lang_m_s_log_topic['act_st_7'];
        elseif ($row['act_st'] == 8) $act_st = $lang_m_s_log_topic['act_st_8'];
        elseif ($row['act_st'] == 9) $act_st = $lang_m_s_log_topic['act_st_9'];
        elseif ($row['act_st'] == 10) $act_st = $lang_m_s_log_topic['act_st_10'];
        elseif ($row['act_st'] == 11) $act_st = $lang_m_s_log_topic['act_st_11'];
        elseif ($row['act_st'] == 12) $act_st = $lang_m_s_log_topic['act_st_12'];
        elseif ($row['act_st'] == 13) $act_st = $lang_m_s_log_topic['act_st_13'];
        
        if ($row['info'] AND $row['act_st'] != 0)
            $act_st .= "<br /><a href=\"#\" onclick=\"ShowAndHide('".$row['id']."'); return false;\" title=\"".$lang_m_s_log_topic['hint_info']."\">".$lang_m_s_log_topic['info']."</a><div id=\"".$row['id']."\" style=\"display:none;\"><br />".$row['info']."</div>";
        
        $profile_link = profile_link($row['name'], $row['user_id']);
        
$info_data .= <<<HTML
    
                <tr class="{$class}">
    				<td align=left><font class="blueHeader"><a href="{$profile_link}">{$row['name']}</a></font></td>
    				<td align=left>{$act_st}</td>
    				<td align=right>{$row['date']}</td>
                </tr>
    
HTML;
    
    }

$tpl->load_template ( 'info/log_topic_post.tpl' );

$tpl->tags( '{TITLE_BOARD}', $cache_config['general_name']['conf_value'] );
$tpl->tags( '{charset}', $LB_charset );

$tpl->tags( '{title}', $lang_m_s_log_topic['module_title'].$topic['title'] );

if ($info_data)
    $tpl->tags( '{info_data}', $info_data );
else
{
    
$info_data = <<<HTML
    
                <tr class="{$class}">
    				<td align=center colspan=3>{$lang_m_s_log_topic['not_found_log']}</td>
                </tr>
    
HTML;

    $tpl->tags( '{info_data}', $info_data );
    
}

$tpl->compile ( 'system_info' );
$tpl->global_tags ('system_info');

echo $tpl->result['system_info'];
$tpl->global_clear ();

GzipOut();
}
?>