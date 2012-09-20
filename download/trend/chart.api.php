<?php
include_once("pChart/pData.class");
include_once("pChart/pChart.class");
function pr($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "<br>";
}
class Chart extends ChartCache
{
    var $preffix="test";
    var $imgdir='./tmp/';
    var $imgname="";
    var $Yplot=array();
    var $id=null;
    var $name="";
    var $ydiff=12;
    var $yspace=null;
    
    function Chart($name='test',$id=null,$title="")
    { 
        $this->preffix=$name;
        $this->ChartCache($this->preffix);
        $this->get();
        $this->id=$id;
        $this->name=$title;
        $this->imgname=$id.".png";
    }
    function getImg() 
    {
        $this->setYplot();
        if(count($this->Yplot)>1)$this->prgrath();
        if(count($this->Yplot)>1)return $this->imgdir.$this->preffix."_".$this->imgname;
        else return false;
    }
    function setYplot()
    {
        reset($this->data);
        $key=key($this->data);
        if(!is_array($this->data[$key]))
        $serie3=array_keys($this->data);
        else $serie3=array_keys($this->data[$key]);
        $count=count($serie3);
        //echo $count;
        if(is_null($this->yspace))$this->yspace=intval($count/$this->ydiff)+1;
        //echo "j=".$j;
        foreach($serie3 as $k=>$v)
        {
        if($i%$this->yspace==0)
        {
            //delete year
            $a=explode("-",$v);
            unset($a[0]);
            $a=implode('-',$a);
            $serie4[$k]=$a;
        }
        else $serie4[$k]="";
        $i++;
        }
        return $this->Yplot=$serie4;
        
    }
    
    
 function prgrath()
 {
 @unlink($this->imgdir.$this->preffix."_".$this->id.".png");
 // Dataset definition 
 $DataSet = new pData;
 reset($this->data);
 $key=key($this->data);
 if(!is_array($this->data[$key]))$DataSet->AddPoint($this->data,"Serie1");
 else  foreach ($this->data as $k=>$v)$DataSet->AddPoint($v,"id_".$k);
 //$DataSet->AddPoint($serie2,"Serie2");
 $DataSet->AddPoint($this->Yplot,"Serie3"); 
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie3");
 if(!is_array($this->data[$key]))$DataSet->SetSerieName("Num","Serie1");
 else foreach ($this->data as $k=>$v)$DataSet->SetSerieName("link ".$k,"id_".$k);
 //$DataSet->SetSerieName("Publication","Serie2");
 //$DataSet->SetSerieName("Updates","Serie3");
 
//print_r($DataSet->GetData());
 // Initialise the graph
 $Test = new pChart(1000,500);
 //$Test->setFixedScale(-2,8);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(100,30,850,450);
 $Test->drawFilledRoundedRectangle(7,7,1000,500,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,1000,500,5,230,230,230);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
 $Test->drawGrid(4,TRUE,230,230,230,50);

 // Draw the 0 line
 $Test->setFontProperties("Fonts/tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the cubic curve graph
// $DataSet->RemoveSerie("Serie2");

 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());

 if(!is_array($this->data[$key]))$DataSet->RemoveSerie("Serie1");
 else foreach ($this->data as $k=>$v)$DataSet->RemoveSerie("id_".$k);

 //$DataSet->RemoveSerie("Serie3");
 //$DataSet->AddSerie("Serie2");

// Draw the limit graph
 //$Test->drawLimitsGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,180,180);

 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->drawLegend(900,30,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 //echo iconv('windows-1251','utf-8',$title);
 $Test->drawTitle(50,22,$this->name,50,50,50,585);
 $Test->Render($this->imgdir.$this->preffix."_".$this->id.".png");
}

    
}
class ChartCache
{
    var $dir='./tmp/';
    var $name='';
    var $fullpath;
    var $data=null;
    var $DEBUG=true;
    function ChartCache($name)
    {
        $this->name=$name.".tmp";
        $this->fullpath=$this->dir.$this->name;
    }
    function getfullpath()
    {
        return $this->fullpath;
    }
    function get()
    {
        if($this->DEBUG)echo  __FUNCTION__."<br>";
        $data=file_get_contents($this->fullpath);
        $this->data=unserialize($data);
    }
    function getData($fromfile=true)
    {
        if($this->DEBUG)echo  __FUNCTION__."<br>";
        if($fromfile==true)$this->get();
        return $this->data;
    }
    function set()
    {
       if($this->DEBUG)echo  __FUNCTION__."<br>";
       $handler = @fopen($this->fullpath, "w");
       fwrite($handler,serialize($this->data));
       fclose($handler);    
        
    }
    function setData($data,$tofile=true)
    {
        if($this->DEBUG)echo  __FUNCTION__."<br>";
        $this->data=$data;
        if($tofile==true)$this->set();
    }
    function clear()
    {
        if($this->DEBUG)echo  __FUNCTION__."<br>";
        unset($this->fullpath);
    }
    
    //to Array type $k=>$v as "YYYY-MM-DD_HH-ii-ss"
    function deleteFromArray($beforekey)
    {        
       reset($this->data);
       $key=key($this->data);
      if(!is_array($this->data[$key]))
      {
            ksort($this->data);
            $data2=array();
            $needle=false;
            foreach($this->data as $k=>$v)
            { 
                if($k==$beforekey)$needle=true;
                if($needle==true)$data2[$k]=$v;
            }
        $this->data=$data2;
      }
      else
      {
          $datas=array();
           foreach($this->data as $k1=>$v1)
           {
            ksort($v1);
            $data2=array();
            $needle=false;
            foreach($v1 as $k=>$v)
            { 
                if($k==$beforekey)$needle=true;
                if($needle==true)$data2[$k]=$v;
            }
        $datas[$k1]=$data2;

           } 
        $this->data=$datas;  
      }
        
    }
    
    
}
class ChartCacheExample
{
function ChartCacheExample(){
$cache = new Chart("month");
$cleardate=date("Y-m-d",time()-14*3600*24);
echo $cleardate;
$cache->deleteFromArray($cleardate);
$serie1=$cache->getData(false);
$img=$cache->getImg();
$id=1;
//echo $img;
if($img)echo "<br><a href='".$img."'><img src='".$img."'></a>";
echo "<table border=1><tr><td><a href='/id/{$id}' target=_blank>Publication</a><pre>";
print_r($serie1);
foreach($serie6 as $k=>$link){echo "<td><a href ='index2.php?sgroup=2&id={$k}'>Graph</a><br><a href='http://fastlink.ws/catalog/file/{$k}'>{$k}</a><pre>";print_r($link);}

//pr($cache->getData(false));
//$cache->set();
echo "\r\nOK";
}
}

//$p=new ChartCacheExample();

?>
