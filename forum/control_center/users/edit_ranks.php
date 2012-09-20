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

$editrank = $DB->one_select( "*", "members_ranks", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=ranks\">Звания</a>|Редактирование: ".$editrank['title'];
$control_center->header("Пользователи", $link_speddbar);
$onl_location = "Пользователи &raquo; Звания &raquo; Редактирование: ".$editrank['title'];

$control_center->errors = array ();

if ($editrank['id'])
{
	if (isset($_POST['editrank']))
	{
		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$safehtml->protocolFiltering = "black";

		$control_center->errors = array ();

		$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
		if (utf8_strlen($title) < 3 OR utf8_strlen($title) > 255)
			$control_center->errors[] = "Длина звания меньше 3 символов или больше 255.";

        if (!$editrank['mid'])
        {
    		$post_num = intval( $_POST['post_num'] );
    		if ($post_num < 0)
    			$control_center->errors[] = "Минимальное количество сообщений меньше нуля.";
        }
        else
            $post_num = 0;
            
        if ($editrank['mid'])
        {
            $mid = $DB->addslashes( $safehtml->parse( trim( $_POST['name_rank'] ) ) );
            $DB->prefix = DLE_USER_PREFIX;
            $check = $DB->one_select ("user_id", "users", "name = '{$mid}'");
            if ($check['user_id'])
            {
                $mid = $check['user_id'];
                $check_2 = $DB->one_select ("id", "members_ranks", "mid = '{$mid}' AND id <> '{$id}'");
                if ($check_2['id'])
                    $control_center->errors[] = "У выбранного пользователя уже есть личное звание.";
            }
            else
                $control_center->errors[] = "Выбранный пользователь не найден в базе данных.";
        }
        else
            $mid = 0;
            
		if (is_int($_POST['stars']))
			$stars = intval( $_POST['stars'] );
		else
		{
			$stars = $DB->addslashes( $safehtml->parse( trim( $_POST['stars'] ), false, true ) );
            if (strtolower($stars) == "default.png")
                $control_center->errors[] = "Запрещено использование данного названия для картинки.";
		}
		if (!$stars)
			$control_center->errors[] = "Не указано количество звёзд или имя картинки.";

		if (!$control_center->errors)
		{
			$DB->update("title = '{$title}', post_num = '{$post_num}', stars = '{$stars}', mid = '{$mid}'", "members_ranks", "id='{$id}'");

			$dop_info = "";
			if ($title != $editrank['title'])
				$dop_info .= "<br>Изменено название звания: ".$editrank['title']." -> ".$title;

			$cache->clear("", "ranks");

			$info = "<font color=orange>Редактирование</font> звания пользователей: ".$title.$dop_info;
            $info = $DB->addslashes($info);
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: ".$redirect_url."?do=users&op=ranks" );
            exit();
		}
		else
			$control_center->errors_title = "Ошибка!";
	}

	$control_center->message();

	$editrank['title'] = htmlspecialchars($editrank['title']);
    
    if ($editrank['mid'])
    {
        $DB->prefix = DLE_USER_PREFIX;
        $check = $DB->one_select ("name", "users", "user_id = '{$editrank['mid']}'");
    }

echo <<<HTML
<form  method="post" name="editrank" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование звания: {$editrank['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>                       
HTML;

if ($editrank['mid'])
{
echo <<<HTML

                                        <div>
                                            <div class="inputCaption">Пользователь:<br><font class="smalltext">Введите логин</font></div>
                                            <div><input type="text" name="name_rank" value="{$check['name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
HTML;
}
echo <<<HTML

                                       <div>
                                            <div class="inputCaption">Звание:</div>
                                            <div><input type="text" name="title" value="{$editrank['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        
                                        
HTML;

if (!$editrank['mid'])
{
echo <<<HTML
                                        <div>
                                            <div class="inputCaption">Минимальное кол-во сообщений:</div>
                                            <div><input type="text" name="post_num" value="{$editrank['post_num']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
HTML;
}
echo <<<HTML
                                        <div>
                                            <div class="inputCaption">Количество звед или название изображения:<br><font class="smalltext">Изображение должно быть в папке templates/ШАБЛОН/ranks</font></div>
                                            <div><input type="text" name="stars" value="{$editrank['stars']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editrank" value="сохранить" class="btnBlack" />
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
else
{
	$control_center->errors_title = "Не найдено!";
	$control_center->errors[] = "Выбранный ранг не найден в базе данных.";
	$control_center->message();
}

?>