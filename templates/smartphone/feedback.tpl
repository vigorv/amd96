<div class="title"><strong>�������� �����</strong></div>
<div class="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
[not-logged]
                            <tr>
                              <td width="120" height="25">���� ���:</td>
                              <td><input type="text" maxlength="35" name="name" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td width="120" height="25">E-Mail:</td>
                              <td><input type="text" maxlength="35" name="email" class="f_input" /></td>
                            </tr>
[/not-logged]
                            <tr>
                              <td width="120" height="25">���������:</td>
                              <td><input type="text" maxlength="45" name="subject" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td width="120" height="25">����������:</td>
                              <td>{recipient}</td>
                            </tr>
							</table>
						    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td >���������:<br /><textarea name="message" style="width:97%; height:90px" class="f_textarea" /></textarea></td>
                            </tr>
							</table>
						    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            [sec_code]<tr>
                              <td width="120" height="25">��� ������������:</td>
                              <td><br />{code}</td>
                            </tr>[/sec_code]
                            [recaptcha] <tr>
                        <td colspan="2" height="25"><strong>������� ��� �����, ���������� �� �����������:</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" height="25">{recaptcha}</td>
                      </tr>[/recaptcha]
                            <tr>
                              <td width="120" height="25">������� ���:</td>
                              <td><input type="text" maxlength="45" name="sec_code" style="width:115px" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td width="120" height="25">&nbsp;</td>
                              <td><input type="submit" value=" ��������� " /></td>
                            </tr>
                          </table>
</div>