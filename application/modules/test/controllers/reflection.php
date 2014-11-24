<?php
/**
 * Get custom reflection data
 *
 * @category Application
 *
 * @author   dark
 * @created  17.05.13 17:05
 */
namespace Application;

use Bluz\Controller\Reflection;
use Bluz\Proxy\Layout;

return
/**
 * @key Example of custom key-value
 * @key Array also supported
 * @param int $id
 * @param string $other
 * @route /test-reflection-{$id}.html
 * @route /test-reflection.html
 * @return \closure
 */
function ($id = 0, $other = "default value") use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Reflection of this controller',
        ]
    );

    $reflection = new Reflection(__FILE__);
    $reflection->process();

    $view->functionData = file_get_contents(__FILE__);
    $view->reflectionData = $this->reflection(__FILE__);
    $view->id = $id;
    $view->other = $other;
};
