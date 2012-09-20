<?php

    /**
     *  Funcs
     *
     */
    function netMatch($CIDR, $IP)
    {
        list ($net, $mask) = explode('/', $CIDR);
        return (ip2long(trim($IP)) & ~((1 << (32 - trim($mask))) - 1)) == ip2long(trim($net));
    }

    /**
     *  Getters
     *
     */
    function getZone()
    {
        global $db;

        if (isset($_GET['force_zone'])) {
            if ($_GET['force_zone'] == 0)
                unset ($_SESSION['zone']);
            else
                $_SESSION['zone'] = $_GET['force_zone'];
            return $_GET['force_zone'];
        }
        if ($_SESSION['zone'] && $_SESSION['zone'] <> 0)
            return $_SESSION['zone'];

        @include ROOT_DIR . '/engine/cache/system/zones.php';
        $zones = @unserialize($zones);

        if ($cachedate < (time() - 60 * 10)) {
//if(1){       
            $zones = array();
            $SelectZonesSQL = $db->query("SELECT * FROM zones order by priority desc");
            while ($zone = $db->get_row($SelectZonesSQL)) {
                $zones[] = $zone;
            }
            $db->free($SelectZonesSQL);

            $handler = @fopen(ROOT_DIR . '/engine/cache/system/zones.php', "w");
            fwrite($handler, "<?PHP \n\n//System Cache\n\n\$cachedate = '" . time() . "';\n\n\$zones ='" . serialize($zones) . "';  \n\n");
            fwrite($handler, "\n?>");
            fclose($handler);
        }


        $type = 0;
        $isOperaMini = false;
        #while ($zone = $db->get_row($SelectZonesSQL)) {
        foreach ($zones as $zone) {
            if (
                netMatch($zone['network'] . '/' . $zone['mask'], $_SERVER['REMOTE_ADDR'])
                ||
                netMatch($zone['network'] . '/' . $zone['mask'], $_SERVER['X_REAL_IP'])
            ) {
                $type = $zone['type'];
                $_SESSION['zone'] = $type;
                break;
            }
        }


        return $type;
    }


function makeFlow($hideContent){
    global $member_id;
    global $row;
    $flowServer = '92.63.196.82';
    $flowServerWport = '92.63.196.82:82';

    function getDefinition($fn)
    {
        $definitions = array('iso', 270, 404, 720, 1080);
        foreach ($definitions as $d)
        {
            if (strpos($fn, '/' . $d . '/')) return $d;
        }
        return 0;
    }

    function getPart($fn)
    {
        $p = strpos($fn, 'part_');
        if ($p)
        {
            return intval(substr($fn, $p + 5, 1));
        }
        return 1;
    }

    //if (strpos($hideContent, $flowServerWport))
    if (strpos($hideContent, $flowServer))
    {
        $directLinks = array();
        preg_match_all('#(http\:\/\/' . preg_quote($flowServer) . '\/)([^:]+)\.(mp4|iso|avi|mkv)#i', $hideContent, $directLinks);
        $flowLinks = array();
        preg_match_all('#(http\:\/\/' . preg_quote($flowServerWport) . '\/)([^:]+)\.(mp4|iso)#i', $hideContent, $flowLinks);
        if (!empty($directLinks[2]))
        {
            if (!empty($flowLinks[2]))
            {
//echo '<pre>';
//print_r($directLinks);
//print_r($flowLinks);
//echo '</pre>';
//exit;

//ÏÐÅÎÁÐÀÇÓÅÌ ÑÑÛËÊÈ È ÄÎÁÀÂËßÅÌ ÎÍËÀÉÍ-ÏÐÎÈÃÐÛÂÀÒÅËÜ
                $newContent = $hideContent;

                $downloadLinks = array();
                $playOnlineLinks = array();
                $definitions = array('iso', 270, 404, 720, 1080);

                foreach ($directLinks[2] as $dk => $dl)
                {
                    foreach ($flowLinks[2] as $fk => $fl)
                    {
                        if ($dl == $fl)
                        {
                            break;
                        }
                    }
                    $statLink = ' <a href="/hawk/hawk2.php?id=' . $row['id'] . '&href=' . $directLinks[0][$dk] . '">[?]</a> ';
                    if ($member_id['user_group'] > 1)
                        $statLink = '';
                    if ($dl == $fl)
                    {
                        $statOnlineLink = ' <a href="/hawk/hawk2.php?id=' . $row['id'] . '&href=' . $flowLinks[0][$fk] . '.online">[?]</a> ';
                        if ($member_id['user_group'] > 1)
                            $statOnlineLink = '';
                        $newContent = str_replace($flowLinks[0][$fk], '', $newContent);
                        $newContent = str_replace("<br /><br />", '<br />', $newContent);
                        //$newContent = str_replace($directLinks[0][$dk], $statLink . '<a title="ñêà÷àòü" onclick="return filmClk(this);" href="' . $directLinks[0][$dk] . '">' . $directLinks[0][$dk] . '</a> (' . $statOnlineLink . '<a href="' . $flowLinks[0][$fk] . '.online" onclick="document.getElementById(\'server\').value=\'' . $flowLinks[1][$fk] . '\'; document.getElementById(\'path\').value=\'' . $flowLinks[0][$fk] . '\'; filmClk(this); return addVideo();"><b>ñìîòðåòü online</b></a>)', $newContent);

                        $newContent = str_replace($directLinks[0][$dk], '', $newContent);
                        $definition = getDefinition($directLinks[0][$dk]);
                        $part = getPart($directLinks[0][$dk]);
                        $downloadLinks[$definition][$part] = '<a title="ñêà÷àòü" onclick="return filmClk(this);" href="' . $directLinks[0][$dk] . '"><img src="/templates/rumedia/images/hd/d' . $definition . 'p' . $part . '.png" width="150" height="40" /></a>';
                        //$playOnlineLinks[$definition][$part] = '<a rel="online" href="' . $flowLinks[0][$fk] . '.online" onclick="filmClk(this); return addVideo(\'' . $flowLinks[0][$fk] . '\');"><img src="/templates/wsm/images/hd/v' . $definition . 'p' . $part . '.png" width="150" height="40" /></a>';
                        $playOnlineLinks[$definition][$part] = '
                                                        <div style="display: none"><div id="video' . $fk . '"><a style="width:640px; height:480px; display:block" id="ipad' . $fk . '" onclick="return addVideo(' . $fk . ', \'' . $flowLinks[0][$fk] . '\');"></a></div></div>
                                                        <a rel="video" alt="" title="" href="#video' . $fk . '"><img src="/templates/rumedia/images/hd/v' . $definition . 'p' . $part . '.png" width="150" height="40" /></a>';
                    }
                    else
                    {
                        //$newContent = str_replace($directLinks[0][$dk], $statLink . '<a title="ñêà÷àòü" onclick="return filmClk(this);" href="' . $directLinks[0][$dk] . '">' . $directLinks[0][$dk] . '</a>', $newContent);
                        $newContent = str_replace($directLinks[0][$dk], '', $newContent);
                        $definition = getDefinition($directLinks[0][$dk]);
                        $part = getPart($directLinks[0][$dk]);
                        $downloadLinks[$definition][$part] = '<a title="ñêà÷àòü" onclick="return filmClk(this);" href="' . $directLinks[0][$dk] . '"><img src="/templates/rumedia/images/hd/d' . $definition . 'p' . $part . '.png" width="150" height="40" /></a>';
                        $playOnlineLinks[$definition][$part] = '';
                    }
                }
//      <script type="text/javascript" src="/templates/wsm/scripts/jquery.js"></script>
//<script type="text/javascript" src="/templates/rumedia/scripts/jquery.fancybox-1.3.4/jquery-1.4.3.min.js"></script>
                $playerCode = '
        <link rel="stylesheet" type="text/css" href="/templates/rumedia/css/fancybox-1.3.4/jquery.fancybox-1.3.4.css" />
        <script type="text/javascript" src="/templates/rumedia/scripts/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.js"></script>
        <script type="text/javascript" src="/templates/rumedia/scripts/flowplayer/flowplayer-3.2.4.min.js"></script>
        <script type="text/javascript" src="/templates/rumedia/scripts/flowplayer/flowplayer.ipad-3.2.1.js"></script>

                                                <div id="flowplayerdiv" style="display: none"><center>
                                                <h4><a href="#" onclick="document.getElementById(\'flowplayerdiv\').style.display=\'none\'; return false;">âûêëþ÷èòü ïðîèãðûâàòåëü</a></h4>
        <a href="#"
                style="display:block;width:95%;height:297px"
                id="ipad">
        </a>
                                                </div>

        <script>
                $(document).ready(function() {
                        $("a[rel=video]").fancybox({
                        "zoomSpeedIn":  0,
                        "zoomSpeedOut": 0,
                        "overlayShow":  true,
                        "overlayOpacity": 0.8,
                        "showNavArrows": false,
                                "onComplete": function() { $(this.href + " a").trigger("click"); return false; }
                        });
                });

                function addVideo(num, path) {
                        document.getElementById("ipad" + num).href=path;
                        document.getElementById("video" + num).style.display="";
                        $f("ipad" + num, "/templates/rumedia/scripts/flowplayer/flowplayer-3.2.5.swf",
                                                                {plugins: {
                                                                        h264streaming: {
                                                                                url: "/templates/rumedia/scripts/flowplayer/flowplayer.pseudostreaming-3.2.5.swf"
                                                                                                 }
                                     },
                                                                clip: {
                                                                        provider: "h264streaming",
                                                                        autoPlay: true,
                                                                        scaling: "fit",
                                                                        autoBuffering: true,
                                                                        scrubber: true
                                                                },
                                                                canvas: {
                                                                        // remove default canvas gradient
                                                                        backgroundGradient: "none",
                                                                        backgroundColor: "#000000"
                                                                }
                                        }
                                                ).ipad();
                        return false;
                }
        </script>
                                        ';

                $tbl = '<table cellspacing="3"><tr align="center"><td><h4>ñêà÷àòü</h4></td><td><h4>ñìîòðåòü online</h4></td></tr>';
                foreach ($definitions as $dfn)
                {
                    for ($part = 1; $part < 5; $part++)
                    {
                        if ((empty($downloadLinks[$dfn][$part])) && (empty($playOnlineLinks[$dfn][$part])))
                            break;
                        $tbl .= '<tr valign="middle">';
                        if (!empty($downloadLinks[$dfn][$part]))
                        {
                            $tbl .= '<td>' . $downloadLinks[$dfn][$part] . '</td>';
                        }
                        else
                            $tbl .= '<td></td>';
                        if (!empty($playOnlineLinks[$dfn][$part]))
                        {
                            $alt = 'Êà÷åñòâî ' . $dfn . 'p';
                            if (!empty($playOnlineLinks[$dfn][2]))
                            {
                                $alt .= ' ÷àñòü ' . $part;
                            }
                            $playOnlineLinks[$dfn][$part] = str_replace('alt="" title=""', 'alt="' . $alt . '" title="' . $alt . '"', $playOnlineLinks[$dfn][$part]);
                            $tbl .= '<td>' . $playOnlineLinks[$dfn][$part] . '</td>';
                        }
                        else
                            $tbl .= '<td></td>';
                        $tbl .= '</tr>';
                    }
                }
                $tbl .= '</table>';

                $newContent = $playerCode . $newContent . $tbl;
               return $newContent;
            }
            else
            {
                //ÏÐÅÎÁÐÀÇÓÅÌ ÑÑÛËÊÈ ÁÅÇ ÎÍËÀÉÍ-ÏÐÎÈÃÐÛÂÀÒÅËß
                $newContent = $hideContent;
                foreach ($directLinks[2] as $dk => $dl)
                {

                    //$statLink = ' <a href="/hawk/hawk2.php?id=' . $row['id'] . '&href=' . $directLinks[0][$dk] . '">[?]</a> ';
                    //if ($member_id['user_group'] > 1)
                    $statLink = '';
                    $newContent = str_replace($directLinks[0][$dk], $statLink . '<a title="ñêà÷àòü" onclick="return filmClk(this);" href="' . $directLinks[0][$dk] . '">' . $directLinks[0][$dk] . '</a>', $newContent);
                }
                return $newContent;
            }
        }
    }
}
