<?php
/**
 * Example of REST controller for GET method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Application\Test;
use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Controller;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Bluz\Validator\Exception\ValidatorException;

/**
 * @param $primary
 * @param $data
 * @param Test\Crud $crud
 * @return array
 * @throws BadRequestException
 * @throws NotImplementedException
 */
return
/**
 * @accept HTML
 * @accept JSON
 * @method POST
 */
function ($primary, $data, $crud) {
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
