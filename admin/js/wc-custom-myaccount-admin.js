jQuery(document).ready(function($) {
	'use strict';

	//File upload on backend
	var wccm_file_frame;

	var wccm_fields = {
		/**
		 * Initialization Custom My Account Tab Js.
		 */
		init: function() {
			$(document.body).
				on('click', '.wccm-accordion', this.wccmAccordionClick).
				on('click', '.wccm_add_tab', this.wccmOpenAddTabModle).
				on('click', '.wccm_save_tab', this.wccmUpdateTabChanges).
				on('click', '.wccm_save_new_tab', this.wccmSaveNewTab).
				on('click', '.wccm-open-support-modal', this.wccmOpenSupportModal).
				on('click', '.wccm-close', this.wccmCloseModal).
				on('input propertychange paste', '#wccm_endpoint_name', this.wccmEndpointChange).
				on('click', '.wccma-remove-endpoints', this.wccmRemoveTab).
				on('keypress', '#wccm_endpoint_slug', this.wccmSlugKeyPress).
				on('click', '.wccm-submit-request', this.wccmSubmitSupportRequest).
				on('click', '.upload_file_button', this.wccmUploadCoverBannerImage).
				on('click', '.remove_file_button', this.wccmRemoveCoverBannerImage).
				on('click', '#wccm_show_gravatar_field', this.wccmShowCustomGravatarOption).
				on('click', '#wccm_show_cover_banner_field', this.wccmShowCoverBannerOption);
		},

		wccmShowCustomGravatarOption: function(event) {
			$('.hide_custom_gravatar').slideToggle('slow');
		},

		wccmShowCoverBannerOption: function(event) {
			$('.hide_default_cover_banner').slideToggle('slow');
			$('.hide_custom_cover_banner').slideToggle('slow');
		},

		/**
		 * Plugin Menu Accordion Toggle.
		 * @param event
		 */
		wccmAccordionClick: function(event) {
			event.preventDefault();

			var $data_id = $(this).data('id');

			if ($(this).hasClass('active')) {
				$(this).removeClass('active');
				$('#' + $data_id).slideToggle('slow');
			} else {
				$('.wccm-accordion').removeClass('active');
				$(this).addClass('active');
				$('.wccm-content-panel').slideUp('slow');
				$('#' + $data_id).slideDown('slow');
			}
		},

		/**
		 * Create Endpoint slug.
		 * @param event
		 */
		wccmEndpointChange: function(event) {
			var $this = $(this);
			var $wccm_endpoint_name = $this.val();

			// Replace the dash string for rewrite rule.
			var $wccm_endpoint_slug = $wccm_endpoint_name.replace(/\W+(?!$)/g, '-').toLowerCase();
			$wccm_endpoint_slug = $wccm_endpoint_slug.replace(/\W$/, '').toLowerCase();
			$wccm_endpoint_slug = $wccm_endpoint_slug.replace(/\d+/g, '').toLowerCase();

			$('#wccm_endpoint_slug').val($wccm_endpoint_slug);

		},

		wccmSlugKeyPress: function(event) {
			var wccm_slug = String.fromCharCode(event.which);
			if ( !wccm_slug.match(/[A-Za-z-]/)) {
				return false;
			}
		},

		/**
		 * Add new Tab button click toggle open and close.
		 * @param event
		 */
		wccmOpenAddTabModle: function(event) {
			event.preventDefault();

			$('#wccm-request-addtab-modal').css('display', 'block');
			$('.wccm-request-addtab-modal-content').html('<img src=" ' + WCCM_Admin_JS_Obj.loader_url + ' " alt="Loader" /><p> ' + WCCM_Admin_JS_Obj.fetch_support_modal_wait_msg + '</p>').addClass('wccm-modal-loading');
			var data = {
				'action': 'wccm_get_add_new_tab_html',
				security: WCCM_Admin_JS_Obj.wccm_nonce,
				_wccm_http_referer: WCCM_Admin_JS_Obj._wccm_http_referer,
			};
			$.ajax({
				dataType: 'JSON',
				url: WCCM_Admin_JS_Obj.wccm_admin_url,
				type: 'POST',
				data: data,
				success: function(response) {
					if (true === response.success) {
						$('.wccm-request-addtab-modal-content').html(response.html).removeClass('wccm-modal-loading');
						tinymce.execCommand('mceAddEditor', false, 'wccm_endpoint_content');
						/*quicktags({id: 'wccm_endpoint_content'});*/
					}
				},
			});

		},

		wccmCloseModal: function(event) {
			event.preventDefault();
			var $this = $(this);
			$this.closest('.wccm-modal').css('display', 'none');
			tinymce.execCommand('mceRemoveEditor', false, 'wccm_endpoint_content');
		},

		/**
		 * Exists Tab data Updated.
		 * @param event
		 */
		wccmUpdateTabChanges: function(event) {
			event.preventDefault();
			tinyMCE.triggerSave();
			var $saveButton = $(this),
				$wccm_endpoint_slug = $saveButton.attr('id'),
				$form_selecter = $('#wccm-form-' + $wccm_endpoint_slug);

			var $wccm_endpoint_name = $form_selecter.find('#wccm_endpoint_name').val();
			var $wccm_sub_title = $form_selecter.find('#wccm_sub_title').val();
			var $content_text = '';
			var tinymce_element = 'wccm_endpoint_content_' + $wccm_endpoint_slug;
			if ($('#wp-' + tinymce_element + '-wrap').hasClass('tmce-active')) {
				$content_text = tinyMCE.get(tinymce_element).getContent();
			} else {
				$content_text = $('#' + tinymce_element).val();
			}

			var $wccm_tab_content_position = $form_selecter.find('input[name=wccm_tab_content_position]:checked').val();

			$('.wccm-validation-msg').remove();

			if ($wccm_endpoint_name.length < 1) {
				$form_selecter.find('#wccm_endpoint_name').after('<span class="wccm-validation-msg wccm-error">' + WCCM_Admin_JS_Obj.wccm_endpoint_name_msg + '</span>');
				return false;
			}

			$.ajax({
				type: 'post',
				url: WCCM_Admin_JS_Obj.wccm_admin_url,
				data: {
					action: 'wccm_update_endpoint_data',
					security: WCCM_Admin_JS_Obj.wccm_nonce,
					wccm_endpoint_slug: $wccm_endpoint_slug,
					wccm_endpoint_name: $wccm_endpoint_name,
					wccm_sub_title: $wccm_sub_title,
					wccm_content: $content_text,
					wccm_tab_content_position: $wccm_tab_content_position,
				},

				success: function(response) {
					if (true === response.success) {
						$('.wccm-accordion.active .wccm-title').text($wccm_endpoint_name);
						$form_selecter.find('.wccm-response-msg').text(response.msg).show().addClass('wccm-success').removeClass('wccm-error').delay(3000).fadeOut(400);
					} else {
						$form_selecter.find('.wccm-response-msg').text(response.msg).show().addClass('wccm-error').removeClass('wccm-success').delay(3000).fadeOut(400);
					}
				},
			});
		},

		/**
		 * Save New Tab.
		 * @param event
		 */
		wccmSaveNewTab: function(event) {
			event.preventDefault();
			tinyMCE.triggerSave();
			var $saveButton = $(this), $form_selecter = $('#wccm-form-new-tab');

			var $wccm_endpoint_name = $form_selecter.find('#wccm_endpoint_name').val();
			var $wccm_endpoint_slug = $form_selecter.find('#wccm_endpoint_slug').val();
			var $wccm_sub_title = $form_selecter.find('#wccm_sub_title').val();

			var $content_text = '';
			var tinymce_element = 'wccm_endpoint_content';
			if ($('#wp-' + tinymce_element + '-wrap').hasClass('tmce-active')) {
				$content_text = tinyMCE.get(tinymce_element).getContent();
			} else {
				$content_text = $('#' + tinymce_element).val();
			}

			var $is_valid = true;

			$('.wccm-validation-msg').remove();

			if ($wccm_endpoint_name.length < 1) {
				$form_selecter.find('#wccm_endpoint_name').after('<span class="wccm-validation-msg wccm-error">' + WCCM_Admin_JS_Obj.wccm_endpoint_name_msg + '</span>');
				$is_valid = false;
			}

			if ($wccm_endpoint_slug.length < 1) {
				$form_selecter.find('#wccm_endpoint_slug').after('<span class="wccm-validation-msg wccm-error">' + WCCM_Admin_JS_Obj.wccm_endpoint_slug_msg + '</span>');
				$is_valid = false;
			}

			if ($wccm_sub_title.length < 1) {
				$form_selecter.find('#wccm_sub_title').after('<span class="wccm-validation-msg wccm-error">' + WCCM_Admin_JS_Obj.wccm_sub_title_msg + '</span>');
				$is_valid = false;
			}

			if (false === $is_valid) {
				return false;
			}

			$form_selecter.submit();
		},

		wccmOpenSupportModal: function(event) {
			event.preventDefault();
			$('#wccm-request-support-modal').css('display', 'block');
			$('.wccm-request-support-modal-content').html('<img src=" ' + WCCM_Admin_JS_Obj.loader_url + ' " alt="Loader" /><p> ' + WCCM_Admin_JS_Obj.fetch_support_modal_wait_msg + '</p>').addClass('wccm-modal-loading');

			var data = {
				'action': 'wccm_open_developer_support_modal',
				'security': WCCM_Admin_JS_Obj.wccm_nonce,
			};

			$.ajax({
				dataType: 'JSON',
				url: WCCM_Admin_JS_Obj.wccm_admin_url,
				type: 'POST',
				data: data,
				success: function(response) {
					if ('wccm-developer-support-modal' === response.data.message) {
						$('.wccm-request-support-modal-content').html(response.data.html).removeClass('wccm-modal-loading');
					}
				},
			});

		},

		wccmRemoveTab: function(event) {

			event.preventDefault();
			var $this = $(this);
			var $wccm_endpoint_slug = $this.data('remenu');
			var $wccm_is_wcmenu = $this.data('wcmenu');
			var $wccm_slug = 'wccm_endpoint_content_' + $wccm_endpoint_slug;
			var $form_selecter = $('#wccm-form-' + $wccm_endpoint_slug);

			var data = {
				'action': 'wccm_remove_menu_tab_item',
				'wccm_endpoint_slug': $wccm_endpoint_slug,
				'wccm_is_wcmenu': $wccm_is_wcmenu,
				security: WCCM_Admin_JS_Obj.wccm_nonce,
			};

			$.ajax({
				dataType: 'JSON',
				url: WCCM_Admin_JS_Obj.wccm_admin_url,
				type: 'POST',
				data: data,
				success: function(response) {
					if (true === response.success) {
						if (false !== response.wccm_default) {
							var $wccm_form = $('#wccm-form-' + $wccm_endpoint_slug);
							$wccm_form.find('#wccm_endpoint_name').val(response.wccm_default);
							$('.wccm-accordion.active .wccm-title').text(response.wccm_default);
							$wccm_form.find('#wccm_sub_title').val('');
							var activeEditor = tinyMCE.get($wccm_slug);
							var content = '';
							if (activeEditor !== null) {
								activeEditor.setContent(content);
							} else {
								$('#' + $wccm_slug).val(content);
							}
							$form_selecter.find('.wccm-response-msg').text(response.msg).show().addClass('wccm-success').removeClass('wccm-error').delay(3000).fadeOut(400);
						} else {
							$('#wccm-' + $wccm_endpoint_slug).fadeOut(300, function() {
								$(this).remove();
							});
						}
					} else {
						$form_selecter.find('.wccm-response-msg').text(response.msg).show().addClass('wccm-error').removeClass('wccm-success').delay(3000).fadeOut(400);
					}
				},
			});
		},

		wccmSubmitSupportRequest: function(event) {
			event.preventDefault();

			var $name = $('#wccm-requesting-user-name').val();
			var $email = $('#wccm-requesting-user-email').val();
			var $message = $('#wccm-requesting-user-message').val();
			var btn_html = $('.wccm-submit-request').html();
			$('.wccm-submit-request').html('<i class="fa fa-refresh fa-spin"></i> Pocessing...');
			var $data = {
				'action': 'wccm_submit_support_request',
				'name': $name,
				'email': $email,
				'message': $message,
				'security': WCCM_Admin_JS_Obj.wccm_nonce,
			};
			$.ajax({
				dataType: 'JSON',
				url: WCCM_Admin_JS_Obj.wccm_admin_url,
				type: 'POST',
				data: $data,
				success: function(response) {
					if ('wccm-developer-support-request-submitted' === response.data.message) {
						$('.wccm-submit-request').html(btn_html);
						$('.wccm-support-request-submit-success').show();
						setTimeout(function() {
							$('#wccm-request-support-modal').fadeOut();
						}, 3000);
					}
				},
			});
		},

		wccmUploadCoverBannerImage: function(event) {
			var $el = $(this);
			$.fn.renderMediaUploader($el);
		},
		wccmRemoveCoverBannerImage: function(event) {
			$('.wccm-preview').hide();
			$('.wccm-upload-button').show();
			$('.site-banner-preview img').attr('src', '');
			$('#wccm_default_cover_banner_id').val('');
		},
	};
	wccm_fields.init();

	$.fn.renderMediaUploader = function($el) {
		'use strict';

		/**
		 * If an instance of wccm_file_frame already exists, then we can open it
		 * rather than creating a new instance.
		 */
		if (undefined !== wccm_file_frame) {

			wccm_file_frame.open();
			return;

		}

		/**
		 * If we're this far, then an instance does not exist, so we need to
		 * create our own.
		 *
		 * Here, use the wp.media library to define the settings of the Media
		 * Uploader. We're opting to use the 'post' frame which is a template
		 * defined in WordPress core and are initializing the file frame
		 * with the 'insert' state.
		 *
		 * We're also not allowing the user to select more than one image.
		 */
		wccm_file_frame = wp.media.frames.wccm_file_frame = wp.media({
			title: $el.data('choose'),
			button: {text: $el.data('update')},
			multiple: false,
		});

		/**
		 * Setup an event handler for what to do when an image has been
		 * selected.
		 *
		 * Since we're using the 'view' state when initializing
		 * the wccm_file_frame, we need to make sure that the handler is attached
		 * to the insert event.
		 */
		wccm_file_frame.on('select', function() {

			// We set multiple to false so only get one image from the uploader
			var attachment = wccm_file_frame.state().get('selection').first().toJSON();
			var attachment_url = attachment.url, attachment_id = attachment.id;

			// Do something with attachment.id and/or attachment.url here
			$el.closest('tr').find('.site-banner-preview input[type="hidden"]').val(attachment_id);
			//$el.closest('tr').find('div.file_url input[type="text"]').val(attachment_url);
			$el.closest('tr').find('.site-banner-preview img').attr('src', attachment_url);

			$('.wccm-upload-button').hide();
			$('.wccm-preview').show();

		});

		// Now display the actual wccm_file_frame
		wccm_file_frame.open();

	};

});
