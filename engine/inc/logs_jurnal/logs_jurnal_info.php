<?
if(!defined('DATALIFEENGINE'))
die("Hacking attempt!");
echoheader("","");
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Информация о модуле "Журнал логов"</div></td>
    </tr>
</table>
<div class="unterline"></div>

<table width="100%">
<tr><td>
Модуль <b>журнал логов</b> был разработан для отслеживания действий пользователей сайта, особенно команды сайта.<br><br>
Благодаря данному модулю вы сможете увидеть, что и главное кто делает на вашем сайте, когда было совершено какое-либо действие.
Часто на сайтах сложно понять, кто удалил чей-то пост или новость, кто изменял настройки движка, но теперь вы сможете об этом легко и быстро узнать.
В модуле существует система сортировки данных по дате, автору и объекту над которым было совершено какое-либо действие.<br><br>
<b>Версия модуля:</b> 5.0 (Платная)<br>
<b>Автор модуля:</b> ShapeShifter<br>
<b>Контакты:</b> ICQ 10-280-282 или E-Mail shapeshifter2008@yandex.ru<br>
<b>Сайт разработчика:</b> <a href="http://savgroup.ru/">SaVGroup.ru</a><br>
</td></tr>
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
?>