						<tr [hiden]class="ft_red"[/hiden]>
							<td>
								<img src="{TEMPLATE}/images/topic/{ico}.png" width="25" height="20" alt="{alt_topic}" />
							</td>
							<td class="ft_topic_name">
                                [topic_log]<span class="ft_topic_prev"><a href="#" onclick="window.open('{HOME_LINK}?do=system_info&op=log_topic&id={topic_id}','��� �������� � �����','width=700,height=500,toolbar=1,location=0,scrollbars=1'); return false;" title="���� ��������"><img src="{TEMPLATE}/images/topic/topicpreview.png" width="16" height="16" alt="���� ��������" /></a></span>[/topic_log]
								<h6>[fixed]<img src="{TEMPLATE}/images/topic/alarm.png" width="14" height="14" alt="�����!" />[/fixed]<a href="{link}" title="{description}">{title}</a> [topic_nav]<font class="smalltext">��������: [ {topic_nav} ]</font>[/topic_nav]</h6>[full_link]<font class="smalltext">�����: {forum}</font>[/full_link]
							</td>
							<td>[member_topic]<a href="{author_link}" title="�������� �������">[/member_topic]{author}[member_topic]</a>[/member_topic]</td>
							<td>
								<ol>
									<li><a href="{link_last}">{answers} �������</a> [hide_post]<a href="{link_hide}" title="������� � ������� ����������"><img src="{TEMPLATE}/images/topic/alarm.png" width="10" height="10" alt="������ {post_hiden} ���������!" /></a>[/hide_post]</li>
									<li>{views} ����������</li>
								</ol>
							</td>
							<td>
								<dl>
									<dt class="ft_date cle"><span>{date}</span><a href="{link_last}" title="�������� ���������� ���������"></a></dt>
									<dt>�����:</dt>
									<dd>[member_post]<a href="{last_author_link}" title="�������� �������">[/member_post]{last_author}[member_post]</a>[/member_post]</dd>
								</dl>
							</td>
							[moder_line]<td class="ft_che"><input type="checkbox" name="topics[]" value="{topic_id}" class="counter" /></td>[/moder_line]
						</tr>