/**
 * Bluz notifications
 * mixed from Messages and Bootstrap Notify
 *
 * @author   Anton Shevchuk
 * @created  02.08.13 10:52
 */
/*global define,require*/
define(["jquery"], function($) {
    "use strict";

    let notify, classes, container, counter, deferred;
    let promises = [];

    counter = 0;

    /**
     * Notifications types matched to Twitter Bootstrap CSS classes
     * @type {{error: string, notice: string, success: string}}
     */
    classes = {
        "error":"alert alert-danger",
        "notice":"alert alert-info",
        "success":"alert alert-success"
    };

    /**
     * Return messages container
     * @returns {HTMLElement}
     */
    function getContainer () {
        container = document.getElementById("notify");
        if (!container) {
            container = document.createElement("div");
            container.id = "notify";
            document.body.appendChild(container);
        }

        return container;
    }

    /**
     * Prepare DOM element with native JS
     * @param {string} type
     * @param {string} content
     * @returns {HTMLElement}
     */
    function getNotify (type, content) {
        let div = document.createElement("div");
            div.className = classes[type];
            div.innerHTML = content;
            div.style.display = "none";
            div.onclick = function() {
                div.parentNode.removeChild(div);
            };
        getContainer().appendChild(div);
        return div;
    }


    notify = {
        /**
         * Set notifications from hash:
         *
         * {
         *      "error": ["error message", "another message"]
         *      "notice": ["info message", "messages"]
         *      "success": ["message", "messages"]
         * }
         *
         * @param notifications
         */
        set: function (notifications) {
            for (let type in notifications) {
                if (notifications.hasOwnProperty(type)) {
                    for (let i = 0; i < notifications[type].length; i++) {
                        notify.add(type, notifications[type][i]);
                    }
                }
            }
            return $.when.apply($, promises);
        },
        /**
         * @param {string} type
         * @param {string} content
         */
        add: function (type, content) {
            let $el, $def, promise;
            $def = new $.Deferred();

            $el = $(getNotify(type, content));
            $el.animate({opacity:"show"}, 300)
                .delay(5000)
                .animate({opacity:"hide"}, 300, function() {
                    // remove DOM element
                    $el.remove();
                    $def.resolve();
                });
            promise = $def.promise();
            promises.push(promise);
            return promise;
        },
        addError: function (content) {
            return notify.add("error", content);
        },
        addNotice: function (content) {
            return notify.add("notice", content);
        },
        addSuccess: function (content) {
            return notify.add("success", content);
        },
        /**
         * @param callback
         */
        done: function (callback) {
            $.when.apply($, promises).done(callback);
        }
    };

    return notify;
});