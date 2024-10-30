<?php
//https://docs.gravityforms.com/checkbox_and_select/
//
//Extend GFAddOn to add following settings fields support
//
//1. Standard Condition Logic
//2. Size with Unit support
//3. Image picker
//
GFForms::include_addon_framework();

class GFYAKAddOnEx extends GFAddOn {

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts() {
		wp_enqueue_media();

		$scripts = array(
			array(
				'handle'  => 'jq_color_picker',
				'src'     => $this->get_base_url() . '/js/jqColorPicker.min.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				// 'strings' => array(
				// 	'first'  => esc_html__( 'First Choice', 'yakkeraddon' ),
				// 	'second' => esc_html__( 'Second Choice', 'yakkeraddon' ),
				// 	'third'  => esc_html__( 'Third Choice', 'yakkeraddon' )
				// ),
				'enqueue' => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => $this->_slug
					)
				)
			),
			array(
				'handle'  => 'gform_forms',
				// 'src'     => $this->get_base_url() . '/js/jqColorPicker.min.js',
				// 'version' => $this->_version,
				// 'deps'    => array( 'jquery' ),
				// 'strings' => array(
				// 	'first'  => esc_html__( 'First Choice', 'yakkeraddon' ),
				// 	'second' => esc_html__( 'Second Choice', 'yakkeraddon' ),
				// 	'third'  => esc_html__( 'Third Choice', 'yakkeraddon' )
				// ),
				'enqueue' => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => $this->_slug
					)
				)
			),
		);

		return array_merge( parent::scripts(), $scripts );
	}

	public function get_standard_conditional_logic_field_html($field)
	{
		// Get the setting name.
		$name = $field['name'];
		$object_type = $field['type'];

		// Define the properties for the checkbox to be used to enable/disable access to the yakker condition settings.
		$checkbox_field = array(
			'name'    => $name,
			'type'    => 'checkbox',
			'choices' => array(
				array(
					'label' => $field['choices'][0]['label'],
					'name'  => $object_type . '_conditional_logic',
				),
			),
			'onclick' => "".$object_type."SetConditionalLogic(this.checked);ToggleConditionalLogic(false, '{$name}');if(this.checked){jQuery('#{$name}_condition_container').show();} else{jQuery('#{$name}_condition_container').hide();}",
		);

		// Determine if the checkbox is checked, if not the yakker condition settings should be hidden.
		$is_enabled      = $this->get_setting( $name . '_conditional_logic' ) == '1';
		$container_style = ! $is_enabled ? "style='display:none;'" : '';

		// Put together the field markup.
		$str = sprintf( "%s<div id='%s_condition_container' %s>%s</div>",
			$this->settings_checkbox( $checkbox_field, false ),
			$name,
			$container_style,
			$this->standard_condition( $object_type )
		);
		return $str;
	}

	/**
	 * Outputs scripts for conditional logic fields.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GF_Fields::get_all()
	 * @uses GF_Field::is_conditional_logic_supported()
	 *
	 * @param bool $echo If the scripts should be echoed. Defaults to true.
	 *
	 * @return string $script_str The scripts to be output.
	 */
	public static function get_conditional_logic_fields_scripts( $echo = true ) {
		$script_str = '';
		$conditional_logic_fields = array();

		foreach ( GF_Fields::get_all() as $gf_field ) {
			if ( $gf_field->is_conditional_logic_supported() ) {
				$conditional_logic_fields[] = $gf_field->type;
			}
		}

		$script_str .= sprintf( 'function GetConditionalLogicFields(){return %s;}', json_encode( $conditional_logic_fields ) ) . PHP_EOL;

		if ( ! empty( $script_str ) && $echo ) {
			echo $script_str;
		}

		return $script_str;
	}

	//--------------  Standard Condition  ------------------------------------------------

	/**
	 * Helper to create a standard conditional logic set of fields. It creates multiple rows of conditional logic with Field/Operator/Value inputs.
	 *
	 * @param mixed $setting_name_root - The root name to be used for inputs. It will be used as a prefix to the inputs that make up the conditional logic fields.
	 *
	 * @return string The HTML
	 */
	public function standard_condition( $setting_name_root ) {

		$str = sprintf( "<div id='%s_conditional_logic_container'></div>", esc_attr( $setting_name_root ), esc_attr( $setting_name_root ), esc_attr( $setting_name_root ) );


		$str .= "<script type='text/javascript'>\r\n".self::get_conditional_logic_fields_scripts(false)."\r\n
			jQuery(document).ready(
				function(){
					console.log('form.".$this->_slug."', form);
					var form_addon_settings = form.".$this->_slug." || {};
					window.".$setting_name_root."SetConditionalLogic = function(isChecked) {
						console.log('".$setting_name_root."SetConditionalLogic', form.".$this->_slug.");
			    		form_addon_settings.".$setting_name_root."_conditional_logic_rules = isChecked ? {conditionalLogic: new ConditionalLogic()} : {};
					};
					

					window.".$setting_name_root."StandardConditionalObject = function(object, objectType){
						console.log('gform_conditional_object', form_addon_settings.".$setting_name_root."_conditional_logic_rules);
						if(objectType == '".$setting_name_root."') {
							return form_addon_settings.".$setting_name_root."_conditional_logic_rules;
						}
						else {
							return object;
						}
					}

					gform.addFilter( 'gform_conditional_object', '".$setting_name_root."StandardConditionalObject' );

					jQuery( '#form_settings' ).submit(function( event ) {
						console.log('form_addon_settings submit', form_addon_settings);
						jQuery('#".$setting_name_root."_conditional_logic_rules').val(jQuery.toJSON(form_addon_settings.".$setting_name_root."_conditional_logic_rules));
						// event.preventDefault();
					});

					//jQuery(document).bind('gform_load_form_settings', function(e, form){
						console.log('gform_load_form_addon_settings', form_addon_settings.".$setting_name_root."_conditional_logic);
				    	
						if (form_addon_settings.".$setting_name_root."_conditional_logic == 1) {
							ToggleConditionalLogic(false, '".$setting_name_root."');
						}
					//});
				}
			);
			</script>";

		return $str;
	}


	/**
	 * Renders and initializes size picker with a input field togethor with select field.
	 * 
	 * @access public
	 * @param array $field - Field array containing the configuration options of this field
	 * @param bool  $echo  = true - true to echo the output to the screen, false to simply return the contents as a string
	 *
	 * @return string The HTML for the field
	 */
	public function settings_size( $field, $echo = true ) {
		$field['text'] = array(
                'name'  => $field['name'].'Value'
        );
		$field['select'] = array(
            'name'    => $field['name'].'Unit',
            'choices' => array(
                array(
                    'label' => esc_html__( 'px', 'yakkeraddon' ),
                    'value' => 'px'
                ),
                array(
                    'label' => esc_html__( 'em', 'yakkeraddon' ),
                    'value' => 'em'
                ),
                array(
                    'label' => esc_html__( 'rem', 'yakkeraddon' ),
                    'value' => 'rem'
                ),
                array(
                    'label' => esc_html__( '%', 'yakkeraddon' ),
                    'value' => '%'
                )
            )
        );

		$html = $this->settings_text_and_select( $field, $echo );

		return $html;
	}

	/**
     * Displays a file upload field for a settings field
     *
     * @param array   $args settings field args
     */
    public function settings_file( $field, $echo = true ) {
    	$field['type'] = 'text';

    	$field['class'] .= ' yakker-url';

    	$label = "Select Image";
    	$field['after_input'] = '<input type="button" class="button yakker-browse" value="' . $label . '" /><div class="image-preview-wrapper"><div class="image-preview-close"><button type="button" class="notice-dismiss"><span class="screen-reader-text">Remove this image.</span></button></div>
        <img id="image-preview" default-src="'.$field['default_value'].'" width="26" height="26" style="max-height: 26px; width: 26px;">
</div>';

		// get markup
		$html = sprintf(
			'%s',
			$this->settings_text( $field, false )
		);
		$field['type'] = 'file';

		if ( $echo ) {
			echo $html;
		}

		return $html;
    }


	/**
	 * Renders and initializes a input field togethor with select field.
	 * 
	 * @access public
	 * @param array $field - Field array containing the configuration options of this field
	 * @param bool  $echo  = true - true to echo the output to the screen, false to simply return the contents as a string
	 *
	 * @return string The HTML for the field
	 */
	public function settings_color( $field, $echo = true ) {
		$field['type'] = 'text';
		// get markup
		$html = sprintf(
			'%s',
			$this->settings_text( $field, false )
		);
		$field['type'] = 'color';

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	/**
	 * Renders and initializes a input field togethor with select field.
	 * 
	 * @access public
	 * @param array $field - Field array containing the configuration options of this field
	 * @param bool  $echo  = true - true to echo the output to the screen, false to simply return the contents as a string
	 *
	 * @return string The HTML for the field
	 */
	public function settings_text_and_select( $field, $echo = true ) {

		$field = $this->prepare_settings_text_and_select( $field );

		$text_field = $field['text'];
		$select_field = $field['select'];

		// get markup

		$html = sprintf(
			'%s <span id="%s" class="%s">%s %s</span>',
			$this->settings_text( $text_field, false ),
			$select_field['name'] . 'Span',
			'',
			$this->settings_select( $select_field, false ),
			$this->maybe_get_tooltip( $select_field )
		);

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	public function prepare_settings_text_and_select( $field ) {

		// prepare text

		$text_input = rgars( $field, 'text' );

		$text_field = array(
			'type'       => 'text',
			'name'       => $field['name'] . 'Value',
			'horizontal' => true,
			'choices'    => false,
			'tooltip'    => false
		);

		$text_field = wp_parse_args( $text_input, $text_field );

		// prepare select

		$select_input = rgars( $field, 'select' );

		$select_field = array(
			'name'    => $field['name'] . 'Select',
			'type'    => 'select',
			'class'   => '',
			'tooltip' => false
		);

		$select_field['class'] .= ' ' . $select_field['name'];

		$select_field = wp_parse_args( $select_input, $select_field );


		$field['select'] = $select_field;
		$field['text'] = $text_field;

		return $field;
	}

}