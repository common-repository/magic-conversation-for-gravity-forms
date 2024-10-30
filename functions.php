<?php 

function mcfgf_parseMergeTags($form, $text = false) {
    if(!empty($text)) {
        $text_alt = GFCommon::replace_variables( $text, $form, false, false, false, false );
        if(!empty($text_alt)) {
            return $text_alt;
        }
    }
    return $text;
}

function mcfgf_build_css_code($css_options) {
    $fontSize = intval($css_options['fontSize']);

    if($fontSize < 6) {
        $fontSize = 12;
    }

    $fontSize = $fontSize.'px';

    $css_code = '';

    $css_code .= '.yakker-html-wrapper {font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", sans-serif; line-height: '.$css_options['lineHeight'].'; font-size:'.$fontSize.';';
    $css_code .= '}';

    $css_code .= '.yakker-message-container-left .yakker-html-wrapper { color: '.$css_options['fontColor'].';}';
    $css_code .= '.yakker-message-container-right .yakker-html-wrapper { color: '.$css_options['userFontColor'].';}';

    return $css_code;
}

function mcfgf_get_conversation_permalink($form_id, $exParams = '') {
    // return 'http://localhost:4000/?form_id='.$form_id.'&'.$exParams;
    // return home_url('/magic-conversation/'.$form_id.'/'.'?'.$exParams);
    if(!empty($exParams)) $exParams = '&'.$exParams;
    global $mcfgf_settings_basics;
    $lang = isset($mcfgf_settings_basics['dateLocaleCode']) ? $mcfgf_settings_basics['dateLocaleCode'] : '';

    return home_url('/magic-conversation/'.$form_id.'/?lang='.$lang.$exParams);
}

function mcfgf_get_products_by_ids($product_ids) {
    $args = array(
        'orderby' => 'modified',
        'order' => 'DESC',
        'paginate' => true,
        // 'type' => 'external',
        'include' => $product_ids
    );

    $results = wc_get_products( $args );
    $products = array();
    if(intval($results->total) > 0) {
        foreach ($results->products as $product) {
            $new = array();
            $new['id'] = $product->get_id();
            $new['title'] = $product->get_name();
            $new['short_description'] = $product->get_short_description();
            $new['description'] = $product->get_description();
            $new['sku'] = $product->get_sku();
            $new['price'] = $product->get_price();
            $products[] = $new;
        }
    }
    return $products;
}

function mcfgf_get_gravityforms_list($placeholder) {
    $forms = class_exists('RGFormsModel') ? RGFormsModel::get_forms( null, 'title' ) : array();
    $formidnames = array('' => $placeholder);
    foreach( $forms as $form ){
        $formidnames[$form->id] = $form->title;
    }
    return $formidnames;
}

function mcfgf_forms_picker($fieldname, $placeholder = false) {
    $formidnames = mcfgf_get_gravityforms_list($placeholder);
    $html = '<select name="'.$fieldname.'">';
    foreach ($formidnames as $id => $title) {
        $html .= '<option value="'.$id.'">'.$title.'</option>';
    }
    $html .= '</select>';
    return $html;
}

function mcfgf_get_radio_field_of_woo_product_variation($variations, $maxid, $level = 0, $product_id, $product_field_id) {

    // print_r($variations);
    // die();
    $radio_for_variations = array(
        'id' => $maxid,
        'label' => 'Please choose '.$variations[0][$level]['name'],
        'type'  => 'radio',
        'mcfgf_enable_woocommerce_product' => true,
        'enableChoiceValue' => true,
        'choices' => array(),
        "conditionalLogic" => array(
            "actionType" => "show",
            "logicType" => "all",
            "rules" => array(
                array(
                    "fieldId" => $product_field_id,
                    "operator" => "is",
                    "value" => $product_id.""
                )
            )
        )
    );

    $product_options = array();

    foreach ($variations as $variation) {
        foreach ($variation as $index => $variation_item) {
            if($index == $level) {
                if(!in_array($variation_item['option'], $product_options)) {
                    $product_options[] = $variation_item['option'];
                    $radio_for_variations['choices'][] = array(
                        'text' => $variation_item['option'], 
                        'value' => "woo-v-".$product_id.'-'.$level.'-'.$variation_item['option'],
                        'isSelected' => false
                    );
                }
            }
        }
    }

    return $radio_for_variations;
}

function mcfgf_is_free() {
    return !(file_exists(dirname(__FILE__).'/license.php') || file_exists(dirname(__FILE__).'/yakker-mcfwc.php')); 
}

function mcfgf_add_free_version_welcome_message(&$json) {
    if(mcfgf_is_free()) {
        $standard_fields = array('html', 'text', 'textarea', 'select', 'multiselect', 'number', 'checkbox', 'radio', 'hidden', 'page', 'section');
        $needNotice = false;
        foreach ($json['gf']['fields'] as $index => $field) {
            if((!in_array($field['type'], $standard_fields)) || !(empty($field['conditionalLogic'])) ) {
                $needNotice = true;
                break;
            }
        }
        if($needNotice) {
            array_unshift($json['gf']['fields'], json_decode('{
                "type": "html",
                "id": 0,
                "isRequired": false,
                "visibility": "visible",
                "formId": '.$json['gf']['id'].',
                "mcfgf_questions": "<p>You are using the free version, that only supports <b>Standard Fields</b> and <b>DOES NOT</b> support <b>Conditional Logic</b>. Please upgrade now to the Pro version to use Conditional Logic and other great features. <a href=\"https://magicconversation.net/pricing/\">Purchase Pro version now</a>.</p>",
                "pageNumber": 1
            }'));
        }
    }
    
    // return $json;
}

if(!function_exists('yakker_woocommerce_rest_check_permissions')) {
                      
    function yakker_woocommerce_rest_check_permissions($permission, $context, $object_id, $post_type) {
        return true;
    }
    function yakker_woocommerce_rest_prepare_product_variation_object($response, $object, $request) {
        $data = $response->get_data();
        $query = parse_url($data['permalink'], PHP_URL_QUERY);
        
        parse_str($query, $args);

        $new_url = add_query_arg( 'redirect-to-checkout', 'yes', $object->add_to_cart_url());
        foreach ($args as $key => $value) {
            $new_url = add_query_arg( $key, $value, $new_url);
        }

        $data['permalink_add_to_cart'] = $new_url;
        $data['permalink_add_to_cart_debug'] = $query;
        $response->set_data($data); 
        return $response;
    }

    function yakker_woocommerce_product_add_to_cart_url($url, $that) {
        $url = $that->is_purchasable() && $that->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $that->get_id(), get_permalink( $that->get_id() ) ) ) : get_permalink( $that->get_id() );

        return $url;
    }
    function yakker_woocommerce_rest_prepare_product_object($response, $object, $request) {
        $data = $response->get_data();
        $data['permalink_add_to_cart'] = add_query_arg( 'redirect-to-checkout', 'yes', $object->add_to_cart_url());
        $response->set_data($data);
        return $response;
    }

    function yakker_get_the_product_thumbnail_url( $productId, $size = 'shop_catalog' ) {
      global $post;
      $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );
      return get_the_post_thumbnail_url( $productId, $image_size );
    }

    // get order list 
    function yakker_woocommerce_get_user_orders($user_id) {
        $args = array(
            'customer_id' => $user_id,
        );
        $orders = wc_get_orders( $args );
        return $orders;
    }

    function yakker_woocommerce_get_user_orders_for_radio_choices($user_id, $prefix = '') {
        $orders = yakker_woocommerce_get_user_orders($user_id);
        $choices = array();
        // Loop through Order IDs
        foreach( $orders as $order ) {
            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            // Get the Order ID
            $order_id = $order->get_id();
            // $order->get_formatted_order_total()
            $order_date_formatted = wc_format_datetime( $order->get_date_created() );
            $order_total_hint = wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->order_total, $item_count ) );
            $order_status_hint = ' ('.wc_get_order_status_name( $order->get_status() ).')';
            // $choices[] = array('text' => $order->get_date_completed(), 'value' => $order_id);
            $choices[] = array('text' => '#'.$order_id.' '.$order_date_formatted . ' ' . $order_total_hint. $order_status_hint, 'value' => $prefix.$order_id);
            // And so on …
        }

        return $choices;
    }

    function yakker_handle_populate_orders_for_radio_choices_with_prefix($json, $key) {
        if(count($json['gf']['fields'][$key]['choices']) > 0) {

            $mcfgf_prefix = $json['gf']['fields'][$key]['choices'][0]['value'].'';
            // echo $mcfgf_prefix;
            // die();
            $mcfgf_choices = array();
            $mcfgf_orders = $json['orders'];
            if($mcfgf_orders && count($mcfgf_orders) > 0) {
                foreach ($mcfgf_orders as $mcfgf_order) {
                    $mcfgf_choices[] = array(
                        'text' => $mcfgf_order['text'],
                        'value' => $mcfgf_prefix.$mcfgf_order['value']
                    );
                }
            }
            
            // var_dump($mcfgf_choices);
            // die();
            $json['gf']['fields'][$key]['choices'] = $mcfgf_choices;// $mcfgf_choices;
        }

        return $json;
    }

    function yakker_woocommerce_get_cart_for_session() {
        $cart_session = array();
        global $woocommerce;
        foreach ( $woocommerce->cart->get_cart() as $key => $values ) {
            $cart_session[ $key ] = $values;
            unset( $cart_session[ $key ]['data'] ); // Unset product object.
        }

        return $cart_session;
    }

    function yakker_woocommerce_get_cart_handle_error($message) {
        global $yakker_woocommerce_errors;

        if(!isset($yakker_woocommerce_errors)) {
            $yakker_woocommerce_errors = array();
        }

        $yakker_woocommerce_errors[] = $message;

    }

    function yakker_woocommerce_get_cart() {
        global $woocommerce;

        add_filter('woocommerce_add_error', 'yakker_woocommerce_get_cart_handle_error', 10, 1);
        // $woocommerce->cart->session->get_cart_from_session();
        $items = $woocommerce->cart->get_cart();

        $cartItems = array();

        foreach($items as $item => $values) { 
            $_product =  wc_get_product( $values['data']->get_id() );
            //product image
            $getProductDetail = wc_get_product( $values['product_id'] );

            $cartItem = array(
                'imageUrl' => yakker_get_the_product_thumbnail_url($values['product_id']), //$getProductDetail->get_image(),
                'title' => $_product->get_title(),
                'qty' => intval($values['quantity']),
                'price' => floatval(get_post_meta($values['product_id'] , '_price', true)),
                'regular_price' => floatval(get_post_meta($values['product_id'] , '_regular_price', true)),
                'sale_price' => floatval(get_post_meta($values['product_id'] , '_sale_price', true)),
                'sub_total' => $values['line_total'],
                'line' => $values
            );
            // echo $getProductDetail->get_image(); // accepts 2 arguments ( size, attr )

            // echo "<b>".$_product->get_title() .'</b>  <br> Quantity: '.$values['quantity'].'<br>'; 
            // $price = get_post_meta($values['product_id'] , '_price', true);
            // echo "  Price: ".$price."<br>";
            // /*Regular Price and Sale Price*/
            // echo "Regular Price: ".get_post_meta($values['product_id'] , '_regular_price', true)."<br>";
            // echo "Sale Price: ".get_post_meta($values['product_id'] , '_sale_price', true)."<br>";
            $cartItems[] = $cartItem;
        }

        global $yakker_woocommerce_errors;

        $result = array('items' => $cartItems, 'user_id' => get_current_user_id(),
                'reorder_nonce' => wp_create_nonce( 'woocommerce-order_again' ));

        if(isset($yakker_woocommerce_errors) && count($yakker_woocommerce_errors) > 0) {
            $result['errors'] = $yakker_woocommerce_errors;
        }

        return $result;
    }

    // get html to show cart list with product html template
    function yakker_woocommerce_get_cart_html_with_template($cartJson, $mainTpl, $productTpl, $styleTpl) {
        $html = '';
        if($cartJson && $cartJson['items']) {
            foreach ($cartJson['items'] as $i => $item) {
                $productHtml = str_replace("{{yakker_cart_item_image_url}}", $item['imageUrl'], $productTpl);
                $productHtml = str_replace("{{yakker_cart_item_title}}", $item['title'], $productHtml);
                $productHtml = str_replace("{{yakker_cart_item_description}}", 'x '.$item['qty'], $productHtml);
                $productHtml = str_replace("{{yakker_cart_item_price}}", $item['sub_total'], $productHtml);
                $html .= $productHtml;
            }
        }

        $html = str_replace("{{yakker_cart_items_view}}", $html, $styleTpl.$mainTpl);

        $html = str_replace("{{yakker_cart_sub_total}}", '', $html);
        $html = str_replace("{{yakker_cart_shipping}}", $html, $html);
        $html = str_replace("{{yakker_cart_total}}", $html, $html);
    }


    function yakker_add_target_blank_to_links_in_html($str) {
        $re = '/(<a[\s\S]+?)href=/m';
        $subst = '$1target="_blank" href=';
        $result = preg_replace($re, $subst, $str);
        return $result;
    }

    function yakker_woocommerce_get_mini_cart_json($mainTpl, $styleTpl) {
        global $woocommerce;
        $woocommerce->include_template_functions();

        ob_start();

        woocommerce_mini_cart();

        $mini_cart = ob_get_clean();

        $mini_cart = yakker_add_target_blank_to_links_in_html($mini_cart);

        $html = '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>';
        $html = str_replace("{{yakker_cart_view}}", $html, $styleTpl.$mainTpl);

        $data = array(
            'fragments' => apply_filters(
                'woocommerce_add_to_cart_fragments',
                array(
                    'div.widget_shopping_cart_content' => $html,
                )
            ),
            'cart_hash' => WC()->cart->get_cart_hash(),
        );


        return $data;
    }

    add_filter( 'gform_pre_render', 'gform_pre_render_yakker_handle_old_orders' );
    add_filter( 'gform_pre_validation', 'gform_pre_render_yakker_handle_old_orders' );
    add_filter( 'gform_pre_submission_filter', 'gform_pre_render_yakker_handle_old_orders' );
    add_filter( 'gform_admin_pre_render', 'gform_pre_render_yakker_handle_old_orders' );
    function gform_pre_render_yakker_handle_old_orders( $form ) {
     
        // foreach ( $form['fields'] as &$field ) {
     
        //     if ( $field->type != 'radio' || strpos( $field->cssClass, 'mc-populate-orders' ) === false ) {
        //         continue;
        //     }
     

        //     $customer_orders = get_posts( array(
        //         'numberposts' => -1,
        //         'meta_key'    => '_customer_user',
        //         'meta_value'  => get_current_user_id(),
        //         'post_type'   => wc_get_order_types(),
        //         'post_status' => array_keys( wc_get_order_statuses() ),
        //     ) );
        //     // you can add additional parameters here to alter the posts that are retrieved
        //     // more info: http://codex.wordpress.org/Template_Tags/get_posts
        //     $posts = get_posts( 'numberposts=-1&post_status=publish' );
     
        //     $choices = array();
     
        //     foreach ( $posts as $post ) {
        //         $choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title );
        //     }
     
        //     // update 'Select a Post' to whatever you'd like the instructive option to be
        //     $field->placeholder = 'Select an Order';
        //     $field->choices = $choices;
     
        // }
     
        return $form;
    }
}