/*
 * jQuery UI Effects Bounce 1.8.9
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Effects/Bounce
 *
 * Depends:
 *	jquery.effects.core.js
 */
(function(e){e.effects.bounce=function(b){return this.queue(function(){var a=e(this),l=["position","top","bottom","left","right"],h=e.effects.setMode(a,b.options.mode||"effect"),d=b.options.direction||"up",c=b.options.distance||20,m=b.options.times||5,i=b.duration||250;/show|hide/.test(h)&&l.push("opacity");e.effects.save(a,l);a.show();e.effects.createWrapper(a);var f=d=="up"||d=="down"?"top":"left";d=d=="up"||d=="left"?"pos":"neg";c=b.options.distance||(f=="top"?a.outerHeight({margin:true})/3:a.outerWidth({margin:true})/
3);if(h=="show")a.css("opacity",0).css(f,d=="pos"?-c:c);if(h=="hide")c/=m*2;h!="hide"&&m--;if(h=="show"){var g={opacity:1};g[f]=(d=="pos"?"+=":"-=")+c;a.animate(g,i/2,b.options.easing);c/=2;m--}for(g=0;g<m;g++){var j={},k={};j[f]=(d=="pos"?"-=":"+=")+c;k[f]=(d=="pos"?"+=":"-=")+c;a.animate(j,i/2,b.options.easing).animate(k,i/2,b.options.easing);c=h=="hide"?c*2:c/2}if(h=="hide"){g={opacity:0};g[f]=(d=="pos"?"-=":"+=")+c;a.animate(g,i/2,b.options.easing,function(){a.hide();e.effects.restore(a,l);e.effects.removeWrapper(a);
b.callback&&b.callback.apply(this,arguments)})}else{j={};k={};j[f]=(d=="pos"?"-=":"+=")+c;k[f]=(d=="pos"?"+=":"-=")+c;a.animate(j,i/2,b.options.easing).animate(k,i/2,b.options.easing,function(){e.effects.restore(a,l);e.effects.removeWrapper(a);b.callback&&b.callback.apply(this,arguments)})}a.queue("fx",function(){a.dequeue()});a.dequeue()})}})(jQuery);

/*
 * jQuery UI Effects Explode 1.8.9
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Effects/Explode
 *
 * Depends:
 *	jquery.effects.core.js
 */
(function(j){j.effects.explode=function(a){return this.queue(function(){var c=a.options.pieces?Math.round(Math.sqrt(a.options.pieces)):3,d=a.options.pieces?Math.round(Math.sqrt(a.options.pieces)):3;a.options.mode=a.options.mode=="toggle"?j(this).is(":visible")?"hide":"show":a.options.mode;var b=j(this).show().css("visibility","hidden"),g=b.offset();g.top-=parseInt(b.css("marginTop"),10)||0;g.left-=parseInt(b.css("marginLeft"),10)||0;for(var h=b.outerWidth(true),i=b.outerHeight(true),e=0;e<c;e++)for(var f=
0;f<d;f++)b.clone().appendTo("body").wrap("<div></div>").css({position:"absolute",visibility:"visible",left:-f*(h/d),top:-e*(i/c)}).parent().addClass("ui-effects-explode").css({position:"absolute",overflow:"hidden",width:h/d,height:i/c,left:g.left+f*(h/d)+(a.options.mode=="show"?(f-Math.floor(d/2))*(h/d):0),top:g.top+e*(i/c)+(a.options.mode=="show"?(e-Math.floor(c/2))*(i/c):0),opacity:a.options.mode=="show"?0:1}).animate({left:g.left+f*(h/d)+(a.options.mode=="show"?0:(f-Math.floor(d/2))*(h/d)),top:g.top+
e*(i/c)+(a.options.mode=="show"?0:(e-Math.floor(c/2))*(i/c)),opacity:a.options.mode=="show"?1:0},a.duration||500);setTimeout(function(){a.options.mode=="show"?b.css({visibility:"visible"}):b.css({visibility:"visible"}).hide();a.callback&&a.callback.apply(b[0]);b.dequeue();j("div.ui-effects-explode").remove()},a.duration||500)})}})(jQuery);

/*====================================================
=====================================================*/

function iChatAdd(place)
{

if(document.iChat_form.message.value == ''){ 

DLEalert(iChat_lang[3], iChat_lang[2]); 

return false;

};

if(document.iChat_form.message.value.length > iChat_cfg[0]){ 

DLEalert(iChat_lang[4], iChat_lang[2]); 

return false;

};

	var name = document.getElementById('name').value;
	var mail = document.getElementById('mail').value;
	var message = document.getElementById('message').value;

	iShowLoading('');

	$.post(dle_root + "engine/modules/iChat/ajax/add.php", { name: name, mail: mail, message: message, place: place }, function(data){

		iHideLoading('');

	$("#iChat-messages").fadeOut(500, function() {
			$(this).html(data);
			$(this).fadeIn(500);
	});

	});

	return false;

};

function iChatEdit( id,place ){
	var b = {};

	b[dle_act_lang[3]] = function() { 
		$(this).dialog('close');						
	};

	b[dle_act_lang[2]] = function() { 
		if ( $('#dle-promt-text').val().length < 1) {
			$('#dle-promt-text').addClass('ui-state-error');
		} else {
			var response = $('#dle-promt-text').val()
			$(this).dialog('close');
			$('#dlepopup').remove();
			$.post(dle_root + 'engine/modules/iChat/ajax/edit.php', { id:id, action: "save", new_message: response, place: place },
				function(data){
if(place != 'history') $("#iChat-messages").html(data);
       else $("#history_c").html(data); reFreshiChat(); 
				});

		}				
	};

$.post(dle_root + "engine/modules/iChat/ajax/edit.php", { id:id, action: "show" }, function(data){

		iHideLoading('');

	old_message = data;

	$('#dlepopup').remove();
					
	$('body').append("<div id='dlepopup' title='"+iChat_lang[0]+"' style='display:none'><br />"+iChat_lang[1]+"<br /><br /><textarea name='dle-promt-text' id='dle-promt-text' class='ui-widget-content ui-corner-all' style='width:97%;height:100px; padding: .4em;'>" + old_message + "</textarea></div>");
					
	$('#dlepopup').dialog({
		autoOpen: true,
		width: 500,
		buttons: b
	});

});

};

function iChatDelete(id,place)
{

DLEconfirm( dle_del_agree, dle_confirm, function () {

	iShowLoading('');

	$.post(dle_root + "engine/modules/iChat/ajax/delete.php", { id: id, place: place }, function(data){

	     iHideLoading('');

if(place != 'history'){

	     $("#iChat-messages").hide('slide',{ direction: "left" }, 500).html(data).show('slide',{ direction: "right" }, 500);

}else{

          $("#history_c").hide('slide',{ direction: "left" }, 500).html(data).show('slide',{ direction: "right" }, 500);
reFreshiChat();

}

	});

	return false;

} );

};


function iChatRefresh(place)
{
	
	$.post(dle_root + "engine/modules/iChat/ajax/refresh.php", { action: "refresh", place: place }, function(data){

if(data != 'no need refresh'){

		$("#iChat-messages").html(data);

};


	});

	return false;
};
/*
function iChatRefresh(place){
    $.post(dle_root + "engine/modules/iChat/ajax/refresh.php", { action: "refresh", place: place }, function(data){
        if($.trim(data) != 'no need refresh'){
	    $("#iChat-messages").html(data);
	};
});
return false;
 };
 */

function iChatPopUpRules( r )
{
	var b = {};

	b[iChat_lang[5]] = function() { 
					$(this).dialog("close");							
			    };

	$("#rules").remove();

	$("body").append(r);

	$('#rules').dialog({
		autoOpen: true,
		show: 'bounce',
		hide: 'explode',
		buttons: b,
		width: 400
	});
	
	return false;
};

function iChatRules()
{
	iShowLoading('');

	$.get(dle_root + "engine/modules/iChat/ajax/rules.php", { action: "rules" }, function(data){

		iHideLoading('');

		iChatPopUpRules( data );

	});

	
	return false;
};

function iChatPopUpHistory( r )
{
	$("#history").remove();

	$("body").append(r);

	$('#history').dialog({
		autoOpen: true,
		show: 'blind',
		hide: 'blind',
		width: 300,
           height:410
	});
	
	return false;
};

function iChatHistory(page)
{
	iShowLoading('');

	$.post(dle_root + "engine/modules/iChat/ajax/history.php", { action: "history", page: page }, function(data){

		iHideLoading('');

		iChatPopUpHistory( data );

	});

	
	return false;
};

function iChatPopUpAdmin( r )
{
	var b = {};

	     b[iChat_lang[8]] = function() { 
					CheckUpdates(); return false;						
			    };


		b[iChat_lang[7]] = function() { 
					iChatClearBd(); return false;							
				};


		b[iChat_lang[6]] = function() {
				      SaveCfg(); return false;				
			    };

	$("#ECPU").remove();

	$("body").append(r);

	$('#ECPU').dialog({
		autoOpen: true,
		show: 'slide',
		hide: 'slide',
		buttons: b,
		width: 500
	});
	
	return false;
};

function iChatAdmin()
{

	iShowLoading('');

	$.get(dle_root + "engine/modules/iChat/ajax/admin.php", { action: "show" }, function(data){

		iHideLoading('');

		iChatPopUpAdmin( data );

	});

	return false;
};

function iShowLoading( message )
{

	if ( message )
	{
		$("#loading-layer-text").html(message);
	}
		
	var setX = ( $(window).width()  - $("#loading-layer").width()  ) / 2;
	var setY = ( $(window).height() - $("#loading-layer").height() ) / 2;
			
	$("#loading-layer").css( {
		left : setX + "px",
		top : setY + "px",
		position : 'fixed',
		zIndex : '99'
	});
		
	$("#loading-layer").fadeTo('slow', 0.6);

};

function iHideLoading( message )
{
	$("#loading-layer").fadeOut('slow');
};

var uagent    = navigator.userAgent.toLowerCase();
var is_safari = ( (uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var is_opera  = (uagent.indexOf('opera') != -1);
var is_ie     = ( (uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) );
var is_ie4    = ( (is_ie) && (uagent.indexOf("msie 4.") != -1) );

var is_win    =  ( (uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") !=- 1) );
var ua_vers   = parseInt(navigator.appVersion);

var text_enter_url       = "Введите полный URL ссылки";
var text_enter_size       = "Введите размеры флэш ролика (ширина, высота)";
var text_enter_flash       = "Введите ссылку на флэш ролик";
var text_enter_page      = "Введите номер страницы";
var text_enter_url_name  = "Введите название сайта";
var text_enter_page_name = "Введите описание ссылки";
var text_enter_image    = "Введите полный URL изображения";
var text_enter_email    = "Введите e-mail адрес";
var text_code           = "Использование: [CODE] Здесь Ваш код.. [/CODE]";
var text_quote          = "Использование: [QUOTE] Здесь Ваша Цитата.. [/QUOTE]";
var error_no_url        = "Вы должны ввести URL";
var error_no_title      = "Вы должны ввести название";
var error_no_email      = "Вы должны ввести e-mail адрес";
var prompt_start        = "Введите текст для форматирования";
var img_title   		= "Введите по какому краю выравнивать картинку (left, center, right)";
var email_title  	    = "Введите описание ссылки";
var text_pages  	    = "Страница";
var image_align  	    = "left";
var bb_t_emo  	        = "Вставка смайликов";
var bb_t_col  	        = "Цвет:";
var text_enter_list     = "Введите пункт списка. Для завершения ввода оставьте поле пустым.";

var iChatselField  = "message";
var iChatfombj    = document.getElementById( 'iChat_form' );

var ie_range_cache = '';
var list_open_tag = '';
var list_close_tag = '';
var listitems = '';
var bbtags   = new Array();

var rus_lr2 = ('Е-е-О-о-Ё-Ё-Ё-Ё-Ж-Ж-Ч-Ч-Ш-Ш-Щ-Щ-Ъ-Ь-Э-Э-Ю-Ю-Я-Я-Я-Я-ё-ё-ж-ч-ш-щ-э-ю-я-я').split('-');
var lat_lr2 = ('/E-/e-/O-/o-ЫO-Ыo-ЙO-Йo-ЗH-Зh-ЦH-Цh-СH-Сh-ШH-Шh-ъ'+String.fromCharCode(35)+'-ь'+String.fromCharCode(39)+'-ЙE-Йe-ЙU-Йu-ЙA-Йa-ЫA-Ыa-ыo-йo-зh-цh-сh-шh-йe-йu-йa-ыa').split('-');
var rus_lr1 = ('А-Б-В-Г-Д-Е-З-И-Й-К-Л-М-Н-О-П-Р-С-Т-У-Ф-Х-Х-Ц-Щ-Ы-Я-а-б-в-г-д-е-з-и-й-к-л-м-н-о-п-р-с-т-у-ф-х-х-ц-щ-ъ-ы-ь-ь-я').split('-');
var lat_lr1 = ('A-B-V-G-D-E-Z-I-J-K-L-M-N-O-P-R-S-T-U-F-H-X-C-W-Y-Q-a-b-v-g-d-e-z-i-j-k-l-m-n-o-p-r-s-t-u-f-h-x-c-w-'+String.fromCharCode(35)+'-y-'+String.fromCharCode(39)+'-'+String.fromCharCode(96)+'-q').split('-');

function iChat_setFieldName(which)
{
    if (which != iChatselField)
    {
        iChatselField = which;

    }
};

function iChat_emoticon(theSmilie)
{
	iChatdoInsert(" " + theSmilie + " ", "", false);
};

function iChat_simpletag(thetag)
{
	iChatdoInsert("[" + thetag + "]", "[/" + thetag + "]", true);

};

function iChat_copy_quote(qname) 
{
	dle_txt= '';

	if (window.getSelection) 
	{
		dle_txt=window.getSelection();
	}
	else if (document.selection) 
	{
		dle_txt=document.selection.createRange().text;
	}
	if (dle_txt != "")
	{
		dle_txt='[quote='+qname+']'+dle_txt+'[/quote]\n';
	}
};

function iChat_reply(name) 
{
	if ( !document.getElementById('iChat_form') ) return false;

	var input=document.getElementById('iChat_form').message;

		if (dle_txt!= "") {
			input.value += dle_txt;
		}
		else { 
			input.value += "[b]"+name+"[/b],"+"\n";
		}
};

function iChat_tag_leech()
{
	var thesel = iChat_get_sel(eval('iChatfombj.'+ iChatselField))

    if (!thesel) {
        thesel ='My Webpage';
    }

	DLEprompt(text_enter_url, "http://", dle_prompt, function (r) {

		var enterURL = r;

		DLEprompt(text_enter_url_name, thesel, dle_prompt, function (r) {

			iChatdoInsert("[leech="+enterURL+"]"+r+"[/leech]", "", false);
			ie_range_cache = null;
	
		});

	});
};

function iChatdoInsert(ibTag, ibClsTag, isSingle)
{
	var isClose = false;
	var obj_ta = eval('iChatfombj.'+ iChatselField);

	if ( (ua_vers >= 4) && is_ie && is_win)
	{
		if (obj_ta.isTextEdit)
		{
			obj_ta.focus();
			var sel = document.selection;
			var rng = ie_range_cache ? ie_range_cache : sel.createRange();
			rng.colapse;
			if((sel.type == "Text" || sel.type == "None") && rng != null)
			{
				if(ibClsTag != "" && rng.text.length > 0)
					ibTag += rng.text + ibClsTag;
				else if(isSingle)
					ibTag += rng.text + ibClsTag;
	
				rng.text = ibTag;
			}
		}
		else
		{
				obj_ta.value += ibTag + ibClsTag;
			
		}
		rng.select();
	    ie_range_cache = null;

	}
	else if ( obj_ta.selectionEnd )
	{ 
		var ss = obj_ta.selectionStart;
		var st = obj_ta.scrollTop;
		var es = obj_ta.selectionEnd;
		
		var start  = (obj_ta.value).substring(0, ss);
		var middle = (obj_ta.value).substring(ss, es);
		var end    = (obj_ta.value).substring(es, obj_ta.textLength);

		if(!isSingle) middle = "";
		
		if (obj_ta.selectionEnd - obj_ta.selectionStart > 0)
		{
			middle = ibTag + middle + ibClsTag;
		}
		else
		{
			middle = ibTag + middle + ibClsTag;
		}
		
		obj_ta.value = start + middle + end;
		
		var cpos = ss + (middle.length);
		
		obj_ta.selectionStart = cpos;
		obj_ta.selectionEnd   = cpos;
		obj_ta.scrollTop      = st;


	}
	else
	{
		obj_ta.value += ibTag + ibClsTag;
	}

	obj_ta.focus();
	return isClose;
};

function iChat_ins_color(buttonElement)
{

	document.getElementById(iChatselField).focus();

	if ( is_ie )
	{
			document.getElementById(iChatselField).focus();
			ie_range_cache = document.selection.createRange();
	}

	$("#cp").remove();

	$("body").append("<div id='cp' title='" + bb_t_col + "' style='display:none'><br /><iframe width=\"154\" height=\"104\" src=\"" + dle_root + "templates/" + dle_skin + "/iChat/img/bbcode/color.html\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe></div>");

	$('#cp').dialog({
		autoOpen: true,
		width: 180
	});
};

function iChat_setColor(color)
{

		iChatdoInsert("[color=" +color+ "]", "[/color]", true );
		$('#cp').dialog("close");
};

function iChat_ins_emo( buttonElement )
{
		document.getElementById(iChatselField).focus();

		if ( is_ie )
		{
			document.getElementById(iChatselField).focus();
			ie_range_cache = document.selection.createRange();
		}

		$("#iChat_emo").remove();

		$("body").append("<div id='iChat_emo' title='" + bb_t_emo + "' style='display:none'>"+ document.getElementById('iChat_emos').innerHTML +"</div>");

		var w = '300';
		var h = 'auto';

		if ( $('#iChat_emos').width() >= 450 )  {$('#iChat_emos').width(450); w = '505';}
		if ( $('#iChat_emos').height() >= 300 ) { $('#iChat_emos').height(300); h = '340';}

		$('#iChat_emo').dialog({
				autoOpen: true,
				show: 'slide',
				hide: 'explode',
				width: w,
				height: h
			});


};

function iChat_smiley ( text ){
	iChatdoInsert(' ' + text + ' ', '', false);

	$('#iChat_emo').dialog("close");
	ie_range_cache = null;
};

function iChat_translit()
{
	var obj_ta = eval('iChatfombj.'+ iChatselField);

	if ( (ua_vers >= 4) && is_ie && is_win) {

		if (obj_ta.isTextEdit) {

			obj_ta.focus();
			var sel = document.selection;
			var rng = sel.createRange();
			rng.colapse;

			if((sel.type == "Text" || sel.type == "None") && rng != null) {
				rng.text = iChat_dotranslate(rng.text);
			}
		} else {

			obj_ta.value = iChat_dotranslate(obj_ta.value);
		}

	} else {
		obj_ta.value = iChat_dotranslate(obj_ta.value);
	}

	obj_ta.focus();

	return;
};

function iChat_dotranslate(text)
{
    var txtnew = "";
    var symb = 0;
    var subsymb = "";
    var trans = 1;
    for (kk=0;kk<text.length;kk++)
    {
        subsymb = text.substr(kk,1);
        if ((subsymb=="[") || (subsymb=="<"))
        {
            trans = 0;
        }
        if ((subsymb=="]") || (subsymb==">"))
        {
            trans = 1;
        }
        if (trans)
        {
            symb = iChat_transsymbtocyr(txtnew.substr(txtnew.length-1,1), subsymb);
        }
        else
        {
            symb = txtnew.substr(txtnew.length-1,1) + subsymb;
        }
        txtnew = txtnew.substr(0,txtnew.length-1) + symb;
    }
    return txtnew;
};

function iChat_transsymbtocyr(pretxt,txt)
{
	var doubletxt = pretxt+txt;
	var code = txt.charCodeAt(0);
	if (!(((code>=65) && (code<=123))||(code==35)||(code==39))) return doubletxt;
	var ii;
	for (ii=0; ii<lat_lr2.length; ii++)
	{
		if (lat_lr2[ii]==doubletxt) return rus_lr2[ii];
	}
	for (ii=0; ii<lat_lr1.length; ii++)
	{
		if (lat_lr1[ii]==txt) return pretxt+rus_lr1[ii];
	}
	return doubletxt;
};

function iChat_insert_font(value, tag)
{
    if (value == 0)
    {
    	return;
	} 

	iChatdoInsert("[" +tag+ "=" +value+ "]", "[/" +tag+ "]", true );
    iChatfombj.bbfont.selectedIndex  = 0;
    iChatfombj.bbsize.selectedIndex  = 0;
};

function iChat_get_sel(obj)
{

 if (document.selection) 
 {

   if ( is_ie )
   {
		document.getElementById(iChatselField).focus();
		ie_range_cache = document.selection.createRange();
   }

   var s = document.selection.createRange(); 
   if (s.text)
   {
	 return s.text;
   }
 }
 else if (typeof(obj.selectionStart)=="number")
 {
   if (obj.selectionStart!=obj.selectionEnd)
   {
     var start = obj.selectionStart;
     var end = obj.selectionEnd;
	 return (obj.value.substr(start,end-start));
   }
 }

 return false;

};
