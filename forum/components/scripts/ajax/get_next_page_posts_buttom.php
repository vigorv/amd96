<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

@session_start ();

if (!isset($_SESSION['Get_Next_Post_Buttom']))
    $_SESSION['Get_Next_Post_Buttom'] = 1;
else
{
    if ($_SESSION['Get_Next_Post_Buttom'] == 0)
        $_SESSION['Get_Next_Post_Buttom'] = 1;
    else
        $_SESSION['Get_Next_Post_Buttom'] = 0;
}

if ($_SESSION['Get_Next_Post_Buttom'])
{
    echo "<script type=\"text/javascript\">
    $(\"a#gnpb\").attr(\"id\", \"gnpb2\");
    $(\"a#gnpb2\").after('<a href=\"#\" title=\"' + LB_lang['gnpb_on_alt'] + '\" id=\"gnpb\" onclick=\"Get_Next_Post_Buttom();return false;\">' + LB_lang['gnpb_on_title'] + '</a>');
    $(\"a#gnpb2\").remove();
    loaded_content = false;
    loaded_content_buttom = true;
    </script>";
}
else
{
    echo "<script type=\"text/javascript\">    
    $(\"a#gnpb\").attr(\"id\", \"gnpb2\");
    $(\"a#gnpb2\").after('<a href=\"#\" title=\"' + LB_lang['gnpb_off_alt'] + '\" id=\"gnpb\" onclick=\"Get_Next_Post_Buttom();return false;\">' + LB_lang['gnpb_off_title'] + '</a>');
    $(\"a#gnpb2\").remove();
    loaded_content = false;
    loaded_content_buttom = false;
    </script>";
}

?>