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

$editforum = $DB->one_select( "*", "forums", "id='{$id}'" );

if ($id AND $editforum['id'])
{
	if ($cache_forums[$id]['parent_id'] == 0)
	{
		$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|Редактирование категории: ".$cache_forums[$id]['title'];
		$control_center->header("Форум", $link_speddbar);
		$onl_location = "Форум &raquo; Редактирование категории: ".$cache_forums[$id]['title'];

		if (isset($_POST['return']) OR isset($_POST['reload']))
		{
			require LB_CLASS . '/safehtml.php';
			$safehtml = new safehtml( );

			$group_permission = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['group_permission'] )) );

			$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
			if (utf8_strlen($title) < 3)
				$control_center->errors[] = "Длина названия категории меньше 3 символов.";

			$posi = intval( $_POST['posi'] );

			if ($posi < 0) $posi = 1;

            $alt_name = $DB->addslashes( $safehtml->parse( totranslit( $_POST['alt_name'] ) ) );
        	if (!$alt_name)
                $alt_name = $DB->addslashes( $safehtml->parse( totranslit( trim( $_POST['title'] ) ) ) );
                
            $check_alt = $DB->one_select("id", "forums", "alt_name = '{$alt_name}' AND id <> '{$id}'");
        	if ($check_alt['id'])
        		$control_center->errors[] = "Форум с таким альтернативным названием уже есть.";	
                
            $meta_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_desc'] ) ) );
            $meta_key = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_key'] ) ) );

			if (!$control_center->errors)
			{
                $description = $DB->addslashes($editforum['description']);
                $allow_bbcode = intval($editforum['allow_bbcode']);
                $allow_poll = intval($editforum['allow_poll']);
                $postcount = intval($editforum['postcount']);
                $password = $DB->addslashes($editforum['password']);
                $password_notuse = $DB->addslashes($editforum['password_notuse']);
                $sort_order = $DB->addslashes($editforum['sort_order']);
                $rules = $DB->addslashes($editforum['rules']);
                $allow_bbcode_list = $DB->addslashes($editforum['allow_bbcode_list']);
                $ficon = $DB->addslashes($editforum['ficon']);

				$DB->update("ficon = '{$ficon}', parent_id = '0', posi = '{$posi}', title = '{$title}', alt_name = '{$alt_name}', group_permission = '{$group_permission}', meta_desc = '{$meta_desc}', meta_key = '{$meta_key}', description = '{$description}', allow_bbcode = '{$allow_bbcode}', allow_poll = '{$allow_poll}', postcount = '{$postcount}', password = '{$password}', password_notuse = '{$password_notuse}', sort_order = '{$sort_order}', rules = '{$rules}', allow_bbcode_list = '{$allow_bbcode_list}'", "forums", "id = '{$id}'");
				$cache->clear("", "forums");

				$dop_info = "";

				if ($title != $DB->addslashes($cache_forums[$id]['title']))
					$dop_info .= "<br>Изменено название категории: ".$DB->addslashes($cache_forums[$id]['title'])." -> ".$title;

				$info = "<font color=orange>Редактирование</font> категории форума: ".$title.$dop_info;
				$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
                
                if (isset($_POST['return']))
				    header( "Location: ".$redirect_url."?do=board" );
                else
                    header( "Location: {$_SERVER['REQUEST_URI']}" );
                    
                exit();
			}
			else
				$control_center->errors_title = "Ошибка!";
		}

		$control_center->message();

		$qp = explode (',', $editforum['group_permission']);

		if ($qp[0] == 0)
			$group_list = "<option value=\"0\" selected>Все группы</option>";
		else
			$group_list = "<option value=\"0\">Все группы</option>";

		foreach($cache_group as $m_group)
		{
			if ($qp[0] != 0)
			{
				if (in_array($m_group['g_id'], $qp))
					$group_list .= "<option value=\"".$m_group['g_id']."\" selected>".$m_group['g_title']."</option>";
				else
					$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
			}
			else
				$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
		}
		$DB->free();

		$editforum['alt_name'] = htmlspecialchars($editforum['alt_name']);
		$editforum['title'] = htmlspecialchars($editforum['title']);
		unset($safehtml);

echo <<<HTML

<form  method="post" name="form_edit" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование категории: {$cache_forums[$id]['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="title" value="{$editforum['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Альтернативное название:</div>
                                            <div><input type="text" name="alt_name" value="{$editforum['alt_name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Позиция:</div>
                                            <div><input type="text" name="posi" value="{$editforum['posi']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Просмотр:</div>
                                            <div><select name="group_permission[]" multiple>{$group_list}</select></div>
                                        </div>
                    <div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Мета описание:<br><font class="smalltext">Описание форума, которое будет выводиться в метатеге "description".<br>Небольше 200 символов.</font></div>
                                            <div><textarea name="meta_desc" class="inputText" style="width:700px;height:60px;">{$editforum['meta_desc']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Мета ключевые слова:<br><font class="smalltext">Ключевые слова форума, которык будут выводиться в метатеге "keywords".<br>Каждое слово через запятую.<br>Небольше 1000 символов..</font></div>
                                            <div><textarea name="meta_key" class="inputText" style="width:700px;height:70px;">{$editforum['meta_key']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>                   
                                        <div align=center>
                                            <div><input type="submit" name="return" value="сохранить*" class="btnBlack" />   <input type="submit" name="reload" value="обновить**" class="btnBlack" /></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr><td align=left>
<br><font class="smalltext">* - При сохранении вы вернётесь на страницу, где были до перехода на страницу редактирования</font><br>
<font class="smalltext">** - При обновлении вы сохраните настройки и обновите данную страницу</font></td></tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</form>
HTML;

	}
	else
	{
		$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|Редактирование форума: ".$cache_forums[$id]['title'];
		$control_center->header("Форум", $link_speddbar);
		$onl_location = "Форум &raquo; Редактирование форума: ".$cache_forums[$id]['title'];

		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );

		if (isset($_POST['return']) OR isset($_POST['reload']))
		{
			$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
			if (!$title)
				$control_center->errors[] = "Вы не ввели название форума.";

			$posi = intval( $_POST['posi'] );
			if ($posi < 0)
				$posi  = 1;

            $alt_name = $DB->addslashes( $safehtml->parse( totranslit( trim( str_replace("/", "_", $_POST['alt_name']) ) ) ) );
        	if (!$alt_name) $alt_name = $DB->addslashes( $safehtml->parse( totranslit( trim( str_replace("/", "_", $_POST['title']) ) ) ) );
                
            $check_alt = $DB->one_select("id", "forums", "alt_name = '{$alt_name}' AND id <> '{$id}'");
        	if ($check_alt['id']) $control_center->errors[] = "Форум с таким альтернативным названием уже есть.";	
            
            $flink = bb_clear_url($safehtml->parse($_POST['flink']));
    
            if (preg_match("/[\||\'|\"|\!|\$|\@|\~\*\+|<|>]/", $flink)) $control_center->errors[] = "В ссылке использованы запрещённые символы.";
            if (!preg_match("#^(http|news|https|ed2k|ftp|aim|mms)://|(magnet:?)#", $flink) AND $flink) $flink = 'http://'.$flink;
                
            $flink = $DB->addslashes($flink);
            $flink_npage = intval($_POST['flink_npage']);
            
            $bb_allowed = array('b', 'i', 's', 'u', 'text_align', 'color', 'url', 'email', 'img', 'translite', 'smile', 'font', 'size');
            
            $_POST['description'] = htmlspecialchars($_POST['description']);
            $_POST['description'] = parse_word(html_entity_decode($safehtml->parse($_POST['description'])), true, true, true, $bb_allowed);
            $description = $DB->addslashes($_POST['description']);
            
			$parent_id = intval($_POST['parent_id']);
            $postcount = intval($_POST['postcount']);

			$check = $DB->one_select("id", "forums", "id = '{$parent_id}'");
			if (!$check['id'])
				$control_center->errors[] = "Выбранная категория или форум не найдены в базе данных.";	
            elseif ($parent_id == $id)
                $control_center->errors[] = "Вы не можете добавить данный форум в подфорум этого же форума.";	

			$allow_bbcode = intval($_POST['allow_bbcode']);
			$allow_poll = intval($_POST['allow_poll']);
            if ($allow_poll)
            {
                if(intval($_POST['allow_poll_guest']))
                    $allow_poll = 2;
            }

			$password = $DB->addslashes( $safehtml->parse( trim( $_POST['password'] ) ) );
            
            if ($_POST['password_notuse'])
                $password_notuse = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['password_notuse'] )) );
            else
                $password_notuse = "";
            
            $meta_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_desc'] ) ) );
            $meta_key = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_key'] ) ) );
            
            $_POST['rules'] = htmlspecialchars($_POST['rules']);
            $_POST['rules'] = parse_word(html_entity_decode($safehtml->parse($_POST['rules'])), true, true, true, $bb_allowed);
            $rules = $DB->addslashes($_POST['rules']);

			if ($_POST['sort_order'] == "ASC")
				$sort_order = "ASC";
			else
				$sort_order = "DESC";

			$group_permission = array();

			foreach($cache_group as $m_group)
			{
				$read_forum = intval( $_POST['read_forum_'.$m_group[g_id]] );
				$read_theme = intval( $_POST['read_theme_'.$m_group[g_id]] );
				$creat_theme = intval( $_POST['creat_theme_'.$m_group[g_id]] );
				$answer_theme = intval( $_POST['answer_theme_'.$m_group[g_id]] );
				$upload_files = intval( $_POST['upload_files_'.$m_group[g_id]] );
				$download_files = intval( $_POST['download_files_'.$m_group[g_id]] );
				$group_permission[$m_group['g_id']] = array();
				$group_permission[$m_group['g_id']]['read_forum'] = $read_forum;
				$group_permission[$m_group['g_id']]['read_theme'] = $read_theme;
				$group_permission[$m_group['g_id']]['creat_theme'] = $creat_theme;
				$group_permission[$m_group['g_id']]['answer_theme'] = $answer_theme;
				$group_permission[$m_group['g_id']]['upload_files'] = $upload_files;
				$group_permission[$m_group['g_id']]['download_files'] = $download_files;
			}

			$group_permission = $DB->addslashes( serialize($group_permission) );
            
            $ficon = "";
            $ficon_0 = $DB->addslashes($safehtml->parse(str_replace("|", "", $_POST['ficon_0'])));
            $ficon_1 = $DB->addslashes($safehtml->parse(str_replace("|", "", $_POST['ficon_1'])));
            
            if ($ficon_0 OR $ficon_1)
            {
                if ((!$ficon_0 AND $ficon_1) OR ($ficon_1 AND !$ficon_1))
                    $control_center->errors[] = "Вы не указали вторую иконку для форума.";
                else
                {
                    $ficon = $ficon_0."|".$ficon_1;
                }
            }
            
            $allow_bbcode_list = $_POST['allow_bbcode_list'];
            foreach ($allow_bbcode_list as $key => $value)
            {
                $allow_bbcode_list[$key] = intval($value);
            }
            $allow_bbcode_list = $DB->addslashes(implode(",", $allow_bbcode_list));

			if (!$control_center->errors)
			{
				$DB->update("ficon = '{$ficon}', parent_id = '{$parent_id}', posi = '{$posi}', title = '{$title}', alt_name = '{$alt_name}', description = '{$description}', group_permission = '{$group_permission}', allow_bbcode = '{$allow_bbcode}', allow_poll = '{$allow_poll}', postcount = '{$postcount}', password = '{$password}', password_notuse = '{$password_notuse}', sort_order = '{$sort_order}', rules = '{$rules}', meta_desc = '{$meta_desc}', meta_key = '{$meta_key}', allow_bbcode_list = '{$allow_bbcode_list}', flink = '{$flink}', flink_npage = '{$flink_npage}'", "forums", "id = '{$id}'");
				$cache->clear("", "forums");

				$dop_info = "";

				if ($title != $DB->addslashes($cache_forums[$id]['title']))
					$dop_info .= "<br>Изменено название форума: ".$DB->addslashes($cache_forums[$id]['title'])." -> ".$title;

				$info = "<font color=orange>Редактирование</font> форума: ".$title.$dop_info;
				$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
                
                if (isset($_POST['return']))
				    header( "Location: ".$redirect_url."?do=board" );
                else
                    header( "Location: {$_SERVER['REQUEST_URI']}" );
                    
                exit ();
			}
			else
				$control_center->errors_title = "Ошибка!";
		}

		$control_center->message();

		$group_list = "";
		$pn = explode (',', $editforum['password_notuse']);
		foreach($cache_group as $m_group)
		{
			if ($pn[0] != "")
			{
				if (in_array($m_group['g_id'], $pn))
					$group_list .= "<option value=\"".$m_group['g_id']."\" selected>".$m_group['g_title']."</option>";
				else
					$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
			}
			else
				$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
		}

		$forum_list = ForumsList ( $editforum['parent_id'] );

        $bb_allowed_out = array('b', 'i', 's', 'u', 'center', 'color', 'url', 'email', 'img', 'translite', 'smile', 'font', 'size');

        require LB_MAIN . '/components/scripts/bbcode/bbcode_cc.php';
        $editforum['rules'] = parse_back_word($editforum['rules']);
        $editforum['description'] = parse_back_word($editforum['description']);

		$editforum['alt_name'] = htmlspecialchars($editforum['alt_name']);
		$editforum['title'] = htmlspecialchars($editforum['title']);
		$editforum['password'] = htmlspecialchars($editforum['password']);

		if($editforum['allow_bbcode'])
		{
			$allow_bbcode1 = "checked";
			$allow_bbcode2 = "";
		}
		else
		{
			$allow_bbcode2 = "checked";
			$allow_bbcode1 = "";
		}

        $allow_poll_guest1 = "";
        $allow_poll_guest2 = "";

		if($editforum['allow_poll'] == 2)
		{
            $allow_poll_guest1 = "checked";
			$allow_poll1 = "checked";
			$allow_poll2 = "";
		}
        elseif($editforum['allow_poll'] == 1)
		{
            $allow_poll_guest2 = "checked";
			$allow_poll1 = "checked";
			$allow_poll2 = "";
		}
		else
		{
            $allow_poll_guest2 = "checked";
			$allow_poll2 = "checked";
			$allow_poll1 = "";
		}

		if($editforum['sort_order'] == "ASC")
		{
			$sort_order1 = "selected";
			$sort_order2 = "";
		}
		else
		{
			$sort_order2 = "selected";
			$sort_order1 = "";
		}
        
        if($editforum['postcount'])
		{
			$postcount1 = "checked";
			$postcount2 = "";
		}
		else
		{ 
			$postcount2 = "checked";
			$postcount1 = "";
		}
        
        if($editforum['flink_npage'])
		{
			$flink_npage1 = "checked";
			$flink_npage2 = "";
		}
		else
		{ 
			$flink_npage2 = "checked";
			$flink_npage1 = "";
		}
                
        $list_allow_bbcode = array();
        
        if ($editforum['allow_bbcode_list'])
            $editforum_bbcode_list = explode (",", $editforum['allow_bbcode_list']);
        else
            $editforum_bbcode_list = array (0 => "");
        
        include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
        
        foreach ($list_allow_bbcode_arr as $key => $value)
        {
            if ($key == 12) $add_br = "<br />";
            else $add_br = "";
            
            if (!$editforum_bbcode_list[0] OR in_array($key, $editforum_bbcode_list))
                $checked = "checked";
            else
                $checked = "";
            
            $list_allow_bbcode[] = $add_br."<input name=\"allow_bbcode_list[]\" type=\"checkbox\" value=\"$key\" ".$checked."> <a href=\"#\" onclick=\"return false;\" title=\"".$value['title']."\"><font class=\"smalltext\">[".$value['name']."]</font></a>";
        }
        
        $list_allow_bbcode = implode (" ", $list_allow_bbcode);

		$group_p = unserialize($editforum['group_permission']);

$group_permission = <<<HTML
<tr>
<td align=left></td>
<td align=left><input name="read_forum_0" id="read_forum_0" type="checkbox" onclick="group_permission_row('read_forum');" value="1"> <font class="smalltext">Просмотр форума</font></td>
<td align=left><input name="read_theme_0" id="read_theme_0" type="checkbox" onclick="group_permission_row('read_theme');" value="1"> <font class="smalltext">Чтение тем</font></td>
<td align=left><input name="creat_theme_0" id="creat_theme_0" type="checkbox" onclick="group_permission_row('creat_theme');" value="1"> <font class="smalltext">Создание тем</font></td>
<td align=left><input name="answer_theme_01" id="answer_theme_0" type="checkbox" onclick="group_permission_row('answer_theme');" value="1"> <font class="smalltext">Ответ в темах</font></td>
<td align=left><input name="upload_files_0" id="upload_files_0" type="checkbox" onclick="group_permission_row('upload_files');" value="1"> <font class="smalltext">Загрузка файлов</font></td>
<td align=left><input name="download_files_0" id="download_files_0" type="checkbox" onclick="group_permission_row('download_files');" value="1"> <font class="smalltext">Скачивание файлов</font></td>
</tr>
<tr><td colspan=7><hr /></td></tr>
HTML;
		$i = 0;
        $group_script = "";

		foreach($cache_group as $m_group)
		{
            $group_script .= "\r\ndocument.getElementById(group_name+'_".$m_group['g_id']."').checked = value;";
            
			$read_forum = "";
			$read_theme = "";
			$creat_theme = "";
			$answer_theme = "";
			$upload_files = "";
			$download_files = "";

			if ($group_p[$m_group['g_id']]['read_forum'])
				$read_forum = "checked";
			if ($group_p[$m_group['g_id']]['read_theme'])
				$read_theme = "checked";
			if ($group_p[$m_group['g_id']]['creat_theme'])
				$creat_theme = "checked";
			if ($group_p[$m_group['g_id']]['answer_theme'])
				$answer_theme = "checked";
			if ($group_p[$m_group['g_id']]['upload_files'])
				$upload_files = "checked";
			if ($group_p[$m_group['g_id']]['download_files'])
				$download_files = "checked";

			$i ++;
			if ($i%2)
				$class = "appLine";
			else
				$class = "appLine dark";
                			
$group_permission .= <<<HTML
<tr class="{$class}">
<td align=left class="ntitle">[<a href="javascript:group_permission('{$m_group['g_id']}', 'yes')">+</a>|<a href="javascript:group_permission('{$m_group['g_id']}', 'no')">-</a>] {$m_group['g_title']}</td>
<td align=left><input type="checkbox" name="read_forum_{$m_group['g_id']}" id="read_forum_{$m_group['g_id']}" value="1" {$read_forum}> <font class="smalltext">Просмотр форума</font></td>
<td align=left><input type="checkbox" name="read_theme_{$m_group['g_id']}" id="read_theme_{$m_group['g_id']}" value="1" {$read_theme}> <font class="smalltext">Чтение тем</font></td>
<td align=left><input type="checkbox" name="creat_theme_{$m_group['g_id']}" id="creat_theme_{$m_group['g_id']}" value="1" {$creat_theme}> <font class="smalltext">Создание тем</font></td>
<td align=left><input type="checkbox" name="answer_theme_{$m_group['g_id']}" id="answer_theme_{$m_group['g_id']}" value="1" {$answer_theme}> <font class="smalltext">Ответ в темах</font></td>
<td align=left><input type="checkbox" name="upload_files_{$m_group['g_id']}" id="upload_files_{$m_group['g_id']}" value="1" {$upload_files}> <font class="smalltext">Загрузка файлов</font></td>
<td align=left><input type="checkbox" name="download_files_{$m_group['g_id']}" id="download_files_{$m_group['g_id']}" value="1" {$download_files}> <font class="smalltext">Скачивание файлов</font></td>
</tr>
<tr><td height="10" colspan=7></td></tr>
HTML;
		}

        if ($editforum['ficon'])
        {
            $editforum['ficon'] = explode ("|", $editforum['ficon']);
        }
        else
        {
            $editforum['ficon'][0] = "";
            $editforum['ficon'][1] = "";
        }

echo <<<HTML

<script language='JavaScript' type="text/javascript">
function group_permission( group, value )
{
	if (value == "no")
		value = false;
	else
		value = true;

	document.getElementById( 'read_forum_' + group ).checked = value;
	document.getElementById( 'read_theme_' + group ).checked = value;
	document.getElementById( 'creat_theme_' + group ).checked = value;
	document.getElementById( 'answer_theme_' + group ).checked = value;
	document.getElementById( 'upload_files_' + group ).checked = value;
	document.getElementById( 'download_files_' + group ).checked = value;
}

function group_permission_row(group_name)
{
	var inp_n = document.getElementById(group_name+'_0');
	var value = inp_n.checked;
	 
	{$group_script}
}

$(document).ready(function(){
    
    $("#flink").change(function () {
        if ($(this).val() == "")
        {
            if ($(".not_link_forum:hidden"))
            {
                $(".not_link_forum").fadeIn(500);
            }
        }
        else
        {
            if ($(".not_link_forum:visible"))
            {
                $(".not_link_forum").fadeOut(500);
            }
        }
    });
    
HTML;

if ($editforum['flink'])
{
echo <<<HTML

    if ($(".not_link_forum:visible"))
    {
        $(".not_link_forum").fadeOut(500);
    }
HTML;
    
}

echo <<<HTML
});

</script>

<form  method="post" name="form_edit" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование форума: {$cache_forums[$id]['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="title" value="{$editforum['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Альтернативное название:</div>
                                            <div><input type="text" name="alt_name" value="{$editforum['alt_name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Иконка:<br><font class="smalltext">Изображение должно быть в папке templates/ШАБЛОН/forum_icons</font></div>
                                            <div><input type="text" name="ficon_0" value="{$editforum['ficon'][0]}" class="inputText" /> <font class="smalltext">Форум не прочитан</font><br /><br /><input type="text" name="ficon_1" value="{$editforum['ficon'][1]}" class="inputText" /> <font class="smalltext">Форум прочитан</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Позиция:</div>
                                            <div><input type="text" name="posi" value="{$editforum['posi']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">Описание:</div></td>
                                            <td width="720" align=left>{$bbcode_script}{$bbcode}<textarea name="description" class="inputText" id="tf" onclick="SetNewField(this.id);" style="width:700px;height:70px;">{$editforum['description']}</textarea></td>
                                            </tr></table>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Категория или форум:</div>
                                            <div><select name="parent_id">{$forum_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Форум-ссылка:<br><font class="smalltext">Если хотите сделать этот форум ссылкой на любую страницу, то просто введите адрес нужной страницы в поле.</font></div>
                                            <div><input type="text" name="flink" id="flink" value="{$editforum['flink']}" class="inputText" style="width:700px;" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Открыть в новой вкладке:<br><font class="smalltext">Открыть ссылку в новой вкладке. Если нет - ссылка будет открыта на текущей странице.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="flink_npage" type="radio" id="flink_npage_1" value="1" {$flink_npage1}></div> <label class="radioLabel" for="flink_npage_1">Да</label>
                        						<div class="radioContainer optionFalse"><input name="flink_npage" type="radio" id="flink_npage_0" value="0" {$flink_npage2}></div> <label class="radioLabel" for="flink_npage_0">Нет</label>
                                            </div>
                                        </div>
                                        
                                        <div class="not_link_forum">
                                            <div class="clear" style="height:6px;"></div>
                        					<hr/>
                        					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Мета описание:<br><font class="smalltext">Описание форума, которое будет выводиться в метатеге "description".<br>Небольше 200 символов.</font></div>
                                            <div><textarea name="meta_desc" class="inputText" style="width:700px;height:60px;">{$editforum['meta_desc']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Мета ключевые слова:<br><font class="smalltext">Ключевые слова форума, которык будут выводиться в метатеге "keywords".<br>Каждое слово через запятую.<br>Небольше 1000 символов..</font></div>
                                            <div><textarea name="meta_key" class="inputText" style="width:700px;height:70px;">{$editforum['meta_key']}</textarea></div>
                                        </div>
					<div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Разрешить BBcode:</div>
                                            <div>
                        						<div class="radioContainer"><input name="allow_bbcode" type="radio" id="allow_bbcode_1" value="1" {$allow_bbcode1}></div><label class="radioLabel" for="allow_bbcode_1">Да</label>
                        						<div class="radioContainer optionFalse"><input name="allow_bbcode" type="radio" id="allow_bbcode_0" value="0" {$allow_bbcode2}></div><label class="radioLabel" for="allow_bbcode_0">Нет</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">Список разрешённых BBcode:</div></td>
                                            <td width="720" align=left>{$list_allow_bbcode}</td>
                                            </tr></table>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Разрешить голосования:</div>
                                            <div>
                    						<div class="radioContainer"><input name="allow_poll" type="radio" id="allow_poll_1" value="1" {$allow_poll1}></div> <label class="radioLabel" for="allow_poll_1">Да</label>
                    						<div class="radioContainer optionFalse"><input name="allow_poll" type="radio" id="allow_poll_0" value="0" {$allow_poll2}></div> <label class="radioLabel" for="allow_poll_0">Нет</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Разрешить голосовать гостям:</div>
                                            <div>
                    						<div class="radioContainer"><input name="allow_poll_guest" type="radio" id="allow_poll_guest_1" value="1" {$allow_poll_guest1}></div> <label class="radioLabel" for="allow_poll_guest_1">Да</label>
                    						<div class="radioContainer optionFalse"><input name="allow_poll_guest" type="radio" id="allow_poll_guest_0" value="0" {$allow_poll_guest2}></div> <label class="radioLabel" for="allow_poll_guest_0">Нет</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Счётчик постов и тем:<br><font class="smalltext">Счётчик у пользователей.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="postcount" type="radio" id="postcount_1" value="1" {$postcount1}></div> <label class="radioLabel" for="postcount_1">Да</label>
                        						<div class="radioContainer optionFalse"><input name="postcount" type="radio" id="postcount_0" value="0" {$postcount2}></div> <label class="radioLabel" for="postcount_0">Нет</label>
                    					    </div>
                                        </div>
                    					<div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Пароль для форума:</div>
                                            <div><input type="text" name="password" value="{$editforum['password']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Доступ без пароля:</div>
                                            <div><select name="password_notuse[]" multiple>{$group_list}</select></div>
                                        </div>
                    					<div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Сортировать по:</div>
                                            <div><select name="sort_order"><option value="ASC" {$sort_order1}>Возрастанию</option><option value="DESC" {$sort_order2}>Убыванию</option></select></div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">Правила форума:</div></td>
                                            <td width="720" align=left>{$bbcode}<textarea name="rules" class="inputText" id="tf2" onclick="SetNewField(this.id);" style="width:700px;height:100px;">{$editforum['rules']}</textarea></td>
                                            </tr></table>
                                        </div>
                                    </div>
                    <div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
					<table>
<tr><td align=left><h5>Группа</h5></td><td align=left><h5>Просмотр форума</h5></td><td align=left><h5>Чтение тем</h5></td><td align=left><h5>Создание тем</h5></td><td align=left><h5>Ответ в темах</h5></td><td align=left><h5>Загрузка файлов</h5></td><td align=left><h5>Скачивание файлов</h5></td></tr>
{$group_permission}
</table>
<table border=0>
<tr><td align=center style="padding-top:5px;"><input type="submit" name="return" value="сохранить*" class="btnBlack" /> <input type="submit" name="reload" value="обновить**" class="btnBlack" /></td></tr>
<tr><td colspan=2 align=left>
<br><font class="smalltext">* - При сохранении вы вернётесь на страницу, где были до перехода на страницу редактирования</font><br>
<font class="smalltext">** - При обновлении вы сохраните настройки и обновите данную страницу</font></td></tr>
</table>
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
</form>
HTML;

		unset($safehtml);
	}
}
else
{
    $link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|Редактирование категории: <i>Ошибка</i>";
    $control_center->header("Форум", $link_speddbar);
    
	$onl_location = "Редактирование категории или форума: Ошибка";
	$control_center->errors_title = "Ошибка.";
	$control_center->errors[] = "Вы не выбрали категорию или форум для редактирования.";
	$control_center->message();
}

?>