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

$lang_m_b_main = language_forum ("board/modules/board/main");

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

function ShowForums($f_id = 0, $parentid = 0, $returnstring = '') 
{
	global $cache_forums, $redirect_url, $secret_key, $tpl, $cache_config, $cache_group, $cache_forums_moder, $lang_m_b_main;

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

                if (forum_permission($id, "read_forum"))
                {
				    if ($cache_forums[$id]['parent_id'] == 0 AND !$f_id)
				    {
				        $tpl->tags_blocks("category_header");
                        $tpl->tags('{category_name}', $forum_title);
                        $tpl->tags('{category_link}', forum_link($id));
                        $tpl->tags('{category_id}', $id);
                        
                        $tpl->tags_blocks("forum", false);
                        $tpl->tags_blocks("category_footer", false);
                    
                        $tpl->compile('content');
                    }
				    else
				    {
					   if ($cache_forums[$cache_forums[$id]['parent_id']]['parent_id'] == 0)
					   {
                            $tpl->tags_blocks("forum");
                            $tpl->tags('{forum_name}', $forum_title);
                            $tpl->tags('{forum_link}', forum_link($id));
                        
                            if ($cache_forums[$id]['description'])
                                $tpl->tags('{forum_desc}', "<p>".$cache_forums[$id]['description']."</p>");
                            else
                                $tpl->tags('{forum_desc}', "");
                                
                            if ($cache_forums[$id]['ficon'])
                            {
                                $forum_icon = explode ("|", $cache_forums[$id]['ficon']);
                            }
                            
                            if (cookie_forums_read($id))
                            {
                                if ($cache_forums[$id]['ficon'])
                                    $tpl->tags('{forum_img}', $forum_icon[1]);
                                else
                                    $tpl->tags('{forum_img}', "ico_forum_read.png");
                                    
                                $tpl->tags('{forum_status}', $lang_m_b_main['forum_read']);
                            }
                            else
                            {
                                if ($cache_forums[$id]['ficon'])
                                    $tpl->tags('{forum_img}', $forum_icon[0]);
                                else
                                    $tpl->tags('{forum_img}', "ico_forum_unread.png");
                                $tpl->tags('{forum_status}', $lang_m_b_main['forum_unread']);
                            }
                            
                            if ($cache_forums[$id]['flink'])
                            {
                                $tpl->tags_blocks("flink_page");
                                $tpl->tags_blocks("flink_npage", intval($cache_forums[$id]['flink_npage']));
                                $tpl->tags_blocks("not_flink_page", false);
                                $tpl->tags('{count_view}', $cache_forums[$id]['posts']);
                                $last_id = 0;
                            }   
                            else
                            {
                                $tpl->tags_blocks("flink_page", false);
                                $tpl->tags_blocks("not_flink_page");
                                
                                $last_mass = check_last_forum ($id);
                                $last_mass = explode("|", $last_mass);
                                list($last_id, $count_posts, $count_topics, $posts_hiden, $topics_hiden) = $last_mass;
                                
                                if(forum_options_topics($id, "hideshow") AND ($posts_hiden OR $topics_hiden))
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
                                            if ($moder_list['fm_forum_id'] == $id)
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
                                    $tpl->tags_blocks("forum_moders", false);
                            
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
                                    $tpl->tags('{last_title}', $lang_m_b_main['closed_forum']);
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
                                $tpl->tags('{last_post_member}', $lang_m_b_main['last_member_none']);
                                $tpl->tags('{last_title}', $lang_m_b_main['last_title_none']);
                                $tpl->tags('{last_post_date}', "");
                                $tpl->tags('{last_topic_link}', "#");
                                $tpl->tags('{last_title_full}', "");
                                $tpl->tags('{last_post_member_link}', "#");
                                
                                $tpl->tags_blocks("guest_post");
                                $tpl->tags_blocks("member_post", false);
                            }
                                            
                            $tpl->tags_blocks("category_header", false);
                    
                            $tpl->tags('{sub_forums}', Build_SubForums ($id, $f_id));
                            
                            $tpl->tags_blocks("category_footer", false);
                    
                            $tpl->compile('content');
					   }
				    }
				    if (!$f_id)
                        ShowForums( $f_id, $id, $returnstring );

				    if ($cache_forums[$id]['parent_id'] == 0)
				    {
				        $tpl->tags_blocks("category_footer");
                        $tpl->tags_blocks("forum", false);
                        $tpl->tags_blocks("category_header", false);
                    
                        $tpl->compile('content');
				    }
                }
			}
		}
	}
}

$link_speddbar = speedbar_forum ($id);
$onl_location = $lang_m_b_main['location_online'];

if(count($cache_forums))
{
    $tpl->load_template ( 'board/main.tpl' );
	ShowForums();
    $tpl->clear();
}
else
    message ($lang_message['error'], $lang_m_b_main['no_forums'], 1);

?>