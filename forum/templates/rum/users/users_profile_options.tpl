<form  method="post" name="editprofile" action="">
				<div class="category_block cb_color">
					<h3>��������� ������: {member_name}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
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
									<td>���������� � ����� �� �� E-Mail:<br /><font class="smalltext">����� ������� ����� ���������</font></td>
									<td>{pmtoemail_op}</td>
								</tr>	
                                <tr>
									<td>���������� �� ������� � ����� ��:<br /><font class="smalltext">����������� � ����������� �����</font></td>
									<td>{subscribe_op}</td>
								</tr>
                                <tr>
									<td>��� ������ ������:<br /><font class="smalltext">�������� ����� �����������</font></td>
									<td>{online_op}</td>
								</tr>
                                [posts_ajax]
                                <tr>
									<td>�������� ���������:<br /><font class="smalltext">�������������� �������� ��������� � ������ ������� ����</font></td>
									<td>{posts_ajax}</td>
								</tr>
                                [/posts_ajax]
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>���������<input type="submit" name="editprofile" value="���������" /></span></span>
                                            <input type="hidden" name="secret_key" value="{SECRET_KEY}" />
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

   	<div class="admin_line cor5 cle">
			<fieldset>
				<div class="al_options">
					<div class="butt butt_b butt_options">
						<span><span><a href="#" title="����� �������">����� �������</a></span></span>
					</div>
					<div class="alo_list">
						<ol>
                            [subscribe]<li><a href="{subscribe}" title="�������� �� ����">�������� �� ����</a></li>[/subscribe]
                            [edit_status]<li><a href="{profile_edit_status}" title="������ ������">������ ������</a></li>[/edit_status]
                            <li><a href="{profile_edit_options}" title="��������� ������">��������� ������</a></li>
						</ol>
					</div>
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>