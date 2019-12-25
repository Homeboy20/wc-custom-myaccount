<?php
/**
 * Get the Endpoint options.
 *
 * @param null $endpoint
 *
 * @return bool|mixed|void|null
 */
function wccm_get_myaccount_endpoint_options( $endpoint = null ) {
	$wccm_endpoint_option = get_option( 'wccm_endpoint_update' );

	if ( null !== $endpoint ) {
		return isset( $wccm_endpoint_option[ $endpoint ] ) ? $wccm_endpoint_option[ $endpoint ] : false;
	}

	return ( ! empty( $wccm_endpoint_option ) ) ? $wccm_endpoint_option : false;
}

/**
 * Get the Endpoint specific endpoint value in options.
 *
 * @param $endpoint
 * @param string $wccm_endpoint
 *
 * @return mixed|null
 */
function wccm_get_endpoint_option( $endpoint, $wccm_endpoint = 'wccm_endpoint_name' ) {

	$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();
	if ( false !== $wccm_endpoint_option && isset( $wccm_endpoint_option[ $endpoint ][ $wccm_endpoint ] ) ) {
		return $wccm_endpoint_option[ $endpoint ][ $wccm_endpoint ];
	}

	return false;
}

/**
 * Get new added tabs.
 *
 * @return array
 */
function wccm_new_tabs() {
	$wccm_new_tabs        = array();
	$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();

	if ( false !== $wccm_endpoint_option ) {
		foreach ( $wccm_endpoint_option as $endpoint => $label ) {
			if ( isset( $label['wccm_pre_default_tab'] ) && true === $label['wccm_pre_default_tab'] ) {
				$wccm_new_tabs[ $endpoint ] = $label['wccm_endpoint_name'];
			}
		}
	}

	return $wccm_new_tabs;
}

/**
 * General setting option.
 * @return bool|mixed|void
 */
function wccm_get_general_setting_options() {
	$wccm_general_settings_option = get_option( 'wccm_general_settings' );

	return ( ! empty( $wccm_general_settings_option ) ) ? $wccm_general_settings_option : false;
}


/**
 * Get the Endpoint specific endpoint value in options.
 *
 * @param $endpoint
 * @param string $wccm_endpoint
 *
 * @return mixed|null
 */
function wccm_get_general_settings_option( $field_name ) {
	$wccm_general_settings_option = wccm_get_general_setting_options();
	if ( false !== $wccm_general_settings_option && isset( $wccm_general_settings_option[ $field_name ] ) ) {
		return $wccm_general_settings_option[ $field_name ];
	}

	return false;
}

/**
 * Check is tab field value exists.
 *
 * @param $endpoint
 * @param $fields
 *
 * @return bool
 */
function wccm_is_tab_fields_value_exists( $endpoint, $fields ) {
	if ( wccm_get_endpoint_option( $endpoint, $fields ) ) {
		return true;
	}

	return false;
}

/**
 * WooCommerce default menu items.
 *
 * @param null $item
 *
 * @return array|mixed
 */
function wccm_woocommerce_default_menu_items( $item = null ) {
	$wccm_woocommerce_default_items = array(
		'dashboard'       => __( 'Dashboard', 'woocommerce' ),
		'orders'          => __( 'Orders', 'woocommerce' ),
		'downloads'       => __( 'Downloads', 'woocommerce' ),
		'edit-address'    => __( 'Addresses', 'woocommerce' ),
		'payment-methods' => __( 'Payment methods', 'woocommerce' ),
		'edit-account'    => __( 'Account details', 'woocommerce' ),
		'customer-logout' => __( 'Logout', 'woocommerce' ),
	);

	if ( isset( $wccm_woocommerce_default_items[ $item ] ) ) {
		return $wccm_woocommerce_default_items[ $item ];
	}

	return $wccm_woocommerce_default_items;
}

/**
 * Check default WooCommerce menu item exists.
 *
 * @param $item
 *
 * @return bool
 */
function wccm_is_default_woocommerce_menu_item( $item ) {
	if ( array_key_exists( $item, wccm_woocommerce_default_menu_items() ) ) {
		return true;
	}

	return false;
}

/**
 * Get default WooCommerce menu name.
 *
 * @param $item_slug
 * @param $is_default
 *
 * @return array|bool|mixed
 */
function wccm_get_default_woocommerce_menu_name( $item_slug, $is_default ) {

	if ( true === $is_default && array_key_exists( $item_slug, wccm_woocommerce_default_menu_items() ) ) {
		return wccm_woocommerce_default_menu_items( $item_slug );
	}

	return false;
}

/**
 * Check default WooCommerce endpoint exists.
 *
 * @param $endpoint_slug
 *
 * @return bool
 */
function wccm_is_default_woocommrece_endpoint( $endpoint_slug ) {

	if ( array_key_exists( $endpoint_slug, wccm_woocommerce_default_menu_items() ) ) {
		return true;
	}

	return false;
}

/**
 * Remove menu item.
 *
 * @param $item
 *
 * @return bool
 */
function wccm_remove_menu_item( $item ) {

	if ( wccm_get_myaccount_endpoint_options( $item ) ) {
		$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();
		unset( $wccm_endpoint_option[ $item ] );

		return wccm_update_myaccount_option( $wccm_endpoint_option );

	}

	return false;
}

/**
 * Save/Update MyAccount option.
 *
 * @param $wccm_updated_endpoint_options
 *
 * @return bool
 */
function wccm_update_myaccount_option( $wccm_updated_endpoint_options ) {
	update_option( 'wccm_endpoint_update', $wccm_updated_endpoint_options );

	return true;
}

/**
 * Admin default selected page.
 * @return mixed|string
 */
function wccm_admin_default_page() {
	// Get the page.
	$wccm_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

	return isset( $wccm_page ) ? $wccm_page : 'wc-custom-myaccount';
}

/**
 * Admin default selected tab.
 * @return mixed|string
 */
function wccm_admin_default_tab() {
	// Get the tab.
	$wccm_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );

	return isset( $wccm_tab ) ? $wccm_tab : 'general';
}

/**
 * Check is Gravatar show setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_show_gravatar() {
	return wccm_get_general_settings_option( 'wccm_show_gravatar' );
}

/**
 * Check is Gravatar custom setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_custom_gravatar() {
	return wccm_get_general_settings_option( 'wccm_custom_gravatar' );
}

/**
 * Check is cover banner show setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_show_banner() {
	return wccm_get_general_settings_option( 'wccm_show_cover_banner' );
}

/**
 * Check is default cover banner setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_default_banner() {
	return wccm_get_general_settings_option( 'wccm_default_cover_banner_id' );
}

/**
 * Check is custom cover banner setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_custom_cover_banner() {
	return wccm_get_general_settings_option( 'wccm_custom_cover_banner' );
}

/**
 * Check is user name setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_username_myaccount() {
	return wccm_get_general_settings_option( 'wccm_show_username' );
}

/**
 * Check is user ip setting ON or OFF.
 * @return mixed|null
 */
function wccm_is_user_ip_myaccount() {
	return wccm_get_general_settings_option( 'wccm_show_user_ip' );
}

/**
 * Get IP.
 * @return array|false|string
 */
function wccm_get_client_ip() {
	$ipaddress = '';
	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		$ipaddress = getenv( 'HTTP_CLIENT_IP' );
	} else if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
	} else if ( getenv( 'HTTP_X_FORWARDED' ) ) {
		$ipaddress = getenv( 'HTTP_X_FORWARDED' );
	} else if ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
	} else if ( getenv( 'HTTP_FORWARDED' ) ) {
		$ipaddress = getenv( 'HTTP_FORWARDED' );
	} else if ( getenv( 'REMOTE_ADDR' ) ) {
		$ipaddress = getenv( 'REMOTE_ADDR' );
	} else {
		$ipaddress = 'UNKNOWN';
	}

	return $ipaddress;
}

/**
 * Generate random endpoint slug for new tab.
 *
 * @param $old_endpoint
 *
 * @return mixed|void
 */
function wccm_generate_random_endpoint_slug( $old_endpoint ) {
	$suffix = 2;
	do {
		$alt_endpoint_name   = _truncate_post_slug( $old_endpoint, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
		$endpoint_slug_check = wccm_get_endpoint_option( $alt_endpoint_name, 'wccm_endpoint_slug' );
		$suffix ++;
	} while ( $endpoint_slug_check );

	return apply_filters( 'wccm_generate_random_endpoint_slug', $alt_endpoint_name, $old_endpoint );
}