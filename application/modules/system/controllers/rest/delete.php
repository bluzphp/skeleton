<?php
/**
 * REST controller for DELETE method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */

namespace Application;

use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Http\StatusCode;
use Bluz\Proxy\Response;

/**
 * @accept HTML
 * @accept JSON
 * @method DELETE
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed            $primary
 * @param  array            $data
 *
 * @return void
 * @throws BadRequestException
 * @throws NotFoundException
 * @throws NotImplementedException
 */
return function ($crud, $primary, $data) {
    if (!empty($primary)) {
        // delete one
        // @throws NotFoundException
        $crud->deleteOne($primary);
    } else {
        // delete collection
        // @throws NotFoundException
        if (!count($data)) {
            // data not exist
            throw new BadRequestException();
        }
        $crud->deleteSet($data);
    }
    Response::setStatusCode(StatusCode::NO_CONTENT);
};
