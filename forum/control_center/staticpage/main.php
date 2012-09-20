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

$control_center->header("����������� ��������", "����������� ��������");
$onl_location = "����������� ��������";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����������� ��������</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>��������</h6></td>
				<td align=center><h6>���� ��������</h6></td>
				<td align=center><h6>����������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "staticpage", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate($row['date']);

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left"><font class="blueHeader"><a href="{$redirect_url}?do=staticpage&op=edit&id={$row['id']}" title="������������� ������ ��������.">{$row['name']}</a></font><br /><font class="smalltext">{$cache_config['general_site']['conf_value']}?do=staticpage&name={$row['title']}</font></td>
                            <td align="center">{$row['date']}</td>
                            <td align="center">{$row['views']}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=staticpage&op=del&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ��� ��������?')" title="������� ������ ��������."><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=4><b>�� ����� �������� �� �������.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=staticpage&op=add" title="�������� ����� ����������� ��������."><img src="{$redirect_url}template/images/page_add.gif" alt="�������� ��������" /></a></td></tr></table>
HTML;

?>