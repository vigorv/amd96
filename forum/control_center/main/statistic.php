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

$DB->prefix = DLE_USER_PREFIX;
$users = $DB->one_select( "COUNT(*) as count", "users" );

$DB->status( LB_DB_NAME );
$mysql_size = 0;
while ( $sql = $DB->get_array() )
{
	if( strpos( $sql['Name'], LB_DB_PREFIX . "_" ) !== false )
		$mysql_size += $sql['Data_length'] + $sql['Index_length'];
}
$DB->free();
$mysql_size = formatsize( $mysql_size );

$onl_limit = $time - (intval($cache_config['online_time']['conf_value']) * 60);
$onl = $DB->one_select( "COUNT(*) as count", "members_online", "mo_date > '$onl_limit'" );

$topic = $DB->one_select( "COUNT(*) as count", "topics" );
$post = $DB->one_select( "COUNT(*) as count", "posts" );

$file_log_mysql_size = intval(@filesize(LB_MAIN."/logs/logs_mysql.log"));
if ($file_log_mysql_size > 0) $file_log_mysql_size = round($file_log_mysql_size/1024, 2); // �������� ������ ����� � ��

if ($file_log_mysql_size > 1024 AND $file_log_mysql_size < 1500)
    $file_log_mysql_size = "<a href=\"#\" onclick=\"return false\" title=\"������ ����� ����� ������ � ������������ ������� - 2 ��.\"><font color=orange>".$file_log_mysql_size."</font></a>";
elseif ($file_log_mysql_size >= 1500)
    $file_log_mysql_size = "<a href=\"#\" onclick=\"return false\" title=\"������ ����� ����� ������ � ������������ ������� - 2 ��.\"><font color=red>".$file_log_mysql_size."</font></a>";
else
    $file_log_mysql_size = "<a href=\"#\" onclick=\"return false\" title=\"������������ ������ ����� ����� - 2 ��.\">".$file_log_mysql_size."</a>";
    
$file_log_files_size = intval(@filesize(LB_MAIN."/logs/logs.log"));
if ($file_log_files_size > 0) $file_log_files_size = round($file_log_files_size/1024, 2); // �������� ������ ����� � ��

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����������</div>
                    </div>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">�������������:</td>
                            <td class="appText appText_bottom2"><center><a href="{$redirect_url}?do=users" title="������� � ������ �������������.">{$users['count']}</a></center></td>
                        </tr>
                    </table>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">������ �� ������:</td>
                            <td class="appText appText_bottom2"><center>{$onl['count']}</center></td>
                        </tr>
                    </table>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">���:</td>
                            <td class="appText appText_bottom2"><center>{$topic['count']}</center></td>
                        </tr>
                    </table>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">���������:</td>
                            <td class="appText appText_bottom2"><center>{$post['count']}</center></td>
                        </tr>
                    </table>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">������ ��:</td>
                            <td class="appText appText_bottom2"><center>{$mysql_size}</center></td>
                        </tr>
                    </table>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">���� ����� MySQL ������:</td>
                            <td class="appText appText_bottom2"><center>{$file_log_mysql_size} ��.</center></td>
                        </tr>
                    </table>
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom2" width="300">���� ����� ������ ��������� � ������:</td>
                            <td class="appText appText_bottom2"><center>{$file_log_files_size} ��.</center></td>
                        </tr>
                    </table>

HTML;


?>