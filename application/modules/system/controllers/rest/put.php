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

use Bluz\Controller;
use Bluz\Crud\Table;
use Bluz\Db\Exception\TableNotFoundException;
use Bluz\Http\Exception\BadRequestException;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Http\Exception\NotImplementedException;
use Bluz\Http\StatusCode;
use Bluz\Proxy\Response;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method PUT
 * @method PATCH
 *
 * @param Table $crud
 * @param mixed $primary
 * @param array $data
 *
 * @return array
 * @throws BadRequestException
 * @throws NotImplementedException
 * @throws TableNotFoundException
 * @throws NotFoundException
 */
return function (Table $crud, $primary, $data) {
    if (!count($data)) {
        // data not found
        throw new BadRequestException;
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
            Response::setStatusCode(StatusCode::NOT_MODIFIED);
        }
        return [];
    } catch (ValidatorException $e) {
        Response::setStatusCode($e->getCode());
        return [
            'code' => $e->getCode(),
            'error' => $e->getMessage(),
            'errors' => $e->getErrors()
        ];
    }
};
