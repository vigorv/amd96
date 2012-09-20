    <div class="paginator cle">
		<ol>
            [page_box]
            <li><a href="#" onclick="Mini_Window(this, '120');return false;" title="Перейти на страницу">Страниц: {number_pages}</a>
            <span class="mini_window_content">
            <input type="text" name="page" style="width:40px;height:15px;" /> <input type="button" class="mini_window_button" onclick="Script_Page(this);return false;" value="Перейти" />
            </span>
            </li>
            [/page_box]
			{pages}
		</ol>
	</div>
	<!--paginator end-->