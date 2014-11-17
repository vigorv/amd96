<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	{headers}
	<link rel="stylesheet" href="{THEME}/css/style.css" type="text/css" />
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="{THEME}/css/style-ie.css" type="text/css" />
	<![endif]-->
	<!--[if IE 8]>
	<link rel="stylesheet" href="{THEME}/css/style-ie8.css" type="text/css" />
	<![endif]-->
	<script type="text/javascript" src="{THEME}/js/shareTT.js"></script>
	<script type="text/javascript" src="{THEME}/js/active.js"></script>
    <script type="text/javascript" src="{THEME}/js/jquery.cookie.js"></script>
    <!--cat menu-->
	<link rel="stylesheet" href="{THEME}/css/cat_menu.css" type="text/css" />
    <!--/cat menu-->
    <!--right menu-->
	<link rel="stylesheet" href="{THEME}/css/right_menu.css" type="text/css" />
    <!--/right menu-->

<script type="text/javascript">
$(document).ready(function() {
$(function() {
  $('#theMenu ul').each(function(i) {
    if ($.cookie('menuLabel-' + i)) { $(this).show(); } else { $(this).hide(); }
    $(this).prev().click(function() {
      $(this).parent().siblings().each(function() {
        $(this).find('ul').slideUp(1, function(){ $.cookie('menuLabel-' + $('#theMenu ul').index($(this)), null, { expires: null, path: '/' }); });
      });
      var n = $('#theMenu ul').index($(this).next());
      if ($(this).next().css('display') == 'none') {
        $(this).next().slideDown(200, function () {
          $.cookie('menuLabel-' + n, 'open', { expires: null, path: '/' });
        });
      } else {
        $(this).next().slideUp(200, function () {
          $.cookie('menuLabel-' + n, null, { expires: null, path: '/' });
        });
      }
      return false;
    });
  });
  if($.cookie('activeHref')) { $('#theMenu a[href*="' + $.cookie('activeHref') + '"]').addClass('active'); }
  $('#theMenu a[href*="folder="]').click(function(){
    $('#theMenu a[href*="folder="]').removeClass('active');
    $.cookie('activeHref', $(this).attr('href'), {expires: null, path: '/'});
    $(this).addClass('active');
  });
});
});
</script>

</head>
<body>
{AJAX}
<div class="main">
	<div class="header">
		<div class="head-block">
			<a href="/" class="logo"></a>
			<!--
            <div class="banner"><img src="{THEME}/images/banner.jpg" alt="banner" /></div>
			-->
            <div class="header_icons">
            	<a href="#"><img src="{THEME}/icons/add.png" alt="banner" /></a>
            	<a href="#"><img src="{THEME}/icons/faq.png" alt="banner" /></a>
                <a href="#"><img src="{THEME}/icons/rules2.png" alt="banner" /></a>
                <a href="#"><img src="{THEME}/icons/contact.png" alt="banner" /></a>
                <a href="#"><img src="{THEME}/icons/stat.png" alt="banner" /></a>
                
                <a href="#"><img src="{THEME}/icons/forum.png" alt="banner" /></a>
                <a href="#"><img src="{THEME}/icons/chat.png" alt="banner" /></a>
            </div>
            [group=5]<div class="login-enter">Войти на сайт</div>[/group]
			[not-group=5]<div class="login-enter">Кабинет</div>[/not-group]
			<div class="search-block">
				<form method="post"  action='' style="margin: 0;padding: 0;">
					<input type="hidden" name="do" value="search" />
                    <input type="hidden" name="subaction" value="search" />
					<input name="story" type="text" class="form-text" id="story" value="поиск по сайту" onblur="if(this.value=='') this.value='поиск по сайту';" onfocus="if(this.value=='поиск по сайту') this.value='';" title="наберите Ваш запрос и нажмите enter" />
					<input type="image" src="{THEME}/images/search.png" value="Найти!" class="form-search" alt="Найти!" />
				</form>
			</div>
		</div>
		<div class="top-menu-block">
        {include file="engine/modules/cat_menu.php?copy=0&hidden=91,60,72"}
		</div>
    </div>
	[aviable=main]
    <div class="slider">
		{custom category="1" template="slider" aviable="global" from="0" limit="5" cache="no"}
		<div style="clear: both;"></div>
	</div>
	<div style="height: 4px;"></div>
    [/aviable]
	
	<div class="content-block">
		[aviable=main]
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
		[/aviable]
		<div class="left-col">
			<div class="shortstory-block">
				<div class="shortstory-block-top">
					
					{info}
					{content}
				
				</div>
			</div>
			<div class="block-news-bottom">
				<div class="news-block-item">
					<div class="news-block-item-title">Свежие рецензии</div>
					<div class="news-block-item-content">
						{custom category="1" template="title" aviable="global" from="0" limit="4" cache="no"}
					</div>
				</div>
				<div class="news-block-item">
					<div class="news-block-item-title">Новости игр</div>
					<div class="news-block-item-content">
						{custom category="1" template="title" aviable="global" from="0" limit="4" cache="no"}
					</div>
				</div>
				<div class="archiv-block-item">
					<div class="archiv-block-item-title">Архивы</div>
					<div class="archiv-block-item-content">
						{archives}
					</div>
				</div>
				<div style="clear: both;"></div>
			</div>
			
		</div>
		
		<div class="right-col">
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Навигация</div>
							<div class="topnews-block-content">
                            
                            {cat_menu}
                            
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
                            	<div class="pop-title">
                                	Популярные новости
                                </div>
                            </div>
							<div class="topnews-block-content">
                            {topnews}
                            </div>
						</div>
					</div>
				</div>
			</div>
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
			<div class="topnews-repeat">
				<div class="topnews-top">
					<div class="topnews-bottom">
						<div class="topnews-block">
							<div class="topnews-block-title reklama">Опрос</div>
							<div class="topnews-block-content" style="padding: 10px;">
                            </div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="counters">
				<img src="{THEME}/images/count.png" alt="count" />
				<img src="{THEME}/images/count.png" alt="count" />
				<img src="{THEME}/images/count.png" alt="count" />
			</div>
		</div>
	
		<div style="clear: both;"></div>
		
		
	</div>
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
	<div class="footer">
Copyright &copy; 2011 Все программы были взяты в свободном распространении в сети Интернет, и предназначены только для ознакомления. Все права на программы принадлежат их авторам. Администрация сайта Rumedia.ws не несёт никакой ответственности за дальнейшее использование программы. Если какая нибудь программа нарушает ваши авторские права, то просто свяжитесь с нами и мы удалим её с сайта!.
	</div>
</div>

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
</body>
</html>