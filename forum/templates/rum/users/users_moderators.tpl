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
                                    <li>Статус на форуме: [online]<font color=blue>Онлайн</font>[/online][offline]<font color=red>Оффлайн</font>[/offline]</li>
                                    <li>Личное звание: {personal_title}</li>
									<li>Группа: {member_group}</li>
								</ol>
							</td>
                            <td>
								<ol>
                                    <li>Регистрация: {reg_date}</li>
                                    <li>Последние посещение: {lastdate}</li>
								</ol>
							</td>
							<td>
								<ol>
									<li><a href="{pm_link}">Личное сообщение</a></li>
                                    <li><a href="{topics_link}">Все темы</a></li>
									<li><a href="{posts_link}">Все сообщения</a></li>
								</ol>
							</td>
						</tr>
                    </tbody>