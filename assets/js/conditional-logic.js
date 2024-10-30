(function($) {

	mcfgf.conversationFormCheckConditionalLogic = function(that, form_id){
		// $(that.domElement).find('select, input[type="radio"], input[type="checkbox"]:not(".copy_values_activated")').trigger('click');
		//console.log('conversationFormCheckConditionalLogic');	
		var domElement = that.domElement ? that.domElement : that._activeElements[0].domElement;

		var checkboxes = jQuery(domElement).closest('.gfield').find('input[type="radio"], input[type="checkbox"]:not(".copy_values_activated")');

		checkboxes.each(function() {
			//console.log('_prepareTags001-click', this);
			var a = !!jQuery(this).is(":checked");
			if(a) {
				if("checkbox" == jQuery(this).attr("type")){
					jQuery(this).prop("checked", false).trigger('click'); 
				}
				else {
					jQuery(this).trigger('click');
				}
			}
	    })

	    var selects = jQuery(domElement).closest('.gfield').find('select, input[type="text"]:not([id*="_shim"]), input[type="number"], textarea');
	    // var selects = jQuery(domElement).parent().find('select');
	    //console.log('_prepareTags001-selects', selects);
	    selects.each(function() {
			//console.log('_prepareTags001-click', this);
			var element = jQuery(this);
			element.trigger('change');
			if (element.is('select') && element.next().hasClass('chosen-container')) {
				element.trigger('chosen:updated');
			}
	    })
	}

	mcfgf.handleMergedTagsInHTMLMessage = function(innerResponse){
		innerResponse = mcfgf.handleMergedTagsInQuestions(innerResponse);
		var reponses = window.ConversationalFormChatResponse.list.getResponses();
        for (var i = 0; i < reponses.length; i++) {
            var response = reponses[i];
            if (response !== this) {
                if (response.tag) {
                    // check for id, standard
                    if (response.tag.id) {
                        innerResponse = innerResponse.split("{" + response.tag.id + "}").join(response.tag.value);
                    }
                    //fallback check for name
                    if (response.tag.name) {
                        innerResponse = innerResponse.split("{" + response.tag.name + "}").join(response.tag.value);
                    }
                }
            }
        }

        if(mcfgf && mcfgf.fixeValueForMergedTagsInQuestion) {
            innerResponse = mcfgf.fixeValueForMergedTagsInQuestion(innerResponse);
        }

        return innerResponse;
	}

	mcfgf.conversationFormMaybeShowConditionalLogicMessage = function(form_id) {
		if($('#gform_'+form_id).attr('mcfgf_confirmation_message_mode')!="1") {
			return;
		}
		var jqHtmls = $('#gform_'+form_id+" .gfield_html");
		//console.log("conversationFormMaybeShowConditionalLogicMessage detect", form_id, jqHtmls.length);
		jqHtmls.each(function(){
			//console.log("conversationFormMaybeShowConditionalLogicMessage detected");
			var $that = $(this);
			if($that.hasClass('mcfgf_message_showed')) {
				return;
			}
			var htmlQuestions = $that.find('.mcfgf_questions');
			var htmlDefaultDom = $that.clone()
			htmlDefaultDom.find('.mcfgf_questions').remove();
			var htmlDefault = htmlDefaultDom.text();
			if(htmlQuestions.length == 0 && htmlDefault.length ==0) {
				return;
			}

			//console.log('htmlDefault.length', htmlDefault, htmlDefault.length);

			var html = htmlDefault.length > 0 ? $that.html() : htmlQuestions.html();

			if($that.css('display')!='none'){
				//console.log("conversationFormMaybeShowConditionalLogicMessage show", html);
				$that.addClass('mcfgf_message_showed');
				window.ConversationalForm.addRobotChatResponse(mcfgf.handleMergedTagsInHTMLMessage(html));
			}
			else {
				//console.log("conversationFormMaybeShowConditionalLogicMessage pending", html);
			}
		});
	}

	mcfgf.conversationFormCheckConditionalLogicRebuildTag = function(form_id, isReplace){
		mcfgf.prepareConversationFormCheckboxes(form_id, true);

		var formEl = document.getElementById("gform_"+form_id);
		var tagsNew = mcfgf.prepareTags(formEl);

		// tagsNew = window.ConversationalForm.setupTagGroups(tagsNew);

		var step = 0;
		
		// //console.log('_prepareTags001-tagsNew2', tagsNew.length, window.ConversationalForm.tags.length);

		// window.ConversationalForm.tags = tagsNew;//.addTags(tagsData, true);
		
		if(isReplace) {
			// window.ConversationalForm.tags = tagsNew;
			window.ConversationalForm.tags = window.ConversationalForm.setupTagGroups(tagsNew);
			window.ConversationalForm.flowManager.setTags(window.ConversationalForm.tags);

		}
		else  {
			step = window.ConversationalForm.tags.length;

			window.ConversationalForm.tags = window.ConversationalForm.setupTagGroups(tagsNew);
			window.ConversationalForm.flowManager.setTags(window.ConversationalForm.tags);
			// tagsNew = tagsNew.slice(step);
			// var tagNewGrouped = window.ConversationalForm.setupTagGroups(tagsNew);
			// window.ConversationalForm.flowManager.stop();
			// // window.ConversationalForm.tags = tagsNew;
			// // window.ConversationalForm.tags = window.ConversationalForm.tags.concat(tagNewGrouped);
			// window.ConversationalForm.tags = window.ConversationalForm.flowManager.addTags(tagNewGrouped, window.ConversationalForm.flowManager.getStep() + 1 );
			// window.ConversationalForm.addTags(tagNewGrouped);
			// window.ConversationalForm.flowManager.stopped = false;
			// window.ConversationalForm.flowManager.nextStep();
			// window.ConversationalForm.flowManager.startFrom(step, true);
			// window.ConversationalForm.remapTagsAndStartFrom(step, false, true);
			window.ConversationalForm.flowManager.start();
			//console.log('_prepareTags001-tagsNew20', window.ConversationalForm.tags, window.ConversationalForm.flowManager);
		}
		

		

		
		// if(!isReplace) {
		// 	window.ConversationalForm.flowManager.startFrom(step, true);
		// }

		// //console.log('_prepareTags001-tagsNew3', tagsNew, window.ConversationalForm.tags);

		mcfgf.inject_validation_callback_for_tags(form_id, 0);
	}

	mcfgf.fixeValueForMergedTagsInQuestion = function(questions){
		var regex = /\{([^\}]+?)\}/g;
		//console.log('fixeValueForMergedTagsInQuestion Before', questions, mcfgf.mergedTagsMap);
		questions = questions.replace(regex, function(match, contents, offset, input_string)
		    {
		    	if($('#'+contents).length > 0) {
		    		return $('#'+contents).val();
		    	} 
		    	else {
		    		return match;
		    	}
		    }
		);
		//console.log('fixeValueForMergedTagsInQuestion After', questions);
		return questions;
	}

	mcfgf.handleMergedTagsInQuestions = function(questions){
		var regex = /\{([^\}]+?)\}/g;
		//console.log('handleMergedTagsInQuestions Before', questions, mcfgf.mergedTagsMap);
		questions = questions.replace(regex, function(match, contents, offset, input_string)
		    {
		    	//console.log('handleMergedTagsInQuestions match', mcfgf.mergedTagsMap[contents.toLowerCase()],match, contents, offset, input_string);
		        return mcfgf.mergedTagsMap[contents.toLowerCase()] ? '{'+mcfgf.mergedTagsMap[contents.toLowerCase()]+'}' : match;
		    }
		);
		//console.log('handleMergedTagsInQuestions After', questions);
		return questions;
	}

	mcfgf.fixFirstNameLastMameIssueForMergedTags = function(inputid) {
		var firstNameInput = $('#'+inputid);
		if(firstNameInput.parent().hasClass('name_first')) {
			first_name_label = firstNameInput.parent().find('label');
			first_name_label_tag =  mcfgf.tagForLabel(first_name_label);
			mcfgf.mergedTagsMap[first_name_label_tag] = first_name_label.attr('for');


			last_name_label = firstNameInput.parent().next().find('label');
			last_name_label_tag =  mcfgf.tagForLabel(last_name_label);
			mcfgf.mergedTagsMap[last_name_label_tag] = last_name_label.attr('for');
		}
	}

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
