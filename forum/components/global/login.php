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

$secret_key = "";
$logged = false;

############# uLogin - Start

if(isset($_POST['token']) AND $cache_config['regist_ulogin']['conf_value'])
{ 
    $lang_g_login = language_forum ("board/global/login");
    
    $errors = array ();
    
    $ulogin_data = file_get_contents('http://ulogin.ru/token.php?token='.$_POST['token'].'&host='.$_SERVER['HTTP_HOST']);
    $ulogin_member = json_decode($ulogin_data, true);
                            
    $ulogin = array();
    $where = array();
    
    $ulogin['ulogin_key'] = $DB->addslashes(filters_input_one($ulogin_member['network'].$ulogin_member['uid']));    // уникальный ключ пользователя, используется для проверки в БД этого пользователя
    
    if(isset($ulogin_member['nickname']))
    {
        $ulogin['name'] = preg_replace( "/[\||\'|\<|\>|\"|\!|\?|\$|\/|\\\|\&\~\*\+\{\}]/", "", $ulogin_member['nickname'] );
        $ulogin['name'] = $DB->addslashes(filters_input_one($ulogin['name']));
    }
    
    if(isset($ulogin_member['first_name']))
    {
        if ($cache_config['general_coding']['conf_value'] != "utf-8") $ulogin_member['first_name'] = mb_convert_encoding($ulogin_member['first_name'], "windows-1251", "UTF-8");
        
        $ulogin['fullname'] = $DB->addslashes(filters_input_one($ulogin_member['first_name']));
    }
    
    if(isset($ulogin_member['last_name']))
    {
        if ($cache_config['general_coding']['conf_value'] != "utf-8") $ulogin_member['last_name'] = mb_convert_encoding($ulogin_member['last_name'], "windows-1251", "UTF-8");
        
        if (isset($ulogin['fullname']))
            $ulogin['fullname'] .= " ".$DB->addslashes(filters_input_one($ulogin_member['last_name']));
        else
            $ulogin['fullname'] = $DB->addslashes(filters_input_one($ulogin_member['last_name']));
            
        $where[] = "fullname = '".$ulogin['fullname']."'";
    }
    
    if (!isset($ulogin['name']))    // если не удалось определить логин/никнейм, то создаём его на основе имени и фамилии
    {
        $ulogin['name'] = preg_replace( "/[\||\'|\<|\>|\"|\!|\?|\$|\/|\\\|\&\~\*\+\{\}]/", "", stripslashes($ulogin['fullname']) );
        $ulogin['name'] = $DB->addslashes($ulogin['name']);
    }
    
    if(utf8_strlen($ulogin['name']) < 2) $errors[] = str_replace("{num}", "2", $lang_g_login['ulogin_name_min']);
    if(utf8_strlen($ulogin['name']) > 30) $errors[] = str_replace("{num}", "30", $lang_g_login['ulogin_name_max']);
    
    if(isset($ulogin_member['email']))
    {
        $ulogin['email'] = $DB->addslashes( trim( strtolower( filters_input_one($ulogin_member['email']) ) ) );
        if(!preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $ulogin['email']) OR empty( $ulogin['email'] ))
	       unset($ulogin['email']);
    }

    if (!isset($ulogin['email']))   // если не удалось определить почту, то создаём её на основе логина и домена соц. сети
    {
        $ulogin['email'] = totranslit(stripslashes($ulogin['name']))."@".clean_url($ulogin_member['identity']);
        $ulogin['email'] = $DB->addslashes($ulogin['email']);
    }
        
    if(isset($ulogin_member['bdate']))
    {
        $ulogin_bdate = explode (".", $ulogin_member['bdate']);
        if (count($ulogin_bdate) == 3)
        {
            $ulogin['b_day'] = intval($ulogin_bdate[0]);
            $ulogin['b_month'] = intval($ulogin_bdate[1]);
            $ulogin['b_year'] = intval($ulogin_bdate[2]);
            
            $where[] = "lb_b_day = '".$ulogin['b_day']."'";
            $where[] = "lb_b_month = '".$ulogin['b_month']."'";
            $where[] = "lb_b_year = '".$ulogin['b_year']."'";
        }
    }
    if(isset($ulogin_member['sex']) AND (intval($ulogin_member['sex']) == 1 OR intval($ulogin_member['sex']) == 2))
    {
        $ulogin['sex'] = intval($ulogin_member['sex']);
        
        if ($ulogin['sex'] == 1) $ulogin['sex'] += 1;
        elseif ($ulogin['sex'] == 2) $ulogin['sex'] -= 1;
        
        $where[] = "lb_sex = '".$ulogin['sex']."'";
    }
    if(isset($ulogin_member['identity']))
        $ulogin['password'] = md5(trim($ulogin_member['identity']));
    else 
        $ulogin['password'] = md5($ulogin['name'].$ulogin['email']);
        
    $ulogin['secret_key'] = md5(md5($ulogin['password'].time().$_IP));
        
    // Создаём массив возможных логинов, если вдруг основной был уже кем-то занят
    
    $ulogin_names = array();
    $ulogin_names[] = $ulogin['name'];
    
    if (isset($ulogin['b_year']))
        $ulogin_names[] = $ulogin['name']."_".$ulogin['b_year'];
        
    $ulogin_names[] = $ulogin['name']."_".rand(1, 1000);
    $ulogin_names[] = $ulogin['name']."_".substr($ulogin['secret_key'], 0, 5);
            
    function check_ulogin_name ()
    {
        global $DB, $ulogin_names, $ulogin;
        
        foreach ($ulogin_names as $value)
        {
            $DB->prefix = DLE_USER_PREFIX;
            $checking = $DB->one_select ("COUNT(*) as count", "users", "name = '{$value}'");
            if (!$checking['count'])
            {
                $ulogin['name'] = $value;
                return true;
            }
        }
        
        return false;
    }
    
    // Создаём массив возможных почты, если вдруг основная была уже кем-то занята
    
    $ulogin_email = array();
    $ulogin_email[] = $ulogin['email'];
    $ulogin_email[] = $DB->addslashes(totranslit(stripslashes($ulogin['name']))."@".clean_url($ulogin_member['identity']));
    
    if (isset($ulogin['b_year']))
        $ulogin_email[] = $DB->addslashes(totranslit(stripslashes($ulogin['name']))."_".$ulogin['b_year']."@".clean_url($ulogin_member['identity']));
        
    $ulogin_email[] = $DB->addslashes(totranslit(stripslashes($ulogin['name']))."_".rand(1, 1000)."@".clean_url($ulogin_member['identity']));
    
    function check_ulogin_email ()
    {
        global $DB, $ulogin_email, $ulogin;
        
        foreach ($ulogin_email as $value)
        {
            $DB->prefix = DLE_USER_PREFIX;
            $checking = $DB->one_select ("COUNT(*) as count", "users", "email = '{$value}'");
            if ($checking['count'])
            {
                $ulogin['email'] = $value;
            }
            else
            {
                $ulogin['email'] = $value;
                return true;
            }
        }
        
        return false;
    }
    
    $ulogin_found = false;
    
    if( ! $errors[0] )
    { 
        $DB->prefix = DLE_USER_PREFIX;
        $checking = $DB->one_select ("COUNT(*) as count", "users", "ulogin_key = '{$ulogin['ulogin_key']}'");
        if ($checking['count']) $ulogin_found = true;
        
        $DB->free($checking);
        
        if (!$ulogin_found)
        {
            if (!check_ulogin_name()) $errors[] = $lang_g_login['ulogin_name'];
            if (!check_ulogin_email()) $errors[] = str_replace("{mail}", $ulogin['email'], $lang_g_login['ulogin_email']);
            
            if( ! $errors[0] )
            {                
                if (count($where))
                    $where = ", ".implode(", ", $where);
                else
                {
                    unset($where);
                    $where = "";
                }
                
                $DB->prefix = DLE_USER_PREFIX;
                $DB->insert("name = '{$ulogin['name']}', ulogin_key = '{$ulogin['ulogin_key']}', password = '".md5($ulogin['password'])."', secret_key = '{$ulogin['secret_key']}', email = '{$ulogin['email']}', user_group = '4', lastdate = '{$time}', reg_date = '{$time}', logged_ip = '{$_IP}'".$where, "users");
                $member_id = $DB->insert_id();
                
                update_cookie( "dle_user_id", $member_id, 365 );
				update_cookie( "dle_password", $password_md1, 365 );
                update_cookie( "LB_member_sc", $ulogin['secret_key'], 365 );
                update_cookie( "LB_password", $ulogin['password'], 365 );
                
                $_SESSION['dle_user_id'] = $member_id;
                $_SESSION['dle_password'] = $ulogin['password'];
                $_SESSION['member_lasttime'] = $time;
    			$_SESSION['LB_lastdate'] = $time;
                $_SESSION['LB_member_sc'] = $ulogin['secret_key'];

                $cache_stats_users['users'] += 1;
                $cache_stats_users['last_name'] = $ulogin['name'];
                $cache_stats_users['last_id'] = $member_id;
                $cache->update("stats_users", $cache_stats_users, "statistics");
                
                header( "Location: {$_SERVER['REQUEST_URI']}" );
                exit();
            }
            else
                message ($lang_message['error'], $errors);
        }
        else
        {
            $DB->prefix = DLE_USER_PREFIX;
            $member_id = $DB->one_select( "*", "users", "ulogin_key = '{$ulogin['ulogin_key']}'" );
            
            if ($cache_config['online_status']['conf_value'])
            {
                $DB->delete("mo_ip = '{$_IP}'", "members_online");
            }
          
            if (!$member_id['secret_key'])
            {
                $member_id['secret_key'] = $ulogin['secret_key'];
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update ("secret_key='{$member['secret_key']}'", "users", "user_id='{$member_id['user_id']}'");
            }
          
            update_cookie( "dle_user_id", $member_id['user_id'], 365 );
            update_cookie( "dle_password", $ulogin['password'], 365 );
            update_cookie( "LB_member_sc", $member_id['secret_key'], 365 );
			
			$_SESSION['dle_user_id'] = $member_id['user_id'];
			$_SESSION['dle_password'] = $ulogin['password'];
			$_SESSION['LB_lastdate'] = $member_id['lastdate'];
            $_SESSION['LB_member_sc'] = $member_id['secret_key'];

			$secret_key = md5( strtolower( clean_url($_SERVER['HTTP_HOST']) . $member_id['name'] . sha1($member_id['password']) . date( "Ymd" ) ) );
			$_SESSION['LB_secret_key'] = $secret_key;

            $DB->prefix = DLE_USER_PREFIX;
			$DB->update ("lastdate='{$time}', logged_ip='{$_IP}'", "users", "user_id='{$member_id['user_id']}'");
			
			$logged = true;
            
            header( "Location: {$_SERVER['REQUEST_URI']}" );
            exit();
        }
    }
}

############# uLogin - End

if (isset($_POST['autoriz']))
{
    if (!isset($lang_g_login))
        $lang_g_login = language_forum ("board/global/login");
    
	$errors = array ();

    filters_input('post');

	if (!$_POST['password'])
		$errors[] = $lang_g_login['no_pass'];
	if (!$_POST['name'])
		$errors[] = $lang_g_login['no_name'];

	if( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\/|\\\|\&\~\*\+\{\}]/", $_POST['name'] ) )
		$errors[] = $lang_g_login['name_symbols'];
        
    if (utf8_strlen($_POST['name']) < 2)
        $errors[] = str_replace("{num}", "2", $lang_g_login['name_min']);
        
    if ($cache_config['security_logincount']['conf_value'] AND intval($cache_config['security_loginmin']['conf_value']))
    {
        $guest = $DB->one_select( "*", "members_login", "login_ip = '{$_IP}'" );        
        if ($guest['login_count'] >= $cache_config['security_logincount']['conf_value'])
        {     
            $time_logblock = $time - (intval($cache_config['security_loginmin']['conf_value']) * 60);
            
            if ($guest['login_date'] < $time_logblock)
            {
                $DB->delete("login_ip = '{$_IP}'", "members_login");
                $guest['login_count'] = 0;
            }
            else
            {
                $errors[] = $lang_g_login['block'];
                
                $lang_g_login['block2'] = str_replace("{min}", intval($cache_config['security_loginmin']['conf_value']), $lang_g_login['block2']);
                $lang_g_login['block2'] = str_replace("{date}", formatdate($guest['login_date']), $lang_g_login['block2']);
                $errors[] = $lang_g_login['block2'];
            }
        }
    }

	if  ( !$errors[0] )
	{       
		$password_md1 = $DB->addslashes(md5($_POST['password']));
        $password_md2 = $DB->addslashes(md5(md5($_POST['password'])));
        
		$name = $DB->addslashes($_POST['name']);
        
        $DB->prefix = DLE_USER_PREFIX;
        if ($cache_config['security_loginemail']['conf_value'])
            $member_id = $DB->one_select( "*", "users", "password = '{$password_md2}' AND email = '{$name}'" );
        else
            $member_id = $DB->one_select( "*", "users", "password = '{$password_md2}' AND name = '{$name}'" );
        
		if (!$member_id['user_id'])
        {
            if (!$cache_config['security_loginemail']['conf_value'])
                $errors[] = $lang_g_login['no_member_id'];
            else
                $errors[] = $lang_g_login['no_member_id_2'];
        }
        
		if ($member_id['user_id'] AND !$errors)
		{
            if ($cache_config['online_status']['conf_value'])
            {
                $DB->delete("mo_ip = '{$_IP}'", "members_online");
            }
          
            if (!$member_id['secret_key'])
            {
                $member_id['secret_key'] = md5(md5($password_md2.time().$_IP));
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update ("secret_key='{$member_id['secret_key']}'", "users", "user_id='{$member_id['user_id']}'");
            }
          
			if (intval($_POST['remember']))
			{
				update_cookie( "dle_user_id", $member_id['user_id'], 365 );
				update_cookie( "dle_password", $password_md1, 365 );
                update_cookie( "LB_member_sc", $member_id['secret_key'], 365 );
			}
			
			$_SESSION['dle_user_id'] = $member_id['user_id'];
			$_SESSION['dle_password'] = $password_md1;
            $_SESSION['member_lasttime'] = $member_id['lastdate'];
			$_SESSION['LB_lastdate'] = $member_id['lastdate'];
            $_SESSION['LB_member_sc'] = $member_id['secret_key'];

			$secret_key = md5( strtolower( clean_url($_SERVER['HTTP_HOST']) . $member_id['name'] . sha1($password_md1) . date( "Ymd" ) ) );
            
			$_SESSION['LB_secret_key'] = $secret_key;
          
            $DB->prefix = DLE_USER_PREFIX;
			$DB->update ("lastdate='{$time}', logged_ip='{$_IP}'", "users", "user_id='{$member_id['user_id']}'");
			
			$logged = true;
			header( "Location: {$_SERVER['REQUEST_URI']}" );
            exit();
		}
		else
			message ($lang_message['error'], $errors);
	}
	else
    {
        if ($cache_config['security_logincount']['conf_value'] AND $cache_config['security_loginmin']['conf_value'] AND $guest['login_count'] < $cache_config['security_logincount']['conf_value'])
        {
            $DB->insert("login_ip ='{$_IP}', login_count='1', login_date = '{$time}' ON DUPLICATE KEY UPDATE login_ip ='{$_IP}', login_count = login_count+1, login_date = '{$time}'", "members_login");
        }
		message ($lang_message['error'], $errors);
    }
}
elseif( intval( $_SESSION['dle_user_id'] ) > 0 OR intval( $_COOKIE['dle_user_id'] ) > 0)
{
    filters_input('coockie|session');
    
	if (intval( $_SESSION['dle_user_id'] ) > 0)
	{
		$id_login = intval( $_SESSION['dle_user_id'] );
		$pass_login = $DB->addslashes($_SESSION['dle_password']);
        $member_sk = $DB->addslashes($_SESSION['LB_member_sc']);
	}
	else
	{
		$id_login = intval( $_COOKIE['dle_user_id'] );
		$pass_login = $DB->addslashes($_COOKIE['dle_password']);
        $member_sk = $DB->addslashes($_COOKIE['LB_member_sc']);
	}

    $DB->prefix = DLE_USER_PREFIX;
	$member_id = $DB->one_select( "*", "users", "user_id='{$id_login}'" );
    $logged = false;
        
	if( $member_id['password'] == md5($pass_login) AND $member_id['secret_key'] == $member_sk AND $member_sk != "")
	{
        if( $member_id['lb_limit_publ'] AND $member_id['lb_limit_days'] AND $member_id['lb_limit_date'] < $time )
        {
    	   $member_id['restricted'] = 0;
    	   $DB->not_filtred( "UPDATE LOW_PRIORITY " . DLE_USER_PREFIX . "_users SET lb_limit_publ='0', lb_limit_days='0', lb_limit_date='0' WHERE user_id='{$member_id['user_id']}'" );
        }
       
        $member_options = unserialize($member_id['mf_options']);
        $member_options = member_options_default($member_options);
        
        if (LB_member_ip($member_id['allowed_ip']))
        {
            $logged = true;
            $secret_key = md5( strtolower( clean_url($_SERVER['HTTP_HOST']) . $member_id['name'] . sha1($pass_login) . date( "Ymd" ) ) );
		
            $_SESSION['dle_user_id'] = $member_id['user_id'];
            $_SESSION['dle_password'] = $pass_login;
            $_SESSION['LB_member_sc'] = $member_id['secret_key'];
            
            if (!$_SESSION['LB_lastdate'])
            {
                $_SESSION['LB_lastdate'] = $member_id['lastdate'];
                $_SESSION['member_lasttime'] = $member_id['lastdate'];
                
                if( ($member_id['lastdate'] + (3600 * 4)) < $time ) //4 минуты
                    $DB->not_filtred( "UPDATE LOW_PRIORITY " . DLE_USER_PREFIX . "_users SET lastdate='{$time}' WHERE user_id='{$member_id['user_id']}'" );
            }            
        }
        else
            message ($lang_message['access_denied'], $lang_g_login['block_ip']);
	}
}

if( !$logged)
{
	$member_id = array ();
    $member_id['user_group'] = 5;
    $member_id['user_id'] = 0;
	update_cookie( "LB_secret_key", "", 0 );
    update_cookie( "LB_member_sc", "", 0 );
    update_cookie( "dle_user_id", "", 0 );
	update_cookie( "dle_password", "", 0 );
	update_cookie( "dle_hash", "", 0 );
	$_SESSION['dle_user_id'] = 0;
	$_SESSION['dle_password'] = "";
	$_SESSION['LB_secret_key'] = "";
    $_SESSION['LB_member_sc'] = "";
}

if( isset( $_REQUEST['logout'] ) AND $logged )
{
	if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}

	$secret_key = "";
	update_cookie( "dle_user_id", "", 0 );
	update_cookie( "dle_password", "", 0 );
	update_cookie( "dle_hash", "", 0 );
	update_cookie( "LB_secret_key", "", 0 );
    update_cookie( "LB_member_sc", "", 0 );
	update_cookie( session_name(), "", 0 );
	@session_unset();
	@session_destroy();
	$logged = false;
    
	header( "Location: {$redirect_url}" );
	exit();
}

?>