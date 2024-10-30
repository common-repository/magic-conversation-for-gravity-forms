(function($) {

	function handleShortCode() {
		var sui = window.GformShortcodeUI;
		if(!(sui && sui.views)) return;
		/**
     * Single edit shortcode content view.
     */
    sui.views.editShortcodeMagicConversation = wp.Backbone.View.extend({

      el: '#mcfgf-shortcode-ui-container',

      template: wp.template('mcfgf-shortcode-default-edit-form'),

      hasAdvancedValue: false,

      events: {
          'click #mcfgf-update-shortcode': 'insertShortcode',
          'click #mcfgf-insert-shortcode': 'insertShortcode',
          'click #mcfgf-cancel-shortcode': 'cancelShortcode'
      },

      initialize: function () {

          _.bindAll(this, 'beforeRender', 'render', 'afterRender');

          var t = this;
          this.render = _.wrap(this.render, function (render) {
              t.beforeRender();
              render();
              t.afterRender();
              return t;
          });


          this.model.get('attrs').each(function (attr) {
            switch (attr.get('section')) {
              case 'required':
                t.views.add(
                    '.mcfgf-edit-shortcode-form-required-attrs',
                    new sui.views.editAttributeField({model: attr, parent: t})
                );
                break;
              case 'standard':
                t.views.add(
                    '.mcfgf-edit-shortcode-form-standard-attrs',
                    new sui.views.editAttributeField({model: attr, parent: t})
                );
                break;
              default:
                t.views.add(
                    '.mcfgf-edit-shortcode-form-advanced-attrs',
                    new sui.views.editAttributeField({model: attr, parent: t})
                );
                if (!t.hasAdvancedVal) {
                    t.hasAdvancedVal = attr.get('value') !== '';
                }
            }
          });

          this.listenTo(this.model, 'change', this.render);
      },

      beforeRender: function () {
          //
      },

      afterRender: function () {
          // mcfgf_initialize_tooltips();

          $('#mcfgf-insert-shortcode').toggle(this.options.viewMode == 'insert');
          $('#mcfgf-update-shortcode').toggle(this.options.viewMode != 'insert');
          $('#mcfgf-edit-shortcode-form-advanced-attrs').toggle(this.hasAdvancedVal);
      },

      insertShortcode: function (e) {

          var isValid = this.model.isValid({validate: true});

          if (isValid) {
              send_to_editor(this.model.formatShortcode());
              tb_remove();

              this.dispose();

          } else {
              _.each(this.model.validationError, function (error) {
                  _.each(error, function (message, attr) {
                      alert(message);
                  });
              });
          }
      },
      cancelShortcode: function (e) {
          tb_remove();
          this.dispose();
      },
      dispose: function () {
          this.remove();
          $('#mcfgf-shortcode-ui-wrap').append('<div id="mcfgf-shortcode-ui-container"></div>');
      }
    });
		
		$(document).on('click', '.mcfgf_media_link', function () {
    	
    	// console.log('mcfgfShortcodeUIData', mcfgfShortcodeUIData, sui);
      sui.shortcodes = new sui.collections.Shortcodes(mcfgfShortcodeUIData.shortcodes);
      var shortcode = sui.shortcodes.findWhere({shortcode_tag: 'magic-conversation', action_tag: ''});

      // console.log('shortcode', shortcode, sui.shortcodes);
      GformShortcodeUI = new sui.views.editShortcodeMagicConversation({model: shortcode, viewMode: 'insert'});
      GformShortcodeUI.render();
      tb_show("Insert Magic Conversation for Gravity Form", "#TB_inline?inlineId=select_magic_conversation_gravity_form&width=753&height=686", "");
    });
	}
	
	$(document).ready(function(){
		
		handleShortCode();

		var qvccl = $('#quick_view_current_conversation');

		var current_conversation_id = qvccl.parent().prev().on('change', function(){
			var value = $(this).val();
			console.log('conversation_gravity_form_id changed', value);
			if(value) {
				qvccl.attr('href', qvccl.attr('url_tpl').replace('{form_id}', value));
				qvccl.show();
			} else {
				qvccl.hide();
			}
		}).trigger('change');



		var file_frame;
		// jQuery(document).delegate('.mcfgf_upload_image_button', 'click', function( event ){
		window.mcfgf_open_upload_image_modal = function(that) {
			console.log('mcfgf_upload_image_button');
			$that = $(that);
			// event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				// Set the post ID to what we want
				// file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				// Open frame
				file_frame.open();
				return;
			} else {
				// Set the wp.media post id so the uploader grabs the ID we want when initialised
				// wp.media.model.settings.post.id = set_to_post_id;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: 'Select a image to upload',
				button: {
					text: 'Use this image',
				},
				multiple: false	// Set to true to allow multiple files to be selected
			});
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				console.log('attachment', attachment);
				// Do something with attachment.id and/or attachment.url here
				
				var mcfgfImagePreview = $that.find( '.mcfgf-image-preview' );
				if(mcfgfImagePreview.length > 0) {
					mcfgfImagePreview.attr( 'src', attachment.url );
				} else {
					$that.html("<img class='mcfgf-image-preview' src='"+attachment.url+"' width='20' height='20' style='max-height: 20px; width: 20px;' />");
				}

				$that.parent().find( '.field-choice-mcfgf-image-url' ).val( attachment.url ).trigger('input'); //attachment.id
				// Restore the main post ID
				// wp.media.model.settings.post.id = wp_media_post_id;
			});
				// Finally, open the modal
				file_frame.open();
		};
		

		$('#mcfgf-conversation-generator-form').submit(function(event){
			var tooltip = window.Tooltip;
			$('#mcfgf-conversation-generator-css-code').val(tooltip.css());
			$('#mcfgf-conversation-generator-css-options').val(JSON.stringify(tooltip.cssOptions()));
			//console.log(document.getElementById('mcfgf-conversation-generator-iframe').contentWindow.Tooltip.options());
			$('#mcfgf-conversation-generator-js-code').val(JSON.stringify(tooltip.options()));		
			//event.preventDefault();
		});

		$('#reset-mcfgf-conversation-generator-form').click(function(){
			var tooltip = window.Tooltip;
			tooltip.reset();
		});


		// $('#toplevel_page_magic_conversation_for_gravity_forms .wp-submenu li:last-child a').attr('target', '_blank');
		var imageChange =  function(){
			var that = $(this);
			var newVal = that.val();
			var imgPreview = that.closest('td').find('#image-preview');
			if(newVal) {
				imgPreview.attr('src', newVal);
			}
			else {
				imgPreview.attr('src', imgPreview.attr('default-src'));
			}

			$('.image-preview-wrapper').css('background-color', $('.conversation_button_background_color .wp-color-picker-field').val());
			if(newVal) {
				imgPreview.prev().show();
			}
			else {
				imgPreview.prev().hide();
			}
		}

		$(".image-preview-close .notice-dismiss").click(function(){
			$(this).closest('td').find('.wpsa-url').val('').trigger('change');
		});

		// //Conversation 
		$('.wpsa-url').on('change', imageChange);
		$('.wpsa-url').each(imageChange);
		
		
		// $(document).on("click", "#toggle-conversation", function(){
	 //        if(!window.ConversationalForm){
	 //          window.ConversationalForm = new cf.ConversationalForm({
	 //            formEl: document.getElementById("conversational"),
	 //            context: document.getElementById("form-outer"),
	 //            userImage: "img/human.png"
	 //          });
	 //        }
	 //        $(this).addClass("disabled");
	 //        var form = $(".conversational-form");
	 //        if (form.hasClass("conversational-form--show")) {
	 //          $(this).removeClass("active");
	 //          $(this).text("Turn on conversation");
	 //          $(".conversational-form").removeClass("conversational-form--show");
	 //        } else {
	 //          $(this).addClass("active");
	 //          $(this).text("Turn off conversation");
	 //          $(".conversational-form").addClass("conversational-form--show");
	 //        }
	 //        $(this).removeClass("disabled");

	 //        if(window.mcfgf_settings_basics && mcfgf_settings_basics.conversation_input_placeholder) {
	 //        	setTimeout(function(){
		// 			console.log('window.mcfgf_settings_basics', window.mcfgf_settings_basics);
		//         	$('#conversational-form cf-input input').attr('placeholder', window.mcfgf_settings_basics.conversation_input_placeholder);
	 //        	}, 13);
	        	
	 //        }
	        
	 //    });
	 //    $('#toggle-conversation').trigger('click');


	    /* conversation generator */

	    function updateAvatarPreviewAndInputValue(preview, img) {
	    	var name = preview.attr('name');
	    	$('#mcfgf-conversation-generator-'+name.replace('_', '-')).val(img);
	    	$('#dropdown-selector-'+name).removeAttr('checked');

	    	preview.attr('src', img);

	    	if(name=='avatar_user') {
	    		Tooltip.style('userAvatar', 'url('+img+') !important');
	    	}
	    	else {
	    		Tooltip.style('robotAvatar', 'url('+img+') !important');
	    	}
	    	
	    }

	    $('.dropdown-avatar .avatar-items').click(function(){
	    	var preview = $(this).closest('.avatar-picker').find('.avatar-preview').find('img');
	    	
	    	var img = $(this).find('img').attr('src');

	    	console.log('input id:', '#mcfgf-conversation-generator-'+name);
	    	updateAvatarPreviewAndInputValue(preview, img);
	    	return false;
	    });

	    $('.dropdown-items > .random').click(function(){
	    	var ri = Math.floor(Math.random() * 20) + 1;
	    	var preview = $(this).closest('.avatar-picker').find('.avatar-preview').find('img');
	    	var img = $(this).parent().next().find('.dropdown-avatar .avatar-items:nth-child('+ri+')').find('img').attr('src');
			updateAvatarPreviewAndInputValue(preview, img);
	    	return false;
	    });

	    $('.dropdown-items > .upload').click(function(e) {
	        var preview = $(this).closest('.avatar-picker').find('.avatar-preview').find('img');

		    // return false;
	        var image = wp.media({ 
	            title: 'Upload Image',
	            // mutiple: true if you want to upload multiple files at once
	            multiple: false
	        }).open()
	        .on('select', function(e){
	            // This will return the selected image from the Media Uploader, the result is an object
	            var uploaded_image = image.state().get('selection').first();
	            // We convert uploaded_image to a JSON object to make accessing it easier
	            // Output to the console uploaded_image
	            console.log(uploaded_image);
	            var img = uploaded_image.toJSON().url;
	            // Let's assign the url value to the input field
	            updateAvatarPreviewAndInputValue(preview, img);
	        });
	        return false;
	    });

	    //TODO: flannian 2017-12-5
			jQuery("#field_settings > ul").append('<li style="width:110px; padding:0px; "><a href="#mcfgf_tab_yakker">Conversation</a></li>');

	    $('.term-name-wrap p').html('The Field Label of your Gravity Forms\' field.');

	    $('<p class="mcfgf-term-hint">The Field Label of your Gravity Forms\' field, that help mapping questions to your form field.</p>').appendTo('#mc-question-category-adder');

	    if($('#select-product').length > 0 && typeof($('#select-product').selectize) !== 'undefined') {
	    	$('#select-product').selectize({
			    valueField: 'id',
			    labelField: 'title',
			    searchField: ['title', 'sku', 'price', 'excerpt', 'description', 'short_description'],
			    create: false,
	    		maxItems: null,
			    options: [
			        // {email: 'brian@thirdroute.com', name: 'Brian Reavis'},
			        // {email: 'nikola@tesla.com', name: 'Nikola Tesla'},
			        // {email: 'someone@gmail.com'}
			    ],
			    render: {
			        item: function(item, escape) {
			        	var sku = item.sku ? 'sku:' + item.sku+'' : 'id:' + item.id+'';
		            return '<div>' +
		                (item.title ? '<span class="title">' + escape(item.title) + '</span>' : '') +'<span class="email">' + escape(sku) + '</span>' +
		            '</div>';
			        },
			        option: function(item, escape) {
		            var label = item.title;
		            var caption = item.short_description || item.description || null;
		            var sku = item.sku ? '(sku:' + item.sku+')' : '(id:' + item.id+')';
		            return '<div>' +
		                '<span class="label">' + escape(label) + sku + '</span>' +
		                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
		            '</div>';
			        }
			    },
			    load: function(query, callback) {
			        if (!query.length) return callback();
			        $.ajax({
			            url: mcfgf_admin.ajaxUrl,
			            type: 'GET',
			            dataType: 'json',
			            data: {
			                q: query,
			                action: 'yakker_get_woo_products',
			            },
			            error: function() {
			              callback();
			            },
			            success: function(res) {
			            	console.log('#select-product', res);
			              callback(res.products);
			            }
			        });
			    }
				});
	    }
	});

})(jQuery);