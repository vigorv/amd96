<?php
//////////
/// FAQ///
//////////
if (file_exists(ROOT_DIR.'/install.php')) { die("Внимание! Вы не удалили install.php файл который находится в корне сайта. Как же Вы забыли удалить его после установки модуля? :("); }

if(!defined('DATALIFEENGINE')) {
	die("Hacking attempt!");
}
if($config['version_id'] > 6.2 ){

DEFINE('CLASSES', '/classes' );
}
else
{
DEFINE('CLASSES', '/inc' );
}
/// Делаем спойлер как у ДЛЕ
//require_once ('engine/classes/parse.class.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/engine/classes/parse.class.php');

$parse = new ParseFilter();
$parse->safe_mode = true;

require_once(ENGINE_DIR.CLASSES.'/mysql.php');


$showfaq = $db->query("SELECT * FROM " . PREFIX . "_faq ORDER BY id");
$faq_main = "";
$faq_speed = "";
$faq_hdtv = "";
$faq_misc = "";


while($row = $db->get_row($showfaq)) {
	switch ($row['prior'])
		{
		case "main":
		$id_spoiler = md5(microtime());
		$id_spoiler[0] = "a";
		$faq_main .= '<li><div class="title_spoiler"><img id="image-'.$id_spoiler.'" style="vertical-align: middle;border: none; margin-top: -15px;" alt="" src="/templates/rum/dleimages/spoiler-plus.gif"/><a href="javascript:ShowOrHide(\''.$id_spoiler.'\')"><!--spoiler_title-->Вопрос: '. $parse->BB_Parse($row['question']).'<!--spoiler_title_end--></a></div><div id="'.$id_spoiler.'" class="text_spoiler" style="display: none"><!--spoiler_text-->Ответ: '. $parse->BB_Parse($row['answer']).'<!--spoiler_text_end--></div><!--/dle_spoiler--></li>';
		#$faq_main .= '<li><!--dle_spoiler--><div class="title_spoiler"><img id="image-'.$id_spoiler.'" style="vertical-align: middle;border: none;" alt="" src="/templates/rum/dleimages/spoiler-plus.gif"/><a href="javascript:ShowOrHide(\''.$id_spoiler.'\')"><!--spoiler_title-->Вопрос: '. $parse->BB_Parse($row['question']).'<!--spoiler_title_end--></a></div><div id="'.$id_spoiler.'" class="text_spoiler" style="display:none;"><!--faq_spoiler_text-->Ответ: '. $parse->BB_Parse($row['answer']).'<!--spoiler_text_end--></div><!--/dle_spoiler--></li>';
		break;
		case "speed":
		$id_spoiler = md5(microtime());
		$id_spoiler[0] = "a";
		$faq_speed .= '<li><div class="title_spoiler"><img id="image-'.$id_spoiler.'" style="vertical-align: middle;border: none; margin-top: -15px;" alt="" src="/templates/rum/dleimages/spoiler-plus.gif"/><a href="javascript:ShowOrHide(\''.$id_spoiler.'\')"><!--spoiler_title-->Вопрос: '. $parse->BB_Parse($row['question']).'<!--spoiler_title_end--></a></div><div id="'.$id_spoiler.'" class="text_spoiler" style="display: none"><!--spoiler_text-->Ответ: '. $parse->BB_Parse($row['answer']).'<!--spoiler_text_end--></div><!--/dle_spoiler--></li>';
		break;
		case "hdtv":
		$id_spoiler = md5(microtime());
		$id_spoiler[0] = "a";
		$faq_hdtv .= '<li><div class="title_spoiler"><img id="image-'.$id_spoiler.'" style="vertical-align: middle;border: none; margin-top: -15px;" alt="" src="/templates/rum/dleimages/spoiler-plus.gif"/><a href="javascript:ShowOrHide(\''.$id_spoiler.'\')"><!--spoiler_title-->Вопрос: '. $parse->BB_Parse($row['question']).'<!--spoiler_title_end--></a></div><div id="'.$id_spoiler.'" class="text_spoiler" style="display: none"><!--spoiler_text-->Ответ: '. $parse->BB_Parse($row['answer']).'<!--spoiler_text_end--></div><!--/dle_spoiler--></li>';
		break;
		case "misc":
		$id_spoiler = md5(microtime());
		$id_spoiler[0] = "a";
		$faq_misc .= '<li><div class="title_spoiler"><img id="image-'.$id_spoiler.'" style="vertical-align: middle;border: none; margin-top: -15px;" alt="" src="/templates/rum/dleimages/spoiler-plus.gif"/><a href="javascript:ShowOrHide(\''.$id_spoiler.'\')"><!--spoiler_title-->Вопрос: '. $parse->BB_Parse($row['question']).'<!--spoiler_title_end--></a></div><div id="'.$id_spoiler.'" class="text_spoiler" style="display: none"><!--spoiler_text-->Ответ: '. $parse->BB_Parse($row['answer']).'<!--spoiler_text_end--></div><!--/dle_spoiler--></li>';
		break;
		}
}
$tpl->load_template('faq.tpl');
$tpl->set('{faq_main}', !empty($faq_main) ? $faq_main : "");
$tpl->set('{faq_speed}', !empty($faq_speed) ? $faq_speed : "");
$tpl->set('{faq_hdtv}', !empty($faq_hdtv) ? $faq_hdtv : "");
$tpl->set('{faq_misc}', !empty($faq_misc) ? $faq_misc : "");
$tpl->set('{description}', 'Ответы на частозадаваемые вопросы (F.A.Q.)');
$tpl->set('{pages}', '');
$tpl->compile('content');
$tpl->clear();

?>