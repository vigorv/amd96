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

include_once ENGINE_DIR . '/modules/logicboard/functions.php';
include_once ENGINE_DIR . '/data/logicboard_conf.php';

if (!isset($LB_rows_save))
    $LB_rows_save = array("twitter", "vk", "skype", "sex"); // удалите не нужное Вам поле из скобок. Значение по умолчанию: "twitter", "vk", "skype", "sex"

if ($action_logicboard['profile_edit'] == 1) // вывод полей в админке
{
    $action_logicboard['profile_edit'] = 0;
    $LB_output = "";
       
    if (in_array("sex", $LB_rows_save))
    {
        $sex_arr = array();
        $sex_arr[0] = "Не выбран";
        $sex_arr[1] = "Мужской";
        $sex_arr[2] = "Женский";
        $lb_sex = LB_select_code("lb_sex", $sex_arr, $row['lb_sex']);
    }
    
    $days = array();     
    for($i=1;$i<=31;$i++)
    {
        $days[$i] = $i;
    } 
    $lb_days = LB_select_code("lb_b_day", $days, $row['lb_b_day']);
            
    $month = array();     
    for($i=1;$i<=12;$i++)
    {
        if ($i < 10)
            $month["0".$i] = "0".$i;
        else
            $month[$i] = $i;
    } 
    $lb_month = LB_select_code("lb_b_month", $month, $row['lb_b_month']);
            
    $years = array();
    $year_now = date("Y"); 
    for($i=$year_now;$i>=1930;$i--)
    {
        $years[$i] = $i;
    }    
    $lb_years = LB_select_code("lb_b_year", $years, $row['lb_b_year']);
    
    $lb_restricted_selected = array (0 => '', 1 => '', 2 => '', 3 => '' );
	$lb_restricted_selected[$row['lb_limit_publ']] = 'selected';
    
    $end_lb_limit = "";
    if ($row['lb_limit_days'])
        $end_lb_limit = $lang['edit_tdate'] . " " . langdate( "j M Y H:i", $row['lb_limit_date'] );
    elseif (!$row['lb_limit_days'] AND $row['lb_limit_publ'])
        $end_lb_limit = "Навсегда"; 
    
    $lb_warnings = "<a href=\"".$logicboard_conf['url']."?do=users&op=warning&member_name=".urlencode($row['name'])."\" target=\"blank\">".$row['count_warning']."</a> (<a href=\"".$logicboard_conf['url']."?do=users&op=warning_add&member_name=".urlencode($row['name'])."\" target=\"blank\">Добавить</a>)";
            
$LB_output = <<<HTML

<tr>
<td colspan="3"><div class="hr_line"></div></td>
</tr>  
<tr>
<td style="padding:4px;">Личное звание:</td>
<td colspan="2"><input type="text" name="lb_personal_title" id="lb_personal_title" value="{$row['personal_title']}" class="edit bk" style="width:220px" />
</td>
</tr>
<tr>
<td colspan="3"><div class="hr_line"></div></td>
</tr> 
<tr>
<td style="padding:4px;">Предупреждений:</td>
<td colspan="2">{$lb_warnings}
</td>
</tr>
<tr>
<td colspan="3"><div class="hr_line"></div></td>
</tr> 
<tr>
<td style="padding:4px;">Запрет на публикацию (форум):</td>
<td colspan="2"><select name="lb_limit_publ"><option value="0" $lb_restricted_selected[0]>Отсутствует</option>
<option value="1" $lb_restricted_selected[1]>Темы</option>
<option value="2" $lb_restricted_selected[2]>Сообщения</option>
<option value="3" $lb_restricted_selected[3]>Темы и сообщения</option>
</select></td>
</tr>
<tr>
<td style="padding:4px;">Сроком на:</td>
<td colspan="2"><input size="5" name="lb_limit_days" class="edit bk" value="{$row['lb_limit_days']}"><a href="#" class="hintanchor" onMouseover="showhint('Укажите количество дней, в течении которых пользователю будет запрещена публикация тем или сообщений. Оставьте <b>0</b>, если срок блокировки неограничен по времени', this, event, '250px')">[?]</a>  {$end_lb_limit}</td>
<input type="hidden" name="prev_lb_limit" value="{$row['lb_limit_days']}">
</tr>
<tr>
<td colspan="3"><div class="hr_line"></div></td>
</tr> 
<tr>
<td style="padding:4px;">Удалить все сообщения на форуме?</td>
<td colspan="2"><input type="checkbox" name="del_all_posts" value="1" /> <font color="#999898">Всего сообщений: {$row['posts_num']}</font></td>
</tr>
<tr>
<td style="padding:4px;">Удалить все темы на форуме?</td>
<td colspan="2"><input type="checkbox" name="del_all_topics" value="1" /> <font color="#999898">Всего тем: {$row['topics_num']}</font></td>
</tr>
<tr>
<td colspan="3"><div class="hr_line"></div></td>
</tr>   
HTML;

    if (in_array("skype", $LB_rows_save))
    {
$LB_output .= <<<HTML

<tr>
<td style="padding:4px;">Skype:</td>
<td colspan="2"><input type="text" name="lb_skype" id="lb_skype" value="{$row['lb_skype']}" class="edit bk" style="width:220px" />
</td>
</tr>
HTML;
    }
    
    if (in_array("twitter", $LB_rows_save))
    {
$LB_output .= <<<HTML

<tr>
<td style="padding:4px;">Twitter:</td>
<td colspan="2"><input type="text" name="lb_twitter" id="lb_twitter" value="{$row['lb_twitter']}" class="edit bk" style="width:220px" /> <font color="#999898">Введите логин</font></td>
</tr>
HTML;
    }
    
    if (in_array("vk", $LB_rows_save))
    {
$LB_output .= <<<HTML
<tr>
<td style="padding:4px;">В контакте:</td>
<td colspan="2"><input type="text" name="lb_vkontakte" id="lb_vkontakte" value="{$row['lb_vkontakte']}" class="edit bk" style="width:220px" /> <font color="#999898">Введите логин или ID</font></td>
</tr>
<tr>
HTML;
    }
    
$LB_output .= <<<HTML

<tr>
<td style="padding:4px;">Дата рождения:</td>
<td colspan="2">{$lb_days} {$lb_month} {$lb_years}</td>
</tr>
HTML;

    if (in_array("sex", $LB_rows_save))
    {
$LB_output .= <<<HTML

<tr>
<td style="padding:4px;">Пол:</td>
<td colspan="2">{$lb_sex}</td>
</tr>                                      
HTML;
    }
}
          
if ($action_logicboard['profile_edit'] == 2) // Редактирование профиля в админке
{
    $where_lb = array();
                                                                     
    if( trim( $editlogin ) != "" )
    {
        $ch_name = $editlogin;
                        
        $db->query( "UPDATE " . LB_DB_PREFIX . "_forums SET last_post_member = '{$ch_name}' WHERE last_post_member_id = '{$id}'" );  
        $db->query( "UPDATE " . LB_DB_PREFIX . "_forums_moderator SET fm_member_name = '{$ch_name}' WHERE fm_member_id = '{$id}'" );
        $db->query( "UPDATE " . LB_DB_PREFIX . "_posts SET post_member_name = '{$ch_name}' WHERE post_member_id = '{$id}'" );
        sleep (2);
        $db->query( "UPDATE " . LB_DB_PREFIX . "_posts SET edit_member_name = '{$ch_name}' WHERE edit_member_id = '{$id}'" );  
        sleep (2);
        $db->query( "UPDATE " . LB_DB_PREFIX . "_posts SET moder_member_name = '{$ch_name}' WHERE moder_member_id = '{$id}'" );  
        $db->query( "UPDATE " . LB_DB_PREFIX . "_topics SET member_name_last = '{$ch_name}' WHERE last_post_member = '{$id}'" );  
        sleep (2);
        $db->query( "UPDATE " . LB_DB_PREFIX . "_topics SET member_name_open = '{$ch_name}' WHERE member_id_open = '{$id}'" );  
        sleep (2);
        $db->query( "UPDATE " . LB_DB_PREFIX . "_forums_notice SET author = '{$ch_name}' WHERE author_id = '{$id}'" );  
        sleep (2);
        $db->query( "UPDATE " . LB_DB_PREFIX . "_members_warning SET moder_name = '{$ch_name}' WHERE moder_id = '{$id}'" );  
    }
              
    $personal_title = $db->safesql(htmlspecialchars($_POST['lb_personal_title']));
    if (LB_utf8_strlen($personal_title) > 50) $personal_title = "";
            
    if (in_array("skype", $LB_rows_save))
    {                                        
        $skype = $db->safesql(htmlspecialchars($_POST['lb_skype']));
        if (LB_utf8_strlen($skype) > 200) $skype = "";
    }            
    
    if (in_array("twitter", $LB_rows_save))
    {
        $twitter = $db->safesql(htmlspecialchars($_POST['lb_twitter']));
        if (LB_utf8_strlen($twitter) > 200) $twitter = "";
    }            
    
    if (in_array("vk", $LB_rows_save))
    {
        $vkontakte = $db->safesql(htmlspecialchars($_POST['lb_vkontakte']));
        if (LB_utf8_strlen($vkontakte) > 200) $vkontakte = "";
    }
    
    if (in_array("sex", $LB_rows_save))
    {                              
        $sex = intval($_POST['lb_sex']);
        if ($sex < 0 OR $sex > 2)
            $sex = 0;
    }     
         
    $b_day = intval($_POST['lb_b_day']);
    if ($b_day < 1 OR $b_day > 31)
        $b_day = 0;
                
    $b_month = intval($_POST['lb_b_month']);
    if ($b_month < 1 OR $b_month > 12)
        $b_month = 0;
                
    $year_now = date("Y"); 
    $b_year = intval($_POST['lb_b_year']);
    if ($b_year < 1930 OR $b_year > $year_now)
        $b_year = 0;
                
    if ($b_year == date("Y"))
    {
        $b_day = 0;
        $b_month = 0;
        $b_year = 0;
    }
    
    $lb_limit_publ = intval($_POST['lb_limit_publ']);
    $lb_limit_days = intval( $_POST['lb_limit_days'] );
    
    if( $lb_limit_days != intval($_POST['prev_lb_limit']) AND $lb_limit_publ )
    {
        $lb_limit_date = time() + ($config['date_adjust'] * 60);
        $lb_limit_date = $lb_limit_days ? $lb_limit_date + ($lb_limit_days * 60 * 60 * 24) : '';
        $where_lb[] = "lb_limit_publ = '{$lb_limit_publ}'";
        $where_lb[] = "lb_limit_days = '{$lb_limit_days}'";
        $where_lb[] = "lb_limit_date = '{$lb_limit_date}'";		
    }
    elseif ($lb_limit_publ)
    {
        $where_lb[] = "lb_limit_publ = '{$lb_limit_publ}'";
    }
    elseif (!$lb_limit_publ)
    {
        $where_lb[] = "lb_limit_publ = '0'";
        $where_lb[] = "lb_limit_days = '0'";
        $where_lb[] = "lb_limit_date = '0'";		
    }
            
    $where_lb[] = "personal_title = '{$personal_title}'";
    if (in_array("skype", $LB_rows_save)) $where_lb[] = "lb_skype = '{$skype}'";
    if (in_array("twitter", $LB_rows_save)) $where_lb[] = "lb_twitter = '{$twitter}'";
    if (in_array("vk", $LB_rows_save)) $where_lb[] = "lb_vkontakte = '{$vkontakte}'";
    if (in_array("sex", $LB_rows_save)) $where_lb[] = "lb_sex = '{$sex}'";
    $where_lb[] = "lb_b_day = '{$b_day}'";
    $where_lb[] = "lb_b_month = '{$b_month}'";
    $where_lb[] = "lb_b_year = '{$b_year}'";
                                                            
    $sql_update .= ", ".implode(", ", $where_lb);
    
    $this_time = time() + ($config['date_adjust'] * 60);
    $db->query( "INSERT INTO " . LB_DB_PREFIX . "_cache_update SET name = 'banned', lastdate = '{$this_time}' ON DUPLICATE KEY UPDATE name = 'banned', lastdate = '{$this_time}'" );
    
    if (intval($_POST['del_all_posts']))
    {
        $posts_db = $db->query( "SELECT p.*, t.forum_id, t.title, t.id, t.date_last, t.hiden, t.last_post_id as topic_last_post_id, f.last_post_date, f.last_post_id as forum_last_post_id, f.last_topic_id FROM " . LB_DB_PREFIX . "_posts p LEFT JOIN " . LB_DB_PREFIX . "_topics t ON p.topic_id=t.id LEFT JOIN " . LB_DB_PREFIX . "_forums f ON t.forum_id=f.id WHERE p.post_member_id = '{$id}' AND p.new_topic = '0'" );  
        $db->query( "DELETE FROM " . LB_DB_PREFIX . "_posts WHERE post_member_id = '{$id}' AND new_topic = '0'" );
                
        $topics_last = array();
                
        $i = 0;
        while ( $row = $db->get_row($posts_db) )
        {                      
            if ($row['hide'])
            {
                $where_post = "post_hiden = post_hiden-1";
                $where_forum = "posts_hiden = posts_hiden-1";
            }
            else
            {
                $i ++;
                $where_post = "post_num = post_num-1";
                $where_forum = "posts = posts-1";
            }
                        
            if ($row['topic_last_post_id'] == $row['pid'])
                $topics_last[$row['topic_id']] = $row['topic_id'];
                   
            $db->query( "UPDATE " . LB_DB_PREFIX . "_topics SET ".$where_post." WHERE id = '{$row['topic_id']}'" ); 
            $db->query( "UPDATE " . LB_DB_PREFIX . "_forums SET ".$where_forum." WHERE id = '{$row['forum_id']}'" );
        }
        $db->free($posts_db);
        $db->query( "UPDATE " . USERPREFIX . "_users SET posts_num = posts_num-{$i} WHERE user_id = '{$id}'" );
                
        $forum_last = array();
                
        if (end($topics_last) != "")
        {
            foreach ($topics_last as $tid)
            {
                $tid = intval($tid);
                $post_last = $db->super_query( "SELECT p.*, t.forum_id, t.hiden, f.last_topic_id FROM " . LB_DB_PREFIX . "_posts p LEFT JOIN " . LB_DB_PREFIX . "_topics t ON p.topic_id=t.id LEFT JOIN " . LB_DB_PREFIX . "_forums f ON t.forum_id=f.id WHERE p.topic_id = '{$tid}' AND hide = '0' ORDER by p.post_date DESC LIMIT 1" );
                if (!$post_last['pid'])
                {
                    $db->free($post_last);
                    $post_last = $db->superquery( "SELECT p.*, t.forum_id, t.hiden, f.last_topic_id FROM " . LB_DB_PREFIX . "_posts p LEFT JOIN " . LB_DB_PREFIX . "_topics t ON p.topic_id=t.id LEFT JOIN " . LB_DB_PREFIX . "_forums f ON t.forum_id=f.id WHERE p.topic_id = '{$tid}' AND new_topic = '1' LIMIT 1" );

                }   
                if ($post_last['last_topic_id'] == $post_last['topic_id'])
                    $forum_last[$post_last['forum_id']] = $post_last['forum_id'];
                        
                $db->query( "UPDATE " . LB_DB_PREFIX . "_topics SET member_name_last = '{$post_last['post_member_name']}', last_post_member = '{$post_last['post_member_id']}', last_post_id = '{$post_last['pid']}', date_last = '{$post_last['post_date']}' WHERE id = '{$tid}'" );
                $db->free($post_last);
            }
        }
                
        if (end($forum_last) != "")
        {
            foreach ($forum_last as $fid)
            {
                $fid = intval($fid);
                $topic_last = $db->super_query( "SELECT t.*, f.last_topic_id, f.last_post_date FROM " . LB_DB_PREFIX . "_topics t LEFT JOIN " . LB_DB_PREFIX . "_forums f ON t.forum_id=f.id WHERE t.forum_id = '{$fid}' AND t.hiden = '0' ORDER by t.date_last DESC LIMIT 1" );
            
                if (!$topic_last['id'])
                {
                    $where = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                }
                else
                {
                    $topic_last['title'] = $db->safesql($topic_last['title']);
                    $where = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                }  
                $db->free($topic_last);

                $db->query( "UPDATE " . LB_DB_PREFIX . "_forums SET ".$where." WHERE id = '{$topic_last['forum_id']}'" );
                unset($where);
            }
        }
    }

    if (intval($_POST['del_all_topics']))
    {
        $topics_db = $db->query( "SELECT t.*, f.last_topic_id, f.last_post_date FROM " . LB_DB_PREFIX . "_topics t LEFT JOIN " . LB_DB_PREFIX . "_forums f ON t.forum_id=f.id WHERE t.member_id_open = '{$id}'" );
        while ( $row = $db->get_row($topics_db) )
        {
            $basket_f = $db->super_query( "SELECT postcount FROM " . LB_DB_PREFIX . "_forums WHERE id = '{$row['basket_fid']}'" );
            if (!$row['basket'] OR ($row['basket'] AND $basket_f['postcount']))
            {
                $m_posts = $db->query( "SELECT post_member_id FROM " . LB_DB_PREFIX . "_posts WHERE topic_id = '{$row['id']}' AND hide = '0' AND new_topic = '0'" );
                while ( $row2 = $db->get_row($m_posts) )
                {
                    if (!isset($m_posts_count[$row2['post_member_id']]))
                        $m_posts_count[$row2['post_member_id']] = 1;
                    else
                        $m_posts_count[$row2['post_member_id']] += 1;
    
                }
                $db->free($m_posts);
                $db->query( "UPDATE " . USERPREFIX . "_users SET topics_num=topics_num-1 WHERE user_id = '{$row['member_id_open']}'" );
            }
            
            $db->query( "DELETE FROM " . LB_DB_PREFIX . "_topics WHERE id = '{$row['id']}'" );
            $db->query( "DELETE FROM " . LB_DB_PREFIX . "_posts WHERE topic_id = '{$row['id']}'" );
                         
            $where = array();
                                
            if ($row['hiden'])
                $where[] = "topics_hiden = topics_hiden-1";
            else
                $where[] = "topics = topics-1";   
                
            if ($row['post_hiden'])
                $where[] = "posts_hiden = posts_hiden-{$row['post_hiden']}";         
                
            if ($row['post_num'])
                $where[] = "posts = posts-{$row['post_num']}";   
                
            $topic_last = $db->super_query( "SELECT t.id, t.title, t.last_post_id, t.member_name_last, t.last_post_member, t.date_last, f.last_topic_id FROM " . LB_DB_PREFIX . "_topics t LEFT JOIN " . LB_DB_PREFIX . "_forums f ON t.forum_id=f.id WHERE t.forum_id = '{$row['forum_id']}' AND hiden = '0' ORDER BY date_last DESC LIMIT 1" );
            if ($topic_last['last_topic_id'] != $topic_last['id'] OR !$topic_last['id'])
            {
                if (!$topic_last['id'])
                {
                    $where[] = "last_post_member = '', last_post_member_id = '0', last_post_date = '', last_title = '', last_topic_id = '0', last_post_id = '0'";
                }
                else
                {
                    $topic_last['title'] = $db->safesql($topic_last['title']);
                    $where[] = "last_post_member = '{$topic_last['member_name_last']}', last_post_member_id = '{$topic_last['last_post_member']}', last_post_date = '{$topic_last['date_last']}', last_title = '{$topic_last['title']}', last_topic_id = '{$topic_last['id']}', last_post_id = '{$topic_last['last_post_id']}'";
                }
            } 
            $db->free($topic_last);

            $where_db = implode(", ", $where);
            $db->query( "UPDATE " . LB_DB_PREFIX . "_forums SET ".$where_db." WHERE id = '{$row['forum_id']}'" );
            unset($where);
        }
        $db->free($topics_db);
                
        if (count($m_posts_count))
        {
            foreach ($m_posts_count as $key => $value)
            {
                $db->query( "UPDATE " . USERPREFIX . "_users SET posts_num=posts_num-{$value} WHERE user_id = '{$key}'" );
            }
        }
    }
}
    
if ($action_logicboard['profile_edit'] == 3) // Редактирование профиля на сайте
{            
    if (in_array("skype", $LB_rows_save))
    {
        $skype = $db->safesql(htmlspecialchars($_POST['lb_skype']));
        if (LB_utf8_strlen($skype) > 200) $skype = "";
    }
    
    if (in_array("twitter", $LB_rows_save))
    {            
        $twitter = $db->safesql(htmlspecialchars($_POST['lb_twitter']));
        if (LB_utf8_strlen($twitter) > 200) $twitter = "";
    }
    
    if (in_array("vk", $LB_rows_save))
    {            
        $vkontakte = $db->safesql(htmlspecialchars($_POST['lb_vkontakte']));
        if (LB_utf8_strlen($vkontakte) > 200) $vkontakte = "";
    }            
    
    if (in_array("sex", $LB_rows_save))   
    {                 
        $sex = intval($_POST['lb_sex']);
        if ($sex < 0 OR $sex > 2)
            $sex = 0;
    }
               
    $b_day = intval($_POST['lb_b_day']);
    if ($b_day < 1 OR $b_day > 31)
        $b_day = 0;
                
    $b_month = intval($_POST['lb_b_month']);
    if ($b_month < 1 OR $b_month > 12)
        $b_month = 0;
                
    $year_now = date("Y"); 
    $b_year = intval($_POST['lb_b_year']);
    if ($b_year < 1930 OR $b_year > $year_now)
        $b_year = 0;
                
    if ($b_year == date("Y"))
    {
        $b_day = 0;
        $b_month = 0;
        $b_year = 0;
    }
    
    $where_lb = array();
            
    if (in_array("skype", $LB_rows_save)) $where_lb[] = "lb_skype = '{$skype}'";
    if (in_array("twitter", $LB_rows_save)) $where_lb[] = "lb_twitter = '{$twitter}'";
    if (in_array("vk", $LB_rows_save)) $where_lb[] = "lb_vkontakte = '{$vkontakte}'";
    if (in_array("sex", $LB_rows_save)) $where_lb[] = "lb_sex = '{$sex}'";
    $where_lb[] = "lb_b_day = '{$b_day}'";
    $where_lb[] = "lb_b_month = '{$b_month}'";
    $where_lb[] = "lb_b_year = '{$b_year}'";
                    
    $where_lb = implode(", ", $where_lb);
}

if ($action_logicboard['profile_edit'] == 4) // вывод полей и тегов в профиле
{            
    $LB_output = "";
		
    if (in_array("sex", $LB_rows_save))
    {
        $sex_arr = array();
        $sex_arr[0] = "Не выбран";
        $sex_arr[1] = "Мужской";
        $sex_arr[2] = "Женский";
        $lb_sex = LB_select_code("lb_sex", $sex_arr, $row['lb_sex']);
    }
            
    $days = array();     
    for($i=1;$i<=31;$i++)
    {
        $days[$i] = $i;
    } 
    $lb_days = LB_select_code("lb_b_day", $days, $row['lb_b_day']);
            
    $month = array();     
    for($i=1;$i<=12;$i++)
    {
        if ($i < 10)
            $month["0".$i] = "0".$i;
        else
            $month[$i] = $i;
    } 
    $lb_month = LB_select_code("lb_b_month", $month, $row['lb_b_month']);
            
    $years = array();
    $year_now = date("Y"); 
    for($i=$year_now;$i>=1930;$i--)
    {
        $years[$i] = $i;
    }    
    $lb_years = LB_select_code("lb_b_year", $years, $row['lb_b_year']);
    
    if (in_array("skype", $LB_rows_save))
    {        
$LB_output .= <<<HTML
            
<tr>
<td>Skype:</td>
<td class="xprofile" colspan="2"><input type="text" name="lb_skype" id="lb_skype" value="{$row['lb_skype']}" style="width:220px" />
</td>
</tr>
HTML;
    }
    
    if (in_array("twitter", $LB_rows_save))
    {
$LB_output .= <<<HTML

<tr>
<td>Twitter:</td>
<td class="xprofile" colspan="2"><input type="text" name="lb_twitter" id="lb_twitter" value="{$row['lb_twitter']}" style="width:220px" /> <font class="smalltext">Введите логин</font></td>
</tr>
<tr>
HTML;
    }
    
    if (in_array("vk", $LB_rows_save))
    {
$LB_output .= <<<HTML

<td>В контакте:</td>
<td class="xprofile" colspan="2"><input type="text" name="lb_vkontakte" id="lb_vkontakte" value="{$row['lb_vkontakte']}" style="width:220px" /> <font class="smalltext">Введите логин или ID</font></td>
</tr>
HTML;
    }
    
$LB_output .= <<<HTML

<tr>
<td>Дата рождения:</td>
<td class="xprofile" colspan="2">{$lb_days} {$lb_month} {$lb_years}</td>
</tr>
HTML;

    if (in_array("sex", $LB_rows_save))
    {
$LB_output .= <<<HTML

<tr>
<td>Пол:</td>
<td class="xprofile" colspan="2">{$lb_sex}</td>
</tr>                                      
HTML;
    }
            
    $row['mstatus'] = intval($row['mstatus']);
    if ($row['mstatus'])
    {
        $lb_status = $db->super_query("SELECT text, date, id FROM " . LB_DB_PREFIX . "_members_status WHERE id = '{$row['mstatus']}'");
        
        if ($member_id['user_group'] == 1 AND $member_id['user_id'] != $row['user_id'])
            $tpl->set('{lb_status}', $lb_status['text']." [ <a href=\"".LB_profile_edit_link($row['name'], "status")."\">Изменить</a> ]");
        else
            $tpl->set('{lb_status}', $lb_status['text']);
        $tpl->set('{lb_status_date}',  langdate( "j.m.Y H:i", $lb_status['date'] ));
        $tpl->set_block( "'\\[lb_mstatus\\](.*?)\\[/lb_mstatus\\]'si", "\\1" );
    }
    else
    {
        $tpl->set('{lb_status}', "");
        $tpl->set('{lb_status_date}', "");
        $tpl->set_block( "'\\[lb_mstatus\\](.*?)\\[/lb_mstatus\\]'si", "" );
    }
    
    if ($row['personal_title'])
    {
        $tpl->set('{personal_title}', $row['personal_title']);
        $tpl->set_block( "'\\[personal_title\\](.*?)\\[/personal_title\\]'si", "\\1" );
    }
    else
    {
        $tpl->set('{personal_title}', "");
        $tpl->set_block( "'\\[personal_title\\](.*?)\\[/personal_title\\]'si", "" );
    }

    $tpl->set( '{lb_posts}', $row['posts_num'] );
    if ($row['posts_num'])
    {
        $tpl->set( '[lb_posts_link]', "<a href=\"".LB_member_posts_link($row['name'], $row['user_id'])."\">" );
        $tpl->set( '[/lb_posts_link]', "</a>" );
    }
    else
    {
        $tpl->set( '[lb_posts_link]', "" );
        $tpl->set( '[/lb_posts_link]', "" );
    }

    $tpl->set( '{lb_topics}', $row['topics_num'] );
    if ($row['topics_num'])
    {
        $tpl->set( '[lb_topics_link]', "<a href=\"".LB_member_topics_link($row['name'], $row['user_id'])."\">" );
        $tpl->set( '[/lb_topics_link]', "</a>" );
    }
    else
    {
        $tpl->set( '[lb_topics_link]', "" );
        $tpl->set( '[/lb_topics_link]', "" );
    }
        
    if (!in_array("sex", $LB_rows_save))
    {
        $tpl->set('{lb_sex}', "");
        $tpl->set_block( "'\\[lb_sex\\](.*?)\\[/lb_sex\\]'si", "" );
    }
    elseif($row['lb_sex'] == 1)
    {
        $tpl->set('{lb_sex}', "Мужской");
        $tpl->set_block( "'\\[lb_sex\\](.*?)\\[/lb_sex\\]'si", "\\1" );
    }
    elseif ($row['lb_sex'] == 2)
    {
        $tpl->set('{lb_sex}', "Женский");
        $tpl->set_block( "'\\[lb_sex\\](.*?)\\[/lb_sex\\]'si", "\\1" );
    }
    else
    {
        $tpl->set('{lb_sex}', "");
        $tpl->set_block( "'\\[lb_sex\\](.*?)\\[/lb_sex\\]'si", "" );
    }
        
    $tpl->set('{lb_b_day}', $row['lb_b_day']);
    if ($row['lb_b_month'] < 10)
        $tpl->set('{lb_b_month}', "0".$row['lb_b_month']);
    else
        $tpl->set('{lb_b_month}', $row['lb_b_month']);
    $tpl->set('{lb_b_year}', $row['lb_b_year']);
        
    $date_now_year = date("Y", $_TIME);
    $date_now_day = date("d", $_TIME); 
    $date_now_month = date("m", $_TIME); 
    
    $age_user_day = $row['lb_b_day'] - $date_now_day;
    $age_user_month = $row['lb_b_month'] - $date_now_month;
    
    if (($age_user_month < 0 AND $age_user_day < 0) OR ($age_user_month < 0 AND $age_user_day >= 0))
        $age_user = $date_now_year - $row['lb_b_year'];
    elseif (($age_user_month == 0 AND $age_user_day < 0) OR ($age_user_day == 0 AND $age_user_month == 0))
        $age_user = $date_now_year - $row['lb_b_year'];
    else
        $age_user = $date_now_year - 1 - $row['lb_b_year'];
    
    $tpl->set('{lb_age}', $age_user);
        
    if ($row['lb_b_day'])
        $tpl->set_block( "'\\[lb_b_day\\](.*?)\\[/lb_b_day\\]'si", "\\1" );
    else
        $tpl->set_block( "'\\[lb_b_day\\](.*?)\\[/lb_b_day\\]'si", "" );
            
    if ($row['lb_skype'] AND in_array("skype", $LB_rows_save))
    {
        $row['lb_skype'] = "<script type=\"text/javascript\" src=\"http://download.skype.com/share/skypebuttons/js/skypeCheck.js\"></script><a href=\"skype:".urlencode($row['lb_skype'])."?call\">".$row['lb_skype']."</a>";
        $tpl->set('{lb_skype}', $row['lb_skype']);
        $tpl->set_block( "'\\[lb_skype\\](.*?)\\[/lb_skype\\]'si", "\\1" );
    }
    else
    {
        $tpl->set('{lb_skype}', "");
        $tpl->set_block( "'\\[lb_skype\\](.*?)\\[/lb_skype\\]'si", "" );
    }
                            
    if ($row['lb_twitter'] AND in_array("twitter", $LB_rows_save))
    {
        $row['lb_twitter'] = "<noindex><a href=\"http://twitter.com/".urlencode($row['lb_twitter'])."\" target=\"_blank\" rel=\"nofollow\">".$row['lb_twitter']."</a></noindex>";
        $tpl->set('{lb_twitter}', $row['lb_twitter']);
        $tpl->set_block( "'\\[lb_twitter\\](.*?)\\[/lb_twitter\\]'si", "\\1" );
    }
    else
    {
        $tpl->set('{lb_twitter}', "");
        $tpl->set_block( "'\\[lb_twitter\\](.*?)\\[/lb_twitter\\]'si", "" );
    }
                    
    if ($row['lb_vkontakte'] AND in_array("vk", $LB_rows_save))
    {
        $row['lb_vkontakte'] = "<noindex><a href=\"http://vkontakte.ru/".urlencode($row['lb_vkontakte'])."\" target=\"_blank\" rel=\"nofollow\">".$row['lb_vkontakte']."</a></noindex>";
        $tpl->set('{lb_vkontakte}', $row['lb_vkontakte']);
        $tpl->set_block( "'\\[lb_vkontakte\\](.*?)\\[/lb_vkontakte\\]'si", "\\1" );
    }
    else
    {
        $tpl->set('{lb_vkontakte}', "");
        $tpl->set_block( "'\\[lb_vkontakte\\](.*?)\\[/lb_vkontakte\\]'si", "" );
    }     
     
    if ($cache_lb_config['warning_on']['conf_value'] AND !$cache_lb_group[$row['user_group']]['g_warning'])
    {
        $tpl->set_block( "'\\[lb_warning\\](.*?)\\[/lb_warning\\]'si", "\\1" );
        if ($cache_lb_config['warning_show']['conf_value'])
            $tpl->set('{lb_warning}', "<a href=\"".LB_warning_link($row['name'], $row['user_id'])."\">".$row['count_warning']."</a>");
        else
            $tpl->set('{lb_warning}', $row['count_warning']);
    }
    else
    {
        $tpl->set('{lb_warning}', "");
        $tpl->set_block( "'\\[lb_warning\\](.*?)\\[/lb_warning\\]'si", "" );
    }  
    
    if ($cache_lb_config['warning_on']['conf_value'] AND $cache_lb_group[$member_id['user_group']]['g_warning'] AND !$cache_lb_group[$row['user_group']]['g_warning'] AND $row['user_group'] != 1)
    {
        $tpl->set_block( "'\\[lb_moder_warning\\](.*?)\\[/lb_moder_warning\\]'si", "\\1" );
        $tpl->set('{lb_moder_warning}', LB_warning_link($row['name'], $row['user_id'], 1));
    }
    else
    {
        $tpl->set('{lb_moder_warning}', "");
        $tpl->set_block( "'\\[lb_moder_warning\\](.*?)\\[/lb_moder_warning\\]'si", "" );
    }
    
    $onl_limit = $_TIME - (intval($cache_lb_config['online_time']['conf_value']) * 60);
    $lb_online = $db->super_query("SELECT mo_id, mo_date, mo_location FROM " . LB_DB_PREFIX . "_members_online WHERE mo_member_id = '{$row['user_id']}'");
    
    if (LB_member_online($lb_online['mo_id'], $lb_online['mo_date'], $onl_limit))
    {
        $tpl->set_block( "'\\[lb_online\\](.*?)\\[/lb_online\\]'si", "\\1" );
        $tpl->set('{lb_online}', $lb_online['mo_location']);
        $tpl->set_block( "'\\[lb_offline\\](.*?)\\[/lb_offline\\]'si", "" );
    }
    else
    {
        $tpl->set_block( "'\\[lb_offline\\](.*?)\\[/lb_offline\\]'si", "\\1" );
        $tpl->set_block( "'\\[lb_online\\](.*?)\\[/lb_online\\]'si", "" );
    }
}

$action_logicboard['profile_edit'] = 0;

?>