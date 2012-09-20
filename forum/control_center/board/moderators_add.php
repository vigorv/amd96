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

$link_speddbar = "<a href=\"".$redirect_url."?do=board\">Форум</a>|<a href=\"".$redirect_url."?do=board&op=moderators\">Модераторы</a>|Добавление модератора";
$control_center->header("Форум", $link_speddbar);
$onl_location = "Форум &raquo; Модераторы &raquo; Добавление модератора";

$control_center->errors = array ();

if (!isset($_GET['step']))
{
    unset($_SESSION['moder_forum_id']);
    unset($_SESSION['moder_name']);
    unset($_SESSION['moder_group']);
    unset($_SESSION['moder_id']);
        
    if (isset($_POST['moder']))
    {
	   require LB_CLASS . '/safehtml.php';
	   $safehtml = new safehtml( );

	   $forums = $_POST['forums'];
	   $member_name = $DB->addslashes( $safehtml->parse( trim( $_POST['member'] ) ) );
    
        if($member_name != "")
        {
            $DB->prefix = DLE_USER_PREFIX;
            $check = $DB->one_select( "user_id, user_group", "users", "name = '{$member_name}'" );
    
            if (!$check['user_id'])
		          $control_center->errors[] = "Пользователь не найден.";
                  
            $_SESSION['moder_id'] = $check['user_id'];
        }
        
        $member_group = intval( $_POST['group_permission'] );
            
        if(!$member_group AND $member_name == "")
            $control_center->errors[] = "Вы должны указать ник пользователя или выбрать группу модераторов.";
        
        if (!$forums)
            $control_center->errors[] = "Вы должны выбрать хотя бы один форум.";
        else
        {
            foreach ($forums as $f_id)
            {
                $f_id = intval($f_id);
                
                if (!$cache_forums[$f_id]['id']) $control_center->errors[] = "Форум не найден (ID ".$f_id.").";
                elseif ($cache_forums[$f_id]['flink']) $control_center->errors[] = "Форум является ссылкой (ID ".$f_id.").";
                else
                {
                    if ($member_name == "")
                    {
                        $check2 = $DB->one_select( "*", "forums_moderator", "fm_forum_id = '{$f_id}' AND fm_group_id = '{$member_group}' AND fm_is_group = '1'" );
                        if ($check2['fm_id']) $control_center->errors[] = "Данная группа уже модерирует выбранный форум (ID ".$f_id.").";
                    }
                    else
                    {
                        $check3 = $DB->one_select( "*", "forums_moderator", "fm_forum_id = '{$f_id}' AND fm_member_name = '{$member_name}' AND fm_is_group = '0'" );
                        if ($check3['fm_id']) $control_center->errors[] = "Данный пользователь уже модерирует выбранный форум (ID ".$f_id.").";
                    }
                }
            }
        }
        
        if ($member_name != "") $member_group = $check['user_group'];
        
        if($cache_group[$member_group]['g_supermoders']) $control_center->errors[] = "Выбранная группа или пользователь уже являются супер модераторами.";
        
	   if (!$control_center->errors)
	   {
            $_SESSION['moder_forum_id'] = array();
            foreach ($forums as $f_id)
            {  
                $_SESSION['moder_forum_id'][] = intval($f_id);
            }
            
            $_SESSION['moder_name'] = $member_name;
            $_SESSION['moder_group'] = $member_group;

            header( "Location: ".$redirect_url."?do=board&op=moder_add&step=2&secret_key={$secret_key}" );
            exit();
	   }
	   else
		  $control_center->errors_title = "Ошибка!";
    }

    $control_center->message();

    $group_list = "<option value=\"0\">-- Выберите группу</option>";

    foreach($cache_group as $m_group)
    {
	   $group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
    }

    $forum_list = ForumsList();

echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление модератора: Шаг 1</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
                                       <div>
                                            <div class="inputCaption2">Пользователь:<br><font class="smalltext">Введите ник пользователя или выберите группу.</font></div>
                                            <div><input type="text" name="member" value="" class="inputText" /></div>
                                        </div>
                                    </td>
                                    <td align=left>
                                         <div>
                                            <div class="inputCaption2">Группа:<br><font class="smalltext">Выберите группу или введите ник пользователя.</font></div>
                                            <div><select name="group_permission">{$group_list}</select></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=left colspan=3>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2">форумы:<br><font class="smalltext">Права модератора будут распространяться на все подфорумы выбранного форума.</font></div>
                                            <div><select name="forums[]" multiple style="width:616px;height:250px;">{$forum_list}</select></div>
                                        </div>
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <div><input type="submit" name="moder" value="Шаг 2" class="btnBlue" /></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</form>
HTML;

}
elseif (isset($_GET['step']) AND $_GET['step'] == 2 AND $_GET['secret_key'] != "" AND $_GET['secret_key'] == $secret_key AND ($_SESSION['moder_name'] != "" OR intval($_SESSION['moder_group']) != 0) AND count($_SESSION['moder_forum_id']))
{
  
    if (isset($_POST['moder_step2']))
    {  
    	$group_permission = array();
        
        $group_permission['global_hideshow'] = intval($_POST['g_global_hideshow']);
        $group_permission['global_hidetopic'] = intval($_POST['g_global_hidetopic']);
        $group_permission['global_deltopic'] = intval($_POST['g_global_deltopic']);
        $group_permission['global_titletopic'] = intval($_POST['g_global_titletopic']);
        $group_permission['global_polltopic'] = intval($_POST['g_global_polltopic']);
        $group_permission['global_opentopic'] = intval($_POST['g_global_opentopic']);
        $group_permission['global_closetopic'] = intval($_POST['g_global_closetopic']);
        $group_permission['global_fixtopic'] = intval($_POST['g_global_fixtopic']);
        $group_permission['global_unfixtopic'] = intval($_POST['g_global_unfixtopic']);
        $group_permission['global_movetopic'] = intval($_POST['g_global_movetopic']);
        $group_permission['global_uniontopic'] = intval($_POST['g_global_uniontopic']);
        $group_permission['global_delpost'] = intval($_POST['g_global_delpost']);
        $group_permission['global_unionpost'] = intval($_POST['g_global_unionpost']);
        $group_permission['global_changepost'] = intval($_POST['g_global_changepost']);
        $group_permission['global_movepost'] = intval($_POST['g_global_movepost']);
        $group_permission['global_fixedpost'] = intval($_POST['g_global_fixedpost']);
        $group_permission['global_topic_log'] = intval($_POST['g_global_topic_log']);
        $group_permission['global_post_log'] = intval($_POST['g_global_post_log']);
        $group_permission['global_movepost_date'] = intval($_POST['g_global_movepost_date']); 
    
        $group_permission = $DB->addslashes( serialize($group_permission) );
        
        if($_SESSION['moder_id'])
            $fm_member_id = $_SESSION['moder_id'];
        else
            $fm_member_id = 0;
            
        $fm_group_id = intval($_SESSION['moder_group']);
                
        foreach ($_SESSION['moder_forum_id'] as $f_id)
        {   
            $f_id = intval($f_id);
            
            if($_SESSION['moder_name'])
            {
                $fm_member_name = $_SESSION['moder_name'];
                $fm_is_group = 0;
                $info = "<font color=green>Добавление</font> модератора <b>".$fm_member_name."</b> для форума: ".$cache_forums[$f_id]['title'];
            }
            else
            {
                $fm_member_name = "";
                $fm_is_group = 1;
                $info = "<font color=green>Добавление</font> группы модераторов ".$cache_group[$fm_group_id]['g_title']." для форума: ".$cache_forums[$f_id]['title'];
            }
        
            $info = $DB->addslashes($info);
            
            $DB->insert("fm_forum_id = '{$f_id}', fm_member_id = '{$fm_member_id}', fm_member_name = '{$fm_member_name}', fm_group_id = '{$fm_group_id}', fm_is_group = '{$fm_is_group}', fm_permission = '{$group_permission}'", "forums_moderator");
            $cache->clear("", "forums_moder");
  
            $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");
        }
        
        unset ($_SESSION['moder_forum_id']);
        unset ($_SESSION['moder_id']);
        unset ($_SESSION['moder_group']);
        unset ($_SESSION['moder_name']);
                     
        header( "Location: ".$redirect_url."?do=board&op=moderators" );
        exit();
    }
    
    function radio_code($name = "", $checked = false)
    {
        $how = "";
        $how2 = "";
    
        if ($checked)
            $how = "checked";
        else
            $how2 = "checked";
        
echo <<<HTML

<div class="radioContainer"><input name="{$name}" type="radio" id="{$name}_1" value="1" {$how}></div><label class="radioLabel" for="{$name}_1">Да</label>
<div class="radioContainer optionFalse"><input name="{$name}" type="radio" id="{$name}_0" value="0" {$how2}></div><label class="radioLabel" for="{$name}_0">Нет</label>

HTML;

    }
    
    function option_code($title = "", $hint = "", $radio_code = "", $type_line = 0)
    {
        if ($hint) $hint = "<br><font class=\"smalltext\">".$hint."</font>";
        
        if ($type_line == 2)
        {
echo <<<HTML

<div class="clear" style="height:8px;"></div>
<hr />
<div class="clear" style="height:8px;"></div>
HTML;
        }
        elseif ($type_line == 1)
        {
echo <<<HTML

<div class="clear" style="height:18px;"></div>
HTML;
        }
        
echo <<<HTML

<div>
    <div class="inputCaption2">{$title}:{$hint}</div>
    <div>
HTML;

        radio_code($radio_code);
        
echo <<<HTML
    </div>
</div>

HTML;

    }
    
echo <<<HTML
<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Добавление модератора: Шаг 2</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                           <table>
                                <tr>
                                    <td align=left>
HTML;

option_code ("Просмотр скрытых тем и сообщений", "", "g_global_hideshow");
option_code ("Скрытие тем и сообщений", "", "g_global_hidetopic", 2);
option_code ("Удаление тем", "", "g_global_deltopic", 2);
option_code ("Изменение названий тем", "Изменение названия и описания тем.", "g_global_titletopic", 1);
option_code ("Изменение голосований", "Изменение или удаление голосований в темах.", "g_global_polltopic", 1);
option_code ("Открытие тем", "Если тема была закрыта.", "g_global_opentopic",1);
option_code ("Закрытие тем", "", "g_global_closetopic", 1);
option_code ("Закрепление тем", "Выводит темы сверху над списком обычных тем с пометкой \"важно\".", "g_global_fixtopic", 1);
option_code ("Опускать темы", "Если тема была закреплена.", "g_global_unfixtopic", 1);
option_code ("Перемещение тем", "Перемещение тем в другие форумы.", "g_global_movetopic", 1);
option_code ("Объединение тем", "Соединение нескольких тем в одну.", "g_global_uniontopic", 1);
option_code ("Просмотр логов тем", "Просмотр логов действий с темой.", "g_global_topic_log", 1);

option_code ("Удаление сообщений", "", "g_global_delpost", 2);
option_code ("Редактирование сообщений", "", "g_global_changepost", 1);
option_code ("Закрепление/открепление сообщений", "", "g_global_fixedpost", 1);
option_code ("Перемещение сообщений", "Перемещение сообщений в другие темы.", "g_global_movepost", 1);
option_code ("Обновление даты сообщений", "Обновление даты сообщений при переносе их в другую тему.", "g_global_movepost_date", 1);
option_code ("Объединение сообщений", "Соединение нескольких сообщений в одно.", "g_global_unionpost", 1);
option_code ("Просмотр логов сообщений", "Просмотр логов действий с сообщением.", "g_global_post_log", 1);

echo <<<HTML
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            <div class="inputCaption2"></div>
                                            <div><input type="submit" name="moder_step2" value="Сохранить" class="btnBlue" />
                                            <input type="hidden" name="secret_key" value="{$secret_key}" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</form>
HTML;
}
else
    header( "Location: {$_SESSION['back_link_board']}" );
    
?>