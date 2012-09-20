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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|������ User Agent";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������ User Agent";

$control_center->errors = array ();

if (isset($_POST['newrank']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$name = $DB->addslashes( $safehtml->parse( trim( $_POST['name_ua'] ) ) );
	if (utf8_strlen($name) == 0 OR utf8_strlen($name) > 255)
		$control_center->errors[] = "�� �� ����� �������� ��� ����� ������ 255 ��������.";

	$search_ua = $DB->addslashes( $safehtml->parse( trim( $_POST['search_ua'] ) ) );
	if (utf8_strlen($search_ua) == 0 OR utf8_strlen($search_ua) > 255)
		$control_center->errors[] = "�� �� ����� User Agent ��� ����� ������ 255 ��������.";

	if (!$control_center->errors)
	{
		$DB->insert("name = '{$name}', search_ua = '{$search_ua}'", "user_agent");
		$cache->clear("", "user_agent");

		$info = "<font color=green>����������</font> User Agent: ".$name;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
	else
		$control_center->errors_title = "������!";
}

if (isset($_REQUEST['del_ua']) AND $id)
{
	if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$del_rank = $DB->one_select ("*", "user_agent", "id = '{$id}'");
	if ($del_rank['id'])
	{
		$DB->delete("id = '{$id}'", "user_agent");
		$cache->clear("", "user_agent");

		$info = "<font color=red>��������</font> User Agent: ".$DB->addslashes($del_rank['name']);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
        header( "Location: ".$redirect_url."?do=configuration&op=user_agent" );
        exit();
	}
	else
	{
		$control_center->errors[] = "��������� User Agent �� ������ � ���� ������.";
		$control_center->errors_title = "������!";
	}
}

$control_center->message();

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">������ User Agent</div>
                    </div>
		          <table class="colorTable">
                        <tr>
				<td align=left><h6>��������</h6></td>
				<td align=left><h6>User Agent</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$DB->select( "*", "user_agent", "", "ORDER BY name ASC" );
$i = 0;
while ( $row = $DB->get_row() )
{
		$i ++;
		if ($i%2)
			$class = "appLine";
		else
			$class = "appLine dark";

echo <<<HTML
                        <tr class="{$class}">
                        	<td align=left class="blueHeader">{$row['name']}</td>
                        	<td align=left>{$row['search_ua']}</td>
                        	<td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=configuration&op=user_agent&del_ua=yes&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� User Agent?')" title="������� ������ User Agent."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

}

echo <<<HTML
		</table>
HTML;

echo <<<HTML
	            <div class="clear" style="height:10px;"></div>
                <form  method="post" name="ranks" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����� User Agent</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><input type="text" name="name_ua" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">User Agent:</div>
                                            <div><input type="text" name="search_ua" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newrank" value="�������" class="btnBlue" />
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