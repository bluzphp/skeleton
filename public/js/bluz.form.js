/**
 * Working with bootstrap forms
 * https://getbootstrap.com/docs/4.0/components/forms/#validation
 *
 * @author Anton Shevchuk
 * @created  26.11.12 12:51
 */
/* global define,require*/
define(['bluz', 'jquery'], function (bluz, $) {
  'use strict';

  // static validator
  let form;
  let settings;
  let defaults = {
    container: 'form-group',           // default for Twitter Bootstrap layout
    feedback: 'invalid-feedback',
    errorClass: 'is-invalid',
    inputCollection: false,            // uses 'data[field-name]' or 'field-name'
    tooltipPosition: 'top'             // can be bottom
  };

  settings = $.extend({}, defaults);

  form = {
    /**
     * Apply new settings
     * @param {{}} options for instance
     * @return {void}
     */
    init: function (options) {
      settings = $.extend({}, defaults, options);
    },
    /**
     * Show notice message for fields
     * @param {jQuery} $form jQuery
     * @param {jQuery} $field name
     * @param {Array|String} messages stack
     * @return {void}
     */
    notice: function ($form, $field, messages) {
      let $group = $field.parents('.' + settings.container);
      let $feedback = $group.find('.' + settings.feedback);

      if (messages instanceof Array) {
        messages = messages.join('<br/>');
      }

      // add error class to field
      $field.addClass(settings.errorClass);
      $field.get(0).setCustomValidity(messages);

      // add error message with
      if ($feedback.length === 0) {
        $feedback = $('<div class="' + settings.feedback + '"></div>');
        // field can be hidden, e.g. by WYSIWYG editor
        $field.after($feedback);
      }
      $feedback.show();
      $feedback.html(messages);

      $field.click(function () {
        $field.get(0).setCustomValidity('');
        $feedback.hide();
      });
    },
    /**
     * Process errors stack from server side
     * @param {jQuery} $form DOMElement
     * @param {{}} data from response
     * @return {void}
     */
    notices: function ($form, data) {
      // clear previously generated classes
      $form.find(settings.container).removeClass(settings.errorClass);

      if (data !== undefined && data.errors !== undefined) {
        $.each(data.errors, (field, notices) => {
          let $field = $('[name^=' + field + ']:first');

          // if field with `name` is not exists
          // try to find `data[name]` field
          if ($field.length === 0) {
            $field = $('[name^=data[' + field + ']]:first');
          }

          // if found field
          if ($field.length !== 0) {
            form.notice($form, $field, notices);
          }
        });
      }
    }
  };
  return form;
});
