 function bb(Tag)
 {
    var Open='['+Tag+']';
    var Close='[/'+Tag+']';
    
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1)
    {   
        var s = document.selection.createRange ();
        if(s)
        {  
            var l = s.text.length;
            s.text = Open + s.text + Close;
            s.moveEnd("character", -Close.length);
            s.moveStart("character", -l);                                           
            s.select();                
        }
    }
    else
    { 
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + Open + sel + Close + sel2;
        doc.selectionStart = sel1.length + Open.length;
        doc.selectionEnd = doc.selectionStart + sel.length;
        doc.scrollTop = ss;                                             
    }
    
    return false;
}

function bb_font(Tag, cTag)
{
    var Open='['+Tag+']';
    var Close='[/'+cTag+']';
    
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1)
    {                                        
        var s = document.selection.createRange ();
        if(s)
        {                                  
            var l = s.text.length;
            s.text = Open + s.text + Close;
            s.moveEnd("character", -Close.length);
            s.moveStart("character", -l);                                           
            s.select();                
        }
    }
    else
    {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + Open + sel + Close + sel2;
        doc.selectionStart = sel1.length + Open.length;
        doc.selectionEnd = doc.selectionStart + sel.length;
        doc.scrollTop = ss;                                             
    }
    
    return false;
}
 
function bb_color(Tag, cTag)
{
    var Open='['+Tag+']';
    var Close='[/'+cTag+']';
    
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1)
    {                                        
        var s = document.selection.createRange ();
        if(s)
        {                                  
            var l = s.text.length;
            s.text = Open + s.text + Close;
            s.moveEnd("character", -Close.length);
            s.moveStart("character", -l);                                           
            s.select();                
        }
    }
    else
    {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + Open + sel + Close + sel2;
        doc.selectionStart = sel1.length + Open.length;
        doc.selectionEnd = doc.selectionStart + sel.length;
        doc.scrollTop = ss;                                             
    }
    
    return false;
}
 
function bb_promt(Tag, input_name, rvalue, closeTag)
{
    var uinput = $("#mini_window #input_bb_"+input_name).val();
    
    if (input_name == "text_align")
    {
        Tag = uinput;
        uinput = "";
        
        if(Tag != "center" && Tag != "right" && Tag != "left")
        {
            show_message("3", LB_lang['error'], LB_lang['bbcode_empty_val']);
            return false;
        }
    }
    else
    {
        if(!uinput && rvalue)
        {
            show_message("3", LB_lang['error'], LB_lang['bbcode_empty_val']);
            return false;
        }
	}  
    
    $("#mini_window #input_bb_"+input_name).val("");
    $('#mini_window').slideUp(50);
      
    if(uinput)
        var Open='['+Tag+'='+uinput+']';
	else
		var Open='['+Tag+']';
				
    var Close='[/'+Tag+']';
    
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
                
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1)
    {                                        
        var s = document.selection.createRange ();
        if(s)
        {                                  
            var l = s.text.length;
                                
            if(closeTag)
                s.text = Open + s.text + Close;
			else
				s.text = Open  + s.text;
									
            s.moveEnd("character", -Close.length);
            s.moveStart("character", -l);                                           
            s.select();                
         }
    }
    else
    {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);  
                        
        if(closeTag)                                            
			doc.value = sel1 + Open + sel + Close + sel2;
		else
            doc.value = sel1 + Open + sel + sel2;
							
       doc.selectionStart = sel1.length + Open.length;
       doc.selectionEnd = doc.selectionStart + sel.length;
       doc.scrollTop = ss;                                             
  }
  
  return false;
}

function bb_promt_img(Tag, input_name)
{
    var uinput = $("#mini_window #input_bb_"+input_name).val();
    var uinput_2 = $("#mini_window #input_bb_"+input_name+"_align").val();
                  
    if(!uinput && !uinput_2)
    {
        show_message("3", LB_lang['error'], LB_lang['bbcode_empty_vals']);
        return false;
    }
    
    $("#mini_window #input_bb_"+input_name).val("");
    $("#mini_window #input_bb_"+input_name+"_align").val("");
    $('#mini_window').slideUp(50);
			
    var Open='['+Tag+'='+uinput_2+']'+uinput;
				
    var Close='[/'+Tag+']';
    
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
                
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1)
    {                                        
        var s = document.selection.createRange ();
        if(s)
        {                                  
            var l = s.text.length;
            s.text = s.text + Open + Close;   
        }
    }
    else
    {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + sel + Open + Close + sel2;
        doc.scrollTop = ss;                                             
    }
    
    return false;
}

function insert_smile(id)
{
    var Open='::' + id;
    var Close='::';
    
    if ($("#" + aid).length == "0")
    {
        aid = 'tf';
    }
    
    var doc = document.getElementById(aid);
    doc.focus();
    if (window.attachEvent && navigator.userAgent.indexOf('Opera') === -1)
    {                                        
        var s = document.selection.createRange ();
        if(s)
        {                                  
            var l = s.text.length;
            s.text =s.text +  Open + Close;
        }
    }
    else
    {                                              
        var ss = doc.scrollTop;
        sel1 = doc.value.substr(0, doc.selectionStart);
        sel2 = doc.value.substr(doc.selectionEnd);
        sel = doc.value.substr(doc.selectionStart, doc.selectionEnd - doc.selectionStart);                                              
        doc.value = sel1 + sel + Open + Close + sel2;
                        
        doc.scrollTop = ss;                                             
    }
    return false;
}

function bb_colors (elem)
{
    $(".smiles").animate({opacity:"hide"}, "fast");
    $(".colors").animate({opacity:"hide"}, "fast");
    
    setTimeout(function(){
        if ($(elem).next(".colors").is(':visible'))
        {
            $(elem).next(".colors").animate({opacity:"hide"}, "fast");
        }
        else
        {
            $(elem).next(".colors").animate({opacity:"toggle"}, "fast");
        }
    }, 50);
    
    $(".colors").click(function(){
        $(".colors").animate({opacity:"hide"}, "fast");
    });

    return false;
}

function bb_smiles (elem)
{
    $(".smiles").animate({opacity:"hide"}, "fast");
    $(".colors").animate({opacity:"hide"}, "fast");
    
     setTimeout(function(){
        if ($(elem).next(".smiles").is(':visible'))
        {
            $(elem).next(".smiles").animate({opacity:"hide"}, "fast");
        }
        else
        {
            $(elem).next(".smiles").animate({opacity:"toggle"}, "fast");
        }
    }, 50);
        
    $(".smiles").click(function(){
        $(".smiles").animate({opacity:"hide"}, "fast");
    });
    
    return false;
}