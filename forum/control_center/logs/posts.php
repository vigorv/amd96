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

$link_speddbar = "<a href=\"".$redirect_url."?do=logs\">Журнал логов</a>|Действия с сообщениями";
$control_center->header("Журнал логов", $link_speddbar);
$onl_location = "Журнал логов &raquo; Действия с сообщениями";

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
		if ($selected AND control_center_admins($member_cca['logs']['posts_del']))
		{
			$j = 0;
			foreach	($selected as $id)
			{
				$j ++;
				$id = intval($id);
				$DB->delete("id = '{$id}'", "logs_posts");
			}

			$info = "<font color=red>Удаление</font> логов действий с сообщениями (".$j.")";
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

			header( "Location: {$redirect_url}?do=logs&op=posts" );
            exit();
		}
        elseif(!control_center_admins($member_cca['logs']['posts_del']))
        {
            $control_center->errors[] = "У вас недостаточно прав, чтобы удалять логи действий.";
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
	$link_nav = $redirect_url."?do=logs&op=posts&where_search=".$ws."&word=".$word."&type=".$type."&page=";
}
else
{
	$word = "";
	$ws = "";
	$type = 0;
	$link_nav = $redirect_url."?do=logs&op=posts&page=";
}

if ($word AND $ws)
{
	require LB_CLASS . '/sql_search.php';
	$sql_search = new SQL_Search;

	if ($ws == "mid" OR $ws == "act_st" OR $ws == "fid" OR $ws == "tid" OR $ws == "pid")
	{
        $where = "log.".$ws." = '$word'";
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
		$where = $sql_search->simple("log.info", $word, $type);
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
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300); 
      $("div #pid_info").hide(300);
      $("div #ip_info").show(500);     
    });
    
    $("#show_date").click(function () {
      $("div #ip_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #date_info").show(500);    
    });

    $("#show_fid").click(function () {
      $("div #date_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #pid_info").hide(300); 
      $("div #fid_info").show(500);   
    });
    
    $("#show_mid").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #mid_info").show(500);      
    });  
    
    $("#show_tid").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #tid_info").show(500);      
    });
    
    $("#show_act_st").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #mid_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #act_st_info").show(500);      
    });
    
    $("#show_info").click(function () {
      $("div #date_info").hide(300);
      $("div #act_st_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #mid_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #info_info").show(500);      
    });
    
    $("#show_pid").click(function () {
      $("div #date_info").hide(300);
      $("div #act_st_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #mid_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #info_info").hide(300); 
      $("div #pid_info").show(500);         
    });
    
  });  
</script>

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">Действия с сообщениями</div>
                    </div>
		<table class="colorTable">
		<form action="" method="post" name="logs_box">

                        <tr>
				<td align=left><h6>Пользователь</h6></td>
                <td align=left><h6>Тема (ID поста)</h6></td>
				<td align=left><h6>Действие</h6></td>
                <td align=center><h6>Информация</h6></td>
				<td align=right><h6>Дата</h6></td>
				<td align=right><input type="checkbox" name="master_box" title="Отметить все" onclick="javascript:checkbox_all()"></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name, t.title, p.post_member_id, p.post_member_name", "LEFT", "logs_posts log||users u||topics t||posts p", "log.mid=u.user_id||log.tid=t.id||log.pid=p.pid", $where, "ORDER BY log.date DESC LIMIT ".$page.", ".$log_result."" );
while ( $row = $DB->get_row() )
{
	$i++;

	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate( $row['date'] );
    
    if ($row['title'])
        $row['title'] = "<a href=\"".$redirect_url_board."?do=board&op=topic&id=".$row['tid']."\" target=\"blank\" title=\"Открыть страницу с темой. ID: ".$row['tid']."\">".sub_title($row['title'], 20)."</a> (ID: ".$row['pid'].")";
    else
        $row['title'] = "<i>Тема удалена</i>";
    
    $act_st = "Не известно";
    
    if ($row['act_st'] == 0) $act_st = "<font color=red>Удалено</font>";
    elseif ($row['act_st'] == 1) $act_st = "Отредактирвоано";
    elseif ($row['act_st'] == 2) $act_st = "Закреплено";
    elseif ($row['act_st'] == 3) $act_st = "Откреплено";
    elseif ($row['act_st'] == 4) $act_st = "Скрыто";
    elseif ($row['act_st'] == 5) $act_st = "Опубликовано";
    elseif ($row['act_st'] == 6) $act_st = "Перемещено";
    elseif ($row['act_st'] == 7) $act_st = "Объединено";
    
    if ($row['info'] AND $logs['act_st'] != 0)
        $act_st .= "<br /><a href=\"#info\" class=\"show_info\" title=\"Нажмите, чтобы увидеть подробности.\">Подробности.</a><div class=\"show_info_block\" style=\"display:none;\"><br />".$row['info']."</div>";
        
    $forum = "<a href=\"".$redirect_url_board."?do=board&op=forum&id=".$row['fid']."\" target=\"blank\" title=\"Открыть страницу с форумом. ID: ".$row['fid']."\">".$cache_forums[$row['fid']]['title']."</a>";
    
echo <<<HTML

                        <tr class="{$class}">
				<td align=left><font class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="Перейти к редактированию данного пользователя. ID: {$row['user_id']}">{$row['name']}</a></font></td>
                <td align=left>{$row['title']}<br /><font class="smalltext">{$forum}</font></td>
				<td align=left>{$act_st}</td>
                <td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=posts&id={$row['id']}','Данные сообщения','width=500,height=430,toolbar=1,location=0,scrollbars=1'); return false;" title="Просмотреть подробную информацию."><img src="{$redirect_url}template/images/info_link.gif" alt="Подробнее..." /></a></td>
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
	$nav = $DB->one_select( "COUNT(*) as count", "logs_posts log", $where);
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

$word = htmlspecialchars($word);

$where_s = array();
$where_s[0] = "mid";
$where_s[1] = "info";
$where_s[2] = "act_st";
$where_s[3] = "ip";
$where_s[4] = "date";
$where_s[5] = "fid";
$where_s[6] = "tid";
$where_s[7] = "pid";

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
<option value="mid" id="show_mid" {$where_s2[0]}>Пользователь (ID)</option>
<option value="info" id="show_info" {$where_s2[1]}>Действия</option>
<option value="act_st" id="show_act_st" {$where_s2[2]}>Тип действия</option>
<option value="ip" id="show_ip" {$where_s2[3]}>IP</option>
<option value="date" id="show_date" {$where_s2[4]}>Дата</option>
<option value="fid" id="show_fid" {$where_s2[5]}>Форум (ID)</option>
<option value="tid" id="show_tid" {$where_s2[6]}>Тема (ID)</option>
<option value="pid" id="show_pid" {$where_s2[7]}>Сообщение (ID)</option>
</select> <select name="type">
<option value="0" {$type_s[0]}>Содержит</option>
<option value="1" {$type_s[1]}>Начинается</option>
<option value="2" {$type_s[2]}>Заканчивается</option>
</select> <input type="submit" name="search" value="Найти" /></td>
</tr>
<tr><td colspan=2 align=left>
<div class="clear" style="height:10px;"></div>

<div id="mid_info" style="display:none;">
<font class="smalltext">
Введите ID пользователя (тип поиска не имеет значения).<br />
ID Вы можете узнать, наведя на ник пользователя (появится подсказка), или на странице пользователей.
</font>
</div>

<div id="info_info" style="display:none;">
<font class="smalltext">
Если в настройках форума включена опция сохранения подробной информации, то Вы можете осуществить поиск по этим данным.
</font>
</div>

<div id="act_st_info" style="display:none;">
<table width="100%" align=left>
<tr><td colspan=2 class="smalltext">Типы действий с темами (тип поиска не имеет значения):</td></tr>
<tr>
<td width="200" class="smalltext">
0 - Удалена<br />
1 - Отредактирована<br />
2 - Закрыта<br />
3 - Открыта<br />
4 - Закреплена<br />
5 - Откреплена<br />
6 - Очищен лист подписчиков
</td>
<td valign=top align=left class="smalltext">
7 - Скрыта<br />
8 - Опубликована<br />
9 - Перемещена<br />
10 - Объединена<br />
11 - Добавлено голосование<br />
12 - Отредактирвоано голосование<br />
13 - Удалено голосование
</td></tr>
</table>
</div>

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

<div id="fid_info" style="display:none;">
<font class="smalltext">
Введите ID форума (тип поиска не имеет значения).<br />
ID Вы можете узнать, наведя на название форума (появится подсказка), или на странице форумов.
</font>
</div>

<div id="tid_info" style="display:none;">
<font class="smalltext">
Введите ID темы (тип поиска не имеет значения).<br />
ID Вы можете узнать, наведя на название форума (появится подсказка).
</font>
</div>

<div id="pid_info" style="display:none;">
<font class="smalltext">
Введите ID сообщения/поста (тип поиска не имеет значения).
</font>
</div>

</td></tr>
</table>
</form>
HTML;
?>