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

$control_center->header("Пользователи", "Пользователи");
$onl_location = "Пользователи";

if (isset($_SESSION['cca_users']))
{
    $_SESSION['cca_users_name'] = urlencode($_SESSION['cca_users_name']);
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
                        <b>Внимание.</b> Вы перенесли пользователя в группу, которой разрешён доступ в центр управления.<br>
                        Вы можете <a href="{$redirect_url}?do=users&op=cca_add&user={$_SESSION['cca_users_name']}">настроить ограничение прав доступа</a> для этого пользователя, иначе у него будет полный доступ.<br>
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
    unset($_SESSION['cca_users']);
    unset($_SESSION['cca_users_name']);
}

$users_result = 20;

if (isset ( $_REQUEST['page'] ))
	$page = intval ( $_GET['page'] );
else
	$page = 0;

if ($page < 0)
	$page = 0;

if ($page)
{
	$page = $page - 1;
	$page = $page * $users_result;
}

if(isset($_GET['gr']))
    $_SESSION['lb_search_users']['member_gr'] = intval($_GET['gr']);

if (isset($_POST['search']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$_SESSION['lb_search_users']['text'] = $DB->addslashes( $safehtml->parse( trim( $_POST['text'] ) ) );
	$_SESSION['lb_search_users']['how_s'] = intval( $_POST['how_s'] );
	$_SESSION['lb_search_users']['type'] = $DB->addslashes( $safehtml->parse( trim( $_POST['type'] ) ) );

	if ($_SESSION['lb_search_users']['text'] == "" OR $_SESSION['lb_search_users']['type'] == "")
		 unset($_SESSION['lb_search_users']);

    $_SESSION['lb_search_users']['member_gr'] = intval( $_POST['member_gr'] );
    $_SESSION['lb_search_users']['date_reg_1'] = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['date_reg_1'] ) ) ) );
    $_SESSION['lb_search_users']['date_reg_2'] = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['date_reg_2'] ) ) ) );

	unset ($safehtml);
}

if (isset($_GET['filters']) AND $_GET['filters'] == "reset")
{
    unset($_SESSION['lb_search_users']);
	header( "Location: {$redirect_url}?do=users" );
    exit();
}

$search_text = "";
$search_type = array();
$search_type[0] = "";
$search_type[1] = "";
$search_type[2] = "";
$search_type[3] = "";

$search_how_s = array();
$search_how_s[0] = "";
$search_how_s[1] = "";
$search_how_s[2] = "";
$search_how_s[3] = "";

$search_data_reg_1 = "";
$search_data_reg_2 = "";

$search = array();

if (is_array($_SESSION['lb_search_users']))
{
	require LB_CLASS . '/sql_search.php';
	$sql_search = new SQL_Search;

    if (isset($_SESSION['lb_search_users']['text']))
    {
	   if ($_SESSION['lb_search_users']['type'] == "name")
	   	   $search[] = "(".$sql_search->simple("name", $_SESSION['lb_search_users']['text'], $_SESSION['lb_search_users']['how_s']).")";
	   elseif ($_SESSION['lb_search_users']['type'] == "email")
		  $search[] = "(".$sql_search->simple("email", $_SESSION['lb_search_users']['text'], $_SESSION['lb_search_users']['how_s']).")";
	   elseif ($_SESSION['lb_search_users']['type'] == "user_id")
		  $search[] = "(".$sql_search->simple("user_id", $_SESSION['lb_search_users']['text'], $_SESSION['lb_search_users']['how_s']).")";
	   else
		  $search[] = "(".$sql_search->regexp_ip($_SESSION['lb_search_users']['text']).")";
    }
    
    if ($_SESSION['lb_search_users']['date_reg_1'] != "")
    {
        $search[] = "(reg_date >= '".strtotime(stripslashes($_SESSION['lb_search_users']['date_reg_1']))."')";
        $search_data_reg_1 = $_SESSION['lb_search_users']['date_reg_1'];
    }
        
    if ($_SESSION['lb_search_users']['date_reg_2'] != "")
    {    
        $search[] = "(reg_date <= '".strtotime(stripslashes($_SESSION['lb_search_users']['date_reg_2']))."')";
        $search_data_reg_2 = $_SESSION['lb_search_users']['date_reg_2'];    
    }
    
    if ($_SESSION['lb_search_users']['member_gr'] != 0)
        $search[] = "(".$sql_search->simple("user_group", $_SESSION['lb_search_users']['member_gr'], 3).")";

	unset ($sql_search);

echo <<<HTML

		<table><tr><td align=right><i>Показаны отфильтрованные результаты. <a href="{$redirect_url}?do=users&filters=reset">Сбросить филтры</a>.</i></td></tr></table>
		<div class="clear" style="height:10px;"></div>
HTML;

	$search_text = htmlspecialchars( stripslashes( $_SESSION['lb_search_users']['text'] ) );

	if ($_SESSION['lb_search_users']['how_s'] == 0)
		$search_how_s[0] = "selected";
	elseif ($_SESSION['lb_search_users']['how_s'] == 1)
		$search_how_s[1] = "selected";
	elseif ($_SESSION['lb_search_users']['how_s'] == 2)
		$search_how_s[2] = "selected";
	else
		$search_how_s[0] = "selected";

	if ($_SESSION['lb_search_users']['type'] == "email")
		$search_type[0] = "selected";
	elseif ($_SESSION['lb_search_users']['type'] == "user_id")
		$search_type[1] = "selected";
	elseif ($_SESSION['lb_search_users']['type'] == "ip")
		$search_type[2] = "selected";
	else
		$search_type[3] = "selected";
}
else
{
	$search_how_s[0] = "selected";
	$search_type[3] = "selected";
    $search_active_status[0] = "selected";
}

$where = implode(" AND ", $search);

$member_gr = "";
$member_gr .= "<option value=\"0\">Не учитывать</option>";

foreach($cache_group as $m_group)
{       
    if ($m_group['g_id'] == $_SESSION['lb_search_users']['member_gr'])
        $member_gr .= "<option value=\"".$m_group['g_id']."\" selected>".$m_group['g_title']."</option>";
    else
        $member_gr .= "<option value=\"".$m_group['g_id']."\" >".$m_group['g_title']."</option>";
}

$i = $page;

$link_nav = $redirect_url."?do=users&page=";

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Пользователи</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Логин</h6></td>
				<td align=left><h6>E-mail</h6></td>
				<td align=center><h6>Группа</h6></td>
				<td align=right><h6>Дата регистрации</h6></td>
				<td align=right><h6>IP</h6></td>
                        </tr>
HTML;

$DB->prefix = DLE_USER_PREFIX;
$DB->select( "user_id, name, logged_ip, reg_date, user_group, banned, email", "users", $where, "ORDER BY user_group ASC LIMIT ".$page.", ".$users_result."" );
$conf_group = array ();

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";
        
	$row['reg_date'] = formatdate($row['reg_date']);
    
    if ($row['logged_ip'])
        $row['logged_ip'] = "<a href=\"".$redirect_url."?do=users&op=tools&ip=".$row['logged_ip']."\" title=\"Найти все упоминания об этом IP адресе.\">".$row['logged_ip']."</a>";
        
    $member_group = member_group($row['user_group'], $row['banned']);

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left"><font class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="Перейти к редактированию данного пользователя. ID: {$row['user_id']}">{$row['name']}</a></font></td>
                            <td align="left">{$row['email']}</td>
                            <td align="center">{$member_group}</td>
                            <td align="right">{$row['reg_date']}</td>
                            <td align="right">{$row['logged_ip']}</td>
                        </tr>
HTML;

}
$DB->free();

echo <<<HTML
                    </table>
HTML;

if ($i > 0)
{
    $DB->prefix = DLE_USER_PREFIX;
	$nav = $DB->one_select( "COUNT(*) as count", "users", $where);
	$nav_all = $nav['count'];
	$DB->free($nav);
	if ($nav_all > $users_result)
	{
		include LB_CLASS.'/navigation.php';
		$navigation = new navigation;
		$navigation->creat($page, $nav_all, $users_result, $link_nav, "7");

echo <<<HTML
<table>
<tr><td align=center style="padding:8px;"><h6>{$navigation->result}</6></td></tr>
</table>
HTML;
		unset($navigation);
	}
}
else
{
	$control_center->errors = array ();
	$control_center->errors[] = "Страница не найдена или поиск не дал никаких результатов.";
	$control_center->errors_title = "Ошибка в навигации.";
	$control_center->message();
}

echo <<<HTML
  
  <script>
  $(document).ready(function(){
    
    $("#show_ip").click(function () {
      $("div #ip_info").show(700);
      $("div #active_st_info").hide(700);
      $("div #date_reg_info").hide(700);
    });
    
    $("#date_reg").click(function () {
      $("div #date_reg_info").show(700);
      $("div #ip_info").hide(700);
      $("div #active_st_info").hide(700);
    });
  
  });
  </script>

	            <div class="clear" style="height:10px;"></div>
<form  method="post" name="filters" action="">
                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">Поиск пользователей</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left widht="50%">
                                       <div>
                                            <div class="inputCaption">Искать:</div>
                                            <div><input type="text" name="text" value="{$search_text}" class="inputText" style="width:200px" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Как искать:</div>
                                            <div><select name="how_s"><option value="3" {$search_how_s[3]}>Совпадает</option><option value="0" {$search_how_s[0]}>Содержит</option><option value="1" {$search_how_s[1]}>Начинается</option><option value="2" {$search_how_s[2]}>Заканчивается</option></select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Тип:</div>
                                            <div><select name="type"><option value="name" {$search_type[3]} id="hide_ip1">Логин</option><option value="email" {$search_type[0]} id="hide_ip2">E-Mail</option><option value="user_id" {$search_type[1]} id="hide_ip3">ID пользователя</option><option value="ip" {$search_type[2]} id="show_ip">IP</option></select></div>
                                        </div>
                                    </td>
                                    <td align=left widht="50%">
                                        <div>
                                            <div class="inputCaption">Группа:</div>
                                            <div><select name="member_gr">{$member_gr}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Дата регистрации между:</div>
                                            <div id="date_reg"><input type="text" name="date_reg_1" value="{$search_data_reg_1}" class="inputText" style="width:110px" /> и <input type="text" name="date_reg_2" value="{$search_data_reg_2}" class="inputText" style="width:110px" /></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=left colspan=2>
                                    <div class="clear" style="height:18px;"></div>
                                     <div>
                                            <div class="inputCaption"></div>
					    <input type="submit" name="search" value="Найти" class="btnYellow" />
                                        </div>
                                        <div class="clear" style="height:10px;"></div>
                                        <div id="ip_info" style="display:none;">
                                            <font class="smalltext">
Примичание по поиску IP адресов:<br>
1) Фильтр "Как искать" не используется.<br>
2) Вы можете использовать спец символ * для того чтобы найти определённую маску IP адресов, например:<br>
IP: 255.255.255.255; 255.255.*; 255.*.255.255<br>
                                            </font>
                                        </div>
                                        <div id="date_reg_info" style="display:none;">
                                            <font class="smalltext">
Формат ввода даты должен быть:<br>
1) 13-04-2010 или 13.04.2010<br>
2) 13:00 23-08-2010 или 13:00 23.08.2010<br>
Не обязательно заполнять оба поля.<br>
                                            </font>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</fotm>
HTML;

?>