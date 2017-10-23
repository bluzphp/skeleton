/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
/* global define,require,window,document,history*/
define(['jquery', 'popper'], function ($, popper) {
  'use strict';

  // set popper as required by Bootstrap
  window.Popper = popper;

  // TODO: fix this after release new version of boostrap
  // @link https://github.com/twbs/bootstrap/issues/23381#issuecomment-330850892
  require(['bootstrap'], function() {
    // do nothing - just let Bootstrap initialise itself
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
  });

  return {
    showLoading: () => {
      $('[data-ajax-loading-show]').show();
    },
    hideLoading: () => {
      $('[data-ajax-loading-hide]').hide();
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
