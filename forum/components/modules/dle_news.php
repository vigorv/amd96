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
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$block_news = "";

if ($cache_config['blockmod_dle_topnews']['conf_value'])
{  
    $cache_dle_cat_info = $cache->take("cat_info", "", "dle_modules");

    if (!is_array($cache_dle_cat_info))
    {
        $cache_dle_cat_info = array ();
       	
        $DB->prefix = DLE_PREFIX;
        $DB->select( "id, parentid, posi, name, alt_name, news_number", "category", "", "ORDER BY posi ASC" );
       	while ( $row = $DB->get_row () )
        {		
      		$cache_dle_cat_info[$row['id']] = array ();
      		foreach ( $row as $key => $value )
            {
     			$cache_dle_cat_info[$row['id']][$key] = stripslashes($value);
      		}
       	}
        $cache->take("cat_info", $cache_dle_cat_info, "dle_modules");
       	$DB->free ();
    }
    
    $num = intval($cache_config['blockmod_dle_topnews_num']['conf_value']);
    if ($num <= 0 OR $num > 100) $num = 7;
    
    $type_sort = intval($cache_config['blockmod_dle_topnews_sort']['conf_value']);
        
    $tpl->result['block_news'] = "";
    if ($type_sort != 7) $tpl->result['block_news'] = $cache->take("dle_news_".$type_sort."_".totranslit($cache_config['template_name']['conf_value']), "", "dle_modules", 1);
    
    if( !$tpl->result['block_news'] )
    {
    	$this_month = date( 'Y-m-d H:i:s', $time );
    	
        $order = "ORDER BY date DESC";
        
        $where = array();
        $where[] = "approve='1'";
        
        if ($type_sort == "1") // ��� �� ����� �� ��������
        {
            $where[] = "date >= '$this_month' - INTERVAL 1 MONTH AND date < '$this_month'";
            $order = "ORDER BY rating DESC";
        }
        elseif ($type_sort == "2") // ��� �� ����� �� ����������
        {
            $where[] = "date >= '$this_month' - INTERVAL 1 MONTH AND date < '$this_month'";
            $order = "ORDER BY news_read DESC";
        }
        elseif ($type_sort == "3") // ��� �� ����� �� ������������
        {
            $where[] = "date >= '$this_month' - INTERVAL 1 MONTH AND date < '$this_month'";
            $order = "ORDER BY comm_num DESC";
        }
        elseif ($type_sort == "4") // ��� �� �� ����� �� ��������
        {
            $order = "ORDER BY rating DESC";
        }
        elseif ($type_sort == "5") // ��� �� �� ����� �� ����������
        {
            $order = "ORDER BY news_read DESC";
        }
        elseif ($type_sort == "6") // ��� �� �� ����� �� ������������
        {
            $order = "ORDER BY comm_num DESC";
        }
        elseif ($type_sort == "7") // ��������� ������
        {
            $order = "ORDER BY RAND() DESC";
        }

        $where[] = "date < '" . date ( "Y-m-d H:i:s", $time ) . "'";
      
        $where = implode (" AND ", $where);
        $DB->prefix = DLE_PREFIX;
        
        // DLE 9.4 � ����: flag 
        $DB->select( "id, title, date, alt_name, category, short_story, xfields", "post", $where, $order." LIMIT 0,".$num );
        
        $xfields_find = ""; // �������� ��������������� ����, �� �������� �� ������ ������� ��������, ��� ���������� ������ �������� ������ �������� ������ ��������: ""
        $tpl->load_template ( 'block_news_dle.tpl' );
    	while ( $row = $DB->get_row() )
        {
            $url_is_find = false;
    		$row['date'] = strtotime( $row['date'] );
    		$row['category'] = intval( $row['category'] );
                        
            if( $cache_config['dle_allow_alt_url']['conf_value'] )
            {
    			if( $cache_config['dle_seo_type']['conf_value'] == 1 OR $cache_config['dle_seo_type']['conf_value'] == 2  )
                {
    				if( $row['category'] and $cache_config['dle_seo_type']['conf_value'] == 2 )
    					$full_link = $cache_config['general_site_dle']['conf_value'] . get_dle_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
    				else
    					$full_link = $cache_config['general_site_dle']['conf_value'] . $row['id'] . "-" . $row['alt_name'] . ".html";    			
    			}
                else
    				$full_link = $cache_config['general_site_dle']['conf_value'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
    		}
            else
    			$full_link = $cache_config['general_site_dle']['conf_value'] . "index.php?newsid=" . $row['id'];
                
            /*
            DLE 9.4 � ����
    		if( $cache_config['dle_allow_alt_url']['conf_value'] )
            {
    			if( $row['flag'] and $cache_config['dle_seo_type']['conf_value'] )
                {
    				if( $row['category'] and $cache_config['dle_seo_type']['conf_value'] == 2 )					
    					$full_link = $cache_config['general_site_dle']['conf_value'] . get_dle_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
    				else
                        $full_link = $cache_config['general_site_dle']['conf_value'] . $row['id'] . "-" . $row['alt_name'] . ".html";			
    			}
                else
                    $full_link = $cache_config['general_site_dle']['conf_value'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";		
    		}
            else
    			$full_link = $cache_config['general_site_dle']['conf_value'] . "index.php?newsid=" . $row['id'];
    		*/
            
    		if( utf8_strlen( $row['title'] ) > 55 )
                $title = utf8_substr( $row['title'], 0, 55 ) . " ...";
    		else
                $title = $row['title'];
                
            $tpl->tags('{title}', stripslashes( $title ));
            $tpl->tags('{link_title}', htmlspecialchars(stripslashes($row['title'])));
            $tpl->tags('{link_news}', $full_link);
    		            
            if ($cache_config['blockmod_dle_topnews_pic']['conf_value'])
    		{
                // ����� �������� �� ��������������� ����.
                if ($xfields_find AND $row['xfields'])
                {
                    $xfieldsdata = explode( "||", $row['xfields'] );
                    $data_xfield = array();
                   	foreach ( $xfieldsdata as $xfielddata )
                    {
                  		list ( $xfielddataname, $xfielddatavalue ) = explode( "|", $xfielddata );
                  		$xfielddataname = str_replace( "&#124;", "|", $xfielddataname );
                  		$xfielddataname = str_replace( "__NEWL__", "\r\n", $xfielddataname );
                  		$xfielddatavalue = str_replace( "&#124;", "|", $xfielddatavalue );
                  		$xfielddatavalue = str_replace( "__NEWL__", "\r\n", $xfielddatavalue );
                  		$data_xfield[$xfielddataname] = $xfielddatavalue;
                        if ($xfielddataname == $xfields_find) break;
                   	}  
                            
                    if ($data_xfield[$xfields_find])
                    {
                        $url_is_find = true;
                        $tpl->tags('{img_link}', $data_xfield[$xfields_find]);
                        $tpl->tags_blocks("img_news");
                    }
                }  
                
                $image == "";
                if (!$url_is_find)
                {
                    preg_match("#<img src=[\"'](\S+?)['\"].+?>#i", stripslashes($row['short_story']), $image);
                    $image = $image[1];
                } 
                               
                if ($image != "")
                {
                    /*
                    // ����� ������� � ����������� ��������.
                    $link = "<a href=\"".$image."\" onclick=\"return hs.expand(this)\" title=\"��������� ��������\"><img src=\"".$image."\" width=\"40\" height=\"40\" class='lb_img' alt=\"�������\" /></a>".$link." ";
                    $url_is_find = true;
                    */
                    
                    $tpl->tags('{img_link}', $image);
                    $tpl->tags_blocks("img_news");
                }
                else
                    $tpl->tags_blocks("img_news", false);
    		}
            else
                $tpl->tags_blocks("img_news", false);
                
            $tpl->tags('{date}', formatdate($row['date']));
            $tpl->compile('block_news');
    	}
    	$tpl->clear();
    	$DB->free();
    
        if ($type_sort != 7) $cache->take("dle_news_".$type_sort."_".totranslit($cache_config['template_name']['conf_value']), $tpl->result['block_news'], "dle_modules");
    }
    
    $block_news = $tpl->result['block_news'];
}

?>