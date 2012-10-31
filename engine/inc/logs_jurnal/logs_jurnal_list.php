<?
if(!defined('DATALIFEENGINE'))
die("Hacking attempt!");
if($member_id['user_group'] <= 2)
{
include (ENGINE_DIR .'/data/logs_jurnal_config.php');
echoheader("","");
$options_conf = array();
$options_conf['menu'] = array(
array(
'name'=>"Настройка модуля",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=config",
'descr'=>"В данном разделе, вы сможете настроить модуль Журнал логов",
'image'=>"options.png",
'access'=>"1",
),
array(
'name'=>"Информация",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=info",
'descr'=>"Информация о модуле и его разработчике",
'image'=>"info.png",
'access'=>"1",
),
);
foreach($options_conf as $sub_options =>$value)
{
$count_options = count($value);
for($i=0;$i <$count_options;$i++)
{
if($member_id['user_group'] != $value[$i]['access'])
unset($options_conf[$sub_options][$i]);
}
}
$subs = 0;
foreach($options_conf as $sub_options)
{
if (!count($sub_options)) continue;
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
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Настройка и информация</div></td>
    </tr>
</table>
<div class=\"unterline\"></div><table width=\"100%\"><tr>";
$i=0;
foreach($sub_options as $option)
{
if ($i >1) {echo "</tr><tr>";$i=0;}
$i++;
echo "<td width=\"50%\">
<table width=\"100%\">
    <tr>
        <td width=\"70\" height=\"70\" valign=\"middle\" align=\"center\" style=\"padding-top:5px;padding-bottom:5px;\"><img src=\"engine/skins/images/logs/{$option['image']}\" border=\"0\"></td>
        <td valign=\"middle\"><div class=\"quick\"><a href=\"{$option['url']}\"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>
    </tr>
</table>
</td>";
}
echo "</tr></table>
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
$options_user = array();
$options_user['menu'] = array(
array(
'name'=>"Новости",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_news",
'descr'=>"Логи изменния новостей сайта.",
'image'=>"news.png",
'access'=>"1",
),
array(
'name'=>"Комментарии",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_news_com",
'descr'=>"Логи изменения комментариев на сайте",
'image'=>"comm.png",
'access'=>"1",
),
array(
'name'=>"Подозрительные ЛС",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_lc",
'descr'=>"Вывод подозрительный личных сообщений",
'image'=>"lc.png",
'access'=>"1",
),
array(
'name'=>"Авторизации с ошибкой",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_autorization",
'descr'=>"Вывод данных о ошибочных авторизациях (не совпадение паролей с учетной записью)",
'image'=>"autor_er.png",
'access'=>"1",
),
array(
'name'=>"Восстановление пароля",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_lostpass",
'descr'=>"Вывод данных о всех запросах на восстановление пароля",
'image'=>"lostpass.png",
'access'=>"1",
),
);
foreach($options_user as $sub_options =>$value)
{
$count_options = count($value);
for($i=0;$i <$count_options;$i++)
{
if($member_id['user_group'] != $value[$i]['access'])
unset($options_user[$sub_options][$i]);
}
}
$subs = 0;
foreach($options_user as $sub_options)
{
if (!count($sub_options)) continue;
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
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Логи пользовательской части сайта</div></td>
    </tr>
</table>
<div class=\"unterline\"></div><table width=\"100%\"><tr>";
$i=0;
foreach($sub_options as $option)
{
if ($i >1) {echo "</tr><tr>";$i=0;}
$i++;
echo "<td width=\"50%\">
<table width=\"100%\">
    <tr>
        <td width=\"70\" height=\"70\" valign=\"middle\" align=\"center\" style=\"padding-top:5px;padding-bottom:5px;\"><img src=\"engine/skins/images/logs/{$option['image']}\" border=\"0\"></td>
        <td valign=\"middle\"><div class=\"quick\"><a href=\"{$option['url']}\"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>
    </tr>
</table>
</td>";
}
echo "</tr></table>
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
$options_admin = array();
$options_admin['menu'] = array(
array(
'name'=>"Редактирование пользователей",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_aul",
'descr'=>"Редактирование, удаление и добавление пользователей",
'image'=>"edituser.png",
'access'=>"1",
),
array(
'name'=>"Рекламные материалы",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_banners",
'descr'=>"Информация об изменениях в рекламных материалах сайта",
'image'=>"reklama.png",
'access'=>"1",
),
array(
'name'=>"Голосования",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_vote",
'descr'=>"Подробные данные об изменениях в данном разделе админцентре",
'image'=>"vote.png",
'access'=>"1",
),
array(
'name'=>"Категории новостей",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_category",
'descr'=>"Вывод логов о добавлении, изменении или удалении категорий новостей",
'image'=>"category.png",
'access'=>"1",
),
array(
'name'=>"Массовая рассылка",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_adl",
'descr'=>"Подробный вывод данных о всех рассылках, сделанных через админцентр",
'image'=>"rassilka.png",
'access'=>"1",
),
array(
'name'=>"Оптимизация данных",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_aol",
'descr'=>"Данные о процессе выполения данной опции в админцентре",
'image'=>"optimiz.png",
'access'=>"1",
),
array(
'name'=>"Авторизации с ошибкой",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_aal",
'descr'=>"Вывод данных о ошибочных авторизациях (не совпадение паролей с учетной записью)",
'image'=>"autor_er.png",
'access'=>"1",
),
array(
'name'=>"Шаблоны сайта",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_templates",
'descr'=>"Вывод логов редактирования шаблонов сайта",
'image'=>"skins.png",
'access'=>"1",
),
array(
'name'=>"Дополнительные поля в новостях",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_apx",
'descr'=>"Данные об изменениях в дополнительных полях для новостей сайта",
'image'=>"poly_news.png",
'access'=>"1",
),
array(
'name'=>"Дополнительные поля в профиле",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_app",
'descr'=>"Данные об изменениях в дополнительных полях для профиля",
'image'=>"poly_news.png",
'access'=>"1",
),
);
foreach($options_admin as $sub_options =>$value)
{
$count_options = count($value);
for($i=0;$i <$count_options;$i++)
{
if($member_id['user_group'] != $value[$i]['access'])
unset($options_admin[$sub_options][$i]);
}
}
$subs = 0;
foreach($options_admin as $sub_options)
{
if (!count($sub_options)) continue;
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
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Логи админской части сайта</div></td>
    </tr>
</table>
<div class=\"unterline\"></div><table width=\"100%\"><tr>";
$i=0;
foreach($sub_options as $option)
{
if ($i >1) {echo "</tr><tr>";$i=0;}
$i++;
echo "<td width=\"50%\">
<table width=\"100%\">
    <tr>
        <td width=\"70\" height=\"70\" valign=\"middle\" align=\"center\" style=\"padding-top:5px;padding-bottom:5px;\"><img src=\"engine/skins/images/logs/{$option['image']}\" border=\"0\"></td>
        <td valign=\"middle\"><div class=\"quick\"><a href=\"{$option['url']}\"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>
    </tr>
</table>
</td>";
}
echo "</tr></table>
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
$options_dop = array();
$options_dop['menu'] = array(
array(
'name'=>"DLE Forum - Темы",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_forum",
'descr'=>"Вывод данных о темах на форуме DLE Forum",
'image'=>"dle_forum.png",
'access'=>"1",
),
array(
'name'=>"DLE Forum - Посты",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_posts",
'descr'=>"Вывод данных о постах на форуме DLE Forum",
'image'=>"dle_forum.png",
'access'=>"1",
),
array(
'name'=>"Модуль Files - Файлы",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_files",
'descr'=>"Вывод данных о файлах в файловом архиве",
'image'=>"files.png",
'access'=>"1",
),
array(
'name'=>"Модуль Files - Комментарии",
'url'=>"$PHP_SELF?mod=admin_logs_jurnal&action=logs_files_com",
'descr'=>"Вывод данных о комментариях в файловом архиве",
'image'=>"files.png",
'access'=>"1",
),
);
foreach($options_dop as $sub_options =>$value)
{
$count_options = count($value);
for($i=0;$i <$count_options;$i++)
{
if($member_id['user_group'] != $value[$i]['access'])
unset($options_dop[$sub_options][$i]);
}
}
$subs = 0;
/*
foreach($options_dop as $sub_options)
{
if (!count($sub_options)) continue;
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
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Логи сторонних модулей (дополнительная опция)</div></td>
    </tr>
</table>
<div class=\"unterline\"></div><table width=\"100%\"><tr>";
$i=0;
foreach($sub_options as $option)
{
if ($i >1) {echo "</tr><tr>";$i=0;}
$i++;
echo "<td width=\"50%\">
<table width=\"100%\">
    <tr>
        <td width=\"70\" height=\"70\" valign=\"middle\" align=\"center\" style=\"padding-top:5px;padding-bottom:5px;\"><img src=\"engine/skins/images/logs/{$option['image']}\" border=\"0\"></td>
        <td valign=\"middle\"><div class=\"quick\"><a href=\"{$option['url']}\"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>
    </tr>
</table>
</td>";
}
echo "</tr></table>
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
*/
$db->query( "SHOW TABLE STATUS FROM `".DBNAME ."`");
$mysql_logs = 0;
while ( $r = $db->get_row() )
{
if( strpos( $r['Name'],PREFIX ."_admin_authoriz_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_users_authoriz_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_users_xfields_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_admin_delivery_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_admin_users_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_admin_optim_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_banners_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_category_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_comments_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_post_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_templates_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_vote_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_pm_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_post_xfields_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
elseif( strpos( $r['Name'],PREFIX ."_lostdb_logs") !== false )
$mysql_logs += $r['Data_length'] +$r['Index_length'];
}
$db->free();
$mysql_logs = formatsize( $mysql_logs );
$stats = array();
$stats['mysql_logs'] = $mysql_logs;
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_admin_delivery_logs");
$stats['admin_delivery_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_pm_logs");
$stats['pm_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_admin_authoriz_logs");
$stats['admin_authoriz_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_users_authoriz_logs");
$stats['users_authoriz_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_admin_users_logs");
$stats['admin_users_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_admin_optim_logs");
$stats['admin_optim_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_category_logs");
$stats['category_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_banners_logs");
$stats['banners_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_post_logs");
$stats['post_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_comments_logs");
$stats['comments_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_templates_logs");
$stats['templates_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_vote_logs");
$stats['vote_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_post_xfields_logs");
$stats['post_xfields_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_users_xfields_logs");
$stats['users_xfields_logs'] = $row['count'];
$row = $db->super_query( "SELECT COUNT(*) as count FROM ".PREFIX ."_lostdb_logs");
$stats['lostdb_logs'] = $row['count'];
$status_logs = array();
if ($lj_conf['logs_aal'] == 1)
$status_logs['aal'] = "<font color=green>Активен</font>";
else
$status_logs['aal'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_adl'] == 1)
$status_logs['adl'] = "<font color=green>Активен</font>";
else
$status_logs['adl'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_aol'] == 1)
$status_logs['aol'] = "<font color=green>Активен</font>";
else
$status_logs['aol'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_aul'] == 1)
$status_logs['aul'] = "<font color=green>Активен</font>";
else
$status_logs['aul'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_news'] == 1)
$status_logs['news'] = "<font color=green>Активен</font>";
else
$status_logs['news'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_news_com'] == 1)
$status_logs['news_com'] = "<font color=green>Активен</font>";
else
$status_logs['news_com'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_lc'] == 1)
$status_logs['lc'] = "<font color=green>Активен</font>";
else
$status_logs['lc'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_category'] == 1)
$status_logs['category'] = "<font color=green>Активен</font>";
else
$status_logs['category'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_apx'] == 1)
$status_logs['logs_apx'] = "<font color=green>Активен</font>";
else
$status_logs['logs_apx'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_app'] == 1)
$status_logs['logs_app'] = "<font color=green>Активен</font>";
else
$status_logs['logs_app'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_templates'] == 1)
$status_logs['templates'] = "<font color=green>Активен</font>";
else
$status_logs['templates'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_vote'] == 1)
$status_logs['vote'] = "<font color=green>Активен</font>";
else
$status_logs['vote'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_banners'] == 1)
$status_logs['banners'] = "<font color=green>Активен</font>";
else
$status_logs['banners'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_autorization'] == 1)
$status_logs['autorization'] = "<font color=green>Активен</font>";
else
$status_logs['autorization'] = "<font color=red>Отключен</font>";
if ($lj_conf['logs_lostpass'] == 1)
$status_logs['logs_lostpass'] = "<font color=green>Активен</font>";
else
$status_logs['logs_lostpass'] = "<font color=red>Отключен</font>";
function show_stats() {
global $stats,$status_logs;
echo "<table width=\"100%\">
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Общий размер базы логов:</td><td align=left>{$stats['mysql_logs']}</td><td align=center>&nbsp;</td></tr>
<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=3></td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Новости\":</td><td align=left>{$stats['post_logs']}</td><td align=center>{$status_logs['news']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Комментарии\":</td><td align=left>{$stats['comments_logs']}</td><td align=center>{$status_logs['news_com']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Подозрительные ЛС\":</td><td align=left>{$stats['pm_logs']}</td><td align=center>{$status_logs['lc']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Авторизации с ошибкой (сайт)\":</td><td align=left>{$stats['users_authoriz_logs']}</td><td align=center>{$status_logs['autorization']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Редактирвоание пользователей\":</td><td align=left>{$stats['admin_users_logs']}</td><td align=center>{$status_logs['aul']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Рекламные материалы\":</td><td align=left>{$stats['banners_logs']}</td><td align=center>{$status_logs['banners']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Голосования\":</td><td align=left>{$stats['vote_logs']}</td><td align=center>{$status_logs['vote']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Категории новостей\":</td><td align=left>{$stats['category_logs']}</td><td align=center>{$status_logs['category']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Массовая рассылка\":</td><td align=left>{$stats['admin_delivery_logs']}</td><td align=center>{$status_logs['adl']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Оптимизация данных\":</td><td align=left>{$stats['admin_optim_logs']}</td><td align=center>{$status_logs['aol']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Авторизации с ошибкой (админцентр)\":</td><td align=left>{$stats['admin_authoriz_logs']}</td><td align=center>{$status_logs['aal']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Шаблоны сайта\":</td><td align=left>{$stats['templates_logs']}</td><td align=center>{$status_logs['templates']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Дополнительные поля в новостях\":</td><td align=left>{$stats['post_xfields_logs']}</td><td align=center>{$status_logs['logs_apx']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Дополнительные поля в профиле\":</td><td align=left>{$stats['users_xfields_logs']}</td><td align=center>{$status_logs['logs_app']}</td></tr>
<tr><td align=left width=400 style=\"padding:2px;\">Кол-во записей в \"Восстановление пароля\":</td><td align=left>{$stats['lostdb_logs']}</td><td align=center>{$status_logs['logs_lostpass']}</td></tr>
</table>";
}
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
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">Статистика модуля Журнала логов</div></td>
    </tr>
</table>
<div class=\"unterline\"></div>";
show_stats();
echo "
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
echofooter();
}
else
{
msg("error",$lang['addnews_denied'],$lang['db_denied']);
}
?>