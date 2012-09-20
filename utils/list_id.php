<?php

    @session_start ();
    $list_id = @$_POST['list_id'];
    if(!isset($_COOKIE['list_id']))
        $_COOKIE['list_id']= $list_id;
    if(!isset($_SESSION['Banned_id']))
        $_SESSION['Banned_id']= $_COOKIE['list_id'];