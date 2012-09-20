<div class="category_block cb_color">
<h3>{title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="reply_list reply_list_big">
					<div class="rl_item">
                        <div class="rl_user_big">
							<div class="rl_user_big_in cle">
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
								</ol>
							</div>
						</div>

						<div class="rl_head cle">
                            <span class="rl-date">сообщение № {notice_id} отправлено {post_date}</span>
						</div>
						<div class="rl_body">
							{post_text}
						</div>
                        [edit_post]
                        <div class="rl_buts_down cle">
                            <ol>
                                			
								<li>
									<div class="butt butt_disable">
										<span><span><a href="{notice_edit_link}" title="Изменить в Центре управления">Изменить</a></span></span>
									</div>
								</li>
							</ol>
                        </div>
                        [/edit_post]
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
					</div>	
</div>
</div>

<form action="{HOME_LINK}?" name="fast_forum" method="get">
<input type="hidden" name="do" value="board" />
<input type="hidden" name="op" value="forum" />
	<div class="admin_line_two cor5 cle">
			<fieldset>		
				<div class="al_do">
					<select name="id" class="lbselect" id="fast_forum">{fast_forum}</select>			
					<div class="butt">
						<span><span>Перейти<input type="submit" value="Перейти" /></span></span>
					</div>
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
</form>