<?php
/*
=====================================================
 ������ ������ Rss Grabber 3.6.7
 http://rss-grabber.ru/
 �����: Andersoni
 �� �����: Alex
 Copyright (c) 2009 - 2010
=====================================================
*/
if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

$xfields = xfieldsload();
switch ($xfieldsaction) {
case "list":
    $output = "";
    foreach ($xfields as $name => $value) {
      $fiel= '_'.$i.'_'.$channel_id;
      $fieldname = $value[0];
      $holderid = "xfield_holder_$fieldname$fiel";

      if ($value[3] == "textarea") {      
      $output .= <<<HTML
<tr id="$holderid">
<td width="140">$value[1]:<br />[if-optional]({$lang['xf_not_notig']})[/if-optional]</td>
<td><!--$fieldname--><textarea style="width:98%; height:80px" name="xfield$i$channel_id.[$fieldname]" id="xf_$i$channel_id$fieldname" onclick="setFieldName(this.id)" >$fieldvalue[$fieldname]</textarea></td></tr>
HTML;
      } elseif ($value[3] == "text") {
        $output .= <<<HTML
<tr id="$holderid">
<td width="140">$value[1]:</td>
<td class=xfields colspan="2"><input type="text" name="xfield$i$channel_id.[$fieldname]" id="xfield$i$channel_id.[$fieldname]" value="$fieldvalue[$fieldname]" />&nbsp;&nbsp;[if-optional]<font style="font-size:7pt">({$lang['xf_not_notig']})</font>[/if-optional]

</tr>
HTML;
      } elseif ($value[3] == "select") {
        $output .= <<<HTML

<tr id="$holderid">
<td width="140">$value[1]:<select size="60" name="xfield$i$channel_id.[$fieldname]">
HTML;
        foreach (explode("\r\n", $value[4]) as $index => $value) {
		  $value = str_replace("'", "&#039;", $value);
          $output .= "<option value=\"$index\"" . ($fieldvalue[$fieldname] == $value ? " selected" : "") . ">$value</option>\r\n";
        }

$output .= <<<HTML
</select></td>
</tr>
HTML;
      }
      $output = preg_replace("'\\[if-optional\\](.*?)\\[/if-optional\\]'s", $value[5] ? "\\1" : "", $output);
      $output = preg_replace("'\\[not-optional\\](.*?)\\[/not-optional\\]'s", $value[5] ? "" : "\\1", $output);
      $output = preg_replace("'\\[if-add\\](.*?)\\[/if-add\\]'s", ($xfieldsadd) ? "\\1" : "", $output);
      $output = preg_replace("'\\[if-edit\\](.*?)\\[/if-edit\\]'s", (!$xfieldsadd) ? "\\1" : "", $output);
    }
    $output .= <<<HTML

<script type="text/javascript">
<!--
    item = document.getElementById("category$i$channel_id");

    onCategoryChange$i$channel_id(item.value);
// -->
</script>
HTML;
    break;

  case "categoryfilter":
    $categoryfilter = <<<HTML
  <script type="text/javascript">
    function onCategoryChange$i$channel_id(value) {

HTML;
    foreach ($xfields as $value) {
      $categories = str_replace(",", "||value==", $value[2]);
      if ($categories) {
        $categoryfilter .= "ShowOrHideEx(\"xfield_holder_".$value[0].'_'.$i.'_'.$channel_id."\", value == $categories);\r\n";
      }
    }
    $categoryfilter .= "  }\r\n</script>";
    break;
  default:
  if (function_exists('msg'))
    msg("error", $lang['xfield_error'], $lang['xfield_xerr2']);
}
?>