<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

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
<!-- ����� ���������� �������: ".microTimer_stop()." ������ -->
<!-- ����� ����������� �� ���������� ��������: ".round($tpl->template_parse_time, 7)." ������ -->
<!-- ����� ����������� �� �������� ������ ����: ".round($cache->time_taken, 7)." ������ -->
<!-- ����� ����������� �� ���������� MySQL ��������: ".round($DB->MySQL_time_taken, 7)." ������ -->
<!-- ����� ���������� MySQL ��������: ".$DB->query_num." -->";

if( function_exists( "memory_get_peak_usage" ) )
    $s .="\n<!-- ��������� ����������� ������: ".round(memory_get_usage()/(1024*1024),2)." MB -->";
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
            $s .= "\n<!-- ��� ������ ��������������: $ENCODING -->\n"; 
        
        $contents = ob_get_contents(); 
        ob_end_clean(); 
        
        if ($debug AND $cache_config['general_gzipout']['conf_value'])
        {
            $s .= "<!-- �������� ������: ".utf8_strlen($contents)." ���� "; 
            $s .= "������ ������: ".strlen(gzencode($contents, 2))." ���� -->"; 
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