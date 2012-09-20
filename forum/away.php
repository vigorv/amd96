<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

@session_start ();

@error_reporting ( E_ERROR );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

define ( 'LogicBoard', true );
define ( 'LB_MAIN', dirname ( __FILE__ ) );

define ( 'LB_CLASS', LB_MAIN . '/components/class' );
define ( 'LB_GLOBAL', LB_MAIN . '/components/global' );
define ( 'LB_CONFIG', LB_MAIN . '/components/config' );
define ( 'LB_MODULES', LB_MAIN . '/components/modules' );
define ( 'LB_UPLOADS', LB_MAIN . '/uploads' );

require_once LB_CLASS . '/database.php';
include_once LB_CONFIG . '/board_db.php';

$_IP = $_SERVER['REMOTE_ADDR'];

require_once LB_CLASS . '/cache.php';
require_once LB_GLOBAL . '/creat_cache.php';

require_once LB_GLOBAL . '/functions.php';

$redirect_url = $cache_config['general_site']['conf_value'];

if (isset($_GET['s']))
{
    if (preg_match("#^[http|www](.+?)#i".regular_coding(), $_GET['s']))
    {
        $_GET['s'] = str_replace ("&amp;", "&", $_GET['s']);
        filters_input('get');
        
        if ($cache_config['link_warning']['conf_value'] AND $file = file_get_contents(LB_MAIN."/templates/redirect.html"))
        {
            $text = add_br($cache_config['link_warning_text']['conf_value']);
            $text = str_replace ("{link}", "<a href=\"".$_GET['s']."\">".$_GET['s']."</a>", $text);
            $text = str_replace ("{title}", $cache_config['general_name']['conf_value'], $text);
            $text = str_replace ("{back_link}", "<a href=\"".$_SERVER['HTTP_REFERER']."\">".$_SERVER['HTTP_REFERER']."</a>", $text);
            $text = str_replace ("{main_link}", "<a href=\"".$cache_config['general_site']['conf_value']."\">".$cache_config['general_site']['conf_value']."</a>", $text);
            
            $file = str_replace ("{text}", $text, $file);
            $file = str_replace ("{charset}", $LB_charset, $file);
            $file = str_replace ("{TITLE_BOARD}", $cache_config['general_name']['conf_value'], $file);
            
            if (intval($cache_config['link_redirect']['conf_value']))
            {
                header( "refresh:".intval($cache_config['link_redirect']['conf_value']).";url=".$_GET['s'] );
            }
            
            if ($cache_config['general_coding']['conf_value'] == "utf-8")
            {
                $file = mb_convert_encoding($file, "UTF-8", "windows-1251");
            }
            
            echo $file;
        }
        else
            header ( "Location: ".$_GET['s'] );
    }
    
    exit ();
}
else
    exit ("Link redirect not found.");
    
?>