<?
if(!defined('DATALIFEENGINE'))
die("Hacking attempt!");
if ($_REQUEST['user_hash'] == ""OR $_REQUEST['user_hash'] != $dle_login_hash)
die("Hacking attempt! User not found");
echoheader("","");
if($action == "logs_aal") {
$base_name = "admin_authoriz_logs";
}elseif($action == "logs_autorization") {
$base_name = "users_authoriz_logs";
}elseif($action == "logs_adl") {
$base_name = "admin_delivery_logs";
}elseif($action == "logs_aol") {
$base_name = "admin_optim_logs";
}elseif($action == "logs_aul") {
$base_name = "admin_users_logs";
}elseif($action == "logs_news") {
$base_name = "post_logs";
}elseif($action == "logs_news_com") {
$base_name = "comments_logs";
}elseif($action == "logs_lc") {
$base_name = "pm_logs";
}elseif($action == "logs_category") {
$base_name = "category_logs";
}elseif($action == "logs_apx") {
$base_name = "post_xfields_logs";
}elseif($action == "logs_app") {
$base_name = "users_xfields_logs";
}elseif($action == "logs_templates") {
$base_name = "templates_logs";
}elseif($action == "logs_vote") {
$base_name = "vote_logs";
}elseif($action == "logs_banners") {
$base_name = "banners_logs";
}elseif($action == "logs_lostpass") {
$base_name = "lostdb_logs";
}elseif($action == "logs_forum") {
$base_name = "forum_topics_logs";
}elseif($action == "logs_posts") {
$base_name = "forum_posts_logs";
}elseif($action == "logs_files") {
include(ENGINE_DIR.'/data/files_config_global.php');
$base_name = $modul_dbtitle."_actionlog";
}elseif($action == "logs_files_com") {
include(ENGINE_DIR.'/data/files_config_global.php');
$base_name = $modul_dbtitle."_com_logs";
}
$id = intval($_GET['id']);
$date = $_GET['clean'];
$selected = $_POST['selected_all'];
if(!$id AND !$date)
{
if ($selected)
{
foreach	($selected as $id)
{
$id = intval($id);
$db->query("DELETE FROM `".PREFIX."_".$base_name."` WHERE `id` = '{$id}'");
}
echo "<center><b><div class='main'>Логи почищены!</div></b></center>";
echo "<center>&nbsp;&nbsp;<a class=main href='".$config['http_home_url'].$config['admin_path']."?mod=admin_logs_jurnal&action=".$action."'><b>В начало</b></a></center>";
}
else
echo "<div class='main'><center><b>Не выбрано действие<b></center></div>";
}
else
{
if($date == "all")
{
$thisdate = time () +($config ['date_adjust'] * 60);
$thisdate = $thisdate -2678400;
if ($action != "logs_lc")
{
$thisdate = date ( "Y-m-d H:i:s",$thisdate );
}
$db->query("DELETE FROM `".PREFIX ."_".$base_name."` WHERE `date` <= '{$thisdate}'");
}
else
$db->query("DELETE FROM `".PREFIX ."_".$base_name."` WHERE `id` = '{$id}'");
echo "<center><b><div class='main'>Логи почищены!</div></b></center>";
echo "<center>&nbsp;&nbsp;<a class=main href='".$config['http_home_url'].$config['admin_path']."?mod=admin_logs_jurnal&action=".$action."'><b>В начало</b></a></center>";
}
echofooter();
?>