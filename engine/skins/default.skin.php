<?PHP

if (!$lang['admin_logo']) $lang['admin_logo'] = "engine/skins/images/nav.jpg";

$skin_header = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>amd96 - Control Panel</title>
<meta content="text/html; charset={$config['charset']}" http-equiv="content-type" />
<link rel="stylesheet" type="text/css" href="engine/skins/default.css?v=2">
<link rel="stylesheet" type="text/css" href="engine/skins/jquery-ui.css?v=2">
<link rel="stylesheet" type="text/css" href="{$config['http_home_url']}templates/rum/css/icons.css">
{js_files}
</head>
<body>
<script language="javascript" type="text/javascript">
<!--
var dle_act_lang   = ["{$lang['p_yes']}", "{$lang['p_no']}", "{$lang['p_enter']}", "{$lang['p_cancel']}", "{$lang['media_upload']}"];
//-->
</script>
<div id="loading-layer"><div id="loading-layer-text">{$lang['ajax_info']}</div></div>
<div class="mainboxsize">
<table id="main_body" width="100%">
    <tr>
        <td width="4" height="16"><img src="engine/skins/images/tb_left.gif" width="4" height="16" border="0" /></td>
		<td background="engine/skins/images/tb_top.gif"><img src="engine/skins/images/tb_top.gif" width="1" height="16" border="0" /></td>
		<td width="4"><img src="engine/skins/images/tb_right.gif" width="3" height="16" border="0" /></td>
    </tr>
	<tr>
        <td width="4" background="engine/skins/images/tb_lt.gif"><img src="engine/skins/images/tb_lt.gif" width="4" height="1" border="0" /></td>
		<td valign="top" style="padding-top:12px; padding-left:13px; padding-right:13px;" bgcolor="#FAFAFA">
		

<div class="adm-head-block">
	<div class="adm-head-add">
	
		<a href="$PHP_SELF?mod=addnews&action=addnews">
			<div class="adm-btn" style="height:10px; width: 200px;"><div class="icon-plus-sign adm-head-icons-position"></div>&nbsp;&nbsp;&nbsp;Добавить запись</div>
		</a>
	</div>
	<div class="adm-head-edit">
		<a href="$PHP_SELF?mod=editnews&action=list" class="adm-btn" style="height:10px; width: 200px;">
			<div class="icon-edit adm-head-icons-position"></div>&nbsp;&nbsp;&nbsp;Редактировать записи
		</a>
	</div>
	<div class="adm-head-more">
		<a href="$PHP_SELF?mod=options&action=options" class="adm-btn" style="height:10px; width: 200px;">
			<div class="icon-th adm-head-icons-position"></div>&nbsp;&nbsp;&nbsp;Все разделы админки
		</a>
	</div>
	
	<div class="adm-head-tomain">
		<a href="$PHP_SELF?mod=main" class="adm-btn" style="height:10px; width: 100px;">
			<div class="icon-home adm-head-icons-position"></div>&nbsp;&nbsp;&nbsp;Главная
		</a>
	</div>
	<div class="adm-head-viewsite"><a href="{$config['http_home_url']}" class="adm-btn" style="height:10px; width: 100px;">
		<div class="icon-eye-open adm-head-icons-position"></div>&nbsp;&nbsp;&nbsp;На сайт
	</a>
	</div>
	<div class="adm-head-exit">
	<a href="$PHP_SELF?action=logout" class="adm-btn" style="height:10px; width: 100px;">
		<div class="icon-hand-left adm-head-icons-position"></div>&nbsp;&nbsp;&nbsp;Выход
	</a>
	</div>
	<div class="adm-head-whoami">{$lang['skin_name']}<br /> <strong style="color: #F00;">{user}</strong><br />({group})</div>
		<a href="/" class="adm-head-logo"></a>
	<div class="adm-head-md">
	<a href="http://rumedia.ws/download/trend/dates.php/" class="adm-btn">dates.php</a>
	<a href="http://rumedia.ws/download/trend/m.php/" class="adm-btn">m.php</a>
	</div>
	<div style="clear:both;"></div>
</div>

</div>
<!--MAIN area-->
HTML;

$skin_footer = <<<HTML
	 <!--MAIN area-->
<div style="padding-top:5px; padding-bottom:10px;">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="40" align="center" style="padding-right:10px;"><div class="navigation"><a href="http://rumedia.ws/" target="_blank">amd96</a><br />Copyright 2004-2012 &copy; </td>
    </tr>
</table></div>		
		</td>
		<td width="4" background="engine/skins/images/tb_rt.gif"><img src="engine/skins/images/tb_rt.gif" width="4" height="1" border="0" /></td>
    </tr>
	<tr>
        <td height="16" background="engine/skins/images/tb_lb.gif"></td>
		<td background="engine/skins/images/tb_tb.gif"></td>
		<td background="engine/skins/images/tb_rb.gif"></td>
    </tr>
</table></div>
</body>

</html>
HTML;

$skin_login = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>amd96 - Control Panel</title>
<meta content="text/html; charset={$config['charset']}" http-equiv="content-type" />
<style type="text/css">
html,body{
	width:100%;
	margin:0px;
	padding: 0px;
	background: #F4F3EE;
	font-size: 11px;
	font-family: verdana;
}

#login-box {
	width:447px;
	height:310px;
	margin:10% auto 0 auto;
	background:#FFFFFF;
}

form {
	margin:0px;
	padding: 0px;
}

input,
select {
	color: #000000;
	outline:none;
}

input[type="text"],
input[type="password"],
select {
	width:340px;
	background-color: #FFFFFF;
	color: #000000;
	font-size: 18px;
	font-family: verdana;
	font-weight: bold;
	border: none;
	margin-top: 20px;
	margin-left: 60px;
}

input[type="checkbox"] {
	padding:0px;
	margin-top: 25px;
}

label {
	padding:0px;
	margin:0px;
}

.error {
	padding-top: 75px;
	padding-left: 27px;
}
</style>
</head>
<body>
<form  name="login" action="" method="post"><input type="hidden" name="subaction" value="dologin">
<div id="login-box">
	<div style="width:447px;height:95px;background: url(engine/skins/images/loginheader.png);"><div class="error">{result}</div></div>
	<div style="width:447px;height:66px;background: url(engine/skins/images/{mauth}.png);"><input type="text" name="username"></div>
	<div style="width:447px;height:67px;background: url(engine/skins/images/loginbox3.png);"><input type="password" name="password"></div>
	<!-- Надо править высоту в стиле login-box
    <div style="width:447px;height:67px;background: url(engine/skins/images/loginbox4.png);">{select}</div>
    -->
	<div style="width:37px;height:82px;float:left;background: url(engine/skins/images/loginbox6.png);"></div>
	<div style="width:283px;height:82px;float:left;background: url(engine/skins/images/loginbox7.png);"><input type="checkbox" name="login_not_save" id="login_not_save" value="1"/><label for="login_not_save">&nbsp;{$lang['m_not_save']}</label></div>
	<div style="width:102px;height:82px;float:left;"><input type="image" src="engine/skins/images/loginbox8.png"></div>
	<div style="width:25px;height:82px;float:right;background: url(engine/skins/images/loginbox5.png);"></div>
</div></form>
</body>
</html>
HTML;

?>