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

if (isset($_POST['del_checked']))
{
    if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}
    
    if(intval($_POST['act']) == 1 OR intval($_POST['act']) == 2)
    {
        $wid = $_POST['selected_all'];
        if (is_array($wid) AND $wid[0] != "")
        {                 
            foreach($wid as $value)
            {
                $value = intval($value);
                
                $DB->prefix = array ( 1 => DLE_USER_PREFIX );
                $log = $DB->one_join_select( "id, mid, email, mf_options, name, user_id", "LEFT", "members_warning||users", "mid=user_id", "id = '{$value}'" );
                
                if (intval($_POST['act']) == 2)
                    $DB->delete("mid = '{$log['user_id']}}' AND id = '{$value}'", "members_warning");
                else
                    $DB->update("st_w = '0'", "members_warning", "mid = '{$log['user_id']}' AND id = '{$value}'");
                    
                $count_war = $DB->one_select( "COUNT(*) as count", "members_warning", "mid = '{$log['user_id']}}' AND st_w = '1'" );
                
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("count_warning = '{$count_war['count']}'", "users", "user_id = '{$log['user_id']}'");   
                             
                if ($cache_config['warning_lcchange']['conf_value'])
                {
                    $text = "Ваш уровень предупреждений был снижен. Текущий уровень: ".$count_war['count'];
                    send_new_pm("Удаление предупреждения.", $log['user_id'], $text, $log['email'], $log['name'], $log['mf_options'], 1);
                }
                
                $DB->free($log);
            }
        }
    }
    
    header( "Location: ".$redirect_url."?do=users&op=warning" );
    exit();
}

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|Предупреждения";
$control_center->header("Пользователи", $link_speddbar);
$onl_location = "Пользователи &raquo; Предупреждения";

echo <<<HTML

<script language='JavaScript' type="text/javascript">
<!--
function checkbox_all() {
    var frm = document.logs_box;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}

-->
</script>

<form action="" method="post" name="logs_box">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Предупреждения</div>
                    </div>
		      <table class="colorTable">
                        <tr>
                <td align=left><h6>Модератор</h6></td>
				<td align=left><h6>Нарушитель</h6></td>
				<td align=center><h6>Дата</h6></td>
                <td align=left><h6>Сообщение</h6></td>
                <td align=left><h6>Статус</h6></td>
				<td align=right><input type="checkbox" name="master_box" title="Отметить все" onclick="javascript:checkbox_all()"></td>
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

$DB->prefix = array( 1 => DLE_USER_PREFIX );
$DB->join_select( "mw.*, u.name", "LEFT", "members_warning mw||users u", "mw.mid=u.user_id", "", "ORDER BY date DESC LIMIT ".$page.", ".$log_result );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate($row['date']);
    
    if ($row['st_w'])
        $st_w = "<font color=red>Активно</font>";
    else
        $st_w = "<font color=green>Удалено</font>";

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" class="blueHeader"><h5><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['moder_id']}">{$row['moder_name']}</a></h5></td>
                            <td align="left" class="blueHeader"><h5><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['mid']}">{$row['name']}</a></h5></td>
                            <td align="center">{$row['date']}</td>
                            <td align="left">{$row['description']}</td>
                            <td align="left">{$st_w}</td>
                            <td align=right><input type="checkbox" name="selected_all[]" value="{$row['id']}" /></td>
                        </tr>
HTML;

}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=6><b>Ни одного предупреждения не найдено.</b></td>
                        </tr>
HTML;

}

echo <<<HTML

                        </table>
                        
    <div class="clear" style="height:10px;"></div>

		<table><tr><td align=right>
		<select name="act">
		<option value="0">Выберите действие</option>
		<option value="1">- Снять предупреждение</option>
        <option value="2">- Удалить предупреждение</option>
		</select>
		<input type="submit" name="del_checked" value="Выполнить">
		<input type="hidden" name="secret_key" value="{$secret_key}" />
		</td></tr>
		</table>
		</form>
        
HTML;

$nav = $DB->one_select( "COUNT(*) as count", "members_warning");
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

?>