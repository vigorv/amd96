<?php

/****************************************/
// ����������:
// ==== �����: LogicBoard
// ==== �����: ������ ������ (ShapeShifter)
// ==== Copyright � ������ ������ �������� 2011-2012
// ==== ������ ��� ������� ���������� �������
// ==== ����������� ����: http://logicboard.ru

/****************************************/

if (! defined('LogicBoard_ADMIN') )
{
	@include '../../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

$group_list = "";

foreach($cache_group as $m_group)
{
    $group_list .= "<option value=\"".$m_group['g_id']."\">".$m_group['g_title']."</option>";
}

echo <<<HTML

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">������� ����� � ��������������</div>
                    </div>
                    <form  method="post" name="filters" action="{$redirect_url}?do=users">
                    <input type="hidden" name="how_s" value="3" />
                    <input type="hidden" name="type" value="name" />
                    <input type="hidden" name="member_gr" value="0" />
                    <input type="hidden" name="date_reg_1" value="" />
                    <input type="hidden" name="date_reg_2" value="" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">����� ������������:</td>
                            <td class="appText appText_bottom"><input type="text" name="text" placeholder="�����/��� ������������" value="" id="text_name" class="inputText" /> <input type="submit" name="search" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    <form action="{$redirect_url}?" name="fast_forum" method="get">
                    <input type="hidden" name="do" value="users" />
                    <input type="hidden" name="op" value="editgroup" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">������������� ������:</td>
                            <td class="appText appText_bottom"><select name="id">{$group_list}</select> <input type="submit" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    <form  method="post" name="allip" action="{$redirect_url}?do=users&op=tools">
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">����� ��� IP ������������:</td>
                            <td class="appText appText_bottom"><input type="text" name="sname" value="" id="sname" placeholder="�����/��� ������������" class="inputText" /> <input type="submit" name="allip" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    <form  method="post" name="infoip" action="{$redirect_url}?do=users&op=tools">
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">����� IP �����:</td>
                            <td class="appText appText_bottom"><input type="text" name="ip" id="word_ip" value="" placeholder="127.0.0.1 ��� 127.*.0.1" class="inputText" /> <input type="submit" name="infoip" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    <form action="{$redirect_url}?" name="fast_forum" method="get">
                    <input type="hidden" name="do" value="board" />
                    <input type="hidden" name="op" value="editforum" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">������������� �����:</td>
                            <td class="appText appText_bottom"><input type="text" name="id" id="fid" placeholder="ID ������ ��� ���������" value="" class="inputText" /> <input type="submit" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    <form method="get" name="logs_s" action="{$redirect_url}?">
                    <input type="hidden" name="do" value="logs" />
                    <input type="hidden" name="op" value="actions" />
                    <input type="hidden" name="where_search" value="member_name" />
                    <input type="hidden" name="type" value="1" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">���� �������� � ��:</td>
                            <td class="appText appText_bottom"><input type="text" name="word" placeholder="�����/��� ������������" value="" id="word_name" class="inputText" /> <input type="submit" name="search" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    <form method="get" name="logs_s" action="{$redirect_url}?">
                    <input type="hidden" name="do" value="logs" />
                    <input type="hidden" name="op" value="login" />
                    <input type="hidden" name="type" value="0" />
                    <table class="appLine">
                        <tr>
                            <td class="appText appText_bottom" width="210">���� ����������� ��:</td>
                            <td class="appText appText_bottom"><input type="text" name="word" placeholder="�����/��� ������������" value="" id="word_name2" class="inputText" /> <input type="submit" name="search" value="��" /></td>
                        </tr>
                    </table>
                    </form>
                    
<script type="text/javascript">
	inputPlaceholder(document.getElementById('fid'))
    inputPlaceholder(document.getElementById('text_name'))
    inputPlaceholder(document.getElementById('word_name'))
    inputPlaceholder(document.getElementById('word_name2'))
    inputPlaceholder(document.getElementById('word_ip'))
    inputPlaceholder(document.getElementById('sname'))
</script>

HTML;


?>