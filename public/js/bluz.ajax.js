/**
 * Declarative AJAX development
 *
 * <code>
 *    <a href="/get" class="ajax">Click Me!</a>
 *    <a href="/dialog" class="dialog">Click Me!</a>
 *    <a href="/delete" class="confirm" data-confirm="Are you sure?">Click Me!</a>
 *    <a href="/delete" class="ajax confirm" data-id="3" data-ajax-method="DELETE">Click Me!</a>
 *    <form action="/save/" class="ajax">
 *        ...
 *    </form>
 *    <source>
 *        // disable event handlers
 *        $('li a').off('.bluz');
 *        // or
 *        $('li a').off('.ajax');
 *    </source>
 * </code>
 * @link   https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes
 * @author Anton Shevchuk
 */
/*global define,require*/
define(['jquery', 'bluz', 'bluz.notify'], function ($, bluz, notify) {
	"use strict";
	// on DOM ready state
	$(function () {
        /**
         * Ajax global events
         * @link http://api.jquery.com/ajaxStart/
         * @link http://api.jquery.com/ajaxSuccess/
         * @link http://api.jquery.com/ajaxError/
         * @link http://api.jquery.com/ajaxComplete/
         */
        $(document)
            .ajaxStart(function () {
                $('#loading').show();
            })
            .ajaxSuccess(function (event, jqXHR) {
                // redirect and reload page
                let callback = null;
                if (jqXHR.getResponseHeader('Bluz-Redirect')) {
                    callback = function () {
                        // redirect to another page
                        window.location = jqXHR.getResponseHeader('Bluz-Redirect');
                    };
                }

                // show messages and run callback after
                if (jqXHR.getResponseHeader('Bluz-Notify')) {
                    let notifications = $.parseJSON(jqXHR.getResponseHeader('Bluz-Notify'));
                    notify.addCallback(callback);
                    notify.set(notifications);
                } else if (callback) {
                    callback();
                }
            })
            .ajaxError(function (event, jqXHR, options, thrownError) {
                bluz.log(thrownError, jqXHR.responseText);

                // show error messages
                if (jqXHR.getResponseHeader('Bluz-Notify')) {
                    let notifications = $.parseJSON(jqXHR.getResponseHeader('Bluz-Notify'));
                    notify.set(notifications);
                }

                // try to get error message from JSON response
                if (!(options.dataType === 'json' ||
                    jqXHR.getResponseHeader('Content-Type') === 'application/json')) {
                    createModal($(document), jqXHR.responseText, 'width:800px').modal('show');
                } else {
                    let response = $.parseJSON(jqXHR.responseText);
                    if (response.hasOwnProperty('error') && response.error.hasOwnProperty('message') ) {
                        notify.addError(response.error.message);
                    }
                }
            })
            .ajaxComplete(function () {
                $('#loading').hide();
            });

        /**
         * Get only plain data
         * @param el
         * @returns {{}}
         */
        let processData = function (el) {
            let data = el.data();
            let plain = {};

			$.each(data, function (key, value) {
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
		};


        /**
         * Create modal element {@link http://getbootstrap.com/javascript/#modals}
         * @param $this is jQuery object of interactive element (like a button)
         * @param content
         * @param style
         * @returns {jQuery|HTMLElement}
         */
        let createModal = function ($this, content, style) {
            let $div = $('div.modal.fade');
            if (!$div.length) {
                $div = $('<div>', {'class':'modal fade'});
            }
            let $divDialog = $('<div>', {'class':'modal-dialog', 'style':style});
            let $divContent = $('<div>', {'class':'modal-content'});

            $divContent.html(content);
            $divDialog.append($divContent);
            $div.append($divDialog);
            $div.modal();

            // you can handle event "shown.bluz.modal" on button
            $div.on('shown.bs.modal', function () {
                $this.trigger('shown.bluz.modal');
            });

            // you can handle event "hidden.bluz.modal" on button
            $div.on('hidden.bs.modal', function () {
                // destroy modal
                $div.data('modal', null).remove();
                $this.trigger('hidden.bluz.modal');
            });

            // you can handle event "push.bluz" on button
            $div.on('push.data.bluz', function (event, data) {
                $this.trigger('push.data.bluz', data);
            });

            $this.data('modal', $div);

            return $div;
        };


		// live event handlers
		$('body')
            /**
             * Confirmation dialog
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#confirm-dialog
             */
			.on('click.bluz.confirm', '.confirm', function (event) {
                event.preventDefault();

                let $this = $(this);

                let message = $this.data('confirm') ? $this.data('confirm') : 'Are you sure?';
				if (!window.confirm(message)) {
					event.stopImmediatePropagation();
				}
			})
            /**
             * Call link by XMLHTTPRequest
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-links
             */
			.on('click.bluz.ajax', 'a.ajax', function (event) {
                event.preventDefault();

                let $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
                    return false;
				}

                let method = $this.data('ajax-method');
                let type = $this.data('ajax-type');
				type = (type ? type : 'json');

                let data = processData($this);
				$.ajax({
					url: $this.attr('href'),
					type: (method ? method : 'post'),
					data: data,
					dataType: type,
					beforeSend: function () {
						$this.addClass('disabled');
					},
                    success: function(data, textStatus, jqXHR) {
                        $this.trigger('success.ajax.bluz', arguments);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('error.ajax.bluz', arguments);
                    },
					complete: function () {
						$this.removeClass('disabled');
					}
				});
			})
            /**
             * Send form by XMLHTTPRequest
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-form
             */
            .on('submit.bluz.ajax', 'form.ajax', function (event) {
                event.preventDefault();

                let $this = $(this);
                if ($this.hasClass('disabled')) {
                    // request in progress
                    return false;
                }

                let method = $this.attr('method');
                let type = $this.data('ajax-type');
                let data = $this.serializeArray();

                $.ajax({
                    url: $this.attr('action'),
                    type: (method ? method : 'post'),
                    data: data,
                    dataType: (type ? type : 'json'),
                    beforeSend: function () {
                        $this.addClass('disabled');
                    },
                    success: function(data, textStatus, jqXHR) {
                        $this.trigger('success.ajax.bluz', arguments);

                        // data can be "undefined" if server return
                        // 204 header without content
                        if (data !== undefined && data.errors !== undefined) {
                            $this.trigger('error.form.bluz', arguments);
                            require(['bluz.form'], function(form) {
                                form.notices($this, data);
                            });
                        } else {
                            $this.trigger('success.form.bluz', arguments);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('error.ajax.bluz', arguments);
                    },
                    complete: function () {
                        $this.removeClass('disabled');
                    }
                });
            })
            /**
             * Load HTML content by XMLHTTPRequest
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-load
             */
			.on('click.bluz.ajax change.bluz.ajax', '.load', function (event) {
                event.preventDefault();

                let data, $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
					return false;
				}

                let method = $this.data('ajax-method');
                let target = $this.data('ajax-target');
                let source = $this.attr('href') || $this.data('ajax-source');

				if (!target) {
					throw "Undefined 'data-ajax-target' attribute";
				}

				if (!source) {
					throw "Undefined 'data-ajax-source' attribute (and href is missing)";
				}

				if ($this.is('select')) {
                    let dataArray = [];
                    let dataOption = $this.find('option:selected');
                    let key = $this.attr('name');
                    dataArray[key] = dataOption.val();

                    data = processData(dataOption);
                    $.extend(data, dataArray);
                } else {
                    data = processData($this);
                }

				$.ajax({
					url: source,
					type: (method ? method : 'post'),
					data: data,
					dataType: 'html',
					beforeSend: function () {
						$this.addClass('disabled');
					},
					success: function (data) {
                        $this.trigger('success.ajax.bluz', arguments);
                        let $target = $(target);
						if ($target.length === 0) {
							throw "Element defined by 'data-ajax-target' not found";
						}
						$target.html(data);
					},
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('error.ajax.bluz', arguments);
                    },
					complete: function () {
						$this.removeClass('disabled');
					}
				});
				return false;
			})
            /**
             * Load HTML content by XMLHTTPRequest into modal dialog
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#modal-dialog
             */
			.on('click.bluz.ajax', '.dialog', function (event) {
                event.preventDefault();

                let $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
					return false;
				}
                let method = $this.data('ajax-method');
                let style = $this.data('modal-style');

                $.ajax({
                    url: $this.attr('href'),
                    type: (method ? method : 'post'),
                    data: processData($this),
                    dataType: 'html',
                    beforeSend: function () {
                        $this.addClass('disabled');
                    },
                    success: function(content) {
                        $this.trigger('success.ajax.bluz', arguments);

                        let $div = createModal($this, content, style);
                        $div.on('success.form.bluz', function () {
                            $this.trigger('complete.ajax.bluz', arguments);
                            $div.modal('hide');
                        });
                        $div.modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('error.ajax.bluz', arguments);
                    },
                    complete: function () {
                        $this.removeClass('disabled');
                    }
                });
			})
            /**
             * Image popup preview
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#image-preview
             */
            .on('click.bluz.preview', '.bluz-preview', function (event) {
                event.preventDefault();

                let url, $this = $(this);
                // get image source
                if ($this.is('a')) {
                    url = $this.attr('href');
                } else {
                    url = $this.data('preview');
                }

                if (!url) {
                    return false;
                }
                let $img = $('<img>', {'src': url, 'class': 'img-polaroid'});
                    $img.css({
                        width: '100%',
                        margin: '0 auto',
                        display: 'block'
                    });

                let $span = $('<span>', {'class':'thumbnail'});
                    $span.append($img);

                createModal($this, $span, '').modal('show');
            })
        ;
	});
});