<div class="s-block">
	<div class="s-block-content">
		<div class="shot-title">
			<h1>����� ���������� �� �����</h1>
		</div>
		<table id="zebra" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top"> ����� ���������� ��������:{news_num}<br />
					�� ��� ������������:{news_allow}<br />
					�� ��� �� �������:{news_main}<br />
					�� ���������:{news_moder}<br />
					������������ �� �����:{news_month}<br />
					������������ �� ������:{news_week}<br />
					������������ �� �����:{news_day}<br /></td>
				<td valign="top"> ����� ������������:{comm_num} (<a href="index.php?do=lastcomments">���������</a>)<br />
					��������� �� �����:{comm_month}<br />
					��������� �� ������:{comm_week}<br />
					��������� �� �����:{comm_day}<br /></td>
				<td valign="top"> ���������������� �������������:{user_num}<br />
					�� ��� ��������:{user_banned}<br />
					���������������� �� �����:{user_month}<br />
					���������������� �� ������:{user_week}<br />
					���������������� �� �����:{user_day}<br /></td>
			</tr>
			[group=1,2,3]
			<tr>
				<td colspan="3"><span style="color: #39a5d9;">����� ������ ���� ������: {datenbank}</span></td>
			</tr>
			[/group]
		</table>
		<br />
		<div class="shot-title">
			<h1>������� ������ ������� �� �����:</h1>
		</div>
		<table cellspacing="0" cellpadding="0" id="zebra">
			{topusers}
		</table>
	</div>
</div>
