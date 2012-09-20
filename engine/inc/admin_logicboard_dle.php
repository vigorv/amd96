<?PHP

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: Интеграция с форумом LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if(!defined('DATALIFEENGINE'))
	die("Hacking attempt!");

@include  ENGINE_DIR."/data/logicboard_conf.php";

if($member_id['user_group'] <= 2)
{

    include_once ENGINE_DIR.'/classes/parse.class.php';
    $parse = new ParseFilter();

    $cache_forums = get_vars( "logicboard_forums" );
    
    if( !$cache_forums )
    {        
        $cache_forums = array();
        $LB_forums = $db->query( "SELECT id, parent_id, group_permission, password_notuse, password, title, alt_name, flink FROM " . LB_DB_PREFIX . "_forums ORDER by posi, title ASC" );
        
        while ( $row_lb = $db->get_row($LB_forums) )
        {               
            $cache_forums[$row_lb['id']] = array ();
            foreach ($row_lb as $key => $value)
                $cache_forums[$row_lb['id']][$key] = $value;
        }
        $db->free($LB_forums);
        set_vars( "logicboard_forums", $cache_forums );
    }

if ($_REQUEST['edit'] == "" AND $_REQUEST['clear'] == "")
{
	echoheader("", "");
        
    function ForumsList($categoryid = 0, $parentid = 0, $sublevelmarker = "", $returnstring = "") 
    {
    	global $cache_forums;
    
    	if ($parentid != 0) $sublevelmarker .= '--&nbsp;';
    
    	if (isset ( $cache_forums ))
    	{
    		$root_category = array();
    		foreach ( $cache_forums as $cats )
    		{
    			if( $cats['parent_id'] == $parentid )
    				$root_category[] = $cats['id'];
    		}
            
    		if( count( $root_category ) )
    		{
    			foreach ( $root_category as $id )
    			{
    				$category_name = $cache_forums[$id]['title'];
    
                    if (is_array($categoryid))
                    {
                        if (in_array($id, $categoryid))
                            $returnstring .= "<option value=\"".$id."\" selected>".$sublevelmarker.$category_name."</option>";
                        else
                            $returnstring .= "<option value=\"".$id."\">".$sublevelmarker.$category_name."</option>";                    
                    }
                    else
                    {
                        if ($categoryid == $id)
                            $returnstring .= "<option value=\"".$id."\" selected>".$sublevelmarker.$category_name."</option>";
                        else
                            $returnstring .= "<option value=\"".$id."\">".$sublevelmarker.$category_name."</option>";
                    }
                    
    				$returnstring = ForumsList ( $categoryid, $id, $sublevelmarker, $returnstring );
    			}
    		}
    	}
        
    	return $returnstring;
    }

?>

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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Информация.</div></td>
    </tr>
</table>
<div class="unterline"></div>
<div id="dle_tabView1">
<div class="dle_aTab">
<table width="100%">
<tr><td style="padding:4px;">
<b>Версия форума:</b> 2.2 (DLE Edition)<br />
<b>Автор:</b> Никита Курдин (ShapeShifter)<br />
<b>Сайт тех. поддержки:</b> <a href="http://logicboard.ru/">LogicBoard.ru</a><br />
</td></tr>
</table>
</div>
</div>
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

<form name="ajaxcomments" id="ajaxcomments" method="post">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Настройка интеграции с форумом LogicBoard</div></td>
    </tr>
</table>
<div class="unterline"></div>
<div id="dle_tabView1">
<div class="dle_aTab">

<table width="100%">
<tr>
<td style="padding:4px;">Адрес форума:<br>
<span class="small">Укажите URL адрес форума, например: http://site.ru/forum/ или http://forum.site.ru/</span></td>
<td style="padding:4px;"><input type="text" name="url" value="<?php echo $logicboard_conf['url']; ?>" style="width:250px;" class="edit bk" /></td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Выводить последние темы с форума:</td>
<td style="padding:4px;">
<?php

if($logicboard_conf['last_topic'] == 1)
{
	$chk1 = " checked='checked'";
	$chk0 = "";
}
else
{
	$chk1 = "";
	$chk0 = " checked='checked'";
}

?>
<input type="radio" class="radio" name="last_topic" value="1" <?php echo $chk1; ?> /> да
<input type="radio" class="radio" name="last_topic" value="0" <?php echo $chk0; ?> /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Кол-во последних тем:<br>
<span class="small">Максимально можно вывести до 20 тем.</span></td>
<td style="padding:4px;"><input type="text" name="last_topic_num" value="<?php echo $logicboard_conf['last_topic_num']; ?>" style="width:30px;" class="edit bk" /></td>
</tr>

<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Выводить только последний форум:<br>
<span class="small">В столбце "Форум" выводятся все форумы, в которых находится тема, иногда таких форумов слишком много, что портит внешний вид блока.<br />При включении данной опции будет выводиться только форум, где находится тема, без родительских форумов.</span></td>
<td style="padding:4px;">
<?php

if($logicboard_conf['show_last_forum'] == 1)
{
	$chk1 = " checked='checked'";
	$chk0 = "";
}
else
{
	$chk1 = "";
	$chk0 = " checked='checked'";
}

?>
<input type="radio" class="radio" name="show_last_forum" value="1" <?php echo $chk1; ?> /> да
<input type="radio" class="radio" name="show_last_forum" value="0" <?php echo $chk0; ?> /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить учёт онлайн пользователей:<br>
<span class="small">При включении данной опции пользователь будет выводиться в списке онлайна на форуме, где будет указано, что пользователь в данный момент находится на сайте.</span></td>
<td style="padding:4px;">
<?php

if($logicboard_conf['online_status'] == 1)
{
	$chk1 = " checked='checked'";
	$chk0 = "";
}
else
{
	$chk1 = "";
	$chk0 = " checked='checked'";
}

?>
<input type="radio" class="radio" name="online_status" value="1" <?php echo $chk1; ?> /> да
<input type="radio" class="radio" name="online_status" value="0" <?php echo $chk0; ?> /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Включить вывод блока онлайн пользователей на сайте:<br>
<span class="small">Если отключена опция "Включить учёт онлайн пользователей", то в блоке не будут выводится пользователи, которые сейчас на сайте.</span></td>
<td style="padding:4px;">
<?php

if($logicboard_conf['online_block'] == 1)
{
	$chk1 = " checked='checked'";
	$chk0 = "";
}
else
{
	$chk1 = "";
	$chk0 = " checked='checked'";
}

?>
<input type="radio" class="radio" name="online_block" value="1" <?php echo $chk1; ?> /> да
<input type="radio" class="radio" name="online_block" value="0" <?php echo $chk0; ?> /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
</table>
</div>
</div>

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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Модуль "Обсудить на форуме"</div></td>
    </tr>
</table>
<div class="unterline"></div>
<div id="dle_tabView1">
<div class="dle_aTab">
<table width="100%">
<tr>
<td style="padding:4px;">Включить "обсудить на форуме":<br />
<span class="small">В новостях будет выводиться ссылка "Обсудить на форуме", при нажатии на которую на форуме будет создана тема про данную новость, если такая тема уже есть - пользователь просто будет направлен на страницу с этой темой.</span></td>
<td style="padding:4px;">
<?php

if($logicboard_conf['discuss_status'] == 1)
{
	$chk1 = " checked='checked'";
	$chk0 = "";
}
else
{
	$chk1 = "";
	$chk0 = " checked='checked'";
}

?>
<input type="radio" class="radio" name="discuss_status" value="1" <?php echo $chk1; ?> /> да
<input type="radio" class="radio" name="discuss_status" value="0" <?php echo $chk0; ?> /> нет
</td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Выберите форум:<br>
<span class="small">Выберите форум, где будут создаваться темы.</span></td>
<td style="padding:4px;"><select name="discuss_fid"><?php echo ForumsList ($logicboard_conf['discuss_fid']); ?></select></td>
</tr>

<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Префикс тем:<br>
<span class="small">Префикс будет добавлен перед название новости. Запрещен HTML и bbcode.</span></td>
<td style="padding:4px;"><input type="text" name="discuss_title" value="<?php echo $logicboard_conf['discuss_title']; ?>" style="width:250px;" class="edit bk" /></td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
<tr>
<td style="padding:4px;">Сообщение темы:<br>
<span class="small">
Содержание первого сообщения в теме. Запрещен HTML и bbcode.<br />
Разрешённые теги:<br />
{title} - Название новости<br />
{date}  - Дата добавления новости<br />
</span></td>
<td style="padding:4px;"><textarea name="discuss_post" style="width:250px; height:70px;" class="edit bk"><?php echo $logicboard_conf['discuss_post']; ?></textarea></td>
</tr>
<tr><td colspan="2"><div class="hr_line"></div></td></tr>
</table>
</div>
</div>
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Действия</div></td>
    </tr>
</table>
<div class="unterline"></div>
<div id="dle_tabView1">
<div class="dle_aTab">
<table width="100%">
<tr>
<td style="padding:4px;" align="right">

    <input type="hidden" name="edit" value="yes">
    <input type="submit" class="buttons" value="Сохранить" style="width:150px;">
    <input type="hidden" name="user_hash" value="<?php echo $dle_login_hash; ?>" />
    </form>

</td>
<td style="padding:4px;" align="left">   
    <form name="ajaxcomments" id="ajaxcomments" method="post">
    <input type="hidden" name="edit" value="clear">
    <input type="submit" class="buttons" value="Очистить кеш" style="width:150px;">
    <input type="hidden" name="user_hash" value="<?php echo $dle_login_hash; ?>" />
    </form>

</td>
</tr>
</table>
</div>
</div>
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

<?php
	echofooter();
}

 	if ($_REQUEST['edit'] == "yes")
	{
        if( $_POST['user_hash'] == "" or $_POST['user_hash'] != $dle_login_hash )
        {
    		die( "Hacking attempt! User not found" );
    	}
       
		$stop = "";

		$last_topic = intval($_POST['last_topic']);
        $last_topic_num = intval($_POST['last_topic_num']);
        $url = strip_tags($_POST['url']);
        $show_last_forum = intval($_POST['show_last_forum']);
        $online_status = intval($_POST['online_status']);
        $online_block = intval($_POST['online_block']);
        
        if(!(eregi("http:\/\/", $url) || eregi("www", $url))) $stop .= "Неверно указана ссылка на ваш форум.<br>";
        if (preg_match("#[\||\'|\"|\!|\$|\@|\~\*\+|<|>|=]#", $url)) $stop .= "В ссылке использованы запрещённые символы.<br>";
        
        if ($last_topic_num < 1) $last_topic_num = 1;
        if ($last_topic_num > 20) $last_topic_num = 20;
        
        $discuss_status = intval($_POST['discuss_status']);
        $discuss_fid = intval($_POST['discuss_fid']);
        $discuss_title = $db->safesql(trim(htmlspecialchars(strip_tags($_POST['discuss_title']))));
        $discuss_post = $db->safesql(trim(htmlspecialchars(strip_tags($_POST['discuss_post']))));
        
        if ($discuss_status)
        {
            if (!$discuss_post) $stop .= "Вы не указали сообщение темы для модуля \"Обсудить на форуме\".<br>";
            if (!$discuss_fid) $stop .= "Вы не указали форум для модуля \"Обсудить на форуме\".<br>";
            elseif ($cache_forums[$discuss_fid]['parent_id'] == 0) $stop .= "Вы указали категорию вместо форума для модуля \"Обсудить на форуме\".<br>";
            elseif ($cache_forums[$discuss_fid]['flink']) $stop .= "Выбранный форум для модуля \"Обсудить на форуме\" является форумом-ссылкой.<br>";
        }
        
		if (!$stop)
		{
    		$content  = "<?PHP\r\n";

            $content .= "\$logicboard_conf['url'] = \"".$url."\";\r\n";
    		$content .= "\$logicboard_conf['last_topic'] = \"".$last_topic."\";\r\n";
    		$content .= "\$logicboard_conf['last_topic_num'] = \"".$last_topic_num."\";\r\n";
            $content .= "\$logicboard_conf['show_last_forum'] = \"".$show_last_forum."\";\r\n";
            $content .= "\$logicboard_conf['online_status'] = \"".$online_status."\";\r\n";
            $content .= "\$logicboard_conf['online_block'] = \"".$online_block."\";\r\n";
            $content .= "\$logicboard_conf['discuss_status'] = \"".$discuss_status."\";\r\n";
            $content .= "\$logicboard_conf['discuss_fid'] = \"".$discuss_fid."\";\r\n";
            $content .= "\$logicboard_conf['discuss_title'] = \"".$discuss_title."\";\r\n";
            $content .= "\$logicboard_conf['discuss_post'] = \"".$discuss_post."\";\r\n";
    
    		$content .= "?>";

    		$filename = ENGINE_DIR."/data/logicboard_conf.php";
    		if ( $file = fopen($filename, "w") )
    		{
    			fwrite($file, $content);
    			fclose($file);
    		}
    		else
    		{
    			echoheader ( "", "" );
    			echo "Не удалось записать файл. Выставьте права доступа на файл logicboard_conf.php 0666";
    			echofooter ();
    			exit();
    		}
    		header("Location: ".$config['http_home_url'].$config['admin_path']."?mod=admin_logicboard_dle");
            exit();
		}
		else
		{
			echoheader ( "", "" );
			echo "Во время сохранения настроек возникли следующие ошибки:<br>".$stop;
			echo "<br><br><a href=\"".$config['http_home_url'].$config['admin_path']."?mod=admin_logicboard_dle\">Вернуться назад.</a>";
			echofooter ();
		}
	}
    elseif ($_REQUEST['edit'] == "clear")
    {
        @unlink(ENGINE_DIR.'/cache/system/logicboard_group.php');
        @unlink(ENGINE_DIR.'/cache/system/logicboard_forums.php');
        @unlink(ENGINE_DIR.'/cache/system/logicboard_config.php');
        @unlink(ENGINE_DIR.'/cache/system/logicboard_user_agents.php');
        header("Location: ".$config['http_home_url'].$config['admin_path']."?mod=admin_logicboard_dle");
        exit();
    }

}
else
{
	msg("error", $lang['addnews_denied'], $lang['db_denied']);
}
?>