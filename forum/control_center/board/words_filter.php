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

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|������ ����";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ������ ����";

$control_center->errors = array ();

function LB_check_filter($word)
{
    global $cache_forums_filter;
    
    $word = utf8_strtolower($word);
    
    if (count($cache_forums_filter))
    {
        foreach($cache_forums_filter as $filter)
        {
            if (utf8_strtolower($filter['word']) == $word)
                return false;
        }
    }
    else
	   return true;

	return true;
}

function words_wilter_old ($text = "", $text_f = "", $text_r = "", $type = 1)
{
    if (!$text)
        return "";
    
    $find = array ();
    $replace = array ();
    
    if ($type == 1)
    {
        
        $find[] = "#([\b|\s|\<br \/>]|^)".preg_quote( $text_f, "#" )."([\b|\s|\!|\?|\.|,]|$)#i".regular_coding();

        if ($text_r)
            $replace[] = "$1".$text_r."$2";
        else
            $replace[] = "\\1\\2";
        
    }
    else
    {
        $find[] = "#".preg_quote($text_f, "#")."#i".regular_coding();
        if ($text_r)
            $replace[] = $text_r;
        else
            $replace[] = "";
    }
        
    if (!count($find))
        return $text;
     
    $text = preg_replace( $find, $replace, $text );
    
    return $text;
}

if (isset($_POST['newfilter']))
{
	$word = $DB->addslashes( trim( $_POST['word'] ) );
	if (!$word)
		$control_center->errors[] = "�� �� ����� �����.";
    elseif (utf8_strlen($word) > 255)
        $control_center->errors[] = "�� ����� ������� ������� �����, ������ 255 ��������.";
        
    $word_replace = $DB->addslashes( trim( $_POST['word_replace'] ) );
    
    if (utf8_strlen($word_replace) > 255)
        $control_center->errors[] = "�� ����� ������� ������� ����� ��� ������, ������ 255 ��������.";

	$type = intval( $_POST['type'] );
	if ($type < 1 OR $type > 2)
        $type = 1;
        
	if (!LB_check_filter($word))
		$control_center->errors[] = "������ ����� ��� ������������ � ������. ������� ������� ���, � ����� �������� ����� ������.";

	if (!$control_center->errors)
	{
		$DB->insert("word = '{$word}', word_replace = '{$word_replace}', type = '{$type}'", "forums_filter");
		$cache->clear("", "forums_filter");
        
        $dop_info = "";
        if ($word_replace)
            $dop_info .= " -> ".$word_replace;
            
        if ($type == 1)
            $dop_info .= "<br>���: ������ ����������";
        else
            $dop_info .= "<br>���: ����� ���������";
            
        $dop_info = $DB->addslashes($dop_info);
            
		$info = "<font color=green>����������</font> ������� ����: <b>".$word."</b>".$dop_info;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
        
        if (isset($_POST['search_replace']) AND intval($_POST['search_replace']))
        {         
            require LB_CLASS . '/sql_search.php';
            $sql_search = new SQL_Search;
                
            $where = $sql_search->simple("text", $word);
                
            unset($sql_search);
            $begin = 0;
                
            while (true)
            {  
                $empty = true;
                $check = $DB->select( "pid, text", "posts", $where, "ORDER BY pid ASC LIMIT ".$begin.", 600" );
                
                while ( $row = $DB->get_row($check) )
                {          
                    $empty = false;
                    $text = $DB->addslashes(words_wilter_old($row['text'], stripslashes($word), stripslashes($word_replace), $type));
                    $DB->update("text = '{$text}'", "posts", "pid = '{$row['pid']}'");
                }
                $DB->free($check);
                
                if ($empty) break;
                
                $begin += 600;
                
                sleep(2);
            }
        }
        
		header( "Location: ".$redirect_url."?do=board&op=words_filter" );
        exit();
	}
	else
		$control_center->errors_title = "������!";
}

if (isset($_POST['del_checked']))
{
	if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$selected = $_POST['selected_all'];
	if ($selected)
	{
        $filt_mass = array();
        foreach	($selected as $id)
        {
            $filt_mass[] = intval( $id );
        }
        $filt = implode("|", $filt_mass);

		$spisok = array();
        
        $filt_db = $DB->select( "*", "forums_filter", "id regexp '[[:<:]](".$filt.")[[:>:]]'" );
        while ( $row = $DB->get_row($filt_db) )
        { 
			$spisok[] = $DB->addslashes($row['word']);
			$DB->delete("id = '{$row['id']}'", "forums_filter");

        }        
        $DB->free($filt_db);

		$cache->clear("", "forums_filter");

		$info = "<font color=red>��������</font> �������� ����: ".implode( ", ", $spisok );
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

		header( "Location: ".$redirect_url."?do=board&op=words_filter" );
        exit();
	}
	else
	{
		$control_center->errors[] = "�� �� ������� ������ ��� ��������.";
		$control_center->errors_title = "������!";
	}
}

$control_center->message();

echo <<<HTML
<form  method="post" name="checked" action="">

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">������ ����</div>
                    </div>
                <table class="colorTable">
                        <tr>
				<td align=left><h6>�����</h6></td>
				<td align=left><h6>������</h6></td>
				<td align=left><h6>���</h6></td>
				<td align=right><h6>��������</h6></td>
                        </tr>
HTML;

$DB->select( "*", "forums_filter", "", "ORDER by id DESC" );

$i = 0;

while ( $row = $DB->get_row() )
{
    $i ++;

    if ($i%2)
        $class = "appLine";
    else
        $class = "appLine dark";
        
    if ($row['type'] == 1)
        $row['type'] = "������ ����������";
    else
        $row['type'] = "����� ���������";
        
    $row['word'] = htmlspecialchars($row['word']);
    $row['word_replace'] = htmlspecialchars($row['word_replace']);

echo <<<HTML

                        <tr class="{$class}">
                        	<td align=left>{$row['word']}</td>
                        	<td align=left>{$row['word_replace']}</td>
                            <td align=left>{$row['type']}</td>
                        	<td align=right width=20><input type="checkbox" name="selected_all[]" value="{$row['id']}" /></td>
                        </tr>
HTML;

}

echo <<<HTML
	        </table>
		 <div class="clear" style="height:10px;"></div>
	        
HTML;

echo <<<HTML
		<table>
		<tr>
			<td align=center>
				<input type="submit" name="del_checked" value="�������" class="btnRed">
				<input type="hidden" name="secret_key" value="{$secret_key}" />
			</td>
		</tr>
		</table>
</form>
HTML;

echo <<<HTML

	            <div class="clear" style="height:10px;"></div>
<form  method="post" name="filters" action="">
                   <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������� ������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption">�����:</div>
                                            <div><input type="text" name="word" class="inputText" style="width:200px" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�������� ��:</div>
                                            <div><input type="text" name="word_replace" class="inputText" style="width:200px" /> <font class="smalltext">�������� ���� ������, ���� ������ ������ ������� ��������� �����.</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">���:</div>
                                            <div><select name="type"><option value="1" selected>������ ����������</option><option value="2">����� ���������</option></select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">��������� ����� � ������:<br /><font class="smalltext">����� ������������� ����� �������� �������.</font></div>
                                            <div><input type="checkbox" name="search_replace" value="1" /> <font class="smalltext">����� ������� ����� ����� �� ���� ��������� � ��������� �� ������.</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption"></div>
					                       <input type="submit" name="newfilter" value="��������" class="btnGreen" />
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
</fotm>
HTML;

?>