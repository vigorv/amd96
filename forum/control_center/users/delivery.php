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

if (isset($_GET['id']) AND !isset($_GET['stop']))
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	   exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
       
    if(control_center_admins($member_cca['users']['delivery_new']))
    {   
        $log_d = $DB->one_select( "*", "logs_delivery", "id = '{$id}'" );
        $DB->delete("id = '{$id}'", "logs_delivery");
    
        $info = "<font color=red>Удаление</font> рассылки: ".$log_d['title'];
        $info = $DB->addslashes( $info );
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    }
    header( "Location: ".$redirect_url."?do=users&op=delivery" );
    exit();
}
elseif (isset($_GET['id']) AND isset($_GET['stop']))
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	   exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
       
    if(control_center_admins($member_cca['users']['delivery_new']))
    { 
        $log_d = $DB->one_select( "*", "logs_delivery", "id = '{$id}'" );
        $DB->update("active_status = '0'", "logs_delivery", "id = '{$id}'");
    
        $info = "<font color=orange>Остановка</font> рассылки: ".$log_d['title'];
        $info = $DB->addslashes( $info );
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    }
    header( "Location: ".$redirect_url."?do=users&op=delivery" );
    exit();
}

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Рассылка";
$control_center->header("Пользователи", $link_speddbar);
$onl_location = "Пользователи &raquo; Рассылка";

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Рассылки</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Заголовок</h6></td>
				<td align=center><h6>Дата</h6></td>
				<td align=center><h6>Заняло времени</h6></td>
                <td align=left><h6>Группам</h6></td>
                <td align=center><h6>Кол-во</h6></td>
                <td align=left><h6>Статус</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "logs_delivery", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	if ($row['active_status'] == 1)
    {
		$status = "<font color=green>Активна</font>";
        $stop_delivery = "<a href=\"javascript:confirmDelete('".$redirect_url."?do=users&op=delivery&id={$row['id']}&stop=yes&secret_key={$secret_key}', 'Вы действительно хотите остановить рассылку?')\" title=\"Остановить данную рассылку.\"><img src=\"".$redirect_url."template/images/stop_delivery.gif\" alt=\"Остановить рассылку\" /></a> ";
	}
    else
    {
		$status = "Завершена/Остановлена";
        $stop_delivery = "";
    }
    
    $date_end = $row['date_end'] - $row['date'];
    $date_end_h = intval(($date_end/60)/60);
    if ($date_end_h < 10)
        $date_end_h = "0".$date_end_h;
         
    $date_end_m = substr(intval($date_end/60), 0, 2);
    if ($date_end_m < 10)
        $date_end_m = "0".$date_end_m;
        
    $date_end_s = intval($date_end%60);
    if ($date_end_s < 10)
        $date_end_s = "0".$date_end_s;

    $date_end = $date_end_h.":".$date_end_m.":".$date_end_s;
    
    $row['date'] = formatdate($row['date']);
    
	$group_access = explode(",", $row['mgr']);
    $group_access_out = array();
    foreach($group_access as $ga)
    {
        $group_access_out[] = $cache_group[$ga]['g_title'];
    }
    $group_access_out = implode(", ", $group_access_out);
    
    if( utf8_strlen( $row['title'] ) > 20 )
            $row['title'] = utf8_substr( $row['title'], 0, 20 ) . "...";

echo <<<HTML

                        <tr class="{$class}">
                            <td align=left width="150"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=delivery&id={$row['id']}','Данные рассылки','width=500,height=430,toolbar=1,location=0,scrollbars=1')" title="Подробная информация о данной рассылке.">{$row['title']}</a></td>
			                 <td align=center>{$row['date']}</td>
			                 <td align=center>{$date_end}</td>
			                 <td align=left width="150">{$group_access_out}</td>
                             <td align=center>{$row['m_count']}</td>
                             <td align=left>{$status}</td>
			                 <td align=right width="80">{$stop_delivery}<a href="javascript:confirmDelete('{$redirect_url}?do=users&op=delivery&id={$row['id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить эту рассылку?')" title="Удалить данную рассылку."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

}
$DB->free();

echo <<<HTML
	        </table>
		 <div class="clear" style="height:10px;"></div>
	        <table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=users&op=delivery_new" title="Добавить новую рассылку."><img src="{$redirect_url}template/images/add.gif" alt="Новая рассылка" /></a></td></tr></table>
HTML;


?>