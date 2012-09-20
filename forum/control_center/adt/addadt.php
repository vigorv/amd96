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

$link_speddbar = "<a href=\"".$redirect_url."?do=adt\">����� � �������</a>|����� ����";
$control_center->header("����� � �������", $link_speddbar);
$onl_location = "����� � ������� &raquo; ����� ����";

if (isset($_POST['newblock']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	$title = $DB->addslashes( $safehtml->parse( trim( htmlspecialchars( $_POST['title'] ) ) ) );
	if (!$title OR utf8_strlen($title) > 250)
		$control_center->errors[] = "�� �� ��������� ���� \"��������\" ��� ����� ������ 250 ��������.";

	$description = $DB->addslashes(trim( $_POST['description'] ) );
	if (!$description)
		$control_center->errors[] = "�� �� ��������� ���� \"�����\".";

	$group_access = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['group_access'] )) );
    $forum_id = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['forums'] )) );

    $in_posts = intval($_POST['in_posts']);
    if ($in_posts < 0 OR $in_posts > 4)
        $in_posts = 0;
        
    $active_status = intval($_POST['active_status']);

	unset($safehtml);

	if (!$control_center->errors)
	{
		$DB->insert("title = '{$title}', text = '{$description}', date = '{$time}', forum_id = '{$forum_id}', in_posts = '{$in_posts}', group_access = '{$group_access}', active_status = '{$active_status}'", "adtblock");
        $cache->clear("template", "adtblock");
        
		$info = "<font color=orange>����������</font> ������ ����� ��� �������: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: {$redirect_url}?do=adt" );
        exit();
	}
	else
		$control_center->errors_title = "������!";
}

$forum_list = ForumsList();

$group_list = "<option value=\"0\" selected>��� ������</option>";

foreach($cache_group as $m_group)
{
	$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}

$in_posts = "<option value=\"0\" selected>�� ��������</option>";
$in_posts .= "<option value=\"1\">�������� �������</option>";
$in_posts .= "<option value=\"2\">�������� �� ������</option>";
$in_posts .= "<option value=\"3\">�������� �����</option>";
$in_posts .= "<option value=\"4\">�������� �������, �� ������ � �����</option>";

$control_center->message();

echo <<<HTML
<form  method="post" name="newpage" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">���������� ����� ��� �������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption2">��������:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������:<br><font class="smalltext">���� ��������� ������ ����</font></div>
                                            <div><select name="group_access[]" multiple>{$group_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����� � �������:<br><font class="smalltext">����� ������� ��������� �������.<br>������ �� ���������, ���� ������ ������� ���� �� ���� ���������.</font></div>
                                            <div><select name="forums[]" multiple style="width:620px;height:200px;">{$forum_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����� � �����:</div>
                                            <div><select name="in_posts">{$in_posts}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�����:<br><font class="smalltext">�������� ������������ html ���� � �������</font></div>
                                            <div><textarea name="description" id="description" class="textarea" style="width:620px; height:300px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������������:</div>
                                            <div>
						<div class="radioContainer"><input name="active_status" type="radio" id="active_status_1" value="1" checked></div>
 <label class="radioLabel" for="active_status_1">��</label>
						<div class="radioContainer optionFalse"><input name="active_status" type="radio" id="active_status_0" value="0"></div>
 <label class="radioLabel" for="active_status_0">���</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <input type="submit" name="newblock" value="�������" class="btnBlue" />
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