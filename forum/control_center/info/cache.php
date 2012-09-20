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

$link_speddbar = "<a href=\"".$redirect_url."?do=system\">Система</a>|Список файлов кеша";
$control_center->header("Система", $link_speddbar);
$onl_location = "Система &raquo; Список файлов кеша";

if (isset($_GET['folder']))
{
	if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}
    
    if(control_center_admins($member_cca['system']['cachedel']))
    {
        $del_file_link = explode('/', str_replace("||", "/", $_GET['folder']));
        $del_file_link_name = array_pop($del_file_link);
        $del_file_link_folder = implode("/", $del_file_link);

        $cache->clear($del_file_link_folder, $del_file_link_name);
        $info = "<font color=red>Удаление</font> файла кеша: ".$del_file_link_folder."/".$del_file_link_name;
        $info = $DB->addslashes($info);
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    }
    
	header( "Location: ".$redirect_url."?do=system&op=cache" );
    exit();
}

if (isset($_GET['del_all']))
{
	if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	{
		exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
	}
    
    $cache->clear();
    $cache->clear("statistics", "stats_users");
    $cache->clear("template");
    $cache->clear("minify");
    $cache->clear("dle_modules");
    
    header( "Location: ".$redirect_url."?do=system&op=cache" );
    exit();
}

$folder = LB_MAIN."/cache";

function cache_list($folder)
{
	global $cache_config, $redirect_url, $secret_key;

	$dir = opendir( $folder );

	$ii = 0;

	while ( $file = readdir( $dir ) )
	{
		$ii ++;
		if ($ii%2)
			$class = "appLine dark";
		else
			$class = "appLine";

		if( $file != "." AND $file != ".." AND $file != ".htaccess")
		{
			if( @is_file( $folder . '/' . $file ) )
			{
				$size = formatsize(filesize( $folder . '/' . $file ));
				$file_link = str_replace(".".end(explode(".", $file)),"",$file);

				$file_time = formatdate(filemtime($folder . '/' . $file));

				$file2 = $file;

				if ($folder != LB_MAIN."/cache")
				{
					$file = end(explode('/', $folder))."/".$file;

					$file_dellink = str_replace (LB_MAIN."/cache/", "", $folder);
				}
				else
					$file_dellink = str_replace (LB_MAIN."/cache", "", $folder);

				$file_dellink = str_replace ("/", "||", $file_dellink."/".$file2);
                
                if ($file2 == "online_max.php")
                    $del_link = "javascript:confirmDelete('".$redirect_url."?do=system&op=cache&folder=".$file_dellink."&secret_key=".$secret_key."', 'Внимание! Если вы удалите этот файл, то вы обнулите рекорд посещаемости.')";
                else
                    $del_link = $redirect_url."?do=system&op=cache&folder=".$file_dellink."&secret_key=".$secret_key;

echo <<<HTML

                        <tr class="{$class}">
				<td align=left><font class="blueHeader"><a href="#" onclick="window.open('{$redirect_url}?do=infopage&op=cache&file={$file_link}','Данные авторизации','width=700,height=700,toolbar=1,location=0,scrollbars=1')" title="Просмотреть содержимое данного файла кеша.">{$file}</a></font></td>
				<td align=center>{$size}</td>
				<td align=right>{$file_time}</td>
				<td align=right><a href="{$del_link}" title="Очистить данный файл кеша."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;
			}
			elseif( @is_dir( $folder . '/' . $file ) )
			{
				cache_list( $folder . '/' . $file );
			}
		}
	}
	closedir( $dir );
}


echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Файлы кеша</div>
                    </div>
		<table class="colorTable">

                        <tr>
				<td align=left><h6>Файл</h6></td>
				<td align=center><h6>Размер</h6></td>
				<td align=right><h6>Дата создания</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>

HTML;

cache_list($folder);

echo <<<HTML

                <tr><td align=left colspan=4>
                        <br><font class="smalltext">Подробную информацию о файлах кеша Вы можете найти на <a href="http://logicboard.ru/" target="blank">официальном сайте движка форума</a>.</font>
                </td></tr>
        </table>
        <div class="clear" style="height:10px;"></div>
		<table>
        <tr><td align=right style="padding-right:10px;">
        <a href="{$redirect_url}?do=system&op=cache&del_all=1&secret_key={$secret_key}" title="Удалить все файлы кеша."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить все файлы кеша." /></a>
        </td></tr></table>
HTML;

?>