/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

var pid;

function Error_AJAX_jQ(dom_element)   
{ 
    $(dom_element).ajaxError(function(event, request, settings){
        remove_loading_message();
        show_message("3", LB_lang['error'], LB_lang['ajax_error']);
    });
}

function show_confirm_message(pid, gde)
{      
    $("div.fp_cp").remove();   
    $.get(LB_root + "components/scripts/ajax/confirm_window.php", {"pid": pid, "gde": gde, "template":LB_skin}, function(data){
        $("div.confirm_window").html(data);
    });
    Error_AJAX_jQ ("div.confirm_window");                
    $(".fp_cp").fadeIn(500);
}

function show_confirm_message_hide()
{
    $(".fp_cp").fadeOut(500).delay(500).remove();
}

function Get_Next_Post_Buttom()   
{   
    $("a#gnpb").load(LB_root + "components/scripts/ajax/get_next_page_posts_buttom.php");
    return false;
}

function PostMenu(bopid)   
{ 
    $(".alo_list").slideUp("fast");
    
    if ($("#bop" + bopid).next(".alo_list").is(':visible'))
    {
        $("#bop" + bopid).next(".alo_list").slideUp('fast');  
    }
    else
    {    
        setTimeout(function(){ $("#bop" + bopid).next(".alo_list").fadeIn('fast'); }, 50);
    }   
              
    return false;
}
      
function AddNewPost()   
{ 	  
    var text = $("#newpost-form :input#tf").val();
    var tid = $("#newpost-form :input[name=tid]").val();
    var guest_name = $("#newpost-form :input#guest_name").val();
    var keystring = $("#newpost-form :input#keystring").val();
    var keystring_dop = $("#newpost-form :input#keystring_dop").val();
      
    if ($("span#newpost-out").length == "0")
    {
        show_message("3", LB_lang['error'], LB_lang['no_object'] + "span#newpost-out");
        return false;
    }
      
    if (text == "")
    {
        show_message("3", LB_lang['error'], LB_lang['no_text']);
        return false;
    }
        
    show_loading_message();
              
    $.post(LB_root + "components/scripts/ajax/newpost.php", {"text":text, "tid":tid, "guest_name":guest_name, "keystring":keystring, "keystring_dop":keystring_dop, "template":LB_skin}, function(data){
        $("span#newpost-out").before(data);
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("span#newpost-out");
        
    return false;
}
  
function AddDelFavorite(tid)   
{ 	       
    if (!tid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_topic']);
        return false;
    }
        
    show_loading_message();
        
    $("#do_favorite").empty();
        
    $.get(LB_root + "components/scripts/ajax/favorite.php", {"secret_key":secret_key, "tid":tid}, function(data){
        remove_loading_message();
        $("#do_favorite").html(data);
    });
    
    Error_AJAX_jQ ("#do_favorite");
        
    return false;
}

function AddDelSubscribe(tid)
{                
    if (!tid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_topic']);
        return false;
    }
        
    show_loading_message();
        
    $("#do_subscribe").empty();
        
    $.get(LB_root + "components/scripts/ajax/subscribe.php", {"secret_key":secret_key, "tid":tid}, function(data){
        $("#do_subscribe").html(data);
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("#do_subscribe");

    return false;
}   

function EditPost(pid)
{     
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }
        
    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/post_edit.php", {"secret_key":secret_key, "id":pid, "act": "edit", "template":LB_skin}, function(data){
        $("#post-id-" + pid).slideUp(500);
        $("#post-id-form-" + pid).remove();
        $("#post-id-" + pid).after("<div id='post-id-form-" + pid + "' style='display:none'></div>");
        $("#post-id-form-" + pid).append(data).slideToggle(1100);
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("#post-id-" + pid);

    return false;
}
    
function EditSavePost(pid)
{  
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }
        
    var text = $("form#editpost_ajax-" + pid + " :input#tf-" + pid).val();
    var edit_reason = $("form#editpost_ajax-" + pid + " :input#er-" + pid).val();
    var moder_reason = $("form#editpost_ajax-" + pid + " :input#mr-" + pid).val();
    var change_moder = $("form#editpost_ajax-" + pid + " :checkbox#cm-" + pid).is(":checked");
      
    if (change_moder)
        change_moder = 1;
    else
        change_moder = 0;        

    if (text == "")
    {
        show_message("3", LB_lang['error'], LB_lang['no_text']);
        return false;
    }
        
    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/post_edit.php", {"secret_key":secret_key, "id":pid, "act": "edit", "editpost":"1", "text":text, "edit_reason":edit_reason, "moder_reason":moder_reason, "change_moder":change_moder, "template":LB_skin}, function(data){             
        $("#post-id-" + pid).empty().append(data).slideDown(500);
        $("#post-id-form-" + pid).slideToggle(1100);
        remove_loading_message();
        setTimeout(function(){ $("#post-id-form-" + pid).remove(); }, 1100);
    });
    
    Error_AJAX_jQ ("#post-id-" + pid);

    return false;
}

function EditStopPost(pid)
{      
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }

    $("#post-id-form-" + pid).slideToggle(1100);
    $("#post-id-" + pid).slideDown(500);
    setTimeout(function(){ $("#post-id-form-" + pid).remove(); }, 1100);

    return false;
}
  
function ShowHidePost(pid)
{  
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }
        
    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/post_edit.php", {"secret_key":secret_key, "id":pid, "act": "showhide", "template":LB_skin}, function(data){
        if ($("#post-showhide-" + pid).hasClass("butt butt_disable"))
        {
            $("#post-showhide-" + pid).removeClass("butt butt_disable").addClass("butt");
            $("#post-showhide-" + pid + " a").attr("title", LB_lang['show_post']).text(LB_lang['show_post']);
        }
        else
        {
            $("#post-showhide-" + pid).removeClass("butt").addClass("butt butt_disable");
            $("#post-showhide-" + pid + " a").attr("title", LB_lang['hide_post']).text(LB_lang['hide_post']);
        }
        $("#post-showhide-" + pid).append(data);
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("#post-showhide-" + pid);

    return false;
}

function DeletePost(pid)
{              
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    } 
         
    show_confirm_message(pid, "posts");
    return false;
}

function DeletePostTrue(pid)
{      
    show_confirm_message_hide();  
    
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    } 

    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/post_edit.php", {"secret_key":secret_key, "id":pid, "act": "delete", "template":LB_skin}, function(data){
        $("#post-delete-" + pid).append(data);
        remove_loading_message();
    });
        
    Error_AJAX_jQ ("#post-delete-" + pid);
                    
    return false;
}
   
function DeletePostFalse()
{              
    show_confirm_message_hide();
    return false;
}
   
function FixedPost(pid)
{                   
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }
        
    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/post_edit.php", {"secret_key":secret_key, "id":pid, "act": "fixed", "template":LB_skin}, function(data){
        $("#post-fixed-" + pid).removeClass("butt butt_disable").addClass("butt");
        $("#post-fixed-" + pid + " a").attr("title", LB_lang['post_unfix']).text(LB_lang['post_unfix']);
        $("#post-fixed-" + pid).append(data);
        $("#post-fixed-" + pid).attr("id", "post-unfixed-" + pid);
        remove_loading_message();  
    });
    
    Error_AJAX_jQ ("#post-fixed-" + pid);

    return false;
}
  
function UnfixedPost(pid)
{   
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }
        
    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/post_edit.php", {"secret_key":secret_key, "id":pid, "act": "unfixed", "template":LB_skin}, function(data){
        $("#post-unfixed-" + pid).removeClass("butt").addClass("butt butt_disable");
        $("#post-unfixed-" + pid + " a").attr("title", LB_lang['post_fix']).text(LB_lang['post_fix']);
        $("#post-unfixed-" + pid).append(data);
        $("#post-unfixed-" + pid).attr("id", "post-fixed-" + pid);
        remove_loading_message();   
    });
        
    Error_AJAX_jQ ("#post-unfixed-" + pid);

    return false;
}
    
// ##########################################################
    
function CheckNewPM()   
{    	                
    show_loading_message();
              
    $.get(LB_root + "components/scripts/ajax/check_new_pm.php", {"secret_key":secret_key}, function(data){
        $(".confirm_window").after(data);
        remove_loading_message();
    });
        
    Error_AJAX_jQ ("#check_new_pm");
                
    return false;
}
    
function Preview()   
{          
    var text = $("form[name=newtopic] :input#tf").val();     
        
    if ($("#preview-topic").length == "0")
    {
        show_message("3", LB_lang['error'], LB_lang['no_object'] + "#preview-topic");
        return false;
    }
        
    if (!text)
    {
        show_message("3", LB_lang['error'], LB_lang['no_text']);
        return false;
    }
        
    show_loading_message();
        
    $.post(LB_root + "components/scripts/ajax/preview.php", {"text": text, "template":LB_skin}, function(data){
        $("#preview-topic-show").remove();
        $("#preview-topic").after("<div id='preview-topic-show' style='display:none'></div>");
        $("#preview-topic-show").append(data).slideToggle(1100);
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("#preview-topic-show");

    return false;
}
    
function PollTopic(tid)   
{ 	        
    var text = $("form#polltopic").serialize();
                          
    if (text == "")
    {
        show_message("3", LB_lang['error'], LB_lang['no_answer']);
        return false;
    }
        
    if (!tid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_topic']);
        return false;
    }
        
    show_loading_message();
              
    $.post(LB_root + "components/scripts/ajax/topic_vote.php", {"text":text, "vote":"1", "tid":tid, "template":LB_skin}, function(data){
        $("#topic_vote_jq").slideUp(500);
        $("#topic_vote_jq_result").slideUp(500);
        setTimeout(function(){ $("#topic_vote_jq_result").remove(); }, 500);
        setTimeout(function(){
            $("#topic_vote_jq").after("<div id='topic_vote_jq_result' style='display:none'></div>");
            $("#topic_vote_jq_result" ).append(data).slideToggle(1100);    
        }, 500); 
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("form#polltopic");
                
    return false;
}
    
function PollResult(tid)   
{        
    if (!tid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_topic']);
        return false;
    }
        
    show_loading_message();
              
    $.post(LB_root + "components/scripts/ajax/topic_vote.php", {"vote":"0", "tid":tid, "template":LB_skin}, function(data){
        $("#topic_vote_jq").slideUp(500);
        $("#topic_vote_jq_result").slideUp(500);
        setTimeout(function(){ $("#topic_vote_jq_result").remove(); }, 500);
        setTimeout(function(){
            $("#topic_vote_jq").after("<div id='topic_vote_jq_result' style='display:none'></div>");
            $("#topic_vote_jq_result" ).append(data).slideToggle(1100);    
        }, 500); 
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("form#polltopic");
        
    return false;
}
    
function PollShow(tid)   
{         
    if (!tid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_topic']);
        return false;
    }
        
    show_loading_message();
              
    $.post(LB_root + "components/scripts/ajax/topic_vote.php", {"vote":"2", "tid":tid, "template":LB_skin}, function(data){
        $("#topic_vote_jq").slideUp(500);
        $("#topic_vote_jq_result").slideUp(500);
        setTimeout(function(){ $("#topic_vote_jq_result").remove(); }, 500);
        setTimeout(function(){
            $("#topic_vote_jq").after("<div id='topic_vote_jq_result' style='display:none'></div>");
            $("#topic_vote_jq_result" ).append(data).slideToggle(1100);    
        }, 500);    
        remove_loading_message();
    });
        
    Error_AJAX_jQ ("form#polltopic");
        
    return false;
}

function SaveText(text_id, tid)   
{    
    var text = $("#" + text_id).val();
    $.get(LB_root + "components/scripts/ajax/savetext.php", {"text":text, "tid":tid});      
    return false;
}

function ProfileInfo(elem, mid)   
{
    var coord = $(elem).offset();
    var width_elem = $(elem).width() + 15;
    
    
    $(".flex_popup.fp_up").remove();
     
    if (!mid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_member']);
        return false;
    }
        
    show_loading_message();
        
    $.get(LB_root + "components/scripts/ajax/member_info.php", {"mid":mid, "template":LB_skin}, function(data){
        $(".confirm_window").after(data);
        $(".flex_popup.fp_up").css("top", coord.top-88).css("left", coord.left+width_elem);
        $(".flex_popup.fp_up").animate({opacity:"show"}, "fastl"); 
        remove_loading_message();
    });
        
    Error_AJAX_jQ (".confirm_window");
          
    $(".upp_close").live('click', function(){
        $(".flex_popup.fp_up").fadeOut('normal', function() {$(".flex_popup .fp_up").remove(); });
        return false;
    });	
           
    return false;
}

function Utility(pid)
{                
    if (!pid)
    {
        show_message("3", LB_lang['error'], LB_lang['no_post']);
        return false;
    }
        
    show_loading_message();
        
    $.get(LB_root + "components/scripts/ajax/utility.php", {"secret_key":secret_key, "pid":pid}, function(data){
        $("#utility_" + pid).html(data);
        remove_loading_message();
    });
    
    Error_AJAX_jQ ("#utility_" + pid);

    return false;
} 

function Complaint(compl_module, compl_id)
{                
    if (!compl_id && !compl_module)
    {
        show_message("3", LB_lang['error'], LB_lang['complaint_object']);
        return false;
    }
    
    var text = $("#complaint_"+compl_id).val();
    
    if (!text)
    {
        show_message("3", LB_lang['error'], LB_lang['complaint_text']);
        return false;
    }
        
    show_loading_message();
    
    $("#mini_window #complaint_"+compl_id).val("");
    $('#mini_window').slideUp(50);
        
    $.get(LB_root + "components/scripts/ajax/complaint.php", {"secret_key":secret_key, "module":compl_module, "id":compl_id, "text":text}, function(data){
        $(".confirm_window").after(data);
        remove_loading_message();
    });
    
    Error_AJAX_jQ (".confirm_window");

    return false;
} 

function Show_StatsBlock_Online(g_do, g_op, g_id)   
{ 	                           
    $.get(LB_root + "components/scripts/ajax/block_stats.php", {"type_mod":"online", "g_do":g_do, "g_op":g_op, "g_id":g_id, "template":LB_skin}, function(data){
        $("#statsblock_online").html(data);
        $("#statsblock_online_ajax").fadeIn("500");
    });
        
    Error_AJAX_jQ ("#statsblock_online");   

    return false;
}

function Show_StatsBlock_Birthday()   
{ 	                     
    $.get(LB_root + "components/scripts/ajax/block_stats.php", {"type_mod":"birthday"}, function(data){
        $("#statsblock_birthday").html(data);
        $("#statsblock_birthday_ajax").fadeIn("500");
    });
        
    Error_AJAX_jQ ("#statsblock_birthday");   

    return false;
}

$(document).ready(function()
{      
   $('#recaptcha').click(function(){
        var rndval = new Date().getTime();     
        $("img#recaptcha_img").attr("src", LB_root + "components/class/kcaptcha/kcaptcha.php?rndval=" + rndval);
        return false;
	});
    
    $("#newpost-form :input#tf").keypress(function(event) { 
        if (event.which == 10 || (event.ctrlKey && event.keyCode==13))
        {
            AddNewPost();
            return false;
        } 
    });
});