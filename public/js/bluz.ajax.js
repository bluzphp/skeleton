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
 *
 * @author   Anton Shevchuk
 */
define(['jquery', 'bluz', 'bluz.messages'], function ($, bluz, messages) {
	"use strict";
	// on DOM ready state
	$(function () {
		// Ajax global events
        $(document)
            .ajaxSuccess(function (event, jqXHR, options) {
                if (options.dataType === 'json' ||
                    jqXHR.getResponseHeader('Content-Type') == 'application/json') {
                    var data;
                    try {
                        data = jQuery.parseJSON(jqXHR.responseText);
                    } catch (error) {
                        // its not json
                        return;
                    }
                    // check handler option
                    if (jqXHR.getResponseHeader('Bluz-Handler') == 0) {
                        return;
                    }
                    // it has the data
                    // redirect and reload page
                    var callback = null;
                    if (jqXHR.getResponseHeader('Bluz-Reload')) {
                        callback = function () {
                            // reload current page
//                            window.location.reload();
                        };
                    } else if (jqXHR.getResponseHeader('Bluz-Redirect')) {
                        callback = function () {
                            // redirect to another page
                            window.location = jqXHR.getResponseHeader('Bluz-Redirect');
                        };
                    }

                    // show messages and run callback after
                    if (data._messages !== undefined) {
                        messages.setCallback(callback);
                        messages.addMessages(data._messages);
                    } else if (callback) {
                        callback();
                    }

                    // callback to AMD
                    if (data.callback !== undefined && (data.callback.substring(0, 5) == 'bluz.')) {
                        var amd = data.callback.split('.');
                        if (amd.length !== 3) {
                            bluz.log('Response consist unsupported callback call: ' + data.callback);
                            return;
                        }
                        require([amd[0]+'.'+amd[1]], function(module){
                            module[amd[2]](data);
                        });
                        return;
                    }

                    // callback to global scope
                    if (data.callback !== undefined && $.isFunction(window[data.callback])) {
                        window[data.callback](data);
                        return;
                    }
                }
            })
            .ajaxError(function (event, jqXHR, options, thrownError) {
                bluz.log(thrownError, jqXHR.responseText);
                messages.addError('Connection is fail');

                // try to get error message from JSON response
                if (options.dataType === 'json' ||
                    jqXHR.getResponseHeader('Content-Type') == 'application/json') {
                    try {
                        var data = jQuery.parseJSON(jqXHR.responseText);
                        // show messages
                        if (data._messages !== undefined) {
                            messages.addMessages(data._messages);
                        }
                    } catch (error) {
                        // its not json
                    }
                }
            });

        // Loading
		$("#loading")
			.ajaxStart(function () {
				$(this).show();
			})
			.ajaxComplete(function () {
				$(this).hide();
			});

		// get only plain data
		var processData = function (el) {
			var data = el.data();
			var plain = {};

			$.each(data, function (key, value) {
				if (
					typeof value === 'function' ||
						typeof value === 'object' ||
						key === 'ajaxMethod' ||
						key === 'ajaxSource' ||
						key === 'ajaxTarget' ||
						key === 'ajaxType'
					) {
					return false;
				} else {
					plain[key] = value;
				}
			});
			return plain;
		};

		// live event handlers
		$('body')
			// Confirmation dialog
			.on('click.bluz.confirm', '.confirm', function (event) {
				var $this = $(this);

				var message = $this.data('confirm') ? $this.data('confirm') : 'Are you sure?';
				if (!confirm(message)) {
					event.stopImmediatePropagation();
					event.preventDefault();
				}
			})
			// Ajax links
			.on('click.bluz.ajax', 'a.ajax', function () {
				var $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
					return false;
				}

				var method = $this.data('ajax-method');
				var type = $this.data('ajax-type');
				type = (type ? type : 'json');
				var data = processData($this);
				$.ajax({
					url: $this.attr('href'),
					type: (method ? method : 'post'),
					data: data,
					dataType: type,
					beforeSend: function () {
						$this.addClass('disabled');
					},
					complete: function () {
						$this.removeClass('disabled');
					}
				});
				return false;
			})
			// Ajax load
			.on('click.bluz.ajax', '.load', function () {
				var $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
					return false;
				}

				var method = $this.data('ajax-method');
				var target = $this.data('ajax-target');
				var source = $this.attr('href') || $this.data('ajax-source');

				if (!target) {
					throw "Undefined 'data-target' attribute";
				}

				if (!source) {
					throw "Undefined 'data-source' attribute (and href is missing)";
				}

				$.ajax({
					url: source,
					type: (method ? method : 'post'),
					data: processData($this),
					dataType: 'html',
					beforeSend: function () {
						$this.addClass('disabled');
					},
					success: function (data) {
						var $target = $(target);
						if ($target.length === 0) {
							throw "Element defined by 'data-ajax-target' not found";
						}
						$target.html(data);
					},
					complete: function () {
						$this.removeClass('disabled');
					}
				});
				return false;
			})
			// Ajax modal dialog
			.on('click.bluz.ajax', '.dialog', function () {
				var $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
					return false;
				}
                var method = $this.data('ajax-method');

                $.ajax({
                    url: $this.attr('href'),
                    type: (method ? method : 'post'),
                    data: processData($this),
                    dataType: 'html',
                    beforeSend: function () {
                        $this.addClass('disabled');
                    },
                    success: function(content) {
                        var $div = $('<div>', {'class': 'modal hide fade'});
                        $div.html(content);
                        $div.modal()
                            .on('shown',function () {
                                var onShown = window[$this.attr('shown')];
                                if (typeof onShown === 'function') {
                                    onShown.call($div);
                                }
                                bluz.ready();
                            }).on('hidden', function () {
                                var onHidden = window[$this.attr('hidden')];
                                if (typeof onHidden === 'function') {
                                    onHidden.call($div);
                                }
                                $(this).remove();
                            });
                        $div.modal('show');
                    },
                    complete: function () {
                        $this.removeClass('disabled');
                    }
                });


				return false;
			})

			// Ajax form
			.on('submit.bluz.ajax', 'form.ajax', function () {
				var $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
					return false;
				}

				var method = $this.attr('method');
				var type = $this.data('ajax-type');
				var data = $this.serializeArray();
                    data.push({name:'_formId', value:$this.attr('id')});

				$.ajax({
					url: $this.attr('action'),
					type: (method ? method : 'post'),
					data: data,
					dataType: (type ? type : 'json'),
					beforeSend: function () {
						$this.addClass('disabled');
					},
					complete: function () {
						$this.removeClass('disabled');
					}
				});
				return false;
			});
	});
});