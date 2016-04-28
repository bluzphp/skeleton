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
use Bluz\Application\Exception\BadRequestException;
use Bluz\Controller;
use Bluz\Proxy\Response;

/**
 * @param $primary
 * @param $data
 * @param Test\Crud $crud
 * @return array
 * @throws BadRequestException
 * @throws \Bluz\Application\Exception\NotFoundException
 * @throws \Bluz\Application\Exception\NotImplementedException
 */
return
/**
 * @accept HTML
 * @accept JSON
 * @method DELETE
 */
function ($primary, $data, $crud) {
    if (!empty($primary)) {
        // delete one
        // @throws NotFoundException
        $crud->deleteOne($primary);
    } else {
        // delete collection
        // @throws NotFoundException
        if (!sizeof($data)) {
            // data not exist
            throw new BadRequestException();
        }
        $crud->deleteSet($data);
    }
    Response::setStatusCode(204);
};
