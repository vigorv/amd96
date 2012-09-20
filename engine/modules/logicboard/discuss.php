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

$id = intval($_GET['id']);

if (!$logicboard_conf['discuss_status'])
{
    msgbox( $lang['all_err_1'], "Модуль отключен." );
}
elseif (!$id)
{
    msgbox( $lang['all_err_1'], "Вы не указали ID новости для обсуждения." );
}
elseif (!$cache_forums[$logicboard_conf['discuss_fid']]['parent_id'])
{
    msgbox( $lang['all_err_1'], "Неверно указан форум." );
}  
elseif (!$logicboard_conf['discuss_post'])
{
    msgbox( $lang['all_err_1'], "Не указано сообщение темы." );
}
else
{
    $check = $db->super_query("SELECT d.*, t.id as topic_id, t.forum_id FROM ".LB_DB_PREFIX."_discuss d LEFT JOIN ".LB_DB_PREFIX."_topics t ON d.tid=t.id WHERE d.nid = '{$id}'");
    
    if ($check['topic_id']) // Если нашли уже созданную тему
    {
        header("HTTP/1.0 301 Moved Permanently");
        header("Location: ".LB_topic_link($check['topic_id'], $check['forum_id']));
        exit ("Redirect.");
    }
    
    if (!$is_logged)
    {
        msgbox( $lang['all_err_1'], "Данная функция доступна только авторизованным пользователям." );
    }
    elseif(!LB_forum_permission($logicboard_conf['discuss_fid'], "read_forum"))
    {
        msgbox( $lang['all_err_1'], "У вас недостаточно прав для чтения тем в данном форуме." );
    }
    elseif(!LB_forum_permission($logicboard_conf['discuss_fid'], "creat_theme"))
    {
        msgbox( $lang['all_err_1'], "У вас недостаточно прав для создания тем в данном форуме." );
    }
    elseif(LB_forum_all_password($logicboard_conf['discuss_fid']))
    {
        msgbox( $lang['all_err_1'], "Данный форум закрыть паролем." );
    }
    elseif($cache_lb_config['basket_on']['conf_value'] AND $cache_lb_config['basket_fid']['conf_value'] == $logicboard_conf['discuss_fid'])
    {
        msgbox( $lang['all_err_1'], "Данный форум является корзиной." );
    }
    elseif ($cache_forums[$logicboard_conf['discuss_fid']]['flink'])
    {
        msgbox( $lang['all_err_1'], "Данный форум является форумом-ссылкой." );
    }
    else
    {
        // Если не нашли упоминание в discuss или нашли, но указанной темы уже не существует
        
        //DLE 9.4 и ниже
        //$news = $db->super_query("SELECT id, autor, date, title, approve, flag, access, alt_name, category FROM " . PREFIX . "_post WHERE id = '{$id}'");
        
        $news = $db->super_query("SELECT p.id, p.autor, p.date, p.title, p.approve, pe.access, p.alt_name, p.category FROM " . PREFIX . "_post p LEFT JOIN " . PREFIX . "_post_extras pe ON p.id=pe.news_id WHERE p.id = '{$id}'");
        
        if (!$news['id'] OR !$news['approve'])
        {
            msgbox( $lang['all_err_1'], "Выбранная новость не найдена." );
        }
        else
        {    
            $perm = 1;
            
            $options = news_permission( $news['access'] );
            
            if($options[$member_id['user_group']] and $options[$member_id['user_group']] != 3) $perm = 1;
    		if($options[$member_id['user_group']] == 3) $perm = 0;
    		
    		if(!$perm)
            {
                msgbox( $lang['all_err_1'], "У Вас недостаточно прав для просмотра данной новости." );
            }
            else
            {
                $news['category'] = intval( $news['category'] );
                $category_id = $news['category'];
                
                if( $config['allow_alt_url'] == "yes" )
                {
        			if( $config['seo_type'] == 1 OR $config['seo_type'] == 2  )
                    {
        				if( $news['category'] and $config['seo_type'] == 2 )
        					$full_link = $config['http_home_url'] . get_url( $news['category'] ) . "/" . $news['id'] . "-" . $news['alt_name'] . ".html";
        				else
        					$full_link = $config['http_home_url'] . $news['id'] . "-" . $news['alt_name'] . ".html";    			
        			}
                    else
        				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $news['date'] ) . $news['alt_name'] . ".html";
        		}
                else
        			$full_link = $config['http_home_url'] . "index.php?newsid=" . $news['id'];
                
                /*
                DLE 9.4 и ниже
                if( $config['allow_alt_url'] == "yes" )
                {
        			if( $news['flag'] and $config['seo_type'] )
                    {
        				if( $category_id and $config['seo_type'] == 2 )
        					$full_link = $config['http_home_url'] . get_url( $category_id ) . "/" . $news['id'] . "-" . $news['alt_name'] . ".html";
                        else
        					$full_link = $config['http_home_url'] . $news['id'] . "-" . $news['alt_name'] . ".html";
        			}
                    else
        				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $news['date'] ) . $news['alt_name'] . ".html";    			
        		}
                else
        			$full_link = $config['http_home_url'] . "index.php?newsid=" . $news['id'];
                */
                    
                $news['title'] = stripslashes($news['title']);
                    
                $discuss_post = str_replace("{title}", "<a href=\"".$full_link."\">".htmlspecialchars($news['title'])."</a>", $logicboard_conf['discuss_post']);
                
                $news['date'] = strtotime( $news['date'] );
                $discuss_post = str_replace("{date}", langdate($config['timestamp_active'], $news['date']), $discuss_post);
                
                $discuss_post = $db->safesql($discuss_post);
                    
                $title = htmlspecialchars($news['title']);
                if( dle_strlen( $title, $config['charset'] ) > 150 ) $title = dle_substr( $title, 0, 150, $config['charset'] ) . " ...";
                
                if ($logicboard_conf['discuss_title']) $logicboard_conf['discuss_title'] .= " ";
                
                $discuss_title = $db->safesql($logicboard_conf['discuss_title'].$title);

                // Создаём тему, пост и обновляем счётчики
                $db->query("INSERT INTO ".LB_DB_PREFIX."_posts SET topic_id = '0', new_topic = '1', text = '{$discuss_post}', attachments = '', post_date = '{$_TIME}', post_member_id = '{$member_id['user_id']}', post_member_name = '{$member_id['name']}', ip = '{$_IP}', utility = '0'");
                $post_id = $db->insert_id();
                      
                $db->query("INSERT INTO ".LB_DB_PREFIX."_topics SET forum_id = '{$logicboard_conf['discuss_fid']}', title = '{$discuss_title}', description = '', post_id = '{$post_id}', date_open = '{$_TIME}', date_last = '{$_TIME}', status = 'open', last_post_id = '{$post_id}', last_post_member = '{$member_id['user_id']}', member_name_last = '{$member_id['name']}', member_name_open = '{$member_id['name']}', member_id_open = '{$member_id['user_id']}'");          
                $topic_id = $db->insert_id();
                
                $db->query("UPDATE ".LB_DB_PREFIX."_posts SET topic_id = '{$topic_id}' WHERE topic_id = '0' AND new_topic='1' AND post_member_id = '{$member_id['user_id']}'");
                $db->query("UPDATE ".LB_DB_PREFIX."_forums SET last_title = '{$discuss_title}', last_post_member = '{$member_id['name']}', last_post_member_id = '{$member_id['user_id']}', last_post_date = '{$_TIME}', last_topic_id = '{$topic_id}', topics = topics+1 WHERE id = '{$logicboard_conf['discuss_fid']}'");
        
                if ($cache_forums[$logicboard_conf['discuss_fid']]['postcount'])
                {
                    $db->query("UPDATE ".USERPREFIX."_users SET topics_num = topics_num+1 WHERE user_id = '{$member_id['user_id']}'");
                }
                                
                if ($check['id'])   // Обновляем информацию, если не была найдена прошлая тема
                {
                    $db->query("UPDATE ".LB_DB_PREFIX."_discuss SET tid = '{$topic_id}', date = '{$_TIME}' WHERE id = '{$check['id']}'");
                }
                else
                {
                    $db->query("INSERT INTO ".LB_DB_PREFIX."_discuss SET nid = '{$id}', tid = '{$topic_id}', date = '{$_TIME}'");
                }
                
                $db->query( "INSERT INTO " . LB_DB_PREFIX . "_cache_update SET name = 'forums', lastdate = '{$_TIME}' ON DUPLICATE KEY UPDATE name = 'forums', lastdate = '{$_TIME}'" );
                
                header("Location: ".LB_topic_link($topic_id, $logicboard_conf['discuss_fid']));
                exit();
            }
        }
    }
}

?>