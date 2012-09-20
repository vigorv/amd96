<?php

if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}

include_once ENGINE_DIR . '/classes/parse.class.php';

/**
 *  Funcs
 * 
 */
function netMatch($CIDR, $IP) {
    list ($net, $mask) = explode('/', $CIDR);
    return (ip2long(trim($IP)) & ~((1 << (32 - trim($mask))) - 1)) == ip2long(trim($net));
}

/**
 *  Getters
 *  
 */
function getZone() {
    global $db;
    $SelectZonesSQL = $db->query("SELECT * FROM zones order by priority desc");
    $type = '';
    $isOperaMini = false;
    while ($zone = $db->get_row($SelectZonesSQL)) {
        if (
                netMatch($zone['network'] . '/' . $zone['mask'], $_SERVER['REMOTE_ADDR'])
                ||
                netMatch($zone['network'] . '/' . $zone['mask'], $_SERVER['X_REAL_IP'])
        ) {
            $type = $zone['type'];
            break;
        }
    }
    $db->free($SelectZonesSQL);
    return $type;
}

function getServers($zone) {
    global $db;
    $outservers = array();
    $SelectServerFromSQL = $db->query("SELECT server,type FROM servers where type='$zone' and active='Y'");
    while ($outservers2 = $db->get_row($SelectServerFromSQL)) {
        $outservers[] = $outservers2;
    }
    $db->free($SelectServerFromSQL);
    if (empty($outservers)) {
        if ($zone == 'OPERA-MINI') {
            $isOperaMini = true;
        }
        $zone = '';
    }
    return $outservers;
}

function getData($idn) {
    global $db;
    $sql_select = "SELECT id,title2,src_link, src_links,xfields FROM " . PREFIX . "_post WHERE id=" . $idn . ' LIMIT 1';
    //$sql_count = "SELECT FOUND_ROWS() as count";
    $res = $db->query($sql_select);
    $row = $db->get_row($res);
    $db->free($res);
    return $row;
}

/*
 * Parsers
 * 
 */

function LinkParse($data) {
    $data['xfields'] = str_replace('ftp://ftp.wsmedia.su', 'ftp://ftp.rumedia.ws', $data['xfields']);
    $data['xfields'] = str_replace('ftp://arc.wsmedia.su', 'ftp://arc.rumedia.ws', $data['xfields']);

    if (!preg_match('/href=[\'"]ftp/i', $data['xfields'])) {
        $arr = explode('|', $data['xfields']);
        $arr = preg_replace('#(ftp\:\/\/ftp\.rumedia\.ws\/)([^:]+)\.(7z|avi|mp4|wmv|mkv|mpg|zip|mdf|mds|iso|wma|srt|rar|pdf|bin|mp3)#i', '<a href="\1\2.\3" target="_blank">\2.\3</a>', $arr);
        $data['xfields'] = implode('|', $arr);

        $arr = explode('|', $data['xfields']);
        $arr = preg_replace('#(ftp\:\/\/arc\.rumedia\.ws\/)([^:]+)\.(7z|avi|mp4|wmv|mkv|mpg|zip|mdf|mds|iso|wma|srt|rar|pdf|bin|mp3)#i', '<a href="\1\2.\3" target="_blank">\2.\3</a>', $arr);
        $data['xfields'] = implode('|', $arr);
    }
    return $data;
}

function ObtainLinks($links) {
    global $server;
    $links = preg_replace('#(ftp\:\/\/ftp\.rumedia\.ws\/)([^:]+)\.(7z|avi|mp4|wmv|mkv|mpg|zip|mdf|mds|iso|wma|srt|rar|pdf|bin|mp3)#i', '<a onclick="return filmClk(this);"  href="\2.\3" target="_blank">\2.\3</a>', $links);
    $links = preg_replace('#(ftp\:\/\/arc\.rumedia\.ws\/)([^:]+)\.(7z|avi|mp4|wmv|mkv|mpg|zip|mdf|mds|iso|wma|srt|rar|pdf|bin|mp3)#i', '<a onclick="return filmClk(this);"  href="\2.\3" target="_blank">\2.\3</a>', $links);
    $links = preg_replace('#(ftp\:\/\/ftp\.wsmedia\.su\/)([^:]+)\.(7z|avi|mp4|wmv|mkv|mpg|zip|mdf|mds|iso|wma|srt|rar|pdf|bin|mp3)#i', '<a onclick="return filmClk(this);"  href="\2.\3" target="_blank">\2.\3</a>', $links);
    $links = str_replace($downloadServerInNews, $downloadServer, $links);
    return $links;
}

function ChooseServer($type, $server, $data) {
    global $config;
    if ($type == 'STK-BAR') {
        $newlinks = str_replace($config['downloadArcServerInNews'], $config['downloadStkBarArcServer'], $data['xfields']);
    } else {
        $newlinks = str_replace($config['downloadArcServerInNews'], $config['downloadArcServer'], $data['xfields']);
    }
    $data['xfields'] = $newlinks;

    $isVIP = false;
    ///vip && glavvredi

    if (($zone != 'ALL') && ($zone != 'STK0') && ($member_id['user_group'] == 10 || $member_id['user_group'] == 3 || $member_id['user_group'] == 2 || $member_id['user_group'] == 1 || $member_id['user_id'] == 5456)) {
        $isVIP = true;
        if ($type == 'STK-BAR') { //ƒÀﬂ ¬»œŒ¬ ¡¿–Õ¿”À¿ ¬€ƒ¿≈Ã œ–Œ—“Œ ¡¿–Õ¿”À‹— »≈
            $downloadServerInNews = $config['downloadServerInNews'];
            $num = mt_rand(0, count($outservers) - 1);
            $downloadServer = $outservers[$num]['server'];
            $newlinksf = str_replace($downloadServerInNews, $downloadServer, $data['full_story']);
            $data['full_story'] = $newlinksf;
            $newlinks = str_replace($downloadServerInNews, $downloadServer, $data['xfields']);
            $data['xfields'] = $newlinks;
        } else { //ƒÀﬂ Œ—“¿À‹Õ€’ ¬»œŒ¬ ¬€ƒ¿≈Ã ¬»œ —≈–¬≈–¿
            $downloadServerInNews = $config['downloadServerInNews'];
            $downloadServer = $config['downloadServerVIP'];
            $newlinksf = str_replace($downloadServerInNews, $downloadServer, $data['full_story']);
            $data['full_story'] = $newlinksf;
            $newlinks = str_replace($downloadServerInNews, $downloadServer, $data['xfields']);
            $data['xfields'] = $newlinks;
        }
    }
    ///end vip
    else { //ƒÀﬂ Õ≈¬»œŒ¬
//*/
        if ($type == 'STK-BAR') { //ƒÀﬂ ¡¿–Õ¿”À¿
            $downloadServerInNews = $config['downloadServerInNews'];
            $num = mt_rand(0, count($outservers) - 1);
            $downloadServer = $outservers[$num]['server'];
            //echo '$downloadServer='.$downloadServer.'<br />';
            //echo '$downloadServerInNews='.$downloadServerInNews.'<br />';

            $newlinksf = str_replace($downloadServerInNews, $downloadServer, $data['full_story']);
            $data['full_story'] = $newlinksf;
            $newlinks = str_replace($downloadServerInNews, $downloadServer, $data['xfields']);
            $data['xfields'] = $newlinks;
        } else { //ƒÀﬂ Œ—“¿À‹Õ€’
            if (date(Ymd, $data['date']) >= $WantDate) {
                $downloadServerInNews = $config['downloadServerInNews'];
                /* $downloadServer = file_get_contents('http://nsk54.com/utils/set_input_server/?type=1&ip='.$_SERVER['REMOTE_ADDR']);
                  $newlinksf=str_replace($downloadServerInNews,$downloadServer,$data['full_story']);
                  $data['full_story']=$newlinksf;
                  $newlinks=str_replace($downloadServerInNews,$downloadServer,$data['xfields']);
                  $data['xfields']=$newlinks; */
                $num = mt_rand(0, count($outservers) - 1);
                $downloadServer = $outservers[$num]['server'];
//COMMENT BY VANO				$newlinksf=str_replace($downloadServerInNews,$downloadServer,$data['full_story']);
                if (!$isVIP)
                    $newlinksf = str_replace($downloadServerInNews, $downloadServer, $data['full_story']);
                else
                    $newlinksf = str_replace($downloadServerInNews, $config['downloadServerVIP'], $data['full_story']);
                $data['full_story'] = $newlinksf;
                $newlinks = str_replace($downloadServerInNews, $downloadServer, $data['xfields']);
                $data['xfields'] = $newlinks;
                #echo "ƒÓ·‡‚ÎÂÌ˚ œÓÁ‰ÌÂÂ ".$WantDate."qweqwe<br />";
            }
            else {  #if (date(Ymd, $data['date']) < $WantDate)
                ///keeper dolboeb pidez pizdit palkoi po bashke, nado tebya na kursi programmirovania otpravit /// Roma vseravno lol
                $randomcheg = rand(0, 99);
//				if ($randomcheg < 100 &&$type=='STK')
                if ($type == 'STK') {
                    $downloadServer = "http://92.63.196.72/";
                    #$downloadServer = "http://212.164.71.72/";
                } else {
                    #$downloadServer = file_get_contents('http://nsk54.com/utils/set_input_server/?type=1&ip='.$_SERVER['REMOTE_ADDR']);
                    $num = mt_rand(0, count($outservers) - 1);
                    $downloadServer = $outservers[$num]['server'];
                }
                ///End roma-lol
                $downloadServerInNews = $config['downloadServerInNews'];
                /* $newlinksf=str_replace($downloadServerInNews,$downloadServer,$data['full_story']);
                  $data['full_story']=$newlinksf;
                  $newlinks=str_replace($downloadServerInNews,$downloadServer,$data['xfields']);
                  $data['xfields']=$newlinks; */
//COMMENT BY VANO				$newlinksf=str_replace($downloadServerInNews,$downloadServer,$data['full_story']);
                if (!$isVIP)
                    $newlinksf = str_replace($downloadServerInNews, $downloadServer, $data['full_story']);
                else
                    $newlinksf = str_replace($downloadServerInNews, $config['downloadServerVIP'], $data['full_story']);

                $data['full_story'] = $newlinksf;
                $newlinks = str_replace($downloadServerInNews, $downloadServer, $data['xfields']);
                $data['xfields'] = $newlinks;
                #echo "ƒÓ·‡‚ÎÂÌ˚ –‡Ì¸¯Â ". $WantDate." Ì‡ 3 ‰Ìˇ <br />";
                #echo date(Ymd, $data['date']);
            }
        } //END OF ƒÀﬂ Ã≈—“Õ€’
    }//END OF ƒÀﬂ Õ≈¬»œŒ¬     
}

/**
 * 
 * Rumedia Main 
 * 
 */
global $tpl;
global $config;

switch ($action) {
    case 'view_full':

        $zone = getZone();

        $servers = getServers($zone);

        $server = $servers[0];
        $data = getData($idn);


        $data = LinkParse($data);

        $downloadServerInNews = 'ftp://ftp.rumedia.ws';
        //$downloadServerInNews = $config['downloadServerInNews'];

        $xdata = xfieldsdataload($data['xfields']);
        $num = mt_rand(0, count($servers) - 1);
        $downloadServer = $servers[$num]['server'];

//        $xdata = preg_replace('/href=\\/','href=',$xdata);

        $links = $xdata['m_direct_links'];
        $music_links = $xdata['music_direct_links'];
        $games_links = $xdata['games_direct_links'];



        $links = preg_replace('/href=\"(.*?)\:\/\/(.*?).ws/', 'href=" '. $downloadServer . '/', $links);
        $music_links = preg_replace('/href=\"(.*?)\:\/\/(.*?).ws/', 'href="' . $downloadServer . '/', $music_links);
        $games_links = preg_replace('/href=\"(.*?)\:\/\/(.*?).ws/', 'href="' . $downloadServer . '/', $games_links);
        ///
        //echo $links;
        //$links = preg_replace('/\\\"ftp:\/\/(.*?).ws/','http://'.$downloadServer.'/',$links);       //echo $links;
        //$music_links = preg_replace('/\\\"ftp:\/\/(.*?).ws/','http://'.$downloadServer.'/',$music_links);        
        //$games_links=preg_replace('/\\\"ftp:\/\/(.*?).ws/','http://'.$downloadServer.'/',$games_links);
        //    $links = ObtainLinks($links);
//        $music_links = ObtainLinks($music_links);
        //   $games_links = ObtainLinks($games_links);

        $this->set('{src_link}', $srcStr);
        $this->set('[m_direct_links]', $links);
        $this->set('[music_direct_links]', $music_links);
        $this->set('[games_direct_links]', $games_links);
        $this->set('{title2}', $data['title2']);

        $created = date('Y-m-d H:s:i');
        $clk_script = '
            <script type="text/javascript">
    	<!--
    		function doClk(href)
    		{
				
				jQuery.post("/utils/lnk_click.php",
				{"chksum": "' . md5($member_id['user_id'] . $created . $data['id']) . '",
				"zone": "' . $zone . '",
				"ip": "' . $_SERVER['REMOTE_ADDR'] . '",
				"created": "' . $created . '",
				"id": ' . $data['id'] . ',
				"user_id": ' . $member_id['user_id'] . ',
				"href": href});
    		}

    		function filmClk(lnk)
    		{
    			if (lnk.rel == "online")
    			{
    				this.toggle();

    			}
    			window.setTimeout("doClk(\'" + lnk.href + "\');", 100);
    			return true;
    		}
    	-->
    	</script>
        ';
        $this->set('{clk_script}', $clk_script);
        break;
    default:
        break;
}
?>


