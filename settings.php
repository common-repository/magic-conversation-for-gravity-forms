<?php

/**
 * Magic Conversation For Gravity Forms settings class
 *
 * @author Flannian Feng
 */

if ( !class_exists('Settings_MagicConversationForGravityForms' ) ):


class Settings_MagicConversationForGravityForms {
    public static $base_url = 'https://magicconversation.net';
    public static $domain = 'magicconversation.net';
    private $settings_api;
    private $settings_api_file;

    function __construct() {
        $reflector = new ReflectionClass('WeDevs_Settings_API');
        $this->settings_api_file = $reflector->getFileName();
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {
        
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        // add_options_page( 'Settings API', 'Settings API', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
        add_menu_page( 'Magic Conversation For Gravity Forms', 'Magic Conversation For Gravity Forms', 'manage_options', 'magic_conversation_for_gravity_forms', array($this, 'plugin_page'));
        add_submenu_page( 
              'magic_conversation_for_gravity_forms' 
            , 'Magic Conversation For Gravity Forms' 
            , 'Settings'
            , 'manage_options'
            , 'magic_conversation_for_gravity_forms'
            , array($this, 'plugin_page')
        );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'mcfgf_basics',
                'title' => __( 'Settings', 'mcfgf' )
            ),
            // array(
            //     'id'    => 'mcfgf_advanced',
            //     'title' => __( 'Advanced Settings', 'mcfgf' )
            // )
        );
        return $sections;
    }

    /**
     * get a list of all Gravity Forms
     * @return [type] [description]
     */
    function get_gravityforms_list() {
        $forms = class_exists('RGFormsModel') ? RGFormsModel::get_forms( null, 'title' ) : array();
        $formidnames = array(''=>__('Please choose a form as Global Conversation Form', 'mcfgf'));
        foreach( $forms as $form ){
            $formidnames[$form->id] = $form->title;
        }
        return $formidnames;
    }

    function get_locales_list() {
        $localeidnames = array(''=> __('Please choose a locale', 'mcfgf'));
        $localeidnames['af'] = 'Afrikaans';
        $localeidnames['ar-dz'] = 'Arabic (Algeria)';
        $localeidnames['ar-kw'] = 'Arabic (Kuwait)';
        $localeidnames['ar-ly'] = 'Arabic (Libya)';
        $localeidnames['ar-ma'] = 'Arabic (Morocco)';
        $localeidnames['ar-sa'] = 'Arabic (Saudi Arabia)';
        $localeidnames['ar-tn'] = 'Arabic (Tunisia)';
        $localeidnames['ar'] = 'Arabic';
        $localeidnames['az'] = 'Azerbaijani';
        $localeidnames['be'] = 'Belarusian';
        $localeidnames['bg'] = 'Bulgarian';
        $localeidnames['bm'] = 'Bambara';
        $localeidnames['bn'] = 'Bengali';
        $localeidnames['bo'] = 'Tibetan';
        $localeidnames['br'] = 'Breton';
        $localeidnames['bs'] = 'Bosnian';
        $localeidnames['ca'] = 'Catalan';
        $localeidnames['cs'] = 'Czech';
        $localeidnames['cv'] = 'Chuvash';
        $localeidnames['cy'] = 'Welsh';
        $localeidnames['da'] = 'Danish';
        $localeidnames['de-at'] = 'German (Austria)';
        $localeidnames['de-ch'] = 'German (Switzerland)';
        $localeidnames['de'] = 'German';
        $localeidnames['dv'] = 'Divehi';
        $localeidnames['el'] = 'Greek';
        $localeidnames['en-au'] = 'English (Australia)';
        $localeidnames['en-ca'] = 'English (Canada)';
        $localeidnames['en-gb'] = 'English (United Kingdom)';
        $localeidnames['en-ie'] = 'English (Ireland)';
        $localeidnames['en-nz'] = 'English (New Zealand)';
        $localeidnames['eo'] = 'Esperanto';
        $localeidnames['es-do'] = 'Spanish (Dominican Republic)';
        $localeidnames['es-us'] = 'Spanish (United States)';
        $localeidnames['es'] = 'Spanish';
        $localeidnames['et'] = 'Estonian';
        $localeidnames['eu'] = 'Basque';
        $localeidnames['fa'] = 'Persian';
        $localeidnames['fi'] = 'Finnish';
        $localeidnames['fo'] = 'Faroese';
        $localeidnames['fr-ca'] = 'French (Canada)';
        $localeidnames['fr-ch'] = 'French (Switzerland)';
        $localeidnames['fr'] = 'French';
        $localeidnames['fy'] = 'Western Frisian';
        $localeidnames['gd'] = 'Scottish Gaelic';
        $localeidnames['gl'] = 'Galician';
        $localeidnames['gom-latn'] = 'gom (Latin)';
        $localeidnames['gu'] = 'Gujarati';
        $localeidnames['he'] = 'Hebrew';
        $localeidnames['hi'] = 'Hindi';
        $localeidnames['hr'] = 'Croatian';
        $localeidnames['hu'] = 'Hungarian';
        $localeidnames['hy-am'] = 'Armenian (Armenia)';
        $localeidnames['id'] = 'Indonesian';
        $localeidnames['is'] = 'Icelandic';
        $localeidnames['it'] = 'Italian';
        $localeidnames['ja'] = 'Japanese';
        $localeidnames['jv'] = 'Javanese';
        $localeidnames['ka'] = 'Georgian';
        $localeidnames['kk'] = 'Kazakh';
        $localeidnames['km'] = 'Khmer';
        $localeidnames['kn'] = 'Kannada';
        $localeidnames['ko'] = 'Korean';
        $localeidnames['ky'] = 'Kirghiz';
        $localeidnames['lb'] = 'Luxembourgish';
        $localeidnames['lo'] = 'Lao';
        $localeidnames['lt'] = 'Lithuanian';
        $localeidnames['lv'] = 'Latvian';
        $localeidnames['me'] = 'me';
        $localeidnames['mi'] = 'Maori';
        $localeidnames['mk'] = 'Macedonian';
        $localeidnames['ml'] = 'Malayalam';
        $localeidnames['mr'] = 'Marathi';
        $localeidnames['ms-my'] = 'Malay (Malaysia)';
        $localeidnames['ms'] = 'Malay';
        $localeidnames['mt'] = 'Maltese';
        $localeidnames['my'] = 'Burmese';
        $localeidnames['nb'] = 'Norwegian Bokmål';
        $localeidnames['ne'] = 'Nepali';
        $localeidnames['nl-be'] = 'Dutch (Belgium)';
        $localeidnames['nl'] = 'Dutch';
        $localeidnames['nn'] = 'Norwegian Nynorsk';
        $localeidnames['pa-in'] = 'Punjabi (India)';
        $localeidnames['pl'] = 'Polish';
        $localeidnames['pt-br'] = 'Portuguese (Brazil)';
        $localeidnames['pt'] = 'Portuguese';
        $localeidnames['ro'] = 'Romanian';
        $localeidnames['ru'] = 'Russian';
        $localeidnames['sd'] = 'Sindhi';
        $localeidnames['se'] = 'Northern Sami';
        $localeidnames['si'] = 'Sinhala';
        $localeidnames['sk'] = 'Slovak';
        $localeidnames['sl'] = 'Slovenian';
        $localeidnames['sq'] = 'Albanian';
        $localeidnames['sr-cyrl'] = 'Serbian (Cyrillic)';
        $localeidnames['sr'] = 'Serbian';
        $localeidnames['ss'] = 'Swati';
        $localeidnames['sv'] = 'Swedish';
        $localeidnames['sw'] = 'Swahili';
        $localeidnames['ta'] = 'Tamil';
        $localeidnames['te'] = 'Telugu';
        $localeidnames['tet'] = 'Tetum';
        $localeidnames['th'] = 'Thai';
        $localeidnames['tl-ph'] = 'Tagalog (Philippines)';
        $localeidnames['tlh'] = 'Klingon';
        $localeidnames['tr'] = 'Turkish';
        $localeidnames['tzl'] = 'tzl';
        $localeidnames['tzm-latn'] = 'tzm (Latin)';
        $localeidnames['tzm'] = 'tzm';
        $localeidnames['uk'] = 'Ukrainian';
        $localeidnames['ur'] = 'Urdu';
        $localeidnames['uz-latn'] = 'Uzbek (Latin)';
        $localeidnames['uz'] = 'Uzbek';
        $localeidnames['vi'] = 'Vietnamese';
        $localeidnames['x-pseudo'] = 'x-pseudo';
        $localeidnames['yo'] = 'Yoruba';
        $localeidnames['zh-cn'] = 'Chinese (China)';
        $localeidnames['zh-hk'] = 'Chinese (Hong Kong SAR China)';
        $localeidnames['zh-tw'] = 'Chinese (Taiwan)';
        return $localeidnames;
    }

    /**
     * get a list of all Categories
     * @return [type] [description]
     */
    function get_categories_list() {
        // Arguments
        $args = array(
            'number' => 99
        );

        // Allow dev to filter the arguments
        $args = apply_filters( 'mcfgf_cats_list_args', $args );

        // Get the cats
        $cats = get_terms( 'category', $args );

        $cateidnames = array();

        foreach( $cats as $category ){
            $cateidnames[$category->term_id] = $category->name;
        }

        return $cateidnames;
    }
    

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $normal_status_button_image = plugins_url( "assets/img/openConversation.png", __FILE__ );
        $active_status_button_image = plugins_url( "assets/img/closeConversation.png", __FILE__ );

        global $mcfgf_settings_basics;

        $form_id = isset($mcfgf_settings_basics['conversation_gravity_form_id']) ? intval($mcfgf_settings_basics['conversation_gravity_form_id']) : 0;

        $fontawesomefontallowed = ' <a target="_blank" href="https://fontawesome.com/icons">Font Awesome 5 icons allowed</a>';

        $viewOnversation = ' <a id="quick_view_current_conversation" target="_blank" href="'.mcfgf_get_conversation_permalink($form_id).'" url_tpl="'.mcfgf_get_conversation_permalink('{form_id}').'">View this conversation</a>';

        $settings_fields = array(
            'mcfgf_basics' => array(
                array(
                    'name'        => 'h2_conversation_form',
                    'desc'        => __( '', 'mcfgf' ),
                    'label'       => __( '<h4>Conversation Form</h4>', 'mcfgf' ),
                    'type'        => 'html'
                ),
                array(
                    'name'              => 'conversation_gravity_form_id',
                    'label'             => __( 'Choose a form', 'mcfgf' ),
                    'desc'              => $viewOnversation,
                    'placeholder'       => __( 'Please choose a form as Global Conversation Form', 'mcfgf' ),
                    'type'    => 'select',
                    // 'default' => 'no',
                    'options' => $this->get_gravityforms_list()
                ),
                array(
                    'name'        => 'h2_conversation_input',
                    'desc'        => __( '', 'mcfgf' ),
                    'label'       => __( '<h4>Conversation Toolbar</h4>', 'mcfgf' ),
                    'type'        => 'html'
                ),
                array(
                    'name'              => 'textForMonthNames',
                    'label'             => __( 'Month names', 'mcfgf' ),
                    'desc'              => __( 'Default: January, February, March, April, May, June, July, August, September, October, November, December', 'mcfgf' ),
                    'placeholder'       => __( 'Month names seperated with comas', 'mcfgf' ),
                    'type'              => 'textarea',
                    'default'           => 'January, February, March, April, May, June, July, August, September, October, November, December',
                    // 'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'dateLocaleCode',
                    'label'             => __( 'Locale for date formatting', 'mcfgf' ),
                    // 'desc'              => __( 'Default: &lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt;', 'mcfgf' ) . $fontawesomefontallowed,
                    'placeholder'       => __( 'Please choose a locale', 'mcfgf' ),
                    'type'    => 'select',
                    // 'default' => 'no',
                    'options' => $this->get_locales_list()
                ),
                array(
                    'name'              => 'textForSend',
                    'label'             => __( 'Send button text', 'mcfgf' ),
                    'desc'              => __( 'Default: &lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt;', 'mcfgf' ) . $fontawesomefontallowed,
                    'placeholder'       => __( 'Please enter send button text', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => '<i class="fas fa-paper-plane"></i>',
                    // 'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'textForSkip',
                    'label'             => __( 'Skip button text', 'mcfgf' ),
                    'desc'              => __( 'Default: Skip', 'mcfgf' ) . $fontawesomefontallowed,
                    'placeholder'       => __( 'Please enter skip button text', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Skip',
                    // 'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_typing_hint',
                    'label'             => __( 'Placeholder for waiting for response', 'mcfgf' ),
                    'desc'              => __( 'Default: %s is typing', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a waiting message', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => '%s is typing',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_question_required',
                    'label'             => __( 'Default response for required field', 'mcfgf' ),
                    'desc'              => __( 'Default: This question is required.', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a message', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'This question is required.',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_submitting',
                    'label'             => __( 'Hint when submitting form.', 'mcfgf' ),
                    'desc'              => __( 'Default: Submitting form data.', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a hint', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Submitting form data.',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_waiting_payment',
                    'label'             => __( 'Hint when processing payment.', 'mcfgf' ),
                    'desc'              => __( 'Default: Waiting for the payment process to complete.', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a hint', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Waiting for the payment process to complete.',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_quantity_required',
                    'label'             => __( 'Hint for quantity field.', 'mcfgf' ),
                    'desc'              => __( 'Default: Please enter quantity.', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a hint', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please enter quantity.',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_multipage_confirm',
                    'label'             => __( 'Confirm button text for Wizard mode (Multi-Page forms)', 'mcfgf' ),
                    'desc'              => __( 'Default: Confirm', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a text', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Confirm',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_input_placeholder',
                    'label'             => __( 'Default Placeholder for Input and Textarea Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                    'placeholder'       => __( 'Default placeholder for Input and Textarea field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Type your answer here',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_input_placeholder_for_radio',
                    'label'             => __( 'Default Placeholder for Radio and Checkbox Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please choose from above', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Radio and Checkbox field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please choose from above',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_please_select_an_option',
                    'label'             => __( 'Please select an option message for Radio Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please select an option.', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Radio and Checkbox field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please select an option.',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForDate',
                    'label'             => __( 'Default Placeholder for Date Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please choose a date with picker', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Date field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please choose a date with picker',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForTime',
                    'label'             => __( 'Default Placeholder for Time Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please choose a time with picker', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Time field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please choose a time with picker',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForAddress',
                    'label'             => __( 'Default Placeholder for Address Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please click to fill an address', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Address field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please click to fill an address',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForName',
                    'label'             => __( 'Default Placeholder for Name Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please click to fill your name', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Name field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please click to fill your name',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForPassword',
                    'label'             => __( 'Default Placeholder for Password Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please click to fill a password', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Password field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please click to fill a password',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForFileUpload',
                    'label'             => __( 'Default Placeholder for File Upload Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please click to select a file', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for File Upload field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please click to select a file',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForChainedselect',
                    'label'             => __( 'Default Placeholder for Chained Select Field', 'mcfgf' ),
                    'desc'              => __( 'Default: Please select with picker', 'mcfgf' ),
                    'placeholder'       => __( 'Please enter a default placeholder for Chained Select field', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Please select with picker',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'conversation_finished',
                    'label'             => __( 'Placeholder for Conversation Finished', 'mcfgf' ),
                    'desc'              => __( 'Default: Conversation finished.', 'mcfgf' ),
                    'placeholder'       => __( 'Enter a placeholder', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Conversation finished.',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'placeholderForConversationFinished',
                    'label'             => __( 'Hint for Conversation Finished', 'mcfgf' ),
                    'desc'              => __( 'Default: Type R to reset', 'mcfgf' ),
                    'placeholder'       => __( 'Hit displayed after conversation finished', 'mcfgf' ),
                    'type'              => 'text',
                    'default'           => 'Type R to reset',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                

                // array(
                //     'name'              => 'conversation_input_done_button_label',
                //     'label'             => __( 'Continue button text for checkbox options', 'mcfgf' ),
                //     'desc'              => __( 'Default: Done', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter continue button text for checkbox options.', 'mcfgf' ),
                //     'type'              => 'text',
                //     'default'           => 'Done',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                // array(
                //     'name'              => 'conversation_input_done_button_width',
                //     'label'             => __( 'Continue button width for checkbox options', 'mcfgf' ),
                //     'desc'              => __( 'Default: 100', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter continue button width for checkbox options.', 'mcfgf' ),
                //     'type'              => 'number',
                //     'default'           => '100',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                // array(
                //     'name'              => 'conversation_input_placeholder_for_checkbox',
                //     'label'             => __( 'Chat Input Placeholder for checkbox group', 'mcfgf' ),
                //     'desc'              => __( 'Default: Please click Done button after you finish your choices', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter a placeholder for checkbox input', 'mcfgf' ),
                //     'type'              => 'text',
                //     'default'           => 'Please click Done button after you finish your choices',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                array(
                    'name'    => 'conversation_toolbar_button_color',
                    'label'   => __( 'Button Color', 'mcfgf' ),
                    'desc'    => __( 'Button font color for conversation send button and option pick button', 'mcfgf' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'        => 'h2_conversation_button',
                    'desc'        => __( '', 'mcfgf' ),
                    'label'       => __( '<h4>Floating Conversation Button</h4>', 'mcfgf' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'enable_conversation_button',
                    'label' => __( 'Global Conversation Button', 'mcfgf' ),
                    'desc'  => __( 'Show a global conversation button at bottom right side of every page.', 'mcfgf' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'  => 'enable_only_home_page',
                    'label' => __( 'Home Page Only', 'mcfgf' ),
                    'desc'  => __( 'Show the global conversation button on home page only.', 'mcfgf' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'  => 'enable_only_as_form',
                    'label' => __( 'Display as form', 'mcfgf' ),
                    'desc'  => __( 'Display as form when the global conversation button is clicked.', 'mcfgf' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'              => 'enable_only_specified_categories',
                    'label'             => __( 'Specified Categories Only', 'mcfgf' ),
                    'desc'              => __( 'Show the global conversation button on specified categories only.', 'mcfgf' ),
                    'type'    => 'multicheck',
                    // 'default' => 'no',
                    'options' => $this->get_categories_list()
                ),
                array(
                    'name'    => 'conversation_button_background_color',
                    'label'   => __( 'Background Color', 'mcfgf' ),
                    'desc'    => __( 'Background color for global conversation button', 'mcfgf' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'conversation_button_image_normal',
                    'label'   => __( 'Image for normal status', 'mcfgf' ),
                    'desc'    => '<div class="image-preview-wrapper"><div class="image-preview-close"><button type="button" class="notice-dismiss"><span class="screen-reader-text">Remove this image.</span></button></div>
        <img id="image-preview" default-src="'.$normal_status_button_image.'" width="26" height="26" style="max-height: 26px; width: 26px;">
</div>',
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                ),
                array(
                    'name'    => 'conversation_button_image_active',
                    'label'   => __( 'Image for active status', 'mcfgf' ),
                    'desc'    => '<div class="image-preview-wrapper"><div class="image-preview-close"><button type="button" class="notice-dismiss"><span class="screen-reader-text">Remove this image.</span></button></div>
        <img id="image-preview" default-src="'.$active_status_button_image.'" width="26" height="26" style="max-height: 26px; width: 26px;">
</div>',
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                ),
                array(
                    'name'  => 'allow_image_button',
                    'label' => __( 'Image button mode', 'mcfgf' ),
                    'desc'  => __( 'Enable image button mode', 'mcfgf' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'              => 'image_button_width_num',
                    'label'             => __( 'Image button width', 'mcfgf' ),
                    // 'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                    // 'placeholder'       => __( 'Please enter a sub title', 'mcfgf' ),
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '100',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'image_button_width_unit',
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        '%' => '%',
                        'px'  => 'px',
                        'em'  => 'em',
                        'rem'  => 'rem'
                    )
                ),
                array(
                    'name'              => 'image_button_height_num',
                    'label'             => __( 'Image button height', 'mcfgf' ),
                    // 'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                    // 'placeholder'       => __( 'Please enter a sub title', 'mcfgf' ),
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '100',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'image_button_height_unit',
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        '%' => '%',
                        'px'  => 'px',
                        'em'  => 'em',
                        'rem'  => 'rem'
                    )
                ),
                // Global Conversation Tooltip
                array(
                    'name'        => 'h2_conversation_notification',
                    'desc'        => __( '', 'mcfgf' ),
                    'label'       => __( '<h4>Conversation Welcome Message</h4>', 'mcfgf' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'enable_notification_message',
                    'label' => __( 'Welcome Tooltip Message', 'mcfgf' ),
                    'desc'  => __( 'Show a welcome tooltip on top of global conversation button.', 'mcfgf' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'conversation_notification_background_color',
                    'label'   => __( 'Background Color', 'mcfgf' ),
                    'desc'    => __( 'Background color for welcome tooltip message', 'mcfgf' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'conversation_notification_font_color',
                    'label'   => __( 'Text Color', 'mcfgf' ),
                    'desc'    => __( 'Text color for welcome tooltip message', 'mcfgf' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                // Global Conversation Header
                array(
                    'name'        => 'h2_conversation_header',
                    'desc'        => __( '', 'mcfgf' ),
                    'label'       => __( '<h4>Global Conversation Header</h4>', 'mcfgf' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'enable_conversation_header',
                    'label' => __( 'Conversation Header', 'mcfgf' ),
                    'desc'  => __( 'Show a conversation header when global conversation button enabled.', 'mcfgf' ),
                    'type'  => 'checkbox'
                ),
                // array(
                //     'name'              => 'conversation_header_title',
                //     'label'             => __( 'Title for Conversation Header', 'mcfgf' ),
                //     // 'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter a title', 'mcfgf' ),
                //     'type'              => 'text',
                //     'default'           => 'Conversation Title',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                // array(
                //     'name'              => 'conversation_header_sub_title',
                //     'label'             => __( 'Sub Title for Conversation Header', 'mcfgf' ),
                //     // 'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter a sub title', 'mcfgf' ),
                //     'type'              => 'text',
                //     'default'           => 'Conversation Sub Title',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                // array(
                //     'name'              => 'conversation_header_username',
                //     'label'             => __( 'Display Name for Conversation Header', 'mcfgf' ),
                //     // 'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter display name for Robot', 'mcfgf' ),
                //     'type'              => 'text',
                //     'default'           => 'Hi',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                
                // array(
                //     'name'              => 'conversation_header_welcome_message',
                //     'label'             => __( 'Welcome message for Conversation Header', 'mcfgf' ),
                //     // 'desc'              => __( 'Default: Type your answer here', 'mcfgf' ),
                //     'placeholder'       => __( 'Please enter a welcome message', 'mcfgf' ),
                //     'type'              => 'text',
                //     'default'           => 'Welcome, this is John assistant serve you.',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                array(
                    'name'    => 'conversation_header_background_color',
                    'label'   => __( 'Background Color', 'mcfgf' ),
                    'desc'    => __( 'Background color for conversation header', 'mcfgf' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'conversation_header_font_color',
                    'label'   => __( 'Text Color', 'mcfgf' ),
                    'desc'    => __( 'Text color for conversation header', 'mcfgf' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'        => 'h2_custom',
                    'desc'        => __( '', 'mcfgf' ),
                    'label'       => __( '<h4>Custom</h4>', 'mcfgf' ),
                    'type'        => 'html'
                ),
                array(
                    'name'        => 'custom_css',
                    'label'       => __( 'Custom Css', 'mcfgf' ),
                    'desc'        => __( 'Custom Css code', 'mcfgf' ),
                    'type'        => 'textarea'
                ),
//                 array(
//                     'name'              => 'number_input',
//                     'label'             => __( 'Number Input', 'mcfgf' ),
//                     'desc'              => __( 'Number field with validation callback `floatval`', 'mcfgf' ),
//                     'placeholder'       => __( '1.99', 'mcfgf' ),
//                     'min'               => 0,
//                     'max'               => 100,
//                     'step'              => '0.01',
//                     'type'              => 'number',
//                     'default'           => 'Title',
//                     'sanitize_callback' => 'floatval'
//                 ),
//                 array(
//                     'name'        => 'textarea',
//                     'label'       => __( 'Textarea Input', 'mcfgf' ),
//                     'desc'        => __( 'Textarea description', 'mcfgf' ),
//                     'placeholder' => __( 'Textarea placeholder', 'mcfgf' ),
//                     'type'        => 'textarea'
//                 ),
//                 array(
//                     'name'        => 'html',
//                     'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'mcfgf' ),
//                     'type'        => 'html'
//                 ),
//                 array(
//                     'name'  => 'checkbox',
//                     'label' => __( 'Checkbox', 'mcfgf' ),
//                     'desc'  => __( 'Checkbox Label', 'mcfgf' ),
//                     'type'  => 'checkbox'
//                 ),
//                 array(
//                     'name'    => 'radio',
//                     'label'   => __( 'Radio Button', 'mcfgf' ),
//                     'desc'    => __( 'A radio button', 'mcfgf' ),
//                     'type'    => 'radio',
//                     'options' => array(
//                         'yes' => 'Yes',
//                         'no'  => 'No'
//                     )
//                 ),
//                 array(
//                     'name'    => 'selectbox',
//                     'label'   => __( 'A Dropdown', 'mcfgf' ),
//                     'desc'    => __( 'Dropdown description', 'mcfgf' ),
//                     'type'    => 'select',
//                     'default' => 'no',
//                     'options' => array(
//                         'yes' => 'Yes',
//                         'no'  => 'No'
//                     )
//                 ),
//                 array(
//                     'name'    => 'password',
//                     'label'   => __( 'Password', 'mcfgf' ),
//                     'desc'    => __( 'Password description', 'mcfgf' ),
//                     'type'    => 'password',
//                     'default' => ''
//                 ),
//                 array(
//                     'name'    => 'file',
//                     'label'   => __( 'File', 'mcfgf' ),
//                     'desc'    => "<div class='image-preview-wrapper'>
//         <img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px;'>
// </div>",
//                     'type'    => 'file',
//                     'default' => '',
//                     'options' => array(
//                         'button_label' => 'Choose Image'
//                     )
//                 )
            ),
            // 'mcfgf_advanced' => array(
            //     array(
            //         'name'    => 'color',
            //         'label'   => __( 'Color', 'mcfgf' ),
            //         'desc'    => __( 'Color description', 'mcfgf' ),
            //         'type'    => 'color',
            //         'default' => ''
            //     ),
            //     array(
            //         'name'    => 'password',
            //         'label'   => __( 'Password', 'mcfgf' ),
            //         'desc'    => __( 'Password description', 'mcfgf' ),
            //         'type'    => 'password',
            //         'default' => ''
            //     ),
            //     array(
            //         'name'    => 'wysiwyg',
            //         'label'   => __( 'Advanced Editor', 'mcfgf' ),
            //         'desc'    => __( 'WP_Editor description', 'mcfgf' ),
            //         'type'    => 'wysiwyg',
            //         'default' => ''
            //     ),
            //     array(
            //         'name'    => 'multicheck',
            //         'label'   => __( 'Multile checkbox', 'mcfgf' ),
            //         'desc'    => __( 'Multi checkbox description', 'mcfgf' ),
            //         'type'    => 'multicheck',
            //         'default' => array('one' => 'one', 'four' => 'four'),
            //         'options' => array(
            //             'one'   => 'One',
            //             'two'   => 'Two',
            //             'three' => 'Three',
            //             'four'  => 'Four'
            //         )
            //     ),
            // )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        // echo '<p>';
        // echo '<b>WeDevs_Settings_API Class File:</b>'.$this->settings_api_file;
        // echo '</p>';
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

    /**
    * Get the value of a settings field
    *
    * @param string $option settings field name
    * @param string $section the section name this field belongs to
    * @param string $default default text if it's not found
    * @return mixed
    */
    function get_option( $option, $section, $default = '' ) {
     
        $options = get_option( $section );
     
        if ( isset( $options[$option] ) ) {
        return $options[$option];
        }
     
        return $default;
    }

}
$settings = new Settings_MagicConversationForGravityForms();
endif;
