<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://multidots.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Myaccount
 * @subpackage Wc_Custom_Myaccount/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<?php
	printf( '<h1>%s</h1>', esc_html( 'Custom My Account' ) );

	do_action( 'wccm_settings_tabs' );

	do_action( 'wccm_settings_contents' );
	?>
</div>
