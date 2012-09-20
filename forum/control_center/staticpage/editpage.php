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

$edit = $DB->one_select( "*", "staticpage", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=staticpage\">Статические страницы</a>|Редактирование: ".$edit['name'];
$control_center->header("Статические страницы", $link_speddbar);
$onl_location = "Статические страницы &raquo; Редактирование: ".$edit['name'];

$control_center->errors = array ();

if ($edit['id'])
{
	if (isset($_POST['editpage']))
	{
		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$safehtml->protocolFiltering = "black";

		$control_center->errors = array ();

		$title = $DB->addslashes($safehtml->parse(trim(htmlspecialchars(totranslit(str_replace("/", "_", $_POST['title']))))));
		if (!$title OR utf8_strlen($title) > 100)
			$control_center->errors[] = "Вы не заполнили поле \"Название (для URL)\" или ввели больше 100 символов.";

		$namepage = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['namepage'] ) ) ) );
		if (!$namepage OR utf8_strlen($namepage) > 255)
			$control_center->errors[] = "Вы не заполнили поле \"Название\" или ввели больше 255 символов.";

		$metadescr = $DB->addslashes( trim( htmlspecialchars( $_POST['metadescr'] ) ) );

		$keywords = $DB->addslashes($safehtml->parse(trim( htmlspecialchars( $_POST['metakeys'] ) ) ) );

        if (intval($_POST['html_br']))
        {
            $_POST['description'] = add_br($_POST['description']);
            $html_br = 1;
        }
        else
            $html_br = 0;
            
        $description = $DB->addslashes(trim($_POST['description']));
		if (!$description)
			$control_center->errors[] = "Вы не заполнили поле \"Текст\".";

		unset($safehtml);
        
        if (!$control_center->errors)
		{
            $check = $DB->one_select("id", "staticpage", "title = '{$title}' AND id <> '{$id}'");
            if ($check['id'])
                $control_center->errors[] = "В базе данных уже есть страница с таким же альтернативным названием.";
        }

		if (!$control_center->errors)
		{
			$DB->update("title = '{$title}', name = '{$namepage}', description = '{$description}', metadescr = '{$metadescr}', metakeys = '{$keywords}', html_br = '{$html_br}'", "staticpage", "id='{$id}'");

            $dop_info = "";
			if ($title != $edit['title'])
				$dop_info = "<br>Изменнено название страницы (URL): ".$edit['title']." -> ".$title;
			if ($namepage != $edit['name'])
				$dop_info = "<br>Изменнено название страницы: ".$edit['name']." -> ".$namepage;

			$info = "<font color=orange>Редактирование</font> ститической страницы: ".$namepage." (".$title.")".$dop_info;
            $info = $DB->addslashes($info);
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: {$redirect_url}?do=staticpage" );
            exit();
		}
		else
			$control_center->errors_title = "Ошибка!";
	}

	$control_center->message();
    
    $edit['description'] = parse_back_word($edit['description'], false);
    
    if ($edit['html_br'])
        $html_br = "checked";
    else
        $html_br = "";

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование: {$edit['name']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">Альтернативное название:<br><font class="smalltext">Испоьзуется в адресной строке (URL)</font></div>
                                            <div><input type="text" name="title" value="{$edit['title']}" class="inputText" /> <font class="smalltext">Небольше 100 символов</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="namepage" value="{$edit['name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Текст:<br><font class="smalltext">Возможно использовать html теги и скрипты</font></div>
                                            <div><textarea name="description" id="description" class="textarea" style="width:720px; height:300px;">{$edit['description']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Автоперенос строк:<br><font class="smalltext">Будет добавлен HTML тег переноса строки &lt;br /></font></div>
                                            <div><input type="checkbox" name="html_br" value="1" {$html_br} /></div>
                                        </div>
                                         <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption">Метатег description:</div>
                                            <div><input type="text" name="metadescr" value="{$edit['metadescr']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Метатег keywords:<br><font class="smalltext">Через запятую</font></div>
                                            <div><textarea name="metakeys" class="textarea" style="width:400px;height:50px;">{$edit['metakeys']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editpage" value="сохранить" class="btnBlack" />
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

}
else
{
	$control_center->errors_title = "Не найдено!";
	$control_center->errors[] = "Выбранная статическая страница не найдена.";
	$control_center->message();
}

?>