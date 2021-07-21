<?php

/**
 * CRUD controller for DELETE method
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

/**
 * @accept HTML
 * @accept JSON
 * @method DELETE
 *
 * @param Table $crud
 * @param mixed $primary
 * @param array $data
 *
 * @return void
 * @throws NotFoundException
 * @throws TableNotFoundException
 */
return function (Table $crud, $primary, $data) {
    // @throws NotFoundException
    $crud->deleteOne($primary);

    Messages::addSuccess('The record was successfully destroyed');
};
