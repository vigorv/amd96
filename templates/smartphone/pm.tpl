<div class="title"><h2>������������ ���������</h2></div>
<div class="post">
    <div class="news">[inbox]�������� ���������[/inbox] <br /> [outbox]������������ ���������[/outbox] <br /> [new_pm]��������� ���������[/new_pm]</div>
</div>
<div class="panel">
    &nbsp;
</div> 

[pmlist]
<div class="title"><h2>������ ���������</h2></div>
<div class="post">
    <div class="news">{pmlist}</div>
</div>
<div class="panel">
    &nbsp;
</div> 
[/pmlist]
[newpm]
<div class="title"><h2>�������� ���������</h2></div>
<div class="post">
 <table width="450" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td height="25">����������:</td>
                              <td height="25"><input type="text" name="name" value="{author}" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td height="25">����:</td>
                              <td height="25"><input type="text" name="subj" value="{subj}" class="f_input" /></td>
                            </tr>
                            <tr>
                              <td colspan="2"><textarea name="comments" id="comments" cols="40" style="height:80px;" />{text}</textarea></td>
                            </tr>
                            <tr>
                              <td colspan="2" class="news"><input type="checkbox" name="outboxcopy" value="1" /> ��������� ��������� � ����� "������������"</td>
                            </tr>
[sec_code]                  <tr>
                              <td height="25">���:</td>
                              <td height="25"><br />{sec_code}</td>
                            </tr>
                            <tr>
                              <td height="25">������� ���:</td>
                              <td height="25"><input type="text" name="sec_code" id="sec_code" style="width:115px" class="f_input" /></td>
                            </tr>
[/sec_code]
[recaptcha]           <tr>
                        <td colspan="2" height="25"><strong>������� ��� �����, ���������� �� �����������:</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" height="25">{recaptcha}</td>
                      </tr>
[/recaptcha]
                            <tr>
                              <td colspan="2"><br /><input class="bbcodes" type="submit" name="add" value="���������" />&nbsp;&nbsp;<input class="bbcodes" type="button" onclick="dlePMPreview()" value="��������" /></td>
                            </tr>
                          </table>
</div>
<div class="panel">
    &nbsp;
</div> 
[/newpm]
[readpm]
<div class="title"><strong>{subj}</strong></div>
<div class="post">
    <div class="news">{text}</div>
</div>
<div class="panel">
    ��������: <strong>{author}</strong> | [reply]��������[/reply] | [del]�������[/del]
</div>
[/readpm]