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

$link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Блокировка и ограничения пользователей";
$control_center->header("Журнал логов", $link_speddbar);
$onl_location = "Журнал логов &raquo; Блокировка и ограничения пользователей";

$control_center->errors = array ();

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

if (isset($_POST['del_checked']))
{
	if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	if ($_POST['act'] == 1)
	{
		$selected = $_POST['selected_all'];
		if ($selected AND control_center_admins($member_cca['logs']['bandel']))
		{
			$j = 0;
			foreach	($selected as $id)
			{
				$j ++;
				$id = intval($id);
				$DB->delete("id = '{$id}'", "logs_blocking");
			}

			$info = "<font color=red>Удаление</font> логов блокировки и ограничений пользователей (".$j.")";
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_blocking");

			header( "Location: {$redirect_url}?do=logs&op=actions" );
            exit();
		}
        elseif(!control_center_admins($member_cca['logs']['bandel']))
        {
            $control_center->errors[] = "У вас недостаточно прав, чтобы удалять логи блокировок.";
			$control_center->errors_title = "Доступ закры.";
        }
		else
		{
			$control_center->errors[] = "Вы не выбрали логи для удаления.";
			$control_center->errors_title = "Ошибка!";
		}
	}
	else
	{
		$control_center->errors[] = "Вы не выбрали действие.";
		$control_center->errors_title = "Ошибка!";
	}
}

if (isset($_REQUEST['where_search']) AND isset($_REQUEST['word']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$type = intval($_REQUEST['type']);
	$ws = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['where_search'] ) ) );
	$word = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['word'] ) ) );

	unset ($safehtml);
	$link_nav = $redirect_url."?do=logs&op=blocking&where_search=".$ws."&word=".$word."&type=".$type."&page=";
}
else
{
	$word = "";
	$ws = "";
	$type = 0;
	$link_nav = $redirect_url."?do=logs&op=blocking&page=";
}

if ($word AND $ws)
{
	require LB_CLASS . '/sql_search.php';
	$sql_search = new SQL_Search;

	if ($ws == "member_id")
	{
		$where = "log.member_id = '".intval($word)."'";
	}
	elseif ($ws == "moder_id")
	{
		$where = "log.moder_id = '".intval($word)."'";
	}
	elseif ($ws == "date")
	{
		$word_s = explode ("|", $word);
		$word_s[0] = strtotime ($word_s[0]);
		if ($word_s[1])
			$word_s[1] = strtotime($word_s[1]);
		else
			$word_s[1] = time ();
		$where = "log.date >= '".$word_s[0]."' AND log.date <= '".$word_s[1]."'";
	}
	elseif ($ws == "ip")
	{
		$where = $sql_search->regexp_ip($word, "log.ip");
	}
	elseif ($ws == "info")
	{
		$where = $sql_search->simple ("log.info", $word, $type);
	}

	unset ($sql_search);
}
else
	$where = "";

$control_center->message();

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

  $(document).ready(function(){
    
    $("#show_ip").click(function () {
      $("div #date_info").hide(300);
      $("div #module_info").hide(300);
      $("div #op_info").hide(300);  
      $("div #ip_info").show(500);    
    });
    
    $("#show_date").click(function () {     
      $("div #ip_info").hide(300);
      $("div #module_info").hide(300);
      $("div #op_info").hide(300);    
      $("div #date_info").show(500);  
    });

    $("#show_module").click(function () {
      $("div #date_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #op_info").hide(300); 
      $("div #module_info").show(500);     
    });
    
    $("#show_op").click(function () {
      $("div #date_info").hide(300);
      $("div #module_info").hide(300);
      $("div #ip_info").hide(300);  
      $("div #op_info").show(500);    
    });  
    
    $("#allhide").click(function () {
      $("div #date_info").hide(300);
      $("div #module_info").hide(300);
      $("div #ip_info").hide(300);  
      $("div #op_info").hide(300);
    });
    
    $("#allhide2").click(function () {
      $("div #date_info").hide(300);
      $("div #module_info").hide(300);
      $("div #ip_info").hide(300);  
      $("div #op_info").hide(300);
    }); 
    
    $("#allhide3").click(function () {
      $("div #date_info").hide(300);
      $("div #module_info").hide(300);
      $("div #ip_info").hide(300);  
      $("div #op_info").hide(300);
    });  
    
  });  
</script>

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">Блокировка и ограничения пользователей</div>
                    </div>
		<table class="colorTable">
		<form action="" method="post" name="logs_box">

                        <tr>
				<td align=left><h6>Пользователь</h6></td>
				<td align=left><h6>Информация</h6></td>
                <td align=left><h6>Модератор</h6></td>
				<td align=center><h6>IP</h6></td>
				<td align=right><h6>Дата</h6></td>
				<td align=right><input type="checkbox" name="master_box" title="Отметить все" onclick="javascript:checkbox_all()"></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.name", "LEFT", "logs_blocking log||users u", "log.member_id=u.user_id", $where, "ORDER BY date DESC LIMIT ".$page.", ".$log_result."" );
while ( $row = $DB->get_row() )
{
	$i++;

	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate( $row['date'] );

echo <<<HTML

                <tr class="{$class}">
				<td align=left><font class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['member_id']}" title="Перейти к редактированию данного пользователя.">{$row['name']}</a></font></td>
				<td align=left>{$row['info']}</td>
                <td align=center><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['moder_id']}" title="Перейти к редактированию данного пользователя.">{$row['moder_name']}</a></td>
				<td align=center>{$row['ip']}</td>
				<td align=right>{$row['date']}</td>
				<td align=right><input type="checkbox" name="selected_all[]" value="{$row['id']}" /></td>
                        </tr>

HTML;

}

echo <<<HTML
		</table>

	        <div class="clear" style="height:10px;"></div>

		<table><tr><td align=right>
		<select name="act">
		<option value="0">Выберите действие</option>
		<option value="1">- Удалить</option>
		</select>
		<input type="submit" name="del_checked" value="Выполнить">
		<input type="hidden" name="secret_key" value="{$secret_key}" />
		</td></tr>
		</table>
		</form>
HTML;

if ($i > 0)
{
	$nav = $DB->one_select( "COUNT(*) as count", "logs_blocking log", $where);
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

$word = htmlspecialchars($word);

$where_s = array();
$where_s[0] = "member_id";
$where_s[1] = "info";
$where_s[2] = "ip";
$where_s[3] = "date";
$where_s[4] = "moder_id";

$j = 0;
$where_s2 = array();
foreach ($where_s as $where_ws)
{
	if ($where_ws == $ws)
		$where_s2[$j] = "selected";
	else
		$where_s2[$j] = "";
	$j ++;
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
<td align=left><select name="where_search">
<option value="member_id" id="allhide" {$where_s2[0]}>ID пользователя</option>
<option value="moder_id" id="allhide2" {$where_s2[4]}>ID модератора</option>
<option value="info" id="allhide3" {$where_s2[1]}>Действия</option>
<option value="ip" id="show_ip" {$where_s2[2]}>IP</option>
<option value="date" id="show_date" {$where_s2[3]}>Дата</option>
</select> <select name="type">
<option value="0" {$type_s[0]}>Содержит</option>
<option value="1" {$type_s[1]}>Начинается</option>
<option value="2" {$type_s[2]}>Заканчивается</option>
</select> <input type="submit" name="search" value="Найти" /></td>
</tr>
<tr><td colspan=2 align=left>
<div class="clear" style="height:10px;"></div>
<div id="ip_info" style="display:none;">
<font class="smalltext">
Поиск по IP: 255.255.255.255; 255.255.*; 255.*.255.255
</font>
</div>
<div id="date_info" style="display:none;">
<font class="smalltext">
Поиск по дате: 21-03-2010 10:33 | 25-03-2010 (с | по); 21-03-2010
</font>
</div>
<div id="module_info" style="display:none;">
<font class="smalltext">
Поиск по модулю (?do=), примеры:<br>
users - поиск в модуле пользователей<br>
configuration - поиск в модуле настроект форума<br>
</font>
</div>
<div id="op_info" style="display:none;">
<font class="smalltext">
Поиск по дополнительным опциям модулей (&op=), примеры:<br>
addgroup - поиск в добавлении новой группы настроект форума<br>
edituser - поиск в редактировании пользователей<br>
</font>
</div>
</td></tr>
</table>
</form>
HTML;
?>