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
    'redactor.imagemanager': './../redactor/plugins/imagemanager',
    dropzone: './../vendor/dropzone-amd-module/dropzone-amd-module.min',
  },
  shim: {
    bootstrap: {
      deps: ['jquery']
    },
    redactor: {
      deps: ['jquery'],
      exports: '$.fn.redactor'
    },
    'redactor.imagemanager': {
      deps: ['redactor', 'jquery'],
      exports: 'RedactorPlugins'
    }
  },
  enforceDefine: true
});

require(['bluz', 'bluz.ajax']);
