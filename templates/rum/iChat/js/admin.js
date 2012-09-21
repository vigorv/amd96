/*====================================================
 Author: RooTM
------------------------------------------------------
 Web-site: http://weboss.net/
=====================================================*/

function CheckUpdates()
{

document.getElementById( 'progres' ).innerHTML = '<div style="background: #CCFFFF;border:1px dotted rgb(190,190,190); padding: 5px;margin-top: 5px;margin-right: 5px;"><img src="'+dle_root+'templates/'+dle_skin+'/iChat/img/loading.gif" border="0" align="absmiddle"><b> '+iChat_lang_loading+'</b></div>';

	iShowLoading('');

	$.post(dle_root + "engine/modules/iChat/ajax/admin.php", { check: "updates" }, function(data){

		iHideLoading('');

		$("#content").html(data);

	});

	return false;
};

function iChatClearBd()
{

DLEconfirm( dle_del_agree, dle_confirm, function () {

document.getElementById( 'progres' ).innerHTML = '<div style="background: #CCFFFF;border:1px dotted rgb(190,190,190); padding: 5px;margin-top: 5px;margin-right: 5px;"><img src="'+dle_root+'templates/'+dle_skin+'/iChat/img/loading.gif" border="0" align="absmiddle"><b> '+iChat_lang_loading+'</b></div>';

	iShowLoading('');

	$.post(dle_root + "engine/modules/iChat/ajax/admin.php", { action: "clear" }, function(data){

	     iHideLoading('');

	$("#content").fadeOut(500, function() {
			$(this).html(data);
			$(this).fadeIn(500);
	});

	});

reFreshiChat();

	return false;

} );

};

function SaveCfg()
{

document.getElementById( 'progres' ).innerHTML = '<div style="background: #CCFFFF;border:1px dotted rgb(190,190,190); padding: 5px;margin-top: 5px;margin-right: 5px;"><img src="'+dle_root+'templates/'+dle_skin+'/iChat/img/loading.gif" border="0" align="absmiddle"><b> '+iChat_lang_loading+'</b></div>';

var cfg01 =  document.getElementById('cfg01').value;
var cfg02 =  document.getElementById('cfg02').value;
var cfg03 =  document.getElementById('cfg03').value;
var cfg04 =  document.getElementById('cfg04').value;
var cfg05 =  document.getElementById('cfg05').value;
var cfg06 =  document.getElementById('cfg06').value;
var cfg07 =  document.getElementById('cfg07').value;
var cfg08 =  document.getElementById('cfg08').value;
var cfg12 =  document.getElementById('cfg12').value;
var cfg13 =  document.getElementById('cfg13').value;
var cfg14 =  document.getElementById('cfg14').value;
var cfg15 =  document.getElementById('cfg15').value;
var cfg16 =  document.getElementById('cfg16').value;

	iShowLoading('');

	$.post(dle_root + "engine/modules/iChat/ajax/admin.php", { action: "save", "save_cfg[sum_msg]": cfg01, "save_cfg[max_text]": cfg12, "save_cfg[format_date]": cfg13, "save_cfg[refresh]": cfg02, "save_cfg[stop_flood]": cfg03, "save_cfg[max_word]": cfg04, "save_cfg[cron_clean]": cfg05, "save_cfg[smiles]": cfg06, "save_cfg[stop_bbcode]": cfg07, "save_cfg[groups_color]": cfg08, "save_cfg[allow_guest]": cfg14, "save_cfg[sum_msg_history]": cfg15, "save_cfg[no_access]": cfg16  }, function(data){


		iHideLoading('');

	$("#content").fadeOut(500, function() {
			$(this).html(data);
			$(this).fadeIn(500);
	});

	});

reFreshiChat();

	return false;
};
