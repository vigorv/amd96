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

$lang_m_feedback = language_forum ("board/modules/feedback");

$link_speddbar = speedbar_forum (0, true)."|".$lang_m_feedback['location'];
$onl_location = $lang_m_feedback['location'];
$meta_info_other = $lang_m_feedback['location'];
$errors = array();

if (!$cache_config['feedback_on']['conf_value'])
    message ($lang_message['access_denied'], $lang_m_feedback['off'], 1);
elseif (!$cache_config['feedback_guest']['conf_value'] AND !$logged)
    message ($lang_message['access_denied'], $lang_m_feedback['not_logged'], 1);
elseif (!$cache_config['feedback_email']['conf_value'] AND !$cache_config['feedback_admins']['conf_value'])
    message ($lang_message['error'], $lang_m_feedback['no_adres'], 1);
else
{
    if( isset( $_POST['send_mess'] ))
    {
        filters_input('request|post');
        
        if (!$logged)
        {
            $from_name = strip_tags( $_REQUEST['from_name'] );
            $from_email = trim( strtolower( $_POST['from_email'] ) );
        }
        else
        {
            $from_name = $member_id['name'];
            $from_email = $member_id['email'];   
        }
	
        $text = parse_word($_POST['text'], false, false, false);
        $title = strip_tags( $_POST['title'] );
        
        if (utf8_strlen($text) < 10)
            $errors[] = $lang_m_feedback['text'];
            
        if (utf8_strlen($title) < 3)
            $errors[] = $lang_m_feedback['title'];
    
        if (!$from_name)
            $errors[] = $lang_m_feedback['name_empty'];
        
        if (utf8_strlen($from_name) <= 3 AND $from_name)
            $errors[] = str_replace("{num}", "3", $lang_m_feedback['name1']);

        if(utf8_strlen($from_name) > 22)
            $errors[] = str_replace("{num}", "22", $lang_m_feedback['name2']);
        
        if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $from_name ) )
            $errors[] = $lang_m_feedback['name_symbols'];
        
        if( !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $from_email) or empty( $from_email ) )
            $errors[] = $lang_m_feedback['email'];

        if ($from_email AND utf8_strlen($from_email) > 50)
            $errors[] = $lang_m_feedback['email_len'];
     
        if ($cache_config['security_captcha_feedback']['conf_value'] AND !$logged)
        {
            if(!isset($_SESSION['captcha_keystring']) OR $_SESSION['captcha_keystring'] != $_POST['keystring'])
                $errors[] = $lang_message['captcha'];
        }
        
        if (captcha_dop_check("feedback") AND !$logged)
        {
            $_SESSION['captcha_keystring_a'] = trim($_POST['keystring_dop']);
            if (!captcha_dop_check_answer())
                $errors[] = $lang_message['keystring'];
        }
        
        if( ! $errors[0] )
        {
            if ($cache_config['feedback_admins']['conf_value'])
            {
                $userid = intval($_POST['send_to']);
                $DB->prefix = DLE_USER_PREFIX;
                $checking = $DB->one_select ("name, user_id, email", "users", "user_id = '{$userid}'");
                
                if (!$checking['user_id'])
                    $errors[] = $lang_m_feedback['no_user'];
                    
                $to_email = $checking['email'];
                $to_name = $checking['name'];
            }
            else
            {
                $to_email = $cache_config['feedback_email']['conf_value'];
                $to_name = $lang_m_feedback['to_name'];
            }
        }

        if( ! $errors[0] )
        {     
            unset($_SESSION['captcha_keystring']);
            unset($_SESSION['captcha_keystring_a']);
            unset($_SESSION['captcha_keystring_q_num']);
            unset($_SESSION['captcha_keystring_q']);     
            
            $email_message = $cache_email[1];
            $message = $lang_m_feedback['to_name']." <b>".$from_name."</b><br />".$text;
            $email_message = str_replace( "{froum_link}", $cache_config['general_site']['conf_value'], $email_message );
            $email_message = str_replace( "{forum_name}", $cache_config['general_name']['conf_value'], $email_message );
            $email_message = str_replace( "{user_name}", $to_name, $email_message );
            $email_message = str_replace( "{user_id}", "", $email_message );
            $email_message = str_replace( "{user_ip}", $_IP, $email_message );
            $email_message = str_replace( "{active_link}", "", $email_message );
            $email_message = str_replace( "{user_password}", "", $email_message );
            $email_message = str_replace( "{message}", $message, $email_message );
            
            mail_sender ($to_email, $to_name, $email_message, $title, false, $from_email);
        
    		message ($lang_m_feedback['send_ok_title'], $lang_m_feedback['send_ok']);
        }
        else
    		message ($lang_message['error'], $errors, 1);
    }
    else
    {
        $tpl->load_template( 'feedback.tpl' );
                    
        if ($cache_config['security_captcha_feedback']['conf_value'] AND !$logged)
        {
      		$tpl->tags( '[captcha]', "" );
            $tpl->tags( '[/captcha]', "" ); 
            $tpl->tags( '{captcha}', "<img id=\"recaptcha_img\" src=\"".$redirect_url."components/class/kcaptcha/kcaptcha.php\"><br /><a href=\"#\" id=\"recaptcha\">".$lang_message['change_captcha']."</a>" );
        }
        else
            $tpl->block( "'\\[captcha\\].*?\\[/captcha\\]'si", "" );
            
        if (captcha_dop_check("feedback") AND !$logged)
        {
            $tpl->tags( '[captcha_dop]', "" );
            $tpl->tags( '[/captcha_dop]', "" ); 
            $tpl->tags( '{captcha_dop}', captcha_dop());
        }
        else
            $tpl->block( "'\\[captcha_dop\\].*?\\[/captcha_dop\\]'si", "" );
            
        if ($cache_config['feedback_admins']['conf_value'])
        {
            $DB->prefix = DLE_USER_PREFIX;
            $DB->select( "name, user_id, email", "users", "user_group = '1'", "ORDER by user_id ASC" );
            $send_to = array();
            while ( $row = $DB->get_row() )
            {
                $send_to[$row['user_id']] = $row['name'];
            }
            $tpl->tags('{send_to}', select_code("send_to", $send_to));
            $tpl->tags( '[send_to]', "" );
            $tpl->tags( '[/send_to]', "" );    
        }
        else
            $tpl->block( "'\\[send_to\\].*?\\[/send_to\\]'si", "" );
            		
        $tpl->copy_template = "<form  method=\"post\" name=\"feedback_form\" id=\"feedback_form\" action=\"\">\n\r" . $tpl->copy_template . "</form>";
    		
        $tpl->compile( 'content' );
        $tpl->clear();
    }
}

?>