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

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|���������� ������";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ���������� ������";

if (isset($_POST['newforum']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );

	$title = $DB->addslashes( $safehtml->parse( trim( $_POST['title'] ) ) );
	if (!$title)
		$control_center->errors[] = "�� �� ����� �������� ������.";

	$posi = intval( $_POST['posi'] );
	if ($posi < 0)
		$posi  = 1;

	$alt_name = $DB->addslashes( $safehtml->parse( totranslit( trim( str_replace("/", "_", $_POST['alt_name']) ) ) ) );
	if (!$alt_name) $alt_name = $DB->addslashes( $safehtml->parse( totranslit( trim( str_replace("/", "_", $_POST['title']) ) ) ) );
        
    $check_alt = $DB->one_select("id", "forums", "alt_name = '{$alt_name}'");
	if ($check_alt['id']) $control_center->errors[] = "����� � ����� �������������� ��������� ��� ����.";	

    $flink = bb_clear_url($safehtml->parse($_POST['flink']));
    
    if (preg_match("/[\||\'|\"|\!|\$|\@|\~\*\+|<|>]/", $flink)) $control_center->errors[] = "� ������ ������������ ����������� �������.";
    if (!preg_match("#^(http|news|https|ed2k|ftp|aim|mms)://|(magnet:?)#", $flink) AND $flink) $flink = 'http://'.$flink;
        
    $flink = $DB->addslashes($flink);
    $flink_npage = intval($_POST['flink_npage']);

    $bb_allowed = array('b', 'i', 's', 'u', 'text_align', 'color', 'url', 'email', 'img', 'translite', 'smile', 'font', 'size');

    $_POST['description'] = htmlspecialchars($_POST['description']);
    $_POST['description'] = parse_word(html_entity_decode($safehtml->parse($_POST['description'])), true, true, true, $bb_allowed);
    $description = $DB->addslashes($_POST['description']);

	$parent_id = intval($_POST['parent_id']);
    $postcount = intval($_POST['postcount']);

	$check = $DB->one_select("id", "forums", "id = '{$parent_id}'");
	if (!$check['id']) $control_center->errors[] = "��������� ��������� ��� ����� �� ������� � ���� ������.";	

	$allow_bbcode = intval($_POST['allow_bbcode']);
	$allow_poll = intval($_POST['allow_poll']);
    
    if ($allow_poll)
    {
        if(intval($_POST['allow_poll_guest']))
            $allow_poll = 2;
    }

	$password = $DB->addslashes( $safehtml->parse( trim( $_POST['password'] ) ) );
	$password_notuse = $DB->addslashes( $safehtml->parse( implode( ',', $_POST['password_notuse'] )) );
    
    $meta_desc = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_desc'] ) ) );
    $meta_key = $DB->addslashes( $safehtml->parse( trim( $_POST['meta_key'] ) ) );

	if ($_POST['sort_order'] == "ASC")
		$sort_order = "ASC";
	else
		$sort_order = "DESC";
        
    $_POST['rules'] = htmlspecialchars($_POST['rules']);
    $_POST['rules'] = parse_word(html_entity_decode($safehtml->parse($_POST['rules'])), true, true, true, $bb_allowed);
    $rules = $DB->addslashes($_POST['rules']);

	$group_permission = array();

	foreach($cache_group as $m_group)
	{
		$read_forum = intval( $_POST['read_forum_'.$m_group[g_id]] );
		$read_theme = intval( $_POST['read_theme_'.$m_group[g_id]] );
		$creat_theme = intval( $_POST['creat_theme_'.$m_group[g_id]] );
		$answer_theme = intval( $_POST['answer_theme_'.$m_group[g_id]] );
		$upload_files = intval( $_POST['upload_files_'.$m_group[g_id]] );
		$download_files = intval( $_POST['download_files_'.$m_group[g_id]] );
		$group_permission[$m_group['g_id']] = array();
		$group_permission[$m_group['g_id']]['read_forum'] = $read_forum;
		$group_permission[$m_group['g_id']]['read_theme'] = $read_theme;
		$group_permission[$m_group['g_id']]['creat_theme'] = $creat_theme;
		$group_permission[$m_group['g_id']]['answer_theme'] = $answer_theme;
		$group_permission[$m_group['g_id']]['upload_files'] = $upload_files;
		$group_permission[$m_group['g_id']]['download_files'] = $download_files;
	}

	$group_permission = $DB->addslashes( serialize($group_permission) );
    
    $ficon = "";
    $ficon_0 = $DB->addslashes($safehtml->parse(str_replace("|", "", $_POST['ficon_0'])));
    $ficon_1 = $DB->addslashes($safehtml->parse(str_replace("|", "", $_POST['ficon_1'])));
            
    if ($ficon_0 OR $ficon_1)
    {
        if ((!$ficon_0 AND $ficon_1) OR ($ficon_1 AND !$ficon_1))
            $control_center->errors[] = "�� �� ������� ������ ������ ��� ������.";
        else
        {
            $ficon = $ficon_0."|".$ficon_1;
        }
    }
    
    $allow_bbcode_list = $_POST['allow_bbcode_list'];
    foreach ($allow_bbcode_list as $key => $value)
    {
        $allow_bbcode_list[$key] = intval($value);
    }
    $allow_bbcode_list = $DB->addslashes(implode(",", $allow_bbcode_list));

	if (!$control_center->errors)
	{
		$DB->insert("ficon = '{$ficon}', parent_id = '{$parent_id}', posi = '{$posi}', title = '{$title}', alt_name = '{$alt_name}', description = '{$description}', group_permission = '{$group_permission}', allow_bbcode = '{$allow_bbcode}', allow_poll = '{$allow_poll}', postcount = '{$postcount}', password = '{$password}', password_notuse = '{$password_notuse}', sort_order = '{$sort_order}', rules = '{$rules}', meta_desc = '{$meta_desc}', meta_key = '{$meta_key}', allow_bbcode_list = '{$allow_bbcode_list}', flink = '{$flink}', flink_npage = '{$flink_npage}'", "forums");
		$cache->clear("", "forums");

		$info = "<font color=green>����������</font> ������: ".$title;
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
		header( "Location: ".$redirect_url."?do=board" );
        exit();
	}
	else
		$control_center->errors_title = "������!";
}

$control_center->message();

$group_list = "";
foreach($cache_group as $m_group)
{
	$group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}

$forum_list = ForumsList ( 0, 0 );

$group_permission = <<<HTML
<tr>
<td align=left></td>
<td align=left><input name="read_forum_0" id="read_forum_0" type="checkbox" onclick="group_permission_row('read_forum');" value="1"> <font class="smalltext">�������� ������</font></td>
<td align=left><input name="read_theme_0" id="read_theme_0" type="checkbox" onclick="group_permission_row('read_theme');" value="1"> <font class="smalltext">������ ���</font></td>
<td align=left><input name="creat_theme_0" id="creat_theme_0" type="checkbox" onclick="group_permission_row('creat_theme');" value="1"> <font class="smalltext">�������� ���</font></td>
<td align=left><input name="answer_theme_01" id="answer_theme_0" type="checkbox" onclick="group_permission_row('answer_theme');" value="1"> <font class="smalltext">����� � �����</font></td>
<td align=left><input name="upload_files_0" id="upload_files_0" type="checkbox" onclick="group_permission_row('upload_files');" value="1"> <font class="smalltext">�������� ������</font></td>
<td align=left><input name="download_files_0" id="download_files_0" type="checkbox" onclick="group_permission_row('download_files');" value="1"> <font class="smalltext">���������� ������</font></td>
</tr>
<tr><td colspan=7><hr /></td></tr>
HTML;

$i = 0;
$group_script = "";

foreach($cache_group as $m_group)
{
    $group_script .= "\r\ndocument.getElementById(group_name+'_".$m_group['g_id']."').checked = value;";
    
	$i ++;
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

$group_permission .= <<<HTML
<tr class="{$class}">
<td align=left>[<a href="javascript:group_permission('{$m_group['g_id']}', 'yes')">+</a>|<a href="javascript:group_permission('{$m_group['g_id']}', 'no')">-</a>] {$m_group['g_title']}</td>
<td align=left><input type="checkbox" name="read_forum_{$m_group['g_id']}" id="read_forum_{$m_group['g_id']}" value="1"> <font class="smalltext">�������� ������</font></td>
<td align=left><input type="checkbox" name="read_theme_{$m_group['g_id']}" id="read_theme_{$m_group['g_id']}" value="1"> <font class="smalltext">������ ���</font></td>
<td align=left><input type="checkbox" name="creat_theme_{$m_group['g_id']}" id="creat_theme_{$m_group['g_id']}" value="1"> <font class="smalltext">�������� ���</font></td>
<td align=left><input type="checkbox" name="answer_theme_{$m_group['g_id']}" id="answer_theme_{$m_group['g_id']}" value="1"> <font class="smalltext">����� � �����</font></td>
<td align=left><input type="checkbox" name="upload_files_{$m_group['g_id']}" id="upload_files_{$m_group['g_id']}" value="1"> <font class="smalltext">�������� ������</font></td>
<td align=left><input type="checkbox" name="download_files_{$m_group['g_id']}" id="download_files_{$m_group['g_id']}" value="1"> <font class="smalltext">���������� ������</font></td>
</tr>
<tr><td height="10" colspan=7></td></tr>
HTML;
}

$bb_allowed_out = array('b', 'i', 's', 'u', 'text_align', 'color', 'url', 'email', 'img', 'translite', 'smile', 'font', 'size');

require LB_MAIN . '/components/scripts/bbcode/bbcode_cc.php';
include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
        
foreach ($list_allow_bbcode_arr as $key => $value)
{
    if ($key == 12) $add_br = "<br />";
    else $add_br = "";
                
    $list_allow_bbcode[] = $add_br."<input name=\"allow_bbcode_list[]\" type=\"checkbox\" value=\"$key\" checked> <a href=\"#\" onclick=\"return false;\" title=\"".$value['title']."\"><font class=\"smalltext\">[".$value['name']."]</font></a>";
}

$list_allow_bbcode = implode (" ", $list_allow_bbcode);

echo <<<HTML

<script language='JavaScript' type="text/javascript">
function group_permission( group, value )
{
	if (value == "no")
		value = false;
	else
		value = true;

	document.getElementById( 'read_forum_' + group ).checked = value;
	document.getElementById( 'read_theme_' + group ).checked = value;
	document.getElementById( 'creat_theme_' + group ).checked = value;
	document.getElementById( 'answer_theme_' + group ).checked = value;
	document.getElementById( 'upload_files_' + group ).checked = value;
	document.getElementById( 'download_files_' + group ).checked = value;
}

function group_permission_row(group_name)
{
	var inp_n=document.getElementById(group_name+'_'+0);
	var value=inp_n.checked;
	
    {$group_script}
}

$(document).ready(function(){
    
    $("#flink").change(function () {
        if ($(this).val() == "")
        {
            if ($(".not_link_forum:hidden"))
            {
                $(".not_link_forum").fadeIn(500);
            }
        }
        else
        {
            if ($(".not_link_forum:visible"))
            {
                $(".not_link_forum").fadeOut(500);
            }
        }
    });
});
</script>

<form  method="post" name="form_add" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">���������� ������ ������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption">��������:</div>
                                            <div><input type="text" name="title" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�������������� ��������:</div>
                                            <div><input type="text" name="alt_name" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">������:<br><font class="smalltext">����������� ������ ���� � ����� templates/������/forum_icons</font></div>
                                            <div><input type="text" name="ficon_0" value="" class="inputText" /> <font class="smalltext">����� �� ��������</font><br /><br /><input type="text" name="ficon_1" value="" class="inputText" /> <font class="smalltext">����� ��������</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">�������:</div>
                                            <div><input type="text" name="posi" value="1" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">��������:</div></td>
                                            <td width="720" align=left>{$bbcode_script}{$bbcode}<textarea name="description" class="inputText" id="tf" onclick="SetNewField(this.id);" style="width:700px;height:70px;"></textarea></td>
                                            </tr></table>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">��������� ��� �����:</div>
                                            <div><select name="parent_id">{$forum_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">�����-������:<br><font class="smalltext">���� ������ ������� ���� ����� ������� �� ����� ��������, �� ������ ������� ����� ������ �������� � ����.</font></div>
                                            <div><input type="text" name="flink" id="flink" value="" class="inputText" style="width:700px;" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������� � ����� �������:<br><font class="smalltext">������� ������ � ����� �������. ���� ��� - ������ ����� ������� �� ������� ��������.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="flink_npage" type="radio" id="flink_npage_1" value="1" checked></div> <label class="radioLabel" for="flink_npage_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="flink_npage" type="radio" id="flink_npage_0" value="0"></div> <label class="radioLabel" for="flink_npage_0">���</label>
                                            </div>
                                        </div>
                                        
                                        <div class="not_link_forum">
                                            <div class="clear" style="height:6px;"></div>
                        					<hr/>
                        					<div class="clear" style="height:6px;"></div>  
                                        <div>
                                            <div class="inputCaption">���� ��������:<br><font class="smalltext">�������� ������, ������� ����� ���������� � �������� "description".<br>�������� 200 ��������.</font></div>
                                            <div><textarea name="meta_desc" class="inputText" style="width:700px;height:60px;"></textarea></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">���� �������� �����:<br><font class="smalltext">�������� ����� ������, ������� ����� ���������� � �������� "keywords".<br>������ ����� ����� �������.<br>�������� 1000 ��������..</font></div>
                                            <div><textarea name="meta_key" class="inputText" style="width:700px;height:70px;"></textarea></div>
                                        </div>
                    					<div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">��������� BBcode:</div>
                                            <div>
						                          <div class="radioContainer"><input name="allow_bbcode" type="radio" id="allow_bbcode_1" value="1" checked></div> <label class="radioLabel" for="allow_bbcode_1">��</label>
						                          <div class="radioContainer optionFalse"><input name="allow_bbcode" type="radio" id="allow_bbcode_0" value="0"></div> <label class="radioLabel" for="allow_bbcode_0">���</label>
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">������ ����������� BBcode:</div></td>
                                            <td width="720" align=left>{$list_allow_bbcode}</td>
                                            </tr></table>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">��������� �����������:</div>
                                            <div>
                        						<div class="radioContainer"><input name="allow_poll" type="radio" id="allow_poll_1" value="1" checked></div> <label class="radioLabel" for="allow_poll_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="allow_poll" type="radio" id="allow_poll_0" value="0"></div> <label class="radioLabel" for="allow_poll_0">���</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">��������� ���������� ������:</div>
                                            <div>
                    						<div class="radioContainer"><input name="allow_poll_guest" type="radio" id="allow_poll_guest_1" value="1"></div> <label class="radioLabel" for="allow_poll_guest_1">��</label>
                    						<div class="radioContainer optionFalse"><input name="allow_poll_guest" type="radio" id="allow_poll_guest_0" value="0" checked></div> <label class="radioLabel" for="allow_poll_guest_0">���</label>
                    					    </div>
                                        </div>
                                        <div class="clear" style="height:6px;"></div>
                    					<hr/>
                    					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">������� ������ � ���:<br><font class="smalltext">������� � �������������.</font></div>
                                            <div>
                        						<div class="radioContainer"><input name="postcount" type="radio" id="postcount_1" value="1" checked></div> <label class="radioLabel" for="postcount_1">��</label>
                        						<div class="radioContainer optionFalse"><input name="postcount" type="radio" id="postcount_0" value="0"></div> <label class="radioLabel" for="postcount_0">���</label>
                    					    </div>
                                        </div>
					<div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">������ ��� ������:</div>
                                            <div><input type="text" name="password" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption">������ ��� ������:</div>
                                            <div><select name="password_notuse[]" multiple>{$group_list}</select></div>
                                        </div>
					<div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <div class="inputCaption">����������� ��:</div>
                                            <div><select name="sort_order"><option value="ASC">�����������</option><option value="DESC" selected>��������</option></select></div>
                                        </div>
                    <div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
                                            <table><tr>
                                            <td width="190" align=left><div class="inputCaption">������� ������:</div></td>
                                            <td width="720" align=left>{$bbcode}<textarea name="rules" class="inputText" id="tf2" onclick="SetNewField(this.id);" style="width:700px;height:100px;"></textarea></td>
                                            </tr></table>
                                        </div>
                                    </div>
                    <div class="clear" style="height:6px;"></div>
					<hr/>
					<div class="clear" style="height:6px;"></div>
                                        <div>
					<table>
<tr><td align=left><h5>������</h5></td><td align=left><h5>�������� ������</h5></td><td align=left><h5>������ ���</h5></td><td align=left><h5>�������� ���</h5></td><td align=left><h5>����� � �����</h5></td><td align=left><h5>�������� ������</h5></td><td align=left><h5>���������� ������</h5></td></tr>
{$group_permission}
</table>
<table width="100%" border=0>
<tr><td width=200 align=left></td><td align=left><input type="submit" name="newforum" value="�������" class="btnBlue" /></td></tr>
</table>
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

?>