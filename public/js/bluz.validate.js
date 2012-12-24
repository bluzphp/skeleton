/**
 * Working with forms
 *
 * @author
 * @created  26.11.12 12:51
 */
define(['jquery', 'bluz'], function ($, bluz) {

    return {
        notice:function(el, data) {
            var $form = $(el);
            $form.find('.control-group').removeClass('error');
            $form.find('.help-inline.error').remove();
            if (data.errors) {
                $.each(data.errors, function(field, messages) {
                    var $ctrlGroup = $form.find('.control-group:has(#'+field+')').addClass('error');

                    messages = messages.join('<br/>');
                    var $field = $('#'+field).tooltip({
                        html:true,
                        title:messages,
                        trigger:'manual'
                    });
                    $field.tooltip('show');
                    $field.click(function(){
                        $(this).tooltip('hide');
                    });
                });
            }
        }
    };
});