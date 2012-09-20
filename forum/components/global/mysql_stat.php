<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if ($member_id['user_id'] == "1")
{
	$mysql_head ="<div id=\"MySQL\" style=\"padding-top:10px;\"><a href=\"#\" onclick=\"ShowAndHide('mysql_admin');return false;\">Вывести данные</a><div style=\"clear:left;width:97%;display:none;\" id=\"mysql_admin\" align=left>";
	$mysql_i = 0;
    $mysql_content = "";
	foreach($DB->query_list['query'] as $q)
	{
            $mysql_i ++;
       		$q = htmlspecialchars($q);
	       	$q = preg_replace( "/^SELECT/i" , "<font color='black'>SELECT</font>"   , $q );
	       	$q = preg_replace( "/^UPDATE/i" , "<font color='green'>UPDATE</font>"  , $q );
       		$q = preg_replace( "/^DELETE/i" , "<font color='red'>DELETE</font>", $q );
	       	$q = preg_replace( "/^INSERT/i" , "<font color='orange'>INSERT</font>" , $q );
       		$q = str_replace( "LEFT JOIN"   , "<font color='purple'>LEFT JOIN</font>" , $q );
	       	$mysql_content .= "$q<hr />\n";
	}	
	$mysql_all = "Всего было выполнено запросов:<b> ".$mysql_i."</b>
    <br>Время на запросы: ".round($DB->MySQL_time_taken, 7)."<br><br>";
    
   	$tpl_i = 0;
    $tpl_content = "";
   	foreach($tpl->tpl_out['templates'] as $t)
	{
        $tpl_i ++;
        $tpl_content .= "- ".$t."<br>";
    }
    $tpl_result = "<br>Всего было загруженно шаблонов:<b> ".$tpl_i."</b>
    <br>Время на шаблоны: ".round($tpl->template_parse_time, 7)."<br>".$tpl_content;
    
   	$cache_i = 0;
    $cache_content = "";
   	foreach($cache->cache_list as $c)
	{
        $cache_i ++;
        $c = str_replace (LB_MAIN."/cache", "", $c);
        $cache_content .= "- ".$c."<br>";
    }
    $cache_result = "<br>Всего было загруженно файлов кеша:<b> ".$cache_i."</b>
    <br>Время загрузки кеша: ".round($cache->time_taken, 7)."<br>".$cache_content;
    
	$mysql_down = "</div></div>";
	$mysql_stat = $mysql_head.$mysql_all.$mysql_content.$tpl_result.$cache_result.$mysql_down;
    
    if ($cache_config['general_coding']['conf_value'] == "utf-8")
        $mysql_stat = mb_convert_encoding($mysql_stat, "UTF-8", "windows-1251");
        
    $tpl->tags( '{mysql_stat}', $mysql_stat );
}
else
{
    $tpl->tags( '{mysql_stat}', "" );
}

?>