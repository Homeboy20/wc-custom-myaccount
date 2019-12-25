<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://multidots.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/includes
 * @author     Hardip Parmar <hardip.parmar@multidots.com>
 */
class Wc_Custom_Myaccount_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-custom-myaccount',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
