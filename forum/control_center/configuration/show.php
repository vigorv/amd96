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

$_SESSION['back_link_conf'] = $_SERVER['REQUEST_URI'];

$group = $DB->one_select( "*", "configuration_group", "conf_gr_id = '{$id}'" );
$control_center->errors = array ();

$link_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a>|������: ".$group['conf_gr_name'];
$control_center->header("���������", $link_speddbar);
$onl_location = "��������� &raquo; ������: ".$group['conf_gr_name'];

if ($group['conf_gr_id'])
{
	$DB->select( "*", "configuration", "conf_group = '{$id}'", "ORDER BY conf_posi ASC" );

	$conf_group = array ();
	$i = 0;
	while ( $row = $DB->get_row() )
	{
		$i ++;
		$conf_group[$row['conf_id']] = array ();
		foreach ($row as $key => $value)
			$conf_group[$row['conf_id']][$key] = $value;
	}
	$DB->free();


	if (isset($_POST['config']))
	{
		require_once LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );

		foreach ($conf_group as $conf_menu)
		{
            if (is_array($_POST[$conf_menu['conf_key']]))
                $conf_value = $DB->addslashes( $safehtml->parse( trim( implode(",", $_POST[$conf_menu['conf_key']]) ) ) );
            else
                $conf_value = $DB->addslashes( $safehtml->parse( trim( $_POST[$conf_menu['conf_key']] ) ) );
			$DB->update("conf_value = '{$conf_value}'", "configuration", "conf_id='{$conf_menu['conf_id']}'");
		}
		$cache->clear("", "config");

		header( "Location: {$_SERVER['REQUEST_URI']}" );
        exit();
	}

	$conf_type = array();

echo <<<HTML

                   <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">{$group['conf_gr_name']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
					<table class="colorTable">

<form  method="post" name="configform" action="">
HTML;

	if ($i)
	{
		$j = 0;

		foreach ($conf_group as $conf_menu)
		{

			$j ++;
			if ($j%2)
				$class = "appLine";
			else
				$class = "appLine dark";

			$conf_type[0] = "";
			$conf_type[1] = "<select name=\"".$conf_menu['conf_key']."\">";
			$conf_type[2] = "<select name=\"".$conf_menu['conf_key']."[]\" multiple=\"multiple\">";
			$conf_type[3] = "<input type=\"text\" name=\"".$conf_menu['conf_key']."\" value=\"".$conf_menu['conf_value']."\" class=\"inputText\" style=\"width:300px\" />";
			$conf_type[4] = "<textarea name=\"".$conf_menu['conf_key']."\" style=\"width:300px;height:100px;\">".$conf_menu['conf_value']."</textarea>";
			$conf_t = "<input type=\"text\" name=\"".$conf_menu['conf_key']."\" class=\"inputText\" style=\"width:300px\" />";
			for ($i=0; $i < 5; $i++)
			{
				if ($conf_menu['conf_type'] == $i)
				{
					if ($i == 1 OR $i == 2)
					{
						$options = explode( "\r\n", $conf_menu['conf_option'] );
                        
                        if ($i == 2)
                        {
                            $options_mult = explode( ",", $conf_menu['conf_value'] );
                        }
                        
						for($ii = 0; $ii < sizeof( $options ); $ii ++)
						{
							$options_key = explode( "=", $options[$ii] );
							list($value, $name) = $options_key;
							if (($i == 1 AND $value == $conf_menu['conf_value']) OR ($i == 2 AND in_array($value, $options_mult)))
							{
								$conf_type[1] .= "<option value=\"".$value."\" selected>".$name."</option>";
								$conf_type[2] .= "<option value=\"".$value."\" selected>".$name."</option>";
							}
							else
							{
								$conf_type[1] .= "<option value=\"".$value."\">".$name."</option>";
								$conf_type[2] .= "<option value=\"".$value."\">".$name."</option>";
							}
						}
						$conf_type[1] .= "</select>";
						$conf_type[2] .= "</select>";
					}
					elseif ($i == 0)
					{
						if ($conf_menu['conf_value'] == 1)
							$conf_type[0] = "<div class=\"radioContainer\"><input name=\"".$conf_menu['conf_key']."\" type=\"radio\" value=\"1\" id=\"".$conf_menu['conf_key']."_1\" checked></div>
 <label class=\"radioLabel\" for=\"".$conf_menu['conf_key']."_1\">��</label> <div class=\"radioContainer optionFalse\"><input name=\"".$conf_menu['conf_key']."\" type=\"radio\" value=\"0\" id=\"".$conf_menu['conf_key']."_0\"></div>

 <label class=\"radioLabel\" for=\"".$conf_menu['conf_key']."_0\">���</label>";
						else
							$conf_type[0] = "<div class=\"radioContainer\"><input name=\"".$conf_menu['conf_key']."\" type=\"radio\" value=\"1\" id=\"".$conf_menu['conf_key']."_1\"></div>
 <label class=\"radioLabel\" for=\"".$conf_menu['conf_key']."_1\">��</label> <div class=\"radioContainer optionFalse\"><input name=\"".$conf_menu['conf_key']."\" type=\"radio\" value=\"0\" id=\"".$conf_menu['conf_key']."_0\" checked></div>

 <label class=\"radioLabel\" for=\"".$conf_menu['conf_key']."_0\">���</label>";
					}

					$conf_t = $conf_type[$i];
					break;
				}
			}

if (!$conf_menu['conf_protect'])
    $edit_panel = "<td align=right width=\"110\">[ <a href=\"{$redirect_url}?do=configuration&op=editconf&id={$conf_menu['conf_id']}\">�������������</a> ]<br />[ <a href=\"javascript:confirmDelete('{$redirect_url}?do=configuration&op=delconf&id={$conf_menu['conf_id']}&secret_key={$secret_key}', '�� ������������� ������ ������� ��� ���������?')\"><font color=red>�������</font></a> ]</td>";
else
    $edit_panel = "<td align=right></td>";

if ($conf_menu['conf_key'] == "general_time")
{
    $conf_menu['conf_desc'] .= "<br />������� ����� �������: ".date("H:i, d.m.Y", time());
}

if ($conf_menu['conf_key'] == "upload_maxsize" OR $conf_menu['conf_key'] == "upload_maxsize_pic")
{
    $maxfile =  @ini_get( 'upload_max_filesize' );
    $conf_menu['conf_desc'] .= "<br />����������� �������: ".$maxfile;
}

echo <<<HTML
<tr class="{$class}">
<td align=left><h5>{$conf_menu['conf_name']}</h5><font class="smalltext">{$conf_menu['conf_desc']}</font></td>
<td align=right>{$conf_t}</td>
{$edit_panel}
</tr>
HTML;

		}
	}
	else
	{

echo <<<HTML
<tr><td><b>������� ��������� �� �������.</b></td></tr>
HTML;

	}

echo <<<HTML
</table>
<tr><td height="10"></td></tr>
<tr><td align=center><input type="submit" name="config" value="���������" class="btnBlack" /></td></tr>
</form>
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

<div class="clear" style="height:10px;"></div>
<table>
<tr><td align=right style="padding-right:15px;"><a href="{$redirect_url}?do=configuration&op=addconf&id={$group['conf_gr_id']}" title="�������� ����� ���������."><img src="{$redirect_url}template/images/process_add.gif" alt="�������� ���������" /></a></td></tr>
</table>
HTML;

}
else
{
	$links_speddbar = "<a href=\"".$redirect_url."?do=configuration\">���������</a> &raquo; ������";
	$control_center->speedbar($links_speddbar);

	$control_center->errors_title = "�� �������!";
	$control_center->errors[] = "��������� ������ �� �������.";
	$control_center->message();
}

?>