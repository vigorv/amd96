<?php
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
$module_info = array ('name'=>'RSS Grabber','version'=>'3.6.8','build'=>'2701');
if ( file_exists( ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng') ) {
	@require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';
}else die("Language file not found");
$dle_plugins = ENGINE_DIR .'/classes/';
require_once $dle_plugins.'templates.class.php';
require_once $dle_plugins.'parse.class.php';
$parse = new ParseFilter (array (),array (),1,1);
$tpl = new dle_template ();
$rss_plugins = ENGINE_DIR .'/inc/plugins/';
require_once $rss_plugins.'core.php';
require_once $rss_plugins.'rss.parser.php';
require_once $rss_plugins.'backup.php';
$tpl->dir = $rss_plugins.'templates/';
require_once $rss_plugins.'rss.classes.php';
require_once $rss_plugins.'rss.functions.php';
require_once $rss_plugins.'channel.php';
@include(ENGINE_DIR.'/data/rss_config.php');
if ($_REQUEST['action'] != '')
{
	$action = $_REQUEST['action'];
}
else
{
	$action = '';
}
if ($_REQUEST['subaction'] != '')
{
	$subaction = $_REQUEST['subaction'];
}
else
{
	$subaction = '';
}
if ($_REQUEST['id'] != '')
{
	$id = intval ($_REQUEST['id']);
}
else
{
	$id = '';
}
$add_bb = ' <div style="width:79%; height:25px; border:0px solid #BBB; background-image:url(\'engine/skins/bbcodes/images/bg.gif\')">
<div> </div><div id="skip" style="padding:5px 0 0 2px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'skip\')" ><b>{skip}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="get" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'get\')"><b>{get}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="num" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'num\')"><b>{num}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div></div>';
$add_bbz = ' <div style="width:79%; height:25px; border:0px solid #BBB; background-image:url(\'engine/skins/bbcodes/images/bg.gif\')">
<div> </div><div id="skip" style="padding:5px 0 0 2px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'skip\')" ><b>{skip}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="get" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'get\')"><b>{get}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="num" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'num\')"><b>{num}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="frag" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'frag\')"><b>{frag}</b>
</div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="zagolovok" style="padding-top:5px;font: 7pt bold;" class="editor_button" onclick="simpletags(\'zagolovok\')"><b>{zagolovok}</b>
</div>
 <div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>';
if (($action == 'channel') and ($subaction == 'add'))
{
	echoheader ('','');
	opentable ($lang_grabber['new_channel']);
	$channel_inf = array();
	$sql_result = $db->query ('SELECT * FROM '.PREFIX .'_rss_category ORDER BY kanal asc');
	$run[0] = '';
	while ($channel_info = $db->get_row($sql_result)) {
		if ($channel_info['osn'] == '0')$channel_inf[$channel_info['id']][$channel_info['id']] =  $channel_info['title'];
		else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- '.$channel_info['title'];
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
	$tpl->load_template ('rss_addchannel.tpl');
	$tpl->set ('{rss-priv}',sel ($run,''));
	$tpl->set ('{title}','');
	$tpl->set ('{category}',categorynewsselection (1,0));
	$tpl->set ('{address}','http://');
	$tpl->set ('{date-format}',gen_date_format ($config_rss['date']));
	$tpl->set ('{charsets}','');
	$tpl->set ('{load-img}',server_host($config_rss['img_host'] ));
	$tpl->set ('{dubl-host}',yesno ('no'));
	$tpl->set ('{rss-parse}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full'],'3'=>$lang_grabber['thumb_shortfull']),''));
	$tpl->set ('{one-serv}',yesno ('no'));
	$tpl->set ('{image-align}',gen_x ($config_rss['image_align']));
	$tpl->set ('{image-align-full}',gen_x ($config_rss['image_align_full']));
	$tpl->set ('{hide}',yesno ($config_rss['hide']));
	$tpl->set ('{leech}',yesno ($config_rss['leech']));
	$tpl->set ('{thumb-images}',yesno ($config_rss['cat']));
	$tpl->set ('{cat-nul}',yesno ('no'));
	$tpl->set ('{cat-sp}',yesno ('no'));
	$tpl->set ('{kategory}','');
	$tpl->set ('{kol-short}','');
	$tpl->set ('{sim-short}','');
	$tpl->set ('{page-break}','');
	$tpl->set ('{data-deap}','');
	$tpl->set ('{deap}',deap ());
	$tpl->set ('{log-pas}',yesno ('no'));
	$tpl->set ('{wat-host}',yesno ('no'));
	$tpl->set ('{tags-auto}',yesno ($config_rss['tags_auto']));
	$tpl->set ('{allow-mod}',yesno ($config_rss['allow-mod']));
	$tpl->set ('{allow-main}',yesno ($config_rss['allow-main']));
	$tpl->set ('{allow-comm}',yesno ($config_rss['allow-comm']));
	$tpl->set ('{allow-rate}',yesno ($config_rss['allow-rate']));
	$tpl->set ('{allow-full}',yesno ($config_rss['allow-full']));
	$tpl->set ('{allow-auto}',yesno ($config_rss['allow-auto']));
	$tpl->set ('{allow-water}',yesno ($config_rss['allow-water']));
	$tpl->set ('{rewrite-news}',yesno ($config_rss['rewrite-news']));
	$tpl->set ('{rewrite-data}',yesno ('no'));
	$tpl->set ('{clear-short}',yesno ($config_rss['clear-short']));
	$tpl->set ('{clear-full}',yesno ('no'));
	$tpl->set ('{short-images}',yesno ($config_rss['short-images']));
	$tpl->set ('{short-full}',yesno ($config_rss['short-full']));
	$tpl->set ('{dop-watermark}',yesno ($config_rss['dop-watermark']));
	$tpl->set ('{null}',yesno ($config_rss['null']));
	$tpl->set ('{grab-pause}','');
	$tpl->set ('{add-pause}','');
	$tpl->set ('{cron-auto}','');
	$tpl->set ('{text-html}',yesno ('no'));
	$tpl->set ('{dim-date}',yesno ('no'));
	$tpl->set ('{dim-sait}',yesno ('no'));
	$tpl->set ('{dim-cat}',yesno ('no'));
	$tpl->set ('{dim-week}',yesno ('no'));
	$tpl->set ('{full-url-and}',yesno ('no'));
	$tpl->set ('{text-url-sel}',sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$config_rss['url-sel']));
	$tpl->set ('{parse-url-sel}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_full'],'2'=>$lang_grabber['thumb_short'],'3'=>$lang_grabber['thumb_shortfull']),''));
	$tpl->set ('{keyw-sel}',sel (array(0 =>$lang_grabber['sel_shortfull'],1 =>$lang_grabber['sel_short'],2 =>$lang_grabber['sel_full'],3 =>$lang_grabber['sel_short_full'],4 =>$lang_grabber['sel_no_gener']),$config_rss['keyw-sel']));
	$tpl->set ('{descr-sel}',sel (array(0 =>$lang_grabber['sel_shortfull'],1 =>$lang_grabber['sel_short'],2 =>$lang_grabber['sel_full'],3 =>$lang_grabber['sel_short_full'],4 =>$lang_grabber['sel_no_gener']),$config_rss['descr-sel']));
	$tpl->set ('{text-url}',sel (array(0 =>$lang_grabber['no_izm'],1 =>$lang_grabber['url_klik'],2 =>$lang_grabber['url_no_donw'],3 =>$lang_grabber['url_no_donor']),$config_rss['text-url']));
	$template .=    '
<div><a href="javascript:ShowOrHide(\'full_1\');"><center><b>Дополнительное поле 1 +/-</b></center></a></div>
<div id="full_1" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><center>'.$lang_grabber['rss_xfields_template'].'</center></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_1" class="load_img">
    '.sel (rss_xfields('1'),'').'
   </select><br>
   '.$lang_grabber['use_po_get'].'
     <select name="ret_xf_1" class="load_img">
    '.yesno ('no').'
   </select>
   '.$lang_grabber['take_short-story'].'
     <select name="sh_fl_1" class="load_img">
    '.yesno ('no').'
   </select><br />
   В поле ссылка на изображение
     <select name="sh_im_1" class="load_img">
    '.yesno ('no').'
   </select>
   Размер изображение
     <input name="rs_im_1" class="load_img" type="text" size="10" value="0">
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_1"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>'.$lang_grabber['templates_search_regular'].' <b>в дополнительном поле</b></center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['expression'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_delete_1"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['paste'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_insert_1"></textarea>
   </td></tr>
</table>
</div>
';
	$tpl->set ('{xfields-template}',$template);
	$tpl->set ('{x}',gen_x ($config_rss['x']));
	$tpl->set ('{y}',gen_y ($config_rss['y']));
	$tpl->set ('{margin}',$config_rss['margin']);
	if (@file_exists ($rss_plugins.'sinonims.php') )
	{
		$sin =  '
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">'.$lang_grabber['sinonims'].':</td>
   <td width="768" style="padding:4px">
   <select name="sinonim" class="edit">
'.yesno ($config_rss['sinonim']).'
   </select>
   <select name="sinonim_sel" class="load_img">'.
		sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$config_rss['sinonim-sel']).'
   </select>
  </td>
  </tr>';
	}
	$tpl->set ('{sinonim}',$sin);
	$tpl->set ('{title-prob}',yesno ('no'));
	$tpl->set ('{no-prow}',dubl_news ());
	$tpl->set ('{pings}',yesno($config_rss['allow_post']));
	$tpl->set ('{show_autor}',yesno ($config_rss['show_autor']));
	$tpl->set ('{show_tegs}',yesno ($config_rss['show_tegs']));
	$tpl->set ('{show_date}',yesno ($config_rss['show_date']));
	$tpl->set ('{show_code}',yesno ($config_rss['show_code']));
	$tpl->set ('{show_date}',yesno ($config_rss['show_date']));
	$tpl->set ('{show_code}',yesno ($config_rss['show_code']));
	$tpl->set ('{show_down}',yesno ($config_rss['show_down']));
	$tpl->set ('{show_f}',yesno ($config_rss['show_f']));
	$tpl->set ('{show_symbol}',yesno ($config_rss['show_symbol']));
	$tpl->set ('{show-url}',yesno ($config_rss['show_url']));
	$tpl->set ('{show_date_expires}',yesno ($config_rss['show_date_expires']));
	$tpl->set ('{show_metatitle}',yesno ($config_rss['show_metatitle']));
	$tpl->set ('{show_metadescr}',yesno ($config_rss['show_metadescr']));
	$tpl->set ('{show_keywords}',yesno ($config_rss['show_keywords']));
	$tpl->set ('{symbol}','');
	$tpl->set ('{auto-symbol}',yesno ('no'));
	$tpl->set ('{auto-numer}',sel (array(1 =>'1',2 =>'2',3 =>'3'),''));
	$tpl->set ('{tags}','');
	$tpl->set ('{teg-fix}','');
	$tpl->set ('{meta-title}','');
	$tpl->set ('{auto-metatitle}',yesno ($config_rss['auto_metatitle']));
	$tpl->set ('{meta-descr}','');
	$tpl->set ('{key-words}','');
	$tpl->set ('{prox}',yesno ('no'));
	$tpl->set ('{start-template}','<div id={skip}news-id-{skip}>{get}</div>');
	$tpl->set ('{delate}','');
	$tpl->set ('{inser}','');
	$tpl->set ('{cookies}','');
	$tpl->set ('{keywords}','');
	$tpl->set ('{xdescr}','');
	$tpl->set ('{stkeywords}','');
	$tpl->set ('{date}','');
	$tpl->set ('{start}','');
	$tpl->set ('{finish}','');
	$tpl->set ('{kol-cron}','');
	$tpl->set ('{tags-kol}','');
	$tpl->set ('{dop-full}','');
	$tpl->set ('{groups}',get_groups(explode(',',$config_rss['reg_group'])));
	$tpl->set ('{Autors}','');
	$tpl->set ('{link-start-del}','');
	$tpl->set ('{link-finish-del}','');
	$tpl->set ('{ful-start}','');
	$tpl->set ('{start-title}','');
	$tpl->set ('{s-title}','');
	$tpl->set ('{end-title}','');
	$tpl->set ('{sfr-short}','');
	$tpl->set ('{efr-short}','');
	$tpl->set ('{sfr-full}','');
	$tpl->set ('{efr-full}','');
	$tpl->set ('{s-del}','');
	$tpl->set ('{end-del}','');
	$tpl->set ('{start-short}','<div id={skip}news-id-{skip}>{get}</div>');
	$tpl->set ('{end-short}',yesno ('no'));
	$tpl->set ('{sart-link}','');
	$tpl->set ('{step-page}','');
	$tpl->set ('{end-link}',yesno ('no'));
	$tpl->set ('{sart-cat}','');
	$tpl->set ('{shab-data}','');
	$tpl->set ('{full-link}','');
	$tpl->set ('{so}','');
	$tpl->set ('{po}','');
	$tpl->set ('{zhv-code}','');
	$tpl->set ('{lang-on}',yesno ('no'));
	$tpl->set ('{lang-in}',slected_lang ('ru'));
	$tpl->set ('{lang-out}',slected_lang ('en'));
	$tpl->set ('{lang-outf}',slected_lang (''));
	$tpl->set ('{add-full}',yesno ('no'));
	include_once ($rss_plugins.'inserttag.php');
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
var skip = 0;
var get = 0;
var frag = 0;
var zagolovok = 0;


function simpletags(thetag)
{
                doInsert(\"{\"+thetag+\"}\", \"\", false);
}

</script>
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'   border='0' /></div>
";
	$tpl->set ('{BB_code}',$add_bb);
	$tpl->set ('{BB_codez}',$add_bbz);
	foreach ($lang_grabber as $key =>$value)$tpl->set ('{'.$key.'}',$value);
	$tpl->copy_template = '<form method="post"><input type="hidden" name="action" value="channel" /><input type="hidden" name="subaction" value="doadd" />'.$tpl->copy_template .$form.'
        <input align="left" class="edit" type="submit" value=" '.$lang_grabber['save'].' " >&nbsp;
        <input type="button"    class="edit" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /></form>';
	$tpl->compile ('rss');
	echo $tpl->result['rss'];
	closetable ();
	echofooter ();$db->close;return 1;}
	if ($config_rss['get_proxy'] == 'yes') get_proxy();
	$config_rss['get_prox'] = $tab_id;
	if (($action == 'channel') and ($subaction == 'doadd')){
		$stop = false;
		$rss_url = stripslashes ($_POST['rss_url']);
		if ($_POST['category'] != ''){
			$category_post = $db->safesql( implode( ',',$_POST['category']));
		}else{$category_post = '0';}
		$category = $category_post.'='.intval ($_POST['rss_priv']);
		$allow_more = intval ($_POST['allow_more']);
		$allow_mod = intval ($_POST['allow_mod']);
		$allow_main = intval ($_POST['allow_main']);
		$allow_comm = intval ($_POST['allow_comm']);
		$allow_rate = intval ($_POST['allow_rate']);
		$load_images = $db->safesql ($_POST['load_img']);
		$thumb_images = intval ($_POST['thumb_img']);
		$allow_watermark = intval ($_POST['allow_watermark']);
		$date_format = intval ($_POST['news_date']);
		$ctp = intval ($_POST['so']).'='.intval ($_POST['po']);
		$dnast = intval ($_POST['image_align']).'='.intval ($_POST['image_align_full']).'='.intval ($_POST['show_symbol']).'='.intval ($_POST['show_metatitle']).'='.intval ($_POST['show_metadescr']).'='.intval ($_POST['show_keywords']).'='.intval ($_POST['show_url']).'='.intval ($_POST['rss_parse']).'='.intval ($_POST['tags_auto']).'='.intval ($_POST['auto_metatitle']).'='.intval ($_POST['data_deap']).'='.intval ($_POST['deap']).'='.intval ($_POST['auto_symbol']).'='.intval ($_POST['auto_numer']).'='.intval ($_POST['show_date_expires']).'='.intval ($_POST['wat_host']).'='.intval ($_POST['cron_auto']).'='.intval ($_POST['rewrite_data']).'='.intval ($_POST['ret_xf']).'='.intval ($_POST['kol_cron']).'='.$db->safesql ($_POST['tags_kol']);
		$full_link = stripslashes ($_POST['full_link']);
		$short_story = intval ($_POST['clear_short']).'='.intval ($_POST['short_img']).'='.intval ($_POST['short_full']).'='.intval ($_POST['sinonim']).'='.intval ($_POST['pings']).'='.$db->safesql ($_POST['teg_fix']).'='.intval ($_POST['cat_nul']).'='.intval ($_POST['keyw_sel']).'='.intval ($_POST['log_pas']).'='.intval ($_POST['text_html']).'='.intval ($_POST['descr_sel']).'='.intval ($_POST['title_prob']).'='.intval ($_POST['no_prow']).'='.intval ($_POST['lang_no']).'='.$db->safesql ($_POST['lang_in']).'='.$db->safesql ($_POST['lang_out']).'='.intval ($_POST['cat_sp']).'='.intval ($_POST['clear_full']).'='.$db->safesql ($_POST['lang_outf']).'='.intval ($_POST['sinonim_sel']).'='.intval ($_POST['add_full']);
		$start_template = $db->safesql ($_POST['start_template']);
		$finish_template = $db->safesql ($_POST['finish_template']);
		$dop_full = $db->safesql ($_POST['dop_full']);
		$start = $db->safesql (str_replace ('
','|||',$_POST['start']));
		$finish = $db->safesql (str_replace ('
','|||',$_POST['finish']));
		$delate = $db->safesql (str_replace ('
','|||',$_POST['delate']));
		$inser = $db->safesql (str_replace ('
','|||',$_POST['inser']));
		$symbol = $db->safesql ($_POST['symbols']);
		$ftags = $db->safesql ($_POST['tags']);
		$metatitle = $db->safesql ($_POST['meta_title']);
		$meta_descr = $db->safesql ($_POST['meta_descr']);
		$key_words = $db->safesql ($_POST['key_words']);
		$ful_start = $db->safesql ($_POST['ful_start']);
		$ful_end = $db->safesql ($_POST['ful_end']);
		$start_title = $db->safesql ($_POST['start_title']);
		$stitles = $db->safesql (str_replace ('
','|||',$_POST['s_del']));
		$ftitles = $db->safesql (str_replace ('
','|||',$_POST['end_del']));
		$end_title = $db->safesql ($_POST['s_title']).'=='.$db->safesql ($_POST['end_title']).'=='.$stitles.'=='.$ftitles.'=='.$db->safesql ($_POST['link_start_del']).'=='.$db->safesql ($_POST['link_finish_del']);
		$start_short = $db->safesql ($_POST['start_short']);
		$end_short = intval ($_POST['end_short']).'='.intval ($_POST['hide']).'='.intval ($_POST['leech']).'='.intval ($_POST['rewrite_news']);
		$sart_link = $db->safesql ($_POST['sart_link']);
		$end_link = $db->safesql ($_POST['end_link']);
		$sart_cat = $db->safesql ($_POST['sart_cat']).'|||'.$db->safesql ($_POST['shab_data']).'|||'.$db->safesql ($_POST['zhv_code']);
		$end_cat = $db->safesql ($_POST['end_cat']);
		$xdescr = $db->safesql($_POST['rss_xdescr']);
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
		$proxy = '';
		$kategory = $db->safesql (str_replace ('
','|||',$_POST['kategory']));
		$xfields_template = $db->safesql ($_POST['rss_xfields_1']).'=='.$db->safesql ($_POST['xfields_template_1']).'=='.intval ($_POST['ret_xf_1']).'=='.intval ($_POST['sh_fl_1']).'=='.intval ($_POST['sh_im_1']).'=='.$db->safesql ($_POST['xfields_delete_1']).'=='.$db->safesql ($_POST['xfields_insert_1']).'=='.intval ($_POST['rs_im_1']);
		if (trim ($rss_url) == '')
		{
			msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['error_url'],$PHP_SELF .'?mod=rss&action=channel&subaction=add');
			return 1;
		}
		if (!(ereg ('http://',$rss_url)) or reset_url($rss_url) == '')
		{
			msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['error_url_msg'],$PHP_SELF .'?mod=rss&action=channel&subaction=add');
			return 1;
		}
		$rss_url = $db->safesql ($rss_url);
		$inf = $db->super_query ('SELECT id,title FROM '.PREFIX ."_rss WHERE url = '".trim($rss_url)."'");
		$rss_result = get_rss_channel_info ($rss_url,intval ($_POST['proxy']),$_POST['charset']);
		if ($rss_result['title'] != '')
		{
			$channel_title = $rss_result['title'];
			$channel_descr = $rss_result['description'];
			$rss = '1';
		}
		else
		{
			$channel_title =    $rss_result['html'];
			$channel_descr = $rss_result['html'];
			$rss = '0';
		}
		if ($rss_result['charset'] != '') $_POST['charset'] = $rss_result['charset'];
		if ($rss == '1'){
			$start_title = '';
			$start_short = '';
			$sart_link = '';
			$ctp = '';
		}
		$dop_nast = intval ($_POST['dop_watermark']).'='.intval ($_POST['text_url']).'='.intval ($_POST['proxy']).'='.intval ($_POST['x']).'='.intval ($_POST['y']).'='.intval ($_POST['show_autor']).'='.$db->safesql ($_POST['show_tegs']).'='.intval ($_POST['show_date']).'='.intval ($_POST['show_code']).'='.intval ($_POST['show_f']).'='.intval ($_POST['null']).'='.intval ($_POST['one_serv']).'='.intval ($_POST['margin']).'='.intval ($_POST['show_down']).'='.$_POST['charset'].'='.intval ($_POST['dubl_host']).'='.intval ($_POST['text_url_sel']).'='.intval ($_POST['parse_url_sel']).'='.intval ($_POST['full_url_and']).'='.intval ($_POST['grab_pause']).'='.intval ($_POST['step_page']).'='.intval ($_POST['add_pause']).'='.intval ($_POST['kol_short']).'='.intval ($_POST['page_break']).'='.$db->safesql ($_POST['sim_short']);
		if ($stop == false)
		{
			$sql_result = $db->query ('SELECT url FROM '.PREFIX .'_rss');
			$pnum = $db->num_rows ($sql_result) +1;
			$channel_title = $db->safesql ($channel_title);
			$channel_descr = $db->safesql ($channel_descr);
			$sql_query = 'INSERT INTO '.PREFIX ."_rss (url, title, descr, category, allow_main, allow_comm, allow_rate, allow_auto, load_img, allow_more, start_template, finish_template, cookies, allow_watermark, date_format, keywords, Autors, thumb_img, allow_mod, stkeywords, rss, ful_start, start_title, start_short, end_short, sart_link, end_link, sart_cat, xdescr, xpos, delate, inser, start, finish, end_title, short_story, dop_nast, ctp, full_link, date, dnast, symbol, ftags, metatitle, meta_descr, key_words, kategory, xfields_template, dop_full) VALUES ('$rss_url', '$channel_title', '$channel_descr', '$category', '$allow_main', '$allow_comm', '$allow_rate', '$auto', '$load_images', '$allow_more', '$start_template', '$finish_template', '$cookies', '$allow_watermark', '$date_format', '$keywords', '$Autors', '$thumb_images', '$allow_mod', '$stkeywords', '$rss', '$ful_start', '$start_title', '$start_short', '$end_short', '$sart_link', '$end_link', '$sart_cat', '$xdescr', '$pnum', '$delate', '$inser', '$start', '$finish', '$end_title', '$short_story', '$dop_nast', '$ctp', '$full_link', '$date', '$dnast', '$symbol', '$ftags', '$metatitle', '$meta_descr', '$key_words', '$kategory', '$xfields_template' ,'$dop_full')";
			$db->query ($sql_query);
			$rss_id = $db->insert_id();
			if (trim ($channel_title) != '')
			{
				$title = stripslashes (strip_tags ($channel_title));
				if (50 <strlen ($title))
				{
					$title = substr ($title,0,50) .'...';
				}
			}
			else
			{
				$title = $lang_grabber['no_title'];
			}
			if ($rss == 1){
				$mgs = $lang_grabber['channel'].' №'.$pnum.' <font color="green">"'.$title.' | '.$rss_url.'"</font> <font color="red">'.$lang_grabber['add_msg_rss'].'</font><br />';
				msg ($lang_grabber['info'],$lang_grabber['add_channel_ms'],$mgs.((count($inf)!=0)?'<br />* * *<br /><b style="color:#ff0000;">'.$lang_grabber['add_msg_er'].'</b><br /><br /><a class="list" href="admin.php?mod=rssaction=channel&subaction=edit&id='.$inf['id'].'"><b style="color:blue;">'.$inf['title'].'</b></a>':''),$PHP_SELF .'?mod=rss');
				return 1;
			}else{
				$mgs = $lang_grabber['channel'].' № <b>'.$pnum.'</b> => <font color="green">"'.$title.' | '.$rss_url.'"</font> <font color="red">'.$lang_grabber['add_msg_html'].'</font><br />';
				msg ($lang_grabber['info'],$lang_grabber['add_channel_ms'],$mgs.((count($inf)!=0)?'<br />* * *<br /><b style="color:#ff0000;">'.$lang_grabber['add_msg_er'].'</b><br /><br /><a class="list" href="admin.php?mod=rss&action=channel&subaction=edit&id='.$inf['id'].'"><b style="color:blue;">'.$inf['title'].'</b></a>':''),$PHP_SELF .'?mod=rss');
				$db->close;
				return 1;
			}
		}
	}
	if (($action == 'channel') and ($subaction == 'do_change')){
		if (isset ($id))
		{
			$stop = false;
			if (!((!(trim ($id) == '') AND !($id == 0))))
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['channel_msg_id'],$PHP_SELF .'?mod=rss');
				return 1;
			}
			$row = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$id'");
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
			$dnast = intval ($_POST['image_align']).'='.intval ($_POST['image_align_full']).'='.intval ($_POST['show_symbol']).'='.intval ($_POST['show_metatitle']).'='.intval ($_POST['show_metadescr']).'='.intval ($_POST['show_keywords']).'='.intval ($_POST['show_url']).'='.intval ($_POST['rss_parse']).'='.intval ($_POST['tags_auto']).'='.intval ($_POST['auto_metatitle']).'='.intval ($_POST['data_deap']).'='.intval ($_POST['deap']).'='.intval ($_POST['auto_symbol']).'='.intval ($_POST['auto_numer']).'='.intval ($_POST['show_date_expires']).'='.intval ($_POST['wat_host']).'='.intval ($_POST['cron_auto']).'='.intval ($_POST['rewrite_data']).'='.intval ($_POST['ret_xf']).'='.intval ($_POST['kol_cron']).'='.$db->safesql ($_POST['tags_kol']);
			$short_story = intval ($_POST['clear_short']).'='.intval ($_POST['short_img']).'='.intval ($_POST['short_full']).'='.intval ($_POST['sinonim']).'='.intval ($_POST['pings']).'='.$db->safesql ($_POST['teg_fix']).'='.intval ($_POST['cat_nul']).'='.intval ($_POST['keyw_sel']).'='.intval ($_POST['log_pas']).'='.intval ($_POST['text_html']).'='.intval ($_POST['descr_sel']).'='.intval ($_POST['title_prob']).'='.intval ($_POST['no_prow']).'='.intval ($_POST['lang_on']).'='.$db->safesql ($_POST['lang_in']).'='.$db->safesql ($_POST['lang_out']).'='.intval ($_POST['cat_sp']).'='.intval ($_POST['clear_full']).'='.$db->safesql ($_POST['lang_outf']).'='.intval ($_POST['sinonim_sel']).'='.intval ($_POST['add_full']);
			$ctp = intval ($_POST['so']).'='.intval ($_POST['po']);
			$full_link = stripslashes ($_POST['full_link']);
			$date = $db->safesql(trim($_POST['date'])).'='.intval ($_POST['dim_week']).'='.intval ($_POST['dim_date']).'='.intval ($_POST['dim_sait']).'='.intval ($_POST['dim_cat']);
			$original_rss_url = $row['url'];
			$rss_url = $db->safesql ($_POST['rss_url']);
			$rss = intval ($_POST['rss_html']);
			if (trim ($rss_url) == '')
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['error_url'],$PHP_SELF .'?mod=rss&action=channel&subaction=add');
				return 1;
			}
			if (!(ereg ('http://',$rss_url)) or reset_url($rss_url) == '')
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['error_url_msg'],$PHP_SELF .'?mod=rss&action=channel&subaction=add');
				return 1;
			}
			if ($original_rss_url != $rss_url)
			{
				$inf = $db->super_query ('SELECT title FROM '.PREFIX ."_rss WHERE url = '".trim($rss_url)."'");
				$rss_result = get_rss_channel_info ($rss_url,intval ($_POST['proxy']),$_POST['charset']);
				if ($rss_result['title'] != '')
				{
					$channel_title = $rss_result['title'];
					$channel_descr = $rss_result['description'];
					$rss = '1';
				}
				else
				{
					$channel_title = $rss_result['html'];
					$channel_descr = $rss_result['html'];
					$rss = '0';
				}
			}
			else
			{
				$channel_title = $db->safesql($_POST['rss_title']);
			}
			if ($stop == false)
			{
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
				$dop_nast = intval ($_POST['dop_watermark']).'='.intval ($_POST['text_url']).'='.intval ($_POST['proxy']).'='.intval ($_POST['x']).'='.intval ($_POST['y']).'='.intval ($_POST['show_autor']).'='.intval ($_POST['show_tegs']).'='.intval ($_POST['show_date']).'='.intval ($_POST['show_code']).'='.intval ($_POST['show_f']).'='.intval ($_POST['null']).'='.intval ($_POST['one_serv']).'='.intval ($_POST['margin']).'='.intval ($_POST['show_down']).'='.$_POST['charset'].'='.intval ($_POST['dubl_host']).'='.intval ($_POST['text_url_sel']).'='.intval ($_POST['parse_url_sel']).'='.intval ($_POST['full_url_and']).'='.intval ($_POST['grab_pause']).'='.intval ($_POST['step_page']).'='.intval ($_POST['add_pause']).'='.intval ($_POST['kol_short']).'='.intval ($_POST['page_break']).'='.$db->safesql ($_POST['sim_short']);
				$end_title = $db->safesql ($_POST['s_title']).'=='.$db->safesql ($_POST['end_title']).'=='.$stitles.'=='.$ftitles.'=='.$db->safesql ($_POST['link_start_del']).'=='.$db->safesql ($_POST['link_finish_del']);
				$start_short = $db->safesql ($_POST['start_short']);
				$end_short = intval ($_POST['end_short']).'='.intval ($_POST['hide']).'='.intval ($_POST['leech']).'='.intval ($_POST['rewrite_news']);
				$sart_link = $db->safesql ($_POST['sart_link']);
				$end_link = $db->safesql ($_POST['end_link']);
				$sart_cat = $db->safesql ($_POST['sart_cat']).'|||'.$db->safesql ($_POST['shab_data']).'|||'.$db->safesql ($_POST['zhv_code']);
				$end_cat = $db->safesql ($_POST['end_cat']);
				$dop_full = $db->safesql ($_POST['dop_full']);
				if ($rss == '1'){
					$start_title = '';
					$start_short = '';
					$sart_link = '';
					$ctp = '';
				}
				for ($x=0;$x++<$_POST['kol_xfields'];){
					if (trim($_POST['xfields_template_'.$x]) != '')$templ[] = $xfields_template = $db->safesql ($_POST['rss_xfields_'.$x]).'=='.$db->safesql ($_POST['xfields_template_'.$x]).'=='.intval ($_POST['ret_xf_'.$x]).'=='.intval ($_POST['sh_fl_'.$x]).'=='.intval ($_POST['sh_im_'.$x]).'=='.$db->safesql ($_POST['xfields_delete_'.$x]).'=='.$db->safesql ($_POST['xfields_insert_'.$x]).'=='.$db->safesql ($_POST['rs_im_'.$x]);
				}
				if($templ)$xfields_template = implode ('|||',$templ);
				$db->query( 'UPDATE '.PREFIX ."_rss SET title = '$channel_title', descr = '$channel_descr', rss = '$rss',category='$category', allow_main = '$allow_main', allow_comm = '$allow_comm', allow_auto = '$allow_auto', allow_more ='$allow_more', allow_rate ='$allow_rate', cookies ='$cookies', start_template ='$start_template', finish_template ='$finish_template', delate = '$delate', load_img ='$allow_load', url ='$rss_url', allow_watermark ='$allow_water', date_format ='$date_format', keywords ='$keywords', Autors ='$Autors', thumb_img ='$thumb_images', allow_mod ='$allow_mod', stkeywords ='$stkeywords', ful_start='$ful_start', start_title='$start_title', start_short='$start_short', end_short='$end_short', sart_link='$sart_link', end_link='$end_link', sart_cat='$sart_cat', xdescr='$xdescr', inser='$inser', start='$start', finish='$finish', end_title = '$end_title', end_link = '$end_link', short_story='$short_story', dop_nast='$dop_nast', full_link='$full_link', ctp='$ctp', date= '$date', dnast='$dnast', symbol='$symbol', ftags='$ftags', metatitle='$metatitle', meta_descr='$meta_descr', key_words='$key_words', kategory='$kategory', xfields_template='$xfields_template', dop_full='$dop_full' WHERE id ='$id'");
				if (trim ($channel_title) != '')
				{
					$title = stripslashes (strip_tags ($channel_title));
					if (50 <strlen ($title))
					{
						$title = substr ($title,0,50) .'...';
					}
				}
				else
				{
					$title = $lang_grabber['no_title'];
				}
				$mgs = $lang_grabber['channel'].' <font color="green">"'.$title.' | '.$rss_url.'"</font> <font color="red">'.$lang_grabber['edit_channel_ok'].'</font><br />';
				msg ($lang_grabber['info'],$lang_grabber['change_channel'],$mgs.($inf?'<br />* * *<br /><b style="color:#ff0000;">'.$lang_grabber['add_msg_er'].'</b><br /><br /><a class="list" href="admin.php?mod=rss&action=channel&subaction=edit&id='.$inf['id'].'"><b style="color:blue;">'.$inf['title'].'</b></a>':''),$PHP_SELF .'?mod=rss');
				$db->close;
				return 1;
			}
		}
	}
	if (($action == 'channel') and ($subaction == 'edit')){
		if (isset ($id))
		{
			if (!((!(trim ($id) == '') AND !($id == 0))))
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['channel_msg_id'],$PHP_SELF .'?mod=rss');
				return 1;
			}
			$rss_channel_info = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$id'");
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
			if (trim ($rss_channel_info['title']) != '')
			{
				$channel_name = '</br> <font color=green> №'.$rss_channel_info['xpos'].' - '.stripslashes ($rss_channel_info['title']) .'</font> (<font color=red>'.stripslashes ($rss_channel_info['url']) .'</font>) <a href="'.$rss_channel_info['url'] .'" target="_blank">[i]</a>';
			}
			$channel_inf = array();
			$sql_result = $db->query ('SELECT * FROM '.PREFIX .'_rss_category ORDER BY kanal asc');
			$run[0] = '';
			while ($channel_info = $db->get_row($sql_result)) {
				if ($channel_info['osn'] == '0')$channel_inf[$channel_info['id']][$channel_info['id']] =  $channel_info['title'];
				else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- '.$channel_info['title'];
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
			opentable ('<a href='.$PHP_SELF .'?mod=rss>'.$lang_grabber['index_page'] .$lang_grabber['change_channel'] .$channel_name);
			if (trim($rss_channel_info['finish_template']) == ''){
				$tpl->load_template ('rss_addchannel.tpl');
			}else{$tpl->load_template ('old_rss_addchannel.tpl');}
			$tpl->set ('{rss_html}',yesno ($rss_channel_info['rss'] == 0 ?'no': 'yes'));
			$tpl->set ('{stkeywords}',$stkeywords);
			$tpl->set ('{charsets}',$dop_nast[14]);
			$tpl->set ('{dubl-host}',yesno ($dop_nast[15] == 0 ?'no': 'yes'));
			$tpl->set ('{one-serv}',yesno ($dop_nast[11] == 0 ?'no': 'yes'));
			$tpl->set ('{title}',$rss_channel_info['title']);
			$tpl->set ('{discr}',stripslashes ($rss_channel_info['descr']));
			$tpl->set ('{address}',stripslashes ($rss_channel_info['url']));
			$tpl->set ('{date-format}',gen_date_format ($rss_channel_info['date_format']));
			$tpl->set ('{category}',categorynewsselection (explode(',',$categoryes[0]),0));
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
			$tpl->set ('{cron-auto}',$dnast[16]);
			$tpl->set ('{kol-cron}',$dnast[19]);
			$tpl->set ('{tags-kol}',$dnast[20]);
			$tpl->set ('{rss-parse}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full'],'3'=>$lang_grabber['thumb_shortfull']),$dnast[7]));
			$tpl->set ('{tags-auto}',yesno ($dnast[8] == 0 ?'no': 'yes'));
			$tpl->set ('{auto-metatitle}',yesno ($dnast[9] == 0 ?'no': 'yes'));
			$tpl->set ('{data-deap}',$dnast[10]);
			$tpl->set ('{deap}',deap ($dnast[11] == 0 ?'yes': 'no'));
			$tpl->set ('{symbol}',$rss_channel_info['symbol']);
			$tpl->set ('{auto-symbol}',yesno ($dnast[12] == 0 ?'no': 'yes'));
			$tpl->set ('{auto-numer}',sel (array(1 =>'1',2 =>'2',3 =>'3'),$dnast[13] ));
			$tpl->set ('{show_date_expires}',yesno ($dnast[14] == 0 ?'no': 'yes'));
			$tpl->set ('{tags}',$rss_channel_info['ftags']);
			$tpl->set ('{meta-title}',$rss_channel_info['metatitle']);
			$tpl->set ('{meta-descr}',$rss_channel_info['meta_descr']);
			$tpl->set ('{key-words}',$rss_channel_info['key_words']);
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
			$tpl->set ('{no-prow}',dubl_news ($short_story[12]));
			$tpl->set ('{grab-pause}',$dop_nast[19]);
			$tpl->set ('{add-pause}',$dop_nast[21]);
			$tpl->set ('{kol-short}',$dop_nast[22]);
			$tpl->set ('{sim-short}',$dop_nast[24]);
			$tpl->set ('{page-break}',$dop_nast[23]);
			$tpl->set ('{image-align}',gen_x ($dnast[0]));
			$tpl->set ('{image-align-full}',gen_x ($dnast[1]));
			$tpl->set ('{start-template}',str_replace ('&','&amp;',stripslashes ($rss_channel_info['start_template'])));
			$tpl->set ('{end-template}',str_replace ('&','&amp;',stripslashes ($rss_channel_info['finish_template'])));
			$tpl->set ('{x}',gen_x ($dop_nast[3]));
			$tpl->set ('{y}',gen_y ($dop_nast[4]));
			$tpl->set ('{delate}',stripslashes ($delate));
			$tpl->set ('{inser}',stripslashes ($inser));
			$tpl->set ('{start}',stripslashes ($start));
			$tpl->set ('{finish}',stripslashes ($finish));
			$tpl->set ('{full-link}',stripslashes ($rss_channel_info['full_link']));
			$tpl->set ('{dop-full}',stripslashes ($rss_channel_info['dop_full']));
			$tpl->set ('{so}',$ctp[0]);
			$tpl->set ('{po}',$ctp[1]);
			$tpl->set ('{dop-watermark}',yesno ($dop_nast[0] == 0 ?'no': 'yes'));
			$tpl->set ('{add-full}',yesno ($short_story[20] == 0 ?'no': 'yes'));
			$tpl->set ('{lang-on}',yesno ($short_story[13] == 0 ?'no': 'yes'));
			$tpl->set ('{lang-in}',slected_lang($short_story[14] == ''?'ru': $short_story[14]));
			$tpl->set ('{lang-out}',slected_lang ($short_story[15] == ''?'en': $short_story[15]));
			$tpl->set ('{lang-in}',slected_lang($short_story[14] == ''?'ru': $short_story[14]));
			$tpl->set ('{lang-outf}',slected_lang ($short_story[18] == ''?'': $short_story[18]));
			$tpl->set ('{cat-sp}',yesno ($short_story[16] == 0 ?'no': 'yes'));
			$tpl->set ('{text-url-sel}',sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$dop_nast[16]));
			$tpl->set ('{full-url-and}',yesno ($dop_nast[18] == 0 ?'no': 'yes'));
			$tpl->set ('{parse-url-sel}',sel (array ('0'=>$lang_grabber['no_thumb'],'1'=>$lang_grabber['thumb_full'],'2'=>$lang_grabber['thumb_short'],'3'=>$lang_grabber['thumb_shortfull']),$dop_nast[17]));
			$tpl->set ('{log-pas}',yesno ($short_story[8] == 0 ?'no': 'yes'));
			$tpl->set ('{keyw-sel}',sel (array(0 =>$lang_grabber['sel_shortfull'],1 =>$lang_grabber['sel_short'],2 =>$lang_grabber['sel_full'],3 =>$lang_grabber['sel_short_full'],4 =>$lang_grabber['sel_no_gener']),$short_story[7]));
			$tpl->set ('{descr-sel}',sel (array(0 =>$lang_grabber['sel_shortfull'],1 =>$lang_grabber['sel_short'],2 =>$lang_grabber['sel_full'],3 =>$lang_grabber['sel_short_full'],4 =>$lang_grabber['sel_no_gener']),$short_story[10]));
			$tpl->set ('{text-url}',sel (array(0 =>$lang_grabber['no_izm'],1 =>$lang_grabber['url_klik'],2 =>$lang_grabber['url_no_donw'],3 =>$lang_grabber['url_no_donor']),$dop_nast[1]));
			$tpl->set ('{prox}',yesno ($dop_nast[2] == 0 ?'no': 'yes'));
			$tpl->set ('{null}',yesno ($dop_nast[10] == 0 ?'no': 'yes'));
			$tpl->set ('{load-img}',server_host($rss_channel_info['load_img']));
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
			$tpl->set ('{end-del}',stripslashes ($ftitles));
			$tpl->set ('{s-del}',stripslashes ($stitles));
			$tpl->set ('{start-short}',stripslashes ($rss_channel_info['start_short']));
			$tpl->set ('{end-short}',yesno ($hide_leech[0] == 0 ?'no': 'yes'));
			$tpl->set ('{sart-link}',stripslashes ($rss_channel_info['sart_link']));
			$tpl->set ('{step-page}',$dop_nast[20]);
			$tpl->set ('{end-link}',yesno ($rss_channel_info['end_link'] == 0 ?'no': 'yes'));
			$sart_cat = explode('|||',$rss_channel_info['sart_cat']);
			$tpl->set ('{sart-cat}',stripslashes ($sart_cat[0]));
			$tpl->set ('{shab-data}',stripslashes ($sart_cat[1]));
			$tpl->set ('{zhv-code}',stripslashes ($sart_cat[2]));
			$tpl->set ('{end-cat}',stripslashes ($rss_channel_info['end_cat']));
			$tpl->set ('{date}',stripslashes ($date[0]));
			$tpl->set ('{cookies}',$cookies);
			$tpl->set ('{keywords}',$keywords);
			$tpl->set ('{Autors}',$Autors);
			$tpl->set ('{kategory}',$kategory);
			$xfields_template = explode ('|||',$rss_channel_info['xfields_template']);
			$template = '';
			$x= 1;
			foreach ($xfields_template as $value){
				if ($value != ''){
					$key = explode ('==',$value);
					$template .= '
<div><a href="javascript:ShowOrHide(\'full_'.$x.'\');"><center><b>Дополнительное поле '.$x.' +/-</b></center></a></div>
<div id="full_'.$x.'" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><center><b>Дополнительное поле '.$x.'</b></center></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_'.$x.'" class="load_img">
    '.sel (rss_xfields('1'),$key[0]).'
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
   Размер изображение
     <input name="rs_im_'.$x.'" class="load_img" type="text" size="10" value="'.$key[7].'">
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_'.$x.'">'.stripslashes($key[1]).'</textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>'.$lang_grabber['templates_search_regular'].' <b>в дополнительном поле</b></center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['expression'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_'.$x.'">'.stripslashes($key[5]).'</textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['paste'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_'.$x.'">'.stripslashes($key[6]).'</textarea>
   </td></tr>
</table>
</div>
';
					$x++;
				}
			}
			$template .=    '

<div><a href="javascript:ShowOrHide(\'full_'.$x.'\');"><center><b>Дополнительное поле '.$x.' +/-</b></center></a></div>
<div id="full_'.$x.'" style="display:none">
<table cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><center><b>Дополнительное поле '.$x.'</b></center></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">'.$lang_grabber['rss_xfields'].'
   <select name="rss_xfields_'.$x.'" class="load_img">
    '.sel (rss_xfields('1'),'').'
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
   Размер изображение
     <input name="rs_im_'.$x.'" class="load_img" type="text" size="10" value="">
 </td>
  </tr>
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">'.$add_bb.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="xfields_template_'.$x.'"></textarea>
   </td></tr>
</table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>'.$lang_grabber['templates_search_regular'].' <b>в дополнительном поле</b></center></td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['expression'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_delete_'.$x.'"></textarea>
   </td></tr>
</table>


   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" >'.$lang_grabber['paste'].'</td>
   <td width="83%" style="padding:4px">'.$add_bbz.'<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:50px" name="xfields_insert_'.$x.'"></textarea>
   </td></tr>
</table>
</div>
';
			$tpl->set ('{kol-xfields}',$x);
			$tpl->set ('{xfields-template}',$template);
			if (@file_exists ($rss_plugins.'sinonims.php') )
			{
				$sin =  '
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">'.$lang_grabber['sinonims'].':</td>
   <td width="768" style="padding:4px">
   <select name="sinonim" class="load_img">
'.yesno ($short_story[3] == 0 ?'no': 'yes').'
   </select>
   <select name="sinonim_sel" class="load_img">'.
				sel (array ('0'=>$lang_grabber['thumb_shortfull'],'1'=>$lang_grabber['thumb_short'],'2'=>$lang_grabber['thumb_full']),$short_story[19]).'
   </select>
  </td>
  </tr>';
			}
			$tpl->set ('{sinonim}',$sin);
			foreach ($lang_grabber as $key =>$value){$tpl->set ('{'.$key.'}',$value);}
			$form = '   <form method="post">
    <input type="hidden" name="id" value="'.$id .'" />
    <input type="hidden" name="action" value="channel" />
    <input type="hidden" name="subaction" value="do_change" />';
			include_once ($rss_plugins.'inserttag.php');
			include_once ($rss_plugins.'inserttag.php');
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
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'   border='0' /></div>
";
			$tpl->set ('{BB_code}',$add_bb);
			$tpl->set ('{BB_codez}',$add_bbz);
			$tpl->copy_template = $form .$tpl->copy_template .'
            <input align="left" class="edit" type="submit"  value=" '.$lang_grabber['save'].' " >&nbsp;
            <input type="button"    class="edit" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /></form>';
			$tpl->compile ('rss');
			echo $tpl->result['rss'];
			closetable ();
			echofooter ();
			$db->close;
			return 1;
		}
	}
	if ($action == 'save')
	{
		echo "<script>

        function storyes ( id, key )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');

var varsString = 'key1=' + id;

        ajax.setVar(\"key\", key);
        ajax.requestFile ='engine/ajax/storyes.php';
        ajax.element = 'progressbar';
        ajax.sendAJAX(varsString);
return false;
    }

    </script>";
		echo "<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif' border='0' /></div>";
		if ($_POST['channels'])
		{
			foreach ($_POST['channels'] as $channel_id)
			{
				$news_per_channel = intval ($_POST['news-per-channel-'.$channel_id]);
				$channel_info = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id ='$channel_id'");
				$dop_nast = explode ('=',$channel_info['dop_nast']);
				$dates = explode ('=',$channel_info['date']);
				$dnast = explode ('=',$channel_info['dnast']);
				$dop_sort = explode ('=',$channel_info['short_story']);
				$n = 1;
				for ($di = 1;$di <$news_per_channel;++$di)
				{
					if (intval($dop_nast[21]) != 0) sleep ($dop_nast[21]);
					$news_selected = $_POST['sel_'.$di.$channel_id];
					if ($news_selected == 1)
					{
						$tegs =$_POST['tags_'.$di.$channel_id];
						if ( count($_POST['category'.$di.$channel_id.'_']))
						{
							$category = $db->safesql( implode( ',',$_POST['category'.$di.$channel_id.'_'] ) );
						}else{$category = '0';}
						$title = $_POST['title_'.$di.$channel_id];
						$_POST['title'] = html_entity_decode($title);
						$alt_name = totranslit ($title);
						$short_story = $_POST['short_'.$di.$channel_id];
						$full_story = $_POST['full_'.$di.$channel_id];
						$sinonims_val = ($_POST['sinonims_'.$di.$channel_id] == 1 ?1 : 0);
						$rewrite = ($_POST['rewrite_'.$di.$channel_id] == 1 ?1 : 0);
						$approve = ($_POST['mod_'.$di.$channel_id] == 1 ?1 : 0);
						$allow_comm = ($_POST['comm_'.$di.$channel_id] == 1 ?1 : 0);
						$allow_main = ($_POST['main_'.$di.$channel_id] == 1 ?1 : 0);
						$allow_rate = intval ($channel_info['allow_rate']);
						$allow_more = intval ($channel_info['allow_more']);
						$added_time =   $_POST['date-from-channel_'.$di.$channel_id];
						$news_full_link = $_POST['news-full-link_'.$di.$channel_id];
						$serv = $_POST['serv_'.$di.$channel_id];
						$full_news_link = $_POST['news_link_'.$di.$channel_id];
						$radikal = ($_POST['radikal_'.$di.$channel_id]== 1 ?1 : 0);
						$xfield = $_POST['xfield'.$di.$channel_id.'_'];
						$author = $db->safesql ($_POST['autor_'.$di.$channel_id]);
						$meta_title = $db->safesql ($_POST['meta_title'.$di.$channel_id]);
						$descr = $db->safesql ($_POST['descr'.$di.$channel_id]);
						$keywords = $db->safesql ($_POST['keywords'.$di.$channel_id]);
						$mets = ($_POST['met_'.$di.$channel_id] == 1 ?1 : 0);
						$expires = $_POST['expires_'.$di.$channel_id];
						$dimages = '';
						if ($mets == 1)$meta_title = $db->safesql ($meta_title.' '.$title);else $meta_title = $db->safesql ($meta_title);
						if ($_POST['alt_name'.$di.$channel_id] != '')$alt_name = totranslit( stripslashes( $_POST['alt_name'.$di.$channel_id] ),true,false );
						else $alt_name = $db->safesql (totranslit( stripslashes( $title ),true,false ));
						$catalog_url = $db->safesql( substr( htmlspecialchars( strip_tags( stripslashes( trim( $_POST['symbol'.$di.$channel_id] ) ) ) ),0,3 ) );
						$stop = false;
						if ($category =='0'and $dop_sort[6] == 1) $stop = true;
						if (trim ($title) != ''and !$stop){
							$safeTitle = $db->safesql ($title);
							if ($dop_sort[12] == 0) {$where = ' LIMIT 1';}
							elseif ($dop_sort[12] == 1 and $full_news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($full_news_link)."%'";}
							elseif ($dop_sort[12] == 2) {$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
							elseif ($dop_sort[12] == 3 and $full_news_link != '') {$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."' or xfields like '%".$db->safesql ($full_news_link)."%'";}
							else {$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
							$sql_Title = $db->query('SELECT * FROM '.PREFIX .'_post'.$where);
							if ($db->num_rows ($sql_Title) == 0 or $rewrite == 1 or $dop_sort[12] == 0)
							{
								if( $config['safe_xfield'] ) {
									$parse->ParseFilter();
									$parse->safe_mode = true;
								}
								if( !empty( $xfield )) {
									foreach ( $xfield as $xfielddataname =>$xfielddatavalue ) {
										if( $xfielddatavalue == '') {
											continue;
										}
										$xfields_im = false;
										$xfte = array();
										$xfte = explode ('|||',$channel_info['xfields_template']);
										foreach($xfte as $value)
										{
											$key = explode ('==',$value);
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
											if ($dates[0] != ''and strlen($dates[0]) != 10){
												$di_control->post = '/'.$dates[0] ;
											}
											if (intval($key[7]) != 0)$di_control->post .= '/th_post';
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
										if (in_array ($xfielddataname ,$config_code_bb) and @file_exists ($rss_plugins.'sinonims.php'))
										{
											include_once($rss_plugins.'sinonims.php');
											preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#i",$xfielddatavalue,$nosinonims);
											foreach ($nosinonims[1] as $key =>$value){
												$noss['nosinonims_'.$key] = $value;
											}
											if (count($noss) != '')$xfielddatavalue=strtr ($xfielddatavalue,array_flip($noss));
											if (preg_match('/\[sin\]/',$xfielddatavalue)){
												$xfielddatavalue =preg_replace ("#\[sin\](.+?)\[\/sin\]#ie","sinonims('\\1')",$xfielddatavalue);
											}else{$xfielddatavalue = sinonims ($xfielddatavalue);}
											if (count($noss) != '')$xfielddatavalue=strtr ($xfielddatavalue,$noss);
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
									$xfields .= '||source_name|'.$channel_info['title'] .'||source_link|'.$full_news_link;
									$xfields = trim($xfields,'||');
									$xfields = $db->safesql($xfields);
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
									$URL = get_urls(trim($channel_info['url']));
									$tit = $URL['host'];
								}
								if ($db->num_rows ($sql_Title) >0 and $rewrite == 1){
									while ( $row = $db->get_row($sql_Title) ) {
										$news_id = $row['id'];
										$author = $row['autor'];
									}
								}
								if ($serv != '0'or $dop_nast[11] == 1)
								{
									$db->close;
									$xdoe = array();
									$di_control = new image_controller ();
									$di_control->post = '';
									$di_control->short_story = $short_story;
									$di_control->full_story = $full_story;
									$di_control->proxy = $dop_nast[2];
									$di_control->dubl =$dop_nast[15];
									if ($dates[0] != ''and strlen($dates[0]) != 10){$di_control->post = '/'.$dates[0] ;}
									if ($dates[1] == 1 )$di_control->dim_week = $alt_name;
									$di_control->dim_date =$dates[2];
									$di_control->dim_sait =$dates[3];
									$di_control->dim_cat =$dates[4];
									$di_control->wat_h =$dnast[15];
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
									echo"
        <div id=\"progressbar\"></div>

        <script>
storyes($skey, $result);
    </script>";
									if ($dop_nast[11] == 1)$di_control->shs = true;
									$pro = $di_control->process($serv);
									if (count($pro) != 0) {
										$xdoe[] = '<b><i>'.$tit.'</i> &#x25ba; '.$title.'</b><br />'.implode('<br />',$pro);
									}
									$short_story = $di_control->short_story;
									$full_story = $di_control->full_story;
									if (count ($di_control->upload_images) != 0)
									{
										$folder_prefix = trim($di_control->post.$di_control->pap_data,'/');
										$dim = '|||'.$folder_prefix.'/';
										$dimage = implode ($dim,$di_control->upload_images);
										$dimages .= '|||'.$db->safesql ($folder_prefix.'/'.$dimage);
									}
								}
								$dimages = trim($dimages ,'|||');
								$short_story = add_short ($short_story);
								$full_story = add_full ($full_story);
								$short_story =relace_news_don ($short_story);
								$full_story = relace_news_don ($full_story);
								if (@file_exists ($rss_plugins.'sinonims.php') )
								{
									include_once($rss_plugins.'sinonims.php');
									preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#is",$short_story,$nosinonimsshort_story);
									foreach ($nosinonimsshort_story[1] as $key =>$value){
										$nossshort_story['nosinonims_'.$key] = $value;
									}
									if (count($nossshort_story) != '')$short_story=strtr ($short_story,array_flip($nossshort_story));
									if (preg_match('/\[sin\]/',$short_story)){
										$short_story =preg_replace ("#\[sin\](.+?)\[\/sin\]#ise","sinonims('\\1')",$short_story);
									}else{
										if ($dop_sort[3] == 1 and $sinonims_val == 1 and ($dop_sort[19] == 0 or $dop_sort[19] == 1 ))$short_story = sinonims ($short_story);
									}
									if (count($nossshort_story) != '')$short_story=strtr ($short_story,$nossshort_story);
									preg_match_all ("#\[nosin\](.+?)\[\/nosin\]#is",$full_story,$nosinonimsfull_story);
									foreach ($nosinonimsfull_story[1] as $key =>$value){
										$nossfull_story['nosinonims_'.$key] = $value;
									}
									if (count($nossfull_story) != '')$full_story=strtr ($full_story,array_flip($nossfull_story));
									if (preg_match('/\[sin\]/',$full_story)){
										$full_story =preg_replace ("#\[sin\](.+?)\[\/sin\]#ise","sinonims('\\1')",$full_story);
									}else{
										if ($dop_sort[3] == 1 and $sinonims_val == 1 and ($dop_sort[19] == 0 or $dop_sort[19] == 2 ))$full_story = sinonims ($full_story);
									}
									if (count($nossfull_story) != '')$full_story=strtr ($full_story,$nossfull_story);
								}
								if (trim($short_story) != ''and trim($title) != ''and (trim($full_story) != ''or $dop_sort[17] == 1 or intval($dop_sort[20]) == 0)) {
									$short_story=strtr($short_story,array('[sin]'=>'','[/sin]'=>'','[nosin]'=>'','[/nosin]'=>'','biggrab '=>''));
									$full_story =strtr($full_story,array('[sin]'=>'','[/sin]'=>'','[nosin]'=>'','[/nosin]'=>'','biggrab '=>''));
									$title = stripslashes($title);
									$short_story = stripslashes($short_story);
									$full_story = stripslashes($full_story);
									$title = $db->safesql($parse->process($title));
									if ($config_rss['create_images'] == 1 or $config_rss['create_images'] == 3){
										$short_story = $db->safesql($parse->BB_Parse(create_images($parse->process($short_story) ,$title) ,false));
									}else{
										$short_story = $db->safesql($parse->BB_Parse($parse->process($short_story) ,false));
									}
									if ($config_rss['create_images'] == 2 or $config_rss['create_images'] == 3){
										$full_story = $db->safesql($parse->BB_Parse(create_images($parse->process($full_story) ,$title) ,false));
									}else{
										$full_story = $db->safesql($parse->BB_Parse($parse->process($full_story) ,false));
									}
									$news_read = rand(intval($config_rss['rate_start']),intval($config_rss['rate_finish']));
									if($allow_rate == 1){$vote_num = rand(0,$news_read);
									$rating = rand($vote_num*3,$vote_num*5);
									}
									$short_story = str_replace ('&#111;','o',$short_story );
									$full_story = str_replace ('&#111;','o',$full_story );
									$safet = $parse->decodeBBCodes($_POST['title']);
									$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
									$date_time = strtotime ($added_time);
									if ($db->num_rows ($sql_Title) >0 and $rewrite == 1){
										if ($config['version_id'] >='7.2')$tes = ", tags='".$db->safesql($tegs)."'";
										if ($config['version_id'] >'8.0')$fgs = ", metatitle='$meta_title', symbol='$catalog_url'";
										$result = $db->query( 'UPDATE '.PREFIX ."_post set date='$added_time', title='$title', short_story='$short_story', full_story='$full_story', descr='$descr', keywords='$keywords', category='$category', alt_name='$alt_name', allow_comm='$allow_comm', approve='$approve', allow_main='$allow_main',  xfields='$xfields' $tes $fgs WHERE id='$news_id'");
										$db->query ('UPDATE '.PREFIX ."_users SET lastdate = '$date_time' WHERE name ='$author'");
										$db->query('UPDATE '.PREFIX ."_images SET images='$dimages', date='$date_time' WHERE news_id ='$news_id'");
									}else{
										if ($config['version_id'] >='7.2'){$te =", '".$db->safesql($tegs)."'";$tes = ', tags';}
										if ($config['version_id'] >'8.0'){$fgrs = ", '$meta_title', '$catalog_url'";$fgs = ', metatitle, symbol';}
										$db->query ( 'INSERT INTO '.PREFIX ."_post (autor, category, date, title, alt_name, short_story, full_story, xfields, allow_main, approve, allow_comm, allow_br, fixed, descr, keywords $tes $fgs) VALUES 
										('$author', '$category', '$added_time', '$title', '$alt_name', '$short_story', '$full_story', '$xfields', '$allow_main', '$approve', '$allow_comm', '1', '0', '$descr', '$keywords' $te $fgrs)");
										$news_id = $db->insert_id();
										if( $approve == '1'and $dop_sort[4] == 1 and @file_exists($rss_plugins.'ping/pingsite.txt')) {
											include ( $rss_plugins.'ping/grabberping.php');
										}
										if( $approve == '1'and @file_exists(ENGINE_DIR .'/inc/crosspost.addnews.php')) {
											$action = 'doaddnews';
											$row = $news_id;
											include ENGINE_DIR .'/inc/crosspost.addnews.php';
										}
										$db->query ('UPDATE '.PREFIX ."_users SET news_num = news_num + 1, lastdate = '$date_time' WHERE name ='$author'");
									}
									$safeT[] = '<b>'.$n.'</b> . <b style="color:green;">'.$tit.'</b> &#x25ba; <a class="list" href="index.php?newsid='.$news_id.'" target="_blank"><b style="color:blue;">'.$safet.'</b></a> '.$ping_msg ;
									if ($tegs != ''and $db->num_rows ($sql_Title) == 0) {
										$tags = array();
										$tegs = explode (',',$tegs);
										foreach ($tegs as $value) {
											if (trim($value) != '') $tags[] = "('$news_id', '".$db->safesql(trim($value))."')";
										}
										$tags = implode(', ',$tags);
										$db->query('INSERT INTO '.PREFIX.'_tags (news_id, tag) VALUES '.$tags);
									}
									if (trim ($dimages) != ''and ($db->num_rows ($sql_Title) == 0 or $dop_sort[12] == 0))
									{
										$db->query('INSERT INTO '.PREFIX ."_images (images, news_id, author, date) VALUES   ('$dimages', '$news_id', '$author', '$date_time')");
									}
									if (intval($dnast[10]) != 0){
										if ($expires != ''){$datede = strtotime ($expires);}else
										{$datede = strtotime ($added_time) +$dnast[10] * 86400;}
										$db->query( 'INSERT INTO '.PREFIX ."_post_log (news_id, expires, action) VALUES('$news_id', '$datede', '{$dnast[11]}')");
									}
								}
								$n++;
							}
						}
					}
				}
			}
			if($config_rss['sitemap'] == 'yes'and @file_exists($rss_plugins.'ping/sitemap.php')) {
				include ( $rss_plugins.'ping/sitemap.php');
			}
			msg ($lang_grabber['info'],$lang_grabber['info'],(sizeof($safeT)?'<b>'.$lang_grabber['post_msg_yes'].'</b><br /><br />'.implode('<br />',$safeT).'<br /><br />Итого  '.sizeof($safeT).' новости '.$ping_ms:'<b style="color:red;">'.$lang_grabber['post_msg_no'].'</b>').(sizeof($xdoe)?'<br />* * *<br /><b style="color:#ff0000;">'.$lang_grabber['post_msg_pics'].'</b><br /><br />'.implode('<br />',$xdoe):''),$PHP_SELF .'?mod=rss');
			$db->free();
			$db->close;
			clear_cache ();
		}else{
			msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
		}
		return 1;
	}
	if (preg_match('/scan/i',$action))
	{
		if (intval($_POST['str_news']) != 0){define('Y_GRAB_LIMIT',intval($_POST['str_news'])-1);
		}else{define('Y_GRAB_LIMIT',0);}
		if (intval($_POST['str_newf']) != 0)define('X_GRAB_LIMIT',intval($_POST['str_newf']));
		else define('X_GRAB_LIMIT',strlen($action)>4?str_replace('scan','',$action):false);
		$channel = $_POST['channel'];
		if (count ($channel) == 0)
		{
			msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
			return 1;
		}
		echoheader ('','');
		opentable ($lang_grabber['grab_msg']);
		if ($config_rss['get_proxy'] == 'yes') get_proxy();
		$config_rss['get_prox'] = $tab_id;
		echo $bb_js."<script type=\"text/javascript\">
var sin_open = 0;
var nosin_open = 0;
    function find_relates ( id )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');
        var title = ajax.encodeVAR( document.getElementById('title_' + id).value);
        var varsString = 'title=' + title;
        ajax.requestFile ='engine/ajax/find_relates.php';
        ajax.element = 'related_news' + id;
        ajax.sendAJAX(varsString);
return false;
    }

    function storyes ( id, key )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');

var varsString = 'key1=' + id;

        ajax.setVar(\"key\", key);
        ajax.requestFile ='engine/ajax/storyes.php';
        ajax.element = 'progressbar';
        ajax.sendAJAX(varsString);
return false;
    }

    function start_sinonims (key, id )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');
if (key == 1)var title = ajax.encodeVAR( document.getElementById('short_' + id).value);
else var title = ajax.encodeVAR( document.getElementById('full' + id).value);

        var varsString = 'story=' + title;
        ajax.setVar(\"id\", id);
        ajax.setVar(\"key\", key);
        ajax.requestFile ='engine/ajax/start_sinonims.php';

        if (key == 1)ajax.element = 'sinonim_short' + id;
else ajax.element = 'sinonim_full' + id;
        ajax.method = 'POST';
        ajax.sendAJAX(varsString);
return false;
    }

    function auto_keywords ( key, id )
    {
        var ajax = new dle_ajax();
        ajax.onShow ('');

        var wysiwyg = '{$config['allow_admin_wysiwyg']}';

            var short_txt = ajax.encodeVAR( document.getElementById('short_' + id).value );
            var varsString = \"short_txt=\" + short_txt;
            ajax.setVar(\"full_txt\", ajax.encodeVAR( document.getElementById('full' + id).value ));

        ajax.setVar(\"key\", key);
        ajax.requestFile = \"engine/ajax/keywords.php\";

        if (key == 1) { ajax.element = 'autodescr' + id; }
        else { ajax.element = 'keywords' + id;}

        ajax.method = 'POST';
        ajax.sendAJAX(varsString);

        return false;
    };

</script>

";
		echo '

    <form method=post name="news_form" id="news_form" action="?mod=rss">
<input type="hidden" name="action" value="save" />
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td style="padding:5px;" bgcolor="#FFFFFF">
    <script>
        var form = document.getElementById(\'news_form\');

        // ---------------------------------
        //  Check column
        // ---------------------------------
        function check_all ( permtype , master_box) {
        var ajax = new dle_ajax();
        ajax.onShow (\'\');
        var checkboxes = form.getElementsByTagName(\'input\');
        for (var i = 0; i < checkboxes.length; i++)
        {
            var element = checkboxes[i];
            if ( element && (element.id != \'mod\') && (element.id != \'main\') && (element.id != \'comm\') ) {
            var element_id = element.id;
            var a = element_id.replace( /^(.+?)_.+?$/, "$1" );
            if (a == permtype)
            {
             element.checked = master_box;
            }
            }
        }
ajax.onHide (\'\');
        return false;
        }
        // ---------------------------------
        //  Check all categories
        // ---------------------------------
        function check_cat() {
         var select_list = form.getElementsByTagName(\'select\');
         var value      = form.category.value;
         for (var i = 0; i < select_list.length; i++)
         {
            var element = select_list[i];
            element.value = value;
         }
         return false;
        }

function checkAll(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==\'\'?\'#E8F9E6\':\'\';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          =\'#E8F9E6\';
      }document.news_form.select_all.checked=true;
    }
}

function selectRow(evnt,elmnt){
  var ch=elmnt.getElementsByTagName("TD")[8].firstChild;
  tg = document.all?evnt.srcElement:evnt.target;
  //if(tg.tagName!=\'INPUT\')ch.checked=!ch.checked;
  elmnt.style.backgroundColor=ch.checked?\'#E8F9E6\':\'\';
}

    function preview( id )
    {
        dd=window.open(\'\',\'prv\',\'height=400,width=750,resizable=1,scrollbars=1\');
        document.addnews.target=\'prv\';
        document.addnews.title.value = document.getElementById(\'title_\' + id).value;
        document.addnews.short_story.value = document.getElementById(\'short_\' + id).value;
        if (document.getElementById(\'full\' + id)) {
        document.addnews.full_story.value = document.getElementById(\'full\' + id).value;
        } else {
        document.addnews.full_story.value = "";
        }
        document.addnews.allow_br.value = 1;
        document.addnews.submit();
    }
function ShowOrHideEx(id, show) {
    var item = null;
    if (document.getElementById) {
        item = document.getElementById(id);
    } else if (document.all) {
        item = document.all[id];
    } else if (document.layers){
        item = document.layers[id];
    }
    if (item && item.style) {
        item.style.display = show ? "" : "none";
    }
    }
    function xfInsertText(text, element_id) {
    var item = null;
    if (document.getElementById) {
        item = document.getElementById(element_id);
    } else if (document.all) {
        item = document.all[element_id];
    } else if (document.layers){
        item = document.layers[element_id];
    }
    if (item) {
        item.focus();
        item.value = item.value + " " + text;
        item.focus();
    }
    }

    </script>
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
';
		echo "
<script>
  $(document).ready(function(e){


    function countChecked() {
      var n = $(\".sel:checked\").length;
      $(\"#checks\").text(n == 0 ? '- новости не выбраны -' : \"Добавить в базу \" + n + (n%10 == 1 ? \" новость\" : (n <= 4 ? \" новости\" : \" новостей\")));
$('button').attr('disabled', n == 0 ? true : '');
    }
    countChecked();
    $(\":checkbox\").click(countChecked);


  });

</script>

<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'   border='0' /></div>";
		include ($rss_plugins.'inserttag.php');
		echo $bb_js;
		$rss_parser = new rss_parser ();
		$channel_list = @implode (',',$channel);
		$sql = $db->query ('SELECT * FROM '.PREFIX .('_rss WHERE id IN ('.$channel_list .') ORDER BY xpos,title ASC'));
		$db->close;
		$news_count = 1;
		while ($row = $db->get_row ($sql))
		{
			$i = 1;
			$end_title = explode ('==',$row['end_title']);
			$dop_sort = explode ('=',$row['short_story']);
			$dop_nast = explode ('=',$row['dop_nast']);
			$ctp = explode ('=',$row['ctp']);
			$start_template = stripslashes ($row['start_template']);
			$finish_template = stripslashes ($row['finish_template']);
			$dnast = explode ('=',$row['dnast']);
			$sart_cat = explode('|||',$row['sart_cat']);
			$cookies = str_replace('|||','; ',str_replace("\r",'',stripslashes(rtrim($row['cookies']))));
			$allow_mod = ($row['allow_mod'] == 0 ?'checked': '');
			$allow_main = ($row['allow_main'] == 1 ?'checked': '');
			$allow_comm = ($row['allow_comm'] == 1 ?'checked': '');
			$channel_id = $row['id'];
			$hide_leech = explode('=',$row['end_short']);
			$rewrite = ($hide_leech[3] == 1 ?'checked': '');
			$met = ($dnast[9] == 1 ?'checked': '');
			$rss = $row['rss'];
			$charsets = '';
			if (trim($dop_nast[14]) != ''or $dop_nast[14] != '0')$charsets = explode('/',$dop_nast[14]);
			if (count ($ctp) == 0 and $rss == 1){$ctp[0] = 0;$ctp[1] = 0;}
			if ($_POST['str_kans'] and $_POST['str_kanf'] and $rss != 1){
				$ctp[0] = $_POST['str_kans'];
				$ctp[1] = $_POST['str_kanf'];
			}
			$URL = get_urls(trim($row['url']));
			if (intval($_POST['str_news']) != 0)$text_str = '<br><font color="green"> с '.$_POST['str_news'].' по '.$_POST['str_newf'].' новость</font>';
			echo '  <input type="hidden" name="channels[]" value="'.$channel_id .'" />
    <fieldset style="border:1px dashed #c4c4c4;">
    <legend><font color="#C0C0C0">№'.$row['xpos'].' - </font>'.$row['title'].'<br /><a href="http://'.$URL['host'].'" target="_blank"><font color="blue">http://'.$URL['host'].'</font></a>'.$text_str.'</legend>'.'';
			$cron_job = ENGINE_DIR.'/cache/cron_job.txt';
			if (@file_exists($cron_job))
			{
				$job = file($cron_job);
				if ($job[0] == $row['id'])
				{
					$lang_grabber['no_news'] = 'Этот канал обрабатыватся кроном';
					echo '
    <table cellpadding="4" cellspacing="0">
    <tr><td class="navigation" style="padding:4px">
     <b>Этот канал обрабатыватся кроном</b>
    </td></tr>
    </table>
    </fieldset>';
					continue;
				}
			}
			$news_per_channel = 1;
			if ($ctp[1] >0 and ($ctp[0] == 0 or $ctp[0] == '')) $ctp[0] = '1';
			for ($cv=$ctp[0];$cv<=$ctp[1];$cv++)
			{
				if ($cv != 0 and $rss == 0)
				{
					if ($row['full_link'] == ''){
						$rows = $row['url'].'/page/'.$cv.'/';
					}else {
						$rows = str_replace ('{num}',$cv,$row['full_link']);
					}
					if ($cv == 0 or $cv == 1 ) $pg = $lang_grabber['pst_st'];else $pg = $lang_grabber['pst'].$cv;
					echo '<table width="100%">
 <tr>
        <td ><a href="'.$rows.'" target="_blank"><b><font color="orange">'.$pg.'</font></b></a></td>
</tr>
</table>';
					$URL = get_urls(trim($rows));
				}
				if ($rss == 1){
					$rss_parser->default_cp = $dop_nast[14];
					$rss_result = $rss_parser->Get ($row['url'],$dop_nast[2]);
				}else{
					$URLitems = get_full ($URL[scheme],$URL['host'],$URL['path'],$URL['query'],$cookies,$dop_nast[2],$dop_sort[8]);
					if (trim($dop_nast[14]) == ''or $dop_nast[14] == '0')$chariks = charset($URLitems);else $chariks = $charsets[0];
					if ($row['ful_start'] != '')$rss_result = get_page ($URLitems,$row['ful_start']);else $rss_result = get_dle($URLitems);
				}
				$time_stamp = time () +$config['date_adjust'] * 60;
				$time = date ('Y-m-d H:i:s',$time_stamp);
				if ($rss_result)
				{
					if ($rss == 1){
						if (X_GRAB_LIMIT&&$rss_result>X_GRAB_LIMIT) {
							if ($rss_result['items_count'] >X_GRAB_LIMIT) $grab_lis = X_GRAB_LIMIT -$rss_result['items_count'];
							$rss_result['items'] = array_slice($rss_result['items'],Y_GRAB_LIMIT,$grab_lis);
						}
						$rss_result = $rss_result['items'];
					}else{
						if (X_GRAB_LIMIT&&$rss_result>X_GRAB_LIMIT) {
							$result = count($rss_result);
							if ($result >X_GRAB_LIMIT) $grab_lis = X_GRAB_LIMIT -$result;
							$rss_result = array_slice($rss_result,Y_GRAB_LIMIT,$grab_lis);
						}
					}
					$news_str_channel = 1;
					$result = count($rss_result);
					asort($rss_result);
					foreach ($rss_result as $skey=>$item)
					{
						$skey = $skey +1;
						echo"
        <div id=\"progressbar\"></div>

        <script>
storyes($skey, $result);
    </script>";
						$tags_tmp = '';
						$charik = '';
						if (intval($dop_nast[19]) != 0)sleep ($dop_nast[19]);
						unset ($news_link);
						unset ($news_tit);
						if ($rss == 1){
							$news_tit = rss_strip ($item['title']);
							$short_story = rss_strip($item['description']);
							$news_link = stripslashes (rss_strip($item['link']));
							$tags_tmp = rss_strip ($item['category']);
						}else{
							if ($chariks != strtolower($config['charset']) AND $item!= '') $item = convert($chariks,strtolower($config['charset']),$item);
							if (trim($row['start_title']) != '')$news_tit = strip_tags(get_full_news($item,$row['start_title']));
							if ($row['end_link'] != 1){
								$short_story = get_short_news ($item,$row['start_short']);
							}else{
								$short_story = get_full_news ($item,$row['start_short']);
							}
							if (trim($row['sart_link'])==''){
								$tu_link = get_link ($item);
								$news_link = 'http://'.$URL['host'].'/index.php?newsid='.$tu_link;
							}else{
								$news_lin = get_full_news($item,$row['sart_link']);
								$news_link = full_path_build ($news_lin,$URL['host'],$URL['path']);
							}
						}
						if (trim($end_title[2]) != ''and trim($news_tit) != '') $news_tit =rss_strip( relace_news ($news_tit,$end_title[2],$end_title[3]));
						$alt_name = $db->safesql (totranslit( stripslashes( $news_tit ),true,false ));
						$safeTitle  = $db->safesql($news_tit);
						if ($dop_sort[12] == 0) {$where = ' LIMIT 1';}
						elseif ($dop_sort[12] == 1 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%'";}
						elseif ($dop_sort[12] == 2) {$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
						elseif ($dop_sort[12] == 3 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%' OR title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
						else {$where = " WHERE title = '".$safeTitle."' OR alt_name = '".$alt_name."'";}
						$sql_result = $db->query('SELECT * FROM '.PREFIX .'_post'.$where);
						if ($db->num_rows ($sql_result) == 0 or $news_tit ==''or $hide_leech[3] == 1 or $dop_sort[12] == 0)
						{
							if ($rss == 1){
								if (trim ($news_link) == '')
								{
									$news_link = stripslashes (rss_strip($item['guid']));
								}
							}
							$news_link = full_path_build ($news_link,$URL['host'],$URL['path']);
							$full_story = '';
							$news_lik = $news_link;
							for ($j=1;$j <= 20;$j++){
								$stoped = false;
								if (trim($row['dop_full']) == ''and $j != '1'and $full_story != '')$stoped = true;
								if (trim ($start_template) != ''and !$stoped)
								{
									if ($dop_nast[18] == 0 ){
										if (trim($row['dop_full'])!= ''and $j >= 2){
											$news_link = str_replace('http://','',$news_lik);
											$fl = explode('/',$news_link);
											$news_linke = '';
											for ($k=0;$k<(count($fl)-1);$k++){
												$news_linke .= $fl[$k].'/';
											}
											$news_linke .= str_replace('{num}',$j,$row['dop_full']);
											$news_link = 'http://'.$news_linke.end($fl);
										}
									}
									else{
										if ($j >= 2) $news_link = $news_lik.$row['dop_full'];
										$news_link= str_replace('{num}',$j,$news_link);
									}
									if (trim($end_title[4]) != ''and trim($news_link) != '') $news_link = relace_news ($news_link,$end_title[4],$end_title[5]);
									$link = replace_url(get_urls(trim(urldecode(rss_strip($news_link)))));
									$full = get_full ($link[scheme],$link['host'],$link['path'],$link['query'],$cookies,$dop_nast[2],$dop_sort[8]);
								}
								else
								{
									break;
								}
								if (trim ($full) != ''){
									if (trim($dop_nast[14]) == ''or $dop_nast[14] == '0')$charik = charset($full);else $charik = strtolower($charsets[1] != ''?$charsets[1]:$charsets[0]);
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
										if ($full_storys != $full_story1 or $j == '1'or $full_story == '')
										{
											$full_story .= $full_storys;
										}else{break;}
									}else{break;}
								}
								if ($full_story == '')break;
							}
							$news_link = $news_lik;
							if (trim($row['start']) != ''){
								$full_story = relace_news ($full_story,$row['start'],$row['finish']);
								$short_story = relace_news ($short_story,$row['start'],$row['finish']);
							}
							$xfields_array = array();
							if (trim($row['xfields_template']) != '')
							{
								$xfields_array = get_xfields (rss_strip($full_story),$short_story,$row['xfields_template']);
								$full_story = $xfields_array['content_story'];
								$short_story = $xfields_array['content0_story'];
							}
							if ($charik != strtolower($config['charset']) and trim ($full_story) != ''and trim ($charik) != '') {$full_story = convert($charik,strtolower($config['charset']),$full_story);}
							if (trim($row['sart_link'])==''and $rss == 0 and $dop_sort[16] != 1)$news_fulink= get_flink ($full,$link['host'],$tu_link);
							if (trim($news_fulink) !='')$news_link = $news_fulink;
							$full_story = html_strip ($full_story);
							if (trim($news_tit) == ''and trim ($full) != '')
							{
								$news_tit = get_title($full);
								if ($charik != strtolower($config['charset']) and trim($news_tit) != '') {$news_tit = convert($charik,strtolower($config['charset']),$news_tit);}
							}
							if (trim($news_tit) == ''and trim ($full_story) != ''){
								$news_tit = get_tit($short_story.$full_story);
								if ($charik != strtolower($config['charset']) and trim($news_tit) != '') {$news_tit = convert($charik,strtolower($config['charset']),$news_tit);}
							}
							$news_tit = rss_strip($news_tit);
							if (trim($end_title[2]) != ''and trim($news_tit) != '') $news_tit = relace_news ($news_tit,$end_title[2],$end_title[3]);
							if($dop_sort[13] == 1) {$news_tit = rss_strip (translate_google ($news_tit,$dop_sort[14] ,$dop_sort[15] ));}
							if($dop_sort[13] == 1 and $dop_sort[18] != '') {$news_tit = rss_strip (translate_google ($news_tit,$dop_sort[15] ,$dop_sort[18] ));}
							srand((float)microtime() * 1000000);
							$end_title0 = explode('|',$end_title[0]);
							$end_title1 = explode('|',$end_title[1]);
							if (trim($news_tit) != '')$news_title =$end_title0[array_rand($end_title0)].' '.$news_tit.' '.$end_title1[array_rand($end_title1)];
							$alt_name = totranslit( stripslashes( trim($news_tit) ),true,false );
							if ($dnast[12] == 1) $row['symbol'] = substr(strtolower ($alt_name),0 ,$dnast[13]);
							if (trim($news_tit) == '') continue;
							$full_allow_news = false;
							if ($dop_sort[12] == 0) {$where = ' LIMIT 1';}
							elseif ($dop_sort[12] == 1 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%'";}
							elseif ($dop_sort[12] == 2) {$where = " WHERE title = '".$db->safesql ($news_tit)."' OR alt_name = '".$db->safesql ($alt_name)."'";}
							elseif ($dop_sort[12] == 3 and $news_link != '') {$where = " WHERE xfields like '%".$db->safesql ($news_link)."%' OR title = '".$db->safesql ($news_tit)."' OR alt_name = '".$db->safesql ($alt_name)."'";}
							else {$where = " WHERE title = '".$db->safesql ($news_tit)."' OR alt_name = '".$db->safesql ($alt_name)."'";}
							$sql_result = $db->query('SELECT * FROM '.PREFIX .'_post'.$where);
							if ($db->num_rows ($sql_result) != 0)$full_allow_news = true;
							if ($db->num_rows ($sql_result) == 0 or $hide_leech[3] == 1 or $dop_sort[12] == 0)
							{
								if($dop_sort[9] != 0) {
									$full_story = trim(preg_replace('/[\r\n\t]+/',' ',$full_story));
									$short_story = trim(preg_replace('/[\r\n\t]+/',' ',$short_story));
								}
								if(trim($sart_cat[2]) != '') $zhv_code = get_full_news ($full_story,$sart_cat[2]);
								if (trim ($zhv_code) != '')
								{
									$zhv_code = rss_strip($zhv_code);
									$zhv_code = addcslashes(stripslashes($zhv_code),'#');
									$zhv_code = parse_Thumb ($zhv_code);
									$zhv_code = parse_rss ($zhv_code);
									$zhv_code = parse_host ($zhv_code,$link['host'],$link['path']);
									$zhv_code = $parse->decodeBBCodes ($zhv_code,false);
									$zhv_code = rss_strip ($zhv_code);
									$zhv_code = strip_tags ($zhv_code,'<object><embed><param>'.$dop_sort[5]);
									if($dop_sort[13] == 1) {$zhv_code = translate_google ($zhv_code,$dop_sort[14] ,$dop_sort[15] );}
									if($dop_sort[13] == 1 and $dop_sort[18] != '') {$zhv_code = rss_strip (translate_google ($zhv_code,$dop_sort[15] ,$dop_sort[18] ));}
									$zhv_code = preg_replace('#&quot;#','"',$zhv_code);
									$zhv_code = strip_br($zhv_code);
								}
								if (trim ($full_story) != '')
								{
									$full_story = rss_strip($full_story);
									$full_story = addcslashes(stripslashes($full_story),'#');
									$full_story = parse_Thumb ($full_story);
									$full_story = parse_rss ($full_story);
									$full_story = parse_host ($full_story,$link['host'],$link['path']);
									$full_story = $parse->decodeBBCodes ($full_story,false);
									$full_story = rss_strip ($full_story);
									$full_story = strip_tags ($full_story,'<object><embed><param>'.$dop_sort[5]);
									if($dop_sort[13] == 1) {$full_story = translate_google ($full_story,$dop_sort[14] ,$dop_sort[15] );}
									if($dop_sort[13] == 1 and $dop_sort[18] != '') {$full_story = rss_strip (translate_google ($full_story,$dop_sort[15] ,$dop_sort[18] ));}
									$full_story = preg_replace('#&quot;#','"',$full_story);
									$full_story = strip_br($full_story);
								}
							}
							else
							{
								$full_story = '';
								$full_allow_news = true;
							}
							$short_story = parse_Thumb ($short_story);
							$short_story = parse_rss ($short_story);
							$short_story = parse_host ($short_story,$link['host'],$link['path']);
							$short_story = $parse->decodeBBCodes ($short_story,false);
							$short_story = rss_strip ($short_story);
							$short_story = strip_tags ($short_story,'<object><embed><param>'.$dop_sort[5]);
							if($dop_sort[13] == 1) {$short_story = rss_strip (translate_google ($short_story,$dop_sort[14] ,$dop_sort[15] ));}
							if($dop_sort[13] == 1 and $dop_sort[18] != '') {$short_story = rss_strip (translate_google ($short_story,$dop_sort[15] ,$dop_sort[18] ));}
							$short_story  = preg_replace('#&quot;#','"',$short_story );
							$short_story  = strip_br($short_story );
							$short_stor =  $short_story;
							if ($dop_sort[2] == 1) $full_story = $short_story.'<br /><br />'.$full_story;
							if ($dop_sort[0] != 0)$short_story = '';
							$full_story = strip_br($full_story);
							if($dop_sort[11] != 0) {
								$news_title = str_replace( '  ',' ',$news_title );
							}
							$full_story = parse_host ($full_story,$link['host'],$link['path']);
							$short_story = parse_host ($short_story,$link['host'],$link['path']);
							$short_story = create_URL ($short_story,$link['host']);
							$full_story = create_URL ($full_story,$link['host']);
							if ($dnast[7] == 1 or $dnast[7] == 3) $short_story = url_img_($short_story );
							if ($dnast[7] == 2 or $dnast[7] == 3) $full_story = url_img_($full_story );
							if ($dop_nast[1] == 1){
								if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$short_story );
								if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$full_story );
							}
							if($dop_nast[1] == 2){
								if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie',"downs_host('\\1', '\\2', ".$dop_nast[1].')',$short_story );
								if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie',"downs_host('\\1', '\\2', ".$dop_nast[1].')',$full_story );
							}
							if($dop_nast[1] == 3){
								if ($dop_nast[16] == 1 or $dop_nast[16] == 0)$short_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie',"downs_host('\\1', '\\2', ".$dop_nast[1].')',$short_story );
								if ($dop_nast[16] == 2 or $dop_nast[16] == 0)$full_story = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie',"downs_host('\\1', '\\2', ".$dop_nast[1].')',$full_story );
							}
							if ($hide_leech[1] == '1'){
								$short_story = replace_hide ($short_story);
								$full_story = replace_hide ($full_story);
							}
							if ($hide_leech[2] == '1'){
								$short_story = replace_leech ($short_story);
								$full_story = replace_leech ($full_story);
							}else{
								$short_story = replace_noleech ($short_story);
								$full_story = replace_noleech ($full_story);
							}
							$short_story = replace_quote ($short_story);
							$full_story = replace_quote ($full_story);
							if ($short_story == ''and (intval($dop_nast[22]) != 0 or $dop_nast[24] != '')){
								if ($dop_nast[24] != '')$kones = $dop_nast[24];
								else $kones = ' ';
								$full_sto = strip_tags(stripslashes($parse->BB_Parse($parse->process($full_story) ,false)),'<b><i><br><center><u><p>'.$dop_sort[5]);
								$dop_kon = strpos(substr( $full_sto ,$dop_nast[22]),$kones);
								$nach = $dop_nast[22] +$dop_kon +1;
								if (intval($dop_nast[22]) != 0) $short_story = substr( $full_sto ,0,$nach).'...';
								else $short_story = substr( $full_sto ,0,strpos(substr( $full_sto ,$dop_nast[22]),$kones)).'...';
								$short_story = preg_replace('#<\S+\.\.\.#','...',$short_story);
								$short_story = str_replace('....','.',$short_story);
								$short_story = str_replace(',...','...',$short_story);
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
								$full_story = str_replace('[thumb','[img',$full_story);
								$full_story = str_replace('thumb]','img]',$full_story);
								preg_match_all ('#\[img.*?\](.+?)\[\/img\]#i',$full_story,$img_a);
								$is = 1;
								$num_i=ceil(count($img_a[0])/$dop_nast[23]);
								$is_k = 1;
								foreach ($img_a[0] as $value)
								{
									if ($is %$dop_nast[23] == 0){
										$full_story = str_replace($value,$value."\n{PAGEBREAK}\n",$full_story);
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
',$short_story));
								$full_story = trim(preg_replace('/[\r\n\t ]{3,}/','
',$full_story));
							}
							$news_title_out = $parse->decodeBBCodes($news_title);
							$short_story = htmlspecialchars ($short_story);
							$full_story = htmlspecialchars ($full_story);
							if (!((!($row['date_format'] == 0) AND !($row['date_format'] == 1))))
							{
								$added_time_stamp = time () +($config['date_adjust'] * 60);
								$dat = $lang_grabber['date_post'].$lang_grabber['date_flowing'];
								if ($row['date_format'] == 1)
								{
									$interval = mt_rand ($config_rss['interval_start']*60,$config_rss['interval_finish']*60);
									$added_time_stamp += $interval;
									$dat = $lang_grabber['date_post'].$lang_grabber['date_casual'];
								}
							}
							else
							{
								if ($rss == 1 or trim($sart_cat[1]) != ''){
									if ($row['date_format'] == 2)
									{
										if ($rss == 0 and $data_tmp != '')$added_time_stamp = strtotime ($data_tmp) -3600;
										else $added_time_stamp = strtotime ($item['pubDate']) -3600;
										$dat = $lang_grabber['date_post'].$lang_grabber['date_channel'];
									}
								}else{$added_time_stamp = time () +($config['date_adjust'] * 60);
								$dat = $lang_grabber['date_post'].$lang_grabber['date_flowing'];
								}
							}
							$str_date = date( 'Y-m-d H:i:s',$added_time_stamp);
							$keywordsd = explode ('===',$row['keywords']);
							$keywords = stripslashes ($keywordsd[0]);
							if (trim ($keywords) != '')
							{
								$allow_news = FALSE;
								$keywords = explode ('|||',$keywords);
								foreach ($keywords as $word)
								{
									$word = addcslashes(stripslashes($word),'"[]!-.#?*%\\()|/');
									if (!((!preg_match ('#'.$word.'#i',$short_story) or !preg_match ('#'.$word.'#i',$full_story) or !preg_match ('#'.$word.'#i',$news_title))))
									{
										$allow_news = TRUE;
									}
								}
							}
							else
							{
								$allow_news = TRUE;
							}
							$stkeywordsd = explode ('===',$row['stkeywords']);
							$stkeywords = stripslashes ($stkeywordsd[0]);
							if (trim ($stkeywords) != '')
							{
								$stkeywords = explode ('|||',$stkeywords);
								foreach ($stkeywords as $word)
								{
									$word = addcslashes(stripslashes($word),'"[]!-.#?*%\\()|/');
									if (!((!preg_match ('#'.$word.'#i',$short_story) or !preg_match ('#'.$word.'#i',$full_story) or !preg_match ('#'.$word.'#i',$news_title))))
									{
										$allow_news = FALSE;
									}
								}
							}
							if (trim ($row['delate']) != '')
							{
								$row_inser= str_replace('{zagolovok}',$news_title,$row['inser']);
								$row_inser= str_replace('{frag}',$zhv_code,$row_inser);
								$short_story = relace_news ($short_story,$row['delate'],$row_inser);
								$full_story = relace_news ($full_story,$row['delate'],$row_inser);
							}
							if (trim($keywordsd[1]) != '')$short_story = $keywordsd[1].' '.$short_story;
							if (trim($keywordsd[2]) != '')$full_story = $keywordsd[2].' '.$full_story;
							if (trim($stkeywordsd[1]) != '')$short_story .=' '.$stkeywordsd[1];
							if (trim($stkeywordsd[2]) != '')$full_story .=' '.$stkeywordsd[2];
							if ($db->num_rows ($sql_result) != 0 and $dop_sort[12] == 1){
								while ($ren = $db->get_row($sql_result)){
									$fuls_story = $db->super_query ('SELECT * FROM '.PREFIX ."_post WHERE id = '".$ren['id']."' ");
									$kolsa = array();$kolsb = array();
									preg_match_all("#<a.*?href[=]?[='\"](\S+?)['\" >].*?>(.*?)<\/a>#is",$fuls_story['full_story'],$kolsa);
									preg_match_all('#\\[url(\S+?)\\].+?\\[/url\\]#i',$full_story,$kolsb);
									if (count($kolsa[0]) >= count($kolsb[0]))$allow_news = false;
									if ($data_tmp <$str_date and $row['date_format'] == 2 and $dnast[17] == 1 and $data_tmp != '')$allow_news = true;
									break;
								}
							}
							if ($allow_news)
							{
								$Autor = explode('=',$row['Autors']);
								if (trim($Autor[0]) != '')
								{
									$input=array ();
									$autor = explode ('|||',stripslashes($Autor[0]));
									foreach ($autor as $value)
									{
										$input[] =trim($value);
									}
								}
								else
								{if (trim($Autor[1]) == '') $Autor[1] = $config_rss['reg_group'];
								if (trim($Autor[1]) == '') $Autor[1] = 1;
								$rows = $db->query ('SELECT * FROM '.PREFIX ."_users WHERE user_group IN ({$Autor[1]})");
								while ($rown = $db->get_row($rows)) {
									$input[] = $rown['name'];
								}
								}
								if ($input != '')$author    = $input[array_rand ($input)];
								$author_info = "&nbsp;<a onclick=\"javascript:window.open('?mod=editusers&action=edituser&user={$author}','User','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500'); return(false)\" href=\"#\"><img src=\"engine/skins/images/adminrss.gif\" style=\"vertical-align: middle;border: none;\" /></a>";
								$news_info = "&nbsp;<a onclick=\"javascript:window.open('{$news_link}','','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500'); return(false)\" href=\"google.com\"><img src=\"engine/skins/images/addresrss.gif\" alt='Оригинал новости' title='Оригинал новости' style=\"vertical-align: middle;border: none;\" /></a>";
								$sin_bb = " <div id=\"sin_b\" class=\"editor_button\" onclick=\"simpletag('sin')\"><img title=\"{$lang_grabber['sin_bbcode']}\" src=\"engine/skins/bbcodes/images/sin2.gif\" width=\"23\" height=\"25\" border=\"0\"></div><div id=\"nosin_b\" class=\"editor_button\" onclick=\"simpletag('nosin')\"><img title=\"{$lang_grabber['nosin_bbcode']}\" src=\"engine/skins/bbcodes/images/nosin2.gif\" width=\"23\" height=\"25\" border=\"0\"></div>";
								$sin_bb .='<div class="editor_button" onclick="javascript:window.open( \''.$PHP_SELF .'?mod=rss&action=sinonim\')"><img title="База синонимов" src="engine/skins/bbcodes/images/sin.gif" width="23" height="25" border="0"></div>';
								$sin_but= "<input class=\"edit\" style=\"margin: 0 0 3 0px; background: #E8F9E6; font-size:9pt;\" onclick=\"simpletag('sin')\" type=\"button\"  value=\"sin\">  <input class=\"edit\" style=\"margin: 0 0 3 0px; background: #E8F9E6; font-size:9pt; text-decoration:line-through; \" onclick=\"simpletag('nosin')\" type=\"button\"    value=\"nosin\">
";
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
								$key_words = trim($row['key_words'].', '.$key_wordss,',');
								$key_words = substr( $key_words ,0,180 +strpos(substr( $key_words ,180),','));
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
								$descr = trim($row['meta_descr'].', '.$descrs ,',');
								$descr = substr( $descr ,0,180 +strpos(substr( $descr ,180),' '));
								$category_row = array();
								$category = array();
								$kateg = array();
								$tags_tmps = array();
								$tags_tm = '';
								$tags_tmps = replace_tags ($tags_tmp.','.$news_tit,$dnast[20]);
								if ($dnast[8] == 1)$tags_tm = $tags_tmps[0];
								$tags_tmp = trim($row['ftags'].','.$tags_tm ,',');
								if($dop_sort[13] == 1 and $tags_tmp != '') {$tags_tmp= rss_strip (translate_google ($tags_tmp,$dop_sort[14] ,$dop_sort[15] ));}
								if($dop_sort[13] == 1 and $tags_tmp != ''and $dop_sort[18] != '') {$tags_tmp= rss_strip (translate_google ($tags_tmp,$dop_sort[15] ,$dop_sort[18] ));}
								if (trim($row['kategory']) != ''){
									foreach (explode ('|||',$row['kategory']) as $value){
										$kr = explode ('==',$value);
										foreach (explode (',',$kr[0]) as $wnd){
											$url_kats = addcslashes(stripslashes(reset_urlk($wnd)),'"[]!-.#?*%\\()|/');
											if($dop_sort[16] == 1)$for = $tags_tmp;
											else $for = $news_link;
											if (preg_match('#'.$url_kats.'#i',$for)){
												foreach (explode (',',$kr[1]) as $key){
													if (trim($key) != '')$sql_cat= $db->super_query ('SELECT * FROM '.PREFIX ."_category WHERE name like '".$db->safesql(trim(strtolower($key)))."%' or alt_name like '".$db->safesql(trim(strtolower($key)))."%' or name like '".$db->safesql(trim($key))."%' or alt_name like '".$db->safesql(trim($key))."%'");
													if (trim($sql_cat) != '')
													{
														$kateg[]=$sql_cat['id'];
													}
												}
											}
										}
									}
								}
								if (count($kateg) == 0){
									if ($row['thumb_img'] == 1){
										$gory = explode (',',$tags_tmps[1].','.$tags[1]);
										foreach ($gory as $value) {
											if (trim($value) != '')$sql_cat= $db->super_query ('SELECT * FROM '.PREFIX ."_category WHERE name like '".$db->safesql(trim(strtolower($value)))."%' or alt_name like '".$db->safesql(trim(strtolower($value)))."%' or alt_name like '".$db->safesql(trim($value))."%' or alt_name like '".$db->safesql(trim($value))."%'");
											if (trim($sql_cat) != '')
											{
												$category[]=$sql_cat['id'];
											}
										}
									}
								}else{$category =$kateg;}
								$categoryes = explode ('=',$row['category']);
								$category_row = explode (',',$categoryes[0]);
								if (count($category_row) == 1)$category_row = $categoryes[0];
								if (count($category) != '0'){
									$categories_list = CategoryNewsSelection (array_unique($category),0);
								}else{
									$categories_list = CategoryNewsSelection ($category_row,0);
								}
								unset ($news_tit);
								$db->close;
								$xfieldsaction = 'categoryfilter';
								include ($rss_plugins.'xfields.php');
								echo $categoryfilter;
								echo "  <script >
    $(document).ready(function(e){
    $(\"select#category$i$channel_id\").change(function (e) {
            var str = \"\";
            $(\"select#category$i$channel_id option:selected\").each(function () {
                str += $(this).text() + \"  \";
                });
            $(\".category$i$channel_id\").text(str);
        })
        .trigger('change');
    });
    </script>

<script>
    $(function(){
        $('#tags$i$channel_id').autocomplete({
            serviceUrl:'engine/ajax/find_tags.php',
            minChars:3,
            delimiter: /(,|;)\s*/,
            maxHeight:400,
            width:348,
            deferRequestBy: 300
          });

    });
</script>
<table width=\"100%\">
    <tr class=\"light\" onMouseOut=this.className=\"light\"
       onMouseOver=this.className=\"highlight\"
       onclick=\"selectRow(event,this)\">
        <td style=\"padding:4px\" align=\"left\" ><a href=\"javascript:ShowOrHide('full_$i$channel_id');\">$news_title_out</a>";
								if ($dop_nast[5] == '0')$ava = ' style="display:none"';
								if ($dop_nast[7] == '0')$avd = ' style="display:none"';
								if ($dop_nast[6] == '0')$avt = ' style="display:none"';
								if ($dop_nast[13] == '0')$avw = ' style="display:none"';
								if ($dnast[2] == '0')$ada = ' style="display:none"';
								if ($dnast[3] == '0')$add = ' style="display:none"';
								if ($dnast[4] == '0')$adt = ' style="display:none"';
								if ($dnast[5] == '0')$adw = ' style="display:none"';
								if ($dnast[6] == '0')$adu = ' style="display:none"';
								if ($dnast[14] == '0')$ade = ' style="display:none"';
								if ($dnast[2] == '0'and $dnast[3] == '0'and $dnast[4] == '0'and $dnast[5] == '0'and $dnast[6] == '0')$adg = ' style="display:none"';
								if (trim ($full_story) == ''and trim ($news_link) !=''and $dop_sort[17] == 0 or intval($dop_sort[20]) == 1)
								{
									echo "      <br /><font color=red>{$lang_grabber['no_full_story']}</font> ==> <a href=\"$news_link\" target=\"_blank\">{$news_link}</a>";
								}
								echo "</td>
        <td align=\"right\" ><font color=red><div class=\"category$i$channel_id\"></div></font></td>
        <td width=\"1%\" ></td>";
								if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php')){
									echo "  <td width=\"2%\" ><input type=\"checkbox\"  name=\"sinonims_$i$channel_id\" id=\"sinonims_$i$channel_id\" checked value=\"1\" style=\"background-color: #ffffff; color: #008000;\" title=\"{$lang_grabber['val_sinonims']}\" /></td>";
								}else {echo "<td width=\"2%\" ></td>";}
								echo "  <td width=\"2%\" ><input type=\"checkbox\"  name=\"rewrite_$i$channel_id\"  id=\"rewrite_$i$channel_id\" {$rewrite} value=\"1\" title=\"{$lang_grabber['val_rewrite']}\" /></td>
        <td width=\"2%\" ><input type=\"checkbox\"  name=\"mod_$i$channel_id\"  id=\"mod_$i$channel_id\" {$allow_mod} value=\"1\" title=\"{$lang_grabber['val_mod']}\" /></td>
        <td width=\"2%\" ><input type=\"checkbox\"  name=\"main_$i$channel_id\" id=\"main_$i$channel_id\" {$allow_main} value=\"1\" title=\"{$lang_grabber['val_main']}\"/></td>
        <td width=\"2%\" ><input type=\"checkbox\"  name=\"comm_$i$channel_id\" id=\"comm_$i$channel_id\" {$allow_comm} value=\"1\" title=\"{$lang_grabber['val_comm']}\"/></td>
        <td width=\"2%\"><input class=\"sel\" type=\"checkbox\" name=\"sel_$i$channel_id\" id=\"sel\" value=\"1\" style=\"background-color: #ffffff; color: #ff0000;\" title=\"{$lang_grabber['val_post']}\" /></td>
</tr>
     <tr>
        <td colspan=\"9\">
<div style=\"padding-top:5px;padding-bottom:2px;display:none\" id=\"full_$i$channel_id\">
<div class=\"hr_line\"></div>
<table width=\"100%\">
    <tr>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['edit_et']}</td>
        <td><input size=\"70\" name=\"title_$i$channel_id\" id=\"title_$i$channel_id\" value=\"{$news_title_out}\"> <input class=\"edit\" type=\"button\" onClick=\"find_relates($i$channel_id); return false;\" style=\"width:160px;\" value=\"{$lang['b_find_related']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang[hint_title]}', this, event, '220px')\">[?]</a> {$news_info} <div id=\"related_news$i$channel_id\"></div>
         </td>
    </tr>
    <tr $ava>
        <td width=\"140\" style=\"padding-left:5px;\">{$lang_grabber['author']}</td>
        <td><input  name=\"autor_$i$channel_id\" id=\"autor_$i$channel_id\" size=\"30\" value=\"{$author}\">$author_info</td>
    </tr>
    <tr $avd>
        <td height=\"29\" style=\"padding-left:5px;\">{$dat}:</td>
        <td><input  name=\"date-from-channel_$i$channel_id\" id=\"date-from-channel_$i$channel_id\" size=\"30\" value=\"{$str_date}\">
<img src=\"engine/skins/images/img.gif\"    align=\"absmiddle\" id=\"f_trigger_c_$i$channel_id\" style=\"cursor: pointer; border: 0\" title=\"{$lang['edit_ecal']}\"/>
<script type=\"text/javascript\">
Calendar.setup({inputField:\"date-from-channel_$i$channel_id\",ifFormat:\"%Y-%m-%d %H:%M\",button:\"f_trigger_c_$i$channel_id\",align : \"Br\",timeFormat:\"24\",showsTime:true,singleClick:true});
</script>
       </td>
</tr>
    <tr>
        <td height=\"29\" style=\"padding-left:5px;\">{$lang['edit_cat']}</td>
        <td><select name=\"category$i$channel_id []\" id=\"category$i$channel_id\" onchange=\"onCategoryChange$i$channel_id(this.value)\" class=\"cat_select\" multiple>{ $categories_list }</select>
        </td>
    </tr>
<tr $avt><td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang_grabber['tegs_post']}:</td>
<td><input size=\"70\" id=\"tags$i$channel_id\" name=\"tags_$i$channel_id\" value=\"{$tags_tmp}\" class=\"edit bk\" autocomplete=\"off\">
        </td>
    </tr>
</table>
<div class=\"hr_line\"></div>
<table width=\"100%\">
    <tr>
	<td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['addnews_short']}<br /></td>
    <td>";
								if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php')){
									if ($dop_nast[8] == '1')echo $sin_bb;
									else echo $sin_but;
								}
								if ($dop_nast[8] == '1'){
									echo $bb_panel;
								}
								echo "<textarea style=\"width:98%; height:200px\" onclick=\"setFieldName(this.name)\" id=\"short_$i$channel_id\" name=\"short_$i$channel_id\">{$short_story}</textarea>";
								if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php') )
								{
									echo "<input class=\"edit\" type=\"button\" onClick=\"start_sinonims(1, $i$channel_id); return false;\" style=\"width:180px; background: #FFF9E0; border: 1px solid #8C8C8C;\" value=\"{$lang_grabber['sinonims_preview']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_sinonims_preview']}', this, event, '220px')\">[?]</a> <span id=\"sinonim_short$i$channel_id\"></span>";}echo "
    </td>
	</tr>
    <tr>
    <td height=\"29\" style=\"padding-left:5px;\">{$lang['addnews_full']}</td>
    <td><br />";
									if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php')){
										if ($dop_nast[8] == '1')echo $sin_bb;
										else echo $sin_but;
									}
									if ($dop_nast[8] == '1'){
										echo $bb_panel;
									}
									echo "<textarea style=\"width:98%; height:200px\" onclick=\"setFieldName(this.name)\" id=\"full$i$channel_id\" name=\"full_$i$channel_id\">{$full_story}</textarea>";
									if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php') )
									{echo "
<input class=\"edit\" type=\"button\" onClick=\"start_sinonims(2, $i$channel_id); return false;\" style=\"width:180px; background: #FFF9E0; border: 1px solid #8C8C8C;\" value=\"{$lang_grabber['sinonims_preview']}\"> <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_sinonims_preview']}', this, event, '220px')\">[?]</a> <span id=\"sinonim_full$i$channel_id\"></span>";}echo "
    </td>
</tr>
<tr $avw>
    <td height=\"29\" style=\"padding-left:5px;\">{$lang_grabber['pics_post']}:</td>
    <td>
<select name=\"serv_$i$channel_id\" id = \"serv_$i$channel_id\" \">
".server_host($row['load_img'])."
</select>
    <a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_post_rad']}', this, event, '220px')\">[?]</a>
    </td>
</tr>";
									if ($dop_nast[9] == '1')
									{
										echo "<tr >
        <td style=\"padding:4px\" align=\"left\" colspan=\"7\"><a href=\"javascript:ShowOrHide('xf_$i$channel_id');\"><font color=\"green\">{$lang_grabber['doppol']}</font></a></td>
    </tr>
     <tr>
        <td colspan=\"7\">
<div style=\"display:none\" id=\"xf_$i$channel_id\">
<div class=\"hr_line\"></div>
<table width=\"100%\">";
										$xreplace = array();
										$ds = explode ('|||',$row['xfields_template']);
										foreach ($ds as $xvalue)
										{
											$xf=array();
											$xf = explode ('==',$xvalue);
											$xreplace[$xf[0]] = $xf;
										}
										$fieldvalue = array();
										if (count($xfields_array) !=0){
											foreach ($xfields_array as $key =>$value){
												if ($key != 'content_story'){
													if(array_key_exists($key,$xreplace)) {
														$xreplace_key = str_replace('{zagolovok}',$news_title,$xreplace[$key][6]);
														$xreplace_key= str_replace('{frag}',$zhv_code,$xreplace_key);
														$value = relace_news ($value,$xreplace[$key][5],$xreplace_key);
													}
													$value = parse_Thumb ($value);
													$value = parse_rss ($value);
													$value = $parse->decodeBBCodes ($value,false);
													$value = rss_strip ($value);
													$value = strip_tags ($value,'<object><embed><param>'.$dop_sort[5]);
													if($dop_sort[13] == 1) {$value = rss_strip (translate_google ($value,$dop_sort[14] ,$dop_sort[15] ));}
													if($dop_sort[13] == 1 and $dop_sort[18] != '') {$value = rss_strip (translate_google ($value,$dop_sort[15] ,$dop_sort[18] ));}
													$value = preg_replace('#&quot;#','"',$value);
													$value = parse_host ($value,$link['host'],$link['path']);
													if ($dop_nast[1] == 1){
														$value=preg_replace( "#(^|\s|>)((http://|https://|ftp://)\w+[^<\s\[\]]+)#i","\\1[url]\\2[/url]",$value );
													}
													if($dop_nast[1] == 2){
														$value = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie',"downs_host('\\1', '\\2', ".$dop_nast[1].')',$value );
													}
													if($dop_nast[1] == 3){
														$value = preg_replace( '#\[url=(.+?)\](.+?)\[\/url\]#ie',"downs_host('\\1', '\\2', ".$dop_nast[1].')',$value );
													}
													$fieldvalue[$key] = $value;
												}
											}
										}
										if ($row['allow_more'] == 1)
										{
											$fieldvalue['source_name'] = $row['title'];
											$fieldvalue['source_link'] = $news_link;
										}
										$xfieldsaction = 'list';
										include ($rss_plugins.'xfields.php');
										$config_code_bb = explode (',',$config_rss['code_bb'] );
										$config_sin_dop = explode (',',$config_rss['sin_dop'] );
										if ($config_rss['code_bb'] != ''){
											foreach ($config_code_bb as $value){
												$output = str_replace('<!--'.$value.'-->','<!--'.$value.'-->'.$bb_panel,$output);
											}
										}
										if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php') and $config_rss['sin_dop'] != '')
										{
											foreach ($config_sin_dop as $val){
												if (in_array($val,$config_code_bb))$output = str_replace('<!--'.$val.'-->',$sin_bb,$output);
												else $output = str_replace('<!--'.$val.'-->',$sin_but,$output);
											}
										}
										echo $output;
										echo '</table>
</div>
</td>
</tr>';
									}
									echo "<tr $adg>
        <td style=\"padding:4px\" align=\"left\" colspan=\"7\"><a href=\"javascript:ShowOrHide('dop_$i$channel_id');\"><font color=\"green\">{$lang_grabber['dopmet']}</font></a></td>
    </tr>
     <tr>
        <td colspan=\"7\">
<div style=\"display:none\" id=\"dop_$i$channel_id\">
<table width=\"100%\" >
";
									if (intval($dnast[10]) != 0){
										$expires = date( 'Y-m-d H:i:s',(strtotime($str_date) +$dnast[10] * 86400));
										if($dnast[11] == 1)$expi = 'selected';else $exp = 'selected';
									}else{$expires = '';}
									if (intval($dnast[10]) != 0){
										if ($expires != ''){$datede = strtotime ($expires);}else
										{$datede = strtotime ($added_time) +$dnast[10] * 86400;}
										$db->query( 'INSERT INTO '.PREFIX ."_post_log (news_id, expires, action) VALUES('$news_id', '$datede', '{$dnast[11]}')");
									}
									echo "        <td colspan=\"2\"><div class=\"hr_line\"></div></td>
    <tr $ada>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['catalog_url']}</td>
        <td><input type=\"text\" name=\"symbol$i$channel_id\" size=\"5\"  class=\"edit\" value=\"{$row['symbol']}\"><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang[catalog_hint_url]}', this, event, '300px')\">[?]</a></td>
    </tr>
    <tr $adu>
        <td width=\"140\" height=\"29\" style=\"padding-left:5px;\">{$lang['addnews_url']}</td>
        <td><input type=\"text\" name=\"alt_name$i$channel_id\" size=\"55\"  class=\"edit\" value=\"{$alt_name}\"><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang[hint_url]}', this, event, '300px')\">[?]</a></td>
    </tr>

    <tr $ade>
        <td height=\"29\" style=\"padding-left:5px;\">{$lang['date_expires']}</td>
        <td><input type=\"text\" name=\"expires_$i$channel_id\" id=\"e_date_c_$i$channel_id\" size=\"20\" value=\"{$expires}\" class=edit>
<img src=\"engine/skins/images/img.gif\"  align=\"absmiddle\" id=\"e_trigger_c_$i$channel_id\" style=\"cursor: pointer; border: 0\" /> {$lang['cat_action']} <select name=\"expires_action_$i$channel_id\"><option $exp value=\"0\">{$lang['edit_dnews']}</option><option $expi value=\"1\" >{$lang['mass_edit_notapp']}</option></select><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang['hint_expires']}', this, event, '320px')\">[?]</a>
<script type=\"text/javascript\">
    Calendar.setup({
        inputField     :    \"e_date_c_$i$channel_id\",     // id of the input field
        ifFormat       :    \"%Y-%m-%d\",      // format of the input field
        button         :    \"e_trigger_c_$i$channel_id\",  // trigger for the calendar (button ID)
        align          :    \"Br\",           // alignment
        singleClick    :    true
    });
</script></td>
    </tr>

        <tr $add>
            <td>&nbsp;</td>
            <td>{$lang['add_metatags']}<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang['hint_metas']}', this, event, '220px')\">[?]</a></td>
        </tr>

        <tr $add>
            <td height=\"29\" style=\"padding-left:5px;\">{$lang['meta_title']}</td>
            <td><input type=\"text\" name=\"meta_title$i$channel_id\" style=\"width:388px;\" class=\"edit\" value=\"".str_replace('{zagolovok}',$news_title,$row['metatitle'])."\"> <input type=\"checkbox\" name=\"met_$i$channel_id\" {$met} value=\"1\" title=\"{$lang_grabber['val_met']}\" /></td>
        </tr>
        <tr $adt>
            <td height=\"29\" style=\"padding-left:5px;\">{$lang['meta_descr']}:<br><i>({$lang['meta_descr_max']})</i></td>
            <td><textarea type=\"text\" name=\"descr$i$channel_id\" id=\"autodescr$i$channel_id\" style=\"width:388px;height:70px;\" >{$descr}</textarea>
        </td>
        </tr>
        <tr $adw>
            <td height=\"29\" style=\"padding-left:5px;\">{$lang['meta_keys']}:<br><i>({$lang['meta_descr_max']})</i></td>
            <td><textarea name=\"keywords$i$channel_id\" id='keywords$i$channel_id' style=\"width:388px;height:70px;\">{$key_words}</textarea>

            </td>
        </tr>
</table>
</div>
</td>
</tr>
";
									echo "<input type=\"hidden\" name=\"news_link_$i$channel_id\" value=\"$news_link\">";
									echo "
<tr>
    <td><input class=\"edit\" style=\"background: #E8F9E6; font-size:9pt;\" onClick=\"preview($i$channel_id)\" type=\"button\"  value=\"{$lang['btn_preview']}\"></td><td align=right><a href=\"javascript:ShowOrHide('full_$i$channel_id');\"><font color=orange>&uarr; СВЕРНУТЬ &uarr;</font></a></td>
    </tr>
</table>
<div class=\"hr_line\"></div>
</div>
</td>
</tr>
</table>
     <table cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">
     <tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
     </table>";
									++$i;
									++$news_count;
									++$news_per_channel;
									++$news_str_channel;
									continue;
							}else{
								if ($full_allow_news){
									echo "<table width=\"100%\">
    <tr class=\"navigation\">
        <td style=\"padding:4px\" align=\"left\" >{$news_title}</td></td>
        <td align=\"right\" > уже имеется в вашей базе</td></tr>
		<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
		</table>";}else{
									echo "<table width=\"100%\">
    <tr class=\"navigation\">
        <td style=\"padding:4px\" align=\"left\" >{$news_title}</td></td>
        <td align=\"right\" > отфильтрована</td></tr>
		<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
		</table>";
		}
							}
						}else{echo "<table width=\"100%\">
    <tr class=\"navigation\">
        <td style=\"padding:4px\" align=\"left\" >{$news_titl}</td>
		<td align=\"right\" > уже имеется в вашей базе</td></tr>
		<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
		</table>";}
					}
					if ($news_count == 1)
					{
						echo '<div align="center" class="navigation">- '.$lang_grabber['no_news'].' -</div>';
					}
					$str = $news_str_channel -1;
					if ($cv != 0 and $rss == 0)
					{
						if ($str == 1) $now = 'новость';elseif($str <4 ) $now = $lang_grabber['news_sm'];else $now = $lang_grabber['post_big'];
						if ($str == 0)
						{
							echo '<font color="#339900">- '.$lang_grabber['no_news'].' -</font>';
						}else{
							echo '
    <table cellpadding="4" cellspacing="0">
    <tr><td class="navigation" style="padding:4px">
     <font color="green">'.$lang_grabber['yes_news'].'<b>'.$str.'</b> '.$now.'</font>
    </td></tr>
    </table>';}
					}
				}
				if (intval($dop_nast[20]) != 0){
					$cv = $cv +($dop_nast[20] -1);
				}
			}
			$kol = $news_per_channel -1 ;
			echo '  <input type="hidden" name="news-per-channel-'.$channel_id .'"   value="'.$news_per_channel .'" />
    <table cellpadding="4" cellspacing="0">
    <tr><td class="navigation" style="padding:4px">
     '.$lang_grabber['yes_news'].' <b>'.$kol .'</b>'.$lang_grabber['post_big'].'
    </td></tr>
    </table>
    </fieldset>';
			continue;
		}
		$db->free();
		$categories_list = categorynewsselection (0,0);
		echo '<br/>';
		unterline ();
		if( function_exists('memory_get_peak_usage') ) {
			$mem_usage = memory_get_peak_usage(true);
			if ($mem_usage <1024)
			echo $mem_usage.' bytes';
			elseif ($mem_usage <1048576)
			$memory_usage = round($mem_usage/1024,2).' кб';
			else
			$memory_usage = round($mem_usage/1048576,2).' мб';
		}
		$kolv = $news_count -1 ;
		echo '  <table cellpadding="0" cellspacing="0" width="100%" border=0>
    <tr >
    <td class="navigation" style="padding:0px">
    '.$lang_grabber['news_all'].': <b>'.$kolv .'</b><br /> Использовано памяти ~ '.$memory_usage.'
    </td>
    <td align="right" style="padding:4px;">
     '.$lang_grabber['gl_val'].':
     <select name="category" id="category" onChange="check_cat();" class="edit">
     '.$categories_list .'
     </select></td>';
		if ($dop_sort[3] == 1 and @file_exists ($rss_plugins.'sinonims.php')){
			echo '<td width="2%" ><input    type="checkbox" name="sinonims_all" id="sinonims_all"   onClick="check_all(\'sinonims\', this.checked);" title="'.$lang_grabber['val_sininims'].' ('.$lang_grabber['val_all'].')">';
		}else{echo '<td width="2%" >';}
		echo '</td>
        <td width="2%" >
    <input type="checkbox" name="rewrite_all" id="rewrite_all" onClick="check_all(\'rewrite\', this.checked);" title="'.$lang_grabber['val_rewrite'].' ('.$lang_grabber['val_all'].')"></td>
        <td width="2%" >
    <input  type="checkbox" name="approve"  id="approve"    onClick="check_all(\'mod\', this.checked);" title="'.$lang_grabber['val_mod'].' ('.$lang_grabber['val_all'].')"></td>
        <td width="2%" >
    <input type="checkbox" name="main_all"  id="main_all"   onClick="check_all(\'main\', this.checked);" title="'.$lang_grabber['val_main'].' ('.$lang_grabber['val_all'].')"></td>
        <td width="2%" >
    <input type="checkbox" name="comm_all"  id="comm_all"   onClick="check_all(\'comm\', this.checked);" title="'.$lang_grabber['val_comm'].' ('.$lang_grabber['val_all'].')"></td>
        <td width="2%" >
    <input type=checkbox name="select_all" id="select_all" onClick="checkAll(document.news_form.sel)" title="'.$lang_grabber['val_all'].'">
    </td>
<td width="1%" >
</td>
    </tr>
    </table>
    <table cellpadding="4" cellspacing="0" width="100%">
    <tr>
    <td align="left" style="padding:4px"><input type="button" class="edit"     value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" /> </td>
    <td align="right" style="padding:4px"><div class="quick" ></div>

 <button align="right" type="submit" class="edit" style="background: #FFF; font-size:8pt;" id="checks" disabled> - не найдено ни одной новости - </button>

    </td>
    </tr>
    </table>
</td>
    </tr>
</table>
</div></form>
';
		echo "<form method=post name=\"addnews\" id=\"addnews\">
<input type=hidden name=\"mod\" value=\"preview\">
<input type=hidden name=\"title\" value=\"\">
<input type=hidden name=\"short_story\" value=\"\">
<input type=hidden name=\"full_story\" value=\"\">
<input type=hidden name=\"allow_br\" value=\"1\">
</form>";
		unterline ();
		closetable ();
		echofooter ();
		$db->close;
		return 1;
	}
	if ($action=='config'){
		include $rss_plugins.'config.php';
		return 1;}
		if( $action == 'upload') {
			if (!is_dir($rss_plugins.'files')){
				@mkdir($rss_plugins.'files',0777);
				@chmod($rss_plugins.'files',0777);
			}
			$uploadfile = ENGINE_DIR.'/inc/plugins/files/proxy.txt';
			if (@move_uploaded_file($_FILES['uploadfile']['tmp_name'],$uploadfile) and $_FILES['uploadfile']['type'] == 'text/plain')
			{
				echo '<font color="green">Файл со списком прокси серверов </font> <font color="red">загружен '.date( 'Y-m-d H:i:s',filectime(ENGINE_DIR .'/inc/plugins/files/proxy.txt')).'</font>';
			}else{
				@unlink($uploadfile);
				echo "<font color=\"red\">Ошибка! {$lang['images_uperr_3']}</font>";
			}
			exit();
		}
		if ($action == 'copy_channel')
		{
			$ids = $_POST['channel'];
			if (count ($ids) == 0)
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
				return 1;
			}
			foreach ($ids as $id)
			{
				$copys = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
				$copy = array();
				$sql_result = $db->query ('SELECT url FROM '.PREFIX .'_rss');
				$copys['xpos'] = $db->num_rows ($sql_result) +1;
				$copys['id'] =  '';
				$copys['title'] = '[Копия] '.$copys['title'];
				foreach ($copys as $key =>$value) $copy[$key] = "'".$db->safesql(stripslashes($value))."'";
				$copye = implode(',',$copy);
				$db->query ('INSERT INTO '.PREFIX ."_rss VALUES ({$copye})");
				if (trim ($copy['title']) != '')
				{$title = stripslashes (strip_tags ($copy['title']));
				if (50 <strlen ($title))
				{
					$title = substr ($title,0,50) .'...';
				}
				}
				else
				{
					$title = $lang_grabber['no_title'];
				}
				$mgs .= $lang_grabber['channel'].' '.$copy['xpos'].'<font color="green">"'.$title.' | '.$copy['url'].'"</font> <font color="red">'.$lang_grabber['copy_channel_ok'].'</font><br />';
			}
			msg ($lang_grabber['info'],$lang_grabber['channel_copy'],$mgs ,$PHP_SELF .'?mod=rss');
			return 1;
		}
		if ($action == 'auto_channel')
		{
			$ids = $_POST['channel'];
			if (count ($ids) == 0)
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
				return 1;
			}
			foreach ($ids as $id)
			{
				$auto = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
				$db->query ('UPDATE '.PREFIX ."_rss SET allow_auto = 1 WHERE id ='$id'");
				if (trim ($auto['title']) != '')
				{
					$title = stripslashes (strip_tags ($auto['title']));
					if (50 <strlen ($title))
					{
						$title = substr ($title,0,50) .'...';
					}
				}
				else
				{
					$title = $lang_grabber['no_title'];
				}
				$mgs .= $lang_grabber['channel'].' <font color="green">"'.$title.' | '.$auto['url'].'"</font> <font color="red">'.$lang_grabber['auto_channel_ok'].'</font><br />';
			}
			clear_cache ('cron.rss');
			msg ($lang_grabber['info'],$lang_grabber['channel_auto_y'],$mgs,$PHP_SELF .'?mod=rss');
			return 1;
		}
		if ($action == 'noauto_channel')
		{
			$ids = $_POST['channel'];
			if (count ($ids) == 0)
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
				return 1;
			}
			foreach ($ids as $id)
			{
				$auto = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
				$db->query ('UPDATE '.PREFIX ."_rss SET allow_auto = '0' WHERE id = '$id' ");
				if (trim ($auto['title']) != '')
				{
					$title = stripslashes (strip_tags ($auto['title']));
					if (50 <strlen ($title))
					{
						$title = substr ($title,0,50) .'...';
					}
				}
				else
				{
					$title = $lang_grabber['no_title'];
				}
				$mgs .= $lang_grabber['channel'].' <font color="green">"'.$title.' | '.$auto['url'].'"</font> <font color="red">'.$lang_grabber['auto_channel_no'].'</font><br />';
			}
			clear_cache ('cron.rss');
			msg ($lang_grabber['info'],$lang_grabber['channel_auto_n'],$mgs,$PHP_SELF .'?mod=rss');
			return 1;
		}
		if ($action == 'del_channel')
		{
			$ids = $_POST['channel'];
			if (count ($ids) == 0)
			{
				msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
				return 1;
			}
			if($_POST['act'] == 'sav'){
				$ids = explode ( ',',$ids);
				foreach ($ids as $id)
				{
					$del = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
					$db->query ('DELETE FROM '.PREFIX .('_rss WHERE id = \''.$id .'\''));
					if (trim ($del['title']) != '')
					{
						$title = stripslashes (strip_tags ($del['title']));
						if (50 <strlen ($title))
						{
							$title = substr ($title,0,50) .'...';
						}
					}
					else
					{
						$title = $lang_grabber['no_title'];
					}
					$mgs .= $lang_grabber['channel'].' <font color="green">"'.$title.' | '.$del['url'].'"</font> <font color="red">'.$lang_grabber['del_channel_ok'].'</font><br />';
				}
				clear_cache ('cron.rss');
				msg ($lang_grabber['info'],$lang_grabber['del_channel'],$mgs,$PHP_SELF .'?mod=rss');
				return 1;
			}elseif($_POST['act'] != 'sav'){
				echoheader('','');
				opentable ($lang_grabber['del_channel']);
				foreach ($ids as $id)
				{
					$del = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
					if (trim ($del['title']) != '')
					{
						$title = stripslashes (strip_tags ($del['title']));
						if (50 <strlen ($title))
						{
							$title = substr ($title,0,50) .'...';
						}
					}
					else
					{
						$title = $lang_grabber['no_title'];
					}
					$mgs .= ' <font color="green">"'.$title.' | '.$del['url'].'"</font> <font color="red"><br />';
				}
				$ids = implode ( ',',$ids);
				echo '
<form method="post" name="del_channel" id="del_channel">
<input type="hidden" name="action" value="del_channel">
<input type="hidden" name="act" value="sav">
<table width="100%">
    <tr>
 <td align="center">
<b><font color="red">'.$lang_grabber['del_action'].'</font></b><br /><br />'.$mgs.'
</td>
</tr>
    <tr>
 <td align="center">
 <br />
<input type="hidden" name="channel" value="'.$ids.'">
<input type="submit" class="edit"   value=" '.$lang['opt_sys_yes'].' " ">
<input type="button" class="edit"   value=" '.$lang['opt_sys_no'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" />
</td>
    </tr>
</table>
</form>';
				closetable ();
				echofooter ();
				return 1;
			}
		}
		if ($action == 'sort') {
			$xpos = $_POST['xpos'];
			$i=1;
			foreach ($xpos as $k=>$v)
			{
				$db->query ('UPDATE '.PREFIX .('_rss set xpos='.((int)$v).' WHERE id = \''.((int)$k) .'\''));
				$i++;
			}
			msg ($lang_grabber['info'],$lang_grabber['sort_channel'],$lang_grabber['sort_channel_ok'],$PHP_SELF .'?mod=rss');
			return 1;}
			/*
			if ($config['keygrab'] != $еmpty and $_SERVER['SERVER_ADDR']!='127.0.0.1')
			{
			if($_POST['act'] == 'sav'and $_POST['key']==$еmpty)
			{
			if(!array_key_exists('keygrab',$config))
			{
			$keyz = array ('keygrab'=>$_POST['key'] );
			$cont = $config +$keyz;
			}
			else
			{
			$config['keygrab'] = $_POST['key'];
			unset($config['keyrss']);
			$cont = $config;
			}
			openz(ENGINE_DIR.'/data/config.php',"<?php \n\n\$config = ".var_export ($cont,true).";\n?>");
			msg ($lang_grabber['info'],$lang_grabber['info'],'Ключ записан',$PHP_SELF .'?mod=rss');
			return 1;
			}
			else
			{
			if(!array_key_exists('keygrab',$config))
			{
			$i_control = new image_controller ();
			$result = $i_control->download_host ('http://rss-grabber.ru/eng/akk.php?host='.$_SERVER['HTTP_HOST'],$fg);
			preg_match('!<div>(.*?)</div>!i',$result,$out);
			}
			if ($out[1] == '')
			{
			echoheader ('Ошибка','');
			echo "<div align=\"center\">
			<font color=\"red\">Не законное использование скрипта !!!</font><br /><br />
			<a href=\"http://www.vsest.com\" target=\"_blank\"><font color=\"green\"><b> &copy;</font></b></a><br /><br />Укажите ключ полученный при оплате.<br /><br />
			<form method=\"post\" >
			<input type=\"hidden\" name=\"act\" value=\"sav\">
			<input size=\"35\" name=\"key\" value=\"\" /><br /><br />
			<span style=\"text-align: center;\">
			<input type=\"submit\" value=\"Зарегистрировать\" />
			</span><br><br>
			</form>
			</div>";
			echofooter ();
			exit;
			}
			else
			{
			$keyz = array ('keygrab'=>$out[1]);
			unset($config['keyrss']);
			$cont = $config +$keyz;
			openz(ENGINE_DIR.'/data/config.php',"<?php \n\n\$config = ".var_export ($cont,true).";\n?>");
			}
			};
			}
			*/
			if ($action == 'sinonim') {
				include $rss_plugins.'add.sin.php';
				return 1;
			}
			if ($action == 'get_proxy') {
				include $rss_plugins.'proxy.php';
				if (get_proxy() == true) {
					msg ($lang_grabber['info'],$lang_grabber['info'],'Список прокси серверов обновлён',$PHP_SELF .'?mod=rss&action=config');
				}else{
					$time = time() -filectime(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
					if ( $time <= 3600) $inf = 'Обновление будет доступно через '.date('i',(1200-$time)).' мин.';
					else $inf = 'Не удалось обновить список прокси серверов';
					msg ($lang_grabber['info'],$lang_grabber['info'],$inf ,$PHP_SELF .'?mod=rss&action=config');
				}
				return 1;
			}
			if ($action == 'grups') {
				include $rss_plugins.'add.grups.php';
				return 1;
			}
			if ($action == 'addgrup_channel')
			{
				$ids = $_POST['channel'];
				if (count ($ids) == 0)
				{
					msg ($lang_grabber['info'],$lang_grabber['info'],$lang_grabber['grab_msg_er'],$PHP_SELF .'?mod=rss');
					return 1;
				}
				if($_POST['act'] == 'sav'){
					$sql_result = $db->super_query ('SELECT * FROM '.PREFIX ."_rss_category WHERE id = '{$_POST['rss_priv']}'");
					if ($sql_result['title'] == '')$mgs = '<b><font color=green>Каналы успешно перенесены</font></b> <br /><br />';
					else $mgs = ' <b><font color=green>Каналы успешно перенесены в группу</font> <font color=red>'.$sql_result['title'].'</font></b><br /><br />';
					$ids = explode ( ',',$ids);
					foreach ($ids as $id)
					{
						$del = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
						$categoryes = explode ('=',$del['category']);
						$ht = $categoryes[0].'='.$_POST['rss_priv'];
						$db->query ('UPDATE '.PREFIX ."_rss SET category='$ht' WHERE id = '$id'");
						if (trim ($del['title']) != '')
						{
							$title = stripslashes (strip_tags ($del['title']));
							if (50 <strlen ($title))
							{
								$title = substr ($title,0,50) .'...';
							}
						}
						else
						{
							$title = $lang_grabber['no_title'];
						}
						$mgs .= ' <font color="#C0C0C0">"'.$title.' | '.$del['url'].'"</font><br />';
					}
					msg ($lang_grabber['info'],'<b>ПЕРЕМЕЩЕНИЕ В ГРУППУ</b>',$mgs,$PHP_SELF .'?mod=rss');
					return 1;
				}elseif($_POST['act'] != 'sav'){
					echoheader('','');
					opentable ('<b>ПЕРЕМЕЩЕНИЕ В ГРУППУ</b>');
					$channel_inf = array();
					$sql_result = $db->query ('SELECT * FROM '.PREFIX .'_rss_category ORDER BY kanal asc');
					$run[0] = '';
					while ($channel_info = $db->get_row($sql_result)) {
						if ($channel_info['osn'] == '0')$channel_inf[$channel_info['id']][$channel_info['id']] =  $channel_info['title'];
						else $channel_inf[$channel_info['osn']][$channel_info['id']] = '-- '.$channel_info['title'];
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
					foreach ($ids as $id)
					{
						$del = $db->super_query ('SELECT * FROM '.PREFIX ."_rss WHERE id = '$id'");
						if (trim ($del['title']) != '')
						{
							$title = stripslashes (strip_tags ($del['title']));
							if (50 <strlen ($title))
							{
								$title = substr ($title,0,50) .'...';
							}
						}
						else
						{
							$title = $lang_grabber['no_title'];
						}
						$mgs .= ' <font color="green">"'.$title.' | '.$del['url'].'"</font> <font color="red"><br />';
					}
					$ids = implode ( ',',$ids);
					echo '
<form method="post" name="addgrup_channel" id="addgrup_channel">
<input type="hidden" name="action" value="addgrup_channel">
<input type="hidden" name="act" value="sav">
<table width="100%">
    <tr>
 <td align="center">
<b><font color="red">Вы собираетесь переместить следующие каналы:</font></b><br /><br />'.$mgs.'
<br /></td>
</tr>
<tr>
<td  class="hr_line" colspan=6></td>
</tr>
    <tr>
 <td align="center"><br />
 Выберите группу: <select name="rss_priv" class="load_img">
    '.sel ($run,'').'
   </select><br />
<br /></td>
</tr>
<tr>
<td  class="hr_line" colspan=6></td>
</tr>
    <tr>
 <td align="center">
 <br />
<input type="hidden" name="channel" value="'.$ids.'">
<input type="submit" class="edit"   value=" Переместить " ">
<input type="button" class="edit"   value=" Выйти " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" />
</td>
    </tr>
</table>
</form>';
					closetable ();
					echofooter ();
					return 1;
				}
			}
			if ($action == 'ping') {
				if ($_GET['deletey'])
				{
					openz(ENGINE_DIR.'/cache/system/pinglogs.txt',' ');
					msg ('Пинг','<b>ОЧИСТКА ЛОГА</b>','<font color=green>Лог пинга успешно очищен</font>',$PHP_SELF .'?mod=rss&action=ping');
				}elseif($_GET['delete']){
					echoheader ('','');
					opentable ('<b>'.$lang_grabber['ping_title'].'</b>');
					echo '<center><b><font color=red>Вы действительно хотите очистить лог пинга ?</font></b><br /><br /><input type="button" class="edit"   value=" '.$lang['opt_sys_yes'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=ping&deletey=yes\'" />
<input type="button" class="edit"   value=" '.$lang['opt_sys_no'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=ping\'" />
   </center>';
					closetable ();
					echofooter ();
					return 1;
					msg ('Пинг','<b>'.$lang_grabber['ping_title'].'</b>','<font color=red>Вы действительно хотите очистить лог пинга ?</font>',$PHP_SELF .'?mod=rss&action=ping&deletey=yes');
				}else{
					echoheader ('','');
					opentable ('<b>'.$lang_grabber['ping_title'].'</b>');
					$arr=array_map('trim',file(ENGINE_DIR.'/cache/system/pinglogs.txt'));
					if($arr[0]!='') $all=count($arr);
					if($config_rss['ping_lognum']!='')$pnumber=$config_rss['ping_lognum'];else$pnumber=5;
					echo "<div class=\"quick\">";
					if (isset ($all)){echo ' <b>'.$lang_grabber['ping_writing_all'].' '.$all.' шт</b><br/>';}else{echo '<b>Лог пуст</b>, возможно Вы НЕ ВКЛЮЧИЛИ <font color=red>уведомления пинг сервисов</font>. Сделать это можно в <font color=green>настройках канала</font><br/>';}
					echo "</div>
<style type=\"text/css\" media=\"all\">
.listp {
    color: #999898;
    font-size: 11px;
    font-family: tahoma;
    padding: 5px;
}

.listp a:active,
.listp a:visited,
.listp a:link {
    color: green;
    text-decoration:none;
    }

.listp a:hover {
    color: blue;
    text-decoration: underline;
    }
</style>
<div class=\"listp\">";
					$page=(isset($_GET['page'])) ?(int)$_GET['page'] : 1;
					$num_pages=ceil($all/$pnumber);
					$start=$page*$pnumber-$pnumber;
					if ($page >$num_pages ||$page <1)
					{
						$page=1;
						$start=0;
					}
					if ($all)
					{
						for ($i=$all-$start-1;$i>=$all-$start-$pnumber;$i--)
						{
							if (!isset($arr[$i])) break;
							echo $all-$i.'. '.$arr[$i];
							echo '<br/>';
						}
						echo '</div>';
						if($num_pages == 1){
						}else{
							$npp_nav = "<div class=\"news_navigation\" style=\"margin-bottom:5px; margin-top:5px;\">";
							for($i =1;$i <= $num_pages;$i++)
							{
								if ($i == 1 or $i == $num_pages or abs($i-$page) <10){
									if ($i == $page)$npp_nav .= " <SPAN>$i</SPAN> ";
									else $npp_nav .= ' <a href="'.$PHP_SELF.'?mod=rss&action=ping&page='.$i.'">'.$i.'</a> ';
								}else{
									if ($page+10 == $i ) {
										$npp_nav .= ' <a href="'.$PHP_SELF.'?mod=rss&action=ping&page='.$i.'">'.$i.'</a> ... ';
									}elseif ( $page-10 == $i ){
										$npp_nav .= ' ... <a href="'.$PHP_SELF.'?mod=rss&action=ping&page='.$i.'">'.$i.'</a> ';
									}else{
										$npp_nav .= '';
									}
								}
							}
							$npp_nav .=    '
    <form action="" onsubmit="topage() return false;">
    <script type="text/javascript">
        function topage() {
            var loca = window.location+"";
            var locas = loca.split("page");
                loca = locas[0];
                locas = loca.split("'.$PHP_SELF .'");
            window.location.href = locas[0] + \''.$PHP_SELF .'?mod=rss&action=ping&page=\' + document.getElementById(\'num_page\').value;
        }
    </script>
        <span><input id="num_page" style="background:none; height:15px; width:50px; border:0;"/></span> <a href="#" onclick="topage(); return false;">Перейти</a>
    </form>
    ';
							$npp_nav .= '</div>';
							echo $npp_nav;
						}
					}
					echo '<br><input type="button" class="edit" value=" Очистить лог " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=ping&delete=yes\'" />
<input type="button" class="edit"   value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" />
   ';
					closetable ();
					echofooter ();
				}
				return 1;
			}
			if ($_GET['s'] == 'go')
			{
				$_POST['search'] = $_GET['s'];
				$_POST['key'] = $_GET['k'];
				$_POST['pol'] = $_GET['p'];
			}
			$search = "<form method=\"post\" >
<input type=\"hidden\" name=\"search\" value=\"go\">
Поиск
    <input size=\"25\" class=\"edit\" name=\"key\" value=\"".$_POST['key']."\" /> по
     <select name=\"pol\">";
			$search .= $_POST['pol'] == 'url'?"<option value=\"url\" selected \">ссылке</option>": "<option value=\"url\">ссылке</option>";
			$search .= $_POST['pol'] == 'title'?"<option value=\"title\" selected \">наименованию</option>": "<option value=\"title\">наименованию</option>";
			$search .= $_POST['pol'] == 'xdescr'?"<option value=\"xdescr\" selected \">описанию</option>": "<option value=\"xdescr\">описанию</option>";
			$search .=  "</select>
    <input type=\"submit\" class=\"edit\" value=\"найти\" />
</form>";
			$vose = '
<style type="text/css">
.title_spoiler {
    color: #636363;
    background-color: #f2f2f2;
    border: 1px solid #bebebe;
    font-weight: bold;
    font-size: 9pt;
    padding: 2px;
    margin-top: 5px;
}
.text_spoiler {
    background-color: #f2f2f2;
    border: 1px solid #bebebe;
    border-top: 0;
    margin-bottom: 5px;
}

.title_spoil {
     color: #636363;
     background-color: #DEEFFD;
     border: 1px solid #C8E4FA;
     font-weight: bold;
     font-size: 9pt;
     padding: 2px;
     margin-top: 5px;
     margin-left: 0px;
     margin-right: 0px;
}
.text_spoil {
     background-color: #DEEFFD;
     border: 1px solid #C8E4FA;
     border-top: 0;
     margin-bottom: 5px;
     margin-left: 0px;
     margin-right: 0px;
}
.darkw{ background-color: #E8F9E6}
</style>
';
			$order_by = '';
			$sort_rss = get_vars('rss.sort');
			if( !$_POST['dlenewssortby'] ) {
				if($sort_rss[0] == ''or $sort_rss[1] == '')$order_by = 'xpos DESC ,title DESC';
				else $order_by = $sort_rss[0].' '.$sort_rss[1];
			}else{$order_by = $_POST['dlenewssortby'].' '.$_POST['dledirection'];
			$sort_rss = array(0 =>$_POST['dlenewssortby'],1 =>$_POST['dledirection']);
			set_vars('rss.sort',$sort_rss);
			}
			$channel_inf = array();
			$grup_result = $db->query ('SELECT * FROM '.PREFIX .'_rss_category ORDER BY kanal asc');
			$channel_inf[0] = '';
			while ($channel_info = $db->get_row($grup_result)) {
				$channel_inf[$channel_info['id']] = $channel_info['osn'];
			}
			if ($_POST['search'] == 'go'and $_POST['key'] != ''){
				if($_POST['pol'] == '')$_POST['pol'] = 'url';
				$sql_result = $db->query ('SELECT * FROM '.PREFIX .'_rss WHERE '.$_POST['pol']." like '%".$_POST['key']."%' ORDER BY $order_by");
				$empty = $db->num_rows ($sql_result) == 0;
				$hk = $db->num_rows ($sql_result);
			}else{
				$sql_result = $db->query ('SELECT * FROM '.PREFIX ."_rss ORDER BY $order_by");
				$empty = $db->num_rows ($sql_result) == 0;
			}
			if ($empty)
			{
				$vose .= '
    <form method="post" name="rss_form" id="rss_form">
<span id="channels"></span>
<table cellpadding="4" cellspacing="0" width="100%">
    <tr>
    <td height="40" valign="middle" align="center" class="navigation">- Список каналов пуст -</td>
    </tr>
    </table>';
			}else{
				$vose .= '<input style="display:none" type="checkbox"  name="tables" id="tables" value="" />';
				$vose .= "
<script>
function checkAll(field){
  nb_checked=0;
  for(n=0;n<field.length;n++)
    if(field[n].checked)nb_checked++;
    if(nb_checked==field.length){
      for(j=0;j<field.length;j++){
        field[j].checked=!field[j].checked;
        field[j].parentNode.parentNode.style.backgroundColor
          =field[j].backgroundColor==''?'#E8F9E6':'';
      }
    }else{
      for(j=0;j<field.length;j++){
        field[j].checked = true;
        field[j].parentNode.parentNode.style.backgroundColor
          ='#E8F9E6';
      }document.news_set_sort.check_all.checked=true;
    }
}
function selectRow(evnt,elmnt){
  var ch=elmnt.getElementsByTagName(\"TD\")[5].firstChild;
  tg = document.all?evnt.srcElement:evnt.target;
  if(tg.tagName!='INPUT')ch.checked=!ch.checked;
  elmnt.style.backgroundColor=ch.checked?'#E8F9E6':'';

document.channels(document.rss_form.channel.length);
}

function ShowOrHidegr( id, name ) {
      var item = document.getElementById(id);
      if ( document.getElementById('image-'+ id) ) {
        var image = document.getElementById('image-'+ id);
      } else {
        var image = null;
      }
      if (!item) {
        retun;
      }  else {
        if (item.style) {
            if (item.style.display == \"none\") {
                item.style.display = \"table\";
                image.src = '/engine/inc/plugins/images/minus.gif';
                var curCookie = id + \"=\" + '1';
            } else {
                item.style.display = \"none\";
                image.src = '/engine/inc/plugins/images/plus.gif';
                var curCookie = id + \"=\" + '';
            }
         } else{ item.visibility = \"show\"; }
      }
document.cookie = curCookie;
};

function ShowOrHidegrp( id, name ) {
      var item = document.getElementById(id);
      if ( document.getElementById('images-'+ id) ) {
        var images = document.getElementById('images-'+ id);
      }
      if (!item) {
        retun;
      }  else {
        if (item.style) {
            if (item.style.display == \"none\") {
                item.style.display = \"table\";
                images.src = '/engine/inc/plugins/images/p-minus.gif';
                var curCookie = id + \"=\" + '1';
            } else {
                item.style.display = \"none\";
                images.src = '/engine/inc/plugins/images/p-plus.gif';
                var curCookie = id + \"=\" + '';
            }
         } else{ item.visibility = \"show\"; }
      }
document.cookie = curCookie;
};

function ShowOrHideAll() {
var show = document.getElementById('tables');
    var item = document.getElementsByTagName('table');
for(n=0;n<item.length;n++){
      if (!item[n]) {
        retun;
      }  else {
        if (item[n].style.display ) {

      if ( document.getElementById('image-'+ item[n].id) ) {
        var image = document.getElementById('image-'+ item[n].id);
      }     else {
        var image = null;
      }
      if ( document.getElementById('images-'+ item[n].id) ) {
        var images = document.getElementById('images-'+ item[n].id);
      } else {
        var images = null;
      }
if (!show.checked) {
            if (item[n].style.display == \"none\") {
                item[n].style.display = \"table\";
                if(image)   image.src = '/engine/inc/plugins/images/minus.gif';
                if(images) images.src = '/engine/inc/plugins/images/p-minus.gif';
var curCookie = item[n].id + \"=\" + '1';
            }
            }else {
                item[n].style.display = \"none\";
                if(image)image.src = '/engine/inc/plugins/images/plus.gif';
                if(images) images.src = '/engine/inc/plugins/images/p-plus.gif';
var curCookie = item[n].id + \"=\" + '';
            }
         }
         else{ item[n].visibility = \"show\"; }

      }
document.cookie = curCookie;
}

if (show.checked)
    {
    show.checked = false;
    }else{
        show.checked = true;}

};


</script>";
				if ($еmpty){
				$vose .= news_sort_rss($_POST['dlenewssortby'],$_POST['dledirection']);
				$vose .= '<div>
    <form method="post" name="rss_form" id="rss_form">
<span id="channels"></span>
    <table cellpadding="6" align="center" cellspacing="0" width="100%" border="0">
    <tr>
    <td colspan="6">';
				$vose .= '<div class="unterline"></div>';
				$vose .= '</td></tr></table>';
				$rss_kanal = array();
				$cat_rss  = array();
				while ($row = $db->get_row ($sql_result))
				{
					$rss_kan = '';
					if (trim ($row['title']) != '')
					{
						$title = stripslashes (strip_tags ($row['title']));
						if (50 <strlen ($title))
						{
							$title = substr ($title,0,50) .'...';
						}
					}
					else
					{
						$title = $lang_grabber['no_title'];
					}
					if ($row['xdescr']) {
						$xdescr = htmlspecialchars($row['xdescr']);
						if (strlen($xdescr)>50) {
							$xdescr = substr($xdescr,0,50).'...';
						}
					}else {
						$xdescr = '&nbsp;';
					}
					if ($row['allow_auto'] == 0){$auto = '';}else{$auto = '<font color=green><b>'.$lang['opt_sys_yes'].'</b></font>';}
					if ($row['rss'] == 0){$rss = '<font color=blue>HTML</font>';}else{$rss = '<font color=red>RSS</font>';}
					$row['url'] = stripslashes ($row['url']);
					$row['descr'] = stripslashes ($row['descr']);
					$categoryes = explode ('=',$row['category']);
					$del=array();
					if (intval($categoryes[1]) != '0'){
						if (intval($channel_inf[$categoryes[1]]) != '0') $style_grups = 'text_spoil';
						else $style_grups = 'text_spoiler';
					}else{$style_grups = 'light';}
					$rss_kan = '<tr class="'.$style_grups.'" onMouseOut=this.className="'.$style_grups.'"
       onMouseOver=this.className="highlight"
       onclick=selectRow(event,this)>
        <td width="5%" style="padding:1px" align="center"><input type="text" name="xpos['.$row['id'].']" value="'.$row['xpos'].'" class="edit" align="center" size="3" /></td>
        <td width="5%" style="padding:1px" align="center">'.$rss.'</td>
        <td width="6%" style="padding:1px" align="center">'.$auto.'</td>
        <td style="padding:4px">
        <a href="'.$row['url'] .'" target=\"_blank\">[i]</a>&nbsp;<a href="'.$PHP_SELF .'?mod=rss&action=channel&subaction=edit&id='.$row['id'] .'" class="hintanchor" onMouseover="showhint(\'<b>'.$row['url'] .'</b><br/>'.$row['descr'] .'\', this, event, \'300px\');">'.$title .'</a></td>
        <td style="padding:4px" align="center">'.$xdescr.'</td>
        <td width="3%"><input style="background-color: #ffffff; color: #ff0000;" title="'.$lang_grabber['val_post'].'" type="checkbox" name="channel[]" id="channel" value="'.$row['id'] .'" />
        </td>
     </tr>
     <tr>';
					if (intval($categoryes[1]) != '0'and array_key_exists($categoryes[1],$channel_inf)) {
						if (intval($channel_inf[$categoryes[1]]) != '0') $cat_rss[$channel_inf[$categoryes[1]]][$categoryes[1]][$row['id']] = $rss_kan;
						else $cat_rss[$categoryes[1]][0][$row['id']] = $rss_kan;
					}else{
						$rss_kanal[$row['id']] = $rss_kan;
					}
				}
				if (count($cat_rss) != '0')
				{
					$grups_rss = array();
					foreach ($cat_rss as $papka =>$kanals){
						$grups_r = '';
						$del = $db->super_query ('SELECT * FROM '.PREFIX ."_rss_category WHERE id= '".$papka."'");
						$id_spoiler = spoiler( $del['title'] );
						$vose .= "    <script type=\"text/javascript\">
    $(document).ready( function() {
       $(\"#ch_$id_spoiler\").click( function() {
            if($('#ch_$id_spoiler').attr('checked')){
                $(\"#\" + $(this).attr('name') + \" input:checkbox:enabled\").attr('checked', true);
                $(\"#\" + $(this).attr('name') + \" tr\").toggleClass(\"highlight\");
            } else {
                $(\"#\" + $(this).attr('name') + \" input:checkbox\").attr('checked', false);
            }
       });
    });
</script>";
						$kol = '';
						$kol = count($kanals,1) -count($kanals);
						if  (count($kanals) == '1'){$kol = count($kanals,1) -count($kanals);}
						else
						{
							$kols = count($kanals) -1;
							$kol = count($kanals,1) -count($kanals).'|'.$kols;
						}
						if ($_COOKIE[$id_spoiler] != 1) {
							$strp = 'style="display:none"';
							$strp_i = 'src="/engine/inc/plugins/images/plus.gif"';
						}else {
							$strp = 'style="display:table"';
							$strp_i = 'src="/engine/inc/plugins/images/minus.gif"';
						}
						$grups_r .= '<table width="100%" border="0" ><div class="title_spoiler"><img id="image-'.$id_spoiler.'" style="vertical-align: middle;border: none;" alt="" '.$strp_i.' />&nbsp;<a href="javascript:ShowOrHidegr(\''.$id_spoiler.'\', \'rss_sp_'.$papka.'\')">'.$del['title'].' ('.$kol.')</a>

<input type="checkbox"  name="'.$id_spoiler.'" id="ch_'.$id_spoiler.'" value="" alt="Выдилить все каналы" title="Выдилить все каналы"/>
</div>

</table>
<table id="'.$id_spoiler.'" name="rss_sp_'.$papka.'" cellpadding="6" align="center" cellspacing="0" width="100%" border="0" class="text_spoiler" '.$strp.' >';
						ksort ($kanals);
						foreach ($kanals as $papk =>$kanal){
							if ($papk != 0){
								$osn = $db->super_query ('SELECT * FROM '.PREFIX ."_rss_category WHERE id= '".$papk."'");
								$id_spoil = spoiler( $osn['title'] );
								$vose .= "    <script type=\"text/javascript\">
    $(document).ready( function() {
       $(\"#ch_$id_spoil\").click( function() {
            if($('#ch_$id_spoil').attr('checked')){
                $(\"#\" + $(this).attr('name') + \" input:checkbox:enabled\").attr('checked', true);
$(\"#\" + $(this).attr('name') + \" tr\").removeClass(\"darkw\");
$(\"#\" + $(this).attr('name') + \" tr\").addClass(\"text_spoil\");

            } else {
                $(\"#\" + $(this).attr('name') + \" input:checkbox\").attr('checked', false);
                $(\"#\" + $(this).attr('name') + \" tr\").removeClass(\"text_spoil\");
                $(\"#\" + $(this).attr('name') + \" tr\").addClass(\"darkw\");
            }
       });
    });
</script>";
								if ($_COOKIE[$id_spoil] != 1) {
									$strj = 'style="display:none"';
									$strj_i = 'src="/engine/inc/plugins/images/p-plus.gif"';
								}else {
									$strj = 'style="display:table"';
									$strj_i = 'src="/engine/inc/plugins/images/p-minus.gif"';
								}
								$grups_r .= '<tr><td colspan=6 ><table width="100%" border="0"><div class="title_spoil"><img id="images-'.$id_spoil.'" style="vertical-align: middle;border: none;" alt="" '.$strj_i.' />&nbsp;<a href="javascript:ShowOrHidegrp(\''.$id_spoil.'\', \'rss_sp_'.$papk.'\')">'.$osn['title'].' ('.count($kanal).')</a>

<input type="checkbox" name="'.$id_spoil.'" id="ch_'.$id_spoil.'" value="" alt="Выделить все каналы" title="Выделить все каналы"/>
</div>
</table>
<table id="'.$id_spoil.'" name="rss_sp_'.$papk.'" cellpadding="6" align="center" cellspacing="0" width="100%" border="0" class="text_spoil" '.$strj.' >'.implode('<td background="engine/skins/images/mline.gif" height=1 colspan=6></td></tr>',$kanal).'</table></td></tr> ';
							}
						}
						if (count($kanals[0]) >0)$grups_r .= implode('<td background="engine/skins/images/mline.gif" height=1 colspan=8></td></tr>',$kanals[0]).'</table> ';
						$grups_rss[$del['kanal']]= $grups_r;
					}
					ksort ($grups_rss);
					$vose .= implode($grups_rss);
				}
			}
}
if (count($rss_kanal) != 0)$vose .= '<table cellpadding="6" align="center" cellspacing="0" width="100%" border="0">'.implode('<td background="engine/skins/images/mline.gif" height=1 colspan=6></td></tr>',$rss_kanal).'<td background="engine/skins/images/mline.gif" height=1 colspan=6></td></tr></table>';
$vose .= '<table cellpadding="4" width="100%"><tr><td colspan="6"><br/>';
$vose .= '<div class="unterline"></div>';
$vose .= '  </td></tr> <tr>
    <td colspan="5">
    <table width="100%" border="0">
     <tr>
<td align="left" width="270">';
if ($_POST['search'] == 'go'and $_POST['key'] != ''){
	$vose .= '
<input type="button" class="edit" value="Вернуться на главную" onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" />
';
}
$vose .= '
        <input type="button" class="edit" value="Добавить канал" onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=channel&subaction=add\'" />
     </td>
     <td style="padding:2px" align="right" colspan="2">'.$lang['xfield_xact'].':
     <select name="action">
        <option value="scan" selected style="background: #EFEFEF;">'.$lang['rss_news'].'</option>
        <option value="scan1">'.$lang_grabber['rss_news'].$lang_grabber['post_sm'].'</option>
        <option value="scan3">'.$lang_grabber['rss_news'].'3'.$lang_grabber['post_sm'].'</option>
        <option value="scan5">'.$lang_grabber['rss_news'].'5'.$lang_grabber['post_big'].'</option>
        <option value="scan10">'.$lang_grabber['rss_news'].'10'.$lang_grabber['post_big'].'</option>
        <option value="scan15">'.$lang_grabber['rss_news'].'15'.$lang_grabber['post_big'].'</option>
        <option value="scan20">'.$lang_grabber['rss_news'].'20'.$lang_grabber['post_big'].'</option>';
if ($config_rss['news_kol'] != ''){$vose .= '<option value="scan'.$config_rss['news_kol'].'">'.$lang_grabber['rss_news'].$config_rss['news_kol'].$lang_grabber['post_big'].'</option>';}
$vose .= '<option value="auto_channel" style="background: #EFEFEF; color:green">'.$lang_grabber['channel_auto_y'].'</option>
        <option value="noauto_channel">'.$lang_grabber['channel_auto_n'].'</option>
        <option value="copy_channel" style="background: #EFEFEF; color:orange; font: bold 110% ;">'.$lang_grabber['channel_copy'].'</option>
        <option value="sort" style="background: #EFEFEF; color:blue">'.$lang_grabber['channel_sort'].'</option>
        <option value="del_channel" style="background: #EFEFEF; color:red">'.$lang_grabber['channel_del'].'</option>
<option value="save_channel" style="background: #EFEFEF;">Экспорт</option>
<option value="save_up_channel" style="background: #EFEFEF;">Импорт</option>
        <option value="addgrup_channel" style="background: #EFEFEF; color:red">Переместить в группу</option>
<option value="editgrup_channel">Групповое изменение</option>
     </select></td>
	 <td align="left" style="padding:2px" class="navigation"> с <input type="text" class="edit" name="str_news" size="3" value=""/> по <input type="text" class="edit" name="str_newf" size="3" value=""/> новость
     </td>
     <td align="right" rowspan="2" width="50">
        <input type="submit" class="edit" style="height: 40px;" value="'.$lang_grabber['b_start'].'"/>
     </td>
	 </tr>
	 <tr>     <td align="left" colspan="3" style="font: bold italic 110% serif;" class="navigation">'.$lang_grabber['help_run'].'</td>

<td style="padding:2px" align="left" class="navigation" width="150"> с <input type="text" class="edit" name="str_kans" size="3" value=""/> по <input type="text" class="edit" name="str_kanf" size="3" value=""/> страницу</td>
</tr>
    </table>
    </td></tr>
    </table></form>';
if (count($cat_rss) != '0') $spoi = '  <a href="javascript:ShowOrHideAll()"><font color=orange>&uarr; СВЕРНУТЬ / РАЗВЕРНУТЬ &darr;</font></a>';
echoheader ('','');
check_disable_functions ();
opentable ($lang_grabber['rss_list'].$spoi,$tr.$search);
if ($_POST['search'] == 'go'and $_POST['key'] != ''){
	echo ' <table width="100%" border=0>
    <tr><td><font color="#999898">Вы искали: <font color="green">'.$_POST['key'].'</font><br />
Найдено совпадений: <font color="blue">'.$hk.'</font></font></td></tr><tr><td background="engine/skins/images/mline.gif" height=1 colspan=6></td></tr></table>';
}
echo $vose;
closetable ();
opentable ();
tableheader ($lang_grabber['tabs_extra']);
echo ' <table width="100%" cellpadding="4">
    <tr><td align="left">
        <input type="button" class="edit"   value=" '.$lang_grabber['glob_options'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=config\'" />

<input type="button" class="edit"   value=" Группы " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=grups\'" />';
if (@file_exists (ENGINE_DIR .'/inc/plugins/sinonims.php') )
{echo'
<input type="button" class="edit"   value=" '.$lang_grabber['sinonims_bottom'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=sinonim\'" />';
}echo'

</td><td align=right>

<input type="button" class="edit"   value=" Лог отправки пингов " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss&action=ping\'" />
     </td></tr>
    <tr>
        <td colspan=6 align="right" style="padding-top:5px;" class="navigation"><a href="http://www.vsest.com" target="_blank">'.$module_info['name'].' '.$module_info['version'].' build'.$module_info['build'].'</a> | Copyright 2009-2011 &copy created by Andersoni. All rights reserved.</td>
    </tr>
 </table>';
closetable ();
if ($action == 'update') {
	/*
	$data = @file('http://rss-grabber.ru/grabbers.php');
	echo md5($data['0']).'<br />';
	echo md5($module_info['version']).'<br />';
	if ($data['0'] >$module_info['version'] or $data['5'] >$module_info['build']) {
		echo "<script>
function closead()
{
    var obj = document.getElementById( \"ad\" );
    obj.style.visibility = \"hidden\";
}
</script>
<body>
<div style=\"background: #fff; border: 1px solid #CAD2C9; padding: 5px; position: absolute; top: 12%; left: 20%;     width: 600px;\" id=\"ad\" align=\"center\">
<div style=\"background: #A0C6E3; padding: 5px; font-weight: bold; font-size: large; text-align: center; text-transform: uppercase; letter-spacing: 0.2em;\" align=\"center\">ОБНОВЛЕНИ СКРИПТА</div>
<p>
            <br /><font color=\"red\">{$data['1']} {$module_info['version']} build{$module_info['build']}</font><br /><br /><b><a href=\"http://www.vsest.com\"><font color=\"green\">{$data['2']} {$data['3']} build{$data['5']}</font></a></b><br /><br />{$data['4']}
</p>
<p style=\"text-align: right;\">
<a href=\"javascript:closead();\">{$lang_grabber['out']}</a>
</p>
</div>";
	}else{
		echo "<script>
function closead()
{
    var obj = document.getElementById( \"ad\" );
    obj.style.visibility = \"hidden\";
}
</script>
<body>
<div style=\"background: #fff; border: 1px solid #CAD2C9; padding: 5px; position: absolute; top: 12%; left: 20%;     width: 600px;\" id=\"ad\" align=\"center\">
<div style=\"background: #A0C6E3; padding: 5px; font-weight: bold; font-size: large; text-align: center; text-transform: uppercase; letter-spacing: 0.2em;\" align=\"center\">ОБНОВЛЕНИЕ СКРИПТА</div>
<p>
     <font color=\"green\">{$lang_grabber['update']}
 {$data['3']}!!!</font>
</p>
<p style=\"text-align: right;\">
<a href=\"javascript:closead();\">{$lang_grabber['out']}</a>
</p>
</div>";
	}
	*/
}
echofooter ();
clear_cache();
$db->close;
?>