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
	[group=1]
	<div class="full-cloud-icon">
	    <a href="http://safelib.com/api/cloudButton?partner_id=6&partner_item_id={news-id}">
		<img width="20px" height="13px" src="http://safelib.com/api/statusimage?partner_id=6&partner_item_id={news-id}" />
	    </a>
	</div>
	[/group]
	[/not-group]
		<h1>{title}</h1>
		<div class="title2">{title2}</div>
	</div>
	<div class="full-news-content-bf">
	[xfgiven_poster4full]
	<div class="fn-lenta"></div>
	<div class="fn-poster" align="center">
	<a href="[xfvalue_poster4full]" onclick="return hs.expand(this)">
<img src="[xfvalue_poster4full]" alt="Mega Poster for {title2}" title="Mega Poster for {title2}"/>
</a>
	</div>	
	<br />
	[/xfgiven_poster4full]
	<div class="fn-about"></div>
	</div>
	<div class="full-news-content">
	<img src="{THEME}/images/spacer.png" widht="0" height="0" border="0" alt="" />
	 {full-story}
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
		<div style="clear: both;"></div>
	<div class="fn-lenta"></div>
	<div align="center">
		<img src="{THEME}/movies_full/4havefun.gif" border="0" />
	</div>
		[xfgiven_m_original_name]
		<table id="zebra">
			<tbody>
				<tr>
					<td width="30%"><strong>Оригинальное название</strong>:</td>
					<td>[xfvalue_m_original_name]</td>
				</tr>
				<tr>
					<td><strong>Год выхода</strong>:</td>
					<td>[xfvalue_m_year]</td>
				</tr>
				<tr>
					<td><strong>Страна</strong>:</td>
					<td>[xfvalue_m_country]</td>
				</tr>
				<tr>
					<td><strong>Режиссёр</strong>:</td>
					<td>[xfvalue_m_director]</td>
				</tr>
				<tr>
					<td><strong>Актёры</strong>:</td>
					<td>[xfvalue_m_actors]</td>
				</tr>
				<tr>
					<td><strong>Другая информация</strong>:</td>
					<td>[xfvalue_m_other]</td>
				</tr>
			</tbody>
		</table>
		[/xfgiven_m_original_name]
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
			Также рекомендуем:
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