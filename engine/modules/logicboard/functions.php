<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

function LB_select_code($name = "", $massive, $selected = "", $lbselect = true)
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

function LB_utf8_strlen($word, $charset = "cp1251")
{
    if (strtolower($charset) == "utf-8")
        return mb_strlen($word, "utf-8");
	else
        return strlen($word);
}

function LB_utf8_substr($s, $offset, $len, $charset = "cp1251")
{
    if (strtolower($charset) == "utf-8")
        return mb_substr($s, $offset, $len, "utf-8");
    else
        return substr($s, $offset, $len);
}

function LB_formatdate($date)
{
    global $config;
    
    $time = time() + ($config['date_adjust'] * 60);
    
	if( date( "Ymd", $date ) == date( "Ymd", $time ) )
		$when = "Сегодня, " . date( "H:i", $date );
	elseif( date( "Ymd", $date ) == date( "Ymd", ($time - 86400) ) )
		$when = "Вчера, " . date( "H:i", $date );
	else
		$when = date( "H:i, d.m.Y", $date );

	return $when;
}

function LB_topic_link ($id = 0, $fid = 0, $last = false, $hide = false)
{
    global $logicboard_conf, $cache_lb_config;
            
    $redirect_url = $logicboard_conf['url'];
    
    if ($last)
    {
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = LB_forum_link($fid)."last/topic-".$id.".html";
        else
            $link = $redirect_url."?do=board&amp;op=topic&amp;id=".$id."&amp;go=last";
    }
    elseif ($hide)
    {
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = LB_forum_link($fid)."hiden/topic-".$id.".html";
        else
            $link = $redirect_url."?do=board&amp;op=topic&amp;id=".$id."&amp;go=hide";
    }
    else
    {
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = LB_forum_link($fid)."topic-".$id.".html";
        else
            $link = $redirect_url."?do=board&amp;op=topic&amp;id=".$id;
    }
        
    return $link;
}

function LB_profile_link ($name = "", $id = 0, $link = false)
{
    global $config, $user_group, $member_id;
    
    if( $config['allow_alt_url'] == "yes" )
        $go_page = $config['http_home_url'] . "user/" . urlencode( $name ) . "/";
	else
		$go_page = $PHP_SELF."?subaction=userinfo&amp;user=" . urlencode( $name );
        
    if ($link) return $go_page;

    if ($config['version_id'] >= "9.0")
    {
        $onclick = "onclick=\"ShowProfile('" . urlencode( $name ) . "', '" . $go_page . "'); return false;\"";
    }
    else
    {
        if( $config['ajax'] )
            $news_page = "onclick=\"DlePage(\'subaction=allnews&user=" . urlencode( $row['autor'] ) . "\'); return false;\" ";
		else
			$news_page = "";
            
        if( $config['allow_alt_url'] == "yes" )
            $news_page .= "href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['autor'] ) . "/news/\"";
        else
            $news_page .= "href=\"$PHP_SELF?subaction=allnews&amp;user=" . urlencode( $row['autor'] ) . "\"";

        
        $onclick = "onclick=\"return dropdownmenu(this, event, UserNewsMenu('".htmlspecialchars( "href=\"".$go_page."\"" )."', '".htmlspecialchars($news_page)."','".urlencode($name)."', '" . $user_group[$member_id['user_group']]['admin_editusers'] . "'), '170px')\" onmouseout=\"delayhidemenu()\"";
    }

    $link = "<a ".$onclick." href=\"".$go_page."\">".$name."</a>";

    return $link;
}

function LB_forum_link ($id = 0)
{
    global $logicboard_conf, $cache_lb_config, $cache_forums;
    
    if ($cache_lb_config['general_rewrite_url']['conf_value'])
        $link = $logicboard_conf['url']."cat-".$cache_forums[$id]['alt_name']."/";
    else
        $link = $logicboard_conf['url']."?do=board&amp;op=forum&amp;id=".$id;

    return $link;
}
    
function LB_speedbar_forum ($id = 0)
{
    global $cache_forums;
    
    $speedbar = LB_main_forum($id);
    if($speedbar)
    {
	   $speedbar = explode ("|", $speedbar);
	   sort($speedbar);
	   reset($speedbar);
	   if( count( $speedbar ) )
	   {
		  $link_speddbar = array();
		  foreach ($speedbar as $link_forum)
		  {
			 if ($id == $link_forum)
			 {
                $link_speddbar[] = "<a href=\"".LB_forum_link($link_forum)."\">".$cache_forums[$link_forum]['title']."</a>";
			 }
			 else
			 {
				$link_speddbar[] = "<a href=\"".LB_forum_link($link_forum)."\">".$cache_forums[$link_forum]['title']."</a>";
			 }
		  }
          $link_speddbar = implode (" &raquo; ", $link_speddbar);
	   }
       else
            $link_speddbar = "Форум";
    }
    else
        $link_speddbar = "Форум";
        
    return $link_speddbar;
}

function LB_main_forum ($id = 0, $list = "")
{
	global $cache_forums;
	
    if($id)
    {
	   if ($list == "")
		  $list = $id;
	   else
		  $list .= "|".$id;

	   if ($cache_forums[$id]['parent_id'] != 0 )
		  $list = LB_main_forum($cache_forums[$id]['parent_id'], $list);
    }
    else
        return;

	return $list;
}

function LB_forum_permission ($id = 0, $perm = "") // права на форумы, настройки форума (не модерация)
{
    global $cache_forums, $member_id;

    if (!$id OR $perm == "")
        return false;
  
    if ($perm == "read_forum")
    {
        $id_mass = LB_main_forum($id);
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

function LB_forum_password ($id = 0)
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
        $notuse = explode("|", $cache_forums[$id]['password_notuse']);
        if (in_array($member_id['user_group'], $notuse))
            return false;
    }
    
    if ($cache_forums[$id]['password'] != "")
        return true;
    else
        return false;
}

function LB_forum_all_password ($id = 0)
{   
    $id_forum_pass = 0;
    $id_f_pass = LB_main_forum ($id);
    $id_f_pass = explode ("|", $id_f_pass);
    array_pop($id_f_pass); // вырезаем категорию из массива
    sort($id_f_pass); // сортировка массива, переворачиваем, начиная от выбранного форума и заканчивая главным форумом (не категории)
    reset($id_f_pass);
    if( count( $id_f_pass ) )
    {
        foreach ($id_f_pass as $idd_f)
        {          
            if(LB_forum_password($idd_f))
            {
                return $idd_f;
            }    
        }
    }
    
    return false;
}

function LB_member_options_default ($options)
{         
    if (!count($options))
        return "";
   
    if (!isset($options['pmtoemail'])) $options['pmtoemail'] = 1;
    if (!isset($options['subscribe'])) $options['subscribe'] = 1;
    if (!isset($options['online'])) $options['online'] = 0;
        
    return $options;
}

function LB_warning_link ($name = "", $id = 0, $type = 0) // ссылка на предупреждения (история и добавление)
{
    global $logicboard_conf, $cache_lb_config;
    
    if ($cc)
        $link = $cache_lb_config['general_site']['conf_value'];
    else
        $link = $logicboard_conf['url'];
    
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

function LB_member_online ($id = 0, $date = 0, $limit = 0)
{    
    if ($id AND $date >= $limit)
        return true;
    else
        return false;
}

function LB_member_posts_link ($name = "", $id = 0) // ссылка на все сообщения определённого пользователя
{
    global $logicboard_conf, $cache_lb_config;
    
    if ($cache_lb_config['general_rewrite_url']['conf_value'])
        $link = $logicboard_conf['url']."all_posts/".urlencode($name)."/";
    else
        $link = $logicboard_conf['url']."?do=users&op=posts&member_name=".urlencode($name);
        
    return $link;
}

function LB_member_topics_link ($name = "", $id = 0) // ссылка на все темы определённого пользователя
{
    global $logicboard_conf, $cache_lb_config;
    
    if ($cache_lb_config['general_rewrite_url']['conf_value'])
        $link = $logicboard_conf['url']."all_topics/".urlencode($name)."/";
    else
        $link = $logicboard_conf['url']."?do=users&op=topics&member_name=".urlencode($name);
        
    return $link;
}

function LB_profile_edit_link ($name = "", $act = "status") // ссылка на редактирвоание опций профиля (настройки форума, личный статус)
{
    global $cache_lb_config, $logicboard_conf;
    
    if ($act == "options")
    {
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = $logicboard_conf['url']."options/".urlencode($name)."/";
        else
            $link = $logicboard_conf['url']."?do=users&op=options&member_name=".urlencode($name);
    }
    elseif ($act == "status")
    {
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = $logicboard_conf['url']."edit_status/".urlencode($name)."/";
        else
            $link = $logicboard_conf['url']."?do=users&op=edit_status&member_name=".urlencode($name);
    }

    return $link;
}

function LB_member_favorite () // ссылка на избранное
{
    global $logicboard_conf, $cache_lb_config;
    
    if ($cache_lb_config['general_rewrite_url']['conf_value'])
        $link = $logicboard_conf['url']."favorite/";
    else
        $link = $logicboard_conf['url']."?do=users&op=favorite";
    
    return $link;
}

function LB_member_subscribe () // ссылка на подписку пользователя
{
    global $logicboard_conf, $cache_lb_config;
    
    if ($cache_lb_config['general_rewrite_url']['conf_value'])
        $link = $logicboard_conf['url']."subscribe/";
    else
        $link = $logicboard_conf['url']."?do=users&op=subscribe";
    
    return $link;
}

function LB_online_link_list ($mod = "", $page = false) // ссылка на вывод списка онлайн (по действию и по имени)
{
    global $logicboard_conf, $cache_lb_config;
                    
    if ($mod == "action")
    {            
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = $logicboard_conf['url']."online/action/";
        else
            $link = $logicboard_conf['url']."?do=users&op=online&order=action";
    }
    elseif ($mod == "name")
    {            
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = $logicboard_conf['url']."online/name/";
        else
            $link = $logicboard_conf['url']."?do=users&op=online&order=name";
    }
    else
    {            
        if ($cache_lb_config['general_rewrite_url']['conf_value'])
            $link = $logicboard_conf['url']."online/";
        else
            $link = $logicboard_conf['url']."?do=users&op=online";
    }
    
    return $link;
}

?>