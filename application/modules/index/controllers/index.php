<?php
/**
 * Default module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return closure
 */
namespace Bluz;

return
/**
 *
 * @return closure
 */
function() use ($request, $view) {
    /**
     * @var closure $bootstrap
     * @var Application $this
     * @var Request\HttpRequest $request
     * @var View\View $view
     */
    $this->getLayout()->title = "Index/Index";

    $this->getSession()->test = 'Test: '.date("H:i:s");

    $view->session = $this->getSession()->test;

    //throw new Exception('Hi!', 404);

//    if ($identity = $this->getAuth()->getIdentity()) {
//        var_dump($acl->isAllowed('index/index', $identity['sid']));
//        var_dump($acl->isAllowed('index/test', $identity['sid']));
//        var_dump($acl->isAllowed('index/error', $identity['sid']));
        //Bluz\Debug::dump($identity);
//    } else {
//        $this->getAuth()->authenticate('admin', '123456');
//    }
//    $this->getMessages()->addError('Error Text');
//    $this->getMessages()->addNotice('Warning Text');
//    $this->getMessages()->addSuccess('Notice Text');
};