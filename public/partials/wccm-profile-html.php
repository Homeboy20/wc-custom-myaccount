<?php

$wccm_myaccount_profile_cover = WCCM_PLUGIN_URL . 'public/images/wccm-banner-default.jpg';
if ( wccm_is_custom_cover_banner() && isset( $wccm_myaccount_profile['wccm_myaccount_profile_cover'] ) ) {
	$wccm_myaccount_profile_cover = wp_get_attachment_url( $wccm_myaccount_profile['wccm_myaccount_profile_cover'] );
} elseif ( wccm_is_default_banner() ) {
	$wccm_myaccount_profile_cover = wp_get_attachment_url( wccm_get_general_settings_option( 'wccm_default_cover_banner_id' ) );
}
$wccm_myaccount_profile_pic = '';
if ( wccm_is_custom_gravatar() && isset( $wccm_myaccount_profile['wccm_myaccount_profile_pic'] ) ) {
	$wccm_myaccount_profile_pic = wp_get_attachment_url( $wccm_myaccount_profile['wccm_myaccount_profile_pic'] );
} else {
	$wccm_myaccount_profile_pic = get_avatar_url( $wccm_current_user->user_email );
}

$wccm_myaccount_username = ! empty( $wccm_myaccount_username ) ? $wccm_myaccount_username : 'Gust';
?>
<div id="wccm-container">

    <div id="wccm-timeline-container">
		<?php if ( wccm_is_show_banner() ) { ?>
            <!-- timeline background -->
            <div id="wccm-timeline-background">
                <img src="<?php echo esc_url( $wccm_myaccount_profile_cover ); ?>" class="wccm_myaccount_profile_cover" style="margin-top: -10px;width: 100%;">
            </div>
			<?php if ( wccm_is_custom_cover_banner() ) { ?>
				<?php esc_html_e( wc_help_tip( __( 'Cover banner image allow 2MB or less.', 'wc-custom-myaccount' ) ) ); ?>
			<?php } ?>
            <!-- timeline background -->
            <div id="wccm-timeline-shade">
				<?php if ( wccm_is_custom_cover_banner() ) { ?>
                    <form id="bgimageform" method="post" enctype="multipart/form-data" action="javascript:void(0);">
                        <div class="wccm-bg-upload-file wccm-timeline-upload-bg">
                            <input type="file" name="wccm_bg_photo_img" id="wccm_bg_photo_img" class="wccm-custom-file-input">
                        </div>
                    </form>
				<?php } ?>
            </div>
		<?php } ?>

		<?php if ( wccm_is_show_gravatar() ) { ?>
            <!-- timeline profile picture -->
            <div id="wccm-timeline-profile-pic">
                <img src="<?php echo esc_url( $wccm_myaccount_profile_pic ); ?>" class="wccm_myaccount_profile_pic">
				<?php if ( wccm_is_custom_gravatar() ) { ?>
                    <div class="wccm-pp-upload-file wccm-upload-profile">
                        <input type="file" name="wccm_profile_photo_img" id="wccm_profile_photo_img" class="wccm-custom-file-input">
                    </div>
					<?php esc_html_e( wc_help_tip( __( 'Please make sure GDPR.', 'wc-custom-myaccount' ) ) ); ?>
				<?php } ?>
            </div>
		<?php } ?>

		<?php
		$wccm_style = '';
		if ( ! wccm_is_show_gravatar() ) {
			$wccm_style = 'margin-left: 20px;';
		}
		?>

		<?php
		if ( wccm_is_username_myaccount() ) {
			?>
            <!-- timeline title -->
            <div id="wccm-timeline-title" style="<?php esc_attr_e( $wccm_style ); ?>">
				<?php esc_html_e( $wccm_myaccount_username ); ?>
            </div>
			<?php
		}
		if ( wccm_is_user_ip_myaccount() ) {
			?>
            <div id="wccm-timeline-ip" style="<?php esc_attr_e( $wccm_style ); ?>">
				<?php esc_html_e( 'Your IP ' . wccm_get_client_ip() ); ?>
            </div>
			<?php
		}
		?>

        <!-- timeline nav -->
        <div id="wccm-timeline-nav"></div>

        <!-- timeline error -->
        <div id="wccm-timeline-error"></div>

    </div>
</div>