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

$DB->prefix = array( 1 => DLE_USER_PREFIX);
$DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_login_cc log||users u", "log.member_name=u.name", "", "ORDER BY log.date DESC LIMIT 5" );
$i = 0;
while ( $row = $DB->get_row() )
{
	$i++;
	$info = unserialize($row['info']);
	if ($row['login'])
		$login = "login_true.gif";
	else
		$login = "login_false.gif";

	$row['date'] = formatdate( $row['date'] );

	if ($i%2)
		$class = "appLine dark";
	else
		$class = "appLine";

echo <<<HTML

                        <tr class="{$class}">
                              	<td class="appText" align=left width=200><div class="blueHeader">
                                  <a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="Перейти к редактированию данного пользователя.">{$row['member_name']}</a></div><div>{$row['date']}</div></td>
				<td class="appBtn"><img src="{$redirect_url}template/images/{$login}" alt="Статус авторизации в Центре Управления" /></td>
                            	<td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=login&id={$row['id']}','Данные авторизации','width=500,height=400,toolbar=1,location=0'); return false;" title="Просмотреть подробную информацию."><img src="{$redirect_url}template/images/info_link.gif" alt="Подробнее..." /></a></td>
                        </tr>
HTML;

}

?>