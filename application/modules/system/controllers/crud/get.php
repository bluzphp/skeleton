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

use Bluz\Proxy\Request;

/**
 * @accept HTML
 * @accept JSON
 * @method GET
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed $primary
 * @return array
 */
return function ($crud, $primary) {
    return [
        'row' => $crud->readOne($primary),
        'method' => empty($primary)?Request::METHOD_POST:Request::METHOD_PUT
    ];
};
