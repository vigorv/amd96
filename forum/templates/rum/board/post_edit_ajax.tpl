<form method="post" name="editpost_ajax-{id}" id="editpost_ajax-{id}" action="">
<table width="100%">
<tr><td colspan="2">{bbcode}<textarea name="text" class="work_table_tarea" id="tf-{id}" onclick="SetNewField(this.id);" style="width:98%;height:150px;">{text}</textarea><br /><br /></td></tr>
</table>
<table width="100%">
<tr><td width="180" valign=top>Причина редактирования:</td><td><textarea name="edit_reason" class="work_table_tarea" id="er-{id}" style="width:98%;height:30px;">{edit_reason}</textarea><br /><br /></td></tr>
[moder_warning]
<tr><td width="180" valign=top>Предупреждение модератора:</td><td><textarea name="moder_reason" class="work_table_tarea" id="mr-{id}" style="width:98%;height:30px;">{moder_reason}</textarea><br /><br /></td></tr>
<tr><td width="180">Автор предупреждения:</td><td><a href="{moder_member_link}">{moder_member_name}</a> <input type="checkbox" name="change_moder" id="cm-{id}" value="1" /> Сменить автора</td></tr>
[/moder_warning]
<tr>
<td style="padding-top:10px;">
<div class="butt butt_b">
<span><span>Отменить<input type="button" name="stopeditpost-{id}" onclick="EditStopPost('{id}');return false;" value="Отменить" /></span></span>
</div>
</td>
<td style="padding-top:10px;">
<div class="butt">
<span><span>Сохранить<input type="button" name="editpost-{id}" onclick="EditSavePost('{id}');return false;" value="Сохранить" /></span></span>
</div>
</td>
</tr>
</table>
</form>