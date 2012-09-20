[moder_warning]
<form action="" name="moder_form" method="post">
<input type="hidden" name="secret_key" value="{SECRET_KEY}" />
<input type="hidden" name="del_warning" value="1" />
[/moder_warning]

				<div class="category_block cb_color">
					<h3>История предупреждений {member_name}<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
                    <div class="forum_table">
                        <table>
                        <!--[if lt IE 9]></table><table cellspacing="0"><![endif]-->
                        <colgroup>
						  <col width="130" />
						  <col />
                          <col />
					    </colgroup>
                        <thead>
						<tr>
							<th>Модератор</th>
							<th>Дата</th>
                            <th>Сообщение</th>
                            <th>Статус</th>
                            [moder_warning]<th width="30"></th>[/moder_warning]
						</tr>
					    </thead>
                        <tbody>
                            {history}
                        </tbody>
                        </table>
				    <div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				    <div class="co co5 co-ft"><div class="tl"></div><div class="tr"></div></div>
                    </div>				
				</div>
                
                [moder_warning]
   	<div class="admin_line cor5 cle">
			<fieldset>				
				<div class="al_do">	
                    <select name="w_select" class="lbselect">{w_select}</select>
                    <div class="butt butt_b butt_disable" id="moder_but">
						<span><span>С отмеченными (0)<input type="submit" value="С отмеченными (0)" disabled="disabled" /></span></span>
					</div>
				</div>
			</fieldset>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
    <!--admin_line end-->
</form>
                [/moder_warning]
