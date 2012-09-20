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
        $notice = $DB->one_select( "*", "topics_sharelink", "id = '{$id}'" );
        $DB->delete("id = '{$id}'", "topics_sharelink");
        $cache->clear("", "topics_sharelink");
    
        $info = "<font color=red>��������</font> ������� ����������: ".$notice['title'];
        $info = $DB->addslashes( $info );
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    
    }
    
    header( "Location: ".$redirect_url."?do=board&op=sharelink" );
    exit();
}

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|������� ����������";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ������� ����������";

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">������� ����������</div>
                    </div>
		<table class="colorTable">
                        <tr>
                            <td align=left width="30"></td>
				            <td align=left><h6>��������</h6></td>
                            <td align=left><h6>����� �������</h6></td>
                            <td align=center><h6>������</h6></td>
				            <td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$DB->select( "*", "topics_sharelink", "", "ORDER by id ASC" );

$i = 0;

while ( $row = $DB->get_row() )
{
    $i ++;
    
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";
        
    if ($row['active_status'])
        $status = "<font color=green>�������</font>";
    else
        $status = "<font color=red>��������</font>";
        
echo <<<HTML

                        <tr class="{$class}">
                            <td align=left><img src="{$cache_config['general_site']['conf_value']}templates/{$cache_config['template_name']['conf_value']}/images/sharelink/{$row['icon']}.png" /></td>
                            <td align=left class="blueHeader"><a href="{$redirect_url}?do=board&op=sharelink_edit&id={$row['id']}" title="������������� ������ ������.">{$row['title']}</a></td>
                            <td align=left>{$row['link']}</td>
                            <td align=center>{$status}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=board&op=sharelink&act=del&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ������ ������ ����������?')" title="������� ������ ������."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

}
$DB->free();

echo <<<HTML
	        </table>
		 <div class="clear" style="height:10px;"></div>
	        <table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=board&op=sharelink_add" title="�������� ����� ������ ���������� ������."><img src="{$redirect_url}template/images/add.gif" alt="�������� ������ ����������" /></a></td></tr></table>
HTML;

?>