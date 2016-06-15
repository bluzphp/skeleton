<?php
/**
 * REST controller for PUT method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Controller;
use Bluz\Proxy\Response;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method PUT
 * @method PATCH
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed $primary
 * @param  array $data
 * @return void|array
 * @throws BadRequestException
 * @throws NotImplementedException
 */
return function ($crud, $primary, $data) {
    if (!sizeof($data)) {
        // data not found
        throw new BadRequestException();
    }

    try {
        if (!empty($primary)) {
            // update one item
            $result = $crud->updateOne($primary, $data);
        } else {
            // update collection
            $result = $crud->updateSet($data);
        }
        // if $result === 0 it's means a update is not apply
        // or records not found
        if (0 === $result) {
            Response::setStatusCode(304);
        }
    } catch (ValidatorException $e) {
        Response::setStatusCode(400);
        return ['errors' => $e->getErrors()];
    }
};
