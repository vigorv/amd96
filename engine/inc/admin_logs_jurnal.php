<?
if(!defined('DATALIFEENGINE'))
die("Hacking attempt!");
if ($config['version_id'] <8)
{
$member_id['user_group'] = $member_db[1];
}
if($member_id['user_group'] <= 2 and $action == "dologin")
{
header("Location: {$config['http_home_url']}index.php?mod=options&action=personal");exit;
}
if($action == "list"OR $action == "")
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_list.php';
}
elseif($action == "logs_news"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_news_com"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_aul"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_aol"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_adl"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_aal"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_autorization"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_lc"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_category"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_apx"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_templates"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_vote"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_banners"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_app"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "logs_lostpass"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal.php';
}
elseif($action == "config"and $member_id['user_group'] == 1)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_config.php';
}
elseif($action == "info"and $member_id['user_group'] == 1)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_info.php';
}
elseif($action == "logs_files"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
if (@file_exists( ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_files.php'))
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_files.php';
else
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_list.php';
}
elseif($action == "logs_files_com"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
if (@file_exists( ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_files_com.php'))
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_files_com.php';
else
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_list.php';
}
elseif($action == "logs_forum"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
if (@file_exists(  ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_forum.php'))
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_forum.php';
else
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_list.php';
}
elseif($action == "logs_posts"AND $subaction != "delete"and $member_id['user_group'] <= 2)
{
if (@file_exists(  ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_posts.php'))
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_posts.php';
else
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_list.php';
}
elseif(($action == "logs_templates"OR $action == "logs_lostpass"OR $action == "logs_autorization"OR $action == "logs_app"OR $action == "logs_news"OR $action == "logs_aol"OR $action == "logs_aul"OR $action == "logs_news_com"OR $action == "logs_aal"OR $action == "logs_adl"OR $action == "logs_category"OR $action == "logs_lc"OR $action == "logs_vote"OR $action == "logs_templates"OR $action == "logs_apx"OR $action == "logs_forum"OR $action == "logs_posts"OR $action == "logs_files"OR $action == "logs_files_com"OR $action == "logs_banners") AND $subaction == "delete"and $member_id['user_group'] == 1)
{
include_once ENGINE_DIR.'/inc/logs_jurnal/logs_jurnal_del.php';
}
else
echo "Доступ отклонён.";
?>