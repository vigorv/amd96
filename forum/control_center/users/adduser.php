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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|����������� ������������";
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; ����������� ������������";

if (isset($_POST['newuser']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$password1 = $DB->addslashes( $safehtml->parse( trim( $_POST['password1'] ) ) );
	$password2 = $DB->addslashes( $safehtml->parse( trim( $_POST['password2'] ) ) );
	$name = $DB->addslashes( $safehtml->parse( trim( $_POST['name'] ) ) );
	$email = $DB->addslashes( $safehtml->parse( trim( strtolower( $_POST['email'] ) ) ) );
	$group = intval( $_POST['member_group'] );
	
	$ch_name = strtolower( $name );

    $DB->prefix = DLE_USER_PREFIX;
	$checking = $DB->one_select ("COUNT(*) as count", "users", "LOWER(name) = '{$name}' OR LOWER(email) = '{$email}'");	

	if ($checking['count'])
		$control_center->errors[] = "������������ � ����� ������� ��� E-mail ��� ���������������.";

	if ($password1 != $password2)
		$control_center->errors[] = "������ �� ���������.";

	if (utf8_strlen($password1) < 6)
		$control_center->errors[] = "����� ������ ������ 6 ��������.";

	if (utf8_strlen($name) < 2)
		$control_center->errors[] = "����� ������ ������ 2 ��������.";

	if(utf8_strlen($name) > 22)
		$control_center->errors[] = "����� ������ ������ 22 ��������.";

	if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $name ) )
		$control_center->errors[] = "����� �������� ����������� �������.";

	if( !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $email) or empty( $email ) )
		$control_center->errors[] = "�� �� ��������� ���� E-mail ��� ��������� �� �����.";

	if ($email AND utf8_strlen($email) > 50)
		$control_center->errors[] = "���� E-mail �������� ������������ ���������� ��������.";

	$found_group = false;
	foreach($cache_group as $m_group)
	{
		if ($m_group['g_id'] == $group)
		{
			$found_group = true;
			break;
		}
	}

	if (!$found_group)
		$control_center->errors[] = "��������� ������ ������������� ������������.";

	$name_filter = LB_filters("name", $name);
	if ($name_filter)
		$control_center->errors[] = "����� �������� ����������� ���������� ��������.";

	$email_filter = LB_filters("email", $email);
	if ($email_filter)
		$control_center->errors[] = "E-mail �������� ����������� ���������� ��������.";

	$personal_title = $DB->addslashes( $safehtml->parse( trim( $_POST['personal_title'] ) ) );

	if (!$control_center->errors)
	{
        $password1 = md5(md5($password1));
		$member_sk = md5(md5($password1.time().$_IP));
        
        $DB->prefix = DLE_USER_PREFIX;
		$DB->insert("name = '{$name}', password = '{$password1}', secret_key = '{$member_sk}', email = '{$email}', user_group = '{$group}', lastdate = '{$time}', reg_date = '{$time}', personal_title = '{$personal_title}'", "users");
        
        $cache_stats_users['users'] += 1;
        $cache_stats_users['last_name'] = $name;
        $cache_stats_users['last_id'] = $DB->insert_id();
        $cache->update("stats_users", $cache_stats_users, "statistics");
        
		$info = "<font color=green>����������</font> ������������: ".$name;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
                
        if ($cache_group[$group]['g_access_cc'])
        {
            $_SESSION['cca_users'] = 1;
            $_SESSION['cca_users_name'] = $name; 
        }
        
		header( "Location: {$redirect_url}?do=users" );
        exit();
	}
	else
		$control_center->errors_title = "������!";

	unset ($safehtml);
}

$group_list = "";

foreach($cache_group as $m_group)
{
	if ($m_group['g_id'] == 4)
		$group_list .= "<option value=\"".$m_group['g_id']."\" selected>".$m_group['g_title']."</option>";
	else
		$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}
$DB->free();

$control_center->message();

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">���������� ������������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">�����:</div>
                                            <div><input type="text" name="name" id="name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������:</div>
                                            <div><input type="text" name="password1" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������������� ������:</div>
                                            <div><input type="text" name="password2" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�����:</div>
                                            <div><input type="text" name="email" id="email" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������:</div>
                                            <div><select name="member_group">{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������ ������:</div>
                                            <div><input type="text" name="personal_title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newuser" value="��������"  class="btnGreen" />
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