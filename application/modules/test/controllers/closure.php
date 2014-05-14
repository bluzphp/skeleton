<?php
/**
 * Disable view, like for backbone.js
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz;

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
            'Closure',
        ]
    );
    return function () {
        echo "<div class='jumbotron'><div class='container'>";
        echo "<h3>Closure is back</h3>";
        echo "<p class='text-warning text-primary'>Executed before render layout</p>";
        echo "</div></div>";
    };
};
