<div class="s-block">
	<div class="title-block">
		���������� ������� �� �����
	</div>
	<div class="s-block-content">
	<table class="user-info-table" width="100%" style="margin: 0;">
		<tr>
			<td width="130" nowrap="nowrap">������� ���������:</td>
			<td><input type="text" name="title" style="width: 300px;" value="{title}" class="add-n-form" maxlength="150" /></td>
		</tr>
		<tr>
			<td>���������:<br /><i>(������ �����)</i></td>
			<td>{category}</td>
		</tr>
	[not-wysywyg]
		<tr>
			<td colspan="2" style="padding: 0;">
			������ ����������<i>(������ � ���������� �������)</i>:<br />
			</td>
		</tr>
	[/not-wysywyg]
		<tr>
			<td colspan="2">������� ����������:<i>(�����������)</i><br />
			[not-wysywyg]<textarea name="short_story" id="short_story" onclick=setFieldName(this.name) style="width:90%; height:160px" class="f_textarea" />{short-story}</textarea>[/not-wysywyg]{shortarea}</td>
		</tr>
		<tr>
			<td colspan="2">������ �������:<br />
			[not-wysywyg]<textarea name="full_story" id="full_story" onclick=setFieldName(this.name) style="width:90%; height:200px" class="f_textarea" />{full-story}</textarea>[/not-wysywyg]{fullarea}</td>
		</tr>
		<tr>
			<td nowrap="nowrap">�������� �����<br /><i>(��� ��������� �������)</i></td>
			<td><input type="text" name="tags" value="{tags}" maxlength="150" class="add-n-form"  /></td>
		</tr>
	{xfields}
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
			<td>���:</td>
			<td><br />{sec_code}</td>
		</tr>
		<tr>
			<td>������� ���:</td>
			<td><input type="text" name="sec_code" id="sec_code" style="width:115px" class="add-n-form" /></td>
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
		<tr>
			<td colspan="2">{admintag}</td>
		</tr>
		<tr>
			<td colspan="2"><input type="image" src="{THEME}/images/add-buttom.jpg" style="border: 0;" name="add" value="���������" />��
			<input type="image" src="{THEME}/images/view-buttom.jpg" style="border: 0;" name="nview" onclick="preview()" value="��������" />
			</td>
		</tr>
	</table>
	</div>
</div>