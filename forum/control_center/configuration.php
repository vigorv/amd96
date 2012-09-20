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

$lang_conf = language_forum ("control_center/configuration");
    
if(control_center_admins($member_cca['config']['config']))
{
    switch ($op)
    {
	   case "addgroup":
            if(control_center_admins($member_cca['config']['add']))
                include_once LB_CONTROL_CENTER . '/configuration/addgroup.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['addgroup_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_conf['addgroup_online'];
                control_center_admins_error();
            }
       break;

	   case "editgroup":
            if(control_center_admins($member_cca['config']['change']))
                include_once LB_CONTROL_CENTER . '/configuration/editgroup.php';
            else
            {
                $editgroup = $DB->one_select( "*", "configuration_group", "conf_gr_id = '{$id}'" );
                
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_board['editgroup_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=configuration&op=show&id=".$id, $link_speddbar);
                $link_speddbar = str_replace("{title}", $editgroup['conf_gr_name'], $link_speddbar);

                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = str_replace("{title}", $editgroup['conf_gr_name'], $lang_board['editgroup_online']);
                control_center_admins_error();
                $DB->free($editgroup);
            }
	   break;

	   case "delgroup":
            if(control_center_admins($member_cca['config']['del']))
                include_once LB_CONTROL_CENTER . '/configuration/delgroup.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['delgroup_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_conf['delgroup_online'];
                control_center_admins_error();
            }
	   break;

	   case "show":
            include_once LB_CONTROL_CENTER . '/configuration/show.php';
	   break;

	   case "addconf":
            if(control_center_admins($member_cca['config']['add']))
                include_once LB_CONTROL_CENTER . '/configuration/addconf.php';
            else
            {
                $ongroup = $DB->one_select( "*", "configuration_group", "conf_gr_id = '{$id}'" );
                
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_board['addconf_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=configuration&op=show&id=".$id, $link_speddbar);
                $link_speddbar = str_replace("{title}", $ongroup['conf_gr_name'], $link_speddbar);
                
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = str_replace("{title}", $ongroup['conf_gr_name'], $lang_board['addconf_online']);
                control_center_admins_error();
                $DB->free($ongroup);
            }
	   break;

	   case "editconf":
            if(control_center_admins($member_cca['config']['change']))
                include_once LB_CONTROL_CENTER . '/configuration/editconf.php';
            else
            {
                $edit_conf = $DB->one_select( "*", "configuration", "conf_id='{$id}'" );
                $group_conf = $DB->one_select( "conf_gr_name", "configuration_group", "conf_gr_id='{$edit_conf['conf_group']}'" );
                
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_board['editconf_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=configuration&op=show&id=".$edit_conf['conf_group'], $link_speddbar);
                $link_speddbar = str_replace("{title}", $group_conf['conf_gr_name'], $link_speddbar);
                
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = str_replace("{title}", $group_conf['conf_gr_name'], $lang_board['editconf_online']);
                control_center_admins_error();
                $DB->free($edit_conf);
                $DB->free($group_conf);
            }
	   break;

	   case "delconf":
            if(control_center_admins($member_cca['config']['del']))
                include_once LB_CONTROL_CENTER . '/configuration/delconf.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['delconf_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_conf['delconf_online'];
                control_center_admins_error();
            }
	   break;

	   case "email":
            if(control_center_admins($member_cca['config']['email']))
                include_once LB_CONTROL_CENTER . '/configuration/email.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['email_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_conf['email_online'];
                control_center_admins_error();
            }
	   break;

	   case "email_edit":
            if(control_center_admins($member_cca['config']['email']))
                include_once LB_CONTROL_CENTER . '/configuration/email_edit.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_board['email_edit_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=configuration&op=email", $link_speddbar);
                
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_board['email_edit_online'];
                control_center_admins_error();
            }
	   break;

	   case "email_del":
            if(control_center_admins($member_cca['config']['email']))
                include_once LB_CONTROL_CENTER . '/configuration/email_del.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_board['email_del_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=configuration&op=email", $link_speddbar);
                
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_board['email_del_online'];
                control_center_admins_error();
            }
	   break;

	   case "email_add":
            if(control_center_admins($member_cca['config']['email']))
                include_once LB_CONTROL_CENTER . '/configuration/email_add.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_board['email_add_speedbar']);
                $link_speddbar = str_replace("{link_2}", $redirect_url."?do=configuration&op=email", $link_speddbar);
                
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_board['email_add_online'];
                control_center_admins_error();
            }
	   break;
    
       case "template":
            if(control_center_admins($member_cca['config']['template']))
                include_once LB_CONTROL_CENTER . '/configuration/template.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['template_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_board['template_online'];
                control_center_admins_error();
            }
	   break;
       
       case "lang":
            if(control_center_admins($member_cca['config']['language']))
                include_once LB_CONTROL_CENTER . '/configuration/language.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['lang_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_board['lang_online'];
                control_center_admins_error();
            }
	   break;
       
       case "user_agent":
            if(control_center_admins($member_cca['config']['user_agent']))
                include_once LB_CONTROL_CENTER . '/configuration/user_agent.php';
            else
            {
                $link_speddbar = str_replace("{link}", $redirect_url."?do=configuration", $lang_conf['user_agent_speedbar']);
                $control_center->header($lang_conf['header'], $link_speddbar);
                $onl_location = $lang_board['user_agent_online'];
                control_center_admins_error();
            }
	   break;

	   default :
            include_once LB_CONTROL_CENTER . '/configuration/main.php';
	   break;
    }
}
else
{
    $control_center->header($lang_conf['header'], $lang_conf['header']);
    $onl_location = $lang_conf['header'];
    control_center_admins_error();
}

if (!isset($_SESSION['back_link_conf']))
    $_SESSION['back_link_conf'] = $redirect_url."?do=configuration";

$control_center->footer(1);

?>