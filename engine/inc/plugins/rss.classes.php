<?php

if (!(defined ('DATALIFEENGINE')))
{
exit ('Hacking attempt!');
}

if (!function_exists('array_merge')){
function array_merge ($array1, $array2){
	$aray = array();
foreach ($array1 as $value1)$aray[] = $value1;
foreach ($array2 as $value2)$aray[] = $value2;
var_export ($aray);
return ;
}
}
@require_once ENGINE_DIR .'/data/rss_config.php';
if ($config_rss['DOCUMENT_ROOT'] !='' and $config_rss['http_url'] !='')define ( 'ROOTS_DIR', $config_rss['DOCUMENT_ROOT'] );
else define ( 'ROOTS_DIR', ROOT_DIR );

class rss_parser
{
var $default_cp = '';
var $CDATA = 'nochange';
var $cp = '';
var $items_limit = 0;
var $stripHTML = False;
var $date_format = '';
var $channeltags = array (0 =>'title',1 =>'link',2 =>'description',3 =>'language',4 =>'copyright',5 =>'managingEditor',6 =>'webMaster',7 =>'lastBuildDate',8

=>'rating',9 =>'docs');
var $itemtags = array (0 =>'title',1 =>'link',2 =>'description',3 =>'author',4 =>'category',5 =>'comments',6 =>'enclosure',7 =>'guid',8 =>'pubDate',9

=>'source');
var $imagetags = array (0 =>'title',1 =>'url',2 =>'link',3 =>'width',4 =>'height');
var $textinputtags = array (0 =>'title',1 =>'description',2 =>'name',3 =>'link');
function get ($rss_url ,$proxy)
{
$result = $this->Parse ($rss_url,$proxy);
return $result;
}
function my_preg_match ($pattern,$subject)
{
preg_match ($pattern,$subject,$out);
if (isset ($out[1]))
{
if ($this->CDATA == 'content')
{
$out[1] = strtr ($out[1],array ('<![CDATA['=>'',']]>'=>''));
}
else
{
if ($this->CDATA == 'strip')
{
$out[1] = strtr ($out[1],array ('<![CDATA['=>'',']]>'=>''));
}
}
return trim ($out[1]);
}
return '';
}
function unhtmlentities ($string)
{
$trans_tbl = get_html_translation_table (HTML_ENTITIES,ENT_QUOTES);
$trans_tbl = array_flip ($trans_tbl);
$trans_tbl += array ('&apos;'=>'\'');
return strtr ($string,$trans_tbl);
}
function parse ($rss_url ,$proxy)
{
global $row,$config_rss,$config;
$cookies = '';
$link = get_urls($rss_url);
$rss_content = get_full ($link[scheme],$link['host'],$link['path'],$link['query'],$cookies,$proxy);
if ($rss_content != '')
{
if ($this->default_cp != '' or $this->default_cp != '0')$this->default_cp =reset( explode("/",$this->default_cp));

if ($this->default_cp == '' or $this->default_cp == '0'){
preg_match ('#<.*?encoding="(.*?)".*?>#i',$rss_content,$charset);
if ($charset[1] =='')preg_match ('#<.*?encoding=\'(.*?)\'.*?>#i',$rss_content,$charset);
if ($charset[1] =='') $charset[1] = charset ($rss_content);
}else{$charset[1] = $this->default_cp;}
//openz(ENGINE_DIR.'/data/rss_co.php', @iconv(strtolower($charset[1]),strtolower($config['charset']).'//IGNORE',$rss_content));
$result['html_title'] = $this->my_preg_match ('#<title>(.*?)</title>#is',$rss_content);
if (strtolower($charset[1]) != strtolower($config['charset'])) {$result['html_title'] = @iconv(strtolower($charset[1]),strtolower($config['charset']).'//IGNORE',trim($result['html_title']));}
preg_match ('\'<channel.*?>(.*?)<item.*?>\'si',$rss_content,$out_channel);
foreach ($this->channeltags as $channeltag)
{
$temp = $this->my_preg_match ('\'<'.$channeltag .'.*?>(.*?)</'.$channeltag .'>\'si',$out_channel[1]);
if ($temp != '')
{
if (strtolower($charset[1]) != strtolower($config['charset'])) {$temp =

@iconv(strtolower($charset[1]),strtolower($config['charset']).'//IGNORE',trim($temp));}
$result[$channeltag] = $temp;
continue;
}
}
if ($this->date_format != '')
{
if ($timestamp = strtotime ($result['lastBuildDate']) !== -1)
{
$result['lastBuildDate'] = date ($this->date_format,$timestamp);
}
}
preg_match ('\'<textinput(|[^>]*[^/])>(.*?)</textinput>\'si',$rss_content,$out_textinfo);
if (isset ($out_textinfo[2]))
{
foreach ($this->textinputtags as $textinputtag)
{
$temp = $this->my_preg_match ('\'<'.$textinputtag .'.*?>(.*?)</'.$textinputtag .'>\'si',$out_textinfo[2]);
if ($temp != '')
{
if (strtolower($charset[1]) != strtolower($config['charset'])) {$temp =

@iconv(strtolower($charset[1]),strtolower($config['charset']).'//IGNORE',trim($temp));}
$result['textinput_'.$textinputtag] = $temp;
continue;
}
}
}
preg_match ('\'<image.*?>(.*?)</image>\'si',$rss_content,$out_imageinfo);
if (isset ($out_imageinfo[1]))
{
foreach ($this->imagetags as $imagetag)
{
$temp = $this->my_preg_match ('\'<'.$imagetag .'.*?>(.*?)</'.$imagetag .'>\'si',$out_imageinfo[1]);
if ($temp != '')
{
if (strtolower($charset[1]) != strtolower($config['charset'])) {$temp =

@iconv(strtolower($charset[1]),strtolower($config['charset']).'//IGNORE',trim($temp));}
$result['image_'.$imagetag] = $temp;
continue;
}
}
}
preg_match_all ("#<item(.*?)>(.*?)<\/item>#is",$rss_content,$items);
$rss_items = $items[2];
$i = 0;
$result['items'] = array ();
foreach ($rss_items as $rss_item)
{
if (!((!($i <$this->items_limit) AND !($this->items_limit == 0))))
{
foreach ($this->itemtags as $itemtag)
{
$temp = $this->my_preg_match ('\'<'.$itemtag .'.*?>(.*?)</'.$itemtag .'>\'si',$rss_item);
if ($temp != '')
{if (strtolower($charset[1]) != strtolower($config['charset'])) {$temp =

@iconv(strtolower($charset[1]),strtolower($config['charset']).'//IGNORE',trim($temp));}
$result['items'][$i][$itemtag] = $temp;
continue;
}
}
if ($this->stripHTML)
{
if ($result['items'][$i]['description'])
{
$result['items'][$i]['description'] = strip_tags ($this->unhtmlentities (strip_tags ($result['items'][$i]['description'])));
}
}
if ($this->stripHTML)
{
if ($result['items'][$i]['title'])
{
$result['items'][$i]['title'] = strip_tags ($this->unhtmlentities (strip_tags ($result['items'][$i]['title'])));
}
}
if ($this->date_format != '')
{
if ($timestamp = strtotime ($result['items'][$i]['pubDate']) !== -1)
{
$result['items'][$i]['pubDate'] = date ($this->date_format,$timestamp);
}
}
++$i;
continue;
}
}
$result['charset'] = $charset[1];
$result['items_count'] = $i;
return $result;
}
return False;
}
}
function strip_image ($text)
{
$text = preg_replace ('#\(\S+?\)#i','',$text);
$quotes = array ('\'','"','`','	','\'',',','/','¬',';',':','@','~','[',']','{','}','=',')','(','*','&','^','%','$','<','>','?','!','"','-');
$text = trim (strip_tags ($text));
$text = str_replace ($quotes,'',$text);
return $text;
}
class image_controller
{var $img;
var $img_orig = '';
var $img_thumb = '';
var $short_story = '';
var $full_story = '';
var $allow_watermark = false;
var $images = array ();
var $short_images = array ();
var $full_images = array ();
var $thumbs = array ();
var $prefix = '';
var $upload_images = array ();
var $upload_image = array ();
var $image = array ();
var $image_url = array ();
var $dim_week = '';
var $post = '';
var $posts = '';
var $max_up_side = 0;
var $radikal = false;



function image_host ($url){
if ($this->dubl == 0 ){
$image_host = @file(ENGINE_DIR.'/inc/plugins/files/image_host.txt');
foreach ($image_host as $it){
$it = addcslashes(stripslashes(trim($it)),'"[]!-.?*\\()|/');
if (preg_match('#'.$it.'#i',$url)) return false;
}
}
return true;
}
function rewrite_im($images,$image_new)
{
$image_new = current(explode ('.',$image_new));
if ($this->rewrite == 1 and count($images) != 0){
foreach ($images as $image_old ){
$image_news = end (explode ('/',$image_old));
if (preg_match('#'.trim($image_new).'#i',trim($image_old) ) )return $image_news;
}
}
return false;
}
function reset_url($url)
{
$value = str_replace('http://','',$url);
$value = str_replace('www.','',$value);
return reset(explode('/',$value));
}
function full_get_images ($content)
{
preg_match_all ('#\[thumb.*?\](.+?)\[\/thumb\]#i',$content,$preg_array);
if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $item)
{
if (!(in_array ($item,$this->full_images)) and !(in_array ($item,$this->short_images)))
{
$this->full_images[] = $item;
continue;
}
}
}
preg_match_all ('#\[img.*?\](.+?)\[\/img\]#i',$content,$preg_array);
if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $item)
{
if (!(in_array ($item,$this->full_images)) and !(in_array ($item,$this->short_images)))
{
$this->full_images[] = $item;
continue;
}
}
}
}
function short_get_images ($content)
{
preg_match_all ('#\[thumb.*?\](.+?)\[\/thumb\]#i',$content,$preg_array);
if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $item)
{
if (!(in_array ($item,$this->short_images)))
{
$this->short_images[] = $item;
continue;
}
}
}
preg_match_all ('#\[img.*?\](.+?)\[\/img\]#i',$content,$preg_array);
if (count ($preg_array[1]) != 0)
{
foreach ($preg_array[1] as $item)
{
if (!(in_array ($item,$this->short_images)))
{
$this->short_images[] = $item;
continue;
}
}
}
}
function serv ($image_url,$i)
{
global $config,$config_rss;
if ($this->dim_week != '')$this->prefix = $this->dim_week.'_';
else $this->prefix = time ();
if (!is_dir(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data)) {
@mkdir(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data,0777);
chmod_pap(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data);
@mkdir(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data.'/thumbs',0777);
chmod_pap(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data.'/thumbs');
}
$image_news = basename ($image_url);
$image_arr = explode ('_',end (explode ('/',$image_news)));
if (count ($image_arr) != 0)$imag_new = strip_image(end ($image_arr));
if ($this->dim_sait == 1 or $this->dim_cat == 1 or $this->dim_week != ''){
$pref = '';
if ($this->dim_sait == 1)$pref .= $this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url'])).'_';
if ($this->dim_cat == 1 and $this->cat != '')$pref .= $this->cat.'_';
$image_new = $pref.$this->prefix.mt_rand(10,99).$i.'.'.end (explode ('.',$imag_new));
}else{
$image_new = $this->prefix.mt_rand(10,99).$i.$imag_new;
}
$image_new = str_replace ('%27','',$image_new);
$rewrites = $this->rewrite_im($this->listimages,$imag_new);

if ($this->rewrite != 1 or count($this->listimages) == 0){
if ($this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']))!= $this->reset_url($image_url))
{
if (chmod_pap($this->img_orig))
{

if(function_exists( 'curl_init') ) {

$info = info_host($image_url);
$image_u= $info['url'];
//var_export ($info);
if ($info['http_code'] == '404' or substr($info['http_code'], -3,1) == '5')return false;

$ch=curl_init ();
curl_setopt($ch,CURLOPT_URL, $image_u);
if ($this->proxy == 1){
if ($config_rss['proxy_file'] == 'yes'or $config_rss['proxy'] == ''){
$proxy_url = @file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
$proxy_url = $proxy_url[array_rand($proxy_url)];
}else{$proxy_url = $config_rss['proxy'];}
if (trim($proxy_url) != '')curl_setopt($ch,CURLOPT_PROXY,trim($proxy_url));
}
$fp =fopen($this->img_orig .$image_new,'w+b');
curl_setopt ($ch,CURLOPT_FILE,$fp);
curl_setopt($ch,CURLOPT_USERAGENT,get_random_agent ());
curl_setopt ($ch,CURLOPT_REFERER,"http://".reset_url($image_u));
curl_setopt ($ch,CURLOPT_AUTOREFERER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
//@curl_setopt ($ch,CURLOPT_FOLLOWLOCATION,1);
curl_exec ($ch);
curl_close ($ch);
fclose ($fp);
}else{@copy($image_url ,$this->img_orig .$image_new);}
if (chmod_file($this->img_orig .$image_new) == false)
{
$image_new = $image_news;
return false;
}
}else{return false;}
}else{$image_new = $image_name;}
}else{
$image_new = $rewrites;
}
if (!(in_array ($image_url,$this->image)))
{
$this->image[$image_new] = $image_url;
}
return true;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function process ($download)
{
define ('$this->img_orig','');
define ('$this->img_thumb','');
global $config, $config_rss, $options_host;
if ($download != 'serv' and $download != '0')$download = $this->chek_serv ($download);
if (!is_dir(ROOTS_DIR.'/uploads/posts'.$this->post)){
@mkdir(ROOTS_DIR.'/uploads/posts'.$this->post,0777);
chmod_pap(ROOTS_DIR.'/uploads/posts'.$this->post);
}
$this->pap_data = '/'.date('Y-m');
if (!is_dir(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data)) {
@mkdir(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data,0777);
chmod_pap(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data);
@mkdir(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data.'/thumbs',0777);
chmod_pap(ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data.'/thumbs');
}
if ($this->dim_date == 1)$this->pap_data .= '/day_'.date('d');
$this->img_orig = ROOTS_DIR.'/uploads/posts'.$this->post.$this->pap_data.'/';
$this->img_thumb = $this->img_orig .'thumbs/';

$eror = array();
if (trim ($this->short_story) != '')
{
$this->short_get_images ($this->short_story);
}
if (trim ($this->full_story) != '')
{
$this->full_get_images ($this->full_story);
}

$this->images = array_unique (array_merge($this->short_images,$this->full_images));
if ($download == 'serv')
{
$i= 0;
foreach ($this->images as $image_url)
{
++$i;
unset ($rz);
if (check_url($image_url) == true){
for ($x=0;$x<1;$x++){
if ($this->serv($image_url,$i) == false){$rz = $image_url;
}else{
unset ($rz);
break;
}
}
}else{$rz = $image_url;}
if (isset($rz) == true)$eror[] = $rz;
}
$this->parseserv ($this->image);
}else{
	if ($this->shs == true)
{
if ($download != '0'){
if (intval($this->wat_h) == 1){
$eror = $this->rezerv_host ($this->full_images ,$download);
}else{
	///////////////////////////////////////
foreach ($this->full_images as $image_url)
{
unset ($rz);
if (check_url($image_url) == true){
if ($this->image_host($image_url) == true){
if ($this->$download ($image_url)==false) $rz = $image_url;
}
}else{$rz = $image_url;}
if (isset($rz) == true)$eror[] = $rz;
}
}
}
///////////////////////////////////////////////
$i= 0;
foreach ($this->short_images as $image_url)
{
++$i;
if (check_url($image_url) == true){
for ($x=0;$x<1;$x++){
if ($this->serv($image_url,$i) == false){$rz = $image_url;}
else{
unset ($rz);
break;
}
}
}else{$rz = $image_url;}
if (isset($rz) == true)$eror[] = $rz;
}
$this->parseserv ($this->image);
}else{
if (intval($this->wat_h) == 1){
$eror = $this->rezerv_host ($this->images ,$download);
}else{
	/////////////////////////////////////////
foreach ($this->images as $image_url){
unset ($rz);
if (check_url($image_url) == true){
if ($this->image_host($image_url) == true){
if ($this->$download ($image_url)==false) $rz = $image_url;
}
}else{$rz = $image_url;}
if (isset($rz) == true)$eror[] = $rz;
}
}
//////////////////////////////////////////////
}
}
if (count($eror) != 0)
	{
if ($download != 'serv' and $download != '0')$eror = $this->rezerv_host ($eror , $this->chek_serv ($download));
	}
return $eror;
}

function chek_serv ($download){
global $options_host;
if (check_url('http://'.$options_host[$download]) == true)return $download;
unset ($options_host['0'],$options_host['serv'],$options_host[$download]);
while (count($options_host) != 0){
$download_d = array_rand ($options_host);
if (check_url('http://'.$options_host[$download_d]) == true)break;
else unset ($options_host[$download_d]);
	}
return $download_d;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////


function parseserv ($timage, $wat_host = false)
{global $config,$config_rss;
foreach ($timage as $image_new =>$image_url)
{
$image_news = basename ($image_url);
$image_arr = explode ('_',end (explode ('/',$image_news)));
if (count ($image_arr) != 0)$imag_new = strip_image(end ($image_arr));
$rewrites = $this->rewrite_im($this->listimages,$imag_new);
if ($rewrites == 0){
if ($this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']))!= $this->reset_url($image_url))
{
if (@file_exists($this->img_orig .$image_new)){
$imageSizeInfo = @getimagesize( $this->img_orig .$image_new);
if ($imageSizeInfo[2] == '1'){$imageType = 'gif';}
if ($imageSizeInfo[2] == '2'){$imageType = 'jpeg';}
if ($imageSizeInfo[2] == '3'){$imageType = 'png';}
$image_form = explode ('.',$image_new);
if ($imageType != end($image_form) and $imageType)
{
if (count($image_form) >= 2)$image_name = str_replace (end($image_form),$imageType,$image_new);
else $image_name = $image_new.'.'.$imageType;

@rename ($this->img_orig .$image_new,$this->img_orig .$image_name);
}else{$image_name = $image_new;}
chmod_file($this->img_orig .$image_name);
}
if (in_array($imageSizeInfo[2],array('1','2','3',)) and filesize($this->img_orig .$image_name) != 0)
{

require_once ENGINE_DIR .'/inc/plugins/thumb.class.php';
if (intval($this->max_up_side) != 0) {
$thumb = new rss_thumbnail ($this->img_orig .$image_name);
$thumb->size_auto( $this->max_up_side );
$thumb->jpeg_quality ($config['jpeg_quality']);
$thumb->save ($this->img_orig .$image_name);
chmod_file( $this->img_orig .$image_name);
unset ($thumb);
}

$thumb = new rss_thumbnail ($this->img_orig .$image_name);
if ($thumb->size_auto($config['max_image'],$config['t_seite']) and $config['max_image'] != '0' and $wat_host == false)
{
$thumb->jpeg_quality ($config['jpeg_quality']);
if($this->allow_watermark)
{
$thumb->watermark_image_light = $this->watermark_image_light;
$thumb->watermark_image_dark = $this->watermark_image_dark;
$thumb->x = $this->x;
$thumb->y = $this->y;
$thumb->margin = $this->margin;
$thumb->insert_watermark ($config['max_watermark']);
}
$thumb->save ($this->img_thumb .$image_name);
unset ($thumb);
}

if($this->allow_watermark)
{
$thumb = new rss_thumbnail ($this->img_orig .$image_name);
$thumb->watermark_image_light = trim($this->watermark_image_light);
$thumb->watermark_image_dark = trim($this->watermark_image_dark);
$thumb->x = $this->x;
$thumb->y = $this->y;
$thumb->margin = $this->margin;
$thumb->insert_watermark ($config['max_watermark']);
$thumb->save ($this->img_orig .$image_name);
chmod_file( $this->img_orig .$image_name);
unset ($thumb);
}
}else{
@unlink($this->img_orig .$image_name);
return false;
}
}else{$image_new = $image_name;}
}else{$image_name = $image_new;}
$imager_url = '';
$serv_url = ($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']).'uploads/posts'.$this->post.$this->pap_data .'/'.$image_name;
$imager_url = trim(addcslashes(stripslashes($image_url),'"[]!-.?*\\()|/'));
//echo "<textarea style=\"width:98%;\" >{$imager_url}</textarea>";
if ($this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']))!= $this->reset_url($image_url))
{
if (chmod_file( $this->img_thumb .$image_name) == true){

$this->short_story = str_replace ("[img=left]".$image_url."[/img]","[thumb=left]".$serv_url."[/thumb]",$this->short_story);
$this->short_story = str_replace ("[img=right]".$image_url."[/img]","[thumb=right]".$serv_url."[/thumb]",$this->short_story);
$this->short_story = str_replace ("[img]".$image_url."[/img]","[thumb]".$serv_url."[/thumb]",$this->short_story);


$this->full_story = str_replace ("[img=left]".$image_url."[/img]","[thumb=left]".$serv_url."[/thumb]",$this->full_story);
$this->full_story = str_replace ("[img=right]".$image_url."[/img]","[thumb=right]".$serv_url."[/thumb]",$this->full_story);
$this->full_story = str_replace ("[img]".$image_url."[/img]","[thumb]".$serv_url."[/thumb]",$this->full_story);

$this->short_story =preg_replace("#(\[thumb(.*)\]".$imager_url."\[\/thumb\])#i","[thumb\\2]".$serv_url."[/thumb]",$this->short_story);
$this->full_story = preg_replace( "#(\[thumb(.*)\]".$imager_url."\[\/thumb\])#i","[thumb\\2]".$serv_url."[/thumb]",$this->full_story);
}else{
$this->short_story = preg_replace ('#\[thumb(.*)\]('.$imager_url.')\[\/thumb\]#i',"[img\\1]".$serv_url."[/img]",$this->short_story);
$this->full_story = preg_replace ('#\[thumb(.*)\]('.$imager_url.')\[\/thumb\]#i',"[img\\1]".$serv_url."[/img]",$this->full_story);
$this->short_story = preg_replace ("#".$imager_url."#i",$serv_url,$this->short_story);
$this->full_story = preg_replace ("#".$imager_url."#i",$serv_url,$this->full_story);
}
}
//echo "<textarea style=\"width:98%;\" >".$this->full_story."</textarea>";
if (!(in_array ($image_name,$this->upload_images)))
{
$this->upload_image[$image_url] = $serv_url;
$this->upload_images[] = $image_name;
}

}
}
function download_host ($url,$fg = '')
{
$urls = str_replace(' ', '%20',$url);
$fg = str_replace(' ', '%20',$fg);
$rh = curl_init();
curl_setopt($rh,CURLOPT_URL,$urls);
curl_setopt($rh,CURLOPT_HEADER,0);
curl_setopt($rh,CURLOPT_CONNECTTIMEOUT,120);
curl_setopt($rh,CURLOPT_TIMEOUT,120);
curl_setopt($rh,CURLOPT_USERAGENT,get_random_agent ());
curl_setopt($rh,CURLOPT_ENCODING,'');
@curl_setopt($rh,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($rh,CURLOPT_RETURNTRANSFER,1);
curl_setopt($rh,CURLOPT_POST,1);
curl_setopt($rh,CURLOPT_POSTFIELDS,$fg);
curl_setopt($rh,CURLOPT_FAILONERROR,1);
@
$result = curl_exec($rh);
curl_close($rh);
if ($result !='')
return $result;
else
return false;

}

function parsehost ($image_url,$result,$pr = 1)
{
$image_url = addcslashes(stripslashes($image_url),'"[]!-.?*\\()|/');
if ($pr == '1'or $pr == ''){
$this->short_story = preg_replace ('#\[img(.*?)\]'.$image_url.'\[\/img\]#i','[img\\1]'.$result.'[/img]',$this->short_story);
$this->full_story = preg_replace ('#\[img(.*?)\]'.$image_url.'\[\/img\]#i','[img\\1]'.$result.'[/img]',$this->full_story);
$this->short_story = preg_replace( "#\[thumb(.*?)\]".$image_url."\[\/thumb\]#i",'[img\\1]'.$result.'[/img]',$this->short_story);
$this->full_story = preg_replace( "#\[thumb(.*?)\]".$image_url."\[\/thumb\]#i",'[img\\1]'.$result.'[/img]',$this->full_story);
}else{
$this->short_story = preg_replace ('#\[img(.*?)\]'.$image_url.'\[\/img\]#i',$result,$this->short_story);
$this->full_story = preg_replace ('#\[img(.*?)\]'.$image_url.'\[\/img\]#i',$result,$this->full_story);
$this->short_story = preg_replace( "#\[thumb(.*?)\]".$image_url."\[\/thumb\]#i",$result,$this->short_story);
$this->full_story = preg_replace( "#\[thumb(.*?)\]".$image_url."\[\/thumb\]#i",$result,$this->full_story);
}
}

function get_host ($url)
{
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_USERAGENT,get_random_agent ());
@curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt ($ch,CURLOPT_AUTOREFERER,1);
curl_setopt( $ch,CURLOPT_CONNECTTIMEOUT,5);
@
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
function clipkey ($image_url)
{
global $config,$config_rss;
$url = 'http://www.im.sexkey.ru/upload.php';
$fg = 'typ=u&u_'.date('d',time()).'1='.$image_url;
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match('!<META.*?URL=(.*?)\">!i',$data,$out);
$data = $this->get_host ($out[1]);
preg_match('!<input.*?value="(.*?)".*?>!i',$data,$out);
if ($config_rss['url_img_sklad'] == ''or $config_rss['url_img_sklad'] == '1'){
preg_match('!<input.*?value=".*?\[img\](.*?)\[\/img\].*?>!i',$data,$out);
$out[1] = str_replace ('thumb','image',$out[1]);
$pr= '1';
}else{preg_match('!<input.*?value="(.*?)".*?>!i',$data,$out);
$out[1] = str_replace ('thumb','image',$out[1]);
$pr= '2';
}
echo $out[1];
if ($out[1] !=''){
$this->parsehost ($image_url,$out[1],$pr);
return true;}
}
}
return false;
}

function radikal ($image_url)
{
global $config,$config_rss;
$url = 'http://www.radikal.ru/action.aspx';
if ($config_rss['water_radikal'] == 'yes'and $config_rss['post_radikal'] == ''){
$water_radikal = '&XE=yes&X='.$this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']));
}else{$water_radikal = '&XE=yes&X='.convert('cp1251','utf-8',$config_rss['post_radikal']);
}
$fg = 'upload=yes'.$thumbs.'&URLF='.$image_url.'&JQ=100&IM=7&VM='.$config['max_image'].$water_radikal;
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data != ''){
if ($config_rss['url_radikal'] == '')$url_radikal = '1';else $url_radikal = $config_rss['url_radikal'];
preg_match('!<input id="input_link_'.$url_radikal.'" value="(.*?)".*?>!i',$data,$out);
if ($config_rss['url_radikal'] == '1'or $config_rss['url_radikal'] == '') $pr= '1';
if ($out[1] !=''){$this->parsehost ($image_url,$out[1],$pr);
return true;}
}
}
return false;
}

function hostpix ($image_url)
{
global $config,$config_rss;
$url = 'http://hostpix.ru/upload.php';
$fg = 'url='.$image_url.'&thumb_size=500';
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?>!i',$data,$out);
if ($config_rss['url_hostpix'] == ''or $config_rss['url_hostpix'] == '1'){
$new_url = $out[1][2];
$pr= '1';
}else{
$new_url = $out[1][0];
$pr= '2';
}
if ($new_url !=''){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}

function zikuka_pr ($image_url)
{
global $config,$config_rss;
$url = 'http://www.radikal.ru/action.aspx';
if ($config_rss['water_radikal'] == 'yes'and $config_rss['post_radikal'] == ''){
$water_radikal = '&XE=yes&X='.$this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']));
}else{$water_radikal = '&XE=yes&X='.convert('cp1251','utf-8',$config_rss['post_radikal']);
}
$fg = 'upload=yes'.$thumbs.'&URLF='.$image_url.'&JQ=100&IM=7&VM='.$config['max_image'].$water_radikal;
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data != ''){
if ($config_rss['url_radikal'] == '')$url_radikal = '1';else $url_radikal = $config_rss['url_radikal'];
preg_match('!<input id="input_link_'.$url_radikal.'" value="(.*?)".*?>!i',$data,$out);
if ($config_rss['url_radikal'] == '1'or $config_rss['url_radikal'] == '') $pr= '1';
if ($out[1] !=''){$this->parsehost ($image_url,$out[1],$pr);
return true;}
}
}
return false;
}

function zikuka ($image_url)
{
global $config,$config_rss;
$url = 'http://zikuka.ru:8080/upload.php';
$fg = 'uploadtype=2&userurl='.$image_url.'&method=file';
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
if ($config_rss['url_zikuka'] == ''or $config_rss['url_zikuka'] == '1'){
preg_match('!<input id="bbCode3".*?<img src=\'(.*?)\'.*?/>!i',$data,$out);
$pr= '1';
}else{preg_match('!<input id="THL1".*?value="(.*?)".*?/>!i',$data,$out);
$pr= '2';
}
if ($out[1] !=''){$this->parsehost ($image_url,$out[1],$pr);
return true;}
}
}
return false;
}

function epikz ($image_url)
{
global $config,$config_rss;
$url = 'http://epikz.net/remote.php';
$fg = 'links='.$image_url;
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?/>!i',$data,$out);
if ($config_rss['url_epikz'] == ''or $config_rss['url_epikz'] == '1'){
$image_host = $out[1][0];
$pr= '1';
}else{
$image_host = $out[1][2];
$pr= '2';
}

if ($image_host !=''){$this->parsehost ($image_url,$image_host,$pr);
return true;}
}
}
return false;
}


function wwwpix ($image_url)
{
global $config,$config_rss;

$url = 'http://www.10pix.ru/';
$fg = 'uploadType=1&url='.$image_url.'&sizeBar=0';
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
if ($config_rss['url_10pix'] == ''or $config_rss['url_10pix'] == '1'){
preg_match('!<input type="text" style="width: 500px;".*?\[IMG\](.*?)\[\/img\].*?>!i',$data,$out);
$out[1] = str_replace ('.th','',$out[1]);
$pr= '1';
}else{preg_match('!<input type="text" style="width: 500px;".*?value=\'(.*?)\'.*?>!i',$data,$out);
$pr= '2';
}
if ($out[1] !=''){$this->parsehost ($image_url,$out[1],$pr);
return true;}
}
}
return false;
}

function immage ($image_url)
{
global $config,$config_rss;
$url = 'http://immage.de/upload.html';
$fg = 'upart=zusammen&remote[0]='.$image_url.'&drehen0=0&umwandeln0=0&thumbg0=1&thumbinfos0=0';
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?>!i',$data,$out);
if ($config_rss['immage'] == ''or $config_rss['immage'] == '1'){
$new_url = $out[1][5];
$pr= '1';
}else{
$new_url = $out[1][1];
$pr= '2';
}
if (preg_match('#http#i', $new_url)){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}

function imageshack ($image_url)
{
global $config,$config_rss;
$url = 'http://www.imageshack.us/transload.php';
$fg = 'uploadtype=on&url='.$image_url;
for ($x=0;$x<1;$x++){
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?>!i',$data,$out);
if ($config_rss['imageshack'] == ''or $config_rss['imageshack'] == '1'){
$new_url = $out[1][4];
$pr= '1';
}else{
$new_url = $out[1][5];
$pr= '2';
}
if ($new_url !=''){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}

function tinypic ($image_url)
{
global $config,$config_rss;
for ($x=0;$x<1;$x++){
$rez = $this->get_host ('http://tinypic.com/');
preg_match ('#<form action="http:\/\/([A-z0-9]*)\.tinypic\.com\/upload\.php".*?id="uid" value="(.*?)".*?name="upk" value="(.*?)"#is',$rez,$out);
$url = 'http://'.$out[1].'.tinypic.com/upload.php';
$fg = 'UPLOAD_IDENTIFIER='.$out[2].'&upk='.$out[3].'&domain_lang=en&action=upload&MAX_FILE_SIZE=500000000&shareopt=true&url='.$image_url.'&file_type=url&dimension=1600&video-settings=sd';
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?>!i',$data,$out);
$new_url = 'http://i'.$out[1][2].'.tinypic.com/'.$out[1][0];
$pr= '1';
if (intval($out[1][2]) != 0 and $out[1][0] != ''){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}

function ambrybox ($image_url)
{
global $config,$config_rss;
if ($config_rss['water_ambrybox'] == 'yes'and $config_rss['post_ambrybox'] == ''){
$water = '&string=true&string_text='.$this->reset_url(($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']));
}else{$water = '&string=true&string_text='.convert('cp1251','utf-8',$config_rss['post_ambrybox']);
}
for ($x=0;$x<1;$x++){
$url = 'http://i1.ambrybox.com/_scripts/';
$fg = 'quality=85&upurl='.$image_url.$water;
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?>!i',$data,$out);
$new_url = 'http://i1.ambrybox.com/'.$out[1][5].'/'.$out[1][0];
$pr= '1';
if ($out[1][0] !='' and $out[1][5] != ''){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}

function shituf ($image_url)
{
global $config,$config_rss;
for ($x=0;$x<1;$x++){
$url = 'http://shituf.org/inc/uploaderurl.php';
$fg = "urls=".$image_url."&thumb_size=500";
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all("!upload\('.*?','.*?','.*?','.*?','.*?','\|(.*?)','.*?','\|(.*?)','.*?','.*?','.*?','.*?'\)!i", $data, $out);
$pr= '1';
if ($out[1][0] != ''){$this->parsehost ($image_url,$out[1][0],$pr);
return true;}
}
}
return false;
}

function fastpic ($image_url)
{
global $config,$config_rss;
if ($config_rss['water_fastpic'] == 'yes' and trim($config_rss['post_fastpic']) == ''){
$water = '&check_thumb=text&thumb_text='.$this->reset_url($config['http_home_url']);
}else{$water = '&check_thumb=text&thumb_text='.convert('cp1251','utf-8',$config_rss['post_fastpic']);
}
if ($config_rss['water_fastpic'] == 'no')$water = '&check_thumb=size';
for ($x=0;$x<1;$x++){
$url = 'http://fastpic.ru/upload_copy?api=1';
if (intval($config_rss['thumb_fastpic']) == 0)$config_rss['thumb_fastpic']=$config['max_image'];
$fg = "files=".$image_url."&uploading=1&orig_rotate=0&thumb_size=".$config_rss['thumb_fastpic'].$water;
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match('!<imagepath>(.*?)</imagepath>!i',$data,$out);
if ($config_rss['url_fastpic'] == '' or $config_rss['url_fastpic'] == '1'){
$new_url = $out[1];
$pr= '1';
}else{
preg_match('!<thumbpath>(.*?)</thumbpath>!i',$data,$ouut);
$new_url = '[url='.$out[1].'][img]'.$ouut[1].'[/img][/url]';
$pr= '2';
}
if ($out[1] !=''){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}

function fotonons ($image_url)
{
global $config,$config_rss;
for ($x=0;$x<1;$x++){
$url = 'http://fotonons.ru/';
$fg = "remota=".$image_url;
$data = $this->download_host($url,$fg);
if ($data !=''){

if ($config_rss['url_fotonons'] == '' or $config_rss['url_fotonons'] == '1'){
preg_match('!<input tabindex="5"value="(.*?)".*?>!i', $data, $out);
$pr= '1';
}else{
preg_match('!<input tabindex="2"value="(.*?)".*?>!i', $data, $out);
$pr= '2';
}
if ($out[1] != ''){$this->parsehost ($image_url,$out[1],$pr);
return true;}
}
}
return false;
}


function rezerv ($image_url)
{
global $config,$config_rss;
for ($x=0;$x<1;$x++){
$rez = $this->get_host ('http://tinypic.com/');
preg_match ('#<form action="http:\/\/([A-z0-9]*)\.tinypic\.com\/upload\.php".*?id="uid" value="(.*?)".*?name="upk" value="(.*?)"#is',$rez,$out);
$url = 'http://'.$out[1].'.tinypic.com/upload.php';
$fg = 'UPLOAD_IDENTIFIER='.$out[2].'&upk='.$out[3].'&domain_lang=en&action=upload&MAX_FILE_SIZE=500000000&shareopt=true&url='.$image_url.'&file_type=url&dimension=1600&video-settings=sd';
$data = $this->download_host($url,$fg);
if ($data !=''){
preg_match_all('!<input.*?value="(.*?)".*?>!i',$data,$out);
$new_url = 'http://i'.$out[1][2].'.tinypic.com/'.$out[1][0];
$pr= '1';
if (intval($out[1][2]) != 0){$this->parsehost ($image_url,$new_url,$pr);
return true;}
}
}
return false;
}


function rezerv_host ($naw, $download){
	global $config;
$i= 0;
var_export ($naw);
foreach ($naw as $image_url)
{
++$i;
if (check_url($image_url) == true){
for ($x=0;$x<1;$x++){
if ($this->serv($image_url,$i) == false){$rz = $image_url;}
else{
unset ($rz);
break;
}
}
}else{$rz = $image_url;}
if (isset($rz) == true)$eror[] = $rz;
}
$this->parseserv ($this->image, true);
if ($download != 'serv' and $download != '0'){
foreach ($this->upload_images as $key => $image_name){
unset ($rz);
if (@filesize($this->img_orig .$image_name) != 0){
$image_url = ($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']) .'uploads/posts'.$this->post.$this->pap_data .'/'.$image_name;
if ($this->$download ($image_url)==false) {
$rz = $image_url;
}else{
 @unlink($this->img_orig .$image_name);
unset ($this->upload_images[$key]);
}
}else{$rz = $image_url;}
if (isset($rz) == true)$eror[] = $rz;
}
}
return $eror;
}








}
$options_host = array
('0'=>'Донор','serv'=>'Сервер','radikal'=>'radikal.ru','clipkey'=>'clipkey.ru','zikuka'=>'zikuka.ru','wwwpix'=>'10pix.ru','immage'=>'immage.de','imageshack'=>'imageshack.us','tinypic'=>'tinypic.com','ambrybox'=>'ambrybox.com','epikz'=>'epikz.net','shituf'=>'shituf.org','fastpic'=>'fastpic.ru','fotonons'=>'fotonons.ru');
function server_host($selected) {
global $options_host;
$output = '';
foreach ( $options_host as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( $selected == $value ) {
$output .= ' selected ';
}
if ($value== ''){
$output .= ' style="color:blue" ';}
elseif($value== 'serv'){
$output .= ' style="color:green" ';}
else{
$output .= ' style="color:red" ';}
$output .= ">$description</option>\n";
}
return $output;
}
function java_host() {
global $options_host;
$output = "  <script type=\"text/javascript\">
    function onImgChange(value) {
ShowOrHideEx(\"0\", value == \"0\");\n";
foreach ( $options_host as $value =>$description ) {
$output .= "ShowOrHideEx(\"".$value."\", value == \"".$value."\");\n";
}
$output .= '};
</script>';
return $output;
}

function check_url($url,$proxy=0) {


return true;
}

function chmod_pap($file) {

        if(file_exists($file)){
        if(is_writable($file)){
            return true;
        }
        else{
            @chmod($file, 0777);
            if(is_writable($file)){
                return true;
            }else{
                @chmod($file, 0755);
                if(is_writable($file)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        }else{return false;}
}
function chmod_file($file) {

        if(@file_exists($file)){
        if(is_writable($file)){
            return true;
        }
        else{
            @chmod($file, 0644);
            if(is_writable($file)){
                return true;
            }else{
                @chmod($file, 0666);
                if(is_writable($file)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        }else{return false;}
}


function donlowd_serv ($url,$dirs)
{
global $config,$config_rss;
if ($url != ''){
$diru = ROOTS_DIR.'/uploads/'.$dirs;
$dir = ROOTS_DIR.'/uploads/'.$dirs.'/'.date('Y-m').'/';
if (!is_dir($diru)) {
@mkdir($diru,0777);
chmod_pap($diru);
}
if (!is_dir($dir)) {
@mkdir($dir,0777);
}

$news = basename ($url);
$arr = explode ('_',end (explode ('/',$news)));
if (count ($arr) != 0)$imag_new = strip_image(end ($arr));

$new = time().mt_rand(10,99).'_'.$imag_new;

$new = str_replace ('%27','',$new);

if (chmod_pap($dir))
{
$last_url = parse_url($url);


if(function_exists('curl_init') ) {
$u = str_replace(' ', '%20',$url);
$info = info_host($u);
$u = $info['url'];
if ($info['http_code'] == '404' or substr($info['http_code'], -3,1) == '5')return $url;
$ch=curl_init ();
curl_setopt($ch,CURLOPT_URL,$u);
if ($proxy == 1){
if ($config_rss['proxy_file'] == 'yes'or $config_rss['proxy'] == ''){
$proxy_url = @file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
$proxy_url = $proxy_url[array_rand($proxy_url)];
}else{$proxy_url = $config_rss['proxy'];}
if (trim($proxy_url) != '')curl_setopt($ch,CURLOPT_PROXY,trim($proxy_url));
}
$fp =fopen($dir.$new,'w+b');
curl_setopt ($ch,CURLOPT_FILE,$fp);
curl_setopt($ch,CURLOPT_USERAGENT,get_random_agent ());
curl_setopt ($ch,CURLOPT_REFERER,"http://".reset_url($u));
curl_setopt ($ch,CURLOPT_AUTOREFERER,1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
$cookie_file = ENGINE_DIR.'/cache/system/'.reset_url($u).'.txt';
echo $cookie_file;
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt ($ch,CURLOPT_FOLLOWLOCATION,1);
curl_exec($ch);

fclose ($fp);
}else{@copy($url ,$dir.$new);}

$serv_url = ($config_rss['http_url'] != ''?$config_rss['http_url']:$config['http_home_url']).'uploads/'.$dirs.'/'.date('Y-m').'/'.$new;
//echo "<textarea style=\"width:98%;\" >{$imager_url}</textarea>";
}else{$serv_url = $url;}

}
return $serv_url;
}



function info_host ($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20',$url));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    @curl_setopt( $ch, CURL_NOBODY, 1);
    curl_redirect( $ch );
    $info = curl_getinfo($ch);

	preg_match ('|charset=(\S+)|i', $info[content_type], $charset);
    curl_close($ch);
//var_export ($info);
	return $info;
}



function curl_redirect( $ch )
{
$loops = 0;
$max_loops = 10;

if ($loops++ >= $max_loops)
{
$loops = 0;
return FALSE;
}
$data = curl_exec($ch);
$temp = $data;
list($header, $data) = explode("\n\n", $data, 2);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http == 301 || $http == 302) {
$matches = array();
preg_match('/ocation:(.*?)\n/', $header, $matches);
$url = @parse_url(trim(array_pop($matches)));
// print_r($url);
if (!$url)
{
$loops = 0;
return $data;
}
$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
if (!$url['scheme'])
$url['scheme'] = $last_url['scheme'];
if (!$url['host'])
$url['host'] = $last_url['host'];
if (!$url['path'])
$url['path'] = $last_url['path'];

$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
//echo "\n redirect to ".$new_url;

    curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20',$new_url));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    @curl_setopt( $ch, CURL_NOBODY, 1);
return curl_redirect($ch);
} else {
$loops=0;
return $temp;
}
}





?>
