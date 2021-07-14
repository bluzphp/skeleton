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
    jqueryui: './../vendor/jquery-ui/jquery-ui.min',
    redactor: './../redactor/redactor',
    imagemanager: './../redactor/_plugins/imagemanager/imagemanager',
    dropzone: './../vendor/dropzone-amd-module/dropzone-amd-module'
  },
  shim: {
    bootstrap: {
      deps: ['jquery']
    },
    redactor: {
      exports: '$R'
    },
    imagemanager: {
      deps: ['redactor'],
      exports: '$R'
    }
  },
  enforceDefine: true
});

require(['bluz', 'bluz.ajax']);
