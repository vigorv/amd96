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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=delivery\">��������</a>|�����";
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; �������� &raquo; �����";

$control_center->errors = array ();

$result_mass = array();

if (isset($_POST['delivery']) AND $_SESSION['LB_delivery'] == 1)
{
    ignore_user_abort(1);
    @set_time_limit(0);
         
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$title = $safehtml->parse( trim( $_REQUEST['title'] ) );
	if (!$title)
		$control_center->errors[] = "�� �� ����� ���������.";
        
    $_POST['text'] = str_replace( "{froum_link}", $cache_config['general_site']['conf_value'], $_POST['text'] );
    $_POST['text'] = str_replace( "{forum_name}", $cache_config['general_name']['conf_value'], $_POST['text'] );
    $_POST['text'] = htmlspecialchars($_POST['text']);
    $_POST['text'] = parse_word(html_entity_decode($safehtml->parse($_POST['text'])));
    $text = $_POST['text'];

    if (!$text)
        $control_center->errors[] = "�� �� ����� ����� ����������.";
        
    $metod = intval($_POST['metod']);
    $interval = intval($_POST['interval']);
    if ($interval < 3)
        $control_center->errors[] = "������� �������� �� ����� ���� ����� 3 ������.";
        
    $onetime = intval($_POST['onetime']);
    if (!$onetime)
        $onetime = 20;

    $groups = $_POST['mgroups'];
    $groups_list = array();
    foreach ($groups as $mg)
    {
        $mg = intval($mg);
        foreach($cache_group as $m_group)
        {
            if($m_group['g_id'] == $mg)
            {
                $groups_list[] = $mg;
                break;
            }
        }
    }

    $groups_list = implode(",", $groups_list);
    if (!$groups_list)
        $control_center->errors[] = "�� �� ������� ������.";

	if (!$control_center->errors)
	{   
	    $_SESSION['LB_delivery'] = 0;
	   
        $title_db = $DB->addslashes( $title );
        $text_db = $DB->addslashes( $text );
        $DB->insert("member_id = '{$member_id['user_id']}', date = '{$time}', ip = '{$_IP}', mgr = '{$groups_list}', metod = '{$metod}', title = '{$title_db}', text = '{$text_db}', send_interval = '{$interval}', onetime = '{$onetime}', active_status = '1'", "logs_delivery");
        $delivery_id = $DB->insert_id();
        
        $info = "<font color=orange>��������</font> �� ���� �������������.";
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
         
        $begin = 0;
                
        $count_members = 0;
        while (true)
        {  
            $empty = true;
            
            $DB->prefix = DLE_USER_PREFIX;
            $members_db = $DB->select( "name, user_id, email, mf_options, user_group, logged_ip", "users", "user_group IN (".$groups_list.")", "ORDER BY reg_date ASC LIMIT ".$begin.", ".$onetime);           
            while ( $row = $DB->get_row($members_db) )
            {     
                $empty = false;
                $count_members ++;
                
                $send_text = $text;
                
                $send_text = str_replace( "{user_name}", $row['name'], $send_text );
                $send_text = str_replace( "{user_email}", $row['email'], $send_text );
                $send_text = str_replace( "{user_id}", $row['user_id'], $send_text );
                $send_text = str_replace( "{user_ip}", $row['logged_ip'], $send_text );
                
                if ($metod)
                {
                    $send_title = $DB->addslashes( $title );
                    $send_text = $DB->addslashes( $send_text );
                    send_new_pm($send_title, $row['user_id'], $send_text, $row['email'], $row['name'], $row['mf_options'], 1);
                }
                else
                {
                    mail_sender ($row['email'], $row['name'], $send_text, $title);
                }
            }
            $DB->free($members_db);
            
            if ($empty) break;
            
            $time = time() + ($cache_config['general_time']['conf_value'] * 60);
            $DB->update("m_count = '{$count_members}', date_end = '{$time}'", "logs_delivery", "id = '{$delivery_id}'");
            $check = $DB->one_select( "active_status", "logs_delivery", "id = '{$delivery_id}'" );
                    
            if (!$check['active_status']) break;
                    
            $DB->free($check);
            sleep($interval);
            
            $begin += $onetime; 
        }
        
        $time = time() + ($cache_config['general_time']['conf_value'] * 60);
        $DB->update("active_status = '0', m_count = '{$count_members}', date_end = '{$time}'", "logs_delivery", "id = '{$delivery_id}'");
        header( "Location: {$redirect_url}?do=users&op=delivery" );
        exit();
	}
	else
    {
		$control_center->errors_title = "������!";
        $control_center->message();
    } 
}
elseif (isset($_POST['delivery']) AND $_SESSION['LB_delivery'] == 0)
{
    $control_center->errors[] = "�� �� ��������� ��������� ��������.";
    $control_center->errors_title = "������!";
    $control_center->message();
}

if (!isset($_POST['delivery']))
{
    $_SESSION['LB_delivery'] = 1;
    
    $group_list = "";

    foreach($cache_group as $m_group)
    {
        if ($m_group['g_id'] == 5 OR $m_group['g_id'] == 6)
            continue;
        
        $group_list .= "<input type=\"checkbox\" name=\"mgroups[]\" value=\"".$m_group['g_id']."\" /> ".$m_group['g_title']."<br />";
    }
    
    require LB_MAIN . '/components/scripts/bbcode/bbcode_cc.php';
    
echo <<<HTML

<form  method="post" name="allip" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">��������� ���������� ��������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">����������:</div>
                                            <div>��������! �� ����� �������� ������� ����� <u>�� ��������� �������� ������</u>. ������� �������� ��� ��������� �������� � ������� �������� � ������� ���.</div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                       <div>
                                            <div class="inputCaption">����� ��������:</div>
                                            <div><select name="metod"><option value="0" selected>E-Mail</option><option value="1">��</option></select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left>������:</div></td>
                                            <td width="720" align=left>{$group_list}</textarea></td>
                                            </tr></table>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�������� �������� (���):<br><font class="smalltext">�� ������������� ������������� ��������� �������� ��������, �.�. ��� �������� �� ������ ���� ������� � ������ ��������.</font></div>
                                            <div><input type="text" name="interval" id="interval" value="5" class="inputText" /> <font class="smalltext">���������� 3 �������</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">���������� �� ���:<br><font class="smalltext">�� ������������� ������� ���-�� �� ���� ���, �.�. ��� �������� �� ������ ���� ������� � ������ ��������.</font></div>
                                            <div><input type="text" name="onetime" id="onetime" value="100" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption">���� ��������:</div>
                                            <div><input type="text" name="title" id="title" value="" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left>���������:<br><font class="smalltext">������ �����:
                                            <br>{user_name} - �����/���
                                            <br>{user_id} - ID ������������
                                            <br>{user_ip} - IP ������������
                                            <br>{user_email} - E-Mail �����
                                            <br>{froum_link} - ������ �� ����� 
                                            <br>{forum_name} - �������� ������
                                            </font></div></td>
                                            <td width="720" align=left>{$bbcode_script}{$bbcode}<textarea name="text" class="textarea" id="tf" style="width:720px; height:200px;"></textarea></td>
                                            </tr></table>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="delivery" value="���������" class="btnBlack" />
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

?>