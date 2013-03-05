<?php
/**
 * CRUD for media
 */
namespace Application;

use Bluz\Request\AbstractRequest;

return
/**
 * @privilege Management
 * @return \closure
 */
function() use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->useLayout('dashboard.phtml');
    $this->getLayout()->breadCrumbs([
        $view->ahref('Dashboard', ['dashboard', 'index']),
        $view->ahref('Media', ['media', 'grid']),
        'Upload'
    ]);

    $userId = $this->getAuth()->getIdentity()->id;

    $crud = new Media\Crud();
    $crud->setUploadDir('uploads/'.$userId.'/media');
    $result = $crud->processController();

    // FIXME: workaround
    if (($crud->getMethod() == AbstractRequest::METHOD_POST
        or $crud->getMethod() == AbstractRequest::METHOD_PUT )
        && !$result) {
        // all ok, go to grid
        $this->redirectTo('media', 'grid');
    }

    return $result;
};
