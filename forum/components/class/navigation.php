<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

class navigation
{
	PUBLIC $prev_page = false;
	PUBLIC $next_page = false;
	PUBLIC $result = '';
	PUBLIC $error = false;

	function creat($page = 0, $nav_all = 0, $number = 0, $link = 0, $max = 10)
	{
		//$page - текущая страница
		//$nav_all - всего записей в базе
		//$number - кол-во результатов на одну страницу
		//$link - ссылка
		//$max - количество ссылок на страниц, например 1,2,3,4,5,6,7,8,9,10 ... LAST

		if ($max < 2)
			$max = 3;

		if ($max%2 == 0)
			$max += 1;

		$this->clear();

		$number_pages = @ceil( $nav_all / $number );
		$this_page = intval($page / $number) + 1;

		if (!$this_page)
			$this_page = 1;

		if ($this_page > $number_pages)
		{
			$this->error = true;
		}

		if ($number_pages <= $max )
		{
			for($j=1; $j<=$number_pages; $j++)
			{
				if($j != $this_page)
					$this->result .= "<a href=\"".$link.$j."\">$j</a> ";
				else
					$this->result .= "<b>$j</b> ";
			}
		}
		else
		{
			$start = 1;
			$end = $max;
			$half_max = intval($max/2);
			$points = " ... ";

			if ($this_page > $half_max)
			{
				$start = $this_page - $half_max;
				$end = $this_page + $half_max;
				if ($end == $number_pages)
				{
					//$end = $number_pages - 1;
				}
				elseif ($end > $number_pages)
				{
					$end = $number_pages;
					$start = ($end - $max) + 1;
				}
			}

			if ($this_page > $number_pages)
			{
				$start = $number_pages - $max;
				$end = $number_pages - 1;
			}

			if ($start > 1)
				$this->result .= "<a href=\"".$link."1\">1</a>".$points;

			for($j=$start; $j<=$end; $j++)
			{
				if($j != $this_page)
					$this->result .= "<a href=\"".$link.$j."\">$j</a> ";
				else
					$this->result .= "<b>$j</b> ";
			}

			if ($end < $number_pages)
				$this->result .= $points;

			if ($end != $number_pages)
				$this->result .= "<a href=\"".$link.$number_pages."\">".$number_pages."</a>";
		}

		if($page > 0)
		{
			$prev = $this_page - 1;
			$prev_link = "<a href=\"".$link.$prev."\">Назад</a> ";
			$this->prev_page = true;
		}

   		if($this_page < $number_pages)
		{
			$next = $this_page + 1;
			$next_link = " <a href=\"".$link.$next."\">Дальше</a>";
			$this->next_page = true;
		}

		if ($this->prev_page AND $this->next_page)
			$this->result = $prev_link.$this->result.$next_link;
		elseif ($this->prev_page)
			$this->result = $prev_link.$this->result;
		elseif ($this->next_page)
			$this->result = $this->result.$next_link;

	}

	function clear()
	{
		$this->prev_page = false;
		$this->next_page = false;
		$this->result = '';
		$this->error = false;
	}
}

?>