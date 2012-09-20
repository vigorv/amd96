<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN'))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

class �ontrol_�enter
{
	PUBLIC $errors_title = '';
	PUBLIC $errors = Array ();

	function header ($title, $speedbar = "")
	{
		global $secret_key, $cache_config, $member_id, $DB, $LB_charset;

        $last_date_login = date("d.m.Y, H:i", $member_id['lastdate']);
        $last_ip_login = $member_id['logged_ip'];
        
        $DB->prefix = DLE_USER_PREFIX;
        $users = $DB->one_select( "COUNT(*) as count", "users" );
        $topic = $DB->one_select( "COUNT(*) as count", "topics" );
        $post = $DB->one_select( "COUNT(*) as count", "posts" );

echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$LB_charset}" />
    <title>{$cache_config['general_name']['conf_value']} &raquo; {$title}</title>
    <link href="{$cache_config['general_site']['conf_value']}control_center/template/style.css" rel="stylesheet" type="text/css"/>
    <link href="{$cache_config['general_site']['conf_value']}control_center/template/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/jquery.js"></script>
    <script type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/jquery-ui-1.8.5.custom.min.js"></script>
    <script type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/jquery.ui.datepicker-ru.js"></script>
    <script type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/placehol.js"></script>
    <script type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/jquery.tooltip.min.js"></script>
    <!--[if lte IE 6]><script language="JavaScript" type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/ie.js"></script><![endif]-->
</head>
<body style="text-align:center;">
<span id="mini_window"></span>
<script type="text/javascript">
function confirmDelete(url, name)
{
    	var agree=confirm(name);
    	if (agree)
    		document.location=url;
}

$(document).ready(function() {
    $('a').tooltip({
        track: true,
        delay: 0,
        showURL: false,
        fade: 200
    });
    
    $('a.show_info').click(function(){
        $(this).nextAll(".show_info_block").eq(0).slideToggle(300);
        return false;
	});
});

</script>
<div class="clear" style="height:15px;"></div>
<div id="siteWidthTop">
    <div style="padding:0px 17px">
        <div style="padding:0px 10px;">
            <div id="forumListLink"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_home.gif" alt="������ �������" /><a href="{$cache_config['general_site']['conf_value']}" title="������ �� ������� �������� ������.">������ �������</a></div>
            <div id="logoutBlock">
                <div class="floatRight" style="padding-top:2px;"><div class="btnGray"><div><div><a href="{$cache_config['general_site']['conf_value']}control_center/?logout=yes&secret_key={$secret_key}" title="����� �� ��������.">�����</a></div></div></div></div>
                <div id="logoutStr"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_user.gif" alt="" />�� ����� ��� ������� ������� <a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=edituser&id={$member_id['user_id']}" title="������ � �������������� �������.">{$member_id['name']}</a></div>
            </div>
        </div>
        <div class="clear" style="height:11px;"></div>
        <div id="header">
            <div id="headerR">
                <div style="height:132px; overflow:hidden;">
                    <div id="logoContainer"><a href="{$cache_config['general_site']['conf_value']}control_center/" title="LogicBoard"><img src="http://rumedia.ws/templates/rum/images/logo_admin.png" alt="Rumedia Forum ADM" /></a></div>
                    <div id="headerInfoContainer">
                        <div id="headerInfoL"></div>
                        <div id="headerInfo">
                            <div id="headerInfoR">
                                <div style="padding-top:16px;width:330px;">
                                    <p>��������� ��� �� ���� ����� {$last_date_login}<br />��� IP: {$last_ip_login}</p>
                                    <div class="floatLeft" style="margin:2px 15px 20px 0px;">
                                        <div class="btnWhite">
                                            <div class="btnWhiteL"></div>
                                            <div class="btnWhiteBg">����������</div>
                                            <div class="btnWhiteR"></div>
                                        </div>
                                    </div>
                                    <p>���-�� ���: {$topic['count']}<br />���-�� ���������: {$post['count']}<br />���-�� �������������: {$users['count']}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear" style="height:1px;"></div>
                </div>
                <div style="padding-left:13px;">
HTML;

		$speedbar = explode ("|", $speedbar);
echo <<<HTML
                    <div class="headerTab">
                        <div class="headerTabL"></div>
                        <div class="headerTabBg"><a href="{$cache_config['general_site']['conf_value']}control_center/" title="������� �� ������� �������� ������ ����������.">����� ���������� LogicBoard</a></div>
                        <div class="headerTabR"></div>
                    </div>
HTML;

if ($speedbar[0] == "")
{
echo <<<HTML
                    <div class="headerTabActive">
                        <div class="headerTabL"></div>
                        <div class="headerTabBg"><i>�� ����������</i></div>
                        <div class="headerTabR"></div>
                    </div>
HTML;
}
else
{
	$speedbar_end = end($speedbar);
	reset($speedbar);

	foreach ($speedbar as $link)
	{

		if($link != $speedbar_end)
		{
echo <<<HTML
                    <div class="headerTab">
                        <div class="headerTabL"></div>
                        <div class="headerTabBg">{$link}</div>
                        <div class="headerTabR"></div>
                    </div>
HTML;
		}
	}

echo <<<HTML
                    <div class="headerTabActive">
                        <div class="headerTabL"></div>
                        <div class="headerTabBg">{$speedbar_end}</div>
                        <div class="headerTabR"></div>
                    </div>
HTML;
}

echo <<<HTML
                </div>
            </div>
        </div>
    </div>
</div>
<div id="siteWidth">
    <div id="generalPadding">
        <div class="clear" style="height:11px; width:970px;"></div>
        <table>
            <tr>
                <td id="ramkaTL"><div></div></td>
                <td id="ramkaT"><div></div></td>
                <td id="ramkaTR"><div></div></td>
            </tr>
            <tr>
                <td id="ramkaL">
                    <div class="leftMenu">
                        <div id="menu1">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu1Closed">
                                <div class="menuItemPlus"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_plus.gif" alt="" id="menuPlus1" /></div>
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_1.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=configuration" title="������������ ������.">������������</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                            <div id="menu1Opened" style="display:none; *margin-top:-12px;">
                                <div class="clear"></div>
                                <div class="submenuT"></div>
                                <div class="submenuBg">
                                    <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_1.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=configuration" title="������������ ������.">������������</a></div>
                                    <div class="clear" style="height:6px;"></div>
                                    <div class="submenuContainer">
                                        <ul>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=configuration&op=template" title="����� ������� �� ���������.">������� ������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=configuration&op=lang" title="����� ����� ������.">������������ ����</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=configuration&op=email" title="������� E-Mail �����������.">E-mail �����������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=configuration&op=user_agent" title="������ User Agent ��� ������ ������">������ User Agent</a></li>
                                        </ul>
                                    </div>        
                                </div>
                                <div class="submenuB"></div>
                            </div>
                        </div>
                        <div id="menu2">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu2Closed">
                                <div class="menuItemPlus"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_plus.gif" alt="" id="menuPlus2" /></div>
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_2.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users" title="������ �������������.">������������</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                            <div id="menu2Opened" style="display:none; *margin-top:-12px;">
                                <div class="clear"></div>
                                <div class="submenuT"></div>
                                <div class="submenuBg">
                                    <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_2.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users" title="������ �������������.">������������</a></div>
                                    <div class="clear" style="height:6px;"></div>
                                    <div class="submenuContainer">
                                        <ul>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=adduser" title="�������� ������ ������������.">�������� ������������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=group" title="������ ����� �������������.">������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=tools" title="������������ ��� ������ ������������ �� ������ ��� IP ������.">�����������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=ranks" title="������ �������������.">������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=delivery" title="�������� �� E-Mail ��� ��.">��������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=warning" title="�������������� �������������.">��������������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=users&op=cca" title="����������� ������� � ������ ����������.">����������� ���� � ��</a></li>
                                            <li><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=blockip" title="������ ��: IP, ������ ��� E-Mail. ������� � �� CMS DLE.">��� ������</a></li>
                                            
                                        </ul>
                                    </div>        
                                </div>
                                <div class="submenuB"></div>
                            </div>
                        </div>
                        <div id="menu3">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu3Closed">
                                <div class="menuItemPlus"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_plus.gif" alt="" id="menuPlus3" /></div>
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_3.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=board" title="������ ��������� � �������.">�����</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                            <div id="menu3Opened" style="display:none; *margin-top:-12px;">
                                <div class="clear"></div>
                                <div class="submenuT"></div>
                                <div class="submenuBg">
                                    <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_3.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=board" title="������ ��������� � �������.">�����</a></div>
                                    <div class="clear" style="height:6px;"></div>
                                    <div class="submenuContainer">
                                        <ul>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=board&op=moderators" title="������ ����������� �������.">����������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=board&op=words_filter" title="�������� ����.">������ ����</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=board&op=notice" title="���������� � �������.">����������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=board&op=sharelink" title="������� ���������� ������ (Twitter, ��������� � ��.).">������� ����������</a></li>
                                        </ul>
                                    </div>        
                                </div>
                                <div class="submenuB"></div>
                            </div>
                        </div>
                        
                        <div id="menu8">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu8Closed">
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_12.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=complaint" title="������.">������</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                        </div>
                        
                        <div id="menu4">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu4Closed">
                                <div class="menuItemPlus"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_plus.gif" alt="" id="menuPlus4" /></div>
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_7.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs" title="������ ����� ������.">������ �����</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                            <div id="menu4Opened" style="display:none; *margin-top:-12px;">
                                <div class="clear"></div>
                                <div class="submenuT"></div>
                                <div class="submenuBg">
                                    <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_7.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs" title="������ ����� ������.">������ �����</a></div>
                                    <div class="clear" style="height:6px;"></div>
                                    <div class="submenuContainer">
                                        <ul>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=actions" title="������ ����� �������� � ������ ����������.">�������� � ��</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=mysql_errors" title="������ ����� MySQL ������.">MySQL ������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=files" title="������ ����� ������ ��������� � ������ ������.">��������� � ������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=login" title="������ ����� ����������� � ������ ����������.">����������� � ��</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=blocking" title="������ ����� �� ����������/������������� �������������.">���������� �������.</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=topics" title="������ ����� �������� ������.">�������� � ������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=logs&op=posts" title="������ ����� �������� � �����������.">�������� � �����������</a></li>
                                        </ul>
                                    </div>        
                                </div>
                                <div class="submenuB"></div>
                            </div>
                        </div>
                        <div id="menu6">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu6Closed">
                                <div class="menuItemPlus"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_plus.gif" alt="" id="menuPlus6" /></div>
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_9.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=system" title="������ � �������.">�������</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                            <div id="menu6Opened" style="display:none; *margin-top:-12px;">
                                <div class="clear"></div>
                                <div class="submenuT"></div>
                                <div class="submenuBg">
                                    <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_9.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/?do=system" title="������ � �������.">�������</a></div>
                                    <div class="clear" style="height:6px;"></div>
                                    <div class="submenuContainer">
                                        <ul>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=system&op=cache" title="����� ���� ������.">����� ����</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=system&op=rebuild" title="�������� ������ �� ������ (���-�� ���, ������� � ��.).">�������� ������</a></li>
                                        </ul>
                                    </div>        
                                </div>
                                <div class="submenuB"></div>
                            </div>
                        </div>
                        <div id="menu7">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu7Closed">
                                <div class="menuItemPlus"><img src="{$cache_config['general_site']['conf_value']}control_center/template/images/ico_plus.gif" alt="" id="menuPlus7" /></div>
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_10.gif') 10px 0px no-repeat;"><a href="#" onclick="return false;" id="menuPlus7">������</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                            <div id="menu7Opened" style="display:none; *margin-top:-12px;">
                                <div class="clear"></div>
                                <div class="submenuT"></div>
                                <div class="submenuBg">
                                    <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_10.gif') 10px 0px no-repeat;">������</div>
                                    <div class="clear" style="height:6px;"></div>
                                    <div class="submenuContainer">
                                        <ul>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=adt" title="����� � ������� �� ������. ���������� �����-���� ���������� �� ��������� ������.">����� � �������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=staticpage" title="����������� ��������.">����������� ��������</a></li>
                                            <li><a href="{$cache_config['general_site']['conf_value']}control_center/?do=rules" title="������� ������.">������� ������</a></li>
                                        </ul>
                                    </div>        
                                </div>
                                <div class="submenuB"></div>
                            </div>
                        </div>
                        <div id="menu5">
                            <div class="clear" style="height:12px;"></div>
                            <div id="menu5Closed">
                                <div class="menuItem" style="background:url('{$cache_config['general_site']['conf_value']}control_center/template/images/left_menu_ico_6.gif') 10px 0px no-repeat;"><a href="{$cache_config['general_site']['conf_value']}control_center/" title="������� �������� ������ ����������.">������� ��������</a></div>
                                <div class="clear" style="height:6px;"></div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
HTML;
	}

	function speedbar ($links)
	{ 
		global $cache_config;

echo <<<HTML
<table width="100%" border=0>
<tr><td align=left><a href="{$cache_config['general_site']['conf_value']}control_center/">����� ���������� LogicBoard</a> &raquo; {$links}</td></tr>
</table>
HTML;
	}

	function footer ($id_menu = 5)
	{
		global $cache_config;
echo <<<HTML
                   <div class="clear" style="height:25px;"></div>
                </td>
                <td id="ramkaR"></td>
            </tr>
            <tr>
                <td id="ramkaBL"><div></div></td>
                <td id="ramkaB"><div></div></td>
                <td id="ramkaBR"><div></div></td>
            </tr>
        </table>
    </div>
</div>
<div id="siteWidthBtm">
    <div style="padding:0px 17px">
        <div class="clear" style="height:11px;"></div>
        <div id="btmBg">
            <div id="btmR">
                <div id="btmL">
                    <div class="btnWhite">
                        <div class="btnWhiteL"></div>
                        <div class="btnWhiteBg">�2011-2012 <a href="http://logicboard.ru/"><font color="black">LogicBoard</font></a></div>
                        <div class="btnWhiteR"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear" style="height:20px;"></div>
<script language="JavaScript" type="text/javascript" src="{$cache_config['general_site']['conf_value']}control_center/template/scripts/js.js"></script>
HTML;

if ($id_menu != 5)
{
echo <<<HTML
<script type="text/javascript"> 
$(document).ready(function() { 

    $('#menuPlus{$id_menu}').click(); 
}); 
</script>
HTML;
}

echo <<<HTML
</body>
</html>
HTML;
	}

	function login_panel ($title)
	{
		global $cache_config, $LB_charset;

echo <<<HTML

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$LB_charset}" />
    <title>{$title}</title>
    <link href="{$cache_config['general_site']['conf_value']}control_center/template/style.css" rel="stylesheet" type="text/css"/>
</head>
<body id="authBody">
    <table id="authMiddle">
        <tr>
            <td style="vertical-align:middle;">
                <div id="authContainer">
                    <!-- ���� � ������� -->
HTML;

			$this->message();

echo <<<HTML
                    <div class="clear" style="height:20px;"></div>
                    <!-- /���� � ������� -->
                    <form action="" method="post">
                    <div id="authBg">
                        <div id="authLogoContainer"><a href="#" title="LOGICBOARD"><img src="http://rumedia.ws/templates/rum/images/logo_admin.png" alt="LOGICBOARD" /></a></div>
                        <div id="authFormContainer">
                            <div class="authInpLine">
                                <div class="authInpCaption">�����</div>
                                <input type="text" name="name" id="name" class="authInp" value="" />
                            </div>
                            <div class="clear" style="height:13px;"></div>
                            <div class="authInpLine">
                                <div class="authInpCaption">������</div>
                                <input type="password" name="password" class="authInp" value="" />
                                <input type="submit" name="autoriz" value="�����" class="btnAuthGreen" />
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="clear"></div>
                </div>
            </td>                
        </tr>
    </table>
</body>
</html>
HTML;
	}

	function message ()
	{
		if ($this->errors)
		{
			$mes = "<ul style=\"text-align:left;\">";
			foreach ($this->errors as $mes_data)
			{
				$mes .= "<li>".$mes_data."</li>";
			}
			$mes .= "</ul>";

echo <<<HTML
		<div class="clear" style="height:20px;"></div>
                   <div class="headerRed">
                        <div class="headerRedArr"><div></div></div>
                        <div class="headerRedL"></div>
                        <div class="headerRedR"></div>
                        <div class="headerRedBg">{$this->errors_title}</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">{$mes}</div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
                    <div class="clear" style="height:20px;"></div>

HTML;
		}
	}
}

?>