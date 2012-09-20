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

function ShowForums_Sub($id = 0, $first = false) 
{
	global $cache_forums, $redirect_url;

	$forum_title = $cache_forums[$id]['title'];

	if ($first)
	{

$returnstring = <<<HTML
<a href="{$redirect_url}?do=board&id={$id}" title="������� � ������. ID: {$id}">{$forum_title}</a>
HTML;

	}
	else
	{

$returnstring = <<<HTML
, <a href="{$redirect_url}?do=board&id={$id}" title="������� � ������. ID: {$id}">{$forum_title}</a>
HTML;

	}

	return $returnstring;
}

function Build_SubForums_2($id = 0, $f_id = 0)
{
	global $cache_forums, $redirect_url, $secret_key;

	$build = "";

	$sub = sub_forums ($id);
	if ($sub)
	{
		$sub = explode ("|", $sub);
		if( count( $sub ) > 1 )
		{
			$first = true;
			foreach ($sub as $sub_forum)
			{
				$sid = $sub_forum;
				if ($cache_forums[$sid]['parent_id'] == $id AND $id != $sid )
				{
					$forum_title = $cache_forums[$sid]['title'];
                    
                    if ($cache_forums[$sid]['flink']) $forum_title .= " <font class=\"smalltext\">[�����-������]</font>";
$build .= <<<HTML

<table class="colorTable">
<tr>
<td class="blueHeader appLine" align=left><a href="{$redirect_url}?do=board&id={$sid}" title="������� � ������. ID: {$sid}">{$forum_title}</a></td>
<td align=right><div class="config_edit_pan" style="display:none;padding:5px;">[ <a href="{$redirect_url}?do=board&op=editforum&id={$sid}" title="������������� ������ �����.">�������������</a> ] [ <a href="{$redirect_url}?do=board&op=delforum&id={$sid}&secret_key={$secret_key}" title="������� ������ �����."><font color=red>�������</font></a> ]</div><span class="config_edit_butt"><a href="#" title="�����"><img src="{$redirect_url}template/images/config_edit_butt.png" alt="�����" /></a></span></td>
</tr>
</table>

HTML;
					$build .= Build_SubForums ($sid, $f_id);
					$first = false;
				}
			}
		}
	}
	return $build;
}

function Build_SubForums($id = 0, $f_id = 0)
{
	$sub = sub_forums ($id);
	$build = "";

	if ($sub)
	{
		$sub = explode ("|", $sub);
		if( count( $sub ) > 1 )
		{

$build .= <<<HTML

<table>
<tr><td align=left>
<fieldset style="margin-top:4px"><legend>��������� </legend>
HTML;
			$first = true;
			foreach ($sub as $sub_forum)
			{
				if ($sub_forum != $f_id AND $sub_forum != $id)
				{
					$id = $sub_forum;
					$build .= ShowForums_Sub ( $id, $first );
					$first = false;
				}
			}
$build .= <<<HTML

</fieldset></td></tr>
</table>
HTML;
		}
	}

	return $build;
}

function ShowForums($f_id = 0, $parentid = 0, $returnstring = '') 
{
	global $cache_forums, $redirect_url, $secret_key;

	if (isset ( $cache_forums ))
	{
		$main_category = array();
		if (!$f_id)
		{
			foreach ( $cache_forums as $mass )
			{
				if( $mass['parent_id'] == $parentid )
					$main_category[] = $mass['id'];
			}
		}
		else
			$main_category[] = $f_id;

		if( count( $main_category ) )
		{
			foreach ( $main_category as $id )
			{
				$forum_title = $cache_forums[$id]['title'];
                
                if ($cache_forums[$id]['flink']) $forum_title .= " <font class=\"smalltext\">[�����-������]</font>";

				if ($cache_forums[$id]['parent_id'] == 0 AND !$f_id)
				{
$returnstring .= <<<HTML

                   <div class="clear" style="height:8px;"></div>

				<div class="adm_pop_cont">
					<a class="adm_pop_but" href="#" title="���������� �����."><img src="{$redirect_url}template/images/darr.png" alt="" /></a>
					<div class="adm_popup">
						<ol>
							<li><a href="{$redirect_url}?do=board&op=editforum&id={$id}">�������������</a></li>
							<li><a href="{$redirect_url}?do=board&op=delforum&id={$id}&secret_key={$secret_key}"><font color=red>�������</font></a></li>
						</ol>
						<div class="adm_pop_down">
							<div></div><span></span><span class="co_r"></span>
						</div>
					</div>
				</div>

                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">{$forum_title}</div>
                    </div>
HTML;
				}
				elseif ($f_id == $id)
				{

$returnstring .= <<<HTML

 		    <div class="clear" style="height:8px;"></div>

                <div class="adm_pop_cont">
					<a class="adm_pop_but" href="#" title="���������� �����."><img src="{$redirect_url}template/images/darr.png" alt="" /></a>
					<div class="adm_popup">
						<ol>
							<li><a href="{$redirect_url}?do=board&op=editforum&id={$id}" title="������������� ������ ���������/�����.">�������������</a></li>
							<li><a href="{$redirect_url}?do=board&op=delforum&id={$id}&secret_key={$secret_key}" title="������� ������ ���������/�����."><font color=red>�������</font></a></li>
						</ol>
						<div class="adm_pop_down">
							<div></div><span></span><span class="co_r"></span>
						</div>
					</div>
				</div>
                
                    <div class="headerGray">
                        <div class="headerGrayL"></div>
                        <div class="headerGrayR"></div>
                        <div class="headerGrayBg">{$forum_title}</div>
                    </div>

HTML;
					$returnstring .= Build_SubForums_2($id, $f_id);
				}
				else
				{
					if ($cache_forums[$cache_forums[$id]['parent_id']]['parent_id'] == 0)
					{
$returnstring .= <<<HTML

<table class="colorTable">
<tr>
<td class="blueHeader appLine" align=left><a href="{$redirect_url}?do=board&id={$id}" title="������� � ������. ID: {$id}">{$forum_title}</a></td>
<td align=right><div class="config_edit_pan" style="display:none;padding:5px;">[ <a href="{$redirect_url}?do=board&op=editforum&id={$id}" title="������������� ������ �����.">�������������</a> ] [ <a href="{$redirect_url}?do=board&op=delforum&id={$id}&secret_key={$secret_key}" title="������� ������ �����."><font color=red>�������</font></a> ]</div><span class="config_edit_butt"><a href="#" title="�����"><img src="{$redirect_url}template/images/config_edit_butt.png" alt="�����" /></a></span></td>
</tr>
</table>
HTML;

						$returnstring .= Build_SubForums($id, $f_id);
					}
				}
				if (!$f_id)
					$returnstring = ShowForums( $f_id, $id, $returnstring );

				if ($cache_forums[$id]['parent_id'] == 0 OR $f_id == $id)
				{
$returnstring .= <<<HTML


HTML;
				}

			}
		}
	}
	return $returnstring;
}

$link_speddbar = "�����";
$onl_location = "�����";

if ($id > 0)
{
	$speedbar = main_forum($id);
	if($speedbar)
	{
		$speedbar = explode ("|", $speedbar);
		sort($speedbar);
		reset($speedbar);
		if( count( $speedbar ) )
		{
			$link_speddbar = "<a href=\"".$redirect_url."?do=board\">�����</a>";
			foreach ($speedbar as $link_forum)
			{
				if ($id == $link_forum)
				{
					$link_speddbar .= "|".$cache_forums[$link_forum]['title'];
					$onl_location .= " &raquo; ".$cache_forums[$link_forum]['title'];
				}
				else
				{
					$link_speddbar .= "|<a href=\"".$redirect_url."?do=board&id={$link_forum}\">".$cache_forums[$link_forum]['title']."</a>";
					$onl_location .= " &raquo; ".$cache_forums[$link_forum]['title'];
				}
			}
		}
	}
}

$control_center->header("�����", $link_speddbar);

$DB->select( "*", "forums", "", "ORDER by posi ASC" );
$forum = array ();

$i = 0;

while ( $row = $DB->get_row() )
{
	$i ++;
	$forum[$row['id']] = array ();
	foreach ($row as $key => $value)
		$forum[$row['id']][$key] = $value;
}
$DB->free();

echo <<<HTML

<table border=0>
<tr><td align=left style="vertical-align:middle;"><h6>������ ��������� � �������:</h6></td><td align=right><a href="{$redirect_url}?do=board&op=addcategory" title="�������� ����� ���������."><img src="{$redirect_url}template/images/category_add.gif" alt="����� ���������" /></a>   <a href="{$redirect_url}?do=board&op=addforum" title="�������� ����� �����."><img src="{$redirect_url}template/images/forum_add.gif" alt="����� �����" /></a></td></tr>
<tr><td colspan=2><hr></td></tr>
</table>
HTML;


if ($i)
{
	echo ShowForums($id);
}
else
{

echo <<<HTML
<table width="100%" border=0>
<tr><td>�� ������ ������ �� �������.</td></tr>
</table>
HTML;

}

?>