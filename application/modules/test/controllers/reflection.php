<?php
/**
 * Get custom reflection data
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  17.05.13 17:05
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Controller\Reflection;
use Bluz\Proxy\Layout;

/**
 * @key Example of custom key-value
 * @key Array also supported
 * @param int $id
 * @param string $other
 * @route /test-reflection-{$id}.html
 * @route /test-reflection.html
 * @return array
 */
return function ($id = 0, $other = "default value") {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Reflection of this controller',
        ]
    );

    $reflection = new Reflection(__FILE__);
    $reflection->process();

    return [
        'functionData' => file_get_contents(__FILE__),
        'reflectionData' => $reflection,
        'id' => $id,
        'other' => $other
    ];
};
