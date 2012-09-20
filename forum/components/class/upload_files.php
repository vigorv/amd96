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

class LB_Upload
{
    PRIVATE $dir_name = "attachment";
	PRIVATE $allow = 1;
    PRIVATE $extensions_allowed = "zip, rar, jpg, jpeg, gif, png, txt, pdf, doc";
	PRIVATE $extensions_notallowed = "phtml, php, php3, php4, php5, php6, phps, cgi, pl, asp, aspx, tpl, jsp";
    PRIVATE $file_size = 30720;
    PRIVATE $picture_size = 1024;
    PRIVATE $download_user = 5;
    PRIVATE $download_speed = 0;
    PRIVATE $download_counter = 1;

	function Uploading ($fid = 0, $tid = 0, $pid = 0)
	{
        global $DB, $member_id, $logged, $time, $cache_group, $cache_config;

        $picture_types = array("jpg", "jpeg", "png", "gif", "bmp", "tif", "ico");

        $lang_c_upload_files = language_forum ("board/class/upload_files");

        if (!$logged) return $lang_c_upload_files['not_logged'];
        if (!$fid) return $lang_c_upload_files['no_forum_id'];

        $this->Check_Data();
        
        if (!$this->allow) return $lang_c_upload_files['upload_off'];
        
        $name_input = "attachment";
        $count = count($_FILES[$name_input]['tmp_name']);   // Инпут должен быть массивом
        
        for ($i=0; $i<=($count-1); $i++)
        {
            $prefix = time() + rand( 1, 100 ) + $i;
    		$prefix .= "_";
            
    		$upl_tmp_name = $_FILES[$name_input]['tmp_name'][$i];
            
            if (!$upl_tmp_name) continue;
            
    		$upl_name = $_FILES[$name_input]['name'][$i];
    		$upl_size = $_FILES[$name_input]['size'][$i];
    		$upl_code = $_FILES[$name_input]['error'][$i];
            
            $upl_name_mas = explode (".", $upl_name);
            $upl_name_type = end ($upl_name_mas);
            
            if (!forum_permission($fid, "upload_files"))return str_replace("{group}", $cache_group[$member_id['user_group']]['g_title'], $lang_c_upload_files['access_denied_group']);
      
            if (in_array(strtolower($upl_name_type), $picture_types))   // Если файл является картинкой
            {
                if ($upl_size > $this->picture_size AND $this->picture_size) return str_replace("{size}", $this->picture_size/1024, $lang_c_upload_files['max_size']);
            }
            else
            {
                if ($upl_size > $this->file_size AND $this->file_size) return str_replace("{size}", $this->file_size/1024, $lang_c_upload_files['max_size']);
            }
            
            $error_code = "";
            
            if ($upl_code !== UPLOAD_ERR_OK)
            {
                switch ($upl_code)
                {
                    case UPLOAD_ERR_INI_SIZE: 
                        $error_code = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                        break;
                    case UPLOAD_ERR_FORM_SIZE: 
                        $error_code = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                        break;
                    case UPLOAD_ERR_PARTIAL: 
                        $error_code = 'The uploaded file was only partially uploaded';
                        break;
                    case UPLOAD_ERR_NO_FILE: 
                        $error_code = 'No file was uploaded'; 
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR: 
                        $error_code = 'Missing a PHP temporary folder';
                        break;
                    case UPLOAD_ERR_CANT_WRITE: 
                        $error_code = 'Failed to write file to disk';
                        break;
                    case UPLOAD_ERR_EXTENSION: 
                        $error_code = 'File upload stopped by extension';
                        break;
                    default: 
                        $error_code = 'Unknown upload error';
                        break;
                } 
            }
            
            if ($error_code) return $error_code;    // Если во время загрузки файла возникла ошибка со стороны сервера
            if ($upl_name_type == "") return $lang_c_upload_files['no_file_extension']; // Если не удалось определить расширение файла
            if (!in_array(strtolower($upl_name_type), $this->extensions_allowed)) return str_replace("{type}", $upl_name_type, $lang_c_upload_files['denied_file_extension']);   // Если расширения файла нет в списке разрешённых
            if (in_array(strtolower($upl_name_type), $this->extensions_notallowed)) return str_replace("{type}", $upl_name_type, $lang_c_upload_files['denied_file_extension']); // Если расширение файла есть в списке запрещённых расширений
                
            $upl_name = $this->Clear(totranslit(implode(".", $upl_name_mas)));
            $upl_name_db = $DB->addslashes($this->Clear(implode(".", $upl_name_mas)));
            
            define( 'FOLDER_PREFIX', date( "Y-m" )."/" );
            
            if(!is_dir( LB_UPLOADS . "/attachment/" . FOLDER_PREFIX ))
            {
    			@mkdir( LB_UPLOADS . "/attachment/" . FOLDER_PREFIX, 0777 );
    			@chmod( LB_UPLOADS . "/attachment/" . FOLDER_PREFIX, 0777 );
    		}
    		
    		if(!is_dir( LB_UPLOADS . "/attachment/" . FOLDER_PREFIX )) return str_replace("{folder}", FOLDER_PREFIX, $lang_c_upload_files['create_folder']);      // Если не удалось создать папку
    		if(!is_writable( LB_UPLOADS . "/attachment/" . FOLDER_PREFIX )) return str_replace("{folder}", FOLDER_PREFIX, $lang_c_upload_files['denied_folder']); // Если не удалось настроить права записи на папку
                
            $upload_dir_name = LB_UPLOADS . "/attachment/" . FOLDER_PREFIX;
            
            $error = "";
            
            @move_uploaded_file( $upl_tmp_name, $upload_dir_name.$prefix.$upl_name ) OR $error = $lang_c_upload_files['download_error'];
            
            if(@file_exists($upload_dir_name.$prefix.$upl_name))
            {
                $real_size = intval(@filesize($upload_dir_name.$prefix.$upl_name)); // Определяем размер файла на сервере
                
                if (in_array(strtolower($upl_name_type), $picture_types))
                {
                    if ($real_size > $this->picture_size AND $this->picture_size) $error = str_replace("{size}", $this->picture_size/1024, $lang_c_upload_files['max_size']);
                }
                else
                {                
                    if ($real_size > $this->file_size AND $this->file_size) $error = str_replace("{size}", $this->file_size/1024, $lang_c_upload_files['max_size']);
                }
                
                if($error)  // Если возникла ошибка - удаляем загруженный файл
                {
                    @unlink($upload_dir_name.$prefix.$upl_name);
                }
                else
                {                                
                    if (!in_array(strtolower($upl_name_type), $picture_types))
                        $type = "file";
                    else
                        $type = "picture";
                        
                    $file_name_mini = "";
                    if ($type == "picture" AND $cache_config['pic_smallphp']['conf_value'])
                    {
                        $width_height = @GetImageSize($upload_dir_name.$prefix.$upl_name);
                        $conf_max_pix = intval($cache_config['pic_autosize']['conf_value']);
    
                        if($width_height[0] > $conf_max_pix OR $width_height[1] > $conf_max_pix)
                        {
                            require_once LB_CLASS . '/easyphpthumbnail.php';
                            $thumb = new easyphpthumbnail;
                        
                            $thumb->Thumbsize = $conf_max_pix;
                            
                            $thumb -> Thumblocation = $upload_dir_name;
                            $thumb -> Thumbprefix = "mini_";
                            $thumb -> Thumbsaveas = $upl_name_type;
                            $thumb -> Thumbfilename = $prefix.$upl_name;
                        
                            $thumb->Createthumb( $upload_dir_name.$prefix.$upl_name , 'file');
                            
                            $file_name_mini = $thumb -> Thumbprefix.$prefix.$upl_name;
                            $file_name_mini = $DB->addslashes($file_name_mini);
                            
                            @chmod($file_name_mini, 0666);
                            
                            unset($thumb);
                        }
                    }
                        
                    $real_size = $real_size;
                    @chmod($upload_dir_name.$prefix.$upl_name, 0666);
                    
                    $upl_name = $DB->addslashes($prefix.$upl_name);
                    
                    $DB->insert("file_title = '{$upl_name_db}', file_name = '{$upl_name}', file_name_mini = '{$file_name_mini}', file_type = '{$type}', file_mname = '{$member_id['name']}', file_mid = '{$member_id['user_id']}', file_date = '{$time}', file_size = '{$real_size}', file_fid = '{$fid}', file_tid = '{$tid}', file_pid = '{$pid}'", "topics_files");									
                }
            }
            
            if ($error) return $error;
        }

		return false;
	}
    
    function Clear($text)
    {
        $quotes = array ("'", "¬", "@", "~", "{", "}", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"', "#" );
        $text = trim( strip_tags( $text ) );
        $text = str_replace( $quotes, '', $text );
        return $text;
    }
    
    function Out_link ($tid = 0, $pid = 0, $mid = 0, $edit = 0)
	{
        global $DB, $cache_config;
        
        $files = array();
        
        // 1 - DLE Forum
        // 2 - TWSF
        
        $lang_c_upload_files = language_forum ("board/class/upload_files");
        
        $where = array();
        $where[] = "file_tid = '{$tid}'";
        if (!$pid)
            $where[] = "file_pid = '{$pid}' AND file_mid = '{$mid}'";
        else
            $where[] = "file_pid = '{$pid}'";
        
        $where = implode (" AND ", $where);
        
        $DB->select( "*", "topics_files", $where );
        while ( $row = $DB->get_row() )
        {
            $dir_name = date( "Y-m", $row['file_date'] );
            $file_size = formatsize($row['file_size']);
            
            $mini_file = "";

            $out_tag = "";
            if ($edit) $out_tag = "<div style=\"float:left; width:100px;\"><a href=\"#\" onclick=\"add_attachment('[attachment=".$row['file_id']."]');return false;\" title=\"".$lang_c_upload_files['add_in_post_title']."\"><center>".$lang_c_upload_files['add_in_post']."</center></a></div><div style=\"float:left; width:80px;\"><center><input type=\"checkbox\" name=\"del_file[]\" value=\"".$row['file_id']."\" /></center></div>";
             
            if ($row['file_convert'] == "1" AND $cache_config['upload_convert']['conf_value'] AND $row['file_type'] != "picture")
            {
                $files[] = "\r\n\r\n<div style=\"clear:left;\"><div style=\"float:left; width:300px;\"><a href=\"".$cache_config['upload_convert']['conf_value'].$row['file_name']."\" title=\"".$lang_c_upload_files['open_file']."\" target=\"_blank\">".$row['file_title']."</a>".$mini_file."</div><div style=\"float:left; width:70px;\"><center>".$file_size."</center></div>".$out_tag."</div>";
            }
            elseif ($row['file_convert'] == "1" AND $cache_config['upload_convert_img']['conf_value'] AND $row['file_type'] == "picture")
            {
                $files[] = "\r\n\r\n<div style=\"clear:left;\"><div style=\"float:left; width:300px;\"><a href=\"".$cache_config['upload_convert_img']['conf_value'].$row['file_name']."\" title=\"".$lang_c_upload_files['open_file']."\" target=\"_blank\">".$row['file_title']."</a>".$mini_file."</div><div style=\"float:left; width:70px;\"><center>".$file_size."</center></div>".$out_tag."</div>";
            }
            elseif ($row['file_convert'] == "2" AND $cache_config['upload_convert_img']['conf_value'] AND $row['file_type'] == "picture")
            {
                $files[] = "\r\n\r\n<div style=\"clear:left;\"><div style=\"float:left; width:300px;\"><a href=\"".$cache_config['upload_convert_img']['conf_value'].$dir_name."/".$row['file_name']."\" title=\"".$lang_c_upload_files['open_file']."\" target=\"_blank\">".$row['file_title']."</a>".$mini_file."</div><div style=\"float:left; width:70px;\"><center>".$file_size."</center></div>".$out_tag."</div>";
            }
            else
            {
                if ($row['file_name_mini']) $mini_file = " [<a href=\"".$cache_config['general_site']['conf_value']."uploads/attachment/".$dir_name."/".$row['file_name_mini']."\" title=\"".$lang_c_upload_files['open_file']."\" target=\"_blank\">".$lang_c_upload_files['mini_file']."</a>]";
                $files[] = "\r\n\r\n<div style=\"clear:left;\"><div style=\"float:left; width:300px;\"><a href=\"".$cache_config['general_site']['conf_value']."uploads/attachment/".$dir_name."/".$row['file_name']."\" title=\"".$lang_c_upload_files['open_file']."\" target=\"_blank\">".$row['file_title']."</a>".$mini_file."</div><div style=\"float:left; width:70px;\"><center>".$file_size."</center></div>".$out_tag."</div>";
            }
        }
        $DB->free();
        
        if (count($files))
        {
            
$files_table = <<<HTML

<div style="clear:left">
<div style="float:left; width:300px;">{$lang_c_upload_files['files_table_name']}</div><div style="float:left; width:70px;"><center>{$lang_c_upload_files['files_table_size']}</center></div>
HTML;
            
            if ($edit) $files_table .= "<div style=\"float:left; width:100px;\"><center>".$lang_c_upload_files['files_table_addinpost']."</center></div><div style=\"float:left; width:80px;\"><center>".$lang_c_upload_files['del_file']."</center></div>";
            $files_table .= "</div>";
            
            $files_table .= implode ("<br />", $files);
            
            return $files_table;        
        }
        else
            return false;
	}
    
    function Add_attachments ($tid = 0, $pid = 0, $mid = 0, $text = "")
	{
        global $DB;
        
        $files = array();
        $result = array();
        $result[1] = array();
        
        $where = array();
        $where[] = "file_tid = '{$tid}'";
        $where[] = "file_pid = '{$pid}'";
        
        if (!$tid AND !$pid) $where[] = "file_mid = '{$mid}'";
        
        $DB->select( "file_id", "topics_files", implode(" AND ", $where) );
        while ( $row = $DB->get_row() )
        {
            if (!preg_match("#\[attachment=".$row['file_id']."(\|(.*?))?\]#i", $text))
                $files[] = $row['file_id'];
                
            $result[1][$row['file_id']] = $row['file_id'];
        }
        $DB->free();
        
        if (count($files))
        {
            $files_tags = array();
            foreach ($files as $value)
            {
                $files_tags[] = "[attachment=".$value."]";
            }
            $files_tags = implode ("<br />", $files_tags);
            $text .= "<br />".$files_tags;
        }
        
        $result[0] = $text;
        
        return $result;
	}
    
	function Del_Record ($id = 0, $get_secret_key = "")
	{
        global $DB, $member_id, $logged, $secret_key;

        $lang_c_upload_files = language_forum ("board/class/upload_files");

        if (!$logged) return $lang_c_upload_files['not_logged'];
        if (!$id) return $lang_c_upload_files['no_file_id'];
        if (!$get_secret_key OR $get_secret_key != $secret_key) return $lang_c_upload_files['secret_key'];
                
        $file_db = $DB->one_select( "*", "topics_files", "file_id = '{$id}'" );
        
        if ($file_db['file_id'])
        {
            if ((!$file_db['file_pid'] AND $file_db['file_mid'] == $member_id['user_id']) OR ($file_db['file_pid'] AND $file_db['file_mid'] == $member_id['user_id'] AND group_permission("local_changepost")) OR forum_options_topics($file_db['file_fid'], "changepost"))
            {
                $upload_dir_name = LB_UPLOADS . "/attachment/".date( "Y-m", $file_db['file_date'] )."/";
                
                $DB->delete("file_id = '{$id}'", "topics_files");    
                @unlink($upload_dir_name.$file_db['file_name']);
                
                if ($file_db['file_name_mini']) @unlink($upload_dir_name.$file_db['file_name_mini']);
            }
            else
                return $lang_c_upload_files['not_enough_rights'];
        }
        else
            return $lang_c_upload_files['file_is_not_found'];
        
        return false;
	}
    
    function Check_Data ()
	{
        global $cache_config;
        
        $this->allow = $cache_config['upload_global']['conf_value'];
        $this->extensions_allowed = $cache_config['upload_type']['conf_value'];
        
        if ($this->extensions_allowed == "")
            $this->extensions_allowed = "zip, rar, jpg, jpeg, gif, png, txt, pdf, doc";
            
        $e_allowed = explode (",", $this->extensions_allowed);
        unset ($this->extensions_allowed);
        $this->extensions_allowed = array();
        foreach ($e_allowed as $value)
        {
            $this->extensions_allowed[] = trim(strtolower($value));
        }
        
        $e_notallowed = explode (",", $this->extensions_notallowed);
        unset ($this->extensions_notallowed);
        $this->extensions_notallowed = array();
        foreach ($e_notallowed as $value)
        {
            $this->extensions_notallowed[] = trim(strtolower($value));
        }
            
        $this->file_size = intval($cache_config['upload_maxsize']['conf_value']);   // Размер в килобайтах
        $this->file_size = $this->file_size * 1024;
        
        $this->picture_size = intval($cache_config['upload_maxsize_pic']['conf_value']);    // Размер в килобайтах
        $this->picture_size = $this->picture_size * 1024;
        
        $this->download_user = $cache_config['upload_num']['conf_value'];
        $this->download_speed = $cache_config['upload_speed']['conf_value'];
        $this->download_counter = $cache_config['upload_count']['conf_value'];
	}
}

?>