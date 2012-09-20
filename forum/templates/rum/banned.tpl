<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={charset}" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>{TITLE_BOARD}</title>
<link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?f=templates/{TEMPLATE_NAME}/css/style.css" media="all" />
<style type="text/css" media="all">
#authMiddle {
    position:absolute;
    *position:static;
    width:100%;
    height:100%;
}
#authContainer {
    margin:0px auto;
    width:650px;
}
</style>
</head>
<body>
<table id="authMiddle">
<tr><td style="vertical-align:middle;">
    <div id="authContainer">
        <div id="board_index" class="cle">
            <div class="category_block cb_color_orange">
					<h3>Вы заблокированы!<i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
                    <div class="autoriz">
                            <ol>
                                <li>Вы были заблокированы на нашем форуме <b>{how}</b> администрацией.</li>
                                [info]
                                <li>Количество дней: {days}</li>
                                <li>Дата окончания бана: {time}</li>
                                <li>Причина блокировки: {msg}</li>
                                [/info]
                            </ol>
						<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
                    </div>				
            </div>
        </div>
    </div>
</td></tr>
</table>
</body>
</html>