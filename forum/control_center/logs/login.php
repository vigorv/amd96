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

$link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Логи авторизации";
$control_center->header("Журнал логов", $link_speddbar);
$onl_location = "Журнал логов &raquo; Логи авторизации";

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Логи авторизации</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Пользователь</h6></td>
				<td align=center><h6>Статус</h6></td>
				<td align=center><h6>Информация</h6></td>
                        </tr>
HTML;

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

if (isset($_REQUEST['word']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$type = intval($_REQUEST['type']);
	$word = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['word'] ) ) );
	unset ($safehtml);
	$link_nav = $redirect_url."?do=logs&op=login&word=".$word."&type=".$type."&page=";
}
else
{
	$word = "";
	$type = 0;
	$link_nav = $redirect_url."?do=logs&op=login&page=";
}

if ($word)
{
	require LB_CLASS . '/sql_search.php';
	$sql_search = new SQL_Search;
    $where = $sql_search->simple ("member_name", $word, $type);
    unset ($sql_search);
}
else
	$where = "";

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_login_cc log||users u", "log.member_name=u.name", $where, "ORDER BY log.date DESC LIMIT ".$page.", ".$log_result."" );
while ( $row = $DB->get_row() )
{
	$i++;
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

if ($i > 0)
{
	$nav = $DB->one_select( "COUNT(*) as count", "logs_login_cc");
	$nav_all = $nav['count'];
	$DB->free($nav);
	if ($nav_all > $log_result)
	{
		include LB_CLASS.'/navigation.php';
		$navigation = new navigation;
		$navigation->creat($page, $nav_all, $log_result, $link_nav, "5");

echo <<<HTML
<table>
<tr><td align=center style="padding:8px;"><h6>{$navigation->result}</6></td></tr>
</table>
HTML;
		unset($navigation);
	}
}

$type_s = array();
for ($j=0;$j<=2;$j++)
{
	if ($j == $type)
		$type_s[$j] = "selected";
	else
		$type_s[$j] = "";
}

echo <<<HTML
<form action="{$link_nav}1" method="post" name="logs">
<table border=0>
<tr>
<td align=left width="350">Поиск: <input type="text" name="word" class="inputText" value="{$word}" style="width:300px" /></td>
<td align=left><select name="type">
<option value="0" {$type_s[0]}>Содержит</option>
<option value="1" {$type_s[1]}>Начинается</option>
<option value="2" {$type_s[2]}>Заканчивается</option>
</select> <input type="submit" name="search" value="Найти" /></td>
</tr>
</table>
</form>
HTML;

?>