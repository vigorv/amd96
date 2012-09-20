	var params = {
		changedEl: ".lbselect",
		checkZIndex:true,
		visRows:6,
		scrollArrows: true
	}
	cuSel(params);
	
	var cookset;
	var che="0";
	
	var cookcheck="0"; /*for browsers without cookies*/
	
	var mas=[];
	var mas_line="";
	var key=0;
	
$(document).ready(function(){

		/*seacrh pop-up*/
		$("#search span").click(function(){
			$("#search ul").slideToggle('normal');
			return false;
		});
		$("#search ul label").click(function(){
			var i=$(this).text();
			$("#search span").text(i);
			$("#search ul").animate({opacity:"hide"}, "slow");
		});	
		$(document).click(function(){
			$("#search ul").animate({opacity:"hide"}, "slow");
		});
	
		/*h3-blocks show-hide*/
		$(".c_toggle").click(function(){
			$(this).parent().next().slideToggle('normal');
			$(this).parent().toggleClass('collapsed');
			return false;
		});
		
		/*sidebar show-hide and set cookie*/
		$("#tog_sidebar").click(function(){
			
			if(($.cookie('cook_side'))=="1") cookcheck="1";
/*
			
			if(cookcheck=="0")
			{
				$(".board_side").animate({opacity: "hide"}, "normal");
				$(this).animate({width:$(this).width()},600,
					function()
					{ 
						$("#tog_sidebar > span").toggleClass('ts_active');
						$(".categories_in").animate({"margin-right":"-=256px"},"normal");
						$("#board_index").toggleClass('no_sidebar');
					});
				$.cookie('cook_side', "1",  {expires: 365});
				cookcheck="1";
			}
			else
			{
				$(".board_side").animate({opacity: "show"}, "normal");
				$("#tog_sidebar > span").toggleClass('ts_active');
				$(".categories_in").animate({"margin-right":"+=256px"},"fast");
				$("#board_index").toggleClass('no_sidebar');
				$.cookie('cook_side', "0",  {expires: 365});
				cookcheck="0";
			}

*/
if(cookcheck=="0")
{
        $(".board_side").animate({opacity: "show"}, "normal");
        $("#tog_sidebar > span").toggleClass('ts_active');
        $(".categories_in").animate({"margin-right":"+=256px"},"fast");
        $("#board_index").toggleClass('no_sidebar');
        $.cookie('cook_side', "1",  {expires: 365});
        cookcheck="1";
}
else
{
        $(".board_side").animate({opacity: "hide"}, "normal");
        $(this).animate({width:$(this).width()},600,
        function()
        { 
                $("#tog_sidebar > span").toggleClass('ts_active');
                $(".categories_in").animate({"margin-right":"-=256px"},"normal");
                $("#board_index").toggleClass('no_sidebar');
        });
        $.cookie('cook_side', "0",  {expires: 365});
        cookcheck="0";
}            
            return false;
		});
		
		/*cat_list rows bg*/
		$(".cat_list>ol>li:odd").addClass("cl_row"); 
		
		/*sidebar rows bg*/
		$(".bb_cont>ol>li:odd").addClass("bb_row_2"); 
		
		/*forum_table rows bg*/
		$(".forum_table table tr:nth-child(even) > td").addClass("ft_row"); 
		
		/*icons in themes*/
		$(".ft_topic_name").hover(function(){
			$(this).children(".ft_topic_prev").children("a").toggle();
			return false;
		});
		
		/*flex pop-up - fp_up page-reply1 */
		$(".rl_info").click(function(){ 
			var coord=$(this).offset();
			var po=$(this).parents(".rl_head").prev(".fp_up").clone().prependTo("body");
			if(po)
				{
					$(po).css("top", coord.top-88).css("left", coord.left+25);
					$(po).animate({opacity:"show"}, "fastl"); 
					$(".upp_close").click(function(){
						$(po).fadeOut('normal', function() {$(po).remove(); });
						return false;
					});	
					$(this).click(function(){$(po).remove();  return false;});
					$(document).click(function(){
						$(po).fadeOut('normal', function() {$(po).remove(); });
					});
				}
			return false;
		});	
        
        $(".butt_options").click(function(){   
            $(this).next(".alo_list").slideToggle('fast');         
            return false;
        });
                		
		/*checkboxes*/
		$('input[type="checkbox"].counter').live('click', function(){
            
			if ( $(this).is(':checked') ) 
			{	
				che++; 
				$("#moder_but").removeClass("butt_disable");
				$("#moder_but input").attr("disabled", "");
				$("#moder_but span span").html('С отмеченными ('+che+')<input type="submit" value="С отмеченными ('+che+')" />');
			} 
			else 	
			{
				che--;
				$("#moder_but span span").html('С отмеченными ('+che+')<input type="submit" value="С отмеченными ('+che+')" />');
				if(che==0)
				{
					$("#moder_but").addClass("butt_disable");
					$("#moder_but input").attr("disabled", "disabled");
				}
			}
		});
		        		
		/*set cookies here*/
		$(".c_toggle").click(function(){
			var c_id=$(this).parent().attr('id');
			c_id=c_id.substr(2);			
			
			if($.cookie("c_ids"))
				{
					mas=$.cookie("c_ids").split(",");
				}

			if(($(this).parent().attr('class'))=="collapsed") /*if collapsed- save cookie*/
				{
					if(mas[0]) 	/*esli ne pustoi massiv*/
						{
							for(var i=0; i<mas.length; i++) 
								{
									if(mas[i]!=c_id)
										{
											key=1;
										}
								}
							if(key==1) 
								{
								mas.push(c_id);	 /*add item*/
								key=0;
								}
						}
					else  /*if empty - add first element*/
						{
						 mas[0]=c_id;
						}				
					mas_line=mas.join(',');
					$.cookie("c_ids", mas_line,  {expires: 365}); 
				}
			else /*if open now- > delete cookie*/
				{
					for(var i=0; i<mas.length; i++) 
						{
							if(mas[i]==c_id)
								{
									mas.splice(i,1); /*delete item*/
									break;
								}
						}
					/*save cookie*/
					mas_line=mas.join(',');
					$.cookie("c_ids", mas_line,  {expires: 365}); 
				}
		});
  
		/*read cookies here*/
		if($.cookie("c_ids"))	
		{
			var cook_line=$.cookie("c_ids").split(",");
			for(var i=0; i<cook_line.length; i++) 
			{
				var nid="#c_"+cook_line[i];
				$(nid).attr("class", "collapsed");
				$(nid).next().hide();				
			}
		}		
		
/*
		if(($.cookie('cook_side'))=="1")
			{
				$(".board_side").hide();
*/
if(($.cookie('cook_side'))!="1")
{
        $(".board_side").hide();				
				$("#tog_sidebar > span").toggleClass('ts_active');
				$(".categories_in").animate({"margin-right":"-=256px"},1);
				$("#board_index").toggleClass('no_sidebar');
			}		
	});