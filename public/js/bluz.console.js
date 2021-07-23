/**
 * Bluz console wrapper
 */
export let console = {
    log: function (message, text) {
        if (window.console !== undefined) {
            window.console.log('❕ ' + message, text);
        }
    },

    error: function (message, text) {
        if (window.console !== undefined) {
            window.console.error('‼️ ' + message, text);
        }
    }
}
