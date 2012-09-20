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
       
    $log_d = $DB->one_select( "*", "adtblock", "id = '{$id}'" );
    $DB->delete("id = '{$id}'", "adtblock");
    
    $cache->clear("template", "adtblock");
    
    $info = "<font color=red>Удаление</font> блока или рекламы: ".$log_d['title'];
    $info = $DB->addslashes( $info );
    $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

    header( "Location: {$redirect_url}?do=adt" );
    exit();
}

$control_center->header("Блоки и реклама", "Блоки и реклама");
$onl_location = "Блоки и реклама";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Блоки и реклама</div>
                    </div>
		<table class="colorTable">
                        <tr>
                <td align=left width="40"><h6>Тег</h6></td>
				<td align=left><h6>Название</h6></td>
				<td align=center><h6>Дата создания</h6></td>
                <td align=left><h6>Где выводить</h6></td>
				<td align=center><h6>Статус</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "adtblock", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";
        
    if ($row['active_status'] == 1)
		$status = "<font color=green>Включён</font>";
    else
		$status = "<font color=red>Отключён</font>";

	$row['date'] = formatdate($row['date']);
    
    $outblock = array();
    
    if ($row['forum_id'])
        $outblock[] = "В форумах ID: ".$row['forum_id'];
        
    if ($row['in_posts'])
    {
        if ($row['in_posts'] == 1)
            $outblock[] = "В темах: Выводить наверху";
        if ($row['in_posts'] == 2)
            $outblock[] = "В темах: Выводить по центру";
        if ($row['in_posts'] == 3)
            $outblock[] = "В темах: Выводить внизу";
        if ($row['in_posts'] == 4)
            $outblock[] = "В темах: Выводить наверху, по центру и внизу";
    }
    
    if (!$row['forum_id'] AND !$row['in_posts'])
        $outblock[] = "На всех страницах";
    
    $outblock = implode ("<br />", $outblock );
    
echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" width="40">{adt_{$row['id']}}</td>
                            <td align="left" class="blueHeader"><a href="{$redirect_url}?do=adt&op=edit&id={$row['id']}" title="Редактировать данный блок.">{$row['title']}</a></td>
                            <td align="center">{$row['date']}</td>
                            <td align="left">{$outblock }</td>
                            <td align="center">{$status}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=adt&id={$row['id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить данный блок?')" title="Удалить данный блок."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

    unset($outblock);
}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=6><b>Ни одного блока или рекламы не найдено.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    <tr><td align=left colspan=6>
                        <br><font class="smalltext">Поле "Тег" выводить тег для шаблона global.tpl<br />Его нужно добавлять в шаблон только, если включён вывод на всех страницах, в других случаях блок будет выводиться автоматически в указанном месте.</font>
                    </td></tr>
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=adt&op=add" title="Добавить новый блок или рекламу."><img src="{$redirect_url}template/images/page_add.gif" alt="Добавить блок или рекламу" /></a></td></tr></table>
HTML;

?>