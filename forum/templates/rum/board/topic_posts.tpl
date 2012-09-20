                    <a id="post{pid}"></a>
					<div class="rl_item [hide_post]rl_item_hide[/hide_post]">
						<div class="rl_user_big">
							<div class="rl_user_big_in cle">
                                [member_post]
								<div class="rlu_name"><a href="{profile_link}">{member_name}</a></div>
                                <div class="rlu_char">
									<a href="{profile_link}"><img src="{member_avatar}"/></a>								
									[ranks]<span>{ranks_title}</span>
									<center>{ranks_starts}</center>[/ranks]
                                    {member_group_icon}
								</div>						
								<ol>
                                    <li>[online]<font color=blue>Онлайн</font>[/online][offline]<font color=red>Оффлайн</font>[/offline]</li>
									<li>{member_group}</li>
									<li>{member_posts} сообщений</li>
									<li class="rl_us_mail"><a href="{pm_link}" title="оставить сообщение">Сообщение<i></i></a></li>
									<li class="rl_us_info"><a href="{profile_link}" onclick="ProfileInfo(this, '{member_id}');return false;" title="посмотреть личные данные">Личные&nbsp;данные<i></i></a></li>
                                    [ip]<li>IP: {ip}</li>[/ip]
								</ol>
                                [/member_post]
                                [guest_post]
								<div class="rlu_name">{member_name}</div>
                                <div class="rlu_char">
									<img src="{member_avatar}"/>	
                                    {member_group_icon}						
								</div>						
								<ol>
									<li>{member_group}</li>
                                    [ip]<li>IP: {ip}</li>[/ip]
								</ol>
                                [/guest_post]
							</div>
						</div>
						<div class="rl_head cle">
							<ol class="rl_buts">
                                [moder_mass]<li><input type="checkbox" name="posts[]" value="{pid}" class="counter" /></li>[/moder_mass]
							</ol>
                            <span class="rl-date">
                            Полезность: [utility]<a href="#" onclick="Utility('{pid}');return false;" title="Полезное сообщение!">[/utility]{utility}[utility]</a>[/utility]
                            [complaint] | 
                            <a href="#" onclick="Mini_Window(this, '355');return false;" title="Пожаловаться на сообщение!">Жалоба</a>
                            <span class="mini_window_content">
                            <textarea name="text" id="complaint_{pid}" style="width:350px;height:35px;margin-bottom:5px;"></textarea><br />
                            <center><input type="button" class="mini_window_button" onclick="Complaint({complaint_data});return false;" value="Отправить" /></center>
                            </span>
                            [/complaint]
                             | 
                            [full_link]{forum} &raquo; <a href="{topic_link}">{topic}</a><br />[/full_link][hide_post]<em>скрытое [/hide_post]сообщение <a href="{link_on_post}" class="link_on_post" title="Ссылка на сообщение">№ {post_id}</a>[hide_post]</em>[/hide_post] отправлено {post_date}
                            [post_log][ <a href="#" onclick="window.open('{HOME_LINK}?do=system_info&op=log_post&id={pid}','Лог действий с темой','width=700,height=500,toolbar=1,location=0,scrollbars=1'); return false;" title="Логи действий">Лог</a> ]</span>[/post_log]
                            </span>
						</div>
						<div class="rl_body" id='post-{pid}'>
                            [full_link]<br />[/full_link]
							{post_text}
                            [last_edit_post]
                            <br /><font class="rl-date">
                            Последний раз редактировал <a href="{edit_member_link}">{edit_member}</a> {edit_date}
                            [edit_reason]<br />Причина редактирования: {edit_reason}[/edit_reason]
                            </font>
                            [/last_edit_post]
                            [moder_warning]
                            <br /><br />
                            Предупреждение модератора: <a href="{moder_member_link}">{moder_member_name}</a> {moder_date}<br/>
                            {moder_reason}
                            
                            [/moder_warning]
                            
                            [signature]<br /><br /><font class="smalltext">------------------------------------------</font><br />{signature}[/signature]
						</div>
                        <div class="rl_buts_down cle">
							<ol>
                                [hide_post_moder]	
								<li>
                                    [un_hide_post]
									<div class="butt butt_disable" id="post-showhide-{pid}">
										<span><span><a href="{post_showhide_link}" onclick="ShowHidePost('{pid}');return false;" title="Скрыть">Скрыть</a></span></span>
									</div>
                                    [/un_hide_post]
                                    [hide_post]
                                    <div class="butt" id="post-showhide-{pid}">
										<span><span><a href="{post_showhide_link}" onclick="ShowHidePost('{pid}');return false;" title="Показать">Показать</a></span></span>
									</div>
                                    [/hide_post]
								</li>
                                [/hide_post_moder]
                                [edit_post]			
								<li>
									<div class="butt butt_disable" id="bop{pid}">
										<span><span><a href="{post_edit_link}" title="Изменить" onclick="PostMenu('{pid}');return false;">Изменить</a></span></span>
									</div>
                                    <div class="alo_list">
                                    <ol>
                                        <li><a id="post-edit-{pid}" href="#" onclick="EditPost('{pid}');return false;" title="Быстрое редактирование">Быстрое редактирование</a></li>
                                        <li><a href="{post_edit_link}" title="Полное редактирование">Полное редактирование</a></li>
                                    </ol>
                                    </div>			
								</li>
                                [/edit_post]
                                [fixed_post_moder]	
								<li>
                                    [unfixed_post]
									<div class="butt" id="post-unfixed-{pid}">
										<span><span><a href="{post_unfixed_link}" onclick="UnfixedPost('{pid}');return false;" title="Открепить">Открепить</a></span></span>
									</div>
                                    [/unfixed_post]
                                    [fixed_post]
                                    <div class="butt butt_disable" id="post-fixed-{pid}">
										<span><span><a href="{post_fixed_link}" onclick="FixedPost('{pid}');return false;" title="Закрепить">Закрепить</a></span></span>
									</div>
                                    [/fixed_post]
								</li>
                                [/fixed_post_moder]	
                                [answer_but]
                                <li>
									<div class="butt">
										<span><span><a href="{reply_link}" title="Ответить">Ответить</a></span></span>
									</div>
								</li>
                                [/answer_but]
                                [del_post]
                                <li>
									<div class="butt butt_disable" id="post-delete-{pid}">
										<span><span><a href="{post_del_link}" onclick="DeletePost('{pid}');return false;" title="Удалить">Удалить</a></span></span>
									</div>
								</li>	
                                [/del_post]	
                                [answer_but]
                                <li>
									<div class="butt butt_disable">
										<span><span><a href="#" onclick="quote('post-{pid}', '{member_name}', '{post_date2}'); return false;" title="Цитировать">Цитировать</a></span></span>
									</div>
								</li>
                                [/answer_but]
							</ol>
						</div>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
					</div>	