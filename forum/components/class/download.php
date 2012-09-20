<?php

/****************************************/
// ИНФОРМАЦИЯ:
// ==== Форум: LogicBoard
// ==== Автор: Никита Курдин (ShapeShifter)
// ==== Copyright © Никита Курдин Игоревич 2011-2012
// ==== Данный код защищен авторскими правами
// ==== Официальный сайт: http://logicboard.ru

/****************************************/

/**********************************************************************
** Author: Mourad Boufarguine
***********************************************************************/

if (! defined ( 'LogicBoard' ))
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

class download{
    
    private $properties = array("path" => "",       // путь к файлу
                                "name" => "",       // название файла (можно переименовать при скачивании)
                                "extension" => "",  // расширение
                                "type" => "",       // тип файла
                                "size" => "",       // размер файла
                                "resume" => "",     // докачка
                                "max_speed" => ""   // ограничение скорости
                                );          

    public function __construct($path, $name = "", $resume = 0, $max_speed = 0)
    {  
        // by default, resuming is NOT allowed and there is no speed limit
        $name = ($name == "") ? substr(strrchr("/".$path,"/"),1) : $name; // if "name" is not specified, th file won't be renamed
        $file_extension = strtolower(substr(strrchr($path,"."),1));       // the file extension
        
        switch( $file_extension )
        {                                       
            // the file type
            case "mp3": $content_type="audio/mpeg"; break;
            case "mpg": $content_type="video/mpeg"; break;
            case "avi": $content_type="video/x-msvideo"; break;
            case "wmv": $content_type="video/x-ms-wmv";break;
            case "wma": $content_type="audio/x-ms-wma";break; 
            case "zip": $content_type="application/zip"; break; 
            case "rar": $content_type="application/x-rar-compressed"; break; 
            case "pdf": $content_type="application/pdf"; break; 
            case "doc": $content_type="application/msword"; break; 
            case "xls": $content_type="application/vnd.ms-excel"; break; 
            case "ppt": $content_type="application/vnd.ms-powerpoint"; break;
            default: $content_type="application/octet-streamd";
        }
        
        if(!@file_exists($path))
            exit("Stop.");
            
        if(@is_dir($path))
            exit("Stop.");
        
        $file_size = @filesize($path);                                     // the file size
        $this->properties =  array(
                                    "path" => $path, 
                                    "name" => $name, 
                                    "extension" =>$file_extension,
                                    "type"=>$content_type, 
                                    "size" => $file_size, 
                                    "resume" => $resume, 
                                    "max_speed" => $max_speed
                                    );
    }
    
    // public function to get the value of a property
    public function get_property ($property)
    {
        if ( array_key_exists($property,$this->properties) )   // check if the property do exist
            return $this->properties[$property];               // get its value
        else
            return null;                                       // else return null
    }
    
    // public function to set the value of a property        
    public function set_property ($property, $value)
    {
        if ( array_key_exists($property, $this->properties) )
        { 
            // check if the property do exist
            $this->properties[$property] = $value;             // set the new value
            return true;
        }
        else
            return false;
    }
    
    // public function to start the download
    public function download_file()
    {
        global $cache_config, $DB, $id;
        
        @ob_end_clean();
        
        if ( $this->properties['path'] == "" )                 // if the path is unset, then error !
            echo "Nothing to download!";
        else
        {
            if ($this->properties["resume"])
            {
                if(isset($_SERVER['HTTP_RANGE']))
                {   
                    // check if http_range is sent by browser (or download manager)
                    list($a, $range)=explode("=",$_SERVER['HTTP_RANGE']);  
                    ereg("([0-9]+)-([0-9]*)/?([0-9]*)",$range,$range_parts);    // parsing Range header
                    $byte_from = $range_parts [1];                              // the download range : from $byte_from ...
                    $byte_to = $range_parts [2];                                // ... to $byte_to 
                }
                else
                {
                    if(isset($_ENV['HTTP_RANGE']))
                    {   
                        // some web servers do use the $_ENV['HTTP_RANGE'] instead
                        list($a, $range) = explode("=", $_ENV['HTTP_RANGE']);
                        ereg("([0-9]+)-([0-9]*)/?([0-9]*)", $range, $range_parts);  // parsing Range header
                        $byte_from = $range_parts [1];                              // the download range : from $byte_from ...
                        $byte_to = $range_parts [2];                                // ... to $byte_to 
                    }
                    else
                    {
                        $byte_from = 0;                             // if no range header is found, download the whole file from byte 0 ...
                        $byte_to = $this->properties["size"] - 1;   // ... to the last byte
                    }
                }
                
                if ($byte_to == "")                             // if the end byte is not specified, ...
                    $byte_to = $this->properties["size"] -1;    // ... set it to the last byte of the file
                     
                $download_size = $this->properties["size"] - $byte_from;
                
                if ($byte_from)
                    header( $_SERVER['SERVER_PROTOCOL'] . " 206 Partial Content" ); // send the partial content header
                else
                    header( $_SERVER['SERVER_PROTOCOL'] . " 200 OK" );
                // HTTP/1.1
			}
            else // ... else, download the whole file
            {
                $byte_from = 0;
                $byte_to = $this->properties["size"] - 1;
                $download_size = $this->properties["size"];
                header( $_SERVER['SERVER_PROTOCOL'] . " 200 OK" );
            }
            
            if (($speed = $this->properties["max_speed"]) > 0)                       // determine the max speed allowed ...
                $sleep_time = (8 / $speed) * 1e6;                                    // ... if "max_speed" = 0 then no limit (default)
            else
                $sleep_time = 0;
                            
            header("Pragma: public");                                                // purge the browser cache
            header("Expires: 0");                                                    // ...
            header("Cache-Control: public");                                         // ... 
            header("Content-Description: File Transfer");                            //  
            header("Content-Type: ".$this->properties["type"]);                      // file type
            header('Content-Disposition: attachment; filename="'.$this->properties["name"].'";');
            header("Content-Transfer-Encoding: binary");                             // transfer method
            
            if( $this->properties['resume'] ) header("Accept-Ranges: bytes");
            
            if( $byte_from )
            {
                $download_range = $byte_from."-".$byte_to."/".$this->properties["size"]; // the download range
                header("Accept-Ranges: bytes");
                header("Content-Range: ".$download_range);                                // download range
            }

            header("Content-Length: ".$download_size);                                // download length
       
            $fp = fopen($this->properties["path"],"r"); // open the file 
                        
            if(!$fp) exit; // if $fp is not a valid stream resource, exit
                
            fseek($fp,$byte_from);                          // seek to start of missing part   
            while(!feof($fp))
            {   
                // start buffered download  
                set_time_limit(0);                          // reset time limit for big files (has no effect if php is executed in safe mode)
                print(fread($fp,1024*8));                   // send 8ko 
                ob_flush();
                flush();
                usleep($sleep_time);                        // sleep (for speed limitation)
            }
            
            if ($cache_config['upload_count']['conf_value'] == 2)
                $DB->update("file_count = file_count + 1", "topics_files", "file_id='{$id}'");
            
            fclose($fp);                                    // close the file
            
            $DB->close();
            exit;  
        }
    }
}
?>