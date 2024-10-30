(function(document, $){
	var yakkerScriptsLoaded = false;

	function loadjscssfile(e,t){if("js"==t)(s=document.createElement("script")).setAttribute("type","text/javascript"),s.setAttribute("src",e);else if("css"==t){var s=document.createElement("link");s.setAttribute("rel","stylesheet"),s.setAttribute("type","text/css"),s.setAttribute("href",e)}void 0!==s&&document.getElementsByTagName("head")[0].appendChild(s)}
	
	function loadYakkerScriptsIfVisible() {
		if (!yakkerScriptsLoaded) {
			yakkerScriptsLoaded = true;
			setTimeout(function(){
				loadjscssfile(baseUrl+"/assets/v3/css/main.css?ver="+ver,"css");
				loadjscssfile(baseUrl+"/assets/v3/js/main.js?ver="+ver,"js");
			}, 100);
		}
	}
	
	function _getParameterByName(name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, "\\$&");
	    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, " "));
	}
	var $className = function(key) {
		return document.getElementsByClassName(key);
	}
	
	var getScriptURL = (function() {
	    var scripts = document.getElementsByTagName('script');
	    var index = scripts.length - 1;
	    var myScript = scripts[index];
	    return function() { return myScript.src; };
	})();
	var thisscript = mcfgf_global.script_real_url; //getScriptURL();
	var baseUrl= thisscript.replace(/\/assets\/js\/v3.+/g, '');
	var ver= _getParameterByName('ver', thisscript) || 'latest';

	var yakkers = $className("yakker-container");

	var isFree = window.mcfgf_is_free || false;

	var yakkerLoadingHTML = '<style>'+
		'.yakker-loading {'+
          'position: absolute;'+
          'left:0px;'+
          'right:0px;'+
          'top:0px;'+
          'bottom: 0px;'+
          'z-index: 1;'+
          'background: #fcfcfc url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgICAgIHdpZHRoPSIyNHB4IiBoZWlnaHQ9IjMwcHgiIHZpZXdCb3g9IjAgMCAyNCAzMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTAgNTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4gICAgPHJlY3QgeD0iMCIgeT0iMTMiIHdpZHRoPSI0IiBoZWlnaHQ9IjUiIGZpbGw9IiMzMzMiPiAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9ImhlaWdodCIgYXR0cmlidXRlVHlwZT0iWE1MIiAgICAgICAgdmFsdWVzPSI1OzIxOzUiICAgICAgICAgYmVnaW49IjBzIiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9InkiIGF0dHJpYnV0ZVR5cGU9IlhNTCIgICAgICAgIHZhbHVlcz0iMTM7IDU7IDEzIiAgICAgICAgYmVnaW49IjBzIiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICA8L3JlY3Q+ICAgIDxyZWN0IHg9IjEwIiB5PSIxMyIgd2lkdGg9IjQiIGhlaWdodD0iNSIgZmlsbD0iIzMzMyI+ICAgICAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iaGVpZ2h0IiBhdHRyaWJ1dGVUeXBlPSJYTUwiICAgICAgICB2YWx1ZXM9IjU7MjE7NSIgICAgICAgICBiZWdpbj0iMC4xNXMiIGR1cj0iMC42cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+ICAgICAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0ieSIgYXR0cmlidXRlVHlwZT0iWE1MIiAgICAgICAgdmFsdWVzPSIxMzsgNTsgMTMiICAgICAgICBiZWdpbj0iMC4xNXMiIGR1cj0iMC42cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+ICAgIDwvcmVjdD4gICAgPHJlY3QgeD0iMjAiIHk9IjEzIiB3aWR0aD0iNCIgaGVpZ2h0PSI1IiBmaWxsPSIjMzMzIj4gICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJoZWlnaHQiIGF0dHJpYnV0ZVR5cGU9IlhNTCIgICAgICAgIHZhbHVlcz0iNTsyMTs1IiAgICAgICAgIGJlZ2luPSIwLjNzIiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9InkiIGF0dHJpYnV0ZVR5cGU9IlhNTCIgICAgICAgIHZhbHVlcz0iMTM7IDU7IDEzIiAgICAgICAgYmVnaW49IjAuM3MiIGR1cj0iMC42cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+ICAgIDwvcmVjdD4gIDwvc3ZnPg==) center center no-repeat;'+
          'background-size: 32px 32px;'+
        '}'+
	'</style><div id="yakker-conversation-container" class="yakker-app-container"></div><div class="yakker-loading"></div>';

	if(isFree) {
		yakkerLoadingHTML += '<a id="yakker-conversation-powered-by" target="_blank" style="position: absolute;right:5px;top:5px;width:17px;height:17px;z-index=2;font-size:9px" href="https://magicconversation.net"><img width="100%" height="100%" src="'+baseUrl+'/assets/img/icon.png"</a>';
	}

	for (var i = 0; i < yakkers.length; i++) {
		yakkers[i].innerHTML = yakkerLoadingHTML;
	}

	loadjscssfile('https://use.fontawesome.com/releases/v5.8.1/css/all.css', "css");

	$(document).ready(function(){
		window.yakkerLoadPureHtml = function(id) {
      var that = $('#'+id);
      var html = that.attr('html');
      if(html) {
        that.html(html);
      }
    }

    window.yakkerDataLoaded = function(data) {
    	if(data.isFree) {

    	}
    }
    // console.log('loadYakkerScriptsIfVisible');
  	loadYakkerScriptsIfVisible();
	});

})(document, jQuery);