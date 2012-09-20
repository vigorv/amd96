<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
//require ('../api_jsonrpc.php');
$url="http://zabbix.anka.ws/api_jsonrpc.php";
$post_data=array(
	"jsonrpc"=>"2.0",
	"method"=> "user.login",
	"params" => array
			(
			 "user"=> "api",
			"password"=> "2uRTsr1H"
			),
	"id"=>1
	);
#echo http_post_data($url,json_encode($m));
    $tuCurl = curl_init();
    curl_setopt($tuCurl, CURLOPT_URL, $url);
    curl_setopt($tuCurl, CURLOPT_PORT , 80);
    curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    curl_setopt($tuCurl, CURLOPT_VERBOSE, 1);
    curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($tuCurl, CURLOPT_HEADER, 0);
    curl_setopt($tuCurl, CURLOPT_POST, 1);
    curl_setopt($tuCurl, CURLOPT_POSTFIELDS, json_encode($post_data));
    $tuData = curl_exec($tuCurl);
    $res=json_decode($tuData);
    echo "<pre>";

    if($res->id<1){echo "failed<br />";exit(0);}
    $res->auth=$res->result;
    $res->method="history.get";
    $res->params=array( "history"=> 0,"itemids"=>array("all"=>"26569","stk"=>"30178","brn"=>"27295","bgp"=>"30192","irk"=>"27292","krs"=>"27294","kem"=>"27293"), "output"=> "extend");
    $res->params["time_from"]= mktime(20,0,0,7,4,2012);
    $res->params["time_till"]= mktime(22,00,0,7,4,2012);
    print_r($res);
//exit(0);
    curl_setopt($tuCurl, CURLOPT_POSTFIELDS, json_encode($res));
    $tuData = curl_exec($tuCurl);
    $res=json_decode($tuData);
//    print_r($res->result);

//exit(0);

    $r=array();
    foreach($res->result as $k=>$v)
    {
	$r[$v->itemid][$k]=$v;
    }
//	print_r($r);
$datas=array();
    foreach($r as $k=>$v)
    {
    $max=0;
    $date=null;
    $arr=array();
	foreach($v as $v2)
	{
	    $data=intval($v2->value/1000/1000);
	    if($max<$data)
	    {$max=$data;
	    $date=$v2->clock;
	    }
        $arr[$v2->clock]=$data;
	//echo $k." ".date("Y-m-d H:i:s",$v2->clock)." =>".$data."<br />";
    }
    $datas[$k]=$arr;
    echo "max on {$k} is ".date("Y-m-d H:i:s",$date)." =>".$max."<br />";
    }
    curl_close($tuCurl);
    echo "<br /> OK!";
    include_once "chart.api.php";
   $cache = new Chart("z","","graph from zabbix");
  // $data=$cache->data;
   $cache->setData($datas, false);
//   pr($datas);
   $img=$cache->getImg();
if($img)echo "<br><a href='".$img."'><img src='".$img."'></a>";

           

?>