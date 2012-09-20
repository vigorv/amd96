<?php
/*
=====================================================
 myFAQ module, version 1.1, for DLE 6.5
-----------------------------------------------------
 http://www.dimonblog.net/
-----------------------------------------------------
 Copyright (c) 2007 CrasH*, PAV
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: faq.php
-----------------------------------------------------
 Назначение: вывод FAQ
=====================================================
*/

if(!defined('DATALIFEENGINE')) {
	die("Hacking attempt!");
}

if (isset ($_REQUEST['id'])) $id = intval($_REQUEST['id']); else $id = "";
if($config['version_id'] > 6.2 ){

DEFINE('CLASSES', '/classes' );
}
else
{
DEFINE('CLASSES', '/inc' );
}
require_once(ENGINE_DIR.CLASSES.'/mysql.php');

//Функции для работы модуля
function opentable() {
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
HTML;
}

function closetable() {
echo <<<HTML
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

function tableheader($value) {
echo <<<HTML
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;">
          <div class="navigation">$value</div></td>
    </tr>
</table>
<div class="unterline"></div>
HTML;
}
// Конец функций работы модуля

function faqmain() {
echoheader("FAQ", "Админпанель модуля FAQ");
}
function faqmenu() {
opentable();
tableheader('Настройка модуля');
echo<<<HTML
<form action="$PHP_SELF?mod=faq" method="post">
<table width="100%">
    <tr>
        <td width="50%"><div class="quick"><a href="$PHP_SELF?mod=faq"><img src="engine/skins/images/general.png" border="0" align="left"><h3>Главная</h3>Перейти на галвную страницу модуля в админпанели</a></div></td>
        <td><div class="quick"><a href="$PHP_SELF?mod=faq&action=add_faq"><img src="engine/skins/images/spset.png" border="0" align="left"><h3>Добавить FAQ</h3>Открыть форму добавления нового вопроса и ответа на него</a></div></td>
    </tr>
    <tr>
        <td><div class="quick"><a href="$PHP_SELF?mod=faq&action=list"><img src="engine/skins/images/comments.png" border="0" align="left"><h3>Управление FAQ</h3>Изменение, удаление существующих FAQ</a></div></td>
        <td><div class="quick"><a href="$PHP_SELF?mod=faq&action=dev"><img src="engine/skins/images/pset.png" border="0" align="left"><h3>Разработчики</h3>Информация о создателях модуля</a></div></td>
    </tr>

</table>
</form>
HTML;
closetable();
}

if ($_REQUEST['action'] == "list") {
	$entries_showed = 0;
	$entries = "";

	//Выводим список существующих FAQ
	$result = $db->query("SELECT * FROM " . PREFIX . "_faq ORDER BY id");
	if($result) {
		while ($row = $db->get_array($result)) {
			  if (strlen(trim($row['question'])) > 55) $question = substr (trim($row['question']), 0, 100)." ..."; else $question = trim($row['question']);
			$entries .= "<tr>
							<td class=\"list\" style=\"padding:4px;\">".$question."</td>";
		  	$entries .= "	<td class=\"list\" style=\"padding:4px;\">".$row['prior']."</td>";
			$entries .= "	<td class=\"list\" style=\"padding:4px;\"><a href=\"$PHP_SELF?mod=faq&action=edit&id=".$row['id']."\">[редактировать]</a>&nbsp;<a href=\"$PHP_SELF?mod=faq&action=delete_faq&id=".$row['id']."\">[удалить]</a></td>";
			$entries .= "	<td class=\"list\" style=\"padding:4px;\"><input name=\"selected_faqs[]\" value=\"{$row['id']}\" type=\"checkbox\"></td>";
			$entries .= "	<td class=\"list\" style=\"padding:4px;\"><input type=\"text\" name=\"textfield\" size=\"5\" value=\"{$row['id']}\" /></td>";
			$entries .= "</tr>
						 <tr>
						 	<td background=\"engine/skins/images/mline.gif\" height\"1\" colspan=\"5\"></td>
						</tr>";
            $entries_showed++;
	     }
	}

echo <<<JSCRIPT
<script language='JavaScript' type="text/javascript">
<!--
function ckeck_uncheck_all() {
    var frm = document.editfaq;
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

	faqmain();
	faqmenu();
	if($entries_showed == 0){
		opentable();
		tableheader($lang['faq_list']);
echo <<<HTML
<table width="100%">
    <tr>
        <td align="center" style="height:50px;">{$lang['edit_nofaq']}</td>
    </tr>
</table>
HTML;
	} else {
echo <<<HTML
<form action="" method="post" name="editfaq">
HTML;
opentable();
tableheader($lang['news_list']);
echo <<<HTML
<table width="100%">
    <tr>
        <td>
			<table width="100%" border="1">
				<tr>
					<td class="list" style="padding:4px;" align="center">{$lang['edit_title_faq']}</td>
					<td class="list" width="10" style="padding:4px;" align="center">Приор</td>
					<td class="list" width="180" style="padding:4px;" align="center">{$lang['edit_cl_faq']}</td>
					<td class="list" width="10" style="padding:4px;" align="center"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()" /></td>
					<td class="list" width="10" style="padding:4px;" align="center">Порядковый номер</td>
				</tr>
				<tr>
					<td colspan="5"><div class="hr_line"></div></td>
				</tr>
					{$entries}
				<tr>
					<td colspan="5"><div class="hr_line"></div></td>
				</tr>
			</table>
HTML;
	}

	if($entries_showed != 0){
echo<<<HTML
			<table width="100%">
				<tr>
				<td>{$npp_nav}</td>
				<td colspan="5" align="right" valign="top"><div style="margin-bottom:5px; margin-top:5px;">
				<select name="action">
				<option value="">{$lang['edit_selact']}</option>
				<option value="mass_delete">{$lang['edit_seldel']}</option>
				</select>
				<input type="hidden" name="mod" value="faq">
				<input class="edit" type="submit" value="{$lang['b_start']}">
				</table>
				</form>
				<td>
				</tr>
HTML;
	}

echo<<<HTML

</td>
    </tr>
</table>
HTML;
	closetable();
	echofooter();
}

elseif ($_REQUEST['action'] == "add_faq") {
	//Вводим данные из форм выше в БД
include_once ENGINE_DIR.CLASSES.'/parse.class.php';
	$parse = new ParseFilter(Array(), Array(), 1, 1);
	$parse->leech_mode = true;

	$question = trim($db->safesql($parse->process($_POST['question'])));
	$answer = trim($db->safesql($parse->process($_POST['answer'])));
	$prior = trim($db->safesql($parse->process($_POST['prior'])));
	$isaddf=false;
	if(isset($save_n) && $question!="" && $answer!="") {
		$row = $db->query("INSERT INTO " . PREFIX . "_faq (id, question, answer, prior) VALUES (NULL, '".$question."', '".$answer."', '".$prior."')");
		msg("info", $lang['mass_head_add_faq'], $lang['mass_addok_faq'], "$PHP_SELF?mod=faq&action=list");
	} else {
		faqmain();
		$isaddf=true;

		$question = stripslashes($_POST['question']);
		$answer = stripslashes($_POST['answer']);
		$prior = stripslashes($_POST['prior']);

		opentable();
		tableheader('Добавление FAQ');
echo<<<HTML
<form action="" method="post">
<table width="100%">
  <tr>
    <td class="option" style="padding:4px;">

<table width="100%">
  <tr>
    <td class="option" style="padding:4px;" valign="top" >
      <b> Вопрос: </b><br />
      <span class="small"> Введите вопрос, будет отображаться в модуле </span>
    <td align="middle" width="400">

HTML;

		echo "<textarea class=\"edit\" style=\"text-align:left\" rows=\"12\" cols=\"120\" value=\"\" name=\"question\">".$question."</textarea>";

echo<<<HTML
      </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding:4px;" valign="top" >
       <b> Ответ </b><br />
       <span class="small"> Ответ на поставленный выше вопрос </span>
    <td align="middle" width="400">
HTML;

		echo "<textarea rows=\"12\" cols=\"120\" class=\"edit\" style=\"text-align:left\" size=\"40\" value=\"\" name=\"answer\">".$answer."</textarea>";

//BEGIN stroka dlya prior
echo<<<HTML
      </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding:4px;" valign="top" >
       <b> Prior </b><br />
       <span class="small"> Приоритет </span>
    <td align="middle" width="400">
HTML;

		echo "<input name=\"prior\" type=\"text\" class=\"edit\" style=\"text-align:left\" value=\"\" size=\"120\">".$prior."</input>";
		#echo "<textarea class=\"edit\" style=\"text-align:left\" rows=\"12\" cols=\"120\" value=\"\" name=\"prior\">".$prior."</textarea>";

//END stroka dlya prior
		if ($isaddf) {
echo<<<HTML
      </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding-bottom:10px; padding-top:10px; padding-left:4px;" colspan="2">
     <font color="darkred">Заполните все поля!</font>
HTML;
		}

echo<<<HTML
       </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding-bottom:10px; padding-top:10px; padding-left:10px; text-align:right;" colspan="2">

HTML;

		echo"<input class=\"buttons\" type=\"submit\" name=\"save_n\" value=\" Сохранить \" />";

echo<<<HTML

      </td>
  </tr>

</table>

</td>
  </tr>

</table>
</form>
HTML;
		closetable();
		echofooter();
	}
}

//Редактируем выбранный FAQ по ID
elseif($_REQUEST['action'] == "edit" AND $id) {
	faqmain();
	faqmenu();
	//Вводим данные из форм ниже в БД
include_once ENGINE_DIR.'/classes/parse.class.php';
	$parse = new ParseFilter(Array(), Array(), 1, 1);
	$parse->leech_mode = true;

	$question = trim($db->safesql($parse->process($_POST['question'])));
	$answer = trim($db->safesql($parse->process($_POST['answer'])));
	$prior = trim($db->safesql($parse->process($_POST['prior'])));

	if(isset($save_n) && $question!="" && $answer!="") {
		$row = $db->query("UPDATE " . PREFIX . "_faq SET question='".$question."', answer='".$answer."', prior='".$prior."' WHERE id='$id'");
		if ($row) $result="Вопрос был изменён!";
		$db->free($row);
	}
	//КОНЕЦ Вводим данные из форм ниже в БД
	//Начало запроса из БД выбранного ID FAQ
	$row = $db->query("SELECT * FROM " . PREFIX . "_faq WHERE id='$id'");
	if($row) {
		$row1 = $db->get_array($row);

		opentable();
		tableheader('Редактирование FAQ');
            echo<<<HTML
           <form action="" method="post">
           <table width="100%">
           <tr>
           <td class="option" style="padding:4px;">



          <table width="100%">
          <tr>
          <td class="option" style="padding:4px;" valign="top" >
          <b> Вопрос: </b><br />
          <span class="small"> Введите вопрос, будет отображаться в модуле </span>
         <td align="middle" width="400">

HTML;

 		echo "<textarea class=\"edit\" style=\"text-align:left\" rows=\"12\" cols=\"120\" value=\"\" name=\"question\">".$row1['question']."</textarea>";

echo<<<HTML
      </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding:4px;" valign="top" >
       <b> Ответ </b><br />
       <span class="small"> Ответ на поставленный выше вопрос </span>
    <td align="middle" width="400">
HTML;

		echo "<textarea rows=\"12\" cols=\"120\" class=\"edit\" style=\"text-align:left\" size=\"40\" value=\"\" name=\"answer\">".$row1['answer']."</textarea>";

//BEGIN stroka dlya prior
echo<<<HTML
      </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding:4px;" valign="top" >
       <b> Prior </b><br />
       <span class="small"> Приоритет </span>
    <td align="middle" width="400">
HTML;

		echo "<input name=\"prior\" type=\"text\" class=\"edit\" style=\"text-align:left\" value=\"".$row1['prior']."\" size=\"120\">";
		#echo "<textarea rows=\"12\" cols=\"120\" class=\"edit\" style=\"text-align:left\" size=\"40\" value=\"\" name=\"prior\">".$row1['prior']."</textarea>";
//END stroka dlya prior

if (!empty($result)) {
echo<<<HTML
      </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding-bottom:10px; padding-top:10px; padding-left:4px;" colspan="2">
HTML;
  echo "<font color=\"green\">".$result."</font>";
}

echo<<<HTML
       </td>
  </tr>

  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>

  <tr>
    <td class="option" style="padding-bottom:10px; padding-top:10px; padding-left:10px;" colspan="2">

HTML;

		echo"<input class=\"buttons\" type=\"submit\" name=\"save_n\" value=\" Сохранить \" />";

echo<<<HTML

      </td>
  </tr>

</table>

</td>
  </tr>

</table>
</form>
HTML;
		// Конец запроса из БД выбранного ID FAQ
	}
	$db->free($row);
	closetable();
	echofooter();
}
//Конец редактирования FAQ

//Удаляем выбранный FAQ по ID
elseif($_REQUEST['action'] == "delete_faq" AND $id) {
	faqmain();

echo <<<HTML
<form action="{$PHP_SELF}" method="post">
HTML;
opentable();
tableheader('myFAQ v1.0 for DLE 6.5');
echo <<<HTML
<table width="100%">
    <tr>
        <td style="padding:2px;" height="100" align="center">{$lang['mass_confirm']}
HTML;

echo "(<b>".count($selected_faqs)."</b>) $lang[mass_confirm_1_faq]<br><br>
<input class=\"bbcodes\" type=\"submit\" value=\"   $lang[mass_yes]   \"> &nbsp; <input type=button class=bbcodes value=\"  $lang[mass_no]  \" onclick=\"javascript:document.location='$PHP_SELF?mod=faq&action=list'\">
<input type=\"hidden\" name=\"action\" value=\"do_delete_faq\">
<input type=\"hidden\" name=\"mod\" value=\"faq\">
<input type=\"hidden\" name=\"id\" value=\"$id\">";

echo <<<HTML
    </tr>
</table>
HTML;
closetable();

echo "</form>";

echofooter();
}

elseif ($_REQUEST['action'] == "do_delete_faq"){
	$db->query("DELETE FROM " . PREFIX . "_faq WHERE id='$id'") or die("Error:<b> ". mysql_error()." </b>in file <b>".__FILE__."</b> on line <b>".__LINE__);
	msg("info", $lang['mass_head_del_faq'], $lang['mass_delok_faq'], "$PHP_SELF?mod=faq&action=list");
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Подтвреждение удаления
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif ($_REQUEST['action'] == "mass_delete"){
	faqmain();

echo <<<HTML
<form action="{$PHP_SELF}" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
HTML;
opentable();
tableheader('myFAQ v1.0 for DLE 6.5');
echo <<<HTML
<table width="100%">
    <tr>
        <td style="padding:2px;" height="100" align="center">{$lang['mass_confirm']}
HTML;

echo "(<b>".count($selected_faqs)."</b>) $lang[mass_confirm_1_faq]<br><br>
<input class=\"bbcodes\" type=\"submit\" value=\"   $lang[mass_yes]   \"> &nbsp; <input type=button class=bbcodes value=\"  $lang[mass_no]  \" onclick=\"javascript:document.location='$PHP_SELF?mod=faq&action=list'\">
<input type=\"hidden\" name=\"action\" value=\"do_mass_delete\">
<input type=\"hidden\" name=\"mod\" value=\"faq\">";
foreach($selected_faqs as $faqid){
echo "<input type=hidden name=selected_faqs[] value=\"$faqid\">\n";
}

echo <<<HTML
    </tr>
</table>
HTML;
closetable();
echo "</form>";

echofooter();
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Удаление FAQ
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif($_REQUEST['action'] == "do_mass_delete"){
	if(!$selected_faqs){ msg("error", $lang['mass_error'], $lang['mass_denied_faq'], "$PHP_SELF?mod=faq&action=list"); }
	$deleted_faqs=0;
	foreach ($selected_faqs as $id) {
		$id = intval($id);
		$db->query("DELETE FROM " . PREFIX . "_faq WHERE id='$id'");
		$deleted_faqs++;
	}
	if(count($selected_faqs) == $deleted_faqs){ msg("info", $lang['mass_head_del_faq'], $lang['mass_delok_faq'], "$PHP_SELF?mod=faq&action=list"); }
	else{ msg("error", $lang['mass_notok_faq'], "$deleted_articles $lang[mass_i] ".count($selected_faqs)." $lang[mass_notok_1_faq]", "$PHP_SELF?mod=faq&action=list"); }
}
elseif ($_REQUEST['action'] == "dev") {
	faqmain();
	faqmenu();
	opentable();
	tableheader('WSM FAQ v1.0');
echo<<<HTML
<form action="" method="post">
<table width="100%">
  <tr>
    <td class="option" style="padding:4px;">
      Модуль FAQ v1.0<br>
      Автор: Keeper<br>
      </td>
  </tr>
</table>
</form>
HTML;
	closetable();
	echofooter();
} else {
	faqmain();
	faqmenu();
	echofooter();
}


?>