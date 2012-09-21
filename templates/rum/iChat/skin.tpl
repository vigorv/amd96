<div class="topnews-repeat">
<div class="topnews-top">
<div class="topnews-bottom">
<div class="topnews-block">
<div class="topnews-block-title reklama">Chat
[group=1]
<input class="bnt" style="font-size: 11px;" title="Options" onclick="iChatAdmin();return false;" type="button" value="Options" />
[/group]
</div>
<div class="topnews-block-content" style="width: 282px; margin-top:-5px;">

<div id="iChat-style" class="scroll-pane" style="width:max;height:400px; overflow:auto;">
	<div id="iChat-messages" align="left">{messages}</div>
</div>
<div style="clear:both; height:5px;"></div>

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
     iChatAdd('site'); return false;
   }
 });
//-->
</script>
<script type="text/javascript" id="sourcecode">
$(function(){ 
    $(function(){
    	$('.scroll-pane').jScrollPane(
    	    {
    	    showArrows: true
    	    }
    	);
    });
});
</script>


<div style="padding-top:5px;">
<input class="btn" style="height: 20px; line-height: 11px; float: left; margin-right: 5px;" title="Правила" onclick="iChatRules(); return false;" type="button" value="Правила" />&nbsp;
<input class="btn" style="height: 20px; line-height: 11px; float: left;" title="Архив" onclick="iChatHistory(); return false;" type="button" value="Архив" />&nbsp;
<input class="btn" style="height: 25px; line-height: 11px; float: right;" title="Отправить" onclick="iChatAdd('site'); return false;" type="button" value="Отправить" />
</div>

<div style="clear:both;"></div>
[/editor_form]

[no_access]

<div class="ui-state-error ui-corner-all" style="padding:9px;">Только зарегистрированные посетители могут писать в чате.<div align="right" style="font-size: 9px; padding-right: 3px;">Designed by <a href="http://weboss.net/" target="_blank" style="text-decoration: none; font-size: 9px;">WEBoss.Net</a> & <a href="http://codingrus.ru/" target="_blank" style="text-decoration: none; font-size: 9px;" title="delphi">delphi coding</a></div></div>

[/no_access]
</div>
</div>
</div>
</div>
</div>