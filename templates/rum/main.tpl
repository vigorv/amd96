<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
{headers}
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/style.css" type="text/css" />
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
<!--cat menu-->
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/cat_menu.css" type="text/css" />
<!--/cat menu-->
<!--right menu-->
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/jquery-ui-1.8.23.custom.css" type="text/css" />
<!--/right menu-->
[group=1]
<!--showstat css-->
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/showstat.css" type="text/css" />
<!--/showstat css-->
[/group]
<script type="text/javascript">
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
</script>
</head>
<body>
{AJAX}
<div class="main">
	<div class="header">
		<div class="head-block"> <a href="/" class="logo"></a> 
			<!--
            <div class="banner"><img src="{THEME}/images/banner.jpg" alt="banner" /></div>
			-->
			<div class="header_icons">
				<a href="/addnews.html"><img src="{THEME}/icons/add2.png" alt="banner" /></a>
				<a href="/index.php?do=faq"><img src="{THEME}/icons/faq.png" alt="banner" /></a>
				<a href="/index.php?do=rules"><img src="{THEME}/icons/rules2.png" alt="banner" /></a>
				<a href="/index.php?do=feedback"><img src="{THEME}/icons/contact.png" alt="banner" /></a>
				<a href="/index.php?do=stats"><img src="{THEME}/icons/stat.png" alt="banner" /></a>
				<a href="/forum/"><img src="{THEME}/icons/forum3.png" alt="banner" /></a>
				[not-group=1]<a href="#"><img src="{THEME}/icons/chat.png" alt="banner" /></a>[/not-group]
				[group=1]<a href="#" title="Р§Р°С‚ РІ РЅРѕРІРѕРј РѕРєРЅРµ" onclick="window.open('/engine/modules/iChat/window.php', '_iChat', 'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=620,height=570');"><img src="{THEME}/icons/chat.png" alt="banner" /></a>[/group]
			</div>
			[group=5]
			<div class="login-enter">Войти на сайт</div>
			[/group]
			[not-group=5]
			<div class="login-enter">Кабинет</div>
			[/not-group]
			<div class="search-block">
				<form method="post"  action='' style="margin: 0;padding: 0;">
					<input type="hidden" name="do" value="search" />
					<input type="hidden" name="subaction" value="search" />
					<input name="story" type="text" class="form-text" id="story" value="поиск по сайту" onblur="if(this.value=='') this.value='поиск по сайту';" onfocus="if(this.value=='поиск по сайту') this.value='';" title="наберите Ваш запрос и нажмите enter" />
					<input type="image" src="{THEME}/images/search.png" value="Найти!" class="form-search" alt="Найти!" />
				</form>
			</div>
		</div>
		<div class="top-menu-block"> {include file="engine/modules/cat_menu.php?copy=0&hidden=91,60,72"} </div>
	</div>
	[aviable=main]
<div class="hideWrap"> 
<a class="hideBtn" href="javascript://" title="Показать\скрыть афишу"></a> 
	<div class="slider hideCont"> 
	{include file="engine/modules/block.pro.2.php?&block_id=top0slider&template=top0slider&day=1000&cache_live=86400&post_id=30220,30661,31444,31415,30814"}
		<div style="clear: both;"></div>
	</div>

</div> 
	[/aviable]
	<div class="content-block">
	[not-aviable=userinfo|register|stats|pm|feedback|favorites|addnews|lastcomments]
		<div class="center-block">
			<div class="top-center-block">
				<div class="bottom-center-block">
					<div class="new-reviews">
						<div class="new-reviews-title-block"> Лучшие фильмы <a href="/movies" class="all-reviews">все фильмы</a> </div>
						<div class="new-reviews-content-block"> {include file="engine/modules/block.pro.2.php?&block_id=top1kino&template=top1kino&day=1000&cache_live=86400&post_id=30661,30470,30814,31385"}
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
						<div class="new-edition-content-block"> {include file="engine/modules/block.pro.2.php?&block_id=top2games&template=top2games&day=1000&cache_live=86400&post_id=30809,31403,30938,30871"}
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="right-center-block">
			<div class="right-top-center-block">
				<div class="right-bottom-center-block">
					<div class="tizer">
						<div class="tizer-title-block"> Новое на ... 
						</div>
						<div class="tizer-content-block">
							<div class="tizer-news">{tizer_animebar}</div>
							<div class="tizer-news">{tizer_videoxq}</div>
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		[/not-aviable]
		<div style="clear: both;"></div>
		<div class="left-col">
			<div class="shortstory-block">
				<div class="shortstory-block-top">
					<div class="main-speedbar"> {speedbar} </div>
					[sort]
					<div class="main-speedbar"> {sort} </div>
					[/sort]
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
			<!--
			<div class="block-news-bottom">
				<div class="news-block-item">
					<div class="news-block-item-title">Свежие рецензии</div>
					<div class="news-block-item-content"> {custom category="1" template="title" aviable="global" from="0" limit="4" cache="no"} </div>
				</div>
				<div class="news-block-item">
					<div class="news-block-item-title">Новости игр</div>
					<div class="news-block-item-content"> {custom category="1" template="title" aviable="global" from="0" limit="4" cache="no"} </div>
				</div>
				<div class="archiv-block-item">
					<div class="archiv-block-item-title">Архивы</div>
					<div class="archiv-block-item-content"> {archives} </div>
				</div>
				<div style="clear: both;"></div>
			</div>
			-->
		</div>
		<div class="right-col">
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Навигация</div>
							<div class="topnews-block-content">
								<div style="width: 280px;">
									<div class="ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" style="padding-bottom: 5px;
padding-top: 5px;padding-left: 2.4em; font-family: Arial;"> <a href="/" style="padding-top: 5px; padding-bottom: 5px;">На главную</a> </div>
								</div>
								<div id="category_menu" style="display:none; width: 280px;"> {include file="engine/modules/category_menu.php"}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title">
								<div class="pop-title"> Популярные новости </div>
							</div>
							<div class="topnews-block-content"> {include file="engine/modules/block.pro.2.php?&block_id=top3news&template=top3news&day=1000&top_rating=y&cache_live=3600"} </div>
						</div>
					</div>
				</div>
			</div>
			<!--
            <div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Реклама</div>
							<div class="topnews-block-content">
								
								<img src="{THEME}/images/reklama2.jpg" alt="reklama" />
							</div>
						</div>
					</div>
				</div>
			</div>
            -->
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Последние темы на форуме</div>
							<div class="topnews-block-content" style=" margin-top: -5px;">{last_topics}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Архив</div>
							<div class="topnews-block-content" style="margin-top: -5px; padding: 0px 10px 10px 10px;">{archives}</div>
						</div>
					</div>
				</div>
			</div>
			[group=1]
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Chat (adm only)</div>
							<div class="topnews-block-content" style="width: 282px;">{include file="engine/modules/iChat/run.php"}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Online Stat (adm only)</div>
							<div class="topnews-block-content" style="padding: 10px;">{online_block}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="counters">
				<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" height="31" width="88" />
				</a> 
			[/group]
			[not-group=1]<div class="counters">[/not-group]
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
				
				<!-- Yandex.Metrika -->
 
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<!--
	[not-aviable=main]
	<div class="fon-block">
		<div class="center-block">
			<div class="top-center-block">
				<div class="bottom-center-block">
					<div class="new-reviews">
						<div class="new-reviews-title-block">
							Новые обзоры
							<a href="#" class="all-reviews">все обзоры</a>
						</div>
						<div class="new-reviews-content-block">
							{custom category="1" template="new-reviews" aviable="global" from="0" limit="4" cache="no"}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="center-block">
			<div class="top-center-block">
				<div class="bottom-center-block">
					<div class="new-reviews">
						<div class="new-reviews-title-block fiolet">
							Выбор редакции
							<a href="#" class="all-reviews" style="color: #b793c8 !important">все рекомендации</a>
						</div>
						<div class="new-edition-content-block">
							{custom category="1" template="new-edition" aviable="global" from="0" limit="2" cache="no"}
							<div style="clear: both; height: 11px;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="right-center-block">
			<div class="right-top-center-block">
				<div class="right-bottom-center-block">
					<div class="video">
						<div class="video-title-block">
							Видео
							<a href="#" class="all-reviews" style="color: #fff !important">все видео</a>
						</div>
						<div class="video-content-block">
							{custom category="1" template="video" aviable="global" from="0" limit="4" cache="no"}
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
		</div>
		[/not-aviable]
        -->
		<div style="clear:both; height:10px;"></div>
		<div class="footer">
			Все программы были взяты в свободном распространении в сети Интернет, и предназначены только для ознакомления. Все права на программы принадлежат их авторам. Администрация сайта Rumedia.ws не несёт никакой ответственности за дальнейшее использование программы. Если какая нибудь программа нарушает ваши авторские права, то просто свяжитесь с нами и мы удалим её с сайта!
			<div style="color: #F00" align="center">Copyright &copy; 2012<br /></div>
		</div>
	</div>
</div>
<div style="clear:both; height:10px;"></div>
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