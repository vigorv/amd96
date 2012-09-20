<div class="s-block">
	<div class="title-block">
		Публикация новости на сайте
	</div>
	<div class="s-block-content">
	<table class="user-info-table" width="100%" style="margin: 0;">
		<tr>
			<td width="130" nowrap="nowrap">Введите заголовок:</td>
			<td><input type="text" name="title" style="width: 300px;" value="{title}" class="add-n-form" maxlength="150" /></td>
		</tr>
		<tr>
			<td>Категория:<br /><i>(раздел сайта)</i></td>
			<td>{category}</td>
		</tr>
	[not-wysywyg]
		<tr>
			<td colspan="2" style="padding: 0;">
			Панель управления<i>(работа с содержимым новости)</i>:<br />
			</td>
		</tr>
	[/not-wysywyg]
		<tr>
			<td colspan="2">Краткое содержание:<i>(обязательно)</i><br />
			[not-wysywyg]<textarea name="short_story" id="short_story" onclick=setFieldName(this.name) style="width:90%; height:160px" class="f_textarea" />{short-story}</textarea>[/not-wysywyg]{shortarea}</td>
		</tr>
		<tr>
			<td colspan="2">Полная новость:<br />
			[not-wysywyg]<textarea name="full_story" id="full_story" onclick=setFieldName(this.name) style="width:90%; height:200px" class="f_textarea" />{full-story}</textarea>[/not-wysywyg]{fullarea}</td>
		</tr>
		<tr>
			<td nowrap="nowrap">Ключевые слова<br /><i>(для поисковых роботов)</i></td>
			<td><input type="text" name="tags" value="{tags}" maxlength="150" class="add-n-form"  /></td>
		</tr>
	{xfields}
	[question]
		<tr>
			<td colspan="2">
				<div style="padding: 5px 0 5px 0;">
				Вопрос:
			</div>
			<div style="padding: 5px 0 5px 0;">
				{question}
			</div>
				
			<div style="padding: 5px 0 5px 0;">
				Ответ:<span class="impot">*</span>
			</div>
				
			<div style="padding: 5px 0 5px 0;">
				<input type="text" name="question_answer" id="question_answer" class="f_input" />
			</div>
			</td>
		</tr>
	[/question]
	[sec_code]            
		<tr>
			<td>Код:</td>
			<td><br />{sec_code}</td>
		</tr>
		<tr>
			<td>Введите код:</td>
			<td><input type="text" name="sec_code" id="sec_code" style="width:115px" class="add-n-form" /></td>
		</tr>
	[/sec_code]
	[recaptcha]
	<tr>	
		<td colspan="2">
			Введите два слова, показанных на изображении: <span class="impot">*</span>
			<div>{recaptcha}</div>
		</td>
	</tr>
	[/recaptcha]
		<tr>
			<td colspan="2">{admintag}</td>
		</tr>
		<tr>
			<td colspan="2"><input type="image" src="{THEME}/images/add-buttom.jpg" style="border: 0;" name="add" value="отправить" />  
			<input type="image" src="{THEME}/images/view-buttom.jpg" style="border: 0;" name="nview" onclick="preview()" value="просмотр" />
			</td>
		</tr>
	</table>
	</div>
</div>