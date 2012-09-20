jQuery(document).ready(function(){

	/*cusel hack for proper width of selects in ie6*/
	jQuery(".cusel").each(
	function(){
		var w = parseInt(jQuery(this).width()),
			scrollPanel = jQuery(this).find(".cusel-scroll-pane");
		if(w>=scrollPanel.width())
		{
			jQuery(this).find(".jScrollPaneContainer").width(w);
			scrollPanel.width(w+19);
		}
	});
	
	/*adds wrapper for popup in ie6*/
	$(".user-popup").wrapInner('<div class="popa" />');
	
	/*hack for seacrh pop-up in ie6*/	
	$("#search ul label").click(function(){
		$(this).find("input").attr("checked","checked");
	});
	
});