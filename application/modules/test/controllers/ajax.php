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
function() use ($view) {
    /**
     * @var closure $bootstrap
     * @var Application $this
     * @var View\View $view
     */
    $this->useJson(true);
    $this->getMessages()->addNotice('Notice Text');
    $this->getMessages()->addSuccess('Success Text');
    $this->getMessages()->addError('Error Text');

    $view->test = 12312414;

    sleep(2);
//    $view->reload = true;
//    $view->callback = 'callback name';
};