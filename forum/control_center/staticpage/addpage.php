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

$link_speddbar = "<a href=\"".$redirect_url."?do=staticpage\">Статические страницы</a>|Новая страница";
$control_center->header("Статические страницы", $link_speddbar);
$onl_location = "Статические страницы &raquo; Новая страница";

if (isset($_POST['newpage']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( totranslit (str_replace("/", "_", $_POST['title'])) ) ) ) );
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
        $check = $DB->one_select("id", "staticpage", "title = '{$title}'");
        if ($check['id'])
            $control_center->errors[] = "В базе данных уже есть страница с таким же альтернативным названием.";
    }

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', name = '{$namepage}', description = '{$description}', date = '{$time}', metadescr = '{$metadescr}', metakeys = '{$keywords}', html_br = '{$html_br}'", "staticpage");
		$info = "<font color=orange>Добавление</font> статической страницы: ".$namepage;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$redirect_url}?do=staticpage" );
        exit();
	}
	else
		$control_center->errors_title = "Ошибка!";
        
    $title = stripslashes($title);
    $namepage = stripslashes($namepage);
    $description = stripslashes($description);
    $metadescr = stripslashes($metadescr);
    $keywords = stripslashes($keywords);
}
else
{
    $title = "";
    $namepage = "";
    $description = "";
    $metadescr = "";
    $keywords = "";
    $html_br = "checked";
}

$control_center->message();

echo <<<HTML
<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление страницы</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">Альтернативное название:<br><font class="smalltext">Испоьзуется в адресной строке (URL)</font></div>
                                            <div><input type="text" name="title" value="{$title}" class="inputText" /> <font class="smalltext">Небольше 100 символов</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="namepage" value="{$namepage}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Текст:<br><font class="smalltext">Возможно использовать html теги и скрипты</font></div>
                                            <div><textarea name="description" id="description" class="textarea" style="width:720px; height:300px;">{$description}</textarea></div>
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
                                            <div><input type="text" name="metadescr" value="{$metadescr}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Метатег keywords:<br><font class="smalltext">Через запятую</font></div>
                                            <div><textarea name="metakeys" class="textarea" style="width:400px;height:50px;">{$keywords}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newpage" value="создать" class="btnBlue" />
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

?>