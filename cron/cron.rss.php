<?php
/*
=====================================================
 Скрипт модуля Rss Grabber

 http://rss-grabber.ru/
 Автор: Andersoni
 со Автор: Alex
 Copyright (c) 2009-2010
=====================================================
*/

@ini_set ('memory_limit',"128M");
@set_time_limit (0);
@ini_set ('max_execution_time',0);
@ini_set ('2048M');
@ini_set ('output_buffering','off');
@ob_end_clean ();
clearstatcache ();
ob_implicit_flush (TRUE);
error_reporting (1);
define('DATALIFEENGINE',true);
extract($_REQUEST,EXTR_SKIP);
define('ROOT_DIR',dirname(dirname(__FILE__)));
define('ENGINE_DIR',ROOT_DIR .'/engine');
require_once ENGINE_DIR .'/init.php';
require_once ENGINE_DIR .'/classes/parse.class.php';
$parse = new ParseFilter (array (),array (),1,1);
require_once ENGINE_DIR .'/inc/plugins/core.php';
require_once ENGINE_DIR .'/inc/plugins/rss.classes.php';
require_once ENGINE_DIR .'/inc/plugins/rss.functions.php';
require_once ENGINE_DIR .'/inc/plugins/rss.parser.php';
@include_once ENGINE_DIR.'/data/rss_config.php';
$start1=gettimeofday();
$i=0;

for ($x=0;$x<2;$x++){
$i=0;
if($grabber == true) break;
$rss_cron_array = get_vars('cron.rss');
if (!$rss_cron_array)$rss_cron_array = array();
$rss_cron_data = get_vars('cron.rss.data');
if (!$rss_cron_data) $rss_cron_data = array();

$sql_result = $db->query("SELECT * FROM ".PREFIX ."_rss WHERE allow_auto = '1' ORDER BY xpos asc");
$pnum = $db->num_rows ($sql_result);
$found = false;
if ($config_rss['get_proxy'] == "yes") get_proxy();
while ($channel_info = $db->get_row($sql_result)) {
$grabber = false;
if ($pnum == 0) break;
if ( count($rss_cron_array) >= $pnum ) $rss_cron_array = array();
$channel_id = $channel_info['id'];
$found = in_array($channel_id,$rss_cron_array);
if (!$found) {
++$i;
//var_export ($rss_cron_array);
$rss_cron_array[] = $channel_id;
$data_cron = time() - $rss_cron_data[$channel_id];
$dnast = explode ('=',$channel_info['dnast']);
if (intval($dnast[16]) != 0)$cron_data = $dnast[16]*60;
	else $cron_data = 0;
if ($data_cron >= $cron_data)
	{
$rss_cron_data[$channel_id] = time();
set_vars('cron.rss.data',$rss_cron_data);
set_vars('cron.rss',$rss_cron_array);
if ( count($rss_cron_array) >= $pnum ) $rss_cron_array = array();
if($config_rss['get_prox'] = $tab_id)$grabber = start_process($channel_id,$channel_info);
}else{
$cron_dop_time = $cron_data - $data_cron;
	echo "<B><font color=#993300>№".$channel_info['xpos']." ".$channel_info['title']."</font></B> оставшееся время для следующего запуска".date( "i:s",$cron_dop_time)."<br />";

set_vars('cron.rss',$rss_cron_array);
}
	}
//if ($pnum == $i)$grabber = true;
if($grabber == true) break;
}

}

if( function_exists('memory_get_peak_usage') ) {
$mem_usage = memory_get_peak_usage(true);
if ($mem_usage < 1024)
echo $mem_usage." bytes";
elseif ($mem_usage < 1048576)
$memory_usage = round($mem_usage/1024,2)." кб";
else
$memory_usage = round($mem_usage/1048576,2)." мб";
}

$end1=gettimeofday();
$totaltime1 = (float)($end1['sec'] - $start1['sec']) + ((float)($end1['usec'] - $start1['usec'])/1000000);

echo "<br /><br /> Использовано памяти - ".$memory_usage."<br />Время выполнения - ".$totaltime1;



function start_process($channel_id,$channel_info)
{
global $db,$parse,$config,$config_rss,$cron_job;

$id_list = array();
$end_title = explode ('==',$channel_info['end_title']);
$dop_sort = explode ('=',$channel_info['short_story']);
$dop_nast = explode ('=',$channel_info['dop_nast']);
$ctp = explode ('=',$channel_info['ctp']);
$start_template = stripslashes ($channel_info['start_template']);
$finish_template = stripslashes ($channel_info['finish_template']);
$sart_cat = explode('|||', $channel_info['sart_cat']);
$dnast = explode ('=',$channel_info['dnast']);
$cookies = str_replace('|||',"; ",str_replace("\r","",stripslashes(rtrim($channel_info['cookies']))));
$allow_main	= $channel_info['allow_main'];
$dates = explode ('=',$channel_info['date']);
$allow_mod	= $channel_info['allow_mod'];
$allow_comm	= $channel_info['allow_comm'];
$allow_rate	= $channel_info['allow_rate'];
if ($allow_mod == 1){$approve = "0";}else{$approve = "1";}
$hide_leech = explode('=',$channel_info['end_short']);
$rss = $channel_info['rss'];
if (trim($dop_nast[14]) != '' or $dop_nast[14] != '0')$charsets = explode("/",$dop_nast[14]);
if ($rss == 1){$ctp[0] = 0;$ctp[1] = 0;}

$rss_parser = new rss_parser();

if ($ctp[1] > 0 and intval($ctp[0]) == 0) $ctp[0] = '1';
for ($cv=intval($ctp[0]);$cv<=intval($ctp[1]);$cv++)
{

if ($cv != 0 and $rss == 0)
{
if ($channel_info['full_link'] == ''){
$rows = $channel_info['url'].'/page/'.$cv.'/';
}else {
$rows = str_replace ('{num}',$cv,$channel_info['full_link']);
}
if ($cv == 0 or $cv == 1 ) $pg = 'Главная страница';else $pg = 'Страница '.$cv;
echo '<table width="100%">
 <tr>
		<td	><a href="'.$rows.'" target="_blank"><b><font color="orange">'.$pg.'</font></b></a></td>
</tr>
</table>';
$URL = get_urls(trim($rows));
}else{$URL = get_urls(trim($channel_info['url']));}


if ($rss == 1){
$rss_parser->default_cp = $dop_nast[14];
$rss_result = $rss_parser->Get ($channel_info['url'],$dop_nast[2]);
}else{
$URLitems =	get_full ($URL[scheme],$URL['host'],$URL['path'],$URL['query'],$cookies,$dop_nast[2], $dop_sort[8]);
if (trim($dop_nast[14]) == ''or $dop_nast[14] == '0')$charik = charset($URLitems);else $charik = $charsets[0];
if ($channel_info['ful_start'] != '')$rss_result = get_page ($URLitems,$channel_info['ful_start']);else $rss_result = get_dle($URLitems);
}
$now_kol = false;
if ($rss_result) {
if ($rss == 1){
$rss_items = $rss_result['items'];
}else{
$rss_items = $rss_result;
}
$i = 0;
asort($rss_items);
foreach ($rss_items as $item) {
$dimages = '';
$xdoe = array();
if (intval($config_rss['news_limit']) !=0) {
if (intval($dnast[19]) !=0 )$config_rss['news_limit'] = $dnast[19];
if ($i == $config_rss['news_limit']) break;
}
if (intval($dop_nast[19]) != 0)sleep ($dop_nast[19]);
unset ($news_link);
unset ($news_tit);
if ($rss == 1){
$news_tit = rss_strip ($item['title']);
$short_story = $item['description'];
$news_link = stripslashes ($item['link']);
$tags_tmp = rss_strip ($item['category']);
}else{
if ($charik != strtolower($config['charset']) AND $item!= "") $item = convert($charik,strtolower($config['charset']),$item);
if (trim($channel_info['start_title']) != '')$news_tit = strip_tags(get_full_news($item,$channel_info['start_title']));
if ($channel_info['end_link'] != 1){
$short_story = get_short_news ($item,$channel_info['start_short']);
}else{
$short_story = get_full_news ($item,$channel_info['start_short']);
}
if (trim($channel_info['sart_link'])==''){
$tu_link = get_link ($item);
$news_link = 'http://'.$URL['host'].'/index.php?newsid='.$tu_link;
}else{
$news_lin = get_full_news($item,$channel_info['sart_link']);
$news_link = full_path_build ($news_lin,$URL['host']);
}
//$tags_tmp =strip_tags(get_full_news($item,$sart_cat[0]));
//$data_tmp =strip_tags(get_full_news($item,$sart_cat[1]));
}

if ($rss == 1){
if (trim ($news_link) == '')
{
$news_link = stripslashes ($item['guid']);
}
}

$cron_fp = fopen($cron_job, "a");
fputs($cron_fp, "\n".$news_link);
fclose($cron_fp);

if (trim($end_title[2]) != '' and trim($news_tit) != '') $news_tit =rss_strip( relace_news ($news_tit,$end_title[2],$end_title[3]));
$alt_name = $db->safesql (totranslit( stripslashes( $news_tit ),true,false ));
if ($dop_sort[12] == 0) {$where = " LIMIT 1";}
elseif ($dop_sort[12] == 1 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%'";}
elseif ($dop_sort[12] == 2) {$where = " WHERE alt_name = '".$alt_name."'";}
elseif ($dop_sort[12] == 3 and $news_link != '') {$where = " WHERE alt_name = '".$alt_name."' OR xfields like '%".$db->safesql ($news_link)."%'";}
else {$where = " WHERE alt_name = '".$alt_name."'";}
$sql_result = $db->query("SELECT * FROM ".PREFIX ."_post".$where);
if ($db->num_rows ($sql_result) == 0 or $news_tit =='' or $hide_leech[3] == 1 or $dop_sort[12] == 0)
{

$full_story = '';
$news_lik = $news_link;
for ($j=1; $j <= 10; $j++){

$stoped = false;
if (trim($channel_info['dop_full']) == '' and $j != '1' and $full_story != '' )$stoped = true;

if (trim ($start_template) != '' and !$stoped)
{
if ($dop_nast[18] == 0 ){
if (trim($channel_info['dop_full'])!= '' and $j >= 2){

$news_link = str_replace('http://', '', $news_lik);
$fl = explode('/',$news_link);
$news_linke = '';
for ($k=0;$k<(count($fl)-1); $k++){
$news_linke .= $fl[$k].'/';
}
$news_linke .= str_replace('{num}', $j, $channel_info['dop_full']);
$news_link = 'http://'.$news_linke.end($fl);
}
}
else{
if ($j >= 2) $news_link = $news_lik.$row['dop_full'];
$news_link= str_replace('{num}',$j,$news_link);
}



$link = replace_url(get_urls(trim($news_link)));
if (trim($end_title[4]) != '' and trim($news_link) != '') $news_link = relace_news ($news_link,$end_title[4],$end_title[5]);
$full = get_full ($link[scheme],$link['host'],$link['path'],$link['query'],$cookies,$dop_nast[2], $dop_sort[8]);
}
else
{
break;
}

//echo "<textarea style=\"width:98%; height:200px\" >{$short_story}</textarea>";

if (trim ($full) != ''){
if (trim($dop_nast[14]) == '' or $dop_nast[14] == '0')$charik = charset($full); else $charik = strtolower($charsets[1] != ''?$charsets[1]:$charsets[0]);
if (trim ($sart_cat[1]) != '')$data_tmp =strip_tags(get_full_news($full,$sart_cat[1]));
if (trim ($tags_tmp) == ''){
if (trim ($sart_cat[0]) != '')$tags_tmp =strip_tags(get_full_news($full,$sart_cat[0]));
if ($charik != strtolower($config['charset']) and trim ($tags_tmp) != ''and trim ($charik) != '') {$tags_tmp = convert($charik,strtolower($config['charset']),$tags_tmp);}
}

if (trim ($start_template) != '')
{
if (trim ($finish_template) != '')
{
$full_storys = get_news ($full,$start_template,$finish_template);
}else{
if ($hide_leech[0] != 1){
$full_storys = get_short_news ($full,$start_template);
}else{
$full_storys = get_full_news ($full,$start_template);
}
}
if ($full_storys == '')break;
if ($j == '1')$full_story1 = $full_storys;
if ($full_storys != $full_story1 or $j == '1')
	{
$full_story .= $full_storys;
	}else{break;}
}else{break;}

}
if ($full_story == '')break;
}
$news_link = $news_lik;
		if($dop_sort[9] != 0) {
			$full_story = trim(preg_replace('/[\r\n\t]+/',' ', $full_story));
			$short_story = trim(preg_replace('/[\r\n\t]+/',' ', $short_story));
		}
if(trim($sart_cat[2]) != '') $zhv_code = get_full_news ($full_story,$sart_cat[2]);
if (trim ($zhv_code) != '')
{
$zhv_code = rss_strip($zhv_code);
$zhv_code = addcslashes(stripslashes($zhv_code),'#');
$zhv_code = parse_Thumb ($zhv_code);
$zhv_code = parse_rss ($zhv_code);
$zhv_code = parse_host ($zhv_code,$link['host']);
$zhv_code = $parse->decodeBBCodes ($zhv_code,false);
$zhv_code = rss_strip ($zhv_code);
$zhv_code = strip_tags ($zhv_code,'<object><embed><param>'.$dop_sort[5]);
if($dop_sort[13] == 1) {$zhv_code = translate_google ($zhv_code,$dop_sort[14] ,$dop_sort[15] );}
if($dop_sort[13] == 1 and $dop_sort[18] != '') {$zhv_code = rss_strip (translate_google ($zhv_code,$dop_sort[15] ,$dop_sort[18] ));}
$zhv_code = preg_replace('#&quot;#','"',$zhv_code);
$zhv_code = strip_br($zhv_code);
}
$xfields_array = array();
if (trim($channel_info['xfields_template']) != '')
		{
$xfields_array = get_xfields (rss_strip($full_story), $short_story, $channel_info['xfields_template']);
$full_story = $xfields_array['content_story'];
$short_story = $xfields_array['content0_story'];
		}

if ($charik != strtolower($config['charset']) and trim ($full_story) != '') {$full_story = convert($charik,strtolower($config['charset']),$full_story);}
if (trim($channel_info['sart_link'])==''and $rss == 0)$news_fulink= get_flink ($full,$link['host'],$tu_link);
if (trim($news_fulink) !='')$news_link = $news_fulink;

if (trim($news_tit) == ''and trim ($full) != '')
{
$news_tit = get_title($full);
if ($charik != strtolower($config['charset']) and trim($news_tit) != '') {$news_tit = convert($charik,strtolower($config['charset']),$news_tit);}
}
if (trim($news_tit) == ''and trim ($full_story) != ''){
$news_tit = get_tit($short_story.$full_story);
if ($charik != strtolower($config['charset']) and trim($news_tit) != '') {$news_tit = convert($charik,strtolower($config['charset']),$news_tit);}
}
$news_title = rss_strip($news_tit);
if (trim($end_title[2]) != '' and trim($news_title) != '') $news_title = relace_news ($news_title,$end_title[2],$end_title[3]);
if($dop_sort[13] == 1 and $news_title != '') {$news_title = rss_strip (translate_google ($news_title, $dop_sort[14] , $dop_sort[15] ));}
if($dop_sort[13] == 1 and $dop_sort[18] != '') {$news_title = rss_strip (translate_google ($news_title, $dop_sort[15] , $dop_sort[18] ));}
srand((float)microtime() * 1000000);
$end_title0 = explode('|',$end_title[0]);
$end_title1 = explode('|',$end_title[1]);
if (trim($news_title) != '')$news_title =$end_title0[array_rand($end_title0)].' '.$news_title.' '.$end_title1[array_rand($end_title1)];
$alt_name = totranslit( stripslashes( trim($news_title) ),true,false );
if ($dnast[12] == 1) $channel_info['symbol'] = substr(strtolower ($alt_name), 0 , $dnast[13]);
if (trim($news_title) == '') continue;
if ($dop_sort[12] == 0) {$where = " LIMIT 1";}
elseif ($dop_sort[12] == 1 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%'";}
elseif ($dop_sort[12] == 2) {$where = " WHERE title = '".$db->safesql($news_title)."' OR alt_name = '".$db->safesql($alt_name)."'";}
elseif ($dop_sort[12] == 3 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%' OR title = '".$db->safesql($news_title)."' OR alt_name = '".$db->safesql($alt_name)."'";}
else {$where = " WHERE title = '".$db->safesql($news_title)."' OR alt_name = '".$db->safesql($alt_name)."'";}
$sql_result = $db->query("SELECT * FROM ".PREFIX ."_post".$where);
if ($db->num_rows ($sql_result) == 0 or $hide_leech[3] == 1 or $dop_sort[12] == 0)
{

if (trim ($full_story) != '')
{
if (trim($channel_info['start']) != '')$full_story = relace_news ($full_story, $channel_info['start'], $channel_info['finish']);
$full_story = rss_strip($full_story);
$full_story = addcslashes(stripslashes($full_story), "#");
$full_story = parse_Thumb ($full_story);
$full_story = parse_rss ($full_story);
$full_story = $parse->decodeBBCodes ($full_story,false);
$full_story = rss_strip ($full_story);
$full_story = strip_tags ($full_story,'<object><embed><param><br /><br><BR><BR />'.$dop_sort[5]);
$full_story = parse_host ($full_story,$link['host']);
if($dop_sort[13] == 1 and $full_story != ''){$full_story = rss_strip (translate_google ($full_story, $dop_sort[14] , $dop_sort[15] ));}
if($dop_sort[13] == 1 and $dop_sort[18] != '' and $full_story != '') {$full_story = rss_strip (translate_google ($full_story, $dop_sort[15] , $dop_sort[18] ));}
$full_story = preg_replace("#&quot;#", '"', $full_story);
}
else
{
$full_story = '';
}

if ($dop_sort[0] == 0){
$short_story = parse_Thumb ($short_story);
if (trim($channel_info['start']) != '')$short_story = relace_news ($short_story,$channel_info['start'],$channel_info['finish']);
$short_story = parse_rss ($short_story);
$short_story = $parse->decodeBBCodes ($short_story,false);
$short_story = rss_strip ($short_story);
$short_story = strip_tags ($short_story,'<object><embed><param><br /><br><BR><BR />'.$dop_sort[5]);
$short_story = parse_host ($short_story,$link['host']);
if($dop_sort[13] == 1 and $short_story != ''){$short_story = rss_strip (translate_google ($short_story, $dop_sort[14] , $dop_sort[15] ));}
if($dop_sort[13] == 1 and $dop_sort[18] != '' and $short_story != '') {$short_story = rss_strip (translate_google ($short_story, $dop_sort[15] , $dop_sort[18] ));}
$short_story  = preg_replace("#&quot;#", '"', $short_story );
}else{$short_story = '';}
$stop = true;
}
//echo "<textarea style=\"width:98%; height:200px\" >{$short_story}</textarea>";
//echo "<textarea style=\"width:98%; height:200px\" >{$full_story}</textarea>";
if ($stop){

if ($dop_sort[2] == 1) $full_story = $short_story.'<br /><br />'.$full_story;
if ($dop_sort[0] != 0)$short_story = '';

$full_story = strip_br($full_story);
		if($dop_sort[11] != 0) {
			$news_title = str_replace( '  ', ' ', $news_title );
		}

$full_story = parse_host ($full_story,$link['host']);
$short_story = parse_host ($short_story,$link['host']);

$short_story = create_URL ($short_story,$link['host']);
$full_story = create_URL ($full_story,$link['host']);

if ($dnast[7] == 1 or $dnast[7] == 3) $short_story = url_img_($short_story );
if ($dnast[7] == 2 or $dnast[7] == 3) $full_story = url_img_($full_story );

if ($dop_nast[1] == 1){
if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$short_story );
if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$full_story );
}
if($dop_nast[1] == 2){
if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie', "downs_host('\\1', '\\2', ".$dop_nast[1].')', $short_story );
if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie', "downs_host('\\1', '\\2', ".$dop_nast[1].')', $full_story );
}
if($dop_nast[1] == 3){
if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie', "downs_host('\\1', '\\2', ".$dop_nast[1].')', $short_story );
if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie', "downs_host('\\1', '\\2', ".$dop_nast[1].')', $full_story );
}

/*
$story = preg_replace ('#\[url\](\S+?)\[\/url\]#is', '[quote][url]\\1[/url][/quote]', $story);
$story = preg_replace ('#\[url=(\S+?)\](.+?)\[\/url\]#is', '[quote][url=\\1]\\2[/url][/quote]', $story);
*/
if ($hide_leech[1] == '1'){
$short_story = replace_hide ($short_story);
$full_story = replace_hide ($full_story);
}
if ($hide_leech[2] == '1'){
$short_story = replace_leech ($short_story);
$full_story = replace_leech ($full_story);
}

$short_story = replace_quote ($short_story);
$full_story = replace_quote ($full_story);

	if ($short_story == '' and intval($dop_nast[22]) != 0){
$full_sto = strip_tags(stripslashes($parse->BB_Parse($parse->process($full_story) , false)),'<b><i><br><center><u><p>'.$dop_sort[5]);
$short_story = substr( $full_sto , 0, $dop_nast[22] + strpos(substr( $full_sto ,$dop_nast[22]), ' ')).'...';
}

if ($dop_sort[1] == 1 or $dop_sort[0] == 1) {
if (($short_story == ''and $full_story != '') or (!preg_match ('#\\[img\\]#i',$short_story)and !preg_match ('#\\[thumb\\]#i',$short_story)))$short_story = get_im ($full_story).$short_story;
}
if ($dop_sort[17] != 0)$full_story = '';
if ($dop_nast[17] == 1 or $dop_nast[17] == 3){
$indeg = get_im ($full_story);
$full_story = str_replace ($indeg,'',$full_story);
$full_story = $indeg.$full_story;
}
if ($dop_nast[17] == 2 or $dop_nast[17] == 3){
$indeg = get_im ($short_story);
$short_story = str_replace ($indeg,'',$short_story);
$short_story = $indeg.$short_story;
}
if(intval($dop_nast[23]) != 0){
	$full_story = str_replace("[thumb", "[img", $full_story);
	$full_story = str_replace("thumb]","img]",$full_story);
preg_match_all ('#\[img.*?\](.+?)\[\/img\]#i',$full_story, $img_a);
$is = 1;
$num_i=ceil(count($img_a[0])/$dop_nast[23]);
$is_k = 1;
foreach ($img_a[0] as $value)
	{
if ($is %$dop_nast[23] == 0){
	$full_story = str_replace($value, $value."\n{PAGEBREAK}\n",$full_story);
	$is_k++;
}
$is++;
if ($num_i == $is_k)break;
	}
}
$short_story = replace_align ($short_story,$dnast[0]);
$full_story = replace_align ($full_story,$dnast[1]);
if ($dop_nast[10] == 1){
$short_story = trim(preg_replace('/[\r\n\t ]{3,}/','
', $short_story));
$full_story = trim(preg_replace('/[\r\n\t ]{3,}/','
', $full_story));
}
//$short_story = host_video ($short_story, $link['host'],$dop_nast[2]);
//$full_story = host_video ($full_story, $link['host'],$dop_nast[2]);

$news_title_out = $parse->decodeBBCodes($news_title);
$short_story = htmlspecialchars ($short_story);
$full_story = htmlspecialchars ($full_story);

if (!((!($channel_info['date_format'] == 0) AND !($channel_info['date_format'] == 1))))
{
$added_time_stamp = time () + ($config['date_adjust'] * 60);
$dat = $lang_grabber['date_post'].$lang_grabber['date_flowing'];
if ($channel_info['date_format'] == 1)
{
$interval = mt_rand ($config_rss['interval_start']*60,$config_rss['interval_finish']*60);
$added_time_stamp += $interval;
$dat = $lang_grabber['date_post'].$lang_grabber['date_casual'];
}
}
else
{
 if ($rss == 1 or trim($sart_cat[1]) != ''){
if ($channel_info['date_format'] == 2)
{
	if ($rss == 0 and $data_tmp != '')$added_time_stamp = strtotime ($data_tmp) -3600;
else $added_time_stamp = strtotime ($item['pubDate']) -3600;
$dat = $lang_grabber['date_post'].$lang_grabber['date_channel'];
}
}else{$added_time_stamp = time () + ($config['date_adjust'] * 60);
$dat = $lang_grabber['date_post'].$lang_grabber['date_flowing'];
}
}
$str_date = date( 'Y-m-d H:i:s',$added_time_stamp);
$keywordsd = explode ('===', $channel_info['keywords']);
$keywords = stripslashes ($keywordsd[0]);
if (trim ($keywords) != '')
{
$allow_news = FALSE;
$keywords = explode ('|||',$keywords);
foreach ($keywords as $word)
{
$word = addcslashes(stripslashes($word), '"[]!-.#?*%\\()|/');
if (!((!preg_match ('#'.$word.'#i',$short_story) AND !preg_match ('#'.$word.'#i',$full_story) AND !preg_match ('#'.$word.'#i',$news_title_out))))
{
$allow_news = TRUE;
}
}
}
else
{
$allow_news = TRUE;
}
$stkeywordsd = explode ('===', $channel_info['stkeywords']);
$stkeywords = stripslashes ($stkeywordsd[0]);
if (trim ($stkeywords) != '')
{
$stkeywords = explode ('|||',$stkeywords);
foreach ($stkeywords as $word)
{
$word = addcslashes(stripslashes($word), '"[]!-.#?*%\\()|/');
if (!(!preg_match ('#'.$word.'#i',$short_story) AND !preg_match ('#'.$word.'#i',$full_story)))
{
$allow_news = FALSE;
}
}
}

if (trim ($channel_info['delate']) != '')
{
//if(trim($sart_cat[2]) != '') $zhv_code = get_full_news ($short_story.$full_story,$sart_cat[2]);
$channel_info_inser= str_replace('{zagolovok}', $news_title,$channel_info['inser']);
$channel_info_inser= str_replace('{frag}', $zhv_code,$channel_info_inser);
$short_story = relace_news ($short_story, $channel_info['delate'], $channel_info_inser);
$full_story = relace_news ($full_story, $channel_info['delate'], $channel_info_inser);
	}

if (trim($keywordsd[1]) != '')$short_story = $keywordsd[1].' '.$short_story;
if (trim($keywordsd[2]) != '')$full_story = $keywordsd[2].' '.$full_story;
if (trim($stkeywordsd[1]) != '')$short_story .=' '.$stkeywordsd[1];
if (trim($stkeywordsd[2]) != '')$full_story .=' '.$stkeywordsd[2];


if ($db->num_rows ($sql_result) != 0 and ($dop_sort[12] == 1 or $dnast[17] == 1)){
while ($ren = $db->get_row($sql_result)){
	if ($dnast[17] != 1){
$fuls_story = $db->super_query ("SELECT * FROM ".PREFIX ."_post WHERE id = '".$ren['id']."' ");
$kolsa = array();$kolsb = array();
preg_match_all("#<a.*?href[=]?[='\"](\S+?)['\" >].*?>(.*?)<\/a>#is",$fuls_story['full_story'],$kolsa);
preg_match_all('#\[url(\S+?)\].+?\[/url\]#i',$full_story,$kolsb);
if (count($kolsa[0]) >= count($kolsb[0])){$allow_news = false;
}else{
$news_id = $ren['id'];
$author = $ren['autor'];
}
}else{
if($data_tmp < $str_date and $channel_info['date_format'] == 2 and $data_tmp != ''){
	$allow_news = true;
$news_id = $ren['id'];
$author = $ren['autor'];
}else{$allow_news = false;
unset($news_id);
unset($author);
}
}
break;
}
}
if ($allow_news)
{
$Autor = explode('=',$channel_info['Autors']);
if (trim($Autor[0]) != '')
{
$input=array ();
$autor = explode ("|||",stripslashes($Autor[0]));
foreach ($autor as $value)
{
$input[] =trim($value);
}
}
else
{if (trim($Autor[1]) == '') $Autor[1] = $config_rss['reg_group'];
if (trim($Autor[1]) == '') $Autor[1] = 1;
$channel_infos = $db->query ("SELECT * FROM ".PREFIX ."_users WHERE user_group IN ({$Autor[1]})");
while ($channel_infon = $db->get_row($channel_infos)) {
$input[] = $channel_infon['name'];
}
}
if ($input != '')$author= $input[array_rand ($input)];
$metatags_short = create_metategs ($short_story);
$metatags_full = create_metategs ($full_story);
if ($dop_sort[7] != 4){
if ($dop_sort[7] == 1){
$key_wordss = trim(trim($metatags_short['keywords']));
}elseif ($dop_sort[7] == 2){
$key_wordss = trim(trim($metatags_full['keywords']));
}elseif ($dop_sort[7] == 3){
if ($metatags_full['description'] >= $metatags_short['description']){
$key_wordss = trim(trim($metatags_full['keywords']));
}else{
$key_wordss = trim(trim($metatags_short['keywords']));
}
}else{
$key_wordss = trim(trim($metatags_short['keywords']).', '.$metatags_full['keywords']);
}
}

$key_words = trim($channel_info['key_words'].', '.$key_wordss, ',');
$key_words = substr( $key_words , 0, 180 + strpos(substr( $key_words ,180), ','));
if ($dop_sort[10] != 4){
if ($dop_sort[10] == 1){
$descrs = trim(trim($metatags_short['description']));
}elseif ($dop_sort[10] == 2){
$descrs = trim(trim($metatags_full['description']));
}elseif ($dop_sort[10] == 3){
if ($metatags_full['description'] >= $metatags_short['description']){
$descrs = trim(trim($metatags_full['description']));
}else{
$descrs = trim(trim($metatags_short['description']));
}
}else{
$descrs = trim(trim($metatags_short['description'].', '.$metatags_full['description']));
}
}

$descr = trim($channel_info['meta_descr'].', '.$descrs , ',');
$descr = substr( $descr , 0, 180 + strpos(substr( $descr ,180), ' '));
$category = array();
$kateg = array();
$tags_tmps = array();
$tags_tm = '';
$tegs= '';
$tags_tmps = replace_tags ($tags_tmp.','.$news_tit,$dnast[20]);
if ($dnast[8] == 1)$tags_tm = $tags_tmps[0];
$tegs = trim($channel_info['ftags'].','.$tags_tm ,',');
if($dop_sort[13] == 1 and $tegs != '') {$tegs= rss_strip (translate_google ($tegs, $dop_sort[14] , $dop_sort[15] ));}
if($dop_sort[13] == 1 and $tegs != '') {$tegs= rss_strip (translate_google ($tegs, $dop_sort[14] , $dop_sort[15] ));}
if($dop_sort[13] == 1 and $tegs != '' and $dop_sort[18] != '') {$tegs= rss_strip (translate_google ($tegs, $dop_sort[15] , $dop_sort[18] ));}
if (trim($channel_info['kategory']) != ''){
foreach (explode ('|||', $channel_info['kategory']) as $value){
$kr = explode ('==', $value);
foreach (explode (',', $kr[0]) as $wnd){
$url_kats = addcslashes(stripslashes(reset_urlk($wnd)), '"[]!-.#?*%\\()|/');
if($dop_sort[16] == 1)$for = $tags_tmp;
else $for = $news_link;
if (preg_match("#".$url_kats."#i", $for)){
	foreach (explode (',', $kr[1]) as $key){
if (trim($key) != '')$sql_cat= $db->super_query ('SELECT * FROM '.PREFIX ."_category WHERE name like '".$db->safesql(trim(strtolower($key)))."%' or alt_name like '".$db->safesql(trim(strtolower($key)))."%' or name like '".$db->safesql(trim($key))."%' or alt_name like '".$db->safesql(trim($key))."%'");
if (trim($sql_cat) != "")
{
$kateg[]=$sql_cat['id'];
}
				}
			}
		}
	}
}
if (count($kateg) == 0){
if ($channel_info['thumb_img'] == 1){
//$tagst = replace_tags($short_story);
$gory = explode (",",$tags_tmps[1].','.$tagst[1]);
foreach ($gory as $value) {
if (trim($value) != '')$sql_cat= $db->super_query ("SELECT * FROM ".PREFIX ."_category WHERE name like '".$db->safesql(trim($value))."%' or alt_name like '".$db->safesql(trim($value))."%'");
if (trim($sql_cat) != "")
{
$category[]=$sql_cat['id'];
}
}
}
}else{$category =$kateg;}
if (count($category) != '0'){
$category = implode(',',array_unique($category));
}else{
$category = reset (explode ('=',$channel_info['category']));
}
/*
if ($dop_sort[3] == 1 and @file_exists (ENGINE_DIR .'/inc/plugins/sinonims.php')){
$quotes = array(  "\t",'\n','\r', "\n","\r", '\\',",",".","/","¬","#",";",":","@","~","[","]","{","}","=","-","+",")","(","*","&","^","%","$","<",">","?","!", '"', ',,','/','//','&raquo;','|',':',' ',',,','(',')','-' );

	$title_ar = str_replace ($quotes, ',', $news_title_out);
	$title_ar = str_replace (',,', ',', $title_ar);
	$title_ar =	trim($title_ar,',');
$title_ar = explode(',',$title_ar);
foreach ($title_ar as $value)
	{
$short_story =str_replace($value, '[nosin]'.$value.'[/nosin]',$short_story);
$full_story = str_replace($value, '[nosin]'.$value.'[/nosin]',$full_story);
}
}*/
$xreplace = array();
$ds = explode ('|||', $row['xfields_template']);
foreach ($ds as $xvalue)
	{
$xf=array();
$xf = explode ('==', $xvalue);
$xreplace[$xf[0]] = $xf;
}

if (count($xfields_array) !=0){
foreach ($xfields_array as $key => $value){
if ($key != 'content_story'){
if(array_key_exists($key, $xreplace)) {
$xreplace_key = str_replace('{zagolovok}', $news_title, $xreplace[$key][6]);
$xreplace_key= str_replace('{frag}', $zhv_code,$xreplace_key);
$value = relace_news ($value, $xreplace[$key][5], $xreplace_key);
}
$value = parse_Thumb ($value);
$value = parse_rss ($value);
$value = $parse->decodeBBCodes ($value,false);
$value = rss_strip ($value);
$value = strip_tags ($value,'<object><embed><param>'.$dop_sort[5]);
if($dop_sort[13] == 1) {$value = rss_strip (translate_google ($value, $dop_sort[14] , $dop_sort[15] ));}
if($dop_sort[13] == 1 and $dop_sort[18] != '') {$value = rss_strip (translate_google ($value, $dop_sort[15] , $dop_sort[18] ));}
$value = parse_host ($value,$link['host']);
if ($dop_nast[1] == 1){
$value=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$value );
}
if($dop_nast[1] == 2){
$value = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie', "downs_host('\\1', '\\2', ".$dop_nast[1].")", $value );
}
if($dop_nast[1] == 3){
$value = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie', "downs_host('\\1', '\\2', ".$dop_nast[1].")", $value );
}
$fieldvalue[$key] = $value;
}
}
}
	if( $config['safe_xfield'] ) {
		$parse->ParseFilter();
		$parse->safe_mode = true;
	}
if( ! empty( $fieldvalue )) {
foreach ( $fieldvalue as $xfielddataname =>$xfielddatavalue ) {
if( $xfielddatavalue == '') {
continue;
}
$xfields_im = false;
$xfte = array();
$xfte = explode ('|||', $channel_info['xfields_template']);
foreach($xfte as $value)
	{
$key = explode ('==', $value);
if ($key[0] == $xfielddataname and $key[4] == 1){
	$xfields_im = true;
	break;
}
}
$xfielddatavalue = stripslashes($xfielddatavalue);
if ($serv != '0'and $xfields_im == true)
{
$db->close;
$xdoe = array();
$full_images = array();
$di_control = new image_controller ();
$di_control->post = '';
$xfielddatavalue='[img]'.$xfielddatavalue.'[/img]';
$di_control->short_story = $xfielddatavalue;
$di_control->proxy = $dop_nast[2];
$di_control->dubl =$dop_nast[15];
if ($dates[0] != '' and strlen($dates[0]) != 10){
	$di_control->post = '/'.$dates[0] ;
	}
if (intval($key[7]) != 0)$di_control->post .= '/th_post' ;
if ($dates[1] == 1 )$di_control->dim_week = $alt_name;
$di_control->dim_date =$dates[2];
$di_control->dim_sait =$dates[3];
$di_control->dim_cat =$dates[4];
$di_control->wat_h =$dnast[15];
if (intval($key[7]) != 0)$di_control->max_up_side = $key[7];
else $di_control->max_up_side = $config['max_up_side'];
if ( count($_POST['category'.$di.$channel_id.'_'])) $di_control_cat = $db->super_query ('SELECT alt_name FROM '.PREFIX ."_category WHERE id ='".$_POST['category'.$di.$channel_id.'_'][0]."'");
$di_control->cat = $di_control_cat ['alt_name'];
if ($channel_info['allow_watermark'] == 1)
{
$di_control->allow_watermark = true;
if ($dop_nast[0] == 1)
{
$di_control->watermark_image_light = ROOT_DIR .$config_rss ['watermark_image_light'];
$di_control->watermark_image_dark = ROOT_DIR .$config_rss['watermark_image_dark'];
}
}
$di_control->x = $dop_nast[3];
$di_control->y = $dop_nast[4];
$di_control->margin = $dop_nast[12];
$di_control->rewrite = 0;
if ($dop_nast[11] == 1)$di_control->shs = true;
$pro = $di_control->process($serv);
if (count($pro) != 0) {
$xdoe[] = '<b><i>'.$tit.'</i> &#x25ba; '.$title.'</b><br />'.implode('<br />',$pro);
}
if (count ($di_control->upload_images) != 0)
{
$folder_prefix = trim($di_control->post.$di_control->pap_data,'/');
$dim = '|||'.$folder_prefix.'/';
$dimage = implode ($dim,$di_control->upload_images);
$dimages = $db->safesql ($folder_prefix.'/'.$dimage);
}
$xfielddatavalue = $di_control->short_story;
if (count($di_control->upload_image ) != 0 and intval($key[7]) == 0){
	$short_story=strtr ($short_story,$di_control->upload_image);
	$full_story=strtr ($full_story,$di_control->upload_image);
}
$xfielddatavalue=str_replace( '[img]','',$xfielddatavalue );
$xfielddatavalue=str_replace( '[/img]','',$xfielddatavalue );
}



$config_code_bb = explode (',',$config_rss['code_bb'] );
if (in_array ($xfielddataname , $config_code_bb) and @file_exists (ENGINE_DIR .'/inc/plugins/sinonims.php' ))
	{
include_once(ENGINE_DIR .'/inc/plugins/sinonims.php');
$xfielddatavalue = sinonims ($xfielddatavalue);
	}

$xfielddatavalue = $db->safesql( $parse->BB_Parse( $parse->process( $xfielddatavalue ),false ) );
$xfielddataname = $db->safesql( $xfielddataname );
$xfielddataname = str_replace( '|','&#124;',$xfielddataname );
$xfielddataname = str_replace( "\r\n",'__NEWL__',$xfielddataname );
$xfielddatavalue = str_replace( '|','&#124;',$xfielddatavalue );
$xfielddatavalue = str_replace( "\r\n",'__NEWL__',$xfielddatavalue );
$filecontents[] = "$xfielddataname|$xfielddatavalue";
}
if (count($filecontents) != 0) $xfields = implode( '||',$filecontents );
}
else
{
$xfields = '';
}
if ($channel_info['allow_more'] == 1 or $dop_sort[12] == 1  or $dop_sort[12] == 3)
{
$xfields = '||source_name|'.$channel_info['title'] .'||source_link|'.$news_link;
$xfields = trim($xfields, '||');
$xfields = $db->safesql($xfields);
}


if ($dop_sort[3] == 1 and @file_exists (ENGINE_DIR ."/inc/plugins/sinonims.php") )
{
include_once(ENGINE_DIR ."/inc/plugins/sinonims.php");
if ($dop_sort[19] == 0 or $dop_sort[19] == 1 )$short_story = sinonims ($short_story);
if ($dop_sort[19] == 0 or $dop_sort[19] == 2 )$full_story = sinonims ($full_story);
}
//echo "<textarea style=\"width:98%; height:200px\" >{$news_title}</textarea>";
$stop = false;
if ($category =='0' and $dop_sort[6] == 1) $stop = true;
if (trim($short_story) != '' and trim($news_title) != '' and !$stop and  (trim($full_story) != '' or $dop_sort[17] == 1 or intval($dop_sort[20]) == 0)){
if ($channel_info['load_img'] != '0' or $dop_nast[11] == 1)
{
$db->close;
$di_control = new image_controller ();
$di_control->post = '';
$di_control->short_story = $short_story;
$di_control->full_story = $full_story;
$di_control->proxy = $dop_nast[2];
$di_control->dubl =$dop_nast[15];
if ($dates[0] != '' and strlen($dates[0]) != 10){$di_control->post = '/'.$dates[0] ;}
if ($dates[1] == 1 )$di_control->dim_week = $alt_name;
$di_control->dim_date =$dates[2];
$di_control->dim_sait =$dates[3];
$di_control->dim_cat =$dates[4];
$di_control->wat_h =$dnast[15];
if ($channel_info['allow_watermark'] == 1)
{
$di_control->allow_watermark = true;
}
if ($dop_nast[0] == 1)
{
if (trim($config_rss ['watermark_image_light'])!= '')$di_control->watermark_image_light = ROOT_DIR.$config_rss['watermark_image_light'];
if (trim($config_rss['watermark_image_dark']) != '')$di_control->watermark_image_dark = ROOT_DIR .$config_rss['watermark_image_dark'];
}
$di_control->x = $dop_nast[3];
$di_control->y = $dop_nast[4];
$di_control->margin = $dop_nast[12];
if ($db->num_rows ($sql_Title) >0 and $rewrite == 1){
$db->query( "SELECT images  FROM ".PREFIX ."_images where news_id = '".$news_id."'");
while ( $channel_info = $db->get_row() ) {
$di_control->listimages = explode( "|||",$channel_info['images'] );
}
$di_control->rewrite = $rewrite;
}
if ($dop_nast[11] == 1)$di_control->shs = true;

	$pro = $di_control->process($channel_info['load_img']);
if (count($pro) != 0) {
$xdoe = implode('<br />', $pro);
}
$short_story	= $di_control->short_story;
$full_story = $di_control->full_story;
if (count ($di_control->upload_images) != 0)
{
$folder_prefix = trim($di_control->post.$di_control->pap_data, '/');
$dim = '|||'.$folder_prefix.'/';
$dimage = implode ($dim,$di_control->upload_images);
$dimages .= '|||'.$db->safesql ($folder_prefix.'/'.$dimage);
}
}
$dimages = trim($dimages , '|||');
$short_story = add_short ($short_story);
$full_story = add_full ($full_story);


$_POST['title'] = html_entity_decode($news_title);
if ($dop_nast[9] == 1)$meta_title = $db->safesql(trim($channel_info['metatitle'].' '.$news_title));else $meta_title = $db->safesql(str_replace('{zagolovok}', $news_title,$channel_info['metatitle']));
$title = stripslashes($title);
$short_story = stripslashes($short_story);
$full_story = stripslashes($full_story);
$title = $db->safesql($parse->process($news_title));
$short_story = rss_strip ($short_story);
$full_story = rss_strip ($full_story);


if ($config_rss['create_images'] == 1 or $config_rss['create_images'] == 3){
	$short_story = $db->safesql($parse->BB_Parse(create_images($parse->process($short_story) , $title) , false));
}else{
$short_story = $db->safesql($parse->BB_Parse($parse->process($short_story) ,false));
}

if ($config_rss['create_images'] == 2 or $config_rss['create_images'] == 3){
$full_story = $db->safesql($parse->BB_Parse(create_images($parse->process($full_story) , $title) , false));
}else{
$full_story = $db->safesql($parse->BB_Parse($parse->process($full_story) , false));
}
$news_read = rand(intval($config_rss['rate_start']), intval($config_rss['rate_finish']));
if($allow_rate == 1){$vote_num = rand(0, $news_read);
$rating = rand($vote_num*2, $vote_num*4);
}
$short_story = str_replace ('&#111;', 'o', $short_story );
$full_story = str_replace ('&#111;', 'o', $full_story );
$safet = $parse->decodeBBCodes($_POST['title']);
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
$date_time = strtotime ($str_date);
if ($db->num_rows ($sql_result) >0 and $hide_leech[3] == 1){
if ($config['version_id'] >='7.2')$tes = ", tags='".$db->safesql($tegs)."'";
if ($config['version_id'] >'8.0')$fgs = ", metatitle='$meta_title', symbol='{$channel_info['symbol']}'";
$result = $db->query( "UPDATE ".PREFIX ."_post set date='$str_date', title='$title', short_story='$short_story', full_story='$full_story', descr='$descr', keywords='$keywords', category='$category', alt_name='$alt_name', allow_comm='$allow_comm', approve='$approve', allow_main='$allow_main',  xfields='$xfields',  descr='$descr',  keywords='$key_words' $tes $fgs WHERE id='$news_id'");
$db->query ("UPDATE ".PREFIX ."_users SET lastdate = '$date_time' WHERE name ='$author'");
$db->query("UPDATE ".PREFIX ."_images SET images='$dimages', date='$date_time' WHERE news_id ='$news_id'");
}else{
if ($config['version_id'] >='7.2'){$te =", '".$db->safesql($tegs)."'";$tes = ", tags";}
if ($config['version_id'] >'8.0'){$fg = ", '$meta_title', '{$channel_info['symbol']}'";$fgs = ", metatitle, symbol";}
$db->query ( "INSERT INTO ".PREFIX ."_post (autor, category, date, title, alt_name, short_story, full_story, xfields, allow_main, approve, allow_comm, allow_rate, allow_br, rating, vote_num, news_read, fixed, descr, keywords $tes $fgs) VALUES ('$author', '$category', '$str_date', '$title', '$alt_name', '$short_story', '$full_story', '$xfields', '$allow_main', '$approve', '$allow_comm', '$allow_rate', '1', '$rating', '$vote_num', '$news_read', '0', '$descr', '$key_words' $te $fg)");
$news_id = $db->insert_id();
if( $approve == '1'and $dop_sort[4] == 1 and @file_exists(ENGINE_DIR .'/inc/plugins/ping/pingsite.txt')) {
include ( ENGINE_DIR .'/inc/plugins/ping/grabberping.php');
}
$db->query ("UPDATE ".PREFIX ."_users SET news_num = news_num + 1, lastdate = '$date_time' WHERE name ='$author'");
}
if( $approve == '1' and @file_exists(ENGINE_DIR . '/inc/crosspost.addnews.php')) {
$action = "doaddnews";
$row = $news_id;
	include ENGINE_DIR . '/inc/crosspost.addnews.php';
}
$i++;
if ($tegs != ""and $db->num_rows ($sql_result) == 0 and $config['version_id'] >='7.2') {
$tags = array();
$tegs = explode (",",$tegs);
foreach ($tegs as $value) {
if (trim($value) != '') $tags[] = "('$news_id', '".$db->safesql(trim($value))."')";
}
$tags = implode(", ",$tags);
$db->query("INSERT INTO ".PREFIX."_tags (news_id, tag) VALUES ".$tags);
}
if (trim ($dimages) != '' and ($db->num_rows ($sql_result) == 0 or $dop_sort[12] == 0))
{
$db->query("INSERT INTO ".PREFIX ."_images (images, news_id, author, date) VALUES	('$dimages', '$news_id', '$author', '$date_time')");
}
if (intval($dnast[10]) != 0){
$datede = $date_time +$dnast[10] * 86400;
$db->query( "INSERT INTO ".PREFIX ."_post_log (news_id, expires, action) VALUES('$news_id', '$datede', '{$dnast[11]}')");
}
}
}
}
}
if (trim ($channel_info['title']) != '')
{
$tit = stripslashes (strip_tags ($channel_info['title']));
if (50 <strlen ($tit))
{
$tit = substr ($tit,0,50) .'...';
}
}
else
{
$tit = 'Без названия...';
}
if ($title!= '')$news_tit = $title;

if ($z <= 1)$z = 1;
if ($e <= 0)$e = 0;
if ($p <= 0)$p = 0;
if ($_SERVER['HTTP_USER_AGENT'] != ''){
echo(($news_id)?$z.'. <b style="color:blue;">'.$tit.'</b> &#x25ba;<font color=red><b>'.$title.'</b></font><br>':$z.'. <b style="color:green;">'.$tit.'</b> &#x25ba;<b>'.$news_tit.'</b>'.$ping_msg.'<br>').(sizeof($xdoe)?'<br />* * *<br /><b style="color:#ff0000;">'.$lang_grabber['post_msg_pics'].'</b><br /><br />'.$xdoe:'');
}
($news_id)? ++$e : ++$p;
unset ($news_id);
++$z;
}
}else{
echo "<B><font color=#993300>№".$channel_info['xpos']." ".$channel_info['title']."</font></B><br />Канал не сграбблен проверьте настройки<br /><br />";
return false;
}
}
if ($e == 0 and $p == 0)return false;
echo "<B><font color=#993300>№".$channel_info['xpos']." ".$tit."</font></B><br />Добавленно ".$e." новостей<br />Пропущено ".$p." новостей<br /><br />";
if ($e == 0)return false;
if($config_rss['sitemap'] == 'yes' and @file_exists(ENGINE_DIR .'/inc/plugins/ping/sitemap.php')) {
include ( ENGINE_DIR .'/inc/plugins/ping/sitemap.php');
}
$db->free();
clear_cache();
return true;
};

?>