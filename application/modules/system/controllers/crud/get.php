<?php
/**
 * CRUD controller for GET method
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

/**
 * @accept HTML
 * @accept JSON
 * @method GET
 *
 * @param Table $crud
 * @param mixed $primary
 *
 * @return array
 * @throws TableNotFoundException
 * @throws NotFoundException
 */
return function (Table $crud, $primary) {
    $primary = array_filter($primary);
    return [
        'row' => $crud->readOne($primary),
        'method' => empty($primary) ? RequestMethod::POST : RequestMethod::PUT
    ];
};
