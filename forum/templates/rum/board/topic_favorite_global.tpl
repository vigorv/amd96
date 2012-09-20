<form action="" name="moder_form" method="post">
<input name="secret_key" value="{SECRET_KEY}" type="hidden" />
<div class="category_block cb_color">
<h3>Избранные темы<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="forum_table">
    <table>
    <!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
    <colgroup>
        <col width="3%" />
        <col width="55%" />
        <col width="10%" />
        <col width="11%" />
        <col width="16%" />
        <col width="5%" />
    </colgroup>
    <thead>
    <tr>
        <th></th>
        <th>Тема</th>
        <th>Автор</th>
        <th>Статистика</th>
        <th>Последнее сообщение</th>
        <th></th>
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

	<div class="admin_line cor5 cle">
			<fieldset>				
				<div class="al_do">
					<select id="sel_name" name="act" class="lbselect">
                    <option value="1">Удалить выбранное</option>
                    <option value="2">Удалить всё</option>
                    </select>			
					<div class="butt butt_disable" id="moder_but">
						<span><span>С отмеченными (0)<input type="submit" name="action" value="С отмеченными (0)" /></span></span>
					</div>
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
    <!--admin_line end-->
</form>

			{pages}