<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://multidots.com
 * @since             1.0.0
 * @package           Wc_Custom_Myaccount
 *
 * @wordpress-plugin
 * Plugin Name:       WC Custom MyAccount
 * Description:       This plugin helps the administrator to manage the customer content on the account page..
 * Version:           1.0.0
 * Author:            Adarsh Verma
 * Author URI:        https://profiles.wordpress.org/shobhit2412/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-custom-myaccount
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_CUSTOM_MYACCOUNT_VERSION', '1.0.0' );

//Plugin URL
if ( ! defined( 'WCCM_PLUGIN_URL' ) ) {
	define( 'WCCM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
//Plugin Path
if ( ! defined( 'WCCM_PLUGIN_PATH' ) ) {
	define( 'WCCM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-custom-myaccount-activator.php
 */
function activate_wc_custom_myaccount() {
	// When plugin active flush rewrites.
	flush_rewrite_rules();

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-custom-myaccount-activator.php';
	Wc_Custom_Myaccount_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-custom-myaccount-deactivator.php
 */
function deactivate_wc_custom_myaccount() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-custom-myaccount-deactivator.php';
	Wc_Custom_Myaccount_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_custom_myaccount' );
register_deactivation_hook( __FILE__, 'deactivate_wc_custom_myaccount' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_custom_myaccount() {

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-wc-custom-myaccount.php';

	$plugin = new Wc_Custom_Myaccount();
	$plugin->run();

}


/**
 * Check plugin requirement on plugins loaded, this plugin requires WooCommerce to be installed and active.
 *
 * @since    1.0.0
 */
function wccm_initialize_plugin() {

	$wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
	if ( current_user_can( 'activate_plugins' ) && $wc_active !== true ) {
		add_action( 'admin_notices', 'wccm_plugin_admin_notice' );
	} else {
		run_wc_custom_myaccount();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wccm_plugin_links' );
		add_filter( 'plugin_row_meta', 'wccm_custom_plugin_row_meta', 10, 2 );
	}

}

add_action( 'plugins_loaded', 'wccm_initialize_plugin' );

/**
 * Adding additional row meta.
 *
 * @param $links
 * @param $file
 *
 * @return array
 */
function wccm_custom_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'wc-custom-myaccount.php' ) !== false ) {
		$new_links = array(
			'support' => '<a href="javascript:void(0);" class="wccm-open-support-modal" target="_blank">' . esc_html__( 'Developer Support ', 'wc-custom-myaccount' ) . '</a>',
		);

		$links = array_merge( $links, $new_links );
	}

	return $links;

}

/**
 * Settings link on plugin listing page
 */
function wccm_plugin_links( $links ) {

	$vpe_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-custom-myaccount' ) . '">' . esc_html__( 'Settings', 'wc-custom-myaccount' ) . '</a>'
	);

	return array_merge( $links, $vpe_links );

}


/**
 * Show admin notice in case of Gravity Forms plugin is missing.
 *
 * @since    1.0.0
 */
function wccm_plugin_admin_notice() {

	$wccm_plugin = esc_html__( 'WooCommerce Custom My Account', 'wc-custom-myaccount' );
	$wc_plugin   = esc_html__( 'WooCommerce', 'wc-custom-myaccount' );
	?>
    <div class="error">
        <p>
			<?php echo sprintf( esc_html__( '%1$s is ineffective as it requires %2$s to be installed and active.', 'wc-custom-myaccount' ), '<strong>' . esc_html( $wccm_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' ); ?>
        </p>
    </div>
	<?php

}
