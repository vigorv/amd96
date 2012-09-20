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

if(isset($_GET['type']) AND $id)
{
	function infopage ($data, $onl_location, $array_data = false)
	{
		global $cache_config, $LB_charset;
echo <<<HTML

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$LB_charset}" />
<title>{$onl_location}</title>
<link type="text/css" media="all" rel="StyleSheet" href="{$cache_config['general_site']['conf_value']}control_center/template/style.css" />
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
<div style="padding:5px;">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">{$onl_location}</div>
                    </div>
                    
                    <div class="borderL">
                        <div class="borderR">
                            <table>
                                <tr>
                                    <td>
                        <table class="colorTable" align=left>
                        {$data}
HTML;

if ($array_data)
{
echo <<<HTML

                        <tr class="appLine"><td align=left><b>Данные REQUEST:</b></td>
			<td align=left><pre>
HTML;
	print_r ($array_data);
echo <<<HTML

                        </pre></td></tr>
HTML;
}

echo <<<HTML

                        </table>

</td></tr>
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
HTML;
	
	}
    
    function error_data_log ($error = "Выбранной записи нет.")
	{
	   global $control_center;
       
	   $control_center->errors = array ();
	   $control_center->errors[] = "Выбранной записи нет.";
	   $control_center->errors_title = "Ошибка.";
	   $control_center->message();
    }

	$db_name = "";
	if ($_GET['type'] == "login")
	{
		$db_name = "logs_login_cc";
		$onl_location = "Данные авторизации";
        if(!control_center_admins($member_cca['logs']['login']))
            exit ("Доступ закры.");
	}
	elseif ($_GET['type'] == "files")
	{
		$onl_location = "Прямые обращения к файлу";
		$db_name = "files";
        if(!control_center_admins($member_cca['logs']['files']))
            exit ("Доступ закры.");
	}
	elseif ($_GET['type'] == "mysql_errors")
	{
		$onl_location = "MySQL ошибки";
		$db_name = "logs_mysql";
        if(!control_center_admins($member_cca['logs']['mysql']))
            exit ("Доступ закры.");
	}
    elseif ($_GET['type'] == "lostpass")
	{
		$onl_location = "Восстановление паролей";
		$db_name = "logs_password";
        if(!control_center_admins($member_cca['logs']['lostpass']))
            exit ("Доступ закры.");
	}
    elseif ($_GET['type'] == "delivery")
	{
		$onl_location = "Рассылка";
		$db_name = "logs_delivery";
        if(!control_center_admins($member_cca['users']['delivery']))
            exit ("Доступ закры.");
	}
    elseif ($_GET['type'] == "topics")
	{
		$onl_location = "Действия с темами";
		$db_name = "logs_topics";
        if(!control_center_admins($member_cca['logs']['topics']))
            exit ("Доступ закры.");
	}
    elseif ($_GET['type'] == "posts")
	{
		$onl_location = "Действия с сообщениями";
		$db_name = "logs_posts";
        if(!control_center_admins($member_cca['logs']['posts']))
            exit ("Доступ закры.");
	}
    elseif ($_GET['type'] == "complaint")
	{
		$onl_location = "Жалоба на сообщение";
		$db_name = "complaint";
        if(!control_center_admins($member_cca['complaint']['complaint']))
            exit ("Доступ закры.");
	}

	if ($db_name == "logs_login_cc")
	{
		$logs = $DB->one_select( "*", $db_name, "id='{$id}'" );
		if ($logs['id'])
		{
			$date = formatdate($logs['date']);
			$info = unserialize($logs['info']);
			if ($logs['login'])
				$login_s = "<font color=green>Успешно</font>";
			else
				$login_s = "<font color=red>Не удачно</font>";

$data = <<<HTML
<tr class="appLine"><td align=left width=100><b>Ник:</b></td><td align=left>{$logs['member_name']}</td></tr>
<tr class="appLine dark"><td align=left width=100><b>Пароль:</b></td><td align=left>{$info['password']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=100><b>Статус:</b></td><td align=left>{$login_s}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=100><b>IP:</b></td><td align=left>{$logs['ip']}</td></tr>
<tr class="appLine"><td align=left width=100><b>Время:</b></td><td align=left>{$date}</td></tr>
<tr class="appLine dark"><td align=left width=100><b>User Agent:</b></td><td align=left>{$info['user_agent']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=100><b>Страница:</b></td><td align=left>{$info['request_url']}</td></tr>
HTML;
			infopage ($data, $onl_location);

		}
		else
		{
			error_data_log ();
		}
	}
	elseif ($db_name == "files")
	{
		$file = LB_MAIN . "/logs/logs.log";
		$found = false;
		if (file_exists($file))
		{
			$i = 1;
			$content = @file_get_contents( $file );
			$content = explode ("|||", $content);
			$content = array_reverse($content);
			foreach ($content as $massive)
			{
				if ($i == $id)
				{
					$found = true;
					$mass = unserialize($massive);
					$mass['date'] = formatdate( $mass['date'] );
$data = <<<HTML
<tr class="appLine"><td align=left width=130><b>IP:</b></td><td align=left>{$mass['ip']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>User Agent:</b></td><td align=left>{$mass['user_agent']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Время:</b></td><td align=left>{$mass['date']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=130><b>Файл:</b></td><td align=left>{$mass['file']}</td></tr>
HTML;

					infopage ($data, $onl_location, $mass['request']);

					break;
				}
				$i ++;

			}
		}

		if (!$found)
		{
            error_data_log ("Запрашиваемая запись не найдена в файле /logs/logs.log");
		}
	}
	elseif ($db_name == "logs_mysql")
	{
		$file = LB_MAIN . "/logs/logs_mysql.log";
		$found = false;
		if (file_exists($file))
		{
			$i = 1;
			$content = @file_get_contents( $file );
			$content = explode ("|==|==|", $content);
			$content = array_reverse($content);
			foreach ($content as $massive)
			{
				if ($i == $id)
				{
					$found = true;
					$mass = unserialize($massive);
					$mass['time'] = formatdate( $mass['time'] );
                    
                    $info_user = unserialize($mass['info_user']);
                    $info_error = unserialize($mass['info_error']);
                    
$data = <<<HTML
<tr class="appLine"><td align=left width=130><b>Время:</b></td><td align=left>{$mass['time']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>IP:</b></td><td align=left>{$mass['ip']}</td></tr>
<tr class="appLine"><td align=left width=130><b>User Agent:</b></td><td align=left>{$info_user['user_agent']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=130><b>Страница:</b></td><td align=left>{$info_user['file']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Номер ошибки:</b></td><td align=left>{$mass['error_number']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Ошибка:</b></td><td align=left>{$info_error['error']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Запрос:</b></td><td align=left>{$info_error['query']}</td></tr>
HTML;

					infopage ($data, $onl_location, $info_user['request']);

					break;
				}
				$i ++;

			}
		}

		if (!$found)
		{
			error_data_log ("Запрашиваемая запись не найдена в файле /logs/logs_mysql.log");
		}
	}
    elseif ($db_name == "logs_password")
	{
		$logs = $DB->one_select( "*", $db_name, "id='{$id}'" );
		if ($logs['id'])
		{
			$date = formatdate($logs['send_date']);
			$info = unserialize($logs['info']);

$data = <<<HTML
<tr class="appLine"><td align=left width=130><b>Ник:</b></td><td align=left>{$logs['member_name']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=130><b>IP:</b></td><td align=left>{$logs['ip']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Время:</b></td><td align=left>{$date}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>User Agent:</b></td><td align=left>{$info['user_agent']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>

HTML;
			infopage ($data, $onl_location, $info['request']);

		}
		else
		{
			error_data_log ();
		}
	}
    elseif ($db_name == "logs_delivery")
	{
        $DB->prefix = array( 1 => DLE_USER_PREFIX );
		$logs = $DB->one_join_select( "log.*, u.name", "LEFT", $db_name." log||users u", "log.member_id=u.user_id", "log.id='{$id}'" );
		if ($logs['id'])
		{
			$date = formatdate($logs['date']);
            
            $date_end = $logs['date_end'] - $logs['date'];
            $date_end_h = intval(($date_end/60)/60);
            if ($date_end_h < 10)
                $date_end_h = "0".$date_end_h;
         
            $date_end_m = substr(intval($date_end/60), 0, 2);
            if ($date_end_m < 10)
                $date_end_m = "0".$date_end_m;
        
            $date_end_s = intval($date_end%60);
            if ($date_end_s < 10)
                $date_end_s = "0".$date_end_s;

            $date_end = $date_end_h.":".$date_end_m.":".$date_end_s;
            
            $group_access = explode(",", $logs['mgr']);
            $group_access_out = array();
            foreach($group_access as $ga)
            {
                $group_access_out[] = $cache_group[$ga]['g_title'];
            }
            $group_access_out = implode(", ", $group_access_out);
            
            if ($logs['metod'])
                $metod = "ЛС";
            else
                $metod = "E-Mail";
                
            if ($logs['active_status'] == 1)
                $status = "<font color=green>Активна</font>";
            else
                $status = "Завершена/Остановлена";
			

$data = <<<HTML
<tr class="appLine"><td align=left width=130><b>Автор:</b></td><td align=left>{$logs['name']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>IP:</b></td><td align=left>{$logs['ip']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Дата:</b></td><td align=left>{$date}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Заняло времени:</b></td><td align=left>{$date_end}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Группы:</b></td><td align=left>{$group_access_out}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Метод:</b></td><td align=left>{$metod }</td></tr>
<tr class="appLine"><td align=left width=130><b>Интервал:</b></td><td align=left>{$logs['send_interval']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Кол-во за раз:</b></td><td align=left>{$logs['onetime']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Всего отправлено:</b></td><td align=left>{$logs['m_count']}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=130><b>Статус:</b></td><td align=left>{$status}</td></tr>
<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Заголовок:</b></td><td align=left>{$logs['title']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Сообщение:</b></td><td align=left>{$logs['text']}</td></tr>
HTML;
			infopage ($data, $onl_location);

		}
		else
		{
			error_data_log ();
		}
	}
    elseif ($db_name == "logs_topics")
	{
        $DB->prefix = array ( 1 => DLE_USER_PREFIX );
		$logs = $DB->one_join_select( "log.*, u.user_id, u.name, t.title, t.date_open, t.date_last, t.last_post_member, t.member_name_open", "LEFT", $db_name." log||users u||topics t", "log.mid=u.user_id||log.tid=t.id", "log.id='{$id}'" );
		if ($logs['id'])
		{
			$date = formatdate($logs['date']);
            $date_last = formatdate($logs['date_last']);
                
            $redirect_url_new = $redirect_url;
            $redirect_url = $redirect_url_board;
            $forum = speedbar_forum($logs['fid']);
            $forum = explode("|", $forum);
            unset ($forum[0]);
            $forum = implode("|", $forum);
            
            $forum = speedbar($forum);
            
            $redirect_url = $redirect_url_new;
			
            $logs['title'] = "<a href=\"".$redirect_url_board."?do=board&op=topic&id=".$logs['tid']."\" target=\"blank\" title=\"Открыть страницу с темой. ID: ".$logs['tid']."\">".$logs['title']."</a>";

            $act_st = "Не известно";
            
            if ($logs['act_st'] == 0)
            {
                $act_st = "<font color=red>Удалена</font>";
                
                $logs_info = unserialize($logs['info']);
                if (!$logs_info)
                {
                    $logs['title'] = $logs['info'];
                }
                else
                {
                    $post_date = formatdate($logs_info['post_date']);
                    $logs['member_name_open'] = $logs_info['member'];
                    $logs['title']= $logs_info['title'];
                    $date = formatdate($logs_info['date_open']);
                    $date_last = formatdate($logs_info['date_last']);
                }
            }
            elseif ($logs['act_st'] == 1) $act_st = "Отредактирвоана";
            elseif ($logs['act_st'] == 2) $act_st = "Закрыта";
            elseif ($logs['act_st'] == 3) $act_st = "Открыта";
            elseif ($logs['act_st'] == 4) $act_st = "Закрпелена";
            elseif ($logs['act_st'] == 5) $act_st = "Откреплена";
            elseif ($logs['act_st'] == 6) $act_st = "Очищен лист подписчиков";
            elseif ($logs['act_st'] == 7) $act_st = "Скрыта";
            elseif ($logs['act_st'] == 8) $act_st = "Опубликована";
            elseif ($logs['act_st'] == 9) $act_st = "Перемещена";
            elseif ($logs['act_st'] == 10) $act_st = "Объединена";
            elseif ($logs['act_st'] == 11) $act_st = "Добавлено голосование";
            elseif ($logs['act_st'] == 12) $act_st = "Отредактирвоано голосование";
            elseif ($logs['act_st'] == 13) $act_st = "Удалено голосование";
            
            if ($logs['info'] AND $logs['act_st'] != 0)
                $act_st .= "<br />Подробности:<br />".$logs['info'];

$data = <<<HTML

<tr class="appLine"><td align=left width=130><b>Название:</b></td><td align=left>{$logs['title']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Форум:</b></td><td align=left>{$forum}</td></tr>
<tr class="appLine"><td align=left width=130><b>Посл. ответ:</b></td><td align=left>{$date_last}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Автор темы:</b></td><td align=left>{$logs['member_name_open']}</td></tr>

<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Модератор:</b></td><td align=left>{$logs['name']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>IP:</b></td><td align=left>{$logs['ip']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Дата:</b></td><td align=left>{$date}</td></tr>

<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=130><b>Действие:</b></td><td align=left>{$act_st}</td></tr>

HTML;
			infopage ($data, $onl_location);

		}
		else
		{
			error_data_log ();
		}
	}
    elseif ($db_name == "logs_posts")
	{
        $DB->prefix = array ( 1 => DLE_USER_PREFIX );
		$logs = $DB->one_join_select( "log.*, u.user_id, u.name, t.title, t.date_open, t.date_last, t.last_post_member, t.member_name_open, p.post_member_id, p.post_member_name, p.post_date, p.text", "LEFT", $db_name." log||users u||topics t||posts p", "log.mid=u.user_id||log.tid=t.id||log.pid=p.pid", "log.id='{$id}'" );
		if ($logs['id'])
		{          
			$date = formatdate($logs['date']);
            $date_last = formatdate($logs['date_last']);
            $post_date = formatdate($logs['post_date']);
                
            $redirect_url_new = $redirect_url;
            $redirect_url = $redirect_url_board;
            $forum = speedbar_forum($logs['fid']);
            $forum = explode("|", $forum);
            unset ($forum[0]);
            $forum = implode("|", $forum);
            
            $forum = speedbar($forum);
            
            $redirect_url = $redirect_url_new;
			
            $logs['title'] = "<a href=\"".$redirect_url_board."?do=board&op=topic&id=".$logs['tid']."\" target=\"blank\" title=\"Открыть страницу с темой. ID: ".$logs['tid']."\">".$logs['title']."</a>";

            $act_st = "Не известно";
            
            if ($logs['act_st'] == 0)
            { 
                $act_st = "<font color=red>Удалено</font>";
                                
                $logs_info = unserialize($logs['info']);
                if (!$logs_info)
                {
                    $logs['text'] = $logs['info'];
                }
                else
                {
                    $post_date = formatdate($logs_info['post_date']);
                    $logs['post_member_name'] = $logs_info['member'];
                    $logs['text']= $logs_info['text'];
                }
            }
            elseif ($logs['act_st'] == 1) $act_st = "Отредактирвоано";
            elseif ($logs['act_st'] == 2) $act_st = "Закреплено";
            elseif ($logs['act_st'] == 3) $act_st = "Откреплено";
            elseif ($logs['act_st'] == 4) $act_st = "Скрыто";
            elseif ($logs['act_st'] == 5) $act_st = "Опубликовано";
            elseif ($logs['act_st'] == 6) $act_st = "Перемещено";
            elseif ($logs['act_st'] == 7) $act_st = "Объединено";
            
            if ($logs['info'] AND $logs['act_st'] != 0)
                $act_st .= "<br />Подробности:<br />".$logs['info'];
            
                
            $logs['text'] = hide_in_post($logs['text'], $logs['post_member_id']);

$data = <<<HTML

<tr class="appLine"><td align=left width=130><b>Название:</b></td><td align=left>{$logs['title']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Форум:</b></td><td align=left>{$forum}</td></tr>
<tr class="appLine"><td align=left width=130><b>Посл. ответ:</b></td><td align=left>{$date_last}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Автор темы:</b></td><td align=left>{$logs['member_name_open']}</td></tr>

<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Автор сообщения:</b></td><td align=left>{$logs['post_member_name']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Дата сообщения:</b></td><td align=left>{$post_date}</td></tr>
<tr class="appLine"><td align=left width=130><b>ID сообщения:</b></td><td align=left>{$logs['pid']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Текст сообщения:</b></td><td align=left>{$logs['text']}</td></tr>

<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Модератор:</b></td><td align=left>{$logs['name']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>IP:</b></td><td align=left>{$logs['ip']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Дата:</b></td><td align=left>{$date}</td></tr>

<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine dark"><td align=left width=130><b>Действие:</b></td><td align=left>{$act_st}</td></tr>

HTML;
			infopage ($data, $onl_location);

		}
		else
		{
			error_data_log ();
		}
	}
    elseif ($db_name == "complaint")
	{
        $DB->prefix = array ( 1 => DLE_USER_PREFIX );
		$logs = $DB->one_join_select( "log.*, u.user_id, u.name, p.post_member_id, p.post_member_name, p.post_date, p.text, p.topic_id, t.forum_id, t.title, t.date_open, t.date_last, t.last_post_member, t.member_name_open", "LEFT", $db_name." log||users u||posts p||topics t", "log.mid=u.user_id||log.cid=p.pid||p.topic_id=t.id", "log.id='{$id}'" );
		if ($logs['id'])
		{          
			$date = formatdate($logs['date']);
            $post_date = formatdate($logs['post_date']);
                
            $redirect_url_new = $redirect_url;
            $redirect_url = $redirect_url_board;
            $forum = speedbar_forum($logs['forum_id']);
            $forum = explode("|", $forum);
            unset ($forum[0]);
            $forum = implode("|", $forum);
            
            $forum = speedbar($forum);
            			
            $logs['title'] = "<a href=\"".topic_link($logs['topic_id'], $logs['forum_id'])."\" target=\"blank\" title=\"Открыть страницу с темой. ID: ".$logs['topic_id']."\">".$logs['title']."</a>";
            
            $redirect_url = $redirect_url_new;

$data = <<<HTML

<tr class="appLine"><td align=left width=130><b>Форум:</b></td><td align=left>{$forum}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Тема:</b></td><td align=left>{$logs['title']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Автор темы:</b></td><td align=left>{$logs['member_name_open']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>ID сообщения:</b></td><td align=left>{$logs['cid']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Дата сообщения:</b></td><td align=left>{$post_date}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Автор сообщения:</b></td><td align=left>{$logs['post_member_name']}</td></tr>

<tr><td align=left colspan=2><hr /></td></tr>
<tr class="appLine"><td align=left width=130><b>Автор жалобы:</b></td><td align=left>{$logs['name']}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>IP:</b></td><td align=left>{$logs['ip']}</td></tr>
<tr class="appLine"><td align=left width=130><b>Дата жалобы:</b></td><td align=left>{$date}</td></tr>
<tr class="appLine dark"><td align=left width=130><b>Текст жалобы:</b></td><td align=left>{$logs['info']}</td></tr>

HTML;
			infopage ($data, $onl_location);

		}
		else
		{
			error_data_log ();
		}
	}
	else
	{
		$control_center->errors = array ();
		$control_center->errors[] = "Вы не выбрали какую страницу с информации хотите просмотреть.";
		$control_center->errors_title = "Страница не найдена.";
		$control_center->message();
	}
}
else
{
	$control_center->errors = array ();
	$control_center->errors[] = "Вы не выбрали какую страницу с информации хотите просмотреть.";
	$control_center->errors_title = "Страница не найдена.";
	$control_center->message();
}
?>