<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $wccm_new_tab_data ) ) {
	return false;
}
$endpoint                  = ( isset( $wccm_new_tab_data['wccm_endpoint_slug'] ) && ! empty( $wccm_new_tab_data['wccm_endpoint_slug'] ) ) ? $wccm_new_tab_data['wccm_endpoint_slug'] : '';
$label                     = ( isset( $wccm_new_tab_data['wccm_endpoint_name'] ) && ! empty( $wccm_new_tab_data['wccm_endpoint_name'] ) ) ? $wccm_new_tab_data['wccm_endpoint_name'] : '';
$wccm_sub_title            = ( isset ( $wccm_new_tab_data['wccm_sub_title'] ) && ! empty( $wccm_new_tab_data['wccm_sub_title'] ) ) ? $wccm_new_tab_data['wccm_sub_title'] : '';
$wccm_content              = ( isset( $wccm_new_tab_data['wccm_content'] ) && ! empty( $wccm_new_tab_data['wccm_content'] ) ? $wccm_new_tab_data['wccm_content'] : '' );
$wccm_tab_content_position = ( isset( $wccm_new_tab_data['wccm_tab_content_position'] ) && ! empty( $wccm_new_tab_data['wccm_tab_content_position'] ) ) ? $wccm_new_tab_data['wccm_tab_content_position'] : 'wccm_append';
?>
<div class="wccm-endpoint-box" id="wccm-<?php esc_attr_e( $endpoint ); ?>">
    <div class="wccm-accordion" data-id="wccm-box-<?php esc_attr_e( $endpoint ); ?>">
        <span class="wccm-title"><?php esc_html_e( $label ); ?></span>
        <i class="wccm-icon"></i>
    </div>
    <div class="wccm-content-panel" id="wccm-box-<?php esc_attr_e( $endpoint ); ?>">
        <form method="post" name="wccm-<?php esc_attr_e( $endpoint ); ?>" id="wccm-form-<?php esc_attr_e( $endpoint ); ?>" action="javascript:void(0);">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wccm_endpoint_name">
							<?php esc_html_e( 'End Point Name:' ); ?>
                            <span class="wccm-error">*</span>
                        </label>
                    </th>
                    <td>
                        <input name="wccm_endpoint_name" type="text" id="wccm_endpoint_name" value="<?php esc_attr_e( $label ); ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="wccm_sub_title">
							<?php esc_html_e( 'Custom Sub Title' ); ?>
                        </label>
                    </th>
                    <td>
                        <input name="wccm_sub_title" type="text" id="wccm_sub_title" value="<?php esc_attr_e( $wccm_sub_title ); ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="wccm_content">
							<?php esc_html_e( 'Content' ); ?>
                        </label>
                    </th>
                    <td>
						<?php
						$content        = $wccm_content;
						$editor_id      = 'wccm_endpoint_content_' . $endpoint;
						$editor_setting = array(
							'textarea_rows' => 10,
							'quicktags'     => false,
						);
						wp_editor( $content, $editor_id, $editor_setting );
						?>
                    </td>
                </tr>
            </table>
            <div>
                <div class="wccm-actions">
					<?php submit_button( __( 'Save', 'wc-custom-myaccount' ), 'primary wccm_save_tab', $endpoint ); ?>
                </div>
                <div class="wccm-tab-actions">
                    <span class="wccm-tab-remove">
                        <?php
                        if ( wccm_is_default_woocommerce_menu_item( $endpoint ) ) {
	                        printf(
		                        '<a href="javascript:void(0);" data-remenu="%s" data-wcmenu="true" class="wccma-remove-endpoints">%s</a>',
		                        esc_attr__( $endpoint, 'wc-custom-myaccount' ),
		                        esc_html__( 'Restore default', 'wc-custom-myaccount' )
	                        );
                        } else {
	                        printf(
		                        '<a href="javascript:void(0);" data-remenu="%s" data-wcmenu="false" class="wccma-remove-endpoints">%s</a>',
		                        esc_attr__( $endpoint, 'wc-custom-myaccount' ),
		                        esc_html__( 'Remove', 'wc-custom-myaccount' )
	                        );
                        }
                        ?>
                    </span>
					<?php if ( wccm_is_default_woocommrece_endpoint( $endpoint ) ) { ?>
                        <div class="wccm-position-action">
                            <label>
                                <input type="radio" value="wccm_override" name="wccm_tab_content_position" class="wccm_tab_content_position" <?php checked( $wccm_tab_content_position, 'wccm_override', true ); ?> />
								<?php
								esc_html_e( 'Override ', 'wc-custom-myaccount' );
								?>
                            </label>
                            <label>
                                <input type="radio" value="wccm_prepend" name="wccm_tab_content_position" class="wccm_tab_content_position" <?php checked( $wccm_tab_content_position, 'wccm_prepend', true ); ?>/>
								<?php
								esc_html_e( 'Prepend ', 'wc-custom-myaccount' );
								?>
                            </label>
                            <label>
                                <input type="radio" value="wccm_append" name="wccm_tab_content_position" class="wccm_tab_content_position" <?php checked( $wccm_tab_content_position, 'wccm_append', true ); ?>/>
								<?php
								esc_html_e( 'Append ', 'wc-custom-myaccount' );
								?>
                            </label>
                        </div>
					<?php } ?>
                </div>
            </div>

            <div class="wccm-response-msg wccm-response-<?php esc_attr_e( $endpoint ); ?>"></div>
        </form>
    </div>
</div>
