<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
 */
namespace Application;
use Bluz;
return
/**
 * @return \closure
 */
function() use ($view) {
    /**
     * @var Bluz\Application $this
     * @var Bluz\View\View $view
     */
    $this->useJson(true);
    $this->getMessages()->addNotice('Notice Text');
    $this->getMessages()->addSuccess('Success Text');
    $this->getMessages()->addError('Error Text');


    sleep(2);

//    $view->test = 12312414;
//    $view->reload = true;
//    $view->callback = 'callback name';
};