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
					<h3>�������������� ��������� �: {topic_title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>
                            
                            [topic_edit]
                            <thead>
								<tr>
									<th>����</th>
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
                                <tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
                            [/topic_edit]
                            
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
                                <tr>
									<td>������� ���� �����������:</td>
									<td>
									   <input type="checkbox" name="poll_log" value="1" /> <font class="smalltext">���������� �� ����������, �� ������������ ������ �������� ����������</font>
									</td>
								</tr>
                                <tr>
									<td>�������� ����������:</td>
									<td>
									   <input type="checkbox" name="poll_again" value="1" /> <font class="smalltext">���������� � ���� ����������� ���������</font>
									</td>
								</tr>
                                <tr>
									<td><font color="red">������� �����������:</font></td>
									<td>
									   <input type="checkbox" name="poll_del" value="1" />
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
									<th>���������</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>		
								<tr>
									<td>����������:</td>
									<td>{bbcode}
										<textarea name="text" id="tf" style="width:98%;height:150px;">{text}</textarea>
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
									<td style="padding-top:4px; padding-bottom:6px;"><div class="butt butt_b">
												<span><span>���������<input type="submit" name="add_file" value="���������" /></span></span>
										</div>
									</td>
								</tr>
                                [/attachment]
                                <tr>
									<td>������� ��������������:</td>
									<td>
										<textarea name="edit_reason" style="width:98%;height:30px;">{edit_reason}</textarea>
									</td>
								</tr>	
                                [moder_warning]
                                <tr>
									<td>�������������� ����������:</td>
									<td>
										<textarea name="moder_reason" style="width:98%;height:30px;">{moder_reason}</textarea>
									</td>
								</tr>
                                <tr>
									<td>����� ��������������:</td>
									<td>
										<a href="{moder_member_link}">{moder_member_name}</a> <input type="checkbox" name="change_moder" value="1" /> ������� ������
									</td>
								</tr>
                                [/moder_warning]								
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
                                        <div class="butt butt_b">
											<span><span>������������<input type="submit" name="preview" value="������������" /></span></span>
										</div>
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
</form>