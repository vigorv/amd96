<?php
/*
=====================================================
 ������ ������ Rss Grabber
 http://rss-grabber.ru/
 �����: Andersoni
 �� �����: Alex
 Copyright (c) 2009-2010
=====================================================
*/

if( !defined( 'DATALIFEENGINE') ) {
die( "Hacking attempt!");
}
$file = ENGINE_DIR.'/data/rss_config.php';
            @chmod($file, 0644);
            if(is_writable($file)){
            }else{
                @chmod($file, 0666);
                if(is_writable($file)){
                }else{
                    $file_status = '<div style="color: red;"><b>������! ������ ������������ ���������</b><br><font color="green">../engine/data/rss_config.php</font></div>';
                }
			}



if($_POST["act"] == "sav"){
if($_POST['sin_dop_'] != '' )$sin_dop = array ('sin_dop'=>implode (',',$_POST['sin_dop_']));
else $sin_dop =array ('sin_dop'=>'');
if ($_POST['code_bb_'] != '')$code_bb = array ('code_bb'=>implode (',',$_POST['code_bb_']));
else $code_bb = array ('code_bb'=>'');
$grups = array ('reg_group'=>implode (',',$_POST['conf_logs_']));
$config_rss = $module_info +$_POST['conf_log'] + $grups + $code_bb+$sin_dop;
$handler = fopen($file,"w");
fwrite($handler,"<?php
	/*======================================
	Configuration RSS grabber's
	======================================*/
	");
fwrite($handler,"\$config_rss = ".var_export ($config_rss,true).';'."\n?".'>');
fclose($handler);
clear_cache ();
msg("info",$lang_grabber['glob_options1'],"$lang_grabber[opt_sysok_1]<br /><br /><a href=$PHP_SELF?mod=rss>$lang[db_prev]</a>");
}
elseif($_POST["act"] != "sav")
{echoheader('','');
opentable ($lang_grabber['glob_options1']);
$config_form ='';
foreach ( $user_group as $group )
$sys_group_arr[$group['id']] = $group['group_name'];
$sys_group = get_groups();
echo '
<form method="post" name="config" id="config">
<input type="hidden" name="act" value="sav">
<table width="100%">
<script type="text/javascript" src="engine/inc/plugins/ajaxupload.js"></script>
<script type="text/javascript" src="engine/inc/plugins/script.js"></script>
';
tablehead ('<b>'.$lang_grabber['down_radikal_glob'].'</b>');
echo java_host();

if ($config['version_id'] < '8.5'){
echo '<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/dle_ajax.js"></script>';
}


echo "

<script type=\"text/javascript\">

  function SIF () {
    document.form.uploadfile.value = document.config.uploadfile.value;
    document.form.submit();
  }

	function find_upload ( id, key )
	{
		var ajax = new dle_ajax();
		ajax.onShow ('');

var varsString = 'key1=' + id;

		ajax.setVar(\"key\", key);

		ajax.requestFile ='engine/ajax/proxy_up.php';
		ajax.method = 'POST';
		ajax.element = 'find_upload';
		ajax.sendAJAX(varsString);
return false;
	}
</script>
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='{$config['http_home_url']}engine/ajax/loading.gif'	border='0' /></div>";
echo "  <script type=\"text/javascript\">
function ShowOrHideEx(id, show) {
	var item = null;
	if (document.getElementById) {
		item = document.getElementById(id);
	} else if (document.all) {
		item = document.all[id];
	} else if (document.layers){
		item = document.layers[id];
	}
	if (item && item.style) {
		item.style.display = show ? \"\" : \"none\";
	}
};
</script>
<tr>
		<td style=\"padding:4px\" class=\"option\"><b>{$lang_grabber['down_pics']}</b>
		<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$lang_grabber['help_post_rad']}', this, event, '220px')\">[?]</a>
		</td>
<td width=394 align=middle>
<select name=\"conf_log[img_host]\" id = \"img_host\" onchange=\"onImgChange(this.value)\">
".server_host($config_rss['img_host'] )."
</select>

<tbody style=\"background: #FFF9E0; color:red\" id= \"radikal\">";
tablehead ('<font color="red">�������</font>');
showRow( $lang_grabber['radikal_water'],$lang_grabber['help_radikal_water'], makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes'] ),"conf_log[water_radikal]",$config_rss['water_radikal'] ));
showRow( $lang_grabber['radikal_post'],$lang_grabber['help_radikal_post'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[post_radikal]' value=\"{$config_rss['post_radikal']}\" size=50>" );
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[url_radikal]",$config_rss['url_radikal']) );
tablehead ($lang_grabber['post_max_size'].' 10 Mb');
/*
echo "</tbody><tbody style=\"background: #FFF9E0; color:red\" id= \"img_sklad\">";
tablehead ('<font color="red">Img-Sklad</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[url_img_sklad]",$config_rss['url_img_sklad']) );
tablehead ($lang_grabber['post_max_size'].' 1 Mb');
*/
echo "</tbody>
<tbody style=\"background: #FFF9E0; color:red\" id= \"ambrybox\">";
tablehead ('<font color="red">ambrybox.com</font>');
showRow( $lang_grabber['radikal_water'],$lang_grabber['help_radikal_water'], makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes'] ),"conf_log[water_ambrybox]",$config_rss['water_ambrybox'] ));
showRow( $lang_grabber['radikal_post'],$lang_grabber['help_radikal_post'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[post_ambrybox]' value=\"{$config_rss['post_ambrybox']}\" size=50>" );
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[ambrybox]",$config_rss['ambrybox']) );
tablehead ($lang_grabber['post_max_size'].' 10 Mb');

echo "</tbody>
<tbody style=\"background: #FFF9E0; color:red\" id= \"zikuka\">";
tablehead ('<font color="red">zikuka.ru</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[url_zikuka]",$config_rss['url_zikuka']) );
tablehead ($lang_grabber['post_max_size'].' 5 Mb');


echo "</tbody>
<tbody style=\"background: #FFF9E0; color:red\" id= \"wwwpix\">";
tablehead ('<font color="red">10pix.ru</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[url_10pix]",$config_rss['url_10pix']) );
tablehead ($lang_grabber['post_max_size'].' 5 Mb');

echo "</tbody><tr>
<tbody style=\"background: #FFF9E0; color:red\" id= \"immage\">";
tablehead ('<font color="red">immage.de</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[immage]",$config_rss['immage']) );
tablehead ($lang_grabber['post_max_size'].' 5 Mb');

echo "</tbody>
<tbody style=\"background: #FFF9E0; color:red\" id= \"imageshack\">";
tablehead ('<font color="red">imageshack.us</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[imageshack]",$config_rss['imageshack']) );
tablehead ($lang_grabber['post_max_size'].' 5 Mb');

echo "</tbody>
<tbody style=\"background: #FFF9E0; color:red\" id= \"tinypic\">";
tablehead ('<font color="red">tinypic.com</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[tinypic]",$config_rss['tinypic']) );
tablehead ($lang_grabber['post_max_size'].' 5 Mb');

echo "</tbody>
<tbody style=\"background: #FFF9E0; color:red\" id= \"epikz\">";
tablehead ('<font color="red">epikz.net</font>');
showR( $lang_grabber['num_radikal'],"<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_num_radikal']."', this, event, '670px')\">[?]</a>",$lang_grabber['num_radikal_default'],makeDropDown( array ("1"=>"1","2"=>"2" ),"conf_log[url_epikz]",$config_rss['url_epikz']) );
tablehead ('�������: JPG, GIF, PNG, ��������� ��������: 10 �������� �� 2��
������������ �����: �� 5�� (����������: 5000�5000px)');

echo "</tbody><tbody style=\"background: #E8F9E6; color:green\" id= \"serv\">";
tablehead ('<font color="green">������</font>');
showRow( $lang_grabber['watermark_add_glob'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[allow-water]",$config_rss['allow-water'] ) );

echo '<tr><td colspan="2" style="padding:4px" class="option">'.$lang_grabber['place_watermark'].'<a href="#" class="hintanchor" onMouseover="showhint(\''.$lang_grabber['help_place_watermark'].'\', this, event, \'420px\')">[?]</a></td>
	</tr>';
showRo( '<span class=small><b>'.$lang_grabber['place_watermark_x'].'</b></span>','',makeDropDown( array ("right"=>$lang['opt_sys_right'],"center"=>$lang['opt_sys_center'],"left"=>$lang['opt_sys_left'] ),"conf_log[x]",$config_rss['x'] ) );
showRow( '<span class=small><b>'.$lang_grabber['place_watermark_y'].'</b></span>','',makeDropDown( array ("bottom"=>$lang_grabber['opt_below'],"center"=>$lang['opt_sys_center'],"top"=>$lang_grabber['opt_above'] ),"conf_log[y]",$config_rss['y'] ) );
showRow( $lang_grabber['padding_watermark'],$lang_grabber['help_padding_watermark'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[margin]' value=\"{$config_rss['margin']}\" size=10>");
showRow( $lang_grabber['dop_watermark'],$lang_grabber['help_dop_watermark'],makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes']),"conf_log[dop-watermark]",$config_rss['dop-watermark'] ) );
echo "<tr>
		<td colspan=\"2\" style=\"padding:4px\" class=\"option\">".$lang_grabber['site_watermark']."<a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('".$lang_grabber['help_site_watermark']."', this, event, '380px')\">[?]</a></td>
	</tr>";
showRo('<span class=small><b>light</b></span>','',"<input class=edit type=text style=\"text-align: center;\" name='conf_log[watermark_image_light]' value=\"{$config_rss['watermark_image_light']}\" size=50>");
showRow('<span class=small><b>dark</b></span>','',"<input class=edit type=text style=\"text-align: center;\" name='conf_log[watermark_image_dark]' value=\"{$config_rss['watermark_image_dark']}\" size=50>");
tablehead ($lang_grabber['post_max_size']);

	echo '
</tbody>
</td>
	</tr>
<tr>
		<td colspan="2" style="padding:4px" class="option"><b>'.$lang_grabber['align'].':</b><a href="#" class="hintanchor" onMouseover="showhint(\''.$lang_grabber['help_align'].'\', this, event, \'420px\')">[?]</a></td>
	</tr>';
showRo('<span class=small><b>'.$lang_grabber['align_short'].'</b></span>','',makeDropDown( array ("3"=>$lang['opt_sys_none'],"2"=>$lang['opt_sys_left'],"1"=>$lang['opt_sys_center'],"0"=>$lang['opt_sys_right'] ),"conf_log[image_align]",$config_rss['image_align'] ) );
showRow( '<span class=small><b>'.$lang_grabber['align_full'].'</b></span>','',makeDropDown( array ("3"=>$lang['opt_sys_none'],"2"=>$lang['opt_sys_left'],"1"=>$lang['opt_sys_center'],"0"=>$lang['opt_sys_right'] ),"conf_log[image_align_full]",$config_rss['image_align_full'] ) );
echo'
<tr>
<td style="padding:4px" class="option">
<b>������ ����������� ��� ';
echo makeDropDown( array ("0"=>$lang_grabber['no_thumb'],"1"=>$lang_grabber['thumb_short'],"2"=>$lang_grabber['thumb_full'], "3"=>$lang_grabber['thumb_shortfull']),"conf_log[create_images]",$config_rss['create_images'] );
echo'</b><br /><span class=small></span>
<td width=394 align=middle >
<input class=edit type=text style="text-align: center;" name="conf_log[maxWidth]" value="'.$config_rss['maxWidth'].'" size=10> (px)  ';
echo makeDropDown( array ("0"=>$lang['upload_t_seite_1'],"1"=>$lang['upload_t_seite_2'],"2"=>$lang['upload_t_seite_3'] ),"conf_log[upload_t_size]",$config_rss['upload_t_size'] );
echo'        </tr><tr><td background="engine/skins/images/mline.gif" height=1 colspan=2></td></tr><tr>';

showRow('�������� �������� �����������:','������� ��� ������ �� ������� ������������� ���� �����������. ��������: http://yoursite.com/ ��������, ������� ����� �� ����� � ����� ������ �����������.',"<input class=edit type=text style=\"text-align: center;\" name='conf_log[http_url]' value=\"{$config_rss['http_url']}\" size=50>");

showRow('�������� ����� �����������', '�������� ���������� ���� ����� ���� ����� ����������� �����������<br>�������� ������ ���������� ='.$_SERVER['DOCUMENT_ROOT'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[DOCUMENT_ROOT]' value=\"{$config_rss['DOCUMENT_ROOT']}\" size=50>");

tablehead ('<b>'.$lang_grabber['options_new'].'</b>');
showRow( $lang_grabber['cat_auto'],$lang_grabber['help_cat_auto'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[cat]",$config_rss['cat'] ) );
showRow( $lang_grabber['rewrite'],$lang_grabber['help_rewrite'],makeDropDown( array ("no"=>$lang['opt_sys_no'] ,"yes"=>$lang['opt_sys_yes']),"conf_log[rewrite-news]",$config_rss['rewrite-news'] ) );
showRow( $lang_grabber['moderation'],'',makeDropDown( array ("no"=>$lang['opt_sys_no'] ,"yes"=>$lang['opt_sys_yes']),"conf_log[allow-mod]",$config_rss['allow-mod'] ) );
showRow( $lang_grabber['post_index'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[allow-main]",$config_rss['allow-main'] ) );
showRow( $lang_grabber['rating'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[allow-comm]",$config_rss['allow-comm'] ) );
showRow( $lang_grabber['commentary'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[allow-rate]",$config_rss['allow-rate'] ) );

showRow($lang_grabber['interval_min'],'������������ ���-�� ���������� ����������� ��������',"<input class=edit type=text style=\"text-align: center;\" name='conf_log[rate_start]' value=\"{$config_rss['rate_start']}\" size=10>");

showRow($lang_grabber['interval_max'],'����������� ���-�� ���������� ����������� ��������',"<input class=edit type=text style=\"text-align: center;\" name='conf_log[rate_finish]' value=\"{$config_rss['rate_finish']}\" size=10>");

showRow( $lang_grabber['add_auto'],$lang_grabber['help_add_auto'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[allow-auto]",$config_rss['allow-auto'] ) );
if ($config_rss['reg_group'] != ''){$config_rss_group = explode (',',$config_rss['reg_group']);}else{$config_rss_group = 1;}
showRow( $lang_grabber['group_author_default'],$lang_grabber['help_group_author_default'],makeDropDowns( $sys_group_arr,"conf_logs",$config_rss_group ) );
tablehead ('<b>'.$lang_grabber['dop_options_new'].'</b>');
showRow( $lang_grabber['shortstory_del'],$lang_grabber['help_shortstory_del'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[clear-short]",$config_rss['clear-short'] ) );
showRow( $lang_grabber['add_pics_shortstory'],$lang_grabber['help_add_pics_shortstory'],makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes']),"conf_log[short-images]",$config_rss['short-images'] ) );
showRow( $lang_grabber['shortstory_fulstory'],$lang_grabber['help_shortstory_fulstory'],makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes']),"conf_log[short-full]",$config_rss['short-full'] ) );
showRow( $lang_grabber['del_empty_line'],$lang_grabber['help_del_empty_line'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[null]",$config_rss['null'] ) );
//showRow( $lang_grabber['text_url_active'],$lang_grabber['help_text_url_active'],makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes']),"conf_log[text-url]",$config_rss['text-url'] ) );
showRow( $lang_grabber['active_hide'],$lang_grabber['help_active_hide'],makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes']),"conf_log[hide]",$config_rss['hide'] ) );
showRow( $lang_grabber['active_leech'],$lang_grabber['help_active_leech'],makeDropDown( array ("no"=>$lang['opt_sys_no'],"yes"=>$lang['opt_sys_yes']),"conf_log[leech]",$config_rss['leech'] ) );
tablehead ('<b>'.$lang_grabber['options_data'].'</b>');
showRow( $lang_grabber['date_news'],$lang_grabber['help_date_news_glob'],makeDropDown( array ("0"=>$lang_grabber['date_flowing'],"1"=>$lang_grabber['date_casual'],"2"=>$lang_grabber['date_channel']),"conf_log[date]",$config_rss['date'] ) );
showRow($lang_grabber['interval_min'],$lang_grabber['help_interval_min'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[interval_start]' value=\"{$config_rss['interval_start']}\" size=10>");
showRow($lang_grabber['interval_max'],$lang_grabber['help_interval_min'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[interval_finish]' value=\"{$config_rss['interval_finish']}\" size=10>");
tablehead ('<b>'.$lang_grabber['vizual'].'</b>');
showRow( $lang_grabber['display_author'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_autor]",$config_rss['show_autor'] ) );
showRow( $lang_grabber['display_date'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_date]",$config_rss['show_date'] ) );
showRow( $lang_grabber['display_tag'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_tegs]",$config_rss['show_tegs'] ) );
showRow( $lang_grabber['display_bbcode'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_code]",$config_rss['show_code'] ) );
if ($config_rss['code_bb']  != ''){$config_code_bb = explode (',',$config_rss['code_bb'] );}
showRow( $lang_grabber['display_bbcode_xfields'],$lang_grabber['help_display_bbcode_xfields'],makeDropDowns( rss_xfields('1'),"code_bb",$config_code_bb ) );
showRow( $lang_grabber['display_down'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_down]",$config_rss['show_down'] ) );
showRow( $lang_grabber['display_xfields'],$lang_grabber['help_display_xfields'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_f]",$config_rss['show_f'] ) );

showRow( $lang_grabber['display_catalog_grab'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ), "conf_log[show_symbol]",$config_rss['show_symbol'] ) );
showRow( $lang_grabber['display_url_grab'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ), "conf_log[show_url]",$config_rss['show_url'] ) );
showRow( $lang_grabber['display_date_expires'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_date_expires]",$config_rss['show_date_expires'] ) );
showRow( $lang_grabber['display_meta_title_grab'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_show_metatitle]",$config_rss['show_metatitle'] ) );
showRow( $lang_grabber['display_meta_descr_grab'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_metadescr]",$config_rss['show_metadescr'] ) );
showRow( $lang_grabber['display_meta_keys_grab'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[show_keywords]",$config_rss['show_keywords'] ) );

tablehead ('<b>'.$lang_grabber['independent_options'].'</b>');
showRow($lang_grabber['news_grab'],$lang_grabber['help_news_grab'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[news_kol]' value=\"{$config_rss['news_kol']}\" size=10>");
showRow($lang_grabber['crone_glob'],$lang_grabber['help_crone_glob'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[news_limit]' value=\"{$config_rss['news_limit']}\" size=10>");
showRow( $lang_grabber['ping'],$lang_grabber['help_ping'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[allow_post]",$config_rss['allow_post'] ) );
showRow($lang_grabber['ping_lognum'],$lang_grabber['help_ping_lognum'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[ping_lognum]' value=\"{$config_rss['ping_lognum']}\" size=10>");
if(@file_exists(ENGINE_DIR .'/inc/plugins/ping/sitemap.php')) showRow( $lang_grabber['site_map'], $lang_grabber['help_site_map'],makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[sitemap]",$config_rss['sitemap'] ) );
if( @file_exists (ENGINE_DIR ."/inc/plugins/sinonims.php") ){
	showRow( $lang_grabber['sinonims'],'',makeDropDown( array ("yes"=>$lang['opt_sys_yes'],"no"=>$lang['opt_sys_no'] ),"conf_log[sinonim]",$config_rss['sinonim'] ) );
if ($config_rss['sin_dop']  != ''){$config_sin_dop = explode (',',$config_rss['sin_dop'] );}
showRow( $lang_grabber['sin_dop_xfields'],$lang_grabber['help_sin_dop_xfields'],makeDropDowns( rss_xfields('1'),"sin_dop",$config_sin_dop ) );
showRow($lang_grabber['page_sinonims'],'',makeDropDown( array (10=>10 ,20=>20,30=>30,40=>40,50=>50,100=>100),"conf_log[page_sinonims]",$config_rss['page_sinonims'] ) );

}
showRow($lang_grabber['proxy'],$lang_grabber['adress_proxy'],"<input class=edit type=text style=\"text-align: center;\" name='conf_log[proxy]' value=\"{$config_rss['proxy']}\" size=50>");


if (file_exists(ENGINE_DIR ."/inc/plugins/files/proxy.txt"))$files = '<div id="find_upload"><font color="red"></font><font color="green">'.$lang_grabber['msg_proxy_yes'].'</font> <font color="blue">'.date( "Y-m-d
H:i:s",filectime(ENGINE_DIR ."/inc/plugins/files/proxy.txt")).'</font></div>';else $files = '<div id="find_upload"><font color="red"></font><font color="green">'.$lang_grabber['msg_proxy_no'].'</font></div>';
showRo($lang_grabber['file_proxy'],$lang_grabber['help_file_proxy'],makeDropDown( array ("no"=>$lang['opt_sys_no'] ,"yes"=>$lang['opt_sys_yes']),"conf_log[proxy_file]",$config_rss['proxy_file'] ) );
	showRo( '�������� ������ ������ �������� �������������','���� <b>��</b>, �� ��� ������ ��������� �������� ���� �� ������� ������ �������� ����� ����������� �������������',makeDropDown( array ("no"=>$lang['opt_sys_no'] ,"yes"=>$lang['opt_sys_yes']) ,"conf_log[get_proxy]", $config_rss['get_proxy'] ) );
	showRow('' ,$files,'' );
	showRow( '�������� ������ ������ �������� �������','��� ������� ���������� �������������� ���������� ����� � ������ ��������<br /><br />','<input class="edit" type="button" onClick="find_upload(\'1\',\'1\'); return false;" value=" �������� ">
	' );

echo '
<tr>
		<td style="padding:4px" class="option">
		<b>'.$lang_grabber['down_file_proxy'].'</b><br /><span class=small>'.$lang_grabber['help_down_file_proxy'].'</span>
		<td width=394 align=middle >
<button id="uploadButton" class="edit">        ���������        </button>
</tr><tr><td background="engine/skins/images/mline.gif" height=1 colspan=2></td></tr>

<tr>
		<td colspan="2"><div class="hr_line"></div></td>
	</tr><tr>
 <td align="left">
<input type="submit" class="edit" value=" '.$lang_grabber['save'].' " style="width:100px;">
<input type="button" class="edit" value=" '.$lang_grabber['out'].' " onClick="document.location.href = \''.$PHP_SELF .'?mod=rss\'" />
	</tr></table>
</form>
<table width="100%">


';

echo '
<form action="?mod=rss&action=upload" enctype="multipart/form-data" name="form" method="post">
<input type="hidden" type="file" class="edit" name=uploadfile>
</form>
</table>



<script type="text/javascript">
<!--
    item = document.getElementById("img_host");

    onImgChange(item.value);
// -->
</script>

';



closetable ();
echofooter ();


return 1;
}

?>