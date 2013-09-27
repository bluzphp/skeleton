/**
 * Bluz notifications
 * mixed from Messages and Bootstrap Notify
 *
 * @author   Anton Shevchuk
 * @created  02.08.13 10:52
 */
/*global define,require*/
define(['jquery'], function($) {
    "use strict";

    var notify, classes, container, counter, deferred;

    counter = 0;
    deferred = $.Deferred();

    /**
     * Notifications types matched to Twitter Bootstrap CSS classes
     * @type {{error: string, notice: string, success: string}}
     */
    classes = {
        'error':'alert alert-danger',
        'notice':'alert alert-info',
        'success':'alert alert-success'
    };

    /**
     * Return messages container
     * @returns {HTMLElement}
     */
    function prepareContainer () {
        container = document.getElementById('notify');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notify';
            $('body').append(container);
        }

        return container;
    }

    /**
     * Prepare DOM element with native JS
     * @param {string} type
     * @param {string} content
     * @returns {HTMLElement}
     */
    function prepareNotify (type, content) {
        var div = document.createElement('div');
            div.className = classes[type];
            div.innerHTML = content;
            div.style.display = 'none';
            div.onclick = function() {
                div.parentNode.removeChild(div);
            };

        // add notification to container
        var container = prepareContainer();
        container.appendChild(div);

        // increase notifications counter
        counter++;
        return div;
    }

    /**
     *
     * @param {jQuery} $el
     */
    function hideNotify ($el) {
        // decrease
        if (counter) {
            counter--;
        }
        // mark deferred object as resolved
        if (counter === 0) {
            deferred.resolve();
        }
        // remove DOM element
        $el.remove();
    }

    notify = {
        /**
         * Set notifications from hash:
         *
         * {
         *      'error': ['error message', 'another message']
         *      'notice': ['info message', 'messages']
         *      'success': ['message', 'messages']
         * }
         *
         * @param notifications
         */
        set: function (notifications) {
            for (var type in notifications) {
                if (notifications.hasOwnProperty(type)) {
                    for (var i = 0; i < notifications[type].length; i++) {
                        notify.add(type, notifications[type][i]);
                    }
                }
            }
        },
        /**
         * @param {string} type
         * @param {string} content
         */
        add: function (type, content) {
            var $el;
            $el = $(prepareNotify(type, content));
            $el.animate({opacity:"show"}, 300)
                .delay(3000)
                .animate({opacity:"hide"}, 300, function() {
                    hideNotify($el);
                });
        },
        addError: function (content) {
            notify.add('error', content);
        },
        addNotice: function (content) {
            notify.add('notice', content);
        },
        addSuccess: function (content) {
            notify.add('success', content);
        },
        /**
         * @param callback
         */
        addCallback: function (callback) {
            deferred.done(callback);
        }
    };

    return notify;
});