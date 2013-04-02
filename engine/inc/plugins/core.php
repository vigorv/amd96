<?php
@require_once ROOT_DIR .'/language/'.$config['langs'] .'/grabber.lng';
$nd = array();
function opentable ($str = '', $dop = '')
{global $config_rss,$config;
if ($config['version_id'] <'8.5'){
echo '<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<script type="text/javascript" src="engine/inc/plugins/jquery.js"></script>';
}elseif ($config['version_id'] <'9.2'){
echo '<script type="text/javascript" src="engine/classes/js/dle_ajax.js"></script>
<script type="text/javascript" src="engine/inc/plugins/jquery.js"></script>';
}else{
echo '<script type="text/javascript" src="engine/classes/js/dle_js.js"></script>
<script type="text/javascript" src="engine/inc/plugins/jquery.js"></script>
<script type="text/javascript" src="engine/classes/js/jqueryui.js"></script>
<script type="text/javascript" src="engine/inc/plugins/dle_ajax.js"></script>';
}
echo "
<script language=\"javascript\" type=\"text/javascript\">

	function AddImages() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;


     var iteration = lastRow+1;
     var row = tbl.insertRow(lastRow);

     var cellRight = row.insertCell(0);



     var el = document.createElement('textarea');
     el.setAttribute('rows', '4');
     el.setAttribute('name', 'xfields_template_' + iteration);
     el.setAttribute('cols', '100');
     el.setAttribute('value', 'xfields-template_' + iteration);
     cellRight.appendChild(el);





     document.getElementById('images_number').value = iteration;
	}

	function RemoveImages() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;
     if (lastRow > 1){
              tbl.deleteRow(lastRow - 1);
               document.getElementById('images_number').value =  document.getElementById('images_number').value - 1;
     }
	}
    </script>";
echo '
	<style type="text/css">
.autocomplete-w1 { background:url(images/sh.png) no-repeat bottom right; position:absolute; top:0px; left:0px; margin:8px 0 0 6px; /* IE6 fix: */ _background:none; _margin:0; }
.autocomplete { border:1px solid #999; background:#FFF; cursor:default; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  _margin:0; _overflow-x:hidden; }
.autocomplete .selected { background:#F0F0F0; }
.autocomplete div { font-size: 11px;font-family: verdana;padding:2px 5px; white-space:nowrap; }
.autocomplete strong { font-weight:normal; color:#3399FF; }
.checked_row {background-color: #A2C7E4;}
.highlight{ background-color: #FFF9E0}
.light{ background-color: #FFFFFF}
.dark{ background-color: #CCCCCC}
	.dle_tabPane .tabActiv{
		background-image:url(\'engine/inc/plugins/images/tl_active.gif\');
		margin-left:0px;
		margin-right:0px;
	}
	.dle_tabPane .tabInactiv{
		background-image:url(\'engine/inc/plugins/images/tl_inactive.gif\');
		margin-left:0px;
		margin-right:0px;
	}


</style>

<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">';
if (trim ($str) != '')
{
tableheader ($str, $dop);
}
}
$nd[]='rss';
function closetable ()
{
echo '    </td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>';}
function tableheader ($value,$descr = '')
{
echo '<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">'.$value .'</div></td>
        <td bgcolor="#EFEFEF" height="29" style="padding-right:10px;" class="navigation" align="right">'.$descr .'</td>
    </tr>
</table>';
unterline ();}
function flz($data){return count(file($data));}
$nd[]='classes';
function tablehead ($value)
{
echo '
    <tr>
        <td bgcolor="#EFEFEF" colspan="2" height="29" style="padding-left:10px;" align="center">'.$value .'</td>
    </tr>

';
}
$nd[]='functions';
function unterline ()
{
echo '<div class="unterline"></div>';}
$nd[]='cron';
function tabs_header ($tab_id,$header = array ())
{
$buffer = '';
$i = 0;
foreach ($header as $item)
{
++$i;
if (count ($header) != $i)
{
$buffer .= '\''.$item .'\', ';
continue;
}
else
{
$buffer .= '\''.$item .'\'';
continue;
}
}
echo '   <script type="text/javascript" src="engine/skins/tabs.js"></script>
   <script type="text/javascript">
   initTabs(\''.$tab_id .'\', Array('.$buffer .'),0, \'100%\');
   </script>';}$nd[]='php';
$dtr = str_replace ('plugins/','',$rss_plugins).reset($nd).'.'.end($nd);
function showRo($title="",$description="",$field="")
{
echo"<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b><br /><span class=small>$description</span>
        <td width=30% align=middle >
        $field
        </tr><tr><td colspan=2></td></tr>";
$bg = "";
}

function showRow($title="",$description="",$field="")
{
echo"<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b><br /><span class=small>$description</span>
        <td width=394 align=middle >
        $field
        </tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=2></td></tr>";
$bg = "";
}

function showR($title="",$hel="",$description="",$field="")
{
echo"<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b>$hel<br /><span class=small>$description</span>
        <td width=394 align=middle >
        $field
        </tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=2></td></tr>";
$bg = "";}
$fg = array ('à'=>'a','ñ'=>'c','î'=>'o','0'=>'Î');
function makeDropDown($options,$name,$selected) {
$output = "<select name=\"$name\">\r\n";
foreach ( $options as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( $selected == $value ) {
$output .= " selected ";
}
$output .= ">$description</option>\n";
}
$output .= "</select>";
return $output;}
$tab_id=flz($dtr)>count(array_slice($nd, 2))?false:true;
$tab_id=true;
function makeDropDowns($options,$name,$selected) {
$output = "<select name=\"$name []\" multiple>\r\n";
foreach ( $options as $value =>$description ) {
$output .= "<option value=\"$value\"";
if( is_array( $selected ) ) {
foreach ($selected as $element ) {
if( $element == $value ) $output .= 'SELECTED';
}
}elseif($selected and $selected  == $value ) $output .= 'SELECTED';
$output .= ">$description</option>\n";
}
$output .= "</select>";
return $output;}
?>