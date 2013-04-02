<?php
if( ! defined( 'DATALIFEENGINE' ) ) {
die( "Hacking attempt!" );
}

function weblog_ping($data, $site_name= '' , $news_url= '') 
{
	if ($news_url != '' and $site_name != ''){
if (function_exists(xmlrpc_encode_request)){
$request = xmlrpc_encode_request('weblogUpdates.ping', array($site_name, $news_url) );
}else{
$request = 
"<?xml version=\"1.0\" encoding=\"WINDOWS-1251\"?>\n" .
"<methodCall>\n" .
"\t<methodName>weblogUpdates.ping</methodName>\n" .
"\t<params>\n" .
"\t\t<param>\n" .
"\t\t\t<value>". $site_name ."</value>\n" .
"\t\t</param>\n" .
"\t\t<param>\n" .
"\t\t\t<value>". $news_url ."</value>\n" .
"\t\t</param>\n" .
"\t</params>\n" .
"</methodCall>";
}
}else{$request = '';}

  $curls = array();
  $result = array();
  $mh = curl_multi_init();
  foreach ($data as $id => $url) {

$header[] = "Host: ".reset (explode ('/', str_replace('http://','',$url)));
$header[] = "User-Agent: Java";
$header[] = "Content-type: text/xml";
$header[] = "Content-length: ".strlen($request) . "\r\n";
$header[] = $request;


$curls[$id] = curl_init();
curl_setopt($curls[$id], CURLOPT_URL, $url);
curl_setopt($curls[$id], CURLOPT_HEADER, 0);
curl_setopt($curls[$id], CURLOPT_TIMEOUT, 5);
curl_setopt($curls[$id], CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($curls[$id], CURLOPT_FAILONERROR,1);
curl_setopt($curls[$id], CURLOPT_HTTPHEADER, $header );
curl_setopt($curls[$id], CURLOPT_CUSTOMREQUEST, 'POST' );
curl_multi_add_handle($mh, $curls[$id]);
  }
  $running = null;
  do { curl_multi_exec($mh, $running); } while($running > 0);
  foreach($curls as $id => $c) 
  {
$out = array();
if (curl_multi_getcontent($c) != null){
if ($news_url != '' and $site_name != ''){
	preg_match ("#<boolean>(.+?)<\/boolean>#is", curl_multi_getcontent($c), $out);
if (count($out) != '0' and $out[1] == '0')$result[$id] = curl_multi_getcontent($c);
}else{
$result[$id] = curl_multi_getcontent($c);
}
  }
    curl_multi_remove_handle($mh, $c);
  }
  curl_multi_close($mh);

return $result;
}

?>