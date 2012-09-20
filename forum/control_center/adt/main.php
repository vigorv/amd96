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

if (isset($_GET['id']) AND !isset($_GET['stop']))
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	   exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
       
    $log_d = $DB->one_select( "*", "adtblock", "id = '{$id}'" );
    $DB->delete("id = '{$id}'", "adtblock");
    
    $cache->clear("template", "adtblock");
    
    $info = "<font color=red>��������</font> ����� ��� �������: ".$log_d['title'];
    $info = $DB->addslashes( $info );
    $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

    header( "Location: {$redirect_url}?do=adt" );
    exit();
}

$control_center->header("����� � �������", "����� � �������");
$onl_location = "����� � �������";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����� � �������</div>
                    </div>
		<table class="colorTable">
                        <tr>
                <td align=left width="40"><h6>���</h6></td>
				<td align=left><h6>��������</h6></td>
				<td align=center><h6>���� ��������</h6></td>
                <td align=left><h6>��� ��������</h6></td>
				<td align=center><h6>������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "adtblock", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";
        
    if ($row['active_status'] == 1)
		$status = "<font color=green>�������</font>";
    else
		$status = "<font color=red>��������</font>";

	$row['date'] = formatdate($row['date']);
    
    $outblock = array();
    
    if ($row['forum_id'])
        $outblock[] = "� ������� ID: ".$row['forum_id'];
        
    if ($row['in_posts'])
    {
        if ($row['in_posts'] == 1)
            $outblock[] = "� �����: �������� �������";
        if ($row['in_posts'] == 2)
            $outblock[] = "� �����: �������� �� ������";
        if ($row['in_posts'] == 3)
            $outblock[] = "� �����: �������� �����";
        if ($row['in_posts'] == 4)
            $outblock[] = "� �����: �������� �������, �� ������ � �����";
    }
    
    if (!$row['forum_id'] AND !$row['in_posts'])
        $outblock[] = "�� ���� ���������";
    
    $outblock = implode ("<br />", $outblock );
    
echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" width="40">{adt_{$row['id']}}</td>
                            <td align="left" class="blueHeader"><a href="{$redirect_url}?do=adt&op=edit&id={$row['id']}" title="������������� ������ ����.">{$row['title']}</a></td>
                            <td align="center">{$row['date']}</td>
                            <td align="left">{$outblock }</td>
                            <td align="center">{$status}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=adt&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ������ ����?')" title="������� ������ ����."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

    unset($outblock);
}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=6><b>�� ������ ����� ��� ������� �� �������.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    <tr><td align=left colspan=6>
                        <br><font class="smalltext">���� "���" �������� ��� ��� ������� global.tpl<br />��� ����� ��������� � ������ ������, ���� ������� ����� �� ���� ���������, � ������ ������� ���� ����� ���������� ������������� � ��������� �����.</font>
                    </td></tr>
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=adt&op=add" title="�������� ����� ���� ��� �������."><img src="{$redirect_url}template/images/page_add.gif" alt="�������� ���� ��� �������" /></a></td></tr></table>
HTML;

?>