[global_not_group=5]
<form id="user_info" name="user_info" action="">
		<fieldset>
			<dl class="cle">
				<dt><a href="{profile_link}"><img src="{member_avatar}" height="70" width="70" /><b>{member_name}</b></a> {controlcenter}</dt>
				<dd><a href="{pm_link}" title="перейти к сообщениям">{new} новых ЛС<i></i></a> [ <a href="{pm_link}" id="check_new_pm" onclick="CheckNewPM();return false;">Проверить</a> ]</dd>
                <dt><a href="{favorite}">Избранное</a> | <a href="{subscribe}">Подписки</a><br /><a href="{profile_options}" title="Настройки форума">Настройки форума</a> | {member_logout}</dt>
			</dl>
		</fieldset>
  </form>
[/global_not_group]
[global_group=5]
	<form id="user_login" action="" method="post">
		<fieldset>
			<label class="ul_field">
				<input type="text" placeholder="логин" name="name" id="ul_login" />
			</label>
			<label class="ul_field">
				<input type="password" placeholder="пароль" name="password" id="ul_pass" />				
			</label>	
				<input class="btn" style="height: 20px; margin: 5px 0px 0px 10px; line-height: 9px;" type="submit" name="autoriz" value="войти" title="войти" />
			<label class="ul_che">
				<input type="checkbox" name="remember" value="1" checked /> Запомнить
			</label>

			<div class="ul_links">
				<a href="{link_registration}" title="нажмите для начала регистрации">Регистрация</a>
				<a href="{link_lostpass}" title="Забыли пароль?">Забыли пароль?</a>
			</div>
		</fieldset>
	</form>
[/global_group]
