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

$lang_m_search = language_forum ("board/modules/search");

$onl_location = $lang_m_search['location'];
$meta_info_other = $lang_m_search['location'];
$errors = array();

if (!$cache_group[$member_id['user_group']]['g_search'])
    message ($lang_message['access_denied'], str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_m_search['access_denied_group']), 1);
else
{               
    function sub_forums_list ($id = 0, $list = "", $list_check) // вычисляем ID форумов, которые вложенны в другие
    {
        global $cache_forums;
        foreach ($cache_forums as $subforum)
        {
            if ($subforum['parent_id'] == $id AND !in_array($subforum['id'], $list_check))
            {
                $list_check[] = $subforum['id'];
                $list .= "|".$subforum['id'];
                $list  = sub_forums_list($subforum['id'], $list, $list_check);
            }
        }
        return $list;
    }
        
    function sub_forums_search ($id = 0, $list = "") // вычисляем ID форумов
    {
        global $cache_forums;
	            
        $list_check = array();
                
        if (is_array($id))
        {
            foreach ($id as $fid)
            {
                if (!in_array($fid, $list_check))
                        $list_check[] = $fid;
            }
                
            foreach ($list_check as $fid)
            {                    
                if (!$list)
                    $list = $fid;
                else
                    $list .= "|".$fid;
                        
                $list = sub_forums_list($fid, $list, $list_check);
            }
        }
        else
        {
            $list = $id;
            $list = sub_forums_list($id, $list);
        }
            
        return $list;
    }
        
    function search_template($word = "", $author = "", $date_st = "", $date_end = "", $forum = 0, $no_subforum = 0, $topics_id = "", $type_search = 0, $preview = 0, $sort_result = 0, $sort_order = 0, $mod_search = 0, $member_name = "", $member_town = "", $sort_member_order = 0, $sort_member_result = 0, $member_group = 0)
    {
        global $tpl, $cache_config, $cache_group, $lang_m_search;
        
        $tpl->load_template( 'search.tpl' );
		$tpl->tags( '{word}', $word );
		$tpl->tags( '{author}', $author );
        $tpl->tags( '{date_st}', $date_st );
        $tpl->tags( '{date_end}', $date_end );
        $tpl->tags( '{topics_id}', $topics_id );
        
        if (!$no_subforum)
            $tpl->tags( '{no_subforum}', "" );
        else
            $tpl->tags( '{no_subforum}', "checked" );
            
        $tpl->tags( '{slimit}', $cache_config['general_searchword']['conf_value'] );
        
        if (!$forum)
            $tpl->tags( '{forums_list}', "<select name=\"f[]\" multiple style=\"height:200px;\"><option value=\"0\" selected>".$lang_m_search['allforums']."</option>".ForumsList()."</select>" );
        else
        {
            $forum = explode ("|", $forum);
            $tpl->tags( '{forums_list}', "<select name=\"f[]\" multiple><option value=\"0\">".$lang_m_search['allforums']."</option>".ForumsList($forum)."</select>" );
        }
        
        $f_type_search = array();
        $f_type_search[0] = $lang_m_search['type_topics_posts'];
        $f_type_search[1] = $lang_m_search['type_topics'];
        $f_type_search[2] = $lang_m_search['type_posts'];
        $tpl->tags('{type_search}', select_code("ts", $f_type_search, $type_search, false));
        
        $f_preview = array();
        $f_preview[0] = $lang_m_search['preview_topics'];
        $f_preview[1] = $lang_m_search['preview_posts'];
        $tpl->tags('{preview}', select_code("p", $f_preview, $preview, false));
        
        $f_sort_result = array();
        $f_sort_result[0] = $lang_m_search['sort_result_last_answer'];
        $f_sort_result[1] = $lang_m_search['sort_result_title'];
        $f_sort_result[2] = $lang_m_search['sort_result_num_aswers'];
        $f_sort_result[3] = $lang_m_search['sort_result_num_views'];
        $tpl->tags('{sort_result}', select_code("sr", $f_sort_result, $sort_result, false));
        
        $f_sort_order = array();
        $f_sort_order[0] = $lang_m_search['sort_order_DESC'];
        $f_sort_order[1] = $lang_m_search['sort_order_ASC'];
        $tpl->tags('{sort_order}', select_code("so", $f_sort_order, $sort_order, false));
        $tpl->tags('{sort_member_order}', select_code("smo", $f_sort_order, $sort_member_order, false));
        
        $tpl->tags_blocks("hide_table", $word);
            
        $f_mod_search = array();
        $f_mod_search[0] = $lang_m_search['mod_forum'];
        $f_mod_search[1] = $lang_m_search['mod_members'];
        $tpl->tags('{mod_search}', select_code("ms", $f_mod_search, $mod_search, false));
        
        $f_member_group = array();
        $f_member_group[0] = $lang_m_search['members_allgroups'];
        foreach($cache_group as $m_group)
        {
            $f_member_group[$m_group['g_id']] = $m_group['g_title'];
        }
        $tpl->tags('{member_group}', select_code("mg", $f_member_group, $member_group, false));
        
        $f_sort_member_result = array();
        $f_sort_member_result[0] = $lang_m_search['sort_result_m_name'];
        $f_sort_member_result[1] = $lang_m_search['sort_result_m_reg'];
        $f_sort_member_result[2] = $lang_m_search['sort_result_m_last'];
        $f_sort_member_result[3] = $lang_m_search['sort_result_m_posts'];
        $tpl->tags('{sort_member_result}', select_code("smr", $f_sort_member_result, $sort_member_result, false));
        
        $tpl->tags( '{member_town}', $member_town );
		$tpl->tags( '{member_name}', $member_name );
        
		$tpl->compile( 'content' );
		$tpl->clear();   
    }
        
    if (!isset($_REQUEST['w']))
    {       
        $link_speddbar = speedbar_forum (0, true)."|Поиск";
        search_template ();
    }
    elseif ($LB_flood->isBlock() AND isset($_POST['do_search']))
    {
        message ($lang_message['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_message['flood_control']));
    }
    else
    {           
        $link_speddbar = speedbar_forum (0, true)."|<a href=\"".link_on_module("search")."\">".$lang_m_search['location']."</a>|".$lang_m_search['location_result'];
        $_REQUEST['w'] = urldecode($_REQUEST['w']);
        
        filters_input ('request|get');
        
        $where = array();
        
        $mod_search = intval($_REQUEST['ms']);
        if ($mod_search < 0 OR $mod_search > 1)
            $mod_search = 0;
        
        $word = $DB->addslashes(htmlspecialchars(strip_data($_REQUEST['w'])));         
        $author = $DB->addslashes(htmlspecialchars(strip_data($_REQUEST['a'])));
        
        $word_out = stripslashes($word);
        $author_out = stripslashes($author);
        $goodquotes = array ("\-", "\+", "\#" );
        $repquotes = array ("-", "+", "#" );
        $word_out = str_replace( $goodquotes, $repquotes, $word_out );
        $author_out = str_replace( $goodquotes, $repquotes, $author_out );
        
        $date_st = $DB->addslashes(htmlspecialchars($_REQUEST['dst']));
        $date_st = strtotime($date_st);
        
        $date_end = $DB->addslashes(htmlspecialchars($_REQUEST['dend']));
        $date_end = strtotime($date_end);
        
        if (!$mod_search) // поиск по форуму
        {        
            if (utf8_strlen($word) < intval($cache_config['general_searchword']['conf_value']))
                $errors[] = str_replace("{num}", intval($cache_config['general_searchword']['conf_value']), $lang_m_search['word_len']);
            
            if (is_array($_REQUEST['f']))
                $forum_mass = $_REQUEST['f'];
            else
                $forum_mass = explode("|", $_REQUEST['f']);
            
            $forum = array();
            foreach ($forum_mass as $fm)
            {
                $fm = intval($fm);
            
                if ($fm == 0)
                {
                    unset($forum);
                    $forum[] = 0;
                    break;
                }
            
                if (!$cache_forums[$fm]['id'] AND $fm != 0)
                    $errors[] = $lang_m_search['no_forum_id'];
                else
                {
                    if((!forum_permission($fm, "read_forum") OR !forum_permission($fm, "read_theme")) AND $fm != 0)
                        $errors[] = $lang_m_search['access_denied_forum'];
                    else
                        $forum[] = $fm;
                }
            }
        
            $forum_url = implode("|", $forum);
        
            $no_subforum = intval($_REQUEST['sf']);
        
            $type_search = intval($_REQUEST['ts']);
            if ($type_search < 0 OR $type_search > 2)
                $type_search = 0;
        
            $preview = intval($_REQUEST['p']);
            if ($preview < 0 OR $preview > 1)
                $preview = 0;
            
            $sort_result = intval($_REQUEST['sr']);
            if ($sort_result < 0 OR $sort_result > 3)
                $sort_result = 0;
            
            $sort_order = intval($_REQUEST['so']);
            if ($sort_order < 0 OR $sort_order > 1)
                $sort_order = 0;
            
            $topics_id_check = $_REQUEST['t_id'];
            if ($topics_id_check)
            {
                $topics_id_arr = explode (",", $topics_id_check);
                $topics_id = array();
                foreach ($topics_id_arr as $value)
                {
                    $value = intval($value);
                    if ($value) $topics_id[] = $value;
                }
                $topics_id = implode (",", $topics_id);
                
                if ($topics_id) // Если указаны темы - очистить фильтр по форумам
                {
                    $no_subforum = 0;
                    $forum_url = "";
                    unset($forum);
                    $forum = array();
                }
            }
            else
                $topics_id = "";
            
            $op = "board"; // поиск только по форуму
        
            search_template($word_out, $author, $date_st, $date_end, $forum_url, $no_subforum, $topics_id, $type_search, $preview, $sort_result, $sort_order, $mod_search);
            
            if ($sort_order)
                $sort = "ASC";
            else
                $sort = "DESC";
        }
        else
        {
            $member_group = intval($_REQUEST['mg']);
            if ($member_group != 0)
            {
                $group_find = false;
                foreach($cache_group as $m_group)
                {
                    if ($member_group == $m_group['g_id'])
                    {
                        $group_find = true;
                        break;
                    }
                }
        
                if (!$group_find)
                    $errors[] = $lang_m_search['no_group_id'];
            }
                
            $member_town = $DB->addslashes(htmlspecialchars(strip_data($_REQUEST['mt'])));
            $member_name = $DB->addslashes(htmlspecialchars(strip_data($_REQUEST['mn'])));
            
            $sort_member_result = intval($_REQUEST['smr']);
            if ($sort_member_result < 0 OR $sort_member_result > 3)
                $sort_member_result = 0;
            
            $sort_member_order = intval($_REQUEST['smo']);
            if ($sort_member_order < 0 OR $sort_member_order > 1)
                $sort_member_order = 0;
        
            search_template($word_out, "", $date_st, $date_end, 0, 0, "", 0, 0, 0, 0, $mod_search, $member_name, $member_town, $sort_member_order, $sort_member_result, $member_group);
            
            if ($sort_member_order)
                $sort = "ASC";
            else
                $sort = "DESC";
        }
        
        if(!$errors[0] AND !$mod_search)
        {
            $link_nav = navigation_link("search", "", 0, "ms=".$mod_search."&w=".urlencode($word_out)."&a=".urlencode($author)."&dst=".$date_st."&dend=".$date_end."&f=".$forum_url."&sf=".$no_subforum."&t_id=".$topics_id."&ts=".$type_search."&p=".$preview."&sr=".$sort_result."&so=".$sort_order);
            
            if ($preview)
                $max_in_page = $cache_config['topic_post_page']['conf_value'];
            else
                $max_in_page = $cache_config['topic_page']['conf_value'];
            
            if (isset ( $_REQUEST['page'] ))
                $page = intval ( $_GET['page'] );
            else
                $page = 0;

            if ($page < 0)
                $page = 0;
                
            if (isset($_POST['do_search']))
                $page = 0;

            if ($page)
            {
                $page = $page - 1;
                $page = $page * $max_in_page;
            }
    
            $i = $page;
            
            require LB_CLASS . '/sql_search.php';
            $sql_search = new SQL_Search;
        
            if ($topics_id)
            {
                $where[] = "t.id regexp '[[:<:]](".str_replace(",", "|", $topics_id).")[[:>:]]' ";
            }
        
            if ($type_search == 2) // поиск в постах
            {
                $where[] = $sql_search->simple ("p.text", $word);
                if ($author) $where[] = $sql_search->simple ("p.post_member_name", $author);
                if ($date_st) $where[] = "p.post_date >= '".$date_st."'";
                if ($date_end) $where[] = "p.post_date <= '".$date_end."'";
            
                if ($forum[0])
                {
                    if (!$no_subforum)
                        $forum = sub_forums_search($forum);
                    else
                        $forum = $forum_url;
                    
                    $where[] = "t.forum_id regexp '[[:<:]](".$forum.")[[:>:]]' ";
                }
                
                $access_forums = array();
                foreach ($cache_forums as $cf)
                {
                    if(forum_permission($cf['id'], "read_forum"))
                    {
                        if (forum_all_password($cf['id']))
                            $access_forums[] = $cf['id'];
                    }
                    else
                        $access_forums[] = $cf['id'];
                }    
                
                $access_forums = implode (",", $access_forums);
                
                if ($access_forums) $where[] = "forum_id NOT IN (".$access_forums.")";
                if (!forum_options_topics(0, "allpermission")) $where[] = "t.hiden = '0' AND p.hide = '0'";            
            
                $where_db = implode(" AND ", $where);
            
                if ($sort_result == 1) $order = "t.title";
                elseif ($sort_result == 2) $order = "t.post_num";
                elseif ($sort_result == 3) $order = "t.views";
                else $order = "p.post_date";
                
                if ($preview)
                {
                    include LB_CLASS.'/posts_out.php';
                    $LB_posts = new LB_posts;
                    
                    $DB->prefix = array( 2 => DLE_USER_PREFIX );
                    $LB_posts->query = $DB->join_select( "SQL_NO_CACHE p.*, u.name, u.user_id, u.user_group, u.foto, u.banned, u.signature, u.posts_num, u.topics_num, mo.mo_id, mo.mo_date, t.forum_id, t.hiden, t.title, t.status, t.member_id_open", "LEFT", "posts p||topics t||users u||members_online mo", "p.topic_id=t.id||p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", $where_db, "ORDER by p.utility DESC, ".$order." ".$sort." LIMIT ".$page.", ".$max_in_page );
                    
                    $nav = $DB->one_join_select( "SQL_NO_CACHE COUNT(*) as count", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db);
                    $nav_all = $nav['count'];
                    $DB->free($nav);
                }
                else
                {
                    include LB_CLASS.'/topics_out.php';
                    $LB_topics = new LB_topics;
                    $LB_topics->query = $DB->join_select( "SQL_NO_CACHE p.text, p.hide, t.*", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db, "GROUP BY p.topic_id ORDER by p.utility DESC, ".$order." ".$sort." LIMIT ".$page.", ".$max_in_page );
                    
                    $nav = $DB->one_join_select( "SQL_NO_CACHE COUNT(DISTINCT p.topic_id) as count", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db);
                    $nav_all = $nav['count'];
                    $DB->free($nav);
                }
            }
            elseif ($type_search == 1) // поиск в заголовках
            {
                $preview = 0;
                
                $where[] = "(".$sql_search->simple ("title", $word)." OR ".$sql_search->simple ("description", $word).")";
                if ($author)
                    $where[] = $sql_search->simple ("member_name_open", $author);
                
                if ($date_st)
                    $where[] = "date_last >= '".$date_st."'";
            
                if ($date_end)
                    $where[] = "date_last <= '".$date_end."'";
            
                if ($forum[0])
                {
                    if (!$no_subforum)
                        $forum = sub_forums_search($forum);
                    else
                        $forum = $forum_url;
                    
                    $where[] = "forum_id regexp '[[:<:]](".$forum.")[[:>:]]' ";
                }
            
                $access_forums = array();
                foreach ($cache_forums as $cf)
                {
                    if(forum_permission($cf['id'], "read_forum"))
                    {
                        if (forum_all_password($cf['id']))
                            $access_forums[] = $cf['id'];
                    }
                    else
                        $access_forums[] = $cf['id'];
                }    
                
                $access_forums = implode (",", $access_forums);
                
                if ($access_forums)
                    $where[] = "forum_id NOT IN (".$access_forums.")";
                
                if (!forum_options_topics(0, "allpermission"))
                    $where[] = "hiden = '0'"; 
            
                $where_db = implode(" AND ", $where);
            
                if ($sort_result == 1)
                    $order = "title";
                elseif ($sort_result == 2)
                    $order = "post_num";
                elseif ($sort_result == 3)
                    $order = "views";
                else
                    $order = "date_last";

                include LB_CLASS.'/topics_out.php';
                $LB_topics = new LB_topics;
                $LB_topics->query = $DB->select( "SQL_NO_CACHE *", "topics", $where_db, "ORDER by ".$order." ".$sort." LIMIT ".$page.", ".$max_in_page  );
                
                $nav = $DB->one_select( "SQL_NO_CACHE COUNT(*) as count", "topics", $where_db);
                $nav_all = $nav['count'];
                $DB->free($nav);
            }
            else
            {
                $where[] = "(".$sql_search->simple ("t.title", $word)." OR ".$sql_search->simple ("t.description", $word)." OR ".$sql_search->simple ("p.text", $word).")";
                if ($author)
                    $where[] = "(".$sql_search->simple ("t.member_name_open", $author)." OR ".$sql_search->simple ("p.post_member_name", $author).")";
                
                if ($date_st)
                    $where[] = "p.post_date >= '".$date_st."'";
            
                if ($date_end)
                    $where[] = "p.post_date <= '".$date_end."'";
            
                if ($forum[0])
                {
                    if (!$no_subforum)
                        $forum = sub_forums_search($forum);
                    else
                        $forum = $forum_url;
                    
                    $where[] = "t.forum_id regexp '[[:<:]](".$forum.")[[:>:]]' ";
                }
                
                $access_forums = array();
                foreach ($cache_forums as $cf)
                {
                    if(forum_permission($cf['id'], "read_forum"))
                    {
                        if (forum_all_password($cf['id']))
                            $access_forums[] = $cf['id'];
                    }
                    else
                        $access_forums[] = $cf['id'];
                }    
                
                $access_forums = implode (",", $access_forums);
                
                if ($access_forums)
                    $where[] = "forum_id NOT IN (".$access_forums.")";
                
                if (!forum_options_topics(0, "allpermission"))
                    $where[] = "t.hiden = '0' AND p.hide = '0'";
            
                $where_db = implode(" AND ", $where);
            
                if ($sort_result == 1)
                    $order = "t.title";
                elseif ($sort_result == 2)
                    $order = "t.post_num";
                elseif ($sort_result == 3)
                    $order = "t.views";
                else
                    $order = "p.post_date";
                    
                if ($preview)
                {
                    include LB_CLASS.'/posts_out.php';
                    $LB_posts = new LB_posts;
                    $DB->prefix = array( 2 => DLE_USER_PREFIX );
                    $LB_posts->query = $DB->join_select( "SQL_NO_CACHE p.*, u.name, u.user_id, u.user_group, u.foto, u.signature, u.banned, u.posts_num, u.topics_num, mo.mo_id, mo.mo_date, t.forum_id, t.hiden, t.title, t.status, t.member_id_open", "LEFT", "posts p||topics t||users u||members_online mo", "p.topic_id=t.id||p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", $where_db, "ORDER by p.utility DESC, ".$order." ".$sort." LIMIT ".$page.", ".$max_in_page );
                    
                    $nav = $DB->one_join_select( "SQL_NO_CACHE COUNT(*) as count", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db);
                    $nav_all = $nav['count'];
                    $DB->free($nav);
                }
                else
                {
                    include LB_CLASS.'/topics_out.php';
                    $LB_topics = new LB_topics;
                    $LB_topics->query = $DB->join_select( "SQL_NO_CACHE p.text, p.hide, t.*", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db, "GROUP BY p.topic_id ORDER by p.utility DESC, ".$order." ".$sort." LIMIT ".$page.", ".$max_in_page );
                    
                    $nav = $DB->one_join_select( "SQL_NO_CACHE COUNT(DISTINCT p.topic_id) as count", "LEFT", "posts p||topics t", "p.topic_id=t.id", $where_db);
                    $nav_all = $nav['count'];
                    $DB->free($nav);
                }
            }
            
            unset($sql_search);
            
            if (!$nav_all)
                message ($lang_message['information'], $lang_m_search['empty']);
            else
            {
                if (!$preview)
                {
                    $LB_topics->Data_out("board/topic_all.tpl", "topics", true);
    
                    unset($LB_topics);
                                        
                    $script_word = "
                    <script type=\"text/javascript\">
                    $(window).load(function(){
                        $(\".ft_topic_name h6\").highlight('".stripslashes($word)."');
                    });
                    </script>";

                    if ($nav_all > $max_in_page)
                    {
                        include_once LB_CLASS.'/navigation_board.php';
                        $navigation = new navigation;
                        $navigation->create($page, $nav_all, $max_in_page, $link_nav, "7");
                        $navigation->template();
                        unset($navigation);
                    }
                    else
                        $tpl->result['navigation'] = "";
    
                    $tpl->load_template ( 'board/topic_global.tpl' );

                    $tpl->tags('{category_name}', $lang_m_search['result'].$nav_all);

                    if (forum_options_topics(0))
                    {
                        $tpl->tags('{forum_options}', forum_options(0));
                        $tpl->tags('{forum_options_topics}', forum_options_topics(0));
                        $tpl->tags_blocks("moder_line");
                    }
                    else
                        $tpl->tags_blocks("moder_line", false);
        
                    $tpl->tags_blocks("topics_out", false);
  
                    $tpl->tags_templ('{topics}', $tpl->result['topics'].$script_word);
                    $tpl->tags_templ('{pages}', $tpl->result['navigation']);

                    $tpl->compile('content');
                    $tpl->clear();
                }
                else
                {
                    $LB_posts->Data_out("board/topic_posts.tpl", "posts", "", true, true, false, true);
    
                    unset($LB_posts);
                    
                    $script_word = "
                    <script type=\"text/javascript\">
                    $(window).load(function(){
                        $(\"[id^=post-id-]\").highlight('".stripslashes($word)."');
                    });
                    </script>";
                            
                    if ($nav_all > $max_in_page)
                    {
                        include_once LB_CLASS.'/navigation_board.php';
                        $navigation = new navigation;
                        $navigation->create($page, $nav_all, $max_in_page, $link_nav, "7");
                        $navigation->template();
                        unset($navigation);
                    }
                    else
                        $tpl->result['navigation'] = "";
  
                    $tpl->load_template ( 'board/topic_posts_global.tpl' );    
                    $tpl->tags('{topic_title}', $lang_m_search['result'].$nav_all);
                    $tpl->tags_templ('{posts}', $tpl->result['posts'].$script_word);
                    $tpl->tags('{fast_forum}', "");
                    $tpl->tags('{poll}', "");
    
                    if(forum_options_topics_mas(0, 0, "check"))
                    {
                        $tpl->tags_blocks("moder");
                        $tpl->tags_blocks("author_topic", false);
                        $tpl->tags('{moder_comm}', forum_options_topics_mas(0, 0, "posts"));
                        $tpl->tags('{moder_topic}', "");
                    }
                    else
                    {
                        $tpl->tags_blocks("moder", false);
                        $tpl->tags_blocks("author_topic", false);
                    }
    
                    $tpl->tags_blocks("posts_out", false);
                    $tpl->tags_blocks("share_links", false);
    
                    $tpl->tags('{form}', "");
                    $tpl->tags('{posts_fixed}', "");
                    $tpl->tags_templ('{pages}', $tpl->result['navigation']);
                    $tpl->compile('content');
                    $tpl->clear();
                }
            }        
        } 
        elseif(!$errors[0] AND $mod_search)
        {
            $link_nav = navigation_link("search", "", 0, "ms=".$mod_search."&w=".urlencode($word)."&a=".urlencode($author)."&dst=".$date_st."&dend=".$date_end."&mg=".$member_group."&mt=".$member_town."&mn=".$member_name."&smr=".$sort_member_result."&smo=".$sort_member_order);
            
            $max_in_page = $cache_config['member_page']['conf_value'];
            
            if (isset ( $_REQUEST['page'] ))
                $page = intval ( $_GET['page'] );
            else
                $page = 0;

            if ($page < 0)
                $page = 0;
                
            if (isset($_POST['do_search']))
                $page = 0;

            if ($page)
            {
                $page = $page - 1;
                $page = $page * $max_in_page;
            }
    
            $i = $page;  
            
            require LB_CLASS . '/sql_search.php';
            $sql_search = new SQL_Search;
            
            if ($word)
                $where[] = $sql_search->simple ("u.name", $word);
                                         
            if ($date_st)
                $where[] = "u.reg_date >= '".$date_st."'";
            
            if ($date_end)
                $where[] = "u.reg_date <= '".$date_end."'";
                
            if ($member_group)
                $where[] = "u.user_group = '".$member_group."'";
                
            if ($member_town)
                $where[] = $sql_search->simple ("u.fullname", $member_town);
                
            if ($member_name)
                $where[] = $sql_search->simple ("u.fullname", $member_name);
                
            unset($sql_search);
                
            if ($sort_member_result == 1)
                $order = "u.reg_date";
            elseif ($sort_member_result == 2)
                $order = "u.lastdate";
            elseif ($sort_member_result == 3)
                $order = "u.posts_num";
            else
                $order = "u.name";
                
            if (!$where[0])
                message ($lang_message['error'], $lang_m_search['no_data_members']);     
            else
            {
                $where_db = implode(" AND ", $where);
                        
                $DB->prefix = array( 0 => DLE_USER_PREFIX );
                $DB->join_select( "SQL_NO_CACHE u.name, u.user_group, u.banned, u.foto, u.posts_num, u.user_id, u.reg_date, u.lastdate, u.personal_title, u.topics_num, u.posts_num, mo.mo_id, mo.mo_date", "LEFT", "users u||members_online mo", "u.user_id=mo.mo_member_id", $where_db, "ORDER by ".$order." ".$sort." LIMIT ".$page.", ".$max_in_page );
       
                $tpl->load_template ( 'users/users_all.tpl' );

                while ( $row = $DB->get_row() )
                {
                    $i ++;

                    $tpl->tags('{member_name}', $row['name']);
                    $tpl->tags('{member_group}', member_group($row['user_group'], $row['banned']));           
                    $tpl->tags_blocks("online", member_online($row['mo_id'], $row['mo_date'], $onl_limit));
                    $tpl->tags_blocks("offline", member_online($row['mo_id'], $row['mo_date'], $onl_limit), true);
                    $tpl->tags('{member_avatar}', member_avatar($row['foto']));
    
                    $tpl->tags('{member_posts}', $row['posts_num']);
                    $tpl->tags('{member_id}', $row['user_id']);
                    $tpl->tags('{reg_date}', formatdate($row['reg_date']));
                    $tpl->tags('{lastdate}', formatdate($row['lastdate']));
                    $tpl->tags('{personal_title}', $row['personal_title']);
    
                    $tpl->tags('{profile_link}', profile_link($row['name'], $row['user_id']));
                    $tpl->tags('{pm_link}', pm_member($row['name'], $row['user_id']));
                    $tpl->tags('{topics_link}', member_topics_link($row['name'], $row['user_id']));
                    $tpl->tags('{posts_link}', member_posts_link($row['name'], $row['user_id']));
                    
                    $tpl->tags('{topics_num}', $row['topics_num']);
                    $tpl->tags('{posts_num}', $row['posts_num']);
 
                    $tpl->compile('all');
                }
                $DB->free();
                $tpl->clear();
    
                $DB->prefix = DLE_USER_PREFIX;
                $nav = $DB->one_select( "SQL_NO_CACHE COUNT(*) as count", "users u", $where_db);
                $nav_all = $nav['count'];
                $DB->free($nav);

                if ($i)
                {
                    if ($nav_all > $max_in_page)
                    {
                        include_once LB_CLASS.'/navigation_board.php';
                        $navigation = new navigation;
                        $navigation->create($page, $nav_all, $max_in_page, $link_nav, "5");
                        $navigation->template();
                        unset($navigation);
                    }
                    else
                        $tpl->result['navigation'] = "";
    
                    $tpl->load_template ( 'users/users_all_global.tpl' );
                    $tpl->tags('{title}', $lang_m_search['result'].$nav_all);
                    $tpl->tags_templ('{users}', $tpl->result['all']);
                    $tpl->tags_templ('{pages}', $tpl->result['navigation']);
                    $tpl->compile('content');
                    $tpl->clear();
                }
                else
                    message ($lang_message['information'], $lang_m_search['empty']);
            }
        }
        else
            message ($lang_message['error'], $errors);          
    }   
}

?>