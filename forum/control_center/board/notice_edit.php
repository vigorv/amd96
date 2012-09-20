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

$edit = $DB->one_select( "*", "forums_notice", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|<a href=\"".$redirect_url."?do=board&op=notice\">Объявления</a>|Редактирование: ".$edit['title'];
$control_center->header("Форум", $link_speddbar);
$onl_location = "Форум &raquo; Объявления &raquo; Редактирование: ".$edit['title'];

$control_center->errors = array ();

if ($edit['id'])
{
    require LB_CLASS . '/safehtml.php';
    $safehtml = new safehtml( );
    $safehtml->protocolFiltering = "black";
       
    if (isset($_POST['newemail']))
    {
	   $control_center->errors = array ();

	   $title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	   if (!$title OR utf8_strlen($title) > 255)
		  $control_center->errors[] = "Вы не ввели заголовок или ввели слишком длинный заголовок.";
        
       $_POST['text'] = htmlspecialchars($_POST['text']);
       $_POST['text'] = parse_word(html_entity_decode($safehtml->parse($_POST['text'])));
       $text = $DB->addslashes($_POST['text']); 
        
	   if (!$text)
		  $control_center->errors[] = "Вы не ввели текст объявления.";
        
        if ($_POST['start_date'])
            $start_date = intval( strtotime($_POST['start_date']) );
        else
            $start_date = $time;
        
        $end_date = intval( strtotime($_POST['end_date']) );
    
        $group_access = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['group_access'] )) );
        $forum_id = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['forums'] )) );

        $active_status = intval($_POST['active_status']);
        $show_sub = intval($_POST['show_sub']);

        if (!$control_center->errors)
        {
	        $DB->update("title = '{$title}', text = '{$text}', forum_id = '{$forum_id}', start_date = '{$start_date}', end_date = '{$end_date}', group_access = '{$group_access}', active_status = '{$active_status}', show_sub = '{$show_sub}'", "forums_notice", "id='{$id}'");
            $cache->clear("", "forums_notice");
        
            $info = "<font color=orange>Редактирование</font> объявления: ".$title;
            $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
            header( "Location: ".$redirect_url."?do=board&op=notice" );
            exit();
        }
        else
            $control_center->errors_title = "Ошибка!";
    }

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
    $DB->free();
    
    $start_date = date( "d.m.Y", $edit['start_date']);
    
    if ($edit['end_date'])
        $end_date = date( "d.m.Y", $edit['end_date']);
    else
        $end_date = 0;
        
    $active_status1 = "";    
    $active_status2 = "";
    if ($edit['active_status']) $active_status1 = "checked"; else $active_status2 = "checked";
    
    $show_sub1 = "";    
    $show_sub1 = "";
    if ($edit['show_sub']) $show_sub1 = "checked"; else $show_sub2 = "checked";

    $control_center->message();
    
    require LB_MAIN . '/components/scripts/bbcode/bbcode_cc.php';
    $edit['text'] = parse_back_word($edit['text']);
    
    unset($safehtml);

echo <<<HTML

<script type="text/javascript">
    $(function(){
        $.datepicker.setDefaults(
            $.extend($.datepicker.regional["ru"])
        );
        
        $.datepicker.formatDate('dd-mm-yy');
        
        $("#datepicker").datepicker();
        $("#datepicker2").datepicker();
	});
</script>

<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование объявления: {$edit['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">Заголовок:<br></div>
                                            <div><input type="text" name="title" value="{$edit['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Группы:<br><font class="smalltext">Кому разрешено видеть объявление</font></div>
                                            <div><select name="group_access[]" multiple>{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Форумы:<br><font class="smalltext">Можно выбрать несколько форумов</font></div>
                                            <div><select name="forums[]" multiple style="width:720px;height:200px;">{$forum_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Выводить в подфорумах:<br><font class="smalltext">Выводить объявление во всех подфорумах выбранного форума/категории</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="show_sub" type="radio" id="show_sub_1" value="1" {$show_sub1}></div><label class="radioLabel" for="show_sub_1">Да</label>
                        						<div class="radioContainer optionFalse"><input name="show_sub" type="radio" id="show_sub_0" value="0" {$show_sub2}></div><label class="radioLabel" for="show_sub_0">Нет</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Дата начала:<br><font class="smalltext">Оставьте поле пустым или введите 0 - дата будет текущей</font></div>
                                            <div><input type="text" name="start_date" value="{$start_date}" id="datepicker" class="inputText" /> <font class="smalltext">Формат: 25.10.2010</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Дата конца:<br><font class="smalltext">Оставьте поле пустым или введите 0 - выводиться будет всегда</font></div>
                                            <div><input type="text" name="end_date" value="{$end_date}" id="datepicker2" class="inputText" /> <font class="smalltext">Формат: 25.10.2010</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">Объявление:<br><font class="smalltext">Возможно использовать html теги</font></div></td>
                                            <td width="720" align=left>{$bbcode_script}{$bbcode}<textarea name="text" id="tf" class="textarea" style="width:720px; height:200px;">{$edit['text']}</textarea></td>
                                            </tr></table>

                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Активировать:</div>
                                            <div>
						<div class="radioContainer"><input name="active_status" type="radio" id="active_status_1" value="1" {$active_status1}></div>
 <label class="radioLabel" for="active_status_1">Да</label>
						<div class="radioContainer optionFalse"><input name="active_status" type="radio" id="active_status_0" value="0" {$active_status2}></div>
 <label class="radioLabel" for="active_status_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newemail" value="сохранить" class="btnBlack" />
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
	$control_center->errors[] = "Выбранное объявление не найдено в базе данных.";
	$control_center->message();
}

?>