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
 *        $("li a").off(".bluz");
 *        // or
 *        $("li a").off(".ajax");
 *    </source>
 * </code>
 * @link   https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes
 * @author Anton Shevchuk
 */
/*global define,require,window,document*/
define(["jquery", "bluz", "bluz.modal", "bluz.notify"], function ($, bluz, modal, notify) {
    "use strict";

    /**
     * Get all `data-*` from element
     * @param el
     * @returns {{}}
     */
    let processData = function (el) {
        let data = el.data();
        let plain = {};

        $.each(data, function (key, value) {
            if (!(typeof value === "function" ||
                typeof value === "object" ||
                key === "ajaxMethod" ||
                key === "ajaxSource" ||
                key === "ajaxTarget" ||
                key === "ajaxType")) {
                plain[key] = value;
            }
        });
        return plain;
    };

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
            .ajaxStart(function (event) {
                $("#loading").show();
            })
            .ajaxSend(function (event, jqXHR, options) {
                let $element = $(options.context);
                if ($element.hasClass("disabled")) {
                    return false;
                } else {
                    $element.addClass("disabled");
                }
            })
            .ajaxSuccess(function (event, jqXHR, options) {
                try {
                    let $element = $(options.context);
                    $element.trigger("success.ajax.bluz", arguments);

                    // set messages if it exists
                    if (jqXHR.getResponseHeader("Bluz-Notify")) {
                        let notifications = $.parseJSON(jqXHR.getResponseHeader("Bluz-Notify"));
                        notify.set(notifications);
                    }

                    // redirect and reload page
                    if (jqXHR.getResponseHeader("Bluz-Redirect")) {
                        notify.done(function () {
                            // redirect to another page
                            window.location = jqXHR.getResponseHeader("Bluz-Redirect");
                        });
                    }
                } catch (err) {
                    bluz.error(err.name, err.message);
                }
            })
            .ajaxError(function (event, jqXHR, options, thrownError) {
                try {
                    let $element = $(options.context);
                    $element.trigger("error.ajax.bluz", arguments);

                    // try to get messages from headers
                    if (jqXHR.getResponseHeader("Bluz-Notify")) {
                        let notifications = $.parseJSON(jqXHR.getResponseHeader("Bluz-Notify"));
                        notify.set(notifications);
                    }

                    // try to get error message from JSON response
                    if (options.dataType === "json" ||
                        jqXHR.getResponseHeader("Content-Type") === "application/json") {
                        let response = $.parseJSON(jqXHR.responseText);
                        if (response.hasOwnProperty("error") && response.error.hasOwnProperty("message")) {
                            notify.addError(response.error.message);
                        }
                    } else {
                        modal.create($(document), jqXHR.responseText, "width: 800px").modal("show");
                    }
                    bluz.log(thrownError, jqXHR.responseText);
                } catch (err) {
                    bluz.error(err.name, err.message);
                }
            })
            .ajaxComplete(function (event, jqXHR, options) {
                let $element = $(options.context);
                $element.removeClass("disabled");
            })
            .ajaxStop(function (event) {
                $("#loading").hide();
            });

        // live event handlers
        $("body")
            /**
             * Confirmation dialog
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#confirm-dialog
             */
            .on("click.bluz.confirm", ".confirm", function (event) {
                event.preventDefault();

                let $this = $(this);

                let message = $this.data("confirm") || "Are you sure?";
                if (!window.confirm(message)) {
                    event.stopImmediatePropagation();
                }
            })
            /**
             * Call link by XMLHTTPRequest
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-links
             */
            .on("click.bluz.ajax", "a.ajax", function (event) {
                event.preventDefault();

                let $this = $(this);

                $.ajax({
                    url: $this.attr("href"),
                    type: $this.data("ajax-method") || "post",
                    data: processData($this),
                    dataType: $this.data("ajax-type") || "json",
                    context: this
                });
            })
            /**
             * Send form by XMLHTTPRequest
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-form
             */
            .on("submit.bluz.ajax", "form.ajax", function (event) {
                event.preventDefault();

                let $this = $(this);

                $.ajax({
                    url: $this.attr("action"),
                    type: $this.attr("method") || "post",
                    data: $this.serializeArray(),
                    dataType: $this.data("ajax-type") || "json",
                    context: this,
                    success: function (data) {
                        // data can be "undefined" if server return
                        // 204 header without content
                        if (data !== undefined && data.errors !== undefined) {
                            $this.trigger("error.form.bluz", arguments);
                            require(["bluz.form"], function (form) {
                                form.notices($this, data);
                            });
                        } else {
                            $this.trigger("success.form.bluz", arguments);
                        }
                    },
                });
            })
            /**
             * Load HTML content by XMLHTTPRequest
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#ajax-load
             */
            .on("click.bluz.ajax change.bluz.ajax", ".load", function (event) {
                event.preventDefault();

                let data;
                let $this = $(this);

                let method = $this.data("ajax-method");
                let target = $this.data("ajax-target");
                let source = $this.data("ajax-source") || $this.attr("href");

                if (!target) {
                    throw "Undefined `data-ajax-target` attribute";
                }

                if (!source) {
                    throw "Undefined `data-ajax-source` attribute (and href is missing)";
                }

                let $target = $(target);

                if ($target.length === 0) {
                    throw "Element defined by `data-ajax-target` not found";
                }

                if ($this.is("select")) {
                    let dataArray = [];
                    let dataOption = $this.find("option:selected");
                    let key = $this.attr("name");
                    dataArray[key] = dataOption.val();

                    data = processData(dataOption);
                    $.extend(data, dataArray);
                } else {
                    data = processData($this);
                }

                $.ajax({
                    url: source,
                    type: (method || "post"),
                    data: data,
                    dataType: "html",
                    success: function (data) {
                        $target.html(data);
                    }
                });
                return false;
            })
            /**
             * Load HTML content by XMLHTTPRequest into modal dialog
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#modal-dialog
             */
            .on("click.bluz.ajax", ".dialog", function (event) {
                event.preventDefault();

                let $this = $(this);

                $.ajax({
                    url: $this.attr("href"),
                    type: $this.data("ajax-method") || "post",
                    data: processData($this),
                    dataType: "html",
                    success: function (content) {
                        let $div = modal.create($this, content, $this.data("modal-style"));
                        $div.on("success.form.bluz", function () {
                            $this.trigger("complete.ajax.bluz", arguments);
                            $div.modal("hide");
                        });
                        $div.modal("show");
                    }
                });
            })
            /**
             * Image popup preview
             * @link https://github.com/bluzphp/skeleton/wiki/JavaScript-Notes#image-preview
             */
            .on("click.bluz.preview", ".bluz-preview", function (event) {
                event.preventDefault();

                let url;
                let $this = $(this);
                // get image source
                if ($this.is("a")) {
                    url = $this.attr("href");
                } else {
                    url = $this.data("preview");
                }

                if (!url) {
                    return false;
                }
                let $img = $("<img>", {"src": url, "class": "img-polaroid"});
                $img.css({
                    width: "100%",
                    margin: "0 auto",
                    display: "block"
                });

                let $span = $("<span>", {"class": "thumbnail"});
                $span.append($img);

                modal.create($this, $span, "").modal("show");
            });
    });
});