$(document).ready(function() {
	
	// Виджет Социальных закладок (Test-Templates). v1.2
	
	var LinkTitle = $(document).attr('title');
	
	var LinkUrl = window.location.href;
	
	LinkTitle=encodeURIComponent(LinkTitle);
	
	LinkUrl=encodeURIComponent(LinkUrl);
	
	
	// Вставка блока с закладками
	$('.ttfav').append('<div id="tt_social"></div>');
	
	var leftvar = (screen.width-600)/2;
	var topvar = (screen.height-400)/2;
	
	//Массив ссылок
	var LinksItem = [
	'http://vkontakte.ru/share.php?url='+LinkUrl+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Поделиться ВКонтакте"',
	'http://www.facebook.com/sharer.php?u='+LinkUrl+'&t='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в Facebook"',
	'http://twitter.com/share?text='+LinkTitle+'&url='+LinkUrl+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в Twitter"',
	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl='+LinkUrl+'&cntitle='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в LiveInternet"',
	'http://zakladki.yandex.ru/newlink.xml?url='+LinkUrl+'&name='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в Яндекс закладки"',
	'http://connect.mail.ru/share?url='+LinkUrl+'&title='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в Мой Мир"',
	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+LinkUrl+'&title='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в Одноклассники"',
	'http://www.livejournal.com/update.bml?event='+LinkUrl+'&subject='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в ЖЖ"',
	'http://www.tumblr.com/share" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="Добавить в ЖЖ"'
	];
	
	
	var sdvig = 0;
	var sdvig2 = 0;
	
	// Построение списка закладок
	for (i=0; i<LinksItem.length; i++)
	{
		var getLinks = $('<a href="'+LinksItem[i]+' style="background: url(http://flux.anka.ws/templates/rum/images/tt-fav.png) -'+sdvig+'px top  no-repeat;" target="_blank"></a>');
		getLinks.appendTo("#tt_social");
		var sdvig = sdvig + 26;
		var sdvig2 = sdvig2 + 25;
	}
	
});