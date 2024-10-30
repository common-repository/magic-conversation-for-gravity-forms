<?php

/**
 * based on GFP_Stripe Class 
 *
 * Controls everything
 *
 * @since 0.1.0
 * */
class Yakker_GFP_Stripe {
	/**
	 * Instance of this class.
	 *
	 * @since    1.7.9.1
	 *
	 * @var      object
	 */
	private static $_this = null;

	/**
	 * Holds information for mapped fields in Stripe rule, used for Stripe JS
	 *
	 * @since 1.8.17.1
	 *
	 * @var array
	 */
	private static $stripe_rule_field_info = array();

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.7.9.1
	 *
	 * @uses      wp_die()
	 * @uses      __()
	 * @uses      register_activation_hook()
	 * @uses      add_action()
	 *
	 */
	function __construct() {

		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( 'There is already an instance of %s.',
			                     'yakker-gravity-forms-stripe' ), get_class( $this ) ) );
		}

		self::$_this = $this;
	}

	/**
	 * Return the desired API key from the database
	 *
	 * @since
	 *
	 * @uses get_option()
	 * @uses rgar()
	 * @uses esc_attr()
	 *
	 * @param      $type
	 * @param bool $mode
	 *
	 * @return string
	 */
	public static function get_api_key( $type, $mode = false ) {
		$settings = get_option( 'gfp_stripe_settings' );
		if ( ! $mode ) {
			$mode = rgar( $settings, 'mode' );
		}
		$key = $mode . '_' . $type . '_key';

		return trim( esc_attr( rgar( $settings, $key ) ) );

	}

	/**
	 * Check to see if ID is an input ID
	 *
	 * @since 1.7.9.1
	 *
	 * @param $id
	 *
	 * @return int
	 */
	private function is_input_id( $id ) {
		$is_input_id = stripos( $id, '.' );

		return $is_input_id;
	}

	/**
	 * Get rule fields
	 *
	 * @since 1.7.9.1
	 *
	 * @param $form_rule
	 *
	 * @return array
	 */
	private function get_rule_fields( $form_rule ) {
		return array(
			'rule_field_address1' => $form_rule[ 'meta' ][ 'customer_fields' ][ 'address1' ],
			'rule_field_city'     => $form_rule[ 'meta' ][ 'customer_fields' ][ 'city' ],
			'rule_field_state'    => $form_rule[ 'meta' ][ 'customer_fields' ][ 'state' ],
			'rule_field_zip'      => $form_rule[ 'meta' ][ 'customer_fields' ][ 'zip' ],
			'rule_field_country'  => $form_rule[ 'meta' ][ 'customer_fields' ][ 'country' ]
		);
	}

	/**
	 * Get field ID from the ID saved in Stripe feed
	 *
	 * @since    1.7.9.1
	 *
	 * @uses     GFP_Stripe::is_input_id()
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	private function get_field_id( $id ) {
		$input_id = $this->is_input_id( $id );
		if ( $input_id ) {
			$id = substr( $id, 0, $input_id );
		}

		return $id;
	}

	/**
	 * Get field IDs
	 *
	 * @since 1.7.9.1
	 *
	 * @uses  GFP_Stripe::get_field_id()
	 *
	 * @param $feed_fields
	 *
	 * @return array
	 */
	private function get_field_ids( $feed_fields ) {
		$feed_field_address1 = $feed_field_city = $feed_field_state = $feed_field_zip = $feed_field_country = '';
		extract( $feed_fields );

		return array(
			'address1_field_id' => $this->get_field_id( $feed_field_address1 ),
			'city_field_id'     => $this->get_field_id( $feed_field_city ),
			'state_field_id'    => $this->get_field_id( $feed_field_state ),
			'zip_field_id'      => $this->get_field_id( $feed_field_zip ),
			'country_field_id'  => $this->get_field_id( $feed_field_country )
		);
	}

	/**
	 * Get field input ID
	 *
	 * @since 1.7.9.1
	 *
	 * @param $field_input_id
	 *
	 * @return string
	 */
	private function get_field_input_id( $field_input_id ) {
		$separator_position = stripos( $field_input_id, '.' );
		$input_id           = substr( $field_input_id, $separator_position + 1 );

		return $input_id;
	}

	/**
	 * Get form input IDs
	 *
	 * @since 1.7.9.1
	 *
	 * @uses  GFP_Stripe::get_field_input_id()
	 *
	 * @param $form
	 * @param $rule_fields
	 * @param $rule_field_ids
	 *
	 * @return array
	 */
	private function get_form_input_ids( $form, $rule_fields, $rule_field_ids ) {
		$form_input_ids      = array(
			'street_input_id'  => '',
			'city_input_id'    => '',
			'state_input_id'   => '',
			'zip_input_id'     => '',
			'country_input_id' => ''
		);
		$rule_field_address1 = $rule_field_city = $rule_field_state = $rule_field_zip = $rule_field_country = '';
		extract( $rule_fields );
		$address1_field_id = $city_field_id = $state_field_id = $zip_field_id = $country_field_id = '';
		extract( $rule_field_ids );

		foreach ( $form[ 'fields' ] as $field ) {
			if ( 'creditcard' == $field[ 'type' ] ) {
				$form_input_ids[ 'creditcard_field_id' ] = $field[ 'id' ];
			} else if ( ! empty( $field[ 'inputs' ] ) ) {
				foreach ( $field[ 'inputs' ] as $input ) {
					switch ( $input[ 'id' ] ) {
						case $rule_field_address1:
							$input_id                            = $this->get_field_input_id( $input[ 'id' ] );
							$street_input_id                     = $form[ 'id' ] . '_' . $field[ 'id' ] . '_' . $input_id;
							$form_input_ids[ 'street_input_id' ] = $street_input_id;
							break;
						case $rule_field_city:
							$input_id                          = $this->get_field_input_id( $input[ 'id' ] );
							$city_input_id                     = $form[ 'id' ] . '_' . $field[ 'id' ] . '_' . $input_id;
							$form_input_ids[ 'city_input_id' ] = $city_input_id;
							break;
						case $rule_field_state:
							$input_id                           = $this->get_field_input_id( $input[ 'id' ] );
							$state_input_id                     = $form[ 'id' ] . '_' . $field[ 'id' ] . '_' . $input_id;
							$form_input_ids[ 'state_input_id' ] = $state_input_id;
							break;
						case $rule_field_zip:
							$input_id                         = $this->get_field_input_id( $input[ 'id' ] );
							$zip_input_id                     = $form[ 'id' ] . '_' . $field[ 'id' ] . '_' . $input_id;
							$form_input_ids[ 'zip_input_id' ] = $zip_input_id;
							break;
						case $rule_field_country:
							$input_id                             = $this->get_field_input_id( $input[ 'id' ] );
							$country_input_id                     = $form[ 'id' ] . '_' . $field[ 'id' ] . '_' . $input_id;
							$form_input_ids[ 'country_input_id' ] = $country_input_id;
							break;
					}
				}
			} else {
				switch ( $field[ 'id' ] ) {
					case $address1_field_id:
						$form_input_ids[ 'street_input_id' ] = $form[ 'id' ] . '_' . $field[ 'id' ];
						break;
					case $city_field_id:
						$form_input_ids[ 'city_input_id' ] = $form[ 'id' ] . '_' . $field[ 'id' ];
						break;
					case $state_field_id:
						$form_input_ids[ 'state_input_id' ] = $form[ 'id' ] . '_' . $field[ 'id' ];
						break;
					case $zip_field_id:
						$form_input_ids[ 'zip_input_id' ] = $form[ 'id' ] . '_' . $field[ 'id' ];
						break;
					case $country_field_id:
						$form_input_ids[ 'country_input_id' ] = $form[ 'id' ] . '_' . $field[ 'id' ];
						break;
				}
			}
		}

		return $form_input_ids;
	}

	/**
	 * Does rule have conditional logic
	 *
	 * @since 1.7.9.1
	 *
	 * @param $rule
	 * @param $conditional_field_id
	 *
	 * @return bool
	 */
	private function rule_has_condition( $rule, $conditional_field_id ) {

		$has_condition = ( ( '1' == $rule[ 'meta' ][ 'stripe_conditional_enabled' ] ) && ( $conditional_field_id == $rule[ 'meta' ][ 'stripe_conditional_field_id' ] ) );

		return $has_condition;

	}

	/**
	 * @param $rule
	 *
	 * @return array
	 */
	private function get_rule_condition( $rule ) {

		$rule_condition = array();

		$rule_condition[ 'operator' ] = $rule[ 'meta' ][ 'stripe_conditional_operator' ];
		$rule_condition[ 'value' ]    = $rule[ 'meta' ][ 'stripe_conditional_value' ];

		return $rule_condition;

	}

	public function get_stripe_config($form)
	{
		$stripe_rules = GFP_Stripe_Data::get_feed_by_form( $form[ 'id' ], true );

		$conditional_field_id = 0;

		if ( 1 == count( $stripe_rules ) ) {

			$stripe_rules         = $stripe_rules[ 0 ];
			$conditional_field_id = $stripe_rules[ 'meta' ][ 'stripe_conditional_field_id' ];

		} else if ( 1 < count( $stripe_rules ) ) {

			$valid_rules          = 0;
			$conditional_field_id = $stripe_rules[ 0 ][ 'meta' ][ 'stripe_conditional_field_id' ];

			foreach ( $stripe_rules as $rule ) {

				if ( $this->rule_has_condition( $rule, $conditional_field_id ) ) {
					$valid_rules ++;
				}

			}

			if ( $valid_rules !== count( $stripe_rules ) ) {

				$stripe_rules         = $stripe_rules[ 0 ];
				$conditional_field_id = $stripe_rules[ 'meta' ][ 'stripe_conditional_field_id' ];

			}

		}

		if ( ! empty( $stripe_rules ) ) {

			$form_id = $form[ 'id' ];

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$creditcard_field_id = '';

			$multiple_rules = isset( $valid_rules ) && ( 1 < $valid_rules );

			if ( $multiple_rules ) {

				$num_of_rules    = count( $stripe_rules );
				$rule_field_info = array();

				foreach ( $stripe_rules as $rule ) {

					$rule_fields    = $this->get_rule_fields( $rule );
					$rule_field_ids = $this->get_field_ids( $rule_fields );
					$form_input_ids = $this->get_form_input_ids( $form, $rule_fields, $rule_field_ids );
					$rule_condition = $this->get_rule_condition( $rule );

					$rule_field_info[ ] = array_merge( $form_input_ids, $rule_condition );

				}

				foreach ( $rule_field_info as $field_info ) {

					if ( ! empty( $field_info[ 'creditcard_field_id' ] ) ) {

						$creditcard_field_id = $field_info[ 'creditcard_field_id' ];

						break;

					}
				}

				unset( $field_info );


			} else {

				$num_of_rules = 1;

				$rule_field_info = array();

				$rule_fields     = $this->get_rule_fields( $stripe_rules );
				$rule_field_ids  = $this->get_field_ids( $rule_fields );
				$street_input_id = $city_input_id = $state_input_id = $zip_input_id = $country_input_id = '';

				extract( $this->get_form_input_ids( $form, $rule_fields, $rule_field_ids ) );

				if ( ! empty( $creditcard_field_id ) ) {
					$rule_field_info[ 'creditcard_field_id' ] = $creditcard_field_id;
				}

				$rule_field_info[ 'street_input_id' ]  = $street_input_id;
				$rule_field_info[ 'city_input_id' ]    = $city_input_id;
				$rule_field_info[ 'state_input_id' ]   = $state_input_id;
				$rule_field_info[ 'zip_input_id' ]     = $zip_input_id;
				$rule_field_info[ 'country_input_id' ] = $country_input_id;
			}

			$rule_field_info              = apply_filters( 'gfp_stripe_gform_get_form_filter', $rule_field_info, $stripe_rules, $form );
			self::$stripe_rule_field_info = $rule_field_info = apply_filters( 'gfp_stripe_rule_field_info', $rule_field_info, $stripe_rules, $form );

			$rule_has_condition = false;

			if ( array_key_exists( 0, $rule_field_info ) && ( is_array( $rule_field_info[ 0 ] ) ) ) {

				$rule_has_condition = true;

				// wp_enqueue_script( 'gform_conditional_logic', GFCommon::get_base_url() . '/js/conditional_logic.js', array(
				// 	'jquery',
				// 	'gforms_gravityforms'
				// ), GFCommon::$version );

			} else if ( ( $conditional_field_id ) && ( $this->rule_has_condition( $stripe_rules, $conditional_field_id ) ) ) {

				$rule_field_info = array_merge( $rule_field_info, $this->get_rule_condition( $stripe_rules ) );

				if ( array_key_exists( 'operator', $rule_field_info ) ) {

					$rule_has_condition = true;

					// wp_enqueue_script( 'gform_conditional_logic', GFCommon::get_base_url() . '/js/conditional_logic.js', array(
					// 	'jquery',
					// 	'gforms_gravityforms'
					// ), GFCommon::$version );

				}

			}

			if ( ! empty( $creditcard_field_id ) ) {

				// wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v2/', array( 'jquery' ), self::get_version() );

				$publishable_key = apply_filters( 'gfp_stripe_get_publishable_key', self::$_this->get_api_key( 'publishable' ), $form_id );

				//{$suffix}
				// wp_enqueue_script( 'gfp_stripe_js', trailingslashit( GFP_STRIPE_URL ) . "js/form-display.js", array(
				// 	'jquery',
				// 	'stripe-js'
				// ), self::get_version() );

				$creditcard_field      = GFFormsModel::get_field( $form, $creditcard_field_id );
				$allowed_funding_types = rgar( $creditcard_field, 'creditCardFundingTypes' );

				if ( empty( $allowed_funding_types ) ) {
					$allowed_funding_types = array( 'credit', 'debit', 'prepaid', 'unknown' );
				}

				$gfp_stripe_js_vars = array(
					'form_id'               => $form_id,
					'publishable_key'       => $publishable_key,
					'creditcard_field_id'   => $creditcard_field_id,
					'allowed_funding_types' => $allowed_funding_types,
					'num_of_rules'          => $num_of_rules,
					'rule_field_info'       => $rule_field_info,
					'rule_has_condition'    => $rule_has_condition,
					'conditional_field_id'  => $conditional_field_id,
					'error_messages'        => array(
						'funding'         => __( ' cards are not accepted.', 'gravity-forms-stripe' ),
						'card_number'     => __( 'Invalid card number.', 'gravity-forms-stripe' ),
						'expiration'      => __( ' Invalid expiration date.', 'gravity-forms-stripe' ),
						'security_code'   => __( ' Invalid security code.', 'gravity-forms-stripe' ),
						'cardholder_name' => __( ' Invalid cardholder name.', 'gravity-forms-stripe' ),
						'no_card_info'    => __( 'Unable to read card information', 'gravity-forms-stripe' )
					),
					'scripts' => array(
						'https://js.stripe.com/v2/'
					)
				);

				return $gfp_stripe_js_vars;

				// wp_localize_script( 'gfp_stripe_js', 'gfp_stripe_js_vars', $gfp_stripe_js_vars );

			}

		}
		return false;
	}
}
?>