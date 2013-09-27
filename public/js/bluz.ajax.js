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
 * @author   Anton Shevchuk
 */
/*global define,require*/
define(['jquery', 'bluz', 'bluz.notify'], function ($, bluz, notify) {
	"use strict";
	// on DOM ready state
	$(function () {
		// Ajax global events
        $(document)
            .ajaxStart(function () {
                $('#loading').show();
            })
            .ajaxSuccess(function (event, jqXHR) {
                // redirect and reload page
                var callback = null;
                if (jqXHR.getResponseHeader('Bluz-Reload')) {
                    callback = function () {
                        // reload current page
                        window.location.reload();
                    };
                } else if (jqXHR.getResponseHeader('Bluz-Redirect')) {
                    callback = function () {
                        // redirect to another page
                        window.location = jqXHR.getResponseHeader('Bluz-Redirect');
                    };
                }

                // show messages and run callback after
                if (jqXHR.getResponseHeader('Bluz-Notify')) {
                    var notifications = $.parseJSON(jqXHR.getResponseHeader('Bluz-Notify'));
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
                    var notifications = $.parseJSON(jqXHR.getResponseHeader('Bluz-Notify'));
                    notify.set(notifications);
                }

                // try to get error message from JSON response
                if (!(options.dataType === 'json' ||
                    jqXHR.getResponseHeader('Content-Type') === 'application/json')) {
                    var $div = createModal(jqXHR.responseText, 'width:800px');
                    $div.modal('show');
                }
            })
            .ajaxComplete(function () {
                $('#loading').hide();
            });

		// get only plain data
		var processData = function (el) {
			var data = el.data();
			var plain = {};

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

        var modals = [];
        var createModal = function (content, style) {

            var $div = $('<div>', {'class':'modal fade'});
            var $divDialog = $('<div>', {'class':'modal-dialog', 'style':style});
            var $divContent = $('<div>', {'class':'modal-content'});

            $divContent.html(content);
            $divDialog.append($divContent);
            $div.append($divDialog);
            $div.modal();

            modals.push($div);

            return $div;
        };
        var closeModals = function () {
            for (var i = 0; i < modals.length; i++) {
                modals[i].modal('hide');
                modals[i].data('modal', null);
            }
            modals = [];
        };

		// live event handlers
		$('body')
			// Confirmation dialog
			.on('click.bluz.confirm', '.confirm', function (event) {
				var $this = $(this);

				var message = $this.data('confirm') ? $this.data('confirm') : 'Are you sure?';
				if (!window.confirm(message)) {
					event.stopImmediatePropagation();
					event.preventDefault();
				}
			})
			// Ajax links
			.on('click.bluz.ajax', 'a.ajax', function (event) {
				var $this = $(this);
				if ($this.hasClass('disabled')) {
					// request in progress
                    event.preventDefault();
                    event.stopPropagation();
                    return;
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
                    success: function(data, textStatus, jqXHR) {
                        $this.trigger('ajax.bluz.success', arguments);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('ajax.bluz.error', arguments);
                    },
					complete: function () {
						$this.removeClass('disabled');
					}
				});
				event.preventDefault();
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
					throw "Undefined 'data-ajax-target' attribute";
				}

				if (!source) {
					throw "Undefined 'data-ajax-source' attribute (and href is missing)";
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
                        $this.trigger('ajax.bluz.success', arguments);
						var $target = $(target);
						if ($target.length === 0) {
							throw "Element defined by 'data-ajax-target' not found";
						}
						$target.html(data);
					},
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('ajax.bluz.error', arguments);
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
                var style = $this.data('modal-style');

                $.ajax({
                    url: $this.attr('href'),
                    type: (method ? method : 'post'),
                    data: processData($this),
                    dataType: 'html',
                    beforeSend: function () {
                        $this.addClass('disabled');
                    },
                    success: function(content) {
                        $this.trigger('ajax.bluz.success', arguments);
                        var $div = createModal(content, style);
                        $div.on('shown.bs.modal', function () {
                            // you can handle event "shown.bluz.modal" on button
                            $this.trigger('shown.bluz.modal');
                        })
                        .on('hidden.bs.modal', function () {
                            $(this).data('modal', null).remove();
                            // you can handle event "hidden.bluz.modal" on button
                            $this.trigger('hidden.bluz.modal');
                        });
                        $div.modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('ajax.bluz.error', arguments);
                    },
                    complete: function () {
                        $this.removeClass('disabled');
                    }
                });
				return false;
			})
            // Image popup preview
            .on('click.bluz.preview', '.bluz-preview', function() {
                var url, $this = $(this);
                // get image source
                if ($this.is('a')) {
                    url = $this.attr('href');
                } else {
                    url = $this.data('preview');
                }

                if (!url) {
                    return false;
                }
                var $img = $('<img>', {'src': url, 'class': 'img-polaroid'});
                    $img.css({
                        width: '100%',
                        margin: '0 auto',
                        display: 'block'
                    });

                var $span = $('<span>', {'class':'thumbnail'});
                    $span.append($img);

                var $div = createModal($span, '');
                    $div.modal('show');
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

				$.ajax({
					url: $this.attr('action'),
					type: (method ? method : 'post'),
					data: data,
					dataType: (type ? type : 'json'),
					beforeSend: function () {
						$this.addClass('disabled');
					},
                    success: function(data, textStatus, jqXHR) {
                        $this.trigger('ajax.bluz.success', arguments);

                        if (data.errors !== undefined) {
                            $this.trigger('form.bluz.error', arguments);
                            require(['bluz.form'], function(form) {
                                form.notices($this, data);
                            });
                        } else {
                            $this.trigger('form.bluz.success', arguments);
                            closeModals();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $this.trigger('ajax.bluz.error', arguments);
                    },
					complete: function () {
						$this.removeClass('disabled');
					}
				});
				return false;
			});
	});
});