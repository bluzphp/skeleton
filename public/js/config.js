/**
 * Configuration
 * @author Anton Shevchuk
 */
/* global define,require*/
require.config({
  // why not simple 'js'? Because IE eating our minds!
  baseUrl: '/js',
  // if you need disable JS cache
  urlArgs: 'bust=' + Date.now(),
  paths: {
    bootstrap: './../vendor/bootstrap/js/bootstrap.bundle.min',
    jquery: './../vendor/jquery/jquery.min',
  },
  shim: {
    bootstrap: {
      deps: ['jquery']
    }
  },
  enforceDefine: true
});

require(['bluz', 'bluz.ajax']);
