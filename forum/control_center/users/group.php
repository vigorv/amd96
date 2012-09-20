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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Группы";
$control_center->header("Пользователи", $link_speddbar);
$onl_location = "Пользователи &raquo; Группы";

if (isset($_SESSION['cca_edit_group']))
{
    $_SESSION['cca_edit_group_id'] = intval($_SESSION['cca_edit_group_id']);
echo <<<HTML
                <div class="headerRed">
                        <div class="headerRedArr"><div></div></div>
                        <div class="headerRedL"></div>
                        <div class="headerRedR"></div>
                        <div class="headerRedBg">Ограничение прав к центру управления</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                        <div style="text-align:left;">
                        <b>Внимание.</b> Вы разрешили группе ID {$_SESSION['cca_edit_group_id']} доступ в центр управления.<br>
                        Вы можете <a href="{$redirect_url}?do=users&op=cca_add&group={$_SESSION['cca_edit_group_id']}">настроить ограничение прав доступа</a> для этой группы, иначе у неё будет полный доступ.<br>
                        </div>
	                   </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
                    <div class="clear" style="height:20px;"></div>
HTML;
    unset($_SESSION['cca_edit_group']);
    unset($_SESSION['cca_edit_group_id']);
}

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Группы</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Группа</h6></td>
				<td align=center><h6>Доступ в ЦУ</h6></td>
				<td align=center><h6>Супер-модераторы</h6></td>
				<td align=center><h6>Пользователей</h6></td>
                        </tr>
HTML;

$i = 0;

$db_result = $DB->select( "*", "groups", "", "ORDER BY g_id ASC" );

while ( $row = $DB->get_row($db_result) )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	if ($row['g_access_cc'] == 1)
		$access = "access_cc_yes.gif";
	else
		$access = "access_cc_no.gif";

	if ($row['g_supermoders'] == 1)
		$supermoders = "access_cc_yes.gif";
	else
		$supermoders = "access_cc_no.gif";

    $DB->prefix = DLE_USER_PREFIX;
	$count_db = $DB->one_select( "COUNT(*) as count", "users", "user_group='{$row['g_id']}'");
	$count = $count_db['count'];
	$DB->free($count_db);
        
echo <<<HTML

                        <tr class="{$class}">
                            <td align=left>ID {$row['g_id']}: <font class="blueHeader"><a href="{$redirect_url}?do=users&op=editgroup&id={$row['g_id']}" title="Перейти к редактированию данной группы пользователей.">{$row['g_prefix_st']}{$row['g_title']}{$row['g_prefix_end']}</a></font></td>
			    <td align=center><img src="{$redirect_url}template/images/{$access}" alt="Доступ в Центр Управления" /></td>
			    <td align=center><img src="{$redirect_url}template/images/{$supermoders}" alt="Супер-модераторы" /></td>
			    <td align=center><h6>{$count}</h6></td>
                        </tr>
HTML;

}
$DB->free();

echo <<<HTML

	        </table>
HTML;

?>