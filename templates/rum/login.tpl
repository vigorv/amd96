[not-group=5]
<div class="left-block left-block2">
	<div class="left-block-title">
		������ �������:
	</div>
	<div class="left-block-title-hello">
	������, <strong>{login}</strong> <a href="{favorites-link}" title="���� ��������" class="btn" style="height: 10px; width: 10px;"><div class="icon-heart" style="margin-top: -2px; margin-left: -2px;"></div></a>
	</div>
	<div class="left-block-content">
		<div class="l-link">
			[admin-link]<a href="{admin-link}" class="admin-link" target="_blank">����������</a>[/admin-link]
			<!--<a href="{addnews-link}" class="add-link">������������</a>-->
			<a href="{profile-link}" class="profile-link">�������</a>
			<a href="{pm-link}" class="pm-link">��������� ({new-pm} | {all-pm})</a>		
			<a href="{logout-link}" class="lu-link">�����</a>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div class="left-block-bottom"></div>
</div>
[/not-group]
[group=5]
<div class="left-block left-block2">
	<div class="left-block-title left-block2">
		�����������:
	</div>
	<div class="left-block-content">
		<form method="post" action="" style="margin: 0; padding: 0;position: relative;">
			<div class="login-line">
				<input name="login_name" type="text" class="login-input-text" title="���� ��� �� �����" />
			</div>
			<div class="login-line">
				<input name="login_password" type="password" class="login-input-text" title="��� ������" />
			</div>
			<div style="clear: both;"></div>
			<input onclick="submit();" type="image" class="enter" src="{THEME}/images/enter.png" value="����" /><input name="login" type="hidden" id="login" value="submit" />
			
			<div class="reg-link">
				<a href="{registration-link}" title="����������� �� �����">�����������</a> /
				<a href="{lostpassword-link}" title="������������� ������">����� ������?</a>
			</div>
		</form>
	</div>
	<div class="left-block-bottom"></div>
</div>
[/group]