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
<div class="body-cont">
 <div id="iChat-style" class="chat-b-c"style="resize:vertical;">
 <div id="iChat-messages" align="left">{messages}</div>
</div>
 <div class="chat-m-t"></div>
 <div class="chat-m-b"></div>
 </div>
<div style="clear: both; height: 5px;"></div>

[editor_form]

<div class="iChat_editor">

[group=5]  
<table class="panel-off"><tr>
<td><input type="text" maxlength="35" name="name" id="name" class="iChat_input" value="{name}" onblur="if(this.value=='') this.value='{name}';" onfocus="if(this.value=='{def_name}') this.value='';" />   
<input type="text" maxlength="35" name="mail" id="mail" class="iChat_input" value="{mail}" onblur="if(this.value=='') this.value='{mail}';" onfocus="if(this.value=='{def_mail}') this.value='';" /></td>
</tr></table>
[/group]
  
<div class="chat_title">

<input class="bbc1" id="b_b" onclick="iChat_simpletag('b')" type="button" />
<input class="bbc2" id="b_i" onclick="iChat_simpletag('i')" type="button" />
<input class="bbc3" id="b_u" onclick="iChat_simpletag('u')" type="button" />
<input class="bbc4" id="b_s" onclick="iChat_simpletag('s')" type="button" />
<input class="bbc5" id="b_emo" onclick="iChat_ins_emo(this);" type="button" />
[allow_url]
<input class="bbc6" id="b_quote" onclick="iChat_tag_leech()" type="button" />
[/allow_url]
<input class="bbc7" id="b_color" onclick="iChat_ins_color(this);" type="button" />
<!-- <span id="b_hide" onclick="iChat_simpletag('hide')"><img title="Скрытый текст" src="{THEME}/img/bbcode/hide.png" alt="" /></span> -->
<input class="bbc8" id="b_quote" onclick="iChat_simpletag('quote')" type="button" />
<input class="bbc9" id="b_translit" onclick="iChat_translit()" type="button" />

<div class="clr"></div>

</div>

[not-group=5]
<input type="hidden" name="name" id="name" value="" /><input type="hidden" name="mail" id="mail" value="" />
[/not-group]

<div class="chat-mas-c"><textarea name="message" id="message"></textarea></div>

<div align="center" class="chat-mas-b" style="">Введите сообщение и нажмите "ctrl+enter"</div>

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

<center>
<div style="padding:8px 0 26px 0; width: 217px;">
<input class="three_but-one" style="font-size: 11px; float: left;" title="Архив" onclick="iChatHistory(); return false;" type="button" value="" />
<input class="three_but-two" style="font-size: 11px; float: left;" title="Отправить" onclick="iChatAdd('site'); return false;" type="button" value="" />
<input class="three_but-thr" style="font-size: 11px; float: left;" title="Правила" onclick="iChatRules(); return false;" type="button" value="" />
</div>
</center>
<div style="clear:both;"></div>
[/editor_form]

[no_access]

<div class="ui-state-error ui-corner-all" style="padding:9px;">Только зарегистрированные посетители могут писать в чате.</div>

[/no_access]
</div>
</div>
</div>
</div>
</div>
