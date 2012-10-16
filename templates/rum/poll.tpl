<div class="full-news" style="border-bottom: 1px solid #9E9E9E;">

    <div class="full-news-top">
	<h1>{title}</h1>
	<div class="title2">{question}</div>
    </div>
    
    <div class="full-news-content">
	{list}Всего проголосовало: {votes}
	[not-voted]<br /><input type="button" onclick="doPoll('vote'); return false;" value="Голосовать" />&nbsp;<input type="button" onclick="doPoll('results'); return false;" value="Результаты"/>[/not-voted]
    
    <div style="clear: both; height: 10px;"></div>
    
    </div>


</div>
