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

$group_list = "";

foreach($cache_group as $m_group)
{
    $group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Быстрый поиск и редактирование</div>
                    </div>
                    <form  method="post" name="filters" action="{$redirect_url}?do=users">
                    <input type="hidden" name="how_s" value="3" />
                    <input type="hidden" name="type" value="name" />
                    <input type="hidden" name="member_gr" value="0" />
                    <input type="hidden" name="date_reg_1" value="" />
                    <input type="hidden" name="date_reg_2" value="" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Найти пользователя:</td>
                            <td class="appText appText_bottom"><input type="text" name="text" placeholder="Логин/ник пользователя" value="" id="text_name" class="inputText" /> <input type="submit" name="search" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    <form action="{$redirect_url}?" name="fast_forum" method="get">
                    <input type="hidden" name="do" value="users" />
                    <input type="hidden" name="op" value="editgroup" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Редактировать группу:</td>
                            <td class="appText appText_bottom"><select name="id">{$group_list}</select> <input type="submit" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    <form  method="post" name="allip" action="{$redirect_url}?do=users&op=tools">
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Найти все IP пользователя:</td>
                            <td class="appText appText_bottom"><input type="text" name="sname" value="" id="sname" placeholder="Логин/ник пользователя" class="inputText" /> <input type="submit" name="allip" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    <form  method="post" name="infoip" action="{$redirect_url}?do=users&op=tools">
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Найти IP адрес:</td>
                            <td class="appText appText_bottom"><input type="text" name="ip" id="word_ip" value="" placeholder="127.0.0.1 или 127.*.0.1" class="inputText" /> <input type="submit" name="infoip" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    <form action="{$redirect_url}?" name="fast_forum" method="get">
                    <input type="hidden" name="do" value="board" />
                    <input type="hidden" name="op" value="editforum" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Редактировать форум:</td>
                            <td class="appText appText_bottom"><input type="text" name="id" id="fid" placeholder="ID форума или категории" value="" class="inputText" /> <input type="submit" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    <form method="get" name="logs_s" action="{$redirect_url}?">
                    <input type="hidden" name="do" value="logs" />
                    <input type="hidden" name="op" value="actions" />
                    <input type="hidden" name="where_search" value="member_name" />
                    <input type="hidden" name="type" value="1" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Логи действий в ЦУ:</td>
                            <td class="appText appText_bottom"><input type="text" name="word" placeholder="Логин/ник пользователя" value="" id="word_name" class="inputText" /> <input type="submit" name="search" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    <form method="get" name="logs_s" action="{$redirect_url}?">
                    <input type="hidden" name="do" value="logs" />
                    <input type="hidden" name="op" value="login" />
                    <input type="hidden" name="type" value="0" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">Логи авторизаций ЦУ:</td>
                            <td class="appText appText_bottom"><input type="text" name="word" placeholder="Логин/ник пользователя" value="" id="word_name2" class="inputText" /> <input type="submit" name="search" value="ОК" /></td>
                        </tr>
                    </table>
                    </form>
                    
<script type="text/javascript">
	inputPlaceholder(document.getElementById('fid'))
    inputPlaceholder(document.getElementById('text_name'))
    inputPlaceholder(document.getElementById('word_name'))
    inputPlaceholder(document.getElementById('word_name2'))
    inputPlaceholder(document.getElementById('word_ip'))
    inputPlaceholder(document.getElementById('sname'))
</script>

HTML;


?>