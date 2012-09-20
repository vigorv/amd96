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

$link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|�������� � �����������";
$control_center->header("������ �����", $link_speddbar);
$onl_location = "������ ����� &raquo; �������� � �����������";

$control_center->errors = array ();

$log_result = 20;

if (isset ( $_REQUEST['page'] ))
	$page = intval ( $_GET['page'] );
else
	$page = 0;

if ($page < 0)
	$page = 0;

if ($page)
{
	$page = $page - 1;
	$page = $page * $log_result;
}

$i = $page;

if (isset($_POST['del_checked']))
{
	if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	if ($_POST['act'] == 1)
	{
		$selected = $_POST['selected_all'];
		if ($selected AND control_center_admins($member_cca['logs']['posts_del']))
		{
			$j = 0;
			foreach	($selected as $id)
			{
				$j ++;
				$id = intval($id);
				$DB->delete("id = '{$id}'", "logs_posts");
			}

			$info = "<font color=red>��������</font> ����� �������� � ����������� (".$j.")";
			$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

			header( "Location: {$redirect_url}?do=logs&op=posts" );
            exit();
		}
        elseif(!control_center_admins($member_cca['logs']['posts_del']))
        {
            $control_center->errors[] = "� ��� ������������ ����, ����� ������� ���� ��������.";
			$control_center->errors_title = "������ �����.";
        }
		else
		{
			$control_center->errors[] = "�� �� ������� ���� ��� ��������.";
			$control_center->errors_title = "������!";
		}
	}
	else
	{
		$control_center->errors[] = "�� �� ������� ��������.";
		$control_center->errors_title = "������!";
	}
}

if (isset($_REQUEST['where_search']) AND isset($_REQUEST['word']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$type = intval($_REQUEST['type']);
	$ws = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['where_search'] ) ) );
	$word = $DB->addslashes( $safehtml->parse( trim( $_REQUEST['word'] ) ) );

	unset ($safehtml);
	$link_nav = $redirect_url."?do=logs&op=posts&where_search=".$ws."&word=".$word."&type=".$type."&page=";
}
else
{
	$word = "";
	$ws = "";
	$type = 0;
	$link_nav = $redirect_url."?do=logs&op=posts&page=";
}

if ($word AND $ws)
{
	require LB_CLASS . '/sql_search.php';
	$sql_search = new SQL_Search;

	if ($ws == "mid" OR $ws == "act_st" OR $ws == "fid" OR $ws == "tid" OR $ws == "pid")
	{
        $where = "log.".$ws." = '$word'";
	}
	elseif ($ws == "date")
	{
		$word_s = explode ("|", $word);
		$word_s[0] = strtotime ($word_s[0]);
		if ($word_s[1])
			$word_s[1] = strtotime($word_s[1]);
		else
			$word_s[1] = time ();
		$where = "log.date >= '".$word_s[0]."' AND log.date <= '".$word_s[1]."'";
	}
	elseif ($ws == "ip")
	{
		$where = $sql_search->regexp_ip($word, "log.ip");
	}
	elseif ($ws == "info")
	{
		$where = $sql_search->simple("log.info", $word, $type);
	}

	unset ($sql_search);
}
else
	$where = "";

$control_center->message();

echo <<<HTML

<script language='JavaScript' type="text/javascript">
<!--
function checkbox_all() {
    var frm = document.logs_box;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}

-->

  $(document).ready(function(){
    
    $("#show_ip").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300); 
      $("div #pid_info").hide(300);
      $("div #ip_info").show(500);     
    });
    
    $("#show_date").click(function () {
      $("div #ip_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #date_info").show(500);    
    });

    $("#show_fid").click(function () {
      $("div #date_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #pid_info").hide(300); 
      $("div #fid_info").show(500);   
    });
    
    $("#show_mid").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #mid_info").show(500);      
    });  
    
    $("#show_tid").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #mid_info").hide(300); 
      $("div #act_st_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #tid_info").show(500);      
    });
    
    $("#show_act_st").click(function () {
      $("div #date_info").hide(300);
      $("div #info_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #mid_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #act_st_info").show(500);      
    });
    
    $("#show_info").click(function () {
      $("div #date_info").hide(300);
      $("div #act_st_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #mid_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #pid_info").hide(300);
      $("div #info_info").show(500);      
    });
    
    $("#show_pid").click(function () {
      $("div #date_info").hide(300);
      $("div #act_st_info").hide(300);
      $("div #tid_info").hide(300); 
      $("div #mid_info").hide(300); 
      $("div #fid_info").hide(300);
      $("div #ip_info").hide(300);
      $("div #info_info").hide(300); 
      $("div #pid_info").show(500);         
    });
    
  });  
</script>

                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">�������� � �����������</div>
                    </div>
		<table class="colorTable">
		<form action="" method="post" name="logs_box">

                        <tr>
				<td align=left><h6>������������</h6></td>
                <td align=left><h6>���� (ID �����)</h6></td>
				<td align=left><h6>��������</h6></td>
                <td align=center><h6>����������</h6></td>
				<td align=right><h6>����</h6></td>
				<td align=right><input type="checkbox" name="master_box" title="�������� ���" onclick="javascript:checkbox_all()"></td>
                        </tr>
HTML;

$DB->prefix = array ( 1 => DLE_USER_PREFIX );
$DB->join_select( "log.*, u.user_id, u.name, t.title, p.post_member_id, p.post_member_name", "LEFT", "logs_posts log||users u||topics t||posts p", "log.mid=u.user_id||log.tid=t.id||log.pid=p.pid", $where, "ORDER BY log.date DESC LIMIT ".$page.", ".$log_result."" );
while ( $row = $DB->get_row() )
{
	$i++;

	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";

	$row['date'] = formatdate( $row['date'] );
    
    if ($row['title'])
        $row['title'] = "<a href=\"".$redirect_url_board."?do=board&op=topic&id=".$row['tid']."\" target=\"blank\" title=\"������� �������� � �����. ID: ".$row['tid']."\">".sub_title($row['title'], 20)."</a> (ID: ".$row['pid'].")";
    else
        $row['title'] = "<i>���� �������</i>";
    
    $act_st = "�� ��������";
    
    if ($row['act_st'] == 0) $act_st = "<font color=red>�������</font>";
    elseif ($row['act_st'] == 1) $act_st = "���������������";
    elseif ($row['act_st'] == 2) $act_st = "����������";
    elseif ($row['act_st'] == 3) $act_st = "����������";
    elseif ($row['act_st'] == 4) $act_st = "������";
    elseif ($row['act_st'] == 5) $act_st = "������������";
    elseif ($row['act_st'] == 6) $act_st = "����������";
    elseif ($row['act_st'] == 7) $act_st = "����������";
    
    if ($row['info'] AND $logs['act_st'] != 0)
        $act_st .= "<br /><a href=\"#info\" class=\"show_info\" title=\"�������, ����� ������� �����������.\">�����������.</a><div class=\"show_info_block\" style=\"display:none;\"><br />".$row['info']."</div>";
        
    $forum = "<a href=\"".$redirect_url_board."?do=board&op=forum&id=".$row['fid']."\" target=\"blank\" title=\"������� �������� � �������. ID: ".$row['fid']."\">".$cache_forums[$row['fid']]['title']."</a>";
    
echo <<<HTML

                        <tr class="{$class}">
				<td align=left><font class="blueHeader"><a href="{$cache_config['general_site_dle']['conf_value']}{$cache_config['general_site_admindle']['conf_value']}?mod=editusers&action=edituser&id={$row['user_id']}" title="������� � �������������� ������� ������������. ID: {$row['user_id']}">{$row['name']}</a></font></td>
                <td align=left>{$row['title']}<br /><font class="smalltext">{$forum}</font></td>
				<td align=left>{$act_st}</td>
                <td class="appBtn"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=logs&type=posts&id={$row['id']}','������ ���������','width=500,height=430,toolbar=1,location=0,scrollbars=1'); return false;" title="����������� ��������� ����������."><img src="{$redirect_url}template/images/info_link.gif" alt="���������..." /></a></td>
				<td align=right>{$row['date']}</td>
				<td align=right><input type="checkbox" name="selected_all[]" value="{$row['id']}" /></td>
                        </tr>

HTML;

}

echo <<<HTML
		</table>

	        <div class="clear" style="height:10px;"></div>

		<table><tr><td align=right>
		<select name="act">
		<option value="0">�������� ��������</option>
		<option value="1">- �������</option>
		</select>
		<input type="submit" name="del_checked" value="���������">
		<input type="hidden" name="secret_key" value="{$secret_key}" />
		</td></tr>
		</table>
		</form>
HTML;

if ($i > 0)
{
	$nav = $DB->one_select( "COUNT(*) as count", "logs_posts log", $where);
	$nav_all = $nav['count'];
	$DB->free($nav);
	if ($nav_all > $log_result)
	{
		include LB_CLASS.'/navigation.php';
		$navigation = new navigation;
		$navigation->creat($page, $nav_all, $log_result, $link_nav, "7");

echo <<<HTML
<table>
<tr><td align=center style="padding:8px;"><h6>{$navigation->result}</6></td></tr>
</table>
HTML;
		unset($navigation);
	}
}

$word = htmlspecialchars($word);

$where_s = array();
$where_s[0] = "mid";
$where_s[1] = "info";
$where_s[2] = "act_st";
$where_s[3] = "ip";
$where_s[4] = "date";
$where_s[5] = "fid";
$where_s[6] = "tid";
$where_s[7] = "pid";

$j = 0;
$where_s2 = array();
foreach ($where_s as $where_ws)
{
	if ($where_ws == $ws)
		$where_s2[$j] = "selected";
	else
		$where_s2[$j] = "";
	$j ++;
}

$type_s = array();
for ($j=0;$j<=2;$j++)
{
	if ($j == $type)
		$type_s[$j] = "selected";
	else
		$type_s[$j] = "";
}

echo <<<HTML
<form action="{$link_nav}1" method="post" name="logs">
<table border=0>
<tr>
<td align=left width="350">�����: <input type="text" name="word" class="inputText" value="{$word}" style="width:300px" /></td>
<td align=left><select name="where_search">
<option value="mid" id="show_mid" {$where_s2[0]}>������������ (ID)</option>
<option value="info" id="show_info" {$where_s2[1]}>��������</option>
<option value="act_st" id="show_act_st" {$where_s2[2]}>��� ��������</option>
<option value="ip" id="show_ip" {$where_s2[3]}>IP</option>
<option value="date" id="show_date" {$where_s2[4]}>����</option>
<option value="fid" id="show_fid" {$where_s2[5]}>����� (ID)</option>
<option value="tid" id="show_tid" {$where_s2[6]}>���� (ID)</option>
<option value="pid" id="show_pid" {$where_s2[7]}>��������� (ID)</option>
</select> <select name="type">
<option value="0" {$type_s[0]}>��������</option>
<option value="1" {$type_s[1]}>����������</option>
<option value="2" {$type_s[2]}>�������������</option>
</select> <input type="submit" name="search" value="�����" /></td>
</tr>
<tr><td colspan=2 align=left>
<div class="clear" style="height:10px;"></div>

<div id="mid_info" style="display:none;">
<font class="smalltext">
������� ID ������������ (��� ������ �� ����� ��������).<br />
ID �� ������ ������, ������ �� ��� ������������ (�������� ���������), ��� �� �������� �������������.
</font>
</div>

<div id="info_info" style="display:none;">
<font class="smalltext">
���� � ���������� ������ �������� ����� ���������� ��������� ����������, �� �� ������ ����������� ����� �� ���� ������.
</font>
</div>

<div id="act_st_info" style="display:none;">
<table width="100%" align=left>
<tr><td colspan=2 class="smalltext">���� �������� � ������ (��� ������ �� ����� ��������):</td></tr>
<tr>
<td width="200" class="smalltext">
0 - �������<br />
1 - ���������������<br />
2 - �������<br />
3 - �������<br />
4 - ����������<br />
5 - ����������<br />
6 - ������ ���� �����������
</td>
<td valign=top align=left class="smalltext">
7 - ������<br />
8 - ������������<br />
9 - ����������<br />
10 - ����������<br />
11 - ��������� �����������<br />
12 - ��������������� �����������<br />
13 - ������� �����������
</td></tr>
</table>
</div>

<div id="ip_info" style="display:none;">
<font class="smalltext">
����� �� IP: 255.255.255.255; 255.255.*; 255.*.255.255
</font>
</div>

<div id="date_info" style="display:none;">
<font class="smalltext">
����� �� ����: 21-03-2010 10:33 | 25-03-2010 (� | ��); 21-03-2010
</font>
</div>

<div id="fid_info" style="display:none;">
<font class="smalltext">
������� ID ������ (��� ������ �� ����� ��������).<br />
ID �� ������ ������, ������ �� �������� ������ (�������� ���������), ��� �� �������� �������.
</font>
</div>

<div id="tid_info" style="display:none;">
<font class="smalltext">
������� ID ���� (��� ������ �� ����� ��������).<br />
ID �� ������ ������, ������ �� �������� ������ (�������� ���������).
</font>
</div>

<div id="pid_info" style="display:none;">
<font class="smalltext">
������� ID ���������/����� (��� ������ �� ����� ��������).
</font>
</div>

</td></tr>
</table>
</form>
HTML;
?>