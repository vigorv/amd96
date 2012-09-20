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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|<a href=\"".$redirect_url."?do=configuration&op=email\">������� E-mail �����������</a>|����� ������";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������� E-mail ����������� &raquo; ����� ������";

if (isset($_POST['newemail']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	if (!$title)
		$control_center->errors[] = "�� �� ��������� ���� \"��������\".";
        
	$body_text = $DB->addslashes($safehtml->parse(trim( $_POST['body_text'] ), true ) );
	if (!$body_text)
		$control_center->errors[] = "�� �� ��������� ���� \"������\".";

	unset($safehtml);

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', body_text = '{$body_text}', date = '{$time}'", "templates_email");
        $cache->clear("template", "email_template");
        
		$info = "<font color=green>����������</font> ������� E-mail �����������: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=configuration&op=email" );
        exit();
	}
    else
		$control_center->errors_title = "������!";
}

$control_center->message();

echo <<<HTML
<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">���������� ������� E-mail</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">��������:<br></div>
                                            <div><input type="text" name="title" class="inputText" /></div>
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
                                            <div><textarea name="body_text" class="textarea" style="width:720px; height:300px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newemail" value="�������" class="btnBlue" />
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