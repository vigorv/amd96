<?php//������ ���������� ���������� �� ��������� �����  include_once "chart.api.php";include $_SERVER['DOCUMENT_ROOT']."/engine/data/dbconfig.php";    $db1=mysql_connect(DBHOST2, DBUSER2,DBPASS2) or    die("Could not connect: " . mysql_error());    mysql_select_db(DBNAME2,$db1);    mysql_query("SET NAMES ".COLLATE2,$db1);        echo "<table border=1>";   $cache = new Chart("h2","","graph for hours fo amd");   $data=$cache->data;   $cache->yspace=24;   $where='';   if(!empty($data))       {       end($data);       $date=key($data); $where=" and DATE_FORMAT( created,  '%Y-%m-%d_%H' )>='".$date."'";       }           $sql="SELECT DATE_FORMAT( created,  '%Y-%m-%d_%H' ) as created, COUNT( catalog_group_id ) AS  `count` FROM  `fl_catalog_clicks` WHERE  `created` > DATE_SUB( CURDATE( ) , INTERVAL 1 WEEK ) AND catalog_sgroup_id =2 {$where}GROUP BY DATE_FORMAT( created,  '%Y-%m-%d_%H' ) order by created";echo $sql;    $result=mysql_query($sql,$db1);    $rows=array();$ids=array();    while($row=mysql_fetch_assoc($result))    {        $serie[$row['created']]=$row['count'];    }if(is_array($data))$serie= array_merge($data,$serie);$cache->setData($serie);$cache->deleteFromArray(date("Y-m-d_h", mktime(0, 0, 0, date("m"),date("d"),date("Y"))-7*60*60*24));$cache->set();$img=$cache->getImg();if($img)echo "<br><a href='".$img."'><img src='".$img."'></a>";?>