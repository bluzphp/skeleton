/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
/* global define,require,window,document,history*/
define(['jquery', 'bootstrap'], function ($) {
  'use strict';

  $(function () {
    // TODO: require other modules if needed
    if ($.fn.tooltip) {
      $('.bluz-tooltip').tooltip();
    }

    if ($.fn.affix) {
      $('.bluz-affix').affix();
    }

    // remove FB API's anchor #_=_
    if (window.location.hash === '#_=_') {
      window.location.hash = '';
      history.pushState('', document.title, window.location.pathname);
    }
  });

  return {
    showLoading: () => {
      $('#loading').show();
    },
    hideLoading: () => {
      $('#loading').hide();
    },
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
