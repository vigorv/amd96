<?php
/*
=====================================================
 Модуль: Category Menu
 Назначение: формирование меню категорий
=====================================================
*/

if(!defined('DATALIFEENGINE')) die("Hacking attempt!");

$catmenu = dle_cache("catmenu", 1, true);
if(!$catmenu) {
	$cat_allows = false;
	if($user_group[$member_id['user_group']]['allow_cats']=="all") $cat_allows = true;
	else $cat_allow = explode(",", $user_group[$member_id['user_group']]['allow_cats']);

	#****** формирование нужных массивов ******#
	foreach($cat_info as $k => $v) {
		if($cat_allows || in_array($v['id'], $cat_allow)) {
			$cat_all[$k]['id'] = $v['id'];
			$cat_all[$k]['name'] = $v['name'];
			$cat_all[$k]['alt_name'] = $v['alt_name'];
			($v['parentid']==0) ? $cat_g[] = $v['id'] : $cat_p[$v['parentid']][] = $v['id'];
		}
	}
	#****** формирование количества новостей ******#
	$sql = $db->query("SELECT id, date, category FROM ".PREFIX."_post WHERE approve='1'");
	while($row = $db->get_row($sql)) {
		$cat_news_arr = explode(",", $row['category']);
		foreach($cat_news_arr as $x) {
			$cat_all[$x]['news_num']++;
			if(strtotime($row['date'])-time()+86400 > 0) $cat_all[$x]['news_new']++;
		}
	}

	#****** формирование меню ******#
	foreach($cat_g as $v) {
		#****** Формирование склонений ******#
		$cat_name_end = substr($cat_all[$v]['name'], -1);
		if($cat_name_end == "е") $cat_end = "Всё";
		elseif($cat_name_end == "а" || $cat_name_end == "ь") $cat_end = "Вся";
		else $cat_end = "Все";

		#****** Формирование подкатегорий ******#
		$category = array();
		#$news_new = $news_all = 0;
		$news_new = $cat_all[$v]['news_new'];
        $news_all = $cat_all[$v]['news_num'];
		foreach($cat_p[$v] as $z) {
			#****** Новые новости за сутки ******#
			$news_new += $cat_all[$z]['news_new'];
			#****** Все новости категории ******#
			$news_all += $cat_all[$z]['news_num'];

			if(!$cat_all[$z]['news_num']) $cat_all[$z]['news_num']=0;
			if($cat_all[$z]['news_new']) $cat_all[$z]['news_new']=" / <b style=\"color:red;\">+".$cat_all[$z]['news_new']."</b>";

			$category[] = "<div class=\"listCategory\"><a href=\"/{$cat_all[$v]['alt_name']}/{$cat_all[$z]['alt_name']}/\">{$cat_all[$z]['name']}</a> ({$cat_all[$z]['news_num']}{$cat_all[$z]['news_new']})</div>";
		}

		$news_new = ($news_new > 0) ? " <font color=\"#ddd\">(+".$news_new.")</font>" : "";

		#****** Формирование заголовка ******#
		$catmenu .= "<h3><a href=\"#\" style=\"padding-top: 5px; padding-bottom: 5px;\">{$cat_all[$v]['name']}{$news_new}</a></h3><div style=\"padding:5px 10px;\">";
		#****** Ссылка на главную категорию и подкатегории ******#
		$catmenu .= "<div class=\"listCategory\"><a href=\"/{$cat_all[$v]['alt_name']}/\"><b>{$cat_end} {$cat_all[$v]['name']}</b></a> ({$news_all})</div>".implode("",$category)."</div>";
	}

	create_cache("catmenu", $catmenu, 1, true);
	unset($cat_all);
	unset($cat_news_arr);
	unset($cat_g);
	unset($cat_p);
	unset($category);
}

echo $catmenu;
?>