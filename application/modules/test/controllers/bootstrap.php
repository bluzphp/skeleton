<?php
/**
 * Bootstrap example
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
 */
namespace Application;

use Bluz;

return
/**
 * @var \closure $boostrap
 * @return \closure
 */
function () use ($bootstrap, $view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $this->getLayout()->ahref('Test', ['test', 'index']),
            'Bootstrap',
        ]
    );
    $view->result = $bootstrap(2);
};
