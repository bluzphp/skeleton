<?php
/**
 * Dispatch other controllers
 *
 * @author   Anton Shevchuk
 * @created  23.08.12 13:14
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $this->getLayout()->ahref('Test', ['test', 'index']),
            'Dispatch',
        ]
    );
};
