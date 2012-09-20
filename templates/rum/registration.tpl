<div class="s-block">
	<div class="title-block"><span>[registration]Регистрация нового пользователя[/registration][validation]Обновление профиля пользователя[/validation]</span></div>
	<div class="s-block-content">
		<table class="user-info-table" style="padding: 0;margin: 0;">
		[registration]
			<tr>
				 <td colspan="2"><strong>Здравствуйте, уважаемый посетитель нашего сайта!</strong><br /><br />Регистрация на нашем сайте позволит Вам быть его полноценным участником. Вы сможете добавлять новости на сайт, оставлять свои комментарии, просматривать скрытый текст и многое другое.<br /><br />В случае возникновения проблем с регистрацией, обратитесь к администратору сайта.<br /><br /></td>
			</tr>
		[/registration]
		[validation]
			<tr>
				<td colspan="2"><strong>Уважаемый посетитель</strong>,<br /><br />Ваш аккаунт был зарегистрирован на нашем сайте, однако информация о Вас является неполной, поэтому заполните дополнительные поля в Вашем профиле.<br /><br /></td>
			</tr>
		[/validation]
		[registration]
			<tr>
				<td width="200">Логин:<br /><i>(Ваше имя на сайте, никнейм)</i></td>
				<td><input type="text" name="name" id='name' style="width:165px"  /> <input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF" title="Проверить доступность логина для регистрации" onclick="CheckLogin(); return false;" type="button" value="Проверить имя" /><div id='result-registration'></div></td>
			</tr>
			<tr>
				<td width="200">Пароль:<br /><i>(Ваш сложный пароль)</i></td>
				<td><input type="password" name="password1"  /></td>
			</tr>
			<tr>
				<td width="200">Повторите пароль:<br /><i>(Еще разок, чтобы не забыть)</i></td>
				<td><input type="password" name="password2"  /></td>
			</tr>
			<tr>
				<td width="200">Ваш E-Mail:<br /><i>(электронная почта для связи)</i></td>
				<td><input type="text" name="email"  /></td>
			</tr>
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
				<td colspan="2" height="25"><strong>Подтверждение кода безопасности</strong></td>
			</tr>
			<tr>
				<td width="200">Код безопасности:</td>
				<td>{reg_code}</td>
			</tr>
			<tr>
				<td width="200">Введите код:<br /><i>(Докажите, что Вы не робот)</i></td>
				<td><input type="text" name="sec_code" style="width:115px"  /></td>
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
		[/registration]
		[validation]
			<tr>
				<td width="120" height="25">Ваше Имя:<br /><i>(Настоящее имя)</i></td>
				<td><input type="text" name="fullname"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">Место жительства:<br /><i>(Откуда Вы?)</i></td>
				<td><input type="text" name="land"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">Номер ICQ:</td>
				<td><input type="text" name="icq"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">Фото:<br /><i>(Ваша фотография или аватарка)</i></td>
				<td><input type="file" name="image" style="width:304px; height:18px"  /></td>
			</tr>
			<tr>
				<td width="120" height="25">О себе:<br /><i>(Что Вы за человек?)</i></td>
				<td><textarea name="info" style="width:320px; height:70px" /></textarea></td>
			</tr>
		{xfields}
		[/validation]
			<tr>
				<td width="120" height="25">&nbsp;</td>
				<td><div style="padding-top:2px; padding-left:0px;">
					<input type="image" src="{THEME}/images/ok.jpg" name="submit" style="border: 0;" alt="Отправить" /></div>
				</td>
			</tr>
		</table>
	</div>
</div>