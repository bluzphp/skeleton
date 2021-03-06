<?php
/**
 * REST controller for OPTIONS method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */

namespace Application;

use Bluz\Controller;
use Bluz\Http\StatusCode;
use Bluz\Proxy\Response;

/**
 * @accept HTML
 * @accept JSON
 * @method OPTIONS
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed            $primary
 * @param  array            $data
 *
 * @return void
 */
return function ($crud, $primary, $data) {
    Response::setStatusCode(StatusCode::NO_CONTENT);
    Response::setHeader('Allow', implode(',', $data));
};
