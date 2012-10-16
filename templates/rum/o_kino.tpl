{poll}
<div class="main-news">
<div class="main-news-all">
    [edit]
    <div class="short-edit-icon">
    <img src="{THEME}/images/edit.png" width="16" alt="" border="0" />
    </div>
    [/edit]
    
    <div class="main-news-image">[full-link]<span class="main-news-img-fon"><img src="{image-1}" alt="{title}" /></span>[/full-link] </div>
    <div class="right-col-main-news">
      <h1>[full-link][group=1]id# {news-id}&nbsp;[/group]{title}[/full-link]</h1>
      {title2}
      <div class="main-news-content"> {short-story limit="700"}
        <div style="clear: both;"></div>
      </div>
    </div>
    <div class="main-news-info">
      <div class="main-news-more2"> [full-link]Читать далее[/full-link]</div>
      <div class="categs">
	  	<div style="padding: 4px 0px 0px 7px;">
      	{favorites}
      	<div class="icon-calendar"></div>{date=d M Y}&nbsp;&nbsp;
        <div class="icon-eye-open"></div>{views}&nbsp;&nbsp;
        <div class="icon-edit"></div>[com-link]{comments-num}[/com-link]&nbsp;&nbsp;
        <div class="icon-th-list"></div>{link-category}
		</div>
			  	</div>

      <!--
      <div class="date">{date=d M Y}</div>
      -->
      <div class="reit"> [rating]{rating}[/rating] </div>
	  <div style="clear: both;"></div>
    </div>
  </div>
</div>
