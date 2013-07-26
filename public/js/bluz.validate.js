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
            var $group = $field.parents('.control-group');

            if (messages instanceof Array) {
                messages = messages.join('<br/>');
            }

            $group.addClass('error');

            // field can be hidden, e.g. by WYSIWYG editor
            if ($field.is(':hidden')) {
                $field = $group.find('label');
            }

            /**
             * @url https://github.com/twitter/bootstrap/issues/6942
             */
            $field.on('show', function(event){
                event.stopPropagation();
            }).on('hidden', function(event){
                event.stopPropagation();
            });

            // remove previously generated tooltips
             /*$field.tooltip('destroy');*/
            $field.tooltip({
                html: true,
                title: messages,
                trigger: 'manual',
                // change position for long messages, and for hidden fields
                placement: ($field.width() < 320) || (messages.length > 82) || $field.is('label') ? 'right' : 'top'
            });

            $field.tooltip('show');
            $field.click(function () {
                $field.parents('.control-group').removeClass('error');
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
            $form.find('.control-group').removeClass('error');
            if (data.errors) {
                $.each(data.errors, function(field, notices) {
                    validator.notice($form, '[name^="data['+field+']"]:first', notices);
                });
            }
        }
    };
    return validator;
});