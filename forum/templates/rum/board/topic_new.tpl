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

<form method="post" name="newtopic" action="" ENCTYPE="multipart/form-data">
				<div class="category_block cb_color">
					<h3>����� ���� �� ������: {forum_title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>
                            [metadata]
						    <thead>
								<tr>
									<th><strong><a href="#" onclick="ShowAndHide('hide_metadata'); return false;">����������</a></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="hide_metadata">
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>����-��� title:</td>
									<td>
									   <input type="text" name="meta_title" value="{meta_title}" style="width:496px" />
									</td>
								</tr>
                                <tr>
									<td>��������:</td>
									<td>
									   <input type="text" name="meta_description" value="{meta_description}" style="width:496px" />
									</td>
								</tr>
                                <tr>
									<td>�������� �����:</td>
									<td>
									   <textarea name="meta_keywords" style="width:500px;height:60px;">{meta_keywords}</textarea>
									</td>
								</tr>	
                                <tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
                            </tbody>
                            [/metadata]
                            
                            [poll]
						    <thead>
								<tr>
									<th><strong><a href="#" onclick="ShowAndHide('hide_poll'); return false;">�����</a></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="hide_poll">
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>���������:</td>
									<td>
									   <input type="text" name="poll_title" value="{poll_title}" style="width:296px" />
									</td>
								</tr>
                                <tr>
									<td>������:</td>
									<td>
									   <input type="text" name="poll_question" value="{poll_question}" style="width:296px" />
									</td>
								</tr>
                                <tr>
									<td>��������� ��������� �������:</td>
									<td>
									   <input type="checkbox" name="poll_mult" value="1" {poll_mult} />	
									</td>
								</tr>	
								<tr>
									<td>������:<br /><font class="smalltext">������ ����� �� ����� ������</font></td>
									<td>
										<textarea name="variants" style="width:300px;height:100px;">{variants}</textarea>
									</td>
								</tr>
                                <tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
                            </tbody>
                            [/poll]
                            
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
                                [global_group=5]
                                <tr>
									<td>���� ���:</td>
									<td>
										<input type="text" name="guest_name" id="guest_name" value="{guest_name}" style="width:296px" />
									</td>
								</tr>
                                [/global_group]		
								<tr>
									<td>��������:</td>
									<td>
									   <input type="text" name="title" value="{title}" style="width:296px" />
									</td>
								</tr>		
								<tr>
									<td>��������:</td>
									<td>
										<input type="text" name="desc" value="{desc}" style="width:296px" />
									</td>
								</tr>	
								<tr>
									<td>���������:</td>
									<td>{bbcode}
										<textarea name="text" id="tf" style="width:98%;height:150px;">{text}</textarea>
                                        [global_not_group=5]
                                        <br />
                                        <input type="checkbox" id="subscribe_box" name="subscribe" value="1" /> <label for="subscribe_box">����������� �� ������</label>
                                        [/global_not_group]
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
											<span><span>���������<input type="submit" name="newtopic" value="���������" /></span></span>
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