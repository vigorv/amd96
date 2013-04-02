<?php

@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';
$rss_plugins = ENGINE_DIR .'/inc/plugins/';
require_once $rss_plugins.'core.php';
require_once $rss_plugins.'rss.classes.php';
require_once $rss_plugins.'rss.functions.php';
@include(ENGINE_DIR.'/data/rss_config.php');
@require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';
if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/inc/plugins/start_sinonims.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}
$link = get_urls('http://spys.ru/');
if($config_rss['get_prox'] = $tab_id)$proxy_content = get_full ($link[scheme],$link['host'],$link['path'],$link['query'],$cookies,$proxy);
preg_match_all('!(\d+\.\d+\.\d+\.\d+:\d+)!',$proxy_content,$tran);

$tr = '';
foreach ($tran[1] as $value)
		{
$tr .= $value.'
';
}

$writable = false;
$file = ENGINE_DIR.'/inc/plugins/files/proxy.txt';
            @chmod($file, 0644);
            if(is_writable($file)){
				$writable = true;
$file_status = "<font color=red>Разрешено</font>";
            }else{
                @chmod($file, 0666);
                if(is_writable($file)){
					$writable = true;
$file_status = "<font color=red>Разрешено</font>";
                }else{
                    $file_status = "<font color=red>ЗАПРЕЩЕНО</font>";
                }
			}
if ($writable){
$handler = fopen($file,'w+');
fwrite($handler,$tr);
fclose($handler);
}

@header( "Content-type: text/css; charset=" . $config['charset'] );


if (trim($tr) != '' and $writable){echo '<div style="width:100%;"><font color="green">'.$lang_grabber['msg_proxy_yes'].'</font> <font color="red">'.date( "Y-m-d H:i:s",filectime(ENGINE_DIR ."/inc/plugins/files/proxy.txt")).' обновлён </font></div>';}else{echo '<font color="green">Запись файла '.$file.'</font> '.$file_status; }



?>