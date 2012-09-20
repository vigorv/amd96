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

$lang_m_banned = language_forum ("board/modules/banned");

$tpl->load_template ( 'banned.tpl' );

if ($banned_ip)	  $how = $lang_m_banned['by_ip'].$_IP;
if ($banned_name) $how = $lang_m_banned['by_name'].$member['name'];

$tpl->tags( '{how}', $how );

if(count($cache_banfilters))
{
    $tpl->tags_blocks("info");
    
    $moder_desc = "";
    $date_end = 0;
    $ban_days = 0;
    foreach($cache_banfilters as $info_ban)
    {
        if ($banned_name AND $logged AND $member_id['banned'] == "yes" AND $info_ban['users_id'] == $member_id['user_id'])
        {
            $moder_desc = $info_ban['descr'];
            $date_end = $info_ban['date'];
            $ban_days = $info_ban['days'];
            break;
        }
        elseif ($banned_ip AND $info_ban['ip'])
        {
            $info_ban['ip'] = preg_quote( $info_ban['ip'] );
            if (preg_match( "#\*#", $info_ban['ip'] ))
            {
				$info_ban['ip'] = str_replace( "\*", "([0-9]|[0-9][0-9]|[0-9][0-9][0-9])*", $info_ban['ip'] );
				if(preg_match( "#{$info_ban['ip']}#i", $_IP ) )
                {
                    $moder_desc = $info_ban['descr'];
                    $date_end = $info_ban['date'];
                    $ban_days = $info_ban['days'];
                    break;
                }
            }
            else
            {
				if(preg_match( "#^{$info_ban['ip']}$#i", $_IP ) )
				{
                    $moder_desc = $info_ban['descr'];
                    $date_end = $info_ban['date'];
                    $ban_days = $info_ban['days'];
                    break;
                }
            }
        }
    }
    
    if ($moder_desc != "")
        $tpl->tags( '{msg}', $moder_desc );
    else
        $tpl->tags( '{msg}', $lang_m_banned['description'] );
        
    if ($ban_days)
    {
        $tpl->tags( '{time}', formatdate($date_end) );
        $tpl->tags( '{days}', $ban_days );
    }
    else
    {
        $tpl->tags( '{time}', $lang_m_banned['time'] );
        $tpl->tags( '{days}', "0" );
    }
}
else
    $tpl->tags_blocks("info", false);
    
$tpl->tags( '{TITLE_BOARD}', $cache_config['general_name']['conf_value'] );
$tpl->tags( '{charset}', $LB_charset );

$tpl->compile ( 'ban_template' );
$tpl->global_tags ('ban_template');

echo $tpl->result['ban_template'];
$tpl->global_clear ();

GzipOut();
?>