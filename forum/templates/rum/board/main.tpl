[category_header]
<div class="category_block cb_color">
<h3 id="c_{category_id}"><a href="{category_link}">{category_name}</a><span title="свернуть/развернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
<div class="cat_list">
<ol>
[/category_header]
[forum]
    <li class="cl_item">
        [flink_page]
        <div class="cl_f">
        <h4><a href="{forum_link}" [flink_npage]target="_blank"[/flink_npage]>{forum_name}</a></h4>
        <img src="{TEMPLATE}/forum_icons/{forum_img}" alt="" class="f_icon" />
        {forum_desc}
        {sub_forums}
        </div>
        <div class="cl_last">
            <div class="databox">
                <div class="tlast">
                <font class="flink_view">Просмотров: {count_view}</font>
                </div>
            </div>
        </div>
        [/flink_page]
        
        [not_flink_page]
		<div class="cl_last">
            <div class="databox">
                <div class="mavatar"><img src="{last_post_member_avatar}" width="50" height="50" /></div>
                <div class="tlast">
                Тема: <a href="{last_topic_link}" title="{last_title_full}">{last_title}</a><br />
                Автор: [member_post]<a href="{last_post_member_link}">[/member_post]{last_post_member}[member_post]</a>[/member_post]<br />
                <font class="cl_date">{last_post_date}</font>
                </div>
            </div>
        </div>	
        <div class="cl_stats">
    	   <ol>
                <li class="cls_ans">{forum_topics} Тем</li>
                <li>{forum_posts} Ответов</li>
            </ol>
        </div>
        <div class="cl_f">
        <h4><a href="{forum_link}">{forum_name}</a></h4>
        <img src="{TEMPLATE}/forum_icons/{forum_img}" alt="" class="f_icon" title="{forum_status}" />
        {forum_desc}
        [hiden]<i><font color="#993333" size="1.34em">Скрыто сообщений: {posts_hiden}; тем: {topics_hiden}</font></i><br />[/hiden]
        [forum_moders]Модераторы: {forum_moders}[/forum_moders]
        {sub_forums}
        </div>
        [/not_flink_page]
    </li>
[/forum]
[category_footer]
</ol>
<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
</div>
</div>
[/category_footer]