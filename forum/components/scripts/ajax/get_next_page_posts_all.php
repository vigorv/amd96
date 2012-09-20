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
    
if (isset($_GET['content_end']) AND intval($_GET['content_end']))
    exit ();

@session_start ();

if ($_SESSION['Get_Next_Post_Buttom'] == 1)
    exit();

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

if ($logged)
{
    if ($member_options['posts_ajax'])
        stop_script();
}

if (!$cache_config['posts_get_next_page']['conf_value'])
{
    stop_script();
}

if ($cache_config['antiflood_parse']['conf_value'])
{
    if ($LB_flood->isBlock("1"))
        stop_script ("Anti-flood system. Banned for ".$LB_flood->block_time." seconds.");
}

if ($cache_config['general_close']['conf_value'] AND $cache_group[$member_id['user_group']]['g_show_close_f'] != 1)
{
    stop_script ("Offline.");
}

$banned_ip = LB_banned("ip", $_IP);
$banned_name = LB_banned("user_id", $member_id['user_id']);
if ($banned_ip OR $banned_name)
{
    stop_script ("Banned.");
}

if (isset($_GET['template']) AND $cache_config['general_template']['conf_value'])
{
	$_GET['template'] = trim(totranslit($_GET['template'], false));

	if ($_GET['template'] != "" AND @is_dir(LB_MAIN . "/templates/" . $_GET['template']))
    {
		$cache_config['template_name']['conf_value'] = $_GET['template'];
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

$lang_s_a_gnp_posts_all = language_forum ("board/scripts/ajax/get_next_page_posts_all");
$lang_message = language_forum ("board/lang_message");
header( "Content-type: text/html; charset=".$LB_charset );

$where = array();

filters_input('get');

if (isset($_GET['member_name']) AND $_GET['member_name'] != "")
{
    if ($cache_config['general_coding']['conf_value'] != "utf-8")
    {
        require_once LB_CLASS . '/ajax_data.php';
        $ajax_unicode = new ajax_unicode;
        $ajax_unicode->input('get');
        unset($ajax_unicode);
    }
    
    $member_name = $DB->addslashes(strip_tags(urldecode( $_GET['member_name'] ) ) );
    $DB->prefix = DLE_USER_PREFIX;
    $user_out = $DB->one_select( "user_id, name", "users", "name='{$member_name}'" );
    
    if (!$user_out['user_id'])
    {
        stop_script ("User not found.");
    }
    else
    {
        $where[] = "p.post_member_id = '{$user_out['user_id']}'";
    }    
}
    
if (isset ( $_GET['page'] ) AND intval($_GET['page']) > 1)
    $page = intval ( $_GET['page'] ) + 1;
else
    $page = 2;

if ($page < 0)
    $page = 0;
    
$go_page = $page;
       
if ($page)
{
    $page = $page - 1;
    $page = $page * $cache_config['topic_post_page']['conf_value'];
}

$i = $page;
        
$last_post_i = intval($_GET['last_post_i']);
    
if ($i%$last_post_i != 0)
    stop_script();
        
$go_show = "";

$access_forums = array();
foreach ($cache_forums as $cf)
{
    if(forum_permission($cf['id'], "read_forum"))
    {
        if (forum_all_password($cf['id']))
            $access_forums[] = $cf['id'];
    }
    else
        $access_forums[] = $cf['id'];
}    
        
if (!forum_options_topics(0, "allpermission"))
    $where[] = "t.hiden = '0'";
    
$access_forums = implode (",", $access_forums);
    
if ($access_forums)
{
    $where[] = "t.forum_id NOT IN (".$access_forums.")";
}
        
$where[] = "t.basket = '0'";

if ($where[0] != "")
    $where_db = implode(" AND ", $where);
else
    $where_db = "";
    
$go_end = 1;
    
include LB_CLASS.'/posts_out.php';
$LB_posts = new LB_posts;
    
$DB->prefix = array ( 2 => DLE_USER_PREFIX );
$LB_posts->query = $DB->join_select( "p.*, mo.mo_id, mo.mo_date, u.name, user_id, banned, user_group, foto, signature, posts_num, topics_num, t.forum_id, t.hiden, t.title, t.basket, t.status, t.member_id_open", "LEFT", "posts p||topics t||users u||members_online mo", "p.topic_id=t.id||p.post_member_id=u.user_id||u.user_id=mo.mo_member_id", $where_db, "ORDER by post_date DESC LIMIT ".$page.", ".$cache_config['topic_post_page']['conf_value'] );
$LB_posts->Data_out("board/topic_posts.tpl", "posts", "", true, true, false, true, true);
        
unset($LB_posts);
    
if (utf8_strlen($tpl->result['posts']) > 100)
    $go_end = 0;

if (!$go_end)
{ 
    $tpl->global_tags ('posts');                                           
    echo $tpl->result['posts']; 

    echo show_jq_message ("1", $lang_s_a_gnp_posts_all['done_title'], $lang_s_a_gnp_posts_all['done_info'], 1800)."<script type=\"text/javascript\">show_gnpp_panel();</script>";     
}
    
echo '
<span id="script_update">
<script type="text/javascript">
loaded_content = false;
loaded_content_hieght = $(window).scrollTop() + $(window).height();
loaded_content_page = "'.$go_page.'";
loaded_content_post = "'.$i.'";
loaded_content_end = "'.$go_end.'";
loaded_content_buttom = false;
go_show = "'.$go_show.'";
    
setTimeout(function(){ 
    $(document).ready(function(){
                        
    top_s = $("span#newpost-out").offset().top;
                        
    function check(){
                
        if ($("span#newpost-out").length == "0")
        {
            show_message("3", LB_lang[\'error\'], LB_lang[\'no_object\'] + "span#newpost-out");
            return false;
        }
                    
        top_s = $("span#newpost-out").offset().top;
        loaded_content_hieght = $(window).scrollTop() + $(window).height() + 120;
                    
        if ( !loaded_content && !loaded_content_buttom && loaded_content_hieght > top_s ) {
                                
            show_loading_message();
                        
            $.get(LB_root + "components/scripts/ajax/get_next_page_posts_all.php", {member_name:"'.$_GET['member_name'].'", page:loaded_content_page, last_post_i:loaded_content_post, go:go_show, content_end:loaded_content_end}, function(data){
                $("span#newpost-out").before(data);
                remove_loading_message();
            });
                 
            Error_AJAX_jQ ("span#newpost-out");
                    
            loaded_content = true;
        }
    }
            
    $(window).scroll(check);
    
    });
}, 500);
</script>
</span>';          
        
stop_script ();

?>