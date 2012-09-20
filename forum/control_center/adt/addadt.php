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

$link_speddbar = "<a href=\"".$redirect_url."?do=adt\">Блоки и реклама</a>|Новый блок";
$control_center->header("Блоки и реклама", $link_speddbar);
$onl_location = "Блоки и реклама &raquo; Новый блок";

if (isset($_POST['newblock']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	if (!$title OR utf8_strlen($title) > 250)
		$control_center->errors[] = "Вы не заполнили поле \"Название\" иди ввели больше 250 символов.";

	$description = $DB->addslashes(trim( $_POST['description'] ) );
	if (!$description)
		$control_center->errors[] = "Вы не заполнили поле \"Текст\".";

	$group_access = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['group_access'] )) );
    $forum_id = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['forums'] )) );

    $in_posts = intval($_POST['in_posts']);
    if ($in_posts < 0 OR $in_posts > 4)
        $in_posts = 0;
        
    $active_status = intval($_POST['active_status']);

	unset($safehtml);

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', text = '{$description}', date = '{$time}', forum_id = '{$forum_id}', in_posts = '{$in_posts}', group_access = '{$group_access}', active_status = '{$active_status}'", "adtblock");
        $cache->clear("template", "adtblock");
        
		$info = "<font color=orange>Добавление</font> нового блока или рекламы: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$redirect_url}?do=adt" );
        exit();
	}
	else
		$control_center->errors_title = "Ошибка!";
}

$forum_list = ForumsList();

$group_list = "<option value=\"0\" selected>Все группы</option>";

foreach($cache_group as $m_group)
{
	$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}

$in_posts = "<option value=\"0\" selected>Не выводить</option>";
$in_posts .= "<option value=\"1\">Выводить наверху</option>";
$in_posts .= "<option value=\"2\">Выводить по центру</option>";
$in_posts .= "<option value=\"3\">Выводить внизу</option>";
$in_posts .= "<option value=\"4\">Выводить наверху, по центру и внизу</option>";

$control_center->message();

echo <<<HTML
<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление блока или рекламы</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption2">Название:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Группы:<br><font class="smalltext">Кому разрешено видеть блок</font></div>
                                            <div><select name="group_access[]" multiple>{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Вывод в форумах:<br><font class="smalltext">Можно выбрать несколько форумов.<br>Ничего не выбирайте, если хотите вывести блок на всех страницах.</font></div>
                                            <div><select name="forums[]" multiple style="width:620px;height:200px;">{$forum_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Вывод в темах:</div>
                                            <div><select name="in_posts">{$in_posts}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Текст:<br><font class="smalltext">Возможно использовать html теги и скрипты</font></div>
                                            <div><textarea name="description" id="description" class="textarea" style="width:620px; height:300px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Активировать:</div>
                                            <div>
						<div class="radioContainer"><input name="active_status" type="radio" id="active_status_1" value="1" checked></div>
 <label class="radioLabel" for="active_status_1">Да</label>
						<div class="radioContainer optionFalse"><input name="active_status" type="radio" id="active_status_0" value="0"></div>
 <label class="radioLabel" for="active_status_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <input type="submit" name="newblock" value="создать" class="btnBlue" />
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