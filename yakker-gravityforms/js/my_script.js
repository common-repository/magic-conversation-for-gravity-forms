// placeholder for javascript
// 
// 

;(function($){

	
	
	//Conversatoin Button loader
	$(document).ready(function(){
		$('.color').colorPicker({
			opacity: true,

	        convertCallback: function(colors, type) {
	            rgb = colors.RND.rgb;
	            placeholder_color = 'rgba(' + rgb['r'] + ', ' + rgb['g'] + ', ' + rgb['b'] + ', ' + (colors.alpha*0.47).toFixed(2) + ')';
	        },
	        renderCallback: function($elm, toggled) {
	            if (typeof placeholder_color !== undefined && placeholder_color != '' && placeholder_color != null) {
	                $elm.attr('data-ph-color', placeholder_color);
	            }
	            console.log('colorrender', $elm, this);
	            if(this.color.colors.alpha == 1.0){
					$elm.val('#'+this.color.colors.HEX);
	            }
	            
	            //$elm._colorMode
	            window.lastColorPicker = $elm;
	        }
		});

		$('.yakker-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text'),
                },
                multiple: false
            });

            file_frame.on('select', function () {
                attachment = file_frame.state().get('selection').first().toJSON();
                self.prev('.yakker-url').val(attachment.url).change();
            });

            // Finally, open the modal
            file_frame.open();
        });

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
			$(this).closest('td').find('.yakker-url').val('').trigger('change');
		});

		$('.yakker-url').on('change', imageChange);
		$('.yakker-url').each(imageChange);

	});
	
	
})(jQuery);
