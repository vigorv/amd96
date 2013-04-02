<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr><td  colspan="4" style="padding:4px"></td></tr>
<tr><td colspan="2">

 <div id="rss_panel">
   <!-- Основные настройки -->
   <div class="dle_aTab">

  <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
<!--     <td style="padding:4px" width="300"><b>{name_canal}:</b></td> -->
   <td style="padding:4px" colspan=2><b>Выбранные каналы:</b><br>{title}
    </td>
  </tr>

<!--   <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px" width="300"><b>{url_canal}</b></td>
    <td style="padding:4px" colspan=2>{address}
    </td>
  </tr> -->
<input type="hidden"  size="70" name="rss_url" value="{address}">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{chars}:</td>
   <td style="padding:4px"><input type="text"  size="15" name="charset" value="{charsets}">
  <a href="#" class="hintanchor" onMouseover="showhint('{help_chars}', this, event, '420px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{grabpause}</td>
   <td style="padding:4px"><input type="text"  size="5" name="grab_pause" value="{grab-pause}"> в сек.
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{add_pause_word}</td>
   <td style="padding:4px"><input type="text"  size="5" name="add_pause" value="{add-pause}"> в сек.
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{use_proxy}</td>
   <td style="padding:4px">
     <select name="proxy" class="load_img">
      {prox}
     </select>
  <a href="#" class="hintanchor" onMouseover="showhint('{help_use_proxy}', this, event, '420px')">[?]</a>
   </td>
  </tr>
<!-- 
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{rss_canal}</td>
   <td style="padding:4px">
   <select name="rss_html" class="load_img">
    {rss_html}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rss_canal}', this, event, '420px')">[?]</a>
  </td>
  </tr>
 -->

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300">{cat_default}</td>
  <td style="padding:4px">
   <select name="category[]" class="cat_select" multiple>
    {category}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_cat_default}', this, event, '360px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{cat_auto}</td>
   <td style="padding:4px">
   <select name="thumb_img" class="load_img">
    {thumb-images}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_cat_auto}', this, event, '420px')">[?]</a> 
  </td>
  </tr>

  <tr>
   <td style="padding:4px"  width="300">{catsp}</td>
   <td style="padding:4px">
    <select name="cat_sp" class="load_img">
    {cat-sp}
   </select>
<a href="#" class="hintanchor" onMouseover="showhint('{help_catsp}', this, event, '360px')">[?]</a>
  </td>
  </tr>

  <tr>
            <td height="29" style="padding-left:5px;"><b>{kats}:</b>{help_kats}</td>
      <td style="padding:4px">
      <textarea name="kategory" class="load_img" style="width:388px;height:70px;">{kategory}</textarea>
         <a href="#" class="hintanchor" onMouseover="showhint('{help_kats1}', this, event, '360px')">[?]</a>
	</td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{catnul}</td>
   <td style="padding:4px">
   <select name="cat_nul" class="load_img">
    {cat-nul}
   </select>
   
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{teg_auto}</td>
   <td style="padding:4px">
   <select name="tags_auto" class="load_img">
    {tags-auto}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_teg_auto}', this, event, '420px')">[?]</a> 

    <input type="text" id="tags_kol" name="tags_kol" size="10"  class="load_img" value="{tags-kol}"><a href="#" class="hintanchor" onMouseover="showhint('Кол-во слов/Кол-во тегов', this, event, '300px')">[?]</a>

  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{rewrite}</td>
   <td style="padding:4px">
   <select name="rewrite_news" class="load_img">
    {rewrite-news}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rewrite}', this, event, '420px')">[?]</a> 
   Обновлять по дате
   <select name="rewrite_data" class="load_img">
    {rewrite-data}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_rewrite1}', this, event, '420px')">[?]</a> 
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{no_prov}</td>
   <td style="padding:4px">
   <select name="no_prow" class="load_img">
    {no-prow}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_no_prov}', this, event, '420px')">[?]</a> 
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{moderation}</td>
   <td style="padding:4px">

     <select name="allow_mod" class="load_img">
      {allow-mod}
     </select>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{post_index}</td>
   <td style="padding:4px">

     <select name="allow_main" class="load_img">
      {allow-main}
     </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{rating}</td>
   <td style="padding:4px">
    <select name="allow_rate"  class="load_img">
      {allow-rate}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{commentary}</td>
   <td style="padding:4px">
    <select name="allow_comm" class="load_img">
      {allow-comm}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{xfields_canal}</td>
   <td style="padding:4px">
    <select name="allow_more" class="load_img">
     {allow-full}
    </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_xfields_canal}', this, event, '500px')">[?]</a>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{add_auto}<a href="#" class="hintanchor" onMouseover="showhint('{help_add_auto}', this, event, '280px')">[?]</a></td>
   <td style="padding:4px">
    <select name="auto" class="load_img">
     {allow-auto}
    </select> &nbsp;&nbsp;
    {cronauto} <input type="text"  size="5" name="cron_auto" value="{cron-auto}"> в мин.<a href="#" class="hintanchor" onMouseover="showhint('{help_cronauto}', this, event, '420px')">[?]</a>&nbsp;&nbsp;{crone_glob} <input type="text"  size="5" name="kol_cron" value="{kol-cron}">
   </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300">{date_news}</td>
  <td style="padding:4px">
   <select name="news_date"  class="load_img">
    {date-format}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_date_news}', this, event, '360px')">[?]</a>
  </td>
  </tr>

    </table>

   </div>
  <!-- Конец -->

  <!-- Изображения -->
   <div class="dle_aTab">
  <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{parse_rss}</td>
   <td style="padding:4px">
   <select name="rss_parse" class="load_img">
    {rss-parse}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_parse_rss}', this, event, '500px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{down_pics}</td>
   <td style="padding:4px">


<select name="load_img" ">
{load-img}
</select>

	<a href="#" class="hintanchor" onMouseover="showhint('{help_post_rad}', this, event, '220px')">[?]</a>
	</td>
  </tr>

<tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{img_down_full}</td>
   <td style="padding:4px">
   <select name="parse_url_sel" class="load_img">
    {parse-url-sel}
   </select>
<a href="#" class="hintanchor" onMouseover="showhint('{help_img_down_full}', this, event, '420px')">[?]</a>
	</td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{add_pics_shortstory}</td>
   <td style="padding:4px">
   <select name="short_img" class="load_img">
    {short-images}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_add_pics_shortstory}', this, event, '420px')">[?]</a>
  </td>
  </tr>

 <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300">{dubl_host}</td>
  <td style="padding:4px">
   <select name="dubl_host" class="load_img">
    {dubl-host}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_dubl_host}', this, event, '420px')">[?]</a>
    </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300">{img_short}</td>
  <td style="padding:4px">
   <select name="one_serv"  class="load_img">
    {one-serv}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_img_short}', this, event, '420px')">[?]</a>
    </td>
  </tr>

  <tr style=" border-top:1px dashed #c4c4c4" colspan="2">
   <td style="padding:4px" width="304">{align} <a href="#" class="hintanchor" onMouseover="showhint('{help_align}', this, event, '420px')">[?]</a>
   </td>
   <td style="padding:4px">
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{align_short}</td>
   <td style="padding:4px">
    <select name="image_align"  class="load_img">
      {image-align}
    </select>
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{align_full}</td>
   <td style="padding:4px">
    <select name="image_align_full" class="load_img">
      {image-align-full}
    </select>
   </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{folder_pics}<a href="#" class="hintanchor" onMouseover="showhint('{help_folder_pics1}', this, event, '360px')"><font color=red><b>*</b></font></a></td>
 <td style="padding:4px"><input type="text" size="20" name="date" value="{date}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_folder_pics}', this, event, '360px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300"><b>{dim}</b>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_dim}', this, event, '420px')">[?]</a> 

</td>
  <td style="padding:4px">
  {dimdate}
   <select name="dim_date" class="load_img">
    {dim-date}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{help_dimdate}', this, event, '420px')">[?]</a>  
&nbsp;&nbsp;{dimsait}
   <select name="dim_sait" class="load_img">
    {dim-sait}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{help_dimsait}', this, event, '420px')">[?]</a>
   &nbsp;&nbsp;{dimcat}   
   <select name="dim_cat" class="load_img">
    {dim-cat}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{help_dimcat}', this, event, '420px')">[?]</a>
   &nbsp;&nbsp;Заг. новости   
   <select name="dim_week" class="load_img">
    {dim-week}
   </select>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300">{watermark_add}</td>
  <td style="padding:4px">
   <select name="allow_watermark"  class="load_img">
    {allow-water}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_watermark_add}', this, event, '420px')">[?]</a>  
{dop_watermark}
   <select name="dop_watermark" class="load_img">
    {dop-watermark}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{help_dop_watermark}', this, event, '420px')">[?]</a>
{wathost}  
   <select name="wat_host" class="load_img">
    {wat-host}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_wathost}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style=" border-top:1px dashed #c4c4c4" colspan="2">
   <td style="padding:4px" width="304">{place_watermark} <a href="#" class="hintanchor" onMouseover="showhint('{help_place_watermark}', this, event, '420px')">[?]</a>
   </td>
   <td style="padding:4px">
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{place_watermark_x}</td>
   <td style="padding:4px">
    <select name="x"  class="load_img">
      {x}
    </select>
   </td>
  </tr>
  <tr >
   <td style="padding:4px" >{place_watermark_x}</td>
   <td style="padding:4px">
    <select name="y" class="load_img">
      {y}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" >{padding_watermark}</td>
   <td style="padding:4px"><input align="center" type="text" size="5" name="margin" value="{margin}"> 
   <a href="#" class="hintanchor" onMouseover="showhint('{help_padding_watermark}', this, event, '420px')">[?]</a>
   </td>
  </tr>


</table>

   </div>
  <!-- Конец -->

  <!-- Дополнительные настройки -->
   <div class="dle_aTab">

  <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{title_probel}</td>
   <td width="768" style="padding:4px">
   <select name="title_prob" class="load_img">
    {title-prob}
   </select>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{br_text_html}</td>
   <td width="768" style="padding:4px">
   <select name="text_html" class="load_img">
    {text-html}
   </select>
  </td>
  </tr>

   <tr>
   <td colspan="2" style="border-top:1px dashed #c4c4c4" ></td>
  </tr>
  <tr >
    <td style="padding:4px" style="border-top:1px dashed #c4c4c4" >{html_tag_no_del}</td>
    <td style="padding:4px"><input type="text" size="30" name="teg_fix" value="{teg-fix}">  
    <a href="#" class="hintanchor" onMouseover="showhint('{help_html_tag_no_del}', this, event, '360px')">[?]</a>
    </td>
  </tr>



  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{shortstory_del}</td>
   <td width="768" style="padding:4px">
   <select name="clear_short" class="load_img">
    {clear-short}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_shortstory_del}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{fullstory_del}</td>
   <td width="768" style="padding:4px">
   <select name="clear_full" class="load_img">
    {clear-full}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_fullstory_del}', this, event, '420px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{fullstory_add}</td>
   <td width="768" style="padding:4px">
   <select name="add_full" class="load_img">
    {add-full}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_fullstory_add}', this, event, '420px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{kol_short_word}</td>
   <td width="768" style="padding:4px"><input type="text" size="5" name="kol_short" value="{kol-short}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_kol_short_word}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" >{pagebreak}</td>
  <td style="padding:4px" >
<input type="text" size="5" name="page_break" value="{page-break}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_pagebreak}', this, event, '420px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" >{shortstory_fulstory}</td>
  <td style="padding:4px" >
   <select name="short_full"  class="load_img">
    {short-full}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_shortstory_fulstory}', this, event, '420px')">[?]</a>
   </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{del_empty_line}</td>
   <td style="padding:4px">
   <select name="null" class="load_img">
    {null}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_del_empty_line}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{text_url_active}</td>
   <td style="padding:4px">
   <select name="text_url" class="load_img">
    {text-url}
   </select>

   <select name="text_url_sel" class="load_img">
    {text-url-sel}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_text_url_active}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{active_hide}</td>
   <td style="padding:4px">
   <select name="hide" class="load_img">
    {hide}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_active_hide}', this, event, '420px')">[?]</a>
  </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="300">{active_leech}</td>
   <td style="padding:4px">
   <select name="leech" class="load_img">
    {leech}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_active_leech}', this, event, '420px')">[?]</a>
  </td>
  </tr>

    {sinonim}

  <tr>
   <td style="padding:4px"  width="304">{ping}:</td>
   <td style="padding:4px">
   <select name="pings" class="load_img">
    {pings}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_ping}', this, event, '420px')">[?]</a>
  </td>
  </tr>


    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>

  <tr>
   <td style="padding:4px"  width="304">{traslate}</td>
   <td style="padding:4px">
   <select name="lang_on" class="load_img">
    {lang-on}
   </select>
   <select name="lang_in" class="load_img">
    {lang-in}
   </select>
   <b>&rArr;</b>
   <select name="lang_out" class="load_img">
    {lang-out}
   </select>
   <b>&rArr;</b>
   <select name="lang_outf" class="load_img">
    {lang-outf}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_traslate}', this, event, '420px')">[?]</a>
  </td>
  </tr>




    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>

    <tr>
        <td width="140" height="29" style="padding-left:5px;">{catalog_grab}<a href="#" class="hintanchor" onMouseover="showhint('{help_catalog_grab}', this, event, '300px')">[?]</a></td>
        <td style="padding:4px"><input type="text" name="symbols" size="5"  class="load_img" value="{symbol}">
	 auto <select name="auto_symbol" class="load_img">{auto-symbol}</select> кол-во символов <select name="auto_numer" class="load_img">{auto-numer}</select>
	</td>
    </tr>
    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
        <td width="140" height="29" style="padding:4px">{tags_lenta}</td>
        <td style="padding:4px"><input type="text" id="tags" name="tags" size="62"  class="load_img" value="{tags}"><a href="#" class="hintanchor" onMouseover="showhint('{help_tags_lenta}', this, event, '300px')">[?]</a>
    </td></tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px">{date_expires}</td>
   <td style="padding:4px"><input align="center" type="text" size="5" name="data_deap" class="load_img" value="{data-deap}">
   <select name="deap" class="load_img">
    {deap}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{hint_expires}', this, event, '420px')">[?]</a>
  </td>
  </tr>
	    <tr >
	        <td>&nbsp;</td>
	        <td style="padding:4 0 0 4px">{add_metatags_grab}<a href="#" class="hintanchor" onMouseover="showhint('{help_metatags_grab}', this, event, '220px')">[?]</a></td>
	    </tr>
	    <tr>
	        <td height="29" style="padding-left:5px;">{meta_title_grab}</td>
	        <td style="padding:4px"><input type="text" name="meta_title" style="width:250px;" class="load_img" value="{meta-title}">  {auto_meta_title}  <select name="auto_metatitle" class="load_img">
     {auto-metatitle}
    </select>
    </td>

  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{gen_meta_descr_grab}</td>
   <td style="padding:4px"><select name="descr_sel" class="load_img">{descr-sel}</select></td>
  </tr>

	    <tr>
	        <td height="29" style="padding-left:5px;">{meta_descr_grab}:<br><i>{help_meta_descr_grab}</i></td>
	        <td style="padding:4px"><textarea name="meta_descr" class="load_img" style="width:388px;height:70px;">{meta-descr}</textarea></td>
	    </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px"  width="304">{gen_meta_keys_grab}</td>
   <td style="padding:4px"><select name="keyw_sel" class="load_img">{keyw-sel}</select></td>
  </tr>

	    <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
	        <td height="29" style="padding-left:5px;">{meta_keys_grab}:<br><i>{help_meta_keys_grab}</i></td>
	        <td style="padding:4px"><textarea name="key_words" class="load_img" style="width:388px;height:70px;">{key-words}</textarea>
			</td>
	    </tr>

    </table>

   </div>
  <!-- Конец -->


  <!-- Визуализация -->
   <div class="dle_aTab" style="display:none;">
   <table cellpadding="" cellspacing="0" width="98%" align="center">

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_author}</td>
   <td style="padding:4px">
    <select name="show_autor" class="load_img">
     {show_autor}
    </select>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_date}</td>
   <td style="padding:4px">
    <select name="show_date" class="load_img">
     {show_date}
    </select>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_tag}</td>
   <td style="padding:4px">
    <select name="show_tegs" class="load_img">
     {show_tegs}
    </select>
     </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_bbcode}</td>
   <td style="padding:4px">
    <select name="show_code" class="load_img">
     {show_code}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_down}</td>
   <td style="padding:4px">
    <select name="show_down" class="load_img">
     {show_down}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
  <td style="padding:4px" width="300">{display_xfields}</td>
  <td style="padding:4px">
   <select name="show_f"  class="load_img">
    {show_f}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_display_xfields}', this, event, '360px')">[?]</a>
  </td>
  </tr>


  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_catalog_grab}</td>
   <td style="padding:4px">
    <select name="show_symbol" class="load_img">
     {show_symbol}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_url_grab}</td>
   <td style="padding:4px">
    <select name="show_url" class="load_img">
     {show-url}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_date_expires}</td>
   <td style="padding:4px">
    <select name="show_date_expires" class="load_img">
     {show_date_expires}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_meta_title_grab}</td>
   <td style="padding:4px">
    <select name="show_metatitle" class="load_img">
     {show_metatitle}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_meta_descr_grab}</td>
   <td style="padding:4px">
    <select name="show_metadescr" class="load_img">
     {show_metadescr}
    </select>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
   <td style="padding:4px" width="300">{display_meta_keys_grab}</td>
   <td style="padding:4px">
    <select name="show_keywords" class="load_img">
     {show_keywords}
    </select>
   </td>
  </tr>

    </table>

   </div>
  <!-- Конец -->

  <!-- Шаблоны -->
   <div class="dle_aTab" style="display:none;">
   <table cellpadding="" cellspacing="0" width="98%" align="center">
   <tr>
   <td colspan="4" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><center>{templates_fullstory}</center></td>
  </tr>
  <tr>
   <td style="padding:4px"  align="center">{delicate_control_templates}
   <select name="end_short" class="load_img">
    {end-short}
   </select>
 </td>
  </tr>
  </table>

  <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:4px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_delicate_control_templates}</td>
  </tr>
  </table>
   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start_template">{start-template}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{templates_fulltstory}', this, event, '400px')">[?]</a>
   </td></tr>
</table>
   <table cellpadding="0" cellspacing="0" width="98%" align="center">
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px" align="right">{dops_full}</td>
    <td style="padding:4px"><input type="text"  size="50" name="dop_full" value="{dop-full}">

  <select name="full_url_and" class="load_img">
    {full-url-and}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_dop_ful}', this, event, '400px')">[?]</a>
   </td>
  </tr>
</table>
<input type="hidden" name="kol_xfields" value="{kol-xfields}" />
{xfields-template}

  <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">
{rekl_fulltstory}
  </tr>
  </table>



  </div>
  <!-- Конец -->

  <!-- Фильтр, Авторизация -->
   <div class="dle_aTab" style="display:none;">
   <table cellpadding="" border="0" cellspacing="0" width="98%" align="center">
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">

  <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><b>Дополнительное слово в заголовке:</b></td>
  </tr>
  <tr>

   <tr>
   <td colspan="2" style="border-top:1px dashed #c4c4c4" ></td>
  </tr>
  <tr >
    <td style="padding:4px" style="border-top:1px dashed #c4c4c4" >в начале</td>
    <td style="padding:4px"><input type="text" size="30" name="s_title" value="{s-title}">  <a href="#" class="hintanchor" onMouseover="showhint('пример: лучший|хороший|плохой<br><br>При данном примере слова разделённые символом \'|\' будут подставляться случайно', this, event, '400px')">[?]</a>
    </td>
  </tr>

  <tr >
    <td style="padding:4px" >в конце</td>
    <td style="padding:4px"><input type="text" size="30" name="end_title" value="{end-title}">  <a href="#" class="hintanchor" onMouseover="showhint('пример: скачать|бесплатно|скачать без смс<br><br>При данном примере слова разделённые символом \'|\' будут подставляться случайно', this, event, '400px')">[?]</a>
    </td>
  </tr>

  <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><b>Дополнительная фраза/текст в короткой новости:</b></td>
  </tr>
  <tr>

  <tr>
   <td style="padding:4px">в начале</td>
   <td style="padding:4px"><textarea class="load_img" rows="4" cols="60" name="sfr_short">{sfr-short}</textarea>
   </td>
  </tr>

  <tr>
   <td style="padding:4px">в конце</td>
   <td style="padding:4px"><textarea class="load_img" rows="4" cols="60" name="efr_short">{efr-short}</textarea>
   </td>
  </tr>

  <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" ><b>Дополнительная фраза/текст в полной новости:</b></td>
  </tr>
  <tr>

  <tr>
   <td style="padding:4px">в начале</td>
   <td style="padding:4px"><textarea class="load_img" rows="4" cols="60" name="sfr_full">{sfr-full}</textarea>
   </td>
  </tr>

  <tr>
   <td style="padding:4px">в конце</td>
   <td style="padding:4px"><textarea class="load_img" rows="4" cols="60" name="efr_full">{efr-full}</textarea>

   </td>
  </tr>



  <tr style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >
   <td style="padding:4px" valign="top" >{keyword}</td>
   <td style="padding:4px"><textarea class="load_img" rows="4" cols="60" name="keywords">{keywords}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_keyword}', this, event, '400px')">[?]</a>
   </td>
  </tr>


  <tr style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >
   <td style="padding:4px" valign="top" >{stop_keyword}</td>
   <td style="padding:4px"><textarea class="load_img" rows="4" cols="60" name="stkeywords">{stkeywords}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_stop_keyword}', this, event, '400px')">[?]</a>
   </td>
  </tr>



   <tr>
   <td colspan="2" style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >{authorization}</td>
  </tr>
<tr>
   <td style="padding:4px"  width="250">{login_pass}</td>
   <td style="padding:4px">
   <select name="log_pas" class="load_img">
    {log-pas}
   </select>
   </td>
  </tr>

  <tr>
   <td style="padding:4px" valign="top" >{cookiess}</td>
   <td style="padding:4px">
    <textarea class="load_img" rows="4" cols="60" name="cookies">{cookies}</textarea>
    <a href="#" class="hintanchor" onMouseover="showhint('{help_authorization}', this, event, '400px')">[?]</a>
   </td>
  </tr>




  </table>

   </div>


 <!-- Конец -->

 <!-- Авторы -->
   <div class="dle_aTab" style="display:none;">
   <table cellpadding="" cellspacing="0" width="98%" align="center">
 <tr>
   <td colspan="2" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4" >{author_title}</td>
  </tr>

  <tr>
  <td style="padding:4px" >{group_author_default}</td>
  <td style="padding:4px">
   <select name="groups[]" class="cat_select" multiple>
    {groups}
   </select>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_group_author_default}', this, event, '400px')">[?]</a>
  </td>
  </tr>

  <tr>
   <td style="padding:4px" valign="top" >{author}</td>
   <td style="padding:4px"><textarea class="load_img" rows="10" cols="40" name="Autors">{Autors}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_author}', this, event, '400px')">[?]</a>
   </td></tr>
  </table>
  
  </div>

<!-- Конец -->

  <!-- HTML -->

   <div class="dle_aTab" style="display:none;">
   <table cellpadding="" border="0" cellspacing="0" width="98%" align="center">
                  
  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px; width:150;">{templates_page}</td>
    <td style="padding:4px">{BB_code}<input  onclick="setFieldName(this.name)" type="text"  style="width:79%;" class="load_img" name="full_link" value="{full-link}">
   <a href="#" class="hintanchor" onMouseover="showhint('{help_templates_page}', this, event, '400px')">[?]</a>
   </td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px">{steppage}</td>
    <td style="padding:4px"><input type="text"  size="10" name="step_page" class="load_img" value="{step-page}"><a href="#" class="hintanchor" onMouseover="showhint('{help_steppage}', this, event, '400px')">[?]</a></td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px">{start_page}</td>
    <td style="padding:4px"><input type="text" class="load_img" size="10" name="so" value="{so}"></td>
  </tr>

  <tr style="border-bottom:1px dashed #c4c4c4; border-top:1px dashed #c4c4c4">
    <td style="padding:4px">{end_page}</td>
    <td style="padding:4px"><input type="text" class="load_img" size="10" name="po" value="{po}"></td>
  </tr>

   <tr align="center">
   <td colspan="4" >{description_html}</td>
</tr>
</table>
  <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_shortstory}</td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="ful_start">{ful-start}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_templates_shortstory}', this, event, '400px')">[?]</a>
    </td>
   </tr>
</table>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
   <td colspan="4"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_title}</td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start_title">{start-title}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_templates_title}', this, event, '400px')">[?]</a>
   </td>
  </tr>
</table>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
   <td colspan="4"  style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_url_fullstory}</td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="sart_link">{sart-link}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_templates_url_fullstory}', this, event, '400px')">[?]</a>
  </tr>
</table>
 <table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
   <td colspan="4"  style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_news}</td>
</tr>
</table> 
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="center">
  <td  align="center"  style="padding:4px">{delicate_control_templates}
   <select name="end_link" class="load_img">
    {end-link}
   </select>
 </td>
</tr>
</table>
  <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:4px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_delicate_control_templates}</td>
  </tr>
  </table>
  <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
   <textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start_short">{start-short}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{start_templates_html}', this, event, '400px')">[?]</a>
   </td>   
  </tr>
</table>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_tag}</td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="sart_cat">{sart-cat}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{start_templates_html_cat}', this, event, '400px')">[?]</a>
   </td>
  </tr>
</table>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center" style="padding:4px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4">{templates_data}</td>
  </tr>
  <tr align="left">
<td width="17%" align="center" valign="middle"  style="padding:4px" ></td>
   <td width="83%" style="padding:4px">{BB_code}
<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="shab_data">{shab-data}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{start_templates_html_data}', this, event, '400px')">[?]</a>
   </td>
  </tr>
</table>

  <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:4px; border:1px solid #c4c4c4; background-color:#fafafa;">
{rekl_HTML}
   </td>
  </tr>
  </table>

</div>
<!-- Конец -->



 <!-- Замены -->
   <div class="dle_aTab" style="display:none;">



<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search_link}</center></td>
  </tr>
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{link_start_d}</td>
    <td style="padding:4px">{BB_code}<input  onclick="setFieldName(this.name)" type="text"  style="width:79%;" class="load_img" name="link_start_del" value="{link-start-del}">
   </td>
   </tr>
</table>


<table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{link_finish_d}</td>
    <td style="padding:4px">{BB_code}<input  onclick="setFieldName(this.name)" type="text"  style="width:79%;" class="load_img" name="link_finish_del" value="{link-finish-del}">
   </td>
   </tr>
</table>




<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search_title}</center></td>
  </tr>
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{title_start_del}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="s_del">{s-del}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_search_title}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>


<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{title_end_del}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="end_del">{end-del}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_subst_title}', this, event, '400px')">[?]</a>
   </td>
    </tr> 
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_search}</td>
  </tr>
  </table>
<br/>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search}</center></td>
  </tr>
  <tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{search_line}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="delate">{delate}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{help_search_line}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>


<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr align="left">
   <td width="17%" align="center" valign="middle"  style="padding:4px" >{subst_line}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="inser">{inser}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{help_subst_line}', this, event, '400px')">[?]</a>
   </td>
    </tr> 
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">{help_search}</td>
  </tr>
  </table>
<br/>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>
   <td colspan="4" align="center"  style="padding:6px; border-bottom:1px dashed #c4c4c4;  border-top:1px dashed #c4c4c4"><center>{templates_search_regular}</center></td>
  </tr>
  <tr>
   <td width="17%" align="center" valign="middle"  style="padding:4px">{expression}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="start">{start}</textarea>
      <a href="#" class="hintanchor" onMouseover="showhint('{regular_expression}', this, event, '400px')">[?]</a>
   </td>
   </tr>
</table>
<table cellpadding="" cellspacing="0" width="98%" align="center">
<tr>                                                        
    <td width="17%" align="center" valign="middle"  style="padding:4px">{paste}</td>
   <td width="83%" style="padding:4px">{BB_code}<textarea onclick="setFieldName(this.name)" class="load_img" style="width:79%; height:100px" name="finish">{finish}</textarea>
   <a href="#" class="hintanchor" onMouseover="showhint('{end_regular_expression}', this, event, '400px')">[?]</a>
   </td>
   </tr> 
  </table>

   <table cellpadding="" cellspacing="0" width="98%" align="center">
  <tr>
   <td  style="padding:8px; border:1px solid #c4c4c4; background-color:#fafafa;">
{help_search}<br/> <font color="green">к примеру:
<br /><!--code1--><div class="scriptcode"><!--ecode1-->Убрать ссылку на фото в новости?<br />Выражение&#58; &#60;a{skip}href={skip}&#62;&#60;img{skip}src=&#34;{get}&#34;{skip}&#62;&#60;/a&#62;<br />Вставляем&#58; &#91;IMG&#93;{1}&#91;/IMG&#93;<!--code2--></div><!--ecode2--></font>
   </td>
  </tr>
</table>
  </div>

<!-- Конец -->

 </div>


 <br/><div class="unterline"></div>


</td></tr>
</table>


 <script type="text/javascript" src="engine/inc/plugins/tabs.js"></script>
 <script type="text/javascript">
   initTabs('rss_panel', Array('{Options}', '{Images}', '{Dop_Options}', '{Visualization}', '{Templates}', '{Filter_Authorization}', '{Authors}', '{For_HTML}', '{Replacements}'),0, '100%');
 </script>
