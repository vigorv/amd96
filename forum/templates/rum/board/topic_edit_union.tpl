<form  method="post" name="union" action="">
				<div class="category_block cb_color">
					<h3>Объединение тем<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
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
									<td>Выбранные темы:</td>
									<td>
                                    {topics_selected}
									</td>
								</tr>
								<tr>
									<td>Выберите основную тему:</td>
									<td>
                                    <select name="title_id" id="union" class="lbselect">
										{topics_title}
                                    </select>
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
											<span><span>Объеденить<input type="submit" name="uniontopic" value="Объеденить" /></span></span>
										</div>
									</td>
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
            <input type="hidden" name="act" value="8" />
            {topics}
</form>