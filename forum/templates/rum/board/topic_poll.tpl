<form method="post" name="polltopic" id="polltopic" action="">
<input type="hidden" name="tid" id="tid" value="{tid}" />
				<div class="category_block cb_color">
					<h3>Опрос: {title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table" id="vars_td">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>                            
							<thead>
								<tr>
									<th><strong>Опрос</strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Вопрос:</td>
									<td>
									   <b>{question}</b>
									</td>
								</tr>		
								<tr>
									<td>Варианты:</td>
									<td>
                                        <ol class="vote_res">
										{variants}
                                        </ol>
									</td>
								</tr>									
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
                                [vote]
									<td>
                                    <div class="butt butt_b">
											<span><span><a href="{poll_link}" id="pollresult" onclick="PollResult('{tid}');return false;" title="Посмотерть результаты">Посмотреть результаты</a></span></span>
										</div>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>Голосовать<input type="submit" name="polltopic" onclick="PollTopic('{tid}');return false;" value="Голосовать" /></span></span>
										</div>

									</td>
                                [/vote]  
                                [result]
                                <td></td>
									<td>
										<div class="butt butt_b">
											<span><span><a href="{poll_link}" id="poll_show" onclick="PollShow('{tid}');return false;" title="Перейти к голосованию">Перейти к голосованию</a></span></span>
										</div>
									</td> 
                                [/result]
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
				<!--category_block end-->
</form>