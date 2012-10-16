{poll}
<div class="full-news">
	<div class="full-news-top">
	[group=1,2,3]
	<div class="full-edit-icon">
	[edit]<img src="{THEME}/images/edit.png" width="16" alt="" border="0" />[/edit]
	</div>
	[/group]
	[not-group=5]
	<div class="full-fav-icon">
	{favorites}
	</div>
	[/not-group]
		<h1>{title}</h1>
		<div class="title2">{title2}</div>
	</div>
	<div class="full-news-content">
	<img src="{THEME}/images/spacer.png" border="0" alt="" />
	 {full-story}
		<div style="clear: both;height: 20px;"></div>
		<div>
			Ссылки:<br/>
			[src_link]<br/>
		</div>
		[not-group=5]
		[edit-date]
		<br />
		{edit-date}&nbsp;&nbsp;<strong>{editor}</strong><br />
		[edit-reason]<strong style="color: red;">Причина:</strong> {edit-reason}[/edit-reason]
		[/edit-date]
		[/not-group]
		<br/>
		<br/>
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
				<div class="reit"> [rating]{rating}[/rating] </div>
			</div>
		</div>
		
		<div style="clear: both;"></div>
		[related-news]
		<div class="rel-news">
			<h4>
			Похожие новости:
			</h4>
			<div style="clear: both;"></div>
			{related-news}
			<div style="clear: both;"></div>
		</div>
		[/related-news] </div>
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
{clk_script}