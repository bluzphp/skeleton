<?php
/**
 * Example of forms handle
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  13.12.13 18:12
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;

/**
 * @param  $int
 * @param  $string
 * @param  $array
 * @param  int $optional
 * @return array
 */
return function ($int, $string, $array, $optional = 0) {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Form Example',
        ]
    );
    if (Request::isPost()) {
        ob_start();
        var_dump($int, $string, $array, $optional);
        $inside = ob_get_contents();
        ob_end_clean();
        
        return [
            'inside' => $inside,
            'params' => Request::getParams()
        ];
    }
};
