<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

include_once('geshi/geshi.php');

if (!isset($lang_message)) $lang_message = language_forum ("board/lang_message");

function bb_decode($msg, $bb_allowed)
{
    global $cache_config, $lang_message;
    
    if (!$bb_allowed) $bb_allowed = array();
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("b", $bb_allowed)))
    	$msg = preg_replace("#\[b\](.+?)\[/b\]#is".regular_coding(), "<strong>\\1</strong>", $msg); //Bold	
        
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("i", $bb_allowed)))
        $msg = preg_replace("#\[i\](.+?)\[/i\]#is".regular_coding(), "<i>\\1</i>", $msg); //italic
        
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("s", $bb_allowed)))
	   $msg = preg_replace("#\[s\](.+?)\[/s\]#is".regular_coding(), "<s>\\1</s>", $msg); //S
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("u", $bb_allowed)))
	   $msg = preg_replace("#\[u\](.+?)\[/u\]#is".regular_coding(), "<u>\\1</u>", $msg); //S
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("text_align", $bb_allowed)))
        $msg = preg_replace("#\[(left|right|center)+\](.+?)\[/\\1\]#is".regular_coding(), "<div align=\"\\1\">\\2</div>", $msg );
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("size", $bb_allowed)))
	   $msg = preg_replace("#\[size=([0-9]+?)\](.+?)\[/size\]#is".regular_coding(), "<font size='\\1'>\\2</font>", $msg); //size
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("font", $bb_allowed)))
	   $msg = preg_replace("#\[font=([a-z ]+?)\](.+?)\[/font\]#is".regular_coding(), "<font style='font-family:\\1'>\\2</font>", $msg); //font-family
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("color", $bb_allowed)))
    {
	   $msg = preg_replace("#\[color=(\#[0-9ACDEF]+?)\](.+?)\[/color\]#is".regular_coding(), "<font style='color:\\1'>\\2</font>", $msg); //Color
       $msg = preg_replace("#\[color=([a-z]+?)\](.+?)\[/color\]#is".regular_coding(), "<font color='\\1'>\\2</font>", $msg); //Color
    }
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("quote", $bb_allowed)))
    {
        if(preg_match_all("#\[quote(=((.+?)(\|([0-9\., :]+?))?))?\]#is".regular_coding(), $msg, $shadow) == preg_match_all("#\[/quote\]#is".regular_coding(), $msg, $shadow))
        {            
            $msg = preg_replace("#\[quote\]#is".regular_coding(), "<blockquote class=\"blockquote\"><p><span class=\"titlequote\">".$lang_message['quote_title']."</span><span class=\"textquote\">", $msg); //quote
            $msg = preg_replace("#\[quote(=((.+?)(\|([0-9\., :]+?))?))?\]#is".regular_coding(), "<blockquote class=\"blockquote\"><p><span class=\"titlequote\">\\3 (\\5) ".$lang_message['quote_title2']."</span><span class=\"textquote\">", $msg); //quote
    	    $msg = preg_replace("#\[/quote\]#is".regular_coding(), "</span></p></blockquote><!--quote -->", $msg); //quote
            $msg = preg_replace("#<blockquote class=\"blockquote\"><p><span class=\"titlequote\">(.+?) \(\) (.+?)</span>#is".regular_coding(), "<blockquote class=\"blockquote\"><p><span class=\"titlequote\">\\1 \\2</span>", $msg); //quote
    	} 
    }
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("youtube", $bb_allowed)))
        $msg = preg_replace("#\[youtube=(\S.+?)\]#ise".regular_coding(), "bb_create_video('\\1')", $msg);
    
	if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("url", $bb_allowed)))
    {      
        $msg = preg_replace("#(^|\s|>)((http://|https://|ftp://|www\.)\w+[^\s\[\]\<]+)#i".regular_coding(), '\\1[url=\\2]\\2[/url]', $msg);
        $msg = preg_replace("#\[url=(\S.+?)\](.+?)\[/url\]#ise".regular_coding(), "bb_url('\\1', '\\2')", $msg); //url        
    }
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("email", $bb_allowed)))
	   $msg = preg_replace("#\[email=([a-z_\.\-0-9]+?@[a-z_\.\-0-9]+?\.[a-z]+?)\](.+?)\[/email\]#is".regular_coding(), "<a href='mailto:\\1'>\\2</a>", $msg); //email
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("img", $bb_allowed)))
    {
        $msg = preg_replace("#\[img=?\](.+?)\[/img\]#ise".regular_coding(), "bb_create_img('\\1')", $msg); //img center
    	$msg = preg_replace("#\[img=(left|right|center)+?\](.+?)\[/img\]#ise".regular_coding(), "bb_create_img('\\2', '\\1')", $msg); //img left|right
    }
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("php", $bb_allowed)))
    $msg = preg_replace_callback("#\[php\]([\s\S]+?)\[/php\]#is".regular_coding(), "php_syntax", $msg); //php	
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("javascript", $bb_allowed)))
	$msg = preg_replace_callback("#\[javascript\]([\s\S]+?)\[/javascript\]#is".regular_coding(), "javascript_syntax", $msg); //javascript	
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("html", $bb_allowed)))
	$msg = preg_replace_callback("#\[html\]([\s\S]+?)\[/html\]#is".regular_coding(), "html_syntax", $msg); //html	
    
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("translite", $bb_allowed)))
	$msg = preg_replace_callback("#\[translite\]([\s\S]+?)\[/translite\]#is".regular_coding(), "transliteit", $msg); //translite	   

    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("spoiler", $bb_allowed)))
    {
        if(preg_match_all("#\[spoiler(=(.+?))?\]#is".regular_coding(), $msg, $shadow) == preg_match_all("#\[/spoiler\]#is".regular_coding(), $msg, $shadow))
        {
            $msg = preg_replace_callback("#\[spoiler(=(.+?))?\]#is".regular_coding(), "makespoiler", $msg); //spoiler	
    	    $msg = preg_replace("#\[/spoiler\]#i".regular_coding(), "</div></blockquote><!--spoiler -->", $msg); //spoiler
    	}
    }

    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("smile", $bb_allowed)))
	   $msg = preg_replace("#::([0-9]{3,3})::#i".regular_coding(), "<img id='smiles_img' src='{TEMPLATE}/bbcode/smiles/\\1.gif' />", $msg); //smailes
       	
    if (!count($bb_allowed) OR (count($bb_allowed) AND in_array("search", $bb_allowed)))
   	    $msg = preg_replace_callback("#\[search\](.+?)\[/search\]#is".regular_coding(), "search_tag", $msg); //Search	
        
    return $msg;
}

function bb_encode($msg)
{
    global $cache_config, $lang_message;
    
	$msg = preg_replace("#<strong>(.+?)</strong>#is".regular_coding(), "[b]\\1[/b]", $msg); //Bold
    $msg = preg_replace("#<b>(.+?)</b>#is".regular_coding(), "[b]\\1[/b]", $msg); //Bold	
	$msg = preg_replace("#<i>(.+?)</i>#is".regular_coding(), "[i]\\1[/i]", $msg); //italic
	$msg = preg_replace("#<s>(.+?)</s>#is".regular_coding(), "[s]\\1[/s]", $msg); //S
	$msg = preg_replace("#<u>(.+?)</u>#is".regular_coding(), "[u]\\1[/u]", $msg); //S
	$msg = preg_replace("#<font size='([0-9]+?)'>(.+?)</font>#is".regular_coding(), "[size=\\1]\\2[/size]", $msg); //size
	$msg = preg_replace("#<font style='font-family:([a-z ]+?)'>(.+?)</font>#is".regular_coding(), "[font=\\1]\\2[/font]", $msg); //font-family
	$msg = preg_replace("#<font style='color:(\#[0-9ACDEF]+?)'>(.+?)</font>#is".regular_coding(), "[color=\\1]\\2[/color]", $msg); //Color
    $msg = preg_replace("#<font color='([a-z]+?)'>(.+?)</font>#is".regular_coding(), "[color=\\1]\\2[/color]", $msg); //Color
    
    $msg = preg_replace("#<blockquote class=\"blockspoiler\"><span class=\"titlespoiler\"><a href='\#' onclick=\"ShowAndHide\('.+?'\); return false;\">(.+?)</a></span><div id='.+?' style='display:none;' class=\"textspoiler\">(.+?)</div></blockquote><!--spoiler -->#is".regular_coding(), "[spoiler=\\1]\\2[/spoiler]", $msg);
    $msg = str_replace("[spoiler=".$lang_message['spoiler_title']."]", "[spoiler]", $msg);
       
    $pattern = array(
                    "#<blockquote class=\"blockquote\"><p><span class=\"titlequote\">".$lang_message['quote_title']."</span><span class=\"textquote\">#is".regular_coding(),
                    "#<blockquote class=\"blockquote\"><p><span class=\"titlequote\">(.+?) ?(\(([0-9\., :]+?)\))? ".$lang_message['quote_title2']."</span><span class=\"textquote\">#is".regular_coding(),
                    "#<blockquote class=\"blockquote\"><p><span class=\"titlequote\">(.+?)</span><span class=\"textquote\">#is".regular_coding(),
					"#</span></p><\/blockquote><!--quote -->#i"
                    ); 
                    
   	$replacement = array( 
                        '[quote]',
                    	'[quote=$1|$3]', 
                        '[quote]',
                    	'[/quote]'
                        ); 
    
	$msg = preg_replace($pattern, $replacement, $msg);
          	
	$msg = preg_replace("#\[quote=(.+?)\|\]#is".regular_coding(), "[quote=\\1]", $msg); 
    
    $msg = preg_replace_callback("#<!-- Search code --><a href=\".*?&w=.*?&p=[1|0]\".*?\>(.*?)</a><!--/Search code -->#is".regular_coding(), "search_tag_back", $msg); //Search
    
	$msg = preg_replace("#<a href='mailto:([a-z_\.\-0-9]+?@[a-z_\.\-0-9]+?\.[a-z]+?)'>(.+?)</a>#is".regular_coding(), "[email=\\1]\\2[/email]", $msg); //email
    $msg = preg_replace("#<a href=['\"](\S.+?)['\"]\s*(target=\"_blank\")?\s*>(.+?)</a>#ise".regular_coding(), "bb_url('\\1', '\\3', false)", $msg); //url
    
    $msg = preg_replace('#<object width="([0-9]){1,4}" height="([0-9]){1,4}"><param name="movie" value="http://youtube.com/v/(.*?)\?fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="(.*?)" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="([0-9]){1,4}" height="([0-9]){1,4}"></embed></object>#is'.regular_coding(), '[youtube=http://youtube.com/watch?v=\\3]', $msg); //youtube
    $msg = preg_replace('#<object width="([0-9]){1,4}" height="([0-9]){1,4}"><param name="movie" value="http://video.rutube.ru/(.*?)"></param><param name="wmode" value="window"></param><param name="allowFullScreen" value="true"></param><embed src="(.*?)" type="application/x-shockwave-flash" wmode="window" width="([0-9]){1,4}" height="([0-9]){1,4}" allowFullScreen="true" ></embed></object>#is'.regular_coding(), '[youtube=http://video.rutube.ru/\\3]', $msg); //youtube
    $msg = preg_replace('#<iframe src="http://([vkontakte\.ru]|[vk\.com])+/video_ext.php\?(.*?)" width="([0-9]){1,4}" height="([0-9]){1,4}" frameborder="0"></iframe>#is'.regular_coding(), '[youtube=http://vk.com/video_ext.php?\\2]', $msg); //youtube
    $msg = preg_replace('#<iframe src="http://player.vimeo.com/video/(.*?)\?title=0&amp;byline=0&amp;portrait=0" width="([0-9]){1,4}" height="([0-9]){1,4}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>#is'.regular_coding(), '[youtube=http://vimeo.com/\\1]', $msg); //youtube

    $msg = preg_replace("#<!-- Small_img:([:a-z_\-/\.0-9]+?)\|(left|right|center)? -->(.+?)<!--/Small_img -->#ise".regular_coding(), "bb_create_img_back('\\1|\\2')", $msg); //php	
	$msg = preg_replace("#<center><img src='(\S+?)'\s*(class='lb_img')?\s*/></center>#is".regular_coding(), "[img]\\1[/img]", $msg); //img center
	$msg = preg_replace("#<img src='(\S+?)' align='(left|right|center)'\s*(class='lb_img')?\s*/>#is".regular_coding(), "[img=\\2]\\1[/img]", $msg); //img left|right
    
    $msg = preg_replace("#<center>(.+?)</center>#is".regular_coding(), "[center]\\1[/center]", $msg); //center
    
    $msg = preg_replace("#<img id='smiles_img' src='{TEMPLATE}/bbcode/smiles/([0-9]{3,3})\.gif' />#is".regular_coding(), "::\\1::", $msg); //smailes
    
    $msg = preg_replace_callback("#<!-- PHP code -->(.+?)<!--/PHP code -->#is".regular_coding(), "php_decode", $msg); //php	
	$msg = preg_replace_callback("#<!-- JS code -->(.+?)<!--/JS code -->#is".regular_coding(), "js_decode", $msg); //php	
	$msg = preg_replace_callback("#<!-- HTML code -->(.+?)<!--/HTML code -->#is".regular_coding(), "html_decode", $msg); //php
    
    $msg = preg_replace( "#<div align=['\"](left|right|center)+['\"]>(.+?)</div>#is".regular_coding(), "[\\1]\\2[/\\1]", $msg );
	
	return $msg;
}

function search_tag ($msg)
{
    $text = $msg[1];    
    $text = strip_tags($text);
    return "<!-- Search code --><a href=\"".link_on_module("search")."&w=".urlencode($text)."&p=1\">".$text."</a><!--/Search code -->";
}

function search_tag_back ($msg)
{
    $text = $msg[1];
    return "[search]".$text."[/search]";
}

function bb_create_video ($url = "")
{
    if (!$url) return "[youtube=".$url."]";
    
    $url = bb_clear_url($url);
    $url = str_replace("&amp;", "&", $url);
    
    if (!$url) return "[youtube=".$url."]";
    
    $url_parse = parse_url($url);
    $url_parse['host'] = strtolower(str_replace("www.", "", $url_parse['host']));
    
    $allowed_host = array("youtube.com", "youtu.be", "rutube.ru", "video.rutube.ru", "vkontakte.ru", "vk.com", "vimeo.com");
    
    if (!in_array($url_parse['host'], $allowed_host)) return "[youtube=".$url."]";
    
    $video_link = "";
    
    if ($url_parse['host'] == "youtube.com" OR $url_parse['host'] == "rutube.ru" OR $url_parse['host'] == "video.rutube.ru" OR $url_parse['host'] == "youtu.be")
    {
        if ($url_parse['host'] == "youtu.be")
        {
            $video_link = trim(str_replace("/", "", $url_parse['path']));
        }
        elseif ($url_parse['host'] == "video.rutube.ru")
        {
            $video_link = trim(str_replace("/", "", $url_parse['path']));
        }
        else
        {
            $query = explode('&', $url_parse['query']);
            
            foreach ($query as $value)
            {
                list ($key, $data) = explode("=", $value);
                if ($key == "v")
                {
                    $video_link = trim($data);
                    break;
                }
            }
        }
        
        if (!$video_link) return "[youtube=".$url."]";
        
        if ($url_parse['host'] == "youtube.com" OR $url_parse['host'] == "youtu.be")
            $buld_code = '<object width="480" height="385"><param name="movie" value="http://youtube.com/v/'.$video_link.'?fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://youtube.com/v/'.$video_link.'?fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>';    
        else
            $buld_code = '<object width="470" height="353"><param name="movie" value="http://video.rutube.ru/'.$video_link.'"></param><param name="wmode" value="window"></param><param name="allowFullScreen" value="true"></param><embed src="http://video.rutube.ru/'.$video_link.'" type="application/x-shockwave-flash" wmode="window" width="470" height="353" allowFullScreen="true" ></embed></object>';
    }
    elseif ($url_parse['host'] == "vkontakte.ru" OR $url_parse['host'] == "vk.com")
    {
        $query = explode('&', $url_parse['query']);
        $massiv = array();
        foreach ($query as $value)
        {
            list ($key, $data) = explode("=", $value);
            if ($key == "oid") $massiv[$key] = intval($data);
            elseif ($key == "id") $massiv[$key] = intval($data);
            elseif ($key == "hd") $massiv[$key] = intval($data);
            elseif ($key == "hash") $massiv[$key] = $data;
        }

        if (count($massiv) < 3) return "[youtube=".$url."]";
        
        $video_link = "";
        
        foreach ($massiv as $key => $value)
        {
            $video_link .= $key."=".$value."&";
        }
        $video_link .= "sd";
        
        $buld_code = '<iframe src="http://vk.com/video_ext.php?'.$video_link.'" width="607" height="360" frameborder="0"></iframe>';    
    }
    elseif ($url_parse['host'] == "vimeo.com")
    {
        $query = explode('/', $url_parse['path']);        
        if (!intval($query[1])) return "[youtube=".$url."]";
        
        $buld_code = '<iframe src="http://player.vimeo.com/video/'.intval($query[1]).'?title=0&amp;byline=0&amp;portrait=0" width="490" height="300" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';  
    }
    return $buld_code;
}

function bb_url ($link, $text, $encode = true)
{
    global $cache_config, $cache_group, $member_id, $do, $op;
    
    if ($encode)
    {
        $link = bb_clear_url(trim($link));
        
        if (clean_url($link) != clean_url($cache_config['general_site']['conf_value']))
            $target = "target=\"_blank\"";
        else
            $target = "";
        
       	if(!preg_match( "#^(http|news|https|ed2k|ftp|aim|mms)://|(magnet:?)#", $link ))
            $link = 'http://'.$link;
            
        if ($link == 'http://' )
            return "[url=".$link."]".$text."[/url]";
                        
        if ($target)
        {
            $redirect = true;
            if ($cache_config['link_white_list']['conf_value'])
            {
                $white_list = explode ("\r\n", $cache_config['link_white_list']['conf_value']);
                foreach ($white_list as $white_list_c)
                {
                    if (clean_url($link) == clean_url($white_list_c))
                    {
                        $redirect = false;
                        break;
                    }
                }
            }
            
            if ($redirect)
            {
                if ($do == "users" AND $op == "edit")
                    $link = away_from_here($link, $cache_group[$member_id['user_group']]['g_link_signature']);
                else
                    $link = away_from_here($link, $cache_group[$member_id['user_group']]['g_link_forum']);
            }
        }
        
        return "<a href=\"".$link."\" ".$target.">".$text."</a>";
    }
    else
    {
        if (preg_match("#away\.php\?s\=[http|www](.+?)#is".regular_coding(), $link))
        {
            $link = preg_replace("#((.+?)away\.php\?s\=)#is".regular_coding(), "", $link);
            $link = away_from_here($link, 1, 0);
            
            return "[url=".$link."]".$text."[/url]";
        }
        
        return "[url=".$link."]".$text."[/url]";
    }
}

function bb_create_img($img, $align = "")
{
    global $cache_config;

    $img = trim($img);
    $img = urldecode($img);
        
    if( preg_match( "#[?&;%<\[\]]#", $img ) )
    {
        if( $align != "" )
            return "[img=" . $align . "]" . $img . "[/img]";
        else
            return "[img]" . $img . "[/img]";
    }
                
    $img = bb_clear_url($img);
        
    if($img == "") return;
        
    if ($cache_config['pic_smallphp']['conf_value'])
    {
        $resize = false;
        $max_width = intval($cache_config['pic_autosize']['conf_value']);
        if($max_width)
        {
            $img_info = @getimagesize( $img );
            if( $img_info[0] > $max_width )
            {
                $out_heigh = ($img_info[1] / 100) * ($max_width / ($img_info[0] / 100));
                $out_heigh = floor( $out_heigh );
    
                if(!$align OR $align == "center")
                {
                    $img_block = "<!-- Small_img:".$img."| --><center><a href='".$img."' onclick=\"return hs.expand(this)\"><img src='".$img."' width='".$max_width."' height='".$out_heigh."' /></a></center><!--/Small_img -->";
                }
                else
                    $img_block = "<!-- Small_img:".$img."|".$align." --><a href='".$img."' onclick=\"return hs.expand(this)\"><img align='".$align."' src='".$img."' width='".$max_width."' height='".$out_heigh."' /></a><!--/Small_img -->";
                    
                $resize = true;
            }		
        }
        
        if (!$resize)
        {
            if (!$align OR $align == "center")
                $img_block = "<center><img src='".$img."' /></center>";
            else
                $img_block = "<img src='".$img."' align='".$align."' />";
        }
    }
    else
    {        
        if (!$align OR $align == "center")
            $img_block = "<center><img src='".$img."' class='lb_img' /></center>";
        else
            $img_block = "<img src='".$img."' align='".$align."' class='lb_img' />";
    }

	return $img_block;
}

function bb_create_img_back($img)
{
    global $cache_config;
    
    $img = explode ("|", $img);
    
    if ($img[1] != "")  // Если указано расположение картинки
        return "[img=".$img[1]."]".$img[0]."[/img]";
    else
        return "[img]".$img[0]."[/img]";
}

function bb_clear_url($url)
{
    $url = strip_tags( trim( stripslashes( $url ) ) );
	$url = str_replace( '\"', '"', $url );
	$url = str_replace( "document.cookie", "", $url );
	$url = str_replace( " ", "%20", $url );
	$url = str_replace( "'", "", $url );
	$url = str_replace( '"', "", $url );
	$url = str_replace( "<", "&#60;", $url );
	$url = str_replace( ">", "&#62;", $url );
	$url = preg_replace( "#javascript:#i", "j&#097;vascript:", $url );
	$url = preg_replace( "#data:#i", "d&#097;ta:", $url );
		
	return $url;
}

function makespoiler($arg)
{
    global $lang_message;
    
	if($arg[2]) $name = $arg[2];
    else $name = $lang_message['spoiler_title'];
	
	$id = md5($arg[3].$name.rand(5,1000));
	
	$divs = "<blockquote class=\"blockspoiler\">";
    $divs.= "<span class=\"titlespoiler\"><a href='#' onclick=\"ShowAndHide('".$id."'); return false;\">".$name."</a></span>"; 
	$divs.= "<div id='".$id."' style='display:none;' class=\"textspoiler\">".$arg[3];

	return $divs;
}

function transliteit($str)
{
    $tr = array(
        "A","B","V","G", "D","E", "J","Z","I",
        "Y","K","L","M","N", "O","P","R","S","T",
        "U","F","H","TS","CH", "SH","SCH","YI",
        "YU","YA",
        "a","b","v","g", "d","e", "j","z","i",
        "y","k","l","m","n", "o","p","r","s","t",
        "u","f","h","ts","ch", "sh","sch","yi",
        "yu","ya"
    );	
    
     $rr = array(
        "A","Б","В","Г", "Д","Е","Ж","З","И",
        "Й","К","Л","М","Н","О","П","Р","С","Т",
        "У","Ф","Х","Ц","Ч","Ш","Щ","Ы","Ю","Я",
        "а","б","в","г", "д","е","ж","з","и",
        "й","к","л","м","н", "о","п","р","с","т",
        "у","ф","ч","ц","ч","ш","щ","ы","ю","я"
    );	
	return str_replace($tr, $rr, $str[1]);
}

function php_syntax($str)
{
	$rtn = str_replace("<br />", "\r", $str[1]);
	$rtn = trim(htmlspecialchars_decode($rtn));
	
	$geshi = new GeSHi($rtn, "php");
	$geshi->enable_keyword_links(false);
	$geshi->set_header_type(GESHI_HEADER_DIV);
	$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
	$geshi->set_overall_style('font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', false);
	
	$geshi->set_header_content('php code:');
	$geshi->set_header_content_style('font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');
	
	$rtn = "<!-- PHP code -->";
	$rtn.= $geshi->parse_code();
	$rtn.= "<!--/PHP code -->";
	
	return $rtn."";
}
function php_decode($str)
{
	$str = strip_tags($str[1]);
	$str = preg_replace("#^php code:#", "",$str);
	
	$rtn = "[php]\n".$str."[/php]";
	
	return $rtn;
}

function javascript_syntax($str)
{
	$rtn = str_replace("<br />", "\r", $str[1]);
	$rtn = trim(htmlspecialchars_decode($rtn));
	
	$geshi = new GeSHi($rtn, "javascript");
	$geshi->enable_keyword_links(false);
	$geshi->set_header_type(GESHI_HEADER_DIV);
	$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
	$geshi->set_overall_style('font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', false);
	
	$geshi->set_header_content('JavaScript code:');
	$geshi->set_header_content_style('font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');
	
	$rtn = "<!-- JS code -->";
	$rtn.= $geshi->parse_code();
	$rtn.= "<!--/JS code -->";
	
	return $rtn;
}

function js_decode($str)
{
	$str = strip_tags($str[1]);
	$str = preg_replace("#^JavaScript code:#", "",$str);
	
	$rtn = "[javascript]\n".$str."[/javascript]";
	
	return $rtn;	
}

function html_syntax($str)
{
	$rtn = str_replace("<br />", "\r", $str[1]);
	$rtn = trim(htmlspecialchars_decode($rtn));
	
	$geshi = new GeSHi($rtn, "html4strict");
	$geshi->enable_keyword_links(false);
	$geshi->set_header_type(GESHI_HEADER_DIV);
	$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
	$geshi->set_overall_style('font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', false);
	
	$geshi->set_header_content('HTML code:');
	$geshi->set_header_content_style('font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');
	
	$rtn = "<!-- HTML code -->";
	$rtn.= $geshi->parse_code();
	$rtn.= "<!--/HTML code -->";
	
	return $rtn;
}

function html_decode($str)
{
	$str = strip_tags($str[1]);
	$str = preg_replace("#^HTML code:#", "",$str);
	
	$rtn = "[html]".$str."[/html]";
	
	return $rtn;
}

?>