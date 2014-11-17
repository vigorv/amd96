<?php
echo "!!!";

/**
 * rumedia
 */
#include_once ENGINE_DIR . '/classes/parse.class.php';
$member_id;
global $tpl;
echo "2";


function EncodeAB($str, $type) { // $type: 'w' - из UTF в win 'u' - из win в UTF
    static $conv = '';
    if (!is_array($conv)) {
        $conv = array();
        for ($x = 128; $x <= 143; $x++) {
            $conv['utf'][] = chr(209) . chr($x);
            $conv['win'][] = chr($x + 112);
        }
        for ($x = 144; $x <= 191; $x++) {
            $conv['utf'][] = chr(208) . chr($x);
            $conv['win'][] = chr($x + 48);
        }
        $conv['utf'][] = chr(208) . chr(129);
        $conv['win'][] = chr(168);
        $conv['utf'][] = chr(209) . chr(145);
        $conv['win'][] = chr(184);
    }
    if ($type == 'w')
        return str_replace($conv['utf'], $conv['win'], $str);
    elseif ($type == 'u')
        return str_replace($conv['win'], $conv['utf'], $str);
    else
        return $str;
}

$animebar_data='';
$videoxq_data='';

$source = '';
$memcache_obj = memcache_connect('localhost', 11211);

//$maxid=0;
$posts = memcache_get($memcache_obj, 'animebar_randposts');
if ($posts) {
    $posts = unserialize($posts);
    $postid = rand(0, count($posts) - 1);
    $source = $posts[$postid];
    $matches = array();
    preg_match_all('/-->([^<]+)<!--/', $source, $matches);
    $title = str_replace('\"', '"', $matches[1][0]);
    $img = $matches[1][1];
    $link = $matches[1][2];
    $desc = htmlspecialchars(str_replace('\"', '"', $matches[1][3]));

    $title = EncodeAB($title, 'w');

	$animebar_data = "<div class=\"tizer-news-image\"><a href=\"" . $link . "\"><img src=\"" . $img . "\" title=\"" . $desc . "\" alt=\"" . $desc . "\" /></a></div><h2><a href=\"" . $link . "\">" . $title . "</a></h2>";
	#$animebar_data = "<div align=\"center\"><a href=\"" . $link . "\"><img src=\"" . $img . "\" title=\"" . $desc . "\" alt=\"" . $desc . "\" /></a><br /><a href=\"" . $link . "\">" . $title . "</a></div>";
}
//$maxid=0;
    $posts = memcache_get($memcache_obj, 'nsk54_randposts');
    echo "3";print_r($posts);
    if ($posts) {
        $posts = unserialize($posts);
        $postid = rand(0, count($posts) - 1);
        $link = 'http://videoxq.com/media/view/' . $posts[$postid]['Film']['id'];
        $title = iconv('utf8', 'windows-1251', $posts[$postid]['Film']['title']);
        $picture = $posts[$postid]['p']['file_name'];
       
        $videoxq_data = "<div class=\"tizer-news-image\"><a href=\"" . $link . "\"><img src=\" http://data2.videoxq.com/img/catalog/" . $picture . "\" title=\"" . $title . "\" alt=\"" . $title . "\" /></a></div><h2><a href=\"" . $link . "\">" . $title . "</a></h2>";
		
        #$videoxq_data = "<div align=\"center\"><a href=\"" . $link . "\"><img style='max-width:200px' src=\" http://data2.videoxq.com/img/catalog/" . $picture . "\" title=\"" . $title . "\" alt=\"" . $title . "\" /></a><br /><a href=\"" . $link . "\">" . $title . "</a></div>";
    }
    if ($memcache_obj)
        $memcache_obj->close();

echo "5".$videoxq_data;
    $tpl->set('{tizer_videoxq}', '<strong><a href="http://videoxq.com/" title="Самый большой каталог кино для WebStream">VideoXQ.com</a></strong>'. $videoxq_data );

    $tpl->set('{tizer_animebar}', '<strong><a href="http://www.animebar.org/" title="Твой аниме портал">AnimeBar.org</a></strong>' . $animebar_data );
/*
    $tpl->set('{tizer_videoxq}', '<div class="lblockhead"><a href="http://videoxq.com/" title="Самый большой каталог кино для WebStream">В каталоге videoxq.com</a></div><div class="lblockcon"><div class="lblockcon"><div class="lblockcon"><div class="lblockcon">'. $videoxq_data . '</div></div></div></div>');

    $tpl->set('{tizer_animebar}', '
<div class="lblockhead">  <a href="http://www.animebar.org/" title="Твой аниме портал">На AnimeBar.org</a></div>
    <div class="lblockcon"><div class="lblockcon"><div class="lblockcon"><div class="lblockcon">' . $animebar_data . '</div></div></div></div>');
*/
    