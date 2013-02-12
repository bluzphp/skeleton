/**
 * @author Anton Shevchuk
 */
require.config({
    paths: {
        'bootstrap': './vendor/bootstrap'//,
//        'jquery.fileupload': './fileupload/jquery.fileupload',
//        'jquery.ui.widget': './vendor/jquery.ui.widget'
    }
});
require(["jquery", "bootstrap", "bluz", "bluz.messages", "bluz.ajax"], function($, bootstrap, messages, bluz) {
    $(function(){
        // TODO: require other modules if needed
        if ($.fn.tooltip) {
            $('.bluz-tooltip').tooltip();
        }

        if ($.fn.affix) {
            $('.bluz-affix').affix();
        }

        bluz.ready();
    });
});
