
<?php
    @session_start();
    @error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
    @ini_set('display_errors', true);
    @ini_set('html_errors', false);
    @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
    header('Content-Type: text/html; charset=utf-8');
    define('DATALIFEENGINE', true);
    define('ROOT_DIR', '../');
    define('ENGINE_DIR', ROOT_DIR . '/engine');

    include ENGINE_DIR . '/data/config.php';

    if ($config['http_home_url'] == "") {

        $config['http_home_url'] = explode("engine/ajax/adminfunction.php", $_SERVER['PHP_SELF']);
        $config['http_home_url'] = reset($config['http_home_url']);
        $config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];
    }

    require_once ENGINE_DIR . '/classes/mysql.php';
    require_once ENGINE_DIR . '/data/dbconfig.php';
    require_once ENGINE_DIR . '/inc/include/functions.inc.php';
    require_once ENGINE_DIR . '/modules/sitelogin.php';


    function netMatch($CIDR, $IP)
    {
        list ($net, $mask) = explode('/', $CIDR);
        return (ip2long(trim($IP)) & ~((1 << (32 - trim($mask))) - 1)) == ip2long(trim($net));
    }

    $infoSQL = $db->query("SELECT id  FROM `rm_post` WHERE `category` in (82, 14, 91, 90, 88, 87, 86, 62, 85, 84, 83, 61, 25, 24, 22, 21, 20, 19, 77, 18, 17, 16, 15, 13, 11, 23, 6)");
    $result = array();
    $ids = array();
    while ($info= $db->get_row($infoSQL)){
           $ids[] = $info['id'];
    }
    $result['ids']=implode(',',$ids);
    echo serialize($result);
    $db->free($infoSQL);


