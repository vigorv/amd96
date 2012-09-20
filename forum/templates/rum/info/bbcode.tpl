<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={charset}" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>{TITLE_BOARD}</title>
<link type="text/css" media="all" rel="StyleSheet" href="{HOME_LINK}control_center/template/style.css" />
<script type="text/javascript" src="{HOME_LINK}components/scripts/jquery.js"></script>
<style type="text/css" media="all">
blockquote p {margin:0;}
blockquote.blockquote {margin: 5px -20px 0px; padding:5px 20px; background:#e1ebf0; line-height:1.3;}
blockquote .titlequote {display:block; margin-bottom:5px;}
blockquote .textquote {margin-bottom:5px;}
blockquote.blockspoiler {margin: 5px -20px 0px; padding:5px 20px; background:#e1ebf0; line-height:1.3;}
blockquote .titlespoiler {display:block; margin-bottom:5px;}
blockquote .textspoiler {margin-bottom:5px;}
blockquote.blockhide {margin: 5px -20px 0px; padding:5px 20px; background:#e1ebf0; line-height:1.3;}
blockquote .titlehide {display:block; margin-bottom:5px;}
blockquote .texthide {margin-bottom:5px;}
</style>
</head>
<body>
<script type="text/javascript">
function ShowAndHide(id)
{
    $("#" + id).animate({opacity:"toggle"}, "slow");
        
    setTimeout(function(){
        $('#' + id + ' img.lb_img').width(function() {         
            if ($(this).width() > img_lb_width)
            {
                img_src = $(this).attr("src");
                $(this).wrap("<a href='" + img_src + "' onclick=\"return hs.expand(this)\" ></a>");
                return img_lb_width;
            }
        });
    }, 500);
};
</script>
<div style="padding:5px;">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Описание BBcode</div>
                    </div>
                    
                    <div class="borderL">
                        <div class="borderR">
                            <table>
                                <tr>
                                    <td>
<table width="100%" align="left">
<tr><td width="450"><b>Пример использования</b></td><td><b>Результат использования</b></td></tr>
</table>
<table class="colorTable" align="left">

<tr class="appLine">
<td width="450"><b>[b]</b>Текст<b>[/b]</b></td>
<td><b>Текст</b></td>
</tr>

<tr class="appLine dark">
<td><b>[i]</b>Текст<b>[/i]</b></td>
<td><i>Текст</i></td>
</tr>

<tr class="appLine">
<td><b>[s]</b>Текст<b>[/s]</b></td>
<td><s>Текст</s></td>
</tr>

<tr class="appLine dark">
<td><b>[u]</b>Текст<b>[/u]</b></td>
<td><u>Текст</u></td>
</tr>

<tr class="appLine">
<td><b>[left]</b>Текст<b>[/left]</b></td>
<td><div align="left">Текст</div></td>
</tr>

<tr class="appLine dark">
<td><b>[right]</b>Текст<b>[/right]</b></td>
<td><div align="right">Текст</div></td>
</tr>

<tr class="appLine">
<td><b>[center]</b>Текст<b>[/center]</b></td>
<td><div align="center">Текст</div></td>
</tr>

<tr class="appLine dark">
<td><b>[hide]</b>Текст<b>[/hide]</b><br /><br />
Скрытый текст доступен только тем группам, в настройках которых разрешён его просмотр.<br /><br />
Также возможны варианты:<br /><br />
<b>[hide=g2]</b>Текст<b>[/hide]</b> - где <b>g2</b> расшифровывается как: просмотр группе ID 2<br />Список групп:<br />{group_list}<br /><br />
<b>[hide=p10]</b>Текст<b>[/hide]</b> - где <b>p10</b> расшифровывается как: просмотр пользователям имеющим более 10 сообщений<br /><br />
<b>[hide=User1,User2]</b>Текст<b>[/hide]</b> - где <b>User1,User2</b> расшифровывается как: текст виден только пользователям с логином User1 и User2<br /><br />
<b>[hide=d20]</b>Текст<b>[/hide]</b> - где <b>d20</b> расшифровывается как: просмотр разрешён пользователям, которые зарегистрированы более 20 дней<br /><br />
<b>[hide=guest]</b>Текст<b>[/hide]</b> - где <b>guest</b> расшифровывается как: просмотр только гостям<br /><br />
<b>[hide=members]</b>Текст<b>[/hide]</b> - где <b>members</b> расшифровывается как: просмотр только авторизованным пользователям<br /><br />
Внимание! Администраторы, супер-модераторы и автор сообщения видят скрытый текст, независимо от ограничений.

</td>
<td><blockquote class="blockhide"><p><span class="titlehide">Скрытый текст:</span><span class="texthide">Текст</span></p></blockquote></td>
</tr>

<tr class="appLine">
<td><b><b>[size=5]</b>Текст<b>[/size]</b></td>
<td><font size='5'>Текст</font></td>
</tr>

<tr class="appLine dark">
<td><b>[font=Tahoma]</b>Текст<b>[/font]</b></td>
<td><font style='font-family:Tahoma'>Текст</font></td>
</tr>

<tr class="appLine">
<td><b>[color=#FF0000]</b>Текст<b>[/color]</b></td>
<td><font color='#FF0000'>Текст</font></td>
</tr>

<tr class="appLine dark">
<td><b>[quote]</b>Текст<b>[/quote]</b></td>
<td><blockquote class="blockquote"><p><span class="titlequote">Цитата:</span><span class="textquote">Текст</span></p></blockquote><!--quote --></td>
</tr>

<tr class="appLine">
<td><b>[quote=Admin|09.08.2011, 10:33]</b>Текст<b>[/quote]</b><br /><br /><br />
Возможен другой вариант (без даты):<br /><br />
<b>[quote=Admin]</b>Текст<b>[/quote]</b><br /></td>
<td><blockquote class="blockquote"><p><span class="titlequote">Admin (09.08.2011, 10:33) писал:</span><span class="textquote">Текст</span></p></blockquote><!--quote --><br />
<blockquote class="blockquote"><p><span class="titlequote">Admin писал:</span><span class="textquote">Текст</span></p></blockquote><!--quote --></td>
</tr>

<tr class="appLine dark">
<td><b>[youtube=</b>http://www.youtube.com/watch?v=eAWvNPr6r7k<b>]</b><br /><br />
Возможна вставка видео с YouTube, RuTube и "В контакте".<br /><br />
Примеры:<br /><br />
<b>[youtube=</b>http://youtu.be/t5Sd5c4o9UM]</b> - youtu.be ссылка на видео<br /><br />
<b>[youtube=</b>http://www.youtube.com/watch?v=t5Sd5c4o9UM&feature=youtu.be<b>]</b> - youtube.com ссылка на видео, скопированная из адресной строки<br /><br />
<b>[youtube=</b>http://vkontakte.ru/video_ext.php?oid=1716948&id=146334922&hash=c81j60c34z67f84&sd<b>]</b> - "В контакте" ссылка на видео. Для получения ссылки откройте в ВК видео файл и нажмите на "Получить код видео" и скопируйте код, который идёт после <b>src=</b> (без кавычек)<br /><br />
<b>[youtube=</b>http://rutube.ru/tracks/4691176.html?v=b513bc3fc8b56dd059c4d1f0d7f4f276<b>]</b> - rutube.ru ссылка на видео, скопированная из адресной строки<br /></td>
<td><object width="480" height="385"><param name="movie" value="http://youtube.com/v/eAWvNPr6r7k?fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://youtube.com/v/eAWvNPr6r7k?fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object></td>
</tr>

<tr class="appLine">
<td><b>[url=</b>http://logicboard.ru/<b>]</b>Ссылка<b>[/url]</b></td>
<td><a href="http://logicboard.ru/">Ссылка</a></td>
</tr>

<tr class="appLine dark">
<td><b>[email=</b>cheyto@adres.ru<b>]</b>Почта<b>[/url]</b></td>
<td><a href="mailto:cheyto@adres.ru">Почта</a></td>
</tr>

<tr class="appLine">
<td><b>[img=</b>http://img.yandex.net/i/www/logo.png<b>]</b></td>
<td><center><img src="http://img.yandex.net/i/www/logo.png" /></center></td>
</tr>

<tr class="appLine dark">
<td><b>[img=right]</b>http://img.yandex.net/i/www/logo.png<b>[/img]</b><br /><br />Также возможны варианты:<br /><b>[img=left]</b><br /><b>[img=center]</b></td>
<td><img src="http://img.yandex.net/i/www/logo.png" align='right' /></td>
</tr>

<tr class="appLine">
<td><b>[php]</b>echo $data;<b>[/php]</b></td>
<td><!-- PHP code --><div class="php" style="font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;"><div style="font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;">php code:</div><ol><li style="font-weight: normal; vertical-align:top;"><div style="font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;"><span style="color: #b1b100;">echo</span> <span style="color: #000088;">$data</span><span style="color: #339933;">;</span></div></li>
</ol></div><!--/PHP code --></td>
</tr>

<tr class="appLine dark">
<td><b>[javascript]</b>&lt;script&gt;<br />
alert ('LB');<br />
&lt;/script&gt;<b>[/javascript]</b></td>
<td><!-- JS code --><div class="javascript" style="font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;"><div style="font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;">JavaScript code:</div><ol><li style="font-weight: normal; vertical-align:top;"><div style="font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;"><span style="color: #339933;">&lt;</span>script<span style="color: #339933;">&gt;</span></div></li>
<li style="font-weight: normal; vertical-align:top;"><div style="font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;"><span style="color: #000066;">alert</span> <span style="color: #009900;">&#40;</span><span style="color: #3366CC;">'LB'</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span></div></li>
<li style="font-weight: normal; vertical-align:top;"><div style="font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;"><span style="color: #339933;">&lt;/</span>script<span style="color: #339933;">&gt;</span></div></li>
</ol></div><!--/JS code --></td>
</tr>

<tr class="appLine">
<td><b>[html]</b>&lt;b&gt;<b>[/html]</b></td>
<td><!-- HTML code --><div class="html4strict" style="font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;"><div style="font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;">HTML code:</div><ol><li style="font-weight: normal; vertical-align:top;"><div style="font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;"><span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">b</span>&gt;</span></div></li></ol></div><!--/HTML code --></td>
</tr>

<tr class="appLine dark">
<td><b>[translite]</b>Privet<b>[/translite]</b></td>
<td>Привет</td>
</tr>

<tr class="appLine">
<td><b>[spoiler]</b>Текст<b>[/spoiler]</b></td>
<td><blockquote class="blockspoiler"><span class="titlespoiler"><a href='#' onclick="ShowAndHide('222'); return false;">Спойлер [+]</a></span><div id='222' style='display:none;' class="textspoiler">Текст</div></blockquote><!--spoiler --></td>
</tr>

<tr class="appLine dark">
<td><b>[spoiler=Осторожно]</b>Текст<b>[/spoiler]</b></td>
<td><blockquote class="blockspoiler"><span class="titlespoiler"><a href='#' onclick="ShowAndHide('223'); return false;">Осторожно</a></span><div id='223' style='display:none;' class="textspoiler">Текст</div></blockquote><!--spoiler --></td>
</tr>

<tr class="appLine">
<td><b>::001:: ::050::</b><br /><br />По умолчанию количество смайлов равно 50, т.е. цифра между :: и :: - номер смайла.<br /></td>
<td><img id='smiles_img' src='{HOME_LINK}templates/{TEMPLATE_NAME}/bbcode/smiles/001.gif' /> <img id='smiles_img' src='{HOME_LINK}templates/{TEMPLATE_NAME}/bbcode/smiles/050.gif' /></td>
</tr>

<tr class="appLine dark">
<td><b>[search]</b>Привет<b>[/search]</b><br /><br />Тег предназначен для "быстрой" ссылки на поиск ключевого слова или слов, заключенных между тегами.</td>
<td><a href="http://forum.site.ru/?do=search&w=%D0%9F%D1%80%D0%B8%D0%B2%D0%B5%D1%82&p=1">Привет</a></td>
</tr>

</table>

                                </td>
                            </tr>
                         </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</div>
</body>
</html>