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

$control_center->header("������ �����", "������ �����");
$onl_location = "������ �����";

if(control_center_admins($member_cca['logs']['action']))
{
echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg"><a href="{$redirect_url}?do=logs&op=actions" title="������� � ��������� ������� ����� �������� � ������ ����������.">�������� � ������ ����������</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>������������</h6></td>
				<td align=left><h6>��������</h6></td>
				<td align=center><h6>IP</h6></td>
				<td align=right><h6>����</h6></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_actions_cc log||users u", "log.member_name=u.name", "", "ORDER BY log.date DESC LIMIT 5" );
$i = 0;
while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate( $row['date'] );

echo <<<HTML

                        <tr class="{$class}">
				<td align=left><font class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="������� � �������������� ������� ������������.">{$row['member_name']}</a></font></td>
				<td align=left>{$row['info']}</td>
				<td align=center>{$row['ip']}</td>
				<td align=right>{$row['date']}</td>
                        </tr>
HTML;

}

echo <<<HTML
		</table>
                    <div class="clear" style="height:10px;"></div>
HTML;
}

if(control_center_admins($member_cca['logs']['login']))
{
echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg"><a href="{$redirect_url}?do=logs&op=login" title="������� � ��������� ������� ����� ����������� � ������ ����������.">���� �����������</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>������������</h6></td>
				<td align=center><h6>������</h6></td>
				<td align=center><h6>����������</h6></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name", "LEFT", "logs_login_cc log||users u", "log.member_name=u.name", "", "ORDER BY log.date DESC LIMIT 5" );
$i = 0;
while ( $row = $DB->get_row() )
{
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$info = unserialize($row['info']);
	if ($row['login'])
		$login = "login_true.gif";
	else
		$login = "login_false.gif";

	$row['date'] = formatdate( $row['date'] );

echo <<<HTML
                        <tr class="{$class}">
                              	<td class="appText" align=left width=200><div class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="������� � �������������� ������� ������������.">{$row['member_name']}</a></div><div>{$row['date']}</div></td>
				                <td class="appBtn"><img src="{$redirect_url}template/images/{$login}" alt="������ ����������� � ������ ����������" /></td>
                            	<td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=login&id={$row['id']}','������ �����������','width=500,height=430,toolbar=1,location=0,scrollbars=1'); return false;" title="����������� ��������� ����������."><img src="{$redirect_url}template/images/info_link.gif" alt="���������..." /></a></td>
                        </tr>
HTML;
}

echo <<<HTML
		</table>
		<div class="clear" style="height:10px;"></div>
HTML;
}

if(control_center_admins($member_cca['logs']['files']))
{
    
$file = LB_MAIN . "/logs/logs.log";

echo <<<HTML

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg"><a href="{$redirect_url}?do=logs&op=files" title="������� � ��������� ������� ����� ������ ��������� � ������.">������ ��������� � ������</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>IP</h6></td>
				<td align=right><h6>����</h6></td>
                        </tr>
HTML;

if (file_exists($file))
{
	$i = 0;
	$content = @file_get_contents( $file );
	$content = explode ("|||", $content);
	$content = array_reverse($content);
	foreach ($content as $massive)
	{
		if ($i == 5)
			break;
		$i ++;

		if ($i%2)
			$class = "appLine";
		else
			$class = "appLine dark";

		$mass = unserialize($massive);
		$mass['date'] = formatdate( $mass['date'] );
echo <<<HTML
                        <tr class="{$class}">
				<td align=left width=200><font class="blueHeader"><a href="#" onclick="window.open('{$cache_config['general_site']['conf_value']}control_center/?do=infopage&op=logs&type=files&id={$i}','������ ��������� � �����','width=500,height=510,toolbar=1,location=0,scrollbars=1'); return false;" title="����������� ��������� ����������.">{$mass['ip']}</a></font><br>{$mass['date']}</td>
				<td align=right>{$mass['file']}</td>
                        </tr>
HTML;

	}
}
else
{
echo <<<HTML
                        <tr class="appLine">
				<td align=left><b>���� /logs/logs.log ����.</b></td>
                        </tr>
HTML;
}

echo <<<HTML
		</table>
                    <div class="clear" style="height:10px;"></div>
HTML;
}

if(control_center_admins($member_cca['logs']['mysql']))
{
echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg"><a href="{$redirect_url}?do=logs&op=mysql_errors" title="������� � ��������� ������� ����� MySQL ������.">MySQL ������</a></div>
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

$file = LB_MAIN . "/logs/logs_mysql.log";

if (file_exists($file))
{
	$i = 0;
	$content = @file_get_contents( $file );
	$content = explode ("|==|==|", $content);
	$content = array_reverse($content);
	foreach ($content as $massive)
	{
		if ($i == 5)
			break;
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
}
else
{
echo <<<HTML
                        <tr class="appLine">
				<td align=left><b>���� /logs/logs_mysql.log ����.</b></td>
                        </tr>
HTML;
}

echo <<<HTML
		</table>
HTML;
}

?>