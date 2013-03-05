<?php
/**
 * Example of static route
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
 */
namespace Application;
use Bluz;
return
/**
 * @route /static-route.html
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs([
        $this->getLayout()->ahref('Test', ['test', 'index']),
        'Routers Examples',
    ]);
    var_dump("OK");
    return 'route.phtml';
};
