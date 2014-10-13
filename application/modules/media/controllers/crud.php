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
use Bluz\Controller;
use Bluz\Proxy\Config;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Session;
use Bluz\Request\AbstractRequest;

return
/**
 * @privilege Management
 * @return mixed
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Session::start();

    $this->useLayout('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            $view->ahref('Media', ['media', 'grid']),
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


    $crudController = new Controller\Crud();
    $crudController->setCrud($crud);
    $result = $crudController();

    // FIXME: workaround
    if (($crudController->getMethod() == AbstractRequest::METHOD_POST
            or $crudController->getMethod() == AbstractRequest::METHOD_PUT )
        && !$result /*($result instanceof Media\Row)*/) {
        // all ok, go to grid
        $this->redirectTo('media', 'grid');
    }

    return $result;
};
