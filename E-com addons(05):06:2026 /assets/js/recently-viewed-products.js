/**
 * E-Com Addons: Recently Viewed Products Tracking and AJAX Loading
 */
(function ($) {
	'use strict';

	// Retrieve tracked product IDs list from Local Storage (with cookie fallback)
	function getViewedProducts() {
		var viewed = [];
		try {
			var stored = localStorage.getItem('ecma_viewed_products');
			if (stored) {
				viewed = JSON.parse(stored);
			}
		} catch (e) {}

		// Cookie fallback if localStorage is empty/unavailable
		if (!viewed || !viewed.length) {
			var match = document.cookie.match(/(^|;)\s*ecma_viewed_products\s*=\s*([^;]+)/);
			if (match) {
				try {
					viewed = JSON.parse(decodeURIComponent(match[2]));
				} catch (e) {}
			}
		}
		return Array.isArray(viewed) ? viewed : [];
	}

	// Track visits to single product pages automatically
	function trackProductVisit() {
		if (typeof ecmaRecentParams === 'undefined' || !ecmaRecentParams.is_single || !ecmaRecentParams.product_id) {
			return;
		}

		var currentId = parseInt(ecmaRecentParams.product_id, 10);
		if (isNaN(currentId) || currentId <= 0) {
			return;
		}

		var viewed = getViewedProducts();

		// Remove existing duplicates to keep chronological sequence
		viewed = viewed.filter(function (id) {
			return id !== currentId;
		});

		// Add current viewed item at the end of the array (most recent)
		viewed.push(currentId);

		// Cap storage at 15 items maximum
		if (viewed.length > 15) {
			viewed = viewed.slice(viewed.length - 15);
		}

		try {
			localStorage.setItem('ecma_viewed_products', JSON.stringify(viewed));
		} catch (e) {}

		// Fallback cookie configuration
		document.cookie = 'ecma_viewed_products=' + encodeURIComponent(JSON.stringify(viewed)) + '; path=/; max-age=' + (365 * 24 * 60 * 60) + '; SameSite=Lax';
	}

	// Request rendered product cards from WordPress AJAX
	function initWidget($scope) {
		var $wrap = $scope.find('.ecma-recent-products-wrap');
		if (!$wrap.length) {
			return;
		}

		var settings = {};
		try {
			settings = JSON.parse($wrap.attr('data-settings') || '{}');
		} catch (e) {}

		var currentProductId = parseInt($wrap.attr('data-current-product-id') || '0', 10);
		var viewed = getViewedProducts();

		// Check if inside the Elementor builder editor panel
		var isEditor = $('body').hasClass('elementor-editor-active') || (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode());

		// Exclude current product if setting enabled
		if (settings.exclude_current === 'yes' && currentProductId > 0) {
			viewed = viewed.filter(function (id) {
				return id !== currentProductId;
			});
		}

		// Don't send AJAX request if viewed list is empty on live pages
		if ((!viewed || !viewed.length) && !isEditor) {
			if (settings.show_empty_state === 'yes') {
				$wrap.html('<div class="ecma-recent-products-empty">' + (settings.empty_state_text || 'You have not viewed any products yet.') + '</div>');
			} else {
				$wrap.html('');
			}
			return;
		}

		var ajaxUrl = (typeof ecmaRecentParams !== 'undefined' && ecmaRecentParams.ajax_url) ? ecmaRecentParams.ajax_url : (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php');

		$.ajax({
			url: ajaxUrl,
			type: 'POST',
			data: {
				action: 'ecma_get_recently_viewed_products',
				product_ids: viewed,
				settings: JSON.stringify(settings),
				current_product_id: currentProductId,
				is_editor: isEditor ? '1' : '0'
			},
			success: function (response) {
				if (response) {
					$wrap.html(response);

					// Refresh WooCommerce fragments/fragments refresh event
					$(document.body).trigger('wc_fragment_refresh');

					// Setup slider if carousel layout
					if (settings.layout_type === 'carousel') {
						initCarousel($wrap, settings);
					}
				} else {
					$wrap.html('');
				}
			},
			error: function () {
				$wrap.html('');
			}
		});
	}

	// Instantiates the Swiper carousel using local configurations
	function initCarousel($wrap, settings) {
		var $container = $wrap.find('.ecma-layout-carousel.swiper-container');
		if (!$container.length) {
			return;
		}

		var columns = parseInt(settings.columns || '4', 10);
		var columnsTablet = parseInt(settings.columns_tablet || '3', 10);
		var columnsMobile = parseInt(settings.columns_mobile || '2', 10);

		var autoplay = settings.slider_autoplay === 'yes';
		var autoplaySpeed = parseInt(settings.slider_autoplay_speed || '3000', 10);
		var pauseOnHover = settings.slider_pause_hover === 'yes';
		var loop = settings.slider_loop === 'yes';
		var speed = parseInt(settings.slider_speed || '500', 10);

		var swiperOptions = {
			slidesPerView: columnsMobile,
			spaceBetween: 15,
			speed: speed,
			loop: loop,
			pagination: settings.slider_pagination === 'yes' ? {
				el: $container.find('.swiper-pagination')[0],
				clickable: true
			} : false,
			navigation: settings.slider_navigation === 'yes' ? {
				nextEl: $container.find('.swiper-button-next')[0],
				prevEl: $container.find('.swiper-button-prev')[0]
			} : false,
			breakpoints: {
				768: {
					slidesPerView: columnsTablet,
					spaceBetween: 20
				},
				1024: {
					slidesPerView: columns,
					spaceBetween: 20
				}
			}
		};

		if (autoplay) {
			swiperOptions.autoplay = {
				delay: autoplaySpeed,
				disableOnInteraction: false,
				pauseOnMouseEnter: pauseOnHover
			};
		}

		// Use global Swiper bundle or Elementor frontend Swiper handler fallback
		if (typeof Swiper !== 'undefined') {
			new Swiper($container[0], swiperOptions);
		} else if (typeof elementorFrontend !== 'undefined' && elementorFrontend.utils && elementorFrontend.utils.swiper) {
			var SwiperClass = elementorFrontend.utils.swiper;
			new SwiperClass($container[0], swiperOptions);
		}
	}

	// Execute on document ready
	$(document).ready(function () {
		// 1. Run product tracking
		trackProductVisit();

		// 2. Initialize widgets for live page views
		if (!$('body').hasClass('elementor-editor-active')) {
			$('.ecma-recent-products-wrap').each(function () {
				initWidget($(this).parent());
			});
		}
	});

	// Register Elementor live builder preview hooks
	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend !== 'undefined') {
			elementorFrontend.hooks.addAction('frontend/element_ready/ecma-recently-viewed-products.default', function ($scope) {
				initWidget($scope);
			});
		}
	});

})(jQuery);
