<?php
/**
 * CRUD for media
 */
namespace Application;

use Application\Media;
use Bluz\Controller;
use Bluz\Request\AbstractRequest;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
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
    $userId = $this->getAuth()->getIdentity()->id;

    $crud = Media\Crud::getInstance();
    $crud->setUploadDir('uploads/'.$userId.'/media');


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
