					<thead>
						<tr>
							<th><h6><b><a href="{profile_link}">{member_name}</a></b> <a href="#" onclick="ProfileInfo(this, '{member_id}');return false;"><img src="{TEMPLATE}/images/profile_window_icon.png" alt="mini-profile" /></a></h6></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
                    <tbody>
						<tr>
							<td>
								<a href="{profile_link}"><img src="{member_avatar}" border="0" /></a>
							</td>
							<td>
							     <ol>
                                    <li>������ �� ������: [online]<font color=blue>������</font>[/online][offline]<font color=red>�������</font>[/offline]</li>
                                    <li>������ ������: {personal_title}</li>
									<li>������: {member_group}</li>
								</ol>
							</td>
                            <td>
								<ol>
                                    <li>�����������: {reg_date}</li>
                                    <li>��������� ���������: {lastdate}</li>
								</ol>
							</td>
							<td>
								<ol>
									<li><a href="{pm_link}">������ ���������</a></li>
                                    <li><a href="{topics_link}">��� ����</a></li>
									<li><a href="{posts_link}">��� ���������</a></li>
								</ol>
							</td>
						</tr>
                    </tbody>