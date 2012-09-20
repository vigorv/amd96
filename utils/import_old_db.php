<?php
exit();
@session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0 

date_default_timezone_set('Asia/Novosibirsk');
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_NOTICE);

define('DATALIFEENGINE', true);
define('ROOT_DIR', "..");
define('ENGINE_DIR', ROOT_DIR . '/engine');

require_once(ENGINE_DIR . '/data/config.php');
require_once('./../upgrade/mysql.php');
require_once(ENGINE_DIR . '/data/dbconfig.php');
require_once(ENGINE_DIR . '/inc/include/functions.inc.php');

$version_id = ($config_version_id) ? $config_version_id : $config['version_id'];

$js_array = array();
$theme = ENGINE_DIR;
// require_once(dirname(__FILE__) . '/../upgrade/template.php');

extract($_REQUEST, EXTR_SKIP);

$dle_version = "9.4";

$config['version_id'] = "9.4";
$config['auth_metod'] = "0";
$config['comments_ajax'] = "0";
$config['create_catalog'] = "0";
$config['mobile_news'] = "10";
$config['reg_question'] = "0";
set_time_limit(0);

$import_db = new db;
$import_db->connect(DBUSER, DBPASS, 'wsmedia', DBHOST);

function getFieldList($tablename) {
    global $db;
    echo $sql = 'SHOW COLUMNS FROM ' . $tablename;
    $db->query($sql);


    $result = array();
    //var_dump($columns);
    while ($column = $db->get_array()) {
        //var_dump($column);
        $result[$column['Field']] = $column['Default'];
    }
    return $result;
}
echo "<pre>";
$lst1 = array('_banned', '_category', '_comments', '_email', '_flood', '_lostdb', '_images', '_static', '_static_files', '_post', '_users');
$lst3 = array('_post');
if (!isset($_GET['action'])) die('Bye bye');

if ($_GET['action'] == 1)
    foreach ($lst1 as $tablename) {
        echo "start $tablename ";
        $q_id = $import_db->query("SELECT * FROM `dle" . $tablename . "`");
        $db->query('DELETE FROM `rm' . $tablename . '`');


        $flist = getFieldList('rm' . $tablename);
        $klist = implode(',', array_keys($flist));

        //   var_dump($flist);

        if ($q_id) {
            $i = 0;

            echo $klist;
            echo 'FOUND ' . $import_db->num_rows() . PHP_EOL;
            echo 'CLEAN rm' . $tablename . PHP_EOL;

            while ($res = $import_db->get_row($q_id)) {
                $data = $flist;
                foreach ($data as $key => $item) {
                    $data[$key] = $import_db->safesql($res[$key]);
                    //    echo $key; echo $item;
                }
                //  var_dump($res);

                $val = "'".implode("','", array_values($data))."'";
                $sql = 'INSERT INTO `rm' . $tablename . '` (' . $klist . ') VALUES (' . $val . ')';
                //                echo $sql;
                if ($db->query($sql))
                    $i++;
                else{
                    echo $res['id'].PHP_EOL;
                }
                
            }
            //die();
            echo 'rm_' . $tablename . " " . $i . ' inserted' . PHP_EOL;
        } else
            "BAD dle_" . $tablename . PHP_EOL;
    }



/* banner  ext import */
if ($_GET['action'] == 2) {
    $tablename = 'banners';
    $q_id = $import_db->query("SELECT * FROM `dle_" . $tablename . "`");
    $db->query('DELETE FROM `rm_' . $tablename . '`');

    $flist = getFieldList('rm_' . $tablename);
    $klist = implode(',', array_keys($flist));

    //   var_dump($flist);
    echo $klist;
    echo 'FOUND ' . $import_db->num_rows() . PHP_EOL;
    echo 'CLEAN rm_' . $tablename . PHP_EOL;

    if ($q_id) {
        $i = 0;
        while ($res = $import_db->get_row($q_id)) {
            $data = $flist;
            foreach ($data as $key => &$item) {
                $item = $res[$key];

                if ($key == 'is_gzone') {
                    $item = 0;
                    $item = $item | $res['is_ws'] << 1;
                    $item = $item | $res['is_internet'];
                }
                //    echo $key; echo $item;
            }
            //  var_dump($res);

            $val = '\'' . implode("','", array_values($data)) . '\'';
            $sql = 'INSERT INTO `rm_' . $tablename . '` (' . $klist . ') VALUES (' . $val . ')' . PHP_EOL;
            $db->query($sql);

            $i++;
        }
        //die();
        echo 'rm_' . $tablename . " " . $i . ' inserted' . PHP_EOL;
    } else
        "BAD dle_" . $tablename . PHP_EOL;
}

if ($_GET['action'] == 3) {
    $lst1 = array('error404', 'link_views', 'post_change_files', 'zones');
}

echo "</pre>";