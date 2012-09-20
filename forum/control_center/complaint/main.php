<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if (isset($_GET['id']) AND !isset($_GET['stop']))
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	   exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
       
    $log_d = $DB->one_select( "*", "complaint", "id = '{$id}'" );
    $DB->delete("id = '{$id}'", "complaint");
    
    if ($log_d['module'] == "post")
        $info = "<font color=red>��������</font> ������ �� ���������: ".$log_d['cid'];
        
    $info = $DB->addslashes( $info );
    $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

    header( "Location: {$redirect_url}?do=complaint" );
    exit();
}

$control_center->header("������", "������");
$onl_location = "������";

$log_result = 20;

if (isset ( $_REQUEST['page'] ))
	$page = intval ( $_GET['page'] );
else
	$page = 0;

if ($page < 0)
	$page = 0;

if ($page)
{
	$page = $page - 1;
	$page = $page * $log_result;
}

$i = $page;

$link_nav = $redirect_url."?do=complaint&page=";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">������ �� ���������</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>�����</h6></td>
				<td align=left><h6>����� ������</h6></td>
                <td align=center><h6>����</h6></td>
                <td align=center><h6>����������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$db_result = $DB->join_select( "c.*, u.name", "LEFT", "complaint c||users u", "c.mid = u.user_id", "", "ORDER BY c.date DESC LIMIT ".$page.", ".$log_result );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2) $class = "appLine";
	else $class = "appLine dark";
        
	$row['date'] = formatdate($row['date']);
    
echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['mid']}" title="������� � �������������� ������� ������������.">{$row['name']}</a></td>
                            <td align="left">{$row['info']}</td>
                            <td align="center">{$row['date']}</td>
                            <td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=complaint&id={$row['id']}','������ ������','width=500,height=430,toolbar=1,location=0,scrollbars=1'); return false;" title="����������� ��������� ����������."><img src="{$redirect_url}template/images/info_link.gif" alt="���������..." /></a></td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=complaint&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ������ ������?')" title="������� ������ ������."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

    unset($outblock);
}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=6><b>������� ������ �� �������.</b></td>
                        </tr>
HTML;

}

echo <<<HTML

                    </table>
HTML;

if ($i > 0)
{
	$nav = $DB->one_select( "COUNT(*) as count", "complaint c");
	$nav_all = $nav['count'];
	$DB->free($nav);
	if ($nav_all > $log_result)
	{
		include LB_CLASS.'/navigation.php';
		$navigation = new navigation;
		$navigation->creat($page, $nav_all, $log_result, $link_nav, "7");

echo <<<HTML
<table>
<tr><td align=center style="padding:8px;"><h6>{$navigation->result}</6></td></tr>
</table>
HTML;
		unset($navigation);
	}
}

?>