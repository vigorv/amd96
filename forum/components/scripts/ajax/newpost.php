<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

@session_start ();

@error_reporting ( E_ERROR );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LB_MAIN', realpath("../../../") );

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads/' );

require_once LB_CLASS . '/database.php';
include_once LB_CONFIG . '/board_db.php';

if (get_magic_quotes_gpc())
{
    include_once LB_CLASS. "/magic_quotes_gpc.php";
    $mq_gpc = new mq_gpc();
    $mq_gpc->del_slashes();
    unset($mq_gpc);  
}

$_IP = $_SERVER['REMOTE_ADDR'];

require_once LB_CLASS . '/cache.php';
require_once LB_GLOBAL . '/creat_cache.php';

if ($cache_config['general_coding']['conf_value'] != "utf-8")
{
    require_once LB_CLASS . '/ajax_data.php';
    $ajax_unicode = new ajax_unicode;
    $ajax_unicode->input('post');
    unset($ajax_unicode);
}

include_once LB_CLASS. "/flood_recorder.php";
$LB_flood = new LB_Flood();

$LB_flood->loadpage = intval($cache_config['antiflood_loadpage']['conf_value']);
$LB_flood->load_interval = intval($cache_config['antiflood_load_interval']['conf_value']);

$LB_flood->buttom = intval($cache_config['antiflood_buttom']['conf_value']);
$LB_flood->interval = intval($cache_config['antiflood_interval']['conf_value']);
$LB_flood->block_time = intval($cache_config['antiflood_blocktime']['conf_value']);

$redirect_url = $cache_config['general_site']['conf_value'];
$onl_limit = $time - (intval($cache_config['online_time']['conf_value']) * 60);

require_once LB_GLOBAL . '/functions.php';
require_once LB_MAIN . '/components/scripts/bbcode/function.php';
require_once LB_GLOBAL . '/login.php';

if ($cache_config['antiflood_parse']['conf_value'])
{
    if ($LB_flood->isBlock("1"))
        stop_script("Anti-flood system. Banned for ".$LB_flood->block_time." seconds.");
}

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
    stop_script("Offline.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    stop_script("Banned.");
}

if (isset($_POST['template']) AND $cache_config['general_template']['conf_value'])
{
	$_POST['template'] = trim(totranslit($_POST['template'], false));

	if ($_POST['template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_POST['template']))
    {
		$cache_config['template_name']['conf_value'] = $_POST['template'];
  	}
}
elseif (isset($_COOKIE['LB_template']) AND $cache_config['general_template']['conf_value'])
{
	$_COOKIE['LB_template'] = trim(totranslit($_COOKIE['LB_template'], false));

	if ($_COOKIE['LB_template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_COOKIE['LB_template']))
		$cache_config['template_name']['conf_value'] = $_COOKIE['LB_template'];
}

require_once LB_CLASS . '/templates.php';
$tpl = new LB_Template ( );
$tpl->dir = LB_MAIN . '/templates/'.$cache_config['template_name']['conf_value'];

$tid = intval($_POST['tid']);
    
if (!$tid)
{
    stop_script("Topic ID is 0.");
}
    
$lang_s_a_newpost = language_forum ("board/scripts/ajax/newpost");
header( "Content-type: text/html; charset=".$LB_charset );
    
$topic = $DB->one_select( "*", "topics", "id = '{$tid}'" );

if ($topic['id'] AND !forum_permission($topic['forum_id'], "read_theme"))
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['access_denied_read_theme']);
    stop_script();
}
elseif (!$cache_group[$member_id['user_group']]['g_reply_topic'] AND $topic['member_id_open'] != $member_id['user_id'] AND $logged)
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['access_denied_reply']);
    stop_script();
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "read_forum"))
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['access_denied_read_forum']);
    stop_script();
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "answer_theme"))
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['access_denied_answer_theme']);
    stop_script();
}
elseif ($topic['status'] == "closed" AND !forum_options_topics("0", "reply_close") AND !$cache_group[$member_id['user_group']]['g_reply_close'])
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['topic_close']);
    stop_script();
}
elseif ($topic['id'] AND $topic['hiden'] AND !forum_options_topics($topic['forum_id'], "hideshow"))
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['access_denied_hide']);
    stop_script();
}
elseif ($topic['id'] AND forum_all_password($topic['forum_id']))
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['access_denied_pass']);
    stop_script();
}
elseif (!member_publ_access(1))
{   
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], str_replace("{info}", member_publ_info(), $lang_s_a_newpost['access_denied_publ']));
    stop_script();  
}
elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic['forum_id'])
{
    echo show_jq_message("3", $lang_s_a_newpost['access_denied'], $lang_s_a_newpost['forum_basket']);
    stop_script();
}
elseif($topic['id'])
{    
    if ($LB_flood->isBlock())
    {
        echo show_jq_message("3", $lang_s_a_newpost['flood_control'], str_replace("{time}", $LB_flood->block_time, $lang_s_a_newpost['flood_control_stop']));
        stop_script();
    }
    else
    {
        $bb_allowed_out = array();
        if ($cache_forums[$topic['forum_id']]['allow_bbcode'])
        {
            if ($cache_forums[$topic['forum_id']]['allow_bbcode_list'] AND $cache_forums[$topic['forum_id']]['allow_bbcode_list'] != "0")
            {
                include LB_MAIN . '/components/scripts/bbcode/bbcode_list.php';
                $allow_bbcode_list = explode(",", $cache_forums[$topic['forum_id']]['allow_bbcode_list']);
                foreach($allow_bbcode_list as $value)
                {
                    $bb_allowed_out[] = $list_allow_bbcode_arr[$value]['name'];
                }
            }
        }
        
        include_once LB_CLASS.'/posts_out.php';
        $LB_posts = new LB_posts;
    
        $LB_posts->add_post($lang_s_a_newpost, $topic, $bb_allowed_out, "ajax", false);

        $last_post = $DB->one_select( "pid", "posts", "topic_id = '{$topic['id']}' AND post_date = '{$time}'", "ORDER BY post_date DESC LIMIT 1" );
        $post_id = $last_post['pid'];
    
        $DB->prefix = array ( 2 => DLE_USER_PREFIX );
        $LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num, t.forum_id, t.hiden, t.title, t.basket, t.status, t.member_id_open", "LEFT", "posts p||topics t||users u||members_online mo", "p.topic_id=t.id||p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", "p.pid = '{$post_id}'" );
        $LB_posts->Data_out("board/topic_posts.tpl", "message", "", true, true, false, false, true, false);
    
        unset($LB_posts);

        $tpl->global_tags ('message');
                      
        $small_img = "<script type=\"text/javascript\">Resize_img();</script>";
                      
        $pid_t = time();
                        
        $show_post_jq = "<script type=\"text/javascript\">
            $(document).ready(function()
            { 
                $('div#newpost-out-jq".$pid_t."').slideDown(1000);
                $('#newpost-form :input#tf').val('');
            });
            </script>";
        $tpl->result['message'] = "<div id=\"newpost-out-jq".$pid_t."\" style=\"display:none\">".$tpl->result['message']."</div>".$show_post_jq;
            
        if ($post_past)
        {
            echo '
                <script type="text/javascript">
                $(document).ready(function()
                { 
                    if ($("#post-'.$post_id.'").length != "0")
                    {
                        $("#post-'.$post_id.'").attr("id", "'.$pid_t.'");                    
                        $("#'.$pid_t.'").parents(".rl_item").eq(0).slideToggle(1100);
                        setTimeout(function(){ $("#'.$pid_t.'").parents(".rl_item").eq(0).remove(); }, 1100);
                    }
                });
                </script>';
        }

        echo $tpl->result['message'].$small_img; 
        echo show_jq_message ("1", $lang_s_a_newpost['successful'], $lang_s_a_newpost['successful_info']);         
    }
}
else
{
    echo show_jq_message("3", $lang_s_a_newpost['not_found'], $lang_s_a_newpost['not_found_info']);
    stop_script();
}
        
stop_script();

?>