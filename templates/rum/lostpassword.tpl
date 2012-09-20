<div class="s-block">
	<div class="title-block">Восстановление забытого пароля</div>
	<div class="s-block-content">
		<table class="user-info-table">
			<tr>
				<td width="200" height="25">Ваш логин или E-Mail на сайте:</td>
				<td height="25"><input type="text" name="lostname" class="lost-input-text"></td>
			</tr>
			[sec_code]
			<tr>
				<td align="left">Код безопасности:</td>
				<td>{code}</td>
			</tr>
			<tr>
				<td align="left">Введите код:</td>
				<td><input class="lost-input-text" maxlength="45" name="sec_code" size="14"></td>
			</tr>
			[/sec_code]
			[recaptcha]
	<tr>
		<td class="label">
			Введите два слова, показанных на изображении: <span class="impot">*</span>
		</td>
		<td>
			<div>{recaptcha}</div>
		</td>
	</tr>
	[/recaptcha]
			<tr>
				<td></td>
				<td height="25"><input type="image" src="{THEME}/images/ok.jpg" style="border: 0;" value="Отправить" name="submit" alt="Отправить"></td>
			</tr>
		</table>
	</div>
</div>