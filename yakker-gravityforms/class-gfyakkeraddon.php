<?php


GFForms::include_addon_framework();

require_once('class-gf-addon-ex.php');

class GFYakkerAddOn extends GFYAKAddOnEx {

	protected $_version = GF_YAKKER_ADDON_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'gf_conversation';
	protected $_path = 'yakkeraddon/yakkeraddon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Conversation';
	protected $_short_title = 'Conversation';

	private static $_instance = null;

	/**
    * Members plugin integration
    */
    protected $_capabilities = array(
        'gravityforms_yakker',
        'gravityforms_yakker_uninstall',
        'gravityforms_yakker_settings',
    );

    /**
    * Permissions
    */
    protected $_capabilities_settings_page = 'gravityforms_yakker_settings';
    protected $_capabilities_form_settings =  'gravityforms_yakker';
    protected $_capabilities_plugin_page = 'gravityforms_yakker';
    protected $_capabilities_uninstall = 'gravityforms_yakker_uninstall';

	/**
	 * Get an instance of this class.
	 *
	 * @return GFYakkerAddOn
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFYakkerAddOn();
		}

		return self::$_instance;
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {
		parent::init();
		add_filter( 'gform_submit_button', array( $this, 'form_submit_button' ), 10, 2 );
		add_action( 'gform_after_submission', array( $this, 'after_submission' ), 10, 2 );

		// add_action( 'gform_form_settings_page_' . $this->_slug, array( $this, 'form_settings_page_magic_conversation' ), 9, 0 );


		// add_filter( 'gform_admin_pre_render', array( $this, 'gform_admin_pre_render_magic_conversation' ), 9999, 1 );
		

		// add_filter( 'gform_form_settings', array( $this, 'toggle_form_mode_form_setting' ), 10, 2 );
        // add_filter( 'gform_pre_form_settings_save', array( $this, 'save_magic_conversation_form_setting' ) );

        // wp_enqueue_script( 'customize-controls' );
        // wp_enqueue_style( 'customize-controls' );
	}

	// function gform_admin_pre_render_magic_conversation($form) {
	// 	// print_r($form);
	// 	// die();

	// 	$form['gf_conversation']['submission_data_model'] = base64_decode($form['gf_conversation']['submission_data_model']);
	// 	$form['gf_conversation']['welcome_page_template'] = base64_decode($form['gf_conversation']['welcome_page_template']);
	// 	return $form;
	// }

	// function form_settings_page_magic_conversation() {
	// 	if ( count( $_POST ) > 0 && isset($_POST['_gaddon_setting_submission_data_model'])) {
	// 		$_POST['_gaddon_setting_submission_data_model'] = base64_encode(stripslashes_deep($_POST['_gaddon_setting_submission_data_model']));
	// 	}
	// 	if ( count( $_POST ) > 0 && isset($_POST['_gaddon_setting_welcome_page_template'])) {
	// 		$_POST['_gaddon_setting_welcome_page_template'] = base64_encode(stripslashes_deep($_POST['_gaddon_setting_welcome_page_template']));
	// 	}
	// }

	function toggle_form_mode_form_setting( $settings, $form ) {
		

		if ( rgar( $form, 'yakker_form_mode' )==2 ) {
			$yakker_form_mode_normal ='';
			$yakker_form_mode_chatbot = 'checked="checked"';
			$yakker_form_mode_conversation = '';
		}
		else if ( rgar( $form, 'yakker_form_mode' )==3 ) {
			$yakker_form_mode_normal ='';
			$yakker_form_mode_chatbot = '';
			$yakker_form_mode_conversation = 'checked="checked"';
			
		}
		else {
			$yakker_form_mode_normal = 'checked="checked"';
			$yakker_form_mode_chatbot = '';
			$yakker_form_mode_conversation = '';
		}

        $settings['Form Basics']['toggle_form_mode_setting'] = '
		    <tr>
	            <th><label>'.__('Form Mode', 'yakker' ).' '.gform_tooltip( 'yakker_form_mode', '', true ).'</th>
	            <td>
	                <input type="radio" id="yakker_form_mode_normal" name="yakker_form_mode" value="1" ' . $yakker_form_mode_normal . '/>
	                <label for="yakker_form_mode">' . __( 'Form Mode', 'yakker' ) . '</label>
	            </td>
	        </tr>
	        <tr>
	            <th><label>'.gform_tooltip( 'yakker_form_mode', '', true ).'</th>
	            <td>
	                <input type="radio" id="yakker_form_mode_chatbot" name="yakker_form_mode" value="2" ' . $yakker_form_mode_chatbot . '/>
	                <label for="yakker_form_mode_chatbot">' . __( 'Chatbot Mode', 'yakker' ) . '</label>
	            </td>
	        </tr>
	        <tr>
	            <th><label>'.gform_tooltip( 'yakker_form_mode', '', true ).'</th>
	            <td>
	                <input type="radio" id="yakker_form_mode_chatbot" name="yakker_form_mode" value="3" ' . $yakker_form_mode_conversation . '/>
	                <label for="yakker_form_mode_chatbot">' . __( 'Conversation Mode', 'yakker' ) . '</label>
	            </td>
	        </tr>';

        return $settings;
    }
 
    // function save_toggle_form_mode_form_setting( $form ) {
    //     $form['yakker_form_mode'] = rgpost( 'yakker_form_mode' );
    //     return $form;
    // }

    function save_magic_conversation_form_setting( $form ) {
        // $form['submission_data_model'] = base64_encode(rgpost( '_gaddon_setting_submission_data_model'));
        // 
        // print_r($form);

        // die();

        $form['submission_data_model'] = "abc";//rgpost( '_gaddon_setting_submission_data_model')."-------";
        return $form;
    }


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			// 'gform_forms',
			array(
				'handle'  => 'conversation_edit_js',
				'src'     => $this->get_base_url() . '/js/conversationEdit.js',
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

		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'my_styles_css',
				'src'     => $this->get_base_url() . '/css/my_styles.css',
				'version' => $this->_version,
				'enqueue' => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => $this->_slug
					)
				)
			)
		);

		return array_merge( parent::styles(), $styles );
	}


	// # FRONTEND FUNCTIONS --------------------------------------------------------------------------------------------

	/**
	 * Add the text in the plugin settings to the bottom of the form if enabled for this form.
	 *
	 * @param string $button The string containing the input tag to be filtered.
	 * @param array $form The form currently being displayed.
	 *
	 * @return string
	 */
	function form_submit_button( $button, $form ) {
		$settings = $this->get_form_settings( $form );
		if ( isset( $settings['enabled'] ) && true == $settings['enabled'] ) {
			$text   = $this->get_plugin_setting( 'mytextbox' );
			$button = "<div>{$text}</div>" . $button;
		}

		return $button;
	}


	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

	/**
	 * Creates a custom page for this add-on.
	 */
	public function ___plugin_page() {
		echo 'This page appears in the Forms menu';
	}

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	 */
	// public function plugin_settings_fields() {
	// 	return array(
	// 		array(
	// 			'title'  => esc_html__( 'Yakker Add-On Settings', 'yakkeraddon' ),
	// 			'fields' => array(
	// 				array(
	// 					'name'              => 'mytextbox',
	// 					'tooltip'           => esc_html__( 'This is the tooltip', 'yakkeraddon' ),
	// 					'label'             => esc_html__( 'This is the label', 'yakkeraddon' ),
	// 					'type'              => 'text',
	// 					'class'             => 'small',
	// 					'feedback_callback' => array( $this, 'is_valid_setting' ),
	// 				)
	// 			)
	// 		)
	// 	);
	// }

	/**
	 * Configures the settings which should be rendered on the Form Settings > Yakker Add-On tab.
	 *
	 * @return array
	 */
	public function form_settings_fields( $form ) {
		return array(
			// array(
			// 	'title'  => esc_html__( 'Conversation: Form Settings', 'yakkeraddon' ),
			// 	'fields' => array(
			// 		array(
			// 			'label'   => esc_html__( 'Mobile Fullscreen ', 'yakkeraddon' ),
			// 			'type'    => 'checkbox',
			// 			'name'    => 'enable_fullscreen_on_mobile',
			// 			'tooltip' => esc_html__( 'Check to show conversation fullscreen without title and description even when using conversation button', 'yakkeraddon' ),
			// 			'choices' => array(
			// 				array(
			// 					'label' => esc_html__( 'Show conversation fullscreen in mobile device', 'yakkeraddon' ),
			// 					'name'  => 'enable_fullscreen_on_mobile',
			// 				),
			// 			),
			// 		),
			// 		array(
			// 			'label'   => esc_html__( 'Confirmation', 'yakkeraddon' ),
			// 			'type'    => 'radio',
			// 			'name'    => 'conversation_confirm_message_mode',
			// 			'tooltip' => esc_html__( 'How to show confirm message when user submit conversation form', 'yakkeraddon' ),
			// 			'choices' => array(
			// 				array(
			// 					'label' => esc_html__( 'Enable conversation conditional confirmation message configured via the HTML fields.', 'yakkeraddon' ),
			// 					'value' => "1"
			// 				),
			// 				array(
			// 					'label' => esc_html__( 'Show default confirmation message as normal form submission.', 'yakkeraddon' ),
			// 					'value' => "2"
			// 				)
			// 			)
			// 		),
			// 		array(
			// 			'label'   => esc_html__( 'Rewind Conversation Conditional Logic Rules', 'yakkeraddon' ),
			// 			'type'    => 'hidden',
			// 			'name'    => 'rewind_conversation_conditional_logic_rules',
			// 		),
			// 		array(
			// 			'label'   => esc_html__( 'Rewind Conversation', 'yakkeraddon' ),
			// 			'type'    => 'rewind_conversation',
			// 			'name'    => 'rewind_conversation',
			// 			'tooltip' => esc_html__( 'Rewind conversation with conditional logic.', 'yakkeraddon' ),
			// 			'choices' => array(
			// 				array(
			// 					'label' => esc_html__( 'Enable Rewind Conversation after confirmation', 'yakkeraddon' )
			// 				),
			// 			),
			// 		),
			// 	)
			// ),
			array(
				'title'  => esc_html__( 'General', 'yakkeraddon' ),
				'fields' => array(
					
					array(
						'label'      => esc_html__( 'Conversation Mode', 'yakkeraddon' ),
						'type'       => 'radio',
						// 'horizontal' => true,
						'name'       => 'conversation_mode',
						'tooltip'    => esc_html__( 'Choose an option to decide how to control conversation flow.', 'yakkeraddon' ),
						'default_value' => 'chat',
						'choices'    => array(
							array(
								'label' => esc_html__( 'Chat mode', 'yakkeraddon' ),
								'value' => 'chat',
							),
							array(
								'label' => esc_html__( 'Wizard mode (for Multi-Page forms)', 'yakkeraddon' ),
								'value' => 'wizard',
							),
							array(
								'label' => esc_html__( 'Welcome mode (for route conversations)', 'yakkeraddon' ),
								'value' => 'welcome',
							)
						),
					),
					array(
						'label'             => esc_html__( 'Welcome Message Title', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'conversation_welcome_message_title',
						'tooltip'           => esc_html__( 'The welcome message tooltip title displayed on top of conversation button.', 'yakkeraddon' ),
						'class'             => 'medium merge-tag-support mt-position-right',
						'default_value' => 'Hello',
						//'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'label'             => esc_html__( 'Welcome Message Sub-Title', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'conversation_welcome_message_sub_title',
						'tooltip'           => esc_html__( 'The welcome message tooltip sub title displayed on top of conversation button.', 'yakkeraddon' ),
						'class'             => 'medium merge-tag-support mt-position-right',
						'default_value' => 'I\'m Magic Conversation.',
						//'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'label'             => esc_html__( 'Conversation Header Title', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'conversation_header_title',
						'tooltip'           => esc_html__( 'The title of conversation header displayed on top of conversation opened by click conversation button.', 'yakkeraddon' ),
						'class'             => 'medium merge-tag-support mt-position-right',
						'default_value' => 'Hello',
						//'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'label'             => esc_html__( 'Conversation Header Sub-Title', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'conversation_header_sub_title',
						'tooltip'           => esc_html__( 'The sub title of conversation header displayed on top of conversation opened by click conversation button.', 'yakkeraddon' ),
						'class'             => 'medium merge-tag-support mt-position-right',
						'default_value' => 'I\'m Magic Conversation.',
						//'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'label'             => esc_html__( 'Display Name For Robot', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'conversation_robot_display_name',
						'tooltip'           => esc_html__( 'The name displays close to Robot avatar.', 'yakkeraddon' ),
						'class'             => 'small', //'medium',
						'default_value' => 'Magic Conversation',
						//'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'label'   => esc_html__( 'Welcome Page Template', 'yakkeraddon' ),
						'type'    => 'textarea',
						'allow_html' => true,
						'name'    => 'welcome_page_template',
						'tooltip' => esc_html__( 'Setup a webcome page template.', 'yakkeraddon' ),
						'class'   => 'medium merge-tag-support mt-position-right',
						// 'callback' => array( $this, 'render_submission_data_model' ),
						// 'save_callback' => array( $this, 'after_save_json_model' ),
						// 'feedback_callback' => array( $this, 'is_valid_json_model' ),
					),
					// array(
					// 	'label'             => esc_html__( 'Confirm Text for Wizard mode', 'yakkeraddon' ),
					// 	'type'              => 'text',
					// 	'name'              => 'confirm_text_for_wizard_mode',
					// 	'tooltip'           => esc_html__( 'The text for the confirm button when Wizard mode enabled.', 'yakkeraddon' ),
					// 	'class'             => 'small', //'medium',
					// 	'default_value' => 'Confirm',
					// 	//'feedback_callback' => array( $this, 'is_valid_setting' ),
					// ),
					// array(
					// 	'label'             => esc_html__( 'Back Text for Wizard mode', 'yakkeraddon' ),
					// 	'type'              => 'text',
					// 	'name'              => 'back_text_for_wizard_mode',
					// 	'tooltip'           => esc_html__( 'The text for the back button when Wizard mode enabled.', 'yakkeraddon' ),
					// 	'class'             => 'small', //'medium',
					// 	'default_value' => 'Back',
					// 	//'feedback_callback' => array( $this, 'is_valid_setting' ),
					// ),
					array(
						'label'      => esc_html__( 'Confirmation Text', 'yakkeraddon' ),
						'type'       => 'radio',
						// 'horizontal' => true,
						'name'       => 'conversation_action_on_confirmation_text',
						'tooltip'    => esc_html__( 'Choose an option to show conformation message when Confirmation Type is Text.', 'yakkeraddon' ),
						'default_value' => 'conversation_message',
						'choices'    => array(
							array(
								'label' => esc_html__( 'Show as a conversation message', 'yakkeraddon' ),
								'value' => 'conversation_message',
							),
							array(
								'label' => esc_html__( 'Replace conversation box with confirmation text', 'yakkeraddon' ),
								'value' => 'replace',
							)
						),
					),
					array(
						'label'      => esc_html__( 'Confirmation Page or Redirect', 'yakkeraddon' ),
						'type'       => 'radio',
						// 'horizontal' => true,
						'name'       => 'conversation_action_on_page_or_redirect',
						'tooltip'    => esc_html__( 'Choose an option to show conformation message when Confirmation Type is Page or Redirect.', 'yakkeraddon' ),
						'default_value' => 'redirect',
						'choices'    => array(
							array(
								'label' => esc_html__( 'Open in same window or tab', 'yakkeraddon' ),
								'value' => 'redirect',
							),
							array(
								'label' => esc_html__( 'Open in new window or tab', 'yakkeraddon' ),
								'value' => 'redirect_new',
							),
							array(
								'label' => esc_html__( 'Show a link preview', 'yakkeraddon' ),
								'value' => 'linkpreview'
							),
							array(
								'label' => esc_html__( 'Show page HTML content', 'yakkeraddon' ),
								'value' => 'content'
							),
							array(
								'label' => esc_html__( 'Show it in an iframe', 'yakkeraddon' ),
								'value' => 'iframe'
							),
						),
					),
					array(
						'label'             => esc_html__( 'Iframe Height', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'iframe_height',
						'tooltip'           => esc_html__( 'Enter height of the iframe that you would like to show the confirmation page.', 'yakkeraddon' ),
						'class'             => 'small',
						'default_value' => '300',
						// 'feedback_callback' => array( $this, 'is_valid_unit_setting' ),
						"after_input" => "<i> px</i>",
					),
					array(
						'label'   => esc_html__( 'Hide input toolbar', 'yakkeraddon' ),
						'type'    => 'checkbox',
						'name'    => 'enable_hide_toolbar',
						'tooltip' => esc_html__( 'Check to hide input toolbar.', 'yakkeraddon' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Hide input toolbar for conversations with no text input.', 'yakkeraddon' ),
								'name'  => 'enable_hide_toolbar',
								'value' => '1'
							),
						),
					),
					array(
						'label'   => esc_html__( 'Auto confirmation', 'yakkeraddon' ),
						'type'    => 'checkbox',
						'name'    => 'enable_auto_radio_confirm',
						'tooltip' => esc_html__( 'Check to allow send user choice with single click on a radio option.', 'yakkeraddon' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Allow send user choice with single click on a radio option.', 'yakkeraddon' ),
								'name'  => 'enable_auto_radio_confirm',
								'value' => '1'
							),
						),
					),
					// array(
					// 	'label'             => esc_html__( 'JSON Validation Url', 'yakkeraddon' ),
					// 	'type'              => 'text',
					// 	'name'              => 'validation_url',
					// 	'tooltip'           => esc_html__( 'Enter a third party url that you want to override default validation url.', 'yakkeraddon' ),
					// 	'class'             => 'large',
					// 	// 'default_value' => '300',
					// 	// 'feedback_callback' => array( $this, 'is_valid_unit_setting' ),
					// 	// "after_input" => "<i> px</i>",
					// ),
					array(
						'label'      => esc_html__( 'Submission Method', 'yakkeraddon' ),
						'type'       => 'radio',
						// 'horizontal' => true,
						'name'       => 'submit_method',
						'tooltip'    => esc_html__( 'Choose an option how to submit user input data.', 'yakkeraddon' ),
						'default_value' => 'gravityforms',
						'choices'    => array(
							array(
								'label' => esc_html__( 'Submit to Gravity Forms', 'yakkeraddon' ),
								'value' => 'gravityforms',
							),
							array(
								'label' => esc_html__( 'Submit to a Rest API (POST)', 'yakkeraddon' ),
								'value' => 'restapi',
							),
							array(
								'label' => esc_html__( 'Post message to parent window (iframe)', 'yakkeraddon' ),
								'value' => 'postmessage'
							),
							array(
								'label' => esc_html__( 'Show a Reset button', 'yakkeraddon' ),
								'value' => 'reset'
							)
						),
					),
					array(
						'label'             => esc_html__( 'Reset button text', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'reset_btn_text',
						'tooltip'           => esc_html__( 'The text for the reset button.', 'yakkeraddon' ),
						'class'             => 'small', //'medium',
						'default_value' => 'I need more help',
						//'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'label'             => esc_html__( 'JSON Submission Url', 'yakkeraddon' ),
						'type'              => 'text',
						'name'              => 'submission_url',
						'tooltip'           => esc_html__( 'Enter a third party url that you want to override default submission url.', 'yakkeraddon' ),
						'class'             => 'large',
						// 'default_value' => '300',
						// 'feedback_callback' => array( $this, 'is_valid_unit_setting' ),
						// "after_input" => "<i> px</i>",
					),
					array(
						'label'   => esc_html__( 'JSON Submission Data Template', 'yakkeraddon' ),
						'type'    => 'textarea',
						'allow_html' => true,
						'name'    => 'submission_data_model',
						'tooltip' => esc_html__( 'Setup a json model with Merged Tags to convert form data to your REST API data format.', 'yakkeraddon' ),
						'class'   => 'medium merge-tag-support mt-position-right',
						// 'callback' => array( $this, 'render_submission_data_model' ),
						'save_callback' => array( $this, 'after_save_json_model' ),
						// 'feedback_callback' => array( $this, 'is_valid_json_model' ),
					),
					// array(
					// 	'label'             => esc_html__( 'Conversation Area Height', 'yakkeraddon' ),
					// 	'type'              => 'size',
					// 	'name'              => 'conversation_box_height',
					// 	'tooltip'           => esc_html__( 'Enter the height you would like to show the conversation.', 'yakkeraddon' ),
					// 	'class'             => 'small',
					// 	// 'feedback_callback' => array( $this, 'is_valid_unit_setting' ),
					// 	"after_input" => "<i> add units: em/px/pt etc.</i>",
					// ),

					// array(
					// 	'label'             => esc_html__( 'Conversation Area Background Color', 'yakkeraddon' ),
					// 	'type'              => 'color',
					// 	'name'              => 'conversation_box_background_color',
					// 	'tooltip'           => esc_html__( 'Background color for Conversation Area.', 'yakkeraddon' ),
					// 	'class'             => 'color',
					// ),
					// array(
					// 	'label'             => esc_html__( 'Conversation Area Background Image', 'yakkeraddon' ),
					// 	'type'              => 'file',
					// 	'name'              => 'conversation_box_background_image',
					// 	'tooltip'           => esc_html__( 'Background Image for Conversation Area.', 'yakkeraddon' ),
					// 	'class'             => 'file',
					// ),
					// ),
					// array(
					// 	'label'      => esc_html__( 'My Horizontal Radio Buttons', 'yakkeraddon' ),
					// 	'type'       => 'radio',
					// 	'horizontal' => true,
					// 	'name'       => 'myradiogrouph',
					// 	'tooltip'    => esc_html__( 'This is the tooltip', 'yakkeraddon' ),
					// 	'choices'    => array(
					// 		array(
					// 			'label' => esc_html__( 'First Choice', 'yakkeraddon' ),
					// 		),
					// 		array(
					// 			'label' => esc_html__( 'Second Choice', 'yakkeraddon' ),
					// 		),
					// 		array(
					// 			'label' => esc_html__( 'Third Choice', 'yakkeraddon' ),
					// 		),
					// 	),
					// ),
					// array(
					// 	'label'   => esc_html__( 'My Dropdown', 'yakkeraddon' ),
					// 	'type'    => 'select',
					// 	'name'    => 'mydropdown',
					// 	'tooltip' => esc_html__( 'This is the tooltip', 'yakkeraddon' ),
					// 	'choices' => array(
					// 		array(
					// 			'label' => esc_html__( 'First Choice', 'yakkeraddon' ),
					// 			'value' => 'first',
					// 		),
					// 		array(
					// 			'label' => esc_html__( 'Second Choice', 'yakkeraddon' ),
					// 			'value' => 'second',
					// 		),
					// 		array(
					// 			'label' => esc_html__( 'Third Choice', 'yakkeraddon' ),
					// 			'value' => 'third',
					// 		),
					// 	),
					// ),
					// array(
					// 	'label'             => esc_html__( 'My Text Box', 'yakkeraddon' ),
					// 	'type'              => 'text',
					// 	'name'              => 'mytext',
					// 	'tooltip'           => esc_html__( 'This is the tooltip', 'yakkeraddon' ),
					// 	'class'             => 'medium',
					// 	'feedback_callback' => array( $this, 'is_valid_setting' ),
					// ),
					// array(
					// 	'label'   => esc_html__( 'My Text Area', 'yakkeraddon' ),
					// 	'type'    => 'textarea',
					// 	'name'    => 'mytextarea',
					// 	'tooltip' => esc_html__( 'This is the tooltip', 'yakkeraddon' ),
					// 	'class'   => 'medium merge-tag-support mt-position-right',
					// ),
					// array(
					// 	'label' => esc_html__( 'My Hidden Field', 'yakkeraddon' ),
					// 	'type'  => 'hidden',
					// 	'name'  => 'myhidden',
					// ),
					// array(
					// 	'label' => esc_html__( 'My Custom Field', 'yakkeraddon' ),
					// 	'type'  => 'toggle_form_mode_field_type',
					// 	'name'  => 'toggle_form_mode_field',
					// 	'args'  => array(
					// 		'text'     => array(
					// 			'label'         => esc_html__( 'A textbox sub-field', 'yakkeraddon' ),
					// 			'name'          => 'subtext',
					// 			'default_value' => 'change me',
					// 		),
					// 		'checkbox' => array(
					// 			'label'   => esc_html__( 'A checkbox sub-field', 'yakkeraddon' ),
					// 			'name'    => 'toggle_form_mode_field_check',
					// 			'choices' => array(
					// 				array(
					// 					'label'         => esc_html__( 'Activate', 'yakkeraddon' ),
					// 					'name'          => 'subcheck',
					// 					'default_value' => true,
					// 				),
					// 			),
					// 		),
					// 	),
					// ),
					// array(
					// 	'label' => esc_html__( 'Yakker condition', 'yakkeraddon' ),
					// 	'type'  => 'custom_logic_type',
					// 	'name'  => 'custom_logic',
					// ),
				),
			),
		);
	}

	/**
	 * Define the markup for the toggle_form_mode_field_type type field.
	 *
	 * @param array $field The field properties.
	 * @param bool|true $echo Should the setting markup be echoed.
	 */
	public function settings_toggle_form_mode_field_type( $field, $echo = true ) {
		echo '<div>' . esc_html__( 'My custom field contains a few settings:', 'yakkeraddon' ) . '</div>';

		// get the text field settings from the main field and then render the text field
		$text_field = $field['args']['text'];
		$this->settings_text( $text_field );

		// get the checkbox field settings from the main field and then render the checkbox field
		$checkbox_field = $field['args']['checkbox'];
		$this->settings_checkbox( $checkbox_field );
	}


	// # CONDITION EXAMPLE --------------------------------------------------------------------------------------

	/**
	 * Define the markup for the custom_logic_type type field.
	 *
	 * @param array $field The field properties.
	 * @param bool|true $echo Should the setting markup be echoed.
	 */
	public function settings_rewind_conversation( $field, $echo = true ) {
		 //esc_html__( 'Enable Rewind Conversation after confirmation', 'yakkeraddon' );
		$settings_html = $this->get_standard_conditional_logic_field_html($field);
		if($echo){
			global $wp_scripts, $wp_customize;
			require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';
			$GLOBALS['wp_customize'] = new WP_Customize_Manager( compact( 'changeset_uuid', 'theme', 'messenger_channel', 'settings_previewed', 'autosaved', 'branching' ) );
			// require_once( ABSPATH . WPINC . '/class-wp-customize-setting.php' );
			// require_once( ABSPATH . WPINC . '/class-wp-customize-panel.php' );
			// require_once( ABSPATH . WPINC . '/class-wp-customize-section.php' );
			// require_once( ABSPATH . WPINC . '/class-wp-customize-control.php' );

			// require_once( ABSPATH . WPINC . '/customize/class-wp-customize-color-control.php' );
			// require_once( ABSPATH . WPINC . '/customize/class-wp-customize-media-control.php' );
			// require_once( ABSPATH . WPINC . '/customize/class-wp-customize-upload-control.php' );
			// require_once( ABSPATH . WPINC . '/customize/class-wp-customize-image-control.php' );
			$control = new WP_Customize_Image_Control(
				$wp_customize, 'logincust_form_bg_image', array(
					'label' => __( 'Background Image', 'login-customizer' ),
					'section' => 'logincust_form_bg_section',
					'priority' => 5,
					'settings' => 'logincust_form_bg_image',
				)
			);
			// print_r($control);
			$control->print_template();
		// 	echo '<ul><li id="customize-control-custom_logo" class="customize-control customize-control-cropped_image" style="display: list-item;">
					
		
		// 	<label class="customize-control-title" for="customize-media-control-button-36">Logo</label>
		
		// <div class="customize-control-notifications-container" style="display: none;"><ul></ul></div>
		

		
		// 	<div class="attachment-media-view">
		// 		<div class="placeholder">
		// 				No logo selected
		// 		</div>
		// 		<div class="actions">
					
					
		// 			<button type="button" class="button upload-button" id="customize-media-control-button-36">Select logo</button>
					
		// 		</div>
		// 	</div>
		
		// 		</li></ul>';
			echo $settings_html;
		}
		return $settings_html;
	}

	/**
	 * Evaluate the conditional logic.
	 *
	 * @param array $form The form currently being processed.
	 * @param array $entry The entry currently being processed.
	 *
	 * @return bool
	 */
	public function is_custom_logic_met( $form, $entry ) {
		if ( $this->is_gravityforms_supported( '2.0.7.4' ) ) {
			// Use the helper added in Gravity Forms 2.0.7.4.

			return $this->is_yakker_condition_met( 'custom_logic', $form, $entry );
		}

		// Older version of Gravity Forms, use our own method of validating the yakker condition.
		$settings = $this->get_form_settings( $form );

		$name       = 'custom_logic';
		$is_enabled = rgar( $settings, $name . '_enabled' );

		if ( ! $is_enabled ) {
			// The setting is not enabled so we handle it as if the rules are met.

			return true;
		}

		// Build the logic array to be used by Gravity Forms when evaluating the rules.
		$logic = array(
			'logicType' => 'all',
			'rules'     => array(
				array(
					'fieldId'  => rgar( $settings, $name . '_field_id' ),
					'operator' => rgar( $settings, $name . '_operator' ),
					'value'    => rgar( $settings, $name . '_value' ),
				),
			)
		);

		return GFCommon::evaluate_conditional_logic( $logic, $form, $entry );
	}

	/**
	 * Performing a custom action at the end of the form submission process.
	 *
	 * @param array $entry The entry currently being processed.
	 * @param array $form The form currently being processed.
	 */
	public function after_submission( $entry, $form ) {

		// Evaluate the rules configured for the custom_logic setting.
		// $result = $this->is_custom_logic_met( $form, $entry );

		// if ( $result ) {
		// 	// Do something awesome because the rules were met.
		// }
	}


	// # HELPERS -------------------------------------------------------------------------------------------------------

	/**
	 * The feedback callback for validating the size with unit text.
	 *
	 * @param string $value The setting value.
	 *
	 * @return bool
	 */
	public function is_valid_unit_setting( $value ) {
		return strlen( $value ) < 10;
	}

	public function after_save_json_model($field, $field_value) {
		// $_POST['_gaddon_setting_'.$field['name']] = json_encode($_POST['_gaddon_setting_'.$field['name']]);
		$field_value = json_encode($field_value);
		return $field_value;
	}


	public function render_submission_data_model ($field) {
		// print_r($field);
		// $field['value'] = base64_decode($field['value']);
		// $this->settings_textarea($field);
		$echo = false;
		$field['type'] = 'textarea'; //making sure type is set to textarea
		$attributes    = $this->get_field_attributes( $field );
		$default_value = rgar( $field, 'value' ) ? rgar( $field, 'value' ) : rgar( $field, 'default_value' );
		$raw_value         = $this->get_setting( $field['name'], $default_value );

		$value = $raw_value;
		// $value = base64_decode($raw_value);

		$name    = '' . esc_attr( $field['name'] );
		$html    = '';

		// $attributes['id'] = str_replace("id='", "id='conversation_", $attributes['id']);

		if ( rgar( $field, 'use_editor' ) ) {
			
			$html .= '<span class="mt-gaddon-editor mt-_gaddon_setting_'. $field['name'] .'"></span>';
			
			ob_start();
			
			wp_editor( $value, '_gaddon_setting_'. $field['name'], array( 'autop' => false, 'editor_class' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right' ) );
			
			$html .= ob_get_contents();
			ob_end_clean();
			
		} else {
			
			$html .= '<textarea
                    name="_gaddon_setting_' . $name . '" ' .
		         implode( ' ', $attributes ) .
		         '>' .
			         esc_textarea( $value ) .
		         '</textarea>';
			
		}

		if ( $this->field_failed_validation( $field ) ) {
			$html .= $this->get_error_icon( $field );
		}

		if($field['name'] === 'welcome_page_template') {
			// $html .= '<div>abc</div>';
		}

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}
}