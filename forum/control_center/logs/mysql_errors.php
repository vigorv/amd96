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

$link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|MySQL ������";
$control_center->header("������ �����", $link_speddbar);
$onl_location = "������ ����� &raquo; MySQL ������";

$file = LB_MAIN . "/logs/logs_mysql.log";

if (isset ( $_REQUEST['del'] ))
{
	if (!$_REQUEST['secret_key'] OR $_REQUEST['secret_key'] != $secret_key)
	{
		$control_center->errors = array ();
		$control_center->errors[] = "�������� ��������� ����.";
		$control_center->errors_title = "������.";
		$control_center->message();
	}
    elseif(!control_center_admins($member_cca['logs']['mysqldel']))
    {
        $control_center->errors[] = "� ��� ������������ ����, ����� ������� ���� MySQL ������.";
	    $control_center->errors_title = "������ �����.";
        $control_center->message();
    }
	else
	{
		@unlink($file);
		$info = "<font color=red>��������</font> ����� MySQL ������";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
        header( "Location: {$redirect_url}?do=logs&op=mysql_errors" );
        exit();
	}
}

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">MySQL ������</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>IP</h6></td>
				<td align=left><h6>��������</h6></td>
				<td align=center><h6>����</h6></td>
				<td align=center><h6>� ������</h6></td>
				<td align=center><h6>����������</h6></td>
                        </tr>
HTML;

if (file_exists($file))
{
	$i = 0;
	$content = @file_get_contents( $file );
	$content = explode ("|==|==|", $content);
	$content = array_reverse($content);
	foreach ($content as $massive)
	{
		$i ++;

		if ($i%2)
			$class = "appLine";
		else
			$class = "appLine dark";

		$mass = unserialize($massive);
		$mass['time'] = formatdate( $mass['time'] );
    	$info_user = unserialize($mass['info_user']);
        
       	if( utf8_strlen( $info_user['file'] ) > 60 )
            $info_user['file'] = utf8_substr( $info_user['file'], 0, 60 ) . "...";
echo <<<HTML
                        <tr class="{$class}">
                        	<td align=left class="blueHeader">{$mass['ip']}</td>
                        	<td align=left>{$info_user['file']}</td>
                       	 	<td align=center>{$mass['time']}</td>
                       	 	<td align=center>{$mass['error_number']}</td>
                        	<td align=center><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=mysql_errors&id={$i}','MySQL ������','width=600,height=670,toolbar=1,location=0,scrollbars=1'); return false;" title="����������� ��������� ����������."><img src="{$redirect_url}template/images/info_link.gif" alt="���������..." /></a></td>
                        </tr>
HTML;

	}

echo <<<HTML
		</table>
		<table border=0>
                        <tr>
                        	<td align=right colspan=2 style="padding:5px;"><a href="javascript:confirmDelete('{$redirect_url}?do=logs&op=mysql_errors&del=yes&secret_key={$secret_key}', '�� ������������� ������ ������� ���� MySQL ������?')" title="������� ��� ���� MySQL ������."><img src="{$redirect_url}template/images/delete.gif" alt="������� ����" /></a></td>
                        </tr>
		</table>
HTML;

}
else
{
    
echo <<<HTML
		</table>
HTML;

	$control_center->errors = array ();
	$control_center->errors[] = "���� <b>logs_mysql.log</b> �� ������. �������� ��� ������� ��� �� ������� ������� ����.";
	$control_center->errors_title = "������.";
	$control_center->message();
}

?>