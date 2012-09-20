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

$bbcode_script = <<<HTML

	<script language="Javascript" type="text/javascript">	
    var aid = 'tf';
	</script>
        
HTML;

$lang_bbcode = language_forum ("board/scripts/bbcode/bbcode");

if (!$bb_allowed_out) $bb_allowed_out = array();

$bbcode = <<<HTML
        <ol class="bb_codes" style="width:100%;">
HTML;
        
if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("b", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('b'); return false;" title="{$lang_bbcode['b']}"><img src="{TEMPLATE}/bbcode/bb_01.png" alt="{$lang_bbcode['b']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("i", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('i'); return false;" title="{$lang_bbcode['i']}"><img src="{TEMPLATE}/bbcode/bb_02.png" alt="{$lang_bbcode['i']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("s", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('s'); return false;" title="{$lang_bbcode['s']}"><img src="{TEMPLATE}/bbcode/bb_03.png" alt="{$lang_bbcode['s']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("u", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('u'); return false;" title="{$lang_bbcode['u']}"><img src="{TEMPLATE}/bbcode/bb_04.png" alt="{$lang_bbcode['u']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("text_align", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="Mini_Window(this, '200');return false;" title="{$lang_bbcode['text_align_alt']}"><img src="{TEMPLATE}/bbcode/bb_20.png" alt="{$lang_bbcode['text_align_alt']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['text_align']}: <input type="text" name="bb_val" value="center" id="input_bb_text_align" style="width:75px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt('text_align', 'text_align', true, true); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("size", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="Mini_Window(this, '220');return false;" title="{$lang_bbcode['size']}"><img src="{TEMPLATE}/bbcode/bb_05.png" alt="{$lang_bbcode['size']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['size_window']}: <input type="text" name="bb_val" id="input_bb_size" style="width:35px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt('size', 'size', true, true); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("color", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb_colors(this);return false;" title="{$lang_bbcode['color']}"><img src="{TEMPLATE}/bbcode/bb_06.png" alt="{$lang_bbcode['color']}" /></a>
                        <div class="colors">	
								<table >
									<tbody>
										<tr>
											<td id="#FFFFFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffffff;" ></td>
											<td id="#FFCCCC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffcccc;" ></td>
											<td id="#FFCC99" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffcc99;" ></td>
											<td id="#FFFF99" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffff99;" ></td>
											<td id="#FFFFCC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffffcc;" ></td>
											<td id="#99FF99" onclick="bb_color('color='+this.id, 'color')" style="background-color:#99ff99;" ></td>
											<td id="#99FFFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#99ffff;" ></td>
											<td id="#CCFFFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ccffff;" ></td>
											<td id="#CCCCFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ccccff;" ></td>
											<td id="#FFCCFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffccff;" ></td>
										</tr>
										<tr>
											<td id="#CCCCCC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#cccccc;" ></td>
											<td id="#FF6666" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ff6666;" ></td>
											<td id="#FF9966" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ff9966;" ></td>
											<td id="#FFFF66" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffff66;" ></td>
											<td id="#FFFF33" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffff33;" ></td>
											<td id="#66FF99" onclick="bb_color('color='+this.id, 'color')" style="background-color:#66ff99;" ></td>
											<td id="#33FFFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#33ffff;" ></td>
											<td id="#66FFFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#66ffff;" ></td>
											<td id="#9999FF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#9999ff;" ></td>
											<td id="#FF99FF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ff99ff;" ></td>
										</tr>
										<tr>
											<td id="#C0C0C0" onclick="bb_color('color='+this.id, 'color')" style="background-color:#c0c0c0;" ></td>
											<td id="#FF0000" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ff0000;" ></td>
											<td id="#FF9900" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ff9900;" ></td>
											<td id="#FFCC66" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffcc66;" ></td>
											<td id="#FFFF00" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffff00;" ></td>
											<td id="#33FF33" onclick="bb_color('color='+this.id, 'color')" style="background-color:#33ff33;" ></td>
											<td id="#66CCCC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#66cccc;" ></td>
											<td id="#33CCFF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#33ccff;" ></td>
											<td id="#6666CC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#6666cc;" ></td>
											<td id="#CC66CC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#cc66cc;" ></td>
										</tr>
										<tr>
											<td id="#999999" onclick="bb_color('color='+this.id, 'color')" style="background-color:#999999;" ></td>
											<td id="#CC0000" onclick="bb_color('color='+this.id, 'color')" style="background-color:#cc0000;" ></td>
											<td id="#FF6600" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ff6600;" ></td>
											<td id="#FFCC33" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffcc33;" ></td>
											<td id="#FFCC00" onclick="bb_color('color='+this.id, 'color')" style="background-color:#ffcc00;" ></td>
											<td id="#33CC00" onclick="bb_color('color='+this.id, 'color')" style="background-color:#33cc00;" ></td>
											<td id="#00CCCC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#00cccc;" ></td>
											<td id="#3366FF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#3366ff;" ></td>
											<td id="#6633FF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#6633ff;" ></td>
											<td id="#CC33CC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#cc33cc;" ></td>
										</tr>
										<tr>
											<td id="#666666" onclick="bb_color('color='+this.id, 'color')" style="background-color:#666666;" ></td>
											<td id="#990000" onclick="bb_color('color='+this.id, 'color')" style="background-color:#990000;" ></td>
											<td id="#CC6600" onclick="bb_color('color='+this.id, 'color')" style="background-color:#cc6600;" ></td>
											<td id="#CC9933" onclick="bb_color('color='+this.id, 'color')" style="background-color:#cc9933;" ></td>
											<td id="#999900" onclick="bb_color('color='+this.id, 'color')" style="background-color:#999900;" ></td>
											<td id="#009900" onclick="bb_color('color='+this.id, 'color')" style="background-color:#009900;" ></td>
											<td id="#339999" onclick="bb_color('color='+this.id, 'color')" style="background-color:#339999;" ></td>
											<td id="#3333FF" onclick="bb_color('color='+this.id, 'color')" style="background-color:#3333ff;" ></td>
											<td id="#6600CC" onclick="bb_color('color='+this.id, 'color')" style="background-color:#6600cc;" ></td>
											<td id="#993399" onclick="bb_color('color='+this.id, 'color')" style="background-color:#993399;" ></td>
										</tr>
										<tr>
											<td id="#333333" onclick="bb_color('color='+this.id, 'color')" style="background-color:#333333;" ></td>
											<td id="#660000" onclick="bb_color('color='+this.id, 'color')" style="background-color:#660000;" ></td>
											<td id="#993300" onclick="bb_color('color='+this.id, 'color')" style="background-color:#993300;" ></td>
											<td id="#996633" onclick="bb_color('color='+this.id, 'color')" style="background-color:#996633;" ></td>
											<td id="#666600" onclick="bb_color('color='+this.id, 'color')" style="background-color:#666600;" ></td>
											<td id="#006600" onclick="bb_color('color='+this.id, 'color')" style="background-color:#006600;" ></td>
											<td id="#336666" onclick="bb_color('color='+this.id, 'color')" style="background-color:#336666;" ></td>
											<td id="#000099" onclick="bb_color('color='+this.id, 'color')" style="background-color:#000099;" ></td>
											<td id="#333399" onclick="bb_color('color='+this.id, 'color')" style="background-color:#333399;" ></td>
											<td id="#663366" onclick="bb_color('color='+this.id, 'color')" style="background-color:#663366;" ></td>
										</tr>
										<tr>
											<td id="#000000" onclick="bb_color('color='+this.id, 'color')" style="background-color:#000000;" ></td>
											<td id="#330000" onclick="bb_color('color='+this.id, 'color')" style="background-color:#330000;" ></td>
											<td id="#663300" onclick="bb_color('color='+this.id, 'color')" style="background-color:#663300;" ></td>
											<td id="#663333" onclick="bb_color('color='+this.id, 'color')" style="background-color:#663333;" ></td>
											<td id="#333300" onclick="bb_color('color='+this.id, 'color')" style="background-color:#333300;" ></td>
											<td id="#003300" onclick="bb_color('color='+this.id, 'color')" style="background-color:#003300;" ></td>
											<td id="#003333" onclick="bb_color('color='+this.id, 'color')" style="background-color:#003333;" ></td>
											<td id="#000066" onclick="bb_color('color='+this.id, 'color')" style="background-color:#000066;" ></td>
											<td id="#330099" onclick="bb_color('color='+this.id, 'color')" style="background-color:#330099;" ></td>
											<td id="#330033" onclick="bb_color('color='+this.id, 'color')" style="background-color:#330033;" ></td>
										</tr>
									</tbody>
								</table>		
							</div>
							<!--colors end-->
                        </li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("quote", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('quote'); return false;" title="{$lang_bbcode['quote']}"><img src="{TEMPLATE}/bbcode/bb_07.png" alt="{$lang_bbcode['quote']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("smile", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
                        <li><a href="#" onclick="bb_smiles(this);return false;" title="{$lang_bbcode['smiles']}"><img src="{TEMPLATE}/bbcode/bb_18.png" alt="{$lang_bbcode['smiles']}" /></a>
                        <div class="smiles">
								<img src="{TEMPLATE}/bbcode/smiles/001.gif" onclick="insert_smile('001');" />
								<img src="{TEMPLATE}/bbcode/smiles/002.gif" onclick="insert_smile('002');" />
								<img src="{TEMPLATE}/bbcode/smiles/003.gif" onclick="insert_smile('003');" />
								<img src="{TEMPLATE}/bbcode/smiles/004.gif" onclick="insert_smile('004');" />
								<img src="{TEMPLATE}/bbcode/smiles/005.gif" onclick="insert_smile('005');" />
								<img src="{TEMPLATE}/bbcode/smiles/006.gif" onclick="insert_smile('006');" />
								<img src="{TEMPLATE}/bbcode/smiles/007.gif" onclick="insert_smile('007');" />
								<img src="{TEMPLATE}/bbcode/smiles/008.gif" onclick="insert_smile('008');" />
								<img src="{TEMPLATE}/bbcode/smiles/009.gif" onclick="insert_smile('009');" />
								<img src="{TEMPLATE}/bbcode/smiles/010.gif" onclick="insert_smile('010');" />
								<img src="{TEMPLATE}/bbcode/smiles/011.gif" onclick="insert_smile('011');" />
								<img src="{TEMPLATE}/bbcode/smiles/012.gif" onclick="insert_smile('012');" />
								<img src="{TEMPLATE}/bbcode/smiles/013.gif" onclick="insert_smile('013');" />
								<img src="{TEMPLATE}/bbcode/smiles/014.gif" onclick="insert_smile('014');" />
								<img src="{TEMPLATE}/bbcode/smiles/015.gif" onclick="insert_smile('015');" />
								<img src="{TEMPLATE}/bbcode/smiles/016.gif" onclick="insert_smile('016');" />
								<img src="{TEMPLATE}/bbcode/smiles/017.gif" onclick="insert_smile('017');" />
								<img src="{TEMPLATE}/bbcode/smiles/018.gif" onclick="insert_smile('018');" />
								<img src="{TEMPLATE}/bbcode/smiles/019.gif" onclick="insert_smile('019');" />
								<img src="{TEMPLATE}/bbcode/smiles/020.gif" onclick="insert_smile('020');" />
								<img src="{TEMPLATE}/bbcode/smiles/021.gif" onclick="insert_smile('021');" />
								<img src="{TEMPLATE}/bbcode/smiles/022.gif" onclick="insert_smile('022');" />
								<img src="{TEMPLATE}/bbcode/smiles/023.gif" onclick="insert_smile('023');" />
								<img src="{TEMPLATE}/bbcode/smiles/024.gif" onclick="insert_smile('024');" />
								<img src="{TEMPLATE}/bbcode/smiles/025.gif" onclick="insert_smile('025');" />
								<img src="{TEMPLATE}/bbcode/smiles/026.gif" onclick="insert_smile('026');" />
								<img src="{TEMPLATE}/bbcode/smiles/027.gif" onclick="insert_smile('027');" />
								<img src="{TEMPLATE}/bbcode/smiles/028.gif" onclick="insert_smile('028');" />
								<img src="{TEMPLATE}/bbcode/smiles/029.gif" onclick="insert_smile('029');" />
								<img src="{TEMPLATE}/bbcode/smiles/030.gif" onclick="insert_smile('030');" />
								<img src="{TEMPLATE}/bbcode/smiles/031.gif" onclick="insert_smile('031');" />
								<img src="{TEMPLATE}/bbcode/smiles/032.gif" onclick="insert_smile('032');" />
								<img src="{TEMPLATE}/bbcode/smiles/033.gif" onclick="insert_smile('033');" />
								<img src="{TEMPLATE}/bbcode/smiles/034.gif" onclick="insert_smile('034');" />
								<img src="{TEMPLATE}/bbcode/smiles/035.gif" onclick="insert_smile('035');" />
								<img src="{TEMPLATE}/bbcode/smiles/036.gif" onclick="insert_smile('036');" />
								<img src="{TEMPLATE}/bbcode/smiles/037.gif" onclick="insert_smile('037');" />
								<img src="{TEMPLATE}/bbcode/smiles/038.gif" onclick="insert_smile('038');" />
								<img src="{TEMPLATE}/bbcode/smiles/039.gif" onclick="insert_smile('039');" />
								<img src="{TEMPLATE}/bbcode/smiles/040.gif" onclick="insert_smile('040');" />
								<img src="{TEMPLATE}/bbcode/smiles/041.gif" onclick="insert_smile('041');" />
								<img src="{TEMPLATE}/bbcode/smiles/042.gif" onclick="insert_smile('042');" />
								<img src="{TEMPLATE}/bbcode/smiles/043.gif" onclick="insert_smile('043');" />
								<img src="{TEMPLATE}/bbcode/smiles/044.gif" onclick="insert_smile('044');" />
								<img src="{TEMPLATE}/bbcode/smiles/045.gif" onclick="insert_smile('045');" />
								<img src="{TEMPLATE}/bbcode/smiles/046.gif" onclick="insert_smile('046');" />
								<img src="{TEMPLATE}/bbcode/smiles/047.gif" onclick="insert_smile('047');" />
								<img src="{TEMPLATE}/bbcode/smiles/048.gif" onclick="insert_smile('048');" />
								<img src="{TEMPLATE}/bbcode/smiles/049.gif" onclick="insert_smile('049');" />
								<img src="{TEMPLATE}/bbcode/smiles/050.gif" onclick="insert_smile('050');" />
							</div>
							<!--smiles end-->
                        </li>

HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("font", $bb_allowed_out)))
{                     
$bbcode .= <<<HTML
						<li>
							<select name="bb_02" id="bb_02" class="lbselect">
								<option value="" onclick="bb_font('font=Arial', 'font');">Arial</option>
								<option value="" onclick="bb_font('font=Tahoma', 'font');">Tahoma</option>
								<option value="" onclick="bb_font('font=Times New Roman', 'font');">Times New Roman</option>
							</select>
						</li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("spoiler", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="Mini_Window(this, '270');return false;" title="{$lang_bbcode['spoiler']}"><img src="{TEMPLATE}/bbcode/bb_08.png" alt="{$lang_bbcode['spoiler']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['spoiler_window']}:<br /><input type="text" name="bb_val" id="input_bb_spoiler" style="width:231px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt('spoiler', 'spoiler', false, true); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}
 
if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("youtube", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="Mini_Window(this, '270');return false;" title="youtube"><img src="{TEMPLATE}/bbcode/bb_09.png" alt="{$lang_bbcode['youtube']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['youtube_window']}:<br /><input type="text" name="bb_val" id="input_bb_youtube" style="width:231px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt('youtube', 'youtube', true, false); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("translite", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('translite'); return false;" title="{$lang_bbcode['translite']}"><img src="{TEMPLATE}/bbcode/bb_10.png" alt="{$lang_bbcode['translite']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("php", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('php'); return false;" title="{$lang_bbcode['php']}"><img src="{TEMPLATE}/bbcode/bb_11.png" alt="{$lang_bbcode['php']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("html", $bb_allowed_out)))
{                   
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('html'); return false;" title="{$lang_bbcode['html']}"><img src="{TEMPLATE}/bbcode/bb_12.png" alt="{$lang_bbcode['html']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("javascript", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('javascript'); return false;" title="{$lang_bbcode['js']}"><img src="{TEMPLATE}/bbcode/bb_13.png" alt="{$lang_bbcode['js']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("hide", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('hide'); return false;" title="{$lang_bbcode['hide']}"><img src="{TEMPLATE}/bbcode/bb_14.png" alt="{$lang_bbcode['hide']}" /></a></li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("url", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="Mini_Window(this, '270');return false;" title="{$lang_bbcode['url']}"><img src="{TEMPLATE}/bbcode/bb_15.png" alt="{$lang_bbcode['url']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['url_window']}:<br /><input type="text" name="bb_val" id="input_bb_url" style="width:231px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt('url', 'url', true, true); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}
 
if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("img", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
                        <li><a href="#" onclick="Mini_Window(this, '270');return false;" title="{$lang_bbcode['img_alt']}"><img src="{TEMPLATE}/bbcode/bb_16.png" alt="{$lang_bbcode['img_alt']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['img']}:<br /><input type="text" name="bb_val" id="input_bb_img" style="width:265px;height:15px;" /><br /><br />
                        {$lang_bbcode['img_align']}: <input type="text" name="bb_val" value="center" id="input_bb_img_align" style="width:141px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt_img('img', 'img'); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("email", $bb_allowed_out)))
{                       
$bbcode .= <<<HTML
						<li><a href="#" onclick="Mini_Window(this, '217');return false;" title="{$lang_bbcode['email']}"><img src="{TEMPLATE}/bbcode/bb_17.png" alt="{$lang_bbcode['email']}" /></a>
                        <span class="mini_window_content">
                        {$lang_bbcode['email_window']}:<br /><input type="text" name="bb_val" id="input_bb_email" style="width:180px;height:15px;" /> <input type="button" class="mini_window_button2" onclick="bb_promt('email', 'email', true, true); return false;" value="OK" />
                        </span>
                        </li>
HTML;
}

if (!count($bb_allowed_out) OR (count($bb_allowed_out) AND in_array("search", $bb_allowed_out)))
{
$bbcode .= <<<HTML
						<li><a href="#" onclick="bb('search'); return false;" title="{$lang_bbcode['search']}"><img src="{TEMPLATE}/bbcode/bb_21.png" alt="{$lang_bbcode['search']}" /></a></li>
HTML;
}
                     
$bbcode .= <<<HTML
						<li><a href="#" onclick="window.open('{HOME_LINK}?do=system_info','{$lang_bbcode['info_window']}','width=900,height=500,toolbar=1,location=0,scrollbars=1'); return false;" title="{$lang_bbcode['info']}"><img src="{TEMPLATE}/bbcode/bb_19.png" alt="{$lang_bbcode['info']}" /></a></li>
HTML;
                       
$bbcode .= <<<HTML
                        
        </ol>
HTML;

?>