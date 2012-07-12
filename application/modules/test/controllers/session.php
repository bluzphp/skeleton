<?php
/**
 * Default module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return closure
 */
namespace Application;
use Bluz;

return
/**
 *
 * @return \closure
 */
function() use ($request, $view) {
    /**
     * @var Bluz\Request\HttpRequest $request
     * @var Bluz\View\View $view
     */
    $this->getLayout()->title = "Index/Index";

    $this->getSession()->test = 'Session time: '.date("H:i:s");

    $view->session = $this->getSession()->test;

    //var_dump(\Staffload\Client::getInstance()->get('projects/search'));

    //throw new Exception('Hi!', 404);

//    if ($identity = $app->getAuth()->getIdentity()) {
//        var_dump($acl->isAllowed('index/index', $identity['sid']));
//        var_dump($acl->isAllowed('index/test', $identity['sid']));
//        var_dump($acl->isAllowed('index/error', $identity['sid']));
        //Bluz\Debug::dump($identity);
//    } else {
//        $app->getAuth()->authenticate('admin', '123456');
//    }
//    $app->getMessages()->addError('Error Text');
//    $app->getMessages()->addNotice('Warning Text');
//    $app->getMessages()->addSuccess('Notice Text');
};