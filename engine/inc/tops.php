<?php
/* Подключаемые файлы */
require_once (ENGINE_DIR.'/inc/tops.functions.php');
require_once (ENGINE_DIR.'/data/config_tops.php');

/**
* Сохранение настроек модуля
*/
if ($action == "dosave") {
    $find[]     = "'\r'";
    $replace[]  = "";
    $find[]     = "'\n'";
    $replace[]  = "";

	if( $member_id['user_group'] != 1 ) {
		msg( "error", $lang['opt_denied'], $lang['opt_denied'] );
	}

	$save_con = $_POST['save_con'];

	$handler = @fopen(ENGINE_DIR.'/data/config_tops.php', "wb");
	fwrite ($handler, "<?php \n\n//Tops configurations
							 \n\n\$config_tops = array(
							 \n\n'version' => \"v.1.0\",\n\n");

	foreach ( $save_con as $name => $value ) {

					$value = str_replace( "$", "&#036;", $value );
					$value = str_replace( "{", "&#123;", $value );
					$value = str_replace( "}", "&#125;", $value );
					
					$name = str_replace( "$", "&#036;", $name );
					$name = str_replace( "{", "&#123;", $name );
					$name = str_replace( "}", "&#125;", $name );
					
					fwrite( $handler, "'{$name}' => \"{$value}\",\n\n" );
			
		  }
		
	fwrite($handler, ");\n\n?>");
	fclose($handler);

	msg ("info", "Строка изменена",
		 "{$lang['opt_sysok_1']}<br /><br />
		  <a href=\"{$PHP_SELF}?mod=tops\">{$lang['db_prev']}</a>");
}

/**
* Вывод header
*/
echoheader("tops", "Админпанель модуля tops");

/**
* Вывод блока настроек
*/
opentable();
tableheader('Настройка модуля');
echo<<<HTML
<form action="" method="POST">
<table width="100%">
  <tr>
    <td class="option" style="padding:4px;">
      <b> АФИША (самая большая фигня с картинками): </b><br />
      <span class="small">Список ID новостей, которые должны присутствовать в афише.
	  <br />
	  <font color="red">5 групп цифр (ID'ишники), через запятую.</font> Последняя запятая не нужна.
	  </span>
    <td align="middle" width="400">
      <input class="edit" style="text-align:center" size="40" value="{$config_tops['top0slider']}" name="save_con[top0slider]"></td>
  </tr>
  
  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>
  
  <tr>
    <td class="option" style="padding:4px;">
       <b> ТОП Кино: </b><br />
       <span class="small">Список ID новостей, которые должны присутствовать в топе кино.
	   <br />
	   <font color="red">4 группы цифр (ID'ишники), через запятую.</font> Последняя запятая не нужна.
	   </span>

    <td align="middle" width="400">
       <input class="edit" style="text-align:center" size="40" value="{$config_tops['top1kino']}" name="save_con[top1kino]"></td>
  </tr>
  
  <tr>
    <td class="option" style="padding:4px;">
       <b> ТОП Игр: </b><br />
       <span class="small">Список ID новостей, которые должны присутствовать в топе игр.
		<br />
		<font color="red">4 группы цифр (ID'ишники), через запятую.</font> Последняя запятая не нужна.
	  </span>
    <td align="middle" width="400">
       <input class="edit" style="text-align:center" size="40" value="{$config_tops['top2games']}" name="save_con[top2games]"></td>
  </tr>
  
  <tr><td background="engine/skins/images/mline.gif" height="1" colspan="2"></td></tr>
  
  <tr>
    <td class="option" style="padding-bottom:10px; padding-top:10px; padding-left:10px;" colspan="2">
      <input type="hidden" name="mod" value="tops"/>
	  <input class="buttons" type="hidden" name="action" value="dosave" />
      <input class="btn btn-success" type="submit" name="do" value=" Сохранить " /> (очистите CACHE сайта для проверки результата)</td>
  </tr>
  
</table>
</form>
HTML;
closetable();


/**
* Вывод footer
*/
echo <<<HTML
Файлы модуля:<br />
engine/inc/tops.php<br />
engine/inc/tops.functions.php<br />
<br />
Хранение настроек:<br />
engine/data/config_tops.php<br />
Файл подключен в engine/init.php<br />
Замена переменных для шаблона в index.php<br />
<br />
Проверки на правильный ввод нет.<br />
HTML;
echofooter();
?>