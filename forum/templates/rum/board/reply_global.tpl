<span id="preview-topic"></span>
[preview]
				<div class="category_block cb_color">
					<h3>������������<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>                            
							<thead>
								<tr>
									<th><strong>����������</strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>���������:</td>
									<td class="preview_text">{text_pr}</td>
								</tr>							
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>

							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
				<!--category_block end-->
[/preview]

<form  method="post" name="newtopic" action="" ENCTYPE="multipart/form-data">
				<div class="category_block cb_color">
					<h3>����������� ����� � ����: {topic_title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
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
                                [global_group=5]
                                <tr>
									<td>���� ���:</td>
									<td>
										<input type="text" name="guest_name" id="guest_name" value="{guest_name}" style="width:220px" />
									</td>
								</tr>
                                [/global_group]
                                [captcha]
                                <tr>
									<td>���:</td>
									<td>
										{captcha}
									</td>
								</tr>	
								<tr>
									<td>������� ���:</td>
									<td>
										<input type="text" name="keystring" id="keystring" style="width:220px" />
									</td>
								</tr>
                                [/captcha]	
                                [captcha_dop]
                                <tr>
									<td>������:</td>
									<td>
										{captcha_dop}
									</td>
								</tr>	
								<tr>
									<td>�����:</td>
									<td>
										<input type="text" name="keystring_dop" id="keystring_dop" style="width:220px" />
									</td>
								</tr>
                                [/captcha_dop]
								<tr>
									<td>���������:</td>
									<td>{bbcode}
										<textarea name="text" id="tf" style="width:98%;height:150px;" onchange="SaveText('tf', '{tid}');">{quote}</textarea>
									</td>
								</tr>
                                [attachment]
                                <tr>
									<td>&nbsp;</td>
                                    <td>{attachments}</td>
                                </tr>	
                                <tr>
									<td>���������� ����:</td>
									<td>
                                        <input type="file" name="attachment[]" class="work_table_tarea" style="width:270px;" />
                                        <div id="add_file_block"></div>
                                    </td>
								</tr>
                                <tr>
    				                <td>&nbsp;</td>
    				                <td><a href="#" id="add_file_jq">�������� ��� ����</a> :: <a href="#" id="remove_file_jq">������� ��������� ����</a></td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td><div class="butt butt_b">
												<span><span>���������<input type="submit" name="add_file" value="���������" /></span></span>
										</div>
									</td>
								</tr>
                                [/attachment]									
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
                                        <div class="butt butt_b">
											<span><span>������������<input type="submit" name="preview" onclick="Preview();return false;" value="������������" /></span></span>
										</div>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>���������<input type="submit" name="addpost" value="���������" /></span></span>
										</div>
									</td>
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
				</div>
</form>

<div class="category_block cb_color">
<h3>��������� 10 �������<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="reply_list reply_list_big">
{posts}
</div>
</div>