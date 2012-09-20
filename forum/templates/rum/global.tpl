<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset={charset}" />
<title>{meta_title}</title>
<meta http-equiv="content-language" content="ru" />
<meta name="description" content="{meta_description}" />
<meta name="keywords" content="{meta_keyword}" />
<meta name="robots" content="all" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<link rel="alternate" type="application/rss+xml" title="RSS" href="{link_rss}" />
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/icons.css" type="text/css" />
<link rel="stylesheet" href="http://rumedia.ws/templates/rum/css/cat_menu.css" type="text/css" />
<link rel="shortcut icon" href="http://rumedia.ws/templates/rum/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?charset=windows-1251&amp;f=templates/{TEMPLATE_NAME}/css/style.css" media="all" />
    <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?charset=windows-1251&amp;f=templates/{TEMPLATE_NAME}/css/ie.css" media="all" /><![endif]-->  
    {SCRIPTS_FILE}
    <script type="text/javascript" src="{HOME_LINK}components/scripts/min/index.php?charset=windows-1251&amp;b=templates/{TEMPLATE_NAME}/js&amp;f=cusel-min.js,project.js,placehol.js,jquery.tooltip.min.js"></script>
	<script type="text/javascript">
	$('a[class!=tooltip_left][class!=tooltip_top]').tooltip({
        placement: 'right',
        animation: true
    });
    
    $('a.tooltip_left').tooltip({
        placement: 'left',
        animation: true
    });
    
    $('a.tooltip_top').tooltip({
        placement: 'top',
        animation: true
    });
	</script>
    <!--[if IE 6]><script type="text/javascript" src="{HOME_LINK}components/scripts/min/index.php?charset=windows-1251&amp;f=templates/{TEMPLATE_NAME}/js/project_ie6.js"></script><![endif]-->
</head>
<body>
<div class="main">
	<div class="header">
		<div class="head-block"> <a href="/" class="logo"></a> 
			<!--
            <div class="banner"><img src="{THEME}/images/banner.jpg" alt="banner" /></div>
			-->
			<div class="header_icons">
				<a href="/addnews.html"><img src="http://rumedia.ws/templates/rum/icons/add2.png" alt="banner" /></a>
				<a href="/index.php?do=faq"><img src="http://rumedia.ws/templates/rum/icons/faq.png" alt="banner" /></a>
				<a href="/index.php?do=rules"><img src="http://rumedia.ws/templates/rum/icons/rules2.png" alt="banner" /></a>
				<a href="/index.php?do=feedback"><img src="http://rumedia.ws/templates/rum/icons/contact.png" alt="banner" /></a>
				<a href="/index.php?do=stats"><img src="http://rumedia.ws/templates/rum/icons/stat.png" alt="banner" /></a>
				<a href="/forum/"><img src="http://rumedia.ws/templates/rum/icons/forum3.png" alt="banner" /></a>
				<a href="#"><img src="http://rumedia.ws/templates/rum/icons/chat.png" alt="banner" /></a>
			</div>
		    <div class="forum-login">{login}</div>
	<form id="search" name="search" action="{HOME_LINK}?do=search" method="post">
		<fieldset>
			<input type="text" placeholder="поиск по форуму" name="w" id="ul_find" />
			<input type="submit" name="do_search" value="поиск" title="поиск" />
			<span>[module_board]Обсуждения[/module_board][module_users]Пользователи[/module_users]<i></i></span>
			<ul>
				<li><strong>Искать в:</strong></li>
				<li><label><input type="radio" name="ms" value="0" [module_board]checked="checked"[/module_board] />Обсуждения</label></li>
				<li><label><input type="radio" name="ms" value="1" [module_users]checked="checked"[/module_users] />Пользователи</label></li>
			</ul>
		</fieldset>
	</form>
	
		</div>
		<div class="top-menu-block"><ul class="cat_menu" id="cat_menu_c0">{topmenuch}</ul><div style="clear:both"/></div>
	</div>
	<div class="content-block">
	<!--
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
		-->
		<div style="clear: both;"></div>













{SCRIPTS}

<div class="header">
	<div id="main_nav">
	<div class="f-nav-b">
	<a href="{HOME_LINK}" class="btn"><span>Все форумы</span></a>
	<a href="{link_last_topics}" class="btn"><span>Новые темы</span></a>
	<a href="{link_topic_active}" class="btn"><span>Активные темы</span></a>
	<a href="{link_last_posts}" class="btn"><span>Последние ответы</span></a>
	<a href="{link_users}" class="btn"><span>Пользователи</span></a>
	[global_group=5]<a href="{DLE_LINK}index.php?do=register" class="btn"><span>Регистрация</span></a>[/global_group]
	</div>
	</div>
	<div class="h_info cle">
		<ol>
			<li>{speedbar}</li>
		</ol>
		<a href="#" id="tog_sidebar"><span class="ts_active">Скрыть панель справа</span><span>Показать панель справа</span><i></i></a>
	</div>	
</div>
<!--header end-->
<div class="center">

	<div id="board_index" class="cle">
		<div class="categories">
			<div class="categories_in">    
                {mysql_stat}
                [last_forum_news]
                <div class="autoriz" style="margin: 0 0 7px;" id="last_news_box">
                <ol>
                    <li><b>Последние новости:</b> <a href="{last_forum_news_topic_link}">{last_forum_news_title}</a> <a href="{last_forum_news_close}" onclick="Last_News_Close('{time_close}');return false;"><font class="smalltext">(закрыть)</font></a></li>
                </ol>
                <div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
                </div>
                [/last_forum_news]           
                {message}
                {content}
                {templates}
            </div>
		</div>
		<!--categories end-->
	
    
		<div class="board_side">
                
			<div class="board_block">
				<h3 id="c_01">Последние темы<span title="свернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
				<div class="bb_cont">
					<ol class="bb_last_feed">
						{last_topics}				
					</ol>
					<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				</div>
			</div>
			<!--board_block end-->
            
			<!--
            <div class="board_block">
				<h3 id="c_05">Статьи<span title="свернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
				<div class="bb_cont">
					<ol class="bb_status_ch">
                        {dle_news}
					</ol>
					<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				</div>
			</div>
			-->
			<!--board_block end-->
            
		</div>
		<!--board_side end-->
	</div>
	<!--board_index end-->


    {statistic}
	<!--statistics end-->
</div>
<!--center end-->
	
	
<script type="text/javascript">
	inputPlaceholder(document.getElementById('ul_login'))
	inputPlaceholder(document.getElementById('ul_pass'))
	inputPlaceholder(document.getElementById('ul_find'))  
    inputPlaceholder(document.getElementById('ul_inputsearch'))
</script>
	
















		<div style="clear: both;"></div>
	</div>
		<div style="clear:both; height:10px;"></div>
		<div class="footer">
			<center><a href="{clear_cookie}">Очистить Cookies</a>&nbsp;&nbsp;&nbsp;[global_not_group=5]<a href="{all_tf_read}">Отметить все темы и форумы прочитанными</a>[/global_not_group]</center>
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
</body>
</html>