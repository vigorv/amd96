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

$lang_m_s_log_post = language_forum ("board/modules/system_info/log_post");

$post = $DB->one_join_select( "pid, t.forum_id, t.title, t.id", "LEFT", "posts p||topics t", "p.topic_id=t.id", "pid = '{$id}'" );

if (!$post['id'])
{
    message ($lang_m_s_log_post['not_found_title'], $lang_m_s_log_post['not_found_info'], 1);
}
elseif (!forum_options_topics($post['forum_id'], "topic_log"))
{
    message ($lang_message['access_denied'], $lang_m_s_log_post['access_denied_log'], 1);
}
else
{
        
    $info_data = "";
            
    $DB->prefix = array ( 1 => DLE_USER_PREFIX );
    $DB->join_select( "log.*, u.user_id, u.name, p.post_member_id, p.post_member_name", "LEFT", "logs_posts log||users u||posts p", "log.mid=u.user_id||log.pid=p.pid", "log.pid = '".$id."'", "ORDER BY log.date DESC" );
    while ( $row = $DB->get_row() )
    {
    	$i++;
    
    	if ($i%2) $class = "appLine";
    	else $class = "appLine dark";
    
    	$row['date'] = formatdate( $row['date'] );
        
        $act_st = "Не известно";
        
        if ($row['act_st'] == 0) $act_st = $lang_m_s_log_post['act_st_0'];
        elseif ($row['act_st'] == 1) $act_st = $lang_m_s_log_post['act_st_1'];
        elseif ($row['act_st'] == 2) $act_st = $lang_m_s_log_post['act_st_2'];
        elseif ($row['act_st'] == 3) $act_st = $lang_m_s_log_post['act_st_3'];
        elseif ($row['act_st'] == 4) $act_st = $lang_m_s_log_post['act_st_4'];
        elseif ($row['act_st'] == 5) $act_st = $lang_m_s_log_post['act_st_5'];
        elseif ($row['act_st'] == 6) $act_st = $lang_m_s_log_post['act_st_6'];
        elseif ($row['act_st'] == 7) $act_st = $lang_m_s_log_post['act_st_7'];
        
        if ($row['info'] AND $row['act_st'] != 0)
            $act_st .= "<br /><a href=\"#\" onclick=\"ShowAndHide('".$row['id']."'); return false;\" title=\"".$lang_m_s_log_post['hint_info']."\">".$lang_m_s_log_post['info']."</a><div id=\"".$row['id']."\" style=\"display:none;\"><br />".$row['info']."</div>";
        
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

$tpl->tags( '{title}', str_replace("{id}", $post['pid'], $lang_m_s_log_post['module_title']).$post['title'] );

if ($info_data)
    $tpl->tags( '{info_data}', $info_data );
else
{
    
$info_data = <<<HTML
    
                <tr class="{$class}">
    				<td align=center colspan=3>{$lang_m_s_log_post['not_found_log']}</td>
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