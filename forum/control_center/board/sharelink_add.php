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

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|<a href=\"".$redirect_url."?do=board&op=sharelink\">������� ����������</a>|����������";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ������� ���������� &raquo; ����������";

if (isset($_POST['newemail']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();
    
    function strip_data_2($text)
    {
        $quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "'", ",", "/", "�", ";", "@", "~", "{", "}", ")", "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"' );
        $goodquotes = array ("-", "+", "#" );
        $repquotes = array ("\-", "\+", "\#" );
        $text = trim( strip_tags( $text ) );
        $text = str_replace( $quotes, '', $text );
        $text = str_replace( $goodquotes, $repquotes, $text );
        $text = ereg_replace(" +", "", $text);
                
        return $text;
    }

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	if (!$title OR utf8_strlen($title) > 255)
		$control_center->errors[] = "�� �� ����� ��������� ��� ����� ������� ������� ���������.";
        
    $icon = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['icon'] ) ) ) );
	if (!$icon OR utf8_strlen($icon) > 255)
		$control_center->errors[] = "�� �� ����� �������� ������ ��� ����� ������� �������.";
        
    $link = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['link'] ) ) ) );
	if (!$link OR utf8_strlen($link) > 255)
		$control_center->errors[] = "�� �� ����� ����� ������� ��� ����� ������� �������.";
    elseif (preg_match("/[\||\'|\"|\!|\$|\@|\~\*\+|<|>|=]/", $link))
        $control_center->errors[] = "� ������ ������������ ����������� �������.";
    elseif(!(eregi("http:\/\/", $link) || eregi("www", $link)))
		$control_center->errors[] = "������� ������� ������ �� ��� ����.";
        
    $link_topic = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( strip_data_2($_POST['link_topic']) ) ) ) );
	if (utf8_strlen($link_topic) > 255)
		$control_center->errors[] = "�� ����� ������� ������� �������� ������.";
        
    $title_topic = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( strip_data_2($_POST['title_topic']) ) ) ) );
	if (utf8_strlen($title_topic) > 255)
		$control_center->errors[] = "�� ����� ������� ������� �������� ������.";
        
    if (!$link_topic AND !$title_topic)
        $control_center->errors[] = "�� ������ ������� ���� �� ���� ��������.";

    $dop_parametr = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( strip_data_2($_POST['dop_parametr']) ) ) ) );
	if (utf8_strlen($dop_parametr) > 255)
		$control_center->errors[] = "�� ����� ������� ������� �������������� ��������.";

    $active_status = intval($_POST['active_status']);
    $send_url = intval($_POST['send_url']);

	unset($safehtml);

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', icon = '{$icon}', link = '{$link}', link_topic = '{$link_topic}', title_topic = '{$title_topic}', dop_parametr = '{$dop_parametr }', active_status = '{$active_status}', send_url = '{$send_url}'", "topics_sharelink");
        $cache->clear("", "topics_sharelink");
        
		$info = "<font color=green>����������</font> ������� ����������: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=board&op=sharelink" );
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
                        <div class="headerGrayBg">���������� ������� ����������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption2">����������:</div>
                                            <div>���� ��������� ���� ������ ������������ ������ ���� �������� �������� ������, ��������, ������ �������� ������, �� ��������� ���� ��������� ������ �� �����.</div>
                                        </div>
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">��������:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������:<br><font class="smalltext">������� �������� ������ (���������� ������ ���� png) ��� ������ �������������� ��������. �������� ������ ������ � �����: templates/{$cache_config['template_name']['conf_value']}/images/sharelink/</font></div>
                                            <div><input type="text" name="icon" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�����:<br><font class="smalltext">������� ����� �������.<br />��������: http://vkontakte.ru/share.php</font></div>
                                            <div><input type="text" name="link" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ������:<br><font class="smalltext">������� �������� �������� ������ ��� GET ������.</font></div>
                                            <div><input type="text" name="link_topic" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� �����:<br><font class="smalltext">������� �������� �������� ������ ��� GET ������.</font></div>
                                            <div><input type="text" name="title_topic" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������������� ��������:<br><font class="smalltext">������� �������������� ��������, ���� �� �����.</font></div>
                                            <div><input type="text" name="dop_parametr" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">���������� ������ URL:<br><font class="smalltext">��������� �������� ��������� ����.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="send_url" type="radio" id="send_url_1" value="1"></div> <label class="radioLabel" for="send_url_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="send_url" type="radio" id="send_url_0" value="0" checked></div> <label class="radioLabel" for="send_url_0">���</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������������:</div>
                                            <div>
                        						<div class="radioContainer"><input name="active_status" type="radio" id="active_status_1" value="1" checked></div> <label class="radioLabel" for="active_status_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="active_status" type="radio" id="active_status_0" value="0"></div> <label class="radioLabel" for="active_status_0">���</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
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