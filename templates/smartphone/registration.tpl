<div class="panel">
    [registration]����������� ������ ������������[/registration][validation]���������� ������� ������������[/validation]
</div> 
<div class="post">
[registration]
<strong>������������, ��������� ���������� ������ �����!</strong><br /><br />����������� �� ����� ����� �������� ��� ���� ��� ����������� ����������. �� ������� ��������� ������� �� ����, ��������� ���� �����������, ������������� ������� ����� � ������ ������.<br /><br />� ������ ������������� ������� � ������������, ���������� � �������������� �����.<br /><br />
[/registration]

[validation]
<strong>��������� ����������</strong>,<br /><br />��� ������� ��� ��������������� �� ����� �����, ������ ���������� � ��� �������� ��������, ������� ��������� �������������� ���� � ����� �������.<br /><br />
[/validation]
</div>
<div class="panel">&nbsp;</div>
<table width="100%">
[registration]
                            <tr>
                              <td height="25" width="150">�����:</td>
                              <td><input type="text" name="name" id='name' class="f_input" /><br /><input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF" title="��������� ����������� ������ ��� �����������" onclick="CheckLogin(); return false;" type="button" value="��������� ���" /><div id='result-registration'></div></td>
                            </tr>
                            <tr>
                              <td height="25">������:</td>
                              <td><input type="password" name="password1" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25">��������� ������:</td>
                              <td><input type="password" name="password2" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25">��� E-Mail:</td>
                              <td><input type="text" name="email" class="f_input" /></td>
                            </tr>
		[question]
		<tr>
			<td class="label">
				������:
			</td>
			<td>
				<div>{question}</div>
			</td>
		</tr>
		<tr>
			<td class="label">
				�����:<span class="impot">*</span>
			</td>
			<td>
				<div><input type="text" name="question_answer" class="f_input" /></div>
			</td>
		</tr>
		[/question]
[sec_code]
                            <tr>
                              <td colspan="2" height="25"><strong>������������� ���� ������������</strong></td>
                            </tr>
                            <tr>
                              <td height="25">��� ������������:</td>
                              <td>{reg_code}</td>
                            </tr>
                            <tr>
                              <td height="25">������� ���:</td>
                              <td><input type="text" name="sec_code" style="width:115px" class="f_input" /></td>
                            </tr>
[/sec_code]
[recaptcha]
                      <tr>
                        <td colspan="2" height="25"><strong>������� ��� �����, ���������� �� �����������:</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" height="25">{recaptcha}</td>
                      </tr>
[/recaptcha]
[/registration]
[validation]
                            <tr>
                              <td height="25">���� ���:</td>
                              <td><input type="text" name="fullname" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25"><nobr>����� ����������:��</nobr></td>
                              <td><input type="text" name="land" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25">����� ICQ:</td>
                              <td><input type="text" name="icq" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25">����:</td>
                              <td><input type="file" name="image" style="width:304px; height:18px" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25">� ����:</td>
                              <td><textarea name="info" style="width:98%; height:70px" /></textarea></td>
                            </tr>
{xfields}
[/validation]
</div>
                            <tr>
                              <td height="25">&nbsp;</td>
                              <td><div style="padding-top:2px; padding-left:0px;">
                              <input type="submit" value=" ��������� "></div>
                              </td>
                            </tr>
                          </table>