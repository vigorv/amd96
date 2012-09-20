<?php
define('mysql_user','wsmedia');
define('mysql_pass','6ND8vkHlNvwxUGPxfQIRz012');
define('mysql_db','wsmedia2');
define ("COLLATE", "cp1251");
 include("pChart/pData.class");
 include("pChart/pChart.class");
 
function prgrath($id,$serie1,$serie2=array(),$serie3=null,$title="")
{
 @unlink($id.".png");
 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($serie1,"Serie1");
 $DataSet->AddPoint($serie2,"Serie2");
 $DataSet->AddPoint($serie3,"Serie3");

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie3");
 $DataSet->SetSerieName("Num","Serie1");
 $DataSet->SetSerieName("Pub","Serie2");
 $DataSet->SetSerieName("Days","Serie3");
 
//print_r($DataSet->GetData());
 // Initialise the graph
 $Test = new pChart(1000,500);
 //$Test->setFixedScale(-2,8);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(50,30,585,200);
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
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
 $DataSet->AddSerie("Serie2");
// Draw the limit graph
 $Test->drawLimitsGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,180,180);

 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 //echo iconv('windows-1251','utf-8',$title);
 $Test->drawTitle(50,22,$title,50,50,50,585);
 $Test->Render($id.".png");
 echo "<br><a href='{$id}.png'><img src='{$id}.png'></a>";

}

$id=(int)$_REQUEST['id'];
//$id=12364;
if($id>0) $where=" l.news_id=".$id."  ";

    /* соединямеся с базой данных */
    mysql_connect("dhz1.anka.ws", mysql_user, mysql_pass) or    die("Could not connect: " . mysql_error());
    mysql_select_db("wsmedia");
    mysql_query("SET NAMES ".COLLATE);
    $sql="SELECT 
	    l.news_id,min(l.created) as mindate,max(l.created) as maxdate,
	    TO_DAYS(max(l.created))-TO_days(min(l.created))as days,count(*)as count,
	    count(*)/(TO_DAYS(max(l.created))-TO_days(min(l.created))) as avg 
	    FROM dle_loads as l
	    where {$where}
	     group by l.news_id order by count desc limit 20";
	    
    echo $sql;	    
    $result=mysql_query($sql) or die("#1".mysql_error());
    echo "!!!!!!".mysql_num_rows($result)."\n";
    
    echo "<pre>";
//$row=array('id'=>12122,'date'=>'2010-04-01 15:56:15');
///$row=array('id'=>10306,'date'=>'2010-02-26');

//if(1)
while($row=mysql_fetch_assoc($result))
{
print_r($row);

##даты ставим;
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
	 FROM dle_loads  
	 where news_id={$row['news_id']}
	 group by date_format(created,'%Y-%m-%d') order by created2";
	 //echo $sql."<br>"; 
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

    $db2=mysql_connect("localhost", mysql_user, mysql_pass) or    die("Could not connect: " . mysql_error());
    mysql_select_db(mysql_db,$db2);
    mysql_query("SET NAMES ".COLLATE,$db2);

$sql="SELECT date_format(created,'%d.%m') as created FROM `post_changefiles` WHERE `post_id` ={$row['news_id']} and created>='{$min}' and created<='{$maxdd}' group by date_format(created,'%Y-%m-%d')";
//echo $sql;
$result2=mysql_query($sql,$db2) or die(mysql_error($db2));
$serie2=array();

while($row2=mysql_fetch_assoc($result2))
{
$serie2[$row2['created']]=$max;
}
$serie5 = array_merge ($dates, $serie2);
//print_r($serie1);print_r($serie4);print_r($serie5);die();
if(count($serie4)>1)
prgrath($row['news_id'],$serie1,$serie5,$serie4,iconv('windows-1251','utf-8',$row['title']));
else echo "no graph\n<br>";
print_r($serie1);
}

?>