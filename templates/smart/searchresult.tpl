[searchposts]
[fullresult]
<div class="main-news">
<div class="main-news-all">
    [edit]
    <div class="short-edit-icon">
    <img src="{THEME}/images/edit.png" width="16" alt="" border="0" />
    </div>
    [/edit]
    
    <div class="right-col-main-news">
      <h1>[result-link]#{search-id} {result-title}[/result-link]</h1>
      <div class="main-news-content-forum">{result-text}
        <div style="clear: both;"></div>
      </div>
    </div>
    <div class="main-news-info">
      <div class="categs-search">
	  	<div style="padding: 4px 0px 0px 7px;">
      	{favorites}
      	<div class="icon-calendar"></div>{date=d M Y}&nbsp;&nbsp;
        <div class="icon-eye-open"></div>{views}&nbsp;&nbsp;
        <div class="icon-user"></div>{result-author}&nbsp;&nbsp;
        <div class="icon-th-list"></div>{link-category}
		</div>
			  	</div>

	  <div style="clear: both;"></div>
    </div>
  </div>
</div>

[/fullresult]
[shortresult]
<section>
	<div class="blog_item">
		<div class="blog_head">
			<h2>#{search-id} [result-link]{result-title}[/result-link]</h2>
			<span>
				
				<i class="icon-user icon-white" title="Автор"></i>&nbsp;<a onclick="ShowProfile('\{result-author\}', 'http://rumedia.ws/user/\{result-author\}/', '0'); return false;" href="http://rumedia.ws/user/\{result-author\}/">{result-author}</a> &nbsp; 
				<i class="icon-calendar icon-white" title="Дата"></i>&nbsp;{date=d M Y} &nbsp; 
			</span>
		</div>
	</div>
</section>
[/shortresult]
[/searchposts]
[searchcomments]
[fullresult]
<div class="s-block">
<div class="comment-block">
	<div class="comment-block-left">
    	<div class="comment-images">
		<img src="{foto}" alt="{fullname}" />
        </div>
	</div>
	<div class="comment-block-right">
		<div class="comment-block-right2">
			<div class="comment-text">
				<div class="comment-text-title">
					Написал: <strong>{result-author}</strong> {result-date} в <strong>[result-link]{result-title}[/result-link]</strong>
				</div>
				{result-text}
				<div class="comment-text-more">[com-edit]изменить[/com-edit] [com-del]удалить[/com-del]</div>
			</div>		
		</div>
	</div>
</div>
</div>
<div style="clear: both;height: 10px;"></div>
[/fullresult]
[shortresult]
<div class="s-block">
<div class="comment-block">
	<div class="comment-block-left">
    	<div class="comment-images">
		<img src="{foto}" alt="{fullname}" />
        </div>
	</div>
	<div class="comment-block-right">
		<div class="comment-block-right2">
			<div class="comment-text">
				<div class="comment-text-title">
					Написал: <strong>{result-author}</strong> {result-date} в <strong>[result-link]{result-title}[/result-link]</strong>
				</div>
				{result-text}
				<div class="comment-text-more">[com-edit]изменить[/com-edit] [com-del]удалить[/com-del]</div>
			</div>		
		</div>
	</div>
</div>
</div>
<div style="clear: both;height: 10px;"></div>
[/shortresult]
[/searchcomments]
