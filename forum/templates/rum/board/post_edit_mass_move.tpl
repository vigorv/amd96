<form  method="post" name="editpost" action="">
				<div class="category_block cb_color">
					<h3>������� ��������� �� ����: {topic_title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
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
									<td>������� ID ����:</td>
									<td>
										<input type="text" name="move_id"  style="width:200px" /> <font class="smalltext">ID ���� ����� ������ � �������� ������</font>
									</td>
								</tr>
                                [new_date]
                                <tr>
									<td>�������� ���� ���������:</td>
									<td>
										<input type="checkbox" name="new_date" value="1" /> <font class="smalltext">����� ������� ��������� ����� ���������� � ���� � ������� ����� (������� � �������)</font>
									</td>
								</tr>
                                [/new_date]										
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>���������<input type="submit" name="editpost" value="���������" /></span></span>
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
            {pid}
</form>

<div class="category_block cb_color">
<h3>��������� ��� ��������<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="reply_list reply_list_big">
<ol>
{posts}
</ol>
</div>
</div>