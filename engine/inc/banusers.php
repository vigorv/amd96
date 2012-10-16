<?PHP

/*
=======================================================================
--- Данный хак был создан: 		ShapeShifter
--- ICQ:				10-280-282
--- Сайт разработчика:			SaVGroup.ru
--- Год разработки:			2009
--- Реквизиты для благодарности:)	Z197619983572 или R401107552947
=======================================================================
*/

if (! defined ( 'DATALIFEENGINE' ))
	die ( "Hacking attempt!" );

if ($config['version_id'] < "7.5")
{
	$member_id['user_group'] = $member_db[1];
	$member_id['name'] = $member_db[2];
}

if ($member_id ['user_group'] == 1) {
	echoheader ( "", "" );

$action = $_REQUEST ['action'];

include ENGINE_DIR . '/data/banusers_config.php';

if($action == "config")
	$action_title = "Настройка модуля";
elseif($action == "logs")
	$action_title = "Логи действий";
elseif($action == "del_logs")
	$action_title = "Логи удалены";
elseif($action == "edit_config")
	$action_title = "Редактирование завершено";
elseif($action == "list")
	$action_title = "Список заблокированных";
elseif($action == "del_list")
	$action_title = "Разблокирование";
else
	$action_title = "Выберите действие";

if ($action != "")
{
	echo <<<HTML

<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><b>Меню</b></div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
<td width="260" style="padding:4px;"><a href="{$config['http_home_url']}{$config['admin_path']}?mod=banusers"><b>Главное меню</b></a></td>

     </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div>
HTML;
}

echo <<<HTML

<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><b>{$action_title}</b></div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
HTML;

if($action == "del_logs") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {

		die( "Hacking attempt! User not found" );

	}

	include_once ENGINE_DIR.'/classes/parse.class.php';
	$parse = new ParseFilter();

	$selected = $_POST['selected_logs'];
	if ($selected AND intval($_REQUEST['code']) != 0)
	{
		foreach	($selected as $id)
		{
			$id = intval($id);
			if (intval($_REQUEST['code']) == 1)
			{
				$db->query( "DELETE FROM " . PREFIX . "_banned_logs WHERE id='$id'" );
			}
		}
		echo "<tr><td><center>Выбранные логи успешно удалены из базы данных.</center>";
		echo "<br /><b><center><a href=\"" . $config ['http_home_url'] . "" . $config ['admin_path'] . "?mod=banusers&action=logs\">Вернуться</a></center></b></td></tr>";
	}
	else
	{
		echo "<tr><td><center>Вы ничего не выбрали или не выбрали действие.</center>";
		echo "<br /><b><center><a href=\"" . $config ['http_home_url'] . "" . $config ['admin_path'] . "?mod=banusers&action=logs\">Вернуться</a></center></b></td></tr>";
	}	

} elseif($action == "del_list") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {

		die( "Hacking attempt! User not found" );

	}

	include_once ENGINE_DIR.'/classes/parse.class.php';
	$parse = new ParseFilter();

	$selected = $_POST['selected_list'];
	if ($selected AND intval($_REQUEST['code']) != 0)
	{
		foreach	($selected as $id)
		{
			$id = intval($id);
			if (intval($_REQUEST['code']) == 1)
			{
				$sel_banned = $db->super_query("SELECT * FROM " . PREFIX . "_banned where id = '$id'");
				$db->query( "UPDATE " . USERPREFIX . "_users SET banned='' WHERE user_id = '{$sel_banned[users_id]}'" );

				$db->query( "DELETE FROM " . PREFIX . "_banned WHERE id='$id'" );

				$description = "Пользователь был <b><font color=green>разблокирован</font></b>.";
				$date = date ("Y-m-d H:i:s");	
				$row_ban = $db->super_query( "SELECT user_id, name, banned, logged_ip, user_group FROM " . USERPREFIX . "_users WHERE user_id = '$sel_banned[users_id]'" );
				$db->query("INSERT INTO `" . PREFIX . "_banned_logs` SET `date` = '{$date}', `username` = '{$member_id[name]}', `ban_user_id` = '{$row_ban[user_id]}', `ban_user_name` = '{$row_ban[name]}', `description` = '{$description}'");
			}
		}
		echo "<tr><td><center>Выбранные пользователи успешно разблокированы.</center>";
		echo "<br /><b><center><a href=\"" . $config ['http_home_url'] . "" . $config ['admin_path'] . "?mod=banusers&action=list\">Вернуться</a></center></b></td></tr>";
	}
	else
	{
		echo "<tr><td><center>Вы ничего не выбрали или не выбрали действие.</center>";
		echo "<br /><b><center><a href=\"" . $config ['http_home_url'] . "" . $config ['admin_path'] . "?mod=banusers&action=list\">Вернуться</a></center></b></td></tr>";
	}


} elseif($action == "config") {

include_once ENGINE_DIR.'/classes/parse.class.php';
$parse = new ParseFilter();

$group_spisok = "";

$result = $db->query("SELECT id, group_name FROM `" . PREFIX . "_usergroups`");
while($row = $db->get_row($result))
{
	$group_spisok .= $row['id']." / ".$row['group_name']."<br>";
}

echo <<<HTML
<form method=post name="addanons" id="addanons" action="" ENCTYPE="multipart/form-data">

<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">ID группы / Название группы<br><span class=small>Список имеющихся групп на вашем сайте (для удобства заполнение полей)</span></td>
<td style="padding:4px;">
{$group_spisok}
</td>
</tr>

<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Администраторы:<br><span class=small>Введите через запятую ID групп, у которых будет права блокировать пользователей с без ограничений по кол-ву дней блокировки</span></td>
<td style="padding:4px;">
<input type="text" name="admin" value="{$ban_conf['admin']}" style="width:200px;" class="f_input" />
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Модераторы:<br><span class=small>Введите через запятую ID групп, у которых будет права блокировать пользователей с органичениями (кол-во дней блокировки)</span></td>
<td style="padding:4px;">
<input type="text" name="moder" value="{$ban_conf['moder']}" style="width:200px;" class="f_input" />
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Удаление из базы:<br><span class=small>Введите через запятую ID групп, которые смогу удалять пользователя из БД сайта (со всеми комментариями или без них)</span></td>
<td style="padding:4px;">
<input type="text" name="delete" value="{$ban_conf['delete']}" style="width:200px;" class="f_input" />
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Защищенные группы:<br><span class=small>Введите через запятую ID групп, которых нельзя будет заблокировать через сайт</span></td>
<td style="padding:4px;">
<input type="text" name="protected" value="{$ban_conf['protected']}" style="width:200px;" class="f_input" />
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Количество дней:<br><span class=small>Максимальное количество дней, на которое модератор может блокировать пользователя</span></td>
<td style="padding:4px;">
<input type="text" name="days" value="{$ban_conf['days']}" style="width:200px;" class="f_input" />
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
	<td style="padding:4px;"></td>
	<td>
	<br />
	<input type="submit" class="buttons" value="{$lang['btn_send']}" style="width:150px;">
    <input type=hidden name="mod" value="banusers">
	<input type=hidden name="action" value="edit_config">
	<input type="hidden" name="user_hash" value="$dle_login_hash" />
	</td>
</tr>

</form>
HTML;

} elseif($action == "edit_config") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {

		die( "Hacking attempt! User not found" );

	}

	include_once ENGINE_DIR.'/classes/parse.class.php';
	$parse = new ParseFilter();

	function strip_data($text) {
		$quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "'", "/", "¬", ";", ":", "@", "~", "[", "]", "{", "}", "=", ")", "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"' );
		$goodquotes = array ("-", "+", "#" );
		$repquotes = array ("\-", "\+", "\#" );
		$text = stripslashes( $text );
		$text = trim( strip_tags( $text ) );
		$text = str_replace( $quotes, '', $text );
		$text = str_replace( $goodquotes, $repquotes, $text );
		return $text;
	}

	$protected = strip_data( $db->safesql( $_POST['protected'] ) );
	$protected = ereg_replace(" +", " ", $protected);

	$delete = strip_data( $db->safesql( $_POST['delete'] ) );
	$delete = ereg_replace(" +", " ", $delete);

	$moder = strip_data( $db->safesql( $_POST['moder'] ) );
	$moder = ereg_replace(" +", " ", $moder);

	$admin = strip_data( $db->safesql( $_POST['admin'] ) );
	$admin = ereg_replace(" +", " ", $admin);

	$days = intval($_POST['days']);
	if ($days < 0)
		$days = 0 - $days;

	$content  = "<?PHP\r\n";

	$content .= "\$ban_conf = array (\r\n";
	$content .= "'admin' => \"".$admin."\",\r\n";
	$content .= "'moder' => \"".$moder."\",\r\n";
	$content .= "'delete' => \"".$delete."\",\r\n";
	$content .= "'protected' => \"".$protected."\",\r\n";
	$content .= "'days' => \"".$days."\",\r\n";

	$content .= ");\r\n?>";

	$filename = "./engine/data/banusers_config.php";
	if ( $file = fopen($filename, "w") )
	{
		fwrite($file, $content);
		fclose($file);
		echo "<tr><td><center>Конфигурация модуля успешно изменена.</center>";
		echo "<br /><b><center><a href=\"" . $config ['http_home_url'] . "" . $config ['admin_path'] . "?mod=banusers&action=config\">Вернуться</a></center></b></td></tr>";
	}
	else
	{
		echo "<tr><td><center>Не удалось записать файл. Выставьте права достпупа на файл question.php 0666</center></td></tr>";
		exit();
	}

} elseif($action == "logs") {

	include_once ENGINE_DIR . '/classes/parse.class.php';

	$parse = new ParseFilter( Array (), Array (), 1, 1 );

	$uid = intval($_REQUEST['uid']);
	$username = $db->safesql(trim(htmlspecialchars($_REQUEST['username'])));
	$where = array();

	if ($uid)
		$where[] = "(ban_user_id = '{$uid}')";
	if ($username)
		$where[] = "(username = '{$username}')";

	$where = implode(" AND ",$where);

	if ($where)
		$where = "WHERE " . $where;

	if(!intval($files_per_page))
		$files_per_page = 30;

	if (!isset($start_from))
		$start_from = 0;
	$sql = "SELECT * FROM `" . PREFIX . "_banned_logs` {$where} ORDER BY `date` DESC LIMIT {$start_from},{$files_per_page}";

	$result = $db->query($sql);
	$entries_showed = 0;

	$flag = 1;
	if($start_from == "0")
		$start_from = "";

	$i = $start_from;

	while($row = $db->get_row($result))
	{

		$i++;

	$entries .= "<tr onmouseover=\"this.style.backgroundColor='#f9f9f9'\" onmouseout=\"this.style.backgroundColor='#ffffff'\">
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['id']}</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['date']}</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&username={$row['username']}\">{$row['username']}</a></td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&uid={$row['ban_user_id']}\">{$row['ban_user_name']}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['description']}</td>
	<td align=right width=10 style=\"padding:4px;\"><input name=\"selected_logs[]\" value=\"{$row['id']}\" type='checkbox'>
        </tr>
		<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=6></td></tr>";
		$entries_showed ++;

		if($i >= $files_per_page + $start_from)
		break;
	}

	$sql = "SELECT COUNT(*) as count FROM `" . PREFIX . "_banned_logs` {$where}";
	$all_count_news = $db->super_query($sql);
	$all_count_news = $all_count_news['count'];

	echo <<<JSCRIPT
<script language='JavaScript' type="text/javascript">
<!--
function check_uncheck_all() {
    var frm = document.logs;
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

JSCRIPT;

echo <<<HTML
<form method=post name="logs" id="logs" action="" ENCTYPE="multipart/form-data">
 	<tr>
	<td align="center"><b>ID</b></td>
  	<td align="center">Дата</td>
	<td align="center">Кто</td>
    	<td align="center">Кого</td>
	<td align="left">Действие</td>
     	<td width=10 align="right"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:check_uncheck_all()"></td>
	</tr>
	<tr><td colspan="6"><div class="hr_line"></div></td></tr>
	{$entries}
	<tr><td colspan="6"><div class="hr_line"></div></td></tr>

<tr><td colspan=6 align=right>
<select name="code">
<option value="0">--Выбирите действие</option>
<option value="1">Удалить из базы</option>
</select>
<input type="submit" class="buttons" value="{$lang['btn_send']}" style="width:120px;">
<input type=hidden name="mod" value="banusers">
<input type=hidden name="action" value="del_logs">
<input type="hidden" name="user_hash" value="$dle_login_hash" />
</tr>
</form>
HTML;

	$npp_nav ="";

	if($start_from > 0)
	{
		$previous = $start_from - $files_per_page;
		$npp_nav .= "<a href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&amp;start_from=$previous&amp;files_per_page=$files_per_page&amp;username={$username}&amp;uid={$uid}\">&lt;&lt; $lang[edit_prev]</a>";
		//$tmp = 1;
	}

	// pagination

	if($all_count_news > $files_per_page)
	{
		$npp_nav .= " [ ";
		$enpages_count = @ceil($all_count_news/$files_per_page);
		$enpages_start_from = 0;
		$enpages = "";

		for($j=1;$j<=$enpages_count;$j++)
		{
			if($enpages_start_from != $start_from)
			$enpages .= "<a class=maintitle href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&amp;start_from=$enpages_start_from&amp;files_per_page=$files_per_page&amp;username={$username}&amp;uid={$uid}\">$j</a> ";
			else
			$enpages .= "<span class=navigation> $j </span>";

			$enpages_start_from += $files_per_page;
		}
		$npp_nav .= $enpages;
		$npp_nav .= " ] ";
	}


	// pagination


	if($all_count_news > $i)
	{
		$how_next = $all_count_news - $i;
		if($how_next > $files_per_page)
		$how_next = $files_per_page;

		$npp_nav .= "<a href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&amp;start_from=$i&amp;files_per_page=$files_per_page&amp;username={$username}&amp;uid={$uid}\">$lang[edit_next] $how_next &gt;&gt;</a>";
	}

	if($entries_showed != 0)
	{
		echo<<<HTML
<tr><td colspan=6>$npp_nav</td>
</tr>
HTML;
}
} elseif($action == "list") {

	include_once ENGINE_DIR . '/classes/parse.class.php';

	$parse = new ParseFilter( Array (), Array (), 1, 1 );

	if(!intval($files_per_page))
		$files_per_page = 30;

	if (!isset($start_from))
		$start_from = 0;
	$sql = "SELECT * FROM `" . PREFIX . "_banned` WHERE users_id <> '0' ORDER BY `id` DESC LIMIT {$start_from},{$files_per_page}";

	$result = $db->query($sql);
	$entries_showed = 0;

	$flag = 1;
	if($start_from == "0")
		$start_from = "";

	$i = $start_from;

	while($row = $db->get_row($result))
	{

		$i++;
		if ($row['date'])
			$row['date'] = langdate( "j F Y H:i", $row['date'] );
		else
			$row['date'] = "Не ограничено";

		if (!$row['days'])
			$row['days'] = "Не ограничено";

		if (!$row['descr'])
			$row['descr'] = "Не указано";

		$row2 = $db->super_query("SELECT name FROM " . USERPREFIX . "_users where user_id = '$row[users_id]'");

	$entries .= "<tr onmouseover=\"this.style.backgroundColor='#f9f9f9'\" onmouseout=\"this.style.backgroundColor='#ffffff'\">
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['id']}</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['days']}</td>
        <td class=\"list\" align=\"center\" style=\"padding:4px;\">{$row['date']}</td>
	<td class=\"list\" align=\"center\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=editusers&action=list&search=yes&search_name=".urlencode($row2['name'])."\">{$row2['name']}</a></td>
        <td class=\"list\" align=\"left\" style=\"padding:4px;\">{$row['descr']}</td>
	<td align=right width=10><input name=\"selected_list[]\" value=\"{$row['id']}\" type='checkbox'>
        </tr>
		<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=6></td></tr>";
		$entries_showed ++;

		if($i >= $files_per_page + $start_from)
		break;
	}

	$sql = "SELECT COUNT(*) as count FROM `" . PREFIX . "_banned` WHERE users_id <> '0'";
	$all_count_news = $db->super_query($sql);
	$all_count_news = $all_count_news['count'];

	echo <<<JSCRIPT
<script language='JavaScript' type="text/javascript">
<!--
function check_uncheck_all() {
    var frm = document.list;
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

JSCRIPT;

echo <<<HTML
<form method=post name="list" id="list" action="" ENCTYPE="multipart/form-data">
 	<tr>
	<td align="center"><b>ID</b></td>
  	<td align="center">Дней</td>
	<td align="center">Окончание бана</td>
    	<td align="center">Пользователь</td>
	<td align="left">Причина</td>
     	<td width=10 align="right"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:check_uncheck_all()"></td>
	</tr>
	<tr><td colspan="6"><div class="hr_line"></div></td></tr>
	{$entries}
	<tr><td colspan="6"><div class="hr_line"></div></td></tr>

<tr><td colspan=6 align=right>
<select name="code">
<option value="0">--Выбирите действие</option>
<option value="1">Разбанить</option>
</select>
<input type="submit" class="buttons" value="{$lang['btn_send']}" style="width:120px;">
<input type=hidden name="mod" value="banusers">
<input type=hidden name="action" value="del_list">
<input type="hidden" name="user_hash" value="$dle_login_hash" />
</tr>
</form>
HTML;

	$npp_nav ="";

	if($start_from > 0)
	{
		$previous = $start_from - $files_per_page;
		$npp_nav .= "<a href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&amp;start_from=$previous&amp;files_per_page=$files_per_page\">&lt;&lt; $lang[edit_prev]</a>";
		//$tmp = 1;
	}

	// pagination

	if($all_count_news > $files_per_page)
	{
		$npp_nav .= " [ ";
		$enpages_count = @ceil($all_count_news/$files_per_page);
		$enpages_start_from = 0;
		$enpages = "";

		for($j=1;$j<=$enpages_count;$j++)
		{
			if($enpages_start_from != $start_from)
			$enpages .= "<a class=maintitle href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&amp;start_from=$enpages_start_from&amp;files_per_page=$files_per_page\">$j</a> ";
			else
			$enpages .= "<span class=navigation> $j </span>";

			$enpages_start_from += $files_per_page;
		}
		$npp_nav .= $enpages;
		$npp_nav .= " ] ";
	}


	// pagination


	if($all_count_news > $i)
	{
		$how_next = $all_count_news - $i;
		if($how_next > $files_per_page)
		$how_next = $files_per_page;

		$npp_nav .= "<a href=\"$PHP_SELF?mod=banusers&amp;action={$_REQUEST['action']}&amp;start_from=$i&amp;files_per_page=$files_per_page\">$lang[edit_next] $how_next &gt;&gt;</a>";
	}

	if($entries_showed != 0)
	{
		echo<<<HTML
<tr><td colspan=6>$npp_nav</td>
</tr>
HTML;
}

} else {

	$options_conf = array();
    	$options_conf['menu'] = array(
                    	array(
                    		'name'       	=> "Настройка модуля",
                    		'url'        	=> "$PHP_SELF?mod=banusers&action=config",
				'descr'      	=> "В данном разделе, вы сможете настроить модуль",
				'image'      	=> "options.png",
                    		'access'     	=> "1",
                    	),
                    	array(
                    		'name'       	=> "Логи действий",
                    		'url'        	=> "$PHP_SELF?mod=banusers&action=logs",
				'descr'      	=> "Логи блокировок пользователей",
				'image'      	=> "logs.png",
                    		'access'     	=> "1",
                    	),
                    	array(
                    		'name'       	=> "Список заблокированных",
                    		'url'        	=> "$PHP_SELF?mod=banusers&action=list",
				'descr'      	=> "Вывод всех заблокированных пользователей",
				'image'      	=> "list.png",
                    		'access'     	=> "1",
                    	),
     		);

	foreach($options_conf as $sub_options => $value)
	{
   		$count_options = count($value);
    		for($i=0; $i < $count_options; $i++)
		{
    			if($member_id['user_group'] != $value[$i]['access'])
				unset($options_conf[$sub_options][$i]);
    		}
	}

	$subs = 0;

	foreach($options_conf as $sub_options)
	{
		if (!count($sub_options)) continue;

$i=0;

foreach($sub_options as $option)
{

if ($i > 1) {echo "</tr><tr>"; $i=0;}

$i++;

echo <<<HTML
<td width="50%">
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/banusers/{$option['image']}" border="0"></td>
        <td valign="middle"><div class="quick"><a href="{$option['url']}"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>
    </tr>
</table>
</td>
HTML;

}

}

	echo <<<HTML
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table></div>
HTML;

$stats = array();
$row = $db->super_query("SELECT COUNT(*) as count FROM ".PREFIX."_banned WHERE users_id <> '0'");
$stats['all'] = $row['count'];
$row = $db->super_query("SELECT COUNT(*) as count FROM ".PREFIX."_banned WHERE days = '0' AND users_id <> '0'");
$stats['nol'] = $row['count'];
$row = $db->super_query("SELECT COUNT(*) as count FROM ".PREFIX."_banned WHERE days < '10' AND days <> '0' AND users_id <> '0'");
$stats['b10'] = $row['count'];
$row = $db->super_query("SELECT COUNT(*) as count FROM ".PREFIX."_banned WHERE days < '30' AND days <> '0' AND users_id <> '0'");
$stats['b30'] = $row['count'];
$row = $db->super_query("SELECT COUNT(*) as count FROM ".PREFIX."_banned WHERE days > '30' AND users_id <> '0'");
$stats['b30'] = $row['count'];

function show_stats() {
global $stats;

echo "
<tr><td style=\"padding:2px;\">Заблокированных:</td><td>{$stats['all']}</td></tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
<tr><td style=\"padding:2px;\">Заблокированых навсегда</td><td>{$stats['nol']}</td></tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
<tr><td style=\"padding:2px;\">Заблокированых менее чем на 10 дней</td><td>{$stats['b10']}</td></tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
<tr><td style=\"padding:2px;\">Заблокированых менее чем на 30 дней</td><td>{$stats['b30']}</td></tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
<tr><td style=\"padding:2px;\">Заблокированых больше чем на 30 дней</td><td>{$stats['b30']}</td></tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
";
}

echo <<<HTML

<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><b>Статистика</b></div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
HTML;

	show_stats();

}
	echo <<<HTML
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table></div>
HTML;

	echofooter ();


} else {
	msg ( "error", $lang ['addnews_denied'], $lang ['db_denied'] );
}

?>