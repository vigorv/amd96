[not-group=5]
<div class="panel">
<div style="padding-top:2px; padding-left:5px;">������, <b>{login}</b>!</div>
<div style="padding-top:5px; padding-bottom:5px; padding-left:22px;">
    <a href="{profile-link}">��� �������</a><br />
    <a href="{pm-link}">C�������� ({new-pm} | {all-pm})</a><br />
	<a href="{favorites-link}">��� ��������</a><br />
	<a href="{stats-link}">����������</a><br />
	<a href="{newposts-link}">����� ��������������</a>
	</div>
<div style="padding-top:2px; padding-bottom:5px;"><a href="{logout-link}"><b>��������� �����!</b></a></div>
<div style="padding-bottom:5px;">�� �������������� ��������� ������ �����. <a href="/index.php?action=mobiledisable">������� �� ������ ������ �����.</a></div>
</div>
[/not-group]
[group=5]
<div class="panel"><form method="post">
              {login-method}&nbsp;&nbsp;&nbsp;<input type="text" name="login_name" style="width:103px; font-family:tahoma; font-size:11px;"><br />
              ������: <input type="password" name="login_password" style="width:103px; font-family:tahoma; font-size:11px;"> <input type="submit" value=" ����� "><br />
					<input name="login" type="hidden" id="login" value="submit">
			  </form>
              <div style="padding-top:8px; padding-bottom:5px;"><a href="{registration-link}">����������� �� �����!</a> <a href="{lostpassword-link}">������ ������?</a></div>
			  <div style="padding-bottom:5px;">�� �������������� ��������� ������ �����. <a href="/index.php?action=mobiledisable">������� �� ������ ������ �����.</a></div>
</div>
[/group]