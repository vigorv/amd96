							<thead>
								<tr>
									<th></th>
									<th>����: {topic}</th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td></td>
									<td></td>
								</tr>
                                <tr>
									<td>����� ���������:</td>
									<td>
                                        <select name="author" id="ex1" class="lbselect">
										{author_op}
                                        </select>
									</td>
								</tr>
                                <tr>
									<td>���� ���������:</td>
									<td>
                                        <select name="datepost" id="ex2" class="lbselect">
										{date_op}
                                        </select>
									</td>
								</tr>		
								<tr>
									<td>����������:</td>
									<td>
                                        {bbcode}
										<textarea name="text" id="tf" style="width:98%;height:150px;">{text}</textarea>
									</td>
								</tr>
                                <tr>
									<td>������� ��������������:</td>
									<td>
										<textarea name="edit_reason" style="width:98%;height:30px;"></textarea>
									</td>
								</tr>										
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>