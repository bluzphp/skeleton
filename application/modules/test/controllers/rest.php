<?php
/**
 * Complex example of REST controller
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Application\Test;
use Bluz\Application\Exception\ForbiddenException;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Rest;

/**
 * @accept HTML
 * @accept JSON
 * @acl Read
 * @acl Create
 * @acl Update
 * @acl Delete
 *
 * @return mixed
 * @throws ForbiddenException
 */
return function () {
    /**
     * @var Controller $this
     */
    $rest = new Rest();
        
    $rest->setCrud(Test\Crud::getInstance());
        
    $rest->head('system', 'rest/head', 'Read');
    $rest->get('system', 'rest/get', 'Read');
    $rest->options('system', 'rest/options', 'Read');
    $rest->post('system', 'rest/post', 'Create');
    $rest->put('system', 'rest/put', 'Update');
    $rest->patch('system', 'rest/put', 'Update');
    $rest->delete('system', 'rest/delete', 'Delete');

    return $rest->run();
};
