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

if (isset($_GET['act']) AND $_GET['act'] == "del")
{
    if (!$_GET['secret_key'] OR $_GET['secret_key'] != $secret_key)
	   exit ( "Error, wrong secret key.<br><a href=\"/\">Go to main</a>." );
       
    $notice = $DB->one_select( "*", "forums_notice", "id = '{$id}'" );
    $DB->delete("id = '{$id}'", "forums_notice");
    $cache->clear("", "forums_notice");
    
    $info = "<font color=red>Удаление</font> объявления: ".$notice['title'];
    $info = $DB->addslashes( $info );
    $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
    
    header( "Location: ".$redirect_url."?do=board&op=notice" );
    exit();
}

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|Объявления";
$control_center->header("Форум", $link_speddbar);
$onl_location = "Форум &raquo; Объявления";

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Объявления</div>
                    </div>
		<table class="colorTable">
                        <tr>
				<td align=left><h6>Заголовок</h6></td>
				<td align=left><h6>Форум (ID)</h6></td>
                <td align=center><h6>Подфорумы</h6></td>
				<td align=left><h6>Доступ</h6></td>
                <td align=left><h6>Срок размещения</h6></td>
				<td align=right><h6>Действие</h6></td>
                        </tr>
HTML;

$DB->select( "*", "forums_notice", "", "ORDER by end_date ASC" );

$i = 0;

while ( $row = $DB->get_row() )
{
    $i ++;
    
	if ($i%2)
		$class = "appLine";
	else
		$class = "appLine dark";
        
    $date = date( "d.m.Y", $row['start_date']);
    
    $date .= " - ";
    
    if ($row['end_date'])
        $date .= date( "d.m.Y", $row['end_date']);
    else
        $date .= "Навсегда";
        
    if ($row['show_sub'])
        $show_sub = "Да";
    else
        $show_sub = "Нет";
        
    $group_access = explode(",", $row['group_access']);
    if (in_array("0", $group_access))
        $group_access_out = "Все группы";
    else
    {
        $group_access_out = array();
        foreach($group_access as $ga)
        {
            $group_access_out[] = $cache_group[$ga]['g_title'];
        }
        $group_access_out = implode(", ", $group_access_out);
    }
        
echo <<<HTML

                        <tr class="{$class}">
                            <td align=left class="blueHeader"><a href="{$redirect_url}?do=board&op=notice_edit&id={$row['id']}" title="Редактировать данное объявление.">{$row['title']}</a></td>
			                 <td align=left>{$row['forum_id']}</td>
                             <td align=center>{$show_sub}</td>
			                 <td align=left>{$group_access_out}</td>
                             <td align=left>{$date}</td>
			                 <td align=right><a href="javascript:confirmDelete('{$redirect_url}?do=board&op=notice&act=del&id={$row['id']}&secret_key={$secret_key}', 'Вы действительно хотите удалить объявление?')" title="Удалить данное объявление."><img src="{$redirect_url}template/images/delete.gif" alt="Удалить" /></a></td>
                        </tr>
HTML;

}
$DB->free();

echo <<<HTML
	        </table>
		 <div class="clear" style="height:10px;"></div>
	        <table><tr><td align=right style="padding-right:10px;"><a href="{$redirect_url}?do=board&op=notice_add" title="Добавить новое объявление."><img src="{$redirect_url}template/images/add.gif" alt="Добавить объявление" /></a></td></tr></table>
HTML;

?>