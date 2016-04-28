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
use Bluz\Controller;
use Bluz\Proxy\Response;

/**
 * @param array $primary
 * @param array $data
 * @param Test\Crud $crud
 * @return array
 */
return
/**
 * @accept HTML
 * @accept JSON
 * @method OPTIONS
 */
function ($primary, $data, $crud) {
    Response::setHeader('Allow', 'GET,HEAD,POST,PUT,DELETE,OPTIONS');
};
