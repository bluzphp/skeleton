/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
define(['jquery'], function ($) {
	"use strict";

	return {
        Callback: null,
        ready: function() {
            if (!arguments.length) {
                if (self.Callback) {
                    self.Callback.fire();
                }
                return;
            }

            if (!self.Callback) {
                self.Callback = new $.Callbacks();
            }

            self.Callback.add(arguments[0]);
        },
		log: function (error, text) {
			if (console !== undefined) {
				console.error(error, "Response Text:", text);
			}
		}
	};
});