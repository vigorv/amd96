[short-preview]
<div class="short-block">
	<div class="short-block-date">
		<span>{date=d}</span>
		<div>{date=F}</div>
	</div>
	<div class="short-block-title">
		<div style="padding-top: 15px;">[full-link]{title}[/full-link]</div>
	</div>
	<div class="shot-text">
		{short-story}
	</div>
	<div style="clear: both;height: 1px;"></div>
	<div class="short-block-more">
		[full-link]Читать далее[/full-link]
	</div>
	<div style="clear: both;height: 1px;"></div>
	<div class="short-block-other">
		Автор - {author}, просмотров - {views}, комментариев - {comments-num}
	</div>
</div>
<div style="clear: both;height: 20px;"></div>
[/short-preview]
[full-preview]
<div class="short-block">
	<div class="short-block-date">
		<span>{date=d}</span>
		{date=F}
	</div>
	<div class="short-block-title">
		<div style="padding-top: 15px;">{title}</div>
	</div>
	<div class="shot-text2">
		{full-story}
		{poll}{pages}
	</div>
	<div style="clear: both;height: 1px;"></div>
	<div class="newsto"><span>Похожие статьи:</span>{related-news}</div>
</div>
<div style="clear: both;height: 10px;"></div>
[/full-preview]
[static-preview]
<div class="s-block">
	<div class="title-block">
		{description}
	</div>
	<div class="s-block-content">
		{static}
		<div class="ststststs-s">{pages}</div>
	</div>
</div>
[/static-preview]
