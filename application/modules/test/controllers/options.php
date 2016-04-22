<?php
/**
 * Works with Options Module
 *
 * @author   Anton Shevchuk
 * @created  04.11.13 13:30
 */
namespace Application;

use Application\Options;
use Bluz\Controller\Data;
use Bluz\Proxy\Layout;

return
/**
 * @return \closure
 */
function () use ($data) {
    /**
     * @var Bootstrap $this
     * @var Data $view
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Options',
        ]
    );

    if ($example = Options\Table::get('example')) {
        $data->message = sprintf('Option `example` was found, it is `%s`', (string) $example);
        Options\Table::remove('example');
    } else {
        $data->message = 'Option `example` not found, try again later';
        Options\Table::set('example', uniqid('example_'));
    }
};
