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

class mq_gpc
{
	function del_slashes()
	{
		if (is_array( $_POST ))
		{
			foreach ($_POST as $key => $value)
			{
				if (is_array( $_POST[$key] ))
					$_POST[$key] = $this->massive($value);	
                else
					$_POST[$key] = stripslashes($value);
			}
		}

		if (is_array( $_GET ))
		{
			foreach ($_GET as $key => $value)
			{
				if (is_array( $_GET[$key] ))
					$_GET[$key] = $this->massive($value);
				else
					$_GET[$key] = stripslashes($value);
			}
		}

		if (is_array( $_COOKIE ))
		{
			foreach ($_COOKIE as $key => $value)
			{
				if (is_array( $_COOKIE[$key] ))
					$_COOKIE[$key] = $this->massive($value);
				else
					$_COOKIE[$key] = stripslashes($value);
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
				$data[$key] = stripslashes($value);
		}
        return $data;
	}
}

?>