<form method="post" name="search_form" action="">
    			<div class="category_block cb_color">
					<h3>[hide_table]<a href="#" onclick="ShowAndHide('hide_table'); return false;">[/hide_table]����� ������[hide_table]</a>[/hide_table]<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
                    [hide_table]<div id="hide_table" style="display:none;">[/hide_table]
					<div class="work_table">
						<table>
						<!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
							<colgroup>
								<col width="15%" />
								<col width="85%" />
							</colgroup>
                            <thead>
								<tr>
									<th><strong>��� ������</strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>	
								<tr>
									<td>����� �:</td>
									<td>
										{mod_search}
									</td>
								</tr>
                                <tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
                            
							<thead>
								<tr>
									<th><strong>�����</strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>	
								<tr>
									<td>�������� �����:<br /><font class="smalltext">���� ����� � ������������� - �����</font></td>
									<td>
										<input type="text" name="w" value="{word}" style="width:200px" /> <font class="smalltext">����������� ���������� ��������: {slimit}</font>
									</td>
								</tr>
                                <tr>
									<td>����� �� ����:<br /><font class="smalltext">�������������� ����</font></td>
									<td>
										<input type="text" name="dst" value="{date_st}" style="width:100px" /> <b>-</b> <input type="text" name="dend" value="{date_end}" style="width:100px" /> <font class="smalltext">������ ����: 12.10.2010 14:33 ��� 12.10.2010</font>
									</td>
								</tr>
                                <tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
                            <thead>
								<tr>
									<th><strong><a href="#" onclick="ShowAndHide('search_board'); return false;" title="�������/������� ����� ������ �� �����������">����������</a></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody style="display:none;" id="search_board">
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>   
                                <tr>
									<td>�����:<br /><font class="smalltext">�������������� ����</font></td>
									<td>
										<input type="text" name="a" value="{author}" style="width:200px" /> <font class="smalltext">����������� ���������� ��������: {slimit}</font>
									</td>
								</tr> 
                                <tr>
									<td>������ �:</td>
									<td>
										{type_search}
									</td>
								</tr>	
                                <tr>
									<td>������:<br /><font class="smalltext">���� ���� "ID ���" ��������� - �� ������ ����� ����� ��������������.</font></td>
									<td>
                                        <input type="checkbox" name="sf" value="1" {no_subforum} /> ������ ������ � ��������� �������?<br />
										{forums_list}
									</td>
								</tr>
                                <tr>
									<td>ID ���:</td>
									<td>
                                        <input type="text" name="t_id" value="{topics_id}" style="width:200px" /> <font class="smalltext">������� ID ��� ����� �������, � ������� ����������� �����.</font>
									</td>
								</tr>
								<tr>
									<td>�������� ����������:</td>
									<td>
										{preview}
									</td>
								</tr>
                                <tr>
									<td>����������� ��:</td>
									<td>
										{sort_result} {sort_order}
									</td>
								</tr>
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
                            
                            <thead>
								<tr>
									<th><strong><a href="#" onclick="ShowAndHide('search_member'); return false;" title="�������/������� ����� ������ �� �������������">������������</a></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody style="display:none;" id="search_member">
								<tr class="wt_top">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>    
                                <tr>
									<td>������:</td>
									<td>
										{member_group}
									</td>
								</tr>	
                                <tr>
									<td>�����:</td>
									<td>
										<input type="text" name="mt" value="{member_town}" style="width:200px" />
									</td>
								</tr>
								<tr>
									<td>��������� ���:</td>
									<td>
										<input type="text" name="mn" value="{member_name}" style="width:200px" />
									</td>
								</tr>
                                <tr>
									<td>����������� ��:</td>
									<td>
										{sort_member_result} {sort_member_order}
									</td>
								</tr>         
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>	
							</tbody>
                            <tbody>
                                <tr class="wt_last">
									<td>
									</td>
									<td>
										<div class="butt butt_b">
											<span><span>������<input type="submit" name="do_search" value="������" /></span></span>
										</div>
									</td>
								</tr>	
							</tbody>
						</table>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
						<div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
					</div>
                    [hide_table]</div>[/hide_table]
				</div>
				<!--category_block end-->
</form>