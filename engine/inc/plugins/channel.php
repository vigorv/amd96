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

if( !defined( 'DATALIFEENGINE') ) {
die( 'Hacking attempt!');
}



@ini_set ('memory_limit','128M');
@set_time_limit (0);
@ini_set ('max_execution_time',0);
@ini_set ('2048M');
@ini_set ('output_buffering','off');
@ob_end_clean ();
clearstatcache ();
ob_implicit_flush (TRUE);

require_once ENGINE_DIR .'/inc/plugins/core.php';
require_once ENGINE_DIR .'/classes/templates.class.php';
require_once ENGINE_DIR .'/classes/parse.class.php';
include_once ENGINE_DIR.'/classes/rss.class.php';
$parse = new ParseFilter (array (),array (),1,1);
$tpl = new dle_template ();
require_once ENGINE_DIR .'/inc/plugins/backup.php';
$tpl->dir = ENGINE_DIR .'/inc/plugins/templates/';
require_once ENGINE_DIR .'/inc/plugins/channel.php';
require_once ENGINE_DIR .'/inc/plugins/rss.classes.php';
require_once ENGINE_DIR .'/inc/plugins/rss.functions.php';
require_once ENGINE_DIR .'/inc/plugins/rss.parser.php';
@include(ENGINE_DIR.'/data/rss_config.php');
//include_once ENGINE_DIR . '/inc/plugins/xml.video.php';
@require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';



if ($action == 'updategrup_channel'){

if (count ($_POST['id']) != 0)
{

if ($_POST['category'] != ''){
$category_post = $db->safesql( implode( ',',$_POST['category']));
}else{$category_post = '0';}
$category = $category_post.'='.intval ($_POST['rss_priv']);
$allow_main = intval ($_POST['allow_main']);
$allow_mod = intval ($_POST['allow_mod']);
$allow_comm = intval ($_POST['allow_comm']);
$allow_auto = intval ($_POST['allow_auto']);
$allow_load = $db->safesql ($_POST['load_img']);
$thumb_images = intval ($_POST['thumb_img']);
$allow_rate = intval ($_POST['allow_rate']);
$allow_auto = intval ($_POST['auto']);
$allow_more = intval ($_POST['allow_more']);
$allow_water = intval ($_POST['allow_watermark']);
$date_format = intval ($_POST['news_date']);
$symbol = $db->safesql ($_POST['symbols']);
$ftags = $db->safesql ($_POST['tags']);
$metatitle = $db->safesql ($_POST['meta_title']);
$meta_descr = $db->safesql ($_POST['meta_descr']);
$key_words = $db->safesql ($_POST['key_words']);
$dnast = intval ($_POST['image_align']).'='.intval ($_POST['image_align_full']).'='.intval ($_POST['show_symbol']).'='.intval ($_POST['show_metatitle']).'='.intval ($_POST['show_metadescr']).'='.intval ($_POST['show_keywords']).'='.intval ($_POST['show_url']).'='.intval ($_POST['rss_parse']).'='.intval ($_POST['tags_auto']).'='.intval ($_POST['auto_metatitle']).'='.intval ($_POST['data_deap']).'='.intval ($_POST['deap']).'='.intval ($_POST['auto_symbol']).'='.intval ($_POST['auto_numer']).'='.intval ($_POST['show_date_expires']).'='.intval ($_POST['wat_host']).'='.intval ($_POST['cron_auto']).'='.intval ($_POST['rewrite_data']).'='.intval ($_POST['ret_xf']).'='.intval ($_POST['kol_cron']);
$short_story = intval ($_POST['clear_short']).'='.intval ($_POST['short_img']).'='.intval ($_POST['short_full']).'='.intval ($_POST['sinonim']).'='.intval ($_POST['pings']).'='.$db->safesql ($_POST['teg_fix']).'='.intval ($_POST['cat_nul']).'='.intval ($_POST['keyw_sel']).'='.intval ($_POST['log_pas']).'='.intval ($_POST['text_html']).'='.intval ($_POST['descr_sel']).'='.intval ($_POST['title_prob']).'='.intval ($_POST['no_prow']).'='.intval ($_POST['lang_on']).'='.$db->safesql ($_POST['lang_in']).'='.$db->safesql ($_POST['lang_out']).'='.intval ($_POST['cat_sp']).'='.intval ($_POST['clear_full']).'='.$db->safesql ($_POST['lang_outf']).'='.intval ($_POST['sinonim_sel']).'='.intval ($_POST['add_full']);
$ctp = intval ($_POST['so']).'='.intval ($_POST['po']);
$full_link = stripslashes ($_POST['full_link']);
$date = $db->safesql(trim($_POST['date'])).'='.intval ($_POST['dim_week']).'='.intval ($_POST['dim_date']).'='.intval ($_POST['dim_sait']).'='.intval ($_POST['dim_cat']);


$cookies = $db->safesql (str_replace ('
','|||',$_POST['cookies']));
$keywords = $db->safesql(str_replace ('
','|||',$_POST['keywords'])).'==='.$db->safesql($_POST['sfr_short']).'==='.$db->safesql($_POST['sfr_full']);
$stkeywords = $db->safesql (str_replace ('
','|||',$_POST['stkeywords'])).'==='.$db->safesql($_POST['efr_short']).'==='.$db->safesql($_POST['efr_full']);
if ($_POST['groups'] != '')$autor_grups = implode( ',',$_POST['groups']);
$Autors = $db->safesql (str_replace ('
','|||',$_POST['Autors'])).'='.$autor_grups;
$xdescr = $db->safesql ($_POST['rss_xdescr']);
$start_template = $db->safesql ($_POST['start_template']);
$finish_template = $db->safesql ($_POST['finish_template']);
$delate = $db->safesql (str_replace ('
','|||',$_POST['delate']));
$inser = $db->safesql (str_replace ('
','|||',$_POST['inser']));
$start = str_replace ('
','|||',$_POST['start']);
$finish = str_replace ('
','|||',$_POST['finish']);
$ful_start = $db->safesql ($_POST['ful_start']);
$ful_end = $db->safesql ($_POST['ful_end']);
$start_title = $db->safesql ($_POST['start_title']);
$stitles = $db->safesql (str_replace ('
','|||',$_POST['s_del']));
$ftitles = $db->safesql (str_replace ('
','|||',$_POST['end_del']));
$kategory = $db->safesql (str_replace ('
','|||',$_POST['kategory']));
if ($rss_result['charset'] != '') $_POST['charset'] = $rss_result['charset'];
$dop_nast = intval ($_POST['dop_watermark']).'='.intval ($_POST['text_url']).'='.intval ($_POST['proxy']).'='.intval ($_POST['x']).'='.intval ($_POST['y']).'='.intval ($_POST['show_autor']).'='.intval ($_POST['show_tegs']).'='.intval ($_POST['show_date']).'='.intval ($_POST['show_code']).'='.intval ($_POST['show_f']).'='.intval ($_POST['null']).'='.intval ($_POST['one_serv']).'='.intval ($_POST['margin']).'='.intval ($_POST['show_down']).'='.$_POST['charset'].'='.intval ($_POST['dubl_host']).'='.intval ($_POST['text_url_sel']).'='.intval ($_POST['parse_url_sel']).'='.intval ($_POST['full_url_and']).'='.intval ($_POST['grab_pause']).'='.intval ($_POST['step_page']).'='.intval ($_POST['add_pause']).'='.intval ($_POST['kol_short']);
$end_title = $db->safesql ($_POST['s_title']).'=='.$db->safesql ($_POST['end_title']).'=='.$stitles.'=='.$ftitles.'=='.$db->safesql ($_POST['link_start_del']).'=='.$db->safesql ($_POST['link_finish_del']);
$start_short = $db->safesql ($_POST['start_short']);
$end_short = intval ($_POST['end_short']).'='.intval ($_POST['hide']).'='.intval ($_POST['leech']).'='.intval ($_POST['rewrite_news']);
$sart_link = $db->safesql ($_POST['sart_link']);
$end_link = $db->safesql ($_POST['end_link']);
$sart_cat = $db->safesql ($_POST['sart_cat']).'|||'.$db->safesql ($_POST['shab_data']);
$end_cat = $db->safesql ($_POST['end_cat']);
$dop_full = $db->safesql ($_POST['dop_full']);


for ($x=0; $x++<$_POST['kol_xfields'];){
if ($_POST['xfields_template_'.$x] != '')$templ[] = $xfields_template = $db->safesql ($_POST['rss_xfields_'.$x]).'=='.$db->safesql ($_POST['xfields_template_'.$x]).'=='.intval ($_POST['ret_xf_'.$x]).'=='.intval ($_POST['sh_fl_'.$x]).'=='.intval ($_POST['sh_im_'.$x]);
}
if($templ)$xfields_template = implode ('|||', $templ);
$mgs = '';
$id = explode(',',$_POST['id']);
foreach ($id as $key)
	{
$rss_channel_info = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$key'");
$categoryes = explode ('=',$rss_channel_info['category']);
if($category_post != '0')$category = $category_post.'='.intval ($categoryes[1]);
else $category = $rss_channel_info['category'];
$url = get_urls($rss_channel_info['url']);
$url_et = get_urls($_POST['rss_url']);
if ($url['host'] == $url_et['host']){
$db->query( 'UPDATE '.PREFIX ."_rss SET category='$category', allow_main = '$allow_main', allow_comm = '$allow_comm', allow_auto = '$allow_auto', allow_more ='$allow_more', allow_rate ='$allow_rate', cookies ='$cookies', start_template ='$start_template', finish_template ='$finish_template', delate = '$delate', load_img ='$allow_load', allow_watermark ='$allow_water', date_format ='$date_format', keywords ='$keywords', Autors ='$Autors', thumb_img ='$thumb_images', allow_mod ='$allow_mod', stkeywords ='$stkeywords', ful_start='$ful_start', start_title='$start_title', start_short='$start_short', end_short='$end_short', sart_link='$sart_link', end_link='$end_link', sart_cat='$sart_cat', inser='$inser', start='$start', finish='$finish', end_title = '$end_title', end_link = '$end_link', short_story='$short_story', dop_nast='$dop_nast', full_link='$full_link', ctp='$ctp', date= '$date', dnast='$dnast', symbol='$symbol', ftags='$ftags', metatitle='$metatitle', meta_descr='$meta_descr', key_words='$key_words', kategory='$kategory', xfields_template='$xfields_template', dop_full='$dop_full' WHERE id ='$key'");

}else{
$db->query( 'UPDATE '.PREFIX ."_rss SET category='$category',  allow_main = '$allow_main', allow_comm = '$allow_comm', allow_auto = '$allow_auto', allow_more ='$allow_more', allow_rate ='$allow_rate', load_img ='$allow_load', allow_watermark ='$allow_water', date_format ='$date_format', Autors ='$Autors', thumb_img ='$thumb_images', allow_mod ='$allow_mod', dop_nast='$dop_nast', ctp='$ctp', date= '$date', dnast='$dnast', symbol='$symbol', ftags='$ftags', metatitle='$metatitle', meta_descr='$meta_descr', end_short='$end_short', key_words='$key_words' WHERE id ='$key'");
}

if (trim ($rss_channel_info['title']) != '')
{
$title = stripslashes (strip_tags ($rss_channel_info['title']));
if (50 <strlen ($title))
{
$title = substr ($title,0,50) .'...';
}
}
else
{
$title = $lang_grabber['no_title'];
}
$mgs .= $lang_grabber['channel'].' <font color="green">"'.$title.' | '.$rss_channel_info['url'].'"</font> <font color="red">'.$lang_grabber['edit_channel_ok'].'</font><br />';

	}

msg ($lang_grabber['info'],$lang_grabber['change_channel'], $mgs ,$PHP_SELF .'?mod=rss');
$db->close;
exit;
}else{msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['channel_msg_id'],$PHP_SELF .'?mod=rss');}
}


//////////////////////////////////////////////////////////////////////////





if ($action == 'editgrup_channel'){

if (count ($_POST['channel']) == 0)
{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
return 1;
}else{$id = implode(',',$_POST['channel']);}


if (isset ($id))
{
if (trim ($id) == '' and $id == 0)
{
msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['channel_msg_id'],$PHP_SELF .'?mod=rss');
return 1;
}

$rss_channel_info = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='".$_POST['channel'][0]."'");
$titles = '';
$urls ='';



$cookies = str_replace ('|||','
',stripslashes($rss_channel_info['cookies']));
$keywordsd = explode('===',$rss_channel_info['keywords']);
$keywords = str_replace ('|||','
',stripslashes ($keywordsd[0]));
$stkeywordsd = explode('===',$rss_channel_info['stkeywords']);
$stkeywords = str_replace ('|||','
',stripslashes ($stkeywordsd[0]));
$Autor = explode('=',$rss_channel_info['Autors']);
$Autors = str_replace ('|||','
',stripslashes ($Autor['0']));
$short_story = explode('=',$rss_channel_info['short_story']);
$date = explode('=',$rss_channel_info['date']);
$delate = str_replace ('|||','
',stripslashes ($rss_channel_info['delate']));
$inser = str_replace ('|||','
',stripslashes ($rss_channel_info['inser']));
$start = str_replace ('|||','
',$rss_channel_info['start']);
$finish = str_replace ('|||','
',$rss_channel_info['finish']);
$end_title = explode ('==',$rss_channel_info['end_title']);
$hide_leech = explode('=',$rss_channel_info['end_short']);
$ctp = explode ('=',$rss_channel_info['ctp']);
$dop_nast = explode ('=',$rss_channel_info['dop_nast']);
$dnast = explode ('=',$rss_channel_info['dnast']);
$categoryes = explode ('=',$rss_channel_info['category']);
$stitles = str_replace ('|||','
',$end_title[2]);
$ftitles = str_replace ('|||','
',$end_title[3]);
$kategory = str_replace ('|||','
',$rss_channel_info['kategory']);
if(strlen(stripslashes ($date[0])) == 10) $date[0] = '';
echoheader ('','');
$channel_name = '';
foreach($_POST['channel'] as $key)
	{
$title_gr = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$key'");
//$titles .= stripslashes ($title_gr['title']).'<br>';
//$urls .= stripslashes ($title_gr['url']).'<br>';

$title_grups = stripslashes (strip_tags ($title_gr['title']));
if (trim ($title_grups) != '')
{
if (50 <strlen ($title_grups))
{
$title_grups = substr ($title_grups,0,50) .'...';
}
}
else
{
$title_grups = $lang_grabber['no_title'];
}


              $channel_name .= '<font color=green> №' .$title_gr['xpos'].' - '. stripslashes ($title_grups) . '</font> (<font color=red>' . stripslashes ($title_gr['url']) . '</font>) <a href="'.$title_gr['url'] .'" target="_blank" title="Перейти на сайт донора">[i]</a> <a href="?mod=rss&action=channel&subaction=edit&id='.$title_gr['id'] .'" target="_blank" title="Редактировать канал отдельно">[P]</a></br> ';


}


$channel_inf = array();
$sql_result = $db->query ('SELECT * FROM '.PREFIX .'_rss_category ORDER BY kanal asc' );
$run[0] = '';
while ($channel_info = $db->get_row($sql_result)) {
if ($channel_info['osn'] == '0')$channel_inf[$channel_info['id']][$channel_info['id']] =  $channel_info['title'];
else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- '. $channel_info['title'];
}
foreach($channel_inf as $value)
{
	if (count($value) != '0'){
foreach($value as $kkey=>$key)
{
$run[$kkey] = $key;
}
	}
}
opentable ('<a href='.$PHP_SELF .'?mod=rss>'.$lang_grabber['index_page'] . '<b>ГРУППОВОЕ РЕДАКТИРОВАНИЕ КАНАЛОВ</b>' );
$tpl->load_template ('grup_addchannel.tpl');
$tpl->set ('{rss_html}',yesno ($rss_channel_info['rss'] == 0 ?'no': 'yes'));
$tpl->set ('{stkeywords}',$stkeywords);
$tpl->set ('{charsets}',$dop_nast[14]);
$tpl->set ('{dubl-host}',yesno ($dop_nast[15] == 0 ?'no': 'yes'));
$tpl->set ('{one-serv}',yesno ($dop_nast[11] == 0 ?'no': 'yes'));
$tpl->set ('{title}',$channel_name);
$tpl->set ('{discr}',stripslashes ($rss_channel_info['descr']));
$tpl->set ('{address}',$rss_channel_info['url']);
$tpl->set ('{date-format}',gen_date_format ($rss_channel_info['date_format']));
$tpl->set ('{category}',categorynewsselection (0,0));
$tpl->set ('{rss-priv}',sel ($run,$categoryes[1]));
$tpl->set ('{groups}',get_groups(explode(',',$Autor['1'])));
$tpl->set ('{load-images}',yesno ($rss_channel_info['load_img'] == 0 ?'no': 'yes'));
$tpl->set ('{thumb-images}',yesno ($rss_channel_info['thumb_img'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-main}',yesno ($rss_channel_info['allow_main'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-mod}',yesno ($rss_channel_info['allow_mod'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-comm}',yesno ($rss_channel_info['allow_comm'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-rate}',yesno ($rss_channel_info['allow_rate'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-full}',yesno ($rss_channel_info['allow_more'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-auto}',yesno ($rss_channel_info['allow_auto'] == 0 ?'no': 'yes'));
$tpl->set ('{allow-water}',yesno ($rss_channel_info['allow_watermark'] == 0 ?'no': 'yes'));
$tpl->set ('{show_autor}',yesno ($dop_nast[5] == 0 ?'no': 'yes'));
$tpl->set ('{show_tegs}',yesno ($dop_nast[6] == 0 ?'no': 'yes'));
$tpl->set ('{show_date}',yesno ($dop_nast[7] == 0 ?'no': 'yes'));
$tpl->set ('{show_code}',yesno ($dop_nast[8] == 0 ?'no': 'yes'));
$tpl->set ('{show_down}',yesno ($dop_nast[13] == 0 ?'no': 'yes'));
$tpl->set ('{show_f}',yesno ($dop_nast[9] == 0 ?'no': 'yes'));
$tpl->set ('{show_symbol}',yesno ($dnast[2] == 0 ?'no': 'yes'));
$tpl->set ('{show_metatitle}',yesno ($dnast[3] == 0 ?'no': 'yes'));
$tpl->set ('{show_metadescr}',yesno ($dnast[4] == 0 ?'no': 'yes'));
$tpl->set ('{show_keywords}',yesno ($dnast[5] == 0 ?'no': 'yes'));
$tpl->set ('{wat-host}',yesno ($dnast[15] == 0 ?'no': 'yes'));
$tpl->set ('{rewrite-data}',yesno ($dnast[17] == 0 ?'no': 'yes'));
$tpl->set ('{show-url}',yesno ($dnast[6] == 0 ?'no': 'yes'));
$tpl->set ('{cron-auto}', $dnast[16]);
$tpl->set ('{kol-cron}',$dnast[19]);
$tpl->set ('{rss-parse}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full'], '3'=>$lang_grabber['thumb_shortfull']),$dnast[7]));
$tpl->set ('{tags-auto}',yesno ($dnast[8] == 0 ?'no': 'yes'));
$tpl->set ('{auto-metatitle}',yesno ($dnast[9] == 0 ?'no': 'yes'));
$tpl->set ('{data-deap}',$dnast[10]);
$tpl->set ('{deap}',deap ($dnast[11] == 0 ?'yes': 'no'));
$tpl->set ('{symbol}',$rss_channel_info['symbol']);
$tpl->set ('{auto-symbol}',yesno ($dnast[12] == 0 ?'no': 'yes'));
$tpl->set ('{auto-numer}',sel (array(1 => '1', 2 => '2', 3 => '3'), $dnast[13] ));
$tpl->set ('{show_date_expires}',yesno ($dnast[14] == 0 ?'no': 'yes'));
$tpl->set ('{tags}',$rss_channel_info['ftags']);
$tpl->set ('{meta-title}',$rss_channel_info['metatitle']);
$tpl->set ('{meta-descr}',$rss_channel_info['meta_descr']);
$tpl->set ('{key-words}', $rss_channel_info['key_words']);
$tpl->set ('{hide}',yesno ($hide_leech[1] == 0 ?'no': 'yes'));
$tpl->set ('{leech}',yesno ($hide_leech[2] == 0 ?'no': 'yes'));
$tpl->set ('{rewrite-news}',yesno ($hide_leech[3] == 0 ?'no': 'yes'));
$tpl->set ('{clear-short}',yesno ($short_story[0] == 0 ?'no': 'yes'));
$tpl->set ('{clear-full}',yesno ($short_story[17] == 0 ?'no': 'yes'));
$tpl->set ('{short-images}',yesno ($short_story[1] == 0 ?'no': 'yes'));
$tpl->set ('{short-full}',yesno ($short_story[2] == 0 ?'no': 'yes'));
$tpl->set ('{pings}',yesno ($short_story[4] == 0 ?'no': 'yes'));
$tpl->set ('{teg-fix}',stripslashes ($short_story[5]));
$tpl->set ('{cat-nul}',yesno ($short_story[6] == 0 ?'no': 'yes'));
$tpl->set ('{text-html}',yesno ($short_story[9] == 0 ?'no': 'yes'));
$tpl->set ('{dim-week}',yesno ($date[1] == 0 ?'no': 'yes'));
$tpl->set ('{dim-date}',yesno ($date[2] == 0 ?'no': 'yes'));
$tpl->set ('{dim-sait}',yesno ($date[3] == 0 ?'no': 'yes'));
$tpl->set ('{dim-cat}',yesno ($date[4] == 0 ?'no': 'yes'));
$tpl->set ('{title-prob}',yesno ($short_story[11] == 0 ?'no': 'yes'));
$tpl->set ('{no-prow}',yesno ($short_story[12] == 0 ?'no': 'yes'));
$tpl->set ('{grab-pause}',$dop_nast[19]);
$tpl->set ('{add-pause}',$dop_nast[21]);
$tpl->set ('{kol-short}',$dop_nast[22]);
$tpl->set ('{image-align}',gen_x ($dnast[0]));
$tpl->set ('{image-align-full}',gen_x ($dnast[1]));
$tpl->set ('{start-template}',str_replace ('&','&amp;',stripslashes ($rss_channel_info['start_template'])));
$tpl->set ('{end-template}',str_replace ('&','&amp;',stripslashes ($rss_channel_info['finish_template'])));
$tpl->set ('{x}',gen_x ($dop_nast[3]));
$tpl->set ('{y}',gen_y ($dop_nast[4]));
$tpl->set ('{delate}',$delate);
$tpl->set ('{inser}',$inser);
$tpl->set ('{start}',$start);
$tpl->set ('{finish}',$finish);
$tpl->set ('{full-link}',stripslashes ($rss_channel_info['full_link']));
$tpl->set ('{dop-full}',stripslashes ($rss_channel_info['dop_full']));
$tpl->set ('{so}',$ctp[0]);
$tpl->set ('{po}',$ctp[1]);
$tpl->set ('{dop-watermark}',yesno ($dop_nast[0] == 0 ?'no': 'yes'));
$tpl->set ('{add-full}',yesno ($short_story[20] == 0 ?'no': 'yes'));
$tpl->set ('{lang-on}', yesno ($short_story[13] == 0 ?'no': 'yes'));
$tpl->set ('{lang-in}', slected_lang($short_story[14] == '' ?'ru': $short_story[14]));
$tpl->set ('{lang-out}',slected_lang ($short_story[15] == '' ?'en': $short_story[15]));
$tpl->set ('{lang-in}', slected_lang($short_story[14] == '' ?'ru': $short_story[14]));
$tpl->set ('{lang-outf}',slected_lang ($short_story[18] == '' ?'': $short_story[18]));
$tpl->set ('{cat-sp}', yesno ($short_story[16] == 0 ?'no': 'yes'));
$tpl->set ('{text-url-sel}',sel (array ('0'=>$lang_grabber['thumb_shortfull'], '1'=>$lang_grabber['thumb_short'], '2'=>$lang_grabber['thumb_full']), $dop_nast[16]));
$tpl->set ('{full-url-and}',yesno ($dop_nast[18] == 0 ?'no': 'yes'));
$tpl->set ('{parse-url-sel}',yesno ($dop_nast[17] == 0 ?'no': 'yes'));

$tpl->set ('{log-pas}',yesno ($short_story[8] == 0 ?'no': 'yes'));
$tpl->set ('{keyw-sel}',sel (array(0 => $lang_grabber['sel_shortfull'], 1 => $lang_grabber['sel_short'], 2 => $lang_grabber['sel_full'], 3 => $lang_grabber['sel_short_full'], 4 => $lang_grabber['sel_no_gener']), $short_story[7]));
$tpl->set ('{descr-sel}',sel (array(0 => $lang_grabber['sel_shortfull'], 1 => $lang_grabber['sel_short'], 2 => $lang_grabber['sel_full'], 3 => $lang_grabber['sel_short_full'], 4 => $lang_grabber['sel_no_gener']), $short_story[10]));
$tpl->set ('{text-url}',sel (array(0 => $lang_grabber['no_izm'], 1 => $lang_grabber['url_klik'], 2 => $lang_grabber['url_no_donw'], 3 => $lang_grabber['url_no_donor']), $dop_nast[1]));

//$tpl->set ('{text-url}',yesno ($dop_nast[1] == 0 ?'no': 'yes'));

$tpl->set ('{prox}',yesno ($dop_nast[2] == 0 ?'no': 'yes'));
$tpl->set ('{null}',yesno ($dop_nast[10] == 0 ?'no': 'yes'));
$tpl->set ('{load-img}', server_host($rss_channel_info['load_img']));
$tpl->set ('{margin}',intval($dop_nast[12]));
$tpl->set ('{xdescr}',htmlspecialchars($rss_channel_info['xdescr'],ENT_QUOTES));
$tpl->set ('{ful-start}',stripslashes ($rss_channel_info['ful_start']));
$tpl->set ('{ful-end}',stripslashes ($rss_channel_info['ful_end']));
$tpl->set ('{start-title}',stripslashes ($rss_channel_info['start_title']));
$tpl->set ('{end-title}',stripslashes ($end_title[1]));
$tpl->set ('{s-title}',stripslashes ($end_title[0]));
$tpl->set ('{link-start-del}',stripslashes ($end_title[4]));
$tpl->set ('{link-finish-del}',stripslashes ($end_title[5]));
$tpl->set ('{sfr-short}',$keywordsd[1]);
$tpl->set ('{efr-short}',$stkeywordsd[1]);
$tpl->set ('{sfr-full}',$keywordsd[2]);
$tpl->set ('{efr-full}',$stkeywordsd[2]);
$tpl->set ('{end-del}',$ftitles);
$tpl->set ('{s-del}',$stitles);
$tpl->set ('{start-short}',stripslashes ($rss_channel_info['start_short']));
$tpl->set ('{end-short}',yesno ($hide_leech[0] == 0 ?'no': 'yes'));
$tpl->set ('{sart-link}',stripslashes ($rss_channel_info['sart_link']));
$tpl->set ('{step-page}',$dop_nast[20]);
$tpl->set ('{end-link}',yesno ($rss_channel_info['end_link'] == 0 ?'no': 'yes'));
$sart_cat = explode('|||', $rss_channel_info['sart_cat']); 
$tpl->set ('{sart-cat}',stripslashes ($sart_cat[0]));
$tpl->set ('{shab-data}',stripslashes ($sart_cat[1]));
$tpl->set ('{end-cat}',stripslashes ($rss_channel_info['end_cat']));
$tpl->set ('{date}',stripslashes ($date[0]));
$tpl->set ('{cookies}',$cookies);
$tpl->set ('{keywords}',$keywords);
$tpl->set ('{Autors}',$Autors);
$tpl->set ('{kategory}', $kategory);
$xfields_template = explode ('|||', $rss_channel_info['xfields_template']);
$template = '';
$x= 1;
foreach ($xfields_template as $value){
if ($value != ''){
$key = explode ('==', $value);
$template .= '<table cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><center>'.$lang_grabber['rss_xfields_template'].'</center></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_'.$x.'" class="load_img">
    '.sel (rss_xfields('1'), $key[0]).'
   </select><br>
   '.$lang_grabber['use_po_get'].'
     <select name="ret_xf_'.$x.'" class="load_img">
    '.yesno ($key[2] == 0 ?'no': 'yes').'
   </select>
    '.$lang_grabber['take_short-story'].'
     <select name="sh_fl_'.$x.'" class="load_img">
    '.yesno ($key[3] == 0 ?'no': 'yes').'
      </select><br />
   В поле ссылка на изображение
     <select name="sh_im_'.$x.'" class="load_img">
    '.yesno ($key[4] == 0 ?'no': 'yes').'
   </select>
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_'.$x.'">'.stripslashes($key[1]).'</textarea>
   </td></tr>
</table>';

$x++;
}
}
$template .=    '<table cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><center>'.$lang_grabber['rss_xfields_template'].'</center></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_'.$x.'" class="load_img">
    '.sel (rss_xfields('1'), '').'
   </select><br>
   '.$lang_grabber['use_po_get'].'
     <select name="ret_xf_'.$x.'" class="load_img">
    '.yesno ('no').'
   </select>
   '.$lang_grabber['take_short-story'].'
     <select name="sh_fl_'.$x.'" class="load_img">
    '.yesno ('no').'
   </select><br />
   В поле ссылка на изображение
     <select name="sh_im_'.$x.'" class="load_img">
    '.yesno ('no').'
   </select>
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_'.$x.'"></textarea>
   </td></tr>
</table>';
$tpl->set ('{kol-xfields}', $x);
$tpl->set ('{xfields-template}', $template);

if (@file_exists (ENGINE_DIR .'/inc/plugins/sinonims.php') )
{
	
	$sin =  '
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">'.$lang_grabber['sinonims'].':</td>
   <td width="768" style="padding:4px">
   <select name="sinonim" class="load_img">
'.yesno ($short_story[3] == 0 ?'no': 'yes').'
   </select>
   <select name="sinonim_sel" class="load_img">'.
sel (array ('0'=>$lang_grabber['thumb_shortfull'], '1'=>$lang_grabber['thumb_short'], '2'=>$lang_grabber['thumb_full']),  $short_story[19]).'
   </select>
  </td>
  </tr>';
}
$tpl->set ('{sinonim}',$sin);

foreach ($lang_grabber as $key =>$value){$tpl->set ('{'.$key.'}',$value);}
$form = '	<form method="post">
	<input type="hidden" name="id" value="'.$id .'" />
	<input type="hidden" name="action" value="updategrup_channel" />';

if ($config['version_id'] < '8.5'){
$form .= '<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>';
}else{
$form .= '<script type="text/javascript" src="engine/classes/js/dle_ajax.js"></script>';
}

include_once (ENGINE_DIR .'/inc/plugins/inserttag.php');
include_once (ENGINE_DIR .'/inc/plugins/inserttag.php');
$form .= $bb_js ."
<script>
	$(function(){
		$('#tags').autocomplete({ 
		    serviceUrl:'engine/ajax/find_tags.php',
		    minChars:3, 
		    delimiter: /(,|;)\s*/,
		    maxHeight:400,
		    width:348,
		    deferRequestBy: 300
		  });

	});
</script>
<script language=\"javascript\" type=\"text/javascript\">
function simpletags(thetag)
{
				doInsert(\"{\"+thetag+\"}\", \"\", false);
}

</script>
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'	border='0' /></div>
";
$tpl->set ('{BB_code}',	$add_bb);

$tpl->copy_template = $form .$tpl->copy_template .'
			<input align="left" class="edit" type="submit"  value=" '.$lang_grabber['save'].' " >&nbsp;
			<input type="button"	class="edit" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /></form>';
$tpl->compile ('rss');
echo $tpl->result['rss'];
closetable ();
echofooter ();
$db->close;
exit();
}
}


clear_cache();
$db->close;

?>
