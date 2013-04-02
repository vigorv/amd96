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

include_once ENGINE_DIR.'/data/config.php';
require_once ENGINE_DIR . '/inc/plugins/ping/ping.func.php';

global $config,$config_rss,$db;

$ping_url = array_map("trim", file(ENGINE_DIR."/inc/plugins/ping/pingsite.txt"));  
if(count($ping_url)==""){
clear_cache();
exit();
}

$row = $db->super_query ("SELECT id, date, title, category, alt_name, flag FROM ".PREFIX ."_post WHERE id= '$news_id'");
$pgg = array();
$row['date'] = strtotime( $row['date'] );
if( $config['allow_alt_url'] == "yes" ) {
if( $row['flag'] and $config['seo_type'] ) {
if( $row['category'] and $config['seo_type'] == 2 ) {
$url = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
} else {
$url = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
}
} else {
$url = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
}
} else {
$url = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
}
$pgg = weblog_ping($ping_url, $row['title'], $url);


if (count($pgg) != '0') $ping_msg = '   <b><font color="orange">'.$lang_grabber['ping_msg'].'</font></b>';

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
$entry_line = "$daym $monthm$k $year года, в $time  | <a href=\"index.php?newsid=$news_id\" target=\"_blank\" >". stripslashes( stripslashes( $title ) ) ."</a>\n";
$fp = fopen(ENGINE_DIR."/cache/system/pinglogs.txt", "a");
fputs($fp, $entry_line);
fclose($fp);
}
?>