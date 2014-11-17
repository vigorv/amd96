[not-group=5]
<div class="left-block left-block2">
	<div class="left-block-title">
		Личный кабинет:
	</div>
	<div class="left-block-title-hello">
	Привет, <strong>{login}</strong> <a href="{favorites-link}" title="Ваши закладки" class="btn" style="height: 10px; width: 10px;"><div class="icon-heart" style="margin-top: -2px; margin-left: -2px;"></div></a>
	</div>
	<div class="left-block-content">
		<div class="l-link">
			[admin-link]<a href="{admin-link}" class="admin-link" target="_blank">Админцентр</a>[/admin-link]
			<!--<a href="{addnews-link}" class="add-link">Опубликовать</a>-->
			<a href="{profile-link}" class="profile-link">Профиль</a>
			<a href="{pm-link}" class="pm-link">Сообщения ({new-pm} | {all-pm})</a>		
			<a href="{logout-link}" class="lu-link">Выход</a>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div class="left-block-bottom"></div>
</div>
[/not-group]
[group=5]
<div class="left-block left-block2">
	<div class="left-block-title left-block2">
		Авторизация:
	</div>
	<div class="left-block-content">
		<form method="post" action="" style="margin: 0; padding: 0;position: relative;">
			<div class="login-line">
				<input name="login_name" type="text" class="login-input-text" title="Ваше имя на сайте" />
			</div>
			<div class="login-line">
				<input name="login_password" type="password" class="login-input-text" title="Ваш пароль" />
			</div>
			<div style="clear: both;"></div>
			<input onclick="submit();" type="image" class="enter" src="{THEME}/images/enter.png" value="вход" /><input name="login" type="hidden" id="login" value="submit" />
			
			<div class="reg-link">
				<a href="{registration-link}" title="регистрация на сайте">Регистрация</a> /
				<a href="{lostpassword-link}" title="востановление пароля">Забыл пароль?</a>
			</div>
		</form>
	</div>
	<div class="left-block-bottom"></div>
</div>
[/group]