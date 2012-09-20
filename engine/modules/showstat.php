<?php
/*=====================================================
ShowStat - ����� ���������� ������������������ ����� (������������ �� 9.3 - 9.6)
-------------------------------------------------------
������: 2.4 (23.08.2012)
=======================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( '<iframe width="853" height="480" style="margin: 50px;" src="http://www.youtube.com/embed/mTQLW3FNy-g" frameborder="0" allowfullscreen></iframe>' );
}

if ($user_group[$member_id['user_group']]['allow_admin']) {

	global $config, $Timer, $db, $tpl;

	if(!is_numeric($size)) $size = 40; //������������ ������ ����� ����

	$statfile = ROOT_DIR . '/uploads/xvd1619_stat_log.php';
	$dtime = date ('d.m.Y  H:i:s', $_TIME);
	$timer = $Timer->stop();
	$tpl_time = round($tpl->template_parse_time, 5);
	$db_q = $db->query_num;
	$mysql_time = round($db->MySQL_time_taken, 5);

	if ($show_query) {
		$total_time_query = $db->query_list;
		if(is_array($total_time_query)){
			for ($i = 0; $i < count($total_time_query); $i++) { 
			$time_query .= "".$total_time_query[$i][time] > 0.01."" ? "<p><span style='color:red'>".round($total_time_query[$i][time],5)."</span> ���. - [ ".htmlspecialchars($total_time_query[$i][query])." ]</p>" : "<p><span style='color:green'>".round($total_time_query[$i][time],5)."</span> ���. - [ ".htmlspecialchars($total_time_query[$i][query])." ]</p>";}
		}
	}

	if(function_exists( "memory_get_peak_usage" )) $mem_usg = round(memory_get_peak_usage()/(1024*1024),2)."��";


	if ((file_exists($statfile) && filesize($statfile) > $size*1024) OR $nolog) {
		unlink($statfile);
	}
	if (!$nolog) {
		if (!file_exists($statfile)) {
			$cFile = fopen( $statfile, "wb" );
			$firstText = "
<!DOCTYPE html>
<html lang='ru'>
<head>
	<meta charset='".$config['charset']."'>
	<title>��� ���������� ��������� ��������</title>
	<style>
	a { display: inline-block; margin-bottom: 5px; }
	b { color: #c00; }
	p {
		margin: 0 -5px;
		padding: 10px 5px;
		border-bottom: solid 1px #ddd;
	}
	p:hover { background: #fcfcfc; }

	p:last-child { margin-bottom: -6px; }
	.stattable {
		margin: 50px;
		border-collapse: collapse;
		border: solid 1px #ccc;
		font: normal 14px Arial,Helvetica,sans-serif;
	}
	.stattable th b {
		cursor: help;	
	}
	.stattable td { text-align: right; }
	.stattable th, .stattable td {
		font-size: 12px;
		border: solid 1px #ccc;
		padding: 5px 8px;
	}
	.stattable th:first-child, .stattable td:first-child { width: 80%; text-align: left; }
	.stattable tr:hover { background: #f0f0f0; color: #1d1d1d; }
	</style>
	<script type=\"text/javascript\" src='http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'></script>
	<script type=\"text/javascript\">
		$.fn.getZnach = function(prop) {
			var options = $.extend({
				source: '�',
				ins: '',
				quant: '5'
			}, prop);

			var summ=0;
			this.each( function (i) {
				summ+=+($(this).text().replace(/,/,'.').replace(options.source,''));			
			});

			$(options.ins).append('<br /><b title=\"C������ ��������\">'+(summ/this.length).toFixed(options.quant)+options.source+'</b>');
		}
		//�������������
		jQuery(function($) {
			$('td.timer').getZnach({ins:'th.timer'});
			$('td.tpl_time').getZnach({ins:'th.tpl_time'});
			$('td.db_q').getZnach({ins:'th.db_q', source: '', quant: '0'});
			$('td.mysql_time').getZnach({ins:'th.mysql_time'});
			$('td.mem_usg').getZnach({source: '��', ins:'th.mem_usg', quant: '2'});
		});
	</script>
</head>
<body>
	<table class='stattable'>
		<tr>
			<th scope='col' class='queries'>����� �������� � ������� � �� (�����������)</th>
			<th scope='col' class='dtime'>����</th>
			<th scope='col' class='timer'>���� ���������� �������</th>
			<th scope='col' class='tpl_time'>����� �������� �������</th>
			<th scope='col' class='db_q'>���-�� ��������</th>
			<th scope='col' class='mysql_time'>����� ���������� ��������</th>
			<th scope='col' class='mem_usg'>������� ������</th>
		</tr>
	\r\n</table></body></html>";
			fwrite( $cFile, $firstText);
			fclose( $cFile );

		} else {
			$cFileArr = file($statfile);
			$lastLine = array_pop($cFileArr);
			$newText = implode("", $cFileArr);

			$newTextAdd = "��������� ������\r\n";
			$newTextAdd = "	
		<tr>
			<td class='queries'><a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."' title='������� �� ��������' target='_blank'>".$_SERVER['REQUEST_URI']."</a> <br />".$time_query."</td>
			<td class='dtime' valign='top'>$dtime</td>
			<td class='timer' valign='top'><b>".$timer."�</b></td>
			<td class='tpl_time' valign='top'>".$tpl_time."�</td>
			<td class='db_q' valign='top'>".$db_q."</td>
			<td class='mysql_time' valign='top'>".$mysql_time."�</td>
			<td class='mem_usg' valign='top'>".$mem_usg."</td>
		</tr>\r\n";

			$cFile = fopen( $statfile, "w" );	

			fwrite( $cFile, $newText.$newTextAdd.$lastLine);
			fclose( $cFile );
		}
	}

	$showstat .= "<div class='showstat'><i id='showstat-but' title='�������� ���������� (������ - Esc)'></i>";

	if ($show_query) {
		$showstat .= "<i id='queries-stat' title='�������� ������� (������ - Esc)'></i>";
	}
	if (!$nolog) {
		$showstat .= "<a id='log-link' href='".$config['http_home_url']."uploads/xvd1619_stat_log.php' target='_blank' title='�������� ���. ����� ".$size."��,  ������: ".fgets($statfile).round(filesize($statfile)/1024,2)."��'></a>";
	}
	if ($member_id['user_group'] == 1) {
		$showstat .= "
		<i id='clearbutton' title='�������� ���'></i>
		<i id='cache-info'></i>
		";
	}
	$showstat .= "
		<div class='base-stat'>
		<p>������ �������� ��: <b>".$timer."�</b></p>
		<p>������ ������ ��: <b>".$tpl_time."�</b></p>
		<p>�������: <b>".$db_q."</b></p>
		<p>��������� ��: <b>".$mysql_time."�</b></p>";
	if($mem_usg) $showstat .="<p> ������ ��������� <b>".$mem_usg."</b> </p>";
	
	$showstat .= "</div>";
	if ($show_query) {
		$showstat .= "<div class='queries'>".$time_query."</div>";
	}
	$showstat .="
	</div>
	<script type=\"text/javascript\">
		jQuery(function($) {
			$('#showstat-but').click(function () {
				$(this).toggleClass('active');
				$('.base-stat').slideToggle(200);
			});
			$('#queries-stat').click(function () {
				$(this).toggleClass('active');
				$('.queries').slideToggle(200);
			});
			$(document).keyup(function(e) {
				if (e.keyCode == 27) { $('.base-stat, .queries').fadeOut(100); $('#queries-stat, #showstat-but').removeClass('active'); }
			});";			
		if ($member_id['user_group'] == 1) {
			$showstat .="
			$('#clearbutton').click(function() {
				$('#cache-info').html('� �������� ...');
				$.get('".$config['http_home_url']."engine/ajax/adminfunction.php?action=clearcache', function( data ){
					$('#cache-info').html(data);
				});
				return false;
			});";
		}
	$showstat .="
		});
	</script>
	";
	echo $showstat;

}

?>