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
    PUBLIC $number_pages = 0;

	function create($page = 0, $nav_all = 0, $number = 0, $link = 0, $max = 10)
	{
		//$page - текущая страница
		//$nav_all - всего записей в базе
		//$number - кол-во результатов на одну страницу
		//$link - ссылка
		//$max - количество ссылок на страниц, например 1,2,3,4,5,6,7,8,9,10 ... LAST
        
        $lang_c_navigation_board = language_forum ("board/class/navigation_board");

		if ($max < 2)
			$max = 3;

		if ($max%2 == 0)
			$max += 1;

		$this->clear();

		$this->number_pages = @ceil( $nav_all / $number );
		$this_page = intval($page / $number) + 1;

		if (!$this_page)
			$this_page = 1;

		if ($this_page > $this->number_pages)
		{
			$this->error = true;
		}

		if ($this->number_pages <= $max )
		{
			for($j=1; $j<=$this->number_pages; $j++)
			{
				if($j != $this_page)
					$this->result .= "<li><a href=\"".str_replace("{i_page}", $j, $link)."\">$j</a></li>";
				else
					$this->result .= "<li class=\"pag_active\"><span>$j</span></li>";
			}
		}
		else
		{
			$start = 1;
			$end = $max;
			$half_max = intval($max/2);
			$points = "<li>...</li>";

			if ($this_page > $half_max)
			{
				$start = $this_page - $half_max;
				$end = $this_page + $half_max;
				if ($end == $this->number_pages)
				{
					//$end = $this->number_pages - 1;
				}
				elseif ($end > $this->number_pages)
				{
					$end = $this->number_pages;
					$start = ($end - $max) + 1;
				}
			}

			if ($this_page > $this->number_pages)
			{
				$start = $this->number_pages - $max;
				$end = $this->number_pages - 1;
			}

			if ($start > 1 AND ($start - 1) == 1)
				$this->result .= "<li><a href=\"".str_replace("{i_page}", "1", $link)."\">1</a></li>";
            elseif ($start > 1)
                $this->result .= "<li><a href=\"".str_replace("{i_page}", "1", $link)."\">1</a></li>".$points;

			for($j=$start; $j<=$end; $j++)
			{
				if($j != $this_page)
					$this->result .= "<li><a href=\"".str_replace("{i_page}", $j, $link)."\">$j</a></li>";
				else
					$this->result .= "<li class=\"pag_active\"><span>$j</span></li>";
			}

			if ($end < ($this->number_pages - 1))
				$this->result .= $points;

			if ($end != $this->number_pages)
				$this->result .= "<li><a href=\"".str_replace("{i_page}", $this->number_pages, $link)."\">".$this->number_pages."</a></li>";
		}

		if($page > 0)
		{
			$prev = $this_page - 1;
			$prev_link = "<li class=\"pag_back\"><a href=\"".str_replace("{i_page}", $prev, $link)."\">".$lang_c_navigation_board['back']."</a></li>";
			$this->prev_page = true;
		}

   		if($this_page < $this->number_pages)
		{
			$next = $this_page + 1;
			$next_link = "<li class=\"pag_next\"><a href=\"".str_replace("{i_page}", $next, $link)."\">".$lang_c_navigation_board['next']."</a></li>";
			$this->next_page = true;
		}

		if ($this->prev_page AND $this->next_page)
			$this->result = $prev_link.$this->result.$next_link;
		elseif ($this->prev_page)
			$this->result = $prev_link.$this->result;
		elseif ($this->next_page)
			$this->result = $this->result.$next_link;
    }
    
    function create_topic($nav_all = 0, $number = 0, $link = 0, $max = 10)
	{        
        $lang_c_navigation_board = language_forum ("board/class/navigation_board");

		if ($max < 2)
			$max = 3;

		if ($max%2 == 0)
			$max += 1;

		$this->clear();
        $this->result = array();

		$this->number_pages = @ceil( $nav_all / $number );

		if ($this->number_pages <= $max )
		{
			for($j=1; $j<=$this->number_pages; $j++)
			{
				$this->result[] = "<a href=\"".str_replace("{i_page}", $j, $link)."\">$j</a>";
			}
		}
		else
		{
			$start = 1;
			$end = $max;
			$half_max = intval($max/2);
			$points = "...";

			if ($start > 1 AND ($start - 1) == 1)
				$this->result[] = "<a href=\"".str_replace("{i_page}", "1", $link)."\">1</a>";
            elseif ($start > 1)
                $this->result[] = "<a href=\"".str_replace("{i_page}", "1", $link)."\">1</a>".$points;

			for($j=$start; $j<=$end; $j++)
			{
				$this->result[] = "<a href=\"".str_replace("{i_page}", $j, $link)."\">$j</a>";
			}

			if ($end < ($this->number_pages - 1))
				$this->result[] = $points;

			if ($end != $this->number_pages)
				$this->result[] = "<a href=\"".str_replace("{i_page}", $this->number_pages, $link)."\">".$this->number_pages."</a>";
		}
        
        $this->result = implode(", ", $this->result);
    }

    function template($name = "navigation")
	{
        global $tpl;
       
        $tpl->load_template ( $name.'.tpl' );
        $tpl->tags('{number_pages}', $this->number_pages);
        
        if ($this->number_pages > 7) $tpl->tags_blocks("page_box");
        else $tpl->tags_blocks("page_box", false);
            
        $tpl->tags('{pages}', $this->result);
        $tpl->compile($name);
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