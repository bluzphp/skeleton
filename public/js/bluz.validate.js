/**
 * Working with forms
 *
 * @author
 * @created  26.11.12 12:51
 */
define(["jquery", "bootstrap", "bluz"], function ($) {

    // static validator
    var validator;
    validator = {
        /**
         * Show notice message for fields
         * @param $form jQuery
         * @param field
         * @param messages
         */
        notice: function ($form, field, messages) {
            var $field = $(field);
            var $group = $field.parents('.form-group');

            if (messages instanceof Array) {
                messages = messages.join('<br/>');
            }

            $group.addClass('has-error');

            // field can be hidden, e.g. by WYSIWYG editor
            if ($field.is(':hidden')) {
                $field = $group.find('label');
            }

            // remove previously generated tooltips
            $field.tooltip({
                html: true,
                title: messages,
                trigger: 'manual',
                // change position for long messages, and for hidden fields
                placement: ($field.width() < 320) || (messages.length > 82) || $field.is('label') ? 'right' : 'top'
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
            $form.find('.form-group').removeClass('has-error');
            if (data.errors) {
                $.each(data.errors, function(field, notices) {
                    validator.notice($form, '[name^="data['+field+']"]:first', notices);
                });
            }
        }
    };
    return validator;
});