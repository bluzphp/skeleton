/**
 * Bluz modal - prepare Bootstrap modal
 *
 * @author   Anton Shevchuk
 * @created  29.06.17 19:19
 */
import '../vendor/jquery/jquery.js';
import '../vendor/bootstrap/js/bootstrap.bundle.js';

export {modal};

let modal = {
    /**
     * Create modal element {@link http://getbootstrap.com/javascript/#modals}
     * @param {jQuery} $this is jQuery object of interactive element (like a button)
     * @param {String} content HTML
     * @param {String} className for inline
     * @return {jQuery} HTML Element
     */
    create: function ($this, content, className) {
        let $div = $('<div>', {'class': 'modal fade'});
        $('body').append($div);

        let $divDialog = $('<div>', {'class': 'modal-dialog ' + className});
        let $divContent = $('<div>', {'class': 'modal-content'});
        $divDialog.append($divContent);
        $divContent.html(content);
        $div.append($divDialog);

        new bootstrap.Modal($div.get(0));

        // you can handle event 'shown.bluz.modal' on button
        $div.on('shown.bs.modal', function () {
            $divContent.find('.modal-focus').focus();
            $this.trigger('shown.bluz.modal');
        });

        // you can handle event 'hidden.bluz.modal' on button
        $div.on('hidden.bs.modal', function () {
            // destroy modal
            $div.data('modal', null).remove();
            $this.trigger('hidden.bluz.modal');
        });

        // you can handle event 'push.bluz.data' on button
        $div.on('push.bluz.data', function (event, data) {
            $this.trigger('push.bluz.data', data);
        });

        $this.data('modal', $div);

        return $div;
    },
    /**
     * @param {jQuery|string} $element
     */
    show: function($element) {
        $element = $($element);
        if ($element.length) {
            bootstrap.Modal.getOrCreateInstance($element.get(0))?.show();
        }
    },
    /**
     * @param {jQuery|string} $element
     */
    hide: function($element) {
        $element = $($element);
        if ($element.length) {
            bootstrap.Modal.getInstance($element.get(0))?.hide();
        }
    }
};
