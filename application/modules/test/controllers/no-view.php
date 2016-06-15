<?php
/**
 * Disable view, like for backbone.js
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Proxy\Layout;

/**
 * @return void
 */
return function () {
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Without view',
        ]
    );
    $this->template = null;
};
