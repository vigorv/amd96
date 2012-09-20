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
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if (isset($_POST['editrules']))
{    
    $rules = $DB->addslashes(parse_word( $_POST['rules'] ) );
    $DB->insert("id = '1', text = '{$rules}', lastdate = '{$time}' ON DUPLICATE KEY UPDATE id = '1', text = '{$rules}', lastdate = '{$time}'", "rules");
    
    header( "Location: ".$redirect_url."?do=rules" );
    exit();
}

$control_center->header("Правила форума", "Правила форума");
$onl_location = "Правила форума";

$row = $DB->one_select("*", "rules");

require LB_MAIN . '/components/scripts/bbcode/bbcode_cc.php';
$row['text'] = parse_back_word($row['text']);

echo <<<HTML
<form  method="post" name="editrules_form" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Правила форума</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            Правила:<br><font class="smalltext">Если правила отсутствуют, то на странице вывода правил будет выводиться соответствующая информация.</font><br><br>
                                            {$bbcode_script}{$bbcode}
                                            <textarea name="rules" class="textarea" id="tf" style="width:99%;height:300px;">{$row['text']}</textarea>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <a href="{$cache_config['general_site']['conf_value']}?do=rules">Страница правил на форуме.</a><br><br>
                                            <input type="submit" name="editrules" value="сохранить" class="btnBlack" />
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

$control_center->footer(7);

?>