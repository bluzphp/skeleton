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
use Bluz\Application\Exception\ForbiddenException;

use Bluz\Controller\Controller;
use Bluz\Rest\Rest;

/**
 * @return Controller
 * @throws ForbiddenException
 * @internal param Controller $this
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

        $rest = new Rest();

        $rest->setCrud(Test\Crud::getInstance());

        $rest->addMap('HEAD', 'system', 'rest/head', 'Read');
        $rest->addMap('GET', 'system', 'rest/get', 'Read');
        $rest->addMap('POST', 'system', 'rest/post', 'Create');
        $rest->addMap('PUT', 'system', 'rest/put', 'Update');
        $rest->addMap('DELETE', 'system', 'rest/delete', 'Delete');

        $rest->process();

        return $rest->run();
    };
