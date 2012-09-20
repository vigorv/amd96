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


$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|Добавление категории";
$control_center->header("Форум", $link_speddbar);
$onl_location = "Форум &raquo; Добавление категории";

if (isset($_POST['newcat']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );

	$group_permission = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['group_permission'] )) );

	$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
	if (utf8_strlen($title) < 3)
		$control_center->errors[] = "Длина названия категории меньше 3 символов.";

	$posi = intval( $_POST['posi'] );

	if ($posi < 0) $posi = 1;

    $alt_name = $DB->addslashes( $safehtml->parse( totranslit( $_POST['alt_name'] ) ) );
	if (!$alt_name)
        $alt_name = $DB->addslashes( $safehtml->parse( totranslit( trim( $_POST['title'] ) ) ) );
        
    $check_alt = $DB->one_select("id", "forums", "alt_name = '{$alt_name}'");
	if ($check_alt['id'])
		$control_center->errors[] = "Форум с таким альтернативным названием уже есть.";	
        
    $meta_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_desc'] ) ) );
    $meta_key = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_key'] ) ) );

	if (!$control_center->errors)
	{
		$DB->insert("ficon = '', parent_id = '0', posi = '{$posi}', title = '{$title}', alt_name = '{$alt_name}', group_permission = '{$group_permission}', meta_desc = '{$meta_desc}', meta_key = '{$meta_key}', description = '', allow_bbcode = '1', allow_poll = '1', postcount = '1', password = '', password_notuse = '', sort_order = 'DESC', rules = '', allow_bbcode_list = ''", "forums");
		$cache->clear("", "forums");

		$info = "<font color=green>Добавление</font> категории форума: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=board" );
        exit();
	}
	else
		$control_center->errors_title = "Ошибка!";
}

$control_center->message();

$posi = $DB->one_select( "MAX(posi) as max_posi", "forums", "parent_id = '0'" );
$posi['max_posi'] += 1;

$group_list = "<option value=\"0\" selected>Все группы</option>";

foreach($cache_group as $m_group)
{
	$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}
$DB->free();

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление новой категории</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">Название:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Альтернативное название:</div>
                                            <div><input type="text" name="alt_name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Позиция:</div>
                                            <div><input type="text" name="posi" value="{$posi['max_posi']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Просмотр:</div>
                                            <div><select name="group_permission[]" multiple>{$group_list}</select></div>
                                        </div>
                        <div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">Мета описание:<br><font class="smalltext">Описание форума, которое будет выводиться в метатеге "description".<br>Небольше 200 символов.</font></div>
                                            <div><textarea name="meta_desc" class="inputText" style="width:700px;height:60px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">Мета ключевые слова:<br><font class="smalltext">Ключевые слова форума, которык будут выводиться в метатеге "keywords".<br>Каждое слово через запятую.<br>Небольше 1000 символов..</font></div>
                                            <div><textarea name="meta_key" class="inputText" style="width:700px;height:70px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <div><input type="submit" name="newcat" value="создать" class="btnBlue" /></div>
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