[group=1]

<input class="bbcodes" style="font-size: 11px; float: right;" title="Настройка чата" onclick="iChatAdmin(); return false;" type="button" value="Настройка чата" />
<br />&nbsp;

[/group]

<div id="iChat-style" style="width:max;height:385px; overflow:auto;"><div id="iChat-messages" align="left">{messages}</div></div><br />

[editor_form]

<div class="iChat_editor">

[group=5]
<td><input type="text" maxlength="35" name="name" id="name" class="iChat_input" value="{name}" onblur="if(this.value=='') this.value='{name}';" onfocus="if(this.value=='{def_name}') this.value='';" /></td>		
<td><input type="text" maxlength="35" name="mail" id="mail" class="iChat_input" value="{mail}" onblur="if(this.value=='') this.value='{mail}';" onfocus="if(this.value=='{def_mail}') this.value='';" /></td>
[/group]

<div class="iChat_bbeditor">

<span id="b_b" onclick="iChat_simpletag('b')"><img title="Полужирный" src="{THEME}/img/bbcode/b.png" alt="" /></span>
<span id="b_i" onclick="iChat_simpletag('i')"><img title="Наклонный текст" src="{THEME}/img/bbcode/i.png" alt="" /></span>
<span id="b_u" onclick="iChat_simpletag('u')"><img title="Подчеркнутый текст" src="{THEME}/img/bbcode/u.png" alt="" /></span>
<span id="b_s" onclick="iChat_simpletag('s')"><img title="Зачеркнутый текст" src="{THEME}/img/bbcode/s.png" alt="" /></span>

<img class="bbspacer" src="{THEME}/img/bbcode/brkspace.png" alt="" />

<span id="b_emo" onclick="iChat_ins_emo(this);"><img title="Вставка смайликов" src="{THEME}/img/bbcode/emo.png" alt="" /></span>

[allow_url]
<span id="b_quote" onclick="iChat_tag_leech()"><img title="Вставка защищенной ссылки" src="{THEME}/img/bbcode/link.png" alt="" /></span>
[/allow_url]

<span id="b_color" onclick="iChat_ins_color(this);"><img title="Цвет текста" src="{THEME}/img/bbcode/color.png" alt="" /></span>
<!-- <span id="b_hide" onclick="iChat_simpletag('hide')"><img title="Скрытый текст" src="{THEME}/img/bbcode/hide.png" alt="" /></span> -->
<span id="b_quote" onclick="iChat_simpletag('quote')"><img title="Вставка цитаты" src="{THEME}/img/bbcode/quote.png" alt="" /></span>
<span id="b_translit" onclick="iChat_translit()"><img title="Преобразовать выбранный текст из транслитерации в кириллицу" src="{THEME}/img/bbcode/translit.png" alt="" /></span>

<div class="clr"></div>

</div>

[not-group=5]
<input type="hidden" name="name" id="name" value="" /><input type="hidden" name="mail" id="mail" value="" />
[/not-group]

<textarea name="message" id="message"></textarea>

</div>


<script language="javascript" type="text/javascript">
<!--
$("textarea[name='message']").keypress(function(e) {
   if((e.ctrlKey) && ((e.keyCode == 0xA)||(e.keyCode == 0xD))) {
     iChatAdd('window'); return false;
   }
 });
//-->
</script>


<div style="padding-top:12px;">
<input class="bbcodes" style="font-size: 11px; float: left;" title="Правила" onclick="iChatRules(); return false;" type="button" value="Правила" />&nbsp;
<input class="bbcodes" style="font-size: 11px; float: left;" title="Архив" onclick="iChatHistory(); return false;" type="button" value="Архив" />&nbsp;
<input class="bbcodes" style="font-size: 9px; float: right;" title="Отправить" onclick="iChatAdd('window'); return false;" type="button" value="Отправить" />
</div>

[/editor_form]

[no_access]

<div class="ui-state-error ui-corner-all" style="padding:9px;">Только зарегистрированные посетители могут писать в чате.<div align="right" style="font-size: 9px; padding-right: 3px;">Designed by <a href="http://weboss.net/" target="_blank" style="text-decoration: none; font-size: 9px;">WEBoss.Net</a></div></div>

[/no_access]

