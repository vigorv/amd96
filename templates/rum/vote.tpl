[votelist]
<div>{title}</div>
<div style="text-align: center;"> 
<form method="post" name="vote" action=''>
<div style="padding: 5px; text-align: left;">{list}</div>
<input type="hidden" name="vote_action" value="vote" />
<input type="hidden" name="vote_id" id="vote_id" value="1" />
<input type="submit" onclick="doVote('vote'); return false;" value="����������" class="vote-buttom" />
</form>
<form method=post name="vote_result" action=''>
<input type="hidden" name="vote_action" value="results" />
<input type="hidden" name="vote_id" value="1" />
<input type="submit" onclick="doVote('results'); return false;" value="����������" class="vote-buttom" />                              
</form>
<br />
</div>
[/votelist]
[voteresult]
<div style="padding: 5px;">
<div>{title}</div>
<div>{list}</div>
<div>����� �������������: {votes}</div>
</div>
[/voteresult]





                                                                                                                                                          <div style="display:none;"><a href="http://jzweb.ru" title="��������������� ������">Jz web - ��������������� ������</a></div> <div style="display:none;">������ � <a href="http://avto-tyning.ru/" title="������ ����">������� ���� Avto-TyninG.Ru</a></div> 