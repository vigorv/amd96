$(document).ready(function() {

    $('[id^="menuPlus"]').click(function() {
    
        var $parts = $(this).attr('id').split('menuPlus');
        var $nom;

        if (typeof $parts[1] == 'undefined') { return false; }

        $nom = parseInt($parts[1]);
        if ($nom <= 0) { return false; }
        if ($('#menu' + $nom + 'Opened').length <= 0) { return false; }


        // close all unclosed
        $('div[id$="Opened"]').each(function() {

            if ($(this).css('display') == 'block') {
            
                var $parts = $(this).parent().attr('id').split('menu');
                var $nom;
            
                if (typeof $parts[1] == 'undefined') { return false; }
            
                $nom = parseInt($parts[1]);
                if ($nom <= 0) { return false; }


                // close opened
                $('#menu' + $nom + 'Opened ul').parent().animate({height:'0px'}, { queue:true, easing:'swing', duration:300, complete: function() {
                $('#menu' + $nom + ' .submenuT').animate({height:'0px'}, { queue:false, easing:'swing', duration:100, complete: function() {
                $('#menu' + $nom + ' .submenuB').animate({height:'0px'}, { queue:false, easing:'swing', duration:100, complete: function() {
                    
                    $('#menu' + $nom + 'Closed').show();
                    $('#menu' + $nom + 'Opened').hide();
                }})
                }})
                }});
            }
        });


        // prepare to open current
        $('#menu' + $nom + 'Opened ul').parent().height(0);
        $('#menu' + $nom + ' .submenuT').height(0);
        $('#menu' + $nom + ' .submenuB').height(0);

        $('#menu' + $nom + 'Closed').hide();
        $('#menu' + $nom + 'Opened').show();

        var $submenuHeight = $('#menu' + $nom + 'Opened ul').height();


        // open current
        $('#menu' + $nom + ' .submenuT').animate({height:'9px'}, { queue:false, easing:'swing', duration:100, complete: function() {
        $('#menu' + $nom + ' .submenuB').animate({height:'9px'}, { queue:false, easing:'swing', duration:100, complete: function() {
        $('#menu' + $nom + 'Opened ul').parent().animate({height:'' + $submenuHeight}, { queue:true, easing:'swing', duration:300, complete: function() {
        }})
        }})
        }});
    });
    
    	/*adm_popup*/
	$(".adm_pop_but").click(function(){ 
		$(this).next(".adm_popup").slideToggle("fast");
		return false;
	});
	
	$(document).click(function(){
		$(".adm_popup").animate({opacity:"hide"}, "fast"); 
	});
    
    $(".config_edit_butt").click(function(){
        $(this).prev(".config_edit_pan").show(300);
        $(this).hide(100);
        
        setTimeout(function(){ 
            $(".config_edit_pan").mouseleave(function(){
                $(this).next(".config_edit_butt").show(300);
                $(this).hide(100);
            });
        }, 1000);
        
        return false;
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
        
});

function SetNewField(tf)
{
    aid = tf;
};

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

function ShowAndHide(id)
{
    $("#" + id).animate({opacity:"toggle"}, "slow");
};