<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
{headers}
<link rel="stylesheet" href="http://rumedia.ws/templates/smart/css/style.css" type="text/css" />
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/icons.css" type="text/css" />
<link rel="shortcut icon" href="http://rumedia.ws/templates/rum/favicon.ico" type="image/x-icon" />
<!--[if lte IE 7]>
	<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/style-ie.css" type="text/css" />
	<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/style-ie8.css" type="text/css" />
	<![endif]-->
<script type="text/javascript" src="http://rumedia.ws/templates/rum/js/shareTT.js"></script>
<script type="text/javascript" src="http://rumedia.ws/templates/rum/js/active.js"></script>
<script type="text/javascript" src="http://rumedia.ws/templates/rum/js/setcookie.js"></script>

<!-- BEGIN cat menu style -->
<link rel="stylesheet" type="text/css" href="http://rumedia.ws/templates/rum/css/cat_menu.css" />
<!-- END cat menu style -->

<!-- BEGIN right menu style -->
<link rel="stylesheet" type="text/css" href="http://rumedia.ws/templates/rum/css/jquery-ui-1.8.23.custom.css" />
<!-- END right menu style -->

<!-- BEGIN chat style -->
<link rel="stylesheet" type="text/css" href="http://rumedia.ws/templates/rum/iChat/css/style.css" />
<!-- END chat style -->

[group=1]
<!-- BEGIN showstat css-->
<link rel="stylesheet" type="text/css" href="http://rumedia.ws/templates/rum/css/showstat.css" />
<!-- END showstat css-->
[/group]

<script type="text/javascript">
<!--
$(document).ready(function() {
$(function() { $('#category_menu').accordion({ autoHeight:false, active:false, collapsible:true, navigation:true }).show(); });
});
$(document).ready(function(){
$(function() {
 $.fn.scrollToTop = function() {
  $(this).hide().removeAttr("href");
  if ($(window).scrollTop() >= "250") $(this).fadeIn("slow")
  var scrollDiv = $(this);
  $(window).scroll(function() {
   if ($(window).scrollTop() <= "250") $(scrollDiv).fadeOut("slow")
   else $(scrollDiv).fadeIn("slow")
  });
  $(this).click(function() {
   $("html, body").animate({scrollTop: 0}, "slow")
  })
 }
});

$(function() {
 $("#upto").scrollToTop();
});

$(function(){ 
 /* Start DocumentReady */ 
 $('div.hideWrap a.hideBtn').each(function(i){var cookie=getCookie('hideBtn'+i); 
 if(cookie&&cookie.indexOf('close')!=-1){$(this).toggleClass('close').siblings('div.hideCont').hide();}; 
 }); 
 /* StartClickFunction */ 
 $('div.hideWrap a.hideBtn').click(function(){ 
 $(this).toggleClass('close').siblings('div.hideCont').slideToggle('normal'); 
 var hideBtn=$('div.hideWrap a.hideBtn').index($(this)),isShow=$(this).attr('class'); 
 setCookie('hideBtn'+hideBtn,isShow,365);return false; 
 }); 
 /* End DocumentReady */ 
 });
 
});
// -->
</script>

[group=1]
{include file="engine/modules/_admin.php"}
[/group]

</head>
<body>
{AJAX}

<!-- BEGIN main -->
<div class="main">

	<!-- BEGIN header -->
    <div class="header">
	
		<div class="top-menu-block">
		    {include file="engine/modules/cat_menu.php?copy=0&hidden=91,60,72,1,3,5"}
		</div>
		
	</div>
	<!-- END header-->
	
	[aviable=main]
	<div class="hideWrap"> 
		<a class="hideBtn" href="javascript://" title="Показать\скрыть афишу"></a> 
		<div class="slider hideCont">
			{include file="engine/modules/block.pro.2.php?&block_id=top0slider&template=top0slider&day=1000&cache_live=86400&post_id={top0slider}"}
			<div style="clear: both;"></div>
		</div>
	</div> 
	[/aviable]
	
	<!-- BEGIN Content Block -->
	<div class="content-block">
	[static=chat]
	<div class="main-speedbar" style="width: 959px !important;"> {speedbar} </div>
	{content}
	[/static]
	                
	[not-static=chat]
	[not-aviable=userinfo|register|stats|pm|feedback|favorites|addnews|lastcomments]
	<div class="center-block">	
		<div class="top-center-block">
			<div class="bottom-center-block">
				<div class="new-reviews">
					<div class="new-reviews-title-block"> Лучшие фильмы <a href="/movies" class="all-reviews">все фильмы</a> </div>
					<div class="new-reviews-content-block">{include file="engine/modules/block.pro.2.php?&block_id=top1kino&template=top1kino&day=1000&cache_live=86400&post_id={top1kino}"}
						<div style="clear: both;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="center-block">
		<div class="top-center-block">
			<div class="bottom-center-block">
				<div class="new-reviews">
					<div class="new-reviews-title-block salat"> Лучшие игры <a href="/games" class="all-reviews">все игры</a> </div>
					<div class="new-edition-content-block"> {include file="engine/modules/block.pro.2.php?&block_id=top2games&template=top2games&day=1000&cache_live=86400&post_id={top2games}"}
						<div style="clear: both;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	[/not-aviable]
	
	<div style="clear: both;"></div>
	
	<!-- BEGIN left-col-->
	<div class="left-col">
		<div class="shortstory-block">
			<div class="shortstory-block-top">
				<div class="main-speedbar">{speedbar}</div>
				[sort]<div class="main-speedbar">{sort}</div>[/sort]
				{info}
				
				[aviable=lastcomments]
				<div class="s-block">
				[/aviable]
				
				{content} 
				
				[aviable=lastcomments]
				</div>
				[/aviable]
					
			</div>
		</div>
	</div>
	<!-- END left-col-->
	<!-- END right-col -->
	<div style="clear: both;"></div>
	[/not-static]

</div>
<!-- END main -->

<!-- END Content Block -->
<div style="clear:both; height:10px;"></div>
	<div class="footer">
		<div style="color: #F00" align="center">Copyright &copy; 2012<br /></div>
        <!--LiveInternet counter--><script type="text/javascript"><!--
            document.write("<a href='http://www.liveinternet.ru/click' "+
                    "target=_blank><img src='//counter.yadro.ru/hit?t14.6;r"+
                    escape(document.referrer)+((typeof(screen)=="undefined")?"":
                    ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                            screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                    ";"+Math.random()+
                    "' alt='' title='LiveInternet: показано число просмотров за 24"+
                    " часа, посетителей за 24 часа и за сегодня' "+
                    "border='0' width='88' height='31'><\/a>")
            //--></script><!--/LiveInternet-->

	</div>
</div>

<a style="position: fixed; bottom: 25px; right: 1px; cursor:pointer; display:none;" href="#" id="upto"> <img src="{THEME}/images/upto.png" alt="Наверх" title="Наверх" /></a>
<div class="shadow-fon"></div>
<div class="login-panel">
	<div class="login-block">
		<div class="close"></div>
		<div class="avtoriz">{login}</div>
	</div>
</div>

<!--[if IE 6]>
<a href="http://www.microsoft.com/rus/windows/internet-explorer/worldwide-sites.aspx" class="alert"></a>
<![endif]--> 
[group=1]
{include file="engine/modules/showstat.php?&size=1024&show_query=y"}
[/group]
</body>
</html>