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
@include_once ENGINE_DIR .'/inc/plugins/backup.php';
@include_once ENGINE_DIR .'/inc/include/functions.inc.php';
@require_once ENGINE_DIR .'/inc/plugins/rss.classes.php';
@require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';

chmod_pap(ROOT_DIR .'/backup/');
if (!is_dir(ROOT_DIR .'/backup/rss')){
@mkdir(ROOT_DIR .'/backup/rss',0777);
}
chmod_pap(ROOT_DIR .'/backup/rss');

if ($action == 'save_channel')
{
$ids = $_POST['channel'];
if (count ($ids) == 0)
{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
}

$name = '/backup/rss/'.date("Y-m-d_H-i").'_rss.zip';
foreach ($ids as $id)
{
	$cop = '';
$values = array();
$copy = array();
$copys = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
$number = $copys['xpos'];
$copys['id'] =  '';
foreach ($copys as $key => $value){
	$values[] = $key;
$copy[$key] = "'".$db->safesql(stripslashes($value))."'";
}

$copss = implode(',', $copy);
$valuess = implode(',', $values);

$cop = $copss.'++++'.$valuess.'
';

$handler = fopen(ROOT_DIR.$name,'ab');
fwrite($handler,$cop);
fclose($handler);

if (trim ($copy['title']) != '')
{$title = stripslashes (strip_tags ($copy['title']));
if (50 <strlen ($title))
{
$title = substr ($title,0,50) .'...';
}
}
else
{
$title = $lang_grabber['no_title'];
}
$mgs .= 'Канал '.$number.'<font color="green">"'.$title.' | '.$copy['url'].'"</font> <font color="red">'.$lang_grabber['copy_channel_ok'].'</font><br />';


}
if (@file_exists(ROOT_DIR .$name) ){
$mgs .= '<br /><br /><A href="'.$name.'" ><STRONG>СКАЧАТЬ ФАЙЛ</STRONG></A>';
msg ($lang_grabber['info'],'<b>ЭКСПОРТИРОВАНИЕ КАНАЛА</b>', $mgs ,$PHP_SELF .'?mod=rss');
}else{ msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'].'<br />или у вас нет доступа к данному действию',$PHP_SELF .'?mod=rss');}
return 1;
}

if ($action == 'save_up_channel')
{
echoheader ('Загрузка из файла','');
opentable ('');
tableheader ('<b>ИМПОРТ КАНАЛОВ</b>');
echo '<table width="100%">
<tr><td background="engine/skins/images/mline.gif" height=1 colspan=2></td></tr>
<tr>
		<td style="padding:4px" class="option">
		<b>Загрузить файл с сохранёнными ранее каналами</b><br /><span class=small>Файл должен быть в формате ZIP (гггг-мм-чч_час-мин_rss.zip)<br />
<font color=green><i>пример: 2010-07-20_17-54_rss.zip</i></font></span></td>
		<td align=middle >
<form action="?mod=rss" method=post enctype="multipart/form-data" name="form" id="form">
<input type="hidden" name="action" value="backup">
<input type=file class="edit" name=uploadfile>
<input type=submit class="edit" value='.$lang['db_load_a'].'></form></td>
</tr><tr><td background="engine/skins/images/mline.gif" height=1 colspan=2></td></tr>
	<tr>
		<td colspan="2"><div class="hr_line"></div></td>
	</tr>
<tr><td style="padding:4px" class="option">
<input type="button"	class="edit" value=" Выйти " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /></td></tr>
</table>';
closetable ();
echofooter ();
exit();
}




if( $action == 'backup') {
global $lang_grabber;
// var_export ($_POST);
$uploadfile = ROOT_DIR .'/backup/rss/bakup.txt';
if($_POST['save'] != 'save'){
$uploadfilename = $_FILES['uploadfile']['name'];
if (@move_uploaded_file($_FILES['uploadfile']['tmp_name'],$uploadfile))
{
$uploadfile = file($uploadfile);
$tpls = '<form method=post name="news_form" id="news_form" action="?mod=rss">
<input type="hidden" name="action" value="backup" />
<table width="100%">
<tr><td style="padding:4px" class="navigation">
<b>Что обозначает цвет?</b><br />
<font color="red">красным</font> - канал уже имеется в Ваше базе, можете <u>добавить</u>  или <u>перезаписать</u> <i>(выбирается галками)</i>.<br />
<font color="green">зеленым</font> - новый канал.<td></tr><tr>
<td align="right" colspan="3"  class="navigation">cписок каналов из файла: <b>'.$uploadfilename.'</b>
</td></tr>
	<tr>
		<td colspan="3"><div class="hr_line"></div></td>
	</tr>
<tr><td class="navigation"><center><b>Наименование канала</b></center></td><td width="2%" ><input type="checkbox"	name="all_id" id="all_id" onclick="checkAll(document.news_form.id)" title="Добавить" /></td><td width="2%" ><input type="checkbox" name="all_upd" id="all_upd" onclick="checkAlls(document.news_form.upd)" title="Перезаписать" /></td></tr>
	<tr>
		<td colspan="3"><div class="hr_line"></div></td>
	</tr>
</table>
<table width="100%">
';

foreach ($uploadfile as $value)
	{

$key = explode("++++", $value);
$ks = explode ("','", $key[0]);
$vs = explode (",", $key[1]);
foreach ($vs as $k=>$v)
		{
if ($ks[$k] == "'")$ks[$k] = '';
$rss_array[$v] = $ks[$k];
}
//var_export ($rss_array);
$rss_dub = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE url = '".$rss_array['url']."'");
if (trim ($rss_array['title']) != '')
{
$title = trim(stripslashes (strip_tags ($rss_array['title'])), "'");
if (50 <strlen ($title))
{
$title = substr ($title,0,50) .'...';
}
}
else
{
$title = $lang_grabber['no_title'];
}
if ($rss_dub['id'] == '')
		{
$tpls .= '<tr><td style="padding:4px"><font color="green">'.$title.'</font></td><td width="2%" ><input type="checkbox"	name="id[]" id="id" checked value="'.$rss_array['xpos'].'" title="Добавить" /></td><td width="2%" ><input type="checkbox" disabled title="Перезаписать" /></td></tr>';
}else{
$tpls .= '<tr><td style="padding:4px"><font color="red">'.$title.'</font></td><td width="2%" ><input type="checkbox" id="id" name="id[]" value="'.$rss_array['xpos'].'" title="Добавить" /></td><td width="2%" ><input type="checkbox" name="upd[]" id="upd" checked value="'.$rss_array['xpos'].'" title="Перезаписать" /></td></tr>';
}
$tpls .= '<td background="engine/skins/images/mline.gif" height=1 colspan=6></td>';
	}

$tpls .= '</table><div class="hr_line"></div>
<input type="hidden" name="save" value="save" />
<input type="hidden" name="filename" value="'.$uploadfilename.'" />
		<input align="left" class="edit" type="submit" value=" Сохранить " >&nbsp;
		<input type="button"	class="edit" value=" Выйти " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /><br />
</form><br />';
echoheader ('Загрузка из файла','');
opentable ('');
tableheader ('<b>ИМПОРТ КАНАЛОВ</b>');
//tablehead ('Список каналов из файла <font color="green"><b>'.$uploadfilename.'</b></font>');
echo"
<script>
function checkAll(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==''?'#E8F9E6':'';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          ='#E8F9E6';
      }document.news_form.all_id.checked=true;
document.news_form.all_upd.checked=false;
    }
}

function checkAlls(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==''?'#FFCC00':'';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          ='#FFCC00';
      }document.news_form.all_upd.checked=true;
document.news_form.all_id.checked=false;
    }
}

</script>
";
echo $tpls;

closetable ();
echofooter ();
exit();
}else{
@unlink($_FILES['uploadfile']['tmp_name']);
msg($lang_grabber['info'],$lang_grabber['info'],$lang['images_uperr_3'],$PHP_SELF .'?mod=rss');
}

}else{

if (count($_POST['id']) != 0 or count($_POST['upd']) != 0){
$uploadfile = file($uploadfile);
//var_export ($uploadfile);
foreach ($uploadfile as $value)
	{
$rss_array = array();
$rss_upd = array();
$key = explode("++++", $value);
$ks = explode ("','", $key[0]);
$vs = explode (",", $key[1]);
foreach ($vs as $k=>$v)
		{
if ($ks[$k] == "'")$ks[$k] = '';
$rss_array[$v] = $ks[$k];
if ($v != 'xpos' and $v != 'id')$rss_upd[] = $v."="."'".$ks[$k]."'";
}
$update = implode (',', $rss_upd);

if (count($_POST['upd']!=0) and isset($_POST['upd']))
		{
foreach($_POST['upd'] as $kls){
if ($kls == $rss_array['xpos']){
$rss_dub = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE url = '".$rss_array['url']."'");
$db->query( 'UPDATE '.PREFIX ."_rss SET $update WHERE id ='".$rss_dub['id']."'");
}
}
		}

if (count($_POST['id']!=0) and isset($_POST['id']))
		{
foreach($_POST['id'] as $kls)
		{
if ($kls == $rss_array['xpos']){
$rss_dub = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE url = '".$rss_array['url']."'");
if ($rss_dub['id'] == '')
$db->query ('INSERT INTO '.PREFIX ."_rss ({$key[1]})VALUES ({$key[0]})");
$sql_result = $db->query ('SELECT url FROM '.PREFIX .'_rss' );
$id = $db->insert_id();
$pnum = $db->num_rows ($sql_result) + 1;
$db->query( 'UPDATE '.PREFIX ."_rss SET xpos = '$pnum' WHERE id ='$id'");
		}
	}
}

	}

msg($lang_grabber['info'],$lang_grabber['info'],'Каналы из файла добавлены в базу',$PHP_SELF .'?mod=rss');

}else{msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');}
}

return 1;
}


?>
