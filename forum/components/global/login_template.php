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

$tpl->load_template( 'login.tpl' );

if ($logged)
{
    $lang_g_login_template = language_forum ("board/global/login_template");

	$tpl->tags( '{member_name}', $cache_group[$member_id['user_group']]['g_prefix_st'].$member_id['name'].$cache_group[$member_id['user_group']]['g_prefix_end'] );
    $tpl->tags( '{profile_link}', profile_link($member_id['name'], $member_id['user_id']));
    $tpl->tags( '{favorite}', member_favorite($member_id['name'], $member_id['user_id']));
    $tpl->tags( '{subscribe}', member_subscribe());
    $tpl->tags( '{profile_options}', profile_edit_link($member_id['name'], $member_id['user_id'], "options"));
    $tpl->tags( '{pm_link}', pm_member());
    $tpl->tags( '{new}', $member_id['pm_unread']);
    $tpl->tags( '{pm_all}', $member_id['pm_all']);
    
	$tpl->tags( '{member_logout}', "<a href=\"".$redirect_url."?logout=yes&secret_key=".$secret_key."\">".$lang_g_login_template['exit']."</a>" );
	if ($cache_group[$member_id['user_group']]['g_access_cc'] == "1")
		$tpl->tags( '{controlcenter}', "[ <a href=\"".$redirect_url."control_center/index.php\" target=\"_blank\">".$lang_message['control_center']."</a> ]" );
	else
		$tpl->tags( '{controlcenter}', "" );
        
    $tpl->tags('{member_avatar}', member_avatar($member_id['foto'])); 
}
else
{
    $tpl->tags( '{link_registration}', link_on_module_dle("register"));
    $tpl->tags( '{link_lostpass}', link_on_module_dle("lostpassword"));
    $tpl->tags( '{ulogin}', urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
}
		
$tpl->compile( 'login' );
$tpl->clear();

?>