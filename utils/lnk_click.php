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



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['chksum'])
            && !empty($_POST['ip'])
            && !empty($_POST['user_id'])
            && !empty($_POST['created'])
            && !empty($_POST['id'])
            && !empty($_POST['href'])
    ) {
        if ($_POST['chksum'] == md5($_POST['user_id'] . $_POST['created'] . $_POST['id'])) {//��������� ��������                       
            $href = str_replace('http://', '', strtolower(substr($_POST['href'], 0, 255)));
            $href = explode('/', $href);
            unset($href[0]);
            $href = addslashes(implode('/', $href));
            $sql = 'select id from wsm_loads where news_id=' . $_POST["id"] . ' and user_id=' . $_POST["user_id"] . ' and created > "' . date('Y-m-d H:i:s', time() - 60 * 60 * 24) . '" and href="' . $href . '" limit 1';
            $query = $db->query($sql);
            if (!$db->get_row($query)) {//���� ������� �� ������� �� ������
                $sql = 'insert delayed into wsm_loads (id, news_id, created, user_id, zone, ip, href) values (null, ' . $_POST["id"] . ', "' . $_POST["created"] . '", ' . $_POST["user_id"] . ', "' . $_POST["zone"] . '", "' . $_POST["ip"] . '", "' . $href . '")';
                $db->query($sql);
            }
            $db->free($query);
            $db->close;
        }
    }
}