/**
 * Working with forms
 *
 * @author
 * @created  26.11.12 12:51
 */
define(['jquery'], function ($) {

    // static validator
    var validator = {
        /**
         * Show notice message for fields
         * @param form
         * @param field
         * @param messages
         */
        notice:function(form, field, messages) {
            var $form = $(form);
            var $field = $(field);
            var $group = $field.parents('.control-group');

            messages = messages.join('<br/>');

            $group.addClass('error');

            // field can be hidden, e.g. by WYSIWYG editor
            if ($field.is(':hidden')) {
                $field = $group.find('label');
            }

            // remove previously generated tooltips
            $field.tooltip('destroy');
            $field.tooltip({
                html:true,
                title:messages,
                trigger:'manual',
                // change position for long messages, and for hidden fields
                placement: (messages.length > 82) || $field.is('label') ?'right':'top'
            });
            $field.tooltip('show');
            $field.click(function(){
                $(this).tooltip('hide');
            });
        },
        /**
         * Process errors stack from server side
         * @param data
         */
        notices:function(data) {
            var $form = $('#'+data.formId);
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