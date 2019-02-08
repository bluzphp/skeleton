/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
/* global define,require,window,document,history*/
define(['jquery', 'bootstrap'], function ($) {
  'use strict';

  // do nothing - just let Bootstrap initialise itself
  $(function () {
    // TODO: require other modules if needed
    if ($.fn.tooltip) {
      $('[data-toggle="tooltip"]').tooltip();
    }

    // remove FB API's anchor #_=_
    if (window.location.hash === '#_=_') {
      window.location.hash = '';
      history.pushState('', document.title, window.location.pathname);
    }
  });

  return {
    log: (message, text) => {
      if (window.console !== undefined) {
        window.console.log(message, text);
      }
    },
    error: (error, text) => {
      if (window.console !== undefined) {
        window.console.error(error, text);
      }
    }
  };
});
