[global_not_group=5]
<form id="user_info" name="user_info" action="">
		<fieldset>
			<dl class="cle">
				<dt><a href="{profile_link}"><img src="{member_avatar}" height="70" width="70" /><b>{member_name}</b></a> {controlcenter}</dt>
				<dd><a href="{pm_link}" title="������� � ����������">{new} ����� ��<i></i></a> [ <a href="{pm_link}" id="check_new_pm" onclick="CheckNewPM();return false;">���������</a> ]</dd>
                <dt><a href="{favorite}">���������</a> | <a href="{subscribe}">��������</a><br /><a href="{profile_options}" title="��������� ������">��������� ������</a> | {member_logout}</dt>
			</dl>
		</fieldset>
  </form>
[/global_not_group]
[global_group=5]
	<form id="user_login" action="" method="post">
		<fieldset>
			<label class="ul_field">
				<input type="text" placeholder="�����" name="name" id="ul_login" />
			</label>
			<label class="ul_field">
				<input type="password" placeholder="������" name="password" id="ul_pass" />				
			</label>	
				<input class="btn" style="height: 20px; margin: 5px 0px 0px 10px; line-height: 9px;" type="submit" name="autoriz" value="�����" title="�����" />
			<label class="ul_che">
				<input type="checkbox" name="remember" value="1" checked /> ���������
			</label>

			<div class="ul_links">
				<a href="{link_registration}" title="������� ��� ������ �����������">�����������</a>
				<a href="{link_lostpass}" title="������ ������?">������ ������?</a>
			</div>
		</fieldset>
	</form>
[/global_group]
