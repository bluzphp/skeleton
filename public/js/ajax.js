/**
 * Fast AJAX development
 *
 * <code>
 *    <a href="/get" class="ajax">Click Me!</a>
 *    <a href="/dialog" class="dialog">Click Me!</a>
 *    <a href="/delete" class="confirm">Click Me!</a>
 *    <a href="/delete" class="ajax confirm">Click Me!</a>
 *    <form action="/save/" class="ajax">
 *        ...
 *    </form>
 * </code>
 *
 * @author   Anton Shevchuk
 */
(function($){
	$(function() {
		// Ajax global events
		$("#loading").bind("ajaxSend", function(){
		    $(this).show();
		}).bind("ajaxComplete", function(){
		    $(this).hide();
		});

        // Ajax callback
        var ajax = {
			success:function(data) {
				// redirect and reload page
				var callback = null;
				if (typeof data._reload != undefined) {
					callback = function() {
						// reload current page
						window.location.reload();
					}
				} else if (data._redirect != undefined) {
					callback = function() {
						// redirect to another page
						window.location = data.redirect;
					}
				}

				// show messages and run callback after
				if (data._messages != undefined) {
					Messages.setCallback(callback);
					Messages.addMessages(data._messages);
				} else if (callback) {
					callback();
				}

				if (data.callback != undefined && $.isFunction(window[data.callback])) {
					window[data.callback](data);
				}
			},
			error:function() {
				Messages.addError('Connection is fail');
			}
		};

        // get only plain data
        var processData = function(el) {
            var data = el.data();
            var plain = {};

            $.each(data, function(key, value){
                if (
                    typeof value == 'function' ||
                    typeof value == 'object') {
                    return false;
                } else {
                    plain[key] = value;
                }
            });
            return plain;
        };

        // live event handlers
        $('body')
		// disabled bootstrap dropdown event handler
		.off('.dropdown', 'a.ajax, a.dialog, a.confirm')
		// Ajax links
		.on('click.bluz.ajax', 'a.ajax', function(){
            var $this = $(this);
            if ($this.hasClass('noactive')) {
                // request in progress
                return false;
            }

			if ($this.hasClass('confirm')) {
				var message = $this.attr('title') ? $this.attr('title') : 'Are you sure?';
				if (!confirm(message)) {
					return false;
				}
			}

            var data = processData($this);
            data.json = 1;

            $.ajax($.extend({
                url:$this.attr('href'),
                data: data,
                dataType:'json',
                beforeSend:function() {
                    $this.addClass('noactive');
                },
                complete:function() {
                    $this.removeClass('noactive');
                }
            }, ajax));
            return false;
        })
		// Ajax modal
		.on('click.bluz.ajax', 'a.dialog', function(){
			var $this = $(this);
			if ($this.hasClass('noactive')) {
				// request in progress
				return false;
			}

			$.ajax({
				url:$this.attr('href'),
				data: processData($this),
				dataType:'html',
				beforeSend:function() {
				   $this.addClass('noactive');
				},
				success:function(data) {
					var $div = $('<div>', {'class': 'modal hide fade'});
					$div.html(data);
					$div.modal({
						keyboard:true,
						backdrop:true
					}).on('shown', function() {
						var onShown = window[$this.attr('shown')];
						if (typeof onShown === 'function') {
							onShown.call($div);
						}
					}).on('hidden', function() {
						var onHidden = window[$this.attr('hidden')];
						if (typeof onHidden === 'function') {
							onHidden.call($div);
						}
						$(this).remove();
					});
					$div.modal('show');
				},
				error:function() {
				   Messages.addError('Connection is fail');
				},
				complete:function() {
				   $this.removeClass('noactive');
				}
			});
			return false;
		})

        // Ajax form
		.on('submit.bluz.ajax', 'form.ajax', function(){
            var $this = $(this);
            if ($this.hasClass('noactive')) {
                // request in progress
                return false;
            }

            var data = {json: 1}; //responses as json
            var formData = $this.serializeArray();

            for (var i in formData) {
                data[formData[i].name] = formData[i].value;
            }

            $.ajax($.extend({
                url: $this.attr('action'),
                type: 'post',
                data: data,
                dataType:'json',
                beforeSend:function() {
                    $this.addClass('noactive');
                },
                complete:function() {
                    $this.removeClass('noactive');
                }
            }, ajax));
            return false;
        })

        // Confirmation dialog
        .on('click.bluz', '.confirm:not(.ajax)', function(e){
            var $this = $(this);

            var message = $this.attr('title') ? $this.attr('title') : 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
})(jQuery, undefined);