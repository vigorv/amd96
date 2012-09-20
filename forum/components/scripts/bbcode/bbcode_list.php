<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if (!isset($lang_bbcode_list)) $lang_bbcode_list = language_forum ("board/scripts/bbcode/bbcode_list");

$list_allow_bbcode_arr = array();
        
$list_allow_bbcode_arr[1] = array();
$list_allow_bbcode_arr[1]['name'] = "b";
$list_allow_bbcode_arr[1]['title'] = $lang_bbcode_list['b'];
        
$list_allow_bbcode_arr[2] = array();
$list_allow_bbcode_arr[2]['name'] = "i";
$list_allow_bbcode_arr[2]['title'] = $lang_bbcode_list['i'];
        
$list_allow_bbcode_arr[3] = array();
$list_allow_bbcode_arr[3]['name'] = "s";
$list_allow_bbcode_arr[3]['title'] = $lang_bbcode_list['s'];
        
$list_allow_bbcode_arr[4] = array();
$list_allow_bbcode_arr[4]['name'] = "u";
$list_allow_bbcode_arr[4]['title'] = $lang_bbcode_list['u'];
        
$list_allow_bbcode_arr[5] = array();
$list_allow_bbcode_arr[5]['name'] = "size";
$list_allow_bbcode_arr[5]['title'] = $lang_bbcode_list['size'];
        
$list_allow_bbcode_arr[6] = array();
$list_allow_bbcode_arr[6]['name'] = "color";
$list_allow_bbcode_arr[6]['title'] = $lang_bbcode_list['color'];
        
$list_allow_bbcode_arr[7] = array();
$list_allow_bbcode_arr[7]['name'] = "quote";
$list_allow_bbcode_arr[7]['title'] = $lang_bbcode_list['quote'];
        
$list_allow_bbcode_arr[8] = array();
$list_allow_bbcode_arr[8]['name'] = "smile";
$list_allow_bbcode_arr[8]['title'] = $lang_bbcode_list['smiles'];
        
$list_allow_bbcode_arr[9] = array();
$list_allow_bbcode_arr[9]['name'] = "font";
$list_allow_bbcode_arr[9]['title'] = $lang_bbcode_list['font'];
        
$list_allow_bbcode_arr[10] = array();
$list_allow_bbcode_arr[10]['name'] = "spoiler";
$list_allow_bbcode_arr[10]['title'] = $lang_bbcode_list['spoiler'];
        
$list_allow_bbcode_arr[11] = array();
$list_allow_bbcode_arr[11]['name'] = "youtube";
$list_allow_bbcode_arr[11]['title'] = $lang_bbcode_list['youtube'];
        
$list_allow_bbcode_arr[12] = array();
$list_allow_bbcode_arr[12]['name'] = "translite";
$list_allow_bbcode_arr[12]['title'] = $lang_bbcode_list['translite'];
        
$list_allow_bbcode_arr[13] = array();
$list_allow_bbcode_arr[13]['name'] = "php";
$list_allow_bbcode_arr[13]['title'] = $lang_bbcode_list['php'];

$list_allow_bbcode_arr[14] = array();
$list_allow_bbcode_arr[14]['name'] = "html";
$list_allow_bbcode_arr[14]['title'] = $lang_bbcode_list['html'];
        
$list_allow_bbcode_arr[15] = array();
$list_allow_bbcode_arr[15]['name'] = "javascript";
$list_allow_bbcode_arr[15]['title'] = $lang_bbcode_list['js'];
        
$list_allow_bbcode_arr[16] = array();
$list_allow_bbcode_arr[16]['name'] = "hide";
$list_allow_bbcode_arr[16]['title'] = $lang_bbcode_list['hide'];

$list_allow_bbcode_arr[17] = array();
$list_allow_bbcode_arr[17]['name'] = "url";
$list_allow_bbcode_arr[17]['title'] = $lang_bbcode_list['url'];
        
$list_allow_bbcode_arr[18] = array();
$list_allow_bbcode_arr[18]['name'] = "img";
$list_allow_bbcode_arr[18]['title'] = $lang_bbcode_list['img'];
        
$list_allow_bbcode_arr[19] = array();
$list_allow_bbcode_arr[19]['name'] = "email";
$list_allow_bbcode_arr[19]['title'] = $lang_bbcode_list['email'];

$list_allow_bbcode_arr[20] = array();
$list_allow_bbcode_arr[20]['name'] = "text_align";
$list_allow_bbcode_arr[20]['title'] = $lang_bbcode_list['text_align'];

$list_allow_bbcode_arr[21] = array();
$list_allow_bbcode_arr[21]['name'] = "search";
$list_allow_bbcode_arr[21]['title'] = $lang_bbcode_list['search'];

?>