<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
 */
namespace Bluz;
return
/**
 * @return closure
 */
function() use ($app, $bootstrap, $request, $view) {
    /**
     * @var closure $bootstrap
     * @var Application $app
     * @var Request $request
     * @var View $view
     */
    $app->useJson(true);
    $app->addNotice('Notice Text');
    $app->addSuccess('Success Text');
    $app->addError('Error Text');

    $view->test = 12312414;

    sleep(2);
//    $view->reload = true;
//    $view->callback = 'callback name';
};