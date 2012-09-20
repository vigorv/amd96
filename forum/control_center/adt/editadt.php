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

$edit = $DB->one_select( "*", "adtblock", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=adt\">Блоки и реклама</a>|Редактирование: ".$edit['title'];
$control_center->header("Блоки и реклама", $link_speddbar);
$onl_location = "Блоки и реклама &raquo; Редактирование: ".$edit['title'];

$control_center->errors = array ();

if ($edit['id'])
{
	if (isset($_POST['editblock']))
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
            $DB->update("title = '{$title}', text = '{$description}', forum_id = '{$forum_id}', in_posts = '{$in_posts}', group_access = '{$group_access}', active_status = '{$active_status}'", "adtblock", "id='{$id}'");
            $cache->clear("template", "adtblock");

			$info = "<font color=orange>Редактирование</font> блока или рекламы: ".$title;            
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: {$redirect_url}?do=adt" );
            exit();
		}
		else
			$control_center->errors_title = "Ошибка!";
	}

	$control_center->message();
    
    $forum_list = ForumsList(explode(",", $edit['forum_id']));
    
    $qp = explode (',', $edit['group_access']);
    
    if (in_array("0", $qp))
        $group_list = "<option value=\"0\" selected>Все группы</option>";
    else
        $group_list = "<option value=\"0\">Все группы</option>";

    foreach($cache_group as $m_group)
    {
        if (!in_array("0", $qp))
        {
            if (in_array($m_group['g_id'], $qp))
                $group_list .= "<option value=\"".$m_group['g_id']."\" selected>".$m_group['g_title']."</option>";
            else
                $group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
        }
        else
            $group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
    }
    
    $in_posts0 = "";
    $in_posts1 = "";
    $in_posts2 = "";
    $in_posts3 = "";
    $in_posts4 = "";
    
    if ($edit['in_posts'] == 1)
        $in_posts1 = "selected";
    if ($edit['in_posts'] == 2)
        $in_posts2 = "selected";
    if ($edit['in_posts'] == 3)
        $in_posts3 = "selected";
    if ($edit['in_posts'] == 4)
        $in_posts4 = "selected";
        
    $in_posts = "<option value=\"0\" {$in_posts0}>Не выводить</option>";
    $in_posts .= "<option value=\"1\" {$in_posts1}>Выводить наверху</option>";
    $in_posts .= "<option value=\"2\" {$in_posts2}>Выводить по центру</option>";
    $in_posts .= "<option value=\"3\" {$in_posts3}>Выводить внизу</option>";
    $in_posts .= "<option value=\"4\" {$in_posts4}>Выводить наверху, по центру и внизу</option>";   

    function radio_code($name = "", $checked = false)
    {
        $how = "";
        $how2 = "";
    
        if ($checked)
            $how = "checked";
        else
            $how2 = "checked";
        
echo <<<HTML

<div class="radioContainer"><input name="{$name}" type="radio" id="{$name}_1" value="1" {$how}></div><label class="radioLabel" for="{$name}_1">Да</label>
<div class="radioContainer optionFalse"><input name="{$name}" type="radio" id="{$name}_0" value="0" {$how2}></div><label class="radioLabel" for="{$name}_0">Нет</label>

HTML;

    }

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование блока или рекламы: {$edit['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption2">Название:</div>
                                            <div><input type="text" name="title" value="{$edit['title']}" class="inputText" /></div>
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
                                            <div><textarea name="description" id="description" class="textarea" style="width:620px; height:300px;">{$edit['text']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Активировать:</div>
                                            <div>
                                            
HTML;
radio_code("active_status", $edit['active_status']);
echo <<<HTML
                                            
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editblock" value="сохранить" class="btnBlack" />
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