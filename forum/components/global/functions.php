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

function microTimer_start()
{
    global $starttime;
    $mtime = microtime();
    $mtime = explode( ' ', $mtime );
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
}
function microTimer_stop()
{
    global $starttime;
    $mtime = microtime();
    $mtime = explode( ' ', $mtime );
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$totaltime = round( ($endtime - $starttime), 5 );
	return $totaltime;
}

function filters_input($check = 'all')
{
    require_once LB_CLASS . '/safehtml.php';
    $safehtml = new safehtml( );
    $safehtml->protocolFiltering = "black";
    
    require_once LB_CLASS . '/safeinput.php';
    $safeinput = new safeinput;
    $safeinput->safeinput_check($check);
    
    unset($safehtml);
    unset($safeinput);
}

function filters_input_one($data, $type = "black")
{
    require_once LB_CLASS . '/safehtml.php';
    
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = $type; // white или black
    $data = $safehtml->parse( $data );
    
    unset ($safehtml);
    
    return $data;
}

function wrap_word($str)
{
    global $cache_config;
    
   	$max_lenght = intval($cache_config['posts_wordlen']['conf_value']);
	$breaker = ' ';
		
	$str = preg_replace_callback("#(<|\[)(.+?)(>|\])#", create_function('$matches', 'return str_replace(" ", "--_", $matches[1].$matches[2].$matches[3]);'), $str);
			
	$words = explode(" ", $str);
	$words = preg_split("# |\n#", $str);
	
	foreach($words as $word)
    {
		$word."=";
		$split = 1;
		$array = array();
		$count = 0;
		$begin_tag = false;
		$lastKey = '';
		$flag = false;
		
		for ($i=0; $i < strlen($word); )
        {
			//unicode
			$value = ord($word[$i]);
			if($value > 127){
				if ($value >= 192 && $value <= 223)      $split = 2;
				elseif ($value >= 224 && $value <= 239)  $split = 3;
				elseif ($value >= 240 && $value <= 247)  $split = 4;
			} else $split = 1;
			$key = null;
			for ( $j = 0; $j < $split; $j++, $i++ ) $key .= $word[$i];//
			

			if($count%$max_lenght == 0 and $count != 0 and !$begin_tag)
            {
				array_push( $array, $breaker);
			}
				
			array_push( $array, $key );
			
			//echo $key."--$count--$flag<br/>";
			//если урл
			if(preg_match("#^http://#", $word)) continue;

			if($key == '[' or $key == '<' or $key == '&'){
				$begin_tag = true;
				
				if($word[$i].$word[$i+1].$word[$i+2] == 'img' or $word[$i].$word[$i+1].$word[$i+2] == 'url' ){
					$flag = true;
				}elseif($word[$i].$word[$i+1].$word[$i+2].$word[$i+3] == '/img' or $word[$i].$word[$i+1].$word[$i+2].$word[$i+3] == '/url'){
					$flag = false;
				}
				
			}
			
			if(($key == ']' or $key == '>') and !$flag) { $begin_tag = false;$count--;}
			
			if($begin_tag and $key == ';' and !$flag) { $begin_tag = false;}
			
			if(!$begin_tag and !$flag ){
				$count++;
			}						
		}
		$new_word = join("", $array);
		$str = str_replace($word, $new_word, $str);
	}
	
	$str = preg_replace_callback("#(<|\[)(.+?)(>|\])#", create_function('$matches', 'return str_replace("--_", " ", $matches[1].$matches[2].$matches[3]);'), $str);		
		
	return $str;
}

function add_br ($msg = "")
{               
    $find = array();
    $find[] = "'\r'";
    $find[] = "'\n'";

    $replace = array();
    $replace[] = "";
    $replace[] = "<br />";

    $msg = preg_replace( $find, $replace, $msg );
    
    return $msg;
}

function regular_coding ()
{       
    global $cache_config;
    
    $preg_coding = "";
    if ($cache_config['general_coding']['conf_value'] == "utf-8")
        $preg_coding = "u";
    
    return $preg_coding;
}

function clear_word_black_tags ($text = "")
{
    $find = array (
        "#data:#i".regular_coding(), "#about:#i".regular_coding(), "#vbscript:#i".regular_coding(), "#onclick#i".regular_coding(), 
        "#onload#i".regular_coding(), "#onunload#i".regular_coding(), "#onabort#i".regular_coding(), "#onerror#i".regular_coding(), 
        "#onblur#i".regular_coding(), "#onchange#i".regular_coding(), "#onfocus#i".regular_coding(), "#onreset#i".regular_coding(), 
        "#onsubmit#i".regular_coding(), "#ondblclick#i".regular_coding(), "#onkeydown#i".regular_coding(), "#onkeypress#i".regular_coding(), 
        "#onkeyup#i".regular_coding(), "#onmousedown#i".regular_coding(), "#onmouseup#i".regular_coding(), "#onmouseover#i".regular_coding(), 
        "#onmouseout#i".regular_coding(), "#onselect#i".regular_coding(), "#javascript#i".regular_coding(), "#<iframe#i".regular_coding(),
        "#<script#i".regular_coding(), "#</script#i".regular_coding()
    );
    
	$replace = array (
        "d&#097;ta:", "&#097;bout:", "vbscript<b></b>:", "&#111;nclick", 
        "&#111;nload", "&#111;nunload", "&#111;nabort", "&#111;nerror", 
        "&#111;nblur", "&#111;nchange", "&#111;nfocus", "&#111;nreset", 
        "&#111;nsubmit", "&#111;ndblclick", "&#111;nkeydown", "&#111;nkeypress", 
        "&#111;nkeyup", "&#111;nmousedown", "&#111;nmouseup", "&#111;nmouseover", 
        "&#111;nmouseout", "&#111;nselect", "j&#097;vascript", "&lt;iframe",
        "&lt;script", "&lt;/script"
    );
    
	$text = str_replace( "<?", "&lt;?", $text );
	$text = str_replace( "?>", "?&gt;", $text );
    $text = preg_replace( $find, $replace, $text );

    return $text;
}

function parse_word ($msg, $bbcode = true, $wrap_word = true, $words_wilter = true, $bb_allowed = "", $allow_html = false)
{
    global $member_id, $cache_group, $cache_config, $cache_forums_filter;
    
    $msg = trim($msg);
    
    if (!$allow_html) $msg = htmlspecialchars($msg);
    
    if ($wrap_word) $msg = wrap_word($msg);
    
    if (!$allow_html)
    {
        $find = array();
    	$find[] = "'\r'";
    	$find[] = "'\n'";
    
       	$replace = array();
    	$replace[] = "";
    	$replace[] = "<br />";
    
    	$msg = preg_replace( $find, $replace, $msg );
    }
    else
    {
        $msg = clear_word_black_tags($msg);
        $msg = str_replace( "\r\n\r\n", "\n", $msg );
    }
    
    $msg = str_replace( "{TEMPLATE}", "&#123;TEMPLATE}", $msg );
    $msg = str_replace( "{TEMPLATE_NAME}", "&#123;TEMPLATE_NAME}", $msg );
    $msg = str_replace( "{HOME_LINK}", "&#123;HOME_LINK}", $msg );
    $msg = str_replace( "{DLE_LINK}", "&#123;DLE_LINK}", $msg );
    $msg = str_replace( "{SECRET_KEY}", "&#123;SECRET_KEY}", $msg );
    $msg = str_replace( "{copyright}", "&#123;copyright}", $msg );
    
    //if ($cache_config['posts_searchtag']['conf_value']) $msg = search_tag($msg);
    if ($words_wilter) $msg = words_wilter($msg);            
    if ($bbcode) $msg = bb_decode($msg, $bb_allowed);
    
    return $msg;
}

function parse_back_word ($msg, $bbcode = true, $allow_html = false)
{
    global $member_id, $cache_group, $cache_config, $cache_forums_filter;
    
    if (!$allow_html)
    {
        $msg = str_replace( "<br>", "\n", $msg );
        $msg = str_replace( "<br />", "\n", $msg );
    }
    
    //if ($cache_config['posts_searchtag']['conf_value']) $msg = search_tag($msg, 0);
    
    if ($bbcode) $msg = bb_encode($msg);
    
    return $msg;
}

function totranslit($string, $lower = true)
{
    $translit = array('а' => 'a', 'б' => 'b', 'в' => 'v',
		'г' => 'g', 'д' => 'd', 'е' => 'e',
		'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
		'и' => 'i', 'й' => 'y', 'к' => 'k',
		'л' => 'l', 'м' => 'm', 'н' => 'n',
		'о' => 'o', 'п' => 'p', 'р' => 'r',
		'с' => 's', 'т' => 't', 'у' => 'u',
		'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
		'ь' => '', 'ы' => 'y', 'ъ' => '',
		'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
		"ї" => "yi", "є" => "ye", 'А' => 'A',
        'Б' => 'B', 'В' => 'V',	'Г' => 'G', 
        'Д' => 'D', 'Е' => 'E',	'Ё' => 'E', 
        'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 
        'Й' => 'Y', 'К' => 'K',	'Л' => 'L', 
        'М' => 'M', 'Н' => 'N',	'О' => 'O', 
        'П' => 'P', 'Р' => 'R',	'С' => 'S', 
        'Т' => 'T', 'У' => 'U',	'Ф' => 'F', 
        'Х' => 'H', 'Ц' => 'C',	'Ч' => 'Ch', 
        'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '', 
        'Ы' => 'Y', 'Ъ' => '', 'Э' => 'E', 
        'Ю' => 'Yu', 'Я' => 'Ya', "Ї" => "yi", 
        "Є" => "ye", "і" => "i", "I" => "I");

    $string = str_replace( ".php", "", $string );
    $string = trim( strip_tags( $string ) );
    $string = preg_replace( "/\s+/ms", "-", $string );
    $string = preg_replace( '#[\-]+#i', '-', $string );
    
    $string = str_replace( "'", "", $string );
	$string = str_replace( '"', "", $string );
    
    $string = strtr($string, $translit);
    if ( $lower )
        $string = utf8_strtolower( $string );
        
    return $string;
}

function utf8_strlen($word)
{
    global $cache_config;
    
    if ($cache_config['general_coding']['conf_value'] == "utf-8")
        return mb_strlen($word, "utf-8");
	else
        return strlen($word);
}

function utf8_substr($s, $offset, $len)
{
    global $cache_config;
    
    if ($cache_config['general_coding']['conf_value'] == "utf-8")
        return mb_substr($s, $offset, $len, "utf-8");
    else
        return substr($s, $offset, $len);
}

function utf8_strrpos($s, $needle)
{
     global $cache_config;
     
	if ($cache_config['general_coding']['conf_value'] == "utf-8")
        return iconv_strrpos($s, $needle, "utf-8");
	else
        return strrpos($s, $needle);
}

function utf8_strtolower($word)
{
     global $cache_config;
     
	if ($cache_config['general_coding']['conf_value'] == "utf-8")
        return mb_strtolower ($word, "utf-8");
	else
        return strtolower($word);
}

function formatsize($size)
{
	if( $size >= 1073741824 )
		$size = round( $size / 1073741824 * 100 ) / 100 . " Gb";
	elseif( $size >= 1048576 )
		$size = round( $size / 1048576 * 100 ) / 100 . " Mb";
	elseif( $size >= 1024 )
		$size = round( $size / 1024 * 100 ) / 100 . " Kb";
	else
		$size = $size . " b";

	return $size;
}

function formatdate($date)
{
	global $time, $lang_g_function;

	if( date( "Ymd", $date ) == date( "Ymd", $time ) )
		$when = $lang_g_function['formatdate_today'].date( "H:i", $date );
	elseif( date( "Ymd", $date ) == date( "Ymd", ($time - 86400) ) )
		$when = $lang_g_function['formatdate_yesterday'].date( "H:i", $date );
	else
		$when = date( "H:i, d.m.Y", $date );

	return $when;
}


function clean_url($url)
{
	if( $url == "" )
		return;
	
	$url = utf8_strtolower($url);
	$url = str_replace('http://', '',$url);
	$url = str_replace("https://", "", $url);
	$url = str_replace("www.",    "", $url);
	$url = explode('/', $url);
	$url = $url[0];
	
	return $url;
}

function update_cookie($name, $value, $date = "365")
{ 
    $host = clean_url($_SERVER['HTTP_HOST']);
    
    $parts = explode('.', $host);
    if(count($parts)>1)
    {
        $tld = array_pop($parts);
        $domain = array_pop($parts).'.'.$tld;
    }
    else
        $domain = array_pop($parts);
    
    $domain = ".".$domain;
        
	if($date)
		$date = time() + ($date * 86400);
	else
		$date = FALSE;
	
	if( phpversion() < 5.2 )		
		setcookie( $name, $value, $date, "/", $domain."; HttpOnly" );
	else
		setcookie( $name, $value, $date, "/", $domain, NULL, TRUE );
}

function message ($title, $message, $link = 0)
{
	global $tpl, $lang_g_function;

	$tpl->load_template( 'message.tpl' );

	$mes = "";

	if (is_array($message))
	{
		foreach ($message as $mes_data)
		{
			$mes .= str_replace("{text}", $mes_data, $lang_g_function['message_info']);
		}
	}
	else
		$mes .= str_replace("{text}", $message, $lang_g_function['message_info']);

	if ($link)
		$mes .= $lang_g_function['message_back'];

	$tpl->tags( '{title}', $title );
	$tpl->tags( '{message}', $mes );

	$tpl->compile( 'message' );
	$tpl->clear();
}

function LB_filters($type, $word)
{
	global $cache_banfilters;

	if ($type == "email")
	{
		foreach ($cache_banfilters as $ban)
		{
			if (!$ban['users_id'] AND strpos($ban['ip'], "@") !== false)
			{
				$ban['ip'] = preg_quote( $ban['ip'] );
				$ban['ip'] = str_replace( "\*", ".*", $ban['ip'] );
				if(preg_match( "#{$ban['ip']}#i".regular_coding(), $word ) )
					return true;
			}
		}
	}
    elseif ($type == "name")
    {
        foreach ($cache_banfilters as $ban)
		{
			if (!$ban['users_id'] AND strpos($ban['ip'], "@") === false AND count(explode(".", $row['ip'])) != 4)
			{
				$ban['ip'] = preg_quote( $ban['ip'] );
				$ban['ip'] = str_replace( "\*", ".*", $ban['ip'] );
				if(preg_match( "#{$ban['ip']}#i".regular_coding(), $word ) )
					return true;
			}
		}
    }

	return false;
}

function LB_banned($type, $word)
{
	global $cache_banfilters;

	$word =	utf8_strtolower($word);
	if ($type == "user_id")
	{
		foreach ($cache_banfilters as $ban)
		{
			if ($ban['users_id'] != 0)
			{
                if($ban['users_id'] == $word)
                    return true;
            }
		}
	}
	if ($type == "ip")
	{
		foreach ($cache_banfilters as $ban)
		{
			if ($ban['ip'] != "")
			{
				$ban['ip'] = preg_quote( $ban['ip'] );
				if (preg_match( "#\*#", $ban['ip'] ))
				{
					$ban['ip'] = str_replace( "\*", "([0-9]|[0-9][0-9]|[0-9][0-9][0-9])*", $ban['ip'] );
					if(preg_match( "#{$ban['ip']}#i".regular_coding(), $word ) )
						return true;
				}
				else
				{
					if(preg_match( "#^{$ban['ip']}$#i".regular_coding(), $word ) )
						return true;
				}
			}
		}
	}

	return false;
}

function LB_member_ip($ip_array)
{
	global $_IP;

    $ip_array = trim($ip_array);

    if ($ip_array == "")
        return true;
        
    $check = explode( "|", $ip_array );
    $result = array();
    $j = 0;
    foreach ($check as $check_ip)
    {
        $ip_mass = explode (".", $check_ip);
        $ip_user = explode (".", $_IP);
        $result[$j] = 1;   
         
        for($i=0;$i<=3;$i++)
        {
            if($ip_mass[$i] != "*")
            {
                if($ip_mass[$i] != $ip_user[$i])
                    $result[$j] = 0;
 			}
        }
        $j ++;
    }

    if (in_array(1, $result))
        return true;
    else
        return false;
}

function ForumsList($categoryid = 0, $parentid = 0, $sublevelmarker = "", $returnstring = "", $access = false) 
{
	global $cache_forums;

	if ($parentid != 0)
		$sublevelmarker .= '--&nbsp;';

	if (isset ( $cache_forums ))
	{
		$root_category = array();
		foreach ( $cache_forums as $cats )
		{
			if( $cats['parent_id'] == $parentid )
				$root_category[] = $cats['id'];
		}
		if( count( $root_category ) )
		{
			foreach ( $root_category as $id )
			{
				$category_name = $cache_forums[$id]['title'];

                if ((forum_permission($id, "read_forum") AND $access) OR !$access)
                {
                    if (is_array($categoryid))
                    {
				        if (in_array($id, $categoryid))
					       $returnstring .= "<option value=\"".$id."\" selected>".$sublevelmarker.$category_name."</option>";
				        else
					       $returnstring .= "<option value=\"".$id."\">".$sublevelmarker.$category_name."</option>";                    
                    }
                    else
                    {
				        if ($categoryid == $id)
					       $returnstring .= "<option value=\"".$id."\" selected>".$sublevelmarker.$category_name."</option>";
				        else
					       $returnstring .= "<option value=\"".$id."\">".$sublevelmarker.$category_name."</option>";
                    }
                }
                
				$returnstring = ForumsList ( $categoryid, $id, $sublevelmarker, $returnstring, $access );
			}
		}
	}
	return $returnstring;
}

function mail_sender ($member_mail, $member_name = "", $message, $title = "", $file = "", $from_email = "")
{
    global $cache_config;
    
    require_once(LB_CLASS . '/phpmailer/class.phpmailer.php');
    
    if ($message == "" OR $member_mail == "") return;
        
    if ($from_email) $cache_config['mail_email']['conf_value'] = $from_email;    
    if (!$title) $title = $cache_config['general_name']['conf_value'];
    
    $mail = new PHPMailer();
    
    if ($cache_config['general_coding']['conf_value'] == "utf-8")
        $mail->CharSet = "utf-8";
    else
        $mail->CharSet = "cp1251";
        
	$body = $message;
	$body = eregi_replace("[\]", '', $body);
    
	if ($cache_config['mail_sendmetod']['conf_value'] == "php")
	{
        //$mail->IsSendmail();
        $mail->IsMail();
	}
	else
	{
        $mail->IsSMTP();
        $mail->SMTPDebug = false;

        if ($cache_config['mail_smtpname']['conf_value'] != "" AND $cache_config['mail_smtppass']['conf_value'] != "")
            $mail->SMTPAuth = false;
        else
            $mail->SMTPAuth = true;

        $mail->Host       = $cache_config['mail_smtphost']['conf_value'];
        $mail->Port       = $cache_config['mail_smtpport']['conf_value'];
        $mail->Username   = $cache_config['mail_smtpname']['conf_value'];
        $mail->Password   = $cache_config['mail_smtppass']['conf_value'];
	}
    
    $mail->SetFrom($cache_config['mail_email']['conf_value'], $cache_config['general_name']['conf_value']);
    $mail->AddReplyTo($cache_config['mail_email']['conf_value'], $cache_config['general_name']['conf_value']);
    $mail->AddAddress($member_mail, $member_name);

    $mail->Subject = $title;
    $mail->MsgHTML($body);
    
    if (is_array($file) AND count($file))
    {
        foreach ($file as $fname)
        {
            $mail->AddAttachment($fname);
        }
    }
    elseif ($file != "")
        $mail->AddAttachment($file);
        
    $mail->Send();
    
	unset ($mail);
}

function speedbar ($speedbar = "")
{
    global $cache_config, $redirect_url, $lang_g_function;
    
    $link = "";
    $speedbar = explode ("|", $speedbar);
    if ($speedbar[0] == "")
    {
        $link = "<a href=\"".$redirect_url."\">".$cache_config['general_name']['conf_value']."</a> &raquo; ".$lang_g_function['speedbar'];
    }
    else
    {
        $link = implode (" &raquo; ", $speedbar);
    }
    return $link;
}

function online_bots($user_agent = "")
{
    global $cache_user_agent;
    
    if (!$user_agent)
        return false;
	
	$found_bot = false;
	foreach($cache_user_agent as $bot)
    {
		if(stristr($user_agent, $bot['search_ua']))
        {
			$found_bot = $bot['name'];
			break;
		}
	}
	
	return $found_bot;
}

function online_members ($limit = 0, $users = "all", $onl_do = "", $onl_op = "", $onl_id = 0)
{
    global $DB, $cache_group, $cache_config, $time, $cache, $lang_g_function, $onl_limit, $member_id;
        
    $cache_online_max = intval($cache->take("online_max", "", "statistics"));
    
    $list = "";
    $onl_g = 0;
    $onl_u = 0;
    $onl_a = 0;
    $onl_h = 0;
    
    $where = array();
    $where[] = "mo_date > '$onl_limit'";
    
    if ($onl_do != "")
    {
        $where[] = "mo_loc_do = '{$onl_do}'";
        
        if ($onl_op != "") $where[] = "mo_loc_op = '{$onl_op}'";
        if ($onl_id) $where[] = "mo_loc_id = '{$onl_id}'";  
    }

    $where = implode(" AND ", $where);
    $bots_online = array();
    
    if ($users == "all")
    {
        $DB->prefix = array( 1 => DLE_USER_PREFIX );
        $DB->join_select( "mo.*, u.banned", "LEFT", "members_online mo||users u", "mo.mo_member_id=u.user_id", $where, "ORDER by mo_date DESC" );
        while ( $row = $DB->get_row() )
        {
            $onl_a ++;
            $name_bot = online_bots($row['mo_browser']);
            if ($name_bot AND !in_array($name_bot, $bots_online) AND !$row['mo_member_name'])
            {
                $bots_online[] = $name_bot;
                
                if (!$list)
                    $list .= str_replace("{info}", $name_bot, $lang_g_function['online_members_first']);
                else
                    $list .= str_replace("{info}", $name_bot, $lang_g_function['online_members_next']);
                $onl_g ++;
            }
            elseif ($row['mo_member_name'] AND !$row['mo_hide'])
            {
                if (!$row['mo_hide'])
                {
                    $onl_u ++;
                    $class_hide = "";
                }
                else
                {
                    $onl_h ++;
                    $class_hide = "class=\"hiden\"";
                }
                if (!$limit OR $limit >= $onl_u)
                {
                    if (!$row['mo_hide'] OR ($row['mo_hide'] AND $member_id['user_group'] == 1))
                    {
                    
                        $row['mo_location'] = htmlspecialchars(strip_tags(str_replace("|", " ", $row['mo_location'])));
                                        
                        if ($row['banned'])
                            $member_name = "<font color=gray>".$row['mo_member_name']."</font>";
                        else
                            $member_name = $cache_group[$row['mo_member_group']]['g_prefix_st'].$row['mo_member_name'].$cache_group[$row['mo_member_group']]['g_prefix_end'];
                        
                        if ($row['mo_loc_fid'])
                        {
                            if (!forum_permission($row['mo_loc_fid'], "read_forum")) // не видны форум и темы
                                $row['mo_location'] = $lang_g_function['online_members_hide_loc'];
                            elseif (forum_all_password($row['mo_loc_fid']) AND ($row['mo_loc_op'] == "topic" OR $row['mo_loc_op'] == "reply")) // не видны форум и темы
                                $row['mo_location'] = $lang_g_function['online_members_hide_loc'];
                        }
                        
                        $info = "<a href=\"".profile_link($row['mo_member_name'], $row['mo_member_id'])."\" class=\"a_b_s\" title=\"".$row['mo_location']."; ".formatdate($row['mo_date'])."\" ".$class_hide.">".$member_name."</a> <a href=\"#\" onclick=\"ProfileInfo(this, '".$row['mo_member_id']."');return false;\"><img src=\"{TEMPLATE}/images/profile_window_icon.png\" alt=\"mini-profile\" /></a>";
                        
                        if (!$list)
                            $list = str_replace("{info}", $info, $lang_g_function['online_members_first']);
                        else
                            $list .= str_replace("{info}", $info, $lang_g_function['online_members_next']);
                    }
                }
            }
            elseif ($row['mo_hide'])
                $onl_h ++;
            else
                $onl_g ++;
                
            $name_bot = "";          
        }
        $DB->free();
    }
    $online = $onl_g."|".$onl_u."|".$onl_a."|".$onl_h."|".$list;
    $online = explode("|", $online);
        
    if ($cache_online_max < $onl_a)
    {
        $online_max = $onl_a."|".$time;
        $cache->update("online_max", $online_max, "statistics");
    }
    
    return $online;
}

function member_group ($id = 0, $banned = false)
{
    global $cache_group, $cache_config;
    if ($banned)
        $group = "<font color=grey>".$cache_config['general_bangroup']['conf_value']."</font>";
    else
        $group = $cache_group[$id]['g_prefix_st'].$cache_group[$id]['g_title'].$cache_group[$id]['g_prefix_end'];
    return $group;
}

function member_group_icon ($group = 0)
{
    global $redirect_url, $cache_group;
        
    if(!$group OR !$cache_group[$group]['g_icon'])
        return "";
    
    $link = "<img src=\"".$redirect_url.$cache_group[$group]['g_icon']."\" />";
        
    return $link;
}


#################### Ссылки #############################

function profile_link ($name = "", $id = 0) // ссылка на профиль
{
    global $cache_config;
    
    if($name != "")
    {
        if (!$cache_config['dle_allow_alt_url']['conf_value'])
            $link = $cache_config['general_site_dle']['conf_value']."?subaction=userinfo&user=".urlencode($name);
        else
            $link = $cache_config['general_site_dle']['conf_value']."user/".urlencode($name)."/";
    }
    else
        $link = "#";
    return $link;
}

function member_favorite ($name = "", $id = 0) // ссылка на избранное
{
    global $redirect_url, $cache_config;
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url."favorite/";
    else
        $link = $redirect_url."?do=users&op=favorite";
    
    return $link;
}

function member_topics_link ($name = "", $id = 0) // ссылка на все темы определённого пользователя
{
    global $redirect_url, $cache_config;
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url."all_topics/".urlencode($name)."/";
    else
        $link = $redirect_url."?do=users&op=topics&member_name=".urlencode($name);
        
    return $link;
}

function warning_link ($name = "", $id = 0, $type = 0, $cc = 0) // ссылка на предупреждения (история и добавление)
{
    global $redirect_url, $cache_config;
    
    if ($cc)
        $link = $cache_config['general_site']['conf_value'];
    else
        $link = $redirect_url;
    
    if ($type)
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $link."warning_add/".urlencode($name)."/";
        else
            $link = $link."?do=users&op=warning_add&member_name=".urlencode($name);
    }
    else
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $link."warning/".urlencode($name)."/";
        else
            $link = $link."?do=users&op=warning&member_name=".urlencode($name);
    }
    
    return $link;
}

function member_posts_link ($name = "", $id = 0) // ссылка на все сообщения определённого пользователя
{
    global $redirect_url, $cache_config;
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url."all_posts/".urlencode($name)."/";
    else
        $link = $redirect_url."?do=users&op=posts&member_name=".urlencode($name);
        
    return $link;
}


function profile_edit_link ($name = "", $id = 0, $act = "edit") // ссылка на редактирвоание опций профиля (настройки форума, личный статус)
{
    global $cache_config, $redirect_url;
    
    if ($act == "options")
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."options/".urlencode($name)."/";
        else
            $link = $redirect_url."?do=users&op=options&member_name=".urlencode($name);
    }
    elseif ($act == "status")
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."edit_status/".urlencode($name)."/";
        else
            $link = $redirect_url."?do=users&op=edit_status&member_name=".urlencode($name);
    }
    else
    {
        if (!$cache_config['dle_allow_alt_url']['conf_value'])
            $link = $cache_config['general_site_dle']['conf_value']."?subaction=userinfo&user=".urlencode($name);
        else
            $link = $cache_config['general_site_dle']['conf_value']."user/".urlencode($name)."/";
    }

    return $link;
}

function notice_link ($id = 0) // ссылка на объявления
{
    global $redirect_url, $cache_config;
    
    if (!$id)
        $link = "#";
    else
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."notice/".$id.".html";
        else
            $link = $redirect_url."?do=board&op=notice&id=".$id;
    }
        
    return $link;
}

function topic_link ($id = 0, $fid = 0, $last = false, $hide = false, $page = 0) // ссылка на тему (просто тема, последний ответ, скрытые посты)
{
    global $redirect_url, $cache_config;
    
    $page_add = "";
    if ($page)
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $page_add = "-page-".$page;
        else
            $page_add = "&page=".$page;
    }
    
    if ($last)
    {        
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = forum_link($fid)."last/topic-".$id.$page_add.".html";
        else
            $link = $redirect_url."?do=board&op=topic&id=".$id.$page_add."&go=last";
    }
    elseif ($hide)
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = forum_link($fid)."hiden/topic-".$id.$page_add.".html";
        else
            $link = $redirect_url."?do=board&op=topic&id=".$id.$page_add."&go=hide";
    }
    else
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = forum_link($fid)."topic-".$id.$page_add.".html";
        else
            $link = $redirect_url."?do=board&op=topic&id=".$id.$page_add;
    }
    
    return $link;
}

function topic_link_utility ($id = 0, $fid = 0, $page = 0, $nav = false) // ссылка на тему (полезные сообщения)
{
    global $redirect_url, $cache_config;
    
    $page_add = "";
    if ($nav)
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $page_add = "-page-{i_page}";
        else
            $page_add = "&page={i_page}";
    }
    elseif ($page)
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $page_add = "-page-".$page;
        else
            $page_add = "&page=".$page;
    }
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = forum_link($fid)."utility/topic-".$id.$page_add.".html";
    else
        $link = $redirect_url."?do=board&op=topic&id=".$id.$page_add."&utility=1";
        
    return $link;
}

function topic_link_last ($id = 0, $fid = 0, $number_pages = 0, $pid = 0) // формирование ссылки на страницу с последним ответом
{
    global $redirect_url, $cache_config;

    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = forum_link($fid)."topic-".$id."-page-".$number_pages.".html#post".$pid;
    else
        $link = $redirect_url."?do=board&op=topic&id=".$id."&page=".$number_pages."#post".$pid;
    
    return $link;
}

function topic_new_link ($id = 0) // ссылка на созлание новой темы
{
    global $redirect_url, $cache_config;
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = forum_link($id)."newtopic/";
    else
        $link = $redirect_url."?do=board&op=newtopic&id=".$id;
        
    return $link;
}

function forum_link ($id = 0) // ссылка на форум
{
    global $redirect_url, $cache_config, $cache_forums;
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url."cat-".$cache_forums[$id]['alt_name']."/";
    else
        $link = $redirect_url."?do=board&op=forum&id=".$id;
        
    return $link;
}

function reply_link ($id = 0, $pid = 0, $fid = 0) // ссылка на ответ в теме (ответ, ответ на определённый пост)
{
    global $redirect_url, $cache_config;
    
    if (!$pid)
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = forum_link($fid)."reply/topic-".$id.".html";
        else
            $link = $redirect_url."?do=board&op=reply&id=".$id;
    }
    else
    {
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = forum_link($fid)."reply".$pid."/topic-".$id.".html";
        else
            $link = $redirect_url."?do=board&op=reply&id=".$id."&pid=".$pid;
    }
    
    return $link;
}

function post_edit_link ($id = 0, $act = "edit", $page = 0) // ссылка на редактирование сообщения
{
    global $redirect_url, $secret_key;
    
    if (intval($page))
        $page = "&page=".intval($page);
    else
        $page = "";
    
    $link = $redirect_url."?do=board&op=post_edit&act=".$act."&secret_key=".$secret_key."&id=".$id.$page;
    return $link;
}

function pm_topics_link ($id = 0) // ссылка на оперделённое ЛС в CMS DLE
{
    global $cache_config;
    
    $link = $cache_config['general_site_dle']['conf_value']."?do=pm&doaction=readpm&pmid=".$id;
    return $link;
}

function pm_member ($name = "", $id = 0) // ссылка на ЛС в CMS DLE
{
    global $cache_config;
    
    if (!$name)
        $link = $cache_config['general_site_dle']['conf_value']."?do=pm";
    else
        $link = $cache_config['general_site_dle']['conf_value']."?do=pm&doaction=newpm&user=".$id;
    return $link;
}

function member_subscribe () // ссылка на подписку пользователя
{
    global $redirect_url, $cache_config;
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url."subscribe/";
    else
        $link = $redirect_url."?do=users&op=subscribe";
    
    return $link;
}

function topic_favorite ($id = 0) // ссылка на добавление в избранное тему
{
    global $redirect_url, $secret_key;
    
    $id = intval($id);
    
    if (!$id) $link = "#";
    else $link = $redirect_url."?do=board&op=favorite&id=".$id."&secret_key=".$secret_key;
    
    return $link;
}

function topic_subscribe ($id = 0) // ссылка на подписку на тему
{
    global $redirect_url, $secret_key;
    
    $id = intval($id);
    
    if (!$id) $link = "#";
    else $link = $redirect_url."?do=board&op=subscribe&id=".$id."&secret_key=".$secret_key;
    
    return $link;
}

function link_on_module ($do = "", $op = "") // разнообразные ссылки, сформированные путём do= и op=
{
    global $redirect_url, $cache_config;
    
    if (!$do) return "#";
        
    $op_l = "";
    if ($op) $op_l = "&op=".$op;        
    
    if ($cache_config['general_rewrite_url']['conf_value'])
    {
        if ($do == "board" AND $op == "topic_active")
            $link = $redirect_url."topic_active/";
        elseif ($do == "board" AND $op == "last_posts")
            $link = $redirect_url."last_posts/";
        elseif ($do == "board" AND $op == "last_topics")
            $link = $redirect_url."last_topics/";
        elseif ($do == "users" AND $op == "moderators")
            $link = $redirect_url."moderators/";
        elseif ($do == "users" AND !$op)
            $link = $redirect_url."users/";
        else
            $link = $redirect_url."?do=".$do.$op_l;
    }
    else
        $link = $redirect_url."?do=".$do.$op_l;
        
    return $link;
}

function link_on_module_dle ($link = "") // разнообразные ссылки на модули CMS DLE
{
    global $cache_config;
    
    if (!$link) return "#";   
    
    $link = $cache_config['general_site_dle']['conf_value']."?do=".$link;
    return $link;
}

function navigation_link ($do = "board", $op = "", $id = 0, $other = "") // составление навигации по модулям
{
    global $redirect_url;
    
    if ($op != "") $op = "&op=".$op;
        
    $id = intval($id);
    
    if ($id > 0) $id = "&id=".$id;
    else $id = "";
        
    if ($other != "") $other = "&".$other;
    
    $link = $redirect_url."?do=".$do.$op.$id.$other."&page={i_page}";
    
    return $link;
}

function navigation_topic_link ($id = 0, $fid = 0, $other = "") // составление навигации по теме
{
    global $redirect_url, $cache_config;
            
    $id = intval($id);
    $add_other_1 = "";
    $add_other_2 = "";
    
    $chpu = $cache_config['general_rewrite_url']['conf_value'];
    if (is_array($other))
    {
        foreach ($other as $key => $value)
        {
            if ($key == "s") $chpu = false;
            
            $add_other_1 .= "&".$key."=".$value;
            if ($key == "hide") $add_other_2 = "hiden/";
        }
    }
        
    if ($chpu)
        $link = forum_link($fid).$add_other_2."topic-".$id."-page-{i_page}.html";
    else
        $link = $redirect_url."?do=board&op=topic&id=".$id.$add_other."&page={i_page}";
    
    return $link;
}

function navigation_topic_out_link ($mod = "", $member = "", $dop = "") // составление навигации по темам (последние темы, активные темы, темы юзера)
{
    global $redirect_url, $cache_config;
            
    $add_other_1 = "";
    $add_other_2 = "";
        
    if ($mod == "topics")
    {    
        if ($dop == "topics")
        {
            $add_other_1 = "hiden_t/";
            $add_other_2 = "&hide=topics";
        }
        elseif ($dop == "posts")
        {
            $add_other_1 = "hiden_p/";
            $add_other_2 = "&hide=posts";
        }
        
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."all_topics/".$add_other_1.$member."/page-{i_page}";
        else
            $link = $redirect_url."?do=users&op=topics&member_name=".$member.$add_other_2."&page={i_page}";
    }
    elseif ($mod == "last_topics")
    {    
        if ($dop == "topics")
        {
            $add_other_1 = "hiden_t/";
            $add_other_2 = "&hide=topics";
        }
        elseif ($dop == "posts")
        {
            $add_other_1 = "hiden_p/";
            $add_other_2 = "&hide=posts";
        }
        
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."last_topics/".$add_other_1."page-{i_page}";
        else
            $link = $redirect_url."?do=board&op=last_topics".$add_other_2."&page={i_page}";
    }
    elseif ($mod == "topic_active")
    {    
        if ($dop == "topics")
        {
            $add_other_1 = "hiden_t/";
            $add_other_2 = "&hide=topics";
        }
        elseif ($dop == "posts")
        {
            $add_other_1 = "hiden_p/";
            $add_other_2 = "&hide=posts";
        }
        
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."topic_active/".$add_other_1."page-{i_page}";
        else
            $link = $redirect_url."?do=board&op=topic_active".$add_other_2."&page={i_page}";
    }
    
    return $link;
}

function forum_options ($id = 0, $link = "")
{
    global $redirect_url, $member_id, $cache_group, $lang_g_function, $cache_config;
    
    $options = "";
    
    if ($cache_group[$member_id['user_group']]['g_show_hiden'] OR $cache_group[$member_id['user_group']]['g_supermoders'] OR forum_options_topics($id, "hideshow"))
    {
        if (!is_array($link) AND $link == "forum")
        {
            if ($cache_config['general_rewrite_url']['conf_value'])
            {
                $options .= str_replace("{link}", forum_link($id)."hiden_t/", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", forum_link($id)."hiden_p/", $lang_g_function['forum_options_hide_posts']);
            }
            else
            {
                $options .= str_replace("{link}", $redirect_url."?do=board&op=forum&id=".$id."&hide=topics", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."?do=board&op=forum&id=".$id."&hide=posts", $lang_g_function['forum_options_hide_posts']);
            }
        }
        elseif (!is_array($link) AND $link == "last_topics")
        {
            if ($cache_config['general_rewrite_url']['conf_value'])
            {
                $options .= str_replace("{link}", $redirect_url."last_topics/hiden_t/", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."last_topics/hiden_p/", $lang_g_function['forum_options_hide_posts']);
            }
            else
            {
                $options .= str_replace("{link}", $redirect_url."?do=board&op=last_topics&id=".$id."&hide=topics", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."?do=board&op=last_topics&id=".$id."&hide=posts", $lang_g_function['forum_options_hide_posts']);
            }
        }
        elseif (!is_array($link) AND $link == "topic_active")
        {
            if ($cache_config['general_rewrite_url']['conf_value'])
            {
                $options .= str_replace("{link}", $redirect_url."topic_active/hiden_t/", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."topic_active/hiden_p/", $lang_g_function['forum_options_hide_posts']);
            }
            else
            {
                $options .= str_replace("{link}", $redirect_url."?do=board&op=topic_active&id=".$id."&hide=topics", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."?do=board&op=topic_active&id=".$id."&hide=posts", $lang_g_function['forum_options_hide_posts']);
            }
        }
        elseif(is_array($link) AND $link['module'] == "user_topics")
        {
            if ($cache_config['general_rewrite_url']['conf_value'])
            {
                $options .= str_replace("{link}", $redirect_url."all_topics/hiden_t/".$link['dop']."/", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."all_topics/hiden_p/".$link['dop']."/", $lang_g_function['forum_options_hide_posts']);
            }
            else
            {
                $options .= str_replace("{link}", $redirect_url."?do=users&op=topics&member_name=".$link['dop']."&hide=topics", $lang_g_function['forum_options_hide_topics']);
                $options .= str_replace("{link}", $redirect_url."?do=users&op=topics&member_name=".$link['dop']."&hide=posts", $lang_g_function['forum_options_hide_posts']);
            }
        }
    }
    
    return $options;
}

function navigation_post_out_link ($mod = "", $member = "") // составление навигации сообщениям (последние ответы, все посты юзера)
{
    global $redirect_url, $cache_config;
            
    if ($mod == "posts")
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."all_posts/".$member."/page-{i_page}";
        else
            $link = $redirect_url."?do=users&op=posts&member_name=".$member."&page={i_page}";
    }
    elseif ($mod == "last_posts")
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."last_posts/page-{i_page}";
        else
            $link = $redirect_url."?do=board&op=last_posts&page={i_page}";
    }
    
    return $link;
}

function online_link_list ($mod = "", $page = false) // ссылка на вывод списка онлайн (по действию и по имени)
{
    global $redirect_url, $cache_config;
        
    $add_link_1 = "";
    $add_link_2 = ""; 
       
    if ($page)
    {
        $add_link_1 = "page-{i_page}";
        $add_link_2 = "&page={i_page}";
    }
            
    if ($mod == "action")
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."online/action/".$add_link_1;
        else
            $link = $redirect_url."?do=users&op=online&order=action".$add_link_2;
    }
    elseif ($mod == "name")
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."online/name/".$add_link_1;
        else
            $link = $redirect_url."?do=users&op=online&order=name".$add_link_2;
    }
    else
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."online/".$add_link_1;
        else
            $link = $redirect_url."?do=users&op=online".$add_link_2;
    }
    
    return $link;
}

function navigation_link_favsubs ($mod = "") // составление навигации по избранному и подписке
{
    global $redirect_url, $cache_config;
            
    if ($mod == "favorite")
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."favorite/page-{i_page}";
        else
            $link = $redirect_url."?do=users&op=favorite&page={i_page}";
    }
    elseif ($mod == "subscribe")
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."subscribe/page-{i_page}";
        else
            $link = $redirect_url."?do=users&op=subscribe&page={i_page}";
    }
    
    return $link;
}

function navigation_link_forums ($id = "", $dop = "") // составление навигации по форуму
{
    global $redirect_url, $cache_config;
        
    $add_other_1 = "";
    $add_other_2 = "";
        
    if ($dop == "topics")
    {
        $add_other_1 = "hiden_t/";
        $add_other_2 = "&hide=topics";
    }
    elseif ($dop == "posts")
    {
        $add_other_1 = "hiden_p/";
        $add_other_2 = "&hide=posts";
    } 
                      
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = forum_link($id).$add_other_1."page-{i_page}";
    else
        $link = $redirect_url."?do=board&op=forum&id=".$id.$add_other_2."&page={i_page}";
    
    return $link;
}

function rss_link ($rss_link = "") // ссылка на RSS
{
    global $redirect_url, $cache_config;
    
    $add_other_1 = "";
    $add_other_2 = "";
    
    if ($rss_link AND strpos($rss_link, "t") !== false)
    {
        list($name, $id) = explode ("=", $rss_link);
        $add_other_1 = "topic".$id."/";
        $add_other_2 = "topicid=".$id;
    }
    elseif ($rss_link AND strpos($rss_link, "f") !== false)
    {
        list($name, $id) = explode ("=", $rss_link);
        $add_other_1 = "cat".$id."/";
        $add_other_2 = "forumid=".$id;
    }
    
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url.$add_other_1."rss.xml";
    else
        $link = $redirect_url."components/modules/rss.php".$add_other_2;
        
    return $link;
}

function users_link_list () // ссылка на вывод списка всех пользователей
{
    global $redirect_url, $cache_config;
        
    $add_link_1 = "page-{i_page}";
    $add_link_2 = "&page={i_page}";
              
    if ($cache_config['general_rewrite_url']['conf_value'])
        $link = $redirect_url."users/".$add_link_1;
    else
        $link = $redirect_url."?do=users".$add_link_2;

    return $link;
}

function all_status_link ($page = false) // составление навигации по личным статусам
{
    global $redirect_url, $cache_config;
            
    if (!$page)
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."all_status/";
        else
            $link = $redirect_url."?do=users&op=all_status";
    }
    else
    {            
        if ($cache_config['general_rewrite_url']['conf_value'])
            $link = $redirect_url."all_status/page-{i_page}";
        else
            $link = $redirect_url."?do=users&op=all_status&page={i_page}";
    }
    
    return $link;
}

#################### Ссылки #############################

function member_avatar ($img = "")
{
    global $cache_config, $redirect_url;
    
    if($img == "")
        $link = $redirect_url."uploads/users/no_avatar.png";
    else
        $link = $cache_config['general_site_dle']['conf_value']."uploads/fotos/".$img;
        
    return $link;
}

function member_online ($id = 0, $date = 0, $limit = 0)
{    
    if ($id AND $date >= $limit)
        return true;
    else
        return false;
}

function main_forum ($id = 0, $list = "")
{
	global $cache_forums;
	
    if($id)
    {
	   if ($list == "")
		  $list = $id;
	   else
		  $list .= "|".$id;

	   if ($cache_forums[$id]['parent_id'] != 0 )
		  $list = main_forum($cache_forums[$id]['parent_id'], $list);
    }
    else
        return;

	return $list;
}

function speedbar_forum ($id = 0, $main_link = false, $link = true)
{
    global $redirect_url, $cache_config, $cache_forums;
    
    $speedbar = main_forum($id);
    if($speedbar)
    {
	    $speedbar = explode ("|", $speedbar);
	    krsort($speedbar);
        reset($speedbar);
        if( count( $speedbar ) )
        {
            if ($link)
                $link_speddbar = "<a href=\"".$redirect_url."\">".$cache_config['general_name']['conf_value']."</a>";
            else
                $link_speddbar = $cache_config['general_name']['conf_value'];
                
            foreach ($speedbar as $link_forum)
            {
                if ($id == $link_forum)
                {
                    if ($link)
                        $link_speddbar .= "|<a href=\"".forum_link($link_forum)."\">".$cache_forums[$link_forum]['title']."</a>";
                    else
                        $link_speddbar .= "|".$cache_forums[$link_forum]['title'];
                }
                else
                {
                    if ($link)
                        $link_speddbar .= "|<a href=\"".forum_link($link_forum)."\">".$cache_forums[$link_forum]['title']."</a>";
                    else
                        $link_speddbar .= "|".$cache_forums[$link_forum]['title'];
                }
            }
	   }
       else
            $link_speddbar = $cache_config['general_name']['conf_value'];
    }
    else
        $link_speddbar = $cache_config['general_name']['conf_value'];
        
    if (!$id AND $main_link)
        $link_speddbar = "<a href=\"".$redirect_url."\">".$cache_config['general_name']['conf_value']."</a>";
        
    return $link_speddbar;
}

function forum_permission ($id = 0, $perm = "") // права на форумы, настройки форума (не модерация)
{
    global $cache_forums, $member_id;

    if (!$id OR $perm == "")
        return false;
  
    if ($perm == "read_forum")
    {
        $id_mass = main_forum($id);
        $id_mass = explode ("|", $id_mass);
        
        $category = array_pop($id_mass); // проверки доступа у категории
        if ($cache_forums[$category]['group_permission'] != 0)
        {
             $category = explode (",", $cache_forums[$category]['group_permission']);
             if (!in_array($member_id['user_group'], $category))
                return false;
        }
        
	    sort($id_mass); // сортировка массива, переворачиваем, начиная от выбранного форума и заканчивая главным форумом (не категории)
        reset($id_mass);
        if( count( $id_mass ) )
        {
            foreach ($id_mass as $idd)
            {
                $forum_permission = unserialize($cache_forums[$idd]['group_permission']);
                if($forum_permission[$member_id['user_group']][$perm] != 1)
                    return false;
		    }
        }
    }
    
    if ($cache_forums[$id]['parent_id'] != 0)
    {
        $forum_permission = unserialize($cache_forums[$id]['group_permission']);
        if($forum_permission[$member_id['user_group']][$perm] == 1)
            return true;
        else
            return false;
    }
    else
        return true;
}

function forum_password ($id = 0)
{
    global $cache_forums, $member_id, $_IP;
    
    if($member_id['user_group'] != 5)
        $who = $member_id['name'];
    else
        $who = $_IP;
        
    if($_COOKIE['LB_password_forum_'.$id] == md5($who.$cache_forums[$id]['password']))
        return false;
    
    if ($cache_forums[$id]['password_notuse'] != "")
    {
        $notuse = explode(",", $cache_forums[$id]['password_notuse']);
        if (in_array($member_id['user_group'], $notuse))
            return false;
    }
    
    if ($cache_forums[$id]['password'] != "")
        return true;
    else
        return false;
}

function forum_all_password ($id = 0)
{   
    global $cache_forums;
    
    if ($cache_forums[$id]['flink'] != "") return false;
    
    $id_forum_pass = 0;
    $id_f_pass = main_forum ($id);
    $id_f_pass = explode ("|", $id_f_pass);
    array_pop($id_f_pass); // вырезаем категорию из массива
    sort($id_f_pass); // сортировка массива, переворачиваем, начиная от выбранного форума и заканчивая главным форумом (не категории)
    reset($id_f_pass);
    if( count( $id_f_pass ) )
    {
        foreach ($id_f_pass as $idd_f)
        {          
            if(forum_password($idd_f))
            {
                return $idd_f;
            }    
        }
    }
    
    return false;
}

function forum_options_topics_check ($id = 0)
{
      global $member_id, $cache_forums_moder;
            
      foreach ($cache_forums_moder as $moder)
      {
            if ($moder['fm_forum_id'] == $id)
            {
                if ($moder['fm_forum_id'] == $id AND ($moder['fm_member_id'] == $member_id['user_id'] OR ($moder['fm_is_group'] == 1 AND $moder['fm_group_id'] == $member_id['user_group'])))
                    return $moder['fm_permission'];
            }
      } 
      
      return false;  
}

function forum_options_topics ($id = 0, $check_func = "") // права на форумы, модераторы, проверка просмотра тем, ответов и т.п.
{
    global $member_id, $cache_group, $cache_forums_moder, $lang_g_function;
    
    $options = "";
    $access = "";
    
    if ($check_func == "hideshow" AND $cache_group[$member_id['user_group']]['g_show_hiden'])
        return true;
                
    if ($check_func == "reply_close" AND $cache_group[$member_id['user_group']]['g_reply_close'])
        return true;
    
    if ($cache_group[$member_id['user_group']]['g_supermoders'] OR $member_id['user_group'] == 1)
    {
        if ($check_func != "")
            return true;
            
        $options .= "<option value=\"2\">".$lang_g_function['forum_options_topics_close']."</option>";
        $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_open']."</option>";
        $options .= "<option value=\"5\">".$lang_g_function['forum_options_topics_up']."</option>";
        $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_down']."</option>";
        $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_hide']."</option>";      
        $options .= "<option value=\"4\">".$lang_g_function['forum_options_topics_publ']."</option>";
        $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_move']."</option>";
        $options .= "<option value=\"8\">".$lang_g_function['forum_options_topics_union']."</option>";
        $options .= "<option value=\"10\">".$lang_g_function['forum_options_topics_subscribe']."</option>";
        $options .= "<option value=\"9\">".$lang_g_function['forum_options_topics_del']."</option>";
    }
    else
    {        
        $find = false;
        
        if (count($cache_forums_moder) == 0)
            return false;
        
        foreach ($cache_forums_moder as $moder)
        {
            if ($moder['fm_forum_id'] == $id)
            {
                if ($moder['fm_forum_id'] == $id AND ($moder['fm_member_id'] == $member_id['user_id'] OR ($moder['fm_is_group'] == 1 AND $moder['fm_group_id'] == $member_id['user_group'])))
                {
                    $access = $moder['fm_permission'];
                    $find = true;
                }
            }
        } 
        
        if (!$find)
        {
            $id_f_moder = main_forum ($id);
            $id_f_moder = explode ("|", $id_f_moder);
            if( count( $id_f_moder ) )
            {
                foreach ($id_f_moder as $idd_f)
                {    
                    $access = forum_options_topics_check ($idd_f);
                    if ($access)
                    {
                        break;
                    }  
                }
            }  
        }
        
        if($access)
        {
            $access = unserialize($access);
            
            if (!$check_func)
            {
                if ($access['global_closetopic']) $options .= "<option value=\"2\">".$lang_g_function['forum_options_topics_close']."</option>";
                if ($access['global_opentopic']) $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_open']."</option>";
                if ($access['global_fixtopic']) $options .= "<option value=\"5\">".$lang_g_function['forum_options_topics_up']."</option>";
                if ($access['global_unfixtopic']) $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_down']."</option>";
                
                if ($access['global_hidetopic'])
                {
                    $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_hide']."</option>";
                    $options .= "<option value=\"4\">".$lang_g_function['forum_options_topics_publ']."</option>";
                }
                
                if ($access['global_movetopic']) $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_move']."</option>";
                if ($access['global_uniontopic']) $options .= "<option value=\"8\">".$lang_g_function['forum_options_topics_union']."</option>";
                if ($access['global_deltopic']) $options .= "<option value=\"9\">".$lang_g_function['forum_options_topics_del']."</option>";
            }
            else
            {
                if ($access['global_'.$check_func])
                    return true;
                else
                    return false;
            }          
        }     
    }
    
    if ($check_func != "")
        return false;
    
    return $options;
}

function forum_moderation() // является ли пользователь модератором чего-либо
{
    global $member_id, $cache_group, $cache_forums_moder;
            
    if ($cache_group[$member_id['user_group']]['g_supermoders'] == 1 OR $member_id['user_group'] == 1)
        return true;
        
    foreach ($cache_forums_moder as $moder)
    {
        if ($moder['fm_member_id'] == $member_id['user_id'] OR ($moder['fm_is_group'] == 1 AND $moder['fm_group_id'] == $member_id['user_group']))
        {
            return true;
        }
    } 
        
    return false;
}

function group_permission($check = "")
{
    global $member_id, $cache_group;
    
    if (!$check)
        return false;
    
    $access = unserialize($cache_group[$member_id['user_group']]['g_access']);
    
    if ($access[$check])
        return true;
    else
        return false;
        
    return false;
}

function forum_options_topics_mas ($fid = 0, $id = 0, $type = "") // права на посты, массовые действия
{
    global $member_id, $cache_group, $cache_forums_moder, $lang_g_function;
    
    $options = "";
    $access = "";
    
    if ($cache_group[$member_id['user_group']]['g_supermoders'] OR $member_id['user_group'] == 1)
    {    
        if ($type != "posts" AND $type != "topic")
            return true;
        
        if($type == "posts")
        {
            $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_mas_p_hide']."</option>";      
            $options .= "<option value=\"2\">".$lang_g_function['forum_options_topics_mas_p_publ']."</option>";
            $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_mas_p_edit']."</option>";
            $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_mas_p_fix']."</option>";
            $options .= "<option value=\"8\">".$lang_g_function['forum_options_topics_mas_p_unfix']."</option>";
            $options .= "<option value=\"4\">".$lang_g_function['forum_options_topics_mas_p_union']."</option>";
            $options .= "<option value=\"5\">".$lang_g_function['forum_options_topics_mas_p_move']."</option>";
            $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_mas_p_del']."</option>";
        }
        elseif($type == "topic")
        {
            $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_mas_t_close']."</option>";
            $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_mas_t_open']."</option>";
            $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_mas_t_hide']."</option>";      
            $options .= "<option value=\"2\">".$lang_g_function['forum_options_topics_mas_t_pub']."</option>";
            $options .= "<option value=\"4\">".$lang_g_function['forum_options_topics_mas_t_up']."</option>";      
            $options .= "<option value=\"5\">".$lang_g_function['forum_options_topics_mas_t_down']."</option>";
            $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_mas_t_edit']."</option>";
            $options .= "<option value=\"8\">".$lang_g_function['forum_options_topics_mas_t_move']."</option>";
            $options .= "<option value=\"10\">".$lang_g_function['forum_options_topics_mas_t_unsubsc']."</option>";
            $options .= "<option value=\"9\">".$lang_g_function['forum_options_topics_mas_t_del']."</option>";
        }
    }
    else
    {
        $find = false;
        
        if (count($cache_forums_moder) == 0)
            return false;
        
        foreach ($cache_forums_moder as $moder)
        {
            if ($moder['fm_forum_id'] == $fid)
            {
                if ($moder['fm_forum_id'] == $fid AND ($moder['fm_member_id'] == $member_id['user_id'] OR ($moder['fm_is_group'] == 1 AND $moder['fm_group_id'] == $member_id['user_group'])))
                {
                    $access = $moder['fm_permission'];
                    $find = true;
                }
            }
        } 
        
        if (!$find)
        {
            $id_f_moder = main_forum ($fid);
            $id_f_moder = explode ("|", $id_f_moder);
            if( count( $id_f_moder ) )
            {
                foreach ($id_f_moder as $idd_f)
                {    
                    $access = forum_options_topics_check ($idd_f);
                    if ($access)
                    {
                        break;
                    }  
                }
            }   
        }
        
        if($access)
        {
            $access = unserialize($access);
            
            if ($type == "posts" OR $type == "check")
            {
                if ($access['global_hidetopic'] AND $type != "check")
                {
                    $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_mas_p_hide']."</option>";      
                    $options .= "<option value=\"2\"".$lang_g_function['forum_options_topics_mas_p_publ']."</option>";
                }
                elseif ($access['global_hidetopic'] AND $type == "check") return true;
            
                if ($access['global_changepost'] AND $type != "check") $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_mas_p_edit']."</option>";
                elseif ($access['global_changepost'] AND $type == "check") return true;
                
                if ($access['global_fixedpost'] AND $type != "check") $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_mas_p_fix']."</option>";
                elseif ($access['global_fixedpost'] AND $type == "check") return true;
                
                if ($access['global_fixedpost'] AND $type != "check") $options .= "<option value=\"8\">".$lang_g_function['forum_options_topics_mas_p_unfix']."</option>";
                elseif ($access['global_fixedpost'] AND $type == "check") return true;
                
                if ($access['global_unionpost'] AND $type != "check") $options .= "<option value=\"4\">".$lang_g_function['forum_options_topics_mas_p_union']."</option>";
                elseif ($access['global_unionpost'] AND $type == "check") return true;
            
                if ($access['global_movepost'] AND $type != "check") $options .= "<option value=\"5\">".$lang_g_function['forum_options_topics_mas_p_move']."</option>";
                elseif ($access['global_movepost'] AND $type == "check") return true;
             
                if ($access['global_delpost'] AND $type != "check") $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_mas_p_del']."</option>";
                elseif ($access['global_delpost'] AND $type == "check") return true;
            }
            elseif($type == "topic" OR $type == "check")
            {
                if ($access['global_closetopic'] AND $type != "check") $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_mas_t_close']."</option>";
                elseif ($access['global_closetopic'] AND $type == "check") return true;
                
                if ($access['global_opentopic'] AND $type != "check") $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_mas_t_open']."</option>";
                elseif ($access['global_opentopic'] AND $type == "check") return true;
                                        
                if ($access['global_hidetopic'] AND $type != "check")
                {
                    $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_mas_t_hide']."</option>";      
                    $options .= "<option value=\"2\">".$lang_g_function['forum_options_topics_mas_t_pub']."</option>";
                }
                elseif ($access['global_hidetopic'] AND $type == "check") return true;
                                
                if ($access['global_fixtopic'] AND $type != "check") $options .= "<option value=\"4\">".$lang_g_function['forum_options_topics_mas_t_up']."</option>";
                elseif ($access['global_fixtopic'] AND $type == "check") return true;
            
                if ($access['global_unfixtopic'] AND $type != "check") $options .= "<option value=\"5\">".$lang_g_function['forum_options_topics_mas_t_down']."</option>";
                elseif ($access['global_unfixtopic'] AND $type == "check") return true;
                
                if (($access['global_titletopic'] OR $access['global_polltopic']) AND $type != "check") $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_mas_t_edit']."</option>";
                elseif (($access['global_titletopic'] OR $access['global_polltopic']) AND $type == "check") return true;
                       
                if ($access['global_movetopic'] AND $type != "check") $options .= "<option value=\"8\">".$lang_g_function['forum_options_topics_mas_t_move']."</option>";
                elseif ($access['global_movetopic'] AND $type == "check") return true;
                        
                if ($access['global_deltopic'] AND $type != "check") $options .= "<option value=\"9\">".$lang_g_function['forum_options_topics_mas_t_del']."</option>";
                elseif ($access['global_deltopic'] AND $type == "check") return true;
            }
            else
            { 
                if ($access['global_titletopic'] AND $type == "titletopic") return true;
                elseif (!$access['global_titletopic'] AND $type == "titletopic") return false;
                
                if ($access['global_polltopic'] AND $type == "polltopic") return true;
                elseif (!$access['global_polltopic'] AND $type == "polltopic") return false;
                
                if ($access['global_uniontopic'] AND $type == "uniontopic") return true;
                elseif (!$access['global_uniontopic'] AND $type == "uniontopic") return false;
                
                if ($access['global_opentopic'] AND $type == "opentopic") return true;
                elseif (!$access['global_opentopic'] AND $type == "opentopic") return false;
                        
                if ($access['global_closetopic'] AND $type == "closetopic") return true;
                elseif (!$access['global_closetopic'] AND $type == "closetopic") return false;
                        
                if ($access['global_hidetopic'] AND $type == "hidetopic") return true;
                elseif (!$access['global_hidetopic'] AND $type == "hidetopic") return false;
                       
                if ($access['global_fixtopic'] AND $check_func == "fixtopic") return true;
                elseif (!$access['global_fixtopic'] AND $check_func == "fixtopic") return false;
            
                if ($access['global_unfixtopic'] AND $type == "unfixtopic") return true;
                elseif (!$access['global_unfixtopic'] AND $type == "unfixtopic") return false;
            
                if ($access['global_movetopic'] AND $type == "movetopic") return true;
                elseif (!$access['global_movetopic'] AND $type == "movetopic") return false;
            
                if ($access['global_deltopic'] AND $type == "deltopic") return true;
                elseif (!$access['global_deltopic'] AND $type == "deltopic") return false;
            
                if ($access['global_hideshow'] AND $type == "hideshow") return true;
                elseif (!$access['global_hideshow'] AND $type == "hideshow") return false;
            
                if ($access['global_delpost'] AND $type == "delpost") return true;
                elseif (!$access['global_delpost'] AND $type == "delpost") return false;
            
                if ($access['global_changepost'] AND $type == "changepost") return true;
                elseif (!$access['global_changepost'] AND $type == "changepost") return false;
                
                if ($access['global_unionpost'] AND $type == "unionpost") return true;
                elseif (!$access['global_unionpost'] AND $type == "unionpost") return false;
            
                if ($access['global_movepost'] AND $type == "movepost") return true;
                elseif (!$access['global_movepost'] AND $type == "movepost") return false;  
                
                if ($access['global_fixedpost'] AND $type == "fixedpost") return true;
                elseif (!$access['global_fixedpost'] AND $type == "fixedpost") return false;  
                
                if ($access['global_fixedpost'] AND $type != "fixedpost") return true;
                elseif (!$access['global_fixedpost'] AND $type == "fixedpost") return false; 
                
                if ($access['global_movepost_date'] AND $type != "movepost_date") return true;
                elseif (!$access['global_movepost_date'] AND $type == "movepost_date") return false;
            }     
        }
    }
    
    if ($options == "")
        return false;
   
    return $options;
}

function member_publ_access ($type = 1)
{
    global $member_id, $logged;
    
    if ($type == 0)
        return false;
            
    if ($logged)
    {
        if ($member_id['user_group'] == 1)
            return true;
        
        if ($member_id['lb_limit_publ'] == $type OR $member_id['lb_limit_publ'] == 3)
            return false;
        else
            return true;
    }
    else
        return true;  
      
    return false;  
}

function member_publ_info ()
{
    global $member_id, $lang_g_function;
    
    $limit_publ_end = $member_id['lb_limit_date'];
        
    if ($limit_publ_end)
        $message = str_replace("{date}", formatdate($limit_publ_end), $lang_g_function['member_publ_info1']);
    else
        $message = $lang_g_function['member_publ_info2']; 
      
    return $message;  
}

function forum_options_topics_author ($type = "", $opt = "all")
{
    global $member_id, $cache_group, $lang_g_function;
    
    $options = "";
    $access = unserialize($cache_group[$member_id['user_group']]['g_access']);
                 
    if (($access['local_titletopic'] OR $access['local_polltopic']) AND $type != "check") $options .= "<option value=\"3\">".$lang_g_function['forum_options_topics_author_edit']."</option>";
    elseif (($access['local_titletopic'] OR $access['local_polltopic']) AND $type == "check" AND ($opt == "edit" OR $opt == "all")) return true;
                
    if ($access['local_opentopic'] AND $type != "check") $options .= "<option value=\"6\">".$lang_g_function['forum_options_topics_author_open']."</option>";
    elseif ($access['local_opentopic'] AND $type == "check" AND ($opt == "open" OR $opt == "all")) return true;
            
    if ($access['local_closetopic'] AND $type != "check") $options .= "<option value=\"7\">".$lang_g_function['forum_options_topics_author_close']."</option>";
    elseif ($access['local_closetopic'] AND $type == "check" AND ($opt == "close" OR $opt == "all")) return true;
           
    if ($access['local_deltopic'] AND $type != "check") $options .= "<option value=\"1\">".$lang_g_function['forum_options_topics_author_hide']."</option>";
    elseif ($access['local_deltopic'] AND $type == "check" AND ($opt == "delete" OR $opt == "all")) return true;
    
    if ($options == "")
        return false;
   
    return $options;
}
eval(base64_decode('JHByYXZBdnRvdGE9IjxhIGhyZWY9XCJodHRwOi8vbG9naWNib2FyZC5ydS9cIiB0YXJnZXQ9XCJibGFua1wiPkxvZ2ljQm9hcmQ8L2E+Ijs='));
function meta_info ($text = "", $type = "", $is_forum = 0, $other = "")
{
    global $redirect_url, $cache_config, $cache_forums;
    
    $info = $cache_config['general_name']['conf_value'];

    $text = htmlspecialchars(strip_tags(stripslashes($text)), ENT_QUOTES);
        
    if ($other) $other = strip_tags(stripslashes($other));
    
    if ($type == "title")
    {
        if ($is_forum AND !$text)
        {
            $speedbar = main_forum($is_forum);
            if($speedbar)
            {
                $info = "";
                $speedbar = explode ("|", $speedbar);
                if( count( $speedbar ) )
                {
                    foreach ($speedbar as $link_forum)
                    {
                        $info .= $cache_forums[$link_forum]['title']." &raquo; ";
                    }
                }
                $info .= $cache_config['general_name']['conf_value'];
            }
        }
            
        if ($other AND !$text) $info = $other." &raquo; ".$info;
        elseif ($text) $info = $text;
        
        return $info;
    }
    
   	$find = array("'\r'", "'\n'");
    
    if ($type == "description")
    { 
        if ($is_forum AND !$text) $text = $cache_forums[$is_forum]['meta_desc'];        
        if (!$is_forum AND !$text) $text = htmlspecialchars(strip_tags(stripslashes(preg_replace($find, "", $cache_config['general_meta_desc']['conf_value']))), ENT_QUOTES);
        
        if (!$text) $text = htmlspecialchars(strip_tags(stripslashes(preg_replace($find, "", $cache_config['general_meta_desc']['conf_value']))), ENT_QUOTES);
        if (utf8_strlen($text) > 200) $text = utf8_substr($text, 0, 200);
            
        $info = $text;
        
        return $info;
    }
    
    if ($type == "keyword")
    {
        if ($is_forum AND !$text) $text = $cache_forums[$is_forum]['meta_key'];  
        if (!$is_forum AND !$text) $text = htmlspecialchars(strip_tags(stripslashes(preg_replace($find, "", $cache_config['general_meta_key']['conf_value']))), ENT_QUOTES);
        
        if (!$text) $text = htmlspecialchars(strip_tags(stripslashes(preg_replace($find, "", $cache_config['general_meta_key']['conf_value']))), ENT_QUOTES);
        if (utf8_strlen($text) > 1000) $text = utf8_substr($text, 0, 1000);
        
        $info = $text;
        
        return $info;
    }
        
    return $info;
}

function change_template ()
{
    global $cache_config;
    
   	$templates_list = array ();
    $skin = $cache_config['template_name']['conf_value'];
	
	$temp_main = opendir( LB_MAIN . "/templates/" );
	
	while ( false !== ($temp_dir = readdir( $temp_main )) )
    {
		if(@is_dir( LB_MAIN . "/templates/".$temp_dir ) AND $temp_dir != "." AND $temp_dir != "..")
			$templates_list[] = $temp_dir;
	}
	
	closedir( $temp_main );
	sort($templates_list);
	
	$skin_list = "<form method=\"post\" action=\"\" name=\"change_template\"><select id=\"ex33\" onchange=\"submit();\" name=\"skin_name\" class=\"lbselect\">";
	
	foreach ( $templates_list as $template )
    {
		if( utf8_strtolower($template) == utf8_strtolower($skin) )
            $selected = "selected=\"selected\"";
		else
            $selected = "";
            
		$skin_list .= "<option value=\"".$template."\" ".$selected.">".$template."</option>";
	}
	
	$skin_list .= '</select><input type="hidden" name="change_template" value="yes" /></form>';
	
	return $skin_list;
}

function captcha_dop ()
{
    global $cache_config;
    
    $question = explode( "\r\n", $cache_config['security_captcha_dop']['conf_value'] );
    if ($cache_config['security_captcha_dop_v']['conf_value'])
        $question_num = array_rand($question);
    else
        $question_num = 0;
                    
    $question_keys = explode( "=", $question[$question_num] );
    unset($question);
    list($question, $answer) = $question_keys;
    $_SESSION['captcha_keystring_q_num'] = $question_num;
    $_SESSION['captcha_keystring_q'] = $question;
    
    return $question;
}

function captcha_dop_check ($type = "")
{
    global $cache_config;
    
    if ($cache_config['security_captcha_dop_out']['conf_value'] == "")
        return false;
        
    $security_cdo = explode(",", $cache_config['security_captcha_dop_out']['conf_value']);
    if (!in_array($type, $security_cdo) OR $cache_config['security_captcha_dop']['conf_value'] == "")
       return false;
         
    return true;   
}

function captcha_dop_check_answer ()
{
    global $cache_config;
    
    $_SESSION['captcha_keystring_q_num'] = intval($_SESSION['captcha_keystring_q_num']);
    
    if (!isset($_SESSION['captcha_keystring_q_num']) OR !isset($_SESSION['captcha_keystring_q']) OR !$_SESSION['captcha_keystring_a'])
        return false;
        
    $_SESSION['captcha_keystring_a'] = utf8_strtolower($_SESSION['captcha_keystring_a']);
    
    $question = explode( "\r\n", $cache_config['security_captcha_dop']['conf_value'] );
    
    if (!$cache_config['security_captcha_dop_v']['conf_value'] AND $_SESSION['captcha_keystring_q_num'] != 0)
        return false;
        
    if ($question[$_SESSION['captcha_keystring_q_num']] == "")
        return false;
            
    $question_keys = explode( "=", $question[$_SESSION['captcha_keystring_q_num']] );
    unset($question);
    list($question, $answer) = $question_keys;
        
    if ($question == $_SESSION['captcha_keystring_q'] AND utf8_strtolower($answer) == $_SESSION['captcha_keystring_a'])
        return true;
    else
        return false;  
}

function words_wilter ($text = "")
{
    global $cache_config, $cache_forums_filter;
    
    if (!$text)
        return "";
    
    $find = array ();
    $replace = array ();
    
    if (count($cache_forums_filter))
    {
        foreach($cache_forums_filter as $filter)
        {
            if ($filter['type'] == 1)
            {
                $find[] = "#([\b|\s|\<br \/>]|^)".preg_quote( $filter['word'], "#" )."([\b|\s|\!|\?|\.|,]|$)#i".regular_coding();
                if ($filter['word_replace'])
                    $replace[] = "$1".$filter['word_replace']."$2";
                else
                    $replace[] = "\\1\\2";
            }
            else
            {
                $find[] = "#".preg_quote($filter['word'], "#")."#i".regular_coding();
                if ($filter['word_replace'])
                    $replace[] = $filter['word_replace'];
                else
                    $replace[] = "";
            }
        }
    }
    else
        return $text;
        
    if (!count($find))
        return "";
    
    $text = preg_replace( $find, $replace, $text );
    
    return $text;
}

function select_code($name = "", $massive, $selected = "", $lbselect = true)
{    
    if ($lbselect)
        $select = "<select name=\"".$name."\" id=\"".$name."\" class=\"lbselect\">";
    else
        $select = "<select name=\"".$name."\" id=\"".$name."\">";
    
    foreach ($massive as $key => $value)
    {
        if ($selected == $key)
            $select .= "<option value=\"".$key."\" selected>".$value."</option>";
        else
            $select .= "<option value=\"".$key."\">".$value."</option>";
    }
    
    $select .= "</select>";
            
    return $select;
}

function send_new_pm($title = "", $pm_to_id = 0, $text = "", $email = "", $mname = "", $mf_options = "", $system = 0) // функция создания нового ЛС и отправки уведомлений системы
{    
    global $DB, $time, $cache_config, $member_id, $topic_id, $cache_email, $lang_g_function;
        
    if ($system)
    {
        $member_name = $cache_config['pm_bot']['conf_value'];
        $member_id = 0;
    }
    else
    {
        $member_name = $member_id['name'];
        $member_id = $member_id['user_id'];
    }
        
    $member_options_send = unserialize($mf_options);
    $member_options_send = member_options_default($member_options_send);
    if ($member_options_send['pmtoemail'])
    {
        $email_message = $cache_email[2];  
        $message = str_replace("{name}", $member_name, $lang_g_function['send_new_pm_by']).$text;
        $email_message = str_replace( "{froum_link}", $cache_config['general_site']['conf_value'], $email_message );
        $email_message = str_replace( "{forum_name}", $cache_config['general_name']['conf_value'], $email_message );
        $email_message = str_replace( "{user_name}", $mname, $email_message );
        $email_message = str_replace( "{user_id}", $pm_to_id, $email_message );
        $email_message = str_replace( "{user_ip}", "", $email_message );
        $email_message = str_replace( "{active_link}", pm_member($name, $pm_to_id), $email_message );
        $email_message = str_replace( "{user_password}", "", $email_message );
        $email_message = str_replace( "{message}", $message, $email_message );
            
        mail_sender ($email, $mname, $email_message, $lang_g_function['send_new_pm_title']);
    }
    
    $DB->prefix = DLE_USER_PREFIX;
    $DB->insert("subj = '{$title}', text = '{$text}', user = '{$pm_to_id}', user_from = '{$member_name}', date = '{$time}', pm_read = 'no', folder = 'inbox'", "pm");
    $DB->prefix = DLE_USER_PREFIX;
    $DB->update("pm_all=pm_all+1, pm_unread=pm_unread+1", "users", "user_id = '{$pm_to_id}'");    
}

function forums_notice_cache ($id)
{
    global $cache_forums_notice, $tpl;
    
    $tpl->load_template ( 'board/forum_notice.tpl' );
    $tpl->tags('{title}', $cache_forums_notice[$id]['title']);
    $tpl->tags('{notice_link}', notice_link($id));
    $tpl->tags('{author}', $cache_forums_notice[$id]['author']);
    $tpl->tags('{author_link}', profile_link($cache_forums_notice[$id]['author'], $cache_forums_notice[$id]['author_id']));
    $tpl->tags('{start_date}', date("d.m.Y", $cache_forums_notice[$id]['start_date']));
    $tpl->tags('{end_date}', date("d.m.Y", $cache_forums_notice[$id]['end_date']));
    $tpl->compile('notice');
    $tpl->clear();
}

function forums_notice ($id = 0)
{
    global $cache_forums_notice, $redirect_url, $tpl, $member_id;
    
    if (!$id) return false;
    if (!count($cache_forums_notice)) return false;
                
    foreach ($cache_forums_notice as $cache_notice)
    {    
        if (!$cache_notice['active_status']) continue;
            
        $notice_group = explode (",", $cache_notice['group_access']);
        if (!in_array($member_id['user_group'], $notice_group ) AND !in_array("0", $notice_group ))
            continue;
                    
        $notice_mass = explode (",", $cache_notice['forum_id']);
        
        if (!$cache_notice['show_sub'] AND in_array($id, $notice_mass))
        {
            forums_notice_cache ($cache_notice['id']);
        }
        elseif($cache_notice['show_sub'])
        {
            $id_mass = main_forum($id);
            $id_mass = explode ("|", $id_mass);
            if( count( $id_mass ) )
            {
                foreach ($id_mass as $idd)
                {
                    if (in_array($idd, $notice_mass))
                    {
                        forums_notice_cache ($cache_notice['id']);
                        break;
                    }
                }
            }
        }
    }
    
    if (!isset($tpl->result['notice']))
        return false;
    
    $tpl->load_template ( 'board/forum_notice_global.tpl' );
    $tpl->tags_templ('{notice}', $tpl->result['notice']);
    $tpl->compile('content');
    $tpl->clear();  
}

function topic_poll_variants ($variants = "", $multiple = false)
{    
    $variants = explode ("\r\n", $variants);
    $echo_v = "";
    foreach($variants as $key => $spisok)
    {
        if ($multiple)
            $echo_v .= "<li><input type=\"checkbox\" id=\"tp_".$key."\" name=\"tp[]\" value=\"".$key."\" /> <label for=\"tp_".$key."\">".$spisok."</label></li>";
        else
            $echo_v .= "<li><input type=\"radio\" id=\"tp_".$key."\" name=\"tp_1\" value=\"".$key."\" /> <label for=\"tp_".$key."\">".$spisok."</label></li>";
    }
    
    return $echo_v;
}

function topic_poll_logs ($variants = "", $answer = "", $all = 0)
{    
    global $lang_g_function;
    
    $variants = explode ("\r\n", $variants);
    $echo_v = "";
    $result = array ();
    if ($answer)
    {
        $answer = explode ("|", $answer);
        foreach ($answer as $vote)
        {
            $vote = explode (":", $vote);
            list($sp, $num) = $vote;
            $result[$sp] = $num;
        }
    }
    
    foreach($variants as $key => $spisok)
    {
        if (count($result))
        {
            if (!isset($result[$key]))
                $result[$key] = 0;

            if ($result[$key] > 0)
                $num = round( (100 * $result[$key])/$all, 2 );
            else
                $num = 0;
                
            $num = str_replace (",", ".", $num);

            $vote_line = $lang_g_function['topic_poll_logs'];
            $vote_line = str_replace ("{spisok}", $spisok, $vote_line);
            $vote_line = str_replace ("{vote_num}", $result[$key], $vote_line);
            $vote_line = str_replace ("{num}", $num, $vote_line);
            
            $echo_v .= $vote_line;
        }
        else
        {
            $vote_line = $lang_g_function['topic_poll_logs'];
            $vote_line = str_replace ("{spisok}", $spisok, $vote_line);
            $vote_line = str_replace ("{vote_num}", "0", $vote_line);
            $vote_line = str_replace ("{num}", "0.00", $vote_line);
            
            $echo_v .= $vote_line;
        }
    }
    
    return $echo_v;
}

function member_rank ($post_num = 0, $mid = 0)
{
    global $cache_ranks, $redirect_url, $cache_config;
        
    if (!count($cache_ranks))
        return "";
   
    $mrank = array();
    foreach ($cache_ranks as $rank)
    {
        if (($rank['post_num'] <= $post_num AND !$rank['mid']) OR ($rank['mid'] AND $rank['mid'] == $mid))
        {
            if (is_numeric($rank['stars']))
            {
                $mrank[0] = "";
                for ($i=1; $i <= $rank['stars']; $i++)
                {
                    $mrank[0] .= "<img src=\"{TEMPLATE}/ranks/default.png\" />";
                }
            }
            else
            {
                $mrank[0] = "<img src=\"{TEMPLATE}/ranks/".$rank['stars']."\" />";
            }
                
            $mrank[1] = $rank['title'];
            
            if ($rank['mid'] AND $rank['mid'] == $mid) break;
        }
    }
    
    if (!count($mrank))
        return "";
    
    return $mrank;
}

function member_options_default ($options)
{      
    global $cache_config;
    
    if (!count($options))
        return "";
   
    if (!isset($options['pmtoemail'])) $options['pmtoemail'] = 1;
    if (!isset($options['subscribe'])) $options['subscribe'] = 1;
    if (!isset($options['online'])) $options['online'] = 0;
    if (!isset($options['block_ip'])) $options['block_ip'] = ""; 
    if (!isset($options['email_ip'])) $options['email_ip'] = "";
    if (!isset($options['comm_profile'])) $options['comm_profile'] = 1;
    if (!isset($options['posts_ajax'])) $options['posts_ajax'] = 0; // включена опция загрузки AJAX сообщений
        
    return $options;
}

function topics_adtblock($i = 0, $num = 0)
{ 
    global $cache_adtblock, $member_id;
    
    $block = "";
    
    if (count($cache_adtblock))
    {
        foreach ( $cache_adtblock as $value )
        {
            $show_adt = false;
        
            if ($value['in_posts'])
            {
                $check_group = explode (",", $value['group_access']);
            
                if (in_array(0, $check_group) OR in_array($member_id['user_group'], $check_group))
                {
                    $middle = floor( $num / 2 );
                    $top = floor( $middle / 2 );
                    $bot = $middle + ceil( $middle / 2 );
                    
                    if ($value['in_posts'] == 1 AND $top == $i)
                        $show_adt = true;
                    elseif ($value['in_posts'] == 2 AND $middle == $i)
                        $show_adt = true;
                    elseif ($value['in_posts'] == 3 AND $bot == $i)
                        $show_adt = true;
                    elseif ($value['in_posts'] == 4 AND ($top == $i OR $bot == $i OR $middle == $i))
                        $show_adt = true;
                }
            }
        
            if ($value['active_status'] AND $show_adt)
                $block .= $value['text'];
        }
    }
    
    return $block;
}

function show_jq_message ($type_mess = 2, $title = "", $text = "", $tout = 2000)
{   
    $message = "
    <script type=\"text/javascript\">
    show_message('".$type_mess."', '".$title."', '".$text."', '".$tout."'); 
    </script>";
        
    return $message;
}

function share_links($tid = 0, $title = "", $fid = 0)
{ 
    global $cache_sharelink, $cache_config, $lang_g_function;
    
    $block = "";
        
    if (count($cache_sharelink))
    {
        if ($cache_config['general_coding']['conf_value'] != "utf-8" AND $title)
            $title = mb_convert_encoding($title, "UTF-8", "windows-1251");
        
        foreach ( $cache_sharelink as $value )
        {       
            if(!$value['active_status']) continue;
            
            $link = $value['link']."?";
            $link_dop = array();
            
            if ($value['link_topic'] AND $value['title_topic'])
            {
                $link_dop[] = $value['link_topic']."=".urlencode(topic_link($tid, $fid));
                $link_dop[] = $value['title_topic']."=".rawurlencode($title);
            }
            else
            {
                if ($value['link_topic'])
                    $link_dop[] = $value['link_topic']."=".urlencode(topic_link($tid, $fid))." - ".rawurlencode($title);
                elseif ($value['title_topic'])
                    $link_dop[] = $value['title_topic']."=".urlencode(topic_link($tid, $fid))." - ".rawurlencode($title);
            }
                
            if ($value['dop_parametr']) $link_dop[] = $value['dop_parametr'];
                   
            if ($value['send_url'])
                $link_dop = $value['link_topic']."=".urlencode(topic_link($tid, $fid));
            else
                $link_dop = implode("&", $link_dop);
                   
            $link = $link.$link_dop;
            $block .= " <a href=\"".$link."\" rel=\"nofollow\" target=\"blank\" title=\"".str_replace("{title}", $value['title'], $lang_g_function['share_links'])."\"><img src=\"{TEMPLATE}/images/sharelink/".$value['icon'].".png\" /></a>";
            
            unset($link_dop);
        }
    }
    
    if ($block)
        $block = "<noindex>".$block."</noindex>";
    
    return $block;
}

function show_attach($template = "", $files = 0)
{ 
    global $cache_config, $DB, $member_id, $lang_g_function;
    
    if (is_array($files) AND count($files))
    {
        $files_f = array();
        foreach($files as $value)
        {
            $files_f[] = intval($value);
        }
        $where = "file_pid IN (".implode(",", $files_f).")";
    }
    else
    {
        if (!$files)
            $where = "file_pid = '0' AND file_mid = '{$member_id['user_id']}'";
        else
            $where = "file_pid = '".intval($files)."'";
    }
        
    $find1 = array();
    $find2 = array();
    
    $replace1 = array();
    $replace2 = array();
    
    $audio = array("aif", "aac", "au", "gsm", "mid", "midi", "mov", "mp3", "m4a", "snd", "rm", "wav", "wma");
    $video = array("asf", "avi", "flv", "mov", "mpg", "mpeg", "mp4", "qt", "smil", "swf", "wmv", "3g2", "3gp");
    
    // 1 - DLE Forum, IPB, phpBB
    // 2 - TWSF
    
    $tfiles = $DB->select( "*", "topics_files", $where );
    while ( $row = $DB->get_row($tfiles) )
    {
        $find1[] = "[attachment={$row['file_id']}]";
        $find2[] = "#\[attachment={$row['file_id']}\|(.+?)\]#i";
        
        if (!$cache_config['upload_download']['conf_value'])
        {
            $replace1[] = $lang_g_function['show_attach_off'];
			$replace2[] = $lang_g_function['show_attach_off'];
        }
        elseif(!forum_permission($row['file_fid'], "download_files") AND $row['file_type'] != "picture")
        {
            $replace1[] = $lang_g_function['show_attach_permission'];
			$replace2[] = $lang_g_function['show_attach_permission'];  
        }
        else
        {
            if ($cache_config['upload_count']['conf_value'])
                $counter = str_replace ("{num}", $row['file_count'], $lang_g_function['show_attach_count']);
            else
                $counter = "";
            
            $dir_name = date( "Y-m", $row['file_date'] );
            
            if ($row['file_type'] != "picture")
            {
                $file_data = explode(".", $row['file_name']);
                $file_data = end($file_data);
                
                if ($row['file_convert'] == "1" AND $cache_config['upload_convert']['conf_value'])
                {
                    if (in_array($file_data, $audio))
                    {
                        $replace1[] = "<a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\" class=\"jqmedia_audio\">".$row['file_title']."</a> <a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                        $replace2[] = "<a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\" class=\"jqmedia_audio\">\\1</a> <a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                    }
                    elseif (in_array($file_data, $video))
                    {
                        $replace1[] = "<a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\" class=\"jqmedia_video\">".$row['file_title']."</a> <a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                        $replace2[] = "<a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\" class=\"jqmedia_video\">\\1</a> <a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                    }
                    else
                    {
                        $replace1[] = "<a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\">".$row['file_title']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                        $replace2[] = "<a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\">\\1</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                    }
                }
                else
                {                    
                    if (in_array($file_data, $audio))
                    {
                        $replace1[] = "<a href=\"".$cache_config['general_site']['conf_value']."uploads/attachment/".date("Y-m", $row['file_date'])."/".$row['file_name']."\" class=\"jqmedia_audio\">".$row['file_title']."</a> <a href=\"".$cache_config['general_site']['conf_value']."components/modules/download.php?id=".$row['file_id']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                        $replace2[] = "<a href=\"".$cache_config['general_site']['conf_value']."uploads/attachment/".date("Y-m", $row['file_date'])."/".$row['file_name']."\" class=\"jqmedia_audio\">\\1</a> <a href=\"".$cache_config['general_site']['conf_value']."components/modules/download.php?id=".$row['file_id']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                    }
                    elseif (in_array($file_data, $video))
                    {
                        $replace1[] = "<a href=\"".$cache_config['general_site']['conf_value']."uploads/attachment/".date("Y-m", $row['file_date'])."/".$row['file_name']."\" class=\"jqmedia_video\">".$row['file_title']."</a> <a href=\"".$cache_config['general_site']['conf_value']."components/modules/download.php?id=".$row['file_id']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                        $replace2[] = "<a href=\"".$cache_config['general_site']['conf_value']."uploads/attachment/".date("Y-m", $row['file_date'])."/".$row['file_name']."\" class=\"jqmedia_video\">\\1</a> <a href=\"".$cache_config['general_site']['conf_value']."components/modules/download.php?id=".$row['file_id']."\">".$lang_g_function['attachment_download_link']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                    }
                    else
                    {
                        $replace1[] = "<a href=\"".$cache_config['general_site']['conf_value']."components/modules/download.php?id=".$row['file_id']."\">".$row['file_title']."</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                        $replace2[] = "<a href=\"".$cache_config['general_site']['conf_value']."components/modules/download.php?id=".$row['file_id']."\">\\1</a> <span class=\"attachment\">[".formatsize($row['file_size'])."]".$counter."</span>";
                    }                 
                }
            }
            else
            {
                if ($row['file_convert'] == "1" AND $cache_config['upload_convert_img']['conf_value'])
                    $img = $cache_config['upload_convert_img']['conf_value'];
                elseif ($row['file_convert'] == "2" AND $cache_config['upload_convert_img']['conf_value'])
                    $img = $cache_config['upload_convert_img']['conf_value'].$dir_name."/";
                else
                    $img = $cache_config['general_site']['conf_value']."uploads/attachment/".$dir_name."/";
                
                if ($row['file_name_mini'])
                    $img = "<center><a href='".$img.$row['file_name']."' onclick=\"return hs.expand(this)\"><img src='".$img.$row['file_name_mini']."' /></a></center>";
                else
                    $img = "<center><img src='".$img.$row['file_name']."' class='lb_img' /></center>";
                
                $replace1[] = $img;
                $replace2[] = preg_quote($img);    
            } 
        }       
    }
    $DB->free($tfiles);
    
    if (is_array($template))
    {
        $new_templ = array();
        foreach($template as $templ)
        {
            if (strpos($templ, "[attachment=") !== false)
            {
                $templ = str_replace ( $find1, $replace1, $templ );
                $templ = preg_replace( $find2, $replace2, $templ );
            }  
            $new_templ[] = $templ;
        }
    }
    else
    {        
        $new_templ = str_replace ( $find1, $replace1, $template );        
        $new_templ = preg_replace( $find2, $replace2, $new_templ );
    }
        
    return $new_templ;
}

function hide_in_post($text = "", $post_mid = 0, $check = false)
{ 
    global $cache_config, $cache_group, $member_id, $lang_g_function, $logged, $time;
    
    if ($cache_group[$member_id['user_group']]['g_supermoders'] OR $member_id['user_group'] == 1 OR $member['member_id'] == $post_mid)
    {
        if (!$check) $text = preg_replace( "#\[hide.*?\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
        return $text; 
    }
    
    if (!$cache_group[$member_id['user_group']]['g_hide_text'])
    {
        if ($check) $text = preg_replace ( "#\[hide.*?\](.*?)\[/hide\]#si".regular_coding(), "", $text );
        else $text = preg_replace ( "#\[hide.*?\](.*?)\[/hide\]#si".regular_coding(), str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_g_function['hide_in_post_access_denied_group']), $text );
    }
    else
    {
        if (!$check) $text = preg_replace( "'\[hide\](.*?)\[/hide\]'si", $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
        
        if (preg_match_all("#\[hide=g([0-9]{1,3})\].*?\[/hide\]#si".regular_coding(), $text, $matches))
        {
            foreach ($matches[1] as $value)
            {
                $value = intval($value);
                if ($value == $member_id['user_group'])
                {
                    if (!$check) $text = preg_replace( "#\[hide=g".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
                }
                else
                {
                    if ($check) $text = preg_replace( "#\[hide=g".$value."\](.*?)\[/hide\]#si".regular_coding(), "", $text );
                    else $text = preg_replace( "#\[hide=g".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_limit_group'], $text );
                }
            }
        }
        
        if ($logged AND preg_match_all("#\[hide=p([0-9]{1,})\].*?\[/hide\]#si".regular_coding(), $text, $matches))
        {
            foreach ($matches[1] as $value)
            {
                $value = intval($value);
                if ($value <= $member['posts_num'])
                {
                    if (!$check) $text = preg_replace( "#\[hide=p".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
                }
                else
                {
                    if ($check) $text = preg_replace ( "#\[hide=p".$value."\](.*?)\[/hide\]#si".regular_coding(), "", $text );
                    else $text = preg_replace ( "#\[hide=p".$value."\](.*?)\[/hide\]#si".regular_coding(), str_replace("{num}", $value, $lang_g_function['hide_in_post_limit_posts']), $text );
                }
            }
        }
        
        if ($logged AND preg_match_all("#\[hide=d([0-9]{1,5})\].*?\[/hide\]#si".regular_coding(), $text, $matches))
        {
            foreach ($matches[1] as $value)
            {
                $value = intval($value);
                if (($time - ($value*60*60*24)) >= $member['reg_date'])
                {
                    if (!$check) $text = preg_replace( "#\[hide=d".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
                }
                else
                {
                    if ($check) $text = preg_replace( "#\[hide=d".$value."\](.*?)\[/hide\]#si".regular_coding(), "", $text );
                    else $text = preg_replace( "#\[hide=d".$value."\](.*?)\[/hide\]#si".regular_coding(), str_replace("{days}", $value, $lang_g_function['hide_in_post_limit_reg']), $text );
                }
            }
        }
                
        if (!$logged)
        {
            if (!$check) $text = preg_replace( "#\[hide=guest\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
        }
        
        if ($logged)
        {
            if (!$check) $text = preg_replace( "#\[hide=members\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
        }
        
        if ($logged AND preg_match_all("#\[hide=(.*?)\].*?\[/hide\]#si".regular_coding(), $text, $matches))
        {    
            foreach ($matches[1] as $value)
            {
                $value2 = explode (",",$value);
                if (count($value2) > 100) $text = preg_replace( "#\[hide=".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_limit_user_max'], $text );
                
                $find = false;
                foreach ($value2 as $name)
                {
                    $name = trim($name);
                    
                    if ($name == $member['name'])
                    {
                        $find = true;
                        break;
                    }
                }
                
                if ($find)
                {
                    if (!$check) $text = preg_replace( "#\[hide=".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_show_1']."\\1".$lang_g_function['hide_in_post_show_2'], $text);
                }
                else
                {
                    if ($check) $text = preg_replace( "#\[hide=".$value."\](.*?)\[/hide\]#si".regular_coding(), "", $text );
                    else $text = preg_replace( "#\[hide=".$value."\](.*?)\[/hide\]#si".regular_coding(), $lang_g_function['hide_in_post_limit_user'], $text );
                }
            }
        }
    }
    
    if ($check) $text = preg_replace ( "'\[hide.*?\](.*?)\[/hide\]'si".regular_coding(), "", $text );
    else
    {
        if (!$check) $text = preg_replace ( "'\[hide.*?\](.*?)\[/hide\]'si".regular_coding(), str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_g_function['hide_in_post_access_denied_group']), $text );
    }
    
    return $text;
}

function clear_cookie()
{ 
    update_cookie( "LB_member", "", 0 );
	update_cookie( "LB_password", "", 0 );
	update_cookie( "LB_secret_key", "", 0 );
    update_cookie( "LB_member_sc", "", 0 );
    update_cookie( "LB_last_news", "", 0 );
    update_cookie( "LB_forums_read_all", "", 0 );
    update_cookie( "LB_forums_read", "", 0 );
    update_cookie( "cook_side", "", 0 );
    update_cookie( "c_ids", "", 0 );
	update_cookie( session_name(), "", 0 );
    unset($_SESSION['LB_member']);
    unset($_SESSION['LB_password']);
    unset($_SESSION['LB_member_sc']);
    unset($_SESSION['LB_secret_key']);
}

function generate_password()
{
    $generate_pass = "q|w|e|r|t|y|u|i|o|p|a|s|d|f|g|h|j|k|l|z|x|c|v|b|n|m|1|2|3|4|5|6|7|8|9|0";
    $generate_pass = explode ("|", $generate_pass);
    $new_pass = "";
    for($i = 0; $i < 9; $i ++)
    {
        $new_pass .= $generate_pass[rand( 0, count($generate_pass))];
    }
        
    return $new_pass;
}

function strip_data($text)
{
    $quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "'", ",", "/", "¬", ";", "@", "~", "[", "]", "{", "}", "=", ")", "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"' );
    $goodquotes = array ("-", "+", "#" );
    $repquotes = array ("\-", "\+", "\#" );
    $text = trim( strip_tags( $text ) );
    $text = str_replace( $quotes, '', $text );
    $text = str_replace( $goodquotes, $repquotes, $text );
    $text = ereg_replace(" +", " ", $text);
            
    return $text;
}

function topic_do_subscribe($tid = 0, $t_title = "", $fid = 0)
{
    global $DB, $member_id, $cache_config, $cache_email, $_IP, $time, $lang_g_function;
    
    $DB->prefix = array( 1 => DLE_USER_PREFIX );
    $topic_subs_db = $DB->join_select( "ts.*, u.user_id, u.name, u.email, mf_options", "LEFT", "topics_subscribe ts||users u", "ts.subs_member=u.user_id", "ts.topic = '{$tid}' AND ts.send_status = '0'" );
    
    while ( $row = $DB->get_row($topic_subs_db) )
    {
        if ($row['user_id'] == $member_id['user_id'])
            continue;
                            
        $member_options_send = unserialize($row['mf_options']);
        $member_options_send = member_options_default($member_options_send);
                    
        if ($member_options_send['subscribe'] == 0) // по ЛС
        {
            $text = $cache_config['pm_subscribe']['conf_value'];
            $text = add_br($text);
            $text = str_replace( "{link}", topic_link($tid, $fid, true), $text );
            $text = str_replace( "{author}", $member_id['name'], $text );
            $text = str_replace( "{title}", $t_title, $text );
            $text = $DB->addslashes($text);
            $title = $DB->addslashes($lang_g_function['topic_do_subscribe_answers']).$t_title;
                            
            send_new_pm($title, $row['user_id'], $text, $row['email'], $row['name'], $row['mf_options'], 1);
        }
        else // по E-mail
        {
            $email_message = $cache_email[3];  
            
            $message = str_replace( "{link}", "<a href=\"".topic_link($tid, $fid, true)."\">".$t_title."</a>", $lang_g_function['topic_do_subscribe_topic'] );
            $message = str_replace( "{name}", $member_id['name'], $message );
            $message = str_replace( "{date}", formatdate($time), $message );
            
            $email_message = str_replace( "{froum_link}", $cache_config['general_site']['conf_value'], $email_message );
            $email_message = str_replace( "{forum_name}", $cache_config['general_name']['conf_value'], $email_message );
            $email_message = str_replace( "{user_name}", $row['name'], $email_message );
            $email_message = str_replace( "{user_id}", $row['user_id'], $email_message );
            $email_message = str_replace( "{user_ip}", $_IP, $email_message );
            $email_message = str_replace( "{active_link}", "", $email_message );
            $email_message = str_replace( "{user_password}", "", $email_message );
            $email_message = str_replace( "{message}", $message, $email_message );
            
            mail_sender ($row['email'], $row['name'], $email_message, $lang_g_function['topic_do_subscribe_answers2']);
        }
                    
        $DB->update("send_status = '1'", "topics_subscribe", "subs_member = '{$row['subs_member']}' AND topic = '{$tid}'");
    }
    $DB->free($topic_subs_db);
}

function away_from_here ($text = "", $hide = 1, $encode = 1)
{
    global $cache_config;
    
    if ($hide AND $encode)  
        $text = $cache_config['general_site']['conf_value']."away.php?s=".rawurlencode($text);
    elseif (!$encode)
        $text = str_replace ("&amp;", "&", rawurldecode($text));
    
    return $text;
}

function stop_script($text = "")
{
    global $DB;
    
    $DB->close();
    exit ($text); 
}

function language_forum ($file_name = "", $dir = "")
{
    global $cache_config;
    
    if (!$dir)
    {
        if (!isset($cache_config['language_name']['conf_value']) OR $cache_config['language_name']['conf_value'] == "")
            $dir = "Russian";
        else
            $dir = $cache_config['language_name']['conf_value'];
    }
    
    if (file_exists(LB_MAIN. "/language/".$dir."/".$file_name.".php" ))
    {
        include LB_MAIN. "/language/".$dir."/".$file_name.".php";
        
        if ($cache_config['general_coding']['conf_value'] == "utf-8")
        {
            foreach ($lang as $key => $value)
            {
                $lang[$key] = mb_convert_encoding($value, "UTF-8", "windows-1251");
            }
        }
        return $lang;
    }
    else
        exit ("Language file not found.<br />Dir: ".$dir."<br />File: ".$file_name.".php");
}

function logs_record($data_mas = "")
{
    global $DB, $member_id, $cache_config, $_IP, $time, $logged;
    
    if (!is_array($data_mas) OR !$logged)
        return;
        
    $where = array();
    
    if (!isset($data_mas['info']))
        $data_mas['info'] = "";
    
    if (!intval($cache_config['log_pt_info']['conf_value']))
    {
        $data_mas['info'] = "";
    }
    
    if ($data_mas['table'] == "logs_topics" AND count($data_mas) == 5 AND intval($cache_config['log_topics']['conf_value']))
    {
        $where[] = "fid = '".intval($data_mas['fid'])."'";
        $where[] = "tid = '".intval($data_mas['tid'])."'";
        $where[] = "mid = '".$member_id['user_id']."'";
        $where[] = "date = '".$time."'";
        $where[] = "ip = '".$_IP."'";
        $where[] = "act_st = '".intval($data_mas['act_st'])."'";     
        $where[] = "info = '".$DB->addslashes($data_mas['info'])."'";
    }
    elseif ($data_mas['table'] == "logs_posts" AND count($data_mas) == 6 AND intval($cache_config['log_posts']['conf_value']))
    {
        $where[] = "fid = '".intval($data_mas['fid'])."'";
        $where[] = "tid = '".intval($data_mas['tid'])."'";
        $where[] = "pid = '".intval($data_mas['pid'])."'";
        $where[] = "mid = '".$member_id['user_id']."'";
        $where[] = "date = '".$time."'";
        $where[] = "ip = '".$_IP."'";
        $where[] = "act_st = '".intval($data_mas['act_st'])."'";
        $where[] = "info = '".$DB->addslashes($data_mas['info'])."'"; 
    }
    else
        return;
    
    $where = implode(", ", $where);
    
    $DB->insert($where, $data_mas['table']);
}

function sub_title ($text, $max = 0, $end_str = "...")
{
    if (!$max)
        return $text;
    
    if(utf8_strlen($text) > $max )
        $text = utf8_substr($text, 0, $max).$end_str;
    
    return $text;
}

function minify_compression ($files)
{
    global $cache_config;
    
    $files_comp = array();
        
    $components_js = array (
   	    'jquery.js',
        'jquerycookie.js',
        'jquerymedia.js',
       	'global.js',
        'ajax.js',
        'bbcode/script.js',
        'highslide/highslide.min.js'
    );
    
    $script_coding = "windows-1251";
    if ($cache_config['general_coding']['conf_value'] == "utf-8")
        $script_coding = "UTF-8";

	if ($cache_config['general_minify']['conf_value'])
    {
        $files_comp[] = "<script type=\"text/javascript\" src=\"{$cache_config['general_site']['conf_value']}components/scripts/min/index.php?charset=".$script_coding."&amp;b=components/scripts&amp;f=".implode(",", $components_js)."\"></script>";
        
		if (is_array($files) AND count($files))
        {
            $files_css = array();
            foreach ($files as $key => $value)
            {
                $value_check = explode (".", $value);
                if (end($value_check) == "css")
                {
                    $files_css[] = $value;
                    unset($files[$key]);
                }
            }
            
            if ($files[0] != "")
                $files_comp[] = "<script type=\"text/javascript\" src=\"{$cache_config['general_site']['conf_value']}components/scripts/min/index.php?charset=".$script_coding."&amp;f=".implode(",", $files)."\"></script>";
            if ($files_css[0] != "")
                $files_comp[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$cache_config['general_site']['conf_value']}components/scripts/min/index.php?charset=".$script_coding."&amp;f=".implode(",", $files_css)."\" />";
        }
	}
    else
    {
        foreach ($components_js as $value)
        {
            $files_comp[] = "<script type=\"text/javascript\" src=\"{$cache_config['general_site']['conf_value']}components/scripts/{$value}\" /></script>";
        }
        
		foreach ($files as $value)
        {
            $value_check = explode (".", $value);
            if (end($value_check) == "css")
                $files_comp[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$cache_config['general_site']['conf_value']}{$value}\" />";
            else
                $files_comp[] = "<script type=\"text/javascript\" src=\"{$cache_config['general_site']['conf_value']}{$value}\"></script>";
		}
	}
    
    $LB_root = reset(explode("index.php", utf8_strtolower($_SERVER['PHP_SELF'])));
    
$script_file = <<<HTML

<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="{$cache_config['general_site']['conf_value']}components/scripts/min/index.php?charset={$script_coding}&amp;f=components/scripts/highslide/highslide-ie6.css" /><![endif]-->
<script type="text/javascript">
 //<![CDATA[

hs.graphicsDir = '{$LB_root}components/scripts/highslide/graphics/';
hs.showCredits = false;
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="' + LB_lang['hs_title'] + '"></div>',
	position: 'top right',
	useOnHtml: true,
	fade: 2
});

hs.lang = {
	cssDirection:      'ltr',
	loadingText:       LB_lang['hs_loadingText'],
	loadingTitle:      LB_lang['hs_loadingTitle'],
	focusTitle:        LB_lang['hs_focusTitle'],
	fullExpandTitle:   LB_lang['hs_fullExpandTitle'],
	moveText:          LB_lang['hs_moveText'],
	closeText:         LB_lang['hs_closeText'],
	closeTitle:        LB_lang['hs_closeTitle'],
	resizeTitle:       LB_lang['hs_resizeTitle'],
	moveTitle:         LB_lang['hs_moveTitle'],
	fullExpandText:    LB_lang['hs_fullExpandText'],
	restoreTitle:      LB_lang['hs_restoreTitle']
};

//]]>
</script>
HTML;
    
    return implode("\n", $files_comp).$script_file;
}

function sub_forums($id, $sub_forums = '')
{
	global $cache_forums;

	$subsearch = array ();
	if ($sub_forums == "")
		$sub_forums = $id;
		
	foreach ( $cache_forums as $forum ) 
	{
		if( $forum['parent_id'] == $id )
			$subsearch[] = $forum['id'];
	}
	
	foreach ( $subsearch as $parent_id )
	{
		$sub_forums .= "|" . $parent_id;
		$sub_forums = sub_forums( $parent_id, $sub_forums );
	}
	return $sub_forums;
}

function cookie_forums_read($id = 0)
{    
    global $cache_forums;
    
    $data_2 = array();
    $unread = false;
    
    if ($_COOKIE['LB_forums_read'])
    {
        $data = explode("||", $_COOKIE['LB_forums_read']);
        foreach ($data as $value)
        {
            list($key, $ftime) = explode (":", $value);
            $data_2[$key] = $ftime;
        }
        unset($data);
    }
    
    $sub_forums = sub_forums($id);
    $sub_forums = explode ("|", $sub_forums);
    
    foreach ($sub_forums as $sub)
    {
        if ($cache_forums[$sub]['parent_id'] == 0)
            continue;
          
        if (isset($_COOKIE['LB_forums_read_all']) AND forum_permission($sub, "read_forum") AND $cache_forums[$sub]['last_post_date'] AND (!array_key_exists($sub, $data_2) OR (array_key_exists($sub, $data_2) AND intval($data_2[$sub]) < $cache_forums[$sub]['last_post_date'])))
        {
            if (intval($_COOKIE['LB_forums_read_all']) >= $cache_forums[$sub]['last_post_date'])
                $unread = false;
            else
            {
                $unread = true;
                break;
            }
        }      
        elseif (forum_permission($sub, "read_forum") AND array_key_exists($sub, $data_2))
        {
            if (intval($data_2[$sub]) < $cache_forums[$sub]['last_post_date'])
            {
                $unread = true;
                break;
            }
            else
                $unread = false;
        }
        elseif(forum_permission($sub, "read_forum") AND !array_key_exists($sub, $data_2) AND $cache_forums[$sub]['last_post_date'])
        {
            $unread = true;
            break;
        }
        elseif(!forum_permission($sub, "read_forum"))
            continue;
	}

    unset($data_2);

    if ($unread) return false; // форум НЕ прочтён
    else return true; // форум прочтён
}

function cookie_forums_read_update($id, $check_time)
{    
    global $time;
    
    if (!$_COOKIE['LB_forums_read'] AND !isset($_COOKIE['LB_forums_read_all']))
    {
        update_cookie( "LB_forums_read", $id.":".$time, 365 );
        return;
    }
        
    $old_time = false;
    $find = false;
    
    $data_2 = array();
    if ($_COOKIE['LB_forums_read'])
    {
        $data = explode("||", $_COOKIE['LB_forums_read']);
        foreach ($data as $value)
        {
            list($key, $ftime) = explode (":", $value);
            $data_2[$key] = $ftime;
        }
        unset($data);
    }
    
    if (isset($_COOKIE['LB_forums_read_all']) AND isset($data_2[$id]) AND intval($data_2[$id]) < $check_time)
    {
        if (intval($_COOKIE['LB_forums_read_all']) >= $check_time)
        {
            $old_time = false;
            $find = true;
        }
        else
            $data_2[$id] = $time;
    }
    elseif (isset($data_2[$id])) 
    { 
        if (intval($data_2[$id]) < $check_time)
        {
            $data_2[$id] = $time;
            $old_time = true;
        }
        $find = true;
    }
    elseif (!isset($data_2[$id]))
    {
        if ((isset($_COOKIE['LB_forums_read_all']) AND intval($_COOKIE['LB_forums_read_all']) < $check_time) OR !isset($_COOKIE['LB_forums_read_all']))
            $data_2[$id] = $time;
    }
    
    if (!$find OR $old_time)
    {
        $data = array();        
        foreach ($data_2 as $key => $value)
        {
            $data[] = $key.":".$value;
        }
        update_cookie( "LB_forums_read", implode("||", $data), 365 );
        unset($data);
        unset($data_2);
    }
        
    return; 
}

function member_topic_read_update ($tid, $check_time)
{
    global $time, $member_id, $logged, $DB;
    
    if (!$logged) return;
    
    $update = false;
    
    if ($member_id['view_topic'])
    {
        $views = unserialize($member_id['view_topic']);
        if (!is_array($views))
        {
            unset ($views);
            $views = array();
        }
        
        if (array_key_exists($tid, $views))
        {
            if ($views[$tid] < $check_time)
            {
                $update = true;
                $views[$tid] = $time;
            }
            elseif (($views[$tid] + 300) < $time)
            {
                $update = true;
                $views[$tid] = $time;
            }
        }
        else
        {
            $update = true;
            $views[$tid] = $time;
        }
        
        if (array_key_exists("all", $views))
        {
            if ($views["all"] >= $check_time)
                $update = false;
        }
    }
    else
    {
        $views = array();
        $update = true;
        $views[$tid] = $time;
    }
    
    if ($update)
    {
        $views = $DB->addslashes(serialize($views));
        $DB->not_filtred( "UPDATE LOW_PRIORITY ".DLE_USER_PREFIX."_users SET view_topic='{$views}' WHERE user_id='{$member_id['user_id']}'" );
    }
    
    return;
}

function member_topic_read ($tid, $check_time)
{
    global $time, $member_id, $logged;
    
    if (!$logged) return false;
    
    $update = false;
    
    if ($member_id['view_topic'])
    {
        $views = unserialize($member_id['view_topic']);
        if (!is_array($views))
            return false;
        
        if (array_key_exists("all", $views))
        {
            if ($views["all"] >= $check_time)
                return true;
        }
        
        if (array_key_exists($tid, $views))
        {
            if ($views[$tid] >= $check_time)
                return true;
        }
    }
    
    return false;
}

function get_dle_url($id)
{
    global $cache_dle_cat_info;
    	
	if(!$id) return;
	
	$parent_id = $cache_dle_cat_info[$id]['parentid'];
	
	$url = $cache_dle_cat_info[$id]['alt_name'];
	
	while ( $parent_id )
    {
		$url = $cache_dle_cat_info[$parent_id]['alt_name'] . "/" . $url;
		$parent_id = $cache_dle_cat_info[$parent_id]['parentid'];
        
		if( $cache_dle_cat_info[$parent_id]['parentid'] == $cache_dle_cat_info[$parent_id]['id'] ) break;
	}
	
	return $url;
}

function forum_find_alt_name ($name)
{
    global $cache_forums;
                
    foreach ($cache_forums as $value)
    {
        if ($value['alt_name'] == $name)
            return intval($value['id']);
    }
                
    return 0;
}

function topic_allforum ($id = 0, $all = false, $link = true)
{
    global $redirect_url, $cache_config, $cache_forums;
    
    if (!$all) return "<a href=\"".forum_link($id)."\">".$cache_forums[$id]['title']."</a>";
    
    $speedbar = main_forum($id);

    $speedbar = explode ("|", $speedbar);
    krsort($speedbar);
    reset($speedbar);
    if( count( $speedbar ) )
    {
        $link_speddbar = array();
                
        foreach ($speedbar as $link_forum)
        {
            if ($link)
                $link_speddbar[] = "<a href=\"".forum_link($link_forum)."\">".$cache_forums[$link_forum]['title']."</a>";
            else
                $link_speddbar[] = $cache_forums[$link_forum]['title'];
        }
    }
    else
        $link_speddbar[] = "<a href=\"".forum_link($id)."\">".$cache_forums[$id]['title']."</a>";
        
    return implode (" &raquo; ", $link_speddbar);
}

function forum_last_avatar ($fid)
{
    global $cache_forums, $DB;
    
    if ($cache_forums[$fid]['last_post_member_id']) // Если последний юзер зарегистрирован
    {
        $DB->prefix = DLE_USER_PREFIX;
        $favatar = $DB->one_select( "foto", "users", "user_id = '{$cache_forums[$fid]['last_post_member_id']}'" );
        $cache_forums[$fid]['avatar'] = $favatar['foto'];
    }
    else
        $cache_forums[$fid]['avatar'] = "";
}

function create_metatags()
{
	global $cache_group, $member_id;
    
    $metatags = array(
        "title" => "",
        "description" => "",
        "keywords" => ""
    );
    
    if (!intval($cache_group[$member_id['user_group']]['g_metatopic'])) return $metatags;
	
	$symbols = array ("\x22", "\x60", "\t", "\n", "\r", '"', '\r', '\n', "$", "{", "}", "[", "]", "<", ">");

	if(trim($_POST['meta_title']) != "")
    {
		$metatags['title'] = trim(htmlspecialchars(strip_tags(stripslashes($_POST['meta_title']))));
		$metatags['title'] = str_replace($symbols, "", $metatags['title']);
        $metatags['title'] = preg_replace("# +#", " ", $metatags['title']);
	}
	
	if(trim($_POST['meta_description']) != "")
    {
		$metatags['description'] = strip_tags(stripslashes($_POST['meta_description']));
		$metatags['description'] = str_replace($symbols, "", $metatags['description']);
        $metatags['description'] = preg_replace("# +#", " ", $metatags['description']);
	}
	
	if(trim($_POST['meta_keywords']) != "")
    {
        $metatags['keywords'] = str_replace($symbols, " ", strip_tags(stripslashes($_POST['meta_keywords'])));
        $metatags['keywords'] = preg_replace("# +#", " ", $metatags['keywords']);
	}
    
	return $metatags;
}

function developer_test ($mass, $stop = false)
{
    echo "<pre>";
    print_r($mass);
    echo "</pre>";
    
    if ($stop) exit();
}

$lang_g_function = language_forum ("board/global/function");

?>