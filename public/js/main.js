/**
 * @author Anton Shevchuk
 */
require.config({
    baseUrl: 'js',
    // if you need disable JS cache
    urlArgs: "bust=" + (new Date()).getTime(),
    paths: {
        'bootstrap': './vendor/bootstrap',
        'jquery': './vendor/jquery'
    },
    shim: {
        "bootstrap": {
            deps: ["jquery"],
            exports: "$.fn.popover"
        }
    },
    enforceDefine: true
});
require(
    ["jquery", "bootstrap", "bluz", "bluz.notify", "bluz.ajax"],
    function($, bootstrap, bluz, notify) {
    $(function(){
        // TODO: require other modules if needed
        if ($.fn.tooltip) {
            $('.bluz-tooltip').tooltip();
        }

        if ($.fn.affix) {
            $('.bluz-affix').affix();
        }

        bluz.ready();

        // remove FB API's anchor #_=_
        if (window.location.hash == '#_=_') {
            window.location.hash = '';
            history.pushState('', document.title, window.location.pathname);
        }
    });
});
