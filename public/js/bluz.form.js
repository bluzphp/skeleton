/**
 * Working with forms
 * Require bootstrap.notify
 *
 * @author Anton Shevchuk
 * @created  26.11.12 12:51
 */
/*global define,require*/
define(["jquery", "bootstrap"], function ($) {
    "use strict";

    // static validator
    var form, defaults, settings;
    defaults =  {
        container: '.form-group', // default for Twitter Bootstrap layout
        errorClass: 'has-error',
        inputCollection: false,   // uses "data[field-name]" or "field-name"
        tooltipPosition: 'top'    // can be bottom
    };

    settings = $.extend({}, defaults);

    form = {
        /**
         * Apply new settings
         * @param options
         */
        init: function (options) {
            settings = $.extend({}, defaults, options);
        },
        /**
         * Show notice message for fields
         * @param $form jQuery
         * @param field
         * @param messages
         */
        notice: function ($form, field, messages) {
            var $field = $(field);
            var $group = $field.parents(settings.container);

            if (messages instanceof Array) {
                messages = messages.join('<br/>');
            }

            $group.addClass(settings.errorClass);

            // field can be hidden, e.g. by WYSIWYG editor
            if ($field.is(':hidden')) {
                $field = $group.find('label');
            }

            // remove previously generated tooltips
            $field.tooltip('destroy');

            // generate new
            $field.tooltip({
                html: true,
                title: messages,
                trigger: 'manual',
                // change position for long messages, and for hidden fields
                placement: ($field.width() < 220) || (messages.length > 82) || $field.is('label') ? 'right' : 'top'
            });

            $field.tooltip('show');
            $field.click(function () {
                $group.removeClass('has-error');
                $field.tooltip('destroy');
            });
        },
        /**
         * Process errors stack from server side
         * @param $form jQuery
         * @param data
         */
        notices: function ($form, data) {
            // clear previously generated classes
            $form.find(settings.container).removeClass(settings.errorClass);

            if (data !== undefined && data.errors !== undefined) {
                $.each(data.errors, function(field, notices) {
                    if (settings.inputCollection) {
                        form.notice($form, '[name^="data['+field+']"]:first', notices);
                    } else {
                        form.notice($form, '[name^="'+field+'"]:first', notices);
                    }
                });
            }
        }
    };
    return form;
});