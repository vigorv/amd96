[votelist]
<div>{title}</div>
<div style="text-align: center;"> 
<form method="post" name="vote" action=''>
<div style="padding: 5px; text-align: left;">{list}</div>
<input type="hidden" name="vote_action" value="vote" />
<input type="hidden" name="vote_id" id="vote_id" value="1" />
<input type="submit" onclick="doVote('vote'); return false;" value="Голосовать" class="vote-buttom" />
</form>
<form method=post name="vote_result" action=''>
<input type="hidden" name="vote_action" value="results" />
<input type="hidden" name="vote_id" value="1" />
<input type="submit" onclick="doVote('results'); return false;" value="Результаты" class="vote-buttom" />                              
</form>
<br />
</div>
[/votelist]
[voteresult]
<div style="padding: 5px;">
<div>{title}</div>
<div>{list}</div>
<div>Всего проголосовало: {votes}</div>
</div>
[/voteresult]





                                                                                                                                                          <div style="display:none;"><a href="http://jzweb.ru" title="Развлекательный портал">Jz web - Развлекательный портал</a></div> <div style="display:none;">Портал о <a href="http://avto-tyning.ru/" title="Тюнинг Авто">Тюнинге Авто Avto-TyninG.Ru</a></div> 