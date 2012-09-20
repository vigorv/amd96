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

function xfieldsdatasave($data)
{
    $filecontents = "";
    $i=1;
    $count_data=count($data);
    foreach ($data as $index => $value) {
        $value= stripslashes($value);
        $value = str_replace("|", "&#124;", $value);
        $value = str_replace("\r\n", "__NEWL__", $value);
        $index2 = str_replace("|", "&#124;", $index);
        $index2 = str_replace("\r\n", "__NEWL__",$index2);
        $filecontents .= $index2.'|'.$value;
        if ($i<$count_data) $filecontents.="||";
        $i++;
    }
    return $filecontents;
}

//set_time_limit(0);
//ignore_user_abort(true);
$infoSQL = $db->query("SELECT id,xfields FROM rm_post ");
$tmpSQL = $db->query("SELECT id,fname FROM rm_fnames ");
$tmp_data =array();
while ($tmp_row = $db->get_row($tmpSQL)){
    $tmp_data[$tmp_row['id']] = $tmp_row['fname'];
}


$i=0;
while  ($row = $db->get_row($infoSQL)) {
    $xdata = xfieldsdataload($row['xfields']);
            if (isset($xdata['direct_links'])){
                $dlinks_data = $xdata['direct_links'];
                $dlinks_data  = str_replace('<br />',PHP_EOL,$dlinks_data );
                $dlinks_data  = str_replace('<br>',PHP_EOL,$dlinks_data );

                preg_match_all("/catalog\/viewv\/[0-9]+/", $dlinks_data,$data);
                $ids =array();
                foreach ($data as &$matches)
                    foreach($matches as &$str)
                         $ids[] = substr($str,14);
                array_unique($ids,SORT_NUMERIC);
                //preg_match_all("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", $dlinks_data,$data2);
                $links= '';
                foreach ($ids as $id){
                    $links.='<a href="http://fastlink.ws/catalog/viewv/'.$id.'">'.$tmp_data[$id].'</a><br/>';
                }
                echo $links;
                //var_dump ($data2);
                //$dlinks_data  = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" >$3</a>", $dlinks_data );
                //$dlinks_data = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $dlinks_data );
                //$srcStr   = str_replace(PHP_EOL,'<br />',$dlinks_data );
                //$xdata['direct_links']= $srcStr;
                $xdata['direct_links']=$links;
                $xfields = xfieldsdatasave($xdata);
                $xfields = str_replace(PHP_EOL,'<br />',$xfields);
                $xfields = filter_var($xfields,FILTER_SANITIZE_MAGIC_QUOTES);

                $sql = 'Update rm_post SET xfields ="' . $xfields . '" WHERE id =' . $row['id'];

                      $db->query($sql);
            }


             //$command->query();
    $i++;
    }
echo $i;

?>
