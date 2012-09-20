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

$ongroup = $DB->one_select( "*", "configuration_group", "conf_gr_id = '{$id}'" );
$control_center->errors = array ();

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a>|<a href=\"?do=configuration&op=show&id=".$id."\">Группа: ".$ongroup['conf_gr_name']."</a>|Добавление настройки";
$control_center->header("Настройки", $link_speddbar);
$onl_location = "Настройки &raquo; Группа: ".$ongroup['conf_gr_name']." &raquo; Добавление настройки";

if ($ongroup['conf_gr_id'])
{
	if (isset($_POST['newconfig']))
	{
		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$conf_name = $DB->addslashes( $safehtml->parse( trim( $_POST['conf_name'] ) ) );
        
		if (utf8_strlen($conf_name) < 3)
			$control_center->errors[] = "Длина названия настройки меньше 3 символов.";

        $conf_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['conf_desc'] ), true ) );
		$conf_group = intval( $_POST['conf_group'] );

		$conf_posi = intval( $_POST['conf_posi'] );
		if ($conf_posi < 0)
			$conf_posi = 1;

		$conf_option = $DB->addslashes( $safehtml->parse( $_POST['conf_option'] ) );

		$conf_type = intval( $_POST['conf_type'] );
		if ($conf_type < 0 OR $conf_type > 4)
			$control_center->errors[] = "Тип настройки не выбран.";

		$conf_key = $DB->addslashes( $safehtml->parse( trim( totranslit ($_POST['conf_key'] ) ), false, true ) );

		if (utf8_strlen($conf_key) < 3 OR utf8_strlen($conf_key) > 30)
			$control_center->errors[] = "Поле ключа не заполнено.";
        else
        {
            $checking2 = $DB->one_select ("conf_id", "configuration", "conf_key = '{$conf_key}'");

            if ($checking2['conf_id'])
                $control_center->errors[] = "Настройка с таким же ключом уже есть.";
        }

		$conf_value = $DB->addslashes( $safehtml->parse( trim( $_POST['conf_value'] ) ) );

		$checking = $DB->one_select ("conf_gr_id", "configuration_group", "conf_gr_id = '{$conf_group}'");

		if (!$checking['conf_gr_id'])
			$control_center->errors[] = "Выбранная закаладка не найдена.";

		if (!$control_center->errors)
		{
			$DB->insert("conf_name = '{$conf_name}', conf_posi = '{$conf_posi}', conf_desc = '{$conf_desc}', conf_group = '{$conf_group}', conf_type = '{$conf_type}', conf_key = '{$conf_key}', conf_option = '{$conf_option}', conf_value = '{$conf_value}'", "configuration");
			$cache->clear("", "config");

			$info = "<font color=green>Добавление</font> настройки: ".$conf_name;
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: {$_SESSION['back_link_conf']}" );
            exit();
		}
		else
			$control_center->errors_title = "Ошибка!";
	}

	$DB->select( "*", "configuration_group", "", "ORDER BY conf_gr_id ASC" );
	$conf_group = array ();

	$group_list = "";

	while ( $row = $DB->get_row() )
	{
		if ($id == $row['conf_gr_id'])
			$group_list .= "<option value=\"".$row['conf_gr_id']."\" selected>".$row['conf_gr_name']."</option>";
		else
			$group_list .= "<option value=\"".$row['conf_gr_id']."\">".$row['conf_gr_name']."</option>";
	}
	$DB->free();

	$control_center->message();

	$conf_type = array();
	$conf_type[0] = "Кнопки: Да/Нет";
	$conf_type[1] = "Выпадающее меню";
	$conf_type[2] = "Выпадающее меню (мульти)";
	$conf_type[3] = "Одна строка";
	$conf_type[4] = "Текстовое";
    
   	$conf_type_id = array();
	$conf_type_id[0] = "radio_j";
	$conf_type_id[1] = "select_j";
	$conf_type_id[2] = "select_mult_j";
	$conf_type_id[3] = "text_j";
	$conf_type_id[4] = "text2_j";
    
	$conf_type_list = "";
	for ($i=0; $i < 5; $i++)
	{
		$conf_type_list .= "<option value=\"".$i."\" id=\"".$conf_type_id[$i]."\">".$conf_type[$i]."</option>";
	}

	$posi = $DB->one_select( "MAX(conf_posi) as max_posi", "configuration", "conf_group = '{$id}'" );
	$posi['max_posi'] += 1;

echo <<<HTML

<script>

  $(document).ready(function(){
    
    $("#radio_j").click(function () {
      $("div #select").hide(300);
      $("div #select_mult").hide(300);
      $("div #text").hide(300);  
      $("div #radio").show(500);    
    });
    
    $("#select_j").click(function () {
      $("div #radio").hide(300);
      $("div #select_mult").hide(300);
      $("div #text").hide(300);  
      $("div #select").show(500);    
    }); 
    
    $("#select_mult_j").click(function () {
      $("div #radio").hide(300);
      $("div #text").hide(300);  
      $("div #select").show(300);   
      $("div #select_mult").show(500); 
    });  
    
    $("#text_j").click(function () {
      $("div #radio").hide(300);
      $("div #select_mult").hide(300);  
      $("div #select").hide(300);   
      $("div #text").show(500); 
    }); 
    
    $("#text2_j").click(function () {
      $("div #radio").hide(300);
      $("div #select_mult").hide(300);  
      $("div #select").hide(300);   
      $("div #text").show(500); 
    }); 

    $("#radio_j").click();

  });  
</script>

<form  method="post" name="newgroup" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление настройки</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="conf_name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Описание:</div>
                                            <div><textarea name="conf_desc" style="width:300px;height:100px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Позиция в списке:</div>
                                            <div><input type="text" name="conf_posi" value="{$posi['max_posi']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Группа:</div>
                                            <div><select name="conf_group">{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Тип:</div>
                                            <div><select name="conf_type">{$conf_type_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div id="select" style="display:none;">
                                            <div class="inputCaption">Списки:<br><font class="smalltext">Связь: Ключ=Значение</font></div>
                                            <div><textarea name="conf_option" style="width:300px;height:100px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Значение (ключ) по умолчанию:
                                            <div id="select_mult" style="display:none;"><font class="smalltext">Каждый ключ через запятую.</div>
                                            <div id="radio" style="display:none;"><font class="smalltext">1 - да, 0 - нет</div>
                                            <div id="text" style="display:none;"><font class="smalltext">Введите текст</div>
                                            </div>
                                            <div><textarea name="conf_value" style="width:300px;height:100px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Ключ:</div>
                                            <div><input type="text" name="conf_key" value="{$ongroup['conf_gr_prefix']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newconfig" value="создать" class="btnBlue" />
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
	$links_speddbar = "<a href=\"".$redirect_url."?do=configuration\">Настройки</a> &raquo; Ошибка";
	$control_center->speedbar($links_speddbar);

	$onl_location = "Добавление настройки в группу: Не определено";
	$control_center->errors_title = "Не найдено!";
	$control_center->errors[] = "Выбранная группа не найдена.";
	$control_center->message();
}

?>