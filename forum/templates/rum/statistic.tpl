	<div class="statistics [posts]st_noside[/posts] cle">
		<div id="st_info">
			<div id="st_info_in">
                [group_online]
				<script type="text/javascript">
  	            $(window).load(function(){
                    Show_StatsBlock_Online("{global_do}", "{global_op}", "{global_id}");
                });
                </script>
                <span id="statsblock_online"></span>
                [/group_online]
                [birthday]	
				<script type="text/javascript">
  	            $(window).load(function(){
                    Show_StatsBlock_Birthday();
                });
                </script>
                <span id="statsblock_birthday"></span>
                [/birthday]
			</div>
		</div>
        [topics]
        <div id="st_legend">
            <h2>����������� ���:</h2>
			<ol class="cle">
				<li>��������<img src="{TEMPLATE}/images/topic/ico-fold-open.png" width="25" height="20" alt="�����" /></li>
				<li>��� �������<img src="{TEMPLATE}/images/topic/ico-fold-open-empty.png" width="25" height="20" alt="�����" /></li>
				<li>�������<img src="{TEMPLATE}/images/topic/ico-fold-hot.png" width="25" height="20" alt="�����" /></li>
				<li>��� �������<img src="{TEMPLATE}/images/topic/ico-fold-hot-empty.png" width="25" height="20" alt="�����" /></li>
				<li>�����<img src="{TEMPLATE}/images/topic/ico-fold-vote.png" width="25" height="20" alt="�����" /></li>
				<li>��� �������<img src="{TEMPLATE}/images/topic/ico-fold-vote-empty.png" width="25" height="20" alt="�����" /></li>
				<li>��������<img src="{TEMPLATE}/images/topic/ico-fold-closed.png" width="25" height="20" alt="�����" /></li>
			</ol>
        </div>
        [/topics]
        [stats]
        <div id="st_stat">
			<h2>���������� ������:</h2>
			<dl class="cle">
				<dt>���������</dt>
				<dd><strong>{stat_post}</strong></dd>
				<dt>�������������</dt>
				<dd><strong>{stat_users}</strong></dd>
				<dt>����� ��������</dt>
				<dd><a href="{stat_new_member_link}">{stat_new_member}</a></dd>
				<dt>������ ������������</dt>
				<dd>{online_max}<span>{online_max_date}</span></dd>
			</dl>
        </div>
        [/stats]
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
		<div class="co co5 co-stat"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>