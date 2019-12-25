<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://multidots.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/public
 * @author     Hardip Parmar <hardip.parmar@multidots.com>
 */
class Wc_Custom_Myaccount_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	function wccm_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Custom_Myaccount_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Custom_Myaccount_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, WCCM_PLUGIN_URL . 'public/css/wc-custom-myaccount-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	function wccm_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Custom_Myaccount_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Custom_Myaccount_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );


		wp_register_script( $this->plugin_name, WCCM_PLUGIN_URL . 'public/js/wc-custom-myaccount-public.js', array(
			'jquery',
			'jquery-tiptip'
		), $this->version, true );

		wp_localize_script( $this->plugin_name, 'WCCM_Public_JS_Obj',
			array(
				'wccm_admin_url' => admin_url( 'admin-ajax.php' ),
				'wccm_nonce'     => wp_create_nonce( 'wccm-myaccount-nonce' ),
			) );

		if ( wccm_is_custom_gravatar() || wccm_is_custom_cover_banner() ) {
			wp_enqueue_script( 'jquery-tiptip' );
		}
		wp_enqueue_script( $this->plugin_name );


	}

	/**
	 * Add endpoint in rewrite endpoint.
	 *
	 */
	function wccm_add_endpoints() {
		$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();
		if ( false !== $wccm_endpoint_option ) {
			foreach ( $wccm_endpoint_option as $endpoint => $label ) {
				if ( isset( $label['wccm_pre_default_tab'] ) && true === $label['wccm_pre_default_tab'] ) {
					add_rewrite_endpoint( $endpoint, EP_ROOT | EP_PAGES );
				}
			}
			flush_rewrite_rules();
		}
	}

	/**
	 * Add query vars for endpoints.
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	function wccm_query_vars( $vars ) {

		$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();
		if ( false !== $wccm_endpoint_option ) {
			foreach ( $wccm_endpoint_option as $endpoint => $label ) {
				if ( isset( $label['wccm_pre_default_tab'] ) && true === $label['wccm_pre_default_tab'] ) {
					$vars[] = $endpoint;
				}
			}

		}

		return $vars;
	}

	/**
	 * Add query vars for endpoints.
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	function wccm_get_query_vars( $vars ) {

		$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();
		if ( false !== $wccm_endpoint_option ) {
			foreach ( $wccm_endpoint_option as $endpoint => $label ) {
				if ( isset( $label['wccm_pre_default_tab'] ) && true === $label['wccm_pre_default_tab'] ) {
					$vars[ $endpoint ] = $endpoint;
				}
			}

		}

		return $vars;
	}

	/**
	 * Added the end point title.
	 *
	 * @param $title
	 *
	 * @return string|void
	 */
	function wccm_the_endpoint_title( $title ) {
		global $wp_query;

		$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();

		if ( false !== $wccm_endpoint_option ) {
			foreach ( $wccm_endpoint_option as $endpoint => $label ) {
				if ( isset( $label['wccm_pre_default_tab'] ) && true === $label['wccm_pre_default_tab'] ) {
					$is_endpoint = isset( $wp_query->query_vars[ $endpoint ] );
					if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
						// New page title.
						$title = __( $label['wccm_endpoint_name'], 'wc-custom-myaccount' );
						remove_filter( 'the_title', array( $this, 'wccm_the_endpoint_title' ) );
					}
				}
			}
		}

		return $title;
	}

	/**
	 * Override Endpoint content if default endpoint.
	 *
	 * @param $endpoint
	 * @param null $wccm_args
	 */
	function wccm_override_dynamic_endpoint_function_callback( $endpoint, $wccm_args = null ) {

		if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_sub_title' ) ) {
			echo sprintf( '<h2>%s</h2>', esc_html__( wccm_get_endpoint_option( $endpoint, 'wccm_sub_title' ), 'wc-custom-myaccount' ) );
		}
		if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_content' ) ) {
			echo wp_kses_post( wccm_get_endpoint_option( $endpoint, 'wccm_content' ) );
		}

	}

	/**
	 * Prepend the endpoint content if default endpoint.
	 *
	 * @param $endpoint
	 * @param null $wccm_args
	 */
	function wccm_prepend_dynamic_endpoint_function_callback( $endpoint, $wccm_args = null ) {

		if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_sub_title' ) ) {
			echo sprintf( '<h2>%s</h2>', esc_html__( wccm_get_endpoint_option( $endpoint, 'wccm_sub_title' ), 'wc-custom-myaccount' ) );
		}
		if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_content' ) ) {
			echo wp_kses_post( wccm_get_endpoint_option( $endpoint, 'wccm_content' ) );
		}

		$this->wccm_get_default_tab_content( $endpoint, $wccm_args );
	}

	/**
	 * Append the endpint content if default endpoint.
	 *
	 * @param $endpoint
	 * @param null $wccm_args
	 */
	function wccm_append_dynamic_endpoint_function_callback( $endpoint, $wccm_args = null ) {

		$this->wccm_get_default_tab_content( $endpoint, $wccm_args );

		if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_sub_title' ) ) {
			echo sprintf( '<h2>%s</h2>', esc_html__( wccm_get_endpoint_option( $endpoint, 'wccm_sub_title' ), 'wc-custom-myaccount' ) );
		}
		if ( wccm_is_tab_fields_value_exists( $endpoint, 'wccm_content' ) ) {
			echo wp_kses_post( wccm_get_endpoint_option( $endpoint, 'wccm_content' ) );
		}

	}

	/**
	 * Get default tab endpoint content.
	 *
	 * @param $endpoint
	 * @param $args
	 */
	function wccm_get_default_tab_content( $endpoint, $args ) {

		switch ( $endpoint ) {
			case 'dashboard':
				// No endpoint found? Default to dashboard.
				wc_get_template(
					'myaccount/dashboard.php',
					array(
						'current_user' => get_user_by( 'id', get_current_user_id() ),
					)
				);
				break;
			case 'orders':
				$current_page    = ! empty( $args ) ? $args : 1;
				$customer_orders = wc_get_orders(
					apply_filters(
						'woocommerce_my_account_my_orders_query',
						array(
							'customer' => get_current_user_id(),
							'page'     => $current_page,
							'paginate' => true,
						)
					)
				);

				wc_get_template(
					'myaccount/orders.php',
					array(
						'current_page'    => absint( $current_page ),
						'customer_orders' => $customer_orders,
						'has_orders'      => 0 < $customer_orders->total,
					)
				);
				break;
			case 'downloads':
				wc_get_template( 'myaccount/downloads.php' );
				break;

			case 'edit-address' :
				$type = wc_edit_address_i18n( sanitize_title( $args ), true );

				WC_Shortcode_My_Account::edit_address( $type );
				break;

			case 'edit-account' :
				WC_Shortcode_My_Account::edit_account();
				break;

		}
	}

	/**
	 * MyAccount profile and cover banner html.
	 */
	function woocommerce_before_account_navigation_callback() {
		$wccm_current_user       = wp_get_current_user();
		$wccm_current_user_ID    = $wccm_current_user->ID;
		$wccm_myaccount_profile  = get_user_meta( $wccm_current_user_ID, 'wccm_myaccount_profile', true );
		$wccm_myaccount_username = $wccm_current_user->display_name;
		if ( wccm_is_show_gravatar() || wccm_is_show_banner() || wccm_is_username_myaccount() || wccm_is_user_ip_myaccount() ) {
			$file_path = 'partials/wccm-profile-html.php';
			if ( file_exists( plugin_dir_path( __FILE__ ) . $file_path ) ) {
				include plugin_dir_path( __FILE__ ) . $file_path;
			}

		}

	}

	/**
	 * MyAccount frontend profile and cover banner save/update.
	 */
	function wccm_update_myaccount_frontend_callback() {

		check_ajax_referer( 'wccm-myaccount-nonce', 'security' );
		$response            = array();
		$response['success'] = false;
		$response['msg']     = __( 'Sorry, Something went wrong.', 'wc-custom-myaccount' );

		$wccm_action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $wccm_action ) && 'wccm_update_myaccount_frontend_data' === $wccm_action ) {

			$wccm_action = filter_input( INPUT_POST, 'wccm_file_position', FILTER_SANITIZE_STRING );
			if ( empty( $_FILES["wccm_file"] ) ) {
				$response['msg'] = __( 'Something wrong in attached file. please try again.', 'wc-custom-myaccount' );
				wp_send_json( $response );
				wp_die();
			}


			$get_file           = $_FILES["wccm_file"]["name"];
			$pathinfo           = pathinfo( $get_file );
			$extension          = strtolower( $pathinfo['extension'] );
			$extensions_allowed = array( "png", "jpg", "jpeg", ); // extensions allowed
			$types_allowed      = array( "image/jpeg", "image/jpg", "image/x-png", "image/png" ); // types allowed
			$mega_byte          = 1024000;
			$max_file_size      = $mega_byte * 2;

			if ( ! in_array( $_FILES["wccm_file"]["type"], $types_allowed, true ) ) {
				$response['msg'] = __( 'File type is not allowed!', 'wc-custom-myaccount' );
				wp_send_json( $response );
				wp_die();
			}

			if ( in_array( $extension, $extensions_allowed, true ) ) { // check if extension allowed

				if ( $_FILES["wccm_file"]["size"] <= $max_file_size ) {

					$wccm_current_user      = wp_get_current_user();
					$wccm_current_user_ID   = $wccm_current_user->ID;
					$attach_id              = $this->wccm_upload_user_file( $_FILES['wccm_file'], $wccm_current_user_ID );  //Call function
					$wccm_myaccount_profile = get_user_meta( $wccm_current_user_ID, 'wccm_myaccount_profile', true );
					$wccm_myaccount_profile = empty( $wccm_myaccount_profile ) ? array() : $wccm_myaccount_profile;

					if ( false !== $attach_id ) {
						$wccm_myaccount_profile[ $wccm_action ] = $attach_id;
						update_user_meta( $wccm_current_user_ID, 'wccm_myaccount_profile', $wccm_myaccount_profile );
						$response['msg']     = __( 'Myaccount successfully updated.', 'wc-custom-myaccount' );
						$response['success'] = true;

					} else {
						$response['msg'] = __( 'Something went wrong in attachment uploading...', 'wc-custom-myaccount' );

					}
				} else {
					$response['msg'] = __( 'File size is not valid! please upload file 2MB or less.', 'wc-custom-myaccount' );

				}

			} else {
				$response['msg'] = __( 'File extension is not matched!', 'wc-custom-myaccount' );

			}
		}

		wp_send_json( $response );
		wp_die();

	}

	/**
	 * MyAccount user upload the file.
	 *
	 * @param array $file
	 * @param $current_user_id
	 *
	 * @return bool|int|WP_Error
	 */
	function wccm_upload_user_file( $file = array(), $current_user_id ) {
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		$file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

		if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
			return false;
		} else {
			$filename      = $file_return['file'];
			$attachment    = array(
				'post_mime_type' => $file_return['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $file_return['url'],
				'post_author'    => $current_user_id
			);
			$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if ( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}

		return false;
	}
}

