   	<div class="quick_answer cor5 cle" >
		<form method="post" name="newpost" id="newpost-form" action="">
        <input type="hidden" name="tid" id="tid" value="{tid}" />
			<fieldset>
				<h6><img src="{TEMPLATE}/images/topic/ico-faq.png" width="33" height="30" alt="Быстрый ответ" />Быстрый ответ:</h6>
				[global_group=5]
                <div class="qa_ta_in">
                    <div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<tbody>
								<tr>
									<td>Ваше имя:</td>
									<td>
										<input type="text" name="guest_name" id="guest_name" value="" style="width:220px" />
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
							</tbody>
						</table>
					</div>
				</div>
                [/global_group]
				<div class="qa_ta">
					<div class="qa_ta_in">
                        {bbcode}
						<textarea rows="10" cols="10" name="text" id="tf" onclick="SetNewField(this.id);" onchange="SaveText('tf', '{tid}');"></textarea>
                        [subscribe_in_reply]
                        <br />
                        <input type="checkbox" id="subscribe_box" name="subscribe" value="1" /> <label for="subscribe_box">Подписаться на ответы</label>
                        [/subscribe_in_reply]
					</div>
				</div>
				<div class="qa_buts">
					<div class="qa_buts_in">
						<div class="butt">
							<span><span>Ответить<input type="submit" name="addpost" value="Ответить" onclick="AddNewPost(); return false;" /></span></span>
						</div>
						<div class="butt">
							<span><span><a href="{reply_link}" title="Расширенная форма">Расширенная форма</a></span></span>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>