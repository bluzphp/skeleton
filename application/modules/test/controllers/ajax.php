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
     * @var Request\HttpRequest $request
     * @var View\View $view
     */
    $app->useJson(true);
    $app->getMessages()->addNotice('Notice Text');
    $app->getMessages()->addSuccess('Success Text');
    $app->getMessages()->addError('Error Text');

    $view->test = 12312414;

    sleep(1);
//    $view->reload = true;
//    $view->callback = 'callback name';
};