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
                                    <li>[online]<font color=blue>������</font>[/online][offline]<font color=red>�������</font>[/offline]</li>
									<li>{member_group}</li>
									<li>{member_posts} ���������</li>
									<li class="rl_us_mail"><a href="{pm_link}" title="�������� ���������">���������<i></i></a></li>
									<li class="rl_us_info"><a href="{profile_link}" onclick="ProfileInfo(this, '{member_id}');return false;" title="���������� ������ ������">������&nbsp;������<i></i></a></li>
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
								</ol>
                                [/guest_post]
							</div>
						</div>
						<div class="rl_head cle">
                            <span class="rl-date">[hide_post]<em>������� [/hide_post]��������� � {post_id}[hide_post]</em>[/hide_post] ���������� {post_date}</span>
						</div>
						<div class="rl_body" id='post-{pid}'>
							{post_text}
                            [last_edit_post]
                            <br /><br />
                            <font class="rl-date">
                            ��������� ��� ������������ <a href="{edit_member_link}">{edit_member}</a> {edit_date}
                            [edit_reason]<br />������� ��������������: {edit_reason}[/edit_reason]
                            </font>
                            [/last_edit_post]
                            [moder_warning]
                            <br /><br />
                            
                            �������������� ����������: <a href="{moder_member_link}">{moder_member_name}</a> {moder_date}<br/>
                            {moder_reason}
                            
                            [/moder_warning]
						</div>
                        <div class="rl_buts_down cle">
                            <ol>
								<li>
									<div class="butt butt_disable">
										<span><span><a href="#" onclick="quote('post-{pid}', '{member_name}', '{post_date2}'); return false;" title="����������">����������</a></span></span>
									</div>
								</li>
                            </ol>
                        </div>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
					</div>	