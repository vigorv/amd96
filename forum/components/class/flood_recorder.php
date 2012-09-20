<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

class LB_Flood
{
	PUBLIC $block_time = 30;
    PUBLIC $interval = 30;
	PUBLIC $buttom = 10;
    
    PUBLIC $loadpage = 20;
    PUBLIC $load_interval = 1;

	function isBlock ($type = 0)
	{
        global $DB, $member_id, $logged, $_IP, $time;

        $error = false;

        $this->check_data();
        
        if (!$logged)
            $row = $DB->one_select( "*", "members_flood", "fl_ip = '{$_IP}' AND fl_check_type = '{$type}'" );
        else
            $row = $DB->one_select( "*", "members_flood", "fl_mid = '{$member_id['user_id']}' AND fl_check_type = '{$type}'" );
            
        if ($row['fl_id'] AND !$row['is_blocked'])
        {
            $note = unserialize($row['fl_note']);
            $i = 0;
            $note_new = array();
            foreach ($note as $value)
            {
                if (($time - $this->interval) <= $value AND !$type)
                {
                    $i ++;
                    $note_new[] = $value;
                }
                    
                if ($i >= $this->buttom AND !$type) 
                    $error = true;
                        
                if (($time - $this->load_interval) <= $value AND $type)
                {
                    $i ++;
                    $note_new[] = $value;
                }
                        
                if ($i >= $this->loadpage AND $type) 
                $error = true;
            }      
                
            if ($error)
                $is_blocked = 1;
            else
            {
                $is_blocked = 0;
                $note_new[] = $time;
            }
                
            $note_new = $DB->addslashes(serialize($note_new));
                
            if (!$logged)
                $DB->update("fl_mid = '0', fl_last_date = '{$time}', fl_note = '{$note_new}', is_blocked = '{$is_blocked}'", "members_flood", "fl_ip = '{$_IP}' AND fl_check_type = '{$type}'");          
            else
                $DB->update("fl_mid = '{$member_id['user_id']}', fl_ip = '{$_IP}', fl_last_date = '{$time}', fl_note = '{$note_new}', is_blocked = '{$is_blocked}'", "members_flood", "fl_mid = '{$member_id['user_id']}' AND fl_check_type = '{$type}'");
        }
        elseif ($row['fl_id'] AND $row['is_blocked'])
        {
            $update = false;
                
            if (($time - $this->block_time) >= $row['fl_last_date'])
                $update = true;
                
            if ($update)
            {
                $note_new = array();
                $note_new[] = $time;   
                $note_new = $DB->addslashes(serialize($note_new));
                if (!$logged)
                    $DB->update("fl_mid = '0', fl_last_date = '{$time}', fl_note = '{$note_new}', is_blocked = '0'", "members_flood", "fl_ip = '{$_IP}' AND fl_check_type = '{$type}'");
                else
                    $DB->update("fl_mid = '{$member_id['user_id']}', fl_ip = '{$_IP}', fl_last_date = '{$time}', fl_note = '{$note_new}', is_blocked = '0'", "members_flood", "fl_mid = '{$member_id['user_id']}' AND fl_check_type = '{$type}'");
            }
            else
                $error = true;
        }
        else
        {
            $note = array();
            $note[] = $time;
            $note = $DB->addslashes(serialize($note));
            if (!$logged)
                $DB->insert("fl_mid = '0', fl_ip = '{$_IP}', fl_last_date = '{$time}', fl_note = '{$note}', fl_check_type = '{$type}', is_blocked = '0'", "members_flood");
            else
                $DB->insert("fl_mid = '{$member_id['user_id']}', fl_ip = '{$_IP}', fl_last_date = '{$time}', fl_note = '{$note}', fl_check_type = '{$type}', is_blocked = '0'", "members_flood");
        }

		return $error;
	}

	function del_record ()
	{
        global $DB, $member_id, $logged, $_IP, $time;
        
        $this->check_data();
        
        $del_time = $time - $this->interval;
        
        $DB->delete("fl_last_date < '{$del_time}'", "members_flood");
	}
    
    function check_data ()
	{  
        if ($this->time_block <= 0)
            $this->time_block = 30;
            
        if ($this->interval <= 0)
            $this->interval = 30;
            
        if ($this->max_access <= 0)
            $this->max_access = 10;
            
        if ($this->loadpage <= 0)
            $this->loadpage = 20;
            
        if ($this->load_interval <= 0)
            $this->load_interval = 1;   
	}
}

?>