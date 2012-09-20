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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|������";
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; ������";

$control_center->errors = array ();

if (isset($_POST['newrank']) OR isset($_POST['newrank_mid']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
	if (utf8_strlen($title) < 3 OR utf8_strlen($title) > 255)
		$control_center->errors[] = "����� ������ ������ 3 �������� ��� ������ 255.";

    if (isset($_POST['newrank']))
    {
    	$post_num = intval( $_POST['post_num'] );
    	if ($post_num < 0)
    		$control_center->errors[] = "����������� ���������� ��������� ������ ����.";
    }
    else
        $post_num = 0;
        
    if (isset($_POST['newrank_mid']))
    {
        $mid = $DB->addslashes( $safehtml->parse( trim( $_POST['name_rank'] ) ) );
        $DB->prefix = DLE_USER_PREFIX;
        $check = $DB->one_select ("user_id", "users", "name = '{$mid}'");
        if ($check['user_id'])
        {
            $mid = $check['user_id'];
            $check_2 = $DB->one_select ("id", "members_ranks", "mid = '{$mid}'");
            if ($check_2['id'])
                $control_center->errors[] = "� ���������� ������������ ��� ���� ������ ������.";
        }
        else
            $control_center->errors[] = "��������� ������������ �� ������ � ���� ������.";
    }
    else
        $mid = 0;
        
	if (is_int($_POST['stars']))
		$stars = intval( $_POST['stars'] );
	else
	{
		$stars = $DB->addslashes( $safehtml->parse( trim( $_POST['stars'] ), false, true ) );
        if (strtolower($stars) == "default.png")
            $control_center->errors[] = "��������� ������������� ������� �������� ��� ��������.";
	}

	if (!$stars)
		$control_center->errors[] = "�� ������� ���������� ���� ��� ��� ��������.";

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', post_num = '{$post_num}', stars = '{$stars}', mid = '{$mid}'", "members_ranks");
		$cache->clear("", "ranks");

		$info = "<font color=green>����������</font> ������: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=users&op=ranks" );
        exit();
	}
	else
		$control_center->errors_title = "������!";
}

if (isset($_REQUEST['del_ranks']) AND $id)
{
	if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$del_rank = $DB->one_select ("*", "members_ranks", "id = '{$id}'");
	if ($del_rank['id'])
	{
		$DB->delete("id = '{$id}'", "members_ranks");
		$cache->clear("", "ranks");

		$info = "<font color=red>��������</font> ������: ".$DB->addslashes($del_rank['title']);
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=users&op=ranks" );
        exit();
	}
	else
	{
		$control_center->errors[] = "��������� ������ ������������� �� ������� � ���� ������";
		$control_center->errors_title = "������!";
	}
}

$control_center->message();

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">������</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>��������</h6></td>
				<td align=left><h6>���-�� ���������</h6></td>
				<td align=left><h6>���������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$db_result = $DB->select( "*", "members_ranks", "mid = '0'", "ORDER BY post_num ASC" );

while ( $row = $DB->get_row($db_result) )
{
    $i ++;
    if ($i%2)
        $class = "appLine";
    else
        $class = "appLine dark";

echo <<<HTML
                        <tr class="{$class}">
                        	<td align=left class="blueHeader"><a href="{$redirect_url}?do=users&op=edit_ranks&id={$row['id']}" title="������� � �������������� ������� ������.">{$row['title']}</a></td>
                        	<td align=left>{$row['post_num']}</td>
                        	<td align=left>{$row['stars']}</td>
                        	<td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=users&op=ranks&del_ranks=yes&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ��� ������ �������������?')" title="������� ������ ������."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;


}

echo <<<HTML
		</table>
HTML;

echo <<<HTML

    <div class="clear" style="height:10px;"></div>
    <table><tr><td align=right style="padding-right:10px;"><a href="#" onclick="ShowAndHide('add_rank');return false;" title="�������� ������"><img src="{$redirect_url}template/images/add.gif" alt="�������� ������" /></a></td></tr></table>

    <div id="add_rank" style="display:none;">
    <div class="clear" style="height:10px;"></div>
    
    <form  method="post" name="ranks" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����� ������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">������:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">����������� ���-�� ���������:</div>
                                            <div><input type="text" name="post_num" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">���������� ���� ��� �������� �����������:<br><font class="smalltext">����������� ������ ���� � ����� templates/������/ranks</font></div>
                                            <div><input type="text" name="stars" class="inputText" /></div>
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
    </div>
HTML;

##########################c

echo <<<HTML

 <div class="clear" style="height:15px;"></div>
                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">������ ������</div>
                    </div>
		<table class="colorTable">
                        <tr>
                <td align=left><h6>�����</h6></td>
				<td align=left><h6>��������</h6></td>
				<td align=left><h6>���������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$db_result = $DB->join_select( "mr.*, u.name", "LEFT", "members_ranks mr||users u", "mr.mid = u.user_id", "mr.mid > '0'", "ORDER BY mr.mid ASC" );

while ( $row = $DB->get_row($db_result) )
{
    $i ++;
    if ($i%2) $class = "appLine";
    else $class = "appLine dark";

echo <<<HTML
                        <tr class="{$class}">
                            <td align=left class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['mid']}" title="������� � �������������� ������� ������.">{$row['name']}</a></td>
                        	<td align=left><a href="{$redirect_url}?do=users&op=edit_ranks&id={$row['id']}" title="������� � �������������� ������� ������.">{$row['title']}</a></td>
                        	<td align=left>{$row['stars']}</td>
                        	<td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=users&op=ranks&del_ranks=yes&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ��� ������ �������������?')" title="������� ������ ������."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;


}

echo <<<HTML
		</table>
HTML;

echo <<<HTML

    <div class="clear" style="height:10px;"></div>
    <table><tr><td align=right style="padding-right:10px;"><a href="#" onclick="ShowAndHide('add_rank_mid');return false;" title="�������� ������ ������"><img src="{$redirect_url}template/images/add.gif" alt="�������� ������ ������" /></a></td></tr></table>


    <div id="add_rank_mid" style="display:none;">
        <div class="clear" style="height:10px;"></div>
        <form  method="post" name="ranks_mid" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����� ������ ������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">������������:<br><font class="smalltext">������� �����</font></div>
                                            <div><input type="text" name="name_rank" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                       <div>
                                            <div class="inputCaption">������ ������:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">���������� ���� ��� �������� �����������:<br><font class="smalltext">����������� ������ ���� � ����� templates/������/ranks</font></div>
                                            <div><input type="text" name="stars" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newrank_mid" value="�������" class="btnBlue" />
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
    </div>
HTML;

?>