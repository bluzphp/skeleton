/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
define(['jquery'], function ($) {
	"use strict";
	$(function() {
		// TODO: require other modules if needed
		if ($.fn.tooltip) {
			$('.bluz-tooltip').tooltip();
		}

		if ($.fn.affix) {
			$('.bluz-affix').affix();
		}
	});

	return {
		log: function (error, text) {
			if (console !== undefined) {
				console.error(error, "Response Text:", text);
			}
		}
	};
});