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
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$note = $DB->one_select( "*", "note" );

if (isset($_POST['note']))
{
	require_once LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );

	$text = $DB->addslashes($safehtml->parse(trim( $_POST['text'] ) ) );

	if (!$note['id'])
	{
		$DB->insert("text = '{$text}', name = '{$member['name']}', startdate = '{$time}'", "note");
	}
	else
	{
		$DB->update("text = '{$text}', lastdate = '{$time}'", "note", "id='{$note['id']}'");
	}
	header( "Location: {$_SERVER['REQUEST_URI']}" );
}

$note['startdate'] = formatdate( $note['startdate'] );
if (isset($note['lastdate']))
    $note['lastdate'] = formatdate( $note['lastdate'] );
else
    $note['lastdate'] = "��� �� ���������";

echo <<<HTML
		<form  method="post" name="note" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>��������� ���������: {$note['lastdate']}</div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div><textarea name="text" id="note" class="textarea">{$note['text']}</textarea></div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div><input type="submit" name="note" class="btnBlack" value="���������" /></div>
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