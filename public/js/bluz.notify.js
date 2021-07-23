/**
 * Bluz notifications
 * mixed from Messages and Bootstrap Notify
 *
 * @author   Anton Shevchuk
 * @created  02.08.13 10:52
 */
import '../vendor/jquery/jquery.js';
export {notify};

let classes;
let container;
let promises = [];

/**
 * Notifications types matched to Twitter Bootstrap CSS classes
 * @type {{error: string, notice: string, success: string}}
 */
classes = {
    'error': 'alert alert-danger',
    'notice': 'alert alert-info',
    'success': 'alert alert-success'
};

/**
 * Return messages container
 * @return {HTMLElement} container
 */
function getContainer() {
    container = document.getElementById('notify');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notify';
        document.body.appendChild(container);
    }

    return container;
}

/**
 * Prepare DOM element with native JS
 * @param {string} type of notification
 * @param {string} content of notification
 * @return {HTMLElement} notification
 */
function getNotify(type, content) {
    let div = document.createElement('div');
    div.className = classes[type];
    div.innerHTML = content;
    div.style.display = 'none';
    div.onclick = () => {
        div.parentNode.removeChild(div);
    };
    getContainer().appendChild(div);
    return div;
}

let notify = {
    /**
     * Set notifications from hash:
     *
     * {
     *    'error': ['error message', 'another message']
     *    'notice': ['info message', 'messages']
     *    'success': ['message', 'messages']
     * }
     *
     * @param {{}} notifications hash
     * @return {Promise} jQuery `when` promise
     */
    set: (notifications) => {
        for (let type in notifications) {
            if (notifications.hasOwnProperty(type)) {
                for (let i = 0; i < notifications[type].length; i++) {
                    notify.add(type, notifications[type][i]);
                }
            }
        }
        return $.when.apply($, promises);
    },
    add: (type, content) => {
        let $el;
        let $def;
        let promise;
        $def = new $.Deferred();

        $el = $(getNotify(type, content));
        $el.animate({opacity: 'show'}, 300)
            .delay(5000)
            .animate({opacity: 'hide'}, 300, function () {
                // remove DOM element
                $el.remove();
                $def.resolve();
            });
        promise = $def.promise();
        promises.push(promise);
        return promise;
    },
    addError: content => notify.add('error', content),
    addNotice: content => notify.add('notice', content),
    addSuccess: content => notify.add('success', content),
    done: callback => $.when.apply($, promises).done(callback)
};
