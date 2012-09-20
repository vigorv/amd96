<div class="flex_popup fp_cp">
<div class="fp_cont">
    <div class="fp_c fp_tl"></div>
    <div class="fp_c fp_tr"></div>
    <div class="fp_c fp_bl"></div>
    <div class="fp_c fp_br"></div>			
    <div class="fp_cont_in" style="display:inline;">
        <div class="c-popup" id="confirm_mess">
            <a href="#" title="close" onclick="DeletePostFalse();return false;" class="upp_close"></a>
            
            [delete_post]
            <div class="up_h"><span>Подтверждение действия.</span></div>
            <div class="up_links">Вы действительно хотите удалить выбранное сообщение?<br /><br /></div>
            <div class="up_links">
                <div class="butt" style="float:left"><span><span><a href="#" onclick="{onclick_func}return false;" title="Продолжить">Продолжить</a></span></span></div>
                <div class="butt butt_disable" style="float:right;padding-right:10px;"><span><span><a href="#" onclick="DeletePostFalse();return false;" title="Отменить">Отменить</a></span></span></div>
            </div>
            [/delete_post]
            
            [reputation]
            <div class="up_h"><span>{title}</span></div>
            <div class="up_links">{text}<br /></div>
            <div class="up_links">
                [rep_next_step]
                <div class="butt" style="float:left"><span><span><a href="#" onclick="{onclick_func}return false;" title="Продолжить">Продолжить</a></span></span></div>
                [/rep_next_step]
                <div class="butt butt_disable" style="float:right;padding-right:10px;"><span><span><a href="#" onclick="DeletePostFalse();return false;" title="Закрыть">Закрыть</a></span></span></div>
            </div>
            [/reputation]
                        
            <div class="up_links"></div>
        </div>
    </div>			
    </div>
</div>
