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

$link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|MySQL ошибки";
$control_center->header("Журнал логов", $link_speddbar);
$onl_location = "Журнал логов &raquo; MySQL ошибки";

$file = LB_MAIN . "/logs/logs_mysql.log";

if (isset ( $_REQUEST['del'] ))
{
	if (!$_REQUEST['secret_key'] OR $_REQUEST['secret_key'] != $secret_key)
	{
		$control_center->errors = array ();
		$control_center->errors[] = "Неверный секретный ключ.";
		$control_center->errors_title = "Ошибка.";
		$control_center->message();
	}
    elseif(!control_center_admins($member_cca['logs']['mysqldel']))
    {
        $control_center->errors[] = "У вас недостаточно прав, чтобы удалять логи MySQL ошибок.";
	    $control_center->errors_title = "Доступ закры.";
        $control_center->message();
    }
	else
	{
		@unlink($file);
		$info = "<font color=red>Удаление</font> логов MySQL ошибок";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
        header( "Location: {$redirect_url}?do=logs&op=mysql_errors" );
        exit();
	}
}

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">MySQL ошибки</div>
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

if (file_exists($file))
{
	$i = 0;
	$content = @file_get_contents( $file );
	$content = explode ("|==|==|", $content);
	$content = array_reverse($content);
	foreach ($content as $massive)
	{
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

echo <<<HTML
		</table>
		<table border=0>
                        <tr>
                        	<td align=right colspan=2 style="padding:5px;"><a href="javascript:confirmDelete('{$redirect_url}?do=logs&op=mysql_errors&del=yes&secret_key={$secret_key}', 'Вы действительно хотите удалить логи MySQL ошибок?')" title="Удалить все логи MySQL ошибок."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить логи" /></a></td>
                        </tr>
		</table>
HTML;

}
else
{
    
echo <<<HTML
		</table>
HTML;

	$control_center->errors = array ();
	$control_center->errors[] = "Файл <b>logs_mysql.log</b> не найден. Возможно нет записей или не удалось создать файл.";
	$control_center->errors_title = "Ошибка.";
	$control_center->message();
}

?>