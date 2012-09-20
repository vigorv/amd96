<?php
include $_SERVER['DOCUMENT_ROOT']."/engine/data/dbconfig.php";
 

$id=(int)$_REQUEST['id'];if($id>0) $where=" l.catalog_id=".$id."  ";
$sgroup=(int)$_REQUEST['sgroup'];if($sgroup>0) $where.=" and l.catalog_sgroup_id=".$sgroup."  ";

    $db1=mysql_connect(DBHOST2, DBUSER2,DBPASS2) or    die("Could not connect: " . mysql_error());
    mysql_select_db(DBNAME2,$db1);
    mysql_query("SET NAMES ".COLLATE2,$db1);

    $sql="
    SELECT 
	    l.catalog_id,min(l.created) as mindate,max(l.created) as maxdate,
	    TO_DAYS(max(l.created))-TO_days(min(l.created))as days,count(*)as count,
	    count(*)/(TO_DAYS(max(l.created))-TO_days(min(l.created))) as avg 
	    FROM fl_catalog_clicks as l
	    where {$where}
	     group by l.catalog_id order by count desc limit 20";
	    
    //echo $sql."<br>";	    
    $result=mysql_query($sql) or die("#1".mysql_error());
    echo "!!!!!!".mysql_num_rows($result)."\n";
    
    echo "<pre>";
//$row=array('id'=>12122,'date'=>'2010-04-01 15:56:15');
///$row=array('id'=>10306,'date'=>'2010-02-26');

//if(1)
while($row=mysql_fetch_assoc($result))
{
print_r($row);

$min=$row['mindate'];
$maxdd=$row['maxdate'];
$maxd=date('Y-m-d',strtotime($maxdd));
//echo $maxd;
$dates=array();
$date=date('Y-m-d',strtotime($min));
//echo "!!".$date."\n";
$dates[date('d.m',strtotime($date))]=0;
while($date!=$maxd)
{
// echo $date; 
// if(strtotime($date)>strtotime('now'))break;
 $date=date('Y-m-d',strtotime("+1 day",strtotime($date)));
 $dates[date('d.m',strtotime($date))]=0;
}
//print_r($dates);

    $sql="SELECT  
	    count(*) as count,date_format(created,'%d.%m') as created,date_format(created,'%Y-%m-%d') as created2
	 FROM fl_catalog_clicks
	 where catalog_id={$row['catalog_id']} and catalog_sgroup_id={$sgroup}
	 group by date_format(created,'%Y-%m-%d') order by created2";
	// echo $sql."<br>"; 
    $result2=mysql_query($sql) or die(mysql_error());
$serie1=array();
$serie3=array();

while($row2=mysql_fetch_assoc($result2))
{
$serie1[$row2['created']]=$row2['count'];
$serie3[$row2['created']]=$row2['created'];
}
$serie1 = array_merge ($dates, $serie1);
$serie3 = array_merge ($dates, $serie3);
//print_r($result);
//die();

$serie4=array();
$max=max($serie1);
$i=0;
foreach($serie3 as $k=>$v)
{
if($i%7==0)$serie4[$k]=$serie3[$k];
else $serie4[$k]="";
$i++;
}

//print_r($serie4);die();

$serie2=array();
$serie2[]=$row['date'];

    $db2=mysql_connect(DBHOST, DBUSER, DBPASS) or    die("Could not connect: " . mysql_error());
    mysql_select_db(DBNAME,$db2);
    mysql_query("SET NAMES ".COLLATE,$db2);

$sql="SELECT date_format(created,'%d.%m') as created FROM `post_changefiles` WHERE `post_id` ={$row['catalog_id']} and created>='{$min}' and created<='{$maxdd}' group by date_format(created,'%Y-%m-%d')";
//echo $sql;
$result2=mysql_query($sql,$db2) or die(mysql_error($db2));
$serie2=array();

while($row2=mysql_fetch_assoc($result2))
{
$serie2[$row2['created']]=$max;
}
$serie5 = array_merge ($dates, $serie2);
//print_r($serie1);print_r($serie4);print_r($serie5);die();

include_once "chart.api.php";
$id=$row['catalog_id'];
$cache = new Chart("links",$id,iconv('windows-1251','utf-8',$row['title']));
$cache->setData($serie1,false);
//$serie1=$cache->getData(false);
$img=$cache->getImg();


if($img)echo "<br><a href='".$img."'><img src='".$img."'></a>";
$serie6[$id]=$serie1;
echo "<table border=1><tr>";
foreach($serie6 as $k=>$link){echo "<td><a href ='2.php?sgroup=2&id={$k}'>Graph</a><br><a href='http://fastlink.ws/catalog/file/{$k}'>{$k}</a><pre>";print_r($link);}

}




?>