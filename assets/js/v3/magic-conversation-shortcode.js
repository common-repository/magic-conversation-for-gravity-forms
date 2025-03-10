(function($) {
	window.mcfgfp_cid = 1;
	window.startMagicConversationalForm = function(form_id, container_selector, style, queryString) {
		var mcfgf_global = window.mcfgf_global;
		if(!mcfgf_global) return;

		

		var ajax_url = mcfgf_global.ajax_url;
		ajax_url = ajax_url.substr(0, ajax_url.indexOf('/wp-admin/admin-ajax.php')) + '/magic-conversation';
		// var oldConversation = $('#magic_conversation_container');
		// if(oldConversation.length > 0) {
		// 	oldConversation.remove();
		// }

		if(container_selector) {
			if(!style) style = "width: 100%; height: 395px";
			// container_selector.attr('style', style+ ';overflow:hidden;display:inline-block;position:relative;');
			setTimeout(function(){
				$('<div class="magic_conversation_embed_container" style="'+style+ ';overflow:hidden;display:inline-block;position:relative;"><div class="magic_conversation_toggle_fullscreen"><i class="icon iconfont icon-shuaxin"></i><i class="icon iconfont icon-fullscreen"></i></div><iframe id="magic_conversation_frame" src="' + ajax_url +'/'+form_id+'/'+ '?action=gf_button_get_form&embed=1' + '&ver=' + mcfgf_global.ver + '" style="width: 100%; height: 100%;overflow:hidden;border: 1px solid rgb(178, 178, 178);box-sizing: border-box;"></iframe></div>').appendTo(container_selector);
			}, 100);
			
			// $('body').toggleClass('mcfgfp-mobile-no-scroll');

			var pum = container_selector.parents('.pum');
  		// console.log('pum001', pum);
  		if(pum.length > 0) {
  			setTimeout(function(){
					pum.css('opacity', '1');
  				// console.log('pum002', pum.css('opacity'));
  			}, 200);
  		}
		}
		else {
			window.mcfgf_handle_global_conversation_form(form_id, true, queryString);
			// var mcfgfppc = $('#mcfgfp-pin-container');
			// if(mcfgfppc.length > 0) {
			// 	mcfgfppc.remove();

			// }
			// $.get(ajax_url+'/form_id/'+'?action=gf_button_get_form&is_global=no', function(response){
			
	  // 		$(response).appendTo('body');//.fadeIn();

	  // 		// if(window['gformInitDatepicker']) {gformInitDatepicker();}

	  // 		//tb_show("Modal title", "#TB_inline?inlineId=modal_container");
	  		
	  // 		// window.loadMagicConversationForm(form_id);
	  // 		// window.ConversationalForm == null;

	  // 		//mobile full screen
	  // 		$('body').toggleClass('mcfgfp-mobile-no-scroll');


	  		
			// });
		}
	}

	function autoEmbedMagicConversation(mc) {
		var form_id = mc.attr('form-id');
		var height = mc.attr('height') || '395px';
		if(!isNaN(height)) {height += 'px';}
		var width = mc.attr('width') || '100%';
		if(!isNaN(width)) {width += 'px';}
		var style = "width: "+width+"; height: "+height;

		window.startMagicConversationalForm(form_id, mc, style);
	}

	function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }

	//auto load conversation generated by short code
	$(document).ready(function(){
		var mcs = $('magic-conversation');
		if(mcs.length > 0 ) {
			mcs.each(function(){
				var mc = $(this);
				var pum = mc.parents('.pum');
				if(pum.length > 0) {
					// console.log('Popup Maker and Conversation', pum);
					pum.on('pumBeforeOpen', function () {
						var $popup = $(this);
						$popup.css('opacity', '0');
						autoEmbedMagicConversation(mc);
				  }); 
					return;
				}
				// console.log('normal Conversation');
				autoEmbedMagicConversation(mc);
			});
			
		}

		// console.log('conversation_id ready');

		window.mcfgf_open_magic_conversation =  function(url) {
			console.log('mcfgf_open_magic_conversation', url);
			if(url && url.indexOf('open-magic-conversation') >= 0 ) {
				var conversation_id = getParameterByName('form_id', url); //url.replace('magic-conversation-open:', '').trim(); //$(this).attr('magic-conversation-form-id');
				// console.log('conversation_id', conversation_id);
				startMagicConversationalForm(conversation_id, null, null, url.split('?')[1]);
				return false;
			}
		}

		$(document).on('click', 'a', function(){
			var url = $(this).attr('href');
			// console.log('conversation_id url', url);
			return window.mcfgf_open_magic_conversation(url);
		});

		$(document).on('click', '#mcfgfp-pin-container .mcfgfp-pin-app-enabled.mcfgfp-pin-app-embed .mcfgfp-pin-team-close', function(){
			$('#mcfgfp-pin-container').remove();
			return false;
		});

		var currentMCContainer = null;


		function makeAllParentsStatic(dom, className){
			myParent = dom.parent();
			if(myParent.length >0) {
				if(!myParent.is( "body" )) {
					myParent.toggleClass(className);
					makeAllParentsStatic(myParent, className);
				}
			}
		}

		$(document).on('click', '.magic_conversation_toggle_fullscreen i.icon-fullscreen', function(){
			var isFullScreen = $('html').hasClass('mcfgfp-fullscreen');
			var isButtonPopupMode = $(this).parent().parent().hasClass('mcfgfp-pin-conversation-parts');
			if(!isFullScreen) {
				currentMCContainer = $(this).parent().parent().parent();
			}



			makeAllParentsStatic(currentMCContainer, !isButtonPopupMode ? 'mcfgfp-fix-position' : 'mcfgfp-abs-position');
			
			var container = isFullScreen ? currentMCContainer : 'body';
			$(this).parent().parent().toggleClass('magic_conversation_fullscreen');//.detach().appendTo(container);
			$('html').toggleClass('mcfgfp-fullscreen');
			if(isButtonPopupMode) {
				var rootNode = $(this).closest('.mcfgfp-pin-app-child-div');
				rootNode.toggleClass('mcfgfp-pin-conversation-form-frame');
			}
			// $('#mcfgfp-pin-container').toggle();
			return false;
		});

		$(document).on('click', '.magic_conversation_toggle_fullscreen i.icon-shuaxin', function(){
			$(this).closest('.magic_conversation_embed_container').find('iframe').attr( 'src', function ( i, val ) { return val; });
			// $('#mcfgfp-pin-container').toggle();
			return false;
		});

		// var sui = window.GformShortcodeUI;
		// sui.strings = mcfgfShortcodeUIData.strings;

  //   sui.shortcodes = new sui.collections.Shortcodes( mcfgfShortcodeUIData.shortcodes );

    // if( ! mcfgfShortcodeUIData.previewDisabled && typeof wp.mce != 'undefined'){
    //     wp.mce.views.register( 'gravityform', $.extend(true, {}, sui.utils.shortcodeViewConstructor) );
    // }
		
	});

})(jQuery);