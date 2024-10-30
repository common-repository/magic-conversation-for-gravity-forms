<?php

/**
 * Magic Conversation For Gravity Forms Side Form class
 *
 * @author Flannian Feng
 */

$is_mcfwc = false;

// auto login
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

// echo get_current_user_id();

$http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: application/json; charset=utf-8');
    // echo '1111';
    die();
}

header("Access-Control-Allow-Origin: $http_origin");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');


if(!empty($_GET['gf_paypal_return'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, shrink-to-fit=no"><style>
            .yakker-loading {
              position: absolute;
              left:0px;
              right:0px;
              top:0px;
              bottom: 0px;
              z-index: 1;
              background: #fcfcfc url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgICAgIHdpZHRoPSIyNHB4IiBoZWlnaHQ9IjMwcHgiIHZpZXdCb3g9IjAgMCAyNCAzMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTAgNTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4gICAgPHJlY3QgeD0iMCIgeT0iMTMiIHdpZHRoPSI0IiBoZWlnaHQ9IjUiIGZpbGw9IiMzMzMiPiAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9ImhlaWdodCIgYXR0cmlidXRlVHlwZT0iWE1MIiAgICAgICAgdmFsdWVzPSI1OzIxOzUiICAgICAgICAgYmVnaW49IjBzIiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9InkiIGF0dHJpYnV0ZVR5cGU9IlhNTCIgICAgICAgIHZhbHVlcz0iMTM7IDU7IDEzIiAgICAgICAgYmVnaW49IjBzIiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICA8L3JlY3Q+ICAgIDxyZWN0IHg9IjEwIiB5PSIxMyIgd2lkdGg9IjQiIGhlaWdodD0iNSIgZmlsbD0iIzMzMyI+ICAgICAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iaGVpZ2h0IiBhdHRyaWJ1dGVUeXBlPSJYTUwiICAgICAgICB2YWx1ZXM9IjU7MjE7NSIgICAgICAgICBiZWdpbj0iMC4xNXMiIGR1cj0iMC42cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+ICAgICAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0ieSIgYXR0cmlidXRlVHlwZT0iWE1MIiAgICAgICAgdmFsdWVzPSIxMzsgNTsgMTMiICAgICAgICBiZWdpbj0iMC4xNXMiIGR1cj0iMC42cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+ICAgIDwvcmVjdD4gICAgPHJlY3QgeD0iMjAiIHk9IjEzIiB3aWR0aD0iNCIgaGVpZ2h0PSI1IiBmaWxsPSIjMzMzIj4gICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJoZWlnaHQiIGF0dHJpYnV0ZVR5cGU9IlhNTCIgICAgICAgIHZhbHVlcz0iNTsyMTs1IiAgICAgICAgIGJlZ2luPSIwLjNzIiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9InkiIGF0dHJpYnV0ZVR5cGU9IlhNTCIgICAgICAgIHZhbHVlcz0iMTM7IDU7IDEzIiAgICAgICAgYmVnaW49IjAuM3MiIGR1cj0iMC42cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+ICAgIDwvcmVjdD4gIDwvc3ZnPg==) center center no-repeat;
              background-size: 32px 32px;
            }
        </style>
    </head>
    <body>
        <div class="yakker-loading"></div>
    </body>
</html>';
    return;
}
else {
    header('Content-Type: application/json; charset=utf-8');
}


// global $wp;
// $form_id = absint( $wp->query_vars['form_id'] );
// echo $form_id;
// die();
// require_once( GFCommon::get_base_path() . '/form_display.php' );
// $form = GFFormDisplay::get_form( $form_id );
$form = GFAPI::get_form( $form_id );

foreach ($form['fields'] as $key => $field) {
	// echo $field->type;
	if ( $field->type == 'post_category' ) {
		$form['fields'][$key] = GFCommon::add_categories_as_choices( $field, '' );
	}
}


// Handle old orders pre-populate.
$form = gform_pre_render_yakker_handle_old_orders($form);


// $form = gf_apply_filters( array( 'gform_pre_process', $form['id'] ), $form );
$is_valid_form = $form && $form['is_active'];
$form_id = $form['id'];
require_once( GFCommon::get_base_path() . '/form_display.php' );
$submission_info = isset( GFFormDisplay::$submission[ $form_id ] ) ? GFFormDisplay::$submission[ $form_id ] : false;
$is_addon_confirmation = $submission_info ? rgar( $submission_info, 'is_confirmation' ) : false;


// $is_valid_yakker = $yakker_id && $yakker_metas && $yakker_title && $yakker_status === 'publish';
// var_dump($yakker_id);
// var_dump($yakker_metas);
// var_dump($yakker_title);
// var_dump($yakker_status);
// die();
// echo $form_id;
$validation_field_id = isset( $_POST['gform_validation'] ) ? absint( $_POST['gform_validation'] ) : 0;

$raw_field_id = $validation_field_id ? $_POST['gform_validation'] : 0;

$is_submitting = isset( $_POST['is_submit_'.$form_id] ) ? absint( $_POST['is_submit_'.$form_id] ) : 0;

$is_checking_payment_status = isset($_GET['sub_action']) && $_GET['sub_action'] === 'payment_status' && isset($_GET['entry_id']);

if ( $is_valid_form && $validation_field_id ) {
    
    

    $field_values = RGForms::post( 'gform_field_values' );

    global $yak_gf_field_validation_result;
    global $yak_gf_field_validation_field_label;
    global $yak_gf_validation_result;
    $yak_gf_field_validation_result = false;
    $yak_gf_validation_result = false;
    function yk_gform_field_validation_filter($validation_result, $value, $form, $field ){
        global $yak_gf_field_validation_result;
        global $yak_gf_field_validation_field_label;

        $yak_gf_field_validation_result = $validation_result;
        $yak_gf_field_validation_field_label = strtolower($field->label);

        if(!($validation_result['is_valid']) && empty($validation_result['message'])) {
            if ( $field->type ==='phone' && $field->failed_validation ) {
                $phone_format = $field->get_phone_format();
                if ( rgar( $phone_format, 'instruction' ) ) {
                    $yak_gf_field_validation_result['message'] = sprintf( "%s %s", esc_html__( 'Phone format:', 'gravityforms' ), $phone_format['instruction'] );
                }
            } else {
                $yak_gf_field_validation_result['message'] = 'Unknown Error';
            }
        } else if (!$validation_result['is_valid'] && $field->type ==='fileupload' && $field->multipleFiles === true && !empty($gform_uploaded_files_str = stripslashes($_POST['gform_uploaded_files']))) {
            $gform_uploaded_files = json_decode($gform_uploaded_files_str, true);
            $field_id = 'input_'.$field->id;
            if(!empty($gform_uploaded_files[$field_id])) {
                $yak_gf_field_validation_result['is_valid'] = true;
            }
            $yak_gf_field_validation_result['message'] = '';//$gform_uploaded_files_str;
            $yak_gf_field_validation_result['debug'] = $gform_uploaded_files;
            // unset($yak_gf_field_validation_result['message']);
        }
        // echo 'abc';
        // var_dump($validation_result);
        // die();
        // $yak_gf_field_validation_result = array_merge($validation_result, array(
        //     'fieldLabel' => strtolower($field->label)
        // ));
        //$yak_gf_field_validation_result['fieldLabel'] = '3333333333333'; //strtolower($field->label);
        return $validation_result;
    }
    // var_dump($form_id);
    // var_dump($validation_field_id);

    add_filter( 'gform_field_validation_'.$form_id.'_'.$validation_field_id, 'yk_gform_field_validation_filter', 10, 4 );

    function yk_gform_validation_filter($validation_result){
        global $yak_gf_validation_result;
        $yak_gf_validation_result = $validation_result;
        return $validation_result;
    }
    add_filter( 'gform_validation_'.$form_id, 'yk_gform_validation_filter', 10, 1 );
    // GFFormDisplay::process_form( $form_id );
    GFFormsModel::set_uploaded_files( $form_id );
    GFFormDisplay::validate( $form, $field_values );

    $json = $yak_gf_field_validation_result;

    // $json['$field_values'] = $field_values;
    
    // $json['post']= $_POST;

    function get_field_id_value_after_validation($raw_field_id) {
        $json = array();
        $json['fieldId'] = 'input_'.$raw_field_id;
        $post_input_name = str_replace(".", "_", $json['fieldId']);
        $json['fieldValue'] = is_array($_POST[$post_input_name]) ? $_POST[$post_input_name] : stripslashes($_POST[$post_input_name]);
        return $json;
    }

    function yak_update_validation_extra_info($json, $raw_field_id) {
        if(strpos($raw_field_id, ",") !== false ) {
            $field_ids = explode(",", $raw_field_id);
            $json['fieldId'] = array();
            $json['fieldValue'] = array();
            foreach ($field_ids as $field_id) {
                $info = get_field_id_value_after_validation($field_id);
                $json['fieldId'][] = $info['fieldId'];
                $json['fieldValue'][] = $info['fieldValue'];
            }
            $json['fieldIdMain'] = intval($field_ids[0]);
        }
        else {
            $info = get_field_id_value_after_validation($raw_field_id);
            $json['fieldId'] = $info['fieldId'];
            $json['fieldValue'] = $info['fieldValue'];
            $json['fieldIdMain'] = intval($raw_field_id);
        }
        return $json;
    }

    if($yak_gf_field_validation_result) {
        //发生错误
        $json = $yak_gf_field_validation_result;
        $json['form_id'] = $form_id;

        
        $json = yak_update_validation_extra_info($json, $raw_field_id);
        
        
        
        $json['fieldLabel'] = $yak_gf_field_validation_field_label;


        if ( $is_mcfwc && class_exists( 'WooCommerce' ) ) {
            require_once(dirname(__FILE__) .'/yakker-form.php');
            $json_form = yakker_get_form_json($form_id, $form, $user_id);
            $json['cart'] = $json_form['cart'];
            $json['orders'] = $json_form['orders'];
            $json['orders_user_id'] = $json_form['orders_user_id'];
            $json['gf'] = $json_form['gf'];

            if(strpos($json['fieldValue'], 'mc-ajax') === 0) {
                require_once(dirname(__FILE__) .'/yakker-mcfwc.php');
                $json = mcfwc_handle_mc_ajax_validation($json, $raw_field_id);
                $json['cart'] = $json_form['cart'];
                $json['orders'] = $json_form['orders'];
                $json['orders_user_id'] = $json_form['orders_user_id'];
                $json['gf'] = $json_form['gf'];
            }

            // mc-ajax-save-cart-as-reorder-list
        }

        // array(
        //     'is_valid' => $field->failed_validation ? false : true,
        //     'message'  => $field->validation_message
        // )
    }
    else {
        $is_valid = false;
        if($yak_gf_validation_result) {
            
            foreach($yak_gf_validation_result['form']['fields'] as $field) {
                if($field->failed_validation) {
                    break;
                }
                if(intval($field->id) === intval($validation_field_id)) {
                    $is_valid = true;
                    break;
                }
            }
        }
        $json = array(
            'is_valid' => $is_valid,
            'form_id' => $form_id,
            'fieldId' => 'input_'.$raw_field_id,
            'message'  => is_valid ? '' : 'Something went wrong, please contact technical support.',
            'uploaded_files' => GFFormsModel::$uploaded_files[ $form_id ],
            'form' => $yak_gf_validation_result ? $yak_gf_validation_result['form'] : null
        );
        
        $json = yak_update_validation_extra_info($json, $raw_field_id);
    }
}
// Handle form submission
else if($is_valid_form && $is_submitting) {
    $_POST['gform_submit'] = ''.$form_id;
    // echo 'yakker';
    // die();
    add_filter( 'gform_suppress_confirmation_redirect', '__return_true' );

    add_filter( 'gform_mollie_return_url', 'yakker_custom_gf_mollie_return_url', 10, 4 );

    //Add support of GF Mollie by Indigo plugin
    function yakker_custom_gf_mollie_return_url( $url, $form_id, $lead_id, $query ) {
        if(isset($_SERVER['HTTP_REFERER'])) {
            $parts = parse_url($url);
            parse_str($parts['query'], $query);
            $url = add_query_arg( 'gf_mollie_return', $query['gf_mollie_return'], $_SERVER['HTTP_REFERER'] );
        }
        return $url;
    }
    // error_reporting(E_ALL);
    //     ini_set('display_errors', 1);
	// require_once( GFCommon::get_base_path() . '/form_display.php' );
    // require_once( GFCommon::get_base_path() . '/forms_model.php' );
    // require_once( plugin_dir_path( __FILE__ ) . 'forms_model.php' );

    // function yk_gform_suppress_confirmation_redirect($suppress) {
    //     echo 'yk_gform_suppress_confirmation_redirect';
    //     die();
    //     return true;
    //     // if($header_list['Location']) {

    //     // }
    // }

    // add_filter( 'gform_suppress_confirmation_redirect', 'yk_gform_suppress_confirmation_redirect', 99, 1);
    


	// GFFormDisplay::process_form( $form_id );
    $hasError = false;
    try {
        GFFormDisplay::process_form( $form_id );
    } catch ( Exception $ex ) {
        // remove_filter( 'gform_suppress_confirmation_redirect', 'yk_gform_suppress_confirmation_redirect' );
        $hasError = array('code' => $ex->getCode(), 'message' => $ex->getMessage());
    }

    remove_filter( 'gform_suppress_confirmation_redirect', '__return_true' );
    remove_filter( 'gform_mollie_return_url', 'yakker_custom_gf_mollie_return_url');
    // remove_filter( 'gform_suppress_confirmation_redirect', 'yk_gform_suppress_confirmation_redirect' );

    if(!$hasError) {
        $submission = GFFormDisplay::$submission;

        $confirmation_message = $submission[$form_id]['confirmation_message'];

        if(isset($confirmation_message['redirect']) && isset($form['gf_conversation']) && isset($form['gf_conversation']['conversation_action_on_page_or_redirect']) && $form['gf_conversation']['conversation_action_on_page_or_redirect'] === 'content') {
            $confirmation_message = file_get_contents($confirmation_message['redirect']);
        }

        // handle error message, show the first error message.
        if(!$submission[$form_id]['is_valid'] && empty($confirmation_message)) {
            $fields = $submission[$form_id]['form']['fields'];
            $i = 0;
            foreach ($fields as $index => $field) {
                if(array_search($field['type'], array('html')) !== false) continue;
                if($i > 0) {
                    $confirmation_message .= "\n";
                    if($field['validation_message'] == esc_html__( 'At least one field must be filled out', 'gravityforms' )) {
                        continue;
                    }
                }

                if($field['failed_validation']) {
                    $confirmation_message .= $field['validation_message'];
                }
                $i+=0;
            }
        }

        if(isset($confirmation_message['redirect2'])) {
            $confirmation_message['redirect'] = $confirmation_message['redirect2'];
            unset($confirmation_message['redirect2']);
        }

        $json = array(
            'confirmation_message' => $confirmation_message,
            'is_valid' => $submission[$form_id]['is_valid'],
            'form_id' => $form_id,
            'debug' => $submission[$form_id]
        );

        if($submission[$form_id]['is_valid']) {
            $json['entry_id'] = $submission[$form_id]['lead']['id'];
        }
    } else {
        $json = array(
            'confirmation_message' => $hasError['message'],
            'is_valid' => false,
            'form_id' => $form_id,
            'debug' => $submission[$form_id],
            'error' => $hasError
        );
    }
	
} else if ($is_checking_payment_status) {

    $entry_id = $_GET['entry_id'];
    $entry = GFAPI::get_entry( $entry_id );

    $finished = array_search($entry['payment_status'], array("Paid", "Approved")) !== false;

    $json = array(
        'finished' => $finished,
        'is_valid' => true,
        'form_id' => $form_id,
        'entry' => (array) $entry
    );

    if ($finished) {
        // require_once( GFCommon::get_base_path() . '/form_display.php' );
        $confirmation = GFFormDisplay::handle_confirmation( $form, $entry, false );
        if(isset($confirmation['redirect2'])) {
            $confirmation['redirect'] = $confirmation['redirect2'];
            unset($confirmation['redirect2']);
        }
        // if ( is_array( $confirmation ) && isset( $confirmation['redirect'] ) ) {
        //     header( "Location: {$confirmation['redirect']}" );
        //     exit;
        // }

        // GFFormDisplay::$submission[ $form_id ] = array( 'is_confirmation' => true, 'confirmation_message' => $confirmation, 'form' => $form, 'lead' => $lead );
        $json['confirmation_message'] =  $confirmation;
    }
} else if ($is_addon_confirmation) {
    $is_postback          = true;
    $is_valid             = rgar( $submission_info, 'is_valid' ) || rgar( $submission_info, 'is_confirmation' );
    $form                 = $submission_info['form'];
    $entry                 = $submission_info['lead'];
    $confirmation_message = rgget( 'confirmation_message', $submission_info );

    // if ( $is_valid && ! RGForms::get( 'is_confirmation', $submission_info ) ) {

    //     if ( $submission_info['page_number'] == 0 ) {
    //         gf_do_action( array( 'gform_post_submission', $form['id'] ), $lead, $form );
    //     } else {
    //         gf_do_action( array( 'gform_post_paging', $form['id'] ), $form, $submission_info['source_page_number'], $submission_info['page_number'] );
    //     }
    // } else {
    //     $confirmation_message = 'Something wrong. The confirmation data is not valid.';
    // }

    if(isset($confirmation_message['redirect2'])) {
        $confirmation_message['redirect'] = $confirmation_message['redirect2'];
        unset($confirmation_message['redirect2']);
    }

    $json = array(
        'finished' => true,
        'is_valid' => true,
        'form_id' => $form_id,
        'entry' => (array) $entry,
        'submission_info' => $submission_info,
        'confirmation_message' =>  $confirmation_message
    );
} else if($is_valid_form) {
    // error_reporting(E_ALL);
    //     ini_set('display_errors', 1);
    require_once(dirname(__FILE__) .'/yakker-form.php');
    $json = yakker_get_form_json($form_id, $form, $user_id);
}
else {
    http_response_code(500);
    $json = array(
        'message' => 'Invalid request or this Yakker is not active.',
        'gf' => (array)$form
    );
}

// $json['gf']['unique_id'] = GFFormsModel::get_form_unique_id( $json['gf']['id'] );

// if($json['gf']['yakker_form_mode'] == "1") {
//     $json['gf']['yakker_form_mode']
// }
// $json['gf']['sliderMode'] = 'vertical';

// $json['config'] = array(
//     'mode' => 'chatbot', 
// );

// $json['options'] = array(
//     'preventAutoAppend' => true,
//     'preventAutoFocus' => true,
//     'submitCallback' => 'window.onFormlessSubmited',
// );

// function yakker_cf_get_tag($type)
// {
//     $types_gf = array('text', 'password', 'radio', 'checkbox', 'select');
//     $types_cf = array('input', 'input', 'fieldset', 'fieldset', 'select');
//     $index = array_search($type, $types_gf);
//     return $types_cf[$index];
// }

// function yakker_cf_get_id($field_id, $form_id)
// {
//     return 'yak_'.$form_id.'_'.$field_id;
// }

// function yakker_cf_get_option_name($field_id, $form_id)
// {
//     return 'yak_'.$form_id.'_'.$field_id;
// }

// function yakker_cf_get_option_id($option_index, $field_id, $form_id)
// {
//     return 'yak_'.$form_id.'_'.$field_id.'_'.($option_index+1);
// }

// $json['tags'] = array();
// foreach ($json['gf']['fields'] as $field) {
// //     tag "input"
// // type    "text"
// // id  "first-tag"
// // value   "Prefilled value here"
// // cf-questions    "Prefilled1&&with follow-up1&&with
//     $tag = array(
//         'tag' => yakker_cf_get_tag($field['type']), 
//         'id' => yakker_cf_get_id($field['id'], $json['gf']['id']), 
//         'value' => $field['defaultValue'],
//         'cf-input-placeholder' => $field['placeholder'],
//         'cf-questions' => !empty($field['mcfgf_questions']) ? $field['mcfgf_questions'] : $field['label']
//     );

//     if($tag['tag']=='input') {
//         if($field['enablePasswordInput']){
//             $tag['type'] = "password";
//         }
//         else {
//             $tag['type'] = $field['type'];
//         }

//         // max length
//         if(!empty($field['maxLength'])){
//             $start_limit = $field['isRequired'] ? 1 : 0;
//             $tag['pattern']  = ".{".$start_limit.",".$field['maxLength']."}";

//             if($field['isRequired']) {
//                 $tag['cf-error'] = 'No less than 1 and no more than '.$field['maxLength'].' characters';
//             }
//             else {
//                 $tag['cf-error'] = 'No more than 10 characters';
//             }
//         }
//     }
//     else if(in_array($tag['tag'], array('select', 'fieldset'))) {
//         $subtags = array();
//         foreach ($field['choices'] as $index => $choice) {
//             $subtags[] = array(
//                 'tag' => 'option',
//                 'name' => yakker_cf_get_option_name($field['id'], $json['gf']['id']),
//                 'id' => yakker_cf_get_option_id($index, $field['id'], $json['gf']['id']),
//                 'cf-label' => $choice['text'],
//                 'value' => $choice['value']
//             );
//         }
//         $tag['children'] = $subtags;
//     }

//     if($field['isRequired']){
//         $tag['required'] = "required";
//         $tag['cf-error'] = __( 'This field is required.', 'gravityforms' );
//     }

//     if(!empty($field['errorMessage'])) {
//         $tag['cf-error'] = $field['errorMessage'];
//     }

//     if(!empty($field['conditionalLogic'])) {

//         foreach ($field['conditionalLogic']['rules'] as $rule) {
//             $option_name = yakker_cf_get_option_name($rule['fieldId'], $json['gf']['id']);
//             $tag['cf-conditional-'.$option_name] = $rule['value'];
//         }
        
//     }



//     $json['tags'][] = $tag;



// }

echo json_encode($json);?>


