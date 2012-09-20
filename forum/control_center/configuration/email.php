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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|Шаблоны E-mail уведомлений";
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Шаблоны E-mail уведомлений";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Шаблоны E-mail уведомлений</div>
                    </div>
		<table class="colorTable">
                        <tr>
                <td align=left><h6>ID</h6></td>
				<td align=left><h6>Название</h6></td>
				<td align=center><h6>Дата создания</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "templates_email", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate($row['date']);
    
    if ($row['protect'])
        $protect = " Шаблон защищён.";
    else
        $protect = "";

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" width="20"><h5>{$row['id']}</h5></td>
                            <td align="left" class="blueHeader"><a href="{$redirect_url}?do=configuration&op=email_edit&id={$row['id']}" title="Перейти к редактированию данного E-Mail шаблона.">{$row['title']}</a></td>
                            <td align="center">{$row['date']}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=configuration&op=email_del&id={$row['id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить этот шаблон?')" title="Удалить данный E-Mail шаблон.{$protect}"><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=4><b>Ниодного шаблона не найдено.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=configuration&op=email_add" title="Добавить новый E-Mail шаблон."><img src="{$redirect_url}template/images/mail_template_add.gif" alt="Добавить шаблон E-mail уведомлений" /></a></td></tr></table>
HTML;

?>