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

class safeinput extends SafeHTML
{
	function safeinput_check($check = 'all')
	{
		global $safehtml;
         
        $post_check = false;
        $get_check = false;
        $request_check = false;
        $coockie_check = false;
        $session_check = false;
        $server_check = false;
                
        if($check == "all")
        {
            $post_check = true;
            $get_check = true;
            $request_check = true;
            $coockie_check = true;
            $session_check = true;
            $server_check = true;
        }
        else
        {
            $check_mass = explode("|", $check);
            foreach ($check_mass as $cm)
            {
                if($cm == 'coockie') $coockie_check = true;
                if($cm == 'session') $session_check = true;
                if($cm == 'post') $post_check = true;
                if($cm == 'get') $get_check = true;
                if($cm == 'request') $request_check = true;
                if($cm == 'server') $server_check = true;
            }
        }
        
		if (is_array( $_POST ) AND $post_check)
		{
			foreach ($_POST as $key => $value)
			{
				if (is_array( $_POST[$key] ))
					$_POST[$key] = $this->massive($value);	
                else
					$_POST[$key] = SafeHTML::parse(trim($value));
			}
		}

		if (is_array( $_GET ) AND $get_check)
		{
			foreach ($_GET as $key => $value)
			{
				if (is_array( $_GET[$key] ))
					$_GET[$key] = $this->massive($value);
				else
					$_GET[$key] = SafeHTML::parse(trim($value));
			}
		}

		if (is_array( $_REQUEST ) AND $request_check)
		{
			foreach ($_REQUEST as $key => $value)
			{
				if (is_array( $_REQUEST[$key] ))
					$_REQUEST[$key] = $this->massive($value);
				else
					$_REQUEST[$key] = SafeHTML::parse(trim($value));
			}
		}

		if (is_array( $_COOKIE ) AND $coockie_check)
		{
			foreach ($_COOKIE as $key => $value)
			{
				if (is_array( $_COOKIE[$key] ))
					$_COOKIE[$key] = $this->massive($value);
				else
					$_COOKIE[$key] = SafeHTML::parse(trim($value));
			}
		}

		if (is_array( $_SESSION ) AND $session_check)
		{
			foreach ($_SESSION as $key => $value)
			{
				if (is_array( $_SESSION[$key] ))
					$_SESSION[$key] = $this->massive($value);
				else
					$_SESSION[$key] = SafeHTML::parse(trim($value));
			}
		}
        
        if (is_array( $_SERVER ) AND $server_check)
		{
			foreach ($_SERVER as $key => $value)
			{
				if (is_array( $_SERVER[$key] ))
					$_SERVER[$key] = $this->massive($value);	
                else
					$_SERVER[$key] = SafeHTML::parse(trim($value));
			}
		}
	}

	function massive($data)
	{
		foreach ($data as $key => $value)
		{
			if (is_array( $data[$key] ))
				$data[$key] = $this->massive($value);
			else
				$data[$key] = SafeHTML::parse(trim($value));
		}
        return $data;
	}
}

?>