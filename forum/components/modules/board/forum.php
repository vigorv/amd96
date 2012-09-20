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
	@include '../../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$_SESSION['back_link_board'] = $_SERVER['REQUEST_URI'];

$lang_m_b_forum = language_forum ("board/modules/board/forum");

function ShowForums_Sub($id = 0) 
{
	global $cache_config, $cache_forums, $redirect_url, $tpl;

	$forum_title = $cache_forums[$id]['title'];
    $link = forum_link($id);
    
    $size = intval(100/intval($cache_config['forums_subforums']['conf_value']));

$returnstring = <<<HTML

<td style="width:{$size}%;"><a href="{$link}">{$forum_title}</a></td>
HTML;

	return $returnstring;
}

function Build_SubForums_2($id = 0, $f_id = 0, $sub)
{
	global $cache_forums, $redirect_url, $secret_key, $tpl, $cache_config, $cache_group, $cache_forums_moder, $lang_m_b_forum;

	$build = "";

    $first = true;
    foreach ($sub as $sub_forum)
    {
        $sid = $sub_forum;
        if ($cache_forums[$sid]['parent_id'] == $id AND $id != $sid AND forum_permission($sid, "read_forum"))
        {                    
            $forum_title = $cache_forums[$sid]['title'];
            $tpl->tags_blocks("forum");        
            
            $tpl->tags('{forum_name}', $forum_title);
            $tpl->tags('{forum_link}', forum_link($sid));
            
            if ($cache_forums[$sid]['description'])
                $tpl->tags('{forum_desc}', "<p>".$cache_forums[$sid]['description']."</p>");
            else
                $tpl->tags('{forum_desc}', "");
                                       
            if ($cache_forums[$sid]['ficon'])
            {
                $forum_icon = explode ("|", $cache_forums[$sid]['ficon']);
            }
            
            if (cookie_forums_read($sid))
            {
                if ($cache_forums[$sid]['ficon'])
                    $tpl->tags('{forum_img}', $forum_icon[1]);
                else
                    $tpl->tags('{forum_img}', "ico_forum_read.png");
                    
                $tpl->tags('{forum_status}', $lang_m_b_forum['forum_read']);
            }
            else
            {
                if ($cache_forums[$sid]['ficon'])
                    $tpl->tags('{forum_img}', $forum_icon[0]);
                else
                    $tpl->tags('{forum_img}', "ico_forum_unread.png");
                                    
                $tpl->tags('{forum_status}', $lang_m_b_forum['forum_unread']);
            }
            
            if ($cache_forums[$sid]['flink'])
            {
                $tpl->tags_blocks("flink_page");
                $tpl->tags_blocks("flink_npage", intval($cache_forums[$id]['flink_npage']));
                $tpl->tags_blocks("not_flink_page", false);
                $tpl->tags('{count_view}', $cache_forums[$sid]['posts']);
                $last_id = 0;
            }   
            else
            {
                $tpl->tags_blocks("flink_page", false);
                $tpl->tags_blocks("not_flink_page");
                
                $last_mass = check_last_forum ($sid);
                $last_mass = explode("|", $last_mass);
                list($last_id, $count_posts, $count_topics, $posts_hiden, $topics_hiden) = $last_mass;
            
                if(forum_options_topics($sid, "hideshow") AND ($posts_hiden OR $topics_hiden))
                {
                    $tpl->tags('{posts_hiden}', $posts_hiden);
                    $tpl->tags('{topics_hiden}', $topics_hiden);
                    $tpl->tags_blocks("hiden");
                }
                else
                    $tpl->tags_blocks("hiden", false);
                    
                $tpl->tags('{last_post_member_avatar}', member_avatar($cache_forums[$last_id]['avatar']));
                
                if (intval($cache_config['forums_moder_list']['conf_value']))
                {
                    $forum_moders = array();
                    if (count($cache_forums_moder))
                    {
                        foreach ($cache_forums_moder as $moder_list)
                        {
                            if ($moder_list['fm_forum_id'] == $sid)
                            {
                                if ($moder_list['fm_member_id'])
                                    $forum_moders[] = "<a href=\"".profile_link($moder_list['fm_member_name'], $moder_list['fm_member_id'])."\">".$moder_list['fm_member_name']."</a>";
                                else
                                    $forum_moders[] = $cache_group[$moder_list['fm_group_id']]['g_prefix_st'].$cache_group[$moder_list['fm_group_id']]['g_title'].$cache_group[$moder_list['fm_group_id']]['g_prefix_end'];
                            }
                        }
                    }
                                    
                    if (count($forum_moders))
                    {
                        $tpl->tags_blocks("forum_moders");
                        $tpl->tags('{forum_moders}', implode(", ", $forum_moders));
                    }
                    else
                        $tpl->tags_blocks("forum_moders", false);
                }
                else
                {
                    $tpl->tags_blocks("forum_moders", false);
                }
                
                $tpl->tags('{forum_topics}', $count_topics);
                $tpl->tags('{forum_posts}', $count_posts);
            }
            
            if ($last_id != 0)
            {
                if ($cache_forums[$last_id]['last_post_member_id'] == 0)
                {
                    $tpl->tags_blocks("guest_post");
                    $tpl->tags_blocks("member_post", false);
                }
                else
                {
                    $tpl->tags_blocks("guest_post", false);
                    $tpl->tags_blocks("member_post"); 
                    $tpl->tags('{last_post_member_link}', profile_link($cache_forums[$last_id]['last_post_member']));
                }
        
                $tpl->tags('{last_post_member}', $cache_forums[$last_id]['last_post_member']);
                $tpl->tags('{last_post_member_id}', $cache_forums[$last_id]['last_post_member_id']);
                $tpl->tags('{last_post_date}', formatdate($cache_forums[$last_id]['last_post_date']));

                if (forum_all_password($last_id))
                {
                    $tpl->tags('{last_title}', $lang_m_b_forum['closed_forum']);
                    $tpl->tags('{last_topic_link}', "#");
                    $tpl->tags('{last_title_full}', $lang_m_b_main['closed_forum']);
                }
                else
                {
                    $tpl->tags('{last_title_full}', htmlspecialchars($cache_forums[$last_id]['last_title']));
                                     
                    $last_title = sub_title($cache_forums[$last_id]['last_title'], intval($cache_config['forums_topic_title']['conf_value']));                
                    $tpl->tags('{last_title}', $last_title);
                    $tpl->tags('{last_topic_link}', topic_link($cache_forums[$last_id]['last_topic_id'], $last_id, true));
                }
                $tpl->tags('{last_topic_id}', $cache_forums[$last_id]['last_topic_id']);
            }
            else
            {
                $tpl->tags('{last_post_member}', $lang_m_b_forum['last_member_none']);
                $tpl->tags('{last_title}', $lang_m_b_forum['last_title_none']);
                $tpl->tags('{last_post_date}', "");
                $tpl->tags('{last_topic_link}', "#");
                $tpl->tags('{last_title_full}', "");
                $tpl->tags('{last_post_member_link}', "#");
                
                $tpl->tags_blocks("guest_post");
                $tpl->tags_blocks("member_post", false);
            }
                     
            $tpl->tags_blocks("category_header", false);
                    
            $tpl->tags('{sub_forums}', Build_SubForums ($sid, $f_id));
                    
            $tpl->tags_blocks("category_footer", false);
                    
            $tpl->compile('content');
            $first = false;
        }
    }
	return $build;
}

function check_last_forum ($id = 0)
{
    global $cache_forums;
    
    $sub = sub_forums ($id);
    $last_date = 0;
    $last_id = 0;
    $count_posts = 0;
    $count_topics = 0;
    $count_posts_hiden = 0;
    $count_topics_hiden = 0;
   	if ($sub)
	{
		$sub = explode ("|", $sub);
		if( count( $sub ) > 1 )
        {
            foreach ($sub as $sub_forum)
			{
                if ($sub_forum != $id)
                {
                    if (forum_permission($sub_forum, "read_forum"))
                    {
                        if($cache_forums[$sub_forum]['last_post_date'] > $last_date)
                        {
                            $last_date = $cache_forums[$sub_forum]['last_post_date'];
                            $last_id = $sub_forum;
                        }
                        $count_posts += $cache_forums[$sub_forum]['posts'];
                        $count_topics += $cache_forums[$sub_forum]['topics'];
                        $count_posts_hiden += $cache_forums[$sub_forum]['posts_hiden'];
                        $count_topics_hiden += $cache_forums[$sub_forum]['topics_hiden'];
                    }
                }
            }
        }
    }

    if($cache_forums[$id]['last_post_date'] > $last_date)
    {
        $last_id = $id;
    }
    
    $count_posts += $cache_forums[$id]['posts'];
    $count_topics += $cache_forums[$id]['topics'];
    $count_posts_hiden += $cache_forums[$id]['posts_hiden'];
    $count_topics_hiden += $cache_forums[$id]['topics_hiden'];
    
    return $last_id."|".$count_posts."|".$count_topics."|".$count_posts_hiden."|".$count_topics_hiden;
}

function Build_SubForums($id = 0, $f_id = 0)
{
    global $cache_config;
    
	$sub = sub_forums ($id);
	$build = "";

	if ($sub)
	{
		$sub = explode ("|", $sub);
		if( count( $sub ) > 1 )
		{
			$i = 0;
			foreach ($sub as $sub_forum)
			{
				if ($sub_forum != $f_id AND $sub_forum != $id)
				{
					$id = $sub_forum;
                    if (forum_permission($id, "read_forum"))
                    {
                        if (!$i)
                            $build .= "\r\n<tr>";
                        elseif ($i%intval($cache_config['forums_subforums']['conf_value']) == 0)
                            $build .= "</tr>\r\n<tr>";
                            
                        $build .= ShowForums_Sub ( $id );
                        $i ++;
                    }
				}
			}
            if ($build != "")
                $build = $build."</tr>";
		}
	}

	return "<table class=\"sub_forums_out\">".$build."</table>";
}

function Build_head_forum($id = 0)
{
	$sub = sub_forums ($id);
	$build = "";

	if ($sub)
	{
		$sub = explode ("|", $sub);
		if( count( $sub ) > 1 )
		{
			foreach ($sub as $sub_forum)
			{
				if ($sub_forum != $id)
				{
					$id = $sub_forum;
                    if (forum_permission($id, "read_forum"))
					   $build .= ShowForums_Sub ( $id );
				}
			}
            if ($build != "")
                return true;
		}
	}

	return false;
}

function ShowForums($f_id = 0, $parentid = 0, $returnstring = '') 
{
	global $cache_forums, $redirect_url, $secret_key, $tpl;

	if (isset ( $cache_forums ))
	{
		$main_category = array();
		if (!$f_id)
		{
			foreach ( $cache_forums as $mass )
			{
				if( $mass['parent_id'] == $parentid )
					$main_category[] = $mass['id'];
			}
		}
		else
			$main_category[] = $f_id;

		if( count( $main_category ) )
		{
			foreach ( $main_category as $id )
			{
				$forum_title = $cache_forums[$id]['title'];
                
                if ($f_id == $id)
				{
				    $sub = sub_forums ($id);
       	            if ($sub)
                    {
                        $sub = explode ("|", $sub);
                        if( count( $sub ) > 1 )
                        {
                            $tpl->tags_blocks("category_header");
  
                            if ($cache_forums[$id]['parent_id'])
                            {
                                $tpl->tags_blocks("is_forum");
                                $tpl->tags_blocks("is_category", false);
                            }
                            else
                            {
                                $tpl->tags_blocks("is_category");
                                $tpl->tags_blocks("is_forum", false);
                            }
                                
                            $tpl->tags_blocks("forum", false);
                            $tpl->tags_blocks("category_footer", false);
                            
                            $tpl->tags('{category_name}', $forum_title);
                    
                            $tpl->compile('content');

                            Build_SubForums_2($id, $f_id, $sub);
                            
                            $tpl->tags_blocks("category_footer");
                            $tpl->tags_blocks("forum", false);
                            $tpl->tags_blocks("category_header", false);
                    
                            $tpl->compile('content');
                                
                        }
                        else
                            return;
                    }
				}
			}
		}
	}
}

$link_speddbar = speedbar_forum ($id);
$onl_location = str_replace("{forum}", $cache_forums[$id]['title'], $lang_m_b_forum['location_online']);

if (count($cache_adtblock))
{
	foreach ( $cache_adtblock as $value )
    {
        $show_adt = false;
        
        if ($value['forum_id'])
        {
            $check_group = explode (",", $value['group_access']);
            $check_forum = explode (",", $value['forum_id']);
            
            if (in_array(0, $check_group) OR in_array($member_id['user_group'], $check_group))
            {
                if (in_array($id, $check_forum))
                    $show_adt = true;
            }
        }
        
        if ($value['active_status'] AND $show_adt)
        {
            $tpl->copy_template .= $value['text'];
            $tpl->compile('content');
        }
	}
}

forums_notice ($id);

if (Build_head_forum($id))
{
    $tpl->load_template ( 'board/forum.tpl' );
    ShowForums($id);
    $tpl->clear();    
}  

$rss_link = "f=".$id;
$meta_info_forum = $id;
  
if ($cache_forums[$id]['parent_id'])
{      
    cookie_forums_read_update ($id, $cache_forums[$id]['last_post_date']);
    
    if ($cache_forums[$id]['rules'])
    {
        $tpl->load_template ( 'board/rules.tpl' );
        $tpl->tags('{forum_rules}', $cache_forums[$id]['rules']);
        $tpl->compile('content');
        $tpl->clear();    
    }
    
    $j = 0;
    
    $where = "";
    
    if (isset ( $_REQUEST['page'] ))
	   $page = intval ( $_GET['page'] );
    else
	   $page = 0;

    if ($page < 0)
	   $page = 0;

    if ($page)
    {
	   $page = $page - 1;
	   $page = $page * $cache_config['topic_page']['conf_value'];
    }

    $i = $page;
    
    $dopnav = "";
    
    if(!forum_options_topics($id, "hideshow"))
        $where = "AND hiden = '0'";
    else
    {
        if (isset($_GET['hide']) AND $_GET['hide'] == "topics")
        {
            $where = "AND hiden = '1'";
            $dopnav = "topics";
        }
        elseif (isset($_GET['hide']) AND $_GET['hide'] == "posts")
        {
            $where = "AND post_hiden <> '0'";
            $dopnav = "posts";
        }            
    }
    
    include LB_CLASS.'/topics_out.php';
    $LB_topics = new LB_topics;

    if ($page <= 1)
    {
        $LB_topics->query = $DB->select( "*", "topics", "forum_id = '{$id}' AND fixed = '1' {$where}", "ORDER by fixed DESC, date_last DESC" );
        $j = 0;
        $LB_topics->Data_out("board/topic_all.tpl", "topics");
    }
    
    $LB_topics->query = $DB->select( "*", "topics", "forum_id = '{$id}' AND fixed = '0' {$where}", "ORDER by fixed DESC, date_last DESC LIMIT ".$page.", ".$cache_config['topic_page']['conf_value'] );
    $LB_topics->Data_out("board/topic_all.tpl", "topics");
    
    unset($LB_topics);
    
    $nav = $DB->one_select( "COUNT(*) as count", "topics", "forum_id = '{$id}' AND fixed = '0' {$where}");
    $nav_all = $nav['count'];
    $DB->free($nav);
    $link_nav = navigation_link_forums($id, $dopnav);
    if ($nav_all > $cache_config['topic_page']['conf_value'])
    {
        include_once LB_CLASS.'/navigation_board.php';
        $navigation = new navigation;
        $navigation->create($page, $nav_all, $cache_config['topic_page']['conf_value'], $link_nav, "3");
        $navigation->template();
        unset($navigation);
    }
    else
        $tpl->result['navigation'] = "";
              
    $tpl->load_template ( 'board/topic_global.tpl' );

    $tpl->tags('{category_name}', $cache_forums[$id]['title']);
    $tpl->tags('{new_topic}', topic_new_link($id));
    $tpl->tags('{fast_forum}', ForumsList($id, 0, "", "", true));

    if (forum_options_topics($id))
    {
        $tpl->tags('{forum_options}', forum_options($id, "forum"));
        $tpl->tags('{forum_options_topics}', forum_options_topics($id));
        $tpl->tags_blocks("moder_line");
    }
    else
        $tpl->tags_blocks("moder_line", false);
            
    $tpl->tags_blocks("topics_out");
  
    $tpl->tags_templ('{topics}', $tpl->result['topics']);
    $tpl->tags('{forum_id}', $id);
    $tpl->tags_templ('{pages}', $tpl->result['navigation']);

    $tpl->compile('content');
    $tpl->clear();
    
    if (!$j)
        message ($lang_message['information'], $lang_m_b_forum['no_topics']);
}
?>