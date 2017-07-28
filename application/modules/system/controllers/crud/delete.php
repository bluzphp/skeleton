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

use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Proxy\Messages;

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
    // @throws NotFoundException
    $crud->deleteOne($primary);

    Messages::addSuccess('Record was deleted');
};
