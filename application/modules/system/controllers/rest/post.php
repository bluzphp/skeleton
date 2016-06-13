<?php
/**
 * REST controller for POST method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Controller;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @accept HTML
 * @accept JSON
 * @method POST
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed $primary
 * @param  array $data
 * @return void|array
 * @throws BadRequestException
 * @throws NotImplementedException
 */
return function ($crud, $primary, $data) {
    if (!empty($primary)) {
        // POST + ID is incorrect behaviour
        throw new NotImplementedException();
    }

    try {
        $result = $crud->createOne($data);
        if (!$result) {
            // system can't create record with this data
            throw new BadRequestException();
        }

        if (is_array($result)) {
            $result = join('-', array_values($result));
        }
    } catch (ValidatorException $e) {
        Response::setStatusCode(400);
        return ['errors' => $e->getErrors()];
    }

    Response::setStatusCode(201);
    Response::setHeader(
        'Location',
        Router::getUrl(Request::getModule(), Request::getController()).'/'.$result
    );
};
