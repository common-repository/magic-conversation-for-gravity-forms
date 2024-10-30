(function($) {

	function _prepareConversationFormQuestions(form_id) {
		var placeholder = window.mcfgf_settings_basics.conversation_input_placeholder;
		$('.mcfgf_question_text').each(function(){
			var questions = $(this).html().replace(/\n/g, "|");
			var inputid = $(this).parent().find('.gfield_label').attr('for');

			$('#'+inputid).attr('cf-questions', questions);
			$('#'+inputid).attr('cf-input-placeholder', placeholder);
		})
	}

	function _prepareConversationFormCheckboxesRaw(jqElements, force) {
		jqElements.each(function(){
			var $that = $(this);
			
			if(typeof($that.attr("org-name"))!="undefined"){
				if(!force) {
					return;
				}
				else {
					$that.attr('name', $that.attr('org-name').split('.')[0]);
				}
			}
			else {
				var name = $that.attr('name');

				$that.attr('org-name', name+"").attr('name', name.split('.')[0]);
			}
		})
	}

	mcfgf.prepareConversationFormCheckboxes = function(form_id, force) {
		_prepareConversationFormCheckboxesRaw($('#gform_'+form_id+' input[type=checkbox]'), force);
	}

	function _restoreConversationFormCheckboxes(form_id) {
		$('#gform_'+form_id+' input[type=checkbox]').each(function(){
			var $that = $(this);
			var name = $that.attr('org-name');
			$that.attr('name', name);
		})
	}

	function _isValidTag(element) {
		if (element.getAttribute("type") === "hidden")
        return false;
    if (element.getAttribute("type") === "submit")
        return false;
    // ignore buttons, we submit the form automatially
    if (element.getAttribute("type") == "button")
        return false;
    if (element.style) {
        // element style can be null if markup is created from DOMParser
        if (element.style.display === "none")
            return false;
        if (element.style.visibility === "hidden")
            return false;
    }
		var gfield = $(element).closest('.gfield');
		if((gfield.css('display') == 'none') || gfield.hasClass('gfield_visibility_hidden')){
			return false;
		}
		
		if($(element).parent().hasClass('name_last')) {
			return false;
		}
		else if($(element).parent().hasClass('gform_hidden')) {
			return false;
		}
		// else if($(element).parent().hasClass('ginput_right') && $(element).parent().parent().hasClass('ginput_container_password')) {
		// 	return false;
		// }
		else if($(element).attr('type')=='hidden') {
			return false;
		}
		else if($(element).parent().hasClass('chosen-search')) {
			return false;
		}
		else if($(element).parent().is('[data-label]')) {
			$(element).attr('cf-label', $(element).parent().attr('data-label'));
		}
		

		return true;
	}

	mcfgf.prepareTags = function(formEl) {
		var fields = [].slice.call(formEl.querySelectorAll("input, textarea, select, button"), 0);
		//console.log('element--22', 'fields', fields);
		var tags = [];
		var lastI = 0;
		// Fix issue of multiple page. 2018-7-30
		// cf.Tag.isTagValid = _isValidTag;
		for (var i = 0; i < fields.length; i++) {
		    var element = fields[i];
		    
		    if(_isValidTag(element)) {
		    	lastI = i;
		    	var tagnew = cf.Tag.createTag($(element).removeAttr('style').get(0));
		    	if(tagnew) {
		    		tags.push(tagnew);
		    	}
		    	
		    	//console.log('element--22', element, cf.Tag.createTag($(element).removeAttr('style').get(0)));
		    }
		}

		// if(lastI < fields.length) {
		// 	window.mcfgf_prevent_submit_again_on_callback = true;
		// }

		//console.log("_prepareTags001--1", tags);
		return tags;
	}

	/*
	//magic conversation injection add it to conversational-form
                if(mcfgf && mcfgf.fixeValueForMergedTagsInQuestion) {
                    innerResponse = mcfgf.fixeValueForMergedTagsInQuestion(innerResponse);
                }
	 */

	

	mcfgf.tagForLabel = function(jqLabel){
		return jqLabel.text().replace('*', '').toLowerCase();
	}

	mcfgf.questionForLabel = function(jqLabel){
		return jqLabel.text().replace('*', '');
	}

	function _prepareConversationFormQuestions2(form_id) {
		//console.log('mcfgf_questions', mcfgf_questions);
		var placeholder = window.mcfgf_settings_basics.conversation_input_placeholder;
		var placeholder_for_ratio = window.mcfgf_settings_basics.conversation_input_placeholder_for_radio;

		// var placeholder_for_checkbox = window.mcfgf_settings_basics.conversation_input_placeholder_for_checkbox;

		// //console.log('placeholder', placeholder, placeholder_for_ratio, placeholder_for_checkbox);

		mcfgf.prepareConversationFormCheckboxes(form_id);

		mcfgf.mergedTagsMap = {};

		mcfgf.findInputJQDom = function($that){
			var inputid = $that.attr('for');
			if(inputid) {
				return $('#'+inputid);
			}
			else {
				// var radioChoiceId = $(this).parent().attr('id').replace('field_','choice_')+'_0';
				// $('#'+radioChoiceId).attr('cf-questions', questions);
				
				var dom_ratio = $that.closest('.gfield').find('.gfield_radio > li > input:first-child');
				if(dom_ratio.length > 0) return dom_ratio;

				var dom_checkbox = $that.closest('.gfield').find('.gfield_checkbox > li > input:first-child');
				if(dom_checkbox.length > 0) return dom_checkbox;

				var dom_ratio = $that.closest('.gfield').find('.gsurvey-likert-choice > input:first-child');
				if(dom_ratio.length > 0) return dom_ratio;

				var dom_ratio = $that.closest('.gfield').find('.gsurvey-rating > input:first-child');
				if(dom_ratio.length > 0) return dom_ratio;

				
				
			}

			return false;
		};

		$('#gform_'+form_id+' .gfield_label').each(function(){
			var $that = $(this);
			var gfield_label = mcfgf.tagForLabel($that);
			var jqInputDom = mcfgf.findInputJQDom($that);
			if(jqInputDom==false) return;
			//console.log('findInputJQDom', jqInputDom, gfield_label, jqInputDom.attr('id'));
			var inputid = jqInputDom.attr('id');
			//update mergedTagsMap
			if(typeof(mcfgf.fixFirstNameLastMameIssueForMergedTags) == 'function') {
				mcfgf.mergedTagsMap[gfield_label] = inputid;
				mcfgf.fixFirstNameLastMameIssueForMergedTags(inputid);
			}

			var questions = '';
			var mcfgf_field_questions = $that.closest('.gfield').find('.mcfgf_questions');
			if(mcfgf_field_questions.length > 0) {
				questions = mcfgf_field_questions.html().split('\r\n').join('|');
			}
			else if(mcfgf_questions && mcfgf_questions[gfield_label] && mcfgf_questions[gfield_label].length > 0) {
				questions = mcfgf_questions[gfield_label].join('|');
			}
			else {
				questions = mcfgf.questionForLabel($that);
			}

			if(typeof(mcfgf.handleMergedTagsInQuestions) == 'function') {
				questions = mcfgf.handleMergedTagsInQuestions(questions);
			}

			var placeholderX = jqInputDom.attr('type')=='checkbox' ? placeholder_for_ratio : (jqInputDom.attr('type')=='radio' ? placeholder_for_ratio : placeholder);
			
			jqInputDom.attr('cf-questions', questions)
					  .attr('cf-input-placeholder', placeholderX);

			// if(inputid) {
			// 	jqInputDom.attr('cf-questions', questions)
			// 		.attr('cf-input-placeholder', placeholderX);
			// }
			// else {
			// 	// var radioChoiceId = $(this).parent().attr('id').replace('field_','choice_')+'_0';
			// 	// $('#'+radioChoiceId).attr('cf-questions', questions);
			// 	//console.log('handle ratio and checkbox');
			// 	var dom_ratio = $that.closest('.gfield').find('.gfield_radio > li > input:first-child');
			// 	dom_ratio.attr('cf-questions', questions)
			// 		.attr('cf-input-placeholder', placeholder_for_ratio);

			// 	var dom_checkbox = $that.closest('.gfield').find('.gfield_checkbox > li > input:first-child');
			// 	dom_checkbox.attr('cf-questions', questions)
			// 		.attr('cf-input-placeholder', placeholder_for_ratio);
			// }
		});
	}

	function mcfgf_fix_bottom() {
		var newHeight = $('.mcfgfp-pin-composer').height();
		var linkHeight = mcfgf_settings.isFree ? $('.mcfgfp-pin-link-container').height()-10 : 0;
		$('.mcfgfp-pin-conversation-body-parts').css('bottom', (newHeight+linkHeight)+'px');

		if(mcfgf_settings.isFree) {
			$('cf-input-control-elements').css('top', (-linkHeight)+'px')
		}
		
		//console.log('mcfgf_fix_bottom', linkHeight, newHeight, (newHeight+10+linkHeight)+'px');
	}
	function mcfgf_fix_bottom_final() {
		$('cf-input-control-elements cf-list').remove();
		var newHeight = $('.mcfgfp-pin-composer').height();
		var linkHeight = mcfgf_settings.isFree ? $('.mcfgfp-pin-link-container').height()-10 : 0;
		
		$('.mcfgfp-pin-conversation-body-parts').css('bottom', (newHeight +linkHeight)+'px');
	}

	function mcfgf_fix_top() {
		var newHeight = $('.mcfgfp-pin-conversation-body-profile').height();
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
				//console.log('mcfgf_auto_scroll', scrollTop);
			}
			
        }, 1000);
	}

	function mcfgf_fix_first_name_last_name_issue(dto) {
		var firstNameInput = $(dto.input._currentTag.domElement);
		if(firstNameInput.parent().hasClass('name_first')) {
			var value = dto.text.split(' ');
			if(value.length<2) return;
			var fistName = value[0];
			var lastName = value[1];
			firstNameInput.val(fistName);
			firstNameInput.parent().next().find('input').val(lastName);
		}
	}

	mcfgf.rewind_enabled = function(form_id){
		var mcfgf_form_config = window.mcfgf_normal ? window.mcfgf_normal : window.mcfgf_global;
		if(mcfgf_form_config && mcfgf_form_config.enable_rewind == 1) {
    		if(gf_get_field_action( form_id, mcfgf_form_config.rewind_conditional_logic.conditionalLogic ) == 'show') {
    			return true;
    		}
    	}
    	return false;
	}

	//detect if validation process need to be bypassed.
	mcfgf.bypass_validation = function(dto) {
		return mcfgf.is_password_input_first(dto);
	}


	//detect if it is first input of password
	mcfgf.is_password_input_first = function(dto) {
		var passwordInput = $(dto.input._currentTag.domElement);
		// 	return false;
		if(passwordInput.parent().hasClass('ginput_left') && passwordInput.parent().parent().hasClass('ginput_container_password')) {
			//console.log('is_password_input_first', 'bypass');
			return true;
		}
		return false;
	}
	

	mcfgf.inject_validation_callback_for_tags = function(form_id, step) {
		var c = window.ConversationalForm.tags.length;
		// //console.log('_prepareTags001-_inject_validation_callback 2', form.attr('target'), c);
		if(c > 0) {
			for(var i=step; i<c ;i++) {
				window.ConversationalForm.tags[i].validationCallback = function(dto, success, error) {
					//console.log('_prepareTags001-_inject_validation_callback', this, dto, success, error);

					mcfgf_fix_first_name_last_name_issue(dto);

					if(typeof(mcfgf.conversationFormCheckConditionalLogic) == 'function') {
						mcfgf.conversationFormCheckConditionalLogic(this, form_id);
					}

					// setTimeout(function(){
						
						//console.log('continue after conversationFormCheckConditionalLogic');
						_restoreConversationFormCheckboxes(form_id);

						mcfgf.validationCallback = {
							dto: dto,
							success: success,
							error: error
						}

						if(mcfgf.bypass_validation(dto)) {
							//console.log('bypass');
							mcfgf.validationCallback.success();
						}
						else {
							window["gf_submitting_"+form_id]=false;
							//console.log('continue after conversationFormCheckConditionalLogic 2');
							$('#gform_submit_button_'+form_id).show().click();	
							//console.log('continue after conversationFormCheckConditionalLogic 3');
						}

											
					// }, 100);
					
				}
			}
		}
	}

	function mcfgf_success_step(isFinal, form_id, isReplace) {
		//_prepareConversationFormCheckboxes(form_id);
		if(typeof(mcfgf.conversationFormCheckConditionalLogicRebuildTag) == 'function') {
			mcfgf.conversationFormCheckConditionalLogicRebuildTag(form_id, isReplace);
		}

		if(isReplace) {
			if(isFinal && typeof(mcfgf.conversationFormMaybeShowConditionalLogicMessage) == 'function') {
				mcfgf.conversationFormMaybeShowConditionalLogicMessage(form_id);
			}
		
			mcfgf.validationCallback.success();
			mcfgf_auto_scroll();
		}
	}

	function _inject_validation_callback(form_id) {

		//console.log('_inject_validation_callback', form_id);
		var iframe_dummy = _get_iframe_dummy(form_id);
		$(iframe_dummy).appendTo('body');
		var form = $("#gform_"+form_id);
		// var targeFrameId = form.attr('target');
		// $('#'+targeFrameId).attr('id', 'mcfgf_final_'+targeFrameId).attr('name', 'mcfgf_final_'+targeFrameId);
		// form.attr('action', form.attr('action').'');
		form.attr('target', 'mcfgf_'+form.attr('target'));
		form.attr('novalidate', 'novalidate');
		

		mcfgf.inject_validation_callback_for_tags(form_id, 0);

		

		//hook temp submission result to get validation message.
		jQuery('#mcfgf_gform_ajax_frame_'+form_id).load(function(){
			//console.log('mcfgf_gform_ajax_frame_'+form_id);
			var contents = jQuery(this).contents().find('*').html();
			var is_postback = contents.indexOf('GF_AJAX_POSTBACK') >= 0;
			window["gf_submitting_"+form_id]=false;
			if(!is_postback){
				//console.log('mcfgf_gform_ajax_frame_'+form_id, contents);
				if(contents.indexOf('mcfgf') >= 0) {
					mcfgf_success_step(true, form_id, true);
				}
				return;
			}
			
			
			var inputDomId = $(mcfgf.validationCallback.dto.input._currentTag.domElement).attr('id');
			var input = jQuery(this).contents().find('#'+inputDomId);
			if(input.closest('.gfield').hasClass('gfield_error')) {
				var message = input.closest('.gfield').find('.validation_message').html();
				//console.log('mcfgf_gform_ajax_frame_'+form_id+' error', message, input , input.closest('.gfield'));
				mcfgf.validationCallback.error(message);
			}
			else {
				mcfgf_success_step(false, form_id, true);
			}
		});
    	
    	//console.log('window.ConversationalForm', window.ConversationalForm);
	}

	function _loadConversationForm(form_id) {
		var isGlobalButtonMode = $('#mcfgfp-pin-conversation-parts').length > 0;
		//console.log('_loadConversationForm 3');
		// if(!window.ConversationalForm){
			var formEl = document.getElementById("gform_"+form_id);


      if($(formEl).parent().hasClass('mcfgf-mobile-fullscreen')) {
				$('body').addClass('mcfgfp-mobile-no-scroll');
			}
			//console.log('formEl', formEl, form_id, document.getElementById("gform_wrapper_"+form_id));
			// cf-questions="What is your name?|Please tell me your name."
			// $('#input_2_1').attr('cf-questions', "What is your name?|Please tell me your name.");

			// var tags = [];
			// var fields = [].slice.call(formEl.querySelectorAll("input, select, button, textarea"), 0);
			// for (var i = 0; i < fields.length; i++) {
			//     var element = fields[i];
			//     //console.log('ConversationalForm element', element);
			//     tags.push(cf.Tag.createTag(element));
			// }
			// //console.log('ConversationalForm tags', tags);
	        
	        //ask gravity form do a callback after submit.
	        

	        window.ConversationalForm = new cf.ConversationalForm({
	            formEl: formEl,
	            tags: mcfgf.prepareTags(formEl),
	            loadExternalStyleSheet: false,
	            preventAutoFocus: true,
	            dictionaryData: window.mcfg_dictionary,
	            context: isGlobalButtonMode ? document.getElementById("mcfgfp-pin-conversation-parts") : document.getElementById("gform_wrapper_"+form_id).parentNode,
	            submitCallback: function(){
	            	//console.log('submitCallback', form_id, window.mcfgf_normal);
	            	_restoreConversationFormCheckboxes(form_id);
	            	var bypass = false;
	            	var form = $("#gform_"+form_id);
	            	if(mcfgf.rewind_enabled(form_id)) {
            			//console.log('need rewind');
            			form.attr('mcfgf-rewind-action', form.attr('action'));
            			form.attr('mcfgf-rewind-target', form.attr('target'));
            			bypass = true;
	            	}
	            	else {
	            		form.parent().attr('mcfgf-paging-action', form.attr('action'));
            			form.parent().attr('mcfgf-paging-target', form.attr('target'));
	            	}
            		
            		var form_post_url = window.mcfgf_normal && window.mcfgf_normal.form_post_url ? window.mcfgf_normal.form_post_url : window.mcfgf_global.form_post_url
	            	form.attr('action', form_post_url);
	            	form.attr('target', form.attr('target').replace('mcfgf_', ''));
	            	form.find('#mcfgf_mark').remove();
	            	
	            	//tigger submit button
	            	window["gf_submitting_"+form_id]=false;
	            	$('#gform_submit_button_'+form_id).show().click();

	            	
	            	// return false;
	            },
	            flowStepCallback: function(dto, success, error){
	            	//console.log('flowStepCallback', dto, success, error);
// 	            	var mcc = $('#conversational-form > cf-chat');
// 	            	mcc.anim
// ate({
//   						scrollTop: mcc.get(0).scrollHeight}, 1000);
	            	return success(dto);
	            }
	            // userImage: "img/human.png",
	            // 'tags?': tags
	        });

	        

	        setTimeout(function(){

	        	// $('#conversational-form cf-input input').attr('placeholder', window.mcfgf_settings_basics.conversation_input_placeholder);
	        	_inject_validation_callback(form_id);

	        	//console.log("$('#conversational-form')", $('#conversational-form').html()+'');
	        	
	        	if($('.mcfgfp-pin-composer').length > 0) {
	        		$('#conversational-form cf-input.animate-in').detach()
			       				.appendTo('.mcfgfp-pin-composer');
	        	}
	        	mcfgf_auto_scroll();
	        	
// $('.mcfgfp-pin-composer').show();
	        	// var mcc = $('#conversational-form > cf-chat');

	        	// setTimeout(function(){
       			// 	$('.mcfgfp-pin-composer').show();
       			// 	//console.log("$('.mcfgfp-pin-composer .animate-in')22", $('.mcfgfp-pin-composer').html()+'');
       			// }, 3300);

	   //      	mcc.bind('DOMSubtreeModified', function(e) {
				// 	//console.log('DOMSubtreeModified');
				// 	setTimeout(function(){
				// 		var mbody = $('.mcfgfp-pin-conversation-body-parts');
				// 		if(mbody.length > 0) {
				// 			$('.mcfgfp-pin-conversation-body-parts').animate({
	  	// 					scrollTop: $('.mcfgfp-pin-conversation-body-parts').get(0).scrollHeight}, 10);
				// 		}
						
		  //           }, 1300);
		  //           setTimeout(function(){
				// 		if(mcc.find('cf-chat-response').length == 2) {
				// 			if($('.mcfgfp-pin-composer').length > 0) {
				        	
			 //       				setTimeout(function(){
				//        				$('.mcfgfp-pin-composer').show();
				//        				//console.log("$('#conversational-form .animate-in')22", $('#conversational-form .animate-in').html());
				//        			}, 1300);
				//         	}
				// 		}
				// 	}, 13);
				// });
	        }, 13);
	        
        // }
        // $(this).addClass("disabled");
        // var form = $(".conversational-form");
        // if (form.hasClass("conversational-form--show")) {
        // 	$(this).removeClass("active");
        // 	// $(this).text("Turn on conversation");
        // 	$(".conversational-form").removeClass("conversational-form--show");
        // } else {
        // 	$(this).addClass("active");
        // 	// $(this).text("Turn off conversation");
        // 	$(".conversational-form").addClass("conversational-form--show");
        // }
	}

	window.loadMagicConversationForm = function(form_id) {
		_prepareConversationFormQuestions2(form_id);
		_loadConversationForm(form_id);
	}

	function _get_iframe_dummy(form_id) {
		return '<iframe style="display:none" src="about:blank" name="mcfgf_gform_ajax_frame_'+form_id+'" id="mcfgf_gform_ajax_frame_'+form_id+'"></iframe>';
	}

	function remove_iframe_dummy(form_id) {
		$('#mcfgf_gform_ajax_frame_'+form_id).remove();
	}

	function _handle_global_conversation_form() {
		if(!window.mcfgf_global) return;


		//Do Not Show Conversation Button on mobile view (small screen)
		//
		// if($(window).width() < 600) return;

		var form_id  = mcfgf_global.form_id;
	    var ajax_url = mcfgf_global.ajax_url;
	    var form = $("#gform_"+form_id);

	    if(form_id && form_id > 0 && form.length ==0) {
	    	if(window.mcfgf_normal && window.mcfgf_normal.form_id == form_id) {
	    		return;
	    	}
	    	if($('magic-conversation').length > 0) {
	    		return;
	    	}
	    	$.get(ajax_url+'?action=gf_button_get_form&form_id='+form_id, function(response){
	    		
	    		$('<div id="gf_button_form_container_'+form_id+'"></div>').appendTo('body').html(response);//.fadeIn();

	    		if(window['gformInitDatepicker']) {gformInitDatepicker();}
	    		
	    		

				$('.mcfgfp-notifications-dismiss-button').click(function(){
					$(this).closest('.mcfgfp-notifications').find('.mcfgfp-say-hi-card').remove();
					$(this).remove();
				});

	    		// $("#gform_"+form_id).attr('target', 'mcfgf_gform_ajax_frame_'+form_id);

	    		var inited =  3;

	    		var htmlForm = $('#mcfgfp-gf-container').html();

	    		_prepareConversationFormQuestions2(form_id);
	    		// _loadConversationForm(form_id);
	    		window.ConversationalForm == null;

	    		//side form
			    $('#mcfgfp-pin-container .mcfgfp-pin-btn, .mcfgfp-say-hi-body, .mcfgfp-launcher-badge, #mcfgfp-pin-container  .mcfgfp-pin-team-close').on('click', function(){
			    	//console.log('_loadConversationForm 1', inited);
			    	$('.mcfgfp-notifications-dismiss-button').trigger('click');
			    	$('#mcfgfp-pin-container .mcfgfp-pin-btn').toggleClass('mcfgfp-pin-btn-active');
			    	$('#mcfgfp-pin-container .mcfgfp-pin-app').toggleClass('mcfgfp-pin-app-enabled');
			    	// if($(window).width() < 768) {
			    		$('body').toggleClass('mcfgfp-mobile-no-scroll');
			    	// }
			    	$('.mcfgfp-launcher-badge').toggle();
			    	if(inited==3) {
			    		if(window.ConversationalForm == null) {
			    			//console.log('_loadConversationForm 2', inited);

			    			_prepareConversationFormQuestions2(form_id);
			    			_loadConversationForm(form_id);
			    			mcfgf_fix_top();
			    			
			    		}
						// window.ConversationalForm.remapTagsAndStartFrom(0);
			    		
		    			// _loadConversationForm(form_id);
			    		// setTimeout(function(){
			    			
		    			// }, 1300);
		    			inited = 2;
			    	}
			    	else if(inited==2){
			    		// //console.log('反初始化 1', window.ConversationalForm.userInput.controlElements);
			    		// var ol = window.ConversationalForm.userInput.controlElements.elements.length;
			    		// if(ol > 0) {
			    		// 	for(var ii=0;ii < ol;ii++) {
			    		// 		//console.log('反初始化 2', ii, ol, window.ConversationalForm.userInput.controlElements.elements[ii]);

			    		// 		if(typeof(window.ConversationalForm.userInput.controlElements.elements[ii].elements)=='undefined') {
			    		// 			window.ConversationalForm.userInput.controlElements.elements[ii].dealloc();
			    		// 			continue;
			    		// 		}
			    		// 		var jjl = window.ConversationalForm.userInput.controlElements.elements[ii].elements.length;
			    		// 		for(var jj =0; jj < jjl; jj++) {
				    	// 			//console.log('反初始化 3', jj, jjl);
				    	// 			window.ConversationalForm.userInput.controlElements.elements[ii].elements[jj].dealloc();
				    	// 		}
			    				
			    		// 	}
			    		// }
			    		// window.ConversationalForm.userInput.controlElements.dealloc();
			    		// window.ConversationalForm.userInput.dealloc();
			    		if(window.mcfgf_global_submitted) {
			    			window.ConversationalForm.remapTagsAndStartFrom(0);
			    			window.ConversationalForm.remove();
							// $('.mcfgfp-pin-composer').html('');
							$('#mcfgfp-gf-container').html(htmlForm);
							// document.removeEventListener(UserInputEvents.SUBMIT, this.userInputSubmitCallback, false);
				    		window.ConversationalForm = null;
							window.mcfgf_global_submitted = false;
							remove_iframe_dummy(form_id)
			    		}
			    		
			    		//console.log('_loadConversationForm 4', inited);
			    		inited = 3;
			    	}
			    	else {
			    		//console.log('_loadConversationForm 5', inited);
			    		inited = 2;
			    		mcfgf_fix_top();
			    		mcfgf_fix_bottom();
			    	}
			    	//console.log('_loadConversationForm 6', inited);
			    })
		    	
			});
	    }
	}

	function _handle_normal_conversation_form() {
		if(!window.mcfgf_normal) return;
		var form_id  = mcfgf_normal.form_id;
	    var ajax_url = mcfgf_normal.ajax_url;
	    if($("#gform_"+form_id).length > 0) {
	    	_prepareConversationFormQuestions2(form_id);
	    	_loadConversationForm(form_id);
	    }
	}
	$(document).ready(function(){
		//console.log('mcfgf configs', {
		// 	mcfgf_settings_basics: window.mcfgf_settings_basics, 
		// 	mcfgf_global: window.mcfgf_global, 
		// 	mcfgf: window.mcfgf, 
		// 	mcfgf_settings: window.mcfgf_settings, 
		// 	mcfgf_normal:window.mcfgf_normal
		// });

		if(!window.mcfgf_settings) {
			return;
		}

		if(!mcfgf_settings.isFree) {
			if(typeof(mcfgf_settings.is_valid_license_key)=='undefined') {
				//console.log('not mcfgf_settings found ');
				return;
			}
			//console.log('mcfgf_settings', mcfgf_settings);
			if(!(parseInt(mcfgf_settings.is_valid_license_key) == 1)) {
				return;
			}
		}

		

		if(window.mcfgf) {
			//console.log('window.mcfgf', JSON.stringify(mcfgf));

		}
		
		if(window.mcfgf_settings) {
			//console.log('mcfgf_settings', JSON.stringify(mcfgf_settings));
		}

		if(window.mcfgf_settings_basics) {
			//console.log('mcfgf_settings_basics', JSON.stringify(mcfgf_settings_basics));
		}

		var placeholder = window.mcfgf_settings_basics.conversation_input_placeholder;
		var placeholder_for_ratio = window.mcfgf_settings_basics.conversation_input_placeholder_for_radio;

		window.mcfg_dictionary = {
            "user-image": "",
            "entry-not-found": "Dictionary item not found.",
            "input-placeholder": placeholder,
            "group-placeholder": placeholder_for_ratio,
            "input-placeholder-error": "Your input is not correct ...",
            "input-placeholder-required": "Input is required ...",
            "input-placeholder-file-error": "File upload failed ...",
            "input-placeholder-file-size-error": "File size too big ...",
            "input-no-filter": "No results found for <strong>{input-value}</strong>",
            "user-reponse-and": " and ",
            "user-reponse-missing": "Missing input ...",
            "user-reponse-missing-group": "Nothing selected ...",
            "general": "General type1|General type2",
            "icon-type-file": "<svg class='cf-icon-file' viewBox='0 0 10 14' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'><g transform='translate(-756.000000, -549.000000)' fill='#0D83FF'><g transform='translate(736.000000, 127.000000)'><g transform='translate(0.000000, 406.000000)'><polygon points='20 16 26.0030799 16 30 19.99994 30 30 20 30'></polygon></g></g></g></g></svg>",
        };
		

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

		if(mcfgf_settings_basics.conversation_button_background_color) {
			mcfgf_css_ex += '.mcfgfp-pin-btn { background-color: '+mcfgf_settings_basics.conversation_button_background_color+' !important;}'+"\r\n";
		}

		if(mcfgf_settings_basics.conversation_button_image_normal) {
			mcfgf_css_ex += '#mcfgfp-pin-container .mcfgfp-pin-btn .mcfgfp-pin-btn-open { background-image: url('+mcfgf_settings_basics.conversation_button_image_normal+') !important;}'+"\r\n";
		}

		if(mcfgf_settings_basics.conversation_button_image_active) {
			mcfgf_css_ex += '#mcfgfp-pin-container .mcfgfp-pin-btn .mcfgfp-pin-btn-close { background-image: url('+mcfgf_settings_basics.conversation_button_image_active+') !important;}'+"\r\n";
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

		//console.log('mcfgf_css_ex', mcfgf_css_ex, mcfgf_settings_basics);
		

		// if(!mcfgf_global) {
		// 	return;
		// }
		
		
		if(window.mcfgf) {
			var $container = $('<style id="mcfgf-css" type="text/css"></style>').appendTo("body");
			//console.log('mcfgf.css_code', mcfgf.css_code);
			if(mcfgf.css_code.indexOf('cf-chat-response text') < 0) {
				mcfgf.css_code = mcfgf.css_code.replace('cf-chat-response text', 'cf-chat-response text').replace('cf-chat-response.user text', 'cf-chat-response.user text');
			}
			
	    	$container.text(mcfgf.css_code+mcfgf_css_ex+mcfgf_settings_basics.custom_css);

	    	//console.log('bind gform_post_render');

	    	//After form submission
		    jQuery(document).bind('gform_post_render', function(e, form_id){
		    	//console.log('gform_post_render001', form_id);
		    	var $form = $('#gform_'+form_id);


	        	///gform_ajax_frame_2
				
				var message = $("#gform_ajax_frame_" + form_id).contents().find("#gform_confirmation_message_"+form_id).html();
				//console.log('gform_post_render', form_id, message);
				if(message) {

        	if(mcfgf.rewind_enabled(form_id)) {
            			//console.log("Need rewind");
            			//clear form
            			$form.find('input:text, input:password, input:file, textarea').val('');
					    $form.find('select option:selected').removeAttr('selected');
					    $form.find('input:checkbox, input:radio').removeAttr('checked');
            			// $('#gform_'+form_id)[0].reset();
            			// window["gf_submitting_" + form_id] = false;
            			// $('#gform_ajax_spinner_' + form_id).remove();

						if(window['gformInitDatepicker']) {gformInitDatepicker();}
						if(window['gformInitPriceFields']) {gformInitPriceFields();}
						var current_page = jQuery('#gform_source_page_number_'+ form_id).val();
						// gformInitSpinner( form_id, '{$spinner_url}' );
						jQuery(document).trigger('gform_page_loaded', [form_id, current_page]);
						window['gf_submitting_'+form_id] = false;
						$('#gform_ajax_spinner_' + form_id).remove();
            			
						//hack submission again.
						$form.append('<input name="mcfgf_mark" id="mcfgf_mark" type="hidden" value="mcfgf">');

						$form.attr('action', $form.attr('mcfgf-rewind-action'));
            			$form.attr('target', $form.attr('mcfgf-rewind-target'));

            			window.ConversationalForm.remapTagsAndStartFrom(0, false, true);
            			return;
	            	}

	            	if($form.attr('mcfgf_confirmation_message_mode')=="1") {
						return;
					}

	            	
					//console.log('addRobotChatResponse2', message);
					window.ConversationalForm.addRobotChatResponse(message);
					// $('<cf-chat-response class="robot show"><thumb></thumb><text value-added="">'+message+'</text></cf-chat-response>').appendTo('#conversational-form > cf-chat');
					$('cf-input input').val(' ');
					window.mcfgf_global_submitted = true;
					mcfgf_auto_scroll(true);
				}
				else if(window.ConversationalForm && window.ConversationalForm.tags){
					$form.attr('action', $form.parent().attr('mcfgf-paging-action'));
          $form.attr('target', $form.parent().attr('mcfgf-paging-target'));
          _prepareConversationFormQuestions2(form_id);
					mcfgf_success_step(false, form_id, false);
				}
				
				
			});


			$(document).on('click', '.cf-checkbox-button', function(){
				//console.log('cf-checkbox-button', this);
				// $('.conversational-form-inner cf-input textarea').hide();
				$('.conversational-form-inner cf-input-button, .mcfgfp-pin-composer cf-input-button').addClass("mcfgf_done");
				
			});

			$(document).on('click', '.conversational-form-inner cf-input-button, .mcfgfp-pin-composer cf-input-button', function(){
				//console.log('cf-checkbox-button', this);
				// $('.conversational-form-inner cf-input textarea').show();
				$('.conversational-form-inner cf-input-button, .mcfgfp-pin-composer cf-input-button').removeClass("mcfgf_done");
				
			});

		    
		    setTimeout(function(){
				if(!mcfgf_settings.isFree) {
			    	if(window.mcfgf_normal){
			    		_handle_normal_conversation_form();
			    	}
					else {

						_handle_global_conversation_form();
					}
				}
				else {
					var form_id  = mcfgf_global.form_id;
					if($('#gf_'+form_id).length > 0) {
						_handle_normal_conversation_form();
					}
					else {
						_handle_global_conversation_form();
					}
					
				}
		    },100);
		    
		}
		else {
			var $container = $('<style id="mcfgf-css" type="text/css"></style>').appendTo("body");
	    	$container.text(mcfgf_css_ex+mcfgf_settings_basics.custom_css);

	    	function _inject_validation_callback_demo(form_id) {
	    		var c = window.ConversationalForm.tags.length;
				if(c > 0) {
					for(var i=0; i<c ;i++) {
						window.ConversationalForm.tags[i].validationCallback = function(dto, success, error) {
							//console.log('_inject_validation_callback_demo', window.ConversationalForm, dto, success, error);
							if($(dto.input._currentTag.domElement).attr('id')=='your-opinion') {
								//console.log("$('cf-chat-response.user:last-child')", $('cf-chat-response.user:last-child'));
								$('cf-chat-response.user:last-child').find('text').removeAttr('thinking').attr('value-added', null).html(dto.text);

								window.ConversationalForm.addRobotChatResponse('If you enjoy using Magic Conversation For Gravity Forms, please spread the word and tell everyone about our plugin!');


								$('cf-input input').val(' ');
							}
							else {
								success();
							}
							// if(!dto.) {

							// 	//success();
							// }
						}
					}
				}
	    	}

	    	_inject_validation_callback_demo('conversational');
		}
	});

})(jQuery);
/*

<cf-input placeholder="Type your answer here ..." class="animate-in" tag-type="select">
	<cf-input-control-elements>
		<cf-list-button direction="prev">
		</cf-list-button>
		<cf-list-button direction="next">
		</cf-list-button>
		<cf-list style="width: 100%;">
			<cf-info></cf-info>
		<cf-button class="cf-button " selected="selected">Blue</cf-button><cf-button class="cf-button ">Red</cf-button><cf-button class="cf-button ">Green</cf-button><cf-button class="cf-button ">dsfsd</cf-button><cf-button class="cf-button ">ddd</cf-button><cf-button class="cf-button ">ddddd</cf-button><cf-button class="cf-button ">ddd</cf-button><cf-button class="cf-button ">sss</cf-button><cf-button class="cf-button ">sss</cf-button><cf-button class="cf-button ">ssssss</cf-button></cf-list>
	</cf-input-control-elements>

	<cf-input-button class="cf-input-button">
		<div class="cf-icon-progress"></div>
		<div class="cf-icon-attachment"></div>
	</cf-input-button>
	
	<input type="input" tabindex="1" data-value="" placeholder="haha you type here please">

</cf-input>



<cf-input placeholder="Type your answer here ..." class="animate-in" tag-type="select">
	<cf-input-control-elements class="one-row animate-in hide-nav-buttons">
		<cf-list-button direction="prev">
		</cf-list-button>
		<cf-list-button direction="next">
		</cf-list-button>
		<cf-list style="width: 100%; transform: translateX(0px);">
			<cf-info></cf-info>
		<cf-button class="cf-button animate-in" selected="selected" tabindex="2">Blue</cf-button><cf-button class="cf-button animate-in" tabindex="3">Red</cf-button><cf-button class="cf-button animate-in" tabindex="4">Green</cf-button><cf-button class="cf-button animate-in" tabindex="5">dsfsd</cf-button><cf-button class="cf-button animate-in" tabindex="6">ddd</cf-button><cf-button class="cf-button animate-in" tabindex="7">ddddd</cf-button><cf-button class="cf-button animate-in" tabindex="8">ddd</cf-button><cf-button class="cf-button animate-in" tabindex="9">sss</cf-button><cf-button class="cf-button animate-in" tabindex="10">sss</cf-button><cf-button class="cf-button animate-in" tabindex="11">ssssss</cf-button></cf-list>
	</cf-input-control-elements>

	<cf-input-button class="cf-input-button">
		<div class="cf-icon-progress"></div>
		<div class="cf-icon-attachment"></div>
	</cf-input-button>
	
	<input type="input" tabindex="1" data-value="" placeholder="haha you type here please">

</cf-input>*/
