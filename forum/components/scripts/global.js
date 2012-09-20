/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

var aid = 'tf';

function SetNewField(tf)
{
    aid = tf;
};

function ShowAndHide(id)
{
    $("#" + id).animate({opacity:"toggle"}, "slow");
        
    setTimeout(function(){
        $('#' + id + ' img.lb_img').width(function() {         
            if ($(this).width() > img_lb_width)
            {
                img_src = $(this).attr("src");
                $(this).wrap("<a href='" + img_src + "' onclick=\"return hs.expand(this)\" ></a>");
                return img_lb_width;
            }
        });
    }, 500);
};

function Resize_img()
{
    setTimeout(function(){
        if (img_lb_width > 0)
        {
            var img_src = "";
            $('img.lb_img').width(function() {   
                if ($(this).width() > img_lb_width)
                {                 
                    img_src = $(this).attr("src");
                    $(this).wrap("<a href='" + img_src + "' onclick=\"return hs.expand(this)\" ></a>");
                    return img_lb_width;
                }
            });
        }
    }, 500);
};

function ShowOrHide(id)
{
    $("#" + id).animate({opacity:"toggle"}, "slow");
};

function Last_News_Close(time_press)   
{   
    $.cookie("LB_last_news", time_press,  {
        expires: 20,
        path: "/",
        domain: "." + domain_js
        }); 
    $("#last_news_box").fadeOut(500);
    return false;
}

function quote(id, name, data)
{
    if (window.getSelection)
    {
        txt = window.getSelection().toString();
    } else if (document.getSelection) {
        txt = document.getSelection();                
    } else if (document.selection) {
        txt = document.selection.createRange().text;
    }
								
    if(txt == "")
    {
        var div = name;
        var Open='[b]';
        var Close='[/b], ';
	
        div = div.replace(/<[^>]+>/g, "");             
    } else {
        var div ="";
        var opt = name + "|" + data;
        opt = opt.replace(/<[^>]+>/g, "");
        var Open='[quote='+opt+']'+txt;
        var Close='[/quote]';
    }

    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }

    var doc = document.getElementById(aid);
    doc.focus();
                           
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1) {                                        
        var s = document.selection.createRange ();
        if(s){                                  
            var l = s.text.length;
            s.text = s.text + Open +div+ Close;            
        }
    } else {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + sel + Open + div + Close + sel2;
        doc.scrollTop = ss;                                             
    }
    
    return false;
}

function add_attachment(data)
{
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
                           
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1) {                                        
        var s = document.selection.createRange ();
        if(s){                                  
            var l = s.text.length;
            s.text = s.text + data;            
        }
    } else {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + sel + data + sel2;
        doc.scrollTop = ss;                                             
    }
    
    return false;
}

function Mini_Window(element, width)
{
    var offset = $(element).offset();
	var coordX = offset.left;
	var coordY = offset.top+$(element).innerHeight();
	var content_w = $(element).nextAll(".mini_window_content").eq(0).html();
        
    if(coordX == parseInt($('#mini_window').css("left")) && coordY == parseInt($('#mini_window').css("top")))
    {
        $('#mini_window').css({top:"0px", left:"0px"}).hide();
    }
    else
    {
        $("#mini_window").hide();
        setTimeout(function(){
            $("#mini_window").css({top:coordY+"px", left:coordX+"px", width:width+"px"}).html(content_w).slideToggle(70);
        }, 80);
    }
    return false;
}

function Script_Page(element)
{
    var script_page = $(element).prev(":input[name=page]").val();
    document.location.href = LB_base_url + "&page=" + script_page;

    return false;
}

$(document).ready(function() {
    
    $('a').tooltip({
        track: true,
        delay: 0,
        showURL: false,
        fade: 200
    });
    
    $(document).click(function(){
        $(".alo_list:visible").slideUp("fast");
    });

    var is_out_click = false;
	$('#mini_window').hover(
    	function(){is_out_click = false;},
    	function(){is_out_click = true;}
	);
        
	$("body").click(function(){
		if(is_out_click)
        {
			$('#mini_window').slideUp(50);
			is_out_click = false;
		}
	});
    
    $('a.jqmedia_video').media();
    $('a.jqmedia_audio').media( { width: 300, height: 20 } );
            
    var num_file_block = 1;
    
    $("#add_file_jq").click(function () {
        
        if (num_file_block == 10)
        {
            return false;
        }
        
        num_file_block = num_file_block + 1;
        
        $("#add_file_block").before('<div id="file_block_'+num_file_block+'" style="display:none;clear:left;">'
				+'<br /><input type="file" name="attachment[]" class="work_table_tarea" style="width:270px;" /></td>'
        +'</div>');
        
        $("#file_block_"+num_file_block).show(300);
        
        return false;
    });
    
    $("#remove_file_jq").click(function () {
        if (num_file_block == 1)
        {
            return false;
        }
                
        $("#file_block_"+num_file_block).remove();
        num_file_block = num_file_block - 1;
        return false;
    });
});

function Button_jQ_Up()
{
    $("html:not(:animated)"+( ! $.browser.opera ? ",body:not(:animated)" : "")).animate({scrollTop: $("body").position().top - 70}, 700); 
    return false;
}

function check_all_checkbox(name_form)
{
    var frm = document.getElementById( name_form );
    for (var i=0;i<frm.elements.length;i++)
    {
        var elmnt = frm.elements[i];
        if (elmnt.type == 'checkbox')
        {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}

/*
highlight v3
Highlights arbitrary terms.
<http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html>
MIT license.
Johann Burkard
<http://johannburkard.de>
<mailto:jb@eaio.com>
*/

jQuery.fn.highlight = function(pat)
{
    function innerHighlight(node, pat)
    {
        var skip = 0;
        if (node.nodeType == 3)
        {
            var pos = node.data.toUpperCase().indexOf(pat);
            if (pos >= 0)
            {
                var spannode = document.createElement('span');
                spannode.className = 'search_word';
                var middlebit = node.splitText(pos);
                var endbit = middlebit.splitText(pat.length);
                var middleclone = middlebit.cloneNode(true);
                spannode.appendChild(middleclone);
                middlebit.parentNode.replaceChild(spannode, middlebit);
                skip = 1;
            }
        }
        else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName))
        {
            for (var i = 0; i < node.childNodes.length; ++i)
            {
                i += innerHighlight(node.childNodes[i], pat);
            }
        }
        return skip;
    }
    return this.each(function() {
        innerHighlight(this, pat.toUpperCase());
    });
};

jQuery.fn.removeHighlight = function() {
    return this.find("span.search_word").each(function()
    {
        this.parentNode.firstChild.nodeName;
        with (this.parentNode)
        {
            replaceChild(this.firstChild, this);
            normalize();
        }
    }).end();
};