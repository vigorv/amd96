<?
//echo "<pre>";
//print_r($_SERVER);
$url='';
if(isset($_GET['url']))
    $url=$_GET['url'];
    else 
     header('location: /404.html');

if(trim($url)=='')exit(0);
//echo $url;
//echo "<br>";
$server=$_GET['server'];
//echo $server;
//echo "<br>";

$b_info=$_SERVER['HTTP_USER_AGENT'];//запоминаем browser info
$user_ip=(empty($_SERVER['HTTP_X_REAL_IP'])? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_REAL_IP']);// Запоминаем IP

//страница откуда пришел юзер если он это сделал нажав с сайта нерабочую ссылку
$event= empty($_SERVER['HTTP_REFERER'])? '' : $_SERVER['HTTP_REFERER'];//ссылка на новость
$cookies=explode(';',(empty($_SERVER['HTTP_COOKIE'])? '' : $_SERVER['HTTP_COOKIE']));
foreach($cookies as $cookie)
{
if(strpos($cookie,'user_id')){list($name,$user_id1)=explode('=',$cookie);}
}
if(isset($user_id1))$user_id=$user_id1;//получили user_id
else $user_id=0;

echo to_base('http://'.$server.''.$url,$user_ip,$user_id,$event,$b_info);

function to_base($link,$user_ip,$user_id,$event,$info)
{
    include $_SERVER['DOCUMENT_ROOT']."/engine/data/dbconfig.php";
    $db1=mysql_connect(DBHOST2, DBUSER2,DBPASS2) or    die("Could not connect: " . mysql_error());
    mysql_select_db(DBNAME2,$db1);
    mysql_query("SET NAMES ".COLLATE2,$db1);

    $link = mysql_real_escape_string($link);
    $event		= mysql_real_escape_string($event);
    $info		= mysql_real_escape_string($info);
    //echo $link."<br>";

    $user_id	= intval($user_id);


    
    //$sql= "select id from `error404` where link='{$link}';";
    //$result=mysql_query($sql);
    //echo mysql_num_rows($result);
    //if(mysql_num_rows($result)==0)
    if(1)
    {
    $sql= "insert into `error404` (user_ip,user_id,link,event,info) values ('{$user_ip}',{$user_id},'{$link}','{$event}','".mysql_escape_string($info)."');";
    $result = mysql_query($sql);
    }else
    {
    $sql= "update `error404` set user_ip='{$user_ip}', count=count+1 where link='{$link}'";
    $result = mysql_query($sql);

    }

    return $sql;
}
//
//echo ['HTTP_COOKIE']
//$_SERVER[()
#echo $HTTP_REFERER;
#echo "TEST";
header('location: /404.html');
?>