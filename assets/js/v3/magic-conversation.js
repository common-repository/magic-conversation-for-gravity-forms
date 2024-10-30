(function($) {

	

	

	function mcfgf_fix_bottom() {
		var newHeight = $('.mcfgfp-pin-composer').height();
		var linkHeight = mcfgf_settings.isFree ? $('.mcfgfp-pin-link-container').height()-10 : 0;
		$('.mcfgfp-pin-conversation-body-parts').css('bottom', (newHeight+linkHeight)+'px');

		if(mcfgf_settings.isFree) {
			$('cf-input-control-elements').css('top', (-linkHeight)+'px')
		}
		
		//// console.log('mcfgf_fix_bottom', linkHeight, newHeight, (newHeight+10+linkHeight)+'px');
	}
	function mcfgf_fix_bottom_final() {
		$('cf-input-control-elements cf-list').remove();
		var newHeight = $('.mcfgfp-pin-composer').height();
		var linkHeight = mcfgf_settings.isFree ? $('.mcfgfp-pin-link-container').height()-10 : 0;
		
		$('.mcfgfp-pin-conversation-body-parts').css('bottom', (newHeight +linkHeight)+'px');
	}

	function mcfgf_fix_top() {
		var newHeight = $('.mcfgfp-pin-conversation-body-profile').height();
		// console.log('mcfgf_fix_top newHeight', newHeight);
		if(!newHeight) {
			newHeight = 0;
		}
		$('.mcfgfp-pin-conversation-body-parts').css('top', newHeight+'px');
	}

	function mcfgf_auto_scroll(isFinal) {
		
		setTimeout(function(){

			if(isFinal) {
				mcfgf_fix_bottom_final();
			}
			else {
				mcfgf_fix_bottom();
			}
			return;
			var mbody = $('.mcfgfp-pin-conversation-body-parts');
			if(mbody.length > 0) {
				var mh = mbody.find('cf-chat-response:last-child');
				var scrollTop = mbody.get(0).scrollHeight - mh.height() - 40;
				mbody.animate({
					scrollTop: scrollTop}, 10);
				// 	
				//// console.log('mcfgf_auto_scroll', scrollTop);
			}
			
        }, 1000);
	}

	function _handle_global_conversation_form(form_id, autoShowConversation, queryString) {
		// console.log('_handle_global_conversation_form', form_id);
		if(!form_id) return;
		// if(!window.mcfgf_global) return;

		// var form_id  = mcfgf_global.form_id;
    var ajax_url = mcfgf_global.ajax_url;
    var form = $("#gform_"+form_id);

		// console.log('_handle_global_conversation_form form', form);
    if(form_id && form_id > 0 && form.length ==0) {
    	var mcfgfppc = $('#mcfgfp-pin-container');
			if(mcfgfppc.length > 0) {
				mcfgfppc.remove();
			}

			// console.log('_handle_global_conversation_form 2', 2);

    	$.get(ajax_url+'?action=gf_button_get_form&'+(queryString ? queryString : 'form_id='+form_id), function(response){
    		
    		$('<div id="gf_button_form_container_'+form_id+'"></div>').appendTo('body').html(response);//.fadeIn();

    		if(window['gformInitDatepicker']) {gformInitDatepicker();}
    		
    		

				$('.mcfgfp-notifications-dismiss-button').click(function(){
					$(this).closest('.mcfgfp-notifications').find('.mcfgfp-say-hi-card').remove();
					$(this).remove();
				});

    		var inited =  3;

    		var htmlForm = $('#mcfgfp-gf-container').html();

    		// _prepareConversationFormQuestions2(form_id);
    		// _loadConversationForm(form_id);
    		window.ConversationalForm == null;

    		//side form
		    $('#mcfgfp-pin-container .mcfgfp-pin-btn, .mcfgfp-say-hi-card, .mcfgfp-say-hi-body, .mcfgfp-launcher-badge, #mcfgfp-pin-container  .mcfgfp-pin-team-close').on('click', function(){
		    	// console.log('_loadConversationForm 1', inited);
		    	$('.mcfgfp-notifications-dismiss-button').trigger('click');
		    	$('#mcfgfp-pin-container .mcfgfp-pin-btn').toggleClass('mcfgfp-pin-btn-active');
		    	$('#mcfgfp-pin-container .mcfgfp-pin-app').toggleClass('mcfgfp-pin-app-enabled');
		    	// if($(window).width() < 768) {
		    		$('body').toggleClass('mcfgfp-mobile-no-scroll');
		    	// }
		    	$('.mcfgfp-launcher-badge').toggle();
		    	$iframe = $('#mcfgfp-pin-conversation-parts11 iframe');
		    	if($iframe.length >0 && $iframe.attr('data_src')) {
		    		$iframe.attr('src', $iframe.attr('data_src'));
		    		$iframe.removeAttr('data_src');
		    	}
		    	mcfgf_fix_top();
		    });

		    $('#mcfgfp-pin-container .mcfgfp-pin-team-refresh').on('click', function(){
					$( '.mcfgfp-pin-conversation-parts iframe' ).attr( 'src', function ( i, val ) { return val; });
		    });

		    if(autoShowConversation) {
		    	$('#mcfgfp-pin-container .mcfgfp-pin-btn').trigger('click');
		    }
			});
    }
	}
	window.mcfgf_handle_global_conversation_form = _handle_global_conversation_form;

	$(document).ready(function(){

		if(!window.mcfgf_settings) {
			return;
		}

		if(!mcfgf_settings.isFree) {
			if(typeof(mcfgf_settings.is_valid_license_key)=='undefined') {
				//// console.log('not mcfgf_settings found ');
				return;
			}
			//// console.log('mcfgf_settings', mcfgf_settings);
			if(!(parseInt(mcfgf_settings.is_valid_license_key) == 1)) {
				return;
			}
		}
		if(window.mcfgf) {
			//// console.log('window.mcfgf', JSON.stringify(mcfgf));

		}
		
		if(window.mcfgf_settings) {
			//// console.log('mcfgf_settings', JSON.stringify(mcfgf_settings));
		}

		if(window.mcfgf_settings_basics) {
			//// console.log('mcfgf_settings_basics', JSON.stringify(mcfgf_settings_basics));
		}
		

		var COLOR_REGEX = /^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/;
		function colorHexToRGB(htmlColor) {

		    arrRGB = htmlColor.match(COLOR_REGEX);
		    if (arrRGB == null) {
		        alert("Invalid color passed, the color should be in the html format. Example: #ff0033");
		    }
		    var red = parseInt(arrRGB[1], 16);
		    var green = parseInt(arrRGB[2], 16);
		    var blue = parseInt(arrRGB[3], 16);
		    return {"r":red, "g":green, "b":blue};
		}

		function caculateTransparentColor(foregroundColor, backgroundColor, opacity) {
		    if (opacity < 0.0 || opacity > 1.0) {
		        alert("assertion, opacity should be between 0 and 1");
		    }
		    opacity = opacity * 1.0; // to make it float
		    var foregroundRGB = colorHexToRGB(foregroundColor);
		    var backgroundRGB = colorHexToRGB(backgroundColor);
		    var finalRed = Math.round(backgroundRGB.r * (1-opacity) + foregroundRGB.r * opacity);
		    var finalGreen = Math.round(backgroundRGB.g * (1-opacity) + foregroundRGB.g * opacity);
		    var finalBlue = Math.round(backgroundRGB.b * (1-opacity) + foregroundRGB.b * opacity);
		    return colorRGBToHex(finalRed, finalGreen, finalBlue);
		}

		function colorRGBToHex(red, green, blue) {
		    if (red < 0 || red > 255 || green < 0 || green > 255 || blue < 0 || blue > 255) {
		        alert("Invalid color value passed. Should be between 0 and 255.");
		    }
		    var formatHex = function(value) {
		        value = value + "";
		        if (value.length == 1) {
		            return "0" + value;
		        }
		        return value;
		    }
		    hexRed = formatHex(red.toString(16));
		    hexGreen = formatHex(green.toString(16));
		    hexBlue = formatHex(blue.toString(16));

		    return "#" + hexRed + hexGreen + hexBlue;
		}


		var mcfgf_css_ex = ''+"\r\n";

		if(mcfgf_settings_basics.conversation_header_background_color) {
			mcfgf_css_ex += '.mcfgfp-pin-conversation-body-profile { background-color: '+mcfgf_settings_basics.conversation_header_background_color+' !important;}'+"\r\n";
		}

		if(mcfgf_settings_basics.conversation_header_font_color) {
			mcfgf_css_ex += '#mcfgf-pin-container .mcfgf-pin-team-profile-full-response-delay, #mcfgf-pin-container .mcfgf-pin-team-profile-full-team-name { color: '+mcfgf_settings_basics.conversation_header_font_color+' !important;}'+"\r\n";
		}

		if(mcfgf_settings_basics.conversation_notification_background_color) {
			mcfgf_css_ex += '.mcfgfp-say-hi-card { background-color: '+mcfgf_settings_basics.conversation_notification_background_color+' !important;}'+"\r\n";
			
			mcfgf_css_ex += '#mcfgfp-pin-container .mcfgfp-say-hi-card::after { border-color: transparent transparent '+mcfgf_settings_basics.conversation_notification_background_color+' !important;}'+"\r\n";
		}

		if(mcfgf_settings_basics.conversation_notification_font_color) {
			mcfgf_css_ex += '.mcfgfp-say-hi-card .mcfgfp-say-hi-title-name, .mcfgfp-say-hi-card .mcfgfp-say-hi-body, .mcfgfp-say-hi-card .mcfgfp-say-hi-body span { color: '+mcfgf_settings_basics.conversation_notification_font_color+' !important;}'+"\r\n";
		}

		if(mcfgf_settings_basics.allow_image_button !== 'on') {
			if(mcfgf_settings_basics.conversation_button_background_color) {
				mcfgf_css_ex += '.mcfgfp-pin-btn { background-color: '+mcfgf_settings_basics.conversation_button_background_color+' !important;}'+"\r\n";
			}

			if(mcfgf_settings_basics.conversation_button_image_normal) {
				mcfgf_css_ex += '#mcfgfp-pin-container .mcfgfp-pin-btn .mcfgfp-pin-btn-open { background-image: url('+mcfgf_settings_basics.conversation_button_image_normal+') !important;}'+"\r\n";
			}

			if(mcfgf_settings_basics.conversation_button_image_active) {
				mcfgf_css_ex += '#mcfgfp-pin-container .mcfgfp-pin-btn .mcfgfp-pin-btn-close { background-image: url('+mcfgf_settings_basics.conversation_button_image_active+') !important;}'+"\r\n";
			}
		}

		if(window.mcfgf && window.mcfgf.css_options) {
			var mcfgf_css_options = JSON.parse(window.mcfgf.css_options);

			mcfgf_css_ex += 'cf-input-button.cf-input-button.mcfgf_done {background: '+mcfgf_css_options.userBackgroundColor+'; border: 1px solid '+mcfgf_css_options.userFontColor+'; font-family: Arial,Helvetica,sans-serif}';

			mcfgf_css_ex += 'cf-input-button.cf-input-button.mcfgf_done .cf-icon-progress:before { color: '+mcfgf_css_options.userFontColor+' ; font-family: Arial,Helvetica,sans-serif}';
			
			mcfgf_css_ex += 'cf-input-button.cf-input-button.mcfgf_done:hover {background: '+mcfgf_css_options.userFontColor+'; border: 1px solid '+mcfgf_css_options.userBackgroundColor+'; font-family: Arial,Helvetica,sans-serif}';

			mcfgf_css_ex += 'cf-input-button.cf-input-button.mcfgf_done:hover .cf-icon-progress:before { color: '+mcfgf_css_options.userBackgroundColor+' ; font-family: Arial,Helvetica,sans-serif}';
		}
		

		var conversation_input_done_button_label = window.mcfgf_settings_basics.conversation_input_done_button_label;
		var conversation_input_done_button_width = window.mcfgf_settings_basics.conversation_input_done_button_width;

		if(conversation_input_done_button_label) {
			mcfgf_css_ex += '.mcfgf_done .cf-icon-progress:before { content: "'+conversation_input_done_button_label+'"; }';
		}

		if(conversation_input_done_button_width) {
			if(parseInt(conversation_input_done_button_width)+''==conversation_input_done_button_width+'') {
				conversation_input_done_button_width = conversation_input_done_button_width + 'px';
			}
			mcfgf_css_ex += 'cf-input-button.cf-input-button.mcfgf_done {width:'+conversation_input_done_button_width+';}';
		}
		

		var bc = mcfgf_settings_basics.conversation_toolbar_button_color;
		if(bc) {

			mcfgf_css_ex += '.cf-button, .cf-icon-progress, cf-list-button, .cf-button.cf-checkbox-button cf-checkbox::after { color: '+bc+' !important;}'+"\r\n";

			

			mcfgf_css_ex += 'cf-input-button.cf-input-button:hover, cf-input-button.cf-input-button:focus, cf-radio-button.cf-button cf-radio, .cf-button.cf-checkbox-button cf-checkbox {'+"\r\n"
			    +'background: '+caculateTransparentColor(bc, '#FFFFFF', 0.05)+';'+"\r\n"
			+'}'+"\r\n";

			mcfgf_css_ex += 'cf-radio-button.cf-button:focus cf-radio, cf-radio-button.cf-button:hover cf-radio, cf-radio-button.cf-button[checked="checked"] cf-radio, .cf-button.cf-checkbox-button:hover cf-checkbox {'+"\r\n"
			    +'background: '+bc+';'+"\r\n"
			+'}'+"\r\n";

			mcfgf_css_ex += 'cf-input-button.cf-input-button:hover, cf-input-button.cf-input-button:focus {'+"\r\n"
			    +'border: 1px solid '+caculateTransparentColor(bc, '#FFFFFF', 0.5)+';'+"\r\n"
			+'}'+"\r\n";

			mcfgf_css_ex += '.cf-button {'+"\r\n"
			    +'border: 1px solid '+caculateTransparentColor(bc, '#FFFFFF', 0.5)+';'+"\r\n"
			+'}'+"\r\n";

			mcfgf_css_ex += '.cf-button:hover, .cf-button:focus {'+"\r\n"
			    +'background: '+caculateTransparentColor(bc, '#FFFFFF', 0.05)+';'+"\r\n"
			+'}'+"\r\n";
		}
		
		if(window.mcfgf) {
			var $container = $('<style id="mcfgf-css" type="text/css"></style>').appendTo("body");
			if(mcfgf.css_code.indexOf('cf-chat-response text') < 0) {
				mcfgf.css_code = mcfgf.css_code.replace('cf-chat-response text', 'cf-chat-response text').replace('cf-chat-response.user text', 'cf-chat-response.user text');
			}
			
	    $container.text(mcfgf.css_code+mcfgf_css_ex+mcfgf_settings_basics.custom_css);
	    
		  setTimeout(function(){
				if(!mcfgf_settings.isFree) {
		    	if(window.mcfgf_normal){
		    		//_handle_normal_conversation_form();
		    	}
					else {
						_handle_global_conversation_form(mcfgf_global ? mcfgf_global.form_id : false, false);
					}
				}
				else if(window.mcfgf_global) {
					var form_id  = mcfgf_global.form_id;
					if($('#gf_'+form_id).length > 0) {
						//_handle_normal_conversation_form();
					}
					else {
						_handle_global_conversation_form(form_id, false);
					}
				}
		  },100);
		}
	});

})(jQuery);
