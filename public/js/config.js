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
        "pager": "./vendor/pager",
        "text": "./vendor/text",
        "jsx": "./vendor/jsx",
        "JSXTransformer": "./vendor/JSXTransformer",
        // see more at https://cdnjs.com/
        "underscore": '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore',
        "backbone": '//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.3.3/backbone',
        "react": '//cdnjs.cloudflare.com/ajax/libs/react/15.0.1/react',
        "react-dom": '//cdnjs.cloudflare.com/ajax/libs/react/15.0.1/react-dom',
        "redux": '//cdnjs.cloudflare.com/ajax/libs/redux/3.5.2/redux',
        "react-redux": '//cdnjs.cloudflare.com/ajax/libs/react-redux/4.4.5/react-redux'
    },
    jsx: {
        fileExtension: '.jsx'
    },
    shim: {
        "bootstrap": {
            deps: ['jquery'],
            exports: '$.fn.popover'
        },
        "backbone": {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        "react": {
            exports: 'React'
        },
        "react-dom": {
            deps: ['react'],
            exports: 'ReactDOM'
        },
        "react-redux": {
            deps: ['react', 'redux'],
            exports: 'ReactRedux'
        },
        "pager": {
            deps: ['react'],
            exports: 'Pager'
        },
        "redactor": {
            deps: ['jquery'],
            exports: '$.fn.redactor'
        },
        "redactor.imagemanager": {
            deps: ['redactor', 'jquery'],
            exports: 'RedactorPlugins'
        },
        "underscore": {
            exports: '_'
        },
        "jquery-ui": {
            deps: ['jquery'],
            exports: '$.ui'
        }
    },
    enforceDefine: true
});

require(['bluz', 'bluz.ajax', 'bootstrap']);