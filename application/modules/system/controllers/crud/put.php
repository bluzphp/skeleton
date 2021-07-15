<?php
/**
 * CRUD controller for PUT method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */

namespace Application;

use Bluz\Crud\Table;
use Bluz\Db\Exception\TableNotFoundException;
use Bluz\Http\Exception\BadRequestException;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Http\Exception\NotImplementedException;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method PUT
 *
 * @param Table $crud
 * @param mixed $primary
 * @param array $data
 *
 * @return array
 * @throws TableNotFoundException
 * @throws NotFoundException
 */
return function (Table $crud, $primary, $data) {
    try {
        // Result is numbers of affected rows
        $crud->updateOne($primary, $data);

        Messages::addSuccess('The record was successfully updated');

        return [
            'row' => $crud->readOne($primary),
            'method' => Request::getMethod()
        ];
    } catch (ValidatorException $e) {
        $row = $crud->readOne($primary);
        $row->setFromArray($data);

        return [
            'row' => $row,
            'errors' => $e->getErrors(),
            'method' => Request::getMethod()
        ];
    }
};
