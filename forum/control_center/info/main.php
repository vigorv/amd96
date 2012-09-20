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

$_SESSION['back_link_info'] = $_SERVER['REQUEST_URI'];

$control_center->header("Система", "Система");
$onl_location = "Система";

if (extension_loaded('mysqli'))
	$mysql_v = "MySQLi";
else
	$mysql_v = "MySQL";

$version_php = PHP_VERSION . " (" . @php_sapi_name() . ")";
$version_os = @php_uname();
$disabled_func = @ini_get('disable_functions') ? str_replace( ",", ", ", @ini_get('disable_functions') ) : "Не определено";
$modules = get_loaded_extensions();
$modules = array_combine( $modules, $modules );
sort( $modules, SORT_STRING );
$modules_all = "";
foreach ($modules as $mod)
{
	if (!$modules_all)
		$modules_all .= $mod;
	else
		$modules_all .= ", ".$mod;
}
$safemode = (@ini_get( 'safe_mode' ) == 1) ? "<font color=red>Включён</font>" : "<font color=green>Выключен</font>";
$maxfile =  @ini_get( 'upload_max_filesize' );
$maxmemory = (@ini_get( 'memory_limit' ) != '') ? @ini_get( 'memory_limit' ) : "Не определено";
$disk = formatsize(@disk_free_space( "." ));

if( strpos( strtolower( PHP_OS ), 'win' ) === 0 )
{
	$tasks = @shell_exec( "tasklist" );
	$tasks = str_replace( " ", "&nbsp;", $tasks );
}
elseif( strtolower( PHP_OS ) == 'darwin' )
{
	$tasks = @shell_exec( "top -l 1" );
	$tasks = str_replace( " ", "&nbsp;", $tasks );
}
else
{
	$tasks = @shell_exec( "top -b -n 1" );
	$tasks = str_replace( " ", "&nbsp;", $tasks );
}

$LB_v = $DB->one_select( "vid", "history_update", "", "ORDER BY vid DESC LIMIT 1" );

$LB_root = reset(explode("index.php", strtolower($_SERVER['PHP_SELF'])));

echo <<<HTML

<script type="text/javascript">

function Check_New_Version (ver)
{
    $.get("{$LB_root}check_version.php", {"ver":ver}, function(data){
       $("#new_version").html(data);
    });
    
    $("#new_version").ajaxError(function(event, request, settings){
        $("#new_version").html("Не удалось подключиться к LogicBoard.ru");
    });
    return false;
}

</script>

                   <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg">Информация о системе</div>
                    </div>
		<table class="colorTable">
                        <tr class="appLine">
                            <td align=left width="170">Версия LogicBoard:</td>
                            <td align=left>{$LB_v['vid']}; <a href="#" onclick="Check_New_Version('{$LB_v['vid']}');return false;">Проверить наличие обновлений.</a> <span id="new_version"></span></td>
                        </tr>
                        <tr class="appLine dark">
                            <td align=left width="170">MySQL версия:</td>
                            <td align=left>{$mysql_v} {$DB->mysql_version}</td>
                        </tr>
                        <tr class="appLine">
                            <td align=left width="170">PHP версия:</td>
                            <td align=left>{$version_php}</td>
                        </tr>
                        <tr class="appLine dark">
                            <td align=left width="170">Отключённые функции PHP:</td>
                            <td align=left>{$disabled_func}</td>
                        </tr>
                        <tr class="appLine">
                            <td align=left width="170">Загруженные дополнения:</td>
                            <td align=left>{$modules_all}</td>
                        </tr>
                        <tr class="appLine dark">
                            <td align=left width="170">Безопасный режим:</td>
                            <td align=left>{$safemode}</td>
                        </tr>
                        <tr class="appLine">
                            <td align=left width="170">Максимальный размер загружаемого файла:</td>
                            <td align=left>{$maxfile}</td>
                        </tr>
                        <tr class="appLine dark">
                            <td align=left width="170">Оперативной памяти:</td>
                            <td align=left>{$maxmemory}</td>
                        </tr>
                        <tr class="appLine">
                            <td align=left width="170">Размер свободного места:</td>
                            <td align=left>{$disk}</td>
                        </tr>
                        <tr class="appLine dark">
                            <td align=left width="170">ПО сервера:</td>
                            <td align=left>{$version_os}</td>
                        </tr>
		 </table>

                   <div class="clear" style="height:20px;"></div>
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Текущая информация о процессах</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
			<table width="100%" border=0><tr><td align=left align=left><pre>{$tasks}</pre></td></tr></table>
                      </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
HTML;

?>