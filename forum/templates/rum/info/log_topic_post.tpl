<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={charset}" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>{TITLE_BOARD}</title>
<link type="text/css" media="all" rel="StyleSheet" href="{HOME_LINK}control_center/template/style.css" />
<script type="text/javascript" src="{HOME_LINK}components/scripts/jquery.js"></script>
<style type="text/css" media="all">
blockquote p {margin:0;}
blockquote.blockquote {margin: 5px -20px 0px; padding:5px 20px; background:#e1ebf0; line-height:1.3;}
blockquote .titlequote {display:block; margin-bottom:5px;}
blockquote .textquote {margin-bottom:5px;}
blockquote.blockspoiler {margin: 5px -20px 0px; padding:5px 20px; background:#e1ebf0; line-height:1.3;}
blockquote .titlespoiler {display:block; margin-bottom:5px;}
blockquote .textspoiler {margin-bottom:5px;}
blockquote.blockhide {margin: 5px -20px 0px; padding:5px 20px; background:#e1ebf0; line-height:1.3;}
blockquote .titlehide {display:block; margin-bottom:5px;}
blockquote .texthide {margin-bottom:5px;}
</style>
</head>
<body>
<script type="text/javascript">
function ShowAndHide(id)
{
    $("#" + id).animate({opacity:"toggle"}, "slow");
        
    setTimeout(function(){
        $('#' + id + ' img.lb_img').width(function() {         
            if ($(this).width() > img_lb_width)
            {
                img_src = $(this).attr("src");
                $(this).wrap("<a href='" + img_src + "' onclick=\"return hs.expand(this)\" ></a>");
                return img_lb_width;
            }
        });
    }, 500);
};
</script>
<div style="padding:5px;">
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">{title}</div>
                    </div>
                    
                    <div class="borderL">
                        <div class="borderR">

<table class="colorTable">

<tr>
<td align="left" width="150"><h6>Пользователь</h6></td>
<td align="left"><h6>Действие</h6></td>
<td align="right" width="150"><h6>Дата</h6></td>
</tr>

{info_data}
</table>

                        </div>
                    </div>
                    <div>
                        <div class="borderBtmR"></div>
                        <div class="borderBtmL"></div>
                        <div class="borderBtm"></div>
                    </div>
</div>
</body>
</html>