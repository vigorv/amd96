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

$lang_g_cron = "";

$cron_date_now = $time;
$cron_date_old = $cache_cron;

$cron_date_now = date ("dmY", $cron_date_now);
$cron_date_old = date ("dmY", $cron_date_old);

if ($cron_date_old != $cron_date_now)
{
    ignore_user_abort(1);
    @set_time_limit(0);

	if ($cache_config['log_autoriz_del']['conf_value'])
	{
		$time_del = $time - (30 * 86400);
		$DB->delete("date < '{$time_del}'", "logs_login_cc");
	}

    $general_onlinetime = $time - intval($cache_config['online_time']['conf_value']) * 60;
    $DB->delete("mo_date < '{$general_onlinetime}'", "members_online");
    
	$cache_cron = $time;
    $cache->update("cron", $cache_cron);
    $cache->clear("statistics", "stats_users");
    
    $LB_flood->del_record();
    
    $b_day = date ("d", $time);
    $b_month = date ("m", $time);
    $DB->prefix = DLE_USER_PREFIX;
    $today = $DB->select( "lb_b_day, lb_b_month, lb_b_year, name, user_id", "users", "lb_b_day='{$b_day}' AND lb_b_month = '{$b_month}'", "ORDER BY lb_b_year ASC" );
    $cache_birthday = array ();
    while ( $row = $DB->get_row($today) )
    {
        $cache_birthday[$row['user_id']] = array ();
        foreach ($row as $key => $value)
            $cache_birthday[$row['user_id']][$key] = $value;
    }
    $DB->free($today);
    if (count($cache_birthday))
    {
        $cache->update("birthday", $cache_birthday);
        $cache_birthday = $cache->take("birthday");
    }
    else
        $cache->update("birthday", "no_cache");
}

$cache_birthday = $cache->take("birthday");
if (!$cache_birthday AND $cache_birthday != "no_cache")
{
    $b_day = date ("d", $time);
    $b_month = date ("m", $time);
    $DB->prefix = DLE_USER_PREFIX;
    $today = $DB->select( "lb_b_day, lb_b_month, lb_b_year, name, user_id", "users", "lb_b_day='{$b_day}' AND lb_b_month = '{$b_month}'", "ORDER BY lb_b_year ASC" );
    $cache_birthday = array ();
    while ( $row = $DB->get_row($today) )
    {
        $cache_birthday[$row['user_id']] = array ();
        foreach ($row as $key => $value)
            $cache_birthday[$row['user_id']][$key] = $value;
    }
    $DB->free($today);
    if (count($cache_birthday))
    {
        $cache->update("birthday", $cache_birthday);
        $cache_birthday = $cache->take("birthday");
    }
    else
        $cache->update("birthday", "no_cache");
}
elseif ($cache_birthday == "no_cache")
{
    unset($cache_birthday);
    $cache_birthday = array();
}

if(count($cache_banfilters))
{
    if (!$lang_g_cron)
        $lang_g_cron = language_forum ("board/global/cron");
    
    $update_cb = false;
    foreach($cache_banfilters as $del_ban)
    {
        if ($del_ban['users_id'] AND $del_ban['days'] AND $del_ban['date'] <= $time)
        {
            $update_cb = true;
            $DB->prefix = DLE_USER_PREFIX;
            $DB->delete("users_id = '{$del_ban['users_id']}'", "banned");
            $info = $lang_g_cron['log_block'];
            $DB->insert("member_id = '{$del_ban['users_id']}', date = '{$time}', info = '{$info}', auto_unblock = '1'", "logs_blocking");
            
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update("banned = ''", "users", "user_id = '{$del_ban['user_id']}'");
        }
    }
    
    if ($update_cb)
        $cache->clear("", "banfilters");
}

if(count($cache_forums_notice))
{        
    $update_cfn = false;
    foreach($cache_forums_notice as $del_fn)
    {
        if ($del_fn['end_date'] AND $del_fn['end_date'] <= $time AND $del_fn['active_status'] != 0)
        {
            $update_cfn = true;
            if ($cache_config['general_del_fnotice']['conf_value'])
                $DB->delete("id = '{$del_fn['id']}'", "forums_notice");
            else
                $DB->update("active_status = '0'", "forums_notice", "id = '{$del_fn['id']}'");
        }
    }
    
    if ($update_cfn)
        $cache->clear("", "forums_notice");
}

?>