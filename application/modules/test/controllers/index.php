<?php
/**
 * Test of test of test
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 * @return closure
 */
namespace Application;
use Bluz;
return
/**
 * @return \closure
 */
function() use ($bootstrap, $request, $view) {
    /**
     * @var \closure $bootstrap
     * @var Bluz\Application $this
     * @var Bluz\Request\AbstractRequest $request
     * @var Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs([
        'Test',
    ]);
};