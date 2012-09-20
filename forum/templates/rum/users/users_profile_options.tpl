<form  method="post" name="editprofile" action="">
				<div class="category_block cb_color">
					<h3>Настройки форума: {member_name}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>
							<thead>
								<tr>
									<th><strong>Информация</strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>	
                                 <tr>
									<td>Уведомлять о новом ЛС по E-Mail:<br /><font class="smalltext">Будет выслана копия сообщения</font></td>
									<td>{pmtoemail_op}</td>
								</tr>	
                                <tr>
									<td>Уведомлять об ответах в темах по:<br /><font class="smalltext">Уведомление о подписанных темах</font></td>
									<td>{subscribe_op}</td>
								</tr>
                                <tr>
									<td>Ваш онлайн статус:<br /><font class="smalltext">Выберите режим отображения</font></td>
									<td>{online_op}</td>
								</tr>
                                [posts_ajax]
                                <tr>
									<td>Загрузка сообщений:<br /><font class="smalltext">Автоматическая загрузка сообщений с других страниц темы</font></td>
									<td>{posts_ajax}</td>
								</tr>
                                [/posts_ajax]
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>Сохранить<input type="submit" name="editprofile" value="Сохранить" /></span></span>
                                            <input type="hidden" name="secret_key" value="{SECRET_KEY}" />
										</div>
									</td>
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
				<!--category_block end-->
</form>

   	<div class="admin_line cor5 cle">
			<fieldset>
				<div class="al_options">
					<div class="butt butt_b butt_options">
						<span><span><a href="#" title="Опции профиля">Опции профиля</a></span></span>
					</div>
					<div class="alo_list">
						<ol>
                            [subscribe]<li><a href="{subscribe}" title="Подписки на темы">Подписки на темы</a></li>[/subscribe]
                            [edit_status]<li><a href="{profile_edit_status}" title="Личный статус">Личный статус</a></li>[/edit_status]
                            <li><a href="{profile_edit_options}" title="Настройки форума">Настройки форума</a></li>
						</ol>
					</div>
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>