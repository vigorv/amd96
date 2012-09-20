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

$control_center->header("Журнал логов", "Журнал логов");
$onl_location = "Журнал логов";

if(control_center_admins($member_cca['logs']['action']))
{
echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg"><a href="{$redirect_url}?do=logs&op=actions" title="Перейти к просмотру журнала логов действий в центре управления.">Действия в центре управления</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Пользователь</h6></td>
				<td align=left><h6>Действие</h6></td>
				<td align=center><h6>IP</h6></td>
				<td align=right><h6>Дата</h6></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_actions_cc log||users u", "log.member_name=u.name", "", "ORDER BY log.date DESC LIMIT 5" );
$i = 0;
while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate( $row['date'] );

echo <<<HTML

                        <tr class="{$class}">
				<td align=left><font class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="Перейти к редактированию данного пользователя.">{$row['member_name']}</a></font></td>
				<td align=left>{$row['info']}</td>
				<td align=center>{$row['ip']}</td>
				<td align=right>{$row['date']}</td>
                        </tr>
HTML;

}

echo <<<HTML
		</table>
                    <div class="clear" style="height:10px;"></div>
HTML;
}

if(control_center_admins($member_cca['logs']['login']))
{
echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg"><a href="{$redirect_url}?do=logs&op=login" title="Перейти к просмотру журнала логов авторизаций в центре управления.">Логи авторизации</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Пользователь</h6></td>
				<td align=center><h6>Статус</h6></td>
				<td align=center><h6>Информация</h6></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_login_cc log||users u", "log.member_name=u.name", "", "ORDER BY log.date DESC LIMIT 5" );
$i = 0;
while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$info = unserialize($row['info']);
	if ($row['login'])
		$login = "login_true.gif";
	else
		$login = "login_false.gif";

	$row['date'] = formatdate( $row['date'] );

echo <<<HTML
                        <tr class="{$class}">
                              	<td class="appText" align=left width=200><div class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="Перейти к редактированию данного пользователя.">{$row['member_name']}</a></div><div>{$row['date']}</div></td>
				                <td class="appBtn"><img src="{$redirect_url}template/images/{$login}" alt="Статус авторизации в Центре Управления" /></td>
                            	<td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=login&id={$row['id']}','Данные авторизации','width=500,height=430,toolbar=1,location=0,scrollbars=1'); return false;" title="Просмотреть подробную информацию."><img src="{$redirect_url}template/images/info_link.gif" alt="Подробнее..." /></a></td>
                        </tr>
HTML;
}

echo <<<HTML
		</table>
		<div class="clear" style="height:10px;"></div>
HTML;
}

if(control_center_admins($member_cca['logs']['files']))
{
    
$file = LB_MAIN . "/logs/logs.log";

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg"><a href="{$redirect_url}?do=logs&op=files" title="Перейти к просмотру журнала логов прямых обращений к файлам.">Прямые обращения к файлам</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>IP</h6></td>
				<td align=right><h6>Файл</h6></td>
                        </tr>
HTML;

if (file_exists($file))
{
	$i = 0;
	$content = @file_get_contents( $file );
	$content = explode ("|||", $content);
	$content = array_reverse($content);
	foreach ($content as $massive)
	{
		if ($i == 5)
			break;
		$i ++;

		if ($i%2)
			$class = "appLine";
		else
			$class = "appLine dark";

		$mass = unserialize($massive);
		$mass['date'] = formatdate( $mass['date'] );
echo <<<HTML
                        <tr class="{$class}">
				<td align=left width=200><font class="blueHeader"><a href="#" onclick="window.open('{$cache_config['general_site']['conf_value']}control_center/?do=infopage&op=logs&type=files&id={$i}','Прямые обращения к файлу','width=500,height=510,toolbar=1,location=0,scrollbars=1'); return false;" title="Просмотреть подробную информацию.">{$mass['ip']}</a></font><br>{$mass['date']}</td>
				<td align=right>{$mass['file']}</td>
                        </tr>
HTML;

	}
}
else
{
echo <<<HTML
                        <tr class="appLine">
				<td align=left><b>Файл /logs/logs.log пуст.</b></td>
                        </tr>
HTML;
}

echo <<<HTML
		</table>
                    <div class="clear" style="height:10px;"></div>
HTML;
}

if(control_center_admins($member_cca['logs']['mysql']))
{
echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg"><a href="{$redirect_url}?do=logs&op=mysql_errors" title="Перейти к просмотру журнала логов MySQL ошибок.">MySQL ошибки</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>IP</h6></td>
				<td align=left><h6>Страница</h6></td>
				<td align=center><h6>Дата</h6></td>
				<td align=center><h6>№ ошибки</h6></td>
				<td align=center><h6>Информация</h6></td>
                        </tr>
HTML;

$file = LB_MAIN . "/logs/logs_mysql.log";

if (file_exists($file))
{
	$i = 0;
	$content = @file_get_contents( $file );
	$content = explode ("|==|==|", $content);
	$content = array_reverse($content);
	foreach ($content as $massive)
	{
		if ($i == 5)
			break;
		$i ++;

		if ($i%2)
			$class = "appLine";
		else
			$class = "appLine dark";
            
		$mass = unserialize($massive);
        
        $mass['time'] = formatdate( $mass['time'] );
    	$info_user = unserialize($mass['info_user']);
        
       	if( utf8_strlen( $info_user['file'] ) > 60 )
            $info_user['file'] = utf8_substr( $info_user['file'], 0, 60 ) . "...";
echo <<<HTML
                        <tr class="{$class}">
                        	<td align=left class="blueHeader">{$mass['ip']}</td>
                        	<td align=left>{$info_user['file']}</td>
                       	 	<td align=center>{$mass['time']}</td>
                       	 	<td align=center>{$mass['error_number']}</td>
                        	<td align=center><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=mysql_errors&id={$i}','MySQL ошибки','width=600,height=670,toolbar=1,location=0,scrollbars=1'); return false;" title="Просмотреть подробную информацию."><img src="{$redirect_url}template/images/info_link.gif" alt="Подробнее..." /></a></td>
                        </tr>
HTML;

	}
}
else
{
echo <<<HTML
                        <tr class="appLine">
				<td align=left><b>Файл /logs/logs_mysql.log пуст.</b></td>
                        </tr>
HTML;
}

echo <<<HTML
		</table>
HTML;
}

?>