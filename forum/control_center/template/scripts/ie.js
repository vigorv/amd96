function mkwidth(){

    var minwidth = document.getElementById("siteWidth").currentStyle['min-width'].replace('px', '');
    var maxwidth = document.getElementById("siteWidth").currentStyle['max-width'].replace('px', '');
    
    document.getElementById("siteWidth").style.width = document.documentElement.clientWidth < minwidth ? minwidth+"px" : (document.documentElement.clientWidth > maxwidth ? maxwidth+"px" : "100%");


    if (document.getElementById("siteWidthTop").currentStyle['min-width'] 
        && document.getElementById("siteWidthTop").currentStyle['max-width']) {
        
        var minwidthTop = document.getElementById("siteWidthTop").currentStyle['min-width'].replace('px', '');
        var maxwidthTop = document.getElementById("siteWidthTop").currentStyle['max-width'].replace('px', '');
    
        document.getElementById("siteWidthTop").style.width = document.documentElement.clientWidth < minwidthTop ? minwidthTop+"px" : (document.documentElement.clientWidth > maxwidthTop ? maxwidthTop+"px" : "100%");
    }

    if (document.getElementById("siteWidthBtm").currentStyle['min-width'] 
        && document.getElementById("siteWidthBtm").currentStyle['max-width']) {
        
        var minwidthBtm = document.getElementById("siteWidthBtm").currentStyle['min-width'].replace('px', '');
        var maxwidthBtm = document.getElementById("siteWidthBtm").currentStyle['max-width'].replace('px', '');
    
        document.getElementById("siteWidthBtm").style.width = document.documentElement.clientWidth < minwidthBtm ? minwidthBtm+"px" : (document.documentElement.clientWidth > maxwidthBtm ? maxwidthBtm+"px" : "100%");
    }
};

window.attachEvent('onload', mkwidth);
window.attachEvent('onresize', mkwidth);
