							<thead>
								<tr>
									<th>Автор: {author}</th>
									<th>Тема: {topic}</th>
								</tr>
							</thead>
							<tbody>
								<tr class="wt_top">
									<td></td>
									<td></td>
								</tr>		
								<tr>
									<td>Сообщение:</td>
									<td>{bbcode}
										<textarea name="text_{pid}" id="tf{pid}" style="width:98%;height:150px;" onclick="SetNewField(this.id);" >{text}</textarea>
									</td>
								</tr>
                                <tr>
									<td>Причина редактирования:</td>
									<td>
										<textarea name="edit_reason_{pid}" style="width:98%;height:30px;">{edit_reason}</textarea>
									</td>
								</tr>
                                [moder_warning]
                                <tr>
									<td>Предупреждение модератора:</td>
									<td>
										<textarea name="moder_reason_{pid}" style="width:98%;height:30px;">{moder_reason}</textarea>
									</td>
								</tr>
                                <tr>
									<td>Автор предупреждения:</td>
									<td>
										<a href="{moder_member_link}">{moder_member_name}</a> <input type="checkbox" name="change_moder_{pid}" value="1" /> Сменить автора
									</td>
								</tr>
                                [/moder_warning]										
								<tr class="wt_pre_last">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="wt_last">
									<td>
									</td>
									<td>
                                        <input type="checkbox" name="posts[]" value="{pid}" /> Сохранить изменения
									</td>
								</tr>	
							</tbody>