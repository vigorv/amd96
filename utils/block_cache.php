<?php
echo "refreshing cache for randposts from videoxq, RuMedia, animebar...\n";
$memcache_obj = memcache_connect('localhost', 11211);

$nsk54 = "http://videoxq.com/media/cache";
$echo = file_get_contents($nsk54);
if ($echo)
{
	memcache_set($memcache_obj, 'nsk54_randposts', $echo, 0);
	echo "nsk54 OK\n";
}

$animebar = "http://animebar.org/rnn54top.php";
$echo = file_get_contents($animebar);
if ($echo)
{
	memcache_set($memcache_obj, 'animebar_randposts', $echo, 0);
	echo "animebar OK\n";
}

$wsmedia = "http://rumedia.ws/rnn54top.php";
$echo = file_get_contents($wsmedia);
if ($echo)
{
	memcache_set($memcache_obj, 'wsm_randposts', $echo, 0);
	echo "RuMedia OK\n";
}

$memcache_obj->close();
