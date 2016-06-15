<?php
/**
 * Empty view
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Proxy\Layout;

/**
 * @return \closure
 */
return function () {
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Empty',
        ]
    );
};
