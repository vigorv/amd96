<form  method="post" name="iChat_form" id="iChat_form" class="iChat" action="/">

<link media="screen" href="{THEME}/css/ui.css" type="text/css" rel="stylesheet" />
<link media="screen" href="{THEME}/css/style.css" type="text/css" rel="stylesheet" />
<link media="screen" href="{THEME}/css/window.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="{THEME}/js/action.js"></script>

[group=1]
<div style="padding-bottom:12px;">

<input class="button" style="font-size: 11px; float: right;" title="��������� ����" onclick="iChatAdmin(); return false;" type="button" value="��������� ����" />

<div class="clr"></div>

</div>

[/group]

<div id="iChat-style" style="width:max;height:385px; overflow:auto;"><div id="iChat-messages">{messages}</div></div><br />

[editor_form]

<div class="iChat_editor">

[group=5]
<td><input type="text" maxlength="35" name="name" id="name" class="iChat_input" value="{name}" onblur="if(this.value=='') this.value='{name}';" onfocus="if(this.value=='{def_name}') this.value='';" /></td>		
<td><input type="text" maxlength="35" name="mail" id="mail" class="iChat_input" value="{mail}" onblur="if(this.value=='') this.value='{mail}';" onfocus="if(this.value=='{def_mail}') this.value='';" /></td>
[/group]

<div class="iChat_bbeditor">

<span onclick="iChat_simpletag('b')"><img title="����������" src="{THEME}/img/bbcode/b.png" alt="" /></span>
<span onclick="iChat_simpletag('i')"><img title="��������� �����" src="{THEME}/img/bbcode/i.png" alt="" /></span>
<span onclick="iChat_simpletag('u')"><img title="������������ �����" src="{THEME}/img/bbcode/u.png" alt="" /></span>
<span onclick="iChat_simpletag('s')"><img title="����������� �����" src="{THEME}/img/bbcode/s.png" alt="" /></span>

<img class="bbspacer" src="{THEME}/img/bbcode/brkspace.png" alt="" />

<span onclick="iChat_ins_emo(this);"><img title="������� ���������" src="{THEME}/img/bbcode/emo.png" alt="" /></span>

[allow_url]
<span onclick="iChat_tag_leech()"><img title="������� ���������� ������" src="{THEME}/img/bbcode/link.png" alt="" /></span>
[/allow_url]

<span onclick="iChat_ins_color(this);"><img title="���� ������" src="{THEME}/img/bbcode/color.png" alt="" /></span>
<span onclick="iChat_simpletag('quote')"><img title="������� ������" src="{THEME}/img/bbcode/quote.png" alt="" /></span>
<span onclick="iChat_translit()"><img title="������������� ��������� ����� �� �������������� � ���������" src="{THEME}/img/bbcode/translit.png" alt="" /></span>

<div class="clr"></div>

</div>

<textarea name="message" id="message" rows="" cols=""></textarea>

<div class="copyright" style="display: none;"> <a href="http://iartbot/" target="_blank">iArtbot</a></div>

<div class="clr"></div>

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

<div style="padding-top:12px;">
<input class="button" style="font-size: 11px; float: left;" title="�������" onclick="iChatRules(); return false;" type="button" value="�������" />&nbsp;
<input class="button" style="font-size: 11px; float: left;" title="�����" onclick="iChatHistory(); return false;" type="button" value="�����" />&nbsp;
<input class="button" style="font-size: 9px; float: right;" title="���������" onclick="iChatAdd('site'); return false;" type="button" value="���������" />

<div class="clr"></div>

</div>

[/editor_form]

[no_access]

<div class="ui-state-error ui-corner-all" style="padding:9px;">������ ������������������ ���������� ����� ������ � ����.<div class="copyright" style="display: none;"> <a href="http://8dle.ru/" target="_blank">8Dle</a> <a href="http://jzweb.ru">Jz web</a></div><div class="clr"></div></div>

[/no_access]

</form>








