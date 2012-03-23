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
function() use ($app, $bootstrap, $request, $view) {
    /**
     * @var closure $bootstrap
     * @var Application $app
     * @var Request $request
     * @var View\View $view
     */
    $app->getLayout()->title = "Index/Index";

    $app->getSession()->test = 'Test: '.date("H:i:s");

    $view->session = $app->getSession()->test;

    //throw new Exception('Hi!', 404);

//    if ($identity = $app->getAuth()->getIdentity()) {
//        var_dump($acl->isAllowed('index/index', $identity['sid']));
//        var_dump($acl->isAllowed('index/test', $identity['sid']));
//        var_dump($acl->isAllowed('index/error', $identity['sid']));
        //Bluz\Debug::dump($identity);
//    } else {
//        $app->getAuth()->authenticate('admin', '123456');
//    }
//    $app->addError('Error Text');
//    $app->addNotice('Warning Text');
//    $app->addSuccess('Notice Text');
};