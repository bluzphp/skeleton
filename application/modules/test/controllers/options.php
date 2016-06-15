<?php
/**
 * Works with Options Module
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  04.11.13 13:30
 */
namespace Application;

use Application\Options;
use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Options',
        ]
    );

    if ($example = Options\Table::get('example')) {
        $message = sprintf('Option `example` was found, it is `%s`', (string) $example);
        Options\Table::remove('example');
    } else {
        $message = 'Option `example` not found, try again later';
        Options\Table::set('example', uniqid('example_'));
    }
    
    $this->assign('message', $message);
};
