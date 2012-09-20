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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|������������";
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; ������������";

$control_center->errors = array ();

$search_tables = array();
$search_tables['logs_actions_cc'] = "member_name"; // ���� �������� � ��
$search_tables['logs_blocking'] = "moder_name"; // ���� ���������� �������������
$search_tables['logs_login_cc'] = "member_name"; // ���� ����������� � ��
$search_tables['users'] = "name"; // ���� �������������
$search_tables['topics_poll_logs'] = "member_name"; // ���� �����������
$search_tables['posts'] = "post_member_name"; // ����� �� ������

$result_mass = array();

if (isset($_POST['allip']) OR isset($_GET['sname']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$sname = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['sname'] ) ) );
	if (utf8_strlen($sname) < 2)
		$control_center->errors[] = "����� ������ ������ 2 ��������.";

	if (!$control_center->errors)
	{
        require LB_CLASS . '/sql_search.php';
        $sql_search = new SQL_Search;
        
        foreach ($search_tables as $key => $value)
        {
            $where = $sql_search->simple ($value, $sname);
            $result_mass[$key] = array();
            
            if ($key == "users")
            {
                $row_out = "logged_ip";
                $DB->prefix = DLE_USER_PREFIX;
            }
            else
                $row_out = "ip";
            
            $DB->select( "COUNT(".$value.") as count, ".$row_out, $key, $where, "GROUP BY ".$row_out );
            while ( $row = $DB->get_row() )
            {
                $result_mass[$key][] = $row;
            }
            $DB->free();
            
            if (!count($result_mass[$key]))
                unset ($result_mass[$key]);
        }
        
        unset ($sql_search);
	}
	else
		$control_center->errors_title = "������!";
}
elseif (isset($_POST['infoip']) OR isset($_GET['ip']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$sip = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['ip'] ) ) );
	if (utf8_strlen($sip) < 2)
		$control_center->errors[] = "����� IP ������ ������ 2 ��������.";

	if (!$control_center->errors)
	{
        require LB_CLASS . '/sql_search.php';
        $sql_search = new SQL_Search;
        
        foreach ($search_tables as $key => $value)
        {
            if ($key == "users")
            {
                $row_out = "logged_ip";
                $DB->prefix = DLE_USER_PREFIX;
            }
            else
                $row_out = "ip";
                
            $where = $sql_search->regexp_ip($sip, $row_out);
            $result_mass[$key] = array();
            
            $DB->select( "COUNT(".$value.") as count, ".$value, $key, $where, "GROUP BY ".$value );
            while ( $row = $DB->get_row() )
            {
                $result_mass[$key][] = $row;
            }
            $DB->free();
            
            if (!count($result_mass[$key]))
                unset ($result_mass[$key]);
        }
        
        unset ($sql_search);
	}
	else
		$control_center->errors_title = "������!";
}

$control_center->message();

if (!count($result_mass) AND !isset($_POST['allip']) AND !isset($_POST['infoip']))
{
    
echo <<<HTML

<form  method="post" name="allip" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">����� ���� IP ������� � ������������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">����� ������������:</div>
                                            <div><input type="text" name="sname" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="allip" value="�����" class="btnYellow" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</form>

	            <div class="clear" style="height:10px;"></div>
<form  method="post" name="infoip" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">���������� �� IP ������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">������� IP �����:</div>
                                            <div><input type="text" name="ip" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
                                            <input type="submit" name="infoip" value="�����" class="btnYellow" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</form>

HTML;
}
elseif (!count($result_mass) AND (isset($_POST['allip']) OR isset($_GET['sname']) OR isset($_POST['infoip']) OR isset($_GET['ip'])) AND !$control_center->errors)
{
    $control_center->errors[] = "����� �� ��� �����������.";
    $control_center->errors_title = "������!";
    $control_center->message(); 
}
else
{        
    $j = 0;
    foreach ($result_mass as $key => $values)
    {
        $j ++;
                
        if ($j%2)
            $class_main = "Blue";
        else
            $class_main = "Gray";
        
        if ($key == "logs_actions_cc")
            $title = "������ ����� �������� � ��";
        elseif ($key == "logs_blocking")
            $title = "������ ����� ���������� �������������";
        elseif ($key == "logs_login_cc")
            $title = "������ ����� ����������� � ��";
        elseif ($key == "users")
            $title = "������� ������";
        elseif ($key == "topics_poll_logs")
            $title = "������ ����� ����������� � �����";
        elseif ($key == "posts")
            $title = "��������� �� ������";
            
echo <<<HTML
                    <div class="header{$class_main}">
                        <div class="header{$class_main}L"></div>
                        <div class="header{$class_main}R"></div>
                        <div class="header{$class_main}Bg">{$title}</div>
                    </div>
                    
HTML;
        if (isset($_POST['allip']) OR isset($_GET['sname']))
        {
echo <<<HTML

		          <table class="colorTable">
                        <tr>
				            <td align=left><h6>IP</h6></td>
				            <td align=right><h6>���-��</h6></td>
                        </tr>
HTML;
            if ($key == "users")
                $row_out = "logged_ip";
            else
                $row_out = "ip";
                
            $link = "ip";
        }
        else
        {
echo <<<HTML

		          <table class="colorTable">
                        <tr>
				            <td align=left><h6>�����</h6></td>
				            <td align=right><h6>���-��</h6></td>
                        </tr>
HTML;

            $row_out = $search_tables[$key];    
            $link = "sname";
        }
        
        
        $i = 0;
        
        foreach ($values as $row)
        {

	       $i++;

	       if ($i%2)
		      $class = "appLine";
	       else
		      $class = "appLine dark";
              
           if ($row[$row_out] != "")
                $row[$row_out] = "<a href=\"".$redirect_url."?do=users&op=tools&".$link."=".urlencode($row[$row_out])."\" title=\"����� ��� ����������.\">{$row[$row_out]}</a>";
            else
                $row[$row_out] = "<i>�����</i>";

echo <<<HTML

                        <tr class="{$class}">
				            <td align=left><font class="blueHeader">{$row[$row_out]}</font></td>
				            <td align=right>{$row['count']}</td>
                        </tr>

HTML;
        }

echo <<<HTML
		</table>

	        <div class="clear" style="height:10px;"></div>
HTML;
        
    }
}

?>