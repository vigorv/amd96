<?php
/*
=====================================================
 Скрипт модуля Rss Grabber 3.6.8
 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2009-2010
=====================================================
*/

if( !defined( 'DATALIFEENGINE') ) {
die( "Hacking attempt!");
}

if ($_REQUEST['doaction'] == 'del')
{
if( !($member_id['user_group'] =='1')) msg ( 'error','Нет доступа','У вас нет прав для выполнения данного действия',$PHP_SELF .'?mod=rss');
$db->query ('DELETE FROM '.PREFIX ."_rss_category where id='".$_REQUEST['id']."'");

$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss where category like '%=".$_REQUEST['id']."'");
while ($row = $db->get_row ($sql_result))
{
$row['category'] = str_replace('='.$_REQUEST['id'], '=0', $row['category']);
$db->query ('UPDATE '.PREFIX .('_rss set category='.$row['category'].' WHERE id = \''.$row['id'].'\''));
}
msg ('Редактирование','<b>УДАЛЕНИЕ ГРУППЫ</b>', 'Группа удалена',$PHP_SELF .'?mod=rss&action=grups');

}

if ($_POST['sort'] == ' Сортировать группы ')
{
$i=1;
foreach ($_POST['kanal'] as $k=>$v)
{
$db->query ('UPDATE '.PREFIX .('_rss_category set kanal='.((int)$v).' WHERE id = \''.((int)$k) .'\''));
$i++;
}
msg ('Редактирование','<b>СОРТИРОВКА ГРУПП</b>', 'Группы отсортированы',$PHP_SELF .'?mod=rss&action=grups');
return 1;
}

if ($_POST['add'] == 'Добавить' and trim($_POST['title']) != ''){

$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss_category where title='".$_POST['title']."'");
if ($db->num_rows ($sql_result) == 0){
	$db->query( 'INSERT INTO '.PREFIX ."_rss_category (osn, title, kanal)VALUES('".intval($_POST['rss_priv'])."', '".$_POST['title']."', '".count($_POST['kanal'])."'+1)");
msg ('Редактирование','<b>ДОБАВЛЕНИЕ ГРУППЫ</b>', 'Группа создана',$PHP_SELF .'?mod=rss&action=grups');
}else{msg ('Редактирование','<b>ДОБАВЛЕНИЕ ГРУППЫ</b>', 'Такая группа уже есть в базе',$PHP_SELF .'?mod=rss&action=grups');}
}




if ($_POST['rid'] == 'Изменить')
{
	$db->query( 'UPDATE ' . PREFIX . "_rss_category set title='".$_POST['title']."' where id='".$_POST['id']."'");
	msg ('Редактирование','<b>РЕДАКТИРОВАНИЕ ГРУППЫ</b>', 'Группа отредактирована',$PHP_SELF .'?mod=rss&action=grups');
}


//Главная
echoheader("ГРУППЫ", '');
opentable ('<b>ГРУППЫ</b>');
$df = array();
$sql_category = $db->query ('SELECT * FROM '.PREFIX ."_rss");
while ($row = $db->get_row ($sql_category))
{
$category = explode ('=', $row['category']);

$df[$category[1]][] = $row['id'];

}


$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss_category ORDER BY kanal asc");
$channel_inf = array();
$entries = array();
while ($row = $db->get_row ($sql_result))
{
$run[0] = '-- Основная группа --';
if ($row['osn'] == '0'){
            $entries[$row['id']][0] = "
<tr>
		<td width=\"5%\" style=\"padding:1px\" align=\"center\"><input type=\"text\" name=\"kanal[".$row['id']."]\" value=\"".$row['kanal']."\" class=\"edit\" align=\"center\" size=\"3\" /></td>
<td>
{$row['title']} (".count($df[$row['id']]).")
</td>
<td width=\"5%\" ><a onClick=\"return dropdownmenu(this, event, MenuBuild('" . $row['id'] . "', '" . $led_action . "'), '150px')\" href=\"#\"><img src=\"engine/skins/images/browser_action.gif\" border=\"0\"></a></td>
</tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>";
$channel_inf[$row['id']][$row['id']] =  $row['title'];
}else{
$entries[$row['osn']][$row['id']] = "
<tr>
		<td width=\"5%\" style=\"padding:1px\" align=\"center\"><input type=\"text\" name=\"kanal[".$row['id']."]\" value=\"".$row['kanal']."\" class=\"edit\" align=\"center\" size=\"3\" /></td>
<td>
-- {$row['title']} (".count($df[$row['id']]).")
</td>
<td width=\"5%\" ><a onClick=\"return dropdownmenu(this, event, MenuBuild('" . $row['id'] . "', '" . $led_action . "'), '150px')\" href=\"#\"><img src=\"engine/skins/images/browser_action.gif\" border=\"0\"></a></td>
</tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>";
$channel_inf[$row['osn']][$row['id']] = '-- '. $row['title'];
}
}
foreach($channel_inf as $value)
{
	if (count($value) != '0'){
foreach($value as $kkey=>$key)
{
$run[$kkey] = $key;
}
	}
}

echo <<< HTML
<form method="post" name="rss_sort" id="rss_sort">
<table width="100%">
<tr>
<th width="5%" align="center" class="navigation" style="padding:4px">№</th>
<th align="center" class="navigation" style="padding:4px">Наименование</th>
<th width="5%" align="center" class="navigation" style="padding:4px">Действие</th>
</tr>
</table>
HTML;
unterline ();
echo <<< HTML
<table width="100%">
HTML;
if ($config['version_id'] < '8.5'){
echo '<script type="text/javascript" src="engine/ajax/menu.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/menu.js"></script>';
}


echo <<< HTML
<script language="javascript" type="text/javascript">
<!--
function MenuBuild( m_id , led_action){

var menu=new Array()
var lang_action = "";

menu[0]='<a onClick="document.location=\'?mod=rss&action=grups&doaction=exp&id=' + m_id + '\'; return(false)" href="#">Редактировать</a>';
menu[1]='<a onClick="javascript:confirmdelete(' + m_id + '); return(false)" href="#">удалить</a>';

return menu;
}
function confirmdelete(id){
var agree=confirm("Вы действительно хотите удалить выбраную группу? *При удалении группы все каналы будут перенесены в основной список");
if (agree)
document.location="?mod=rss&action=grups&doaction=del&id="+id;
}
//-->
</script>
HTML;
if (count($entries) != '0'){
foreach($entries as $value)
{
echo implode('', $value);
}
}else{echo  '<tr><td colspan=3><center>-- Нет созданных групп --</center></td></tr>';}

if ($_REQUEST['doaction'] != 'exp')
{
echo '
 <tr><td colspan=3 class="unterline"></td></tr>
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;" colspan=2><div class="navigation" ><b>ДОБАВИТЬ ГРУППУ/ПОДГРУППУ</b></div></td>
        <td bgcolor="#EFEFEF" height="29" style="padding-right:10px;" class="navigation" align="right" ></td>
</tr>
 <tr><td colspan=3><div class="unterline"></div></td></tr>
';
	echo "<tr><td height=1 colspan=3 ><br />
<input type=\"text\" class=\"edit\" name=\"title\" value=\"\">
   <select name=\"rss_priv\" class=\"load_img\">
   ".sel ($run)."
   </select>
<input name=\"add\" type=\"submit\" class=\"edit\" style=\"background: #FFF; font-size:8pt;\" value=\"Добавить\" >
<input name=\"sort\" type=\"submit\" class=\"edit\"	value=' Сортировать группы '/>
</td></tr>";

}
echo "</table>
</form>";

if ($_REQUEST['doaction'] == 'exp')
{
	if ($_POST['rid'] != 'Изменить'){
$sql_result = $db->super_query ('SELECT * FROM '.PREFIX ."_rss_category where id='".$_REQUEST['id']."'");
echo '
<table width="100%">
 <tr><td colspan=3 class="unterline"></td></tr>
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><b>РЕДАКТИРОВАТЬ ГРУППУ/ПОДГРУППУ</b></div></td>
        <td bgcolor="#EFEFEF" height="29" style="padding-right:10px;" class="navigation" align="right"></td>
    </tr>
</table>
<div class="unterline"></div>';
echo "
<div align=\"left\">
<font color='green'><b>{$sql_result['title']}</b></font><br />
<form method=post name=\"addnews\" id=\"addnews\">
<input type=hidden name=\"id\" value=\"{$sql_result['id']}\">
<input class=\"edit\" name=\"title\" value=\"{$sql_result['title']}\">
   <select name=\"rss_priv\" class=\"load_img\">
   ".sel ($run,$sql_result['osn'])."
   </select> 
<input name=\"rid\" type=\"submit\" class=\"edit\" style=\"background: #FFF; font-size:8pt;\" value=\"Изменить\" > <input type=\"button\" class=\"edit\"	value=' Отмена ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss&action=grups\"' />
</form>  </div>
";
	}
}

if ($_REQUEST['doaction'] != 'exp')
{
echo "<br /><div class=\"unterline\"></div>
<div align=\"left\">
<input type=\"button\" class=\"edit\"	value=' ".$lang_grabber['out']." ' onClick='document.location.href = \"".$PHP_SELF ."?mod=rss\"' />
</div>";
}
closetable ();
echofooter ();

?>