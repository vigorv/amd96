<?php
/*
=====================================================
 Ñêðèïò ìîäóëÿ Rss Grabber 3.6.7
 http://rss-grabber.ru/
 Àâòîð: Andersoni
 ñî Àâòîð: Alex
 Copyright (c) 2009
=====================================================
*/


	if (!(defined ('DATALIFEENGINE')))
	{
	exit ('Hacking attempt!');
	}

	function add_short ($text)
	{

	return $text;
	}


function add_full ($text)
	{

	return $text;
	}


function parse_rss ($story)
	{
		global $parse,$db;

	 $story = preg_replace( "#<!--dle_leech_begin--><a href=[\"'](http://|https://|ftp://|ed2k://|news://|magnet:)?(\S.+?)['\"].*?" . ">(.+?)</a><!--dle_leech_end-->#ie", "\$parse->decode_leech('\\1\\2', '\\3')", $story );

	$story = preg_replace( "#<img.*?src[=]?[='\"](\S+?)['\" >].*?>#is", "[img]\\1[/img]", $story );

	$story = preg_replace( "#<strong>(\S+?)</strong>#is", "[b]\\1[/b]", $story );


$story = preg_replace( "#<a.*?href[=]?[='\"](.+?)['\" >].*?>(.*?)<\/a>#is", "[url=\\1]\\2[/url]", $story );


 $story = preg_replace ('#<!--SpoilerTor-->.+?<!--SpTitleTorBegin-->(.+?)<!--SpTitleTorEnd-->.+?<!--SpTextTorBegin-->(.+?)<!--SpTextTorEnd-->.+?<!--SpoilerTorEnd-->#is', '[spoiler=\\1]\\2[/spoiler]', $story);

 	$story = preg_replace( "#<!--dle_video_begin(.+?)-->(.+?)<!--dle_video_end-->#is", '[video=\\1]', $story );

/*$story = preg_replace( '#<(object|embed|param).*?(http\S+?\.flv).*?>#is', '[video=\\2]', $story );*/



return $story;
}

	function strip_data ($text)
	{
	$quotes = array ('\'', '"', '`', '	', '
', '
', '\'', ',', '/', '¬', ';', ':', '@', '~', '[', ']', '{', '}', '=', ')', '(', '*', '&', '^', '%', '$', '<', '>', '?', '!', '"');
	$goodquotes = array ('-', '+', '#');
	$repquotes = array ('\\-', '\\+', '\\#');
	$text = stripslashes ($text);
	$text = trim (strip_tags ($text));
	$text = str_replace ($quotes, '', $text);
	$text = str_replace ($goodquotes, $repquotes, $text);
	return $text;
	}

	function html_strip ($story)
	{
	$story = str_replace ('&lt;&lt;&lt;', '', $story);
	$story = str_replace ('&gt;&gt;&gt;', '', $story);
$story = str_replace ('>>', '>', $story);
	$story = str_replace ('&lt;&lt;', '', $story);
	$story = str_replace ('&gt;&gt;', '', $story);

	return $story;
	}

function url_img($url, $img)
	{

if (preg_match ('#skrinshot\.ru#i', $url)){$img=str_replace ('_preview', '', $img);}
if ((preg_match ('#fastpic#i', $url))){
	$url =  str_replace ('.html', '', $url);
	$url_news =  basename ($url);
	$image_news = basename ($img);
	$url =  str_replace ($image_news, $url_news, $img);
	$url =  str_replace ('/thumb/', '/big/', $url);
	}
if ((preg_match ('#radikal#i', $url))) $url = str_replace ('http://radikal.ru/', '', str_replace ('.html', '', $url));
	$r_url = end(explode(".", $url));
$r_img = end(explode(".", $img));
if ($r_url == 'jpg' or $r_url == 'png' or $r_url == 'jpeg' or $r_url == 'jpeg') return '[img]'.$url.'[/img]<br />';
if ($r_img == 'jpg' or $r_img == 'png' or $r_img == 'gif' or $r_img == 'jpeg') return '[img]'.$img.'[/img]<br />';
return '[url='.$url.'][img]'.$img.'[/img][/url]';
	}


	function rss_strip ($str)
	{
	$str = str_replace ('&amp;#8203;', '', $str);
	$str = str_replace ('&lt;', '<', $str);
	$str = str_replace ('&gt;', '>', $str);
	$str = str_replace ('&quot;', '"', $str);
	$str = str_replace ('&#34;', '"', $str);
	$str = str_replace ('&#39;', '\'', $str);
	$str = str_replace ('&#039;', '\'', $str);
	$str = str_replace ('&#40;', '(', $str);
	$str = str_replace ('&#41;', ')', $str);
	$str = str_replace ('&#58;', ':', $str);
	$str = str_replace ('&#91;', '[', $str);
	$str = str_replace ('&#93;', ']', $str);
	$str = str_replace ('&nbsp;', ' ', $str);
	$str = str_replace ('amp;', '', $str);
	$str = str_replace ('&raquo;', '»', $str);
	$str = str_replace ('&laquo;', '«', $str);
	$str = str_replace ('&rsaquo;', '›', $str);
	$str = str_replace ('&lsaquo;', '‹', $str);
	$str = str_replace ('[code]', '', $str);
	$str = str_replace ('[/code]', '', $str);
	$str = str_replace ('<![CDATA[', '', $str);
	$str = str_replace (']]>', '', $str);
$keys = array(
'À'=>'&#1040;',
'Á'=>'&#1041;',
'Â'=>'&#1042;',
'Ã'=>'&#1043;',
'Ä'=>'&#1044;',
'ª'=>'&#1028;',
'Å'=>'&#1045;',
'¨'=>'&#1025;',
'Æ'=>'&#1046;',
'½'=>'&#1029;',
'Ç'=>'&#1047;',
'²'=>'&#1030;',
'È'=>'&#1048;',
'É'=>'&#1049;',
'Ê'=>'&#1050;',
'Ë'=>'&#1051;',
'Ì'=>'&#1052;',
'Í'=>'&#1053;',
'Î'=>'&#1054;',
'Ï'=>'&#1055;',
'Ð'=>'&#1056;',
'Ñ'=>'&#1057;',
'Ò'=>'&#1058;',
'Ó'=>'&#1059;',
'Ô'=>'&#1060;',
'Õ'=>'&#1061;',
'Ö'=>'&#1062;',
'×'=>'&#1063;',
'Ø'=>'&#1064;',
'Ù'=>'&#1065;',
'Ú'=>'&#1066;',
'Û'=>'&#1067;',
'Ü'=>'&#1068;',
'Ý'=>'&#1069;',
'Þ'=>'&#1070;',
'ß'=>'&#1071;',
'à'=>'&#1072;',
'á'=>'&#1073;',
'â'=>'&#1074;',
'ã'=>'&#1075;',
'ä'=>'&#1076;',
'º'=>'&#1108;',
'å'=>'&#1077;',
'¸'=>'&#1105;',
'æ'=>'&#1078;',
'¾'=>'&#1109;',
'ç'=>'&#1079;',
'³'=>'&#1110;',
'è'=>'&#1080;',
'é'=>'&#1081;',
'ê'=>'&#1082;',
'ë'=>'&#1083;',
'ì'=>'&#1084;',
'í'=>'&#1085;',
'î'=>'&#1086;',
'ï'=>'&#1087;',
'ð'=>'&#1088;',
'ñ'=>'&#1089;',
'ò'=>'&#1090;',
'ó'=>'&#1091;',
'ô'=>'&#1092;',
'õ'=>'&#1093;',
'ö'=>'&#1094;',
'÷'=>'&#1095;',
'ø'=>'&#1096;',
'ù'=>'&#1097;',
'ú'=>'&#1098;',
'û'=>'&#1099;',
'ü'=>'&#1100;',
'ý'=>'&#1101;',
'þ'=>'&#1102;',
'ÿ'=>'&#1103;',
);
foreach ($keys as $key => $value){
$str = str_replace ($value, $key, $str);
}
$values = array (
'&#174;'=>'®',
'&#167;'=>'§',
'&#169;'=>'©',
'&#176;'=>'°',
'&#8482;'=>'™',
'&#8230;'=>'…',
'&#8226;'=>'•',
'&#8211;'=>'–',
'&#8212;'=>'—',
'&#177;'=>'±',
'&#8470;'=>'¹',
'&#38;'=>'&',
'&#60;'=>'<',
'&#62;'=>'>',
'&#45;'=>'— ',
'&#8216;'=>'‘',
'&#8217;'=>'’',
'&#171;'=>'«',
'&#150;'=>'—',
'&#133;'=>'...',
'&#187;'=>'»',
'&#8217;'=>'’',
'&#8222;'=>'„',
'&#8220;'=>'“',
'&#8220;'=>'“',
'&#8221;'=>'”',
'&#96;'=>'`',
'&#35;'=>'#',
'&#36;'=>'$',
'&#37;'=>'%',
'&#38;'=>'&',
'&#39;'=>'\'',
'&#40;'=>'(',
'&#41;'=>')',
'&#42;'=>'*',
'&#43;'=>'+',
'&#44;'=>',',
'&#45;'=>'— ',
'&#46;'=>'.',
'&#47;'=>'/',
'&#57;'=>'0 — 9',
'&#48;'=>'0 — 9',
'&#58;'=>':',
'&#60;'=>'<',
'&#61;'=>'=',
'&#62;'=>'>',
'&#64;'=>'@',
'&#90;'=>'A — Z',
'&#65;'=>'A — Z',
'&#91;'=>'[',
'&#93;'=>']',
'&#94;'=>'^',
'&#95;'=>'_',
'&#96;'=>'`',
'&#122;'=>'a — z',
'&#97;'=>'a — z',
'&#123;'=>'{',
'&#124;'=>'|',
'&#125;'=>'}',
'&#126;'=>'~',
'&#159;'=>'— ',
'&#127;'=>'— ',
'&#163;'=>'ˆ',
'&#164;'=>'¤',
'&#166;'=>'¦',
'&#167;'=>'§',
'&#169;'=>'©',
'&#171;'=>'«',
'&#172;'=>'¬',
'&#174;'=>'®',
'&#176;'=>'°',
'&#177;'=>'±',
'&#181;'=>'µ',
'&#182;'=>'¶',
'&#183;'=>'·',
'&#8211;'=>'–',
'&#8212;'=>'—',
'&#8216;'=>'‘',
'&#8217;'=>'’',
'&#8218;'=>'‚',
'&#8220;'=>'“',
'&#8221;'=>'”',
'&#8222;'=>'„',
'&#8224;'=>'†',
'&#8225;'=>'‡',
'&#8240;'=>'‰',
'&#8249;'=>'‹',
'&#8250;'=>'›',
'&#8364;'=>'ˆ',
'&#8226;'=>'•',
'&#8230;'=>'…',
'&#8482;'=>'™',
'&reg;'=>'®',
'&sect;'=>'§',
'&copy;'=>'©',
'&deg;'=>'°',
'&trade;'=>'™',
'&hellip;'=>'…',
'&bull;'=>'•',
'&ndash;'=>'–',
'&mdash;'=>'—',
'&plusmn;'=>'±',
'&amp;'=>'&',
'&lt;'=>'<',
'&gt;'=>'>',
'&lsquo;'=>'‘',
'&rsquo;'=>'’',
'&laquo;'=>'«',
'&raquo;'=>'»',
'&rsquo;'=>'’',
'&bdquo;'=>'„',
'&ldquo;'=>'“',
'&ldquo;'=>'“',
'&rdquo;'=>'”',
'&amp;'=>'&',
'(&apos;)'=>'\'',
'&lt;'=>'<',
'&gt;'=>'>',
'&curren;'=>'¤',
'&brvbar;'=>'¦',
'&sect;'=>'§',
'&copy;'=>'©',
'&laquo;'=>'«',
'&not;'=>'¬',
'&reg;'=>'®',
'&deg;'=>'°',
'&plusmn;'=>'±',
'&micro;'=>'µ',
'&para;'=>'¶',
'&middot;'=>'·',
'&raquo;'=>'»',
'&ndash;'=>'–',
'&mdash;'=>'—',
'&lsquo;'=>'‘',
'&rsquo;'=>'’',
'&sbquo;'=>'‚',
'&ldquo;'=>'“',
'&rdquo;'=>'”',
'&bdquo;'=>'„',
'&dagger;'=>'†',
'&Dagger;'=>'‡',
'&permil;'=>'‰',
'&lsaquo;'=>'‹',
'&rsaquo;'=>'›',
'&euro;'=>'ˆ',
'&bull;'=>'•',
'&hellip;'=>'…',
'&trade;'=>'™',
);
foreach ($values as $value => $key){
$str = str_replace ($value, $key, $str);
}

$str = preg_replace ("#<script[^>]*?>.*?<\/script>#si","",$str);
	return $str;
	}


function create_URL ($story, $link)
	{
$story = str_replace ('[URL=/', '[URL=http://' . $link . '/', $story);
$story = str_replace ('[URL=./', '[URL=http://' . $link . '/', $story);
$story = str_replace ('[url=/', '[url=http://' . $link . '/', $story);
$story = str_replace ('[url=./', '[url=http://' . $link . '/', $story);
$story = str_replace ('[quote]http', '[quote]
http', $story);
$story = str_replace ('[quote]	http', '[quote]
http', $story);


return $story;
}





function replace_align ($story,$align)
	{
			$story = str_replace( "<br>", "\n", $story );
			$story = str_replace( "<br />", "\n", $story );
			$story = str_replace( "<BR>", "\n", $story );
			$story = str_replace( "<BR />", "\n", $story );

$story = preg_replace ("#\[center\]\[url=(.*?)\]\[img\](.+?)\[\/img\]\[\/url\]\[\/center\]#is", '[url=\\1][img]\\2[/img][/url]', $story);
$story = preg_replace ("#\[center\][\n\r\t ]+\[url=(.*?)\][\n\r\t ]+\[img\](.+?)\[\/img\][\n\r\t ]+\[\/url\][\n\r\t ]+\[\/center\]#is", '[url=\\1][img]\\2[/img][/url]', $story);
$story = preg_replace ("#\[center\][\n\r\t ]+\[\/center\]#is", '', $story);
	 if ($align == '2')
		 {
$story = str_replace ('[center]', '', $story);
$story = str_replace ('[/center]', '', $story);
$story = str_replace ('[img]', '[img=left]', $story);
$story = str_replace ('[thumb]', '[thumb=left]', $story);
$story = str_replace ('right', 'left', $story);
	}
	elseif ($align == '0')
	{
$story = str_replace ('[center]', '', $story);
$story = str_replace ('[/center]', '', $story);
$story = str_replace ('[img]', '[img=right]', $story);
$story = str_replace ('[thumb]', '[thumb=right]', $story);
$story = str_replace ('left', 'right', $story);
	}
	elseif ($align == '1')
	{
$story = str_replace ('[center]', '', $story);
$story = str_replace ('[/center]', '', $story);
$story = preg_replace ('#\[center\](.+?)\[\/center\]#is', '\\1', $story);
$story = preg_replace ("#(<(object|embed).*?</\\2>)#is", '[center]<noindex>\\1</noindex>[/center]', $story);
$story = preg_replace ('#<\/noindex>\[\/center\].+?<\/object>#i', '</object></noindex>[/center]', $story);
$story = preg_replace ("#\\[video=(.+?)\\]#is", '[center][video=\\1][/center]', $story);
$story = preg_replace ("#\[center\]\[url=(.+?)\](.+?)\[\/url\]\[\/center\]#is", '[url=\\1]\\2[/url]
', $story);
/*$story = preg_replace ("#\\[url=(.+?)\\](.+?)\\[/url\\]#is", '[center][url=\\1]\\2[/url][/center]', $story);*/
$story = preg_replace ("#\\[img\\](.+?)\\[/img\\]#is", '[center][img]\\1[/img][/center]', $story);
$story = preg_replace ("#\\[thumb\\](.+?)\\[/thumb\\]#is", '[center][thumb]\\1[/thumb][/center]', $story);
$story = str_replace ('[youtube=', '[center][youtube=', $story);
$story = str_replace ('embedded]', 'embedded][/center]', $story);
$story = str_replace ('[center][thumb][center][img]', '[center][img]', $story);
$story = str_replace ('[/img][/center][/thumb][/center]', '[/img][/center]', $story);
$story = preg_replace ("#\\[left\\](.+?)\\[/left\\]#is", '\\1', $story);
$story = preg_replace ("#\\[right\\](.+?)\\[/right\\]#is", '\\1', $story);
	}
$story = str_replace ('[center][center]', '[center]', $story);
$story = str_replace ('[/center][/center]', '[/center]', $story);
$story = str_replace ('[/center][center]', '', $story);
$story = preg_replace ("#\[center\][\n\r\t ]+\[center\]#is", '[center]', $story);
$story = preg_replace ("#\[\/center\][\n\r\t ]+\[\/center\]#is", '[/center]', $story);


return $story;
}


function replace_tags ($story,$vb_teg=1)
{
	$story = strip_tags($story);
	$key	=	array(',,','/','//','&raquo;','|',':',' ',',,','(',')','-');
	$value = array(',',',', ',' ,'','','',',',',',',',',',',');
$quotes = array(  "\t",'\n','\r', "\n","\r", '\\',",",".","/","¬","#",";",":","@","~","[","]","{","}","=","-","+",")","(","*","&","^","%","$","<",">","?","!", '"', ',,','/','//','&raquo;','|',':',' ',',,','(',')','-' );
	$story = str_replace ("'", '', $story);
	$story = str_replace ($quotes, ',', $story);
	$story = str_replace (',,', ',', $story);
	$story =	trim($story,',');
$strs = array();
$sort = array();
$story = explode(',',$story);
	foreach ($story as $s){
$obr = '/(à|åâ|îâ|èå|üå|å|èÿìè|ÿìè|àìè|åè|èè|è|èåé|åé|îé|èé|é|èÿì|ÿì|èåì|åì|àì|îì|î|ó|àõ|èÿõ|ÿõ|û|ü|èþ|üþ|þ|èÿ|üÿ|ÿ)$/';
$str = preg_replace($obr, '', trim($s));
if (!(in_array($str,$strs)) and strlen ($s) > 3 and preg_match('/\D+/i',$s))
	{
$strs []= $str;
$sort []= $s;
}
}
if ($vb_teg == '')$vb_teg=1;
$kol_teg = explode('/',$vb_teg);
$match = array_chunk($sort, $kol_teg[0]);
$strsm = array ();
if ( count ($kol_teg) == 1)$kol_teg[1] = count ($match);

for ($i=0;$i<=intval($kol_teg[1])-1;$i++)
	{
$strsm[] = implode (' ',$match[$i]);
}


$sort = implode (',',$strsm);
$strs = implode (',',$strs);
return array($sort, $strs);
}

function replace_mb ($story)
	{
				$key = array(" MB", " Ìá", " ìá", " ÌÁ", " Mb", " mb", "mb", "MB", "Ìá", "ìá", "ÌÁ", "Mb", "	MB", "	Ìá", "	ìá", "	ÌÁ", "	Mb", "	mb",);
				$value = array(" Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb", " Mb");
				$story = str_replace ($key, $value, $story);

return $story;
	}

function replace_url ($str)
	{
$key1 = array ("à","á","â","ã","ä","å","¸","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","ô","õ","ö","÷","ø","ù","ü","ú","û","ý","þ","ÿ"," ");
$key2 = array ("%D0%B0","%D0%B1","%D0%B2","%D0%B3","%D0%B4","%D0%B5","%D1%91","%D0%B6","%D0%B7","%D0%B8","%D0%B9","%D0%BA","%D0%BB","%D0%BC","%D0%BD","%D0%BE","%D0%BF","%D1%80","%D1%81","%D1%82","%D1%83","%D1%84","%D1%85","%D1%86","%D1%87","%D1%88","%D1%89","%D1%8C","%D1%8A","%D1%8B","%D1%8D","%D1%8E","%D1%8F","%20");
$str = str_replace ($key1, $key2, $str);
return $str;
}

function parse_Thumb ($story)
	{
$story = preg_replace ('#<!--ThumbBegin-->(.+?)ShowBild\\(\\\'(.+?)\\\'\\)(.+?)<!--ThumbEnd-->#is', '[THUMB]\\2[/THUMB]', $story);
$story = preg_replace( '#<!--TBegin-->(.+?)href=[\'"](.+?)[\'"].+?<!--TEnd-->#is', '[THUMB]\\2[/THUMB]', $story );
$story = preg_replace ('#<!--ThumbBegin-->(.+?)href=[\'"](.+?)[\'"].+?<!--ThumbEnd-->#is', '[THUMB]\\2[/THUMB]', $story);
$story = preg_replace ('#<!--ThumbBegin_hl-->(.+?)href=[\'"](.+?)[\'"].+?<!--ThumbEnd_hl-->#is', '[THUMB]\\2[/THUMB]', $story);

return $story;
}

function url_img_ ($story)
	{
$story = preg_replace( "#\[img\]\S+?smile\S+?\.gif\[\/img\]#i", "", $story );
$story = preg_replace( "#\[url=(\S+?)\][\n\r\t ]+\[img\](\S+?)\[\/img\][\n\r\t ]+\[\/url\]#ise", "url_img('\\1', '\\2')", $story );
$story = preg_replace( "#\[url=(\S+?)\]\[img\](\S+?)\[\/img\]\[\/url\]#ise", "url_img('\\1', '\\2')", $story );
$story = preg_replace( "#\[leech=(\S+?)\][\n\r\t ]+\[img\](\S+?)\[\/img\][\n\r\t ]+\[\/leech\]#ise", "url_img('\\1', '\\2')", $story );
$story = preg_replace( "#\[leech(\S+?)\]\[img\](\S+?)\[\/img\]\[\/leech\]#ise", "url_img('\\1', '\\2')", $story );return $story;}
function prg($data){return preg_replace ('/\$([a-z_]+)/se', "url_i('\\1')",$data);}
function parse_host ($story,$host,$path){
	$story = preg_replace ('#\\[img\\](\\S.+?)\\[/img\\]#ie', 'image_path_build(\'\\1\', \'' . $host . '\', \'' . $path . '\')', $story);
$story = preg_replace ('#\\[img(.*?)\\](\\S.+?)\\[/img\\]#ie', 'image_path_build(\'\\2\', \'' . $host . '\', \'' . $path . '\')', $story);
$story = preg_replace ('#\\[thumb\\](\\S.+?)\\[/thumb\\]#ie', 'thumb_path_build(\'\\1\', \'' . $host . '\', \'' . $path . '\')', $story);
$story = preg_replace ('#\\[thumb(.*?)\\](\\S.+?)\\[/thumb\\]#ie', 'thumb_path_build(\'\\2\', \'' . $host . '\', \'' . $path . '\')', $story);
$story = str_replace (':http://', 'http://', $story);
return $story;
}


function replace_quote ($story)
	{
/*$story = preg_replace ('#\[url\](\S+?)\[\/url\]#is', '[quote][url]\\1[/url]', $story);
$story = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[quote][url=\\1]\\2[/url][/quote]', $story);*/
$story = preg_replace ('#\[\/quote\][\r\n\t ]+\[quote\]#', '
', $story);
return $story;}

function replace_hide ($story)
	{
$story = str_replace ('[hide]', "", $story);
$story = str_replace ('[/hide]', "", $story);
$story = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[hide][url=\\1]\\2[/url][/hide]', $story);
$story = preg_replace ('#\[hide\]\[url=(\S+?)\]\[img(\S+?)\](.+?)\[\/img\]\[\/url\]\[\/hide\]#is', '[url=\\1][img\\2]\\3[/img][/url]', $story);
$story = preg_replace ('#\[leech=(\S+?)\](.+?)\[\/leech\]#is', '[hide][leech=\\1]\\2[/leech][/hide]', $story);
$story = preg_replace ('#\[hide\]\[leech=(\S+?)\]\[img(\S+?)\](.+?)\[\/img\]\[\/leech\]\[\/hide\]#is', '[leech=\\1][img\\2]\\3[/img][/leech]', $story);
$story = preg_replace ('#\[\/hide\][\r\n\t ]+\[hide\]#is', "\n", $story);
return $story;
}

function replace_leech ($story)
	{
$story = preg_replace ('#\[url=(.+?dude.+?)\](.+?)\[\/url\]#ie', "dude_noleech('\\1','\\2', true)", $story);
$story = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[leech=\\1]\\2[/leech]', $story);
return $story;
}

function dude_noleech ($story,$title,$leech=false)
{
$story = preg_replace ('#.+?\?(.*)#is', '\\1', $story);
list($type, $url) = explode(":",urldecode($story), 2);
if ($leech)$url = "[leech=".base64_decode($url)."]".$title."[/leech]";
else $url = "[url=".base64_decode($url)."]".$title."[/url]";
return $url;
}

function replace_noleech ($story)
	{
$story = preg_replace ('#\[url=(.+?\/out.+?)](.+?)\[\/url\]#ie', "dude_noleech('\\1','\\2')", $story);
$story = preg_replace ('#\[url=(.+?dude.+?)\](.+?)\[\/url\]#ie', "dude_noleech('\\1','\\2')", $story);
$story = str_replace ('[leech', '[url', $story);
$story = str_replace ('leech]', 'url]', $story);
return $story;
}

	function create_images($story, $title) {
		global $config_rss;
			if( intval( $config_rss['maxWidth'] ) ) {
$story = preg_replace( "#(\[img\](.+?)\[\/img\])#ie", "urlth_image('\\1', '\\2', \$title)", $story );
$story = preg_replace( "#(\[thumb\](.+?)\[\/thumb\])#ie", "urlth_image('\\1', '\\2', \$title)", $story );
$story = preg_replace( "#(\[img=(.+?)\](.+?)\[\/img\])#ie", "urlth_image('\\1', '\\3', \$title, '\\2')", $story );
$story = preg_replace( "#(\[thumb=(.+?)\](.+?)\[\/thumb\])#ie", "urlth_image('\\1', '\\3', \$title, '\\2')", $story );
			}

return $story;
	}

function urlth_image($str, $url = "", $title = "", $align = "") {
global $config_rss;

			$alt = "alt='" . $title . "' title='" . $title . "' ";


				$img_info = @getimagesize( $url );


				if( $img_info[0] > $config_rss['maxWidth'] or $img_info[1] > $config_rss['maxWidth']) {
				if ($config_rss['upload_t_size'] == '1' and $img_info[0] > $config_rss['maxWidth']){
					$infos = "width=\"{$config_rss['maxWidth']}\"";
				}elseif($config_rss['upload_t_size'] == '2' and $img_info[1] > $config_rss['maxWidth']){
					$infos = "height=\"{$config_rss['maxWidth']}\"";
				}elseif($config_rss['upload_t_size'] == '0' ){
					if ($img_info[0] > $img_info[1]){
					$infos = "width=\"{$config_rss['maxWidth']}\"";
					}else{
					$infos = "height=\"{$config_rss['maxWidth']}\"";
					}
				}else{return $str;}

if( $align == '' ) return "<a href=\"{$url}\" onclick=\"return hs.expand(this)\" ><img src=\"$url\" $infos {$alt} /></a>";
else return "<a href=\"{$url}\" onclick=\"return hs.expand(this)\" ><img align=\"$align\" src=\"$url\" $infos {$alt} /></a>";
}

		return $str;
}


?>
