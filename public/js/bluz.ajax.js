/**
 * Declarative AJAX development
 *
 * <code>
 *  <a href="/get" class="ajax">Click Me!</a>
 *  <a href="/dialog" class="dialog">Click Me!</a>
 *  <a href="/delete" class="confirm" data-confirm="Are you sure?">Click Me!</a>
 *  <a href="/delete" class="ajax confirm" data-id="3" data-ajax-method="DELETE">Click Me!</a>
 *  <form action="/save/" class="ajax">
 *    ...
 *  </form>
 *  <source>
 *    // disable event handlers
 *    $("li a").off(".bluz");
 *    // or
 *    $("li a").off(".ajax");
 *  </source>
 * </code>
 * @link   https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes
 * @author Anton Shevchuk
 */
/* global define,require,window,document*/
define(['jquery', 'bluz', 'bluz.modal', 'bluz.notify'], function ($, bluz, modal, notify) {
  'use strict';

  /**
   * Repack data to simple hash
   * @param {{}} data of DOM Element
   * @return {{}} hash
   */
  function repackData(data) {
    let plain = {};

    $.each(data, (key, value) => {
      if (!(typeof value === 'function' ||
          typeof value === 'object' ||
          key === 'ajaxMethod' ||
          key === 'ajaxSource' ||
          key === 'ajaxTarget' ||
          key === 'ajaxType')) {
        plain[key] = value;
      }
    });
    return plain;
  }

  /**
   * Get all `data-*` from element
   * @param {jQuery} $element for extract data
   * @return {{}} hash
   */
  function processData($element) {
    let data = repackData($element.data());

    if ($element.is('select')) {
      data[$element.attr('name')] = $element.val();
    }

    return data;
  }

  /**
   * Try to extract `Bluz-Notify` header and setup notification
   * @param {XMLHttpRequest} jqXHR jQuery wrapper for XMLHttpRequest
   * @return {void}
   */
  function extractNotifyHeader(jqXHR) {
    if (jqXHR.getResponseHeader('Bluz-Notify')) {
      let notifications = $.parseJSON(jqXHR.getResponseHeader('Bluz-Notify'));
      notify.set(notifications);
    }
  }

  /**
   * Try to extract `Bluz-Redirect` header and redirect
   * @param {XMLHttpRequest} jqXHR jQuery wrapper for XMLHttpRequest
   * @return {void}
   */
  function extractRedirectHeader(jqXHR) {
    if (jqXHR.getResponseHeader('Bluz-Redirect')) {
      notify.done(function () {
        // redirect to another page
        window.location = jqXHR.getResponseHeader('Bluz-Redirect');
      });
    }
  }

  // on DOM ready state
  $(function () {
    /**
     * Ajax global events
     * @link http://api.jquery.com/ajaxStart/
     * @link http://api.jquery.com/ajaxSend/
     * @link http://api.jquery.com/ajaxSuccess/
     * @link http://api.jquery.com/ajaxError/
     * @link http://api.jquery.com/ajaxComplete/
     * @link http://api.jquery.com/ajaxStop/
     */
    $(document)
      .ajaxStart(bluz.showLoading)
      .ajaxSend((event, jqXHR, options) => {
        let $element = $(options.context);
        if ($element.hasClass('disabled')) {
          return false;
        }
        $element.addClass('disabled');
        return true;
      })
      .ajaxSuccess((event, jqXHR, options) => {
        try {
          let $element = $(options.context);
          $element.trigger('success.ajax.bluz', arguments);

          // try to get messages from headers
          extractNotifyHeader(jqXHR);

          // redirect and reload page
          extractRedirectHeader(jqXHR);
        } catch (err) {
          bluz.error(err.name, err.message);
        }
      })
      .ajaxError((event, jqXHR, options, thrownError) => {
        try {
          let $element = $(options.context);
          $element.trigger('error.ajax.bluz', arguments);

          // try to get messages from headers
          extractNotifyHeader(jqXHR);

          // try to get error message from JSON response
          if (options.dataType === 'json' ||
            jqXHR.getResponseHeader('Content-Type') === 'application/json') {
            let response = $.parseJSON(jqXHR.responseText);
            if (response.hasOwnProperty('error') && response.error.hasOwnProperty('message')) {
              notify.addError(response.error.message);
            }
          } else {
            modal.create($(document), jqXHR.responseText, 'width: 800px').modal('show');
          }
          bluz.log(thrownError, jqXHR.responseText);
        } catch (err) {
          bluz.error(err.name, err.message);
        }
      })
      .ajaxComplete((event, jqXHR, options) => {
        let $element = $(options.context);
        $element.removeClass('disabled');
      })
      .ajaxStop(bluz.hideLoading);

    // live event handlers
    $('body')
      /**
       * Confirmation dialog
       * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#confirm-dialog
       */
      .on('click.bluz.confirm', '.confirm', confirmDialog)
      /**
       * Call link by XMLHTTPRequest
       * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-links
       */
      .on('click.bluz.ajax', 'a.ajax', ajaxLink)
      /**
       * Send form by XMLHTTPRequest
       * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-form
       */
      .on('submit.bluz.ajax', 'form.ajax', ajaxForm)
      /**
       * Load HTML content by XMLHTTPRequest
       * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-load
       */
      .on('change.bluz.ajax', '.load', ajaxLoad)
      /**
       * Load HTML content by XMLHTTPRequest into modal dialog
       * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#modal-dialog
       */
      .on('click.bluz.ajax', '.dialog', ajaxDialog)
      /**
       * Image popup preview
       * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#image-preview
       */
      .on('click.bluz.preview', '.bluz-preview', imagePreview);


    function ajaxDialog(event) {
      event.preventDefault();

      let $this = $(this);

      $.ajax({
        url: $this.attr('href'),
        type: $this.data('ajax-method') || 'post',
        data: processData($this),
        dataType: 'html',
        success: function (content) {
          let $div = modal.create($this, content, $this.data('modal-style'));
          $div.on('success.form.bluz', function () {
            $this.trigger('complete.ajax.bluz', arguments);
            $div.modal('hide');
          });
          $div.modal('show');
        }
      });
    }

    function ajaxLoad(event) {
      event.preventDefault();

      let $this = $(this);

      let source = $this.data('ajax-source') || $this.attr('href');
      if (!source) {
        throw new Error('Undefined `data-ajax-source` attribute (and href is missing)');
      }

      let target = $this.data('ajax-target');
      if (!target) {
        throw new Error('Undefined `data-ajax-target` attribute');
      }

      let $target = $(target);

      if ($target.length === 0) {
        throw new Error('Element defined by `data-ajax-target` not found');
      }

      $.ajax({
        url: source,
        type: $this.data('ajax-method') || 'post',
        data: processData($this),
        dataType: 'html',
        success: function (data) {
          $target.html(data);
        }
      });
      return false;
    }

    function ajaxForm(event) {
      event.preventDefault();

      let $this = $(this);

      $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method') || 'post',
        data: $this.serializeArray(),
        dataType: $this.data('ajax-type') || 'json',
        context: this,
        success: function (data) {
          // data can be 'undefined' if server return
          // 204 header without content
          if (data !== undefined && data.errors !== undefined) {
            $this.trigger('error.form.bluz', arguments);
            require(['bluz.form'], function (form) {
              form.notices($this, data);
            });
          } else {
            $this.trigger('success.form.bluz', arguments);
          }
        }
      });
    }

    function ajaxLink(event) {
      event.preventDefault();

      let $this = $(this);

      $.ajax({
        url: $this.attr('href'),
        type: $this.data('ajax-method') || 'post',
        data: processData($this),
        dataType: $this.data('ajax-type') || 'json',
        context: this
      });
    }

    function confirmDialog(event) {
      event.preventDefault();

      let $this = $(this);

      let message = $this.data('confirm') || 'Are you sure?';
      if (!window.confirm(message)) {
        event.stopImmediatePropagation();
      }
    }

    function imagePreview(event) {
      event.preventDefault();

      let $this = $(this);

      let url = $this.is('a') ? $this.attr('href') : $this.data('preview');

      if (!url) {
        event.preventDefault();
        return;
      }

      let $img = $('<img>', {'src': url, 'class': 'img-polaroid'});
      $img.css({
        width: '100%',
        margin: '0 auto',
        display: 'block'
      });

      let $span = $('<span>', {'class': 'thumbnail'});
      $span.append($img);

      modal.create($this, $span, '').modal('show');
    }
  });
});
