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

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>|<a href=\"".$redirect_url."?do=board&op=moderators\">����������</a>|�������������� ����������";
$control_center->header("�����", $link_speddbar);
$onl_location = "����� &raquo; ���������� &raquo; �������������� ����������";

$moder = $DB->one_select( "*", "forums_moderator", "fm_id = '{$id}'" );

$control_center->errors = array ();

if($moder['fm_id'])
{
    if (isset($_POST['moder']) AND $_POST['secret_key'] != "" AND $_POST['secret_key'] == $secret_key)
    {  
    	$group_permission = array();
        
        $group_permission['global_hideshow'] = intval($_POST['g_global_hideshow']);
        $group_permission['global_hidetopic'] = intval($_POST['g_global_hidetopic']);
        $group_permission['global_deltopic'] = intval($_POST['g_global_deltopic']);
        $group_permission['global_titletopic'] = intval($_POST['g_global_titletopic']);
        $group_permission['global_polltopic'] = intval($_POST['g_global_polltopic']);
        $group_permission['global_opentopic'] = intval($_POST['g_global_opentopic']);
        $group_permission['global_closetopic'] = intval($_POST['g_global_closetopic']);
        $group_permission['global_fixtopic'] = intval($_POST['g_global_fixtopic']);
        $group_permission['global_unfixtopic'] = intval($_POST['g_global_unfixtopic']);
        $group_permission['global_movetopic'] = intval($_POST['g_global_movetopic']);
        $group_permission['global_uniontopic'] = intval($_POST['g_global_uniontopic']);
        $group_permission['global_delpost'] = intval($_POST['g_global_delpost']);
        $group_permission['global_unionpost'] = intval($_POST['g_global_unionpost']);
        $group_permission['global_changepost'] = intval($_POST['g_global_changepost']);
        $group_permission['global_movepost'] = intval($_POST['g_global_movepost']);
        $group_permission['global_fixedpost'] = intval($_POST['g_global_fixedpost']);
        $group_permission['global_topic_log'] = intval($_POST['g_global_topic_log']);
        $group_permission['global_post_log'] = intval($_POST['g_global_post_log']);
        $group_permission['global_movepost_date'] = intval($_POST['g_global_movepost_date']);
        
        $group_permission = $DB->addslashes( serialize($group_permission) );
        
        if(!$moder['fm_is_group'])
            $info = "<font color=orange>��������������</font> ���������� <b>".$moder['fm_member_name']."</b> ������: ".$cache_forums[$moder['fm_forum_id']]['title'];
        else
            $info = "<font color=orange>��������������</font> ������ ����������� ".$cache_group[$fm_group_id]['g_title']." ������: ".$cache_forums[$moder['fm_forum_id']]['title'];
        
        $info = $DB->addslashes($info);

        $DB->update("fm_permission = '{$group_permission}'", "forums_moderator", "fm_id = '{$id}'");
        $cache->clear("", "forums_moder");
  
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
                
        header( "Location: ".$redirect_url."?do=board&op=moderators" );
        exit();
    }
    
    $g_access = unserialize($moder['fm_permission']);
    
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
    
    function option_code($title = "", $hint = "", $radio_code = "", $type_line = 0, $value = 0)
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

        radio_code($radio_code, $value);
        
echo <<<HTML
    </div>
</div>

HTML;

    }
    
echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">�������������� ���������� ������: {$cache_forums[$moder['fm_forum_id']]['title']}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
HTML;

option_code ("�������� ������� ��� � ���������", "", "g_global_hideshow", 0, $g_access['global_hideshow']);
option_code ("������� ��� � ���������", "", "g_global_hidetopic", 2, $g_access['global_hidetopic']);
option_code ("�������� ���", "", "g_global_deltopic", 2, $g_access['global_deltopic']);
option_code ("��������� �������� ���", "��������� �������� � �������� ���.", "g_global_titletopic", 1, $g_access['global_titletopic']);
option_code ("��������� �����������", "��������� ��� �������� ����������� � �����.", "g_global_polltopic", 1, $g_access['global_polltopic']);
option_code ("�������� ���", "���� ���� ���� �������.", "g_global_opentopic", 1, $g_access['global_opentopic']);
option_code ("�������� ���", "", "g_global_closetopic", 1, $g_access['global_closetopic']);
option_code ("����������� ���", "������� ���� ������ ��� ������� ������� ��� � �������� \"�����\".", "g_global_fixtopic", 1, $g_access['global_fixtopic']);
option_code ("�������� ����", "���� ���� ���� ����������.", "g_global_unfixtopic", 1, $g_access['global_unfixtopic']);
option_code ("����������� ���", "����������� ��� � ������ ������.", "g_global_movetopic", 1, $g_access['global_movetopic']);
option_code ("����������� ���", "���������� ���������� ��� � ����.", "g_global_uniontopic", 1, $g_access['global_uniontopic']);
option_code ("�������� ����� ���", "�������� ����� �������� � �����.", "g_global_topic_log", 1, $g_access['global_topic_log']);

option_code ("�������� ���������", "", "g_global_delpost", 2, $g_access['global_delpost']);
option_code ("�������������� ���������", "", "g_global_changepost", 1, $g_access['global_changepost']);
option_code ("�����������/����������� ���������", "", "g_global_fixedpost", 1, $g_access['global_fixedpost']);
option_code ("����������� ���������", "����������� ��������� � ������ ����.", "g_global_movepost", 1, $g_access['global_movepost']);
option_code ("���������� ���� ���������", "���������� ���� ��������� ��� �������� �� � ������ ����.", "g_global_movepost_date", 1, $g_access['global_movepost_date']);
option_code ("����������� ���������", "���������� ���������� ��������� � ����.", "g_global_unionpost", 1, $g_access['global_unionpost']);
option_code ("�������� ����� ���������", "�������� ����� �������� � ����������.", "g_global_post_log", 1, $g_access['global_post_log']);

echo <<<HTML
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <div><input type="submit" name="moder" value="���������" class="btnBlue" />
                                            <input type="hidden" name="secret_key" value="{$secret_key}" />
                                            </div>
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
{
	$control_center->errors_title = "�� ������!";
	$control_center->errors[] = "��������� ��������� �� ������ � ���� ������.";
	$control_center->message();
}
?>