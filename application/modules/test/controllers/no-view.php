<?php
/**
 * Disable view, like for backbone.js
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

return
/**
 * @return \closure
 */
function () {
    $this->getLayout()->breadCrumbs(
        [
            $this->getLayout()->ahref('Test', ['test', 'index']),
            'Without view',
        ]
    );
    return function () {
    };
};
