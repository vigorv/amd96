<div class="s-block">
	<div class="title-block">
		������������ ���������	
	</div>
	<div class="s-block-content">
		<div class="imp" style="padding: 0;margin: 0;">
		<div class="pm-block-menu">
			<div class="pm_status">
				<div class="pm_status_head">��������� �����</div>
				<div class="pm_status_content">����� ������������ ��������� ��������� ��:
			{pm-progress-bar}
			{proc-pm-limit}% �� ������ ({pm-limit} ���������)
				</div>
			</div>
			<div style="padding-top:15px;">[inbox]�������� ���������[/inbox] |
			[outbox]������������ ���������[/outbox] |
			[new_pm]��������� ���������[/new_pm]</div>
		</div><br /><br />
		<div style="clear:both;"></div>
		<br /><br /><br />
		[pmlist]
		<div class="pm-content">
		{pmlist}</div>
		[/pmlist]
		[newpm]
		<div class="pm-tititittile">�������� ������������� ���������</div>
		<div class="new-PM"><span>����������:</span><input type="text" class="add-n-form" name="name" value="{author}" /></div>
		<div class="new-PM"><span>����:</span><input type="text" class="add-n-form" name="subj" value="{subj}" /></div>

		<div class="pm-content">{editor}

		<br /><input type="checkbox" name="outboxcopy" class="poiskk" value="1" /> ��������� ��������� � ����� "������������"</div>
		[sec_code] 
		<div>���:{sec_code}</div>
		<div>������� ���:<input type="text" name="sec_code" id="sec_code" style="width:115px" class="f_input" /></td></div>
		[/sec_code]
		[recaptcha]
			������� ��� �����, ���������� �� �����������: <span class="impot">*</span>
			<div>{recaptcha}</div>
		[/recaptcha]
		<input type="image" src="{THEME}/images/ok.jpg" name="add" value="���������" style="margin: 0 10px 0 50px; border: 0; float: left;background: none;" />&nbsp;&nbsp;<input type="button" style="background: url({THEME}/images/view-buttom.jpg); width: 202px; height: 27px; padding: 0; margin: 0;border: 0;float: left; cursor: pointer;"  onclick="dlePMPreview()" value="" />
		<div style="clear: both;height: 1px;"></div>
		   [/newpm]
		[readpm]
		<div class="a-mess">��������: <strong>{author}</strong> | [reply]��������[/reply] | [del]�������[/del] | [complaint]������������[/complaint] | [ignore]������������[/ignore]</div>
		<div class="theme-mess">���� ��������� - {subj}</div>
		<div style="padding: 10px;">{text}</div>
		[/readpm]
		</div>
	</div>
</div>