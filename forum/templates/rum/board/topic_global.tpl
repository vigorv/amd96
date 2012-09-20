[topics_out]
<div class="buts_line cle">
<div class="butt">
<span><span><a href="{new_topic}" title="Создать новую тему">Создать новую тему</a></span></span>
</div>	
</div>
[/topics_out]

[moder_line]<form action="{HOME_LINK}?do=board&op=moderation&secret_key={SECRET_KEY}" name="moder_form" method="post">[/moder_line]
<div class="category_block cb_color">
<h3>{category_name}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="forum_table">
    <table>
    <!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
    <colgroup>
        <col width="3%" />
        <col width="55%" />
        <col width="10%" />
        <col width="11%" />
        <col width="16%" />
        [moder_line]<col width="5%" />[/moder_line]
    </colgroup>
    <thead>
    <tr>
        <th></th>
        <th>Тема</th>
        <th>Автор</th>
        <th>Статистика</th>
        <th>Последнее сообщение</th>
        [moder_line]<th></th>[/moder_line]
        </tr>
    </thead>
    <tbody>
    {topics}
    </tbody>
    </table>
    <div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
    <div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
</div>
</div>

[topics_out]
<div class="buts_line cle">
<div class="butt">
<span><span><a href="{new_topic}" title="Создать новую тему">Создать новую тему</a></span></span>
</div>	
</div>
[/topics_out]

[moder_line]
	<div class="admin_line cor5 cle">
			<fieldset>
				<div class="al_options">
					<div class="butt butt_b butt_options">
						<span><span><a href="#" title="Опции форума">Опции форума</a></span></span>
					</div>
					<div class="alo_list">
						<ol>
							{forum_options}
						</ol>
					</div>			
				</div>
				
				<div class="al_do">
					<select id="sel_name" name="act" class="lbselect" id="forum_opt_t">{forum_options_topics}</select>			
					<div class="butt butt_disable" id="moder_but">
						<span><span>С отмеченными (0)<input type="submit" name="action" value="С отмеченными (0)" disabled="disabled" /></span></span>
					</div>
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
    <!--admin_line end-->
</form>
[/moder_line]

			{pages}
            
[topics_out]          
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
[/topics_out]