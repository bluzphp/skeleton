<?php
/**
 * Works with Options Module
 *
 * @author   Anton Shevchuk
 * @created  04.11.13 13:30
 */
namespace Application;

use Bluz;
use Application\Options;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Options',
        ]
    );

    if ($data = Options\Table::get('example')) {
        $view->message = sprintf('Option `example` was found, it is `%s`', (string) $data);
        Options\Table::remove('example');
    } else {
        $view->message = 'Option `example` not found, try again later';
        Options\Table::set('example', uniqid('example_'));
    }
};
