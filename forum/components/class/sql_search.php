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

class SQL_Search
{
	PUBLIC $massive = array();
	PUBLIC $query = '';
	PRIVATE $not_like = 'NOT LIKE';

	function simple ($row, $word, $type = 0, $not_like = false)
	{
		$this->clear();

		if ($type < 0 OR $type > 3)
			$type = 0;

		if (!$not_like)
			$this->not_like = "LIKE";

		if (is_array($word))
		{
			$word = explode("|", $word);
			foreach ($word as $search)
			{
				if ($type == 0)
					$this->massive[] = "(".$row." ".$this->not_like." '%".$search."%')";
				elseif ($type == 1)
					$this->massive[] = "(".$row." ".$this->not_like." '".$search."%')";
				elseif ($type == 2)
					$this->massive[] = "(".$row." ".$this->not_like." '%".$search."')";
				elseif ($type == 3)
					$this->massive[] = "(".$row." = '".$search."')";
			}

			$this->query = implode (" OR ", $this->massive);
		}
		else
		{
			if ($type == 0)
				$this->query = $row." ".$this->not_like." '%".$word."%'";
			elseif ($type == 1)
				$this->query = $row." ".$this->not_like." '".$word."%'";
			elseif ($type == 2)
				$this->query = $row." ".$this->not_like." '%".$word."'";
			elseif ($type == 3)
				$this->query = $row." = '".$word."'";
		}

		return $this->query;
	}

	function regexp ($row, $word)
	{
		$this->clear();

		$this->query = $row." REGEXP '".$word."'";

		return $this->query;
	}

	function regexp_ip ($ip, $row = "ip")
	{
		$this->clear();

   		$ip = explode('.', $ip);
		$ip_c = count($ip);

		if (!$ip[0])
			return;

    		for($i=0;$i<=3;$i++)
		{
			if ($ip[$i])
			{
        			if($ip[$i] == '*')
					$this->massive[] = "([0-9]|[0-9][0-9]|[0-9][0-9][0-9])*";
				else
					$this->massive[] = $ip[$i];
			}
			else
				$this->massive[] = "([0-9]|[0-9][0-9]|[0-9][0-9][0-9])*";
    		}
		$this->query = implode (".", $this->massive);
		$this->query = $row." REGEXP \"^".$this->query."$\"";

		return $this->query;
	}

	function clear()
	{
		$this->query = '';
		$this->massive = array ();
		$this->not_like = 'NOT LIKE';
	}
}

?>