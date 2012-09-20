<?php

/**
 * rumedia
 */
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}

global $tpl;
$tpl->set('{ip_addr}',$_SERVER['REMOTE_ADDR']);