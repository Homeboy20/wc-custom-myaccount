<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://multidots.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/includes
 * @author     Hardip Parmar <hardip.parmar@multidots.com>
 */
class Wc_Custom_Myaccount {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Custom_Myaccount_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WC_CUSTOM_MYACCOUNT_VERSION' ) ) {
			$this->version = WC_CUSTOM_MYACCOUNT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wc-custom-myaccount';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Custom_Myaccount_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Custom_Myaccount_i18n. Defines internationalization functionality.
	 * - Wc_Custom_Myaccount_Admin. Defines all hooks for the admin area.
	 * - Wc_Custom_Myaccount_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */

		require_once plugin_dir_path( __FILE__ ) . 'class-wc-custom-myaccount-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-wc-custom-myaccount-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wc-custom-myaccount-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wc-custom-myaccount-public.php';

		/**
		 * Common function.
		 */
		include plugin_dir_path( __FILE__ ) . 'class-wc-custom-myaccount-functions.php';

		$this->loader = new Wc_Custom_Myaccount_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Custom_Myaccount_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wc_Custom_Myaccount_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Custom_Myaccount_Admin( $this->get_plugin_name(), $this->get_version() );

		// Wordpress Hooks.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wccm_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wccm_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wccm_register_submenu_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wccm_register_settings' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'wccm_support_request_html' );

		// Admin user profile page.
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'wccm_add_wc_myaccount_user_fields' );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'wccm_add_wc_myaccount_user_fields' );

		// Get the tab.
		$wccm_active_tab = wccm_admin_default_tab();

		// Wccm Hooks.
		$this->loader->add_action( 'wccm_settings_tabs', $plugin_admin, 'wccm_settings_tab' );
		$this->loader->add_action( 'wccm_settings_contents', $plugin_admin, 'wccm_settings_contents_' . $wccm_active_tab );
		$this->loader->add_action( 'wccm_add_new_tab_endpoint_content', $plugin_admin, 'wccm_add_new_tab_content' );

		// WooCommerce Hooks.
		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_admin, 'wccm_woocommerce_account_menu_items', 10, 2 );

		// Post request.
		$this->loader->add_action( 'admin_post_wccm_save_new_endpoint_data', $plugin_admin, 'wccm_save_new_endpoint_data_callback' );

		// Ajax request.
		$this->loader->add_action( 'wp_ajax_wccm_update_endpoint_data', $plugin_admin, 'wccm_update_endpoint_data_callback' );
		$this->loader->add_action( 'wp_ajax_wccm_get_add_new_tab_html', $plugin_admin, 'wccm_get_add_new_tab_html_callback' );
		$this->loader->add_action( 'wp_ajax_wccm_remove_menu_tab_item', $plugin_admin, 'wccm_remove_menu_tab_item_callback' );
		$this->loader->add_action( 'wp_ajax_wccm_open_developer_support_modal', $plugin_admin, 'wccm_open_developer_support_modal' );
		$this->loader->add_action( 'wp_ajax_wccm_submit_support_request', $plugin_admin, 'wccm_submit_support_request' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Custom_Myaccount_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wccm_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wccm_enqueue_scripts' );

		$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();
		if ( false !== $wccm_endpoint_option ) {
			foreach ( $wccm_endpoint_option as $endpoint => $label ) {
				if ( 'dashboard' === $endpoint ) {
					$add_action_hook = 'woocommerce_account_page_endpoint';
				} else {
					$add_action_hook = 'woocommerce_account_' . $endpoint . '_endpoint';
				}

				if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_sub_title' ) || wccm_is_tab_fields_value_exists( $endpoint, 'wccm_content' ) ) {
					// Change my account Hooks values.
					add_action( $add_action_hook, function ( $wccm_args ) use ( $endpoint ) {
						$this->wccm_dynamic_function_callback( $endpoint, $wccm_args );
					} );

					/**
					 * Remove the default myaccount tab function when the
					 */
					if ( wccm_is_default_woocommrece_endpoint( $endpoint ) ) {
						remove_action( $add_action_hook, 'woocommerce_account_' . str_replace( '-', '_', $endpoint ) );
					}

				}
			}
		}

		$this->loader->add_action( 'init', $plugin_public, 'wccm_add_endpoints' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'wccm_query_vars', 0 );
		$this->loader->add_filter( 'the_title', $plugin_public, 'wccm_the_endpoint_title' );

		// Append,Prepend and Override hooks in plugin files.
		$this->loader->add_action( 'wccm_override_dynamic_endpoint_function', $plugin_public, 'wccm_override_dynamic_endpoint_function_callback', 10, 2 );
		$this->loader->add_action( 'wccm_prepend_dynamic_endpoint_function', $plugin_public, 'wccm_prepend_dynamic_endpoint_function_callback', 10, 2 );
		$this->loader->add_action( 'wccm_append_dynamic_endpoint_function', $plugin_public, 'wccm_append_dynamic_endpoint_function_callback', 10, 2 );

		$this->loader->add_action( 'woocommerce_before_account_navigation', $plugin_public, 'woocommerce_before_account_navigation_callback' );

		// Ajax request.
		$this->loader->add_action( 'wp_ajax_wccm_update_myaccount_frontend_data', $plugin_public, 'wccm_update_myaccount_frontend_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_wccm_update_myaccount_frontend_data', $plugin_public, 'wccm_update_myaccount_frontend_callback' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Wc_Custom_Myaccount_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Display the html for particular tabs.
	 *
	 * @param $endpoint
	 * @param $wccm_endpoint_option
	 */
	public function wccm_dynamic_function_callback( $endpoint, $wccm_args ) {

		$wccm_position = wccm_get_endpoint_option( $endpoint, 'wccm_tab_content_position' );
		$wccm_position = empty( $wccm_position ) ? 'wccm_override' : $wccm_position;
		do_action( $wccm_position . '_dynamic_endpoint_function', $endpoint, $wccm_args );

	}


}
