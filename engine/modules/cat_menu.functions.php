<?php
/*
=====================================================
 Menu
 Файл: cat_menu.functions.php
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	define( 'DATALIFEENGINE', true );
}
if( ! defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', substr( dirname( __FILE__ ), 0, - 11 ) );
}
if( ! defined( 'ENGINE_DIR' ) ) {
	define( 'ENGINE_DIR', ROOT_DIR . '/engine' );
}
//tim tim
require_once ENGINE_DIR . '/modules/functions.php';
///tim tim

/* Функция сохранения кеш 
(взята с API DLE, API не используется ввиду глючности множественных подключений модов с API) */
function CatMenuSave($fname, $vars) {
	$cache_dir = ENGINE_DIR."/cache/";
	$filename = $fname.".tmp";
	$f = @fopen($cache_dir.$filename, "w+");
	@chmod('0777', $cache_dir.$filename);
	if (is_array($vars)) $vars = serialize($vars);
	@fwrite($f, $vars);
	@fclose($f);
	return $vars;
}

/* Функция загрузки с кеша 
(взята с API DLE, API не используется ввиду глючности множественных подключений модов с API) */
function CatMenuLoad($fname, $timeout=300, $type = 'text') {
	$cache_dir = ENGINE_DIR."/cache/";
	$filename = $fname.".tmp";
	if (!file_exists($cache_dir.$filename)) return false;
	if ((filemtime($cache_dir.$filename)) < (time()-$timeout)) return false;
	if ($type=='text'){
		return file_get_contents($cache_dir.$filename);
	} else {
		return unserialize(file_get_contents($cache_dir.$filename));
	}
}	

/* Функция построения адреса подкатегории для ЧПУ
(аналогична стандартной функции DLE) */
function CatMenuUrl( $id, $all_info ) {
	if ( ! $id ) return;
	$parent_id = $all_info[$id]['parentid'];
	$url = $all_info[$id]['alt_name'];
	while ( $parent_id ) {
		$url = $all_info[$parent_id]['alt_name'] . "/" . $url;
		$parent_id = $all_info[$parent_id]['parentid'];
		if ( $all_info[$parent_id]['parentid'] == $all_info[$parent_id]['id'] ) break;
	}
	return $url;
}

/* Функция подсчета общего количества новостей и комментариев */
function CatMenuStats( $all_info = array() ) {
	$allstats['post_new'] = 0;
	$allstats['post_all'] = 0;
	$allstats['comm_new'] = 0;
	$allstats['comm_all'] = 0;
	foreach ( $all_info as $cats ) {
		$allstats['post_new'] += $cats['new'];
		$allstats['post_all'] += $cats['all'];
		$allstats['comm_new'] += $cats['newc'];
		$allstats['comm_all'] += $cats['allc'];
	}
	$allstats['post_all'] -= $allstats['post_new'];
	$allstats['comm_all'] -= $allstats['comm_new'];
	return $allstats;
}


/* Функция подсчета сумм новостей и комментариев из подкатегорий  */
function CatMenuNews( $current = 0, $all_info = array(), $start = 0, $news = array() ) {
	global $config; // конфигурация движка
	static $news;
	
	// При первом вызове берем за основу новости самой категории
	if ( $current == $start ) {
		$news['new'] = $all_info[$current]['new'];
		$news['all'] = $all_info[$current]['all'];
		$news['newc'] = $all_info[$current]['newc'];
		$news['allc'] = $all_info[$current]['allc'];
	}
	
	// Если стоит настройка НЕ выводить новости опубликованные в субкатегориях, то не выводим их и в меню - закомментируйте строчку ниже, если хотите, чтобы сумма новостей в ветке категорий считалась независимо от настроек движка
	if ( $config['show_sub_cats'] == 0 ) return $news;
	
	// если есть категории, продолжаем...
	if ( count( $all_info ) > 0 ) {
		
		// ищем, есть ли подкатегории у текущей категории
		foreach ( $all_info as $cats ) {
			// если у текущей категории есть потомки
			if ( $current == $cats['parentid'] ) $children[] = $cats['id'];
		}
		
		$subcount = count( $children );
		
		if ( $current != $start ) {
			$news['new'] += $all_info[$current]['new'];
			$news['all'] += $all_info[$current]['all'];
			$news['newc'] += $all_info[$current]['newc'];
			$news['allc'] += $all_info[$current]['allc'];
		}
		
		// если у текущей категории есть подкатегории
		if ( $subcount > 0 ) {
			// потом собираем блок подкатегорий
			foreach ( $children as $id ) {
				CatMenuNews( $id, $all_info, $start, $news );
			}
		}
	
	}
	
	// если это последний вызов функции
	if ( $current == $start ) {
		return $news;
	}
	
}


/* Функция построения дерева меню */
function CatMenu( $current = 0, $all_info = array(), $new_days = 0, $com_days = 0, $iconimg = 0, $cute = 0, $hiddens = array() ) {
	global $config, $member_id; // конфигурация движка
	static $build; // переменная сборки

	// если есть категории, продолжаем...
	if ( count( $all_info ) > 0 ) {
		
			
		if( !in_array($current, $hiddens) ) {
			// ищем, есть ли подкатегории у текущей категории
			foreach ( $all_info as $cats ) {
				// если у текущей категории есть потомки
				if ( $current == $cats['parentid'] ) {
					// собираем их идентификаторы
					$children[] = $cats['id'];
				}
			}
		}
		
		$subcount = count( $children );
		
		// проверка, это первый вызов
		if ( $current != 0 ) {
		
			if( !in_array($current, $hiddens) ) {
			
			// создаем стрелку если необходимо
			if ( $subcount > 0 ) {$arrow = " class=\"subcat\"";}
			else { $arrow = ''; }
			
			//tim tim colored <<<TOP>>>
			$colored = "";
			$stl = "cat_menu_li";
			
			if ($all_info[$current]['id'] == 1) {
			$colored = "class=\"". $stl ."\"";
			}
				if ($all_info[$current]['parentid'] == 1) { //Основная
				$colored = "class=\"". $stl ."_li\"";
				}

			if ($all_info[$current]['id'] == 2) {
			$colored = "class=\"". $stl ." akva\"";
			}
				if ($all_info[$current]['parentid'] == 2) { //Фильмы
				$colored = "class=\"". $stl ."_li akva\"";
				}

			if ($all_info[$current]['id'] == 88) {
			$colored = "class=\"". $stl ." green\"";
			}
				if ($all_info[$current]['parentid'] == 88) { //HD фильмы online
				$colored = "class=\"". $stl ."_li green\"";
				}

			if ($all_info[$current]['id'] == 82) {
			$colored = "class=\"". $stl ." fiolet\"";
			}
				if ($all_info[$current]['parentid'] == 82) { //Сериалы
				$colored = "class=\"". $stl ."_li fiolet\"";
				}

			if ($all_info[$current]['id'] == 85) {
			$colored = "class=\"". $stl ." oran\"";
			}
				if ($all_info[$current]['parentid'] == 85) { //Телевидение
				$colored = "class=\"". $stl ."_li oran\"";
				}

			if ($all_info[$current]['id'] == 3) {
			$colored = "class=\"". $stl ." blue\"";
			}
				if ($all_info[$current]['parentid'] == 3) { //Музыка
				$colored = "class=\"". $stl ."_li blue\"";
				}

			if ($all_info[$current]['id'] == 4) {
			$colored = "class=\"". $stl ." pink\"";
			}
				if ($all_info[$current]['parentid'] == 4) { //Программы
				$colored = "class=\"". $stl ."_li pink\"";
				}

			if ($all_info[$current]['id'] == 5) {
			$colored = "class=\"". $stl ." salat\"";
			}
				if ($all_info[$current]['parentid'] == 5) { //Игры
				$colored = "class=\"". $stl ."_li salat\"";
				}

			if ($all_info[$current]['id'] == 60) {
			$colored = "class=\"". $stl ." salat\"";
			}
				if ($all_info[$current]['parentid'] == 60) { //HDTV
				$colored = "class=\"". $stl ."_li salat\"";
				}

			if ($all_info[$current]['id'] == 90) {
			$colored = "class=\"". $stl ." ser\"";
			}
				if ($all_info[$current]['parentid'] == 90) { //3D Stereo
				$colored = "class=\"". $stl ."_li ser\"";
				}
			///tim tim colored
			
			// создаем описание если доступно и обрезаем имя, если требуется
			$name = stripslashes( $all_info[$current]['name'] );
			//timtim
			$name = str_replace ("&","and",$name);
			///timtim
			
			//tim tim
			if ($all_info[$current]['id'] == 88) {
			$name = "HD online";
			}			
			if ($all_info[$current]['id'] == 85) {
			$name = "ТВ";
			$name = iconv("UTF-8", "windows-1251", $name);
			}			
			/// tim tim
			
			if ( $all_info[$current]['descr'] ) {
				$descr = " title=\"" . strip_tags( stripslashes( $all_info[$current]['descr'] ) ) . "\"";
				//timtim
				$descr = str_replace ("&","and",$descr);
				///timtim
			}
			else $descr = '';
			if ( $cute > 0 and $cute < strlen($name) ) { 
				$name = substr($name, 0 , $cute-3) . "...";
			}
		
			// создаем смену иконок если необходимо
			if ( $iconimg == 1 ) {
				$caticon = "<img src=\""; 
				$parent = $all_info[$current]['parentid'];
				// если есть иконка...
				if ( $all_info[$current]['icon'] ) {$caticon .= $all_info[$current]['icon'];}
				// если нет иконки, берем от родительской
				elseif ( $all_info[$parent]['icon'] ) {$caticon .= $all_info[$parent]['icon'];}
				// а если нет и родительской иконки - ставим по умолчанию
				else {$caticon .= "{THEME}/dleimages/no-icon.gif";}
				$caticon .= "\" border=\"0\" alt=\"\" align=\"middle\" />";
			}
			elseif ( $iconimg == 2 and $all_info[$current]['parentid'] == 0 ) {
				$caticon = "<img src=\""; 
				// если есть иконка...
				if ( $all_info[$current]['icon'] ) {$caticon .= $all_info[$current]['icon'];}
				// если нет иконки, берем от родительской
				else {$caticon .= "{THEME}/dleimages/no-icon.gif";}
				$caticon .= "\" border=\"0\" alt=\"\" align=\"middle\" />";
			}
			elseif ( $iconimg == 3 and $all_info[$current]['parentid'] == 0 and $all_info[$current]['icon'] ) {
				$caticon = "<img src=\"" . $all_info[$current]['icon'] . "\" border=\"0\" alt=\"\" align=\"middle\" />";
			}
			else { $caticon = ''; }
		
			$newmarker = '';
			$alls_info = '';
			
			// создаем информацию о комментариях в категории, если необходимо
			if ( $com_days > 0 ) {
				// получаем инфу о количестве новостей в категории и входящих в нее подкатегориях
				$count = CatMenuNews( $current, $all_info, $current );
				$newc = $count['newc'];
				$allc = $count['allc'];
				if ($newc > 0) {
					$newmarker = " class=\"newc\"";
					$newc = "+".$newc;
					$allc -= $newc;
				}
				else {
					$newmarker = '';
					$newc = '';
				}
				// формируем показ информации о комментариях
				$comm_info = $allc . "<span class=\"newc\">" . $newc . "</span>";
			} else $comm_info = false;
			
			// создаем информацию о новостях категории, если необходимо
			if ( $new_days > 0 ) {
				// получаем инфу о количестве новостей в категории и входящих в нее подкатегориях
				$count = CatMenuNews( $current, $all_info, $current );
				$new = $count['new'];
				$all = $count['all'];
				// маркер категорий с новыми новостями
				if ($new > 0) {
					$newmarker = " class=\"new\"";
					$new = "+".$new;
					$all -= $new;
				}
				else {
					if (!$newmarker) $newmarker = '';
					$new = '';
				}
				// формируем показ информации о новостях
				$post_info = $all . "<span class=\"new\">" . $new . "</span>";
			} else $post_info = false;
			
			// формируем итоговый показ информации
			if ( $comm_info or $post_info ) $alls_info .= '<div class="post_info">';
			if ( $post_info ) $alls_info .= $post_info;
			if ( $comm_info ) $alls_info .= '&nbsp;(' . $comm_info;
			if ( $comm_info ) $alls_info .= ')';
			if ( $comm_info or $post_info ) $alls_info .= '</div>';
			
			// создаем линк
			//tim tim
			//if ( $config['allow_alt_url'] == "yes" ) $build .= "<li" . $newmarker . "><a href=\"" . $config['http_home_url'] . CatMenuUrl( $current, $all_info  ) . "/\" rel=\"index section\"" . $descr . $arrow . ">" . $caticon . $alls_info . "<strong>" . $name . "</strong></a>";
			if ( $config['allow_alt_url'] == "yes" ) $build .= "<li" . $newmarker . " " . $colored . "><a href=\"" . $config['http_home_url'] . get_url( $current  ) . "/\" rel=\"index section\"" . $descr . $arrow . ">" . $caticon . $alls_info . "" . $name . "</a>";
			///tim tim
			
			else $build .= "<li" . $newmarker . "><a href=\"{$config['http_home_url']}index.php?do=cat&category=" . $all_info[$current]['alt_name'] . "\" rel=\"index section\"" . $descr . $arrow . ">" . $caticon . $alls_info . "<strong>" . $name . "</strong></a>";
		
			}
		
		} else {
			// если это первый вызов - создаем меню
			$build = '';
		}
		
		// если у текущей категории есть подкатегории
		if ( $subcount > 0 ) {
			// то создаем ветку подкатегорий
			if ( $current != 0 ) $build .= "<ul>";
			// потом собираем блок подкатегорий
			for ( $i = 0; $i <= $subcount; $i++ ) {
				// если подкатегории завершились, закрываем блок и  пункт списка
				if ( $i == $subcount ) {
					$build .= "</ul></li>"; 
				}
				// а если нет - продолжаем строить
				else {
					CatMenu( $children[$i], $all_info, $new_days, $com_days, $iconimg, $cute, $hiddens );
				}
			}
		}
		// если нет подкатегорий, закрываем пункт списка
		else {
			$build .= "</li>";
		}
	}
	else {echo 'No categories';}
	
	
	// если это последний вызов функции
	if ( $current == 0 ) {
		// завершаем дерево категорий
		$build = str_replace ("</li></ul></li></li>","</li></ul></li>",$build);
		$build = str_replace ("</a></li></ul></li></ul></li>","</a></li></ul></li>",$build);
		//$build = str_replace ("</a></li></ul></li></ul></ul>","</a></li></ul></li></ul>",$build);
		//$build = str_replace ("</li></ul></li></li></ul></li>","</li></ul></li>",$build);
		return $build;
	}
	
}

/* Функция сборки рабочего массива */
function CatMenuInit ($post_list=false, $new_days = 0, $comm_list=false, $com_days=0) {
	global $cat_info;
	
	// формируем свой массив категорий без лишних данных
	foreach ( $cat_info as $key => $cats ) {
		
		// все необходимые нам сведения
		$all_info[$key]['id'] = $cats['id'];
		$all_info[$key]['parentid'] = $cats['parentid'];
		$all_info[$key]['posi'] = $cats['posi'];
		$all_info[$key]['name'] = $cats['name'];
		$all_info[$key]['alt_name'] = $cats['alt_name'];
		$all_info[$key]['icon'] = $cats['icon'];
		$all_info[$key]['descr'] = $cats['descr'];
		
		// если нужно кол-во новостей - включаем в обработку
		if ($new_days > 0 and $post_list != false) {
			$new_posts = 0;
			$all_posts = 0;
			$new_comms = 0;
			$all_comms = 0;
			foreach ( $post_list as $news ) {
				// если новости есть для данной категории
				$news_category = explode( ',', $news['category'] );
				if ( in_array( $cats['id'], $news_category ) ) {
					
					$all_posts++; // увеличиваем счетчик всех новостей самой категории					
					// создаем метку устаревания новости
					$old = strtotime($news['date']) - time() + 86400 * $new_days;
					// если новость не устарела увеличиваем счетчик новых новостей всех новостей самой категории	
					if ( $old > 0 ) $new_posts++;
					
					// если нужно количество комментариев - включаем их в обработку
					if ($com_days > 0 and $comm_list != false) {
						$newc = 0;
						$allc = 0;
						foreach ( $comm_list as $comms ) {
							if ( $comms['post_id'] == $news['id'] ) {
								$allc++;
								$oldc = strtotime($comms['date']) - time() + 86400 * $com_days;
								if ( $oldc > 0 ) $newc++;
							}
						}
						$new_comms += $newc;
						$all_comms += $allc;
					}
					
				}
			}
			// сохраняем данные для категории
			$all_info[$key]['new'] = $new_posts;
			$all_info[$key]['all'] = $all_posts;
			if ($com_days > 0 and $comm_list != false) {
				$all_info[$key]['newc'] = $new_comms;
				$all_info[$key]['allc'] = $all_comms;
			}
		}
		
	}
	
	return $all_info;

}

?>