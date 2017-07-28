<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Api;

use Application\Tests\ControllerTestCase;
use Bluz\Http\StatusCode;
use Bluz\Proxy\Response;

/**
 * @group    api
 *
 * @author   Dmitriy Rassadkin
 * @created  29.09.14 10:37
 */
class ApiTest extends ControllerTestCase
{
    public function testMethodNotFound()
    {
        $this->dispatch('api/not-exists');
        // resource not-exists not exists => not found
        self::assertResponseCode(StatusCode::NOT_FOUND);
    }

    public function testWrongMethod()
    {
        $this->dispatch('api/login', [], 'GET');
        // get is not allowed => not implemented
        self::assertResponseCode(StatusCode::METHOD_NOT_ALLOWED);
    }

    public function testMissingParam()
    {
        $this->dispatch('api/login', ['login' => 'admin'], 'POST');
        // missed password => bad request
        self::assertResponseCode(StatusCode::BAD_REQUEST);
    }

    public function testLoginWrongPassword()
    {
        $this->dispatch('api/login', ['login' => 'admin', 'password' => 'password'], 'POST');
        // wrong password => authorization failed
        self::assertResponseCode(StatusCode::UNAUTHORIZED);
    }

    public function testLoginSuccess()
    {
        $this->dispatch('api/login', ['login' => 'admin', 'password' => 'admin'], 'POST');

        self::assertOk();
        self::assertArrayHasKey('token', Response::getBody()->getData()->toArray());
    }
}
