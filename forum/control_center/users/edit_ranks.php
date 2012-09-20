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

$editrank = $DB->one_select( "*", "members_ranks", "id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=ranks\">������</a>|��������������: ".$editrank['title'];
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; ������ &raquo; ��������������: ".$editrank['title'];

$control_center->errors = array ();

if ($editrank['id'])
{
	if (isset($_POST['editrank']))
	{
		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$safehtml->protocolFiltering = "black";

		$control_center->errors = array ();

		$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
		if (utf8_strlen($title) < 3 OR utf8_strlen($title) > 255)
			$control_center->errors[] = "����� ������ ������ 3 �������� ��� ������ 255.";

        if (!$editrank['mid'])
        {
    		$post_num = intval( $_POST['post_num'] );
    		if ($post_num < 0)
    			$control_center->errors[] = "����������� ���������� ��������� ������ ����.";
        }
        else
            $post_num = 0;
            
        if ($editrank['mid'])
        {
            $mid = $DB->addslashes( $safehtml->parse( trim( $_POST['name_rank'] ) ) );
            $DB->prefix = DLE_USER_PREFIX;
            $check = $DB->one_select ("user_id", "users", "name = '{$mid}'");
            if ($check['user_id'])
            {
                $mid = $check['user_id'];
                $check_2 = $DB->one_select ("id", "members_ranks", "mid = '{$mid}' AND id <> '{$id}'");
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
			$DB->update("title = '{$title}', post_num = '{$post_num}', stars = '{$stars}', mid = '{$mid}'", "members_ranks", "id='{$id}'");

			$dop_info = "";
			if ($title != $editrank['title'])
				$dop_info .= "<br>�������� �������� ������: ".$editrank['title']." -> ".$title;

			$cache->clear("", "ranks");

			$info = "<font color=orange>��������������</font> ������ �������������: ".$title.$dop_info;
            $info = $DB->addslashes($info);
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: ".$redirect_url."?do=users&op=ranks" );
            exit();
		}
		else
			$control_center->errors_title = "������!";
	}

	$control_center->message();

	$editrank['title'] = htmlspecialchars($editrank['title']);
    
    if ($editrank['mid'])
    {
        $DB->prefix = DLE_USER_PREFIX;
        $check = $DB->one_select ("name", "users", "user_id = '{$editrank['mid']}'");
    }

echo <<<HTML
<form  method="post" name="editrank" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������������� ������: {$editrank['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>                       
HTML;

if ($editrank['mid'])
{
echo <<<HTML

                                        <div>
                                            <div class="inputCaption">������������:<br><font class="smalltext">������� �����</font></div>
                                            <div><input type="text" name="name_rank" value="{$check['name']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
HTML;
}
echo <<<HTML

                                       <div>
                                            <div class="inputCaption">������:</div>
                                            <div><input type="text" name="title" value="{$editrank['title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        
                                        
HTML;

if (!$editrank['mid'])
{
echo <<<HTML
                                        <div>
                                            <div class="inputCaption">����������� ���-�� ���������:</div>
                                            <div><input type="text" name="post_num" value="{$editrank['post_num']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
HTML;
}
echo <<<HTML
                                        <div>
                                            <div class="inputCaption">���������� ���� ��� �������� �����������:<br><font class="smalltext">����������� ������ ���� � ����� templates/������/ranks</font></div>
                                            <div><input type="text" name="stars" value="{$editrank['stars']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="editrank" value="���������" class="btnBlack" />
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

	unset($safehtml);

}
else
{
	$control_center->errors_title = "�� �������!";
	$control_center->errors[] = "��������� ���� �� ������ � ���� ������.";
	$control_center->message();
}

?>