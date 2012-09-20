<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

if(control_center_admins($member_cca['logs']['logs']))
{
    switch ($op)
    {
	   case "login":
            if(control_center_admins($member_cca['logs']['login']))
                require LB_CONTROL_CENTER . '/logs/login.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|���� �����������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; ���� �����������";
                control_center_admins_error();
            }
	   break;

	   case "files":
            if(control_center_admins($member_cca['logs']['files']))
                require LB_CONTROL_CENTER . '/logs/files.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|������ ��������� � ������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; ������ ��������� � ������";
                control_center_admins_error();
            }
	   break;

	   case "actions":
            if(control_center_admins($member_cca['logs']['action']))
                require LB_CONTROL_CENTER . '/logs/actions.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|�������� � ������ ����������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; �������� � ������ ����������";
                control_center_admins_error();
            }
	   break;

	   case "mysql_errors":
            if(control_center_admins($member_cca['logs']['mysql']))
                require LB_CONTROL_CENTER . '/logs/mysql_errors.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|MySQL ������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; MySQL ������";
                control_center_admins_error();
            }
	   break;
    
       case "blocking":
            if(control_center_admins($member_cca['logs']['ban']))
                require LB_CONTROL_CENTER . '/logs/blocking.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|���������� � ����������� �������������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; ���������� � ����������� �������������";
                control_center_admins_error();
            }
	   break;
              
       case "topics":
            if(control_center_admins($member_cca['logs']['topics']))
                require LB_CONTROL_CENTER . '/logs/topics.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|�������� � ������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; �������� � ������";
                control_center_admins_error();
            }
	   break;
       
       case "posts":
            if(control_center_admins($member_cca['logs']['posts']))
                require LB_CONTROL_CENTER . '/logs/posts.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=logs\">������ �����</a>|�������� � �����������";
                $control_center->header("������ �����", $link_speddbar);
                $onl_location = "������ ����� &raquo; �������� � �����������";
                control_center_admins_error();
            }
	   break;

	   default :
		  include_once LB_CONTROL_CENTER . '/logs/main.php';
	   break;
    }
}
else
{
    $control_center->header("������ �����", "������ �����");
    $onl_location = "������ �����";
    control_center_admins_error();
}

$control_center->footer(4);

?>