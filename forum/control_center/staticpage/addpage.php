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

$link_speddbar = "<a href=\"".$redirect_url."?do=staticpage\">����������� ��������</a>|����� ��������";
$control_center->header("����������� ��������", $link_speddbar);
$onl_location = "����������� �������� &raquo; ����� ��������";

if (isset($_POST['newpage']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( totranslit (str_replace("/", "_", $_POST['title'])) ) ) ) );
	if (!$title OR utf8_strlen($title) > 100)
		$control_center->errors[] = "�� �� ��������� ���� \"�������� (��� URL)\" ��� ����� ������ 100 ��������.";

	$namepage = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['namepage'] ) ) ) );
	if (!$namepage OR utf8_strlen($namepage) > 255)
		$control_center->errors[] = "�� �� ��������� ���� \"��������\" ��� ����� ������ 255 ��������.";

	$metadescr = $DB->addslashes( trim( htmlspecialchars( $_POST['metadescr'] ) ) );

	$keywords = $DB->addslashes($safehtml->parse(trim( htmlspecialchars( $_POST['metakeys'] ) ) ) );

    if (intval($_POST['html_br']))
    {
        $_POST['description'] = add_br($_POST['description']);
        $html_br = 1;
    }
    else
        $html_br = 0;
        
    $description = $DB->addslashes(trim($_POST['description']));
	if (!$description)
		$control_center->errors[] = "�� �� ��������� ���� \"�����\".";

	unset($safehtml);

    if (!$control_center->errors)
    {
        $check = $DB->one_select("id", "staticpage", "title = '{$title}'");
        if ($check['id'])
            $control_center->errors[] = "� ���� ������ ��� ���� �������� � ����� �� �������������� ���������.";
    }

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', name = '{$namepage}', description = '{$description}', date = '{$time}', metadescr = '{$metadescr}', metakeys = '{$keywords}', html_br = '{$html_br}'", "staticpage");
		$info = "<font color=orange>����������</font> ����������� ��������: ".$namepage;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$redirect_url}?do=staticpage" );
        exit();
	}
	else
		$control_center->errors_title = "������!";
        
    $title = stripslashes($title);
    $namepage = stripslashes($namepage);
    $description = stripslashes($description);
    $metadescr = stripslashes($metadescr);
    $keywords = stripslashes($keywords);
}
else
{
    $title = "";
    $namepage = "";
    $description = "";
    $metadescr = "";
    $keywords = "";
    $html_br = "checked";
}

$control_center->message();

echo <<<HTML
<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">���������� ��������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">�������������� ��������:<br><font class="smalltext">����������� � �������� ������ (URL)</font></div>
                                            <div><input type="text" name="title" value="{$title}" class="inputText" /> <font class="smalltext">�������� 100 ��������</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><input type="text" name="namepage" value="{$namepage}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�����:<br><font class="smalltext">�������� ������������ html ���� � �������</font></div>
                                            <div><textarea name="description" id="description" class="textarea" style="width:720px; height:300px;">{$description}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">����������� �����:<br><font class="smalltext">����� �������� HTML ��� �������� ������ &lt;br /></font></div>
                                            <div><input type="checkbox" name="html_br" value="1" {$html_br} /></div>
                                        </div>
                                         <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption">������� description:</div>
                                            <div><input type="text" name="metadescr" value="{$metadescr}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������� keywords:<br><font class="smalltext">����� �������</font></div>
                                            <div><textarea name="metakeys" class="textarea" style="width:400px;height:50px;">{$keywords}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newpage" value="�������" class="btnBlue" />
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