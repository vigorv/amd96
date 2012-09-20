<?php

include $_SERVER['DOCUMENT_ROOT']."/engine/data/dbconfig.php";
include("pChart/pData.class");
include("pChart/pChart.class");
 

$id=(int)$_REQUEST['id'];if($id>0) $where=" l.catalog_group_id=".$id."  ";
$sgroup=(int)$_REQUEST['sgroup'];if($sgroup>0) $where.=" and l.catalog_sgroup_id=".$sgroup."  ";
    $db1=mysql_connect(DBHOST2, DBUSER2,DBPASS2) or    die("Could not connect: " . mysql_error());
    mysql_select_db(DBNAME2,$db1);
    mysql_query("SET NAMES ".COLLATE2,$db1);
    $sql="
    SELECT 
	    l.catalog_group_id as id,l.catalog_group_id as news_id,min(l.created) as mindate,max(l.created) as maxdate,
	    TO_DAYS(max(l.created))-TO_days(min(l.created))as days,count(*)as count,
	    count(*)/(TO_DAYS(max(l.created))-TO_days(min(l.created))) as avg 
	    FROM fl_catalog_clicks as l
	    where {$where}
	     group by l.catalog_group_id order by count desc limit 20";
    $result=mysql_query($sql,$db1) or die("#1".mysql_error());
    echo "!!!!!!".mysql_num_rows($result)."\n";
    
    echo "<pre>";
while($row=mysql_fetch_assoc($result))
{
    echo "<a href='/id/{$row['news_id']}' target=_blank>link</a><br>";
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

    $sql="SELECT  
	    count(*) as count,date_format(created,'%d.%m') as created,date_format(created,'%Y-%m-%d') as created2
	 FROM fl_catalog_clicks
	 where catalog_group_id={$row['id']} and catalog_sgroup_id={$sgroup}
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

//get all series
    $sql="SELECT  
	    count(*) as count,catalog_id,date_format(created,'%d.%m') as created,date_format(created,'%Y-%m-%d') as created2
	 FROM fl_catalog_clicks
	 where catalog_group_id={$row['news_id']} and catalog_sgroup_id={$sgroup}
	 group by date_format(created,'%Y-%m-%d'),catalog_id order by created2";
	 //echo $sql."<br>"; 
    $result2=mysql_query($sql) or die(mysql_error());
$links=array();
while($row2=mysql_fetch_assoc($result2))
{
    $links[$row2['catalog_id']][$row2['created']]=$row2['count'];
}

//print_r($serie4);die();

$serie2=array();
$serie2[]=$row['date']; 

    $db2=mysql_connect(DBHOST, DBUSER, DBPASS) or    die("Could not connect: " . mysql_error());
    mysql_select_db(DBNAME,$db2);
    mysql_query("SET NAMES ".COLLATE,$db2);

$sql="SELECT date_format(created,'%d.%m') as created FROM `post_changefiles` WHERE `post_id` ={$row['news_id']} and created>='{$min}' and created<='{$maxdd}' group by date_format(created,'%Y-%m-%d')";
$result2=mysql_query($sql,$db2) or die(mysql_error($db2));
$serie2=array();

while($row2=mysql_fetch_assoc($result2))
{
$serie2[$row2['created']]=$max;
}
$serie5 = array_merge ($dates, $serie2);
foreach($links as $k=>$link){
$serie6[$k] = array_merge ($dates, $link);
}
$sql="SELECT title FROM `".PREFIX."_post` WHERE `id` ={$row['news_id']}";
//echo $sql;
$result2=mysql_query($sql,$db2) or die(mysql_error($db2));
$row['title']= mysql_fetch_row($result2);
$row['title']=$row['title'][0];
print_r($row);
//print_r($serie1);print_r($serie4);print_r($serie5);die();



if(count($serie4)>1)
prgrath($row['news_id'],$serie1,$serie5,$serie4,$serie6,iconv('windows-1251','utf-8',$row['title']));
else echo "no graph\n<br>";
   

echo "<table border=1><tr><td><a href='/id/{$row['news_id']}' target=_blank>Publication</a><pre>";
print_r($serie1);
foreach($serie6 as $k=>$link){echo "<td><a href ='index2.php?sgroup=2&id={$k}'>Graph</a><br><a href='http://fastlink.ws/catalog/file/{$k}'>{$k}</a><pre>";print_r($link);}
}


function prgrath($id,$serie1,$serie2=array(),$serie3=null,$serie6=array(),$title="")
{
 @unlink("cat_".$id.".png");
 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($serie1,"Serie1");
 $DataSet->AddPoint($serie2,"Serie2");
 foreach ($serie6 as $k=>$v)$DataSet->AddPoint($v,"id_".$k);
 $DataSet->AddPoint($serie3,"Serie3"); 

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie3");
 $DataSet->SetSerieName("Num","Serie1");
 $DataSet->SetSerieName("Publication","Serie2");
 foreach ($serie6 as $k=>$v)$DataSet->SetSerieName("link ".$k,"id_".$k);
 $DataSet->SetSerieName("Updates","Serie3");
 
//print_r($DataSet->GetData());
 // Initialise the graph
 $Test = new pChart(1000,500);
 //$Test->setFixedScale(-2,8);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(100,30,850,450);
 $Test->drawFilledRoundedRectangle(7,7,1000,500,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,1000,500,5,230,230,230);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
 $Test->drawGrid(4,TRUE,230,230,230,50);

              
                  

 // Draw the 0 line
 $Test->setFontProperties("Fonts/tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the cubic curve graph
 $DataSet->RemoveSerie("Serie2");

 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());

 $DataSet->RemoveSerie("Serie1");
 $DataSet->RemoveSerie("Serie3");
 foreach ($serie6 as $k=>$v)$DataSet->RemoveSerie("id_".$k);
 $DataSet->AddSerie("Serie2");

// Draw the limit graph
 $Test->drawLimitsGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,180,180);

 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->drawLegend(900,30,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 //echo iconv('windows-1251','utf-8',$title);
 $Test->drawTitle(50,22,$title,50,50,50,585);
 $Test->Render("cat_".$id.".png");
 echo "<br><a href='cat_{$id}.png'><img src='cat_{$id}.png'></a>";

}

?>