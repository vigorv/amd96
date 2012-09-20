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

class lb_cache
{
	PUBLIC $time = '24';
	PUBLIC $dir = LB_MAIN;
	PUBLIC $content;
    PUBLIC $time_taken = 0;
    PUBLIC $cache_list = array();
	PRIVATE $type = 0;

	function get_real_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float)$seconds + (float)$microSeconds);
	}

	function take ($name, $data = "", $dir = "", $time = 0)
	{
        $time_before = $this->get_real_time();
		$this->newdata();
		if ($time AND $time != "no")
			$this->time = $time;
            
        if ($time != "no")
            $this->time = $this->time * 3600;

		if ($dir)
		{
			$this->dir = $this->dir."/cache/".$dir;
			if (! is_dir ( $this->dir ))
			{
				@mkdir ( $this->dir, 0777 );
				@chmod ( $this->dir, 0777 );

				if (!file_exists($this->dir."/.htaccess"))
				{
$htacc = "Order Deny,Allow

Deny from all";
				$file_cache = fopen( $this->dir."/.htaccess", 'wb+' );
				fwrite( $file_cache, $htacc );
				fclose( $file_cache );
				@chmod( $this->dir."/.htaccess", 0644 );
				}
			}
		}
		else
			$this->dir = $this->dir."/cache";

		$this->dir = $this->dir."/".$name.".php";

		if(!$name)
			return false;
		if (!$data AND (!file_exists($this->dir) OR ((time() - filemtime($this->dir)) > $this->time) AND $time != "no"))
			return false;
		
        if ($data)
		{
			$this->update($name, $data, $dir);
		}

		$this->content = @file_get_contents( $this->dir );
		
		if (substr($this->content, 0, 7) == "1|TYPE|")
			$this->content = unserialize(substr($this->content, 7));
		else
			$this->content = substr($this->content, 7);

        $this->time_taken += $this->get_real_time() - $time_before;
        $this->cache_list[] = $this->dir;

		return $this->content;
	}

	function update ($name, $data_up = "", $dir_up = "")
	{
		$this->newdata();

		if ($dir_up)
		{
			$this->dir = $this->dir."/cache/".$dir_up;
			if (! is_dir ( $this->dir ))
			{
				@mkdir ( $this->dir, 0777 );
				@chmod ( $this->dir, 0777 );
				if (!file_exists($this->dir."/.htaccess"))
				{
$htacc = "Order Deny,Allow

Deny from all";
				$file_cache = fopen( $this->dir."/.htaccess", 'wb+' );
				fwrite( $file_cache, $htacc );
				fclose( $file_cache );
				@chmod( $this->dir."/.htaccess", 0644 );
				}
			}
		}
		else
			$this->dir = $this->dir."/cache";

		if(!$name)
			return false;
		if (!$data_up)
			return false;

		if ( is_array($data_up) )
		{
			$data_up = serialize( $data_up );
			$this->type = 1;
		}
		else
			$this->type = 0;

		$this->dir = $this->dir."/".$name.".php";

		$data_up = $this->type."|TYPE|".$data_up;

		$file_cache = fopen( $this->dir, 'wb+' );
		fwrite( $file_cache, $data_up );
		fclose( $file_cache );
		@chmod( $this->dir, 0666 );
	}

	function newdata()
	{
		$this->time = '24';
		$this->dir = LB_MAIN;
		$this->content = "";
		$this->type = 0;
	}

	function clear ($dir = "", $name = "")
	{
		$this->newdata();

		if ($dir)
			$this->dir = LB_MAIN."/cache/".$dir;
		else
			$this->dir = $this->dir."/cache";

		$fdir = opendir( $this->dir );
	
		while ( $file = readdir( $fdir ) )
		{
			if( $file != "." AND $file != ".." AND $file != ".htaccess")
			{
				if( $name )
				{
					if( strpos( $file, $name ) !== false )
						@unlink($this->dir . '/' . $file );
				}
				else
					@unlink( $this->dir . '/' . $file );
			}
		}
	}
}

?>