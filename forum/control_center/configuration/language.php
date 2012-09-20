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

if (isset($_GET['name']))
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
    {
        exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
    }
            
    require LB_CLASS . '/safehtml.php';
    $safehtml = new safehtml( );
    $conf_value = $DB->addslashes($safehtml->parse($_GET['name']));
    
    $DB->update("conf_value = '{$conf_value}'", "configuration", "conf_key = 'language_name'");
    $cache->clear("", "config");
    
    unset($safehtml);
    
    if ($DB->addslashes($cache_config['language_name']['conf_value']) != $conf_value)
    {
        $info = "<br><font color=orange>��������������</font> ����� ������. ���������: ".$DB->addslashes($cache_config['language_name']['conf_value'])." -> ".$conf_value;
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    }
    
    header( "Location: ".$redirect_url."?do=configuration&op=lang" );
    exit();
}

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|����� ������";
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ����� ������";

echo <<<HTML
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����� ������</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>����</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$i = 0;

$temp_main = opendir( LB_MAIN . "/language/" );
while ( false !== ($temp_dir = readdir( $temp_main )) )
{
    if(@is_dir( LB_MAIN . "/language/".$temp_dir ) AND $temp_dir != "." AND $temp_dir != "..")
    {
	   $i ++;
	   if ($i%2)
		  $class = "appLine";
	   else
		  $class = "appLine dark";
          
    if (strtolower($cache_config['language_name']['conf_value']) == strtolower($temp_dir))
        $img_t = "<img src=\"".$redirect_url."template/images/template_true.gif\" alt=\"������������ ����\" />";
    else
        $img_t = "<a href=\"javascript:confirmDelete('".$redirect_url."?do=configuration&op=lang&name=".urlencode($temp_dir)."&secret_key=".$secret_key."', '�� ������������� ������ ������� ���� ���� ��������?')\" title=\"������� ������ ���� ��� ��������.\"><img src=\"".$redirect_url."template/images/template_false.gif\" alt=\"����\" /></a>";

echo <<<HTML

                        <tr class="{$class}">
                            <td align="left" class="blueHeader">{$temp_dir}</a></td>
                            <td align=right>{$img_t}</td>
                        </tr>
HTML;
    }
}
closedir( $temp_main );

if ($i == 0)
{

echo <<<HTML

                        <tr class="appLine">
                            <td align="left" colspan=2><b>�������� ����� �� �������.</b></td>
                        </tr>
HTML;

}

echo <<<HTML
                    </table>
HTML;

?>