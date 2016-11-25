/**
 * Configuration example
 * @author Anton Shevchuk
 */
/*global define,require*/
require.config({
    // why not simple "js"? Because IE eating our minds!
    baseUrl: '/js',
    // if you need disable JS cache
    urlArgs: "bust=" + (new Date()).getTime(),
    paths: {
        "bootstrap": './vendor/bootstrap',
        "jquery": './vendor/jquery',
        "dropzone": './vendor/dropzone-amd-module',
        "redactor": './../redactor/redactor',
        "redactor.imagemanager": './../redactor/plugins/imagemanager',
        "text": "./vendor/text",
        "jsx": "./vendor/jsx",
        "JSXTransformer": "./vendor/JSXTransformer"
    },
    jsx: {
        fileExtension: '.jsx'
    },
    shim: {
        "bootstrap": {
            deps: ['jquery'],
            exports: '$.fn.popover'
        },
        "redactor": {
            deps: ['jquery'],
            exports: '$.fn.redactor'
        },
        "redactor.imagemanager": {
            deps: ['redactor', 'jquery'],
            exports: 'RedactorPlugins'
        },
        "jquery-ui": {
            deps: ['jquery'],
            exports: '$.ui'
        }
    },
    enforceDefine: true
});

require(['bluz', 'bluz.ajax', 'bootstrap']);
