<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$lang_board = language_forum ("control_center/board");

if(control_center_admins($member_cca['board']['board']))
{
    switch ($op)
    {
	   case "addcategory":
            if(control_center_admins($member_cca['board']['addcategory']))
                include_once LB_CONTROL_CENTER . '/board/addcategory.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['addcategory_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['addcategory_online'];
                control_center_admins_error();
            }
	   break;

	   case "addforum":
            if(control_center_admins($member_cca['board']['addforum']))
                include_once LB_CONTROL_CENTER . '/board/addforum.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['addforum_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['addforum_online'];
                control_center_admins_error();
            }
	   break;

	   case "editforum":
            if(control_center_admins($member_cca['board']['editforum']))
                include_once LB_CONTROL_CENTER . '/board/edit_cat_forum.php';
            else
            {
                if ($cache_forums[$id]['parent_id'] == 0)
                {
                    $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['addforum_speedbar']);
                    $link_speddbar = str_replace("{title}", $cache_forums[$id]['title'], $link_speddbar);
                    $control_center->header($lang_board['header'], $link_speddbar);
                    $onl_location = str_replace("{title}", $cache_forums[$id]['title'], $lang_board['editforum_online']);
                }
                else
                {
                    $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['addforum_speedbar_2']);
                    $link_speddbar = str_replace("{title}", $cache_forums[$id]['title'], $link_speddbar);
                    $control_center->header($lang_board['header'], $link_speddbar);
                    $onl_location = str_replace("{title}", $cache_forums[$id]['title'], $lang_board['editforum_online_2']);
                }
                control_center_admins_error();
            }
   	    break;

	   case "delforum":
            if(control_center_admins($member_cca['board']['delforum']))
                include_once LB_CONTROL_CENTER . '/board/delforum.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['delforum_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['delforum_online'];
                control_center_admins_error();
            }
	   break;
    
       case "moderators":
            if(control_center_admins($member_cca['board']['moders']))
                include_once LB_CONTROL_CENTER . '/board/moderators.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['moderators_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['moderators_online'];
                control_center_admins_error();
            }
	   break;
    
       case "moder_add":
            if(control_center_admins($member_cca['board']['moders']) AND control_center_admins($member_cca['board']['moders_edit']))
                include_once LB_CONTROL_CENTER . '/board/moderators_add.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['moder_add_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=board&op=moderators", $link_speddbar);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['moder_add_online'];
                control_center_admins_error();
            }
	   break;

       case "moder_edit":
            if(control_center_admins($member_cca['board']['moders']) AND control_center_admins($member_cca['board']['moders_edit']))
                include_once LB_CONTROL_CENTER . '/board/moderators_edit.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['moder_edit_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=board&op=moderators", $link_speddbar);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['moder_edit_online'];
                control_center_admins_error();
            }
	   break;
       
       case "words_filter":
            if(control_center_admins($member_cca['board']['filters']))
                include_once LB_CONTROL_CENTER . '/board/words_filter.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['words_filter_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['words_filter_online'];
                control_center_admins_error();
            }
	   break;
       
       case "notice":
            if(control_center_admins($member_cca['board']['notice']))
                include_once LB_CONTROL_CENTER . '/board/notice.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['notice_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['notice_online'];
                control_center_admins_error();
            }
	   break;
       
       case "notice_add":
            if(control_center_admins($member_cca['board']['notice']))
                include_once LB_CONTROL_CENTER . '/board/notice_add.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['notice_add_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=board&op=notice", $link_speddbar);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['notice_add_online'];
                control_center_admins_error();
            }
	   break;
       
       case "notice_edit":
            if(control_center_admins($member_cca['board']['notice']))
                include_once LB_CONTROL_CENTER . '/board/notice_edit.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['notice_edit_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=board&op=notice", $link_speddbar);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['notice_edit_online'];
                control_center_admins_error();
            }
	   break;
       
       case "sharelink":
            if(control_center_admins($member_cca['board']['sharelink']))
                include_once LB_CONTROL_CENTER . '/board/sharelink.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['sharelink_speedbar']);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['sharelink_online'];
                control_center_admins_error();
            }
	   break;
       
       case "sharelink_add":
            if(control_center_admins($member_cca['board']['sharelink_edit']))
                include_once LB_CONTROL_CENTER . '/board/sharelink_add.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['sharelink_add_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=board&op=sharelink", $link_speddbar);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['sharelink_add_online'];
                control_center_admins_error();
            }
	   break;
       
       case "sharelink_edit":
            if(control_center_admins($member_cca['board']['sharelink_edit']))
                include_once LB_CONTROL_CENTER . '/board/sharelink_edit.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=board", $lang_board['sharelink_edit_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=board&op=sharelink", $link_speddbar);
                $control_center->header($lang_board['header'], $link_speddbar);
                $onl_location = $lang_board['sharelink_edit_online'];
                control_center_admins_error();
            }
	   break;

	   default :
		  include_once LB_CONTROL_CENTER . '/board/main.php';
	   break;
    }
}
else
{   
    $link_speddbar = $lang_board['header'];
    $control_center->header($lang_board['header'], $link_speddbar);
    $onl_location = $lang_board['header'];
    control_center_admins_error();
}

$control_center->footer(3);

?>