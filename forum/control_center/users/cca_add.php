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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=cca\">����������� ���� � ������ ����������</a>|���������� �����������";
$control_center->header("������������", $link_speddbar);
$onl_location = "������������ &raquo; ����������� ���� � ������ ���������� &raquo; ���������� �����������";

$control_center->errors = array ();

if (!isset($_GET['step']))
{
    unset($_SESSION['cca_name']);
    unset($_SESSION['cca_id']);
    unset($_SESSION['cca_group']);
    
    if (isset($_POST['moder']))
    {
	   require LB_CLASS . '/safehtml.php';
	   $safehtml = new safehtml( );

	   $member_name = $DB->addslashes( $safehtml->parse( trim( $_POST['member'] ) ) );
       $member_group = intval( $_POST['group_permission'] );
    
        if($member_name != "")
        {
            $DB->prefix = DLE_USER_PREFIX;
            $check = $DB->one_select( "user_id, user_group", "users", "name = '{$member_name}'" );
    
            if (!$check['user_id'])
                $control_center->errors[] = "������������ �� ������.";
            elseif ($check['user_id'] == $member['user_id'])
                $control_center->errors[] = "�� �� ������ ���� ���� ����������.";
            else
            {
                $cca_check = $DB->one_select( "*", "control_center_admins", "cca_member_id = '{$check['user_id']}'" );
                if ($cca_check['cca_id'])
                    $control_center->errors[] = "��������� ������������ ��� �������� � ������ ����������� ������� � ������ ����������.";
                $DB->free($cca_check);
            }   
        }
        else
        {
            if ($member_group)
            {
                $check2 = $DB->one_select( "g_id", "groups", "g_id = '{$member_group}'" );
                if (!$check2['g_id'])
                    $control_center->errors[] = "��������� ������ �� �������.";
                elseif ($check2['g_id'] == 1)
                    $control_center->errors[] = "�� �� ������ ���������� ������ ��� ���� ������ ".$cache_group['1']['g_title'].".";
                else
                {
                    $cca_check2 = $DB->one_select( "*", "control_center_admins", "cca_group = '{$member_group}' AND cca_is_group = '1'" );
                    if ($cca_check2['cca_id'])
                        $control_center->errors[] = "��������� ������ ��� ��������� � ������ ����������� ������� � ������ ����������.";
                    $DB->free($cca_check2);
                }

                $check['user_id'] = 0;
            }
            else
                $control_center->errors[] = "�� ������ ������� ��� ������������ ��� ������� ������ �����������.";
        }
        
        if (!$control_center->errors)
        {            
            $_SESSION['cca_id'] = $check['user_id'];
            if ($check['user_group'])
                $_SESSION['cca_group'] = $check['user_group'];
            else
                $_SESSION['cca_group'] = $member_group;            
                
            header( "Location: {$_SERVER['REQUEST_URI']}&step=2&secret_key={$secret_key}" );
            exit();
        }
        else
            $control_center->errors_title = "������!";
            
        unset($safehtml);
    }

    $control_center->message();
    
    $group_list = "<option value=\"0\">-- �������� ������</option>";

    foreach($cache_group as $m_group)
    {
        if (isset($_GET['group']) AND intval($_GET['group']) == $m_group['g_id'])
            $group_list .= "<option value=\"".$m_group['g_id']."\" selected>".$m_group['g_title']."</option>";
        else
            $group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
    }
    
    if (isset($_GET['user']))
        $user = urldecode($_GET['user']);
    else
        $user = "";

echo <<<HTML
<form  method="post" name="newgroup" action="">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�����������: ��� 1</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption2">������������:<br><font class="smalltext">������� ��� ������������ ��� �������� ������.</font></div>
                                            <div><input type="text" name="member" value="{$user}" class="inputText" /></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <div><input type="submit" name="moder" value="��� 2" class="btnBlue" /></div>
                                        </div>
                                    </td>
                                    <td align=left>
                                         <div>
                                            <div class="inputCaption2">������:<br><font class="smalltext">�������� ������ ��� ������� ��� ������������.</font></div>
                                            <div><select name="group_permission">{$group_list}</select></div>
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
elseif (isset($_GET['step']) AND $_GET['step'] == 2 AND $_GET['secret_key'] != "" AND $_GET['secret_key'] == $secret_key AND (intval($_SESSION['cca_id']) != 0 OR intval($_SESSION['cca_group']) != 0))
{
  
    if (isset($_POST['moder_step2']))
    {  
    	$group_permission = array();
        
        $group_permission['config'] = array();
        $group_permission['config']['config'] = intval($_POST['config']);
        $group_permission['config']['change'] = intval($_POST['config_change']);
        $group_permission['config']['add'] = intval($_POST['config_add']);
        $group_permission['config']['del'] = intval($_POST['config_del']);
        $group_permission['config']['email'] = intval($_POST['config_email']);
        $group_permission['config']['template'] = intval($_POST['config_template']);
        $group_permission['config']['template_edit'] = intval($_POST['config_template_edit']);
        $group_permission['config']['user_agent'] = intval($_POST['config_user_agent']);
        $group_permission['config']['language'] = intval($_POST['config_language']);
        
        $group_permission['users'] = array();
        $group_permission['users']['users'] = intval($_POST['users']);
        $group_permission['users']['main'] = intval($_POST['users_main']);
        $group_permission['users']['add'] = intval($_POST['users_add']);
        $group_permission['users']['group'] = intval($_POST['users_group']);
        $group_permission['users']['group_edit'] = intval($_POST['users_group_edit']);
        $group_permission['users']['ranks'] = intval($_POST['users_ranks']);
        $group_permission['users']['delivery'] = intval($_POST['users_delivery']);
        $group_permission['users']['delivery_new'] = intval($_POST['users_delivery_new']);
        $group_permission['users']['cca'] = intval($_POST['users_cca']);
        $group_permission['users']['tools'] = intval($_POST['users_tools']);
        $group_permission['users']['warning'] = intval($_POST['users_warning']);
                
        $group_permission['board'] = array();
        $group_permission['board']['board'] = intval($_POST['board']);
        $group_permission['board']['addforum'] = intval($_POST['board_addforum']);
        $group_permission['board']['addcategory'] = intval($_POST['board_addcategory']);
        $group_permission['board']['editforum'] = intval($_POST['board_editforum']);
        $group_permission['board']['delforum'] = intval($_POST['board_delforum']);
        $group_permission['board']['filters'] = intval($_POST['board_filters']);
        $group_permission['board']['moders'] = intval($_POST['board_moders']);
        $group_permission['board']['moders_edit'] = intval($_POST['board_moders_edit']);
        $group_permission['board']['moders_del'] = intval($_POST['board_moders_del']);
        $group_permission['board']['notice'] = intval($_POST['board_notice']);
        $group_permission['board']['sharelink'] = intval($_POST['board_sharelink']);
        $group_permission['board']['sharelink_edit'] = intval($_POST['board_sharelink_edit']);
        
        $group_permission['complaint'] = array();
        $group_permission['complaint']['complaint'] = intval($_POST['complaint']);
        
        $group_permission['logs'] = array();
        $group_permission['logs']['logs'] = intval($_POST['logs']);
        $group_permission['logs']['action'] = intval($_POST['logs_action']);
        $group_permission['logs']['action_del'] = intval($_POST['logs_action_del']);
        $group_permission['logs']['login'] = intval($_POST['logs_login']);
        $group_permission['logs']['files'] = intval($_POST['logs_files']);
        $group_permission['logs']['filesdel'] = intval($_POST['logs_filesdel']);
        $group_permission['logs']['mysql'] = intval($_POST['logs_mysql']);
        $group_permission['logs']['mysqldel'] = intval($_POST['logs_mysqldel']);
        $group_permission['logs']['ban'] = intval($_POST['logs_ban']);
        $group_permission['logs']['bandel'] = intval($_POST['logs_bandel']);
        $group_permission['logs']['topics'] = intval($_POST['logs_topics']);
        $group_permission['logs']['topics_del'] = intval($_POST['logs_topics_del']);
        $group_permission['logs']['posts'] = intval($_POST['logs_posts']);
        $group_permission['logs']['posts_del'] = intval($_POST['logs_posts_del']);
        
        $group_permission['system'] = array();
        $group_permission['system']['system'] = intval($_POST['system']);
        $group_permission['system']['info'] = intval($_POST['system_info']);
        $group_permission['system']['cache'] = intval($_POST['system_cache']);
        $group_permission['system']['cachedel'] = intval($_POST['system_cachedel']);
        $group_permission['system']['rebuild'] = intval($_POST['system_rebuild']);
        
        $group_permission['modules'] = array();
        $group_permission['modules']['staticpage'] = intval($_POST['modules_staticpage']);
        $group_permission['modules']['rules'] = intval($_POST['modules_rules']);
        $group_permission['modules']['adt'] = intval($_POST['modules_adt']);
        
        $group_permission = $DB->addslashes( serialize($group_permission) );
        
        if($_SESSION['cca_id'])
            $fm_member_id = $_SESSION['cca_id'];
        else
            $fm_member_id = 0;
            
        $fm_group_id = intval($_SESSION['cca_group']);
        
        if($fm_member_id)
        {
            $fm_is_group = 0;
            $info = "<font color=green>����������</font> ����������� ��� ������������ <b>".$fm_member_name."</b>";
        }
        else
        {
            $fm_is_group = 1;
            $info = "<font color=green>����������</font> ����������� ��� ������ ".$cache_group[$fm_group_id]['g_title'];
        }
        
        $info = $DB->addslashes($info);

        $DB->insert("cca_member_id = '{$fm_member_id}', cca_group = '{$fm_group_id}', cca_is_group = '{$fm_is_group}', cca_permission = '{$group_permission}', cca_update = '{$time}'", "control_center_admins");
  
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
        
        unset($_SESSION['cca_name']);
        unset($_SESSION['cca_id']);
        unset($_SESSION['cca_group']);
                        
        header( "Location: ".$redirect_url."?do=users&op=cca" );
        exit();
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
    
    function option_code($title = "", $hint = "", $radio_code = "", $type_line = 0)
    {
        if ($hint) $hint = "<br><font class=\"smalltext\">".$hint."</font>";
        
        if ($type_line == 2)
        {
echo <<<HTML

<div class="clear" style="height:8px;"></div>
<hr />
<div class="clear" style="height:8px;"></div>
HTML;
        }
        elseif ($type_line == 1)
        {
echo <<<HTML

<div class="clear" style="height:18px;"></div>
HTML;
        }
        
echo <<<HTML

<div>
    <div class="inputCaption2">{$title}:{$hint}</div>
    <div>
HTML;

        radio_code($radio_code);
        
echo <<<HTML
    </div>
</div>

HTML;

    }
        
echo <<<HTML

<script>
$(document).ready(function(){
    
     $("#general_a").click(function () {
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#complaint_block").hide(500);
      $("#general_block").show(500);
    });
    
    $("#users_a").click(function () {
      $("#general_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#complaint_block").hide(500);
      $("#users_block").show(500);
    });
  
    $("#board_a").click(function () {
      $("#general_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").show(500);
    });
    
    $("#logs_a").click(function () {
      $("#general_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#complaint_block").hide(500);
      $("#logs_block").show(500);
    }); 
    
    $("#system_a").click(function () {
      $("#general_block").hide(300);
      $("#modules_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#complaint_block").hide(500);
      $("#system_block").show(500);
    });  
    
    $("#modules_a").click(function () {
      $("#general_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#complaint_block").hide(500);
      $("#modules_block").show(500);
    });
    
    $("#complaint_a").click(function () {
      $("#general_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#complaint_block").show(500);
    });
    
});
</script>

<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�����������: ��� 2</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                        <table><tr>
                        <td align=left><h5>���������:</h5></td>
                        <td align=center id="general_a"><h5><a href="#" onclick="return false;">������������</a></h5></td>
                        <td align=center id="users_a"><h5><a href="#" onclick="return false;">������������</a></h5></td>
                        <td align=center id="board_a"><h5><a href="#" onclick="return false;">�����</a></h5></td>
                        <td align=center id="complaint_a"><h5><a href="#" onclick="return false;">������</a></h5></td>
                        <td align=center id="logs_a"><h5><a href="#" onclick="return false;">������ �����</a></h5></td>
                        <td align=center id="system_a"><h5><a href="#" onclick="return false;">�������</a></h5></td>
                        <td align=center id="modules_a"><h5><a href="#" onclick="return false;">������</a></h5></td>
                        </tr></table>
                        <div class="clear" style="height:8px;"></div>
                        <hr />
                        <div class="clear" style="height:8px;"></div>
                        <div id="general_block">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("������������", "��������� ������ � ��������� �������� ������.", "config", 0);
option_code ("��������� ��������", "", "config_change", 2);
option_code ("���������� ��������", "", "config_add", 1);
option_code ("�������� ��������", "", "config_del", 1);
option_code ("������� ������", "", "config_template", 2);
option_code ("����� �������", "", "config_template_edit", 1);
option_code ("���� ������", "������ ������: �������� � ���������", "config_language", 2);
option_code ("E-mail �����������", "������ ������: ��������, ����������/��������, ��������������", "config_email", 2);
option_code ("������ User Agent", "������ ������: ��������, ����������/��������, ��������������", "config_user_agent", 2);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="users_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left style="padding-top:5px;width:100%;">
HTML;
option_code ("������ � ������ �������������", "��������� ������ � ������ �������������.", "users", 0);
option_code ("�������� � ����� �� �������������", "", "users_main", 2);
option_code ("���������� ������ ������������", "", "users_add", 1);
option_code ("�������� ������ �����", "", "users_group", 1);
option_code ("�������������� �����", "", "users_group_edit", 1);
option_code ("������ ������ � �������", "����������, �������������� � �������� ������.", "users_ranks", 2);
option_code ("������ ������ � ���������������", "", "users_warning", 1);
option_code ("�������� ��������", "", "users_delivery", 2);
option_code ("�������� � �������� ��������", "", "users_delivery_new", 1);
option_code ("������ ������ � ����������� ���� � ��", "����������, �������������� � �������� �����������.", "users_cca", 2);
option_code ("������ ������ � ������������", "", "users_tools", 1);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="board_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("�����", "��������� ������ � ��������� �������", "board", 0);
option_code ("���������� ������", "", "board_addforum", 2);
option_code ("���������� ���������", "", "board_addcategory", 1);
option_code ("�������������� ������ ��� ���������", "", "board_editforum", 1);
option_code ("�������� ������ ��� ���������", "", "board_delforum", 1);
option_code ("�������� �����������", "", "board_moders", 2);
option_code ("���������� � �������� �����������", "", "board_moders_edit", 1);
option_code ("�������������� �����������", "", "board_moders_del", 1);
option_code ("������ ������ � ������� ����", "����������, �������������� � �������� �������.", "board_filters", 2);
option_code ("������ ������ � �����������", "����������, �������������� � �������� ����������.", "board_notice", 1);
option_code ("������� ����������", "�������� ������ �������� ���������� ������.", "board_sharelink", 2);
option_code ("�������������� ������� ����������:", "����������, �������������� � �������� �������� ���������� ������.", "board_sharelink_edit", 1);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="complaint_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;

option_code ("������� �����", "��������� ������ � ��������� � �������� �����", "complaint", 0, $group_permission['complaint']['complaint']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="logs_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("������ �����", "��������� ������ ����� ������.", "logs", 0);
option_code ("�������� ����� �������� � ��", "", "logs_action", 2);
option_code ("�������� ����� �������� � ��", "", "logs_action_del", 1);
option_code ("�������� ����� �����������", "", "logs_login", 2);
option_code ("�������� ����� ��������� � ������", "", "logs_files", 2);
option_code ("�������� ����� ��������� � ������", "", "logs_filesdel", 1);
option_code ("�������� ����� MySQL ������", "", "logs_mysql", 2);
option_code ("�������� ����� MySQL ������", "", "logs_mysqldel", 1);
option_code ("�������� ����� ����������", "", "logs_ban", 2);
option_code ("�������� ����� ����������", "", "logs_bandel", 1);
option_code ("�������� ����� �������� � ������", "", "logs_topics", 2);
option_code ("�������� ����� �������� � ������", "", "logs_topics_del", 1);
option_code ("�������� ����� �������� � �����������", "", "logs_posts", 2);
option_code ("�������� ����� �������� � �����������", "", "logs_posts_del", 1);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="system_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("�������", "��������� ������ � ������ \"�������\".", "system", 0);
option_code ("�������� ���������� �������", "", "system_info", 2);
option_code ("�������� ������ ����", "", "system_cache", 1);
option_code ("�������� ������ ����", "", "system_cachedel", 1);
option_code ("�������� ������", "", "system_rebuild", 1);
echo <<<HTML
                                        
                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="modules_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;

option_code ("������ ������ � ����������� ���������", "", "modules_staticpage", 0);
option_code ("��������� ������ ������", "", "modules_rules", 1);
option_code ("������ ������ � ������ � �������", "", "modules_adt", 1);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <table>
                                <tr>
                                    <td style="padding-top:10px;">
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div><input type="submit" name="moder_step2" value="���������" class="btnBlue" /></div>
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
else
    header( "Location: {$_SESSION['back_link_users']}" );
?>