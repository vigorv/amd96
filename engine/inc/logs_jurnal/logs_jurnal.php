<?
if(!defined('DATALIFEENGINE'))
die("Hacking attempt!");
echoheader("","");
$where = array();
$from_date = $db->safesql( trim( htmlspecialchars( stripslashes( $_REQUEST['from_date'] ),ENT_QUOTES ) ) );
$to_date = $db->safesql( trim( htmlspecialchars( stripslashes( $_REQUEST['to_date'] ),ENT_QUOTES ) ) );
if($action == "logs_aal") {
$ip = $db->safesql(trim(htmlspecialchars($_REQUEST['ip'])));
if ($ip)
$where[] = "(ip = '{$ip}')";
$base_name = "admin_authoriz_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по ошибочным авторизациям в админцентре.".$status_mod;
$lang_log_data = "IP";
$link_nav = "ip={$ip}";
}
elseif($action == "logs_autorization") {
$ip = $db->safesql(trim(htmlspecialchars($_REQUEST['ip'])));
$u_group = intval($_REQUEST['u_group']);
if ($ip)
$where[] = "(`ip` = '{$ip}')";
if ($u_group)
$where[] = "(`group` = '{$u_group}')";
$base_name = "users_authoriz_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по ошибочным авторизациям на сайте.".$status_mod;
$lang_sub_data = '<td width=70 align="center">Группа</td>';
$lang_log_data = "IP";
$link_nav = "ip={$ip}&amp;u_group={$u_group}";
}elseif($action == "logs_adl") {
$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
if ($username)
$where[] = "(username = '{$username}')";
$base_name = "admin_delivery_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по массовой рассылки.".$status_mod;
$lang_log_data = "Автор";
$link_nav = "username={$username}";
}elseif($action == "logs_aol") {
$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
if ($username)
$where[] = "(username = '{$username}')";
$base_name = "admin_optim_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по оптимизации данных.".$status_mod;
$lang_log_data = "Автор";
$link_nav = "username={$username}";
}elseif($action == "logs_aul") {
	
$autor = $db->safesql(trim(htmlspecialchars($_REQUEST['autor'])));
$user_id = intval($_REQUEST['user_id']);
if ($autor)
$where[] = "(autor = '{$autor}')";
if ($user_id)
$where[] = "(user_id = '{$user_id}')";
$base_name = "admin_users_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по пользователям.".$status_mod;
$lang_sub_data = '<td width=70 align="center">Юзер</td>';
$lang_log_data = "Автор";
$link_nav = "autor={$autor}&amp;user_id={$user_id}";
}elseif($action == "logs_news") {
$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$post_id = intval($_REQUEST['post_id']);
if ($username)
$where[] = "(username = '{$username}')";
if ($post_id)
$where[] = "(post_id = '{$post_id}')";
$base_name = "post_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по новостям/статьям.".$status_mod;
$lang_sub_data = '<td width=70 align="center">Автор</td>';
$lang_log_data = "Пользователь";
$link_nav = "username={$username}&amp;post_id={$post_id}";
}elseif($action == "logs_news_com") {
	
$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$post_id = intval($_REQUEST['post_id']);
if ($username)
$where[] = "(username = '{$username}')";
if ($post_id)
$where[] = "(post_id = '{$post_id}')";
$base_name = "comments_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по комментариям к новостям/статьям.".$status_mod;
$lang_sub_data = '<td width=70 align="center">Комм.</td>';
$lang_log_data = "Пользователь";
$link_nav = "username={$username}&amp;post_id={$post_id}";
}elseif($action == "logs_lc") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$autor = $db->safesql(trim(htmlspecialchars($_REQUEST['autor'])));
$pm_id = intval($_REQUEST['pm_id']);
if ($username)
$where[] = "(username = '{$username}')";
if ($autor)
$where[] = "(autor = '{$autor}')";
if ($pm_id)
$where[] = "(pm_id = '{$pm_id}')";
$base_name = "pm_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по подозрительным ЛС.".$status_mod;
$lang_sub_data = '<td align="center">Получатель</td>';
$lang_log_data = "Отправитель";
$link_nav = "username={$username}&amp;autor={$autor}&amp;pm_id={$pm_id}";
}elseif($action == "logs_category") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$cat_id = intval($_REQUEST['cat_id']);
if ($username)
$where[] = "(username = '{$username}')";
if ($cat_id)
$where[] = "(cat_id = '{$cat_id}')";
$base_name = "category_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по катеогриям новостей.".$status_mod;
$lang_sub_data = '<td align="center">Категория</td>';
$lang_log_data = "Автор";
$link_nav = "username={$username}&amp;cat_id={$cat_id}";
}elseif($action == "logs_apx") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$post_id = intval($_REQUEST['post_id']);
if ($username)
$where[] = "(username = '{$username}')";
if ($post_id)
$where[] = "(post_id = '{$post_id}')";
$base_name = "post_xfields_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по доп. полям для новостей.".$status_mod;
$lang_log_data = "Пользователь";
$link_nav = "username={$username}&amp;post_id={$post_id}";
}elseif($action == "logs_app") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$post_id = intval($_REQUEST['post_id']);
if ($username)
$where[] = "(username = '{$username}')";
if ($post_id)
$where[] = "(post_id = '{$post_id}')";
$base_name = "users_xfields_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по доп. полям для профиля.".$status_mod;
$lang_log_data = "Пользователь";
$link_nav = "username={$username}&amp;post_id={$post_id}";
}elseif($action == "logs_templates") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$templates = $db->safesql(trim(htmlspecialchars($_REQUEST['templates'])));
if ($username)
$where[] = "(username = '{$username}')";
if ($templates)
$where[] = "(templates = '{$templates}')";
$base_name = "templates_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по шаблонам сайта.".$status_mod;
$lang_sub_data = '<td align="center">Шаблон</td>';
$lang_log_data = "Автор";
$link_nav = "username={$username}&amp;templates={$templates}";
}elseif($action == "logs_vote") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$id_vote = intval($_REQUEST['id_vote']);
if ($username)
$where[] = "(username = '{$username}')";
if ($id_vote)
$where[] = "(id_vote = '{$id_vote}')";
$base_name = "vote_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по шаблонам сайта.".$status_mod;
$lang_sub_data = '<td align="center">Название</td>';
$lang_log_data = "Автор";
$link_nav = "username={$username}&amp;id_vote={$id_vote}";
}elseif($action == "logs_banners") {

$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
$id_banner = intval($_REQUEST['id_banner']);
if ($username)
$where[] = "(username = '{$username}')";
if ($id_vote)
$where[] = "(id_banner = '{$id_banner}')";
$base_name = "banners_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по рекламным материалам.".$status_mod;
$lang_sub_data = '<td align="center">Название</td>';
$lang_log_data = "Автор";
$link_nav = "username={$username}&amp;id_banner={$id_banner}";
}elseif($action == "logs_lostpass") {
$ip = $db->safesql(trim(htmlspecialchars($_REQUEST['ip'])));
$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
if ($ip)
$where[] = "(`ip` = '{$ip}')";
if ($username)
$where[] = "(`username` = '{$username}')";
$base_name = "lostdb_logs";
if ($lj_conf[$action] == 1)
$status_mod = " Статус: <font color=green>Активен</font>";
else
$status_mod = " Статус: <font color=red>Отключен</font>";
$lang_log_title = "Журнал действий по восстановлению пароля.".$status_mod;
$lang_sub_data = '<td width=70 align="center">Аккаунт</td>';
$lang_log_data = "IP";
$link_nav = "ip={$ip}&amp;username={$username}";
}
if($from_date != "") {
if ($action == "logs_lc")
$from_date = strtotime($from_date);
$where[] = "(date >= '{$from_date}')";
}
if($to_date != "") {
if ($action == "logs_lc")
$to_date = strtotime($to_date);
$where[] = "(date <= '{$to_date}')";
}
$where = implode(" AND ",$where);
if ($where)
$where = "WHERE ".$where;

//print_r($_REQUEST);
$files_per_page = $_REQUEST['files_per_page'];

if(!intval($files_per_page))
$files_per_page = $lj_conf['number'];

if (isset($_REQUEST['start_from']))
{$start_from=$_REQUEST['start_from'];}else $start_from = 0;
$sql = "SELECT * FROM `".PREFIX ."_".$base_name ."` {$where} ORDER BY `date` DESC LIMIT {$start_from},{$files_per_page}";
echo $sql;
$result = $db->query($sql);
$entries_showed = 0;
if($action == "logs_aul"OR $action == "logs_news")
$username_enc = urlencode($row['username']);
$flag = 1;
if($start_from == "0")
$start_from = "";
$i = $start_from;
$entries_showed = 0;
while($row = $db->get_row($result))
{
$i++;
if($action == "logs_aal") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=iptools&ip=".$row['ip']."\">{$row['ip']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&ip=".$row['ip']."\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</div></td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору (IP):</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"ip\" value=\"{$ip}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_autorization") {
$group_name = "";
foreach ( $user_group as $group )
{
if( $row['group'] == $group['id'] )
$group_name = $group['group_name'];
}
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=iptools&ip=".$row['ip']."\">{$row['ip']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&ip=".$row['ip']."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&u_group=".$row['group']."\">{$group_name}</a> (ID: {$row['group']})</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</div></td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору (IP):</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"ip\" value=\"{$ip}\" type=\"text\" size=\"35\"></td>
    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по ID грыппы:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"u_group\" value=\"{$u_group}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_adl") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['username'])."\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['username'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\"><a href=\"javascript:ShowOrHide('{$row[id]}')\"><b>Открыть поробное описание</b></a><div id=\"{$row[id]}\" style=\"display:none;\"><br>{$row['description']}</div></div></td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_aol") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['username'])."\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['username'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</div></td>
		<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_aul") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['autor'])."\">{$row['autor']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['autor'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['username']}<br>
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&user_id={$row['user_id']}\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</div></td>
	  	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по пользователю (<b>ID</b>):</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"post_id\" value=\"{$user_id}\" type=\"text\" size=\"35\"></td>

    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"autor\" value=\"{$autor}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_news") {
$posts_link = "<a href=\"/user/{$row['autor']}/\">{$row['autor']}</a><br><a href=\"$PHP_SELF?mod=editnews&action=editnews&id={$row['post_id']}\">{$row['post_id']}</a>";
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['username']}\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&username={$row['username']}\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$posts_link}<br>
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&post_id={$row['post_id']}\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">".stripslashes($row['description'])."</div></td>
	  	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по новости (<b>ID</b>):</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"post_id\" value=\"{$post_id}\" type=\"text\" size=\"35\"></td>

    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по пользователю:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_news_com") {
$posts_link = "<a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['autor']}\">{$row['autor']}</a><br><a href=\"{$config['http_home_url']}index.php?newsid={$row['back_link']}\">{$row['post_id']}</a>";
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['username']}\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&username={$row['username']}\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$posts_link}<br>
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&post_id={$row['post_id']}\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">".stripslashes($row['description'])."</div></td>
	  <td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по комментарию (<b>ID</b>):</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"post_id\" value=\"{$post_id}\" type=\"text\" size=\"35\"></td>

    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по пользователю:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_lc") {
$row['date'] = langdate( "j F Y H:i",$row['date'] );
$pm = "<a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['username']}\">{$row['username']}</a><br>(<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&username={$row['username']}\">журнал</a>)<br>".$row['pm_id'];
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['autor']}\">{$row['autor']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor={$row['autor']}\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$pm}<br>
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&pm_id={$row['pm_id']}\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\"><b>Заголовок:</b> {$row['title']}<br>{$row['description']}</div></td>
	  <td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по ID ЛС:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"pm_id\" value=\"{$pm_id}\" type=\"text\" size=\"35\"></td>

    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по получателю:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по отправителю:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"autor\" value=\"{$autor}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_category") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['username'])."\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['username'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&cat_id={$row['cat_id']}\">{$row['cat_id']}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_apx") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;width:80px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['username']}\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&username={$row['username']}\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;width:100%\">".stripslashes($row['description'])."</div></td>
	  	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по пользователю:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_app") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;width:80px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['username']}\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&username={$row['username']}\">журнал</a>)</td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;width:100%\">".stripslashes($row['description'])."</div></td>
	  	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по пользователю:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_templates") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['username'])."\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['username'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&templates=".$row['templates']."\">{$row['templates']}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_vote") {
include_once ENGINE_DIR .'/classes/parse.class.php';
$parse = new ParseFilter( );
$title = $parse->decodeBBCodes( $row['title'],false );
$row['description'] = stripslashes( $row['description'] );
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['username'])."\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['username'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&id_vote=".$row['id_vote']."\">{$title}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по ID голосования:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"id_vote\" value=\"{$id_vote}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_banners") {
include_once ENGINE_DIR .'/classes/parse.class.php';
$parse = new ParseFilter( );
$title = $parse->decodeBBCodes( $row['title'],false );
$row['description'] = stripslashes( $row['description'] );
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row['username'])."\">{$row['username']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&autor=".urlencode($row['username'])."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&id_banner=".$row['id_banner']."\">{$title}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по автору:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по ID баннера:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"id_banner\" value=\"{$id_banner}\" type=\"text\" size=\"35\"></td>
    </tr>";
}elseif($action == "logs_lostpass") {
$data = "
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=iptools&ip=".$row['ip']."\">{$row['ip']}</a><br />
        (<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&ip=".$row['ip']."\">журнал</a>)</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&username=".urlencode($row['username'])."\">{$row['username']}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</div></td>
	<td align=center width=10;><input name=\"selected_all[]\" value=\"{$row['id']}\" type='checkbox'>
	";
$search_data = "
    <tr>
		<td style=\"padding:5px;\">Поиск записей по IP:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"ip\" value=\"{$ip}\" type=\"text\" size=\"35\"></td>
    </tr>
    <tr>
		<td style=\"padding:5px;\">Поиск записей по названию аккаунта:</td>
		<td colspan=\"3\"><input class=\"edit\" name=\"username\" value=\"{$username}\" type=\"text\" size=\"35\"></td>
    </tr>";
}
if($action == "logs_news")
$postzs_link = "<a href=\"/user/{$row['autor']}/\">{$row['autor']}</a><br><a href=\"$PHP_SELF?mod=editnews&action=editnews&id={$row['post_id']}\">{$row['post_id']}</a>";
elseif($action == "logs_news_com") {
		if ($row['back_link'] != 0)
$posts_link = "<a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['autor']}\">{$row['autor']}</a><br><a href=\"{$row['back_link']}\">{$row['post_id']}</a>";
else
$posts_link = "<a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name={$row['autor']}\">{$row['autor']}</a><br>".$row['post_id'];
}
$entries .= "<tr onmouseover=\"this.style.backgroundColor='#f9f9f9'\" onmouseout=\"this.style.backgroundColor='#ffffff'\">
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['id']}</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['date']}</td>
		{$data}
        </tr>
		<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=6></td></tr>";
$entries_showed ++;
if($i >= $files_per_page +$start_from)
break;
}
$sql = "SELECT COUNT(*) as count FROM `".PREFIX ."_".$base_name ."` {$where}";
$all_count_news = $db->super_query($sql);
$all_count_news = $all_count_news['count'];
echo "<table width=\"100%\">
    <tr>
        <td width=\"4\"><img src=\"engine/skins/images/tl_lo.gif\" width=\"4\" height=\"4\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_oo.gif\"><img src=\"engine/skins/images/tl_oo.gif\" width=\"1\" height=\"4\" border=\"0\"></td>
        <td width=\"6\"><img src=\"engine/skins/images/tl_ro.gif\" width=\"6\" height=\"4\" border=\"0\"></td>
    </tr>
    <tr>
        <td background=\"engine/skins/images/tl_lb.gif\"><img src=\"engine/skins/images/tl_lb.gif\" width=\"4\" height=\"1\" border=\"0\"></td>
        <td style=\"padding:5px;\" bgcolor=\"#FFFFFF\">
<table width=\"100%\">
    <tr>
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Меню</div></td>
    </tr>
</table>
<div class=\"unterline\"></div>
<table width=\"100%\">
    <tr>";
echo  "<td width=\"260\" style=\"padding:4px;\"><a href=\"".$config['http_home_url'].$config['admin_path']."?mod=admin_logs_jurnal&action=list\"><b>Главное меню</b></a></td>";
echo "     </tr>
    <tr>
        <td colspan=\"2\"><div class=\"hr_line\"></div></td>
    </tr>
</table>
</td>
        <td background=\"engine/skins/images/tl_rb.gif\"><img src=\"engine/skins/images/tl_rb.gif\" width=\"6\" height=\"1\" border=\"0\"></td>
    </tr>
    <tr>
        <td><img src=\"engine/skins/images/tl_lu.gif\" width=\"4\" height=\"6\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_ub.gif\"><img src=\"engine/skins/images/tl_ub.gif\" width=\"1\" height=\"6\" border=\"0\"></td>
        <td><img src=\"engine/skins/images/tl_ru.gif\" width=\"6\" height=\"6\" border=\"0\"></td>
    </tr>
</table>
</div>";
echo "<form action=\"\" method=\"post\" name=\"{$_REQUEST['action']}\">";
if($entries_showed == 0)
{
echo "
<div style=\"padding-top:5px;padding-bottom:2px;\">
<table width=\"100%\">
    <tr>
        <td width=\"4\"><img src=\"engine/skins/images/tl_lo.gif\" width=\"4\" height=\"4\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_oo.gif\"><img src=\"engine/skins/images/tl_oo.gif\" width=\"1\" height=\"4\" border=\"0\"></td>
        <td width=\"6\"><img src=\"engine/skins/images/tl_ro.gif\" width=\"6\" height=\"4\" border=\"0\"></td>
    </tr>
    <tr>
        <td background=\"engine/skins/images/tl_lb.gif\"><img src=\"engine/skins/images/tl_lb.gif\" width=\"4\" height=\"1\" border=\"0\"></td>
        <td style=\"padding:5px;\" bgcolor=\"#FFFFFF\">
<table width=\"100%\">
    <tr>
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\"><b>{$lang_log_title}</b></div></td>
    </tr>
</table>
<div class=\"unterline\"></div>
<table width=\"100%\">
    <tr>
        <td align=\"center\" style=\"height:50px;\">Записи отсутствуют</td>
    </tr>
</table>
</td>
        <td background=\"engine/skins/images/tl_rb.gif\"><img src=\"engine/skins/images/tl_rb.gif\" width=\"6\" height=\"1\" border=\"0\"></td>
    </tr>
    <tr>
        <td><img src=\"engine/skins/images/tl_lu.gif\" width=\"4\" height=\"6\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_ub.gif\"><img src=\"engine/skins/images/tl_ub.gif\" width=\"1\" height=\"6\" border=\"0\"></td>
        <td><img src=\"engine/skins/images/tl_ru.gif\" width=\"6\" height=\"6\" border=\"0\"></td>
    </tr>
</table>
</div>";
}
else{
echo "<script language='JavaScript' type=\"text/javascript\">
<!--
function check_uncheck_all() {
    var frm = document.{$_REQUEST['action']};
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
";
echo "<div style=\"padding-top:5px;padding-bottom:2px;\">
<table width=\"100%\">
    <tr>
        <td width=\"4\"><img src=\"engine/skins/images/tl_lo.gif\" width=\"4\" height=\"4\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_oo.gif\"><img src=\"engine/skins/images/tl_oo.gif\" width=\"1\" height=\"4\" border=\"0\"></td>
        <td width=\"6\"><img src=\"engine/skins/images/tl_ro.gif\" width=\"6\" height=\"4\" border=\"0\"></td>
    </tr>
    <tr>
        <td background=\"engine/skins/images/tl_lb.gif\"><img src=\"engine/skins/images/tl_lb.gif\" width=\"4\" height=\"1\" border=\"0\"></td>
        <td style=\"padding:5px;\" bgcolor=\"#FFFFFF\">
<table width=\"100%\">
    <tr>
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\" style=\"float:left\"><b>{$lang_log_title}</b></div><div style=\"float:right\"><a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&amp;subaction=delete&amp;clean=all&amp;user_hash={$dle_login_hash}\" />Удалить старые записи</a> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('Очищаем все записи старше 30 дней.', this, event, '220px')\">[?]</div></td>
    </tr>
</table>
<div class=\"unterline\"></div>
<table width=\"100%\">
    <tr>
        <td>
	<table width=100%>
	<tr>
    	<td align=\"center\"><b>ID</b></td>
	<td align=\"center\">Дата</td>
	<td align=\"center\">{$lang_log_data}</td>
	{$lang_sub_data}
    	<td align=\"left\">Событие</td>
    	<td width=10 align=\"center\"><input type=\"checkbox\" name=\"master_box\" title=\"{$lang['edit_selall']}\" onclick=\"javascript:check_uncheck_all()\"></td>
	</tr>
	<tr><td colspan=\"7\"><div class=\"hr_line\"></div></td></tr>
	{$entries}
	<tr><td colspan=\"7\"><div class=\"hr_line\"></div></td></tr>";
$npp_nav ="";
if($start_from >0)
{
$previous = $start_from -$files_per_page;
$npp_nav .= "<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&amp;start_from=$previous&amp;files_per_page=$files_per_page&amp;$link_nav\">&lt;&lt; $lang[edit_prev]</a>";
}
if($all_count_news >$files_per_page)
{
$npp_nav .= " [ ";
$enpages_count = @ceil($all_count_news/$files_per_page);
$enpages_start_from = 0;
$enpages = "";
for($j=1;$j<=$enpages_count;$j++)
{
if($enpages_start_from != $start_from)
$enpages .= "<a class=maintitle href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&amp;start_from=$enpages_start_from&amp;files_per_page=$files_per_page&amp;$link_nav\">$j</a> ";
else
$enpages .= "<span class=navigation> $j </span>";
$enpages_start_from += $files_per_page;
}
$npp_nav .= $enpages;
$npp_nav .= " ] ";
}
if($all_count_news >$i)
{
$how_next = $all_count_news -$i;
if($how_next >$files_per_page)
$how_next = $files_per_page;
$npp_nav .= "<a href=\"$PHP_SELF?mod=admin_logs_jurnal&amp;action={$_REQUEST['action']}&amp;start_from=$i&amp;files_per_page=$files_per_page&amp;$link_nav\">$lang[edit_next] $how_next &gt;&gt;</a>";
}
echo"<tr><td colspan=5 align=right>
<select name=\"subaction\">
<option value=\"\">{$lang['edit_selact']}</option>
<option value=\"delete\">{$lang['edit_seldel']}</option>
</select>
<input type=hidden name=\"action\" value=\"{$_REQUEST['action']}\">
<input type=hidden name=\"mod\" value=\"admin_logs_jurnal\">
<input type=\"hidden\" name=\"user_hash\" value=\"$dle_login_hash\" />
<input class=\"buttons\" type=\"submit\" value=\"{$lang['b_start']}\">
</tr>";
if($entries_showed != 0)
{
echo"<tr><td colspan=6>$npp_nav</td>
</tr>";
}
echo"	</table>
</td>
    </tr>
</table>
</td>
        <td background=\"engine/skins/images/tl_rb.gif\"><img src=\"engine/skins/images/tl_rb.gif\" width=\"6\" height=\"1\" border=\"0\"></td>
    </tr>
    <tr>
        <td><img src=\"engine/skins/images/tl_lu.gif\" width=\"4\" height=\"6\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_ub.gif\"><img src=\"engine/skins/images/tl_ub.gif\" width=\"1\" height=\"6\" border=\"0\"></td>
        <td><img src=\"engine/skins/images/tl_ru.gif\" width=\"6\" height=\"6\" border=\"0\"></td>
    </tr>
</table>
</div>";
}
echo "</form>";
echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"engine/skins/calendar-blue.css\" title=\"win2k-cold-1\" />
<script type=\"text/javascript\" src=\"engine/skins/calendar.js\"></script>
<script type=\"text/javascript\" src=\"engine/skins/calendar-en.js\"></script>
<script type=\"text/javascript\" src=\"engine/skins/calendar-setup.js\"></script>
<script type=\"text/javascript\" src=\"engine/skins/calendar-setup2.js\"></script>
<script type=\"text/javascript\" src=\"engine/skins/tabs.js\"></script>
<script type=\"text/javascript\" src=\"engine/ajax/dle_ajax.js\"></script>";
echo "<form action=\"$PHP_SELF\" method=\"post\" name=\"options_bar\">
    <input type=\"hidden\" name=\"mod\" value=\"admin_logs_jurnal\">
    <input type=\"hidden\" name=\"action\" value=\"{$_REQUEST['action']}\">

<div style=\"padding-top:5px;padding-bottom:2px;\">
<table width=\"100%\">
    <tr>
        <td width=\"4\"><img src=\"engine/skins/images/tl_lo.gif\" width=\"4\" height=\"4\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_oo.gif\"><img src=\"engine/skins/images/tl_oo.gif\" width=\"1\" height=\"4\" border=\"0\"></td>
        <td width=\"6\"><img src=\"engine/skins/images/tl_ro.gif\" width=\"6\" height=\"4\" border=\"0\"></td>
    </tr>
    <tr>
        <td background=\"engine/skins/images/tl_lb.gif\"><img src=\"engine/skins/images/tl_lb.gif\" width=\"4\" height=\"1\" border=\"0\"></td>
        <td style=\"padding:5px;\" bgcolor=\"#FFFFFF\">
<table width=\"100%\">
    <tr>
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Показано событий: <b>{$entries_showed}</b><br> Всего записей: <b>{$all_count_news}</b> {$cat_msg}</div></td>
    </tr>
</table>
<div class=\"unterline\"></div>
<table width=\"100%\">

    <tr>
		<td colspan=\"2\" width=\"140\" style=\"padding:5px;\">Период</td>
        <td>с&nbsp;&nbsp;
<input name=\"from_date\" id=\"from_date\" size=\"20\"  class=\"edit\" value = \"\">
<img src=\"engine/skins/images/img.gif\"  align=\"absmiddle\" id=\"f_trigger_c\" style=\"cursor: pointer; border: 0\" title=\"{$lang['edit_ecal']}\"/><br />
по
<input name=\"to_date\" id=\"to_date\" size=\"20\"  class=\"edit\" value = \"\">
<img src=\"engine/skins/images/img.gif\"  align=\"absmiddle\" id=\"to_trigger_c\" style=\"cursor: pointer; border: 0\" title=\"{$lang['edit_ecal']}\"/>

<script type=\"text/javascript\">
    Calendar.setup({
      inputField     :    \"from_date\",     // id of the input field
      ifFormat       :    \"%Y-%m-%d\",      // format of the input field
      button         :    \"f_trigger_c\",  // trigger for the calendar (button ID)
      align          :    \"Br\",           // alignment
		  timeFormat     :    \"24\",
		  showsTime      :    false,
      singleClick    :    true
    });
</script>
<script type=\"text/javascript\">
    Calendar.setup({
      inputField     :    \"to_date\",     // id of the input field
      ifFormat       :    \"%Y-%m-%d\",      // format of the input field
      button         :    \"to_trigger_c\",  // trigger for the calendar (button ID)
      align          :    \"Br\",           // alignment
		  timeFormat     :    \"24\",
		  showsTime      :    false,
      singleClick    :    true
    });
</script>
</td>
    </tr>

    <tr>
		<td colspan=\"2\" width=\"140\" style=\"padding:5px;\">Записей на страницу</td>
		<td ><input class=\"edit\" style=\"text-align: center\" name=\"files_per_page\" value=\"{$files_per_page}\" type=\"text\" size=\"3\"></td>
    </tr>
{$search_data}
    <tr>
    <td></td>
    <td colspan=\"3\">
    <input class=\"edit\" type=\"submit\" value=\"{$lang['edit_act_1']}\">
     </td>
    </tr>
</table>
</td>
        <td background=\"engine/skins/images/tl_rb.gif\"><img src=\"engine/skins/images/tl_rb.gif\" width=\"6\" height=\"1\" border=\"0\"></td>
    </tr>
    <tr>
        <td><img src=\"engine/skins/images/tl_lu.gif\" width=\"4\" height=\"6\" border=\"0\"></td>
        <td background=\"engine/skins/images/tl_ub.gif\"><img src=\"engine/skins/images/tl_ub.gif\" width=\"1\" height=\"6\" border=\"0\"></td>
        <td><img src=\"engine/skins/images/tl_ru.gif\" width=\"6\" height=\"6\" border=\"0\"></td>
    </tr>
</table>
</div>
</form>";
echofooter();
?>