(function($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */



	$('#wccm_bg_photo_img').on('change', function() {
		var $wccm_preview = 'wccm_myaccount_profile_cover';
		$.fn.wccmAjax(this, $wccm_preview);
	});

	$('#wccm_profile_photo_img').on('change', function() {
		var $wccm_preview = 'wccm_myaccount_profile_pic';
		//$.fn.wccmReadURL(this, $wccm_preview);
		$.fn.wccmAjax(this, $wccm_preview);
	});

	$.fn.wccmAjax = function(input, preview_position) {

		var $file_obj = input, $file = input.files[0], $file_position = preview_position;

		var wccmFormData = new FormData();
		wccmFormData.append('wccm_file', $file);
		wccmFormData.append('wccm_file_position', $file_position);
		wccmFormData.append('action', 'wccm_update_myaccount_frontend_data');
		wccmFormData.append('security', WCCM_Public_JS_Obj.wccm_nonce);

		$.ajax({
			type: 'POST',
			url: WCCM_Public_JS_Obj.wccm_admin_url,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			data: wccmFormData,

			success: function(response) {
				console.log(response);
				if (true === response.success) {
					$('#wccm-timeline-error').text(response.msg).show().addClass('wccm-success').removeClass('wccm-error').delay(3000).fadeOut(400, function() {
						$(this).text('');
					});
					$.fn.wccmReadURL($file_obj, $file_position);
				} else {
					$('#wccm-timeline-error').text(response.msg).show().addClass('wccm-error').removeClass('wccm-success').delay(3000).fadeOut(400, function() {
						$(this).text('');
					});
				}
			},
		});

	};

	$.fn.wccmReadURL = function(input, image_preview) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('.' + image_preview).attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	};

	$('.woocommerce-help-tip').tipTip({
		'attribute': 'data-tip',
		'fadeIn': 50,
		'fadeOut': 50,
		'delay': 200,
	});

})(jQuery);
