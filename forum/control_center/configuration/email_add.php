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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|<a href=\"".$redirect_url."?do=configuration&op=email\">Шаблоны E-mail уведомлений</a>|Новый шаблон";
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Шаблоны E-mail уведомлений &raquo; Новый шаблон";

if (isset($_POST['newemail']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	if (!$title)
		$control_center->errors[] = "Вы не заполнили поле \"Название\".";
        
	$body_text = $DB->addslashes($safehtml->parse(trim( $_POST['body_text'] ), true ) );
	if (!$body_text)
		$control_center->errors[] = "Вы не заполнили поле \"Шаблон\".";

	unset($safehtml);

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', body_text = '{$body_text}', date = '{$time}'", "templates_email");
        $cache->clear("template", "email_template");
        
		$info = "<font color=green>Добавление</font> шаблона E-mail уведомления: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=configuration&op=email" );
        exit();
	}
    else
		$control_center->errors_title = "Ошибка!";
}

$control_center->message();

echo <<<HTML
<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление шаблона E-mail</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">Название:<br></div>
                                            <div><input type="text" name="title" class="inputText" /></div>
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
                                            <div><textarea name="body_text" class="textarea" style="width:720px; height:300px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newemail" value="создать" class="btnBlue" />
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