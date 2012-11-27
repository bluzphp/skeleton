/**
 * Working with forms
 *
 * @author
 * @created  26.11.12 12:51
 */
define(['jquery', 'bluz'], function ($, bluz) {





    return {
        notice:function(el, data) {
            $(el).find('.control-group').removeClass('error');
            $(el).find('.help-inline.error').remove();

            if (data.errors) {
                $.each(data.errors, function(field, messages) {
                    $(el).find('.control-group:has(#'+field+')').addClass('error');
                    $.each(messages, function(i, msg){
                        $('#'+field).parent('.controls') // <div class="controls">..</div>
                            .append('<span class="help-inline error">'+msg+'</span>');
                    });
                });
            }
        }
    };
});