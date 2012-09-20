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

if ($id)
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
    {
        exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
    }
    
    $DB->prefix = array ( 1 => DLE_USER_PREFIX );
    $ogran = $DB->one_join_select( "cc.*, u.name", "LEFT", "control_center_admins cc||users u", "cc.cca_member_id=u.user_id", "cca_id = '{$id}'" );
    $DB->delete("cca_id = '{$id}'", "control_center_admins");

    if ($ogran['cca_is_group'])
        $info = "<font color=red>Удаление</font> ограниения для группы: ".$DB->addslashes($cache_group[$ogran['cca_group']]['g_title']);
    else
        $info = "<font color=red>Удаление</font> ограничения для пользователя: ".$DB->addslashes($ogran['name']);
    $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    header( "Location: ".$redirect_url."?do=users&op=cca" );
    exit();
}
	
$link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Ограничение прав к центру управления";
$control_center->header("Пользователи", $link_speddbar);
$onl_location = "Пользователи &raquo; Ограничение прав к центру управления";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Ограничение прав к центру управления</div>
                    </div>
		<table class="colorTable">
                        <tr>
                <td align=left><h6>Пользователь</h6></td>
				<td align=left><h6>Группа</h6></td>
				<td align=center><h6>Дата обновления</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "cc.*, u.name", "LEFT", "control_center_admins cc||users u", "cc.cca_member_id=u.user_id", "", "ORDER BY cc.cca_update DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['cca_update'] = formatdate($row['cca_update']);
    
    if ($row['cca_is_group'])
        $row['name'] = "<a href=\"".$redirect_url."?do=users&op=cca_edit&id=".$row['cca_id']."\" title=\"Перейти к редактированию данного ограничениея к центру управления.\"><i>Группа</i></a>";
    else
        $row['name'] = "<a href=\"".$redirect_url."?do=users&op=cca_edit&id=".$row['cca_id']."\" title=\"Перейти к редактированию данного ограничениея к центру управления.\">".$row['name']."</a>";

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" class="blueHeader"><h5>{$row['name']}</h5></td>
                            <td align="left">{$cache_group[$row['cca_group']]['g_prefix_st']}{$cache_group[$row['cca_group']]['g_title']}{$cache_group[$row['cca_group']]['g_prefix_end']}</td>
                            <td align="center">{$row['cca_update']}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=users&op=cca&id={$row['cca_id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить ограничение доступа к центру управления?')"><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=4><b>Ни одного ограничения не найдено.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=users&op=cca_add" title="Добавить новое ограничение к центру управления."><img src="{$redirect_url}template/images/cca_add.gif" alt="Добавить ограничение для пользователя" /></a></td></tr></table>
HTML;

?>