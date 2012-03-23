/**
 * Ready event
 *
 * @author   Anton Shevchuk
 */

(function($, undefined) {
    $(function(){

		// fix sub nav on scroll
		var $win = $(window)
		  , $nav = $('.subnav')
		  , navTop = $('.subnav').length && $('.subnav').offset().top - 40
		  , isFixed = 0;

		processScroll();

		$win.on('scroll', processScroll);

		function processScroll() {
			var scrollTop = $win.scrollTop();
			if (scrollTop >= navTop && !isFixed) {
				isFixed = 1;
				$nav.addClass('subnav-fixed');
			} else if (scrollTop <= navTop && isFixed) {
				isFixed = 0;
				$nav.removeClass('subnav-fixed');
			}
		}

		// jQUery UI widgets
        // Datepickers
        if (!$.browser.opera) {
            $('input[type=date]').datepicker({"dateFormat":'yy-mm-dd'});
        }
		// Tabs
		$(".tabs").tabs();

        // Toggle the dropdown menu's
//        $(".dropdown .button, .dropdown button").click(function () {
//            if (!$(this).find('span.toggle').hasClass('active')) {
//                $('.dropdown-slider').slideUp();
//                $('span.toggle').removeClass('active');
//            }
//
//            // open selected dropown
//            $(this).parent().find('.dropdown-slider').slideToggle('fast');
//            $(this).find('span.toggle').toggleClass('active');
//
//            return false;
//        });
//		// Close open dropdown slider by clicking elsewhwere on page
//		$(document).bind('click', function (e) {
//			if (e.target.id != $('.dropdown').attr('class')) {
//				$('.dropdown-slider').slideUp();
//				$('span.toggle').removeClass('active');
//			}
//		});

        // Check All checkbox

		// Ajax global events
		$("#loading").bind("ajaxSend", function(){
		    $(this).show();
		}).bind("ajaxComplete", function(){
		    $(this).hide();
		});

        // Ajax callback
        var ajaxCallback = function(data) {
            // redirect and reload page
            var callback = null;
            if (data.reload != undefined) {
                callback = function() {
                    // reload current page
                    window.location.reload();
                }
            } else if (data.redirect != undefined) {
                callback = function() {
                    // redirect to another page
                    window.location = data.redirect;
                }
            }

            // show messages and run callback after
            if (data._messages != undefined) {
                Messages.setCallback(callback);
                Messages.addMessages(data._messages);
            } else {
                callback();
            }

            if (data.callback != undefined && $.isFunction(window[data.callback])) {
                window[data.callback](data);
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

        // Ajax links
        $(document).on('click', 'a.ajax', function(){
            var $this = $(this);
            if ($this.hasClass('noactive')) {
                // request in progress
                return false;
            }

            $.ajax({
                url:$this.attr('href'),
                data: processData($this),
                dataType:'json',
                beforeSend:function() {
                    $this.addClass('noactive');
                },
                success:ajaxCallback,
                error:function() {
                    Messages.addWarning('Connection is fail');
                },
                complete:function() {
                    $this.removeClass('noactive');
                }
            });
            return false;
        });

		// Ajax modal
		$(document).on('click', 'a.dialog', function(){
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
				   Messages.addWarning('Connection is fail');
				},
				complete:function() {
				   $this.removeClass('noactive');
				}
			});
			return false;
		});

        // Ajax form
		$(document).on('submit', 'form.ajax', function(){
            var $this = $(this);
            if ($this.hasClass('noactive')) {
                // request in progress
                return false;
            }

            $.ajax({
                url:$this.attr('action'),
                type: 'post',
                data: $this.serializeArray(),
                dataType:'json',
                beforeSend:function() {
                    $this.addClass('noactive');
                },
                success:ajaxCallback,
                error:function() {
                    Messages.addWarning('Connection is fail');
                },
                complete:function() {
                    $this.removeClass('noactive');
                }
            });
            return false;
        });
    });
})(jQuery);

