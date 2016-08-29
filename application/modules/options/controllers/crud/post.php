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

use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method POST
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed $primary
 * @param  array $data
 * @return array
 */
return function ($crud, $primary, $data) {
    try {
        $crud->createOne($data);

        Messages::addSuccess("Record was created");
        
        return [
            'row'    => $data,
            'method' => Request::METHOD_PUT
        ];
    } catch (ValidatorException $e) {
        $row = $crud->readOne(null);
        $row->setFromArray($data);
        return [
            'row'    => $row,
            'errors' => $e->getErrors(),
            'method' => Request::getMethod()
        ];
    }
};
