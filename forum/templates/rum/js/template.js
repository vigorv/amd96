function show_message(type_mes, title, text, tout)   
{
    var class_mess = "info_popup_warning";
        
    if (!tout)
    {
        var tout = 2000;
    }  
      
    if (type_mes == "1")
    {
        class_mess = "info_popup_norm";
    }
    
    if (type_mes == "3")
    {
        class_mess = "info_popup_error";
    }

    $("div.info_popup_norm").remove();
    $("div.info_popup_error").remove();
    $("div.info_popup_warning").remove();
    $("div.confirm_window").after('<div class="' + class_mess + '"><div><i></i><span>' + title + ' </span>' + text + '</div></div>');
    $("." + class_mess).fadeIn(500);
    $("." + class_mess).delay(tout).fadeOut(500);
}

function show_loading_message(text)   
{         
    if (!text)
    {
        text = LB_lang['loading'];
    }
    
    $("div#info_load").remove();
    $("body").append('<div id="info_load">' + text + '</div>');
    $("#info_load").slideDown(400);
}

function remove_loading_message()   
{         
    $("#info_load").slideUp(400);
    setTimeout(function(){ $("div#info_load").remove(); }, 400);
}

function show_gnpp_panel()   
{       
    $(document).ready(function(){  
        $('span#script_update').remove();
            
        if ($('#button_jq_up').length == '0')
        {
            $('.confirm_window').after('<div class="info_popup_jp_posts" id="button_jq_up"><div><span>' + LB_lang['info_popup_posts_title'] + '</span><a href="#" title="' + LB_lang['buttom_up_alt'] + '" onclick="Button_jQ_Up();return false;">' + LB_lang['buttom_up_title'] + '</a> | <a href="#" title="' + LB_lang['gnpb_off_alt'] + '" id="gnpb" onclick="Get_Next_Post_Buttom();return false;">' + LB_lang['gnpb_off_title'] + '</a></div></div>');
        }  
    });
}