$(document).ready(function() {
	
	// ������ ���������� �������� (Test-Templates). v1.2
	
	var LinkTitle = $(document).attr('title');
	
	var LinkUrl = window.location.href;
	
	LinkTitle=encodeURIComponent(LinkTitle);
	
	LinkUrl=encodeURIComponent(LinkUrl);
	
	
	// ������� ����� � ����������
	$('.ttfav').append('<div id="tt_social"></div>');
	
	var leftvar = (screen.width-600)/2;
	var topvar = (screen.height-400)/2;
	
	//������ ������
	var LinksItem = [
	'http://vkontakte.ru/share.php?url='+LinkUrl+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="���������� ���������"',
	'http://www.facebook.com/sharer.php?u='+LinkUrl+'&t='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � Facebook"',
	'http://twitter.com/share?text='+LinkTitle+'&url='+LinkUrl+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � Twitter"',
	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl='+LinkUrl+'&cntitle='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � LiveInternet"',
	'http://zakladki.yandex.ru/newlink.xml?url='+LinkUrl+'&name='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � ������ ��������"',
	'http://connect.mail.ru/share?url='+LinkUrl+'&title='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � ��� ���"',
	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+LinkUrl+'&title='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � �������������"',
	'http://www.livejournal.com/update.bml?event='+LinkUrl+'&subject='+LinkTitle+'" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � ��"',
	'http://www.tumblr.com/share" onClick="popupWin = window.open(this.href, \'contacts\', \'location,width=600,height=400,left='+leftvar+',top='+topvar+'\'); popupWin.focus(); return false;" title="�������� � ��"'
	];
	
	
	var sdvig = 0;
	var sdvig2 = 0;
	
	// ���������� ������ ��������
	for (i=0; i<LinksItem.length; i++)
	{
		var getLinks = $('<a href="'+LinksItem[i]+' style="background: url(http://flux.anka.ws/templates/rum/images/tt-fav.png) -'+sdvig+'px top  no-repeat;" target="_blank"></a>');
		getLinks.appendTo("#tt_social");
		var sdvig = sdvig + 26;
		var sdvig2 = sdvig2 + 25;
	}
	
});