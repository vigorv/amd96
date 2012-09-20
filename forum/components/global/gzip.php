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

function CheckCanGzip()
{
    if (headers_sent() OR connection_aborted() OR !function_exists('ob_gzhandler') OR ini_get('zlib.output_compression'))
        return 0; 

    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)
            return "x-gzip"; 
    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
            return "gzip"; 

    return 0; 
}

function GzipOut($debug = 1)
{
    global $cache_config, $DB, $tpl, $cache;
    
$s = "
<!-- Copyright LogicBoard (LogicBoard.ru) -->";

if ($cache_config['general_gzipout']['conf_value'])
{
$s .="
<!-- Время выполнения скрипта: ".microTimer_stop()." секунд -->
<!-- Время затраченное на компиляцию шаблонов: ".round($tpl->template_parse_time, 7)." секунд -->
<!-- Время затраченное на загрузку файлов кеша: ".round($cache->time_taken, 7)." секунд -->
<!-- Время затраченное на выполнение MySQL запросов: ".round($DB->MySQL_time_taken, 7)." секунд -->
<!-- Общее количество MySQL запросов: ".$DB->query_num." -->";

if( function_exists( "memory_get_peak_usage" ) )
    $s .="\n<!-- Затрачено оперативной памяти: ".round(memory_get_usage()/(1024*1024),2)." MB -->";
}

    @header ("Last-Modified: " . date('r', time()-60*60) ." GMT");
    
    if (!$cache_config['general_gzip']['conf_value'])
    {
        if ($debug) echo $s;
        ob_end_flush();
        exit;
        return;
    }
    
    $ENCODING = CheckCanGzip(); 
    if ($ENCODING)
    {
        if ($cache_config['general_gzipout']['conf_value'])
            $s .= "\n<!-- Для сжатия использовалось: $ENCODING -->\n"; 
        
        $contents = ob_get_contents(); 
        ob_end_clean(); 
        
        if ($debug AND $cache_config['general_gzipout']['conf_value'])
        {
            $s .= "<!-- Исходный размер: ".utf8_strlen($contents)." байт "; 
            $s .= "Сжатый размер: ".strlen(gzencode($contents, 2))." байт -->"; 
        }
        
        $contents .= $s; 
        
        header("Content-Encoding: $ENCODING");
        
        $contents = gzencode($contents, 2);
        
        echo $contents;        
        exit; 
    }
    else
    {
        ob_end_flush(); 
        exit; 
    }
}
?>