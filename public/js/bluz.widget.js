/**
 * Widget behaviour
 *
 * @author   Anton Shevchuk
 */
define(['jquery', 'bluz', 'bluz.storage'], function($, bluz, storage) {
	// on DOM ready state
	$(function() {
		// one handler for all widgets and all controls
		$(document).on('click', '.widget .widget-control', function() {
			var $this = $(this);
			// widget container
			var $widget = $this.parents('.widget');
			// widget content
			var $content = $widget.find('.widget-content');
			// storage key
			var key = $widget.data('widget-key');
			// switch by control action
			var control = $this.data('widget-control');
			switch (control) {
				case 'collapse':
					$content.slideToggle(function(){
						if (key) {
							var collapsedFlag = $content.is(':hidden') + 0; // save as integer in storage
							storage.setItem(key+'-collapse', collapsedFlag);
						}
					});
					// $widget.toggleClass('collapsed');
					// update icon
					$this.find('i').toggleClass('icon-chevron-up icon-chevron-down');
					break;
			}
		});

		$('.widget').each(function(i, el){
			var $widget = $(el);
			var $content = $widget.find('.widget-content');
			var key = $widget.data('widget-key');

			if (key) {
				// try to check collapse
				var collapsedFlag = storage.getItem(key+'-collapse');
				if (collapsedFlag == 1) {
					$content.hide();
//					$widget.addClass('collapsed');
					$widget.find('.widget-control i').toggleClass('icon-chevron-up icon-chevron-down');
				}
			}
		});
    });
});