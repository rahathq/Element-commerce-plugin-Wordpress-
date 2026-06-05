/**
 * Product Gallery Slider Widget Handler
 */
(function($) {
	'use strict';

	// Global Lightbox logic
	$(document).ready(function() {
		var $lightbox = $('.ecma-lightbox');
		if (!$lightbox.length) {
			$lightbox = $('<div class="ecma-lightbox">' +
				'<div class="ecma-lightbox-close">&times;</div>' +
				'<div class="ecma-lightbox-content">' +
					'<div class="ecma-lightbox-btn ecma-lightbox-prev">&#10094;</div>' +
					'<img class="ecma-lightbox-img" src="" alt="">' +
					'<div class="ecma-lightbox-btn ecma-lightbox-next">&#10095;</div>' +
				'</div>' +
			'</div>');
			$('body').append($lightbox);
		}

		var $lightboxImg = $lightbox.find('.ecma-lightbox-img');

		window.ecmaUpdateLightboxImage = function() {
			if (!window.ecmaActiveSwiper) return;
			var $activeSlide = $(window.ecmaActiveSwiper.el).find('.swiper-slide-active img');
			if ($activeSlide.length) {
				$lightboxImg.attr('src', $activeSlide.attr('src'));
			}
		};

		window.ecmaOpenLightbox = function(swiperInstance) {
			window.ecmaActiveSwiper = swiperInstance;
			window.ecmaUpdateLightboxImage();
			$lightbox.addClass('active');
			$('body').css('overflow', 'hidden');
		};

		window.ecmaCloseLightbox = function() {
			$lightbox.removeClass('active');
			$('body').css('overflow', '');
			window.ecmaActiveSwiper = null;
		};

		$lightbox.on('click', function(e) {
			if ($(e.target).hasClass('ecma-lightbox') || $(e.target).hasClass('ecma-lightbox-close')) {
				window.ecmaCloseLightbox();
			}
		});

		$lightbox.find('.ecma-lightbox-prev').on('click', function(e) {
			e.stopPropagation();
			if (window.ecmaActiveSwiper) {
				window.ecmaActiveSwiper.slidePrev();
			}
		});

		$lightbox.find('.ecma-lightbox-next').on('click', function(e) {
			e.stopPropagation();
			if (window.ecmaActiveSwiper) {
				window.ecmaActiveSwiper.slideNext();
			}
		});

		$(document).on('keydown', function(e) {
			if (!$lightbox.hasClass('active')) return;
			if (e.key === 'Escape') {
				window.ecmaCloseLightbox();
			} else if (e.key === 'ArrowLeft') {
				if (window.ecmaActiveSwiper) window.ecmaActiveSwiper.slidePrev();
			} else if (e.key === 'ArrowRight') {
				if (window.ecmaActiveSwiper) window.ecmaActiveSwiper.slideNext();
			}
		});
	});

	var ecmaProductGallerySliderHandler = function($scope, $) {
		var $gallery = $scope.find('.ecma-product-gallery');
		if (!$gallery.length) {
			return;
		}

		var layout = $gallery.attr('data-layout') || 'below';
		var slidesPerView = parseInt($gallery.attr('data-slides-per-view')) || 4;
		var slidesPerViewTablet = parseInt($gallery.attr('data-slides-per-view-tablet')) || slidesPerView;
		var slidesPerViewMobile = parseInt($gallery.attr('data-slides-per-view-mobile')) || slidesPerViewTablet;
		var spaceBetween = parseInt($gallery.attr('data-space-between')) || 8;
		var isVertical = (layout === 'left' || layout === 'right');

		var loopAttr = $gallery.attr('data-loop') === 'yes';
		var autoplayAttr = $gallery.attr('data-autoplay') === 'yes';
		var autoplaySpeed = parseInt($gallery.attr('data-autoplay-speed')) || 3000;
		var effectAttr = $gallery.attr('data-effect') || 'slide';
		var hoverZoomAttr = $gallery.attr('data-hover-zoom') !== 'no';

		var $thumbSlider = $scope.find('.ecma-thumb-slider');
		var $mainSlider = $scope.find('.ecma-main-slider');

		// Function to update vertical thumbnails wrapper size dynamically
		var updateThumbWrapperHeight = function() {
			var $wrapper = $thumbSlider.closest('.ecma-thumb-wrapper');
			if (isVertical) {
				var mainHeight = $scope.find('.ecma-main-slider-wrapper').outerHeight();
				if (mainHeight > 0) {
					$wrapper.css('height', mainHeight + 'px');

					// Determine slides per view count based on current window width breakpoints
					var currentSlidesPerView = slidesPerView;
					var winWidth = $(window).width();
					if (winWidth < 768) {
						currentSlidesPerView = slidesPerViewMobile;
					} else if (winWidth < 1025) {
						currentSlidesPerView = slidesPerViewTablet;
					}

					// Dynamic Thumbnail Sizing logic
					var totalGaps = (currentSlidesPerView - 1) * spaceBetween;
					var slideHeight = (mainHeight - totalGaps) / currentSlidesPerView;

					// Determine ratio based on aspect ratio attribute
					var aspect = $gallery.attr('data-aspect-ratio') || '1-1';
					var ratio = 1.0; // default to 1:1
					if (aspect === '4-3') {
						ratio = 4 / 3;
					} else if (aspect === '3-4') {
						ratio = 3 / 4;
					} else if (aspect === '16-9') {
						ratio = 16 / 9;
					} else if (aspect === 'original') {
						ratio = 1.0; // fallback to 1:1 for original in vertical layout
					}

					var colWidth = slideHeight * ratio;
					$wrapper.css({
						'width': colWidth + 'px',
						'flex-basis': colWidth + 'px'
					});
				}
			} else {
				$wrapper.css({
					'height': '',
					'width': '',
					'flex-basis': ''
				});
			}
		};

		// Set initial height
		updateThumbWrapperHeight();

		// Initialize Thumbnails Slider
		var thumbsSwiper = new Swiper($thumbSlider[0], {
			direction: isVertical ? 'vertical' : 'horizontal',
			slidesPerView: slidesPerViewMobile,
			spaceBetween: spaceBetween,
			watchSlidesProgress: true,
			loop: loopAttr,
			mousewheel: isVertical ? { forceToAxis: true } : false,
			observer: true,
			observeParents: true,
			breakpoints: {
				768: {
					slidesPerView: slidesPerViewTablet,
				},
				1025: {
					slidesPerView: slidesPerView,
				}
			}
		});

		// Initialize Main Slider
		var mainSwiper = new Swiper($mainSlider[0], {
			spaceBetween: 10,
			loop: loopAttr,
			effect: effectAttr === 'fade' ? 'fade' : 'slide',
			fadeEffect: effectAttr === 'fade' ? { crossFade: true } : undefined,
			autoplay: autoplayAttr ? {
				delay: autoplaySpeed,
				disableOnInteraction: false
			} : false,
			autoHeight: true,
			thumbs: {
				swiper: thumbsSwiper,
			},
			observer: true,
			observeParents: true,
			on: {
				afterInit: function() {
					updateThumbWrapperHeight();
				},
				slideChange: function() {
					setTimeout(updateThumbWrapperHeight, 50);
					if (window.ecmaActiveSwiper === mainSwiper && typeof window.ecmaUpdateLightboxImage === 'function') {
						window.ecmaUpdateLightboxImage();
					}
				}
			}
		});

		// Navigation Arrows
		var $prevBtn = $scope.find('.ecma-custom-prev');
		var $nextBtn = $scope.find('.ecma-custom-next');

		$prevBtn.on('click', function() {
			mainSwiper.slidePrev();
		});

		$nextBtn.on('click', function() {
			mainSwiper.slideNext();
		});

		// Lightbox Click Event
		$mainSlider.on('click', '.swiper-slide img', function(e) {
			if (typeof window.ecmaOpenLightbox === 'function') {
				window.ecmaOpenLightbox(mainSwiper);
			}
		});

		// Hover Zoom Logic
		if (hoverZoomAttr) {
			$mainSlider.on('mouseenter', '.swiper-slide img', function() {
				$(this).css('transform', 'scale(1.5)');
			});

			$mainSlider.on('mousemove', '.swiper-slide img', function(e) {
				var rect = this.getBoundingClientRect();
				var x = e.clientX - rect.left;
				var y = e.clientY - rect.top;
				var xPercent = (x / rect.width) * 100;
				var yPercent = (y / rect.height) * 100;
				$(this).css('transform-origin', xPercent + '% ' + yPercent + '%');
			});

			$mainSlider.on('mouseleave', '.swiper-slide img', function() {
				$(this).css({
					'transform': 'scale(1)',
					'transform-origin': 'center center'
				});
			});
		}

		// Force update on initialization
		setTimeout(function() {
			updateThumbWrapperHeight();
			mainSwiper.update();
			thumbsSwiper.update();
		}, 150);

		// ResizeObserver to handle dynamic size calculations (fixes collapsed vertical/horizontal sliders)
		if (window.ResizeObserver && $gallery.length) {
			var resizeObserver = new ResizeObserver(function() {
				updateThumbWrapperHeight();
				if (mainSwiper && typeof mainSwiper.update === 'function') {
					mainSwiper.update();
				}
				if (thumbsSwiper && typeof thumbsSwiper.update === 'function') {
					thumbsSwiper.update();
				}
			});
			resizeObserver.observe($gallery[0]);

			var mainWrapper = $scope.find('.ecma-main-slider-wrapper')[0];
			if (mainWrapper) {
				resizeObserver.observe(mainWrapper);
			}
		}

		// Dynamic Stock Badge listener for variation products
		var $stockBadge = $scope.find('#ecma-stock-indicator');
		var $variationsForm = $('form.variations_form');

		if ($stockBadge.length && $variationsForm.length) {
			var inStockText = $stockBadge.data('in-stock-text') || 'IN STOCK · SHIPS TODAY';
			var outOfStockText = $stockBadge.data('out-of-stock-text') || 'OUT OF STOCK';
			var defaultStockStatus = $stockBadge.data('default-stock');

			$variationsForm.on('show_variation', function(event, variation) {
				if (variation && variation.is_in_stock) {
					$stockBadge.text(inStockText);
					$stockBadge.removeClass('ecma-out-of-stock');
				} else {
					$stockBadge.text(outOfStockText);
					$stockBadge.addClass('ecma-out-of-stock');
				}
			});

			$variationsForm.on('hide_variation reset_data', function() {
				if (defaultStockStatus === 'yes') {
					$stockBadge.text(inStockText);
					$stockBadge.removeClass('ecma-out-of-stock');
				} else {
					$stockBadge.text(outOfStockText);
					$stockBadge.addClass('ecma-out-of-stock');
				}
			});
		}
	};

	// Bind to Elementor frontend hook
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ecma-product-gallery-slider.default',
			ecmaProductGallerySliderHandler
		);
	});

})(jQuery);
