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

class DB
{
	PUBLIC 	$db_id = false;
	PUBLIC 	$query_num = 0;
	PUBLIC 	$query_list = array();
	PRIVATE $mysql_error = '';
	PUBLIC 	$mysql_version = '';
	PUBLIC 	$mysql_error_num = 0;
	PUBLIC 	$MySQL_time_taken = 0;
	PUBLIC 	$query_id = false;
	PRIVATE $show_error = true;
    PUBLIC 	$prefix = LB_DB_PREFIX;
    
	function access($USER, $PASSWORD, $NAME, $LOCATION = 'localhost')
	{        
		$SQLPORT = false;
		if ( strstr( $LOCATION, ':' ) )
		{
			list( $loc, $port ) = explode( ':', $LOCATION );
			$LOCATION = $loc;
			$SQLPORT = $port;
		}
        
		if ($SQLPORT)
			$this->db_id = @mysqli_connect($LOCATION, $USER, $PASSWORD, $NAME, $SQLPORT);
		else
			$this->db_id = @mysqli_connect($LOCATION, $USER, $PASSWORD, $NAME);

		if(!$this->db_id)
		{
			if($this->show_error)
				$this->message(mysqli_connect_error(), '1');
			else
				return false;
		} 

		$this->mysql_version = mysqli_get_server_info($this->db_id);

		if(!defined('LB_DB_COLLATE'))
		{ 
			define ("LB_DB_COLLATE", "cp1251");
		}
        
		mysqli_query($this->db_id, "SET NAMES '" . LB_DB_COLLATE . "'");

		return true;
	}

	function check_error($query)
	{
		if(!($this->query_id = mysqli_query($this->db_id, $query)))
		{
			$this->mysql_error = mysqli_error($this->db_id);
			$this->mysql_error_num = mysqli_errno($this->db_id);

			if($this->show_error)
				$this->message($this->mysql_error, $this->mysql_error_num, $query);
		}
	}

	function select($rows = "*", $table, $where = "", $sort = "")
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);
		
		if (strlen($where) > 1)
			$where = "WHERE ".$where;
            
        $table = trim($table);
        if (preg_match("#\s+#", $table))
            $table = preg_replace("#\s+#iu", "` ", $table, 1);
        else
            $table .= "`";
            
        $query = "SELECT " . $rows . " FROM `" . $this->prefix ."_". $table ." ".$where." ".$sort;

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;
        $this->prefix = LB_DB_PREFIX;

		return $this->query_id;
	}

	function join_select($rows = "*", $join, $table, $on, $where = "", $sort = "")
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);
        
		if (strlen($where) > 1)
			$where = "WHERE ".$where;

		$table = explode ("||", $table);
		$join = explode ("||", $join);
		$on = explode ("||", $on);
		$join_s = sizeof($join);
        
        if (!is_array($this->prefix))
        {
            $this->prefix = array();
        }

		$query = "";
		$j = 0;
        $i = 0;
		foreach ( $table as $table_name )
		{
            if (!isset($this->prefix[$i]) OR $this->prefix[$i] == "")
            {
                $this->prefix[$i] = LB_DB_PREFIX;
            }
            
            if (preg_match("#\s+#", $table_name))
                $table_name = preg_replace("#\s+#iu", "` ", $table_name, 1);
            else
                $table_name .= "`";
            
            $table_name = trim($table_name);
            
			if (!$query)
				$query = "`".$this->prefix[$i] ."_". $table_name;
			else
			{
				if ($join_s == 1)
					$query .= " ".$join[0]." JOIN `". $this->prefix[$i] ."_". $table_name ." ON ".$on[$j];
				else
					$query .= " ".$join[$j]." JOIN `". $this->prefix[$i] ."_". $table_name ." ON ".$on[$j];
				$j ++;
			}
            $i ++;
		}

		$query = "SELECT " . $rows . " FROM " . $query ." ".$where." ".$sort;

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;
        
        unset($this->prefix);
        $this->prefix = LB_DB_PREFIX;
        
		return $this->query_id;
	}

	function update($rows, $table, $where = "")
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);
		
		if (strlen($where) > 1)
			$where = "WHERE ".$where;

		$query = "UPDATE `" . $this->prefix ."_". trim($table) ."` SET " . $rows . " ".$where;

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;
        $this->prefix = LB_DB_PREFIX;
        
		return $this->query_id;
	}

	function insert($rows, $table)
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);
		        
		$query = "INSERT INTO `" . $this->prefix ."_". trim($table) ."` SET " . $rows;

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;
        $this->prefix = LB_DB_PREFIX;

		return $this->query_id;
	}

	function delete($rows, $table)
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);
		        
		$query = "DELETE FROM `" . $this->prefix ."_". trim($table) ."` WHERE " . $rows;

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;
        $this->prefix = LB_DB_PREFIX;
        
		return $this->query_id;
	}

	function status($table)
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);

		$query = "SHOW TABLE STATUS FROM `". trim($table) ."`";

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;

		return $this->query_id;
	}

	function not_filtred($query)
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->access(LB_DB_USER, LB_DB_PASS, LB_DB_NAME, LB_DB_HOST);

		$this->check_error($query);
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 'num'   => (count($this->query_list) + 1));
        $this->query_list['query'][] = $query;
        
		$this->query_num ++;

		return $this->query_id;
	}
    
   	function one_not_filtred($query)
	{
  		$this->not_filtred($query);
		$data = $this->get_row();
		$this->free();			
		return $data;
	}

	function get_row($query_id = false)
	{
		if (!$query_id)
			$query_id = $this->query_id;

		return mysqli_fetch_assoc($query_id);
	}

	function get_array($query_id = false)
	{
		if (!$query_id)
			$query_id = $this->query_id;

		return mysqli_fetch_array($query_id);
	}
	
	
	function one_select($rows = "*", $table, $where = "", $sort = "")
	{
		$this->select($rows, $table, $where, $sort);
		$data = $this->get_row();
		$this->free();			
		return $data;
	}
    
   	function one_join_select($rows = "*", $join, $table, $on, $where = "", $sort = "")
	{
		$this->join_select($rows , $join, $table, $on, $where, $sort);
		$data = $this->get_row();
		$this->free();			
		return $data;
	}
	
	function num_rows($query_id = false)
	{
		if (!$query_id)
			$query_id = $this->query_id;

		return mysqli_num_rows($query_id);
	}
	
	function insert_id()
	{
		return mysqli_insert_id($this->db_id);
	}

	function get_result_fields($query_id = false)
	{
		if (!$query_id)
			$query_id = $this->query_id;

		while ($field = mysql_fetch_field($query_id))
		{
            		$fields[] = $field;
		}
		
		return $fields;
   	}

	function addslashes( $source )
	{
        return mysql_escape_string($source);
	}

	function free( $query_id = false )
	{
		if (!$query_id)
			$query_id = $this->query_id;

		@mysqli_free_result($query_id);
	}

	function close()
	{
		@mysqli_close($this->db_id);
	}

	function get_real_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float)$seconds + (float)$microSeconds);
	}	

	function message($error, $error_num, $query = '')
	{
		global $cache_config;

		if($query)
		{
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query);
		}

		unset($safehtml);

        if ($cache_config['security_mysql']['conf_value'] != "")
        {
      		$security_mysql = explode( "\r\n", $cache_config['security_mysql']['conf_value'] );
      		$show_error = false;
        
      		function check_ip($mask, $ip)
      		{
                $arr_mask = explode('.', $mask);
     			$arr_ip = explode('.', $ip);
        
                for($i=0;$i<=3;$i++)
     			{
                    if($arr_mask[$i] != '*')
    				{
				        if($arr_ip[$i] != $arr_mask[$i]) return false;
                    }
     			}
     			return true;
      		}
        
      		$check = false;
      		foreach ($security_mysql as $access)
      		{
     			$check = check_ip($access, $_SERVER['REMOTE_ADDR']);
     			if ($check) break;
      		}
                
            if (!$check AND $cache_config['security_mysqlpass']['conf_value'] != "" AND $cache_config['security_mysqlpass']['conf_value'] == $_GET['sql_error'])
            {
                $check = true;
            }
        }
        else
            $check = true;
            
        ########### Пишем лог ошибки БД - Start
        
        require_once LB_CLASS . '/safehtml.php';
		$safehtml = new safehtml( );
		$safehtml->protocolFiltering = "black";
        
		require_once LB_CLASS . '/safeinput.php';
		$safeinput = new safeinput;
		$safeinput->safeinput_check();
        
        $info_user = array ();
		$info_user['user_agent'] = $safehtml->parse($_SERVER['HTTP_USER_AGENT']);
		$info_user['file'] = $safehtml->parse($_SERVER['REQUEST_URI']);
		$info_user['request'] = $_REQUEST;
		$info_user = serialize($info_user);
    
  		$info_error = array ();
  		$info_error['error'] = $safehtml->parse($error);
  		$info_error['query'] = $safehtml->parse($query);
  		$info_error = serialize($info_error);
        
        unset($safehtml);
  		unset($safeinput);
        
        $info = array ();
        $info['info_user'] = $info_user;
        $info['time'] = time();
        $info['info_error'] = $info_error;
        $info['error_number'] = $error_num;
        $info['ip'] = $_SERVER['REMOTE_ADDR'];
        
        $file_log_size = intval(@filesize(LB_MAIN."/logs/logs_mysql.log"));
        
        if ($file_log_size > 0) $file_log_size = $file_log_size/1024; // получаем размер файла в кб
        
        if ($file_log_size < "2048")
        {
            if (file_exists(LB_MAIN."/logs/logs_mysql.log"))
            	$data = "|==|==|".serialize($info);
            else
            	$data = serialize($info);
    
            $save = fopen( LB_MAIN."/logs/logs_mysql.log", 'a' );
            fwrite( $save, $data );
            fclose( $save );
            @chmod( LB_MAIN."/logs/logs_mysql.log", 0644 );
        }
        
        ########### Пишем лог ошибки БД - End
            
		if ($check)
		{

echo <<<HTML
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MySQL Fatal Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-style: normal; color: #000000; }
</style>
</head>
<body>
<font size="4">MySQL Error!</font> 
<br />------------------------<br />
<br />	
<u>The Error returned was:</u> 
<br />
<strong>{$error}</strong>
<br /><br />
</strong><u>Error Number:</u> 
<br />
<strong>{$error_num}</strong>
<br /><br />
<textarea name="" rows="10" cols="52" wrap="virtual">
{$query}
</textarea>
</body>
</html>
HTML;
            exit();
		}
        else
		      exit("<div style=\"clear_left\"><b>MySQL Error!</b></div>");
	}

}


?>