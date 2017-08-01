/**
 * Working with forms
 * Require bootstrap.tooltips
 * http://getbootstrap.com/javascript/#tooltips
 * http://getbootstrap.com/css/?#forms-control-validation
 *
 * @author Anton Shevchuk
 * @created  26.11.12 12:51
 */
/* global define,require*/
define(['jquery', 'bootstrap'], function ($) {
  'use strict';

  // static validator
  let form;
  let settings;
  let defaults = {
    container: '.form-group', // default for Twitter Bootstrap layout
    errorClass: 'has-error',
    inputCollection: false,   // uses 'data[field-name]' or 'field-name'
    tooltipPosition: 'top'  // can be bottom
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
     * @param {String} field name
     * @param {Array} messages stack
     * @return {void}
     */
    notice: function ($form, field, messages) {
      let $field = $(field);
      let $group = $field.parents(settings.container);

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

      if ($field.is('input')) {
        form.icon($form, $field);
      }

      $field.click(function () {
        $group.removeClass('has-error');
        $field.tooltip('destroy');
      });
    },
    /**
     * Add icon to field
     * @param {jQuery} $form DOMElement
     * @param {String} field name
     * @return {void}
     */
    icon: function ($form, field) {
      let $field = $(field);
      let $group = $field.parents(settings.container);

      $group.addClass('has-feedback');

      let $icon = $('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
      let $sr = $('<span class="sr-only">(error)</span>');

      $field.after($sr);
      $field.after($icon);

      $field.click(function () {
        $group.removeClass('has-feedback');
        $icon.remove();
        $sr.remove();
      });
      /*
      <span class='fa fa-times form-control-feedback' aria-hidden='true'></span>
      <span class='sr-only'>(error)</span>

       <div class='form-group has-success has-feedback'>
        <label class='control-label col-sm-3' for='inputSuccess3'>Input with success</label>
        <div class='col-sm-9'>
          <input type='text' class='form-control' id='inputSuccess3' aria-describedby='inputSuccess3Status'>
          <span class='glyphicon glyphicon-ok form-control-feedback' aria-hidden='true'></span>
          <span id='inputSuccess3Status' class='sr-only'>(success)</span>
        </div>
       </div>
      */
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
        $.each(data.errors, function (field, notices) {
          if (settings.inputCollection) {
            form.notice($form, '[name^=data[' + field + ']]:first', notices);
          } else {
            form.notice($form, '[name^=' + field + ']:first', notices);
          }
        });
      }
    }
  };
  return form;
});
