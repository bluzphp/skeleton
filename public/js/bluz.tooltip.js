/**
 * Boostrap Tooltips
 */
import '../vendor/bootstrap/js/bootstrap.bundle.js';

let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
