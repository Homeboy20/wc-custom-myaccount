<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


?>
<div class="wccm-add-new-tab">
    <div class="wccm-endpoint-box">
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" name="wccm-form-new-tab" id="wccm-form-new-tab" novalidate="novalidate">
            <input type="hidden" name="action" value="wccm_save_new_endpoint_data">
			<?php if ( isset( $_wccm_http_referer ) && ! empty( $_wccm_http_referer ) ) { ?>
                <input type="hidden" name="wccm_add_new_endpoint_nonce" value="<?php esc_attr_e( wp_create_nonce( 'wccm-endpoint-nonce' ) ); ?>"/>
                <input type="hidden" name="_wccm_http_referer" value="<?php esc_attr_e( $_wccm_http_referer ); ?>"/>
			<?php } ?>

            <table class="form-table">

                <tr>
                    <th scope="row">
                        <label for="wccm_endpoint_name">
							<?php esc_html_e( 'End Point Name:', 'wc-custom-myaccount' ); ?>
                            <span class="wccm-error">*</span>
                        </label>
                    </th>
                    <td>
                        <input name="wccm_endpoint_name" type="text" id="wccm_endpoint_name" value="" class="regular-text" required placeholder="<?php esc_html_e('Endpoint title...', 'wc-custom-myaccount');?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row"></th>
                    <td>
                        <input name="wccm_endpoint_slug" type="text" id="wccm_endpoint_slug" value="" class="regular-text" required placeholder="<?php esc_html_e('Endpoint slug...', 'wc-custom-myaccount');?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="wccm_sub_title">
							<?php esc_html_e( 'Custom Sub Title', 'wc-custom-myaccount' ); ?>
                            <span class="wccm-error">*</span>
                        </label>
                    </th>
                    <td>
                        <input name="wccm_sub_title" type="text" id="wccm_sub_title" value="" class="regular-text" required placeholder="<?php esc_html_e('Subtitle...', 'wc-custom-myaccount');?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="wccm_content">
							<?php esc_html_e( 'Content', 'wc-custom-myaccount' ); ?>
                        </label>
                    </th>
                    <td>
						<?php
						$content        = '';
						$editor_id      = 'wccm_endpoint_content';
						$editor_setting = array(
							'textarea_rows' => 10,
							'quicktags'     => false,
						);
						wp_editor( $content, $editor_id, $editor_setting );
						?>
                    </td>
                </tr>

            </table>
			<?php submit_button( esc_html__( 'Submit', 'wc-custom-myaccount' ), 'wccm_save_new_tab', 'button-primary wccm_save_new_tab' ); ?>
            <div class="wccm-response-msg wccm-new-tab"></div>
        </form>

    </div>
</div>
