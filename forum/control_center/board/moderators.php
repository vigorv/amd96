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

if (isset($_GET['act']) AND $_GET['act'] == "del")
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	   exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
       
    if(control_center_admins($member_cca['board']['moders_del']))
    {
        $moder = $DB->one_select( "*", "forums_moderator", "fm_id = '{$id}'" );
        $DB->delete("fm_id = '{$id}'", "forums_moderator");
        $cache->clear("", "forums_moder");
        
        if(!$moder['fm_is_group'])
            $info = "<font color=red>��������</font> ���������� <b>".$moder['fm_member_name']."</b> ������: ".$cache_forums[$moder['fm_forum_id']]['title'];
        else
            $info = "<font color=red>��������</font> ������ ����������� ".$cache_group[$fm_group_id]['g_title']." ������: ".$cache_forums[$moder['fm_forum_id']]['title'];
        
        $info = $DB->addslashes($info);

        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    }
    
    header( "Location: ".$redirect_url."?do=board&op=moderators" );
    exit();
}

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|����������";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ����������";

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">������</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>�����</h6></td>
				<td align=left><h6>�����</h6></td>
				<td align=left><h6>������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$DB->join_select( "*", "LEFT", "forums_moderator fm||forums f", "fm.fm_forum_id=f.id", "", "ORDER by fm.fm_forum_id ASC" );

$i = 0;

while ( $row = $DB->get_row() )
{
    $i ++;
    
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";
    
    if ($row['fm_member_id'])
        $user = "<a href=\"".$redirect_url."?do=users&op=edituser&id=".$row['fm_member_id']."\" title=\"������� � �������������� ������� ������������.\">".$row['fm_member_name']."</a>";
    else
        $user = "";
    
echo <<<HTML

                        <tr class="{$class}">
                            <td align=left class="blueHeader"><a href="{$redirect_url}?do=board&id={$row['fm_forum_id']}" title="������� � ������.">{$row['title']}</a></td>
			                 <td align=left class="blueHeader">{$user}</td>
			                 <td align=left>{$cache_group[$row['fm_group_id']]['g_prefix_st']}{$cache_group[$row['fm_group_id']]['g_title']}{$cache_group[$row['fm_group_id']]['g_prefix_end']}</td>
			                 <td align=right><a href="{$redirect_url}?do=board&op=moder_edit&id={$row['fm_id']}" title="������������� ������� ����������."><img src="{$redirect_url}template/images/edit_moder.gif" alt="�������������" /></a><a href="javascript:confirmDelete('{$redirect_url}?do=board&op=moderators&act=del&id={$row['fm_id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ����������?')" title="������� ������� ����������."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

}
$DB->free();

echo <<<HTML
	        </table>
		 <div class="clear" style="height:10px;"></div>
	        <table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=board&op=moder_add" title="�������� ������ ����������."><img src="{$redirect_url}template/images/add.gif" alt="�������� ����������" /></a></td></tr></table>
HTML;

?>