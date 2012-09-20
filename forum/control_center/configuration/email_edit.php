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

$edit = $DB->one_select( "*", "templates_email", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|<a href=\"".$redirect_url."?do=configuration&op=email\">������� E-mail �����������</a>|��������������: ".$edit['title'];
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������� E-mail ����������� &raquo; ��������������: ".$edit['title'];

$control_center->errors = array ();

if ($edit['id'])
{
   	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";
    
	if (isset($_POST['editemail']))
	{
		$control_center->errors = array ();

	   $title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	   if (!$title)
		      $control_center->errors[] = "�� �� ��������� ���� \"��������\".";
        
	   $body_text = $DB->addslashes($safehtml->parse(trim( $_POST['body_text'] ), true ) );
	   if (!$body_text)
		      $control_center->errors[] = "�� �� ��������� ���� \"������\".";

		if (!$control_center->errors)
		{
			$DB->update("title = '{$title}', body_text = '{$body_text}'", "templates_email", "id='{$id}'");
            $cache->clear("template", "email_template");

            $dop_info = "";
			if ($title != $edit['title'])
				$dop_info = "<br>��������� �������� ������� E-mail �����������: ".$DB->addslashes($edit['title'])." -> ".$title;

			$info = "<font color=orange>��������������</font> ������� E-mail �����������: ".$title.$dop_info;
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: ".$redirect_url."?do=configuration&op=email" );
            exit();
		}
		else
			$control_center->errors_title = "������!";
	}

	$control_center->message();
    
    $edit['body_text'] = str_replace("<br />", "\n", $edit['body_text']);
    
   	unset($safehtml);

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������������� ������� E-mail �����������: {$edit['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><input type="text" name="title" value="{$edit['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                        <table><tr>
                                            <td align=left width="180" style="padding-right:10px; padding-top:4px;">�������� �����:<br><font class="smalltext">����, ������� ����� ������������ � �������.</font></td>
                                            <td align=left>{froum_link} - ������ �� �����
                                            <br>{forum_name} - �������� ������
                                            <br>{user_name} - ����� ������������
                                            <br>{user_id} - ID ������������
                                            <br>{user_ip} - IP ������������
                                            <br>{message} - �����
                                            <br>{active_link} - ������ <font class="smalltext">(������������ ���: �����������, �������������� ������ � ����������� � ����� ��)</font>
                                            <br>{user_password} - ������, ��������� ��� ����������� <font class="smalltext">(������������ ���: �����������)</font>
                                            <br>{user_login_name} - ����� ��� ����� ��� ����������� <font class="smalltext">(������� �� ��������� ������������, ������������ ���: �����������)</font></td>
                                        </tr></table>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������:<br><font class="smalltext">�������� ������������ html ���� � �������</font></div>
                                            <div><textarea name="body_text" class="textarea" style="width:720px; height:300px;">{$edit['body_text']}</textarea></div>
                                        </div>
                                          <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editemail" value="���������" class="btnBlack" />
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
	$control_center->errors[] = "��������� ������ E-mail ����������� �� ������ ��� �������.";
	$control_center->message();
}

?>