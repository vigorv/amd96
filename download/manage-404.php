<?
    $page=0;
    $limit=50;
    $page=$_REQUEST['page'];
    include $_SERVER['DOCUMENT_ROOT']."/engine/data/dbconfig.php";
    $db1=mysql_connect(DBHOST2, DBUSER2,DBPASS2) or    die("Could not connect: " . mysql_error());
    mysql_select_db(DBNAME2,$db1);
    mysql_query("SET NAMES ".COLLATE2,$db1);
    #mysql_connect("localhost", "wsmedia", "6ND8vkHlNvwxUGPxfQIRz012");
    #mysql_select_db("wsmedia2");
    #mysql_query('SET NAMES cp1251');
    $sql= "select count(*) from `error404`";
    $result=mysql_query($sql);
        $count=mysql_result($result,0);
        if (floor($count/$limit)<$page)$page=floor($count/$limit);
        
        $sql= "select * from `error404` order by id desc limit ".$page*$limit.",".$limit.";";
        $result=mysql_query($sql);
        if($page>0) echo "<a href=?page=".($page-1)."> < </a>";
        echo ($page+1);
        if(floor($count/$limit)>$page)echo "<a href=?page=".($page+1)."> > </a>";
    echo "<table border=1>";
    //foreach ($row = mysql_fetch_assoc($result))
    while ($row = mysql_fetch_assoc($result))
    {
	echo "<tr>";
	    foreach($row as $val)
	    {
		echo "<td>";echo $val;
	    }
    }
    echo "</table>";
?>