<div class="s-block">
	<div class="s-block-content">
		<div class="shot-title">
			<h1>Общая статистика по сайту</h1>
		</div>
		<table id="zebra" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top"> Общее количество новостей:{news_num}<br />
					Из них опубликовано:{news_allow}<br />
					Из них на главной:{news_main}<br />
					На модерации:{news_moder}<br />
					Опубликовано за месяц:{news_month}<br />
					Опубликовано за неделю:{news_week}<br />
					Опубликовано за сутки:{news_day}<br /></td>
				<td valign="top"> Всего комментариев:{comm_num} (<a href="index.php?do=lastcomments">последние</a>)<br />
					Добавлено за месяц:{comm_month}<br />
					Добавлено за неделю:{comm_week}<br />
					Добавлено за сутки:{comm_day}<br /></td>
				<td valign="top"> Зарегистрировано пользователей:{user_num}<br />
					Из них забанено:{user_banned}<br />
					Зарегистрировано за месяц:{user_month}<br />
					Зарегистрировано за неделю:{user_week}<br />
					Зарегистрировано за сутки:{user_day}<br /></td>
			</tr>
			[group=1,2,3]
			<tr>
				<td colspan="3"><span style="color: #39a5d9;">Общий размер базы данных: {datenbank}</span></td>
			</tr>
			[/group]
		</table>
		<br />
		<div class="shot-title">
			<h1>Десятка лучших авторов на сайте:</h1>
		</div>
		<table cellspacing="0" cellpadding="0" id="zebra">
			{topusers}
		</table>
	</div>
</div>
