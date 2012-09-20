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

$lang_m_u_options = language_forum ("board/modules/users/options");

$DB->prefix = DLE_USER_PREFIX;
$row = $DB->one_select( "name, user_id, user_group, mf_options", "users", "name='{$member_name}'" );

if($row['user_id'] AND ($member_id['user_id'] == $row['user_id'] OR $member_id['user_group'] == 1))
{    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_options['location']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $link_speddbar = speedbar_forum (0, true)."|".$lang_location;
    
    $lang_location = str_replace("{link}", profile_link($row['name'], $row['user_id']), $lang_m_u_options['location_online']);
    $lang_location = str_replace("{name}", $row['name'], $lang_location);
    $onl_location = $lang_location;
    
    $meta_info_other = str_replace("{name}", $row['name'], $lang_m_u_options['meta_info']);
    
    if (isset($_POST['editprofile']))
    {        
        if (!$_POST['secret_key'] OR $_POST['secret_key'] != $secret_key)
        {
            exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
        }
            
        filters_input('post');
        $edit_options = array();
        
        $edit_options['subscribe'] = 0;
        if (intval($_POST['subscribe']))
            $edit_options['subscribe'] = 1;
            
        $edit_options['pmtoemail'] = 0;
        if (intval($_POST['pmtoemail']))
            $edit_options['pmtoemail'] = 1;
            
        $edit_options['online'] = 0;
        if (intval($_POST['online']))
            $edit_options['online'] = 1;  
            
        $edit_options['comm_profile'] = 0;
        if (intval($_POST['comm_profile']))
            $edit_options['comm_profile'] = 1;
                    
        $edit_options['posts_ajax'] = 0;
        if (intval($_POST['posts_ajax']) AND intval($cache_config['posts_get_next_page']['conf_value']))
            $edit_options['posts_ajax'] = 1;
            
        $email_ip = $DB->addslashes($_POST['email_ip']);
       	if( !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $email_ip) AND !empty( $email_ip ) )
			$errors[] = $lang_m_u_options['email_error'];        
            
        if ($email_ip AND utf8_strlen($email_ip) > 50)
			$errors[] = $lang_m_u_options['email_max'];  
            
        $block_ip = $_POST['block_ip'];
        if ($block_ip)
        {
            $check = explode ("\r\n", $block_ip);
            foreach ($check as $check_ip)
            {
                $ip_mass = explode (".", $check_ip);
                if (!$ip_mass[0])
                {
                    $errors[] = $lang_m_u_options['ip_error'];  
                    break;
                }
                
                for($i=0;$i<=3;$i++)
                {
                    if($ip_mass[$i] != "*")
                    {
                        if(!preg_match("#^[0-9]{1,3}$#", $ip_mass[$i]))
                        {
                            $errors[] = $lang_m_u_options['ip_error'];  
                            break;
                        }
        			}
    			}
                
                if ($errors[0])
                    break;
            }
        } 
        $edit_options['block_ip'] = $block_ip;  
        $edit_options['email_ip'] = $email_ip;  
        
        if (! $errors[0])
        {
            $edit_options = $DB->addslashes( serialize($edit_options) );
            
            $DB->prefix = DLE_USER_PREFIX;
            $DB->update ("mf_options = '{$edit_options}'", "users", "user_id='{$row['user_id']}'");
            header( "Location: ".profile_edit_link($row['name'], $row['user_id'], "options") );
            exit();
        }
        else
            message ($lang_message['error'], $errors);
    }
    else
    {     
        $moptions = unserialize($row['mf_options']);
        $moptions = member_options_default($moptions);
        
        $tpl->load_template ( 'users/users_profile_options.tpl' );
        $tpl->tags('{member_name}', $row['name']);
        
        $pmtoemail = array(0 => $lang_m_u_options['pmtoemail_op_no'], 1 => $lang_m_u_options['pmtoemail_op_yes']);
        $tpl->tags('{pmtoemail_op}', select_code("pmtoemail", $pmtoemail, $moptions['pmtoemail']));
        
        $subscribe = array(0 => $lang_m_u_options['subscribe_op_pm'], 1 => $lang_m_u_options['subscribe_op_email']);
        $tpl->tags('{subscribe_op}', select_code("subscribe", $subscribe, $moptions['subscribe']));
        
        $online_op = array(0 => $lang_m_u_options['online_op_show'], 1 => $lang_m_u_options['online_op_hide']);
        $tpl->tags('{online_op}', select_code("online", $online_op, $moptions['online']));
        
        $tpl->tags_blocks("posts_ajax", intval($cache_config['posts_get_next_page']['conf_value']));
        if (intval($cache_config['posts_get_next_page']['conf_value']))
        {
            $posts_ajax = array(1 => $lang_m_u_options['posts_ajax_no'], 0 => $lang_m_u_options['posts_ajax_yes']);
            $tpl->tags('{posts_ajax}', select_code("posts_ajax", $posts_ajax, $moptions['posts_ajax']));
        }
        else
            $tpl->tags('{posts_ajax}', "");
        
                
        if($row['user_id'] == $member_id['user_id'])
        {
            $tpl->tags_blocks("subscribe");        
            $tpl->tags('{subscribe}', member_subscribe());
        }
        else
            $tpl->tags_blocks("subscribe", false); 
            
        if(intval($cache_group[$row['user_group']]['g_status']))
        {
            $tpl->tags_blocks("edit_status");
            $tpl->tags('{profile_edit_status}', profile_edit_link($row['name'], $row['user_id'], "status"));
        }
        else
            $tpl->tags_blocks("edit_status", false);
             
        $tpl->tags('{profile_edit_options}', profile_edit_link($row['name'], $row['user_id'], "options"));
    
        $tpl->compile('content');
        $tpl->clear();
    }
}
else
{
    $link_speddbar = speedbar_forum (0, true)."|".$lang_m_u_options['location_error'];
    $onl_location = $lang_m_u_options['location_online_error'];
    message ($lang_message['error'], $lang_m_u_options['not_found'], 1);
}

$DB->free();

?>