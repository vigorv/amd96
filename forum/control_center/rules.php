<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

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

$control_center->header("������� ������", "������� ������");
$onl_location = "������� ������";

$row = $DB->one_select("*", "rules");

require LB_MAIN . '/components/scripts/bbcode/bbcode_cc.php';
$row['text'] = parse_back_word($row['text']);

echo <<<HTML
<form  method="post" name="editrules_form" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">������� ������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            �������:<br><font class="smalltext">���� ������� �����������, �� �� �������� ������ ������ ����� ���������� ��������������� ����������.</font><br><br>
                                            {$bbcode_script}{$bbcode}
                                            <textarea name="rules" class="textarea" id="tf" style="width:99%;height:300px;">{$row['text']}</textarea>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <a href="{$cache_config['general_site']['conf_value']}?do=rules">�������� ������ �� ������.</a><br><br>
                                            <input type="submit" name="editrules" value="���������" class="btnBlack" />
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