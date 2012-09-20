<form  method="post" name="editpost" action="">
				<div class="category_block cb_color">
					<h3>{title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>
                            {post_edit_form}
	                       <tbody>								
								<tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>Сохранить<input type="submit" name="editpost" value="Сохранить" /></span></span>
										</div>
									</td>
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
                <input type="hidden" name="moder_posts" value="{moder_posts}" />
                [mass_union]<input type="hidden" name="topic_id" value="{topic_id}" />
                {pid}
                [/mass_union]
</form>