// placeholder for javascript
// 
// 

;(function($){
	var editor_mcfgf_welcome_page_template = null;
	function initWelcomePageTemplate() {
		if(editor_mcfgf_welcome_page_template!=null) {
			editor_mcfgf_welcome_page_template.codemirror.getDoc().setValue(jQuery('#welcome_page_template').val());
			return;
		}
		setTimeout(function(){
			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit: 2,
					tabSize: 2,
				// mode: 'javascript'
				}
			);
			editor_mcfgf_welcome_page_template = wp.codeEditor.initialize( 'welcome_page_template', editorSettings );
			editor_mcfgf_welcome_page_template.codemirror.on('change',function(cMirror, changeObj){
				// console.log('changeObj', changeObj);
				// get value right from instance
				jQuery('#welcome_page_template').val(cMirror.getValue()).trigger('input');
			});
			// editor_mcfgf_welcome_page_template.codemirror.on('cursorActivity',function(cMirror){
			// 	console.log('cursorActivity', cMirror.state);
			// });
			
		}, 13);

		jQuery('#welcome_page_template').parent().on('click', '#gf_merge_tag_list li a', function(){
			var text = jQuery(this).data('value');
			var doc = editor_mcfgf_welcome_page_template.codemirror.getDoc();
			var cursor = doc.getCursor();
			doc.replaceRange(text, cursor);
		});
		// jQuery('#welcome_page_template').parent().on('propertychange', function(){

		// 	// editor_mcfgf_welcome_page_template.codemirror.getDoc().setValue(jQuery('#welcome_page_template').val());
		// });
	}

	//Conversatoin Button loader
	$(document).ready(function(){
		initWelcomePageTemplate();
	});
})(jQuery);
