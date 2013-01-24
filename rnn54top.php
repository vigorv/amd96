<?php
//exit;
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
function truncate($string, $len, $wordsafe = FALSE, $dots = FALSE) {
	$slen = strlen($string);
	if ($slen <= $len) {
		return $string;
	}

	if ($wordsafe) {
		$end = $len;
		while (($string[--$len] != ' ') && ($len > 0)) {};
		if ($len == 0) {
			$len = $end;
		}
	}

	if ((ord($string[$len]) < 0x80) || (ord($string[$len]) >= 0xC0)) {
		return substr($string, 0, $len) . ($dots ? ' ...' : '');
	}

	while (--$len >= 0 && ord($string[$len]) >= 0x80 && ord($string[$len]) < 0xC0) {};
	return substr($string, 0, $len) . ($dots ? ' ...' : '');
}

define ( 'ROOT_DIR', dirname ( __FILE__ ) );
require_once ROOT_DIR . '/engine/data/dbconfig.php';


mysql_connect(DBHOST, DBUSER, DBPASS)or die("Could not connect: " . mysql_error());
mysql_select_db(DBNAME);
mysql_query('SET NAMES cp1251');
$posts = array();

$stopCats = array(3,72,1);
$stopIds = array();
$sql="SELECT id FROM ".USERPREFIX."_category WHERE parentid IN (" . (implode(',',$stopCats)) . ")";
//echo $sql."\n";
$result = mysql_query($sql);
while ($row = mysql_fetch_row($result))
{
	$stopIds[] = $row[0];
}
mysql_free_result($result);
$sql="SELECT id, title, date, alt_name,short_story, category FROM ".USERPREFIX."_post WHERE category NOT IN (" . (implode(',', $stopIds)) . ") AND short_story REGEXP \"<!--TBegin-->\" AND approve='1' ORDER BY RAND() LIMIT 50;";
//echo $sql;
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result))
{
	$rec = "<!--title_begin-->".$row['title']."<!--title_end-->";
	preg_match('/src="([^"]*)"/i', $row['short_story'], $img);
	$rec.= "<!--img_begin-->".$img[1]."<!--img_end-->";
	$rec.= "<!--link_begin-->http://rumedia.ws/".$row['id']."-".$row['alt_name'].".html";
	$desc = preg_replace('#(.*?)<!--TEnd-->(.*?)#si', '\2', $row['short_story']);
	$rec.= "<!--desc_begin-->".strip_tags(truncate($desc, 200))."...<!--desc_end-->";

	$posts[] = $rec;
}
mysql_free_result($result);

echo serialize($posts);