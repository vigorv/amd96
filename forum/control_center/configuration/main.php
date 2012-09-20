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

$_SESSION['back_link_conf'] = $_SERVER['REQUEST_URI'];

$control_center->header("Настройки", "Настройки");
$onl_location = "Настройки";


$DB->select( "*", "configuration_group", "", "ORDER BY conf_gr_id ASC" );
$conf_group = array ();

$i = 0;

while ( $row = $DB->get_row() )
{
	$i ++;
	$conf_group[$row['conf_gr_id']] = array ();
	foreach ($row as $key => $value)
		$conf_group[$row['conf_gr_id']][$key] = $value;
}
$DB->free();

$k = 1;
$DB->select( "*", "configuration_group", "", "GROUP BY conf_gr_group" );
$conf_group_menu = array ();
while ( $row = $DB->get_row() )
{
	$i ++;
    if ($row['conf_gr_group'] == "general")
        $conf_group_menu[0] = array ();
    else
        $conf_group_menu[$k] = array ();
            
	foreach ($row as $key => $value)
    {
        if ($row['conf_gr_group'] == "general")
            $conf_group_menu[0][$key] = $value;
        else
            $conf_group_menu[$k][$key] = $value;
    }
    $k ++;
}
$DB->free();

sort($conf_group_menu);
reset($conf_group_menu);

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">Настройки форума</div>
                    </div>
        <div style="padding:10px;">
		<table>
		<tr><td align=left><h5>Категории:</h5></td>
HTML;

if ($i)
{

echo <<<HTML

<script>
$(document).ready(function(){
    
HTML;
 
    foreach ($conf_group_menu as $conf_menu)
	{
	   
echo "\r\$(\"#{$conf_menu['conf_gr_group']}_a\").click(function () {";

       
        foreach ($conf_group_menu as $conf_menu2)
        {
            if ($conf_menu['conf_gr_group'] != $conf_menu2['conf_gr_group'])
            {
echo <<<HTML

      $("#{$conf_menu2['conf_gr_group']}").hide(300);
HTML;
            }
        }
        
echo <<<HTML

      $("#{$conf_menu['conf_gr_group']}").show(500);
    });
    
HTML;
        
	}

echo <<<HTML

});
</script>
HTML;

	foreach ($conf_group_menu as $conf_menu)
	{

echo <<<HTML
		<td align=left id="{$conf_menu['conf_gr_group']}_a"><h5><a href="#" onclick="return false;" title="Выберите категорию настроек.">{$conf_menu['conf_gr_group_title']}</a></h5></td>
HTML;

	}

echo <<<HTML
		</tr></table>
        <div class="clear" style="height:8px;"></div>
        <hr />
        </div>

HTML;

	foreach ($conf_group_menu as $conf)
	{
		$j = 0;
		foreach ($conf_group as $conf2)
		{
			if ($conf2['conf_gr_group'] == $conf['conf_gr_group'])
			{

if (!$j)
{
    if ($conf2['conf_gr_group'] == "general")
        $style_block = "";
    else
        $style_block = "display:none;";
    
echo <<<HTML

        <div id="{$conf2['conf_gr_group']}" style="{$style_block}">
		<table class="colorTable">
HTML;
}

$edit_panel = "[ <a href=\"".$redirect_url."?do=configuration&op=editgroup&id=".$conf2['conf_gr_id']."\" title=\"Редактировать группу настроек.\">Редактировать</a> ] [ <a href=\"javascript:confirmDelete('".$redirect_url."?do=configuration&op=delgroup&id=".$conf2['conf_gr_id']."&secret_key=".$secret_key."', 'Вы действительно хотите удалить эту группу настроек?')\" title=\"Удалить группу настроек.\"><font color=red>Удалить</font></a> ]";

echo <<<HTML

		<tr>
			<td align=left><font class="blueHeader"><a href="{$redirect_url}?do=configuration&op=show&id={$conf2['conf_gr_id']}" title="Прейти к настройкам данной группы.">{$conf2['conf_gr_name']}</a></font><br><font class="smalltext">{$conf2['conf_gr_desc']}</font></td>
			<td align=right><div class="config_edit_pan" style="display:none;padding:5px;">{$edit_panel}</div><span class="config_edit_butt"><a href="#" title="Опции"><img src="{$redirect_url}template/images/config_edit_butt.png" alt="Опции" /></a></span></td>
		</tr>
HTML;
				$j = 1;
			}
		}

		if ($j == 1)
		{
echo <<<HTML

		</table>
        </div>
HTML;
		}
	}

}

echo <<<HTML


		<div class="clear" style="height:10px;"></div>
		<table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=configuration&op=addgroup" title="Добавить новую группу настроек."><img src="{$redirect_url}template/images/process_add.gif" alt="Добавить группу" /></a></td></tr></table>
HTML;

?>