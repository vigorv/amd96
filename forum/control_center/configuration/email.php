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

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|������� E-mail �����������";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������� E-mail �����������";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">������� E-mail �����������</div>
                    </div>
		<table class="colorTable">
                        <tr>
                <td align=left><h6>ID</h6></td>
				<td align=left><h6>��������</h6></td>
				<td align=center><h6>���� ��������</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$DB->select( "*", "templates_email", "", "ORDER BY date DESC" );

while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate($row['date']);
    
    if ($row['protect'])
        $protect = " ������ �������.";
    else
        $protect = "";

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" width="20"><h5>{$row['id']}</h5></td>
                            <td align="left" class="blueHeader"><a href="{$redirect_url}?do=configuration&op=email_edit&id={$row['id']}" title="������� � �������������� ������� E-Mail �������.">{$row['title']}</a></td>
                            <td align="center">{$row['date']}</td>
                            <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=configuration&op=email_del&id={$row['id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ���� ������?')" title="������� ������ E-Mail ������.{$protect}"><img src="{$redirect_url}template/images/delete.gif" alt="�������" /></a></td>
                        </tr>
HTML;

}
$DB->free();

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=4><b>�������� ������� �� �������.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    </table>
		<div class="clear" style="height:10px;"></div>
		<table><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=configuration&op=email_add" title="�������� ����� E-Mail ������."><img src="{$redirect_url}template/images/mail_template_add.gif" alt="�������� ������ E-mail �����������" /></a></td></tr></table>
HTML;

?>