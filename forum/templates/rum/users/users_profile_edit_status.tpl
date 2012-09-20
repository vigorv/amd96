<form  method="post" name="editprofile" action="">
				<div class="category_block cb_color">
					<h3>Редактирование статуса: {member_name}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>
							<thead>
								<tr>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><label for="pass1">Личный статус:</label></td>
									<td>
										<textarea name="mstatus" id="mstatus" style="width:600px;height:60px;">{status}</textarea>
									</td>
								</tr>											
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>Изменить<input type="submit" name="editprofile" value="Изменить" /></span></span>
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