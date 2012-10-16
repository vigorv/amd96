{poll}
<div class="full-news">
	<div class="full-news-top">
		<h1>{title}	[edit]<img src="{THEME}/images/edit.png" width="16" alt="" border="0" />[/edit]
</h1>
	</div>
	
	
	<div class="full-news-content">
		{full-story}
		<div style="clear: both;height: 10px;"></div>
		[not-group=5]
		[edit-date]
		<br />
		{edit-date}&nbsp;&nbsp;<strong>{editor}</strong><br />
		[edit-reason]<strong style="color: red;">Причина:</strong> {edit-reason}[/edit-reason]
		[/edit-date]
		[/not-group]
		<div class="soc-zakladi">
			<div class="ttfav"></div>
			<span>расскажи друзьям</span>
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;height: 10px;"></div>
		
		<div class="full-fon-middle">
			<div class="categs-full">
				<div class="icon-user"></div>&nbsp;{author}&nbsp;&nbsp;
				<div class="icon-calendar"></div>{date=d M Y}&nbsp;&nbsp;
				<div class="icon-eye-open"></div>{views}&nbsp;&nbsp;
				<div class="icon-edit"></div>[com-link]{comments-num}[/com-link]&nbsp;&nbsp;
				<br />
				<div class="icon-th-list"></div>&nbsp;{link-category}
			</div>
			<div class="right-info">
				<div class="date">{date=d M Y}</div>
				<div class="reit"> [rating]{rating}[/rating] </div>
			</div>
		</div>
		
		<div style="clear: both;height: 10px;"></div>
		[related-news]
		<div class="rel-news">
			<h4>Похожие новости:</h3>
			<div style="clear: both;"></div>
			{related-news}
			<div style="clear: both;"></div>
		</div>
		[/related-news]
	</div>
	
	<div class="koment-fon">
		[not-comments]
			<strong>Комментариев пока еще нет.</strong>
		[/not-comments]
		[comments]
			<strong>Комментарии к новости:</strong>
		[/comments]
		{addcomments}<br /><br />
		{comments}
	</div>
	
	<div class="full-fon-bottom">
	</div>
</div>