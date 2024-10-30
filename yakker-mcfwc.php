<?php

/**
 * Magic Conversation For Gravity Forms Side Form class
 *
 * @author Flannian Feng
 */


function mcfwc_is_reorder_history_empty($json) {
    return isset($json['reorder']) && isset($json['reorder']['orders']) && count($json['reorder']['orders']) === 0;
}

function mcfwc_is_cart_empty($json) {
    return isset($json['cart']) && isset($json['cart']['items']) && count($json['cart']['items']) === 0;
}

function mcfwc_is_orders_empty($json) {
    return isset($json['orders']) && count($json['orders']) === 0;
}

function mcfwc_handle_mc_ajax_validation_cart_is_empty($json, $raw_field_id) {
    $json_new = array(
        'is_valid' => false,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => 'Your cart is currently empty, please choose other option(s).'
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}

function mcfwc_handle_mc_ajax_validation_recent_orders_is_empty($json, $raw_field_id) {
    $json_new = array(
        'is_valid' => false,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => 'Your don\'t have any recent order yet, please choose other option(s).'
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}


function mcfwc_handle_mc_ajax_validation_reorder_history_is_empty($json, $raw_field_id) {
    $json_new = array(
        'is_valid' => false,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => 'Your cart is currently empty, please choose other option(s).'
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}



function mcfwc_handle_mc_ajax_validation_checkout($json, $raw_field_id) {
    $json_new = array(
        'is_valid' => false,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => array('redirect' => wc_get_checkout_url() )     
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}

function mcfwc_handle_mc_ajax_validation_empty_cart($json, $raw_field_id) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
    update_user_meta(
        get_current_user_id(),
        '_woocommerce_persistent_cart_' . get_current_blog_id(),
        array(
            'cart' => yakker_woocommerce_get_cart_for_session(),
        )
    );
    $json_new = array(
        'is_valid' => false,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => 'Your cart has been cleared. Is there any more I can do for you?'     
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}

function mcfwc_handle_mc_ajax_validation_view_order($json, $raw_field_id, $order_id) {
    $view_order_url = apply_filters( 'woocommerce_get_view_order_url', wc_get_endpoint_url( 'view-order', $order_id, wc_get_page_permalink( 'myaccount' ) ), null );
    $json_new = array(
        'is_valid' => false,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => array('redirect' => $view_order_url )     
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}


function mcfwc_get_orders_template_html() { 
    ob_start();
    woocommerce_account_orders(1);
    return ob_get_clean();
}

function mcfwc_handle_mc_ajax_validation_view_orders($json, $raw_field_id) {
    $json_new = array(
        'is_valid' => true,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => '<div class="mcfwc-woo-mobile">'.yakker_add_target_blank_to_links_in_html(mcfwc_get_orders_template_html()).'</div>'
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}

function mcfwc_handle_mc_ajax_validation_reorder_show_messages($json, $raw_field_id) {
    $json_new = array(
        'is_valid' => true,
        'form_id' => $json['form_id'],
        'fieldId' => $json['fieldId'],
        'message'  => 'Your cart has been filled with the items from your previous order.'
    );
    $json_new = yak_update_validation_extra_info($json_new, $raw_field_id);
    return $json_new;
}

function mcfwc_handle_mc_ajax_validation($json, $raw_field_id)
{
    $is_cart_empty = mcfwc_is_cart_empty($json);
    if( strpos($json['fieldValue'], 'mc-ajax-reorder-id-') !== false ) {
        $order_id = intval(str_replace('mc-ajax-reorder-id-', '', $json['fieldValue']));
        update_user_option(get_current_user_id(), 'mcfwc_reorder_id', $order_id);

        return mcfwc_handle_mc_ajax_validation_reorder_show_messages($json, $raw_field_id);
        // other action is in main.php woocommerce_cart_loaded_from_session and woocommerce_valid_order_statuses_for_order_again
    } else if(strpos($json['fieldValue'], 'mc-ajax-view-order-id-') !== false) {
        $order_id = intval(str_replace('mc-ajax-view-order-id-', '', $json['fieldValue']));
        return mcfwc_handle_mc_ajax_validation_view_order($json, $raw_field_id, $order_id);
        
    } else if($json['fieldValue'] === 'mc-ajax-my-cart') {
        if($is_cart_empty) {
            return mcfwc_handle_mc_ajax_validation_cart_is_empty($json, $raw_field_id);
        }
    } else if($json['fieldValue'] === 'mc-ajax-recent-order') {
        $is_orders_empty = mcfwc_is_orders_empty($json);
        if($is_orders_empty) {
            return mcfwc_handle_mc_ajax_validation_recent_orders_is_empty($json, $raw_field_id);
        } else {
            return mcfwc_handle_mc_ajax_validation_view_orders($json, $raw_field_id);
        }
    } else if($json['fieldValue'] === 'mc-ajax-reorder') {
        $is_orders_empty = mcfwc_is_orders_empty($json);
        if($is_orders_empty) {
            return mcfwc_handle_mc_ajax_validation_recent_orders_is_empty($json, $raw_field_id);
        }
    } else if($json['fieldValue'] === 'mc-ajax-checkout') {
        if($is_cart_empty) {
            return mcfwc_handle_mc_ajax_validation_cart_is_empty($json, $raw_field_id);
        } else {
            return mcfwc_handle_mc_ajax_validation_checkout($json, $raw_field_id);
        }
    } else if($json['fieldValue'] === 'mc-ajax-empty-cart') {
        if($is_cart_empty) {
            return mcfwc_handle_mc_ajax_validation_cart_is_empty($json, $raw_field_id);
        } else {
            return mcfwc_handle_mc_ajax_validation_empty_cart($json, $raw_field_id);
            
        }
    }

    return $json;
}
?>


