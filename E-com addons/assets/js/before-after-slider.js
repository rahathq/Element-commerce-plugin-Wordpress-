/**
 * Before After Image Slider Widget Handler
 */
(function($) {
	'use strict';

	var ecmaBeforeAfterSliderHandler = function($scope, $) {
		var $container = $scope.find('.ecma-before-after-container');
		if (!$container.length) {
			return;
		}

		var orientation = $container.attr('data-orientation') || 'horizontal';
		var defaultOffset = parseInt($container.attr('data-offset')) || 50;
		var interactionMode = $container.attr('data-interaction') || 'drag';

		var $beforeWrapper = $container.find('.ecma-before-wrapper');
		var $handle = $container.find('.ecma-slider-handle');

		var isDragging = false;
		var uniqueId = Math.random().toString(36).substring(2, 9);
		var eventNamespace = '.ecma_ba_' + uniqueId;

		// Function to update clip-path and handle position
		var updatePosition = function(percent) {
			percent = Math.max(0, Math.min(100, percent));

			if (orientation === 'vertical') {
				$beforeWrapper.css('clip-path', 'inset(0 0 ' + (100 - percent) + '% 0)');
				$handle.css('top', percent + '%');
			} else {
				$beforeWrapper.css('clip-path', 'inset(0 ' + (100 - percent) + '% 0 0)');
				$handle.css('left', percent + '%');
			}
		};

		// Set default initial position
		updatePosition(defaultOffset);

		// Calculate slider offset percentage from mouse/touch position
		var getPercent = function(e) {
			var containerRect = $container[0].getBoundingClientRect();
			var clientX = e.clientX;
			var clientY = e.clientY;

			if (e.touches && e.touches.length) {
				clientX = e.touches[0].clientX;
				clientY = e.touches[0].clientY;
			} else if (e.originalEvent && e.originalEvent.touches && e.originalEvent.touches.length) {
				clientX = e.originalEvent.touches[0].clientX;
				clientY = e.originalEvent.touches[0].clientY;
			}

			if (orientation === 'vertical') {
				var relativeY = clientY - containerRect.top;
				return (relativeY / containerRect.height) * 100;
			} else {
				var relativeX = clientX - containerRect.left;
				return (relativeX / containerRect.width) * 100;
			}
		};

		if (interactionMode === 'hover') {
			// Mouse Hover tracking trigger mode
			$container.on('mousemove touchmove', function(e) {
				var percent = getPercent(e);
				updatePosition(percent);
			});

			$container.on('mouseleave', function() {
				// Smoothly ease slider handle back to default position
				$beforeWrapper.css('transition', 'clip-path 0.3s ease');
				$handle.css('transition', 'left 0.3s ease, top 0.3s ease');
				updatePosition(defaultOffset);

				setTimeout(function() {
					$beforeWrapper.css('transition', '');
					$handle.css('transition', '');
				}, 300);
			});

			$container.on('mouseenter touchstart', function() {
				$beforeWrapper.css('transition', '');
				$handle.css('transition', '');
			});
		} else {
			// Click & Drag trigger mode
			var startDragging = function(e) {
				isDragging = true;
				$container.addClass('dragging');
				e.preventDefault();
			};

			var stopDragging = function() {
				if (isDragging) {
					isDragging = false;
					$container.removeClass('dragging');
				}
			};

			var onDrag = function(e) {
				if (!isDragging) return;

				// Garbage collect window event listeners if Elementor replaces this DOM element
				if (!$.contains(document.documentElement, $container[0])) {
					$(window).off(eventNamespace);
					return;
				}

				var percent = getPercent(e);
				updatePosition(percent);
			};

			// Bind handlers to handle click and swipe controls
			$handle.on('mousedown touchstart', startDragging);

			$container.on('mousedown touchstart', function(e) {
				if ($(e.target).closest('.ecma-slider-handle').length === 0) {
					var percent = getPercent(e);
					updatePosition(percent);
					startDragging(e);
				}
			});

			$(window).on('mousemove' + eventNamespace + ' touchmove' + eventNamespace, onDrag);
			$(window).on('mouseup' + eventNamespace + ' touchend' + eventNamespace + ' touchcancel' + eventNamespace, stopDragging);
		}

		// Re-trigger layout checks when images fully render in viewport
		$container.find('img').on('load', function() {
			updatePosition(defaultOffset);
		});
	};

	// Bind to Elementor frontend initialization hook
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ecma-before-after-slider.default',
			ecmaBeforeAfterSliderHandler
		);
	});

})(jQuery);
