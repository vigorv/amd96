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

if (isset($_REQUEST['item_id'])) {
    $item_id = (int)$_REQUEST['item_id'];
    $infoSQL = $db->query("SELECT id,short_story,title,title2,xfields,reason FROM rm_post rp LEFT JOIN rm_post_extras rpe on  rpe.news_id = rp.id where id = $item_id LIMIT 1");
    if ($info = $db->get_row($infoSQL)) {
        $xfieldsdata = xfieldsdataload($info['xfields']);
        $data = array();
        $data['title'] = $info['title'];
        preg_match("/uploads\/posts\/[0-9\-]+\/[0-9a-zA-Z\_\-\.]+/", $info['short_story'], $images);
        $data['image'] = $images[0];
        $data['reason'] = $info['reason'];
        if (isset($xfieldsdata['m_original_name'])) {
            $movie['original_title'] = $xfieldsdata['m_original_name'];
            $movie['year'] = $xfieldsdata['m_year'];
            $movie['country'] = $xfieldsdata['m_country'];
            $movie['director'] = $xfieldsdata['m_director'];
            $movie['actors'] = $xfieldsdata['m_actors'];
            $movie['original_title']= $info['title2'];
            $data['movie'] = $movie;
        }
        foreach ($data as &$value)
            if (is_array($value)) {
                foreach ($value as &$value_dim2)
                    $value_dim2 = iconv('windows-1251', 'utf-8', $value_dim2);
            } else
                $value = iconv('windows-1251', 'utf-8', $value);
        echo serialize($data);
    } else {
        echo serialize(array('error' => 1));
    }
    $db->free($infoSQL);

}