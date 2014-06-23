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
    $this->getSession()->getStore()->start();

    $this->useLayout('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
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
    $path = $this->getConfigData('upload_dir', 'path');
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
