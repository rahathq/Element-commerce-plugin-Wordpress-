/**
 * ECMA Addons Admin Dashboard Javascript Script
 */
jQuery(document).ready(function($) {
	'use strict';

	// Handle Widget Activation Toggle Switches
	$('.ecma-widget-toggle').on('change', function() {
		var $toggle = $(this);
		var widgetId = $toggle.data('widget-id');
		var isChecked = $toggle.is(':checked');
		var status = isChecked ? 'yes' : 'no';

		// Temporarily lock toggle switch input control during database update
		$toggle.prop('disabled', true);

		$.ajax({
			url: ecmaAdmin.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'ecma_toggle_widget',
				widget_id: widgetId,
				status: status,
				nonce: ecmaAdmin.nonce
			},
			success: function(response) {
				// Unlock control
				$toggle.prop('disabled', false);

				if (response.success) {
					showToast(response.data.message || 'Settings updated successfully.');
				} else {
					// Revert toggle state if update failed on server-side check
					$toggle.prop('checked', !isChecked);
					showToast(response.data || 'Failed to update widget settings.', true);
				}
			},
			error: function() {
				// Revert toggle state and unlock control on network errors
				$toggle.prop('disabled', false);
				$toggle.prop('checked', !isChecked);
				showToast('A network connection error occurred.', true);
			}
		});
	});

	// Toast Notification Utility
	function showToast(message, isError) {
		var $toast = $('#ecma-toast');
		if (!$toast.length) {
			return;
		}

		var $icon = $toast.find('.ecma-toast-icon');
		var $msg = $toast.find('.ecma-toast-message');

		$msg.text(message);

		if (isError) {
			$icon.removeClass('dashicons-yes-alt').addClass('dashicons-warning');
			$icon.css('color', '#ef4444');
		} else {
			$icon.removeClass('dashicons-warning').addClass('dashicons-yes-alt');
			$icon.css('color', '#10b981');
		}

		// Reveal toast
		$toast.addClass('show');

		// Hide toast after 3 seconds
		setTimeout(function() {
			$toast.removeClass('show');
		}, 3000);
	}
});
