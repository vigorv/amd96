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

$edit = $DB->one_select( "*", "staticpage", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=staticpage\">����������� ��������</a>|��������������: ".$edit['name'];
$control_center->header("����������� ��������", $link_speddbar);
$onl_location = "����������� �������� &raquo; ��������������: ".$edit['name'];

$control_center->errors = array ();

if ($edit['id'])
{
	if (isset($_POST['editpage']))
	{
		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$safehtml->protocolFiltering = "black";

		$control_center->errors = array ();

		$title = $DB->addslashes($safehtml->parse(trim(htmlspecialchars(totranslit(str_replace("/", "_", $_POST['title']))))));
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
            $check = $DB->one_select("id", "staticpage", "title = '{$title}' AND id <> '{$id}'");
            if ($check['id'])
                $control_center->errors[] = "� ���� ������ ��� ���� �������� � ����� �� �������������� ���������.";
        }

		if (!$control_center->errors)
		{
			$DB->update("title = '{$title}', name = '{$namepage}', description = '{$description}', metadescr = '{$metadescr}', metakeys = '{$keywords}', html_br = '{$html_br}'", "staticpage", "id='{$id}'");

            $dop_info = "";
			if ($title != $edit['title'])
				$dop_info = "<br>��������� �������� �������� (URL): ".$edit['title']." -> ".$title;
			if ($namepage != $edit['name'])
				$dop_info = "<br>��������� �������� ��������: ".$edit['name']." -> ".$namepage;

			$info = "<font color=orange>��������������</font> ����������� ��������: ".$namepage." (".$title.")".$dop_info;
            $info = $DB->addslashes($info);
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: {$redirect_url}?do=staticpage" );
            exit();
		}
		else
			$control_center->errors_title = "������!";
	}

	$control_center->message();
    
    $edit['description'] = parse_back_word($edit['description'], false);
    
    if ($edit['html_br'])
        $html_br = "checked";
    else
        $html_br = "";

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">��������������: {$edit['name']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">�������������� ��������:<br><font class="smalltext">����������� � �������� ������ (URL)</font></div>
                                            <div><input type="text" name="title" value="{$edit['title']}" class="inputText" /> <font class="smalltext">�������� 100 ��������</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><input type="text" name="namepage" value="{$edit['name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�����:<br><font class="smalltext">�������� ������������ html ���� � �������</font></div>
                                            <div><textarea name="description" id="description" class="textarea" style="width:720px; height:300px;">{$edit['description']}</textarea></div>
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
                                            <div><input type="text" name="metadescr" value="{$edit['metadescr']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������� keywords:<br><font class="smalltext">����� �������</font></div>
                                            <div><textarea name="metakeys" class="textarea" style="width:400px;height:50px;">{$edit['metakeys']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editpage" value="���������" class="btnBlack" />
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
	$control_center->errors_title = "�� �������!";
	$control_center->errors[] = "��������� ����������� �������� �� �������.";
	$control_center->message();
}

?>