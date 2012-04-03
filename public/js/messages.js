/**
 * Notices
 */
var Messages = (function($, undefined) {
    var M = {
        _uid:"messages",
        _el:null,
        _to:null,
        _callback:null,
        _settings:{
            'fadeIn':500,
            'fadeOut':500,
            'showTime':3000
        },
        getContainer:function(){
            if (!M._el) {
                if ($('#'+M._uid).length == 0) {
                    M._el = $('<div id="'+M._uid+'">'+'</div>');
                    M._el.prependTo('body');
                    M._el.css({display:'none'});
                } else if ($('#'+M._uid).length > 0) {
                    M._el = $('#'+M._uid);
                }
                M._el.click(function(){
                    $(this).hide();
                });
            }
            return M._el;
        },
        showMessage:function(type, text) {
            var el = M.getContainer();

            if (type != undefined && text != undefined) {
                // add new message to container
                var p = $('<p class="'+type+'">'+text+'</p>');
                el.append(p);
            } else {
                text = el.text();
            }

            // additonal 12ms for char
            var delay = M._settings.showTime + text.length * 12;

            el.stop(true, true);
            el.data('fx', null);
			// hack for bootstrap.js with float header menu

			var top = 0;

			if ($('.navbar-fixed-top').length) {
				top += 40;
			}

			if ($('.subnav-fixed').length) {
				top += 37;
			}

			el.css('top', top);
            el.animate({opacity:"show"}, M._settings.fadeIn)
              .delay(delay)
              .animate({opacity: "hide"}, M._settings.fadeOut, M.callback);

        },
        addMessages:function(messages) {
            for (var type in messages) {
                $(messages[type]).each(function(i, el){
                    M.showMessage(type, el);
                })
            }
        },
        addError:function(message) {
            M.showMessage('error', message);
        },
        addNotice:function(message) {
            M.showMessage('info', message);
        },
        addSuccess:function(message) {
            M.showMessage('success', message);
        },
        setCallback:function(callback) {
            M._callback = callback;
        },
        /**
         * callback realization
         * call after hided message bar
         */
        callback:function() {
            // clear messages
            M.clear();
            // if exists callback run it and clean
            if (M._callback) {
                M._callback();
                M._callback = null;
            }

        },
        clear:function() {
            M.getContainer().html('');
        },
        ready:function() {
            if ($('#'+M._uid).length > 0) {
                M.showMessage();
            }
        }
    };
    return M;
})(jQuery);

// DOM ready event
jQuery(Messages.ready);
