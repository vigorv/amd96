<div class="add-comment">
	[not-logged]
	<div class="add-comment-line">
		<div class="add-comment-line-left">
			���� ���:
		</div>
		<div class="add-comment-line-right">
			<input type="text" name="name" id="name" class="form-input-stand" />
		</div>
	</div>
	<div class="add-comment-line">
		<div class="add-comment-line-left">
			��� E-Mail:
		</div>
		<div class="add-comment-line-right">
			<input type="text" name="mail" id="mail" class="form-input-stand" />
		</div>
	</div>
	[/not-logged]
	
	<div class="add-comment-line" style="padding: 10px 0 10px 0;">
		{editor}
	</div>
	[question]
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
	[/question]
	[sec_code]
	<div class="add-comment-line">
		<div class="add-comment-line-left">
			���:
		</div>
		<div class="add-comment-line-right">
			{sec_code}
		</div>
	</div>
	<div class="add-comment-line">
		<div class="add-comment-line-left">
			������� ���:
		</div>
		<div class="add-comment-line-right">
			<input type="text" name="sec_code" id="sec_code" class="form-input-stand" />
		</div>
	</div>
	[/sec_code]
	
	[recaptcha]
	<div class="add-comment-line">
		������� ��� �����, ���������� �� �����������: <span class="impot">*</span>
			<div>{recaptcha}</div>
	</div>
	[/recaptcha]
	<div class="add-comment-line">
		<input value="��������" name="submit" type="image" src="{THEME}/images/add-buttom.jpg" style="border: 0;" />
	</div>
</div>