/**
 * Widget behaviour
 *
 * @author Anton Shevchuk
 */
/*global define,require*/
define(['jquery', 'bluz', 'bluz.storage'], function ($, bluz, storage) {
	"use strict";
	// on DOM ready state
	$(function () {
		// one handler for all widgets and all controls
		$(document).on('click', '.widget .widget-control', function() {
			var $this, $widget, $content, key, control;
			$this = $(this);
			// widget container
			$widget = $this.parents('.widget');
			// widget content
			$content = $widget.find('.widget-content');
			// storage key
			key = $widget.data('widget-key');
			// switch by control action
			control = $this.data('widget-control');

			if (control === 'collapse') {
				$content.slideToggle(function () {
					if (key) {
                        if ($content.is(':hidden')) {
                            storage.setItem(key + '-collapse', 1);
                        } else {
                            storage.removeItem(key + '-collapse');
                        }
					}
				});
				$this.find('i').toggleClass('fa-chevron-up fa-chevron-down');
			}
		});

		$('.widget').each(function (i, el) {
			var $widget, $content, key;
			$widget = $(el);
			$content = $widget.find('.widget-content');
			key = $widget.data('widget-key');

			if (key) {
				// try to check collapse
				if (storage.getItem(key + '-collapse')) {
					$content.hide();
//					$widget.addClass('collapsed');
					$widget.find('.widget-control i').toggleClass('fa-chevron-up fa-chevron-down');
				}
			}
		});
    });
});