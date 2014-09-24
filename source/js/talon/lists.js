/**
 * Talon CMS - List JS
 *
 * Created by Adam on 16/09/14.
 */
(function($) {
	$(function() {
		var lists = $('.dataTable').dataTable(),
			$deleteBtn = $('#deleteSelected');

		lists.find('tbody').on('click', 'tr', function(e) {
			var $this = $(this);

			if ( $this.hasClass('selected') ) {
				$this.removeClass('selected');
			} else {
				lists.find('tr.selected').removeClass('selected');
				$this.addClass('selected');
			}
		});

		$deleteBtn.click( function () {
			lists.filter('').row('.selected').remove().draw( false );
		});

		window.Talon.$lists =
	});
})($);