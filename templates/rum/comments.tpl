<div class="comment-block">
	<div class="comment-block-left">
		<div class="comment-images"><img src="{foto}" alt="{fullname}" /></div>
		<strong class="author">{author}</strong>
	</div>
	[not-aviable=lastcomments]
	<div class="id-koment">id# <strong>{comment-id}</strong></div>
	[group=1]<div class="ip-koment" style="top: 16px;"><strong>{ip}</strong></div>[/group]
	[/not-aviable]
	[aviable=lastcomments]
	<div class="id-koment" style="top: 3px;">id# <strong>{comment-id}</strong></div>
	[group=1]<div class="ip-koment"><strong>{ip}</strong></div>[/group]
	[/aviable]
	<div class="usericon-koment">{group-icon}</div>
	[not-aviable=lastcomments]
	<div class="date-koment">дата - {date=d-m-Y H:i}</div> 
	<div class="status-avtora">статус автора - [online]<img src="{THEME}/images/online.png" style="vertical-align: middle;" title="Пользователь Онлайн" alt="Пользователь Онлайн" />[/online][offline]<img src="{THEME}/images/offline.png" style="vertical-align: middle;" title="Пользователь offline" alt="Пользователь offline" />[/offline] </div>
	[/not-aviable] 
	[aviable=lastcomments]
	<div class="date-koment" style="top: 3px;">дата - {date=d-m-Y H:i}</div> 
	<div class="location-koment">{news_title}</div>
	<div class="status-avtora" style="top: 3px;">статус автора - [online]<img src="{THEME}/images/online.png" style="vertical-align: middle;" title="Пользователь Онлайн" alt="Пользователь Онлайн" />[/online][offline]<img src="{THEME}/images/offline.png" style="vertical-align: middle;" title="Пользователь offline" alt="Пользователь offline" />[/offline] </div>
	[/aviable] 
	<div class="comment-block-right">
		<div class="comment-block-right2">
			<div class="comment-text">
				{comment}[signature]<br /><br />--------------------<br /><div class="slink">{signature}</div>[/signature]<br />
				<div class="comment-text-more">
				{mass-action}
				[fast]<span class="btn comm_btn_style"><span class="icon-indent-left"></span>цитировать</span>[/fast]
				[com-edit]<span class="btn comm_btn_style"><span class="icon-edit"></span>изменить</span>[/com-edit]
				[com-del]<span class="btn comm_btn_style"><span class="icon-trash"></span>удалить</span>[/com-del]
				[complaint]<span class="btn comm_btn_style"><span class="icon-thumbs-down"></span>жалоба</span>[/complaint]
				</div>
			</div>		
		</div>
	</div>
</div>
<div style="clear: both;height: 10px;"></div>