<?php
@session_start();
@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

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



function netMatch($CIDR, $IP) {
    list ($net, $mask) = explode('/', $CIDR);
    return (ip2long(trim($IP)) & ~((1 << (32 - trim($mask))) - 1)) == ip2long(trim($net));
}

/**
 *  Getters
 *
 */
function getZone($ip) {

    if (isset($_GET['force_zone'])) {
        if ($_GET['force_zone']==0)
            unset ($_SESSION['zone']);
        else
            $_SESSION['zone']=$_GET['force_zone'];
        return $_GET['force_zone'];
    }
    if($_SESSION['zone'] && $_SESSION['zone']<>0)
        return $_SESSION['zone'];

    @include ROOT_DIR . '/engine/cache/system/zones.php';
    $zones=@unserialize($zones);


 if ($cachedate < (time() - 60 * 10)) {
//if(1){
        $zones=array();
        $SelectZonesSQL = $db->query("SELECT * FROM zones order by priority desc");
        while ($zone = $db->get_row($SelectZonesSQL)) {
            $zones[]=$zone;
        }
        $db->free($SelectZonesSQL);

        $handler = @fopen(ROOT_DIR . '/engine/cache/system/zones.php', "w");
        fwrite($handler, "<?PHP \n\n//System Cache\n\n\$cachedate = '" . time() . "';\n\n\$zones ='".serialize($zones)."';  \n\n");
        fwrite($handler, "\n?>");
        fclose($handler);
    }
    $type = 0;
    $isOperaMini = false;
    #while ($zone = $db->get_row($SelectZonesSQL)) {
    foreach ($zones as $zone){
        if (netMatch($zone['network'] . '/' . $zone['mask'], $ip)) {
            $type = $zone['type'];
            $_SESSION['zone']=$type;
            break;
        }
    }


    return $type;
}

if(isset($_GET['ip'])){
    echo getZone($_GET['ip']);
}