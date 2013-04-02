<?php

@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );





if ($key1 != $key )echo "<div id='loading-layer' style='font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$title} </div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'	border='0' /></div>";



?>