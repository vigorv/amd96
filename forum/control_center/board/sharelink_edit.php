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

$edit = $DB->one_select( "*", "topics_sharelink", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|<a href=\"".$redirect_url."?do=board&op=sharelink\">������� ����������</a>|��������������: ".$edit['title'];
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ������� ���������� &raquo; ��������������: ".$edit['title'];

$control_center->errors = array ();

if ($edit['id'])
{
    require LB_CLASS . '/safehtml.php';
    $safehtml = new safehtml( );
    $safehtml->protocolFiltering = "black";
       
    if (isset($_POST['newemail']))
    {
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

        if (!$control_center->errors)
        {
	        $DB->update("title = '{$title}', icon = '{$icon}', link = '{$link}', link_topic = '{$link_topic}', title_topic = '{$title_topic}', dop_parametr = '{$dop_parametr }', active_status = '{$active_status}', send_url = '{$send_url}'", "topics_sharelink", "id='{$id}'");
            $cache->clear("", "topics_sharelink");
        
            $info = "<font color=orange>��������������</font> ������� ����������: ".$title;
            $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
            header( "Location: ".$redirect_url."?do=board&op=sharelink" );
            exit();
        }
        else
            $control_center->errors_title = "������!";
    }
        
    $active_status1 = "";    
    $active_status2 = "";
    if ($edit['active_status']) $active_status1 = "checked"; else $active_status2 = "checked";

    $send_url1 = "";    
    $send_url2 = "";
    if ($edit['send_url']) $send_url1 = "checked"; else $send_url2 = "checked";

    $control_center->message();
        
    unset($safehtml);

echo <<<HTML

<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������������� ����������: {$edit['title']}</div>
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
                                            <div><input type="text" name="title"  value="{$edit['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������:<br><font class="smalltext">������� �������� ������ (���������� ������ ���� png) ��� ������ �������������� ��������. �������� ������ ������ � �����: templates/{$cache_config['template_name']['conf_value']}/images/sharelink/</font></div>
                                            <div><input type="text" name="icon"  value="{$edit['icon']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�����:<br><font class="smalltext">������� ����� �������.<br />��������: http://vkontakte.ru/share.php</font></div>
                                            <div><input type="text" name="link" value="{$edit['link']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ������:<br><font class="smalltext">������� �������� �������� ������ ��� GET ������.</font></div>
                                            <div><input type="text" name="link_topic" value="{$edit['link_topic']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� �����:<br><font class="smalltext">������� �������� �������� ������ ��� GET ������.</font></div>
                                            <div><input type="text" name="title_topic" value="{$edit['title_topic']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������������� ��������:<br><font class="smalltext">������� �������������� ��������, ���� �� �����.</font></div>
                                            <div><input type="text" name="dop_parametr" value="{$edit['dop_parametr']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">���������� ������ URL:<br><font class="smalltext">��������� �������� ��������� ����.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="send_url" type="radio" id="send_url_1" value="1" {$send_url1}></div> <label class="radioLabel" for="send_url_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="send_url" type="radio" id="send_url_0" value="0" {$send_url2}></div> <label class="radioLabel" for="send_url_0">���</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������������:</div>
                                            <div>
                        						<div class="radioContainer"><input name="active_status" type="radio" id="active_status_1" value="1" {$active_status1}></div> <label class="radioLabel" for="active_status_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="active_status" type="radio" id="active_status_0" value="0" {$active_status2}></div> <label class="radioLabel" for="active_status_0">���</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newemail" value="���������" class="btnBlack" />
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
	$control_center->errors[] = "��������� ���������� �� ������� � ���� ������.";
	$control_center->message();
}

?>