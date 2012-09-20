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

$editgroup = $DB->one_select( "*", "configuration_group", "conf_gr_id = '{$id}'" );

$control_center->errors = array ();

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|<a href=\"?do=configuration&op=show&id=".$id."\">������: ".$editgroup['conf_gr_name']."</a>|��������������";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������: ".$editgroup['conf_gr_name']." &raquo; ��������������";

if ($editgroup['conf_gr_id'])
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );

	if (isset($_POST['newgroup']))
	{
		$gr_name = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_name'] ) ) );
		if (utf8_strlen($gr_name) < 3)
			$control_center->errors[] = "����� �������� ����� ������ ������ 3 ��������.";

		$gr_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_desc'] ), true ) );
		$gr_group = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_group'] ) ) );
		$gr_prefix = $DB->addslashes( $safehtml->parse( trim( $_POST['gr_prefix'] ), false, true ) );

		$creat_name = $DB->addslashes( $safehtml->parse( trim( $_POST['creat_name'] ) ) );
		$creat_key = $DB->addslashes( $safehtml->parse( trim( totranslit ($_POST['creat_key'] ) ), false, true ) );

		if ($creat_name OR $creat_key)
		{
			if (utf8_strlen($creat_name) < 3 OR utf8_strlen($creat_name) > 30)
				$control_center->errors[] = "����� �������� ����� �������� ������ 3 �������� ��� ������ 30.";

			if (utf8_strlen($creat_key) < 3 OR utf8_strlen($creat_key) > 30)
				$control_center->errors[] = "����� ����� ����� �������� ������ 3 �������� ��� ������ 30.";

			$gr_group = $creat_name;
			$gr_key = $creat_key;
		}
		else
		{
			$checking = $DB->one_select ("*", "configuration_group", "conf_gr_group = '{$gr_group}'");

			if (!$checking['conf_gr_id'])
				$control_center->errors[] = "��������� ��������� �� �������.";

			$gr_group = $checking['conf_gr_group_title'];
			$gr_key = $checking['conf_gr_group'];
		}
        
        $checking2 = $DB->one_select ("conf_gr_id", "configuration_group", "conf_gr_prefix = '{$gr_prefix}' AND conf_gr_id <> '{$id}'");

        if ($checking2['conf_gr_id'])
            $control_center->errors[] = "������ � ����� �� ��������� ��� ����.";

		if (!$control_center->errors)
		{
			$DB->update("conf_gr_name = '{$gr_name}', conf_gr_desc = '{$gr_desc}', conf_gr_prefix = '{$gr_prefix}', conf_gr_group = '{$gr_key}', conf_gr_group_title = '{$gr_group}'", "configuration_group", "conf_gr_id='{$id}'");

			$dop_info = "";
			if ($gr_name != $editgroup['conf_gr_name'])
				$dop_info .= "<br>�������� �������� ������ ��������: ".$editgroup['conf_gr_name']." -> ".$gr_name;

			$info = "<font color=orange>��������������</font> ������ ��������: ".$gr_name.$dop_info;
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

			header( "Location: {$_SESSION['back_link_conf']}" );
            exit();
		}
		else
			$control_center->errors_title = "������!";
	}

	$DB->select( "*", "configuration_group", "", "GROUP BY conf_gr_group" );
	$conf_group = array ();

	$group_list = "";

	while ( $row = $DB->get_row() )
	{
		if ($row['conf_gr_group'] == $editgroup['conf_gr_group'])
			$group_list .= "<option value=\"".$row['conf_gr_group']."\" selected>".$row['conf_gr_group_title']."</option>";
		else
			$group_list .= "<option value=\"".$row['conf_gr_group']."\">".$row['conf_gr_group_title']."</option>";
	}
	$DB->free();

	$control_center->message();

	$editgroup['conf_gr_desc'] = str_replace("<br />", "\n", $editgroup['conf_gr_desc']);
	$editgroup['conf_gr_name'] = htmlspecialchars( $editgroup['conf_gr_name'] );

echo <<<HTML

<form  method="post" name="newgroup" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������������� ������ ���������: {$editgroup['conf_gr_name']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><input type="text" name="gr_name" value="{$editgroup['conf_gr_name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><textarea name="gr_desc" style="width:300px;height:100px;">{$editgroup['conf_gr_desc']}</textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�������:</div>
                                            <div><input type="text" name="gr_prefix" value="{$editgroup['conf_gr_prefix']}" class="inputText" /> <font class="smalltext">�� ������� �������� ����, ���� �� ������ ��� ���� ��� �����</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><select name="gr_group">{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">����� �������� (��������):</div>
                                            <div><input type="text" name="creat_name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">����� �������� (����):</div>
                                            <div><input type="text" name="creat_key" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="newgroup" value="���������" class="btnBlack" />
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

	unset ($safehtml);

}
else
{
	$control_center->errors_title = "�� �������!";
	$control_center->errors[] = "��������� ������ �� �������.";
	$control_center->message();
}

?>