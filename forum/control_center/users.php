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
    
if(control_center_admins($member_cca['users']['users']))
{
    switch ($op)
    {
	   case "group":
            if(control_center_admins($member_cca['users']['group']))
                require LB_CONTROL_CENTER . '/users/group.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ������";
                control_center_admins_error();
            }
	   break;

	   case "editgroup":
            if(control_center_admins($member_cca['users']['group']) AND control_center_admins($member_cca['users']['group_edit']))
                require LB_CONTROL_CENTER . '/users/editgroup.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=group\">������</a>|��������������: <i>������ ������</i>";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ������ &raquo; ��������������: <i>������ ������</i>";
                control_center_admins_error();
            }                
	   break;

        case "adduser":
            if(control_center_admins($member_cca['users']['add']))
                require LB_CONTROL_CENTER . '/users/adduser.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|����������� ������������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ����������� ������������";
                control_center_admins_error();
            }
	   break;

	   case "ranks":
            if(control_center_admins($member_cca['users']['ranks']))
                require LB_CONTROL_CENTER . '/users/ranks.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ������";
                control_center_admins_error();
            }
	   break;

	   case "edit_ranks":
            if(control_center_admins($member_cca['users']['ranks']))
                require LB_CONTROL_CENTER . '/users/edit_ranks.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=ranks\">������</a>|��������������: <i>������ ������</i>";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ������ &raquo; ��������������: <i>������ ������</i>";
                control_center_admins_error();
            }
	   break;
    
        case "cca":
            if(control_center_admins($member_cca['users']['cca']))
                require LB_CONTROL_CENTER . '/users/cca.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|����������� ���� � ������ ����������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ����������� ���� � ������ ����������";
                control_center_admins_error();
            }
	   break;
    
        case "cca_add":
            if(control_center_admins($member_cca['users']['cca']))
                require LB_CONTROL_CENTER . '/users/cca_add.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=cca\">����������� ���� � ������ ����������</a>|���������� �����������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ����������� ���� � ������ ���������� &raquo; ���������� �����������";
                control_center_admins_error();
            }
	   break;
    
        case "cca_edit":
            if(control_center_admins($member_cca['users']['cca']))
                require LB_CONTROL_CENTER . '/users/cca_edit.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=cca\">����������� ���� � ������ ����������</a>|�������������� �����������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ����������� ���� � ������ ���������� &raquo; �������������� �����������";
                control_center_admins_error();
            }
	   break;
       
       case "tools":
            if(control_center_admins($member_cca['users']['tools']))
                require LB_CONTROL_CENTER . '/users/tools.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|�����������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; �����������";
                control_center_admins_error();
            }
	   break;
              
       case "delivery":
            if(control_center_admins($member_cca['users']['delivery']))
                require LB_CONTROL_CENTER . '/users/delivery.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|��������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; ��������";
                control_center_admins_error();
            }
	   break;

        case "delivery_new":
            if(control_center_admins($member_cca['users']['delivery_new']))
                require LB_CONTROL_CENTER . '/users/delivery_new.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|<a href=\"".$redirect_url."?do=users&op=delivery\">��������</a>|�����";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; �������� &raquo; �����";
                control_center_admins_error();
            }
	   break;
       
       case "warning":
            if(control_center_admins($member_cca['users']['warning']))
                require LB_CONTROL_CENTER . '/users/warning.php';
            else
            {
                $link_speddbar = "<a href=\"".$redirect_url."?do=users\">������������</a>|��������������";
                $control_center->header("������������", $link_speddbar);
                $onl_location = "������������ &raquo; �������������� &raquo; �����";
                control_center_admins_error();
            }
	   break;

	   default :
       
            if(control_center_admins($member_cca['users']['main']))
                require LB_CONTROL_CENTER . '/users/main.php';
            else
            {
                $control_center->header("������������", "������������");
                $onl_location = "������������";
                control_center_admins_error();
            }
	   break;
    }
}
else
{
    $control_center->header("������������", "������������");
    $onl_location = "������������";
    control_center_admins_error();
}

$control_center->footer(2);

?>