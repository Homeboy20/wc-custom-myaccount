<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://multidots.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/admin
 * @author     Hardip Parmar <hardip.parmar@multidots.com>
 */
class Wc_Custom_Myaccount_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	function wccm_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, WCCM_PLUGIN_URL . 'admin/css/wc-custom-myaccount-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	function wccm_enqueue_scripts() {
		wp_enqueue_media();

		wp_register_script( $this->plugin_name, WCCM_PLUGIN_URL . 'admin/js/wc-custom-myaccount-admin.js', array(
			'jquery',
			'media-models'
		), $this->version, false );

		$_request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

		wp_localize_script( $this->plugin_name, 'WCCM_Admin_JS_Obj',
			array(
				'wccm_admin_url'               => admin_url( 'admin-ajax.php' ),
				'wccm_nonce'                   => wp_create_nonce( 'wccm-endpoint-nonce' ),
				'loader_url'                   => includes_url( 'images/spinner-2x.gif' ),
				'fetch_support_modal_wait_msg' => esc_html__( 'Please want while the form loads...', 'wc-custom-myaccount' ),
				'wccm_endpoint_name_msg'       => esc_html__( 'Please enter endpoint name.', 'wc-custom-myaccount' ),
				'wccm_endpoint_slug_msg'       => esc_html__( 'Please enter endpoint slug.', 'wc-custom-myaccount' ),
				'wccm_sub_title_msg'           => esc_html__( 'Please enter sub title.', 'wc-custom-myaccount' ),
				'wccm_content_msg'             => esc_html__( 'Please enter content.', 'wc-custom-myaccount' ),
				'_wccm_http_referer'           => wp_unslash( $_request_uri )
			) );

		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Add sub menu in WooCommerce.
	 *
	 */
	function wccm_register_submenu_page() {

		add_submenu_page( 'woocommerce', esc_html__( 'Custom My Account Settings', 'wc-custom-myaccount' ), esc_html__( 'Custom My Account', 'wc-custom-myaccount' ), 'manage_options', 'wc-custom-myaccount',
			array( $this, 'wccm_submenu_page_callback' )
		);

	}

	/**
	 * Html Display in admin side.
	 *
	 */
	function wccm_submenu_page_callback() {

		$file_path = 'partials/wc-custom-myaccount-admin-display.php';
		if ( file_exists( plugin_dir_path( __FILE__ ) . $file_path ) ) {
			include_once plugin_dir_path( __FILE__ ) . $file_path;
		}

	}

	/**
	 * Create a setting tabs.
	 *
	 */
	function wccm_settings_tab() {

		$wccm_active_page = wccm_admin_default_page();
		$wccm_active_tab  = wccm_admin_default_tab();

		$wccm_tabs = array(
			'general'  => esc_html__( 'General Settings', 'wc-custom-myaccount' ),
			'endpoint' => esc_html__( 'EndPoints', 'wc-custom-myaccount' ),
		);

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $wccm_tabs as $wccm_tab => $wccm_tab_name ) {
			$wccm_class = ( $wccm_tab === $wccm_active_tab ) ? 'nav-tab-active' : '';
			printf( "<a class='nav-tab %s' href='?page=%s&tab=%s'>%s</a>",
				esc_attr( $wccm_class ),
				esc_attr( $wccm_active_page ),
				esc_attr( $wccm_tab ),
				esc_html( $wccm_tab_name )
			);

		}
		echo '</h2>';

	}

	/**
	 * Create the endpoint html.
	 *
	 */
	function wccm_settings_contents_endpoint() {

		do_action( 'wccm_add_new_tab_endpoint_content' );
		?>
        <div class="wccm-endpoint-contender">
			<?php
			foreach ( wc_get_account_menu_items() as $endpoint => $label ) {

				$wccm_file = 'partials/wc-custom-myaccount-new-tab.php';
				if ( file_exists( plugin_dir_path( __FILE__ ) . $wccm_file ) ) {

					$wccm_new_tab_data = array(
						'wccm_endpoint_slug'        => $endpoint,
						'wccm_endpoint_name'        => $label,
						'wccm_sub_title'            => wccm_get_endpoint_option( $endpoint, 'wccm_sub_title' ),
						'wccm_content'              => wccm_get_endpoint_option( $endpoint, 'wccm_content' ),
						'wccm_tab_content_position' => wccm_get_endpoint_option( $endpoint, 'wccm_tab_content_position' ),
					);
					include plugin_dir_path( __FILE__ ) . $wccm_file;
				}

			}
			?>
        </div>
		<?php

	}

	/**
	 * Include new tab content file.
	 */
	function wccm_add_new_tab_content() {

		submit_button( esc_html__( 'Add New Tab', 'wc-custom-myaccount' ), 'wccm_add_tab', 'wccm_add_tab' );
		include plugin_dir_path( __FILE__ ) . 'partials/wc-custom-myaccount-new-tab-request.php';

	}

	/**
	 * General setting content function.
	 */
	function wccm_settings_contents_general() {
		?>
        <div class="wccm-general-contender">
            <div class="wrap">
                <form method="post" action="options.php" novalidate="novalidate">
					<?php
					// Add the setting section.
					settings_fields( "wccm-general-settings-section" );

					// Add the session with fields.
					do_settings_sections( "wc-custom-myaccount" );

					// Add the submit button to serialize the options
					submit_button();
					?>
                </form>

            </div>
        </div>
		<?php
	}

	/**
	 * Register settings for General setting tab.
	 */
	function wccm_register_settings() {

		add_settings_section(
			'wccm-general-settings-section',
			'General Settings',
			'',
			'wc-custom-myaccount'
		);

		/**
		 * Show the Gravtar on front side.
		 */
		add_settings_field(
			'wcca-show-gravatar-general-setting',
			'Show Gravatar',
			array( $this, 'wccm_show_gravatar_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section'
		);

		/**
		 * Upload custom gravatar from front side.
		 */
		$custom_gravatar_class = wccm_is_show_gravatar() ? 'wccm_visible' : 'wccm_hidden';
		add_settings_field(
			'wcca-custom-gravatar-general-setting',
			'Custom Gravatar',
			array( $this, 'wccm_custom_gravatar_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section',
			array( 'class' => 'hide_custom_gravatar ' . $custom_gravatar_class )
		);

		/**
		 * Show cover banner image.
		 */
		add_settings_field(
			'wcca-show-cover-general-setting',
			'Show Cover Banner',
			array( $this, 'wccm_show_cover_banner_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section'
		);

		/**
		 * Default cover banner image.
		 */
		$custom_cover_banner_class   = wccm_is_show_banner() ? 'wccm_visible' : 'wccm_hidden';
		$custom_default_banner_class = wccm_is_default_banner() ? 'wccm_visible' : 'wccm_hidden';
		$is_previewed_show           = wccm_is_default_banner() ? 'preview_on' : '';

		add_settings_field(
			'wcca-default-cover-banner-general-setting',
			'Default Cover Banner',
			array( $this, 'wccm_default_cover_banner_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section',
			array(
				'class'               => 'hide_default_cover_banner ' . $custom_cover_banner_class,
				'field_class'         => $custom_cover_banner_class,
				'default_cover_class' => $custom_default_banner_class,
				'is_previewed_show'   => $is_previewed_show

			)
		);

		/**
		 * Upload custom cover banner from front side.
		 */
		add_settings_field(
			'wcca-custom-cover-banner-general-setting',
			'Custom Cover Banner',
			array( $this, 'wccm_custom_cover_banner_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section',
			array( 'class' => 'hide_custom_cover_banner ' . $custom_cover_banner_class )
		);

		/**
		 * Show User name myaccount page.
		 */
		add_settings_field(
			'wcca-show-username-general-setting',
			'Show Client name',
			array( $this, 'wccm_show_username_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section'
		);

		/**
		 * Show User IP myaccount page.
		 */
		add_settings_field(
			'wcca-show-user-ip-general-setting',
			'Show Client IP',
			array( $this, 'wccm_show_user_ip_fields' ),
			'wc-custom-myaccount',
			'wccm-general-settings-section'
		);

		register_setting(
			'wccm-general-settings-section',
			'wccm_general_settings',
			array(
				'type'              => 'array',
				'show_in_rest'      => apply_filters( 'wccm_show_in_rest', false ),
				'sanitize_callback' => array( $this, 'wccm_save_setting_options' ),
			)
		);
	}

	/**
	 * Save the general setting.
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	function wccm_save_setting_options( $fields ) {

		if ( ! isset( $fields['wccm_show_gravatar'] ) && empty( $fields['wccm_show_gravatar'] ) ) {
			unset( $fields['wccm_custom_gravatar'] );
		}

		if ( ! isset( $fields['wccm_show_cover_banner'] ) && empty( $fields['wccm_show_cover_banner'] ) ) {
			unset( $fields['wccm_default_cover_banner_id'] );
			unset( $fields['wccm_custom_cover_banner'] );
		}

		return $fields;
	}

	/**
	 * Show Gravatar field html.
	 */
	function wccm_show_gravatar_fields() {
		printf( "<label class='wccm-switch'><input type='checkbox' name='wccm_general_settings[wccm_show_gravatar]' id='wccm_show_gravatar_field' value='1' %s /><div class='wccm-slider round'></div></label>", checked( wccm_get_general_settings_option( 'wccm_show_gravatar' ), 1, false ) );
	}

	/**
	 * Custom Gravatar field html.
	 */
	function wccm_custom_gravatar_fields() {
		printf( "<label class='wccm-switch'><input type='checkbox' name='wccm_general_settings[wccm_custom_gravatar]' id='wccm_custom_gravatar_field' value='1' %s /><div class='wccm-slider round'></div></label>", checked( wccm_get_general_settings_option( 'wccm_custom_gravatar' ), 1, false ) );
	}

	/**
	 * Cover banner field html.
	 */
	function wccm_show_cover_banner_fields() {
		printf( "<label class='wccm-switch'><input type='checkbox' name='wccm_general_settings[wccm_show_cover_banner]' id='wccm_show_cover_banner_field' value='1' %s /><div class='wccm-slider round'></div></label>", checked( wccm_get_general_settings_option( 'wccm_show_cover_banner' ), 1, false ) );
	}

	/**
	 * Default cover banner field html.
	 *
	 * @param $args
	 */
	function wccm_default_cover_banner_fields( $args ) {


		$wccm_class = $args['is_previewed_show'];

		?>

        <div class="attachment-media-view">

            <button type="button" class="wccm-upload-button button-add-media upload_file_button <?php esc_attr_e( $wccm_class ); ?>" data-choose="<?php esc_attr_e( 'Choose file', 'wc-custom-myaccount' ); ?>" data-update="<?php esc_attr_e( 'Insert file URL', 'wc-custom-myaccount' ); ?>"><?php echo esc_html__( 'Select image file', 'wc-custom-myaccount' ); ?></button>
            <div class="wccm-preview <?php esc_attr_e( $wccm_class ); ?>">
                <div class="site-banner-preview wp-clearfix">
                    <img src="<?php echo esc_url( wp_get_attachment_url( wccm_get_general_settings_option( 'wccm_default_cover_banner_id' ) ) ); ?>" alt="<?php esc_attr_e( 'Preview as a cover banner image' ); ?>"/>
                    <input type="hidden" class="input_text" name="wccm_general_settings[wccm_default_cover_banner_id]" id="wccm_default_cover_banner_id" value="<?php esc_attr_e( wccm_get_general_settings_option( 'wccm_default_cover_banner_id' ) ); ?>"/>
                </div>
                <div class="actions">
                    <button type="button" class="button remove_file_button">Remove</button>
                    <button type="button" class="button upload_file_button">Change Image</button>
                </div>
            </div>
        </div>
		<?php

	}

	/**
	 * Custom cover banner field html.
	 */
	function wccm_custom_cover_banner_fields() {
		printf( "<label class='wccm-switch'><input type='checkbox' name='wccm_general_settings[wccm_custom_cover_banner]' id='wccm_custom_cover_banner_field' value='1' %s /><div class='wccm-slider round'></div></label>", checked( wccm_get_general_settings_option( 'wccm_custom_cover_banner' ), 1, false ) );
	}

	/**
	 * Show user name field html.
	 */
	function wccm_show_username_fields() {
		printf( "<label class='wccm-switch'><input type='checkbox' name='wccm_general_settings[wccm_show_username]' id='wccm_show_username_field' value='1' %s /><div class='wccm-slider round'></div></label>", checked( wccm_get_general_settings_option( 'wccm_show_username' ), 1, false ) );
	}

	/**
	 * Show user ip field html.
	 */
	function wccm_show_user_ip_fields() {
		printf( "<label class='wccm-switch'><input type='checkbox' name='wccm_general_settings[wccm_show_user_ip]' id='wccm_show_user_ip_field' value='1' %s /><div class='wccm-slider round'></div></label>", checked( wccm_get_general_settings_option( 'wccm_show_user_ip' ), 1, false ) );
	}

	/**
	 * Sanitize html in filter_input.
	 *
	 * @param $html
	 *
	 * @return HTML
	 */
	function wccm_content_sanitize( $html ) {
		return $this->sanitize_html_field( $html );
	}

	/**
	 * Sanitizes a HTML from user input or from the database.
	 *
	 * - Checks for invalid UTF-8,
	 * - Converts single `<` characters to entities
	 * - Strips all tags
	 * - Removes line breaks, tabs, and extra whitespace
	 * - Strips octets
	 *
	 * @param HTML $html HTML to sanitize.
	 *
	 * @return HTML Sanitized string.
	 * @see wp_check_invalid_utf8()
	 *
	 * @since 2.9.0
	 *
	 * @see sanitize_textarea_field()
	 */
	function sanitize_html_field( $html ) {
		$filtered = $this->_sanitize_html_fields( $html, false );

		/**
		 * Filters a sanitized text field string.
		 *
		 * @param string $filtered The sanitized string.
		 * @param string $str The string prior to being sanitized.
		 *
		 * @since 2.9.0
		 *
		 */
		return apply_filters( 'sanitize_html_field', $filtered, $html );
	}

	/**
	 * Internal helper function to sanitize a html from user input or from the db
	 *
	 * @param string $html String to sanitize.
	 * @param bool $keep_newlines optional Whether to keep newlines. Default: false.
	 *
	 * @return string Sanitized string.
	 * @since 4.7.0
	 * @access private
	 *
	 */
	function _sanitize_html_fields( $html, $keep_newlines = false ) {
		if ( is_object( $html ) || is_array( $html ) ) {
			return '';
		}

		$html = (string) $html;

		$filtered = wp_check_invalid_utf8( $html );

		if ( strpos( $filtered, '<' ) !== false ) {
			$filtered = wp_pre_kses_less_than( $filtered );

			// Use html entities in a special case to make sure no later
			// newline stripping stage could lead to a functional tag
			$filtered = str_replace( "<\n", "&lt;\n", $filtered );
		}

		if ( ! $keep_newlines ) {
			$filtered = preg_replace( '/[\r\n\t ]+/', ' ', $filtered );
		}
		$filtered = trim( $filtered );

		$found = false;
		while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
			$filtered = str_replace( $match[0], '', $filtered );
			$found    = true;
		}

		if ( $found ) {
			// Strip out the whitespace that may now exist after removing the octets.
			$filtered = trim( preg_replace( '/ +/', ' ', $filtered ) );
		}

		return $filtered;
	}

	/**
	 * Save the updated endpoint data.
	 *
	 */
	function wccm_update_endpoint_data_callback() {

		check_ajax_referer( 'wccm-endpoint-nonce', 'security' );
		$response            = array();
		$response['success'] = false;
		$response['msg']     = __( 'Sorry, Something went wrong.', 'wc-custom-myaccount' );

		$wccm_action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $wccm_action ) && 'wccm_update_endpoint_data' === $wccm_action ) {

			$wccm_endpoint_slug = filter_input( INPUT_POST, 'wccm_endpoint_slug', FILTER_SANITIZE_STRING );

			if ( empty( $wccm_endpoint_slug ) ) {
				wp_send_json( $response );
				wp_die();
			}

			$wccm_endpoint_name        = filter_input( INPUT_POST, 'wccm_endpoint_name', FILTER_SANITIZE_STRING );
			$wccm_sub_title            = filter_input( INPUT_POST, 'wccm_sub_title', FILTER_SANITIZE_STRING );
			$wccm_content              = filter_input( INPUT_POST, 'wccm_content', FILTER_CALLBACK, array(
				'options' => array(
					$this,
					'wccm_content_sanitize'
				)
			) );
			$wccm_tab_content_position = filter_input( INPUT_POST, 'wccm_tab_content_position', FILTER_SANITIZE_STRING );

			$wccm_endpoint_name        = ! empty( $wccm_endpoint_name ) ? $wccm_endpoint_name : '';
			$wccm_sub_title            = ! empty( $wccm_sub_title ) ? $wccm_sub_title : '';
			$wccm_content              = ! empty( $wccm_content ) ? $wccm_content : '';
			$wccm_tab_content_position = ! empty( $wccm_tab_content_position ) ? $wccm_tab_content_position : 'wccm_append';

			$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();

			if ( isset( $wccm_endpoint_option ) && ! empty( $wccm_endpoint_option ) && false !== $wccm_endpoint_option ) {
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_slug'] = $wccm_endpoint_slug;
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_name'] = $wccm_endpoint_name;
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_sub_title']     = $wccm_sub_title;
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_content']       = $wccm_content;


			} else {
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_slug'] = $wccm_endpoint_slug;
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_name'] = $wccm_endpoint_name;
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_sub_title']     = $wccm_sub_title;
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_content']       = $wccm_content;

			}

			if ( wccm_is_default_woocommrece_endpoint( $wccm_endpoint_slug ) ) {
				$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_tab_content_position'] = $wccm_tab_content_position;
			}

			$response_result = wccm_update_myaccount_option( $wccm_endpoint_option );

			$response['success'] = $response_result;
			$response['msg']     = __( 'Endpoint successfully updated.', 'wc-custom-myaccount' );
		}

		wp_send_json( $response );
		wp_die();
	}

	/**
	 * Save the new endpoint data.
	 *
	 */
	function wccm_save_new_endpoint_data_callback() {

		$wccm_nonce = filter_input( INPUT_POST, 'wccm_add_new_endpoint_nonce', FILTER_SANITIZE_STRING );

		if ( isset( $wccm_nonce ) && wp_verify_nonce( $wccm_nonce, 'wccm-endpoint-nonce' ) ) {
			$wccm_endpoint_slug = filter_input( INPUT_POST, 'wccm_endpoint_slug', FILTER_SANITIZE_STRING );
			$wccm_endpoint_name = filter_input( INPUT_POST, 'wccm_endpoint_name', FILTER_SANITIZE_STRING );
			$wccm_sub_title     = filter_input( INPUT_POST, 'wccm_sub_title', FILTER_SANITIZE_STRING );
			$wccm_content       = filter_input( INPUT_POST, 'wccm_endpoint_content', FILTER_CALLBACK, array(
				'options' => array(
					$this,
					'wccm_content_sanitize'
				)
			) );
			$_wccm_http_referer = filter_input( INPUT_POST, '_wccm_http_referer', FILTER_SANITIZE_STRING );


			$wccm_endpoint_slug = ! empty( $wccm_endpoint_slug ) ? $wccm_endpoint_slug : '';
			$wccm_endpoint_name = ! empty( $wccm_endpoint_name ) ? $wccm_endpoint_name : '';
			$wccm_sub_title     = ! empty( $wccm_sub_title ) ? $wccm_sub_title : '';
			$wccm_content       = ! empty( $wccm_content ) ? $wccm_content : '';

			if ( '' !== $wccm_endpoint_name && '' !== $wccm_endpoint_slug ) {
				$wccm_endpoint_option = wccm_get_myaccount_endpoint_options();

				if ( $wccm_endpoint_slug === $wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_slug'] ) {
					$wccm_endpoint_slug = wccm_generate_random_endpoint_slug( $wccm_endpoint_slug );
				}

				if ( isset( $wccm_endpoint_option ) && ! empty( $wccm_endpoint_option ) && false !== $wccm_endpoint_option ) {

					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_slug']   = $wccm_endpoint_slug;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_name']   = $wccm_endpoint_name;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_sub_title']       = $wccm_sub_title;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_content']         = $wccm_content;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_pre_default_tab'] = true;
				} else {

					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_slug']   = $wccm_endpoint_slug;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_endpoint_name']   = $wccm_endpoint_name;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_sub_title']       = $wccm_sub_title;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_content']         = $wccm_content;
					$wccm_endpoint_option[ $wccm_endpoint_slug ]['wccm_pre_default_tab'] = true;
				}

				wccm_update_myaccount_option( $wccm_endpoint_option );
				wp_redirect( $_wccm_http_referer );
				exit;

			} else {
				wp_die( esc_html__( 'Invalid nonce specified', 'wc-custom-myaccount' ), esc_html__( 'Error', 'wc-custom-myaccount' ), array(
					'response'  => 403,
					'back_link' => 'admin.php?page=wc-custom-myaccount',
				) );
			}

		} else {
			wp_die( esc_html__( 'Invalid nonce specified', 'wc-custom-myaccount' ), esc_html__( 'Error', 'wc-custom-myaccount' ), array(
				'response'  => 403,
				'back_link' => 'admin.php?page=wc-custom-myaccount',
			) );
		}

	}

	/**
	 * My Account menu item show.
	 *
	 * @param $items
	 * @param $endpoints
	 *
	 * @return array
	 */
	function wccm_woocommerce_account_menu_items( $items, $endpoints ) {

		// Remove the logout menu item.
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		$new_items = array();

		foreach ( $items as $endpoint_name => $item ) {
			$updated_endpoint_name = wccm_get_endpoint_option( $endpoint_name );
			if ( false !== $updated_endpoint_name ) {
				$new_items[ $endpoint_name ] = $updated_endpoint_name;
			} else {
				$new_items[ $endpoint_name ] = $item;
			}
		}

		// For New added Tab.
		$wccm_new_tabs_array = wccm_new_tabs();
		if ( 0 !== count( $wccm_new_tabs_array ) ) {
			$new_items = array_merge( $new_items, $wccm_new_tabs_array );
		}

		$wccm_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$wccm_tab  = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );

		if ( 'wc-custom-myaccount' !== $wccm_page && 'endpoint' !== $wccm_tab ) {
			// Insert back the logout item.
			$new_items['customer-logout'] = $logout;
		}

		return apply_filters( 'wccm_new_wc_account_menu_items', $new_items, $items );

	}

	/**
	 * New tab html callback function.
	 */
	function wccm_get_add_new_tab_html_callback() {
		check_ajax_referer( 'wccm-endpoint-nonce', 'security' );
		$response            = array();
		$response['success'] = false;
		$response['msg']     = __( 'Sorry, Something went wrong.', 'wc-custom-myaccount' );
		$response['html']    = false;

		$wccm_action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		if ( isset( $wccm_action ) && 'wccm_get_add_new_tab_html' === $wccm_action ) {
			$wccm_file_html     = '';
			$_wccm_http_referer = filter_input( INPUT_POST, '_wccm_http_referer', FILTER_SANITIZE_STRING );
			$wccm_file          = 'partials/wc-custom-myaccount-new-tab-html.php';
			if ( file_exists( plugin_dir_path( __FILE__ ) . $wccm_file ) ) {

				ob_start();
				include_once plugin_dir_path( __FILE__ ) . $wccm_file;
				$wccm_file_html      = ob_get_clean();
				$response['html']    = $wccm_file_html;
				$response['success'] = true;
			}
		}

		wp_send_json( $response );
		wp_die();

	}

	/**
	 * Remove menu tab item callback function.
	 */
	function wccm_remove_menu_tab_item_callback() {
		check_ajax_referer( 'wccm-endpoint-nonce', 'security' );
		$response                 = array();
		$response['success']      = false;
		$response['msg']          = __( 'Sorry, Something went wrong.', 'wc-custom-myaccount' );
		$response['wccm_default'] = false;
		$wccm_action              = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $wccm_action ) && 'wccm_remove_menu_tab_item' === $wccm_action ) {

			$wccm_endpoint_slug = filter_input( INPUT_POST, 'wccm_endpoint_slug', FILTER_SANITIZE_STRING );
			$wccm_is_wcmenu     = filter_input( INPUT_POST, 'wccm_is_wcmenu', FILTER_SANITIZE_STRING );
			$wccm_is_wcmenu     = filter_var( $wccm_is_wcmenu, FILTER_VALIDATE_BOOLEAN );

			$wccm_default_item_name = wccm_get_default_woocommerce_menu_name( $wccm_endpoint_slug, $wccm_is_wcmenu );

			$wccm_response = wccm_remove_menu_item( $wccm_endpoint_slug );

			if ( true === $wccm_response ) {
				if ( false !== $wccm_default_item_name ) {
					$response['success']      = $wccm_response;
					$response['wccm_default'] = $wccm_default_item_name;
					$response['msg']          = __( 'Successfully restored.', 'wc-custom-myaccount' );
				} else {
					$response['success']      = $wccm_response;
					$response['wccm_default'] = $wccm_default_item_name;
					$response['msg']          = __( 'Successfully deleted.', 'wc-custom-myaccount' );
				}
			} else {
				if ( $wccm_default_item_name ) {
					$response['wccm_default'] = $wccm_default_item_name;
					$response['msg']          = __( 'In this tab no need to restore.', 'wc-custom-myaccount' );
				}
			}
		}
		wp_send_json( $response );
		wp_die();
	}

	/**
	 * Function added to fetch the html for developer support.
	 *
	 * @since    1.0.0
	 */
	function wccm_open_developer_support_modal() {

		check_ajax_referer( 'wccm-endpoint-nonce', 'security' );
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'wccm_open_developer_support_modal' === $action ) {
			ob_start();
			?>
            <form action="" method="post">
                <h3 class="wccm-support-request-submit-success"><?php esc_html_e( 'Support Request Submitted Successfully!', 'wc-custom-myaccount' ); ?></h3>
                <p><?php esc_html_e( 'We usually respond within the next 24 hours. Thank you for your patience!', 'wc-custom-myaccount' ); ?></p>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row"><label><?php esc_html_e( 'Name', 'wc-custom-myaccount' ); ?></label></th>
                        <td>
                            <input type="text" class="regular-text" placeholder="Eg: Mr. X" id="wccm-requesting-user-name" required/>
                            <p class="description"><?php esc_html_e( 'Your name goes above.', 'wc-custom-myaccount' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label><?php esc_html_e( 'Email', 'wc-custom-myaccount' ); ?></label></th>
                        <td>
                            <input type="email" class="regular-text" placeholder="Eg: testuser@example.com" id="wccm-requesting-user-email"
                                   value="<?php esc_attr_e( get_option( 'admin_email' ) ); ?>" required/>
                            <p class="description"><?php esc_html_e( 'Your email goes above.', 'wc-custom-myaccount' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label><?php esc_html_e( 'Message', 'wc-custom-myaccount' ); ?></label></th>
                        <td>
                            <textarea placeholder="Eg: my concern is..." id="wccm-requesting-user-message" required></textarea>
                            <p class="description"><?php esc_html_e( 'Your concern message goes above...', 'wc-custom-myaccount' ); ?></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <button type="submit"
                            class="button button-primary wccm-submit-request"><?php esc_html_e( 'Submit', 'wc-custom-myaccount' ); ?></button>
                </p>
            </form>
			<?php
			$html = ob_get_clean();

			wp_send_json_success(
				array(
					'message' => 'wccm-developer-support-modal',
					'html'    => $html
				)
			);
			wp_die();
		}

	}

	/**
	 * Function added to submit developer support request.
	 *
	 * @since    1.0.0
	 */
	function wccm_submit_support_request() {

		check_ajax_referer( 'wccm-endpoint-nonce', 'security' );
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'wccm_submit_support_request' === $action ) {
			$name    = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
			$email   = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING );
			$message = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );

			$mail_content = 'Hi Dev<br /><br />';
			$mail_content .= 'There is one support request from: <br /><br />';
			$mail_content .= "Name: {$name} ({$email})<br />";
			$mail_content .= "Message: {$message}<br /><br />";
			$mail_content .= "Thank you!";
			$headers      = array( 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( 'adarsh.srmcem@gmail.com', 'Developer Support Request @ WC Custom MyAccount Plugin', $mail_content, $headers );
			wp_send_json_success(
				array(
					'message' => 'wccm-developer-support-request-submitted',
				)
			);
			wp_die();
		}

	}

	/**
	 * Support request html file include.
	 */
	function wccm_support_request_html() {

		$file_path = 'partials/wccm-submit-support-request.php';
		if ( file_exists( plugin_dir_path( __FILE__ ) . $file_path ) ) {
			include_once plugin_dir_path( __FILE__ ) . $file_path;
		}

	}

	/**
	 * Adding custom MyAccount fields to individual user settings page and to user list.
	 *
	 * @param $user
	 */
	function wccm_add_wc_myaccount_user_fields( $user ) {
		$wccm_myaccount_data = get_user_meta( $user->ID, 'wccm_myaccount_profile', true );
		$wccm_banner_url     = ( isset( $wccm_myaccount_data['wccm_myaccount_profile_cover'] ) ) ? wp_get_attachment_url( $wccm_myaccount_data['wccm_myaccount_profile_cover'] ) : '';
		if ( '' !== $wccm_banner_url ) {
			?>
            <h3><?php esc_html_e( "WC Custom Myaccount", "wc-custom-myaccount" ); ?></h3>
            <table class="form-table">
                <tr class="user-cover-banner-wrap">
                    <th><label for="Cover Banner"><?php esc_html_e( 'Cover Banner', 'wc-custom-myaccount' ); ?></label></th>
                    <td>
                        <img src="<?php echo esc_url( $wccm_banner_url ); ?>" alt="<?php esc_attr_e( 'Preview as a cover banner image', 'wc-custom-myaccount' ); ?>"/>
                    </td>
                </tr>
            </table>
			<?php
		}
	}

}
