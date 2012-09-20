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
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if(isset($_GET['file']))
{
	$file = $_GET['file'];
	$onl_location = "Кеш: ".$file ;

echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$LB_charset}" />
<title>{$onl_location}</title>
<link type="text/css" media="all" rel="StyleSheet" href="{$cache_config['general_site']['conf_value']}control_center/template/style.css" />
</head>
<body>
<div style="padding:5px;">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">{$onl_location}</div>
                    </div>
                    
                    <div class="borderL">
                        <div class="borderR">
                            <table>
                                <tr>
                                    <td>
					<pre>
HTML;

$folder = LB_MAIN."/cache";

function cache_list($folder, $search)
{
	$dir = opendir( $folder );

	while ( $file = readdir( $dir ) )
	{
		if( $file != "." AND $file != ".." AND $file != ".htaccess")
		{
			if( @is_file( $folder . '/' . $file ))
			{
				$file_link = str_replace(".".end(explode(".", $file)),"",$file);

				if ($file_link == $search)
				{
					$content = @file_get_contents( $folder . '/' . $file );
					if (substr($content, 0, 7) == "1|TYPE|")
						$content = unserialize(substr($content, 7));
					else
						$content = substr($content, 7);
                        
					print_r ($content);
					return;
				}

			}
			elseif( @is_dir( $folder . '/' . $file ) )
			{
				cache_list( $folder . '/' . $file, $search );
			}
		}
	}
	closedir( $dir );
}

cache_list($folder, $file);

echo <<<HTML

					</pre>
				</td></tr>
			</table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</body>
</html>
HTML;


}
else
{

echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$onl_location}</title>
<link type="text/css" media="all" rel="StyleSheet" href="{$cache_config['general_site']['conf_value']}control_center/template/style.css" />
</head>
<body>
<div style="padding:5px;">

HTML;

	$control_center->errors = array ();
	$control_center->errors[] = "Вы не выбрали файл кеша для просмотра.";
	$control_center->errors_title = "Страница не найдена.";
	$control_center->message();

echo <<<HTML

</div>
</body>
</html>
HTML;
}
?>