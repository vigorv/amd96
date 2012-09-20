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
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$secret_key = "";
$logged = false;

$lang_login = language_forum ("control_center/login");

if (isset($_POST['autoriz']))
{
	require LB_CLASS . '/safehtml.php';
	$safehtml = new safehtml( );
	$safehtml->protocolFiltering = "black";

	$control_center->errors = array ();

	if (!$_POST['password']) $control_center->errors[] = $lang_login['no_pass'];
	if (!$_POST['name']) $control_center->errors[] = $lang_login['no_name'];

	if( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\/|\\\|\&\~\*\+\{\}]/", $_POST['name'] ) )
		$control_center->errors[] = $lang_login['name_symbols'];
        
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
                $control_center->errors[] = $lang_login['block'];
                $lang_login['block2'] = str_replace("{min}", intval($cache_config['security_loginmin']['conf_value']), $lang_login['block2']);
                $control_center->errors[] = str_replace("{date}", formatdate($guest['login_date']), $lang_login['block2']);
            }
        }
    }

	if  (!$control_center->errors)
	{
        $password_md1 = $DB->addslashes(md5($_POST['password']));
        $password_md2 = $DB->addslashes(md5(md5($_POST['password'])));

        $password2 = $DB->addslashes($_POST['password']);
        
		$name = $DB->addslashes($safehtml->parse($_POST['name']));
		$login_сс = 0;

        $DB->prefix = DLE_USER_PREFIX;
        if ($cache_config['security_loginemail']['conf_value'])
            $member_id = $DB->one_select( "*", "users", "password = '{$password_md2}' AND email = '{$name}'" );
        else
            $member_id = $DB->one_select( "*", "users", "password = '{$password_md2}' AND name = '{$name}'" );

		if (!$member_id['user_id'])
        {
            if (!$cache_config['security_loginemail']['conf_value'])
                $control_center->errors[] = $lang_login['no_member_id'];
            else
                $control_center->errors[] = $lang_login['no_member_id_2'];
        }
        elseif ($cache_group[$member_id['user_group']]['g_access_cc'] != "1")
			$control_center->errors[] = $lang_login['no_access'];
                    
		if ($member_id['user_id'] AND !$control_center->errors)
		{		
            if (!$member_id['secret_key'])
            {
                $member_id['secret_key'] = md5(md5($password_md2.time().$_IP));
                $DB->prefix = DLE_USER_PREFIX;
                $DB->update ("secret_key='{$member_id['secret_key']}'", "users", "user_id='{$member_id['user_id']}'");
            }
          
			$_SESSION['LB_CC_member'] = $member_id['user_id'];
			$_SESSION['LB_CC_password'] = $password_md2;
			$_SESSION['LB_CC_lastdate'] = $time;
            $_SESSION['LB_CC_member_sc'] = $member_id['secret_key'];
            
            $_SESSION['dle_user_id'] = $member_id['user_id'];
			$_SESSION['dle_password'] = $password_md1;
            $_SESSION['member_lasttime'] = $member_id['lastdate'];
			$_SESSION['LB_lastdate'] = $member_id['lastdate'];
            $_SESSION['LB_member_sc'] = $member_id['secret_key'];
            
			$secret_key = md5( strtolower( clean_url($_SERVER['HTTP_HOST']) . $member_id['name'] . sha1($password_md1) . date( "Ymd" ) ) );
			$_SESSION['LB_CC_secret_key'] = $secret_key;
            $_SESSION['LB_secret_key'] = $secret_key;

            $DB->prefix = DLE_USER_PREFIX;
			$DB->update ("lastdate='{$time}', logged_ip='{$_IP}'", "users", "user_id='{$member_id['user_id']}'");
			
			$logged = true;
			$login_сс = 1;
            header( "Location: {$_SERVER['REQUEST_URI']}" );
		}
		else
			$control_center->errors_title = $lang_message['error'];

		$info = array ();
		$info['user_agent'] = $safehtml->parse($_SERVER['HTTP_USER_AGENT']);
		$info['request_url'] = $safehtml->parse($_SERVER['REQUEST_URI']);
        
		$info_pass_len = utf8_strlen($password2) - 2;
		$info_pass = utf8_substr($password2."X", $info_pass_len+1, -1);

		$info_pass_z = "";
		$info_i = 0;
		while ($info_i <= $info_pass_len)
		{
			$info_pass_z .= "*"; 
			$info_i ++;
		}
		$info_pass = $info_pass_z.$info_pass;

		$info['password'] = $safehtml->parse($info_pass);
		$info = $DB->addslashes(serialize($info));
		$DB->insert("member_name = '{$name}', date = '{$time}', ip = '{$_IP}', login = '{$login_сс}', info = '{$info}'", "logs_login_cc");
		unset($safehtml);
	}
	else
    {
        if ($cache_config['security_logincount']['conf_value'] AND $cache_config['security_loginmin']['conf_value'] AND $guest['login_count'] < $cache_config['security_logincount']['conf_value'])
        {
            $DB->insert("login_ip ='{$_IP}', login_count='1', login_date = '{$time}' ON DUPLICATE KEY UPDATE login_ip ='{$_IP}', login_count = login_count+1, login_date = '{$time}'", "members_login");
        }
		$control_center->errors_title = $lang_message['error'];
    }
}
elseif( intval( $_SESSION['LB_CC_member'] ) > 0)
{
	$id_login = intval( $_SESSION['LB_CC_member'] );
	$pass_login = $DB->addslashes($_SESSION['LB_CC_password']);
    $member_sk = $DB->addslashes($_SESSION['LB_member_sc']);
    $logged = false;
    
    $DB->prefix = array ( 0 => DLE_USER_PREFIX );
    $member_id = $DB->one_join_select( "*", "LEFT", "users u||control_center_admins cca", "u.user_id=cca.cca_member_id", "user_id='{$id_login}'" );

	if( $member_id['password'] == $pass_login AND $member_id['secret_key'] == $member_sk AND $member_sk != "")
	{       
		if (($_SESSION['LB_CC_lastdate'] + $time_session) < $time)
		{
			$control_center->errors = array ();
			$control_center->errors[] = $lang_login['time_is_up_session'];
			$control_center->errors_title = $lang_message['error'];
		}		
        elseif (LB_member_ip($member_id['allowed_ip']))
		{
			$logged = true;
			$secret_key = md5( strtolower( clean_url($_SERVER['HTTP_HOST']) . $member_id['name'] . sha1($pass_login) . date( "Ymd" ) ) );
            
			$_SESSION['LB_CC_member'] = $member_id['user_id'];
			$_SESSION['LB_CC_password'] = $pass_login;
			$_SESSION['LB_CC_lastdate'] = $time;
            $_SESSION['LB_CC_member_sc'] = $member_id['secret_key'];
            
            $member_cca = "";
            
            if (!$member_id['cca_id'])
            {
                $member_cca_group = $DB->one_select( "*", "control_center_admins", "cca_group='{$member_id['user_group']}' AND cca_is_group = '1'" );
                $member_cca = unserialize($member_cca_group['cca_permission']);
            }
            else
                $member_cca = unserialize($member_id['cca_permission']);
		}
        elseif (!LB_member_ip($member_id['allowed_ip']))
        {
            $control_center->errors = array ();
			$control_center->errors[] = $lang_login['block_ip'];
			$control_center->errors_title = $lang_message['access_denied'];
        }
	}
}

if( !$logged)
{
	$member = array ();
	$_SESSION['LB_CC_member'] = "";
	$_SESSION['LB_CC_password'] = "";
	$_SESSION['LB_CC_secret_key'] = "";
    $_SESSION['LB_CC_member_sc'] = "";
}

if( isset( $_REQUEST['logout'] ) AND $logged )
{
	if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}
	
	$DB->delete("member_id_online = '{$member_id['user_id']}'", "session_cc");

	$secret_key = "";
	$_SESSION['LB_CC_member'] = "";
	$_SESSION['LB_CC_password'] = "";
	$_SESSION['LB_CC_secret_key'] = "";
    $_SESSION['LB_CC_member_sc'] = "";
	$logged = false;
	
	header( "Location: {$redirect_url}" );
	exit();
}

?>