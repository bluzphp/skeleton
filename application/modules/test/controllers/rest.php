<?php
/**
 * Example of REST controller for GET method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Application\Test;
use Bluz\Application\Application;
use Bluz\Application\Exception\ForbiddenException;

use Bluz\Controller\Controller;
use Bluz\Proxy\Acl;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;

/**
 * @var Controller $this
 */
return
    /**
     * @accept HTML
     * @accept JSON
     * @acl Read
     * @acl Create
     * @acl Update
     * @acl Delete
     * @return mixed
     */
    function () {
        $this->useJson();

        // rewrite REST with "_method" param

        // get path
        // %module% / %controller% / %id% / %relation% / %id%
        $path = Router::getCleanUri();

        $params = explode('/', rtrim($path, '/'));

        // module
        array_shift($params);

        // controller
        array_shift($params);

        if (sizeof($params)) {
            $primary = explode('-', array_shift($params));
        } else {
            $primary = null;
        }

        if (sizeof($params)) {
            $relation = array_shift($params);
        }
        if (sizeof($params)) {
            $relationId = array_shift($params);
        }

        $data = Request::getParams();

        unset($data['_method'], $data['_module'], $data['_controller']);

        $crud = new Test\Crud();

        switch (Request::getMethod()) {
            case 'OPTIONS':
                if (!Acl::isAllowed($this->module, 'Read')) {
                    throw new ForbiddenException;
                }
                return Application::getInstance()->dispatch(
                    'system',
                    'rest/options',
                    ['primary' => $primary, 'data' => $data, 'crud' => $crud]
                );
            case 'HEAD':
                if (!Acl::isAllowed($this->module, 'Read')) {
                    throw new ForbiddenException;
                }
                $result = Application::getInstance()->dispatch(
                    'system',
                    'rest/get',
                    ['primary' => $primary, 'data' => $data, 'crud' => $crud]
                );
                $size = strlen(json_encode($result));
                Response::addHeader('Content-Length', $size);
                return null;
            case 'GET':
                if (!Acl::isAllowed($this->module, 'Read')) {
                    throw new ForbiddenException;
                }
                return Application::getInstance()->dispatch(
                    'system',
                    'rest/get',
                    ['primary' => $primary, 'data' => $data, 'crud' => $crud]
                );
            case 'POST':
                if (!Acl::isAllowed($this->module, 'Create')) {
                    throw new ForbiddenException;
                }
                return Application::getInstance()->dispatch(
                    'system',
                    'rest/post',
                    ['primary' => $primary, 'data' => $data, 'crud' => $crud]
                );
            case 'PUT':
                if (!Acl::isAllowed($this->module, 'Update')) {
                    throw new ForbiddenException;
                }
                return Application::getInstance()->dispatch(
                    'system',
                    'rest/put',
                    ['primary' => $primary, 'data' => $data, 'crud' => $crud]
                );
            case 'DELETE':
                if (!Acl::isAllowed($this->module, 'Delete')) {
                    throw new ForbiddenException;
                }
                return Application::getInstance()->dispatch(
                    'system',
                    'rest/delete',
                    ['primary' => $primary, 'data' => $data, 'crud' => $crud]
                );
        }
    };
