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
use Bluz\Controller\Mapper\Crud;
use Bluz\Proxy\Config;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Session;

/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 *
 * @return array
 * @throws Exception
 * @throws \Bluz\Application\Exception\ForbiddenException
 * @throws \Bluz\Application\Exception\NotImplementedException
 */
return function () {
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

    $crudController = new Crud();
    
    $crudController->setCrud($crud);

    $crudController->get('system', 'crud/get');
    $crudController->post('system', 'crud/post');
    $crudController->put('system', 'crud/put');
    $crudController->delete('system', 'crud/delete');

    $result = $crudController->run();

    // FIXME: workaround
    // check result for instance of Media\Row
    if ((Request::isPost() || Request::isPut()) && !$result) {
        // all ok, go to grid
        Response::redirectTo('media', 'grid');
    }

    return $result;
};
