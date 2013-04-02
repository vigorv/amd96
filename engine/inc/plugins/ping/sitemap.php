<?php
if( ! defined( 'DATALIFEENGINE' ) ) {
die( "Hacking attempt!" );
}

@ini_set ('memory_limit',"128M");
@set_time_limit (0);
@ini_set ('max_execution_time',0);
define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');


require_once ENGINE_DIR . '/inc/plugins/ping/ping.func.php';
include_once ENGINE_DIR.'/classes/google.class.php';
$pgg = array();
global $config,$config_rss,$db;

	$map = new googlemap($config);
	$sitemap = $map->build_map();
    $handler = fopen(ROOT_DIR. "/uploads/sitemap.xml", "wb+");
    fwrite($handler, $sitemap);
    fclose($handler);
	@chmod(ROOT_DIR. "/uploads/sitemap.xml", 0666);

$url = $config['http_home_url']."sitemap.xml";
if($rss_config['yahookey'] == "" or !$rss_config['yahookey']){
$ping_url = array (
"http://ping.blogs.yandex.ru/ping?sitemap=".$url,
"http://api.moreover.com/ping?u=".$url,
"http://www.google.com/webmasters/sitemaps/ping?sitemap=".$url,
"http://www.submissions.ask.com/ping?sitemap=".$url,
"http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url=".$url,
"http://www.bing.com/webmaster/ping.aspx?siteMap=".$url
);
}else{
$ping_url = array (
"http://ping.blogs.yandex.ru/ping?sitemap=".$url,
"http://api.moreover.com/ping?u=".$url,
"http://www.google.com/webmasters/sitemaps/ping?sitemap=".$url,
"http://www.submissions.ask.com/ping?sitemap=".$url,
"http://www.bing.com/webmaster/ping.aspx?siteMap=".$url,
"http://www.search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=".$rss_config['yahookey']."&url=".$url
);
}
$pgg = weblog_ping($ping_url);

if (count($pgg) != '0')$ping_ms =  ' <br /><b><font color="red">Карта отправлена</font></b><br />';

if (count($pgg) != '0') {
$month[1] = "Январ";
$month[2] = "Феврал";
$month[3] = "Март";
$month[4] = "Апрел";
$month[5] = "Ма";
$month[6] = "Июн";
$month[7] = "Июл";
$month[8] = "Август";
$month[9] = "Сентябр";
$month[10] = "Октябр";
$month[11] = "Ноябр";
$month[12] = "Декабр";
$dnum = date("w");
$mnum = date("n");
$daym = date("d");
$year = date("Y");
$textday = $day[$dnum];
$monthm = $month[$mnum];
if ($mnum==3||$mnum==8){$k="а";}else{$k="я";}
$time = date('H:i:s');
$entry_line = "$daym $monthm$k $year года, в $time  | <font color=red>Отправка карты сайта в поисковые системы</font>\n";
$fp = fopen(ENGINE_DIR."/cache/system/pinglogs.txt", "a");fputs($fp, $entry_line);fclose($fp);}
?>

