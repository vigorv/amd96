<?
if(!defined('DATALIFEENGINE'))
die("Hacking attempt!");
include_once ENGINE_DIR.'/classes/parse.class.php';
$parse = new ParseFilter();
include  "./engine/data/logs_jurnal_config.php";
if ($_REQUEST['edit'] == "")
{
echoheader("","");
function log_group($log_group = false)
{
global $db;
if ($log_group)
{
$log_list = explode (',',$log_group);
$group_list = "<option value=\"\">Не следить</option>";
}
else
$group_list = "<option value=\"\" selected>Не следить</option>";
$db->query("SELECT * FROM ".USERPREFIX ."_usergroups GROUP BY id");
while($row = $db->get_row())
{
if ($log_group)
{
if (in_array($row['id'],$log_list))
$selected = "selected";
else 
$selected = "";
}
$group_list .= "<option value=\"{$row['id']}\" {$selected}>{$row['group_name']}</option>";
}
return ($group_list);
}
;echo '
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Меню</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
';
echo  "<td width=\"260\" style=\"padding:4px;\"><a href=\"".$config['http_home_url'].$config['admin_path']."?mod=admin_logs_jurnal\"><b>Главное меню</b></a></td>";
;echo '     </tr>
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

<form action="".$config[\'http_home_url\']."".$config[\'admin_path\']."?mod=admin_logs_jurnal&action=config&edit=yes" name="ajaxcomments" id="ajaxcomments" method="post">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Настройка журнала логов</div></td>
    </tr>
</table>
<div class="unterline"></div>
<div id="dle_tabView1">
<div class="dle_aTab">

<table width="100%">
<tr>
<td style="padding:4px;">Включить лог "Подозрительных ЛС":<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_lc'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_lc'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_lc" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_lc" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>

<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Слова, по которым будут отслеживаться подозрительные ЛС:<br>
<span class=small>Введите слова через запятую.</span>
</td>
<td style="padding:4px;">
<input type="text" name="logs_lc_word" value="';echo $lj_conf['logs_lc_word'];;echo '" style="width:400px;" class="f_input" />
</td>
</tr>

<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог ошибочных авторизаций (сайт):<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_autorization'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_autorization'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_autorization" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_autorization" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<td style="padding:4px;">Группы, за которыми вести логи ошибочных авторизаций с сайта:<br>
<span class=small>Выберите группу (можно несколько) для создания логов авторизаций</span>
</td>
<td style="padding:4px;">
<select name="logs_group[]" multiple>';echo log_group($lj_conf['logs_group']);;echo '</select>
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог ошибочных авторизаций (админцентр):<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_aal'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_aal'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_aal" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_aal" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог статей и новостей:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_news'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_news'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_news" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_news" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог комментариев из статей:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_news_com'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_news_com'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_news_com" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_news_com" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог редактирвоания пользователей в админке:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_aul'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_aul'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_aul" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_aul" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог оптимизации данных:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_aol'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_aol'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_aol" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_aol" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог массовых рассылок:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_adl'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_adl'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_adl" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_adl" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог категорий:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_category'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_category'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_category" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_category" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог доп. полей для новостей:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_apx'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_apx'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_apx" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_apx" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог доп. полей для профиля:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_app'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_app'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_app" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_app" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог шаблонов сайта:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_templates'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_templates'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_templates" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_templates" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог голосований:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_vote'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_vote'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_vote" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_vote" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог рекламных материалов:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_banners'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_banners'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_banners" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_banners" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить лог восстановления пароля:<br>
<span class=small>Если нет - логи не будут записываться в базу данных, тем самым снижая нагрузку на БД</span>
</td>
<td style="padding:4px;">
';
if($lj_conf['logs_lostpass'] == 1){
$chk1 = " checked='checked'";
$chk0 = '';
}
if($lj_conf['logs_lostpass'] == 0){
$chk0 = " checked='checked'";
$chk1 = '';
}
;echo '<input type="radio" class="radio" name="logs_lostpass" value="1"';echo $chk1;;echo ' /> да
<input type="radio" class="radio" name="logs_lostpass" value="0"';echo $chk0;;echo ' /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Количество сайтов на одной странице:<br>
<span class=small>Выберите кол-во выводимых событий на одной странице при просмотре логов в админке</span>
</td>
<td style="padding:4px;">
<select class="select" name="number">
<option value="';echo $lj_conf['number'];;echo '" selected>';echo $lj_conf['number'];;echo '</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="40">40</option>
<option value="50">50</option>
<option value="60">60</option>
</select>
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
</table>
</div>
</div>
<table width="100%">
<tr>
<td style="padding:4px;">
<input type="hidden" name="action" value="config">
<input type="hidden" name="edit" value="yes">
<center>
<input type="submit" class="buttons" value="Сохранить" style="width:150px;">
<input type="hidden" name="user_hash" value="';echo $dle_login_hash;;echo '" />
</center>

</td>
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
</div></form>

';
echofooter();
}
if ($_REQUEST['edit'] == "yes")
{
include_once ENGINE_DIR.'/classes/parse.class.php';
$parse = new ParseFilter();
$logs_lc_word = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['logs_lc_word'] ),false ) );
$logs_lc_word = htmlspecialchars ( strip_tags( stripslashes( $logs_lc_word ) ) );
$logs_group = $db->safesql( strip_tags( implode( ',',$_POST['logs_group'] )) );
$content  = "<?PHP\r\n";
$content .= "\$lj_conf['logs_lc']            = \"".intval($_POST['logs_lc'])."\";\r\n";
$content .= "\$lj_conf['logs_lc_word']       = \"".$logs_lc_word."\";\r\n";
$content .= "\$lj_conf['logs_autorization']  = \"".intval($_POST['logs_autorization'])."\";\r\n";
$content .= "\$lj_conf['logs_news']          = \"".intval($_POST['logs_news'])."\";\r\n";
$content .= "\$lj_conf['logs_news_com']      = \"".intval($_POST['logs_news_com'])."\";\r\n";
$content .= "\$lj_conf['logs_aul']           = \"".intval($_POST['logs_aul'])."\";\r\n";
$content .= "\$lj_conf['logs_aol']           = \"".intval($_POST['logs_aol'])."\";\r\n";
$content .= "\$lj_conf['logs_adl']           = \"".intval($_POST['logs_adl'])."\";\r\n";
$content .= "\$lj_conf['logs_aal']           = \"".intval($_POST['logs_aal'])."\";\r\n";
$content .= "\$lj_conf['logs_category']      = \"".intval($_POST['logs_category'])."\";\r\n";
$content .= "\$lj_conf['logs_apx']  	     = \"".intval($_POST['logs_apx'])."\";\r\n";
$content .= "\$lj_conf['logs_templates']     = \"".intval($_POST['logs_templates'])."\";\r\n";
$content .= "\$lj_conf['logs_vote']  	     = \"".intval($_POST['logs_vote'])."\";\r\n";
$content .= "\$lj_conf['logs_banners']       = \"".intval($_POST['logs_banners'])."\";\r\n";
$content .= "\$lj_conf['logs_app']           = \"".intval($_POST['logs_app'])."\";\r\n";
$content .= "\$lj_conf['logs_lostpass']      = \"".intval($_POST['logs_lostpass'])."\";\r\n";
$content .= "\$lj_conf['number']             = \"".intval($_POST['number'])."\";\r\n";
$content .= "\$lj_conf['logs_group']         = \"".$logs_group."\";\r\n";
$content .= "?>";
$filename = "./engine/data/logs_jurnal_config.php";
if ( $file = fopen($filename,"w") )
{
fwrite($file,$content);
fclose($file);
}
else
{
echo "Не удалось записать файл. Выставьте права достпупа на файл logs_jurnal_config.php 0666";
exit();
}
header("Location: ".$config['http_home_url']."".$config['admin_path']."?mod=admin_logs_jurnal&action=config");
}
?>