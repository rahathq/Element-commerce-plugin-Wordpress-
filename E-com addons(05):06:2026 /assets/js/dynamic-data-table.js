/**
 * Dynamic Data Table Frontend Script
 */
(function($) {
	'use strict';

	/**
	 * Initialize Live Search and Sorting logic on a table wrapper.
	 */
	function initTableWidget($wrapper) {
		var $searchInput = $wrapper.find('.ecma-table-search-input');
		var $table = $wrapper.find('.ecma-data-table');
		var $tbody = $table.find('tbody');
		var $headers = $table.find('thead th[data-sortable="true"]');

		// 1. Live Search Handler
		$searchInput.on('keyup', function() {
			var query = $(this).val().toLowerCase().trim();
			
			$tbody.find('tr').each(function() {
				var $row = $(this);
				var rowText = $row.text().toLowerCase();

				// If search field is empty or row text contains query
				if (query === '' || rowText.indexOf(query) > -1) {
					$row.show();
				} else {
					$row.hide();
				}
			});
		});

		// 2. Click-to-Sort Columns Handler
		$headers.on('click', function() {
			var $th = $(this);
			var colIndex = $th.index();
			var $rows = $tbody.find('tr').get();
			var isAscending = !$th.hasClass('asc');

			// Clear all sort classes from sibling columns
			$th.siblings().removeClass('asc desc');

			// Toggle sort direction classes
			if (isAscending) {
				$th.addClass('asc').removeClass('desc');
			} else {
				$th.addClass('desc').removeClass('asc');
			}

			// Sort rows Array
			$rows.sort(function(rowA, rowB) {
				var cellA = $(rowA).children('td').eq(colIndex).text().trim();
				var cellB = $(rowB).children('td').eq(colIndex).text().trim();

				// Try to extract numerical values (ignore $, %, etc.)
				var cleanA = cellA.replace(/[^0-9.-]/g, '');
				var cleanB = cellB.replace(/[^0-9.-]/g, '');

				var numA = parseFloat(cleanA);
				var numB = parseFloat(cleanB);

				// Perform sorting based on data types
				if (!isNaN(numA) && !isNaN(numB) && cleanA !== '' && cleanB !== '') {
					return isAscending ? numA - numB : numB - numA;
				}

				// Fallback to alphabetical sorting
				return isAscending 
					? cellA.localeCompare(cellB, undefined, { numeric: true, sensitivity: 'base' })
					: cellB.localeCompare(cellA, undefined, { numeric: true, sensitivity: 'base' });
			});

			// Re-append sorted rows into the tbody
			$.each($rows, function(index, row) {
				$tbody.append(row);
			});
		});
	}

	// Elementor JS Hooks Registration
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/ecma-dynamic-data-table.default', function($scope) {
			initTableWidget($scope);
		});
	});

	// Standard Document Ready fallback for non-Elementor screens
	$(document).ready(function() {
		if (!window.elementorFrontend) {
			$('.ecma-table-wrapper').each(function() {
				initTableWidget($(this));
			});
		}
	});

})(jQuery);
