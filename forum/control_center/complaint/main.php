<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011
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
       
    $log_d = $DB->one_select( "*", "complaint", "id = '{$id}'" );
    $DB->delete("id = '{$id}'", "complaint");
    
    if ($log_d['module'] == "post")
        $info = "<font color=red>Удаление</font> жалобы на сообщение: ".$log_d['cid'];
        
    $info = $DB->addslashes( $info );
    $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

    header( "Location: {$redirect_url}?do=complaint" );
    exit();
}

$control_center->header("Жалобы", "Жалобы");
$onl_location = "Жалобы";

$log_result = 20;

if (isset ( $_REQUEST['page'] ))
	$page = intval ( $_GET['page'] );
else
	$page = 0;

if ($page < 0)
	$page = 0;

if ($page)
{
	$page = $page - 1;
	$page = $page * $log_result;
}

$i = $page;

$link_nav = $redirect_url."?do=complaint&page=";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Жалобы на сообщения</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Автор</h6></td>
				<td align=left><h6>Текст жалобы</h6></td>
                <td align=center><h6>Дата</h6></td>
                <td align=center><h6>Информация</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$db_result = $DB->join_select( "c.*, u.name", "LEFT", "complaint c||users u", "c.mid = u.user_id", "", "ORDER BY c.date DESC LIMIT ".$page.", ".$log_result );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2) $class = "appLine";
	else $class = "appLine dark";
        
	$row['date'] = formatdate($row['date']);
    
echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['mid']}" title="Перейти к редактированию данного пользователя.">{$row['name']}</a></td>
                            <td align="left">{$row['info']}</td>
                            <td align="center">{$row['date']}</td>
                            <td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=complaint&id={$row['id']}','Данные жалобы','width=500,height=430,toolbar=1,location=0,scrollbars=1'); return false;" title="Просмотреть подробную информацию."><img src="{$redirect_url}template/images/info_link.gif" alt="Подробнее..." /></a></td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=complaint&id={$row['id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить данную жалобу?')" title="Удалить данную жалобу."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

    unset($outblock);
}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=6><b>Ниодной жалобы не найдено.</b></td>
                        </tr>
HTML;

}

echo <<<HTML

                    </table>
HTML;

if ($i > 0)
{
	$nav = $DB->one_select( "COUNT(*) as count", "complaint c");
	$nav_all = $nav['count'];
	$DB->free($nav);
	if ($nav_all > $log_result)
	{
		include LB_CLASS.'/navigation.php';
		$navigation = new navigation;
		$navigation->creat($page, $nav_all, $log_result, $link_nav, "7");

echo <<<HTML
<table>
<tr><td align=center style="padding:8px;"><h6>{$navigation->result}</6></td></tr>
</table>
HTML;
		unset($navigation);
	}
}

?>