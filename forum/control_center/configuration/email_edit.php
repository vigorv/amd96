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

$edit = $DB->one_select( "*", "templates_email", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|<a href=\"".$redirect_url."?do=configuration&op=email\">Шаблоны E-mail уведомлений</a>|Редактирование: ".$edit['title'];
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Шаблоны E-mail уведомлений &raquo; Редактирование: ".$edit['title'];

$control_center->errors = array ();

if ($edit['id'])
{
   	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";
    
	if (isset($_POST['editemail']))
	{
		$control_center->errors = array ();

	   $title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	   if (!$title)
		      $control_center->errors[] = "Вы не заполнили поле \"Название\".";
        
	   $body_text = $DB->addslashes($safehtml->parse(trim( $_POST['body_text'] ), true ) );
	   if (!$body_text)
		      $control_center->errors[] = "Вы не заполнили поле \"Шаблон\".";

		if (!$control_center->errors)
		{
			$DB->update("title = '{$title}', body_text = '{$body_text}'", "templates_email", "id='{$id}'");
            $cache->clear("template", "email_template");

            $dop_info = "";
			if ($title != $edit['title'])
				$dop_info = "<br>Изменнено название шаблона E-mail уведомелния: ".$DB->addslashes($edit['title'])." -> ".$title;

			$info = "<font color=orange>Редактирование</font> шаблона E-mail уведомелния: ".$title.$dop_info;
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: ".$redirect_url."?do=configuration&op=email" );
            exit();
		}
		else
			$control_center->errors_title = "Ошибка!";
	}

	$control_center->message();
    
    $edit['body_text'] = str_replace("<br />", "\n", $edit['body_text']);
    
   	unset($safehtml);

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование шаблона E-mail уведомления: {$edit['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="title" value="{$edit['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                        <table><tr>
                                            <td align=left width="180" style="padding-right:10px; padding-top:4px;">Описание тегов:<br><font class="smalltext">Теги, которые можно использовать в шаблоне.</font></td>
                                            <td align=left>{froum_link} - Ссылка на форум
                                            <br>{forum_name} - Название форума
                                            <br>{user_name} - Логин пользователя
                                            <br>{user_id} - ID пользователя
                                            <br>{user_ip} - IP пользователя
                                            <br>{message} - Текст
                                            <br>{active_link} - Ссылка <font class="smalltext">(используется для: регистрации, восстановления пароля и уведомления о новом ЛС)</font>
                                            <br>{user_password} - Пароль, указанный при регистрации <font class="smalltext">(используется для: регистрации)</font>
                                            <br>{user_login_name} - Логин или почта для авторизации <font class="smalltext">(зависет от настроект безопасности, используется для: регистрации)</font></td>
                                        </tr></table>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Шаблон:<br><font class="smalltext">Возможно использовать html теги и скрипты</font></div>
                                            <div><textarea name="body_text" class="textarea" style="width:720px; height:300px;">{$edit['body_text']}</textarea></div>
                                        </div>
                                          <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editemail" value="сохранить" class="btnBlack" />
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
	$control_center->errors[] = "Выбранный шаблон E-mail уведомления не найден или защищён.";
	$control_center->message();
}

?>