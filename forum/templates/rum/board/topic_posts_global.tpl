{poll}

{pages}

[moder]<form method="post" name="moder_posts" action="{HOME_LINK}?do=board&op=post_edit_mass&secret_key={SECRET_KEY}">[/moder]
<div class="category_block cb_color">
<h3>{topic_title}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="reply_list reply_list_big">
{posts_fixed}
{posts}
<span id="newpost-out">&nbsp;</span>
</div>
</div>

[moder]
	<div class="admin_line cor5 cle">
			<fieldset>
				<div class="al_do">
					<select name="moder_posts" id="moder_sob" class="lbselect">
						<option value="0">Модерация отмеченных сообщений</option>
						{moder_comm}
					</select>			
					<div class="butt butt_b butt_disable" id="moder_but">
						<span><span>С отмеченными (0)<input type="submit" name="moder_act_post" value="С отмеченными (0)" disabled="disabled" /></span></span>
					</div>
				</div>
</form>
[posts_out]
<form method="post" name="moder_topic" action="{HOME_LINK}?do=board&op=topic_edit&secret_key={SECRET_KEY}">	
				<div class="al_options_mod">
					<select name="moder_topic" id="moder_options" class="lbselect">
                        <option value="0">Опции темы</option>
						{moder_topic}
					</select>
					<div class="butt butt_b">
						<span><span>Ок<input type="submit" name="moder_act_topic" value="Ок" /></span></span>
					</div>				
				</div>
<input type="hidden" name="topic_id" value="{id}" />
</form>
                
[/posts_out]
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
	<!--admin_line end-->
[/moder]

[author_topic]
<form method="post" name="moder_topic" action="{HOME_LINK}?do=board&op=topic_edit&secret_key={SECRET_KEY}">
	<div class="admin_line cor5 cle">
			<fieldset>
				<div class="al_options_mod" style="padding-right:10px;">
					<select name="moder_topic" id="moder_options" class="lbselect">
                        <option value="0">Опции темы</option>
						{moder_topic}
					</select>
					<div class="butt butt_b">
						<span><span>Ок<input type="submit" name="moder_act_topic" value="Ок" /></span></span>
					</div>				
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
	<!--admin_line end-->
    
<input type="hidden" name="topic_id" value="{id}" />
</form>
[/author_topic]

	{pages}

[share_links] 
<div style="clesr:left;">
Поделиться темой: {share_links}
</div>
[/share_links]

[posts_out] 
	<div class="admin_line_two cor5 cle">
			<fieldset>
                [global_not_group=5]
                <div class="al_options">
					<div class="butt butt_b butt_options">
						<span><span><a href="#" title="Опции темы">Опции темы</a></span></span>
					</div>
					<div class="alo_list">
						<ol>
							<li id="do_favorite">[link_favorite]{favorite_title}[/link_favorite]</li>
                            <li id="do_subscribe">[link_subscribe]{subscribe_title}[/link_subscribe]</li>
                            <li>{link_utility}</li>
						</ol>
					</div>			
				</div>	
                [/global_not_group]	
                
                <form action="{HOME_LINK}?" name="fast_forum" method="get">
                <input type="hidden" name="do" value="board" />
                <input type="hidden" name="op" value="forum" />		
				<div class="al_do">
					<select name="id" class="lbselect" id="fast_forum">{fast_forum}</select>			
					<div class="butt">
						<span><span>Перейти<input type="submit" value="Перейти" /></span></span>
					</div>
				</div>
                </form>
                
                <form action="{HOME_LINK}?" name="search_topic" method="get">
                <input type="hidden" name="do" value="search" />
                <div class="al_do_s">
                    <input type="hidden" name="ms" value="0" />
                    <input type="hidden" name="sf" value="0" />
                    <input type="hidden" name="t_id" value="{id}" />
                    <input type="hidden" name="ts" value="2" />
                    <input type="hidden" name="p" value="1" />
                    <input type="hidden" name="sr" value="0" />
                    <input type="hidden" name="so" value="0" />
					<input type="text" name="w" value="" placeholder="поиск в этой теме" id="ul_inputsearch" class="work_table_tarea inputsearch" />
					<div class="butt">
						<span><span>Найти<input type="submit" name="moder_act_topic" value="Найти" /></span></span>
					</div>
				</div>
                </form>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
    
{form}
[/posts_out]