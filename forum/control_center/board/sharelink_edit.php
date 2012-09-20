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

$edit = $DB->one_select( "*", "topics_sharelink", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|<a href=\"".$redirect_url."?do=board&op=sharelink\">Сервисы публикации</a>|Редактирование: ".$edit['title'];
$control_center->header("Форум", $link_speddbar);
$onl_location = "Форум &raquo; Сервисы публикации &raquo; Редактирование: ".$edit['title'];

$control_center->errors = array ();

if ($edit['id'])
{
    require LB_CLASS . '/safehtml.php';
    $safehtml = new safehtml( );
    $safehtml->protocolFiltering = "black";
       
    if (isset($_POST['newemail']))
    {
	   $control_center->errors = array ();

        function strip_data_2($text)
        {
            $quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "'", ",", "/", "¬", ";", "@", "~", "{", "}", ")", "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"' );
            $goodquotes = array ("-", "+", "#" );
            $repquotes = array ("\-", "\+", "\#" );
            $text = trim( strip_tags( $text ) );
            $text = str_replace( $quotes, '', $text );
            $text = str_replace( $goodquotes, $repquotes, $text );
            $text = ereg_replace(" +", "", $text);
                
            return $text;
        }

	   $title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
    	if (!$title OR utf8_strlen($title) > 255)
    		$control_center->errors[] = "Вы не ввели заголовок или ввели слишком длинный заголовок.";
            
        $icon = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['icon'] ) ) ) );
    	if (!$icon OR utf8_strlen($icon) > 255)
    		$control_center->errors[] = "Вы не ввели навазние иконки или ввели слишком длинное.";
            
        $link = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['link'] ) ) ) );
    	if (!$link OR utf8_strlen($link) > 255)
    		$control_center->errors[] = "Вы не ввели адрес сервиса или ввели слишком длинный.";
        elseif (preg_match("/[\||\'|\"|\!|\$|\@|\~\*\+|<|>|=]/", $link))
            $control_center->errors[] = "В ссылке использованы запрещённые символы.";
        elseif(!(eregi("http:\/\/", $link) || eregi("www", $link)))
    		$control_center->errors[] = "Неверно указана ссылка на ваш сайт.";
            
        $link_topic = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( strip_data_2($_POST['link_topic']) ) ) ) );
    	if (utf8_strlen($link_topic) > 255)
    		$control_center->errors[] = "Вы ввели слишком длинный параметр ссылки.";
            
        $title_topic = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( strip_data_2($_POST['title_topic']) ) ) ) );
    	if (utf8_strlen($title_topic) > 255)
    		$control_center->errors[] = "Вы ввели слишком длинный параметр текста.";
            
        if (!$link_topic AND !$title_topic)
            $control_center->errors[] = "Вы должны указать хотя бы один параметр.";
            
        $dop_parametr = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( strip_data_2($_POST['dop_parametr']) ) ) ) );
	    if (utf8_strlen($dop_parametr) > 255)
		    $control_center->errors[] = "Вы ввели слишком длинный дополнительный параметр.";
    
        $active_status = intval($_POST['active_status']);
        $send_url = intval($_POST['send_url']);

        if (!$control_center->errors)
        {
	        $DB->update("title = '{$title}', icon = '{$icon}', link = '{$link}', link_topic = '{$link_topic}', title_topic = '{$title_topic}', dop_parametr = '{$dop_parametr }', active_status = '{$active_status}', send_url = '{$send_url}'", "topics_sharelink", "id='{$id}'");
            $cache->clear("", "topics_sharelink");
        
            $info = "<font color=orange>Редактирование</font> сервиса публикации: ".$title;
            $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
            header( "Location: ".$redirect_url."?do=board&op=sharelink" );
            exit();
        }
        else
            $control_center->errors_title = "Ошибка!";
    }
        
    $active_status1 = "";    
    $active_status2 = "";
    if ($edit['active_status']) $active_status1 = "checked"; else $active_status2 = "checked";

    $send_url1 = "";    
    $send_url2 = "";
    if ($edit['send_url']) $send_url1 = "checked"; else $send_url2 = "checked";

    $control_center->message();
        
    unset($safehtml);

echo <<<HTML

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
                                            <div class="inputCaption2">Информация:</div>
                                            <div>Если выбранный вами сервис поддерживает только один параметр передачи данных, например, только передача текста, то заполнять поле параметра ссылки не нужно.</div>
                                        </div>
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">Название:</div>
                                            <div><input type="text" name="title"  value="{$edit['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Иконка:<br><font class="smalltext">Введите название иконки (расширение должно быть png) для вывода соотсветвующей картинки. Картинка должна лежать в папке: templates/{$cache_config['template_name']['conf_value']}/images/sharelink/</font></div>
                                            <div><input type="text" name="icon"  value="{$edit['icon']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Адрес:<br><font class="smalltext">Введите адрес сервиса.<br />Например: http://vkontakte.ru/share.php</font></div>
                                            <div><input type="text" name="link" value="{$edit['link']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Параметр ссылки:<br><font class="smalltext">Введите параметр передачи ссылки для GET метода.</font></div>
                                            <div><input type="text" name="link_topic" value="{$edit['link_topic']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Параметр текст:<br><font class="smalltext">Введите параметр передачи текста для GET метода.</font></div>
                                            <div><input type="text" name="title_topic" value="{$edit['title_topic']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Дополнительный параметр:<br><font class="smalltext">Введите дополнительный параметр, если он нужен.</font></div>
                                            <div><input type="text" name="dop_parametr" value="{$edit['dop_parametr']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Передавать только URL:<br><font class="smalltext">Запретить передачу заголовка темы.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="send_url" type="radio" id="send_url_1" value="1" {$send_url1}></div> <label class="radioLabel" for="send_url_1">Да</label>
                        						<div class="radioContainer optionFalse"><input name="send_url" type="radio" id="send_url_0" value="0" {$send_url2}></div> <label class="radioLabel" for="send_url_0">Нет</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Активировать:</div>
                                            <div>
                        						<div class="radioContainer"><input name="active_status" type="radio" id="active_status_1" value="1" {$active_status1}></div> <label class="radioLabel" for="active_status_1">Да</label>
                        						<div class="radioContainer optionFalse"><input name="active_status" type="radio" id="active_status_0" value="0" {$active_status2}></div> <label class="radioLabel" for="active_status_0">Нет</label>
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