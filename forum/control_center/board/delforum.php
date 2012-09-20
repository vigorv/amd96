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

ignore_user_abort(1);
@set_time_limit(0);

if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
{
	exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
}

$control_center->errors = array ();

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|�������� ���������/������";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; �������� ���������/������";

if ($id)
{
	$del = $DB->one_select ("*", "forums", "id = '{$id}'");
	if ($del['id'])
	{
		if (isset($_POST['delete']))
		{
			$DB->delete("id = '{$id}'", "forums");
            
            $f_rows = $DB->select( "id, hiden, forum_id, poll_id", "topics", "forum_id = '{$id}'");
            $i = 0;
            while ( $row = $DB->get_row($f_rows) )
            {    
                $i ++;
                    
                if ($i >= 100)
                {
                    sleep(2);
                    $i = 0;
                }

                $DB->delete("topic_id = '{$row['id']}'", "posts");
                $DB->delete("id = '{$row['id']}'", "topics");
                if ($row['poll_id'])
                {
                    $DB->delete("id = '{$row['poll_id']}'", "topics_poll");
                    $DB->delete("poll_id = '{$row['poll_id']}'", "topics_poll_logs");
                }
                    
                $del_file_t = $DB->select( "file_id, file_date, file_name", "topics_files", "file_tid = '{$row['id']}'");
                while ( $row2 = $DB->get_row($del_file_t) )
                { 
                    $upload_dir_name = LB_UPLOADS . "/attachment/".date( "Y-m", $row2['file_date'] )."/";
                    @unlink($upload_dir_name.$row2['file_name']);
                }
                $DB->delete("file_tid = '{$row['id']}'", "topics_files");
                $DB->free($del_file_t);
            }
            $DB->free($f_rows);

			$update_id = $del['parent_id'];

			$DB->update("parent_id = '{$update_id}'", "forums", "parent_id = '{$del['id']}'");
			$cache->clear("", "forums");

			$info = "<font color=red>��������</font> ���������/������: ".$DB->addslashes($del['title']);
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
			header( "Location: ".$redirect_url."?do=board" );
            exit();
		}


echo <<<HTML
<form  method="post" name="form_del" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������� ���������/������: {$cache_forums[$id]['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            �� ������������� ������ ������� ������ ���������/�����?<br /><b>��� ���� � ��������� �� ����� ������ ���� ����� �������!</b>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <input type="submit" name="delete" value="�������" class="btnRed" />
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
		$control_center->errors[] = "��������� ��������� ��� ����� �� ������� � ���� ������.";
		$control_center->errors_title = "������!";
		$control_center->message();
	}
}
else
{
	$control_center->errors[] = "�� �� ������� ��������� ��� ����� ��� ��������.";
	$control_center->errors_title = "������!";
	$control_center->message();
}

?>