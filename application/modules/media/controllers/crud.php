<?php
/**
 * CRUD for media
 *
 * @author   Anton Shevchuk
 */

/**
 * @namespace
 */
namespace Application;

use Application\Media;
use Bluz\Controller\Controller;
use Bluz\Proxy\Config;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Session;

return
/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 * @return mixed
 */
function () {
    /**
     * @var Controller $this
     */
    Session::start();

    $this->useLayout('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('Media', ['media', 'grid']),
            __('Upload')
        ]
    );
    if (!$this->user()) {
        throw new Exception('User not found');
    }

    $userId = $this->user()->id;

    $crud = Media\Crud::getInstance();
    // get path from config
    $path = Config::getModuleData('media', 'upload_path');
    if (empty($path)) {
        throw new Exception('Upload path is not configured');
    }
    $crud->setUploadDir($path.'/'.$userId.'/media');

//    $crudController = new Controller\Crud();
//    $crudController->setCrud($crud);
//    $result = $crudController();
//
//    // FIXME: workaround
//    if (($crudController->getMethod() == Request::METHOD_POST
//            or $crudController->getMethod() == Request::METHOD_PUT )
//        && !$result /*($result instanceof Media\Row)*/) {
//        // all ok, go to grid
//        Response::redirectTo('media', 'grid');
//    }
//
//    return $result;
};
