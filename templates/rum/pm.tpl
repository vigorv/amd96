<div class="s-block">
	<div class="title-block">
		Персональные сообщения	
	</div>
	<div class="s-block-content">
		<div class="imp" style="padding: 0;margin: 0;">
		<div class="pm-block-menu">
			<div class="pm_status">
				<div class="pm_status_head">Состояние папок</div>
				<div class="pm_status_content">Папки персональных сообщений заполнены на:
			{pm-progress-bar}
			{proc-pm-limit}% от лимита ({pm-limit} сообщений)
				</div>
			</div>
			<div style="padding-top:15px;">[inbox]Входящие сообщения[/inbox] |
			[outbox]Отправленные сообщения[/outbox] |
			[new_pm]Отправить сообщение[/new_pm]</div>
		</div><br /><br />
		<div style="clear:both;"></div>
		<br /><br /><br />
		[pmlist]
		<div class="pm-content">
		{pmlist}</div>
		[/pmlist]
		[newpm]
		<div class="pm-tititittile">Отправка персонального сообщения</div>
		<div class="new-PM"><span>Получатель:</span><input type="text" class="add-n-form" name="name" value="{author}" /></div>
		<div class="new-PM"><span>Тема:</span><input type="text" class="add-n-form" name="subj" value="{subj}" /></div>

		<div class="pm-content">{editor}

		<br /><input type="checkbox" name="outboxcopy" class="poiskk" value="1" /> Сохранить сообщение в папке "Отправленные"</div>
		[sec_code] 
		<div>Код:{sec_code}</div>
		<div>Введите код:<input type="text" name="sec_code" id="sec_code" style="width:115px" class="f_input" /></td></div>
		[/sec_code]
		[recaptcha]
			Введите два слова, показанных на изображении: <span class="impot">*</span>
			<div>{recaptcha}</div>
		[/recaptcha]
		<input type="image" src="{THEME}/images/ok.jpg" name="add" value="отправить" style="margin: 0 10px 0 50px; border: 0; float: left;background: none;" />&nbsp;&nbsp;<input type="button" style="background: url({THEME}/images/view-buttom.jpg); width: 202px; height: 27px; padding: 0; margin: 0;border: 0;float: left; cursor: pointer;"  onclick="dlePMPreview()" value="" />
		<div style="clear: both;height: 1px;"></div>
		   [/newpm]
		[readpm]
		<div class="a-mess">Отправил: <strong>{author}</strong> | [reply]ответить[/reply] | [del]удалить[/del] | [complaint]Пожаловаться[/complaint] | [ignore]Игнорировать[/ignore]</div>
		<div class="theme-mess">Тема сообщения - {subj}</div>
		<div style="padding: 10px;">{text}</div>
		[/readpm]
		</div>
	</div>
</div>