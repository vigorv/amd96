<div class="s-block">
	<div class="title-block"><span>[registration]����������� ������ ������������[/registration][validation]���������� ������� ������������[/validation]</span></div>
	<div class="s-block-content">
		<table class="user-info-table" style="padding: 0;margin: 0;">
		[registration]
			<tr>
				 <td colspan="2"><strong>������������, ��������� ���������� ������ �����!</strong><br /><br />����������� �� ����� ����� �������� ��� ���� ��� ����������� ����������. �� ������� ��������� ������� �� ����, ��������� ���� �����������, ������������� ������� ����� � ������ ������.<br /><br />� ������ ������������� ������� � ������������, ���������� � �������������� �����.<br /><br /></td>
			</tr>
		[/registration]
		[validation]
			<tr>
				<td colspan="2"><strong>��������� ����������</strong>,<br /><br />��� ������� ��� ��������������� �� ����� �����, ������ ���������� � ��� �������� ��������, ������� ��������� �������������� ���� � ����� �������.<br /><br /></td>
			</tr>
		[/validation]
		[registration]
			<tr>
				<td width="200">�����:<br /><i>(���� ��� �� �����, �������)</i></td>
				<td><input type="text" name="name" id='name' style="width:165px"  /> <input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF" title="��������� ����������� ������ ��� �����������" onclick="CheckLogin(); return false;" type="button" value="��������� ���" /><div id='result-registration'></div></td>
			</tr>
			<tr>
				<td width="200">������:<br /><i>(��� ������� ������)</i></td>
				<td><input type="password" name="password1"  /></td>
			</tr>
			<tr>
				<td width="200">��������� ������:<br /><i>(��� �����, ����� �� ������)</i></td>
				<td><input type="password" name="password2"  /></td>
			</tr>
			<tr>
				<td width="200">��� E-Mail:<br /><i>(����������� ����� ��� �����)</i></td>
				<td><input type="text" name="email"  /></td>
			</tr>
		[question]
			<tr>
				<td colspan="2">
					<div style="padding: 5px 0 5px 0;">
					������:
				</div>
				<div style="padding: 5px 0 5px 0;">
					{question}
				</div>
					
				<div style="padding: 5px 0 5px 0;">
					�����:<span class="impot">*</span>
				</div>
					
				<div style="padding: 5px 0 5px 0;">
					<input type="text" name="question_answer" id="question_answer" class="f_input" />
				</div>
				</td>
			</tr>
		[/question]	
		[sec_code]
			<tr>
				<td colspan="2" height="25"><strong>������������� ���� ������������</strong></td>
			</tr>
			<tr>
				<td width="200">��� ������������:</td>
				<td>{reg_code}</td>
			</tr>
			<tr>
				<td width="200">������� ���:<br /><i>(��������, ��� �� �� �����)</i></td>
				<td><input type="text" name="sec_code" style="width:115px"  /></td>
			</tr>
		[/sec_code]
		[recaptcha]
		<tr>	
		<td colspan="2">
			������� ��� �����, ���������� �� �����������: <span class="impot">*</span>
			<div>{recaptcha}</div>
		</td>
	</tr>
		[/recaptcha]
		[/registration]
		[validation]
			<tr>
				<td width="120" height="25">���� ���:<br /><i>(��������� ���)</i></td>
				<td><input type="text" name="fullname"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">����� ����������:<br /><i>(������ ��?)</i></td>
				<td><input type="text" name="land"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">����� ICQ:</td>
				<td><input type="text" name="icq"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">����:<br /><i>(���� ���������� ��� ��������)</i></td>
				<td><input type="file" name="image" style="width:304px; height:18px"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">� ����:<br /><i>(��� �� �� �������?)</i></td>
				<td><textarea name="info" style="width:320px; height:70px" /></textarea></td>
			</tr>
		{xfields}
		[/validation]
			<tr>
				<td width="120" height="25">&nbsp;</td>
				<td><div style="padding-top:2px; padding-left:0px;">
					<input type="image" src="{THEME}/images/ok.jpg" name="submit" style="border: 0;" alt="���������" /></div>
				</td>
			</tr>
		</table>
	</div>
</div>