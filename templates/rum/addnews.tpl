<link rel="stylesheet" type="text/css" href="engine/skins/chosen/chosen.css"/>
<script type="text/javascript" src="engine/skins/chosen/chosen.js"></script>
<script type="text/javascript">
$(function(){
	$('#category').chosen({allow_single_deselect:true, no_results_text: 'Ничего не найдено'});
});
</script>
<div class="s-block">
  <div class="title-block"> Публикация новости на сайте </div>
  <div class="s-block-content">
    <table id="zebra-nborder">
      <tr>
        <td class="label"> Заголовок:<span class="impot">*</span></td>
        <td><input type="text" name="title" value="{title}" maxlength="150" class="f_input" style="width:100%;" /></td>
      </tr>
      <tr>
        <td class="label"> Оригинальный Заголовок:<span class="impot">*</span></td>
        <td><input type="text" name="title2" value="{title2}" maxlength="150" class="f_input" style="width:100%;" /></td>
      </tr>
      [urltag]
      <tr>
        <td class="label">URL статьи:</td>
        <td><input type="text" name="alt_name" value="{alt-name}" maxlength="150" class="f_input" style="width:100%;" /></td>
      </tr>
      [/urltag]
      <tr>
        <td class="label"> Категория:<span class="impot">*</span></td>
        <td>{category}</td>
      </tr>
      <tr>
        <td>Источник</td>
        <td><input name="src_link"  id="src_link" style="width:98%;" class="bk" value="{src_link}"/></td>
      </tr>
      <tr>
        <td>Ссылки Источника</td>
        <td><textarea rows="19" name="src_links" oid="src_links" style="width:98%;" class="bk">{src_links}</textarea></td>
      </tr>
      <tr>
        <td height="29" width="140" style="padding-left:5px;">Data</td>
        <td><a href="{service_uri}site/upload" target="_blank" onClick="addData(); return false;" class="btn">Добавить ссылки </a></td>
        <script langauge="javascript">
                    function addData(){
                        window.open('{service_uri}site/upload?lay_mini=1', '_AddLinks', 'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=640,height=550');
                    }
                </script> 
      </tr>
      <tr>
        <td colspan="2"><b>Вводная часть: <span class="impot">*</span></b> (Обязательно)
          <div> [not-wysywyg]
            <div>{bbcode}</div>
            <textarea name="short_story" id="short_story" onclick=setFieldName(this.name) style="width:100%;" rows="15" class="f_textarea" >{short-story}</textarea>
            [/not-wysywyg]
            {shortarea} </div></td>
      </tr>
      <tr>
        <td colspan="2"><b>Подробная часть:</b> (Необязательно)
          <div> [not-wysywyg]
            <div>{bbcode}</div>
            <textarea name="full_story" id="full_story" onclick=setFieldName(this.name) style="width:100%;" rows="20" class="f_textarea" >{full-story}</textarea>
            [/not-wysywyg]
            {fullarea} </div></td>
      </tr>
      {xfields}
      <tr>
        <td class="label">Ключевые слова для облака тегов:</td>
        <td><input type="text" name="tags" id="tags" value="{tags}" maxlength="150"  class="f_input" autocomplete="off" style="width:100%;" /></td>
      </tr>
      [question]
      <tr>
        <td class="label"> Вопрос: </td>
        <td><div>{question}</div></td>
      </tr>
      <tr>
        <td class="label"> Ответ:<span class="impot">*</span></td>
        <td><div>
            <input type="text" name="question_answer" class="f_input"/>
          </div></td>
      </tr>
      [/question]
      [sec_code]
      <tr>
        <td class="label"> Введите код<br />
          с картинки:<span class="impot">*</span></td>
        <td><div>{sec_code}</div>
          <div>
            <input type="text" name="sec_code" id="sec_code" style="width:115px" class="f_input" />
          </div></td>
      </tr>
      [/sec_code]
      [recaptcha]
      <tr>
        <td class="label"> Введите два слова,<br />
          показанных на изображении:<span class="impot">*</span></td>
        <td><div>{recaptcha}</div></td>
      </tr>
      [/recaptcha]
      <tr>
        <td colspan="2">{admintag}</td>
      </tr>
    </table>
    <div class="fieldsubmit" style="float:right;">
      <button name="add" class="btn" type="submit"><span>Отправить</span></button>
      <button name="nview" onclick="preview()" class="btn" type="submit"><span>Просмотр</span></button>
    </div>
    <div style="clear:both"></div>
  </div>
</div>
