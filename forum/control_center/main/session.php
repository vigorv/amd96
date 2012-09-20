<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "ses.*, u.name", "LEFT", "session_cc ses||users u", "ses.member_id_online=u.user_id" );

$i = 0;

echo <<<HTML

		<table class="colorTable">
                        <tr>
				<td align=left><h6>Пользователь</h6></td>
				<td align=left><h6>Местонахождение</h6></td>
				<td align=right><h6>IP</h6></td>
                        </tr>
HTML;

while ( $row = $DB->get_row() )
{
	$i ++;

	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

echo <<<HTML

                        <tr class="{$class}">
                            	<td align=left><div class="blueHeader">
                                <a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['member_id_online']}" title="Перейти к редактированию данного пользователя.">{$row['name']}</a></div></td>
        			             <td align=left>{$row['location_online']}</td>
                            	<td align=right><a href="{$redirect_url}?do=users&op=tools&ip={$row['ip_online']}" title="Найти все упоминания об этом IP адресе.">{$row['ip_online']}</a></td>
                        </tr>
HTML;

}

echo <<<HTML
		</table>
HTML;

?>