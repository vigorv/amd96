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

$link_speddbar = "<a href=\"".$redirect_url."?do=users\">Пользователи</a>|<a href=\"".$redirect_url."?do=users&op=cca\">Ограничение прав к центру управления</a>|Редактирование ограничения";
$control_center->header("Пользователи", $link_speddbar);
$onl_location = "Пользователи &raquo; Ограничение прав к центру управления &raquo; Редактирование ограничения";

$DB->prefix = array( 1 => DLE_USER_PREFIX );
$moder = $DB->one_join_select( "cc.*, u.name", "LEFT", "control_center_admins cc||users u", "cc.cca_member_id=u.user_id", "cca_id = '{$id}'" );

$control_center->errors = array ();

if($moder['cca_id'])
{  
    if (isset($_POST['moder']) AND $_POST['secret_key'] != "" AND $_POST['secret_key'] == $secret_key)
    {  
    	$group_permission = array();
        
        $group_permission['config'] = array();
        $group_permission['config']['config'] = intval($_POST['config']);
        $group_permission['config']['change'] = intval($_POST['config_change']);
        $group_permission['config']['add'] = intval($_POST['config_add']);
        $group_permission['config']['del'] = intval($_POST['config_del']);
        $group_permission['config']['email'] = intval($_POST['config_email']);
        $group_permission['config']['template'] = intval($_POST['config_template']);
        $group_permission['config']['template_edit'] = intval($_POST['config_template_edit']);
        $group_permission['config']['user_agent'] = intval($_POST['config_user_agent']);
        $group_permission['config']['language'] = intval($_POST['config_language']);
         
        $group_permission['users'] = array();
        $group_permission['users']['users'] = intval($_POST['users']);
        $group_permission['users']['main'] = intval($_POST['users_main']);
        $group_permission['users']['add'] = intval($_POST['users_add']);
        $group_permission['users']['group'] = intval($_POST['users_group']);
        $group_permission['users']['group_edit'] = intval($_POST['users_group_edit']);
        $group_permission['users']['ranks'] = intval($_POST['users_ranks']);
        $group_permission['users']['delivery'] = intval($_POST['users_delivery']);
        $group_permission['users']['delivery_new'] = intval($_POST['users_delivery_new']);
        $group_permission['users']['cca'] = intval($_POST['users_cca']);
        $group_permission['users']['tools'] = intval($_POST['users_tools']);
        $group_permission['users']['warning'] = intval($_POST['users_warning']);
        
        $group_permission['board'] = array();
        $group_permission['board']['board'] = intval($_POST['board']);
        $group_permission['board']['addforum'] = intval($_POST['board_addforum']);
        $group_permission['board']['addcategory'] = intval($_POST['board_addcategory']);
        $group_permission['board']['editforum'] = intval($_POST['board_editforum']);
        $group_permission['board']['delforum'] = intval($_POST['board_delforum']);
        $group_permission['board']['filters'] = intval($_POST['board_filters']);
        $group_permission['board']['moders'] = intval($_POST['board_moders']);
        $group_permission['board']['moders_edit'] = intval($_POST['board_moders_edit']);
        $group_permission['board']['moders_del'] = intval($_POST['board_moders_del']);
        $group_permission['board']['notice'] = intval($_POST['board_notice']);
        $group_permission['board']['sharelink'] = intval($_POST['board_sharelink']);
        $group_permission['board']['sharelink_edit'] = intval($_POST['board_sharelink_edit']);
        
        $group_permission['complaint'] = array();
        $group_permission['complaint']['complaint'] = intval($_POST['complaint']);
        
        $group_permission['logs'] = array();
        $group_permission['logs']['logs'] = intval($_POST['logs']);
        $group_permission['logs']['action'] = intval($_POST['logs_action']);
        $group_permission['logs']['action_del'] = intval($_POST['logs_action_del']);
        $group_permission['logs']['login'] = intval($_POST['logs_login']);
        $group_permission['logs']['files'] = intval($_POST['logs_files']);
        $group_permission['logs']['filesdel'] = intval($_POST['logs_filesdel']);
        $group_permission['logs']['mysql'] = intval($_POST['logs_mysql']);
        $group_permission['logs']['mysqldel'] = intval($_POST['logs_mysqldel']);
        $group_permission['logs']['ban'] = intval($_POST['logs_ban']);
        $group_permission['logs']['bandel'] = intval($_POST['logs_bandel']);
        $group_permission['logs']['topics'] = intval($_POST['logs_topics']);
        $group_permission['logs']['topics_del'] = intval($_POST['logs_topics_del']);
        $group_permission['logs']['posts'] = intval($_POST['logs_posts']);
        $group_permission['logs']['posts_del'] = intval($_POST['logs_posts_del']);
        
        $group_permission['system'] = array();
        $group_permission['system']['system'] = intval($_POST['system']);
        $group_permission['system']['info'] = intval($_POST['system_info']);
        $group_permission['system']['cache'] = intval($_POST['system_cache']);
        $group_permission['system']['cachedel'] = intval($_POST['system_cachedel']);
        $group_permission['system']['rebuild'] = intval($_POST['system_rebuild']);
        
        $group_permission['modules'] = array();
        $group_permission['modules']['staticpage'] = intval($_POST['modules_staticpage']);
        $group_permission['modules']['rules'] = intval($_POST['modules_rules']);
        $group_permission['modules']['adt'] = intval($_POST['modules_adt']);
        
        $group_permission = $DB->addslashes( serialize($group_permission) );
        
        if(!$moder['cca_is_group'])
            $info = "<font color=orange>Редактирование</font> ограничения пользвоатля <b>".$moder['name']."</b>";
        else
            $info = "<font color=orange>Редактирование</font> ограничения группы ".$cache_group[$moder['cca_group']]['g_title'];
        
        $info = $DB->addslashes($info);

        $DB->update("cca_permission = '{$group_permission}', cca_update = '{$time}'", "control_center_admins", "cca_id = '{$id}'");
  
        $DB->insert("member_name = '{$member_id['name']}', date = '{$time}', ip = '{$_IP}', module = '{$do}', op = '{$op}', info = '{$info}'", "logs_actions_cc");

        header( "Location: ".$redirect_url."?do=users&op=cca" );
        exit();
    }
    
    $group_permission = unserialize($moder['cca_permission']);
    
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
    
    function option_code($title = "", $hint = "", $radio_code = "", $type_line = 0, $value = 0)
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

        radio_code($radio_code, $value);
        
echo <<<HTML
    </div>
</div>

HTML;

    }
        
echo <<<HTML

<script>
$(document).ready(function(){
    
     $("#general_a").click(function () {
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#complaint_block").hide(500);
      $("#general_block").show(500);
    });
    
    $("#users_a").click(function () {
      $("#general_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#complaint_block").hide(500);
      $("#users_block").show(500);
    });
  
    $("#board_a").click(function () {
      $("#general_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").show(500);
    });
    
    $("#logs_a").click(function () {
      $("#general_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#complaint_block").hide(500);
      $("#logs_block").show(500);
    }); 
    
    $("#system_a").click(function () {
      $("#general_block").hide(300);
      $("#modules_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#complaint_block").hide(500);
      $("#system_block").show(500);
    });  
    
    $("#modules_a").click(function () {
      $("#general_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#complaint_block").hide(500);
      $("#modules_block").show(500);
    });
    
    $("#complaint_a").click(function () {
      $("#general_block").hide(300);
      $("#users_block").hide(300);
      $("#board_block").hide(300);
      $("#logs_block").hide(300);
      $("#system_block").hide(300);
      $("#modules_block").hide(300);
      $("#complaint_block").show(500);
    });
    
});
</script>

<form  method="post" name="newgroup" action="">

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">Редактирование ограничения к центру управления</div>
                    </div>
                    <div class="borderL">
                        <div class="borderR">
                        <table><tr>
                        <td align=left><h5>Категории:</h5></td>
                        <td align=center id="general_a"><h5><a href="#" onclick="return false;">Конфигурация</a></h5></td>
                        <td align=center id="users_a"><h5><a href="#" onclick="return false;">Пользователи</a></h5></td>
                        <td align=center id="board_a"><h5><a href="#" onclick="return false;">Форум</a></h5></td>
                        <td align=center id="complaint_a"><h5><a href="#" onclick="return false;">Жалобы</a></h5></td>
                        <td align=center id="logs_a"><h5><a href="#" onclick="return false;">Журнал логов</a></h5></td>
                        <td align=center id="system_a"><h5><a href="#" onclick="return false;">Система</a></h5></td>
                        <td align=center id="modules_a"><h5><a href="#" onclick="return false;">Модули</a></h5></td>
                        </tr></table>
                        <div class="clear" style="height:8px;"></div>
                        <hr />
                        <div class="clear" style="height:8px;"></div>
                        <div id="general_block">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("Конфигурация", "Разрешить доступ к просмотру настроек форума.", "config", 0, $group_permission['config']['config']);
option_code ("Изменение значений", "", "config_change", 2, $group_permission['config']['change']);
option_code ("Добавление настроек", "", "config_add", 1, $group_permission['config']['add']);
option_code ("Удаление настроек", "", "config_del", 1, $group_permission['config']['del']);
option_code ("Шаблоны форума", "", "config_template", 2, $group_permission['config']['template']);
option_code ("Смена шаблона", "", "config_template_edit", 1, $group_permission['config']['template_edit']);
option_code ("Язык форума", "Полный доступ: просмотр и изменение", "config_language", 2, $group_permission['config']['language']);
option_code ("E-mail уведомлений", "Полный доступ: просмотр, добавление/удаление, редактирование", "config_email", 2, $group_permission['config']['email']);
option_code ("Список User Agent", "Полный доступ: просмотр, добавление/удаление, редактирование", "config_user_agent", 2, $group_permission['config']['user_agent']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="users_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left style="padding-top:5px;width:100%;">
HTML;
option_code ("Доступ к модулю пользователей", "Разрешить доступ к модулю пользователей.", "users", 0, $group_permission['users']['users']);
option_code ("Просмотр и поиск по пользователям", "", "users_main", 2, $group_permission['users']['main']);
option_code ("Добавление нового пользователя", "", "users_add", 1, $group_permission['users']['add']);
option_code ("Просмотр списка групп", "", "users_group", 1, $group_permission['users']['group']);
option_code ("Редактирование групп", "", "users_group_edit", 1, $group_permission['users']['group_edit']);
option_code ("Полный доступ к званиям", "Добавление, редактирование и удаление званий.", "users_ranks", 2, $group_permission['users']['ranks']);
option_code ("Полный доступ к предупреждениям", "", "users_warning", 1, $group_permission['users']['warning']);
option_code ("Просмотр рассылок", "", "users_delivery", 2, $group_permission['users']['delivery']);
option_code ("Отправка и удаление рассылок", "", "users_delivery_new", 1, $group_permission['users']['delivery_new']);
option_code ("Полный доступ к ограничению прав к ЦУ", "Добавление, редактирование и удаление ограничений.", "users_cca", 2, $group_permission['users']['cca']);
option_code ("Полный доступ к инструментам", "", "users_tools", 1, $group_permission['users']['tools']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="board_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;

option_code ("Форум", "Разрешить доступ к просмотру форумов", "board", 0, $group_permission['board']['board']);
option_code ("Добавление форума", "", "board_addforum", 2, $group_permission['board']['addforum']);
option_code ("Добавление категории", "", "board_addcategory", 1, $group_permission['board']['addcategory']);
option_code ("Редактирование форума или категории", "", "board_editforum", 1, $group_permission['board']['editforum']);
option_code ("Удаление форума или категории", "", "board_delforum", 1, $group_permission['board']['delforum']);
option_code ("Просмотр модераторов", "", "board_moders", 2, $group_permission['board']['moders']);
option_code ("Добавление и удаление модераторов", "", "board_moders_edit", 1, $group_permission['board']['moders_edit']);
option_code ("Редактирование модераторов", "", "board_moders_del", 1, $group_permission['board']['moders_del']);
option_code ("Полный доступ к фильтру слов", "Добавление, редактирование и удаление фильтра.", "board_filters", 2, $group_permission['board']['filters']);
option_code ("Полный доступ к объявлениям", "Добавление, редактирование и удаление объявлений.", "board_notice", 1, $group_permission['board']['notice']);
option_code ("Сервисы публикации", "Просмотр списка сервисов публикации ссылок.", "board_sharelink", 2, $group_permission['board']['sharelink']);
option_code ("Редактирование сервиса публикации:", "Добавление, редактирование и удаление сервисов публикации ссылок.", "board_sharelink_edit", 1, $group_permission['board']['sharelink_edit']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="complaint_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;

option_code ("Система жалоб", "Разрешить доступ к просмотру и удалению жалоб", "complaint", 0, $group_permission['complaint']['complaint']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="logs_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("Журнал логов", "Разрешить доступ логам форума.", "logs", 0, $group_permission['logs']['logs']);
option_code ("Просмотр логов действий в ЦУ", "", "logs_action", 2, $group_permission['logs']['action']);
option_code ("Удаление логов действий в ЦУ", "", "logs_action_del", 1, $group_permission['logs']['action_del']);
option_code ("Просмотр логов авторизаций", "", "logs_login", 2, $group_permission['logs']['login']);
option_code ("Просмотр логов обращений к файлам", "", "logs_files", 2, $group_permission['logs']['files']);
option_code ("Удаление логов обращений к файлам", "", "logs_filesdel", 1, $group_permission['logs']['filesdel']);
option_code ("Просмотр логов MySQL ошибок", "", "logs_mysql", 2, $group_permission['logs']['mysql']);
option_code ("Удаление логов MySQL ошибок", "", "logs_mysqldel", 1, $group_permission['logs']['mysqldel']);
option_code ("Просмотр логов блокировок", "", "logs_ban", 2, $group_permission['logs']['ban']);
option_code ("Удаление логов блокировок", "", "logs_bandel", 1, $group_permission['logs']['bandel']);
option_code ("Просмотр логов действий с темами", "", "logs_topics", 2, $group_permission['logs']['topics']);
option_code ("Удаление логов действий с темами", "", "logs_topics_del", 1, $group_permission['logs']['topics_del']);
option_code ("Просмотр логов действий с сообщениями", "", "logs_posts", 2, $group_permission['logs']['posts']);
option_code ("Удаление логов действий с сообщениями", "", "logs_posts_del", 1, $group_permission['logs']['posts_del']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="system_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("Система", "Разрешить доступ к модулю \"система\".", "system", 0, $group_permission['system']['system']);
option_code ("Просмотр информации сервера", "", "system_info", 2, $group_permission['system']['info']);
option_code ("Просмотр файлов кеша", "", "system_cache", 1, $group_permission['system']['cache']);
option_code ("Удаление файлов кеша", "", "system_cachedel", 1, $group_permission['system']['cachedel']);
option_code ("Пересчёт данных", "", "system_rebuild", 1, $group_permission['system']['rebuild']);
echo <<<HTML
                                        
                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="modules_block" style="display:none;">
                           <table>
                                <tr>
                                    <td align=left>
HTML;
option_code ("Полный доступ к статическим страницам", "", "modules_staticpage", 0, $group_permission['modules']['staticpage']);
option_code ("Изменение правил форума", "", "modules_rules", 1, $group_permission['modules']['rules']);
option_code ("Полный доступ к блокам и рекламе", "", "modules_adt", 1, $group_permission['modules']['adt']);
echo <<<HTML

                                    </td>
                                </tr>
                            </table>
                            </div>
                            
                            <table>
                                <tr>
                                    <td style="padding-top:10px;">
                                        <div class="clear" style="height:18px;"></div>
                                        <div>
                                            
                                            <div><input type="submit" name="moder" value="Сохранить" class="btnBlue" />
                                            <input type="hidden" name="secret_key" value="{$secret_key}" /></div>
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
{
	$control_center->errors_title = "Не найден!";
	$control_center->errors[] = "Выбранное ограничение не найдено в базе данных.";
	$control_center->message();
}
?>