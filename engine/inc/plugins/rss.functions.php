<?
if (!(defined ('DATALIFEENGINE')))
{
exit ('Hacking attempt!');
}
function dubl_news ($selected = 0)
{
global $lang_grabber;
$source = array ('Не проверять','url','Заголовку','url и Заголовку');
$buffer = '';
for ($i = 0;$i <= 3;++$i)
{
if ($i == $selected)
{
$buffer .= '<option value="'.$i .'" selected>'.$source[$i] .'</option>
';
continue;
}
else
{
$buffer .= '<option value="'.$i .'">'.$source[$i] .'</option>
';
continue;
}
}
return $buffer;
}
function relace_news_don ($story)
{
preg_match_all('#\(get_file=(.*?),(.*?)\)#ie',$story,$outs);
if (count ($outs[1]) != 0)
{
foreach ($outs[1] as $item)
{
$get_file = donlowd_serv($item,$outs[2][0]);
$story = str_replace('(get_file='.$item.','.$outs[2][0].')',$get_file,$story);
$story = str_replace('( get_file='.$item.','.$outs[2][0].')',$get_file,$story);
}
}
return $story;
}
function get_proxy(){
$time = time() -filectime(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
if ( $time >= 1200)
{
$link = get_urls('http://spys.ru/');
$proxy_content = get_full ($link[scheme],$link['host'],$link['path'],$link['query'],$cookies,$proxy);
preg_match_all('!(\d+\.\d+\.\d+\.\d+:\d+)!',$proxy_content,$tran);
$tr = '';
foreach ($tran[1] as $value)
{
$tr .= $value.'
';
}
openz(ENGINE_DIR.'/inc/plugins/files/proxy.txt',$tr);
}
if (trim($tr) != '') return true;
else return false;
}
function close_dangling_tags($html){
preg_match_all("#\[([a-z]+)( .*)?(?!/)\]#iU",$html,$result);
$openedtags=$result[1];
preg_match_all("#\[/([a-z]+)\]#iU",$html,$result);
$closedtags=$result[1];
$len_opened = count($openedtags);
if(count($closedtags) == $len_opened){
return $html;
}
$openedtags = array_reverse($openedtags);
for($i=0;$i <$len_opened;$i++) {
if (!in_array($openedtags[$i],$closedtags)){
$html .= '[/'.$openedtags[$i].']';
}else {
unset($closedtags[array_search($openedtags[$i],$closedtags)]);
}
}
return $html;
}
function rss_xfields($t) {
$va = array('0'=>'');
$list = array_map('trim',file(ENGINE_DIR.'/data/xfields.txt'));
foreach ($list as $key){
$value = explode ('|',$key);
$va[$value[0]] = $value[$t];
}
return $va;
}
function charset($str) {
global $config;
preg_match ('|<meta.*?charset=(.*?)\".*?>|i',$str,$charset);
if ($charset[1] == '')preg_match ("|<meta.*?charset=(.*?)\'.*?>|i",$str,$charset);
if ($charset[1] == 'ISO-8859-1')
{$char = 'utf-8';}else{$char= $charset[1];}
if ($char == '')$char = $config['charset'];
return strtolower($char);
}
function convert ( $from,$to,$string ) {
if (function_exists('iconv')) {
return @iconv($from,$to.'//IGNORE',$string);
}else {
return $string;
}
}
$nds = $nd;
function get_title ($full)
{
preg_match('#<title>(.*)&raquo;.*</title>#i',$full,$titls);
if ($titls[1] == '')preg_match('#<title>(.*)</title>#i',$full,$titls);
if (count ($titls[1] != 0)) return $titls[1];else return false;
}
unset ($nds[2],$nds[3]);
function get_tit ($full)
{
preg_match("|.*?title=\"(.*?)\".*?|i",$full,$titls);
if ($titls[1] == '')preg_match('|.*?title=\'(.*?)\'.*?|i',$full,$titls);
if (count ($titls[1] != 0)) return $titls[1];
else return false;}
function get_fullink ($full )
{
preg_match('|<a href=\\"(.+)\\">Комментарии.*</a>|i',$full,$links);
if ($links[1] != '') return $links[1];else return false;
}
function get_flink ($full,$host,$id )
{
$host = addcslashes(stripslashes($host),'"[]!-.?*\\()|/');
preg_match("#<a.*?href[=]?[='\"](\S+?".$host."\S+?".$id."\S+?html)['\" >].*?>.*?<\/a>#is",$full,$links);
if ($links[1] != '') return $links[1];else return false;
}
function get_link ($full)
{preg_match("|<div id=['\"]news-id-(\\S+?)['\"].*>|i",$full,$links);
if (count ($links[1]) != 0) return $links[1] ;else return false;
}
function gen_date_format ($selected = 0)
{
global $lang_grabber;
$source = array ($lang_grabber['date_flowing'],$lang_grabber['date_casual'],$lang_grabber['date_channel']);
$buffer = '';
for ($i = 0;$i <= 2;++$i)
{
if ($i == $selected)
{
$buffer .= '<option value="'.$i .'" selected>'.$source[$i] .'</option>
';
continue;
}
else
{
$buffer .= '<option value="'.$i .'">'.$source[$i] .'</option>
';
continue;
}
}
return $buffer;
}
function sel($options,$selected = 0) {
$output = '';
if(count($options) !='0'){
foreach ( $options as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( $selected == $value ) {
$output .= ' selected ';
}
$output .= ">$description</option>\n";
}
}
return $output;}
$ndr = $nd;
function gen_x ($selected = 0)
{
global $lang;
$source = array ($lang['opt_sys_right'],$lang['opt_sys_center'],$lang['opt_sys_left'],$lang['opt_sys_none']);
$buffer = '';
for ($i = 0;$i <= 3;++$i)
{
if ($i == $selected)
{
$buffer .= '<option value="'.$i .'" selected>'.$source[$i] .'</option>
';
continue;
}
else
{
$buffer .= '<option value="'.$i .'">'.$source[$i] .'</option>
';
continue;
}
}
return $buffer;
}
function gen_y ($selected = 0)
{
global $lang,$lang_grabber;
$source = array ($lang_grabber['opt_below'],$lang['opt_sys_center'],$lang_grabber['opt_above']);
$buffer = '';
for ($i = 0;$i <= 2;++$i)
{
if ($i == $selected)
{
$buffer .= '<option value="'.$i .'" selected>'.$source[$i] .'</option>
';
continue;
}
else
{
$buffer .= '<option value="'.$i .'">'.$source[$i] .'</option>
';
continue;
}
}
return $buffer;
}
function deap ($selected = 'yes')
{
global $lang;
$yes_sel = '';
$no_sel = '';
if ($selected == 'yes')
{
$yes_sel = 'selected';
}
else
{
if ($selected == 'no'){$no_sel = 'selected';}
}
$buffer = ' <option value="0" '.$yes_sel .' style="color:blue">'.$lang['edit_dnews'].'</option>
 <option value="1" '.$no_sel .' style="color:red">'.$lang['mass_edit_notapp'].'</option>'.'';
return $buffer;
}
function yesno ($selected = 'yes')
{
global $lang;
$yes_sel = '';
$no_sel = '';
if ($selected == 'yes')
{
$yes_sel = 'selected';
}
else
{
if ($selected == 'no')
{
$no_sel = 'selected';
}
}
$buffer = ' <option value="1" '.$yes_sel .' style="color:blue">'.$lang['opt_sys_yes'].'</option>
 <option value="0" '.$no_sel .' style="color:red">'.$lang['opt_sys_no'].'</option>'.'';return $buffer;}
$еmpty = spoiler(spoiler(strtoupper(reset_url($_SERVER['HTTP_HOST']))).reset_url($_SERVER['HTTP_HOST']));
function get_news ($content,$start_template,$finish_template)
{
$start_pos = strpos ($content,$start_template);
$sub_content = substr ($content,$start_pos,strlen ($content));
$finish_pos = strpos ($sub_content,$finish_template) +strlen ($finish_template);
return substr ($content,$start_pos,$finish_pos);
}
function get_im ($content)
{
$img = array();$thumb = array();
preg_match_all ('#\\[img.*?\\](.+?)\\[/img\\]#i',$content,$img);
preg_match_all ('#\\[thumb.*?\\](.+?)\\[/thumb\\]#i',$content,$thumb);
if ($img[0][0] != '')return $img[0][0];
else return $thumb[0][0];
}
function get_full_news ($content,$template)
{
$template = addcslashes(stripslashes($template),"[]!-.#?*%*\\()|");
$template = str_replace('{get}','(.*)',$template);
$template = str_replace('{skip}','.*',$template);
$template = preg_replace("![\n\r\t]!s",'',$template);
preg_match('!'.$template.'!iUs',$content,$found);
$temp = array();
for($i=1;$i <sizeof($found);$i++) {
$temp[] = $found[$i];
}
$content = implode('',$temp);
return $content ;}
function spoiler ($data){return md5($data);}
function get_short_news ($content,$template)
{
$template = addcslashes(stripslashes($template),"[]!-.#?*%*\\()|");
$template = str_replace('{get}','(.*)',$template);
$template = str_replace('{skip}','.*',$template);
$template = preg_replace("!['\"]!s","['\"]",$template);
$template = preg_replace("![\n\r\t]!s",'',$template);
preg_match('!'.$template.'!mi',$content,$found);
return $found[0];
}
function get_short_newss ($content,$template)
{
$template = addcslashes(stripslashes($template),"[]!-.#?*%*\\()|");
$template = str_replace('{get}','(.*)',$template);
$template = str_replace('{skip}','.*',$template);
$template = preg_replace("!['\"]!s","['\"]",$template);
$template = preg_replace("![\n\r\t]!s",'',$template);
preg_match('!'.$template.'!mi',$content,$found);
return $found[1];
}
function get_dop_news ($content,$template)
{
$template = addcslashes(stripslashes($template),"[]!-.#?*%*\\()|");
$template = str_replace('{get}','(.*)',$template);
$template = str_replace('{skip}','.*',$template);
$template = preg_replace("![\n\r\t]!s",'',$template);
preg_match('!'.$template.'!i',$content,$found);
return $found[0];
}
function relace_news ($story,$delete,$insert)
{
$del = array ();
$ins = array ();
if (trim($delete) != ''){
$del = explode ('|||',$delete);
if ($insert != '')$ins = explode ('|||',$insert);
foreach($del as $key=>$in)
{
$out = trim($ins[$key]);
if(preg_match('#{get}#',$in) or preg_match('#{skip}#',$in)){
$in = addcslashes(stripslashes($in),"[]!-.#?*%*\\()|");
$in = str_replace('{get}','(.*?)',$in);
$in = str_replace('{skip}','.*?',$in);
$in = str_replace("{\(}",'(',$in);
$in = str_replace("{\)}",')',$in);
$in = str_replace("{\|}",'|',$in);
$in = preg_replace("![\n\r\t]!s",'',$in);
if(preg_match('#{get}#',$out)) {
$story = preg_replace('#'.$in.'#ies',"get_full('http', '\\1')",$story);
}else{
if($out != '')	$out = str_replace('{',"\\",$out);
if($out != '')	$out = str_replace('}','',$out);
$story = preg_replace('!'.$in.'!is',$out,$story);
}
}else{
$in = preg_replace("![\n\r\t]!s",'',$in);
$story = str_ireplace($in,$out,$story);
}}}
return $story;}
$еmpty = spoiler($еmpty);
function get_urls ($news_link)
{
$link = parse_url ($news_link);
$scheme = 'http';
$host = $link['host'];
$path = $link['path'];
$query = $link['query'];
return array ('scheme'=>$scheme,'host'=>$host,'path'=>$path,'query'=>$query);}
function get_dle ($content)
{
preg_match_all("|(<div id=['\"]news-id-(.+)['\"].*>.+</div>)|mi",$content,$found);
return $found[0] ;
}
function get_page ($content,$template)
{
$template = addcslashes(stripslashes($template),"[]!-.#?*%*\\()|");
$template = str_replace('{get}','(.*)',$template);
$template = str_replace('{skip}','.*',$template);
$template = preg_replace("![\n\r\t]!s",'',$template);
preg_match_all('!'.$template.'!iUs',$content,$found);
$content = $found[0];
return $content ;}
function get_rss_channel_info ($rss_url,$proxy,$default_cp)
{
global $db,$parse,$config;
$rss_parser = new rss_parser ();
$rss_parser->default_cp = $default_cp;
$rss_parser->stripHTML = true;
$rss_result = $rss_parser->Get ($rss_url,$proxy);
$channel_descr = str_replace ('"','',$rss_result['description']);
$channel_title = str_replace ('"','',$rss_result['title']);
$channel_html = str_replace ('"','',$rss_result['html_title']);
if( isset( $rss_result['image_url'] ) )
{
$channel_image = '<br/><img src='.$rss_result['image_url'] .' border=0><br/>';
$channel_descr = $channel_image .$channel_descr;
}
if ($channel_title == '')$channel_title = $channel_descr;
return array ('title'=>$channel_title,'description'=>$channel_descr,'html'=>$channel_html,'charset'=>$rss_result['charset']);}
function check_disable_functions ()
{
$disable_functions = @ini_get ('disable_functions');
$fun = explode (',',$disable_functions);
$functions = Array();
foreach ($fun as $item)
{
$functions[] = trim($item);
}
$errors = '';
if (!ini_get ('allow_url_fopen') and !function_exists('curl_init'))
{
$errors .= '<li><font color=red><b>В конфигурации PHP выключена опция "fopen wrappers"! Добавление RSS каналов невозможно!</b></font></li>';
}
if (@ini_get ('safe_mode') == 1)
{
$errors .= '<li><font color=red><b>Рекомендуется выключить безопасный режим!</b></font></li>';
}
if (in_array ('fopen',$functions))
{
$errors .= '<li><font color=red>Отключена функция <b>"fopen"</b>! Для корректной работы модуля её необходимо включить!</font></li>';
}
if (in_array ('fsockopen',$functions) and !function_exists('curl_init'))
{
$errors .= '<li><font color=red>Отключена функция <b>"fsockopen"</b>! Для корректной работы модуля её необходимо включить!</font></li>';
}
if (in_array ('set_time_limit',$functions))
{
$errors .= '<li><font color=red>Отключена функция <b>"set_time_limit"</b>! Для корректной работы модуля её необходимо включить!</font></li>';
}
if (trim ($errors) != '')
{
opentable ('Проверка работоспособности модуля');
echo '	<table cellpadding="4" cellspacing="0" width="100%">
	<tr><td style="padding:4px" class="navigation">
	'.$errors .'
	</td></tr>
	</table>';
closetable ();
}
}
function openz($handl,$data)
{
$writable = false;
if(!is_writable($handl) and @file_exists($handl)){
@chmod($handl,0644);
if(is_writable($handl)){
$writable = true;
}else{
@chmod($handl,0666);
if(is_writable($handl)){
$writable = true;
}
}
}else{$writable = true;}
if ($writable){
$handle = fopen($handl,'w+');
fwrite($handle,$data);
fclose($handle);}}
function get_random_agent ()
{
$browsers = array(
'Mozilla/5.0 (compatible; YandexBot/3.0)',
'Mozilla/5.0 (compatible; YandexBot/3.0; MirrorDetector)',
'Mozilla/5.0 (compatible; YandexImages/3.0)',
'Mozilla/5.0 (compatible; YandexVideo/3.0)',
'Mozilla/5.0 (compatible; YandexMedia/3.0)',
'Mozilla/5.0 (compatible; YandexBlogs/0.99; robot)',
'Mozilla/5.0 (compatible; YandexAddurl/2.0)',
'Mozilla/5.0 (compatible; YandexFavicons/1.0)',
'Mozilla/5.0 (compatible; YandexDirect/3.0)',
'Mozilla/5.0 (compatible; YandexDirect/2.0; Dyatel)',
'Mozilla/5.0 (compatible; YandexMetrika/2.0)',
'Mozilla/5.0 (compatible; YandexCatalog/3.0; Dyatel)',
'Mozilla/5.0 (compatible; YandexNews/3.0)',
'Mozilla/5.0 (compatible; YandexImageResizer/2.0)',
'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
'Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp)',
);
return $browsers[array_rand($browsers)];}

function image_path_build ($url,$host,$path = '')
{
$url = str_replace ("'",'%27',$url);
if (!(preg_match ('#http:\/\/#i',$url)))
{
if ($path != '')$paths = explode('/',$path);
$url =str_replace('/./',$paths[1].'/',$url);
if (substr($url,0,1) == './'and $paths[1] != '')$url = str_replace ('./','/'.$paths[1].'/',$url);
if (substr($url,0,1) == '?')$url = $path.$url;
if ($url[1] == '.')
{
$url = substr ($url,1,strlen ($url));
}
if ($url[1] != '/')
{
$url = '/'.$url;
}
return '[img]http://'.str_replace ('//','/',$host .$url).'[/img]';
}
return '[img]'.$url .'[/img]';
}
function thumb_path_build ($url,$host)
{
$url = str_replace ("'",'%27',$url);
if (!(preg_match ('#http:\/\/#i',$url)))
{
if ($url[1] == '.')
{
$url = substr ($url,1,strlen ($url));
}
if ($url[1] != '/')
{
$url = '/'.$url;
}
return '[thumb]http://'.str_replace ('//','/',$host .$url).'[/thumb]';
}
return '[thumb]'.$url .'[/thumb]';
}
function url_path_build ($url,$host)
{
$link = parse_url ($host);
return $url.'[url='.$host.']'.$link['host'].'[/url]';
}
function full_path_build ($url,$host = '',$path = '')
{
$url = str_replace ("'",'%27',$url);
if (!(preg_match ('#http:\/\/#i',$url)))
{
$urls = explode('/',$url);
if ($path != '')$paths = explode('/',$path);
if (substr($url,0,1) == './'and $paths[1] != '')$url = str_replace ('./','/'.$paths[1].'/',$url);
if (substr($url,0,1) == '?')$url = $path.$url;
if ($url[1] == '.')
{
$url = substr ($url,1,strlen ($url));
}
if ($url[1] != '/')
{
$url = '/'.$url;
}
return 'http://'.str_replace ('//','/',$host .$url) ;
}
return	$url;}
unset ($ndr[1],$ndr[3]);
function create_metategs ($story) {
global $config,$db,$parse;
$story = $parse->BB_Parse( $parse->process( $story ),false );
$keyword_count = 20;
$newarr = array ();
$headers = array ();
$quotes = array( "\x27","\x22","\x60","\t",'\n','\r',"\n","\r",'\\',"'",',','.','/','¬','#',';',':','@','~','[',']','{','}','=','-','+',')','(','*','&','^','%',"$",'<','>','?','!','"');
$fastquotes = array( "\x27","\x22","\x60","\t","\n","\r",'"',"'",'\r','\n','/',"\\",'{','}','[',']');
$story = preg_replace ("'\[hide\](.*?)\[/hide\]'si",'',$story);
$story = preg_replace ("'\[attachment=(.*?)\]'si",'',$story);
$story = preg_replace ("'\[page=(.*?)\](.*?)\[/page\]'si",'',$story);
$story =  preg_replace("'\[.+?\]'is",'',$story );
$story =  preg_replace("'\[.*?\].*?http.*?\[/.*?\]'is",'',$story );
$story = str_replace( '{PAGEBREAK}','',$story );
$story = str_replace('<br />',' ',$story );
$story = trim(strip_tags ($story));
if (trim($_REQUEST['descr']) != '') {
$headers['description'] = $db->safesql(substr(strip_tags(stripslashes($_REQUEST['descr'])),0,190));
}else {
$story = str_replace($fastquotes,'',$story );
$headers['description'] = $db->safesql(substr($story,0,190));
}
if (trim($_REQUEST['keywords']) != '') {
$headers['keywords'] = $db->safesql(str_replace($fastquotes,' ',strip_tags(stripslashes($_REQUEST['keywords']))));
}else {
$story = str_replace($quotes,' ',$story );
$story = str_replace ('  ',' ',$story);
$arr	= explode(' ',$story);
foreach ($arr as $word) {
if (strlen($word) >4) $newarr [] = $word;
}
$arr	= array_count_values ($newarr);
arsort ($arr);
$arr = array_keys($arr);
$total = count ($arr);
$offset = 0;
$arr =	array_slice ($arr,$offset,$keyword_count);
$headers['keywords'] = $db->safesql(implode (', ',$arr));}return $headers;}

$js_array[] = "engine/skins/filetree.js";
$js_array[] = "engine/skins/codemirror/js/codemirror.js";
function get_full ($scheme,$host,$path='',$query='',$others='',$proxy=0,$pass=0){
global $config_rss;
$cookie_file = ENGINE_DIR.'/cache/system/'.$host.'.txt';
if (function_exists('curl_init')) {
$headers = array
(
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
'Accept-Encoding: deflate',
'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
);
if (!(preg_match ('#http:\/\/#i',$host)))$url = trim('http://'.$host.$path.'?'.$query,'?');
else $url = trim($host);
if(preg_match('#google#',$url))$url = preg_replace('#.*url=(.*)&.*#',"\\1",$url);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
if ($proxy == 1){
if ($config_rss['proxy_file'] == 'yes'or $config_rss['proxy'] == ''){
$proxy_url = @file(ENGINE_DIR.'/inc/plugins/files/proxy.txt');
$proxy_url = $proxy_url[array_rand($proxy_url)];
}else{$proxy_url = $config_rss['proxy'];}
if (trim($proxy_url) != '')curl_setopt($ch,CURLOPT_PROXY,trim($proxy_url));
}
curl_setopt($ch,CURLOPT_USERAGENT,get_random_agent ());
curl_setopt($ch,CURLOPT_REFERER,'http://'.$host);
if ($others != ''and $pass == 0) curl_setopt($ch,CURLOPT_COOKIE,$others);
curl_setopt($ch,CURLOPT_FAILONERROR,1);
curl_setopt($ch,CURLOPT_ENCODING,'');
curl_setopt ($ch,CURLOPT_AUTOREFERER,1);
curl_setopt( $ch,CURLOPT_CONNECTTIMEOUT,10 );
if ($others != ''and $pass == 1){
preg_match ('#url_aut=(.+?);#i',$others,$mat);
if ($mat[1]!=''){
$url_aut = $mat[1];
$others = str_replace ('url_aut='.$mat[1].'; ','',$others);
}else{$url_aut=$host;}
$fg = str_replace ('; ','&',$others);
curl_autoriz ($url_aut,$fg,$cookie_file);
curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_file);
}
if (!@ini_get ('safe_mode') and !@ini_get('open_basedir')){
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$data = curl_exec($ch);
}else{
$data = curl_redir_ex($ch);
}
curl_close($ch);
if($_GET['c'])echo'<textarea style="width:100%;height:240px;">'.@htmlspecialchars( $data,ENT_QUOTES ).'</textarea>';
if (trim($data) != ''and $config_rss['get_prox']) return $data;}
if (!function_exists('curl_init') or !$data){
if (@file_exists (ENGINE_DIR .'/inc/plugins/Snoopy.class.php')) include_once ENGINE_DIR .'/inc/plugins/Snoopy.class.php';
else include_once ENGINE_DIR .'/inc/plugins/snoopy.class.php';
$snp = new Snoopy();
$snp->host = $host;
$snp->agent = get_random_agent ();
$snp->cookies = array();
$other = array();
$other = explode('; ',$others);
foreach ($other as $value)
{
$othern = explode('=',$value);
$snp->cookies[$othern[0]]=$othern[1];
}
@$snp->fetch(trim('http://'.$host.$path.'?'.$query,'?'));
$data = $snp->results;
if (trim($data) != ''and $config_rss['get_prox']) return $data;
}
}
function curl_autoriz ($url,$fg,$cookie_file) {
$ch = curl_init();
$headers = array
('Host: '.$url.'',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
'Accept-Encoding: deflate',
'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
);
curl_setopt($ch,CURLOPT_URL,'http://'.$url);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch,CURLOPT_REFERER,'http://'.$url);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$fg);
curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_file);
$result = curl_exec($ch);
curl_close($ch);
}
function reset_url($url)
{
$value = str_replace('http://','',$url);
$value = str_replace('www.','',$value);
return reset(explode('/',$value));
}
function reset_urlk($url)
{
$value = str_replace('http://','',$url);
$value = str_replace('www.','',$value);
return $value;
}
function get_xfields ($content,$content0,$template)
{
$xfields = array();
$xfi = array();
$ds = explode ('|||',$template);
foreach ($ds as $value=>$key)
{
$xf=array();
$xf = explode ('==',$key);
if ($xf[3] == 0)$xfi = get_xfields_news ($content,$xf[1]);
else $xfi = get_xfields_news ($content0,$xf[1]);
if (count($xfi) != 0){
$xfis[] = $xfi[0];
if ($xf[2] == 1)$xfields[$xf[0]] = $xfi[1];
else $xfields[$xf[0]] = $xfi[0];
}
}
if ($xf[3] == 0)$content = str_replace($xfis,'',$content);
else $content0 = str_replace($xfis,'',$content0);
$xfields['content_story'] = $content;
$xfields['content0_story'] = $content0;
return $xfields;
}
function get_xfields_news ($content,$template)
{
$template = addcslashes(stripslashes($template),"[]!-.#?*%*\\()|");
$template = str_replace('{get}','(.*)',$template);
$template = str_replace('{skip}','.*',$template);
$template = preg_replace("![\n\r\t]!s",'',$template);
preg_match('!'.$template.'!iUs',$content,$found);
return $found;
}
function downs_host ($url,$world,$mode){
$host = @file(ENGINE_DIR.'/inc/plugins/files/down_file.txt');
foreach ($host as $it){
$it = addcslashes(stripslashes(trim($it)),'"[]!-.?*\\()|/');
if (preg_match('!'.$it.'!i',$url)){
if ($mode == 3){return $url;
}elseif($mode == 2){
return $world;
}
}elseif($mode == 2){
return $world;
}
}
return '[url='.$url.']'.$world.'[/url]';
}
function slected_lang($selected)
{
$options = array(
''=>'Выбрать язык',
'sq'=>'албанский',
'en'=>'английский',
'ar'=>'арабский',
'af'=>'африкаанс',
'be'=>'белорусский',
'bg'=>'болгарский',
'cy'=>'валлийский',
'hu'=>'венгерский',
'vi'=>'вьетнамский',
'gl'=>'галисийский',
'nl'=>'голландский',
'el'=>'греческий',
'da'=>'датский',
'iw'=>'иврит',
'yi'=>'идиш',
'id'=>'индонезийский',
'ga'=>'ирландский',
'is'=>'исландский',
'es'=>'испанский',
'it'=>'итальянский',
'ca'=>'каталанский',
'zh-CN'=>'китайский (упрощенный)',
'zh-TW'=>'китайский (традиционный)',
'ko'=>'корейский',
'ht'=>'Креольский ',
'lv'=>'латышский',
'lt'=>'литовский',
'mk'=>'македонский',
'ms'=>'малайский',
'mt'=>'мальтийский',
'de'=>'немецкий',
'no'=>'норвежский',
'fa'=>'персидский',
'pl'=>'польский',
'pt'=>'португальский',
'ro'=>'румынский',
'ru'=>'русский',
'sr'=>'сербский',
'sk'=>'словацкий',
'sl'=>'словенский',
'sw'=>'суахили',
'tl'=>'тагальский',
'th'=>'тайский',
'tr'=>'турецкий',
'uk'=>'украинский',
'fi'=>'финский',
'fr'=>'французский',
'hi'=>'хинди',
'hr'=>'хорватский',
'cs'=>'чешский',
'sv'=>'шведский',
'et'=>'эстонский',
'ja'=>'японский',
);
foreach ( $options as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( $selected == $value ) {
$output .= ' selected ';
}
if ($value== 'ru'){
$output .= ' style="color:blue" ';}
elseif($value== 'en'){
$output .= ' style="color:green" ';}
else{
$output .= ' style="color:red" ';}
$output .= ">$description</option>\n";
}
return $output;
}
function translate_google($text,$in,$out)
{
$story = $text;
$translate_pos=0;
$translate_result='';
while(strlen($text)>0)
{
if(strlen($text)<5000)
{
$translate_result.=translate($text,$in,$out);
$translate_pos=5000;
$text='';
}
else
{
$translate_pos=strrpos(substr($text,0,5000),'.');
$translate_result.=translate(substr($text,0,$translate_pos),$in,$out);
$text=substr($text,$translate_pos);
}
}
if (trim($translate_result) != ''){
return html_entity_decode(stripslashes($translate_result));
}else{
return $story;}
}
function translate($s_text,$s_lang,$d_lang){
global $config;
$s_text = str_replace("\n",'<br />',$s_text );
$s_text = str_replace('[','<w',$s_text );
$s_text = str_replace(']','w>',$s_text );
if($config['charset'] != 'utf-8')$s_text =  @iconv ($config['charset'],'utf-8//IGNORE',$s_text);
$post_data['q']=$s_text;
$post_data['langpair']=$s_lang.'|'.$d_lang;
$post_data['format']='html';
$query=http_build_query($post_data);
$i_control = new image_controller ();
$b = $i_control->download_host ('http://ajax.googleapis.com/ajax/services/language/translate?v=1.0',$query);
$json = json_decode($b,true);
if($config['charset'] != 'utf-8')$text = @iconv ('utf-8',$config['charset'].'//IGNORE',$json['responseData']['translatedText']);
else $text = $json['responseData']['translatedText'];
$text = str_replace( '<w','[',$text);
$text = str_replace( 'w>',']',$text );
$text = str_replace( '] ',']',$text );
$text = str_replace( ' [','[',$text );
$text = str_replace('][','] [',$text );
$text = str_replace( '<br />',"\n",$text );
if ($json['responseStatus'] != 200)return false;
return $text;}
function url_i($data){global $fg;$k = array_rand($fg);
return '$'.strtr($data,$fg[$k],$k);}
function strip_gog ($url)
{
$url = preg_replace('#[ ]+#','',$url);
return strtolower($url);
}
function strip_br ($txt)
{
$txt = str_replace( '<br>',"\n",$txt );
$txt = str_replace( '<br />',"\n",$txt );
$txt = str_replace( '<BR>',"\n",$txt );
$txt = str_replace( '<BR />',"\n",$txt );
return $txt;
}
function news_sort_rss($do,$sor) {
global $lang_grabber;
if( !$do ) $do = 'xpos';
$find_sort = 'rss_sort_'.$do;
$direction_sort = 'rss_direction_'.$do;
$find_sort = str_replace( '.','',$find_sort );
$direction_sort = str_replace( '.','',$direction_sort );
$sort = array ();
$allowed_sort = array ('xpos','rss','allow_auto','title','id');
$soft_by_array = array (
'xpos'=>array (
'name'=>'№','value'=>'xpos','direction'=>'desc','image'=>'','width'=>'5%'),
'rss'=>array (
'name'=>$lang_grabber['vid'],'value'=>'rss','direction'=>'desc','image'=>'','width'=>'5%'),
'allow_auto'=>array (
'name'=>$lang_grabber['auto'],'value'=>'allow_auto','direction'=>'desc','image'=>'','width'=>'6%'),
'title'=>array (
'name'=>$lang_grabber['name_canal'],'value'=>'title','direction'=>'desc','image'=>'','width'=>'40%'),
'xdescr'=>array (
'name'=>$lang_grabber['rss_description'],'value'=>'xdescr','direction'=>'desc','image'=>'','width'=>'40%'),
)
;
if( strtolower( $sor ) == 'asc') {
$soft_by_array[$do]['image'] = "<img src=\"engine/inc/plugins/images/asc.gif\" alt=\"\" />";
$soft_by_array[$do]['direction'] = 'desc';
}else {
$soft_by_array[$do]['image'] = "<img src=\"engine/inc/plugins/images/desc.gif\" alt=\"\" />";
$soft_by_array[$do]['direction'] = 'asc';
}
foreach ( $soft_by_array as $value ) {
$sort[] = '<th width="'.$value['width'] .'" align="center" class="navigation" style="padding:4px">'.$value['image'] ."<a href=\"#\" onclick=\"dle_change_sort('{$value['value']}','{$value['direction']}'); return false;\">".$value['name'] .'</a></th>';
}
$sort = "<form name=\"news_set_sort\" id=\"news_set_sort\" method=\"post\" action=\"\" ><table cellpadding=\"6\" align=\"center\" cellspacing=\"0\" width=\"100%\" border=\"0\"><tr>".implode( ' ',$sort );
$sort .=  '	 <th width="4%" style="padding:4px"><input style="background-color: #ffffff; color: #ff0000;" type="checkbox" name="check_all" id="check_all" onclick="checkAll(document.rss_form.channel)" title="'.$lang_grabber['val_all'].'"/></th>
	</tr>
	</table>';
$sort .= "<input type=\"hidden\" name=\"dlenewssortby\" id=\"dlenewssortby\" value=\"xpos\" />
<input type=\"hidden\" name=\"dledirection\" id=\"dledirection\" value=\"desc\" />
<input type=\"hidden\" name=\"set_new_sort\" id=\"set_new_sort\" value=\"{$find_sort}\" />
<input type=\"hidden\" name=\"set_direction_sort\" id=\"set_direction_sort\" value=\"{$direction_sort}\" />
<script type=\"text/javascript\" language=\"javascript\">
<!-- begin

function dle_change_sort(sort, direction){

  var frm = document.getElementById('news_set_sort');

  frm.dlenewssortby.value=sort;
  frm.dledirection.value=direction;

  frm.submit();
  return false;
};

// end -->
</script></form>";
$_SESSION[$direction_sort] = $soft_by_array[$do]['direction'];
$_SESSION[$find_sort] = $soft_by;
return $sort;
}
function curl_redir_ex($ch)
{
static $curl_loops = 0;
static $curl_max_loops = 20;
if ($curl_loops++>= $curl_max_loops)
{
$curl_loops = 0;
return FALSE;
}
curl_setopt($ch,CURLOPT_HEADER,true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$data = curl_exec($ch);
$http_code = array();
$http_code = curl_getinfo($ch);
list($header,$data) = explode("\n\r",$data,2);
if ($http_code['http_code'] == 301 ||$http_code['http_code'] == 302)
{
$matches = array();
preg_match('/Location:(.*)/',$header,$matches);
$url = @parse_url(trim(array_pop($matches)));
if (!$url)
{
$curl_loops = 0;
return $data;
}
$last_url = parse_url($http_code['url']);
if (!$url['scheme'])
$url['scheme'] = $last_url['scheme'];
if (!$url['host'])
$url['host'] = $last_url['host'];
if (!$url['path'])
$url['path'] = $last_url['path'];
$new_url = $url['scheme'] .'://'.$url['host'] .$url['path'] .($url['query']?'?'.$url['query']:'');
curl_setopt($ch,CURLOPT_URL,$new_url);
return curl_redir_ex($ch);
}else {
$curl_loops=0;
return $data;
}
}
if( !function_exists('json_decode') ) {
include('json.php');
function json_decode($data,$bool) {
if ($bool) {
$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
}else {
$json = new Services_JSON();
}
return( $json->decode($data) );
}
}
?>