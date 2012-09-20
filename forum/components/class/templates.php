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

class LB_Template
{
	PUBLIC $dir = '.';
	PUBLIC $template = null;
	PUBLIC $copy_template = null;
	PUBLIC $data = array ();
    PUBLIC $data_template = array ();
	PUBLIC $tpl_out = array ();
	PUBLIC $block_data = array ();
	PUBLIC $result = array ('message' => '', 'speedbar' => '', 'content' => '' );
	PUBLIC $template_parse_time = 0;
	
	function tags($name, $var)
	{
		if( is_array( $var ) && count( $var ) )
		{
			foreach ( $var as $key => $key_var )
			{
				$this->tags( $key, $key_var );
			}
		}
		else
			$this->data[$name] = $var;
	}
    
    function tags_templ($name, $var)
	{
		if( is_array( $var ) && count( $var ) )
		{
			foreach ( $var as $key => $key_var )
			{
				$this->tags_templ( $key, $key_var );
			}
		}
		else
			$this->data_template[$name] = $var;
	}
    
    function tags_blocks($name = "", $opt = true, $opposide = false)
	{
		if (($opt AND !$opposide) OR (!$opt AND $opposide))
        {
            $this->tags( '['.$name.']', "" );
            $this->tags( '[/'.$name.']', "" ); 
        }
        else
            $this->block( "'\\[".$name."\\].*?\\[/".$name."\\]'si", "" );
	}
    	
    function global_tags($template)
	{
        global $cache_config, $secret_key;
                
		$this->result[$template] = str_replace ( '{TEMPLATE}', $cache_config['general_site']['conf_value'] . 'templates/'.$cache_config['template_name']['conf_value'], $this->result[$template] );
        $this->result[$template] = str_replace ( '{TEMPLATE_NAME}', $cache_config['template_name']['conf_value'], $this->result[$template] );
        $this->result[$template] = str_replace ( '{HOME_LINK}', $cache_config['general_site']['conf_value'], $this->result[$template] );
        $this->result[$template] = str_replace ( '{DLE_LINK}', $cache_config['general_site_dle']['conf_value'], $this->result[$template] );
        $this->result[$template] = str_replace ( '{SECRET_KEY}', $secret_key, $this->result[$template] );
    }
        	
	function block($name, $var)
	{
		if( is_array( $var ) && count( $var ) )
		{
			foreach ( $var as $key => $key_var )
			{
				$this->block( $key, $key_var );
			}
		}
		else
			$this->block_data[$name] = $var;
	}

	function load_template($tpl_name)
	{
        global $cache_config, $LB_charset;
        
		$time_before = $this->get_real_time();
		
		if( $tpl_name == '' OR ! file_exists( $this->dir . DIRECTORY_SEPARATOR . $tpl_name ) )
		{
            header( "Content-type: text/css; charset=".$LB_charset );
			exit( "Невозможно загрузить шаблон: " . $tpl_name );
			return false;
		}

		$this->template = file_get_contents( $this->dir . DIRECTORY_SEPARATOR . $tpl_name );
        
        if ($cache_config['general_coding']['conf_value'] == "utf-8")
        {
            $this->template = mb_convert_encoding($this->template, "UTF-8", "windows-1251");
        }
	
		if (strpos ( $this->template, "[global_group=" ) !== false)
        {
			$this->template = preg_replace ( "#\\[global_group=(.+?)\\](.*?)\\[/global_group\\]#ies", "\$this->check_group('\\1', '\\2')", $this->template );
		}
		if (strpos ( $this->template, "[global_not_group=" ) !== false)
        {
			$this->template = preg_replace ( "#\\[global_not_group=(.+?)\\](.*?)\\[/global_not_group\\]#ies", "\$this->check_group('\\1', '\\2', false)", $this->template );
		}
        
		$this->copy_template = $this->template;
        
        $this->tpl_out['templates'][] = $tpl_name;
        
		$this->template_parse_time += $this->get_real_time() - $time_before;
		return true;
	}

	function check_group($groups, $block, $available = true)
	{
		global $member_id;
		
		$groups = explode( ',', $groups );
		if( $available )
        {
			if(!in_array($member_id['user_group'], $groups))
                return "";
		}
        else
        {
			if(in_array($member_id['user_group'], $groups))
                return "";
		}
		$block = str_replace( '\"', '"', $block );
		
		return $block;
	}
	
	function _clear()
	{
		$this->data = array ();
        $this->data_template = array ();
		$this->block_data = array ();
		$this->copy_template = $this->template;
	}
	
	function clear()
	{
		$this->data = array ();
        $this->data_template = array ();
		$this->block_data = array ();
		$this->copy_template = null;
		$this->template = null;
	}
	
	function global_clear()
	{
		$this->data = array ();
        $this->data_template = array ();
		$this->block_data = array ();
		$this->result = array ();
		$this->copy_template = null;
		$this->template = null;
	}
	
	function compile($tpl)
	{
	    global $pravAvtota, $cache_config, $cache_group, $member_id;
		$time_before = $this->get_real_time();

        $find = array();
        $replace = array();

		if( count( $this->block_data ) )
		{
			foreach ( $this->block_data as $key_find => $key_replace )
			{
				$find_preg[] = $key_find;
				$replace_preg[] = $key_replace;
			}
			$this->copy_template = preg_replace( $find_preg, $replace_preg, $this->copy_template );
		}
		
        $find_t = array();
		$replace_t = array();
        
        if (count($this->data_template))
        {            
            foreach ( $this->data_template as $key_find => $key_replace )
    		{
                $tag_name = "templ".rand(1, 100000)."ate";
                $find_t[] = $tag_name;
                $replace_t[] = $key_replace;               
                $this->copy_template = str_replace( $key_find, $tag_name, $this->copy_template );
    		}
        }
        
		foreach ( $this->data as $key_find => $key_replace )
		{
			$find[] = $key_find;
			$replace[] = $key_replace;
		}
        
        $this->copy_template = str_replace( $find, $replace, $this->copy_template );
        
        if (count($find_t)) $this->copy_template = str_replace( $find_t, $replace_t, $this->copy_template );
                                                               
/* Copyright removed */
 if ($tpl == "global_template" AND (($cache_config['general_close']['conf_value'] AND $cache_group[$member['member_group']]['g_show_close_f'] == 1) OR !$cache_config['general_close']['conf_value']))
 {
 $this->copy_template = str_replace( "{copyright}", $pravAvtota, $this->copy_template );
 }


 if( isset( $this->result[$tpl] ) )
 $this->result[$tpl] .= $this->copy_template;
 else
 $this->result[$tpl] = $this->copy_template;
 
 		        
        $this->tpl_out['compile'][] = $tpl;
                
		$this->_clear();
		
		$this->template_parse_time += $this->get_real_time() - $time_before;
	}
    
	function get_real_time()
	{
		list ( $seconds, $microSeconds ) = explode( ' ', microtime() );
		return (( float ) $seconds + ( float ) $microSeconds);
	}
}

?>