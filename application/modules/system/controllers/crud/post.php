<?php

/**
 * CRUD controller for POST method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */

namespace Application;

use Bluz\Crud\Table;
use Bluz\Db\Exception\TableNotFoundException;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Http\RequestMethod;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method POST
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
        // Unset empty primary key(s)
        foreach ($primary as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }

        // Result is Primary Key(s)
        $result = $crud->createOne($data);

        Messages::addSuccess('The record was successfully created');

        return [
            'row' => $crud->readOne($result),
            'method' => RequestMethod::PUT
        ];
    } catch (ValidatorException $e) {
        $row = $crud->readOne(null);
        $row->setFromArray($data);

        return [
            'row' => $row,
            'errors' => $e->getErrors(),
            'method' => Request::getMethod()
        ];
    }
};
