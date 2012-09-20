<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$lang_m_system_info = language_forum ("board/modules/system_info");

switch ($op)
{
	case "log_topic":
        if (!$id)
            message ($lang_message['error'], $lang_m_system_info['no_topic_id'], 1);
        else
            include_once LB_MODULES . '/system_info/log_topic.php';
	break;
    
    case "log_post":
        if (!$id)
            message ($lang_message['error'], $lang_m_system_info['no_post_id'], 1);
        else
            include_once LB_MODULES . '/system_info/log_post.php';
	break;

	default :
		include_once LB_MODULES . '/system_info/bbcode.php';
	break;
}
?>