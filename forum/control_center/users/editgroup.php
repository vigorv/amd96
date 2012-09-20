<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$editgroup = $DB->one_select( "*", "groups", "g_id = '{$id}'" );

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=group\">������</a>|��������������: ".$editgroup['g_title'];
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; ������ &raquo; ��������������: ".$editgroup['g_title'];

$control_center->errors = array ();

if($editgroup['g_id'] == 1 AND !control_center_admins($member_cca['users']['admin']))
{
    $control_center->errors_title = "������ ������.";
    $control_center->errors[] = "��� ��������� ������������� ������ ".$cache_group[1]['g_title'].".";
	$control_center->message();
}
elseif ($editgroup['g_id'])
{
	if (isset($_POST['return']) OR isset($_POST['reload']))
	{
		require LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$safehtml->protocolFiltering = "black";

		$control_center->errors = array ();

		$g_title = $DB->addslashes( $safehtml->parse( trim( $_POST['g_title'] ) ) );
		if (utf8_strlen($g_title) < 3 OR utf8_strlen($g_title) > 30)
			$control_center->errors[] = "����� �������� ������ ������ 3 �������� ��� ������ 30.";

		$g_prefix_st = $DB->addslashes( trim( $_POST['g_prefix_st'] ) );
		$g_prefix_end = $DB->addslashes( trim( $_POST['g_prefix_end'] ) );

		$g_icon = $DB->addslashes(trim($_POST['g_icon']));

		$g_access_cc = intval($_POST['g_access_cc']);
		$g_supermoders = intval($_POST['g_supermoders']);
        $g_show_close_f = intval($_POST['g_show_close_f']);
        
		$group_permission = array();
        $group_permission['local_deltopic'] = intval($_POST['g_local_deltopic']);
        $group_permission['local_titletopic'] = intval($_POST['g_local_titletopic']);
        $group_permission['local_polltopic'] = intval($_POST['g_local_polltopic']);
        $group_permission['local_opentopic'] = intval($_POST['g_local_opentopic']);
        $group_permission['local_closetopic'] = intval($_POST['g_local_closetopic']);
        $group_permission['local_delpost'] = intval($_POST['g_local_delpost']);
        $group_permission['local_changepost'] = intval($_POST['g_local_changepost']);
        
        $group_permission = $DB->addslashes( serialize($group_permission) );
        
        $access_online = intval($_POST['g_access_online']);
        $access_newtopic = intval($_POST['g_access_newtopic']);
        $access_replytopic = intval($_POST['g_access_replytopic']);
        $access_replyclosed = intval($_POST['g_access_replyclosed']);
        $access_warning = intval($_POST['g_access_warning']);
        $access_show_hiden = intval($_POST['g_access_show_hiden']);
        
        $g_hide_text = intval($_POST['g_hide_text']);
        $g_signature = intval($_POST['g_signature']);
        $g_search = intval($_POST['g_search']);
        $g_status = intval($_POST['g_status']);
        
        $g_link_forum = intval($_POST['g_link_forum']);
        
        $g_tc_time = abs(intval($_POST['g_tc_time']));
        if ($g_tc_time > 10000) $g_tc_time = 10000;
        
        $g_pc_time = abs(intval($_POST['g_pc_time']));
        if ($g_pc_time > 10000) $g_pc_time = 10000;
        
        $g_show_ip = intval($_POST['g_show_ip']);
        $g_html_allowed = intval($_POST['g_html_allowed']);
        $g_metatopic = intval($_POST['g_metatopic']);
        
		unset($safehtml);

		if (!$control_center->errors)
		{
			$DB->update("g_title = '{$g_title}', g_prefix_st = '{$g_prefix_st}', g_prefix_end = '{$g_prefix_end}', g_icon = '{$g_icon}', g_access_cc = '{$g_access_cc}', g_supermoders = '{$g_supermoders}', g_access = '{$group_permission}', g_show_online = '{$access_online}', g_new_topic = '{$access_newtopic}', g_reply_topic = '{$access_replytopic}', g_reply_close = '{$access_replyclosed}', g_warning = '{$access_warning}', g_show_hiden = '{$access_show_hiden}', g_show_close_f = '{$g_show_close_f}', g_hide_text = '{$g_hide_text}', g_signature = '{$g_signature}', g_search = '{$g_search}', g_status = '{$g_status}', g_link_forum = '{$g_link_forum}', g_tc_time = '{$g_tc_time}', g_pc_time = '{$g_pc_time}', g_show_ip = '{$g_show_ip}', g_html_allowed = '{$g_html_allowed}', g_metatopic = '{$g_metatopic}'", "groups", "g_id='{$id}'");
            $cache->clear("", "group");

            $dop_info = "";
			if ($g_access_cc AND !$editgroup['g_access_cc'])
				$dop_info .= "<br><font color=green>�������� ������</font> � ����� ����������.";
			elseif (!$g_access_cc AND $editgroup['g_access_cc'])
				$dop_info .= "<br><font color=red>�������� ������</font> � ����� ����������.";
                
            if ($g_supermoders AND !$editgroup['g_supermoders'])
				$dop_info .= "<br><font color=green>���� ����� �����-����������</font>.";
			elseif (!$g_supermoders AND $editgroup['g_supermoders'])
				$dop_info .= "<br><font color=red>������� ����� �����-����������</font>.";

			$info = "<font color=orange>��������������</fonr> ������ �������������: ".$g_title.$dop_info;
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
            
            if (!$editgroup['g_access_cc'] AND $g_access_cc)
            {
                $cca_check = $DB->one_select( "cca_id, cca_group", "control_center_admins", "cca_group = '{$id}' AND cca_is_group = '1'" );
                if (!$cca_check['cca_id'])
                {
                    $_SESSION['cca_edit_group'] = 1;
                    $_SESSION['cca_edit_group_id'] = $id;
                }    
            }
            
            if (isset($_POST['return']))
                header( "Location: {$redirect_url}?do=users&op=group" );
            else
                header( "Location: {$_SERVER['REQUEST_URI']}" );
                
            exit();
		}
		else
			$control_center->errors_title = "������!";
	}

	$control_center->message();

	$editgroup['g_prefix_st'] = htmlspecialchars($editgroup['g_prefix_st']);
	$editgroup['g_prefix_end'] = htmlspecialchars($editgroup['g_prefix_end']);
	$editgroup['g_title'] = htmlspecialchars($editgroup['g_title']);
        
    $g_access = unserialize($editgroup['g_access']);
            
if (isset($_SESSION['cca_edit_group']))
{
    $_SESSION['cca_edit_group_id'] = intval($_SESSION['cca_edit_group_id']);
echo <<<HTML
                <div class="headerRed">
                        <div class="headerRedArr"><div></div></div>
                        <div class="headerRedL"></div>
                        <div class="headerRedR"></div>
                        <div class="headerRedBg">����������� ���� � ������ ����������</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                        <div style="text-align:left;">
                        <b>��������.</b> �� ��������� ������ ������ ������ � ����� ����������.<br>
                        �� ������ <a href="{$redirect_url}?do=users&op=cca_add&group={$_SESSION['cca_edit_group_id']}">��������� ����������� ���� �������</a> ��� ���� ������, ����� � �� ����� ������ ������.<br>
                        </div>
	                   </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
                    <div class="clear" style="height:20px;"></div>
HTML;
    unset($_SESSION['cca_edit_group']);
    unset($_SESSION['cca_edit_group_id']);
}

function radio_code($name = "", $checked = false)
{
    $how = "";
    $how2 = "";
    
    if ($checked)
        $how = "checked";
    else
        $how2 = "checked";
        
echo <<<HTML

<div class="radioContainer"><input name="{$name}" type="radio" id="{$name}_1" value="1" {$how}></div><label class="radioLabel" for="{$name}_1">��</label>
<div class="radioContainer optionFalse"><input name="{$name}" type="radio" id="{$name}_0" value="0" {$how2}></div><label class="radioLabel" for="{$name}_0">���</label>

HTML;

}

echo <<<HTML

  <script>
  $(document).ready(function(){
    
    $("#general_a").click(function () {
      $("#topics").hide(300);
      $("#posts").hide(300);
      $("#access").hide(300);
      $("#other").hide(300);
      $("#general").show(500);
    });
    
    $("#topics_a").click(function () {
      $("#general").hide(300);
      $("#posts").hide(300);
      $("#access").hide(300);
      $("#other").hide(300);
      $("#topics").show(500);
    });
    
    $("#posts_a").click(function () {
      $("#general").hide(300);
      $("#topics").hide(300);
      $("#access").hide(300);
      $("#other").hide(300);
      $("#posts").show(500);
    });
    
    $("#access_a").click(function () {
      $("#general").hide(300);
      $("#posts").hide(300);
      $("#topics").hide(300);
      $("#other").hide(300);
      $("#access").show(500);
    }); 
    
    $("#other_a").click(function () {
      $("#general").hide(300);
      $("#posts").hide(300);
      $("#topics").hide(300);
      $("#access").hide(300);
      $("#other").show(500);
    });  
    
    var supermoders = {$editgroup['g_supermoders']};
    var control_center = {$editgroup['g_access_cc']};
    
    $("#save_b").click(function () {
  
        if($('input:radio#g_supermoders_1[checked]').is(":checked") && !supermoders && $('input:radio#g_access_cc_1[checked]').is(":checked") && !control_center){  
            return confirm("�� �������, ��� ������ ���� ����� �����-����������� ������ � ������ � ��?");
        } 
    
        if($('input:radio#g_supermoders_1[checked]').is(":checked") && !supermoders){  
            return confirm("�� �������, ��� ������ ���� ����� �����-����������� ������?");
        } 
        
        if($('input:radio#g_access_cc_1[checked]').is(":checked") && !control_center){  
            return confirm("�� �������, ��� ������ ���� ������ � �� ������?");
        } 
  });
  
  });
  </script>

<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������������� ������ �������������: {$editgroup['g_title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                        <table><tr>
                        <td align=left><h5>���������:</h5></td>
                        <td align=left id="general_a"><h5><a href="#" onclick="return false;">��������</a></h5></td>
                        <td align=left id="topics_a"><h5><a href="#" onclick="return false;">����� �� ���� ������</a></h5></td>
                        <td align=left id="posts_a"><h5><a href="#" onclick="return false;">����� �� ��������� �� ������</a></h5></td>
                        <td align=left id="access_a"><h5><a href="#" onclick="return false;">����� �������</a></h5></td>
                        <td align=left id="other_a"><h5><a href="#" onclick="return false;">������</a></h5></td>
                        </tr></table>
                        <div id="general">
                           <table width="100%">
                                <tr>
                                    <td align=left style="padding-top:5px;width:100%;">
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">��������:</div>
                                            <div><input type="text" name="g_title" value="{$editgroup['g_title']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������:</div>
                                            <div><input type="text" name="g_prefix_st" value="{$editgroup['g_prefix_st']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������:</div>
                                            <div><input type="text" name="g_prefix_end" value="{$editgroup['g_prefix_end']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������:<br><font class="smalltext">������� ����� �� ������<br/>������: templates/icon.jpg</font></div>
                                            <div><input type="text" name="g_icon" value="{$editgroup['g_icon']}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                         <div>
                                            <div class="inputCaption2">�������� ��������� ������:<br><font class="smalltext">����� ������������� �������� �����.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_show_close_f", $editgroup['g_show_close_f']);
echo <<<HTML
				
                                            </div>
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">�����-����������:<br><font class="smalltext">����� <u>��� ����� �� ���� � ���������</u> ��� ���������� � ���������� ������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_supermoders", $editgroup['g_supermoders']);
echo <<<HTML
						
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������ � ����� ����������:</div>
                                            <div>
                                            
HTML;
radio_code("g_access_cc", $editgroup['g_access_cc']);
echo <<<HTML

					                       </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="topics" style="display:none;">
                          <table>
                                <tr><td>
                                <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                </td></tr>
                                <tr><td align=left><h5>�������� �� ������ ������</h5></td></tr>
                                <tr>
                                     <td align=left style="padding-top:5px;">
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ��� �����:<br><font class="smalltext">������ �������� - ��� ������� ���.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_local_deltopic", $g_access['local_deltopic']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                          <div>
                                            <div class="inputCaption2">��������� �������� ���:<br><font class="smalltext">��������� �������� � �������� ���.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_local_titletopic", $g_access['local_titletopic']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">��������� �����������:<br><font class="smalltext">��������� ��� �������� ����������� � �����.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_local_polltopic", $g_access['local_polltopic']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ���:<br><font class="smalltext">���� ���� ���� �������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_local_opentopic", $g_access['local_opentopic']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ���:</div>
                                            <div>
                                            
HTML;
radio_code("g_local_closetopic", $g_access['local_closetopic']);
echo <<<HTML
 
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����� �������:<br><font class="smalltext">������� ���������� �����, � ������� ������� ������������ ������ ��������, �������, ������� ���� ����.</font></div>
                                            <div><input type="text" name="g_tc_time" value="{$editgroup['g_tc_time']}" class="inputText" /> <font class="smalltext">������� 0, ���� ������ ����� �����������</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����������:<br><font class="smalltext">��������� ��������� ����-��� title, �������� ����� � �������� ��� ���.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_metatopic", $editgroup['g_metatopic']);
echo <<<HTML
 
					                       </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>  
                            </div>                          
                            
                            <div id="posts" style="display:none;">
                          <table>
                                 <tr><td>
                                <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                </td></tr>
                                <tr><td align=left><h5>�������� �� ������ �����������</h5></td></tr>
                                <tr>
                                    <td align=left style="padding-top:5px;" >
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ���������:<br><font class="smalltext">�������� �� �� ��� ������� � �������, ���� ��� ������������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_local_delpost", $g_access['local_delpost']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������������� ���������:</div>
                                            <div>
                                            
HTML;
radio_code("g_local_changepost", $g_access['local_changepost']);
echo <<<HTML
                                            
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����� �������:<br><font class="smalltext">������� ���������� �����, � ������� ������� ������������ ������ ������� ��� �������� ��� ���������.</font></div>
                                            <div><input type="text" name="g_pc_time" value="{$editgroup['g_pc_time']}" class="inputText" /> <font class="smalltext">������� 0, ���� ������ ����� �����������</font></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">��������� HTML ���:<br><font class="smalltext">��� �������� ������������ ��������� � ��������� ����� HTML ���.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_html_allowed", $editgroup['g_html_allowed']);
echo <<<HTML
                                            
					                       </div>
                                        </div>
                                    </td>
                                </tr>
                            </table> 
                            </div>
                            
                            <div id="access" style="display:none">
                            <table>
                                <tr>
                                    <td align=left style="padding-top:5px;">
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                          <div>
                                            <div class="inputCaption2">�������� ������:<br><font class="smalltext">����� ����� �� ������� ������ �������������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_access_online", $editgroup['g_show_online']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ����� ���:<br><font class="smalltext">��� ��� ���������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_access_newtopic", $editgroup['g_new_topic']);
echo <<<HTML

					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����� � ����� �����:</div>
                                            <div>
                                            
HTML;
radio_code("g_access_replytopic", $editgroup['g_reply_topic']);
echo <<<HTML
  
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">����� � �������� �����:</div>
                                            <div>
                                            
HTML;
radio_code("g_access_replyclosed", $editgroup['g_reply_close']);
echo <<<HTML
                                            
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������������� �������������:<br><font class="smalltext">����������� ������������� ������ ���������� ������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_access_warning", $editgroup['g_warning']);
echo <<<HTML
                                            
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������� ���� � ���������:<br><font class="smalltext">����������� ������ <u>�� ���� �������</u> ������� ���� � ���������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_access_show_hiden", $editgroup['g_show_hiden']);
echo <<<HTML
 
					                       </div>
                                        </div>
                                    <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������������� ������:</div>
                                            <div>
                                            
HTML;
radio_code("g_search", $editgroup['g_search']);
echo <<<HTML

					                   </div>
                                    </div>
                                    <div class="clear" style="height:18px;"></div>
                                          <div>
                                            <div class="inputCaption2">�������� IP �������������:<br><font class="smalltext">��������� ���������, ����� ������������ ������ ������ �������� �����-����������� ��� �����������.<br />�� ������ ��������������� ��� ��������� �� ������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_show_ip", $editgroup['g_show_ip']);
echo <<<HTML

					                       </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="other" style="display:none;">
                          <table>
                                <tr>
                                    <td align=left style="padding-top:5px;" >
                                        <div class="clear" style="height:8px;"></div>
                                        <hr />
                                        <div class="clear" style="height:8px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� �������� �����:<br><font class="smalltext">�������� ������ ��� ������ [hide]�����[/hide]</font></div>
                                            <div>
                                            
HTML;
radio_code("g_hide_text", $editgroup['g_hide_text']);
echo <<<HTML
                                            
					                       </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">������������� �������:</div>
                                            <div>
                                            
HTML;
radio_code("g_signature", $editgroup['g_signature']);
echo <<<HTML

					                       </div>
                                        </div>
                                        
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">�������� ������ ������ � ����������:<br><font class="smalltext">��� ������ �� �������� ����� ����� �����������.</font></div>
                                            <div>
                                            
HTML;
radio_code("g_link_forum", $editgroup['g_link_forum']);
echo <<<HTML

					                       </div>
                                        </div>
                                        
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">���-�� �������� � �������:<br><font class="smalltext">�������� ���� ������ ��� ������� 0, ����� ��������� ������������� ��������</font></div>
                                            <div><input type="text" name="g_status" value="{$editgroup['g_status']}" class="inputText" /></div>
                                    </div>
                                    </td>
                                </tr>
                            </table> 
                            </div>
                            
                            <table>
                                <tr>
                                    <td align=center style="padding-top:10px;">
                                        <div class="clear" style="height:18px;"></div>
                                        <div align=center>
                                            <div align=center id="save_b"><input type="submit" name="return" value="���������*" class="btnBlack" />   <input type="submit" name="reload" value="��������**" class="btnBlack" /></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr><td align=left>
<br><font class="smalltext">* - ��� ���������� �� �������� �� ��������, ��� ���� �� �������� �� �������� ��������������</font><br>
<font class="smalltext">** - ��� ���������� �� ��������� ��������� � �������� ������ ��������</font></td></tr>
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
else
{
	$control_center->errors_title = "�� �������!";
	$control_center->errors[] = "��������� ������ �� �������.";
	$control_center->message();
}

?>