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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|Добавление группы настроек";
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Добавление группы настроек";

if (isset($_POST['newgroup']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );

	$control_center->errors = array ();

	$gr_name = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_name'] ) ) );
    
	if (utf8_strlen($gr_name) < 3)
		$control_center->errors[] = "Длина названия новой группы меньше 3 символов.";

	$gr_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_desc'] ) ) );
	$gr_group = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_group'] ) ) );
	$gr_prefix = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_prefix'] ), false, true ) );

	$creat_name = $DB->addslashes( $safehtml->parse( trim( $_POST['creat_name'] ) ) );
	$creat_key = $DB->addslashes( $safehtml->parse( trim( totranslit ($_POST['creat_key'] ) ), false, true ) );

	if ($creat_name OR $creat_key)
	{
		if (utf8_strlen($creat_name) < 3 OR utf8_strlen($creat_name) > 30)
			$control_center->errors[] = "Длина названия новой закладки меньше 3 символов или больше 30.";

		if (utf8_strlen($creat_key) < 3 OR utf8_strlen($creat_key) > 30)
			$control_center->errors[] = "Длина ключа новой закладки меньше 3 символов или больше 30.";

		$gr_group = $creat_name;
		$gr_key = $creat_key;
	}
	else
	{
		$checking = $DB->one_select ("*", "configuration_group", "conf_gr_group = '{$gr_group}'");

		if (!$checking['conf_gr_id'])
			$control_center->errors[] = "Выбранная закаладка не найдена.";

		$gr_group = $checking['conf_gr_group_title'];
		$gr_key = $checking['conf_gr_group'];
	}
    
    $checking2 = $DB->one_select ("conf_gr_id", "configuration_group", "conf_gr_prefix = '{$gr_prefix}'");

    if ($checking2['conf_gr_id'])
        $control_center->errors[] = "Группа с таким же префиксом уже есть.";

	if (!$control_center->errors)
	{
		$DB->insert("conf_gr_name = '{$gr_name}', conf_gr_desc = '{$gr_desc}', conf_gr_prefix = '{$gr_prefix}', conf_gr_group = '{$gr_key}', conf_gr_group_title = '{$gr_group}'", "configuration_group");
		$info = "<font color=green>Добавление</font> группы настроек: ".$gr_name;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$_SESSION['back_link_conf']}" );
        exit();
	}
	else
		$control_center->errors_title = "Ошибка!";
}

$DB->select( "*", "configuration_group", "", "GROUP BY conf_gr_group" );
$conf_group = array ();

$group_list = "";

while ( $row = $DB->get_row() )
{
	$group_list .= "<option value=\"".$row['conf_gr_group']."\">".$row['conf_gr_group_title']."</option>";
}
$DB->free();

$control_center->message();

echo <<<HTML

<form  method="post" name="newgroup" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление группы настроект</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                     <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="gr_name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Описание:</div>
                                            <div><textarea name="gr_desc" style="width:300px;height:100px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Префикс:</div>
                                            <div><input type="text" name="gr_prefix" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Закладка:</div>
                                            <div><select name="gr_group">{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Новая закладка (название):</div>
                                            <div><input type="text" name="creat_name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Новая закладка (ключ):</div>
                                            <div><input type="text" name="creat_key" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newgroup" value="создать" class="btnBlue" />
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