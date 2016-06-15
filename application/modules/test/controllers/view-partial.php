<?php
/**
 * Example of partial methods of View
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  13.10.11 12:39
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @return array
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'View Partial Helpers',
        ]
    );
    return ['data' => [
        'first'=> array(2,3,4,5),
        'second'=> array(9,8,7,6),
        'third'=> array(1,3,5,7),
    ]];
};
