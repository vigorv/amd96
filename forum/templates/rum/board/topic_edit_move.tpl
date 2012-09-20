<form  method="post" name="movetopic" action="">
				<div class="category_block cb_color">
					<h3>Перенос [one_topic]темы[/one_topic][mass_topic]тем из форума[/mass_topic]: {title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
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
                                [mass_topic]
                                <tr>
									<td>Темы для переноса:</td>
									<td>
										{topics}
									</td>
								</tr>
                                [/mass_topic]	
								<tr>
									<td>Выберите форум:</td>
									<td>
                                    <select name="move_id" id="move" class="lbselect">
										{forums}
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
											<span><span>Перенести<input type="submit" name="movetopic" value="Перенести" /></span></span>
										</div>
									</td>
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
            [mass_topic]
            <input type="hidden" name="act" value="7" />
            [/mass_topic]
            [one_topic]
            <input type="hidden" name="moder_topic" value="{moder_topic}" />
            <input type="hidden" name="topic_id" value="{tid}" />
            [/one_topic]
</form>