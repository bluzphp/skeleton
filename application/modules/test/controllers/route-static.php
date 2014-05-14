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
 * @route /static-route/
 * @route /another-route.html
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $this->getLayout()->ahref('Test', ['test', 'index']),
            'Routers Examples',
        ]
    );

    $uri = $this->getRequest()->getRequestUri();
    echo <<<CODE
<h4>URL: $uri</h4>
<h4>Route: {$this->getRequest()->getModule()}/{$this->getRequest()->getController()}</h4>
<pre>
/**
 * @route /static-route/
 * @route /another-route.html
 * @return \closure
 */
</pre>
CODE;
    return false;
};
