<?php
function start($story)
{
$story=strtr($story, array(
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',
'�' => 'biggrab �',  	    
));
$story=strtr($story, array( "\n" => " \n",':' => ' :',';' => ' ;',',' => ' ,','.' => ' .','?' => ' ?','!' => ' !',
"\\\"" => ' #k1#',"\\\'" => ' #k2#',
" \\\"" => ' #k3#'," \\\'" => '  #k4#',
')' => ' ) ','(' => '( ', '>>' => ' >> ','<<' => ' << ',));
return $story;
}

function finish($story)
{

$story=strtr($story, array('  ' => ' ',));
$story = str_replace('biggrab <font color="red">', '<font color="red">biggrab ',$story);
$story=strtr($story,
array(
'  ' => ' ',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab �' => '�',
'biggrab 1' => '1',
'biggrab 2' => '2',
'biggrab 3' => '3',
'biggrab 4' => '4',
'biggrab 5' => '5',
'biggrab 6' => '6',
'biggrab 7' => '7',
'biggrab 8' => '8',
'biggrab 9' => '9',
'biggrab 0' => '0',    	        
));
$story=strtr($story, array( " \n" => "\n",' :' => ':',' ;' => ';',' ,' => ',',' .' => '.',' ? ' => '?',' ! ' => '!',' #k1#' => "\"",' #k2#' => "\'",
' #k3#' => " \"",' #k4#' => " \'",
' ) ' => ')',' ( ' => '(',' >> ' => '>>',' << ' => '<<',' !' => '!',' ?' => '?',"\\" => '','[sin]'=>'', '[/sin]'=>'','[nosin]'=>'', '[/nosin]'=>'', 'biggrab '=>''));

return $story;
}

function sinonims($story, $kol = false)
{
$story = start($story);
$story = sinomize($story, $kol);
$story = finish($story);
return $story;
}
function sinomize($text, $kol)
{ // BEGIN function sinomize
       global $db, $parse, $config;
$story = $parse->BB_Parse($text ,true);
$story = strip_tags ($story);
$sinonim = array();
//preg_match_all('/([�-��-�]+)/', $text, $words);
$words[1] = explode ( ' ', $story);
$sss=$words[1];
sort($sss);
$oldvalue='';
$where='';
foreach ($sss as $key=>$value) {
  if ($value!=$oldvalue and $value!= '' and strlen ($value) > 1 ){
    $newarr[]="like '%".$db->safesql($value)."|%'";
  } 
  $oldvalue=$value;	
}

if (count($newarr) != '0'){$where = implode (' or string ', $newarr);
$db->connect(DBUSER,DBPASS,DBNAME,DBHOST);
           $sql = $db->query("SELECT * FROM " . PREFIX . "_synonims WHERE string $where");
                  if ($db->num_rows ($sql) > 0) {
          while ($row = $db->get_array($sql)){

            $storyr=explode("|",$row['string']);
//echo $storyr[0].'<br />';
if (preg_match ("#".$storyr[0]."#i", $text)){
            $pattern=' '.$storyr[0].' ';
            $vars =explode(",",$storyr[1]);
            $rnd=array_rand($vars);
			$f = '<font color="red">';
			$e = "</font>";
if($kol == true)
			  {
            $repl=' '.$f.$vars[$rnd].$e.' ';
			  }else{
            $repl=' '.$vars[$rnd].' ';
			  }

$sinonim1[] = $pattern;
$sinonim2[] = $repl;
		  }

          }
//var_export($sinonim);
//$text=strtr($text, array(' ' => '  ',));
//echo $text;
@$text=str_replace($sinonim1,$sinonim2,$text);
 
				  }
}
return $text;
      }
$db->close;
?>