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
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$_SESSION['back_link'] = $_SERVER['REQUEST_URI'];

$control_center->header("Статистика", "Статистика");
$onl_location = "Статистика";

echo <<<HTML
		<table width="100%" border=0>
		<tr><td style="padding-right:5px;" align=left width="50%" valign=top>
HTML;

require LB_CONTROL_CENTER . '/main/statistic.php';


echo <<<HTML
</td><td style="padding-left:5px;" align=left width="50%" valign=top>
HTML;

require LB_CONTROL_CENTER . '/main/fastsearch.php';


echo <<<HTML
		</td></tr>
		</table>
HTML;

echo <<<HTML
<div class="clear" style="height:20px;"></div>

HTML;

require LB_CONTROL_CENTER . '/main/notebook.php';


echo <<<HTML
		  <div class="clear" style="height:20px;"></div>
		
                    <div class="headerBlue">
                        <div class="headerBlueL"></div>
                        <div class="headerBlueR"></div>
                        <div class="headerBlueBg"><a href="{$redirect_url}?do=logs&op=login" title="Перейти к просмотру журнала логов авторизации в центре управления.">Авторизации в ЦУ</a></div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Пользователь</h6></td>
				<td align=center><h6>Статус</h6></td>
				<td align=center><h6>Информация</h6></td>
                        </tr>

HTML;

require LB_CONTROL_CENTER . '/main/logs_cc.php';


echo <<<HTML
		</table>
		  <div class="clear" style="height:20px;"></div>
		
                    <div class="headerRed">
                        <div class="headerRedArr"><div></div></div>
                        <div class="headerRedL"></div>
                        <div class="headerRedR"></div>
                        <div class="headerRedBg">Текущая активность в ЦУ</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
HTML;

require LB_CONTROL_CENTER . '/main/session.php';

echo <<<HTML
			</div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
HTML;

$control_center->footer();

?>