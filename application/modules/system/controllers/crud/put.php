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

use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method PUT
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed $primary
 * @param  array $data
 * @return void|array
 * @throws BadRequestException
 * @throws NotImplementedException
 */
return function ($crud, $primary, $data) {

    try {
        // Result is numbers of affected rows
        $crud->updateOne($primary, $data);

        Messages::addSuccess("Record was updated");

        return [
            'row'    => $crud->readOne($primary),
            'method' => Request::getMethod()
        ];
    } catch (ValidatorException $e) {
        $row = $crud->readOne($primary);
        $row->setFromArray($data);

        return [
            'row'    => $row,
            'errors' => $e->getErrors(),
            'method' => Request::getMethod()
        ];
    }
};
