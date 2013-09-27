/**
 * Bluz storage handler
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 10:02
 */
/*global define,require*/
define(['jquery'], function ($) {
	"use strict";
	// initial local storage
	var storage = null,
		storageCheck = false,
		data = {};

	if ("localStorage" in window) {
		try {
			window.localStorage.setItem('_test', 'test');
			storageCheck = true;
			window.localStorage.removeItem('_test');
		} catch (BogusQuotaExceededErrorOnIos5) {
			// Thanks be to iOS5 Private Browsing mode which throws
			// QUOTA_EXCEEDED_ERRROR DOM Exception 22.
		}
	}

	// storage fail
	// use simple object
	// TODO: add cookie save handler
	if (!storageCheck) {
		storage = {
			setItem: function (key, value) {
				data[key] = value;
			},
			getItem: function (key) {
				if (data[key] !== undefined) {
					return data[key];
				} else {
					return false;
				}
			},
			removeItem: function (key) {
				if (data[key] !== undefined) {
					delete data[key];
				}
			},
			clear: function () {
				data = {};
			}
		};
	} else {
		storage = window.localStorage;
	}

	// return default API
	return {
		setItem: function (key, value) {
			storage.setItem('bluz-' + key, value);
		},
		getItem: function (key) {
			return storage.getItem('bluz-' + key);
		},
		removeItem: function (key) {
			storage.removeItem('bluz-' + key);
		},
		clear: function () {
			storage.clear();
		}
	};
});