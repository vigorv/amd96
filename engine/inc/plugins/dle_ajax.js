function showBusyLayer() {

};

function dle_ajax(file){
	this.AjaxFailedAlert = "AJAX not supported.\n";
	this.requestFile = file;
	this.method = "POST";
	this.URLString = "";
	this.encodeURIString = true;
	this.execute = false;
	this.add_html = false;
	this.effect = false;
	this.loading_fired		= 0;
	this.centerdiv          = null;

	this.onLoading = function() { };
	this.onLoaded = function() { };
	this.onInteractive = function() { };
	this.onCompletion = function( response ) { };

	this.onShow = function( message )
	{
		if ( ! this.loading_fired )
		{
			this.loading_fired = 1;
		
			//------------------------------------------------
			// Change text?
			//------------------------------------------------
		
			if ( message )
			{
				$("#loading-layer-text").html(message);
			}
		
			var setX = ( $(window).width()  - $("#loading-layer").width()  ) / 2;
			var setY = ( $(window).height() - $("#loading-layer").height() ) / 2;
			
		    $("#loading-layer").css( {
		      left : setX + "px",
		      top : setY + "px",
		      position : 'fixed',
		      zIndex : '99'
		    });
		
			$("#loading-layer").fadeTo('slow', 0.6);
		}
		
		return;
	};

	this.onHide = function()
	{
		$("#loading-layer").fadeOut('slow');
	
		this.loading_fired = 0;
	
		return;
	};


	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (err) {
				this.xmlhttp = null;
			}
		}
		if(!this.xmlhttp && typeof XMLHttpRequest != "undefined")
			this.xmlhttp = new XMLHttpRequest();
		if (!this.xmlhttp){
			this.failed = true; 
		}
	};
	
	this.setVar = function(name, value){
		if (this.URLString.length < 3){
			this.URLString = name + "=" + value;
		} else {
			this.URLString += "&" + name + "=" + value;
		}
	};
	
	this.encVar = function(name, value){
		var varString = encodeURIComponent(name) + "=" + encodeURIComponent(value);
	return varString;
	};
	
	this.encodeURLString = function(string){
		varArray = string.split('&');
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split('=');
			if (urlVars[0].indexOf('amp;') != -1){
				urlVars[0] = urlVars[0].substring(4);
			}
			varArray[i] = this.encVar(urlVars[0],urlVars[1]);
		}
	return varArray.join('&');
	};

	this.encodeVAR = function(url){
		url = url.toString();
		url = url.replace(/\+/g, "%2B");
		url = url.replace(/\=/g, "%3D");
		url = url.replace(/\?/g, "%3F");
		url = url.replace(/\&/g, "%26");	
	  return url;

	};
	
	this.runResponse = function(){

                        var milisec = new Date;
                        var jsfound = false;
                        milisec = milisec.getTime();

                        var js_reg = /<script.*?>(.|[\r\n])*?<\/script>/ig;

                        var js_str = js_reg.exec(this.response);
                        if (js_str != null) {

						var js_arr = new Array(js_str.shift());
                        var jsfound = true;
        
                        while(js_str) {
                                js_str = js_reg.exec(this.response);
                                if (js_str != null) js_arr.push(js_str.shift());
                        }

                          for(var i=0; i<js_arr.length;i++) {
                                this.response = this.response.replace(js_arr[i],'<span id="'+milisec+i+'" style="display:none;"></span>');
                          }
						}
                            if ( this.add_html ) {
                                this.elementObj.innerHTML += this.response; 
                            } else {
                                this.elementObj.innerHTML = this.response; 
                            }

                        if (jsfound) {

                        var js_content_reg = /<script.*?>((.|[\r\n])*?)<\/script>/ig;

                        for (i = 0; i < js_arr.length; i++) {
                                var mark_node = document.getElementById(milisec+''+i);
                                var mark_parent_node = mark_node.parentNode;
                                mark_parent_node.removeChild(mark_node);
                                
                                js_content_reg.lastIndex = 0;
                                var js_content = js_content_reg.exec(js_arr[i]);
                                var script_node = mark_parent_node.appendChild(document.createElement('script'));
							    script_node.text = js_content[1];  

                                var script_params_str = js_arr[i].substring(js_arr[i].indexOf(' ',0),js_arr[i].indexOf('>',0));
                                var params_arr = script_params_str.split(' ');

								if (params_arr.length > 1) {
                                   for (var j=0;j< params_arr.length; j++ )        {
                                        
                                        if(params_arr[j].length > 0){
                                                var param_arr = params_arr[j].split('=');
                                                param_arr[1] = param_arr[1].substr(1,(param_arr[1].length-2));
                                                script_node.setAttribute(param_arr[0],param_arr[1]);
                                        }

                                  }
								}

                          }
                        }
	};


	
	this.sendAJAX = function(urlstring){
		this.responseStatus = new Array(2);
		if(this.failed && this.AjaxFailedAlert){ 
			alert(this.AjaxFailedAlert); 
		} else {
			if (urlstring){ 
				if (this.URLString.length){
					this.URLString = this.URLString + "&" + urlstring; 
				} else {
					this.URLString = urlstring; 
				}
			}
			if (this.encodeURIString){
				var timeval = new Date().getTime(); 
				this.URLString = this.encodeURLString(this.URLString);
				this.setVar("rndval", timeval);
			}
			if (this.element) { this.elementObj = document.getElementById(this.element); }
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					var totalurlstring = this.requestFile + "?" + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
				}
				if (this.method == "POST"){
  					try {
						this.xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');  
					} catch (e) {}
				}

				this.xmlhttp.send(this.URLString);
				this.xmlhttp.onreadystatechange = function() {
					switch (self.xmlhttp.readyState){
						case 1:
							self.onLoading();
						break;
						case 2:
							self.onLoaded();
						break;
						case 3:
							self.onInteractive();
						break;
						case 4:
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;
						    self.onHide();
							self.onCompletion( self.response );
							if (self.elementObj) {
								var elemNodeName = self.elementObj.nodeName;
								elemNodeName = elemNodeName.toLowerCase();
								if (elemNodeName == "input" || elemNodeName == "select" || elemNodeName == "option" || elemNodeName == "textarea"){
									if (self.response == 'error') { DLEalert('This action can not be completed. Access denied.', 'info'); } else {
                                    if ( self.add_html ) {
									self.elementObj.value += self.response;
                                    } else { self.elementObj.value = self.response;}
									}
								} else {
									if (self.response == 'error') { DLEalert('This action can not be completed. Access denied.', 'info'); } else {
									if(self.execute) { self.runResponse(); } else {
                                        if ( self.add_html ) {

                                           self.elementObj.innerHTML += self.response;

                                        } else	{ 

											if (self.effect == "left" ) {

												$("#" + self.element).hide('slide',{ direction: "left" }, 500).html(self.response).show('slide',{ direction: "right" }, 500);
	
											} else if (self.effect == "right") { 

												$("#" + self.element).hide('slide',{ direction: "right" }, 500).html(self.response).show('slide',{ direction: "left" }, 500);
	
											} else if (self.effect == "fade") { 

												$("#" + self.element).fadeOut(500, function() {
																				        $(this).html(self.response);
																				        $(this).fadeIn(500);
																				      });

											} else {

												self.elementObj.innerHTML = self.response; 
											}


										}
                                      }
									}
								}

								if (self.effect == "blind" && self.response != 'error' && document.getElementById('blind-animation')) {

								 $("html"+( ! $.browser.opera ? ",body" : "")).animate({scrollTop: $("#" + self.element).position().top - 70}, 1100);

								 setTimeout(function() { $("#blind-animation").show('blind',{},1500, function() { if ( document.getElementById('dle-captcha') ) reload(); } )}, 1100);

								}
							}
							self.URLString = "";
						break;
					}
				};
			}
		}
	};
this.createAJAX();
};