<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

ignore_user_abort(1);
@set_time_limit(0);

$control_center->errors = array ();

$_SESSION['back_link_info'] = $_SERVER['REQUEST_URI'];

function rebuild_message ()
{
echo <<<HTML
                            <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Пересчёт успешно выполен</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
Функция пересчёта успешно выполена. <a href="{$_SERVER['REQUEST_URI']}">Вернуться.</a>
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
HTML;
}

if (isset($_POST['do_rebuild']) AND $_SESSION['LB_rebuild'] == 1)
{
    $_SESSION['LB_rebuild'] = 0;
    $link_speddbar = "<a href=\"".$redirect_url."?do=system\">Система</a>|<a href=\"".$redirect_url."?do=system&op=rebuild\">Пересчёт данных</a>|Процесс завершён";
    $control_center->header("Сиситема", $link_speddbar);
    $onl_location = "Система &raquo; Пересчёт данных &raquo; Процесс завершён";
    
    if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$re_topics = intval( $_POST['re_topics'] );

	if ($re_topics)
	{
        $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id", "forums", "", "ORDER BY id ASC LIMIT ".$begin.", 20");
            while ( $row = $DB->get_row($f_rows ) )
            {
                $empty = false;
                $topic = $DB->one_select( "COUNT(*) as count", "topics", "forum_id = '{$row['id']}' AND hiden = '0'"); 
                $DB->update("topics = '{$topic['count']}'", "forums", "id = '{$row['id']}'");
                $DB->free($topic);
            }
            $DB->free($f_rows );
            
            if ($empty) break;
                
            $begin += 20;            
            sleep(2);
        }
        
		$info = "<font color=orange>Пересчёт</font> тем в форумах.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
   	$re_posts = intval( $_POST['re_posts'] );

	if ($re_posts)
	{
        $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id", "forums", "", "ORDER BY id ASC LIMIT ".$begin.", 20");
            while ( $row = $DB->get_row($f_rows ) )
            {
                $empty = false;
                $post = $DB->one_not_filtred( "SELECT COUNT(p.pid) as count FROM `". LB_DB_PREFIX ."_posts` p, `". LB_DB_PREFIX ."_topics` t WHERE p.topic_id = t.id AND p.new_topic = '0' AND p.hide = '0' AND t.forum_id = '{$row['id']}' AND t.hiden = '0'");
                $DB->free($post);
                $DB->update("posts = '{$post['count']}'", "forums", "id = '{$row['id']}'");
            }
            $DB->free($f_rows );
            
            if ($empty) break;
                
            $begin += 20;            
            sleep(2);
        }

		$info = "<font color=orange>Пересчёт</font> сообщений в форумах.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
   	$re_hide = intval( $_POST['re_hide'] );

	if ($re_hide)
	{
	    $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id", "forums", "", "ORDER BY id ASC LIMIT ".$begin.", 20");
            while ( $row = $DB->get_row($f_rows ) )
            {         
                $empty = false;
                $topic = $DB->one_select( "COUNT(*) as count", "topics", "forum_id = '{$row['id']}' AND hiden = '1'");  
                $DB->free($topic);           
                $post = $DB->one_not_filtred( "SELECT COUNT(p.pid) as count FROM `". LB_DB_PREFIX ."_posts` p, `". LB_DB_PREFIX ."_topics` t WHERE p.topic_id = t.id AND p.new_topic = '0' AND p.hide = '1' AND t.forum_id = '{$row['id']}'"); 
                $DB->free($post);  
                
                $DB->update("posts_hiden = '{$post['count']}', topics_hiden = '{$topic['count']}'", "forums", "id = '{$row['id']}'");
                $DB->free();
            }
            $DB->free($f_rows );
            
            if ($empty) break;
                
            $begin += 20;            
            sleep(2);
        }

		$info = "<font color=orange>Пересчёт</font> скрытых тем и сообщений в форумах.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
                
	$re_syn = intval( $_POST['re_syn'] );

	if ($re_syn)
	{
	    $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id", "forums", "", "ORDER BY id ASC LIMIT ".$begin.", 20");
            while ( $row = $DB->get_row($f_rows ) )
            {    
                $empty = false;
                $topic_last = $DB->one_select( "id, title, member_name_last, last_post_member, last_post_id, date_last", "topics", "forum_id = '{$row['id']}' AND hiden = '0'", "ORDER by date_last DESC LIMIT 1");           
                $DB->free($topic_last);
                
                if($topic_last['id'])
                {
                    $topic_last['title'] = $DB->addslashes( $topic_last['title'] );
                    $DB->update("last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_id = '{$topic_last['last_post_id']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}'", "forums", "id = '{$row['id']}'");
                }
                else
                    $DB->update("last_post_member = '', last_post_member_id = '0', last_post_id = '0', last_post_date = '0', last_title = '', last_topic_id = '0'", "forums", "id = '{$row['id']}'");
                $DB->free();
            }
            $DB->free($f_rows );
            
            if ($empty) break;
                
            $begin += 20;            
            sleep(2);
        }

		$info = "<font color=orange>Синхронизация</font> форумов.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}    
        
    $cache->clear("", "forums");
    
    if (!$re_topics AND !$re_posts AND !$re_hide AND !$re_syn)
    {
        $control_center->errors[] = "Вы ничего не выбрали.";
        $control_center->errors_title = "Ошибка!";
        $control_center->message();
    }
    else
    {
        rebuild_message ();
    }
}
elseif (isset($_POST['do_rebuild2']) AND $_SESSION['LB_rebuild'] == 1)
{
    $_SESSION['LB_rebuild'] = 0;
    $link_speddbar = "<a href=\"".$redirect_url."?do=system\">Система</a>|<a href=\"".$redirect_url."?do=system&op=rebuild\">Пересчёт данных</a>|Процесс завершён";
    $control_center->header("Сиситема", $link_speddbar);
    $onl_location = "Система &raquo; Пересчёт данных &raquo; Процесс завершён";
    
    if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$re_posts = intval( $_POST['re_posts'] );

	if ($re_posts)
	{
	    $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id, forum_id", "topics", "", "ORDER BY id ASC LIMIT ".$begin.", 200");
            while ( $row = $DB->get_row($f_rows ) )
            {    
                $empty = false;
                if ($cache_forums[$row['forum_id']]['postcount'])
                {
                    $post = $DB->one_select( "COUNT(*) as count", "posts", "topic_id = '{$row['id']}' AND hide = '0' AND new_topic = '0'"); 
                    $DB->update("post_num = '{$post['count']}'", "topics", "id = '{$row['id']}'");
                    $DB->free($post);
                }
            }
            $DB->free($f_rows );
            
            if ($empty) break;
                
            $begin += 200;            
            sleep(2);
        }

		$info = "<font color=orange>Пересчёт</font> ответов в темах.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
   	$re_hide = intval( $_POST['re_hide'] );

	if ($re_hide)
	{
        $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id, forum_id", "topics", "", "ORDER BY id ASC LIMIT ".$begin.", 200");
            while ( $row = $DB->get_row($f_rows ) )
            {
                $empty = false;                
                if ($cache_forums[$row['forum_id']]['postcount'])
                {
                    $post = $DB->one_select( "COUNT(*) as count", "posts", "topic_id = '{$row['id']}' AND hide = '1' AND new_topic = '0'"); 
                    $DB->update("post_hiden = '{$post['count']}'", "topics", "id = '{$row['id']}'");
                    $DB->free($post);
                }
            }
            $DB->free($f_rows );
            
            if ($empty) break;
                
            $begin += 200;            
            sleep(2);
        }

		$info = "<font color=orange>Пересчёт</font> скрытых ответов в темах.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
    $re_post_fix = intval( $_POST['re_post_fix'] );
    
    if ($re_post_fix)
	{
	    $begin = 0;
                        
        while (true)
        {        
            $empty = true;
            $t_rows = $DB->select( "id", "topics", "", "ORDER BY id ASC LIMIT ".$begin.", 20");
            while ( $row = $DB->get_row($t_rows ) )
            {         
                $empty = false;
                $posts = $DB->one_select( "COUNT(*) as count", "posts", "fixed = '1' AND topic_id = '{$row['id']}'");  
                $DB->free($posts);           
                
                $DB->update("post_fixed = '{$posts['count']}'", "topics", "id = '{$row['id']}'");
            }
            $DB->free($t_rows );
            
            if ($empty) break;
                
            $begin += 20;            
            sleep(2);
        }

		$info = "<font color=orange>Пересчёт</font> закреплённых сообщений в темах.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
   	$re_syn = intval( $_POST['re_syn'] );

	if ($re_syn)
	{
	    $begin = 0;
                 
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "id, hiden, forum_id, poll_id", "topics", "", "ORDER BY id ASC LIMIT ".$begin.", 200");
            while ( $row = $DB->get_row($f_rows) )
            {     
                $empty = false;
                if ($cache_forums[$row['forum_id']]['id'] != 0)
                {
                    $where = "";
                    $post_first = $DB->one_select( "pid, hide, post_member_name, post_member_id, post_member_name, post_date", "posts", "topic_id = '{$row['id']}' AND new_topic = '1'", "LIMIT 1");           
                    
                    if ($post_first['hide'] AND !$row['hiden']) $where = ", hiden = '1'";
                    
                    $DB->free($post_first);   
                            
                    $post_last = $DB->one_select( "pid, hide, post_member_name, post_member_id, post_member_name, post_date", "posts", "topic_id = '{$row['id']}' AND hide = '0'", "ORDER by post_date DESC LIMIT 1");   
                    $DB->free($post_last);  
                    
                    $DB->update("post_id = '{$post_first['pid']}', member_name_open = '{$post_first['post_member_name']}', member_id_open = '{$post_first['post_member_id']}',member_name_last = '{$post_last['post_member_name']}', last_post_member = '{$post_last['post_member_id']}', last_post_id = '{$post_last['pid']}', date_last = '{$post_last['post_date']}' {$where}", "topics", "id = '{$row['id']}'");
                    $DB->free();
                }
                else
                {
                    $DB->delete("topic_id = '{$row['id']}'", "posts");
                    $DB->delete("id = '{$row['id']}'", "topics");
                    if ($row['poll_id'])
                    {
                        $DB->delete("id = '{$row['poll_id']}'", "topics_poll");
                        $DB->delete("poll_id = '{$row['poll_id']}'", "topics_poll_logs");
                    }
                    
                    $del_file_t = $DB->select( "file_id, file_date, file_name", "topics_files", "file_tid = '{$row['id']}'");
                    while ( $row2 = $DB->get_row($del_file_t) )
                    { 
                        $upload_dir_name = LB_UPLOADS . "/attachment/".date( "Y-m", $row2['file_date'] )."/";
                        @unlink($upload_dir_name.$row2['file_name']);
                    }
                    $DB->delete("file_tid = '{$row['id']}'", "topics_files");
                    $DB->free($del_file_t);
                }
            }
            $DB->free($f_rows);
            
            if ($empty) break;
                
            $begin += 200;            
            sleep(2);
        }

		$info = "<font color=orange>Синхронизация</font> тем.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
    $re_files_t = intval( $_POST['re_files_t'] );
    
    if ($re_files_t)
    {
        $begin = 0;
            
        while (true)
        {        
            $empty = true;
            $f_rows = $DB->select( "file_id, file_date, file_name", "topics_files", "file_pid = '0'", "ORDER BY file_id ASC LIMIT ".$begin.", 400");
            while ( $row = $DB->get_row($f_rows ) )
            { 
                $empty = false;
                $upload_dir_name = LB_UPLOADS . "/attachment/".date( "Y-m", $row['file_date'] )."/";
                @unlink($upload_dir_name.$row['file_name']);
            }
            $DB->free($f_rows);
            
            if ($empty) break;
                
            $begin += 400;            
            sleep(2);
        }
        
        $DB->delete("file_pid = '0'", "topics_files");
    }
           
    if (!$re_posts AND !$re_hide AND !$re_syn AND !$re_files_t AND !$re_post_fix)
    {
        $control_center->errors[] = "Вы ничего не выбрали.";
        $control_center->errors_title = "Ошибка!";
        $control_center->message();
    }
    else
    { 
        rebuild_message ();
    }
}
elseif (isset($_POST['do_rebuild3']) AND $_SESSION['LB_rebuild'] == 1)
{
    $_SESSION['LB_rebuild'] = 0;
    $link_speddbar = "<a href=\"".$redirect_url."?do=system\">Система</a>|<a href=\"".$redirect_url."?do=system&op=rebuild\">Пересчёт данных</a>|Процесс завершён";
    $control_center->header("Сиситема", $link_speddbar);
    $onl_location = "Система &raquo; Пересчёт данных &raquo; Процесс завершён";
    
    if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$re_posts = intval( $_POST['re_posts'] );

	if ($re_posts)
	{
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("posts_num = '0'", "users");
        
        $begin = 0;
            
        while (true)
        {  
            $empty = true;
            $DB->prefix = DLE_USER_PREFIX;
            $all_members = $DB->select( "user_id", "users", "", "ORDER BY user_id ASC LIMIT ".$begin.", 200");
            while ( $row = $DB->get_row($all_members) )
            {
                $empty = false;
                $posts_m = $DB->one_join_select( "COUNT(pid) as count", "LEFT", "posts p||topics t||forums f", "p.topic_id=t.id||t.forum_id=f.id", "p.post_member_id = '{$row['user_id']}' AND p.hide = '0' AND p.new_topic = '0' AND t.hiden = '0' AND f.postcount <> '0'" );
                
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("posts_num = '{$posts_m['count']}'", "users", "user_id = '{$row['user_id']}'");
            }
            $DB->free($all_members); 
 
            if ($empty) break;
                
            $begin += 200;            
            sleep(1);
        }

		$info = "<font color=orange>Пересчёт</font> сообщений у пользователей.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}
    
   	$re_topics = intval( $_POST['re_topics'] );

	if ($re_topics)
	{    
        $DB->prefix = DLE_USER_PREFIX;
	    $DB->update("topics_num = '0'", "users");
        
	    $begin = 0;
            
        while (true)
        {  
            $empty = true;
            $all_topics = $DB->select( "COUNT(*) as count, member_id_open", "topics", "hiden = '0'", "GROUP BY member_id_open LIMIT ".$begin.", 200");
            while ( $row = $DB->get_row($all_topics) )
            {
                $empty = false;
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update("topics_num = {$row['count']}", "users", "user_id = '{$row['member_id_open']}'");
            }
            $DB->free($all_topics);
            
            if ($empty) break;
                
            $begin += 200;
            sleep(2);
        }
        
		$info = "<font color=orange>Пересчёт</font> тем у пользователей.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}

    $re_warning = intval( $_POST['re_warning'] );

	if ($re_warning)
	{         
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("count_warning = '0'", "users");
        
        $begin = 0;
            
        while (true)
        {  
            $empty = true;
            
            $DB->prefix = array( 1 => DLE_USER_PREFIX );
            $all_warning = $DB->join_select( "COUNT(*) as count, w.mid, u.name", "LEFT", "members_warning w||users u", "w.mid=u.user_id", "w.st_w = '1'", "GROUP BY w.mid LIMIT ".$begin.", 250");
            $users = array();
            while ( $row = $DB->get_row($all_warning) )
            {
                $empty = false;
                
                $where = array();
                $where[] = "count_warning = '{$row['count']}'"; 
                
                if ($row['count'] >= intval($cache_config['warning_levels']['conf_value']) AND $member_id['user_id'] != $row['mid'] AND !LB_banned("name", $row['name']))
                {
                    $DB->prefix = DLE_USER_PREFIX;
                    $row2 = $DB->one_select( "user_id, name, email, user_group, mf_options, logged_ip, count_warning", "users", "user_id = '{$row['mid']}'" );
                        
                    $banned_member_days = intval($cache_config['warning_days']['conf_value']);
                    if (!$banned_member_days)
                        $banned_member_days = 0;
                        
                    if ($banned_member_days)
                        $date_end = $time + (60 * 60 * 24 * $banned_member_days);
                    else
                        $date_end = 0;
                        
                    if ($cache_config['warning_text']['conf_value'])
                        $ban_text = $DB->addslashes($cache_config['warning_text']['conf_value']);
                    else
                        $ban_text = "Максимальный уровень предупреждения превышен.";
                    
                    $DB->prefix = DLE_USER_PREFIX;
                    $check = $DB->one_select( "users_id", "banned", "users_id = '{$row2['user_id']}'" );
            
                    if (!$check['users_id'])
                    {
                        $DB->prefix = DLE_USER_PREFIX;
                        $DB->insert("users_id = '{$row2['user_id']}', date = '{$time}', descr = '{$ban_text}', days = '{$banned_member_days}'", "banned");
                    }
                    else 
                    {
                        $DB->prefix = DLE_USER_PREFIX;
                        $DB->update("date = '{$time}', descr = '{$ban_text}', days = '{$banned_member_days}'", "banned", "user_id = '{$row2['user_id']}'");
                    }
                    
                    $info_ban = "<font color=red>Заблокирован</font> пользователь.";
                    $info_ban .= "<br>На ".$banned_member_days." дней.";
                    $info_ban .= "<br>Причина блокировки: ".$ban_text;
                    $info_ban = $DB->addslashes($info_ban);
                    $DB->insert("member_id = '{$row2['user_id']}', moder_id = '{$member_id['user_id']}', moder_name = '{$member_id['name']}', date = '{$time}', info = '{$info_ban}', ip = '{$_IP}'", "logs_blocking");
                            
                    $where[] = "banned = 'yes'";
                }
                
                $where = implode(", ", $where);
                
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update($where, "users", "user_id = '{$row['mid']}'");
                unset ($where);
            }
            $DB->free($all_warning); 
            
            if ($empty) break;
                
            $begin += 250;           
            sleep(2);
        }
        
        $cache->clear("", "banfilters");

		$info = "<font color=orange>Пересчёт</font> уровня предупреждения у пользователей.";
		$DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
	}

    $re_view_topic = intval( $_POST['re_view_topic'] );
    
    if ($re_view_topic)
    {
        $view_topic = array();
        $view_topic['all'] = $time;
        $view_topic = $DB->addslashes(serialize($view_topic));
        
        $DB->prefix = DLE_USER_PREFIX;
        $DB->update("view_topic = '{$view_topic}'", "users");
    }
                       
    if (!$re_posts AND !$re_topics AND !$re_warning AND !$re_view_topic)
    {
        $control_center->errors[] = "Вы ничего не выбрали.";
        $control_center->errors_title = "Ошибка!";
        $control_center->message();
    }
    else
    {
        rebuild_message ();
    }
}
else
{
    $_SESSION['LB_rebuild'] = 1;
    $link_speddbar = "<a href=\"".$redirect_url."?do=system\">Система</a>|Пересчёт данных";
    $control_center->header("Сиситема", $link_speddbar);
    $onl_location = "Система &raquo; Пересчёт данных";
    
echo <<<HTML
	            <div class="clear" style="height:10px;"></div>
<form  method="post" name="build" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Пересчёт статистики в форумах</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption2">Пересчёт тем:</div>
                                            <div class="radioContainer"><input name="re_topics" type="radio" id="re_topics_1" value="1"></div>
 <label class="radioLabel" for="re_topics_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_topics" type="radio" id="re_topics_0" value="0" checked></div>
 <label class="radioLabel" for="re_topics_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт сообщений:</div>
                                            <div class="radioContainer"><input name="re_posts" type="radio" id="re_posts_1" value="1"></div>
 <label class="radioLabel" for="re_posts_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_posts" type="radio" id="re_posts_0" value="0" checked></div>
 <label class="radioLabel" for="re_posts_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт скрытых тем и сообщений:</div>
                                            <div class="radioContainer"><input name="re_hide" type="radio" id="re_hide_1" value="1"></div>
 <label class="radioLabel" for="re_hide_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_hide" type="radio" id="re_hide_0" value="0" checked></div>
 <label class="radioLabel" for="re_hide_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Синхронизация:<br><font class="smalltext">Обновляет информацию о последнем ответе в форумах.</font></div>
                                            <div class="radioContainer"><input name="re_syn" type="radio" id="re_syn_1" value="1"></div>
 <label class="radioLabel" for="re_syn_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_syn" type="radio" id="re_syn_0" value="0" checked></div>
 <label class="radioLabel" for="re_syn_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <input type="submit" name="do_rebuild" value="Выполнить" class="btnBlue" />
                                            <input type="hidden" name="secret_key" value="{$secret_key}" />
                                        </div>
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
</form>

	            <div class="clear" style="height:10px;"></div>
<form  method="post" name="build" action="">

                    <div class="headerGray">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">Пересчёт статистики в темах</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                        <div>
                                            <div class="inputCaption2">Информация:</div>
                                            <div>
                                            Если Вы видете, что при выводе тем навигация по страницам не соответсвует действительности - выполните пересчёт: ответов, скрытых и закреплённых сообщений.
                                            </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт ответов:</div>
                                            <div class="radioContainer"><input name="re_posts" type="radio" id="re_posts_1" value="1"></div>
 <label class="radioLabel" for="re_posts_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_posts" type="radio" id="re_posts_0" value="0" checked></div>
 <label class="radioLabel" for="re_posts_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт скрытых сообщений:</div>
                                            <div class="radioContainer"><input name="re_hide" type="radio" id="re_hide_1" value="1"></div>
 <label class="radioLabel" for="re_hide_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_hide" type="radio" id="re_hide_0" value="0" checked></div>
 <label class="radioLabel" for="re_hide_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт закреплённых сообщений:</div>
                                            <div class="radioContainer"><input name="re_post_fix" type="radio" id="re_post_fix_1" value="1"></div>
 <label class="radioLabel" for="re_post_fix_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_post_fix" type="radio" id="re_post_fix_0" value="0" checked></div>
 <label class="radioLabel" for="re_post_fix_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Синхронизация:<br><font class="smalltext">Обновляет информацию о первом, последнем ответе и статусе темы.</font></div>
                                            <div class="radioContainer"><input name="re_syn" type="radio" id="re_syn_1" value="1"></div>
 <label class="radioLabel" for="re_syn_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_syn" type="radio" id="re_syn_0" value="0" checked></div>
 <label class="radioLabel" for="re_syn_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Удаление не прикреплённых файлов:<br><font class="smalltext">Иногда пользователи загружают файлы, но забывают их удалить, если файлы не прикрепляют к сообщению.</font></div>
                                            <div class="radioContainer"><input name="re_files_t" type="radio" id="re_files_t_1" value="1"></div>
 <label class="radioLabel" for="re_files_t_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_files_t" type="radio" id="re_files_t_0" value="0" checked></div>
 <label class="radioLabel" for="re_files_t_0">Нет</label>
					    </div>
                                        </div>

                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <input type="submit" name="do_rebuild2" value="Выполнить" class="btnBlue" />
                                            <input type="hidden" name="secret_key" value="{$secret_key}" />
                                        </div>
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
</form>

	            <div class="clear" style="height:10px;"></div>
<form  method="post" name="build" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Пересчёт статистики пользователей</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption2">Пересчёт сообщений:<br><font class="smalltext">Пересчёт количества сообщений на форуме.</font></div>
                                            <div class="radioContainer"><input name="re_posts" type="radio" id="re_posts_1" value="1"></div>
 <label class="radioLabel" for="re_posts_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_posts" type="radio" id="re_posts_0" value="0" checked></div>
 <label class="radioLabel" for="re_posts_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт тем:<br><font class="smalltext">Пересчёт количества тем на форуме.</font></div>
                                            <div class="radioContainer"><input name="re_topics" type="radio" id="re_topics_1" value="1"></div>
 <label class="radioLabel" for="re_topics_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_topics" type="radio" id="re_topics_0" value="0" checked></div>
 <label class="radioLabel" for="re_topics_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Пересчёт предупреждений:</div>
                                            <div class="radioContainer"><input name="re_warning" type="radio" id="re_warning_1" value="1"></div>
 <label class="radioLabel" for="re_warning_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_warning" type="radio" id="re_warning_0" value="0" checked></div>
 <label class="radioLabel" for="re_warning_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">Отметить все темы как прочитанные:<br><font class="smalltext">У пользователей все темы будут отмечены как прочитанные, если дата последнего ответа меньше или равна текущей даты.<br />Это освободит не много места в БД.</font></div>
                                            <div class="radioContainer"><input name="re_view_topic" type="radio" id="re_view_topic_1" value="1"></div>
 <label class="radioLabel" for="re_view_topic_1">Да</label>
						<div class="radioContainer optionFalse"><input name="re_view_topic" type="radio" id="re_view_topic_0" value="0" checked></div>
 <label class="radioLabel" for="re_view_topic_0">Нет</label>
					    </div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <input type="submit" name="do_rebuild3" value="Выполнить" class="btnBlue" />
                                            <input type="hidden" name="secret_key" value="{$secret_key}" />
                                        </div>
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
</form>
HTML;

}

?>