<?php

/**
 * Magic Conversation For Gravity Forms Side Form class
 *
 * @author Flannian Feng
 */

$is_mcfwc = false;

// auto login

function yakker_convert_merged_tags($form_id, $mcfgf_questions) {
    // Replacing field variables: {FIELD_LABEL:FIELD_ID} {My Field:2}.
    preg_match_all( '/{[^{]*?:(\d+(\.\d+)?)(:(.*?))?}/mi', $mcfgf_questions, $matches, PREG_SET_ORDER );
    if ( is_array( $matches ) ) {
        foreach ( $matches as $match ) {
            $input_id = $match[1];
            $mcfgf_questions = str_replace( $match[0], "{mc_".$form_id."_".$input_id."}", $mcfgf_questions );

            
            
        }
    }

    // Handle orders count calculation
    if(strpos($mcfgf_questions, '{yakker-orders-count}') !== false) {
        $mcfgf_questions = str_replace( '{yakker-orders-count}', "just a test", $mcfgf_questions );
    }
    return $mcfgf_questions;
}

/**
 * @param $image_path
 * @return bool|mixed
 */
function yakker_get_image_mime_type($image_path)
{
    $mimes  = array(
        IMAGETYPE_GIF => "image/gif",
        IMAGETYPE_JPEG => "image/jpg",
        IMAGETYPE_PNG => "image/png",
        IMAGETYPE_SWF => "image/swf",
        IMAGETYPE_PSD => "image/psd",
        IMAGETYPE_BMP => "image/bmp",
        IMAGETYPE_TIFF_II => "image/tiff",
        IMAGETYPE_TIFF_MM => "image/tiff",
        IMAGETYPE_JPC => "image/jpc",
        IMAGETYPE_JP2 => "image/jp2",
        IMAGETYPE_JPX => "image/jpx",
        IMAGETYPE_JB2 => "image/jb2",
        IMAGETYPE_SWC => "image/swc",
        IMAGETYPE_IFF => "image/iff",
        IMAGETYPE_WBMP => "image/wbmp",
        IMAGETYPE_XBM => "image/xbm",
        IMAGETYPE_ICO => "image/ico");

    if (($image_type = exif_imagetype($image_path))
        && (array_key_exists($image_type ,$mimes)))
    {
        return $mimes[$image_type];
    }
    else
    {
        return FALSE;
    }
}

function yakker_get_data_uri($imageUrl) {
    // $finfo = new finfo(FILEINFO_MIME_TYPE);
    // $type = $finfo->file($imageUrl);
    // 
    if(strpos($imageUrl, "http") !== 0) {
        $imageUrl = home_url($imageUrl);
    }

    $typeString = yakker_get_image_mime_type($imageUrl);
    $ch = curl_init($imageUrl);
    curl_setopt($ch, CURLOPT_URL, $imageUrl); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return 'data:'.$typeString.';base64,'.base64_encode($output);
}

function yakker_get_form_json($form_id, $form, $user_id)
{
    // require_once( GFCommon::get_base_path() . '/form_display.php' );
    $settings = get_option('mcfgf_basics',false);
    // unset($settings['textForSkip']);
    // 

    if(!isset($settings['conversation_typing_hint'])) $settings['conversation_typing_hint'] = '%s is typing';
    if(!isset($settings['conversation_question_required'])) $settings['conversation_question_required'] = 'This question is required.';

    if(!isset($settings['conversation_waiting_payment'])) $settings['conversation_waiting_payment'] = 'Waiting for the payment process to complete.';
    if(!isset($settings['conversation_finished'])) $settings['conversation_finished'] = 'Conversation finished.';

    if(!isset($settings['conversation_submitting'])) $settings['conversation_submitting'] = 'Submitting form data.';

    if(!isset($settings['conversation_quantity_required'])) $settings['conversation_quantity_required'] = 'Please enter quantity.';

    if(!isset($settings['conversation_multipage_confirm'])) $settings['conversation_multipage_confirm'] = 'Confirm';


    if(!isset($settings['conversation_input_placeholder'])) $settings['conversation_input_placeholder'] = 'Type your answer here';
    if(!isset($settings['conversation_input_placeholder_for_radio'])) $settings['conversation_input_placeholder_for_radio'] = 'Please choose from above';
    if(!isset($settings['conversation_please_select_an_option'])) $settings['conversation_please_select_an_option'] = 'Please select an option.';
    if(!isset($settings['textForSkip'])) $settings['textForSkip'] = 'Skip';
    if(!isset($settings['textForSend'])) $settings['textForSend'] = '<i class="fas fa-paper-plane"></i>';
    if(!isset($settings['textForMonthNames'])) $settings['textForMonthNames'] = 'January, February, March, April, May, June, July, August, September, October, November, December';
    if(!isset($settings['placeholderForDate'])) $settings['placeholderForDate'] = 'Please choose a date with picker';

    if(!isset($settings['placeholderForTime'])) $settings['placeholderForTime'] = 'Please choose a time with picker';

    if(!isset($settings['placeholderForAddress'])) $settings['placeholderForAddress'] = 'Please click to fill an address';
    if(!isset($settings['placeholderForName'])) $settings['placeholderForName'] = 'Please click to fill your name';
    if(!isset($settings['placeholderForCreditcard'])) $settings['placeholderForCreditcard'] = 'Please click to fill credit card info.';

    if(!isset($settings['placeholderForPassword'])) $settings['placeholderForPassword'] = 'Please click to fill a password';
    // $settings['placeholderForSingleProduct'] = 'Please click to choose a product';
    if(!isset($settings['placeholderForFileUpload'])) $settings['placeholderForFileUpload'] = 'Please click to select a file';
    if(!isset($settings['placeholderForChainedselect'])) $settings['placeholderForChainedselect'] = 'Please select with picker';
    if(!isset($settings['placeholderForConversationFinished'])) $settings['placeholderForConversationFinished'] = 'Type R to reset';
    $mcfgf_options = get_option('mcfgf_conversation_generator', false);
    $avatar_robot = $mcfgf_options['avatar_robot'];
    // $current_user_id = get_current_user_id();
    $them_options = get_option('mcfgf_conversation_generator', false);
    $address_field = new GF_Field_Address();
    $items = $address_field->get_countries();
    global $yakker_json;
    // $them_options['css_options']['containerBackgroundColor'] = '#ff0000';
    $json = array(
        'type' => 'gf',
        'REFERER' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        'adminAjaxUrl' => admin_url('admin-ajax.php'),

        'home_url' => home_url('/'),
        'file_uploader_url' => plugins_url( "yakker-file-uploader", __FILE__ ).'?ver='.MCFGFP_VER,
        'gf' => (array)$form,
        'robot' => array(
            'avatar' => yakker_get_data_uri($them_options['avatar_robot']),
        	// 'avatarUrl' => $them_options['avatar_robot'],
            'nickName' => isset($form['gf_conversation']['conversation_robot_display_name']) ? $form['gf_conversation']['conversation_robot_display_name'] : 'Magic Conversation'
        ),
        'user' => $user_id > 0 ? array(
            'avatarUrl' => get_avatar_url($user_id),
            'userId' => $user_id
        ) : array(
            'avatar' => yakker_get_data_uri($them_options['avatar_user']),
            'userId' => $user_id
            // 'avatarUrl' => $them_options['avatar_user'], 
        ),
        'theme' => $them_options['css_options'],
        'config' => $settings,
        'countries' => $items,
        'state' => GFFormDisplay::get_state( $form, array() ),
        'unique_id' => uniqid(), //GFFormsModel::get_form_unique_id( $form_id )
        'gform_upload_page_slug' => GFCommon::get_upload_page_slug(),
        'gf_currency_config' => RGCurrency::get_currency( GFCommon::get_currency() )
    );

    $json['css'] = mcfgf_build_css_code(json_decode($them_options['css_options'], true)).$settings['custom_css'];

    // return cart information if WooCommerce exists
    // if ( class_exists( 'WooCommerce' ) ) {
    //     $json['cart'] = yakker_woocommerce_get_cart();
    //     $json['orders'] = yakker_woocommerce_get_user_orders_for_radio_choices($user_id);
    //     $json['orders_user_id'] = $user_id;
    //     $json['scripts'] = array(
    //         // 'https://code.jquery.com/jquery-3.4.0.slim.min.js',
    //         home_url('/wp-includes/js/jquery/jquery.js?ver=1.12.4'),
    //         home_url('/wp-content/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js?ver=2.70'),
    //         home_url('/wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.js')
    //     );

    //     $json['styles'] = array(
    //         plugins_url( 'assets/css/woo-mobile.css', __FILE__ ).'?ver='.MCFGFP_VER
    //     );
    //     $json['script_params'] = array(
    //         'wc_add_to_cart_params' => array(
    //             'ajax_url'                => home_url(WC()->ajax_url()),
    //             'wc_ajax_url'             => home_url(WC_AJAX::get_endpoint( '%%endpoint%%' )),
    //             'i18n_view_cart'          => esc_attr__( 'View cart', 'woocommerce' ),
    //             'cart_url'                => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null ),
    //             'is_cart'                 => true || is_cart(),
    //             'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ),
    //         )
    //     );
    // }

    $yakker_json = $json;

    global $wp_embed;
    add_filter('woocommerce_rest_check_permissions', 'yakker_woocommerce_rest_check_permissions', 10, 4);

    add_filter('woocommerce_rest_prepare_product_variation_object', 'yakker_woocommerce_rest_prepare_product_variation_object', 10, 3);

    add_filter('woocommerce_product_add_to_cart_url', 'yakker_woocommerce_product_add_to_cart_url', 10, 2);

    add_filter('woocommerce_rest_prepare_product_object', 'yakker_woocommerce_rest_prepare_product_object', 10, 3);

    foreach ($json['gf']['fields'] as $key => $value) {
        $field = $json['gf']['fields'][$key];
        if(isset($field['mcfgf_questions']) || isset($field['content'])) {
            $mcfgf_questions = isset($field['mcfgf_questions']) ? $field['mcfgf_questions'] : $field['content'];
            $mcfgf_questions = do_shortcode(stripslashes($wp_embed->run_shortcode($mcfgf_questions)));

            //
            
            $mcfgf_questions = yakker_convert_merged_tags($form_id, $mcfgf_questions);
            //{mc_62_1}

            if($field['type'] !== 'html') {
                $mcfgf_questions = GFCommon::replace_variables( $mcfgf_questions, $form, false, false, false );
            } else if ($field['cssClass'] === 'yakker-view-cart') {
                error_reporting(E_ALL);
                //yakker_woocommerce_get_cart(), $mcfgf_questions, $field['mcfgf_woocommerce_product_template_normal'], $field['mcfgf_woocommerce_product_template_selected']
                // 获取cart的HTML
                $mcfgf_mini_cart_json = yakker_woocommerce_get_mini_cart_json($mcfgf_questions, $field['mcfgf_woocommerce_product_template_selected']);

                $mcfgf_questions = $mcfgf_mini_cart_json['fragments']['div.widget_shopping_cart_content'];
                $json['gf']['fields'][$key]['mcfgf_cart_hash'] = $mcfgf_mini_cart_json['cart_hash'];
            }


            $mcfgf_questions = str_replace('http://{mc-login-url}', wp_login_url(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''), $mcfgf_questions); 
            
            
            $json['gf']['fields'][$key]['mcfgf_questions'] = $mcfgf_questions;

        }

        if(!empty($field['calculationFormula'])) {
            $mcfgf_questions = $field['calculationFormula'];
            
            $mcfgf_questions = yakker_convert_merged_tags($form_id, $mcfgf_questions);
            //{mc_62_1}

            $mcfgf_questions = GFCommon::replace_variables( $mcfgf_questions, $form, false, false, false );
            $json['gf']['fields'][$key]['calculationFormula'] = $mcfgf_questions;
        }
// $ddd = in_array($field['type'], array('radio', 'select')) && isset($field['cssClass']) && $field['cssClass'] === 'mc-populate-orders';
// echo $field['type'];
// echo $field['cssClass']
// var_dump($ddd);
        if(in_array($field['type'], array('radio', 'select')) && isset($field['cssClass']) && $field['cssClass'] === 'mc-populate-orders') {
            $json = yakker_handle_populate_orders_for_radio_choices_with_prefix($json, $key);
        }

        if(in_array($field['type'], array('radio', 'select')) && isset($field['mcfgf_enable_woocommerce_product']) && $field['mcfgf_enable_woocommerce_product']) {

            
            

            foreach ($field['choices'] as $item) {
                if(!is_numeric($item['value'])) continue;
                $request = new WP_REST_Request( 'GET', '/wc/v2/products/'.$item['value'] );
                // $request->set_query_params( [ 'per_page' => 12 ] );
                $response = rest_do_request( $request );
                $server = rest_get_server();
                $data = $server->response_to_data( $response, false );

                $variations = array();
                foreach ($data['variations'] as $variation_id) {
                    $request_v = new WP_REST_Request( 'GET', '/wc/v2/products/'.$item['value'].'/variations/'.$variation_id );
                    // $request->set_query_params( [ 'per_page' => 12 ] );
                    $response_v = rest_do_request( $request_v );
                    $server_v = rest_get_server();
                    $data_v = $server_v->response_to_data( $response_v, false );
                    $variations[] = $data_v;
                }
                if(count($variations) === 0) {

                }
                $data['variations'] = $variations;
                // $json = wp_json_encode( $data );
                $json['gf']['woo_product_'.$item['value']] = $data;

                // $product = wc_get_product($item['value']);
            }

            

        }

        $json['gf']['fields'][$key]['value'] = RGFormsModel::get_field_value( $json['gf']['fields'][$key], array() );

        $json['gf']['fields'][$key]['value'] = RGFormsModel::get_field_value( $json['gf']['fields'][$key], array() );

        if(isset($is_mcfwc) && $is_mcfwc) {
            if(!function_exists('yakker_gform_replace_merge_tags')) {
                function yakker_gform_replace_merge_tags($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
                    if ( strpos( $text, '{' ) !== false ) {

                        //logged in user info
                        global $yakker_json;

                        preg_match_all( "/\{cart:(.*?)\}/", $text, $matches, PREG_SET_ORDER );
                        foreach ( $matches as $match ) {
                            $full_tag = $match[0];
                            $property = $match[1];

                            if($full_tag === '{cart:is_empty}') {
                                $is_empty = $yakker_json && $yakker_json['cart'] && count($yakker_json['cart']['items']) > 0 ? 'no' : 'yes';
                                $text = str_replace( $full_tag, $is_empty, $text );
                            }
                            

                            // $value = $current_user->get( $property );
                            // $value = $url_encode ? urlencode( $value ) : $value;

                            // $text = str_replace( $full_tag, $value, $text );
                        }

                        preg_match_all( "/\{orders:(.*?)\}/", $text, $matches, PREG_SET_ORDER );
                        foreach ( $matches as $match ) {
                            $full_tag = $match[0];
                            $property = $match[1];

                            if($full_tag === '{orders:is_empty}') {
                                $is_empty = $yakker_json && $yakker_json['orders'] && count($yakker_json['orders']) > 0 ? 'no' : 'yes';
                                $text = str_replace( $full_tag, $is_empty, $text );
                            }
                            

                            // $value = $current_user->get( $property );
                            // $value = $url_encode ? urlencode( $value ) : $value;

                            // $text = str_replace( $full_tag, $value, $text );
                        }
                    }

                    return $text;
                }
            }
            

            add_filter('gform_replace_merge_tags', 'yakker_gform_replace_merge_tags', 10, 7);
        }
        

        $json['gf']['fields'][$key]['defaultValue'] = GFCommon::replace_variables_prepopulate($json['gf']['fields'][$key]['defaultValue']);
        if($field['type']=='fileupload' && empty($field['maxFileSize'])) {
            $max_upload_size = (int) wp_max_upload_size() / ( 1024 * 1024 );
            $json['gf']['fields'][$key]['maxFileSize'] = $max_upload_size;
        }

        $json['gf']['fields'][$key]['maxFileSizeMessage'] = sprintf( __( 'Maximum upload file size: %s.' ), $json['gf']['fields'][$key]['maxFileSize'].' MB');
    }

    mcfgf_add_free_version_welcome_message($json);

    remove_filter('woocommerce_rest_check_permissions', 'yakker_woocommerce_rest_check_permissions');
    remove_filter('woocommerce_rest_prepare_product_variation_object', 'yakker_woocommerce_rest_prepare_product_variation_object');
    remove_filter('woocommerce_rest_prepare_product_object', 'yakker_woocommerce_rest_prepare_product_object');
    remove_filter('woocommerce_product_add_to_cart_url', 'yakker_woocommerce_product_add_to_cart_url');

    if(isset($json['gf']['gf_conversation']['submission_data_model'])) {
        $json['gf']['gf_conversation']['submission_data_model'] = json_decode(yakker_convert_merged_tags($form_id, base64_decode($json['gf']['gf_conversation']['submission_data_model']), true));
    }

    if(isset($json['gf']['gf_conversation']['welcome_page_template'])) {
        $json['gf']['gf_conversation']['welcome_page_template'] = mcfgf_parseMergeTags($form, $json['gf']['gf_conversation']['welcome_page_template']);
    }

    if(class_exists('GFP_Stripe_Data')) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once(dirname(__FILE__).'/yakker-stripe.php');
        $yakker_gfp_stripe = new Yakker_GFP_Stripe();
        $json['gf']['stripe_config'] = $yakker_gfp_stripe->get_stripe_config($form);
    }
    return $json;
}
?>


