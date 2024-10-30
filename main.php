<?php
/*
	Magic Conversation For Gravity Forms
*/
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists('MagicConversationForGravityForms' ) ):


$options = get_option( 'mcfgf_settings' );
$GLOBALS['mcfgf_is_valid_license_key'] = isset($options['is_valid_license_key']) && (intval($options['is_valid_license_key'])==1);
// echo $GLOBALS['mcfgf_is_valid_license_key'];
// die();

require_once (dirname(__FILE__).'/lib/wp-hooks.php');

class MagicConversationForGravityForms extends WP_Hooks {
	// private $settings;
	// function __construct() {
	// 	global $mcfgf_settings_basics;
 //        $this->settings = $mcfgf_settings_basics;
 //    }
	
	private $editor_id = 'mcfgf_questions';
	private $version = MCFGFP_VER;

	// detect if WooCommerce installed
	private $isWooCommerceInstalled = false;

	function plugins_loaded() {
		$this->isWooCommerceInstalled = defined('WC_VERSION');
	}

	function _install_me() {
		$options = get_option('mcfgf_conversation_generator',false);
		if(!$options) {
			$defaultOptions = "{\"css_code\":\"cf-chat-response {  color: #FFFFFF;  border-radius: 10px;  font-size: 14px;  -webkit-border-radius: 10px;  -moz-border-radius: 10px;  margin-left: -3.925px;  margin-top: -2.6px;  border-color: #333333;  border-width: 0;}cf-chat-response text {  line-height: 150%;  background-color: #333333;  padding: 7.199999999999999px 18px 7.199999999999999px 18px;}cf-chat-response.user {  color: #FCFCFC;  border-radius: 10px;  -webkit-border-radius: 10px;  -moz-border-radius: 10px;  border-color: #FF9700; border-width: 0;}cf-chat-response.user text {  line-height: 150%;  background-color: #FF9700;}cf-chat-response.robot thumb {  background-image: url(random1.png) !important;}cf-chat-response.user thumb {  background-image: url(random2.png) !important;}\",\"css_options\":\"{\\\"fontColor\\\":\\\"#FFFFFF\\\",\\\"userFontColor\\\":\\\"#FCFCFC\\\",\\\"fontSize\\\":\\\"14\\\",\\\"backgroundColor\\\":\\\"#333333\\\",\\\"userBackgroundColor\\\":\\\"#FF9700\\\",\\\"borderRadius\\\":10,\\\"offsetLeft\\\":\\\"-3.925\\\",\\\"padding\\\":0.6,\\\"offsetTop\\\":\\\"-2.6\\\",\\\"borderColor\\\":\\\"#333333\\\",\\\"userBorderColor\\\":\\\"#333333\\\",\\\"borderWidth\\\":\\\"0\\\",\\\"lineHeight\\\":\\\"150%\\\",\\\"uerFontColor\\\":\\\"#FFFFFF\\\",\\\"robotAvatar\\\":\\\"url(random1.png) !important\\\",\\\"userAvatar\\\":\\\"url(random2.png) !important\\\"}\",\"js_code\":\"{\\\"position\\\":{\\\"my\\\":\\\"left center\\\",\\\"at\\\":\\\"right center\\\",\\\"adjust\\\":{\\\"method\\\":\\\"none\\\"}},\\\"style\\\":{\\\"classes\\\":\\\"mm-tooltip-cf7-container\\\"},\\\"content\\\":{\\\"text\\\":{\\\"0\\\":{},\\\"length\\\":1,\\\"prevObject\\\":{\\\"0\\\":{\\\"jQuery172021905201394110918\\\":4},\\\"context\\\":{\\\"jQuery172021905201394110918\\\":4},\\\"length\\\":1},\\\"context\\\":{\\\"jQuery172021905201394110918\\\":4},\\\"selector\\\":\\\".next(div)\\\"}},\\\"show\\\":true}\",\"avatar_robot\":\"random1.png\",\"avatar_user\":\"random2.png\"}";
			$rand1png = mcfgf_conversation_generator_random_avatar_img();
			$rand2png = mcfgf_conversation_generator_random_avatar_img();
			$defaultOptions = str_replace('random1.png', $rand1png, $defaultOptions);
			$defaultOptions = str_replace('random2.png', $rand2png, $defaultOptions);
			$s = json_decode($defaultOptions,true);
			update_option('mcfgf_conversation_generator',$s);
		}

		$settings = get_option('mcfgf_settings',false);

		if(!$settings) {
			$defaultSettings = '{"dummy":"1"}';
			$settings = json_decode($defaultSettings,true);
		}
		$settings = apply_filters('mcfgf_default_settings', $settings);
		update_option('mcfgf_settings', $settings);

		$settings_basics = get_option('mcfgf_basics',false);
		if(!$settings_basics) {
			$defaultSettingsBasics = "{\"conversation_input_placeholder\":\"Type your answer here\",\"conversation_input_placeholder_for_radio\":\"Please choose from above\",\"conversation_input_done_button_label\":\"Done\",\"conversation_input_done_button_width\":\"100\",\"conversation_toolbar_button_color\":\"#ff9700\",\"enable_conversation_button\":\"on\",\"enable_notification_message\":\"on\",\"conversation_button_background_color\":\"#ff9700\",\"conversation_button_image_normal\":\"\",\"conversation_button_image_active\":\"\",\"enable_conversation_header\":\"on\",\"conversation_header_title\":\"Conversation Title\",\"conversation_header_sub_title\":\"This is Sub Conversation Title\",\"conversation_header_username\":\"Hi\",\"conversation_header_welcome_message\":\"Welcome, I am your assistant John, How can I help you?\",\"conversation_header_background_color\":\"#ff9700\",\"custom_css\":\".conversational-form--enable-animation cf-chat-response.show text[value-added] {\\r\\n   font-size:13px;\\r\\n}\"}";
			$settings_basics = json_decode($defaultSettingsBasics,true);
		}

		$settings_basics = apply_filters('mcfgf_default_settings_basics', $settings_basics);
		update_option('mcfgf_basics', $settings_basics);
	}

	function gform_noconflict_scripts($required_objects) {
		if(is_admin()) {
			
			// $this->maybe_better_font_awesome_enqueue_scripts($required_objects);
			// $this->admin_enqueue_scripts();
			// wp_enqueue_script( 'mce-view' );
			
			// $required_objects[] = 'mce-view';
			$required_objects[] = 'wp-tinymce';
			$required_objects[] = 'wp-tinymce-root';
			$required_objects[] = 'wp-tinymce-lists';
			$required_objects[] = 'mce-view';
			// $required_objects[] = 'jquery_fonticonpicker';
			$required_objects[] = 'mcfgf_admin';

			$required_objects[] = 'code-editor';
			$required_objects[] = 'codemirror';
			$required_objects[] = 'jshint';
			$required_objects[] = 'csslint';
			$required_objects[] = 'htmlhint';
			
			// $required_objects[] = 'jquery_fonticonpicker';

			//Advanced WP Columns
			// $required_objects[] = 'advanced_wp_columns_handle';

			//Popup Maker
			// $required_objects[] = 'pum-admin-shortcode-ui';

			//Layer Slider WP
			$required_objects[] = 'layerslider-global';
			$required_objects[] = 'layerslider-embed';
		}
		return $required_objects;
	}

	function gform_noconflict_styles($required_objects) {
		if(is_admin()) {
			// 
			// $this->maybe_better_font_awesome_enqueue_scripts($required_objects);
			$this->admin_enqueue_scripts();

			// $required_objects[] = 'better-font-awesome-admin';
			// $required_objects[] = 'fontawesome-iconpicker';
			$required_objects[] = 'mce-view';
			$required_objects[] = 'code-editor';
			
			
			$required_objects[] = 'mcfgf_admin';
			
			// $required_objects[] = 'jquery_fonticonpicker';
			// $required_objects[] = 'jquery_fonticonpicker.theme.gray';

			//Advanced WP Columns
			// $required_objects[] = 'dry_awp_admin_style';

			//Popup Maker
			// $required_objects[] = 'pum-admin-shortcode-ui';

			//Layer Slider WP
			$required_objects[] = 'layerslider-global';
			$required_objects[] = 'layerslider-embed';
			
		}
		return $required_objects;
	}

	function admin_enqueue_scripts() {
		// echo 'admin_enqueue_scripts';
		// die();
		// Register and Enqueue a Stylesheet
		//if(is_admin()) {
			if(function_exists('wp_enqueue_code_editor')) {
				wp_enqueue_code_editor( array( 'type' => 'text/html' ));
			}

		    wp_register_style( 'mcfgf_admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->version);
		    wp_enqueue_style( 'mcfgf_admin' );

		    // wp_register_style( 'mcfgf', plugins_url( 'assets/css/custom.css', __FILE__ ));
		    // wp_enqueue_style( 'mcfgf' );
		    // 
		    wp_register_script( 'mcfgf_admin', plugins_url( "assets/js/admin.js", __FILE__ ), array('jquery'), $this->version);
	    	wp_enqueue_script( 'mcfgf_admin' );

	    	wp_register_style( 'selectize', plugins_url( 'assets/selectize/css/selectize.css', __FILE__ ), array(), $this->version);
		    wp_enqueue_style( 'selectize' );

		    wp_register_style( 'selectize_default', plugins_url( 'assets/selectize/css/selectize.default.css', __FILE__ ), array(), $this->version);
		    wp_enqueue_style( 'selectize_default' );

		    wp_register_script( 'selectize', plugins_url( "assets/selectize/js/selectize.min.js", __FILE__ ), array('jquery'), $this->version);
	    	wp_enqueue_script( 'selectize' );

	    	wp_localize_script( 'mcfgf_admin', 'mcfgf_admin', array(
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			) );

	    	
	    	// wp_register_script( 'mcfgf', plugins_url( "assets/js/custom.js", __FILE__ ), array('jquery'));
	    	// wp_enqueue_script( 'mcfgf' );
			// for magic conversation short code
			if ( self::___page_supports_add_form_button() ) {
				wp_enqueue_script( 'gform_shortcode_ui' );
				wp_enqueue_style( 'gform_shortcode_ui' );
				wp_localize_script( 'gform_shortcode_ui', 'mcfgfShortcodeUIData', array(
					'shortcodes'      => self::_get_shortcodes(),
					'previewNonce'    => wp_create_nonce( 'mcfgf-shortcode-ui-preview' ),

					/**
					 * Allows the enabling (false) or disabling (true) of a shortcode preview of a form
					 *
					 * @param bool $preview_disabled Defaults to true.  False to enable.
					 */
					'previewDisabled' => apply_filters( 'mcfgf_shortcode_preview_disabled', true ),
					'strings'         => array(
						'pleaseSelectAForm'   => esc_html__( 'Please select a form to show in Conversation mode', 'gravityforms' ),
						'errorLoadingPreview' => esc_html__( 'Failed to load the preview for this form.', 'gravityforms' ),
					)
				) );
			}
		//}
	}

	/**
	 * Determines if the "Add Form" button should be added to the page.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @return boolean $display_add_form_button True if the page is supported.  False otherwise.
	 */
	public static function ___page_supports_add_form_button() {
		$is_post_edit_page = in_array( MCFGFP_CURRENT_PAGE, array(
			'post.php',
			'page.php',
			'page-new.php',
			'post-new.php',
			'customize.php',
		) );

		$display_add_form_button = apply_filters( 'magic_conversation_add_form_button', $is_post_edit_page );

		return $display_add_form_button;
	}

	/**
	 * Gets the available shortcode attributes.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @return array $shortcodes Shortcode attributes.
	 */
	public static function _get_shortcodes() {
		if(!class_exists('RGFormsModel')) return;
		$forms             = RGFormsModel::get_forms( 1, 'title' );
		$forms_options[''] = __( 'Select a Form for Magic Conversation', 'gravityforms' );
		foreach ( $forms as $form ) {
			$forms_options[ absint( $form->id ) ] = $form->title;
		}

		$default_attrs = array(
			array(
				'label'       => __( 'Select a form below to add it to your post or page.', 'gravityforms' ),
				'tooltip'     => __( 'Select a form from the list to add it to your post or page.', 'gravityforms' ),
				'attr'        => 'id',
				'type'        => 'select',
				'section'     => 'required',
				'description' => __( "Can't find your form? Make sure it is active.", 'gravityforms' ),
				'options'     => $forms_options,
			),
			array(
				'label'   => __( 'Container Width', 'gravityforms' ),
				'attr'    => 'width',
				'default' => '100%',
				'section' => 'standard',
				'type'    => 'text',
				'tooltip' => __( 'The width of conversation area.', 'gravityforms' )
			),
			array(
				'label'   => __( 'Container Height', 'gravityforms' ),
				'attr'    => 'height',
				'default' => '395px',
				'section' => 'standard',
				'type'    => 'text',
				'tooltip' => __( 'The height of conversation area.', 'gravityforms' )
			),
			// array(
			// 	'label'   => __( 'Display form title', 'gravityforms' ),
			// 	'attr'    => 'title',
			// 	'default' => 'true',
			// 	'section' => 'standard',
			// 	'type'    => 'checkbox',
			// 	'tooltip' => __( 'Whether or not to display the form title.', 'gravityforms' )
			// ),
			// array(
			// 	'label'   => __( 'Display form description', 'gravityforms' ),
			// 	'attr'    => 'description',
			// 	'default' => 'true',
			// 	'section' => 'standard',
			// 	'type'    => 'checkbox',
			// 	'tooltip' => __( 'Whether or not to display the form description.', 'gravityforms' )
			// ),
			// array(
			// 	'label'   => __( 'Enable Ajax', 'gravityforms' ),
			// 	'attr'    => 'ajax',
			// 	'section' => 'standard',
			// 	'type'    => 'checkbox',
			// 	'tooltip' => __( 'Specify whether or not to use Ajax to submit the form.', 'gravityforms' )
			// ),
			array(
				'label'   => 'Tabindex',
				'attr'    => 'tabindex',
				'type'    => 'number',
				'tooltip' => __( 'Specify the starting tab index for the fields of this form.', 'gravityforms' )
			),

		);

		/**
		 * Filters through the shortcode builder actions (ajax, tabindex, form title) for adding a new form to a post, page, etc.
		 *
		 * @since Unknown
		 *
		 * @param array() Array of additional shortcode builder actions.  Empty by default.
		 */
		$add_on_actions = apply_filters( 'mcfgf_shortcode_builder_actions', array() );

		if ( ! empty( $add_on_actions ) ) {
			$action_options = array( '' => __( 'Select an action', 'gravityforms' ) );
			foreach ( $add_on_actions as $add_on_action ) {
				foreach ( $add_on_action as $key => $array ) {
					$action_options[ $key ] = $array['label'];
				}
			}

			$default_attrs[] = array(
				'label'   => 'Action',
				'attr'    => 'action',
				'type'    => 'select',
				'options' => $action_options,
				'tooltip' => __( 'Select an action for this shortcode. Actions are added by some add-ons.', 'gravityforms' )
			);
		}

		$shortcode = array(
			'shortcode_tag' => 'magic-conversation',
			'action_tag'    => '',
			'label'         => 'Magic Conversation for Gravity Forms',
			'attrs'         => $default_attrs,
		);

		$shortcodes[] = $shortcode;

		if ( ! empty( $add_on_actions ) ) {
			foreach ( $add_on_actions as $add_on_action ) {
				foreach ( $add_on_action as $key => $array ) {
					$attrs     = array_merge( $default_attrs, $array['attrs'] );
					$shortcode = array(
						'shortcode_tag' => 'gravityform',
						'action_tag'    => $key,
						'label'         => rgar( $array, 'label' ),
						'attrs'         => $attrs,
					);
				}
			}
			$shortcodes[] = $shortcode;
		}

		return $shortcodes;
	}

	/**
	 * Creates the "Add Form" button.
	 *
	 * @since  Unknown
	 * @access public
	 */
	public static function ___add_form_button() {

		$is_add_form_page = self::___page_supports_add_form_button();
		if ( ! $is_add_form_page ) {
			return;
		}

		// display button matching new UI
		echo '<style>.mcfgf_media_icon{
                background-position: center center;
			    background-repeat: no-repeat;
			    background-size: 16px auto;
			    float: left;
			    height: 16px;
			    margin: 0;
			    text-align: center;
			    width: 16px;
				padding-top:10px;
                }
                .mcfgf_media_icon:before{
                color: #999;
			    padding: 7px 0;
			    transition: all 0.1s ease-in-out 0s;
                }
                .wp-core-ui a.mcfgf_media_link{
                 padding-left: 0.4em;
                }
             </style>
              <a href="#" class="button mcfgf_media_link" id="add_magic_conversation" title="' . esc_attr__( 'Add Magic Conversation', 'gravityforms' ) . '"><div class="mcfgf_media_icon svg" style="background-image: url(\'' . self::___get_admin_icon_b64() . '\')"><br /></div><div style="padding-left: 20px;">' . esc_html__( 'Add Magic Conversation', 'gravityforms' ) . '</div></a>';
	}

	public static function ___get_admin_icon_b64( $color = false ) {
		return  plugins_url( 'assets/img/icon.png', __FILE__ );
	}
	/**
	 * Displays the popup to insert a form to a post/page.
	 *
	 * @since  Unknown
	 * @access public
	 */
	public static function ___add_mce_popup() {
		?>
		<script>
			function InsertMagicConversation() {
				var form_id = jQuery("#add_form_id").val();
				if (form_id == "") {
					alert(<?php echo json_encode( __( 'Please select a form', 'gravityforms' ) ); ?>);
					return;
				}

				var form_name = jQuery("#add_form_id option[value='" + form_id + "']").text().replace(/[\[\]]/g, '');
				var display_title = jQuery("#display_title").is(":checked");
				var display_description = jQuery("#display_description").is(":checked");
				var ajax = jQuery("#gform_ajax").is(":checked");
				var title_qs = !display_title ? " title=\"false\"" : "";
				var description_qs = !display_description ? " description=\"false\"" : "";
				var ajax_qs = ajax ? " ajax=\"true\"" : "";

				window.send_to_editor("[gravityform id=\"" + form_id + "\" name=\"" + form_name + "\"" + title_qs + description_qs + ajax_qs + "]");
			}
		</script>

		<div id="select_magic_conversation_gravity_form" style="display:none;">

			<div id="mcfgf-shortcode-ui-wrap" class="wrap <?php echo GFCommon::get_browser_class() ?>">

				<div id="mcfgf-shortcode-ui-container"></div>

			</div>


		</div>

		<?php
	}

	/**
	 * Displays the shortcode editor.
	 *
	 * @since   Unknown
	 * @access  public
	 *
	 * @used-by GFForms::init()
	 * @used    GFForms::get_view()
	 *
	 * @return void
	 */
	public static function ___action_print_media_templates() {

		echo self::___get_view( 'edit-shortcode-form' );
	}

	/**
	 * Gets the view and loads the appropriate template.
	 *
	 * @since   Unknown
	 * @access  public
	 *
	 * @used-by GFForms::action_print_media_templates()
	 *
	 * @param string $template The template to be loaded.
	 *
	 * @return mixed The contents of the template file.
	 */
	public static function ___get_view( $template ) {

		if ( ! file_exists( $template ) ) {

			$template_dir = dirname(__FILE__) . '/templates/';
			$template     = $template_dir . $template . '.tpl.php';

			if ( ! file_exists( $template ) ) {
				return '';
			}
		}

		ob_start();
		include $template;

		return ob_get_clean();
	}
	//determine if full screen mode is enabled
	// function is_mobile_full_screen_enabled() {
	// 	return wp_is_mobile() && rgar( GFAPI::get_form( $item->id ) , 'mcfgf_enable_conversation_mode' );
	// }

	// function body_class($classes){
	// 	if($this->is_mobile_full_screen_enabled()) {
	// 		return array_merge( $classes, array( 'mcfgf-mobile-fullscreen' ) );
	// 	}
	// 	else {
	// 		return $classes;
	// 	}
	// }

	//hook for enable conditional logic for gravity forms.
	function gform_has_conditional_logic($has_conditional_logic, $form) {
		// $has_conditional_logic = $has_conditional_logic || rgar( $form, 'mcfgf_rewind_conditional_logic' ) == 1;
		global $mcfgf_is_global;
		return $has_conditional_logic || rgar( $form , 'mcfgf_enable_conversation_mode' ) || $mcfgf_is_global;//$has_conditional_logic;
	}

	// disable html5
	// function option_rg_gforms_enable_html5($value, $option){
	// 	return false;
	// }

	function gform_submit_button($button_input, $form){
		global $mcfgf_is_global;
		global $mcfgf_settings_basics;
		$formid = intval($mcfgf_settings_basics['conversation_gravity_form_id']);

		$is_the_form_conversation = false;//rgar( $form , 'mcfgf_enable_conversation_mode' ) || ($mcfgf_is_global && $formid == $form['id']);

		if($is_the_form_conversation) {
			$re = '/if\(.+?checkValidity.+?{(.+?)}/m';
			$subst = '$1';
			// echo '<pre>';
			// echo $form_string; 
			// echo '</pre>'; die();
			$button_input = preg_replace($re, $subst, $button_input);
		}

		return $button_input;
		
	}

	//filter form tag
	function gform_form_tag($form_tag, $form)
	{
		$mcfgf_enable_conversation_mode = false;// rgar( $form , 'mcfgf_enable_conversation_mode' );
		$isFree = !file_exists(dirname(__FILE__).'/license.php');
		if($isFree) {
			global $mcfgf_settings_basics;
			$formid = intval($mcfgf_settings_basics['conversation_gravity_form_id']);
			if($form['id'] != $formid) {
				return $form_tag;
			}
		}

		if($mcfgf_enable_conversation_mode) {

			$mcfgf_conversation_confirmation_message_mode = $this->get_mcfgf_conversation_confirmation_message_mode($form);

			$mcfgf_conversation_box_height = $this->get_mcfgf_conversation_box_height($form);
			// $form_string = str_replace('if(!is_postback){return;}', 'return;', $form_string);
			$form_tag = str_replace('>', ' style="opacity:0;height:'.$mcfgf_conversation_box_height.';" mcfgf_confirmation_message_mode="'.$mcfgf_conversation_confirmation_message_mode.'">', $form_tag);

			$form_tag = $form_tag.'<input name="mcfgf_mark" id="mcfgf_mark" type="hidden" value="mcfgf">';
		}
		return $form_tag;
	}

	//check submission prevent submit really.
	function gform_pre_submission($form) {
		if(isset($_GET['action']) && $_GET['action']=='gf_button_get_form') {
			echo 'mcfgf';
			die();
		}
		else if(isset($_POST['mcfgf_mark']) && $_POST['mcfgf_mark']=='mcfgf') {
			echo 'mcfgf';
			die();
		}
	}

	function gform_pre_validation($form) {
		return $this->_force_gform_all_required($form);
	}

	function gform_pre_render($form) {
		return $this->_force_gform_all_required($form);
	}

	function _force_gform_all_required($form)
	{
		$mcfgf_enable_conversation_mode = false;// rgar( $form , 'mcfgf_enable_conversation_mode' );
		if($mcfgf_enable_conversation_mode) {
			foreach ( $form['fields'] as &$field ) {

				if ( !in_array($field->type, array('hidden', 'html', 'page', 'section', 'product', 'total'))) {
					
					if($field->visibility !== 'hidden') {
						$field->isRequired = true;
					}
			        
			    }
		    }
		}
		return $form;
	}

	static function _gf_button_get_form() {
		$form_id = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : 0;
		$is_embed = isset( $_GET['embed'] ) ? absint( $_GET['embed'] ) : 0;
		// Render an AJAX-enabled form.
		// https://www.gravityhelp.com/documentation/article/embedding-a-form/#function-call
		// 
		$mcfgf_options = get_option('mcfgf_conversation_generator', false);
		$avatar_robot = $mcfgf_options['avatar_robot'];
		
		//Init Side Form
		require_once (dirname(__FILE__).'/sideform.php');
		$sideform = new SideForm_MagicConversationForGravityForms();
		if($is_embed) {
			$sideform->showEmbedForm($form_id, $avatar_robot);
		}
		else {
			$sideform->showSideFormButton($form_id, $avatar_robot);
		}
		
		die();
	}

	static function _yakker_get_gf($form_id) {
		// $form_id = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : 0;
		$is_embed = isset( $_GET['embed'] ) ? absint( $_GET['embed'] ) : 0;
		// Render an AJAX-enabled form.
		// https://www.gravityhelp.com/documentation/article/embedding-a-form/#function-call
		// 
		$mcfgf_options = get_option('mcfgf_conversation_generator', false);
		
		
		//Init Side Form
		require_once (dirname(__FILE__).'/yakker.php');
		
		die();
	}

	// function wp_ajax_nopriv_yakker_get_gf() {
	// 	$this->_yakker_get_gf();
	// }

	function allowed_http_origins($origins) {
	    $origins[] = 'http://localhost:3000';
	    $origins[] = 'http://localhost:4000';
	    return $origins;
	}

	function wp_ajax_yakker_get_woo_products() {
		require_once (dirname(__FILE__).'/woo_products.php');
		die();
	}

	// function wp_ajax_yakker_get_gf() {
	// 	$this->_yakker_get_gf();
	// }

	static function _url_preview() {
		//Init Side Form
		require_once (dirname(__FILE__).'/url_preview.php');
		die();
	}

	function wp_ajax_nopriv_mc_url_preview() {
		$this->_url_preview();
	}

	function wp_ajax_mc_url_preview() {
		$this->_url_preview();
	}

	function wp_ajax_nopriv_gf_button_get_form() {
		$this->_gf_button_get_form();
	}

	function wp_ajax_gf_button_get_form() {
		$this->_gf_button_get_form();
	}

	function gform_get_form_filter($form_string, $form) {
		$mcfgf_enable_conversation_mode = false; //rgar( $form , 'mcfgf_enable_conversation_mode' );
		if($mcfgf_enable_conversation_mode) {
			// $form_string = str_replace('if(!is_postback){return;}', 'return;', $form_string);

			$form_string = str_replace('setTimeout(function(){', 'setTimeout(function(){return;', $form_string);

			
		}

		if(!wp_is_mobile()) {
			return $form_string;
		}

		$mcfgf_enable_fullscreen_on_mobile = rgar( $form , 'mcfgf_enable_fullscreen_on_mobile' );

		// var_dump($mcfgf_enable_fullscreen_on_mobile);
		// die();

		if($mcfgf_enable_fullscreen_on_mobile) {
			$re = '/(<div\s+class=[\'|"][^"|\']+)(["|\']\s+id=["|\']gform_wrapper_)/';
			$subst = '$1 mcfgf-mobile-fullscreen$2';
			// echo '<pre>';
			// echo $form_string; 
			// echo '</pre>'; die();
			$form_string = preg_replace($re, $subst, $form_string);

			// add_filter('body_class', 'mcfgf_add_gravity_classes');
			// function mcfgf_add_gravity_classes($classes){
			// 	$classes[] = 'mcfgf-mobile-fullscreen';
			// 	return $classes;
			// }
		}



		return $form_string;
	}

	//force ajax for conversation mode enabled form
	function gform_form_args($form_args) {
		$form_id = $form_args['form_id'];
		$mcfgf_enable_conversation_mode = false;//rgar( GFAPI::get_form( $form_id ) , 'mcfgf_enable_conversation_mode' );
		if($mcfgf_enable_conversation_mode) {
			$form_args['ajax'] =  true;
		}

		$form = GFAPI::get_form( $form_id );

		// echo json_encode($form);
		$confirmation_string = json_encode($form['confirmations']);

		if(strpos($confirmation_string, "open-magic-conversation") !== false) {
			$form_args['ajax'] =  true;
		}


		return $form_args;
	}

	function get_mcfgf_conversation_box_height($form)
	{
		$mcfgf_conversation_box_height = rgar( $form , 'mcfgf_conversation_box_height' );
		if(empty($mcfgf_conversation_box_height)) {
			$mcfgf_conversation_box_height = '600px';
		}
		else if(is_numeric($mcfgf_conversation_box_height)) {
			$mcfgf_conversation_box_height = $mcfgf_conversation_box_height.'px';
		}
		return $mcfgf_conversation_box_height;
	}

	function get_mcfgf_conversation_confirmation_message_mode($form)
	{
		$mcfgf_conversation_confirm_message_mode = rgar( $form , 'mcfgf_conversation_confirm_message_mode' );
		if(empty($mcfgf_conversation_confirm_message_mode)) {
			$mcfgf_conversation_confirm_message_mode = '2';
		}

		return $mcfgf_conversation_confirm_message_mode;
	}
	
	function ____gform_form_settings($settings, $form) {
		$mcfgf_enable_conversation_mode_checked = '';
		if ( rgar( $form, 'mcfgf_enable_conversation_mode' ) ) {
			$mcfgf_enable_conversation_mode_checked = 'checked="checked"';
		}
		
		$settings['Conversation Form']['mcfgf_enable_conversation_mode'] = '
        <tr>
            <th><label for="mcfgf_enable_conversation_mode">Conversation mode '.gform_tooltip( 'mcfgf_enable_conversation_mode', '', true ).'</th>
            <td>
                <input type="checkbox" id="mcfgf_enable_conversation_mode" name="mcfgf_enable_conversation_mode" value="1" ' . $mcfgf_enable_conversation_mode_checked . '/>
                <label for="mcfgf_enable_conversation_mode">' . __( 'Enable conversation mode', 'mcfgf' ) . '</label>
            </td>
        </tr>';

        $mcfgf_enable_fullscreen_on_mobile_checked = '';
		if ( rgar( $form, 'mcfgf_enable_fullscreen_on_mobile' ) ) {
			$mcfgf_enable_fullscreen_on_mobile_checked = 'checked="checked"';
		}

        $settings['Conversation Form']['mcfgf_enable_fullscreen_on_mobile'] = '
        <tr>
            <th><label for="mcfgf_enable_fullscreen_on_mobile">Mobile Fullscreen '.gform_tooltip( 'mcfgf_enable_fullscreen_on_mobile', '', true ).'</th>
            <td>
                <input type="checkbox" id="mcfgf_enable_fullscreen_on_mobile" name="mcfgf_enable_fullscreen_on_mobile" value="1" ' . $mcfgf_enable_fullscreen_on_mobile_checked . '/>
                <label for="mcfgf_enable_fullscreen_on_mobile">' . __( 'Show conversation fullscreen in mobile device', 'mcfgf' ) . '</label>
            </td>
        </tr>';

        $isFree = !file_exists(dirname(__FILE__).'/license.php');

        if(!$isFree) {
        	$mcfgf_enable_conversation_mode_conditional_message = '';
			if ( rgar( $form, 'mcfgf_conversation_confirm_message_mode' )==1 ) {
				$mcfgf_enable_conversation_mode_conditional_message = 'checked="checked"';
			}

			
			$settings['Conversation Form']['mcfgf_enable_conversation_mode_conditional_message'] = '
	        <tr>
	            <th><label>Confirmation '.gform_tooltip( 'mcfgf_enable_conversation_mode_conditional_message', '', true ).'</th>
	            <td>
	                <input type="radio" id="mcfgf_enable_conversation_mode_conditional_message" name="mcfgf_conversation_confirm_message_mode" value="1" ' . $mcfgf_enable_conversation_mode_conditional_message . '/>
	                <label for="mcfgf_enable_conversation_mode_conditional_message">' . __( 'Enable conversation conditional confirmation message configured via the HTML fields.', 'mcfgf' ) . '</label>
	            </td>
	        </tr>';


	        $mcfgf_enable_conversation_mode_submit_result = '';
			if ( rgar( $form, 'mcfgf_conversation_confirm_message_mode' ) == 2 ) {
				$mcfgf_enable_conversation_mode_submit_result = 'checked="checked"';
			}

			if(empty($mcfgf_enable_conversation_mode_conditional_message)) {
				$mcfgf_enable_conversation_mode_submit_result = 'checked="checked"';
			}

			
			$settings['Conversation Form']['mcfgf_enable_conversation_mode_submit_result'] = '
	        <tr>
	            <th><label>'.gform_tooltip( 'mcfgf_enable_conversation_mode_submit_result', '', true ).'</th>
	            <td>
	                <input type="radio" id="mcfgf_enable_conversation_mode_submit_result" name="mcfgf_conversation_confirm_message_mode" value="2" ' . $mcfgf_enable_conversation_mode_submit_result . '/>
	                <label for="mcfgf_enable_conversation_mode_submit_result">' . __( 'Show default confirmation message as normal form submission.', 'mcfgf' ) . '</label>
	            </td>
	        </tr>';
        }


        // Conversation rewind conditional logic.
		
		if ( rgar( $form, 'mcfgf_rewind_conditional_logic' ) == 1 ) {
			$mcfgf_enable_rewind_conditional_checked = 'checked="checked"';
		}

		$settings['Conversation Form']['mcfgf_rewind_conditional_logic'] = '
        <tr>
            <th>
                ' . __( 'Rewind Conversation', 'mcfgf' )  . ' ' . gform_tooltip( 'mcfgf_rewind_conditional_logic', '', true ).
            '</th>
            <td>'.
            	'<script type="text/javascript">
            		(function($) {
            			console.log("gform_load_form_settings001");

	            		// $(document).ready(function(){
							//hook forms settings in admin
							jQuery(document).bind("gform_load_form_settings", function(e, form){
								console.log("gform_load_form_settings", e, form);
						    	window.mcfgf_SetRewindConditionalLogic = function(isChecked) {
						    		form.mcfgfRewindConditionalLogic = isChecked ? {conditionalLogic: new ConditionalLogic()} : {};
								}
								if (form.mcfgf_rewind_conditional_logic==1) {
									console.log("auto trigger new logic", form.mcfgf_rewind_conditional_logic);
									// mcfgf_SetRewindConditionalLogic(true);
									ToggleConditionalLogic(false, "mcfgf_rewind");
									// $("#mcfgf_rewind_conditional_logic").trigger("click");
								}
							});
							console.log("gform_load_form_settings002");

							window.McfgfConditionConditionalObject = function(object, objectType){
								
								if(objectType == "mcfgf_rewind") {
									console.log("gform_conditional_object", object, objectType, form.mcfgfRewindConditionalLogic);
									return form.mcfgfRewindConditionalLogic;
								}
								else {
									return object;
								}
							}

							if(gform) {
								gform.addFilter( "gform_conditional_object", "McfgfConditionConditionalObject" );
							}
						// });
					})(jQuery);
            	</script>'.
                '<input type="checkbox" id="mcfgf_rewind_conditional_logic" name="mcfgf_rewind_conditional_logic" value="1" onclick="mcfgf_SetRewindConditionalLogic(this.checked); ToggleConditionalLogic(false, \'mcfgf_rewind\');" onkeypress="mcfgf_SetRewindConditionalLogic(this.checked); ToggleConditionalLogic(false, \'mcfgf_rewind\');"' . $mcfgf_enable_rewind_conditional_checked . ' />
                <label for="mcfgf_rewind_conditional_logic" class="inline">' . ' ' . __( 'Enable Rewind Conversation after confirmation', 'mcfgf' ) . '</label>
            </td>
         </tr>
         <tr>
            <td colspan="2">

	            <div id="mcfgf_rewind_conditional_logic_container" class="gf_animate_sub_settings" style="display:none;">
	                    <!-- content dynamically created from js.php -->
	             </div>

            </td>
        </tr>';

  //       $mcfgf_conversation_box_height = $this->get_mcfgf_conversation_box_height($form);
  //       $settings['Conversation Form']['mcfgf_conversation_box_height'] = '
  //       <tr>
  //           <th>
  //               <label for="mcfgf_conversation_box_height" style="display:block;">Conversation Area Height <a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_css_class" title="<h6>Conversation Area Height</h6>Enter the height in px you would like to show the conversation."><i class="fa fa-question-circle"></i></a></label>
		// </th>
		// <td>
		// 	<input type="text" id="mcfgf_conversation_box_height" name="mcfgf_conversation_box_height" class="fieldwidth-3" value="' . $mcfgf_conversation_box_height . '">
  //           </td>
  //       </tr>';

    	return $settings;
	}

	function gform_tooltips($tooltips) {
		$url_base = Settings_MagicConversationForGravityForms::$base_url; 
		$domain = Settings_MagicConversationForGravityForms::$domain;
		$tooltips['mcfgf_enable_conversation_mode'] = 'Check to enable conversation mode'; //'check it to enable conversation mode, learn more about it in <a target="_blank" href="'.$url_base.'">'.$domain.'</a>';

		$tooltips[$this->editor_id] = "<h6>Conversation Question</h6>the question for conversation.";
		// $tooltips['mcfgf_field_alias'] = "<h6>Field Alias</h6>Field Alias is the merged tag that will be replace to field value.";
		// $tooltips['mcfgf_field_id'] = "<h6>Field ID</h6>Field ID is the merged tag that will be replace to field value.";
		$tooltips['mcfgf_field_delay_next'] = "<h6>Delayed Response Control</h6>Set a delay time for the robot to move to the next field. This simulates a human response.";
		$tooltips['mcfgf_value_on_back'] = "<h6>Value on Click Previous Button</h6>Change the value automatically when user clicks Previous button.";
		$tooltips['mcfgf_enable_url_redirect'] = "<h6>Confirmation Override</h6>Check to override the confirmation settings to redirect to the url specified in the value field of choices on confirmation.";
		$tooltips['mcfgf_enable_local_validation'] = "<h6>Local Validation</h6>Check to speed up conversation by do validation on client side, or a server side validation will occur by default.";

		$tooltips['mcfgf_enable_options_filter'] = "<h6>Options Filter</h6>Check to enable options filter to help user locate option by enter a keyword.";

		$tooltips['mcfgf_enable_woocommerce_product'] = "<h6>WooCommerce Product Picker</h6>Check to make this field as a product picker. each choice value should be a product id. User will be redirected to the Checkout page after form submission.";

		$tooltips['mcfgf_woocommerce_product_template_normal'] = "<h6>Template for Normal Status</h6>Specify a html template to show product items in normal status.";


		$tooltips['mcfgf_woocommerce_product_template_selected'] = "<h6>Template for Checked Status</h6>Specify a html template to show product items in checked status.";

   		return $tooltips;
	}

	// save your custom form setting
	function gform_pre_form_settings_save($form) {
		$form['mcfgf_enable_conversation_mode'] = rgpost( 'mcfgf_enable_conversation_mode' );
		$form['mcfgf_enable_fullscreen_on_mobile'] = rgpost( 'mcfgf_enable_fullscreen_on_mobile' );
		
		$form['mcfgf_conversation_confirm_message_mode'] = rgpost( 'mcfgf_conversation_confirm_message_mode' );
		$form['mcfgf_rewind_conditional_logic'] = rgpost( 'mcfgf_rewind_conditional_logic' );
		
		$form['mcfgf_conversation_box_height'] = rgpost( 'mcfgf_conversation_box_height' );
    	return $form;
	}

	function gform_form_list_columns($columns) {
		$columns['mcfgf_enable_conversation_mode'] = esc_html__( 'Conversation Permalink', 'mcfgf' );
		return $columns;
	}

	function gform_form_list_column_mcfgf_enable_conversation_mode($item) {
		$url = $this->___get_conversation_permalink($item->id);
		echo '<a href="'.$url.'" title="'.$url.'" target="_blank">'.$url.'</a>';
	}


	function wp_footer_300() {
		//if($GLOBALS['mtfcf7p_is_valid_license_key']) {
		//<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.0.0/imagesloaded.pkgd.min.js"></script>
		?>
			
		<?php 
		//}
	}

	function gform_field_standard_settings( $position, $form_id ) {
	    //create settings on position 25 (right after Field Label)
	    if ( false && $position == 25 ) {
	    	require_once (dirname(__FILE__).'/api.php');
			$api = new API_MagicConversationForGravityForms();
	        ?>
	        <li class="<?php echo $this->editor_id; ?> field_setting" style="display: list-item; ">
	        	<style>
	        	.mcfgf_questions-default-questions {
					padding: 15px;
					background: #efefef;
					margin:10px 15px;
	        	}
	        	/*#mcfgf_questions-enable-custom-questions-2, #mcfgf_questions-enable-custom-questions-1 {
	        		margin-bottom: 0px;
	        		min-height: 23px;
	        		line-height: 23px;
	        	}*/
	        	</style>
	        	<script type="text/javascript">
	        		//mcfgf_default_questions = <?php echo json_encode($api->getAllQuestions()); ?>
	        	</script>

	            <label for="field_admin_label" class="section_label">
	                <?php _e( 'Conversation Questions', 'mcfgf' ); ?>
	                <?php gform_tooltip( $this->editor_id ) ?>
	            </label>
	            
	            <div class="<?php echo $this->editor_id; ?>-enable-custom-questions-box">
	            	<input type="radio" value="1" checked="checked" name="<?php echo $this->editor_id; ?>-enable-custom-questions" id="<?php echo $this->editor_id; ?>-enable-custom-questions-1" /> Use <a href="<?php echo site_url('/wp-admin/edit.php?post_type=mc-question') ?>" target="blank">Questions Library</a>.
	            </div>
	            <div class="<?php echo $this->editor_id; ?>-default-questions">
	            	<?php //echo $api->getQuestionsForFieldLabel('Name'); ?>
	            </div>
	            <div class="<?php echo $this->editor_id; ?>-enable-custom-questions-box">
	            	<input type="radio" value="2" name="<?php echo $this->editor_id; ?>-enable-custom-questions" id="<?php echo $this->editor_id; ?>-enable-custom-questions-2" /> Custom
	            </div>
        		<div class="<?php echo $this->editor_id; ?>-custom-questions" style="display: none;">
        			<?php 
					// wp_enqueue_media();
	            	if(!(isset($GLOBALS['mcfgfp_disable_wp_editor']) && $GLOBALS['mcfgfp_disable_wp_editor'])) {
		            	wp_editor( '', $this->editor_id, array(
							'media_buttons' => true,
							'textarea_name' => $this->editor_id,
							'editor_class' => 'mcfgf-wp_editor wysiwyg_exclude'
						)); 
		            }
		            else {
					?>
        			<textarea id="<?php echo $this->editor_id; ?>" class="fieldwidth-3 fieldheight-2 wysiwyg_exclude"></textarea>
        			<?php } ?>
        		</div>
	        </li>
	        <?php
	    }
	}

	function gform_editor_js() {
		if(!(isset($GLOBALS['mcfgfp_disable_wp_editor']) && $GLOBALS['mcfgfp_disable_wp_editor'])) {
			$this->gform_editor_js_textarea();
		}
		else {
			$this->gform_editor_js_textarea();
		}
	}

	function gform_editor_js_textarea(){
		
	    ?>
	    <script type='text/javascript'>
	        //adding setting to fields of type "text"

	        <?php if(!(isset($GLOBALS['mcfgfp_disable_wp_editor']) && $GLOBALS['mcfgfp_disable_wp_editor'])) : ?>
			var editor_id = '<?php echo $this->editor_id ?>' //'mm_tooltip_text_value';
	        var field_name = "<?php echo $this->editor_id ?>";
	        <?php endif ?>

	        for (var key in fieldSettings) {
				if (fieldSettings.hasOwnProperty(key)) {
				  	fieldSettings[key] += ", .<?php echo $this->editor_id; ?>";
				  	fieldSettings[key] += ", .mcfgf_field_alias";
				  	fieldSettings[key] += ", .mcfgf_field_id";
				  	fieldSettings[key] += ", .mcfgf_field_delay_next";
				  	fieldSettings[key] += ", .mcfgf_value_on_back";
				  	fieldSettings[key] += ", .mcfgf_enable_url_redirect";
				  	fieldSettings[key] += ", .mcfgf_enable_local_validation";
					fieldSettings[key] += ", .mcfgf_enable_options_filter";
				  	fieldSettings[key] += ", .mcfgf_woocommerce_product_template_normal";
				  	fieldSettings[key] += ", .mcfgf_woocommerce_product_template_selected";
				  	fieldSettings[key] += ", .mcfgf_enable_limit_max_column";

				}
			}

			function mcfgf_init_enable_custom() {
				if(jQuery('input[name=<?php echo $this->editor_id; ?>-enable-custom-questions]:checked').val()=='1') {
	        		jQuery('.<?php echo $this->editor_id; ?>-default-questions').show();
	        		jQuery('.<?php echo $this->editor_id; ?>-custom-questions').hide();
	        	}
	        	else {
	        		jQuery('.<?php echo $this->editor_id; ?>-default-questions').hide();
	        		jQuery('.<?php echo $this->editor_id; ?>-custom-questions').show();
	        	}
	        	// alert(jQuery('input[name=<?php echo $this->editor_id; ?>-enable-custom-questions]:checked').val());
			}
	        //console.log(fieldSettings);

	        //binding to the load field settings event to initialize the checkbox
	        

	        jQuery('#<?php echo $this->editor_id; ?>').bind('input propertychange', function() {
	        	SetFieldProperty('<?php echo $this->editor_id; ?>', jQuery(this).val());
				// console.log('update content on change2021',field_name, jQuery(this).val());
			});

	        jQuery('#mm_delay_next_from').bind('input propertychange', function() {
	        	SetFieldProperty('mm_delay_next_from', jQuery(this).val());
			});

			jQuery('#mm_delay_next_to').bind('input propertychange', function() {
	        	SetFieldProperty('mm_delay_next_to', jQuery(this).val());
			});

			jQuery('#mm_value_on_back').bind('input propertychange', function() {
	        	SetFieldProperty('mm_value_on_back', jQuery(this).val());
			});

			jQuery('#field_mcfgf_woocommerce_product_template_normal').bind('input propertychange', function() {
	        	SetFieldProperty('mcfgf_woocommerce_product_template_normal', jQuery(this).val());
			});

			jQuery('#field_mcfgf_woocommerce_product_template_selected').bind('input propertychange', function() {
	        	SetFieldProperty('mcfgf_woocommerce_product_template_selected', jQuery(this).val());
			});


			

	        jQuery('#<?php echo $this->editor_id; ?>-enable-custom-questions-1, #<?php echo $this->editor_id; ?>-enable-custom-questions-2').bind('change', function() {
	        	SetFieldProperty('<?php echo $this->editor_id; ?>_enable_custom', jQuery('input[name=<?php echo $this->editor_id; ?>-enable-custom-questions]:checked').val()=='2');
	        	mcfgf_init_enable_custom();
			});


			jQuery(document).bind("gform_load_field_settings", function(event, field, form){
	        	
	        	<?php if(!(isset($GLOBALS['mcfgfp_disable_wp_editor']) && $GLOBALS['mcfgfp_disable_wp_editor'])) : ?>
	        	if(window.tinymce) {
	        		tinymce.remove(tinyMCE.get(editor_id));
	        	}
	        	<?php endif ?>

	        	var currentVaue = field["<?php echo $this->editor_id; ?>"];
	        	jQuery("#<?php echo $this->editor_id; ?>").val(currentVaue);

	        	var key = field.label.toLowerCase();
	    //     	if(mcfgf_default_questions && mcfgf_default_questions[key]) {
	    //     		jQuery('.<?php echo $this->editor_id; ?>-default-questions').html('<ul><li>'+mcfgf_default_questions[key].join('</li><li>')+'</li></ul>');
	    //     	}
	    //     	else {
					// jQuery('.<?php echo $this->editor_id; ?>-default-questions').html('');
	    //     	}
	            
	        	jQuery('#<?php echo $this->editor_id; ?>-enable-custom-questions-1').attr('checked', field.mcfgf_questions_enable_custom != true);
	        	jQuery('#<?php echo $this->editor_id; ?>-enable-custom-questions-2').attr('checked', field.mcfgf_questions_enable_custom == true).trigger('change');

	        	jQuery('#mm_delay_next_from').val(field.mm_delay_next_from || '0');
	        	jQuery('#mm_delay_next_to').val(field.mm_delay_next_to || '0');
				jQuery('#mm_value_on_back').val(field.mm_value_on_back || '');

				jQuery('#field_mcfgf_enable_url_redirect').attr('checked', field.mcfgf_enable_url_redirect == true);

				jQuery('#field_mcfgf_enable_local_validation').attr('checked', field.mcfgf_enable_local_validation == true);
				jQuery('#field_mcfgf_enable_options_filter').attr('checked', field.mcfgf_enable_options_filter == true);

				if(['radio', 'select'].indexOf(field.type) === -1) {
					jQuery('#field_mcfgf_enable_url_redirect').parent().parent().hide();
					jQuery('#field_mcfgf_enable_local_validation').parent().parent().hide();
					jQuery('#field_mcfgf_enable_options_filter').parent().parent().hide();
				} else {
					jQuery('#field_mcfgf_enable_url_redirect').parent().parent().show();
					jQuery('#field_mcfgf_enable_local_validation').parent().parent().show();
					jQuery('#field_mcfgf_enable_options_filter').parent().parent().show();
				}

				jQuery('#field_mcfgf_enable_woocommerce_product').attr('checked', field.mcfgf_enable_woocommerce_product == true);

				jQuery('#field_mcfgf_enable_limit_max_column').attr('checked', field.mcfgf_enable_limit_max_column == true);

				if(field["mcfgf_num_limit_max_column"]) {
	            	jQuery("#field_mcfgf_num_limit_max_column").val(field["mcfgf_num_limit_max_column"] || '');
	            }


				jQuery('#field_mcfgf_woocommerce_product_template_normal').val(field.mcfgf_woocommerce_product_template_normal || '');

				jQuery('#field_mcfgf_woocommerce_product_template_selected').val(field.mcfgf_woocommerce_product_template_selected || '');

				


				if(['radio', 'select'].indexOf(field.type) === -1) {
					jQuery('#field_mcfgf_enable_woocommerce_product').parent().parent().hide();
				} else {
					jQuery('#field_mcfgf_enable_woocommerce_product').parent().parent().show();
				}

	        	if(field.inputs) {
	        		var visibleInputs = field.inputs.filter(function(input){
	        			return !input.isHidden;
	        		});
	        		var aliass = visibleInputs.map(function(input){
	        			console.log('input', input);
	        			return '{mc_'+form.id+'_'+ (input.customLabel || input.label)+'}';
	        		}).join(' ');
	        		var ids = visibleInputs.map(function(input){
	        			console.log('input', input);
	        			return '{mc_'+form.id+'_'+input.id+'}';
	        		}).join(' ');

	        		jQuery('#mcfgf_field_alias').html(aliass);
		        	jQuery('#mcfgf_field_id').html(ids);
	        	}
	        	else {
	        		jQuery('#mcfgf_field_alias').html('{mc_'+form.id+'_'+field.label+'}');
		        	jQuery('#mcfgf_field_id').html('{mc_'+form.id+'_'+field.id+'}');
	        	}
	        	


	        });



			jQuery('.choices_setting')
                .on('input propertychange', '.field-choice-mcfgf-image-url', function () {
                    var $this = jQuery(this);
                    var i = $this.closest('li.field-choice-row').data('index');
 
                    field = GetSelectedField();
                    field.choices[i].mcfgfImageUrl = $this.val();
                    console.log('gform_append_field_choice_option', field.choices[i], i);
                });

	        // add image pikcer for radio choice
			gform.addFilter('gform_append_field_choice_option', function (str, field, i) {
				// console.log('gform_append_field_choice_option', field);
	            if (field.type != 'radio' && field.type != 'checkbox' && field.type != 'poll' && field.type != 'quiz' && field.type != 'option' && field.type != 'post_tags' && field.type != 'post_custom_field' && field.type != 'product') {
	                return '';
	            }

	            // For fields with multiple input types, we continue only if checkbox or radio
	            if (field.type == 'poll' || field.type == 'quiz' || field.type == 'option' || field.type == 'post_tags' || field.type == 'post_custom_field' || field.type == 'product') {
	                if (field.inputType != 'checkbox' && field.inputType != 'radio'){
	                    return '';
	                }
	            }

	            var inputType = GetInputType(field);
	            var imageUrl = field.choices[i].mcfgfImageUrl ? field.choices[i].mcfgfImageUrl : '';

	            var bh = imageUrl ? "<img class='mcfgf-image-preview' src='"+imageUrl+"' width='20' height='20' style='max-height: 20px; width: 20px;' />" : "<i class=\"fa fa-picture-o\"></i>";
	            
	            return "<button onclick='mcfgf_open_upload_image_modal(this)' class=\"button mcfgf_upload_image_button\">"+bh+"</button><input type='hidden' name='mcfgfImageUrl' class='field-choice-mcfgf-image-url' value='"+imageUrl+"'>";

	            // return "<div class='mcfgf-image-preview-wrapper'><img class='mcfgf-image-preview' src='"+imageUrl+"' width='20' height='20' style='max-height: 20px; width: 20px;'></div><button onclick='mcfgf_open_upload_image_modal(this)' class=\"button mcfgf_upload_image_button\"><i class=\"fa fa-picture-o\"></i></button><input type='hidden' name='mcfgfImageUrl' class='field-choice-mcfgf-image-url' value='"+imageUrl+"'>";
	        });

			<?php if(!(isset($GLOBALS['mcfgfp_disable_wp_editor']) && $GLOBALS['mcfgfp_disable_wp_editor'])) : ?>
			jQuery('#'+editor_id).bind('input propertychange', function() {
	        	SetFieldProperty(field_name, jQuery(this).val());
			});

			jQuery('#field_mcfgf_num_limit_max_column').bind('change propertychange', function() {
	        	SetFieldProperty('mcfgf_num_limit_max_column', jQuery(this).val());
			});

			var editor_mcfgf_woocommerce_product_template_selected = null;
			var editor_mcfgf_woocommerce_product_template_normal = null;
			jQuery("#field_settings").on("click", 'a[href="#mcfgf_tab_yakker"]', function(){
				console.log('mcfgf_tab_yakker clicked');
				if(editor_mcfgf_woocommerce_product_template_selected!=null) {
					editor_mcfgf_woocommerce_product_template_selected.codemirror.getDoc().setValue(jQuery('#field_mcfgf_woocommerce_product_template_selected').val());
					editor_mcfgf_woocommerce_product_template_normal.codemirror.getDoc().setValue(jQuery('#field_mcfgf_woocommerce_product_template_normal').val());
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
					editor_mcfgf_woocommerce_product_template_selected = wp.codeEditor.initialize( 'field_mcfgf_woocommerce_product_template_selected', editorSettings );
					editor_mcfgf_woocommerce_product_template_selected.codemirror.on('change',function(cMirror){
					  // get value right from instance
					  jQuery('#field_mcfgf_woocommerce_product_template_selected').val(cMirror.getValue()).trigger('input');
					});

					editor_mcfgf_woocommerce_product_template_normal = wp.codeEditor.initialize( 'field_mcfgf_woocommerce_product_template_normal', editorSettings );
					editor_mcfgf_woocommerce_product_template_normal.codemirror.on('change',function(cMirror){
					  // get value right from instance
					  jQuery('#field_mcfgf_woocommerce_product_template_normal').val(cMirror.getValue()).trigger('input');
					});
				}, 13);
			});

	        gform.addAction( 'gform_post_load_field_settings', function( field_form_arr ) {
					// console.log('mcfgf: gform_post_load_field_settings');
        			var field = field_form_arr[0];

        			// console.log(field_name, jQuery("#"+editor_id).val());

        			var content = jQuery("#"+editor_id).val();
				
					// $('#field_settings').addClass('init_wp_editor');
					// for(var ed_instance_idx = tinymce.editors.length-1; ed_instance_idx >= 0; ed_instance_idx--){
			  //           if(tinymce.editors[ed_instance_idx].id === editor_id){
			  //               tinymce.remove(tinymce.editors[ed_instance_idx]);
			  //           }
			  //       }
			  		if(window.tinymce) {
				        switchEditors.go( editor_id, 'tmce' );
						// console.log('mcfgf: tmce', editor_id);
				        // $('#field_settings').removeClass('init_wp_editor');
						
				        setTimeout(function(){
				        	// jQuery('#wp-mm_tooltip_text_value-wrap').detach().appendTo('#mm_tooltip_text_editor_container');
							var current_field = field;

							tinyMCE.get(editor_id).setContent(content);
							tinyMCE.get(editor_id).onChange.add(function(ed, l) {
								
						        jQuery('#'+editor_id).val(ed.getContent());
						        current_field[field_name] = jQuery('#'+editor_id).val();
						        // console.log('editor change', ed, l, current_field[field_name], field_name);
						    });
					        // code to trigger on AJAX form render
					        // $('#field_settings').removeClass('init_wp_editor');
					        // console.log(field_name, jQuery("#"+editor_id).val());
				        }, 1000);
			        }

			        
					
				}, 10 );

	        setTimeout(function(){
				// var editor_id = 'mm_tooltip_text_value';
				
				// $('#field_settings').addClass('init_wp_editor');
				// for(var ed_instance_idx = tinymce.editors.length-1; ed_instance_idx >= 0; ed_instance_idx--){
		  //           if(tinymce.editors[ed_instance_idx].id === editor_id){
		  //               tinymce.remove(tinymce.editors[ed_instance_idx]);
		  //           }
		  //       }

				// // jQuery('#wp-mm_tooltip_text_value-wrap').detach().appendTo('#mm_tooltip_text_editor_container');
				// switchEditors.go( editor_id, 'tmce' );
				// setTimeout(function(){
				// 	$('#field_settings').removeClass('init_wp_editor');
				// }, 2000);
				
		
				jQuery('.insert-media').click( function( event ) {
					// console.log('add-media-button');
					var elem = jQuery( event.currentTarget ),
						editor = elem.data('editor'),
						options = {
							frame:    'post',
							state:    'insert',
							title:    wp.media.view.l10n.addMedia,
							multiple: true
						};

					event.preventDefault();

					// Remove focus from the `.insert-media` button.
					// Prevents Opera from showing the outline of the button
					// above the modal.
					//
					// See: https://core.trac.wordpress.org/ticket/22445
					elem.blur();

					if ( elem.hasClass( 'gallery' ) ) {
						options.state = 'gallery';
						options.title = wp.media.view.l10n.createGalleryTitle;
					}

					wp.media.editor.open( editor, options );
				});

				jQuery( '.wp-switch-editor' ).on( 'click', function( event ) {
					var id, mode,
						target = jQuery( event.target );

					if ( target.hasClass( 'wp-switch-editor' ) ) {
						id = target.attr( 'data-wp-editor-id' );
						mode = target.hasClass( 'switch-tmce' ) ? 'tmce' : 'html';
						switchEditors.go( id, mode );
					}
				});

			}, 1000);
			<?php endif ?>

	    </script>
	    <?php
	}

	
	// function gform_tooltips( $tooltips ) {
	//    $tooltips['mcfgf_question_text_value'] = "<h6>Conversation Questions</h6>Show conversation questions when form is shown in conversation mode. You can add multiple questions with line breaks.";
	//    return $tooltips;
	// }

	/**
	 * Show Tooltip in the bottom of field container
	 * @param  [type] $field_container [description]
	 * @param  [type] $field           [description]
	 * @param  [type] $form            [description]
	 * @param  [type] $css_class       [description]
	 * @param  [type] $style           [description]
	 * @param  [type] $field_content   [description]
	 * @return [type]                  [description]
	 */
	function gform_field_container( $field_container, $field, $form, $css_class, $style, $field_content ) {

		if(
			(!is_admin()) || 
			(isset($_GET['action']) && $_GET['action']=='gf_button_get_form') 
		){
		// 	//$field_id = $is_admin || empty( $form ) ? "field_$id" : 'field_' . $form['id'] . "_$id";
			// die();
// 			echo $_GET['action'];

// echo '-----';
// die();
// 			print_r($field);
// 		die();
			global $wp_embed;
			// $mcfgf_questions = isset($field->mcfgf_questions) && $field->mcfgf_questions && isset($field->mcfgf_questions_enable_custom) && $field->mcfgf_questions_enable_custom ? "<div class='mcfgf_questions' style='display:none'>" . do_shortcode(stripslashes($wp_embed->run_shortcode($field->mcfgf_questions))) . '</div>' : '';
			
			$mcfgf_questions = isset($field->mcfgf_questions) && $field->mcfgf_questions && isset($field->mcfgf_questions_enable_custom) && $field->mcfgf_questions_enable_custom ?  $field->mcfgf_questions : $field->label;

			$mcfgf_questions = do_shortcode(stripslashes($wp_embed->run_shortcode($mcfgf_questions)));
			$mcfgf_questions = GFCommon::replace_variables( $mcfgf_questions, $form, false, false, false );
			$field_container = str_replace("</li>", "<div class='mcfgf_questions' style='display:none'>" . $mcfgf_questions."</div></li>", $field_container);
	    	return $field_container;//"<li id='$field_id' class='{$css_class}' $style>{FIELD_CONTENT}{$tooltip}</li>";
		}
		else {
			return $field_container;
		}
	}


	function _mcfgf__enqueue_scripts() {
		// wp_register_style( 'conversational-form', plugins_url( 'assets/js/20171025/conversational-form.min.css', __FILE__ ), array(), $this->version);
		// wp_enqueue_style( 'conversational-form' );

		wp_register_style( 'mcfgf', plugins_url( 'assets/css/custom.css', __FILE__ ), array(), $this->version);
		wp_enqueue_style( 'mcfgf' );


		

		wp_register_script( 'mcfgf', plugins_url( "assets/js/v3/magic-conversation.js", __FILE__ ), array('jquery'), $this->version);
	    wp_enqueue_script( 'mcfgf' );

	    $mcfgf_options = get_option('mcfgf_conversation_generator', false);

	    // echo json_encode($mcfgf_options);
	    // die();

	    wp_localize_script( 'mcfgf', 'mcfgf', $mcfgf_options);

	    wp_register_script( 'mcfgf_sc', plugins_url( "assets/js/v3/magic-conversation-shortcode.js", __FILE__ ), array('jquery'), $this->version);
		wp_enqueue_script( 'mcfgf_sc' );
		

		// wp_register_script( 'mcfgf_local', plugins_url( "assets/moment/locale/en.js", __FILE__ ), array('jquery'), $this->version);
		// wp_enqueue_script( 'mcfgf_local' );
		

	    // wp_register_script( 'mcfgf_cl', plugins_url( "assets/js/conditional-logic.js", __FILE__ ), array('jquery'), $this->version);
	    // wp_enqueue_script( 'mcfgf_cl' );

	    //Init Conversation questions
		// require_once (dirname(__FILE__).'/api.php');
		// $api = new API_MagicConversationForGravityForms();

		// wp_localize_script( 'mcfgf', 'mcfgf_questions', $api->getAllQuestions());

		self::mcfgf_localize_settings();

	}

	public static function mcfgf_localize_settings() {
		$mcfgf_settings = get_option('mcfgf_settings', false);
		
	    // wp_register_script( 'jquery.conversational-form', plugins_url( "assets/js/20171025/conversational-form.js", __FILE__ ), array('jquery'), MCFGFP_VER);
	    // wp_enqueue_script( 'jquery.conversational-form' );

	    $license_control = true;
		if(!$license_control) {
			$mcfgf_settings['is_valid_license_key'] = 1;
		}

		if(isset($mcfgf_settings['license_key'])) {
			unset($mcfgf_settings['license_key']);
		}

		$mcfgf_settings['isFree'] = !file_exists(dirname(__FILE__).'/license.php');
	    wp_localize_script( 'mcfgf', 'mcfgf_settings', $mcfgf_settings);

	    global $mcfgf_settings_basics;
	    

	    wp_localize_script( 'mcfgf', 'mcfgf_settings_basics', $mcfgf_settings_basics);
	}

	//add scripts for global conversation button.
	function wp_enqueue_scripts() {
		global $mcfgf_settings_basics;
		global $mcfgf_is_global;
		
		$this->_mcfgf__enqueue_scripts();
		wp_register_style( 'mcfgf-sideform', plugins_url( 'assets/css/sideform.css', __FILE__ ), array(), $this->version);
		wp_enqueue_style( 'mcfgf-sideform' );
		if(isset($mcfgf_settings_basics['enable_conversation_button']) && $mcfgf_settings_basics['enable_conversation_button']=='on') {

			$onlyhome = isset($mcfgf_settings_basics['enable_only_home_page']) && $mcfgf_settings_basics['enable_only_home_page']=='on';

			$onlycate = isset($mcfgf_settings_basics['enable_only_specified_categories']) && !empty($mcfgf_settings_basics['enable_only_specified_categories']) && count($mcfgf_settings_basics['enable_only_specified_categories']) > 0;

			if($onlyhome && $onlycate) {
				if( !is_front_page() &&  !in_category($mcfgf_settings_basics['enable_only_specified_categories'])) {
					return;
				}
			} else if($onlyhome) {
				if( !is_front_page() ) {
					return;
				}
			} else if($onlycate) {
				if(!in_category($mcfgf_settings_basics['enable_only_specified_categories'])) {
					return;
				}
			}

			$formid = intval($mcfgf_settings_basics['conversation_gravity_form_id']);

			if($formid > 0)  {
				$mcfgf_is_global = true;
				// gravity_form_enqueue_scripts($formid, true);
				$mcfgf_is_global =  false;
				
			    // ['handle' => 'jquery.conversational-form', 'src'=>'assets/js/conversational-form.min.js','dep'=> array( 'jquery' ),'var'=> false, 'in_foot'=> true]
			    // $form = GFAPI::get_form( $formid );
			    wp_localize_script( 'mcfgf', 'mcfgf_global',
		        	array( 
		        		'ajax_url' => admin_url( 'admin-ajax.php' ),
		        		'ver' => $this->version,
		        		'form_post_url' => home_url('/'),
		        		'form_id' => $formid,
		        		// 'enable_rewind' => rgar( $form, 'mcfgf_rewind_conditional_logic' ) == 1,
		        		// 'rewind_conditional_logic' => rgar( $form, 'mcfgfRewindConditionalLogic' ),
		        	) 
				);
				$is_form_mode = isset($mcfgf_settings_basics['enable_only_as_form']) && $mcfgf_settings_basics['enable_only_as_form'] === 'on';
				// magic_conversation_embed_container_form
				if($is_form_mode) {
					gravity_form_enqueue_scripts( $formid, true );
				}
				
		        return;
			}
		}

		wp_localize_script( 'mcfgf', 'mcfgf_global',
        	array( 
        		'ajax_url' => admin_url( 'admin-ajax.php' ),
        		'ver' => $this->version
        	) 
        );
	}

	function magic_conversation_iframe_foot() {
		wp_register_script( 'mcfgf-loader', plugins_url( "assets/js/v3/magic-conversation-loader.js", __FILE__ ), array('jquery'), $this->version);
	    wp_enqueue_script( 'mcfgf-loader' );
	    wp_localize_script( 'mcfgf-loader', 'mcfgf_global',
        	array( 
        		'script_real_url' => plugins_url( 'assets/js/v3/magic-conversation-loader.js', __FILE__ ).'?ver='.$this->version,
        		'ver' => $this->version
        	) 
        );
	}

	//add scripts for normal forms
	function gform_enqueue_scripts($form, $is_ajax) {
		$mcfgf_enable_conversation_mode_checked = '';
		global $mcfgf_is_global;
		if($mcfgf_is_global) return;

		if ( false && rgar( $form, 'mcfgf_enable_conversation_mode' ) ) {
			$this->_mcfgf__enqueue_scripts();
			wp_localize_script( 'mcfgf', 'mcfgf_normal',
	        	array( 
	        		'ajax_url' => admin_url( 'admin-ajax.php' ),
	        		'ver' => $this->version,
	        		'form_post_url' => home_url('/'),
	        		'form_id' => $form['id'],
	        		'enable_rewind' => rgar( $form, 'mcfgf_rewind_conditional_logic' ) == 1,
	        		'rewind_conditional_logic' => rgar( $form, 'mcfgfRewindConditionalLogic' ),
	        		// 'form_settings' => $form
	        	) 
	        );
		}
	}

	// function ___add_rewrite() {
	// 	add_rewrite_rule(
	//         'magic-conversation/([0-9]+)/?$',
	//         '/wp-admin/admin-ajax.php?action=yakker_get_gf&form_id=$matches[1]',
	//         'top' );
	// }

	//Add short code support
	function init() {
		add_shortcode( 'magic-conversation', array($this, '___magic_conversation_short_code_handler') );
		add_shortcode( 'magic-conversation-button', array($this, '___magic_conversation_button_short_code_handler') );

		mcfgf_rewrite_add_rewrites();

		// $this->___add_rewrite();

		if(is_admin() ) {
			// require_once (dirname(__FILE__).'/woo-product-bundle/woo-product-bundle.php');
			if(class_exists('MC_WC_Product_Bundle')) {
				// echo 'abc';
				// die();
				// $productBundle = new MC_WC_Product_Bundle();
			}
			add_action( 'print_media_templates', array( 'MagicConversationForGravityForms', '___action_print_media_templates' ) );
			// Add the "Add Form" button to the editor. The customizer doesn't run in the admin context.
			if ( self::___page_supports_add_form_button() && class_exists('GFCommon') ) {
				require_once( GFCommon::get_base_path() . '/tooltips.php' );

				// Adding "embed form" button to the editor
				add_action( 'media_buttons', array( 'MagicConversationForGravityForms', '___add_form_button' ), 20 );
				// Adding the modal
				add_action( 'admin_print_footer_scripts', array( 'MagicConversationForGravityForms', '___add_mce_popup' ) );
			}
		}
	}

	//add query vars for rewrite support
	function query_vars($query_vars){
		$query_vars[] = 'mcfgf_id';
    	return $query_vars;
	}

	function template_redirect() {
		$form_id = get_query_var( 'mcfgf_id' );
		if( $form_id ) {
			if(isset($_GET['action']) && $_GET['action'] == 'yakker_get_gf') {
				$this->_yakker_get_gf($form_id);
				exit();
			}
			// 
			$mcfgf_options = get_option('mcfgf_conversation_generator', false);
			$avatar_robot = $mcfgf_options['avatar_robot'];
			
			//Init Side Form
			require_once (dirname(__FILE__).'/sideform.php');
			$sideform = new SideForm_MagicConversationForGravityForms();
			$sideform->showEmbedForm($form_id, $avatar_robot);
	        exit();
	    }
	}

	function ___magic_conversation_short_code_handler($atts, $content = "") {
		$atts_str = "";
		if(!empty($atts)) {
			foreach ($atts as $key => $value) {
				if(strtolower($key) == 'id') $key ='form-id';
				$atts_str .= " $key=\"$value\"";
			}
		}

		return "<magic-conversation$atts_str>$content</magic-conversation>";
	}

	function ___magic_conversation_button_short_code_handler($atts, $content = "") {
		$atts_str = "";
		$form_id = false;
		if(!empty($atts)) {
			$form_id = $atts['id'];
			// foreach ($atts as $key => $value) {
			// 	if(strtolower($key) == 'id') $key ='form-id';
			// 	$atts_str .= " $key=\"$value\"";
			// }
		}

		if(!$form_id) {
			return 'Form id not provided. the correct short code format it [magic-conversation-button id="1"]';
		}

		return '<script>
			function __mcb_domReady(fn) {
			  document.addEventListener("DOMContentLoaded", fn);
			  if (document.readyState === "interactive" || document.readyState === "complete" ) {
			    fn();
			  }
			}
			if(window.mcfgf_global && window.mcfgf_global.form_id) {
				window.mcfgf_global.form_id = false;
			}
			__mcb_domReady(function() {
				console.log("__mcb_domReady 002");
				if(typeof(jQuery) !== "undefined" && typeof(
					window.mcfgf_handle_global_conversation_form) !== "undefined" ) {
					console.log("__mcb_domReady 003");
					window.mcfgf_handle_global_conversation_form('.$form_id.', false);
				}
			});
		</script>';

		// return '<script>
		// 	function __mcb_domReady(fn) {
		// 	  document.addEventListener("DOMContentLoaded", fn);
		// 	  if (document.readyState === "interactive" || document.readyState === "complete" ) {
		// 	    fn();
		// 	  }
		// 	}
		// 	console.log("__mcb_domReady 001");
		// 	__mcb_domReady(function() {
		// 		console.log("__mcb_domReady 002");
		// 		if(typeof(jQuery) !== "undefined" && typeof(
		// 			window.mcfgf_handle_global_conversation_form) !== "undefined" ) {
		// 			console.log("__mcb_domReady 003");
		// 			window.mcfgf_handle_global_conversation_form('.$form_id.', false);
		// 		}
		// 	});
		// </script>';
	}

	function ___get_conversation_permalink($form_id) {
		return mcfgf_get_conversation_permalink($form_id);
	}

	function wp_before_admin_bar_render_9999() {
		if(!class_exists('GFFormsModel')) return;
		if(!function_exists('GFFormsModel::get_recent_forms')) return;
		global $wp_admin_bar;

		$recent_form_ids = GFFormsModel::get_recent_forms();

		if ( $recent_form_ids ) {
			$forms = GFFormsModel::get_form_meta_by_id( $recent_form_ids );


			foreach ( $recent_form_ids as $recent_form_id ) {

				foreach ( $forms as $form ) {
					if ( $form['id'] == $recent_form_id ) {
						if ( GFCommon::current_user_can_any( array(
							'gravityforms_edit_forms',
							'gravityforms_create_form',
							'gravityforms_preview_forms'
						) )
						) {
							$wp_admin_bar->add_node(
								array(
									'id'     => 'gform-form-' . $recent_form_id . '-conversation',
									'parent' => 'gform-form-' . $recent_form_id,
									'title'  => esc_html__( 'Conversation', 'gravityforms' ),
									'href'   => $this->___get_conversation_permalink($recent_form_id),
									'target' => '_blank'
								)
							);
						}
					}
				}
			}
		}
	}

	function gform_form_actions($form_actions, $form_id) {
		$form_actions['view'] = array(
			'label'        => __( 'Conversation', 'gravityforms' ),
			'title'        => __( 'View Magic Conversation fullscreen for this form', 'gravityforms' ),
			'url'          => $this->___get_conversation_permalink($form_id),
			'capabilities' => 'read',
			'menu_class'   => 'view',
			'target'       => '_blank',
			'priority'     => 500,
		);
		return $form_actions;
	}

	function gform_toolbar_menu($menu_items, $form_id){
		$menu_items['conversation'] = array(
	        'label' => 'Conversation', // the text to display on the menu for this link
	        'icon'  => '<i class="fa fa-comments fa-lg"></i>',
	        'title' => 'View Magic Conversation fullscreen for this form', // the text to be displayed in the title attribute for this link
	        'url' => $this->___get_conversation_permalink($form_id),// the URL this link should point to
	        //https://app.yakker.io/wp-admin/admin.php?page=gf_edit_forms&view=settings&id=2
			'target'       => '_blank',
	        'menu_class' => 'gf_form_toolbar_conversation', // optional, class to apply to menu list item (useful for providing a custom icon)
	        'link_class' => rgget( 'view' ) == 'conversation' ? 'gf_toolbar_active' : '', // class to apply to link (useful for specifying an active style when this link is the current page)
	        'capabilities' => array( 'gravityforms_edit_forms' ), // the capabilities the user should possess in order to access this page
	        'priority' => 101 // optional, use this to specify the order in which this menu item should appear; if no priority is provided, the menu item will be append to end
	    );
		return $menu_items;
	}

	private function __gform_field_advanced_settings_25($position, $form_id) {
		?>
		</ul>
		<button tabindex="0"  id="mcfgf_tab_yakker_tab_toggle" class="panel-block-tabs__toggle">
		<?php esc_html_e( 'Conversation', 'mcfgf' ); ?>
		</button>
		<ul id="mcfgf_tab_yakker" class="panel-block-tabs__body panel-block-tabs__body--settings">
		<?php
	}

	function gform_field_advanced_settings($position, $form_id){
		//TODO: flannian 2017-12-5 put tooltip settings into tab
		if ( $position == -1 ) {
			if(version_compare( GFForms::$version, '2.5-beta-1', '>=' )) {
				$this->__gform_field_advanced_settings_25($position, $form_id);
			} else {
	        ?>
	        </ul>
	        </div>
	        <div id="mcfgf_tab_yakker">
			<ul>
		        <!-- <li class="label_setting field_setting"> -->
	        <?php
			}
	    // }
	// }

	// function gform_field_standard_settings( $position, $form_id ) {
	    //create settings on position 25 (right after Field Label)

	    //if ( $position == 25 ) {
	    // if(true){
	    	require_once (dirname(__FILE__).'/api.php');
			$api = new API_MagicConversationForGravityForms();
			/*<li class="mcfgf_field_id field_setting" style="display: list-item; ">
	            <label for="mcfgf_field_alias" class="section_label">
	                <?php esc_html_e( 'Field ID', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_field_id' ) ?>
	            </label>
	            <div id="mcfgf_field_id"><?php echo "mc_".$form_id; ?></div>
	        </li>
	        <li class="mcfgf_field_alias field_setting" style="display: list-item; ">
	            <label for="mcfgf_field_alias" class="section_label">
	                <?php esc_html_e( 'Field Alias', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_field_alias' ) ?>
	            </label>
	            <div id="mcfgf_field_alias"><?php echo "mc_".$form_id; ?></div>
	        </li>
	        */
	        ?>
	        <li class="mcfgf_enable_options_filter field_setting">
	        	<label for="mcfgf_enable_options_filter" class="section_label">
	                <?php esc_html_e( 'Options Filter', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_enable_options_filter' ) ?>
	            </label>
	            <!-- <div id="mm_tooltip_text_editor_container"></div> -->
	            <div><input type="checkbox" id="field_mcfgf_enable_options_filter" onclick="SetFieldProperty('mcfgf_enable_options_filter', this.checked);" /> <label for="field_mcfgf_enable_options_filter" class="inline field_mcfgf_enable_options_filter">Enable options filter for this field.</label></div>
	        </li>
			<li class="mcfgf_enable_local_validation_ field_setting">
	        	<label for="mcfgf_enable_local_validation" class="section_label">
	                <?php esc_html_e( 'Local Validation', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_enable_local_validation' ) ?>
	            </label>
	            <!-- <div id="mm_tooltip_text_editor_container"></div> -->
	            <div><input type="checkbox" id="field_mcfgf_enable_local_validation" onclick="SetFieldProperty('mcfgf_enable_local_validation', this.checked);" /> <label for="field_mcfgf_enable_local_validation" class="inline field_mcfgf_enable_local_validation">Enable local validation for this field.</label></div>
	        </li>
	        <li class="mcfgf_field_delay_next field_setting" style="display: list-item; ">
	            <label for="mcfgf_field_delay_next" class="section_label">
	                <?php esc_html_e( 'Delayed Response Control', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_field_delay_next' ) ?>
	            </label>
	            <div id="mcfgf_field_delay_next">
	            	<input type="number" id="mm_delay_next_from" style="width: 50px" size="5">
	            	<span>to</span>
	            	<input type="number" id="mm_delay_next_to" style="width: 50px" size="5">
	            	<span>seconds.</span>
	            </div>
	        </li>
	        <li class="<?php echo $this->editor_id; ?> field_setting" style="display: list-item; ">
	        	<style>
	        	/*.mcfgf_questions-default-questions {
					padding: 15px;
					background: #efefef;
					margin:10px 15px;
	        	}*/
	        	/*#mcfgf_questions-enable-custom-questions-2, #mcfgf_questions-enable-custom-questions-1 {
	        		margin-bottom: 0px;
	        		min-height: 23px;
	        		line-height: 23px;
	        	}*/

				<?php if(version_compare( GFForms::$version, '2.5-beta-1', '>=' )): ?>
				.mcfgf_questions .all-merge-tags{
					position: absolute;
					right: 0px;
					top: -40px;
				}
				.mcfgf_questions .all-merge-tags #gf_merge_tag_list {
					right: 0px;
				}
				<?php endif; ?>
	        	</style>
	        	<script type="text/javascript">
	        		//mcfgf_default_questions = <?php echo json_encode($api->getAllQuestions()); ?>
	        	</script>
	            <label for="field_admin_label" class="section_label">
	                <?php _e( 'Conversation Question', 'mcfgf' ); ?>
	                <?php gform_tooltip( $this->editor_id ) ?>
	            </label>
        		<div class="<?php echo $this->editor_id; ?>-custom-questions">
        			<?php 
					// wp_enqueue_media();
	            	if(!(isset($GLOBALS['mcfgfp_disable_wp_editor']) && $GLOBALS['mcfgfp_disable_wp_editor'])) {
		            	wp_editor( '', $this->editor_id, array(
							'media_buttons' => true,
							'textarea_name' => $this->editor_id,
							'editor_class' => 'merge-tag-support mt-wp_editor wysiwyg_exclude'
						)); 


							// 'autop' => false,
							// 'textarea_name' => $this->editor_id,
							// 'editor_class' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right'
		            }
		            else {
					?>
        			<textarea id="<?php echo $this->editor_id; ?>" class="fieldwidth-3 fieldheight-2 wysiwyg_exclude merge-tag-support"></textarea>
        			<?php } ?>
        		</div>
	        </li>

	        <li class="mcfgf_enable_limit_max_column field_setting">
				<div><input type="checkbox" id="field_mcfgf_enable_limit_max_column" onclick="SetFieldProperty('mcfgf_enable_limit_max_column', this.checked);" /> <label for="field_mcfgf_enable_limit_max_column" class="inline field_mcfgf_enable_limit_max_column">Limit max columns to <select id="field_mcfgf_num_limit_max_column">
	            	<?php 
		          	$named_styles = array(array('id'=>'', 'label'=>'Select'), array('id'=>'1', 'label'=>'1'), array('id'=>'2', 'label'=>'2') );
		          	foreach($named_styles as $cc => $named_style) {
					    echo '<option value="' . $named_style['id'] . '">' . $named_style['label'] . '</option>';
					}?>
				</select> on mobile phone.</label></div>
	        </li>
	        <li class="mcfgf_enable_woocommerce_product field_setting">
	        	<label for="mcfgf_enable_woocommerce_product" class="section_label">
	                <?php esc_html_e( 'WooCommerce Product Picker', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_enable_woocommerce_product' ) ?>
	            </label>
	            <!-- <div id="mm_tooltip_text_editor_container"></div> -->
	            <div><input type="checkbox" id="field_mcfgf_enable_woocommerce_product" onclick="SetFieldProperty('mcfgf_enable_woocommerce_product', this.checked);" /> <label for="field_mcfgf_enable_woocommerce_product" class="inline field_mcfgf_enable_woocommerce_product">Turn it into a product picker and checkout on form submission.</label></div>
	            
	        </li>
	        <li class="mcfgf_woocommerce_product_template_normal field_setting">
	        	<label for="field_mcfgf_woocommerce_product_template_normal" class="section_label">
	                <?php esc_html_e( 'Template for Normal Status', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_woocommerce_product_template_normal' ) ?>
	            </label>
	            <div><textarea id="field_mcfgf_woocommerce_product_template_normal" class="fieldwidth-3 fieldheight-1"></textarea></div>
	        </li>
	        <li class="mcfgf_woocommerce_product_template_selected field_setting">
	        	<label for="field_mcfgf_woocommerce_product_template_selected" class="section_label">
	                <?php esc_html_e( 'Template for Checked Status', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_woocommerce_product_template_selected' ) ?>
	            </label>
	            <div><textarea id="field_mcfgf_woocommerce_product_template_selected" class="fieldwidth-3 fieldheight-1"></textarea></div>
	        </li>
	        <li class="mcfgf_enable_url_redirect field_setting">
	        	<label for="mcfgf_enable_url_redirect" class="section_label">
	                <?php esc_html_e( 'Confirmation Override', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_enable_url_redirect' ) ?>
	            </label>
	            <!-- <div id="mm_tooltip_text_editor_container"></div> -->
	            <div><input type="checkbox" id="field_mcfgf_enable_url_redirect" onclick="SetFieldProperty('mcfgf_enable_url_redirect', this.checked);" /> <label for="field_mcfgf_enable_url_redirect" class="inline field_mcfgf_enable_url_redirect">Enable redirect to the url specified in the value field of choices on confirmation.</label></div>
	            
	        </li>
	        <li class="mcfgf_value_on_back field_setting" style="display: list-item; ">
	            <label for="mcfgf_value_on_back" class="section_label">
	                <?php esc_html_e( 'Value on Click Previous Button', 'gravityforms' ); ?>
	                <?php gform_tooltip( 'mcfgf_value_on_back' ) ?>
	            </label>
	            <div id="mcfgf_value_on_back">
	            	<input type="text" id="mm_value_on_back" style="width: 150px" size="5">
	            </div>
	        </li>
	        <?php
	    }
	}

	function woocommerce_add_to_cart_redirect($url) {
		if(isset($_GET['redirect-to-checkout']) && $_GET['redirect-to-checkout'] === 'yes') {
			return WC()->cart->get_checkout_url();
		}

		return $url;
	}

	function gform_confirmation($confirmation, $form, $entry, $ajax) {

		// $REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		// if(strpos($REFERER, '/magic-conversation/') !== false) {
			// $confirmation = json_encode($confirmation);
			if ( isset( $confirmation['redirect'] ) ) {
		        $url          = esc_url_raw( $confirmation['redirect'] );
	
		        if(strpos($url, 'open-magic-conversation') !== false) {
		        	$confirmation = '';
		        	$confirmation .= "<script type=\"text/javascript\">window.mcfgf_open_magic_conversation('$url');</script>";
		        } else if(isset($_GET['action']) && $_GET['action'] === 'yakker_get_gf') {
					$confirmation['redirect2'] = $confirmation['redirect'];
					// unset($confirmation['redirect']);
					// print_r($confirmation);
					// print_r($_GET);
					// echo $url;
					// die();
				}
		    } 
		    // else if (strpos($confirmation, 'function gformRedirect') !== false) {
		    // 	$confirmation =  str_replace('document.location.href=', 'window.mcfgf_open_magic_conversation(', $confirmation);
		    // 	$confirmation =  str_replace(';}', ');window["gf_submitting_'.$form['id'].'"]=false;jQuery("#gform_ajax_spinner_'.$form['id'].'").remove();}', $confirmation);	 
		    // }
		// }
		
	 
	    return $confirmation;
	}

	function woocommerce_load_cart_from_session()
	{
		$user_id = get_current_user_id();
		// if(!$user_id) {
		//     $user = get_userdatabylogin('flannian');
		//     if($user) {
		//         $remember = false;
		//         $user_id = $user->ID;
		//         wp_clear_auth_cookie();
		//         wp_set_auth_cookie( $user_id, $remember );
		//         wp_set_current_user( $user_id );
		//         wc_set_customer_auth_cookie( $user_id );
		//     }
		// }
	}

	function woocommerce_valid_order_statuses_for_order_again($status) {
		return array('completed', 'processing');
	}

	function woocommerce_cart_loaded_from_session($cart) {
		if(isset( $_GET['action'], $_GET['order_again'] ) && $_GET['action'] === 'yakker_get_gf') {
			update_user_meta(
				get_current_user_id(),
				'_woocommerce_persistent_cart_' . get_current_blog_id(),
				array(
					'cart' => yakker_woocommerce_get_cart_for_session(),
				)
			);

			update_user_meta(
				get_current_user_id(),
				'_woocommerce_load_saved_cart_after_login',
				true
			);

			
			// persistent_cart_update()
			// $items = yakker_woocommerce_get_cart();
			// print_r($cart);
			// die();
			// var_dump($cart);
			// $this->_yakker_get_gf($_POST['gform_validation']);

			http_response_code(500);

			die();
			// WC()->session->set( 'cart_totals', null );
		}
	}

	// function wp_redirect($location, $status) {
	// 	// if(function_exists('wc_get_page_permalink')) {
	// 	// 	if($location === wc_get_page_permalink( 'cart' )) {
	// 	// 		if(isset( $_GET['action'] ) && $_GET['action'] === 'yakker_get_gf') {
	// 	// 			$this->_yakker_get_gf($_POST['gform_validation']);
	// 	// 			return false;
	// 	// 		}
	// 	// 	}
	// 	// }
	// 	return $location;
	// }	
}

global $mcfgf_settings_basics;
$mcfgf_settings_basics = get_option( 'mcfgf_basics' );
global $magic_conversation_for_gravity_forms;
$magic_conversation_for_gravity_forms = new MagicConversationForGravityForms();

register_activation_hook( dirname(__FILE__).'/plugin-index.php', array($magic_conversation_for_gravity_forms, '_install_me') );

/**/
endif;