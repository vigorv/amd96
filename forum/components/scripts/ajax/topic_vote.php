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

$is_vote = intval($_POST['vote']);
$tid = intval($_POST['tid']);

if ($is_vote == 1)
{
    $text = urldecode($_POST['text']);
    $text = explode ("&", $text);
    $vote_variant = array();
    foreach($text as $post)
    {
        $post_mass = explode ("=", $post);
        if (substr($post_mass[0], 0, 2) == "tp")
        {
            $vid = intval($post_mass[1]);
            $vote_variant[] = $vid;
        }    
        unset($post_mass);
    }
}

if (!$tid)
{
    stop_script("Topic ID is 0.");
}

function echo_poll()
{   
    $script = "
    <script type='text/javascript'>
    $(document).ready(function()
    { 
        $('#topic_vote_jq').slideDown(500);
        $('#topic_vote_jq_result').remove();
    });
    </script>
    ";
    return $script;
}
    
$lang_s_a_topic_vote = language_forum ("board/scripts/ajax/topic_vote");
header( "Content-type: text/html; charset=".$LB_charset );
    
$topic = $DB->one_join_select( "t.id, t.forum_id, t.hiden, t.poll_id, p.vote_num, p.title as p_title, p.question, p.variants, p.multiple, p.answers", "LEFT", "topics t||topics_poll p", "t.poll_id=p.id", "t.id = '{$tid}'" );

if ($topic['id'] AND !forum_permission($topic['forum_id'], "read_theme"))
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['access_denied_read']);
    echo echo_poll();
    stop_script();
}
elseif ($topic['id'] AND !forum_permission($topic['forum_id'], "read_forum"))
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['access_denied_forum']);
    echo echo_poll();
    stop_script();
}
elseif ($topic['id'] AND $topic['hiden'] AND !forum_options_topics($topic['forum_id'], "hideshow"))
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['access_denied_hideshow']);
    echo echo_poll();
    stop_script();
}
elseif ($topic['id'] AND forum_all_password($topic['forum_id']))
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['access_denied_pass']);
    echo echo_poll();
    stop_script();
}
elseif($cache_config['basket_on']['conf_value'] AND $cache_config['basket_fid']['conf_value'] == $topic['forum_id'])
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['forum_basket']);
    echo echo_poll();
    stop_script();
}
elseif(!$cache_forums[$topic['forum_id']]['allow_poll'])
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['access_denied_poll']);
    echo echo_poll();
    stop_script();
}
elseif(!$topic['poll_id'])
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['not_found_poll']);
    echo echo_poll();
    stop_script();
}
elseif (!count($vote_variant) AND $is_vote == 1)
{
    echo show_jq_message("3", $lang_s_a_topic_vote['error'], $lang_s_a_topic_vote['no_answer']);
    echo echo_poll();
    stop_script();
}
elseif($cache_forums[$topic['forum_id']]['allow_poll'] == 1 AND !$logged)
{
    echo show_jq_message("3", $lang_s_a_topic_vote['access_denied'], $lang_s_a_topic_vote['access_denied_poll_guest']);
    echo echo_poll();
    stop_script();
}
elseif($topic['id'] AND $is_vote == 1)
{
    include LB_CLASS.'/topics_out.php';
    $LB_topics = new LB_topics;
    $topic_poll_arr = array(
        "id"        => $topic['id'],
        "forum_id"  => $topic['forum_id'],
        "variants"  => $topic['variants'],
        "poll_id"   => $topic['poll_id'],
        "multiple"  => $topic['multiple'],
        "answers"   => $topic['answers']
    );
    
    $LB_topics->do_vote ($topic_poll_arr, true);
    unset($LB_topics);
    
    // если будет ошибка при голосовании, то дальнейший код не будет выполнен
                
    $tpl->load_template ( 'board/topic_poll.tpl' );
    $tpl->tags('{title}', $topic['p_title']); 
    $tpl->tags('{question}', $topic['question']); 
    $tpl->tags('{tid}', $topic['id']);
        
    $topic['vote_num'] = $topic['vote_num'] + 1;
    
    $answers = $DB->one_select( "answers", "topics_poll", "id = '{$topic['poll_id']}'" );
        
    $tpl->tags('{variants}', topic_poll_logs($topic['variants'], $answers['answers'], $topic['vote_num']));  
    $tpl->tags_blocks("result", false);
    $tpl->tags_blocks("vote", false);          

    $tpl->compile('poll');  
    $tpl->global_tags ('poll');
                
    echo show_jq_message ("1", $lang_s_a_topic_vote['done_title'], $lang_s_a_topic_vote['poll_ok']);
    echo $tpl->result['poll'];
            
    stop_script();
}
elseif($topic['id'] AND !$is_vote)
{
    $tpl->load_template ( 'board/topic_poll.tpl' );
    $tpl->tags('{title}', $topic['p_title']); 
    $tpl->tags('{question}', $topic['question']); 
    $tpl->tags('{tid}', $topic['id']);
        
    $tpl->tags('{variants}', topic_poll_logs($topic['variants'], $topic['answers'], $topic['vote_num']));
    $tpl->tags_blocks("result");
    $tpl->tags_blocks("vote", false);

    $tpl->tags('{vote_link}', $link_nav);
    $tpl->tags('{poll_link}', $link_nav);

    $tpl->compile('poll');  
    $tpl->global_tags ('poll');
                
    echo show_jq_message ("1", $lang_s_a_topic_vote['done_title'], $lang_s_a_topic_vote['poll_result']);
    echo $tpl->result['poll'];    
    stop_script();
}
elseif($topic['id'] AND $is_vote == 2)
{
    $tpl->load_template ( 'board/topic_poll.tpl' );
    $tpl->tags('{title}', $topic['p_title']); 
    $tpl->tags('{question}', $topic['question']); 
    $tpl->tags('{tid}', $topic['id']);
    
    $tpl->tags('{variants}', topic_poll_variants($topic['variants'], $topic['multiple']));
    $tpl->tags_blocks("result", false);
    $tpl->tags_blocks("vote");
    $tpl->tags('{vote_link}', $link_nav);
    $tpl->tags('{poll_link}', $link_nav);

    $tpl->compile('poll');  
    $tpl->global_tags ('poll');
                
    echo show_jq_message ("1", $lang_s_a_topic_vote['done_title'], $lang_s_a_topic_vote['can_poll']);
    echo $tpl->result['poll'];      
    stop_script();
}
else
{
    echo show_jq_message("3", $lang_s_a_topic_vote['error'], $lang_s_a_topic_vote['error_info']);
    echo echo_poll();
    stop_script();
}
        
stop_script();

?>