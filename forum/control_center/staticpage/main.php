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

$control_center->header("Статические страницы", "Статические страницы");
$onl_location = "Статические страницы";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Статические страницы</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Название</h6></td>
				<td align=center><h6>Дата создания</h6></td>
				<td align=center><h6>Просмотров</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "staticpage", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate($row['date']);

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left"><font class="blueHeader"><a href="{$redirect_url}?do=staticpage&op=edit&id={$row['id']}" title="Редактировать данную страницу.">{$row['name']}</a></font><br /><font class="smalltext">{$cache_config['general_site']['conf_value']}?do=staticpage&name={$row['title']}</font></td>
                            <td align="center">{$row['date']}</td>
                            <td align="center">{$row['views']}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=staticpage&op=del&id={$row['id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить эту страницу?')" title="Удалить данную страницу."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=4><b>Ни одной страницы не найдено.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=staticpage&op=add" title="Добавить новую статическую страницу."><img src="{$redirect_url}template/images/page_add.gif" alt="Добавить страницу" /></a></td></tr></table>
HTML;

?>