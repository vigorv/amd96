				<div class="category_block cb_color">
					<h3>Обратная связь<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
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
                                [global_group=5]
                                <tr>
									<td>Ваше имя:</td>
									<td>
										<input type="text" name="from_name" id="from_name" value="" style="width:200px" />
									</td>
								</tr>
                                <tr>
									<td>Обратный адрес (E-Mail):</td>
									<td>
										<input type="text" name="from_email" id="from_email" value="" style="width:200px" />
									</td>
								</tr>
                                [/global_group]
                                [send_to]
                                <tr>
									<td>Получатель:</td>
									<td>
										{send_to}
									</td>
								</tr>
                                [/send_to]
								<tr>
									<td>Заголовок:</td>
									<td>
									   <input type="text" name="title"  style="width:200px" />
									</td>
								</tr>		
								<tr>
									<td>Содержание:</td>
									<td>
										<textarea name="text" style="width:600px;height:150px;"></textarea>
									</td>
								</tr>	
                                [captcha]
                                <tr>
									<td>Код:</td>
									<td>
										{captcha}
									</td>
								</tr>	
								<tr>
									<td>Введите код:</td>
									<td>
										<input type="text" name="keystring" id="keystring" style="width:220px" />
									</td>
								</tr>
                                [/captcha]	
                                [captcha_dop]
                                <tr>
									<td>Вопрос:</td>
									<td>
										{captcha_dop}
									</td>
								</tr>	
								<tr>
									<td>Ответ:</td>
									<td>
										<input type="text" name="keystring_dop" id="keystring_dop" style="width:220px" />
									</td>
								</tr>
                                [/captcha_dop]									
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>Отправить<input type="submit" name="send_mess" value="Отправить" /></span></span>
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